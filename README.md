# Barba Admin Bar

Allows users to have functional admin bar when viewing the site.

### Description

This plugin fixes invalid links in Wordpress admin bar on sites that are loaded with [Barba.js](http://barbajs.org/) manager. Because wp admin bar is placed outside Barba container and wp-admin also (thanks, Darwin!) all links will fail to load requested page and if you use "open in new tab" option, wrong pages will be opened in admin because links are only valid for the first page loaded (or accessed directly). This plugin will inject little chunk of js code after admin bar. 'no-barba' class will be added on each link in admin menubar. It will add event listener on ['transitionCompleted'](http://barbajs.org/events.html) event in Barba.js. Every time event is fired ajax request that tries to fetch loaded page id will be made and all links will be updated so users will be able to use "Edit page" link.


### Installation

1. Upload 'barba-admin-bar' to the '/wp-content/plugins/' directory
2. Activate the plugin through the 'Plugins' menu in WordPress

### Tested on
* Mac My Browser     :)
