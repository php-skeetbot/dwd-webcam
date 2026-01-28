# php-skeetbot/dwd-webcam

# dwd-webcam

A [Bluesky bot][bluesky-url] that posts pictures from the [German Weather service (Deutscher Wetterdienst DWD)](https://www.dwd.de/EN/Home/home_node.html) [webcams](https://opendata.dwd.de/weather/webcam/).

(This project is not affiliated with DWD)

[![Run][gh-action-badge]][gh-action]
[![License][license-badge]][license]
[![Bluesky][bluesky-badge]][bluesky-url]

[gh-action-badge]: https://img.shields.io/github/actions/workflow/status/php-skeetbot/dwd-webcam/run.yml?branch=main&logo=github
[gh-action]: https://github.com/php-skeetbot/dwd-webcam/actions/workflows/run.yml?query=branch%3Amain
[license-badge]: https://img.shields.io/badge/license-MIT-green.svg
[license]: https://github.com/php-skeetbot/dwil/blob/main/LICENSE-MIT
[bluesky-badge]: https://img.shields.io/badge/bluesky-%40dwd--webcam.bsky.social-%230085FF?logo=bluesky
[bluesky-url]: https://bsky.app/profile/dwd-webcam.bsky.social

## Currently active cameras

- [DWD Hamburg](https://www.google.com/maps/place/DWD+-+Niederlassung+Hamburg/@53.5465929,9.9659058,591m/data=!3m1!1e3!4m6!3m5!1s0x47b18f7321a8fc45:0x3d232af4986f5c89!8m2!3d53.5466613!4d9.9668693!16s%2Fg%2F11b6dpjzvr!5m1!1e4?entry=ttu&g_ep=EgoyMDI0MTAyOS4wIKXMDSoASAFQAw%3D%3D) - Blick nach Südost, elbaufwärts (Elbphilharmonie)
- DWD Hamburg - Blick nach Südwest, elbabwärts (Containerterminal)
- [Meteorologisches Observatorium Hohenpreißenberg](https://www.google.com/maps/place/Deutscher+Wetterdienst+Meteorologisches+Observatorium/@47.800421,11.0083469,1399m/data=!3m1!1e3!4m10!1m2!2m1!1sMeteorologisches+Observatorium+Hohenprei%C3%9Fenberg!3m6!1s0x479c4bef8a600c37:0x366ece10f684b12c!8m2!3d47.8014781!4d11.0095483!15sCi9NZXRlb3JvbG9naXNjaGVzIE9ic2VydmF0b3JpdW0gSG9oZW5wZWnDn2VuYmVyZ1oxIi9tZXRlb3JvbG9naXNjaGVzIG9ic2VydmF0b3JpdW0gaG9oZW5wZWnDn2VuYmVyZ5IBGHdlYXRoZXJfZm9yZWNhc3Rfc2VydmljZZoBJENoZERTVWhOTUc5blMwVkpRMEZuU1VONWNrbGhaalJSUlJBQuABAA!16s%2Fg%2F120t41mg!5m1!1e4?entry=ttu) - Blick nach Süden (Zugspitze)
- [Meteorologisches Observatorium Lindenberg](https://www.google.com/maps/place/Meteorological+Observatory+Lindenberg/@52.2048045,14.1235898,4681m/data=!3m1!1e3!4m14!1m7!3m6!1s0x47078ca7e525c6bb:0x8d488da2db3ef552!2sMeteorological+Observatory+Lindenberg!8m2!3d52.2095718!4d14.1185483!16s%2Fg%2F122hpc5l!3m5!1s0x47078ca7e525c6bb:0x8d488da2db3ef552!8m2!3d52.2095718!4d14.1185483!16s%2Fg%2F122hpc5l!5m1!1e4?entry=ttu) - Blick nach Nord-Nordosten
- [DWD Offenbach](https://www.google.com/maps/place/Deutscher+Wetterdienst/@50.1019289,8.7429667,4365m/data=!3m1!1e3!4m6!3m5!1s0x47bd0e70c78fb01b:0x6223bc7a43506ad9!8m2!3d50.1026611!4d8.7479084!16s%2Fg%2F1pp2vbj9x!5m1!1e4?entry=ttu) - Ost, Blick nach Offenbach
- DWD Offenbach - West, Blick nach Frankfurt
- [Wetterstation Schmücke](https://www.google.com/maps/search/Wetterstation+Schm%C3%BCcke/@50.6531716,10.7717425,1414m/data=!3m1!1e3!5m1!1e4?entry=ttu) - Blick nach Südwest
- [Rostock-Warnemünde](https://www.google.com/maps/place/Deutscher+Wetterdienst+Ndl.+Rostock/@54.1798279,12.0784804,1086m/data=!3m1!1e3!4m14!1m7!3m6!1s0x47acf8a0eb65ee75:0x42549bc56943e71c!2sDeutscher+Wetterdienst+Ndl.+Rostock!8m2!3d54.1798248!4d12.0810607!16s%2Fg%2F1hc0v92bc!3m5!1s0x47acf8a0eb65ee75:0x42549bc56943e71c!8m2!3d54.1798248!4d12.0810607!16s%2Fg%2F1hc0v92bc!5m1!1e4?entry=ttu) - Blick nach Nordwest
- [Wetterstation Wasserkuppe, Rhön](https://www.google.com/maps/place/Wetterstation/@50.4977716,9.9394841,2360m/data=!3m1!1e3!4m6!3m5!1s0x47a33dda76b071f1:0x87091be68073a43e!8m2!3d50.497106!4d9.9426059!16s%2Fg%2F11h9zdbxgk!5m1!1e4?entry=ttu) - Blick nach Südwest

## related projects
- [php-skeetbot/php-skeetbot](https://github.com/php-skeetbot/php-skeetbot)
	- [php-skeetbot/skeetbot-template](https://github.com/php-skeetbot/skeetbot-template)
- [chillerlan/php-httpinterface](https://github.com/chillerlan/php-httpinterface)
	- [chillerlan/php-http-message-utils](https://github.com/chillerlan/php-http-message-utils)
	- [chillerlan/php-oauth](https://github.com/chillerlan/php-oauth)
- [chillerlan/php-settings-container](https://github.com/chillerlan/php-settings-container)
- [chillerlan/php-dotenv](https://github.com/chillerlan/php-dotenv)


## Image copyright

https://www.dwd.de/EN/service/legal_notice/legal_notice.html

> All open spatial data and spatial data services of the DWD as well as all DWD services that are defined as high-value datasets
> ([HVD](https://eur-lex.europa.eu/eli/reg_impl/2023/138/oj)) may be re-used under the Creative Commons licence conditions
> [CC BY 4.0](https://creativecommons.org/licenses/by/4.0/) provided that the source is acknowledged.
> Spatial data here includes any location-related weather and climate information offered for access.
>
> Rules and templates for acknowledging the DWD as source [can be found here](https://www.dwd.de/EN/service/legal_notice/templates_dwd_as_source.html?nn=450678).
>
> For developing its services, the DWD uses, among other things, the following information from third parties:
>
> Basic spatial data:
>
> Surveying authorities of the Länder<br>Federal Agency for Cartography and Geodesy (Bundesamt für Kartographie und Geodäsie, BKG) (http://www.bkg.bund.de)
>
> Satellite data:
>
> European Organisation for the Exploitation of Meteorological Satellites (EUMETSAT) (http://www.eumetsat.int)<br>
> National Oceanic and Atmospheric Administration (NOAA) (http://www.noaa.gov)

See also https://opendata.dwd.de/README.txt

## Disclaimer

WE'RE TOTALLY NOT RUNNING A PRODUCTION-LIKE ENVIRONMENT ON GITHUB.<br>
WE'RE RUNNING A TEST AND POST THE RESULT TO AN EXTERNAL WEBSITE.<br>
WE'RE JUST LOOKING IF THE SCRIPT STILL WORKS ON A SCHEDULE 8 TIMES A DAY.
