<?php
/* Provider:  - *
 * @author    ThemePunch <info@themepunch.com>
 * @link      http://www.themepunch.com/
 * @copyright 2015 ThemePunch
 */

if( !defined( 'ABSPATH') ) exit();

class RevSliderTemplate {
	
	/* Provider:  - *
	 * Copy a Slide to the Template Slide list
	 * @since: 5.0
	 */
	public function copySlideToTemplates($slide_id, $slide_title, $slide_settings = array()){
		if(intval($slide_id) == 0) return false;
		$slide_title = sanitize_text_field($slide_title);
		if(strlen(trim($slide_title)) < 3) return false;
		
		global $wpdb;
		
		$table_name = RevSliderGlobals::$table_slides;
		
		$duplicate = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %s", $slide_id), ARRAY_A);
		
		if(empty($duplicate)) // slide not found
			return false;
		
		unset($duplicate['id']);
		
		$duplicate['slider_id'] = -1; //-1 sets it to be a template
		$duplicate['slide_order'] = -1;
		
		$params = json_decode($duplicate['params'], true);
		$settings = json_decode($duplicate['settings'], true);
		
		$params['title'] = $slide_title;
		$params['state'] = 'published';
		
		if(isset($slide_settings['width'])) $settings['width'] = intval($slide_settings['width']);
		if(isset($slide_settings['height'])) $settings['height'] = intval($slide_settings['height']);
		
		$duplicate['params'] = json_encode($params);
		$duplicate['settings'] = json_encode($settings);
		
		$response = $wpdb->insert($table_name, $duplicate);
		
		if($response)
			return true;
		
		return false;
	}
	
	
	/* Provider:  - *
	 * Get all Template Slides
	 * @since: 5.0
	 */
	public function getTemplateSlides(){
		global $wpdb;
		
		$table_name = RevSliderGlobals::$table_slides;
		
		$templates = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name WHERE slider_id = %s", -1), ARRAY_A);
		
		//add default Template Slides here!
		$default = $this->getDefaultTemplateSlides();
		
		$templates = array_merge($templates, $default);
		
		if(!empty($templates)){
			foreach($templates as $key => $template){
				$templates[$key]['params'] = json_decode($template['params'], true);
				$templates[$key]['layers'] = json_decode($template['layers'], true);
				$templates[$key]['settings'] = json_decode($template['settings'], true);
			}
		}
		
		return $templates;
	}
	
	
	/* Provider:  - *
	 * Add default Template Slides that can't be deleted for example. Authors can add their own Slides here through Filter
	 * @since: 5.0
	 */
	private function getDefaultTemplateSlides(){
		$templates = array();
		
		$templates = apply_filters('revslider_set_template_slides', $templates);
		
		return $templates;
	}
	
	
	/* Provider:  - *
	 * get default ThemePunch default Slides
	 * @since: 5.0
	 */
	public function getThemePunchTemplateSlides($sliders = false){
		global $wpdb;
		
		$templates = array();
		
		$slide_defaults = array();//
		
		if($sliders == false){
			$sliders = $this->getThemePunchTemplateSliders();
		}
		$table_name = RevSliderGlobals::$table_slides;
		
		if(!empty($sliders)){
			foreach($sliders as $slider){
				if(!isset($slider['installed'])){
					$slides = $this->getThemePunchTemplateDefaultSlides($slider['alias']);
					$templates = array_merge($templates, $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name WHERE slider_id = %s", $slider['id']), ARRAY_A));
					foreach($templates as $key => $tmpl){
						if(isset($slides[$key])) $templates[$key]['img'] = $slides[$key]['img'];
					}
				}/* Provider:  - else{
					$templates = array_merge($templates, array($slide_defaults[$slider['alias']]));
				}*/
			}
		}
		
		if(!empty($templates)){
			foreach($templates as $key => $template){
				if(!isset($template['installed'])){
					$templates[$key]['params'] = json_decode(@$template['params'], true);
					$templates[$key]['layers'] = json_decode(@$template['layers'], true);
					$templates[$key]['settings'] = json_decode(@$template['settings'], true);
				}
			}
		}
		
		return $templates;
	}
	
	
	/* Provider:  - *
	 * get default ThemePunch default Slides
	 * @since: 5.0
	 */
	public function getThemePunchTemplateDefaultSlides($slider_alias){
		
		$slides = array(
			'classic-carousel' => array(
				0 => array('title' => 'Slide 1', 'img' => RS_PLUGIN_URL .'admin/assets/imports/classic-carousel/slide1.jpg'),
				1 => array('title' => 'Slide 2', 'img' => RS_PLUGIN_URL .'admin/assets/imports/classic-carousel/slide2.jpg'),
				2 => array('title' => 'Slide 3', 'img' => RS_PLUGIN_URL .'admin/assets/imports/classic-carousel/slide3.jpg'),
				3 => array('title' => 'Slide 4', 'img' => RS_PLUGIN_URL .'admin/assets/imports/classic-carousel/slide4.jpg'),
				4 => array('title' => 'Slide 5', 'img' => RS_PLUGIN_URL .'admin/assets/imports/classic-carousel/slide5.jpg')				
			),
			'classicslider' => array(
				0 => array('title' => 'Slide 1', 'img' => RS_PLUGIN_URL .'admin/assets/imports/classicslider/slide1.jpg'),
				1 => array('title' => 'Slide 2', 'img' => RS_PLUGIN_URL .'admin/assets/imports/classicslider/slide2.jpg'),
				2 => array('title' => 'Slide 3', 'img' => RS_PLUGIN_URL .'admin/assets/imports/classicslider/slide3.jpg'),
				3 => array('title' => 'Slide 4', 'img' => RS_PLUGIN_URL .'admin/assets/imports/classicslider/slide4.jpg'),
				4 => array('title' => 'Slide 5', 'img' => RS_PLUGIN_URL .'admin/assets/imports/classicslider/slide5.jpg')				
			),
			'contenttabs' => array(
				0 => array('title' => 'Slide 1', 'img' => RS_PLUGIN_URL .'admin/assets/imports/contenttabs/slide1.jpg'),
				1 => array('title' => 'Slide 2', 'img' => RS_PLUGIN_URL .'admin/assets/imports/contenttabs/slide2.jpg'),
				2 => array('title' => 'Slide 3', 'img' => RS_PLUGIN_URL .'admin/assets/imports/contenttabs/slide3.jpg')				
			),
			'facebook-feed' => array(
				0 => array('title' => 'Slide 1', 'img' => RS_PLUGIN_URL .'admin/assets/imports/facebook-feed/slide1.jpg')				
			),
			'fashion' => array(
				0 => array('title' => 'Slide 1', 'img' => RS_PLUGIN_URL .'admin/assets/imports/fashion/slide1.jpg'),
				1 => array('title' => 'Slide 2', 'img' => RS_PLUGIN_URL .'admin/assets/imports/fashion/slide2.jpg'),
				2 => array('title' => 'Slide 3', 'img' => RS_PLUGIN_URL .'admin/assets/imports/fashion/slide3.jpg'),
				3 => array('title' => 'Slide 4', 'img' => RS_PLUGIN_URL .'admin/assets/imports/fashion/slide4.jpg'),
				4 => array('title' => 'Slide 5', 'img' => RS_PLUGIN_URL .'admin/assets/imports/fashion/slide5.jpg')				
			),
			'flickr-gallery' => array(
				0 => array('title' => 'Slide 1', 'img' => RS_PLUGIN_URL .'admin/assets/imports/flickr-gallery/slide1.jpg')				
			),
			'gym' => array(
				0 => array('title' => 'Slide 1', 'img' => RS_PLUGIN_URL .'admin/assets/imports/gym/slide1.jpg'),
				1 => array('title' => 'Slide 2', 'img' => RS_PLUGIN_URL .'admin/assets/imports/gym/slide2.jpg'),
				2 => array('title' => 'Slide 3', 'img' => RS_PLUGIN_URL .'admin/assets/imports/gym/slide3.jpg')				
			),
			'highlight-carousel' => array(
				0 => array('title' => 'Slide 1', 'img' => RS_PLUGIN_URL .'admin/assets/imports/highlight-carousel/slide1.jpg'),
				1 => array('title' => 'Slide 2', 'img' => RS_PLUGIN_URL .'admin/assets/imports/highlight-carousel/slide2.jpg'),
				2 => array('title' => 'Slide 3', 'img' => RS_PLUGIN_URL .'admin/assets/imports/highlight-carousel/slide3.jpg'),
				3 => array('title' => 'Slide 4', 'img' => RS_PLUGIN_URL .'admin/assets/imports/highlight-carousel/slide4.jpg')
				
			),
			'highlight-showcase' => array(
				0 => array('title' => 'Slide 1', 'img' => RS_PLUGIN_URL .'admin/assets/imports/highlight-showcase/slide1.jpg'),
				1 => array('title' => 'Slide 2', 'img' => RS_PLUGIN_URL .'admin/assets/imports/highlight-showcase/slide2.jpg'),
				2 => array('title' => 'Slide 3', 'img' => RS_PLUGIN_URL .'admin/assets/imports/highlight-showcase/slide3.jpg'),
				3 => array('title' => 'Slide 4', 'img' => RS_PLUGIN_URL .'admin/assets/imports/highlight-showcase/slide4.jpg')				
			),
			'imagehero' => array(
				0 => array('title' => 'Slide 1', 'img' => RS_PLUGIN_URL .'admin/assets/imports/imagehero/slide1.jpg')						
			),
			'insta-gallery' => array(
				0 => array('title' => 'Slide 1', 'img' => RS_PLUGIN_URL .'admin/assets/imports/insta-gallery/slide1.jpg')				
			),
			'levanorestaurantbar' => array(
				0 => array('title' => 'Slide 1', 'img' => RS_PLUGIN_URL .'admin/assets/imports/levanorestaurantbar/slide1.jpg'),
				1 => array('title' => 'Slide 2', 'img' => RS_PLUGIN_URL .'admin/assets/imports/levanorestaurantbar/slide2.jpg'),
				2 => array('title' => 'Slide 3', 'img' => RS_PLUGIN_URL .'admin/assets/imports/levanorestaurantbar/slide3.jpg'),
				3 => array('title' => 'Slide 4', 'img' => RS_PLUGIN_URL .'admin/assets/imports/levanorestaurantbar/slide4.jpg'),
				4 => array('title' => 'Slide 5', 'img' => RS_PLUGIN_URL .'admin/assets/imports/levanorestaurantbar/slide5.jpg')				
			),
			'mainfeature' => array(
				0 => array('title' => 'Slide 1', 'img' => RS_PLUGIN_URL .'admin/assets/imports/mainfeature/slide1.jpg'),
				1 => array('title' => 'Slide 2', 'img' => RS_PLUGIN_URL .'admin/assets/imports/mainfeature/slide2.jpg'),
				2 => array('title' => 'Slide 3', 'img' => RS_PLUGIN_URL .'admin/assets/imports/mainfeature/slide3.jpg'),
				3 => array('title' => 'Slide 4', 'img' => RS_PLUGIN_URL .'admin/assets/imports/mainfeature/slide4.jpg'),
				4 => array('title' => 'Slide 5', 'img' => RS_PLUGIN_URL .'admin/assets/imports/mainfeature/slide5.jpg'),				
				4 => array('title' => 'Slide 6', 'img' => RS_PLUGIN_URL .'admin/assets/imports/mainfeature/slide6.jpg'),			
				4 => array('title' => 'Slide 7', 'img' => RS_PLUGIN_URL .'admin/assets/imports/mainfeature/slide7.jpg')				
			),
			'media-gallery-two' => array(
				0 => array('title' => 'Slide 1', 'img' => RS_PLUGIN_URL .'admin/assets/imports/media-gallery-two/slide1.jpg'),
				1 => array('title' => 'Slide 2', 'img' => RS_PLUGIN_URL .'admin/assets/imports/media-gallery-two/slide2.jpg'),
				2 => array('title' => 'Slide 3', 'img' => RS_PLUGIN_URL .'admin/assets/imports/media-gallery-two/slide3.jpg'),
				3 => array('title' => 'Slide 4', 'img' => RS_PLUGIN_URL .'admin/assets/imports/media-gallery-two/slide4.jpg'),
				4 => array('title' => 'Slide 5', 'img' => RS_PLUGIN_URL .'admin/assets/imports/media-gallery-two/slide5.jpg'),
				4 => array('title' => 'Slide 6', 'img' => RS_PLUGIN_URL .'admin/assets/imports/media-gallery-two/slide6.jpg')								
			),
			'media-carousel-autoplay' => array(
				0 => array('title' => 'Slide 1', 'img' => RS_PLUGIN_URL .'admin/assets/imports/media-carousel-autoplay/slide1.jpg'),
				1 => array('title' => 'Slide 2', 'img' => RS_PLUGIN_URL .'admin/assets/imports/media-carousel-autoplay/slide2.jpg'),
				2 => array('title' => 'Slide 3', 'img' => RS_PLUGIN_URL .'admin/assets/imports/media-carousel-autoplay/slide3.jpg'),
				3 => array('title' => 'Slide 4', 'img' => RS_PLUGIN_URL .'admin/assets/imports/media-carousel-autoplay/slide4.jpg'),
				4 => array('title' => 'Slide 5', 'img' => RS_PLUGIN_URL .'admin/assets/imports/media-carousel-autoplay/slide5.jpg'),
				4 => array('title' => 'Slide 6', 'img' => RS_PLUGIN_URL .'admin/assets/imports/media-carousel-autoplay/slide6.jpg')								
			),
			'news-bg-video' => array(
				0 => array('title' => 'Slide 1', 'img' => RS_PLUGIN_URL .'admin/assets/imports/news-bg-video/slide1.jpg')				
			),
			'news-gallery' => array(
				0 => array('title' => 'Slide 1', 'img' => RS_PLUGIN_URL .'admin/assets/imports/news-gallery/slide1.jpg'),
				1 => array('title' => 'Slide 2', 'img' => RS_PLUGIN_URL .'admin/assets/imports/news-gallery/slide2.jpg'),
				2 => array('title' => 'Slide 3', 'img' => RS_PLUGIN_URL .'admin/assets/imports/news-gallery/slide3.jpg'),
				3 => array('title' => 'Slide 4', 'img' => RS_PLUGIN_URL .'admin/assets/imports/news-gallery/slide4.jpg')				
			),
			'news-gallery-post-based' => array(
				0 => array('title' => 'Slide 1', 'img' => RS_PLUGIN_URL .'admin/assets/imports/news-gallery-post-based/slide1.jpg')				
			),
			'news-hero' => array(
				0 => array('title' => 'Slide 1', 'img' => RS_PLUGIN_URL .'admin/assets/imports/news-hero/slide1.jpg')				
			),
			'news-video' => array(
				0 => array('title' => 'Slide 1', 'img' => RS_PLUGIN_URL .'admin/assets/imports/news-video/slide1.jpg')				
			),
			'newsletter-hero' => array(
				0 => array('title' => 'Slide 1', 'img' => RS_PLUGIN_URL .'admin/assets/imports/newsletter-hero/slide1.jpg')				
			),
			'notgeneric' => array(
				0 => array('title' => 'Slide 1', 'img' => RS_PLUGIN_URL .'admin/assets/imports/notgeneric/slide1.jpg'),
				1 => array('title' => 'Slide 2', 'img' => RS_PLUGIN_URL .'admin/assets/imports/notgeneric/slide2.jpg'),
				2 => array('title' => 'Slide 3', 'img' => RS_PLUGIN_URL .'admin/assets/imports/notgeneric/slide3.jpg'),
				3 => array('title' => 'Slide 4', 'img' => RS_PLUGIN_URL .'admin/assets/imports/notgeneric/slide4.jpg'),
				4 => array('title' => 'Slide 5', 'img' => RS_PLUGIN_URL .'admin/assets/imports/notgeneric/slide5.jpg')			
			),
			'photography' => array(
				0 => array('title' => 'Slide 1', 'img' => RS_PLUGIN_URL .'admin/assets/imports/photography/slide1.jpg'),
				1 => array('title' => 'Slide 2', 'img' => RS_PLUGIN_URL .'admin/assets/imports/photography/slide2.jpg'),
				2 => array('title' => 'Slide 3', 'img' => RS_PLUGIN_URL .'admin/assets/imports/photography/slide3.jpg'),
				3 => array('title' => 'Slide 4', 'img' => RS_PLUGIN_URL .'admin/assets/imports/photography/slide4.jpg'),
				4 => array('title' => 'Slide 5', 'img' => RS_PLUGIN_URL .'admin/assets/imports/photography/slide5.jpg'),
				5 => array('title' => 'Slide 6', 'img' => RS_PLUGIN_URL .'admin/assets/imports/photography/slide6.jpg'),
				6 => array('title' => 'Slide 7', 'img' => RS_PLUGIN_URL .'admin/assets/imports/photography/slide7.jpg'),
				7 => array('title' => 'Slide 8', 'img' => RS_PLUGIN_URL .'admin/assets/imports/photography/slide8.jpg'),
				8 => array('title' => 'Slide 9', 'img' => RS_PLUGIN_URL .'admin/assets/imports/photography/slide9.jpg'),
				9 => array('title' => 'Slide 10', 'img' => RS_PLUGIN_URL .'admin/assets/imports/photography/slide10.jpg')					
			),
			'photography-carousel' => array(
				0 => array('title' => 'Slide 1', 'img' => RS_PLUGIN_URL .'admin/assets/imports/photography-carousel/slide1.jpg'),
				1 => array('title' => 'Slide 2', 'img' => RS_PLUGIN_URL .'admin/assets/imports/photography-carousel/slide2.jpg'),
				2 => array('title' => 'Slide 3', 'img' => RS_PLUGIN_URL .'admin/assets/imports/photography-carousel/slide3.jpg'),
				3 => array('title' => 'Slide 4', 'img' => RS_PLUGIN_URL .'admin/assets/imports/photography-carousel/slide4.jpg'),
				4 => array('title' => 'Slide 5', 'img' => RS_PLUGIN_URL .'admin/assets/imports/photography-carousel/slide5.jpg'),
				5 => array('title' => 'Slide 6', 'img' => RS_PLUGIN_URL .'admin/assets/imports/photography-carousel/slide6.jpg'),
				6 => array('title' => 'Slide 7', 'img' => RS_PLUGIN_URL .'admin/assets/imports/photography-carousel/slide7.jpg'),
				7 => array('title' => 'Slide 8', 'img' => RS_PLUGIN_URL .'admin/assets/imports/photography-carousel/slide8.jpg'),
				8 => array('title' => 'Slide 9', 'img' => RS_PLUGIN_URL .'admin/assets/imports/photography-carousel/slide9.jpg'),
				9 => array('title' => 'Slide 10', 'img' => RS_PLUGIN_URL .'admin/assets/imports/photography-carousel/slide10.jpg')					
			),
			'search-form-hero' => array(
				0 => array('title' => 'Slide 1', 'img' => RS_PLUGIN_URL .'admin/assets/imports/search-form-hero/slide1.jpg')				
			),
			'showcasecarousel' => array(
				0 => array('title' => 'Slide 1', 'img' => RS_PLUGIN_URL .'admin/assets/imports/showcasecarousel/slide1.jpg'),
				1 => array('title' => 'Slide 2', 'img' => RS_PLUGIN_URL .'admin/assets/imports/showcasecarousel/slide2.jpg'),
				2 => array('title' => 'Slide 3', 'img' => RS_PLUGIN_URL .'admin/assets/imports/showcasecarousel/slide3.jpg'),
				3 => array('title' => 'Slide 4', 'img' => RS_PLUGIN_URL .'admin/assets/imports/showcasecarousel/slide4.jpg'),
				4 => array('title' => 'Slide 5', 'img' => RS_PLUGIN_URL .'admin/assets/imports/showcasecarousel/slide5.jpg'),
				5 => array('title' => 'Slide 6', 'img' => RS_PLUGIN_URL .'admin/assets/imports/showcasecarousel/slide6.jpg'),
				6 => array('title' => 'Slide 7', 'img' => RS_PLUGIN_URL .'admin/assets/imports/showcasecarousel/slide7.jpg'),
				7 => array('title' => 'Slide 8', 'img' => RS_PLUGIN_URL .'admin/assets/imports/showcasecarousel/slide8.jpg'),
				8 => array('title' => 'Slide 9', 'img' => RS_PLUGIN_URL .'admin/assets/imports/showcasecarousel/slide9.jpg')
							
			),
			'sportshero' => array(
				0 => array('title' => 'Slide 1', 'img' => RS_PLUGIN_URL .'admin/assets/imports/sportshero/slide1.jpg')				
			),
			'twitter-feed' => array(
				0 => array('title' => 'Slide 1', 'img' => RS_PLUGIN_URL .'admin/assets/imports/twitter-feed/slide1.jpg')				
			),
			'vimeo-gallery' => array(
				0 => array('title' => 'Slide 1', 'img' => RS_PLUGIN_URL .'admin/assets/imports/vimeo-gallery/slide1.jpg')				
			),
			'vimeohero' => array(
				0 => array('title' => 'Slide 1', 'img' => RS_PLUGIN_URL .'admin/assets/imports/vimeohero/slide1.jpg')				
			),
			'web-product-dark' => array(
				0 => array('title' => 'Slide 1', 'img' => RS_PLUGIN_URL .'admin/assets/imports/web-product-dark/slide1.jpg'),
				1 => array('title' => 'Slide 2', 'img' => RS_PLUGIN_URL .'admin/assets/imports/web-product-dark/slide2.jpg'),
				2 => array('title' => 'Slide 3', 'img' => RS_PLUGIN_URL .'admin/assets/imports/web-product-dark/slide3.jpg'),
				3 => array('title' => 'Slide 4', 'img' => RS_PLUGIN_URL .'admin/assets/imports/web-product-dark/slide4.jpg')				
			),
			'web-product-dark-hero' => array(
				0 => array('title' => 'Slide 1', 'img' => RS_PLUGIN_URL .'admin/assets/imports/web-product-dark-hero/slide1.jpg')				
			),
			'web-product-light-hero' => array(
				0 => array('title' => 'Slide 1', 'img' => RS_PLUGIN_URL .'admin/assets/imports/web-product-light-hero/slide1.jpg')			
			),
			'webproductlight' => array(
				0 => array('title' => 'Slide 1', 'img' => RS_PLUGIN_URL .'admin/assets/imports/webproductlight/slide1.jpg'),
				1 => array('title' => 'Slide 2', 'img' => RS_PLUGIN_URL .'admin/assets/imports/webproductlight/slide2.jpg'),
				2 => array('title' => 'Slide 3', 'img' => RS_PLUGIN_URL .'admin/assets/imports/webproductlight/slide3.jpg'),
				3 => array('title' => 'Slide 4', 'img' => RS_PLUGIN_URL .'admin/assets/imports/webproductlight/slide4.jpg')			
			),
			'youtube-gallery' => array(
				0 => array('title' => 'Slide 1', 'img' => RS_PLUGIN_URL .'admin/assets/imports/youtube-gallery/slide1.jpg')			
			),
			'youtubehero' => array(
				0 => array('title' => 'Slide 1', 'img' => RS_PLUGIN_URL .'admin/assets/imports/youtubehero/slide1.jpg')			
			)
			
		);
		
		
		return @$slides[$slider_alias];
	}
	
	
	/* Provider:  - *
	 * Get default Template Sliders
	 * @since: 5.0
	 */
	public function getDefaultTemplateSliders(){
		global $wpdb;
		
		$sliders = array();
		$check = array();
		
		$table_name = RevSliderGlobals::$table_sliders;
		
		//add themepunch default Sliders here
		$check = $wpdb->get_results("SELECT * FROM $table_name WHERE type = 'template'", ARRAY_A);
		
		$sliders = apply_filters('revslider_set_template_sliders', $sliders);
		
		/* Provider:  - *
		 * Example		 
			$sliders['Slider Pack Name'] = array(
				array('title' => 'PJ Slider 1', 'alias' => 'pjslider1', 'width' => 1400, 'height' => 868, 'zip' => 'exwebproduct.zip', 'uid' => 'bde6d50c2f73f8086708878cf227c82b', 'installed' => false, 'img' => RS_PLUGIN_URL .'admin/assets/imports/exwebproduct.jpg'),
				array('title' => 'PJ Classic Slider', 'alias' => 'pjclassicslider', 'width' => 1240, 'height' => 600, 'zip' => 'classicslider.zip', 'uid' => 'a0d6a9248c9066b404ba0f1cdadc5cf2', 'installed' => false, 'img' => RS_PLUGIN_URL .'admin/assets/imports/classicslider.jpg')
			);
		 **/
		
		if(!empty($check) && !empty($sliders)){
			foreach($sliders as $key => $the_sliders){
				foreach($the_sliders as $skey => $slider){
					foreach($check as $ikey => $installed){
						if($installed['alias'] == $slider['alias']){
							$img = $slider['img'];
							$sliders[$key][$skey] = $installed;
							
							$sliders[$key][$skey]['img'] = $img;
							break;
						}
					}
				}
			}
		}
		
		return $sliders;
	}
	
	
	/* Provider:  - *
	 * get default ThemePunch default Sliders
	 * @since: 5.0
	 */
	public function getThemePunchTemplateSliders(){
		global $wpdb;
		
		$sliders = array();
		
		$table_name = RevSliderGlobals::$table_sliders;
		
		//add themepunch default Sliders here
		$sliders = $wpdb->get_results("SELECT * FROM $table_name WHERE type = 'template'", ARRAY_A);
		
		
		$defaults = array(			


			array('title' => 'Classic Carousel', 'alias' => 'classic-carousel', 'width' => 1240, 'height' => 600, 'zip' => 'classic-carousel.zip', 'uid' => '146a01dd380c0cdee85c4456ee68cd84', 'installed' => false, 'img' => RS_PLUGIN_URL .'admin/assets/imports/classic-carousel/slider.jpg'),
			array('title' => 'ClassicSlider', 'alias' => 'classicslider', 'width' => 1240, 'height' => 600, 'zip' => 'classicslider.zip', 'uid' => 'a0d6a9248c9066b404ba0f1cdadc5cf2', 'installed' => false, 'img' => RS_PLUGIN_URL .'admin/assets/imports/classicslider/slider.jpg'),
			array('title' => 'Content Tabs', 'alias' => 'contenttabs', 'width' => 1024, 'height' => 600, 'zip' => 'contenttabs.zip', 'uid' => 'e02e91604b690123a3d07a65582c4fd0', 'installed' => false, 'img' => RS_PLUGIN_URL .'admin/assets/imports/contenttabs/slider.jpg'),			
			array('title' => 'Facebook Feed', 'alias' => 'facebook-feed', 'width' => 800, 'height' => 600, 'zip' => 'facebook-feed.zip', 'uid' => '5506431d5b1babcb25dcf52c508d42e3', 'installed' => false, 'img' => RS_PLUGIN_URL .'admin/assets/imports/facebook-feed/slider.jpg'),			
			array('title' => 'Fashion', 'alias' => 'fashion', 'width' => 1240, 'height' => 868, 'zip' => 'fashion.zip', 'uid' => '4f4b914d6db35e19101ff003c4e7ea3a', 'installed' => false, 'img' => RS_PLUGIN_URL .'admin/assets/imports/fashion/slider.jpg'),			
			array('title' => 'Flickr Gallery', 'alias' => 'flickr-gallery', 'width' => 800, 'height' => 640, 'zip' => 'flickr-gallery.zip', 'uid' => 'ad85cfac7acfa678e6a1b8febfee51ed', 'installed' => false, 'img' => RS_PLUGIN_URL .'admin/assets/imports/flickr-gallery/slider.jpg'),			
			array('title' => 'Gym', 'alias' => 'gym', 'width' => 1240, 'height' => 868, 'zip' => 'gym.zip', 'uid' => 'e4d81f13f96fb9bc905f4ad89615032b', 'installed' => false, 'img' => RS_PLUGIN_URL .'admin/assets/imports/gym/slider.jpg'),			
			array('title' => 'Highlight Carousel', 'alias' => 'highlight-carousel', 'width' => 800, 'height' => 720, 'zip' => 'highlight-carousel.zip', 'uid' => 'ada52163f723a942f782351fa0396b3d', 'installed' => false, 'img' => RS_PLUGIN_URL .'admin/assets/imports/highlight-carousel/slider.jpg'),			
			array('title' => 'Highlight Showcase', 'alias' => 'highlight-showcase', 'width' => 1230, 'height' => 720, 'zip' => 'highlight-showcase.zip', 'uid' => '2bfe0bd410fb48fec9d942eab1e21530', 'installed' => false, 'img' => RS_PLUGIN_URL .'admin/assets/imports/highlight-showcase/slider.jpg'),			
			array('title' => 'Image Hero', 'alias' => 'imagehero', 'width' => 1240, 'height' => 600, 'zip' => 'imagehero.zip', 'uid' => '038dab2d980b026ae15c1a22e3175690', 'installed' => false, 'img' => RS_PLUGIN_URL .'admin/assets/imports/imagehero/slider.jpg'),			
			array('title' => 'Instagram Gallery', 'alias' => 'insta-gallery', 'width' => 640, 'height' => 640, 'zip' => 'insta-gallery.zip', 'uid' => '711732b0d42ec2b57818a2b9b1d86cba', 'installed' => false, 'img' => RS_PLUGIN_URL .'admin/assets/imports/insta-gallery/slider.jpg'),			
			array('title' => 'Levano Restaurant Bar', 'alias' => 'levanorestaurantbar', 'width' => 1240, 'height' => 868, 'zip' => 'levanorestaurantbar.zip', 'uid' => '4178f837db67d1b2eb6cb5840bbd0b42', 'installed' => false, 'img' => RS_PLUGIN_URL .'admin/assets/imports/levanorestaurantbar/slider.jpg'),					
			array('title' => 'Main Feature Slider', 'alias' => 'mainfeature', 'width' => 1230, 'height' => 750, 'zip' => 'mainfeature.zip', 'uid' => '1e002a3230ab00095bedc6f60393ee7f', 'installed' => false, 'img' => RS_PLUGIN_URL .'admin/assets/imports/mainfeature/slider.jpg'),						
			array('title' => 'Media Gallery Two', 'alias' => 'media-gallery-two', 'width' => 1230, 'height' => 692, 'zip' => 'media-gallery-two.zip', 'uid' => 'd002f1b1b55805f9322c264c5504ba5a', 'installed' => false, 'img' => RS_PLUGIN_URL .'admin/assets/imports/media-gallery-two/slider.jpg'),						
			array('title' => 'Media Carousel Autoplay', 'alias' => 'media-carousel-autoplay', 'width' => 720, 'height' => 405, 'zip' => 'media-carousel-autoplay.zip', 'uid' => '393d7875b1cc9d933378b35e4f645d76', 'installed' => false, 'img' => RS_PLUGIN_URL .'admin/assets/imports/media-carousel-autoplay/slider.jpg'),						
			array('title' => 'News Background Video', 'alias' => 'news-bg-video', 'width' => 1240, 'height' => 500, 'zip' => 'news-bg-video.zip', 'uid' => '467e74ce10da2c5ca1f4258af558809d', 'installed' => false, 'img' => RS_PLUGIN_URL .'admin/assets/imports/news-bg-video/slider.jpg'),						
			array('title' => 'News Gallery', 'alias' => 'news-gallery', 'width' => 1240, 'height' => 500, 'zip' => 'news-gallery.zip', 'uid' => '3a069c3b286dbb9ee435563f747e3300', 'installed' => false, 'img' => RS_PLUGIN_URL .'admin/assets/imports/news-gallery/slider.jpg'),						
			array('title' => 'News Gallery Post Based', 'alias' => 'news-gallery-post-based', 'width' => 1240, 'height' => 500, 'zip' => 'news-gallery-post-based.zip', 'uid' => '32fe05b1039c29ab9420bfd15aec5488', 'installed' => false, 'img' => RS_PLUGIN_URL .'admin/assets/imports/news-gallery-post-based/slider.jpg'),						
			array('title' => 'News Hero', 'alias' => 'news-hero', 'width' => 1240, 'height' => 500, 'zip' => 'news-hero.zip', 'uid' => '96a0385538a17c8c81ed8175740f70ea', 'installed' => false, 'img' => RS_PLUGIN_URL .'admin/assets/imports/news-hero/slider.jpg'),						
			array('title' => 'News Video', 'alias' => 'news-video', 'width' => 1240, 'height' => 500, 'zip' => 'news-video.zip', 'uid' => 'f901e9e16e0363248156c2209eb584e9', 'installed' => false, 'img' => RS_PLUGIN_URL .'admin/assets/imports/news-video/slider.jpg'),			
			array('title' => 'Newsletter Hero', 'alias' => 'newsletter-hero', 'width' => 1240, 'height' => 600, 'zip' => 'newsletter-hero.zip', 'uid' => '6290a9864d8c4c6311784586ed1cc5fe', 'installed' => false, 'img' => RS_PLUGIN_URL .'admin/assets/imports/newsletter-hero/slider.jpg'),			
			array('title' => 'NotGeneric', 'alias' => 'notgeneric', 'width' => 1240, 'height' => 868, 'zip' => 'notgeneric.zip', 'uid' => '9d87ba95e02210a9f82387add2ceadf9', 'installed' => false, 'img' => RS_PLUGIN_URL .'admin/assets/imports/notgeneric/slider.jpg'),			
			array('title' => 'Photography', 'alias' => 'photography', 'width' => 1240, 'height' => 868, 'zip' => 'photography.zip', 'uid' => '1b2072547afb75e49f33b016751ed360', 'installed' => false, 'img' => RS_PLUGIN_URL .'admin/assets/imports/photography/slider.jpg'),			
			array('title' => 'Photography Carousel', 'alias' => 'photography-carousel', 'width' => 1024, 'height' => 868, 'zip' => 'photography-carousel.zip', 'uid' => '9a84b859ba23dc49ba8784e3a86545fa', 'installed' => false, 'img' => RS_PLUGIN_URL .'admin/assets/imports/photography-carousel/slider.jpg'),									
			array('title' => 'Search Form Hero', 'alias' => 'search-form-hero', 'width' => 1240, 'height' => 600, 'zip' => 'search-form-hero.zip', 'uid' => 'e09eb1bd0f22b3a2b02a1aa251dd1f3e', 'installed' => false, 'img' => RS_PLUGIN_URL .'admin/assets/imports/search-form-hero/slider.jpg'),			
			array('title' => 'Showcase Carousel', 'alias' => 'showcasecarousel', 'width' => 600, 'height' => 600, 'zip' => 'showcasecarousel.zip', 'uid' => 'c5ca218398331bd2c064efc2f62eae56', 'installed' => false, 'img' => RS_PLUGIN_URL .'admin/assets/imports/showcasecarousel/slider.jpg'),			
			array('title' => 'Sports Hero', 'alias' => 'sportshero', 'width' => 1240, 'height' => 720, 'zip' => 'sportshero.zip', 'uid' => 'a7f509fa823db719b1e80d5b325593f2', 'installed' => false, 'img' => RS_PLUGIN_URL .'admin/assets/imports/sportshero/slider.jpg'),			
			array('title' => 'Twitter Feed', 'alias' => 'twitter-feed', 'width' => 800, 'height' => 640, 'zip' => 'twitter-feed.zip', 'uid' => 'efbfc2af5da5258e7b7bed8598e483cc', 'installed' => false, 'img' => RS_PLUGIN_URL .'admin/assets/imports/twitter-feed/slider.jpg'),			
			array('title' => 'Vimeo Gallery', 'alias' => 'vimeo-gallery', 'width' => 1230, 'height' => 692, 'zip' => 'vimeo-gallery.zip', 'uid' => 'fa824ce1ff3942ec268fc9eda60df539', 'installed' => false, 'img' => RS_PLUGIN_URL .'admin/assets/imports/vimeo-gallery/slider.jpg'),			
			array('title' => 'Vimeo Hero', 'alias' => 'vimeohero', 'width' => 1240, 'height' => 600, 'zip' => 'vimeohero.zip', 'uid' => 'c575575f96173d88589cddcb06120b77', 'installed' => false, 'img' => RS_PLUGIN_URL .'admin/assets/imports/vimeohero/slider.jpg'),			
			array('title' => 'Web Product Dark', 'alias' => 'web-product-dark', 'width' => 1400, 'height' => 868, 'zip' => 'web-product-dark.zip', 'uid' => '39b872cf0608e63c3a503e58374dc30a', 'installed' => false, 'img' => RS_PLUGIN_URL .'admin/assets/imports/web-product-dark/slider.jpg'),
			array('title' => 'Web Product Dark Hero', 'alias' => 'web-product-dark-hero', 'width' => 1400, 'height' => 768, 'zip' => 'web-product-dark-hero.zip', 'uid' => 'b6784e8925221f36677217979d26e6f0', 'installed' => false, 'img' => RS_PLUGIN_URL .'admin/assets/imports/web-product-dark-hero/slider.jpg'),			
			array('title' => 'Web Product Light Hero', 'alias' => 'web-product-light-hero', 'width' => 1400, 'height' => 768, 'zip' => 'web-product-light-hero.zip', 'uid' => '428e65d6aaa6ef775429989d50516492', 'installed' => false, 'img' => RS_PLUGIN_URL .'admin/assets/imports/web-product-light-hero/slider.jpg'),			
			array('title' => 'Web Product Light', 'alias' => 'webproductlight', 'width' => 1400, 'height' => 868, 'zip' => 'webproductlight.zip', 'uid' => 'fa23dab5bf1139c6393828647a9de4e0', 'installed' => false, 'img' => RS_PLUGIN_URL .'admin/assets/imports/webproductlight/slider.jpg'),			
			array('title' => 'Youtube Gallery', 'alias' => 'youtube-gallery', 'width' => 1240, 'height' => 600, 'zip' => 'youtube-gallery.zip', 'uid' => 'ee9e4928ac74f5f0c0b697ce708f5aa7', 'installed' => false, 'img' => RS_PLUGIN_URL .'admin/assets/imports/youtube-gallery/slider.jpg'),			
			array('title' => 'Youtube Hero', 'alias' => 'youtubehero', 'width' => 1240, 'height' => 600, 'zip' => 'youtubehero.zip', 'uid' => 'e0b2c12a45841bdf21cb96305f2c85bf', 'installed' => false, 'img' => RS_PLUGIN_URL .'admin/assets/imports/youtubehero/slider.jpg')
		);
		
		if(!empty($sliders)){
			foreach($defaults as $key => $slider){
				foreach($sliders as $ikey => $installed){
					if($installed['alias'] == $slider['alias']){
						$img = $slider['img'];
						$defaults[$key] = $installed;
						
						$defaults[$key]['img'] = $img;
						break;
					}
				}
			}
		}
		
		return $defaults;
		
	}
	
	
	/* Provider:  - *
	 * output markup for the import template, the zip was not yet improted
	 * @since: 5.0
	 */
	public function write_import_template_markup($template){
		?>
		<div data-src="<?php echo $template['img']; ?>" class="template_slider_item_import"
			data-gridwidth="<?php echo $template['width']; ?>"
			data-gridheight="<?php echo $template['height']; ?>"
			data-zipname="<?php echo $template['zip']; ?>"
			data-uid="<?php echo $template['uid']; ?>"
			>
			<!--div class="template_title"><?php echo @$template['title']; ?></div-->
			<div class="not-imported-overlay">
				<div class="icon-install_slider"></div>
			</div>
		</div>
		<?php
	}
	
	
	/* Provider:  - *
	 * output markup for the import template, the zip was not yet improted
	 * @since: 5.0
	 */
	public function write_import_template_markup_slide($template){
		?>
		<div data-src="<?php echo $template['img']; ?>" class="template_slide_item_import"
			data-gridwidth="<?php echo $template['width']; ?>"
			data-gridheight="<?php echo $template['height']; ?>"
			data-zipname="<?php echo $template['zip']; ?>"
			data-uid="<?php echo $template['uid']; ?>"
			data-slidenumber="<?php echo $template['number']; ?>"
			style="height: 126px; background-size: cover; background-position: 50% 50%; background-repeat: no-repeat;"
			>
			<div class="template_title"><?php echo @$template['title']; ?></div>
			<div class="not-imported-overlay">
				<div class="icon-install_slider"></div>
			</div>
		</div>
		<?php
	}
	
	
	/* Provider:  - *
	 * output markup for template
	 * @since: 5.0
	 */
	public function write_template_markup($template, $slider_id = false){
		$params = $template['params'];
		$settings = $template['settings'];
		$slide_id = $template['id'];
		$title = str_replace("'", "", RevSliderBase::getVar($params, 'title', 'Slide'));
		if($slider_id !== false) $title = ''; //remove Title if Slider
		
		$width = RevSliderBase::getVar($settings, "width", 1240);
		$height = RevSliderBase::getVar($settings, "height", 868);
		
		$bgType = RevSliderBase::getVar($params, "background_type","transparent");
		$bgColor = RevSliderBase::getVar($params, "slide_bg_color","transparent");

		$bgFit = RevSliderBase::getVar($params, "bg_fit","cover");
		$bgFitX = intval(RevSliderBase::getVar($params, "bg_fit_x","100"));
		$bgFitY = intval(RevSliderBase::getVar($params, "bg_fit_y","100"));

		$bgPosition = RevSliderBase::getVar($params, "bg_position","center center");
		$bgPositionX = intval(RevSliderBase::getVar($params, "bg_position_x","0"));
		$bgPositionY = intval(RevSliderBase::getVar($params, "bg_position_y","0"));

		$bgRepeat = RevSliderBase::getVar($params, "bg_repeat","no-repeat");

		$bgStyle = ' ';
		if($bgFit == 'percentage'){
			if(intval($bgFitY) == 0 || intval($bgFitX) == 0){
				$bgStyle .= "background-size: cover;";
			}else{
				$bgStyle .= "background-size: ".$bgFitX.'% '.$bgFitY.'%;';
			}
		}else{
			$bgStyle .= "background-size: ".$bgFit.";";
		}
		if($bgPosition == 'percentage'){
			$bgStyle .= "background-position: ".$bgPositionX.'% '.$bgPositionY.'%;';
		}else{
			$bgStyle .= "background-position: ".$bgPosition.";";
		}
		$bgStyle .= "background-repeat: ".$bgRepeat.";";
		
		if(isset($template['img'])){
			$thumb = $template['img'];
		}else{
		
			$imageID = RevSliderBase::getVar($params, "image_id");
			if(empty($imageID)){
				$thumb = RevSliderBase::getVar($params, "image");
		
				$imgID = RevSliderBase::get_image_id_by_url($thumb);
				if($imgID !== false){
					$thumb = RevSliderFunctionsWP::getUrlAttachmentImage($imgID, RevSliderFunctionsWP::THUMB_MEDIUM);
				}
			}else{
				$thumb = RevSliderFunctionsWP::getUrlAttachmentImage($imageID,RevSliderFunctionsWP::THUMB_MEDIUM);
			}
		
			if($thumb == '') $thumb = RevSliderBase::getVar($params, "image");
		}
		

		$bg_fullstyle ='';
		$bg_extraClass='';
		$data_urlImageForView='';

		if($bgType == 'image' || $bgType == 'vimeo' || $bgType == 'youtube' || $bgType == 'html5') {
			$data_urlImageForView = 'data-src="'.$thumb.'"';
			$bg_fullstyle =' style="'.$bgStyle.'" ';
		}

		if($bgType=="solid")
			$bg_fullstyle =' style="background-color:'.$bgColor.';" ';
			
		if($bgType=="trans" || $bgType=="transparent")
			$bg_extraClass = 'mini-transparent';
		
		?>
		<div <?php echo $data_urlImageForView; ?> class="<?php echo ($slider_id !== false) ? 'template_slider_item' : 'template_item'; ?> <?php echo $bg_extraClass; ?>" <?php echo $bg_fullstyle; ?>
			data-gridwidth="<?php echo $width; ?>"
			data-gridheight="<?php echo $height; ?>"
			<?php if($slider_id !== false){ ?>
			data-sliderid="<?php echo $slider_id; ?>"
			<?php }else{ ?>
			data-slideid="<?php echo $slide_id; ?>"
			<?php } ?>
			>
			<div class="template_title"><?php echo $title; ?></div>
		</div>
		<?php
	}
	
}

?>