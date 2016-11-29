=== Mooberry Show Latest Posts ===
Contributors: mooberrydreams
Author URI: http://www.mooberrydreams.com/
Tags: archive, most recent posts, static front page, latest posts, front page, post, posts
Requires at least: 3.3.0
Tested up to: 4.4
Stable tag: 1.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Show latest blog posts on the static front page. Customize number of posts and display orientation.

== Description ==

When you have your website set to use a static front page, you may want to show a list of your most recent blog posts, too.

This plugin will show the title, excerpt, and link of your most recent blog posts at the bottom of your front page. You can customize the following options:
  
* The number of posts to show  
* The category to show posts from (default: "All Categories")
* Whether the posts should display horizontally or vertically  
* The text of the "Read More" link  
* The title of the section (default: "Latest Posts")  

CSS can be used to adjust styling. The following IDs and classes are used:  

* mbdslp (ID) - wraps the entire Latest Posts output  
* mbdslp_title (ID) - title of the section (ie "Latest Posts")  
* mbdslp_table (ID) - the table used for horizontal display  
* mbdslp_post_title (class) - title of the individual post  
* mbdslp_post_text (class) - excerpt of the individual post  
* mbslp_read_more (class) - individual "Read More" link  

== Installation ==

1. Upload `mooberry-show-latest-posts` directory to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to `Settings` -> `Show Latest Posts` to change the settings

== Screenshots ==

1. The settings screen.
2. The posts displayed horizontally.
3. The posts displayed vertically.

== Changelog ==

= 1.3 =
* Added number of words option

= 1.2 =
* Added ability to filter by category

= 1.1 =   
* Wrapped entire output in a div  
* Added IDs to individual elements  

= 1.0 =
* Initial Version
