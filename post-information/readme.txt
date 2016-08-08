=== Post Information ===
Contributors: cpoteet
Tags: posts, meta, jquery, javascript, toggle, metadata
Requires at least: 2.6
Tested up to: 2.9
Stable tag: 1.6.1

This plugin allows you to show/hide post information through a JavaScript toggle. 

== Description ==

If real estate is a priority in your theme then this plugin can help by showing post metadata information on demand by using a JavaScript toggle function.

== Installation ==

1. Extract the ZIP file, and upload to /wp-content/plugins/
2. Activate the plugin
3. Place `<?php postinfo(); ?>` in the loop where you desire.

== Customization ==

= Customizing the Appearance =

Starting in version 1.1 the plugin now adds a stylesheet (post-information.css) to your theme.  Edit that stylesheet to make aesthetic changes.

It is tested and will work in FF 2+, Safari 3 +, IE 6+ (although 6 doesn't support PNG transparency).

== Changelog ==

= 1.6.1 =

* Changed reference to CSS file according to best practice.

= 1.6 =

* Fixed word count when using the !-more- command.

= 1.5 =

* Added CSS sprite for toggle to reduce flash.

= 1.4 =

* Added JavaScript callback to designate graphically box can be minimized
* Changed text for toggle to be more descriptive
* Tweaked CSS

= 1.3 =

* Altered comments link in meta box on single post entries

= 1.2 =

* Added graceful degradation for users without JS

= 1.1 =

* Converted to jQuery from script.aculo.us (uses jQuery bundled with WordPress)
* Added support for tags
* Added extensive styling including the famous [Fam Fam Silk Icons](http://www.famfamfam.com/lab/icons/silk/)
* Reorganized code

= 1.0 =

* Initial release