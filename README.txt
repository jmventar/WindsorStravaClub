=== Plugin Name ===
Contributors: jwind
Donate link: http://windsorup.com/windsor-strava-club-wordpress-plugin/
Tags: googlemaps, strava, cycling, biking, running, sports
Requires at least: 3.0.1
Tested up to: 4.9.2
Stable tag: 1.0.13
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Overlay your Strava club's latest rides on a map with profile icons.

== Description ==

Windsor Strava Club overlays your club's last 200 rides on a Google Map. Options for club, map zoom and map center. Club member rides are marked on the map with their profile picture. Clicking the profile picture reveals a infowindow with the athletes name, ride name and link to the activity.


== Installation ==

1. Upload `windsor-strava-club` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Place short code `[windsor_strava_club]` in your templates. [Shortcode attributes](http://windsorup.com/windsor-strava-club-wordpress-plugin/)

== Frequently Asked Questions ==


Visit Windsor Strava Club [FAQ Page](http://windsorup.com/windsor-strava-club-wordpress-plugin/)


== Changelog ==

= 1.0.13 =
* Date localization - thanks [jmventar](https://github.com/jmventar)

= 1.0.12 =
* remove menu item

= 1.0.11 =
* Fixed PHP warnings

= 1.0.10 =
* Changed “rides” to activities

= 1.0.9 =
* Undefined index in loader

= 1.0.8 =
* Fixed conflict with Yoast SEO where admin area hangs when trying to edit shortcode


= 1.0.7 =
* Fixed bug where manual strava activies broke map due to no polyline
* Added default avatar for users who don't have them
* Fixed maps zoom shortcode attribute

= 1.0.6 =
* Relative gmaps script for proper protocal

= 1.0.5 =
* Removed Google Maps sensor param
* Add default option values for Strava API Key and Google Maps API Key

= 1.0.4 =
* Added field for google javascript API Key to support googles change in terms of service with their maps

= 1.0.3 =
* remove debug code

= 1.0.2 =
* Updated richmarker.js to not use CDN (CDN was broken)
* Added missing API warning
* Only output group image if there is in fact a group image

= 1.0.1 =
* Fixed map title to output club image and club name

= 1.0 =
* Initial Release.
