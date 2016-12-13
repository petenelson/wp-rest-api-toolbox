=== REST API Toolbox ===
Contributors: gungeekatx
Tags: rest api, rest, wp rest api
Donate link: https://petenelson.io/
Requires at least: 4.4
Tested up to: 4.7
Stable tag: 1.3.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Allows tweaking of several REST API settings

== Description ==

Allows tweaking of several REST API settings

* Disable the REST API
* Remove WordPress core endpoints
* Require authentication for core endpoints
* Force SSL
* WP-CLI commands: wp rest-api-toolbox

Find us on GitHub at https://github.com/petenelson/wp-rest-api-toolbox

(Creative commons toolbox image provided by James Tworow https://www.flickr.com/photos/sherlock77/)

== Installation ==

1. Upload rest-api-toolbox to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Visit the Settings -> REST API Toolbox page to customize

== Frequently Asked Questions ==

Have any questions?  We can answer them here?

== Screenshots ==

1. General admin settings
2. WordPress core settings

== Changelog ==

= 1.3.0 December 12th, 2016 =
* Added option to require authentication for core endpoints.

= 1.2.0 December 5th, 2016 =
* Updated the way the REST API can be disabled due to the rest_enabled filter being deprecated.
* Added 'settings' to the list of core endpoints that can be removed.
* Added CLI command: wp rest-api-toolbox status

= 1.1.0 April 16, 2016 =
* Change REST API prefix
* Remove specific core endpoints
* Disable JSONP

= 1.0.0 April 15, 2016 =
* Initial release

== Upgrade Notice ==

== Changelog ==

= 1.3.0 December 12th, 2016 =
* Added option to require authentication for core endpoints.

= 1.2.0 December 5th, 2016 =
* Updated the way the REST API can be disabled due to the rest_enabled filter being deprecated.
* Added 'settings' to the list of core endpoints that can be removed.
* Added CLI command: wp rest-api-toolbox status

= 1.1.0 April 16, 2016 =
* Change REST API prefix
* Remove specific core endpoints
* Disable JSONP
