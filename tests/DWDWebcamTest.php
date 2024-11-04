<?php
/**
 * Class DWDWebcamTest
 *
 * @created      28.10.2024
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2024 smiley
 * @license      MIT
 */
declare(strict_types=1);

namespace PHPSkeetBot\DWDWebcamBotTest;

use PHPSkeetBot\DWDWebcamBot\DWDWebcam;
use PHPSkeetBot\DWDWebcamBot\DWDWebcamOptions;
use PHPSkeetBot\PHPSkeetBot\SkeetBotInterface;
use PHPUnit\Framework\TestCase;

class DWDWebcamTest extends TestCase{

	public function testInstance():void{
		$options = new DWDWebcamOptions;
		$options->dataDir = __DIR__.'/../data';

		$this::assertInstanceOf(SkeetBotInterface::class, new DWDWebcam($options));
	}

}
