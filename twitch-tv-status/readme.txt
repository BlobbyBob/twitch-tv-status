=== Plugin Name ===
Contributors: blobbybob
Tags: twitch, widget
Requires at least: 4.3
Tested up to: 4.6.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Adding a widget to display the status of Twitch.tv Streams

== Description ==

This plugin adds a widget to Wordpress.

Configurable options:
1. Title
2. Streams (as many as you wish, seperated by semicolon)
3. Twitch Client ID

Since all the requests run over the server the clients are not able to extract your Client ID

If you insert no stream name, it will show the status of the SaltyTeemo stream ~.~

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/twitch-tv-status` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Go to the Design/Widget section and add the widget to a sidebar
4. Enter your Client ID (you only need to do this one time) and enter your stream names

== Frequently Asked Questions ==

= The client ID box is always empty, do I have to enter it every time I change something? =

No, the client ID gets written in a file

= How can I change the icons? =

Just replace the images in `/wp-content/plugins/twitch-tv-status/` directory

== Changelog ==

= 1.0 =
* Added Widget
* Added Widget options
* Added Client ID Protection

== Upgrade Notice ==



