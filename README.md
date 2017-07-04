# WP Add Custom CSS Cache #

## Description ##

Instead of loading WordPress on every page to get WP Add Custom CSS values, create a real file and load it instead of
WP Add Custom CSS style.

## Important to know ##

Contributors: NicolasKulka
Requires at least: 3.0.1
Tested up to: 4.5
Stable tag: trunk
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Put WP Add Custom CSS option to a real file

== Installation ==
PHP5 Required.

1.  Download, unzip and upload to your WordPress plugins directory
2.  Activate the plugin within you WordPress Administration Backend
3. That's It !

[More help installing Plugins](http://codex.wordpress.org/Managing_Plugins#Installing_Plugins "WordPress Codex: Installing Plugins")

## Frequently Asked Questions ##

= Where the CSS file is written ? =

By default the css file is written in your WordPress Content dir in the folder cache/wass/ .
The files names are like 10-wass.css where 10 is the blog id.

= How to change the folder where the css files are created ? =

You can use the filter 'WASS_Cache/folder_name' and change the folder name.

= I do not see any changes, the WP Add Custom CSS custom URL is still used =

Check if the folder wp-content/cache/wass is here and writable. If not so, then create it.

## Changelog ##

### 1.0.0
* 4 July 2017
* Initial
