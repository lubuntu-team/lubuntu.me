=== 404page - your smart custom 404 error page ===
Contributors: petersplugins, smartware.cc
Donate link:http://petersplugins.com/make-a-donation/
Tags: page, 404, error, error page, 404 page, page not found, page not found error, 404 error page, missing, broken link, template, 404 link, seo, custom 404, custom 404 page, custom 404 error, custom 404 error page, customize 404, customize 404 page, customize 404 error page
Requires at least: 3.0
Tested up to: 4.6
Stable tag: 2.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Custom 404 the easy way! Set any page as custom 404 error page. No coding needed. Works with (almost) every Theme.

== Description ==

> Create your custom 404 Error Page using the full Power of WordPress

**See also [Plugin Homepage](http://petersplugins.com/free-wordpress-plugins/404page/) and [Plugin Doc](http://petersplugins.com/docs/404page/)**

https://www.youtube.com/watch?v=VTL07Lf0IsY

Create your custom 404 Page as a normal WordPress Page using the full power of WordPress. You can use a Custom Page Template or Custom Fields, you can set a Featured Image - everything like on every other Page. Then go to 'Appearance' -> '404 Error Page' from your WordPress Dashbord and select the created Page as your 404 error page. That's it!

= Why you should choose this plugin =

* Different from other similar plugins the 404page plugin **does not create redirects**. That’s **quite important** because a correct code 404 is delivered which tells search engines that the page does not exist and has to be removed from the index. A redirect would result in a HTTP code 301 or 302 and the URL would remain in the search index.
* Different from other similar plugins the 404page plugin **does not create additional server requests**. 

= Translations =

The 404page Plugin uses GlotPress - the wordpress.org Translation System - for translations. Translations can be submitted at [translate.wordpress.org](https://translate.wordpress.org/projects/wp-plugins/404page).

**Translation are highly appreciated**. It would be great if you'd support the 404page Plugin by adding a new translation or keeping an existing one up to date. If you're new to GlotPress take a look at the [Translator Handbook](https://make.wordpress.org/polyglots/handbook/tools/glotpress-translate-wordpress-org/).

= Do you like the 404page Plugin? =

Thanks, I appreciate that. You don’t need to make a donation. No money, no beer, no coffee. Please, just [tell the world that you like what I’m doing](http://petersplugins.com/make-a-donation/)! And that’s all.

= More plugins from Peter =

* **[hashtagger](https://wordpress.org/plugins/hashtagger/)** - Use hashtags in WordPress
* **[smart Archive Page Remove](https://wordpress.org/plugins/smart-archive-page-remove/)** - Completely remove unwated Archive Pages from your Blog 
* **[smart User Slug Hider](https://wordpress.org/plugins/smart-user-slug-hider/)** - Hide usernames in author pages URLs to enhance security 
* [See all](https://profiles.wordpress.org/petersplugins/#content-plugins)

== Installation ==

= From your WordPress dashboard =

1. Visit 'Plugins' -> 'Add New'
1. Search for '404page'
1. Activate the plugin through the 'Plugins' menu in WordPress

= Manually from wordpress.org =

1. Download 404page from wordpress.org and unzip the archive
1. Upload the `404page` folder to your `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= Are there any requirements? =

To enable the WordPress 404 error handling you have to set the Permalink Structure ('Settings' -> 'Permalinks') to anything else but 'Default'. Otherwise 404 errors are handled by the webserver and not by WordPress.

= Are 404 errors redirected? =

No, there is no redirection! The chosen page is delivered as a 'real' 404 error page. This results in a HTTP 404 code and not in 301 or 302, which is important for Search Engines to tell them, that the page does not exist and should be deleted from the index.

= What about PHP Version 7? =
The plugin works smoothly with PHP 7.

= Is it possible to add custom CSS to the 404 page? =
The 404page plugin adds a CSS class `error404` to the `<body>` tag which can be used for extra styling.

== Screenshots ==

1. Create your 404 Page as a normal WordPress Page
2. Define the created Page as 404 Error Page
3. Your custom 404 Error Page is shown in case of a 404 error

== Changelog ==

= 2.3 (2016-11-21) =
* a few minor bug fixes solve some problems with page templates in certain combinations

= 2.2 (2016-09-26) =
* automatic switch to Compatibility Mode for several plugins removed
* enhanced support for WPML and Polylang
* remove the 404 page from search results (for all languages if WPML or Polylang is used)
* remove the 404 page from sitemap or other page lists (for all languages if WPML or Polylang is used)
* bugfix for author archives
* confusing admin message removed

= 2.1 (2016-04-22) =
* introduction of selectable Operating Methods
* several changes to Compatibility Mode for improved WPML and bbPress compatibility plus compatibility with Page Builder by SiteOrigin
* Polylang compatibility
* automatic switch to Compatibility Mode if WPML, bbPress, Polylang or Page Builder by SiteOrigin is detected
* completely new Customizr Compatibility Mode (automatically enabled if Customizr is detected)
* firing an 404 error in case of directly accessing the 404 error page can now be deactivated
* WP Super Cache support
* option to hide the 404 error page from the Pages list
* 404 error test
* plugin expandable by action
* delete all settings on uninstall

= 2.0 (2016-03-08) =
* WPML compatibility
* bbPress compatibility
* Customizr compatibility
* directly accessing the 404 error page now throws an 404 error
* class `error404` added to the classes that are assigned to the body HTML element
* the settings menu was moved from 'Settings' to 'Appearance'
* translation files removed, using GlotPress exclusively
* [Read more](http://petersplugins.com/blog/2016/02/23/the-404page-plugin-now-works-with-wpml-and-other-enhancements/)

= 1.4 (2015-08-07) =
* edit the 404 page directly from settings page
* Portuguese translation

= 1.3 (2015-01-12) =
* technical improvement (rewritten as class)
* cosmetics

= 1.2 (2014-07-28) =
* Spanish translation
* Serbo-Croatian translation

= 1.1 (2014-06-03) =
* Multilingual support added
* German translation

= 1.0 (2013-09-30) =
* Initial Release

== Upgrade Notice ==

= 2.2 =
Enhanced compatibility. Automated Operating Method select removed. Several fixes.

= 2.1 =
Introduced Compatibility Mode, improved compatibility with several plugins. 

= 2.0 =
Version 2.0 is more or less a completely new development and a big step forward. [Read more](http://petersplugins.com/blog/2016/02/23/the-404page-plugin-now-works-with-wpml-and-other-enhancements/)

= 1.4 =
Editing of the 404 page is now possible directly from settings page. Portuguese translation added.

== Compatibility ==

= The 404page plugin was sucessfully tested by the author with the following themes =
* [Athena](https://wordpress.org/themes/athena/)
* [Customizr](https://wordpress.org/themes/customizr/) (Read more about [Customizr Compatibility Mode](http://petersplugins.com/docs/404page/#settings_operating_method))
* [evolve](https://wordpress.org/themes/evolve/)
* [GeneratePress](https://wordpress.org/themes/generatepress/)
* [Graphene](https://wordpress.org/themes/graphene/)
* [Hemingway](https://wordpress.org/themes/hemingway/)
* [Hueman](https://wordpress.org/themes/hueman/)
* [Responsive](https://wordpress.org/themes/responsive/)
* [Spacious](https://wordpress.org/themes/spacious/)
* [Sparkling](https://wordpress.org/themes/sparkling/)
* [Sydney](https://wordpress.org/themes/sydney/)
* [Twenty Ten](https://wordpress.org/themes/twentyten/)
* [Twenty Eleven](https://wordpress.org/themes/twentyeleven/)
* [Twenty Twelve](https://wordpress.org/themes/twentytwelve/)
* [Twenty Thirteen](https://wordpress.org/themes/twentythirteen/)
* [Twenty Fourteen](https://wordpress.org/themes/twentyfourteen/)
* [Twenty Fifteen](https://wordpress.org/themes/twentyfifteen/)
* [Twenty Sixteen](https://wordpress.org/themes/twentysixteen/)
* [Vantage](https://wordpress.org/themes/vantage/)
* [Virtue](https://wordpress.org/themes/virtue/)
* [Zerif Lite](http://themeisle.com/themes/zerif-lite/)

= The 404page plugin was sucessfully tested by the author with the following starter themes =
* [Bones}(http://themble.com/bones/)
* [JointsWP](http://jointswp.com/)
* [undersores](http://underscores.me/)

= The 404page plugin was sucessfully tested by the author with the following plugins =
* [bbPress](https://wordpress.org/plugins/bbpress/)
* [BuddyPress](https://wordpress.org/plugins/buddypress/)
* [hashtagger](https://wordpress.org/plugins/hashtagger/)
* [Page Builder by SiteOrigin](https://wordpress.org/plugins/siteorigin-panels/)
* [Polylang](https://wordpress.org/plugins/polylang/)
* [User Submitted Posts](https://wordpress.org/plugins/user-submitted-posts/)
* [WooCommerce](https://wordpress.org/plugins/woocommerce/)
* [WP Super Cache](https://wordpress.org/plugins/wp-super-cache/)(Read more about [WP Super Cache Compatibility](http://petersplugins.com/docs/404page/#wp_super_cache)
* [WPML WordPress Multilingual Plugin](https://wpml.org/)([officially approved by WPML team](https://wpml.org/plugin/404page/))

== For developers ==

= Action Hook =
The plugin adds an action hook 404page_after_404 which you can use to add extra functionality. The exact position the action occurs after an 404 error is detected depends on the Operating Method. Your function must not generate any output. There are no parameters.

= Native Support =
If you are a theme developer you can add native support for the 404page plugin to your theme for full control. [Read more](http://petersplugins.com/docs/404page/#theme_native_support).