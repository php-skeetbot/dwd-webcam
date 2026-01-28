<?php
/**
 * Class DWDWebcam
 *
 * @created      28.10.2024
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2024 smiley
 * @license      MIT
 */
declare(strict_types=1);

namespace PHPSkeetBot\DWDWebcamBot;

use chillerlan\HTTP\Utils\MessageUtil;
use chillerlan\Settings\SettingsContainerInterface;
use chillerlan\Utilities\Arr;
use chillerlan\Utilities\File;
use DateTimeImmutable;
use DateTimeZone;
use PHPSkeetBot\PHPSkeetBot\SkeetBot;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Random\Randomizer;
use RuntimeException;
use function array_diff;
use function array_keys;
use function array_values;
use function count;
use function date;
use function explode;
use function intval;
use function preg_match;
use function sleep;
use function sprintf;

/**
 * @see https://opendata.dwd.de
 * @see https://twitter.com/DWD_presse/status/1680946279512252417
 */
class DWDWebcam extends SkeetBot{

	protected const WEBCAMS = [
		'Hamburg-SO'          => 'DWD Hamburg - Blick nach Südost, elbaufwärts (Elbphilharmonie)',
		'Hamburg-SW'          => 'DWD Hamburg - Blick nach Südwest, elbabwärts (Containerterminal)',
		'Hohenpeissenberg-S'  => 'Meteorologisches Observatorium Hohenpreißenberg - Blick nach Süden (Zugspitze)',
		'Hohenpeissenberg-SW' => 'Meteorologisches Observatorium Hohenpreißenberg - Blick nach Südwesten', // offline
		'Lindenberg-NNE'      => 'Meteorologisches Observatorium Lindenberg - Blick nach Nord-Nordosten',
		'Offenbach-O'         => 'DWD Offenbach - Ost, Blick nach Offenbach',
		'Offenbach-W'         => 'DWD Offenbach - West, Blick nach Frankfurt',
		'Schmuecke-SW'        => 'Wetterstation Schmücke - Blick nach Südwest (Suhl)',
		'Warnemuende-NW'      => 'Rostock-Warnemünde - Blick nach Nordwest',
		'Wasserkuppe-SW'      => 'Wasserkuppe, Rhön - Blick nach Südwest',
	];

	protected const WEBCAM_URL = 'https://opendata.dwd.de/weather/webcam/';

	/** @var array<string, int> */
	protected array $lastUpdated;

	/** @var array<int, string> */
	protected array $tried = [];

	/**
	 * DWDWebcam constructor
	 */
	public function __construct(SettingsContainerInterface|DWDWebcamOptions $options){
		parent::__construct($options);

		$this->lastUpdated = File::loadJSON($this->options->dataDir.'/last_updated.json', true);
	}

	/**
	 * save the posted list on exit
	 */
	public function __destruct(){
		File::saveJSON($this->options->dataDir.'/last_updated.json', $this->lastUpdated);
	}

	/**
	 * Posts the skeet
	 */
	public function post():static{

		$body = [
			'collection' => 'app.bsky.feed.post',
			'repo'       => $this->bluesky->getAccountDID(),
			'record'     => [
				'text'      => '',
				'langs'     => [],
				'createdAt' => date('c'),
				'$type'     => 'app.bsky.feed.post',
				'embed'     => [
					'$type'  => 'app.bsky.embed.images',
					'images' => $this->getLatestImages(),
				],
			],
		];

		$this->submitSkeet($body);

		return $this;
	}

	/**
	 * Attempts to fetch one or more image(s) from a random webcam, skipping the ones that aren't updated or errored,
	 * returns an array with the bluesky media IDs on success, throws otherwise.
	 *
	 * @return array<int, array<string, string>>
	 */
	protected function getLatestImages():array{
		$images = [];

		while(true){
			$webcam = $this->getPoolItem();

			// exit early when there's nothing left in the pool
			if($webcam === null){

				// return what we have so far
				if(!empty($images)){
					return $images;
				}

				// throw and exit
				throw new RuntimeException('could not fetch a valid image');
			}

			$timestamp = $this->fetchExifTime($webcam);

			// exif request error or timestamp is same/older as the last updated one
			if($timestamp === null || $timestamp <= $this->lastUpdated[$webcam]){
				continue;
			}

			// yay, we have a possible image candidate
			$image = $this->fetchImage($webcam);

			if($image instanceof StreamInterface){
				$dt  = (new DateTimeImmutable)->setTimestamp($timestamp);
				$utc = $dt->setTimezone(new DateTimeZone('UTC'))->format('d.m.Y, H:i');
				$loc = $dt->setTimezone(new DateTimeZone('Europe/Berlin'))->format('d.m.Y, H:i');

				$description = sprintf('%s (%s UTC/%s local)', $this::WEBCAMS[$webcam], $utc, $loc);
				$blob        = $this->uploadMedia($image);

				// wowee we did it!
				if($blob !== null){
					$this->lastUpdated[$webcam] = $timestamp;
					$images[]                   = ['alt' => $description, 'image' => $blob];

					$this->logger->info(
						sprintf('uploaded latest image for webcam "%s", media id: "%s"', $webcam, $blob['ref']['$link']),
					);
				}

				// try not to hammer
				sleep(1);
			}

			// kthxbye!
			if(count($images) === $this->options->imageCount){
				return $images;
			}
		}

	}

	/**
	 * Fetches a random webcam from the pool while excluding the ones that don't satisfy a request (retry on error/no update)
	 */
	protected function getPoolItem():string|null{
		// diff the webcam names against the array with the names of the ones we already tried
		$diff = array_values(array_diff(array_keys($this::WEBCAMS), $this->tried));

		if(empty($diff)){
			return null;
		}

		$item          = (new Randomizer)->shuffleArray($diff)[0];
		$this->tried[] = $item;

		return $item;
	}

	/**
	 * Fetches the exif file for the "latest" image of the given webcam, returns the timestamp on success, otherwise null
	 *
	 * (hey DWD could you maybe add the timestamp to the *.txt files? that would save ~50kb download!)
	 */
	protected function fetchExifTime(string $webcam):int|null{

		$exifRequest  = $this->requestFactory
			->createRequest('GET', sprintf('%1$s/%2$s/%2$s_latest.exif', $this::WEBCAM_URL, $webcam))
			->withHeader('User-Agent', $this->options->user_agent)
		;

		$exifResponse = $this->http->sendRequest($exifRequest);

		if($exifResponse->getStatusCode() !== 200){
			$this->logger->warning(sprintf('could not fetch exif for webcam "%s"', $webcam));

			return null;
		}

		$content = $exifResponse->getBody()->getContents();

		if(!preg_match('/FileDateTime: (\d+)/', $content, $match)){
			$this->logger->warning('could not match timestamp from exif');

			return null;
		}

		return intval($match[1]);
	}

	/**
	 * Fetches the "latest" image for the given webcam
	 */
	protected function fetchImage(string $webcam):StreamInterface|null{
		$imageURL = sprintf('%1$s/%2$s/%2$s_latest_%3$s.jpg', $this::WEBCAM_URL, $webcam, $this->options->imageSize);

		$imageRequest = $this->requestFactory
			->createRequest('GET', $imageURL)
			->withHeader('User-Agent', $this->options->user_agent)
		;

		$imageResponse = $this->http->sendRequest($imageRequest);

		if($imageResponse->getStatusCode() !== 200){
			$this->logger->warning(sprintf('could not fetch image for webcam "%s"', $webcam));

			return null;
		}

		$contentType = $imageResponse->getHeaderLine('content-type');

		if($contentType !== 'image/jpeg'){
			$this->logger->warning(sprintf('invalid image content type "%s" for webcam "%s"', $contentType, $webcam));

			return null;
		}

		return $imageResponse->getBody();
	}

	protected function submitSkeetSuccess(ResponseInterface $response):never{
		$json = MessageUtil::decodeJSON($response);

		if(!isset($json->validationStatus) || $json->validationStatus !== 'valid'){
			$this->logger->error('invalid status');

			exit(255);
		}

		// my god bsky just give me the full url to the post it's not hard
		$url = sprintf('https://bsky.app/profile/%s/post/%s', $this->bluesky->getHandle(), Arr::last(explode('/', $json->uri)));

		$this->logger->info(sprintf('posted: %s', $url));

		exit(0);
	}

	protected function submitSkeetFailure(ResponseInterface|null $response):never{

		if($response instanceof ResponseInterface){
			$json = MessageUtil::decodeJSON($response);

			if(isset($json->message)){
				$this->logger->error($json->message);
			}
		}

		exit(255);
	}

}
