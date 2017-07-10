=== Barba Admin Bar ===
Contributors: leovujanic
Tags: barba, barba.js, admin, menu, bar, admin menu bar, barba admin bar
Requires at least: 4.8.0
Tested up to: 4.8.0

Allows users to have functional admin bar when viewing the site.

== Description ==

This plugin fixes invalid links in Wordpress admin bar on sites that are loaded with <a href="http://barbajs.org/" target="_blank">Barba.js</a> manager. Because wp admin bar
is placed outside Barba container and wp-admin also (thanks, Darwin!) all links will fail to load requested page and if
you use "open in new tab" option, wrong pages will be opened in admin because links are only valid for the first page loaded
(or accessed directly). This plugin will inject a little chunk of js code after admin bar. 'no-barba' class will be added
on each link in admin menubar. It will add event listener on <a href="http://barbajs.org/events.html" target="_blank">'transitionCompleted'</a>
event in Barba.js. Every time event is fired ajax request that tries to fetch loaded page id will be made and all links
will be updated so users will be able to use "Edit page" link.

= Tested on =
* Mac My Browser 	:)

== Installation ==

1. Upload 'barba-admin-bar' to the '/wp-content/plugins/' directory
2. Activate the plugin through the 'Plugins' menu in WordPress