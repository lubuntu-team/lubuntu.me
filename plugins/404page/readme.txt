=== 404page - your smart custom 404 error page ===
Contributors: petersplugins
Donate link: https://petersplugins.com/make-a-donation/
Tags: page, 404, error, error page, 404 page, page not found, page not found error, 404 error page, missing, broken link, template, 404 link, seo, custom 404, custom 404 page, custom 404 error, custom 404 error page, customize 404, customize 404 page, customize 404 error page
Requires at least: 3.0
Tested up to: 4.9
Stable tag: 3.3
Requires PHP: 5.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Custom 404 the easy way! Set any page as custom 404 error page. No coding needed. Works with (almost) every Theme.

== Description ==

<strong>The 404page plugin is the most used plugin to create a customized 404 error page in WordPress.</strong>

It allows you to easily create your own 404 error page without any effort and it works with almost every theme.

<blockquote>
If you like this plugin please do me favor and leave a review here on wordpress.org so that other people know it is helpful for you. Thanks!
</blockquote>

[youtube https://youtu.be/HygoFMwdIuY]

= Usage =

Create your custom 404 error page just like any other page using the WordPress Page Editor. Then go to 'Appearance' -> '404 Error Page' and select the created page as your custom 404 error page. That's it!

* Different from other similar plugins the 404page plugin **does not create redirects**. That’s **quite important** because a correct code 404 is delivered which tells search engines that the page does not exist and has to be removed from the index. A redirect would result in a HTTP code 301 or 302 and the URL would remain in the search index.
* Different from other similar plugins the 404page plugin **does not create additional server requests**. 

= Docs & Support =

More detailed information about the 404page plugin can be found in the [Plugin Manual](http://petersplugins.com/docs/404page/). For support check the [Support Forum](https://wordpress.org/support/plugin/404page).

== Compatibility ==

= The 404page plugin was sucessfully tested by the author with the following themes =
* [Athena](https://wordpress.org/themes/athena/)
* [Customizr](https://wordpress.org/themes/customizr/) (Read more about [Customizr Compatibility Mode](http://petersplugins.com/docs/404page/#settings_operating_method))
* [Enfold](https://themeforest.net/item/enfold-responsive-multipurpose-theme/4519990?ref=petersplugins)
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
* [Twenty Seventeen](https://wordpress.org/themes/twentyseventeen/)
* [Vantage](https://wordpress.org/themes/vantage/)
* [Virtue](https://wordpress.org/themes/virtue/)
* [Zerif Lite](http://themeisle.com/themes/zerif-lite/)

= The 404page plugin was sucessfully tested by the author with the following starter themes =
* [Bones](http://themble.com/bones/)
* [JointsWP](http://jointswp.com/)
* [undersores](http://underscores.me/)

= The 404page plugin was sucessfully tested by the author with the following plugins =
* [bbPress](https://wordpress.org/plugins/bbpress/)
* [BuddyPress](https://wordpress.org/plugins/buddypress/)
* [DW Question & Answer](https://www.designwall.com/wordpress/plugins/dw-question-answer/)
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

= More plugins from Peter =

* **[hashtagger](https://wordpress.org/plugins/hashtagger/)** - Use hashtags in WordPress
* **[smart Archive Page Remove](https://wordpress.org/plugins/smart-archive-page-remove/)** - Completely remove unwated Archive Pages from your Blog 
* **[smart User Slug Hider](https://wordpress.org/plugins/smart-user-slug-hider/)** - Hide usernames in author pages URLs to enhance security 
* [See all](https://profiles.wordpress.org/petersplugins/#content-plugins)

== Screenshots ==

1. Create your costom 404 Error Page as a normal WordPress Page
2. Set the created Page as 404 Error Page
3. Advanced Settings
4. Advanced Settings with WPML plugin installed (Compatibility Mode is not available, because the 404page plugin automatically switches to WMPL mode)

== Frequently Asked Questions ==

= Are there any requirements? =

To enable the WordPress 404 error handling you have to set the Permalink Structure ('Settings' -> 'Permalinks') to anything else but 'Default'. Otherwise 404 errors are handled by the webserver and not by WordPress.

= Are 404 errors redirected? =

No, there is no redirection! The chosen page is delivered as a 'real' 404 error page. This results in a HTTP 404 code and not in 301 or 302, which is important for Search Engines to tell them, that the page does not exist and should be deleted from the index.

= Is it possible to add custom CSS to the 404 page? =

The 404page plugin adds a CSS class `error404` to the `<body>` tag which can be used for extra styling.

== Changelog ==

= 3.3 (2017-11-16) =
* support for right-to-left-languages added
* faulty display in WP 4.9 fixed

= 3.2 (2017-10-05) =
* new feature to send an HTTP 410 error for deleted objects

= 3.1 (2017-07-24) =
* bugfix for Polylang ([See Topic](https://wordpress.org/support/topic/3-0-breaks-polylang-support/))
* bugfix for CLI ([See Topic](https://wordpress.org/support/topic/uninstall-php-from-cli-failed/))
* add debug class to body tag
* also add body classes for Customizr theme
* do not add error404 class if already exists
* further redesign admin interface

= 3.0 (2017-07-05) =
* new feature to force 404 error after loading page
* new feature to disable URL autocorrection guessing 
* finally removed Polylang stuff disabled in 2.4
* redesigned admin interface
* code improvement

= 2.5 (2017-05-19) =
* hide 404 page from search results on front end (if WPML is active, all languages are hidden)
* do not fire a 404 in Compatibility Mode if the [DW Question & Answer plugin by DesignWall](https://www.designwall.com/wordpress/plugins/dw-question-answer/) is active and a question has no answers

= 2.4 (2017-03-08) =
* ensure that all core files are loaded properly ([See Topic](https://wordpress.org/support/topic/had-to-deactivate-404page-to-make-wordpress-correctly))
* Polylang plugin does no longer require Compatibility Mode ([See Topic](https://wordpress.org/support/topic/still-displaying-the-themes-404-page-with-polylang/))
* hide all translations if WPML is installed and "Hide 404 page" is active (thanks to the [WPML](https://wpml.org/) guys for pointing me at this)
* post status fix ([See topic](https://wordpress.org/support/topic/doesnt-work-with-custom-post-status/))
* [Enfold theme](https://themeforest.net/item/enfold-responsive-multipurpose-theme/4519990?ref=petersplugins) issue fix (thanks to the guys at [Kriesi.at](http://www.kriesi.at/) for supporting me)

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

= 3.3 =
support for right-to-left-languages

= 3.2 =
new feature to send an HTTP 410 error for deleted objects

= 3.1 =
fixed two bugs, plus further enhancements

= 3.0 =
new features added to force 404 error after loading page and to disable URL autocorrection guessing, plus further enhancements

= 2.5 =
Hide 404 page from search results, compatibility with DW Question & Answer plugin

= 2.4 = 
Version 2.4 fixes several issues. See [changelog](https://wordpress.org/plugins/404page/changelog/) for details.

= 2.3 =
A few minor bug fixes solve some problems with page templates in certain combinations.

= 2.2 =
Enhanced compatibility. Automated Operating Method select removed. Several fixes.

= 2.1 =
Introduced Compatibility Mode, improved compatibility with several plugins. 

= 2.0 =
Version 2.0 is more or less a completely new development and a big step forward. [Read more](http://petersplugins.com/blog/2016/02/23/the-404page-plugin-now-works-with-wpml-and-other-enhancements/)

= 1.4 =
Editing of the 404 page is now possible directly from settings page. Portuguese translation added.