<?php
/* Provider:  - *
 * @author    ThemePunch <info@themepunch.com>
 * @link      http://www.themepunch.com/
 * @copyright 2015 ThemePunch
 */

if( !defined( 'ABSPATH') ) exit();

class RevSliderSlide extends RevSliderElementsBase{
	
	private $id;
	private $sliderID;
	private $slideOrder;
	
	private $imageUrl;
	private $imageID;
	private $imageThumb;
	private $imageFilepath;
	private $imageFilename;
	
	private $params;
	private $arrLayers;
	private $settings;
	private $arrChildren = null;
	private $slider;
	
	private $static_slide = false;
	
	private $postData;
	public $templateID;
	
	public function __construct(){
		parent::__construct();
	}
	
	/* Provider:  - *
	 * 
	 * init slide by db record
	 */
	public function initByData($record){
		
		$this->id = $record["id"];
		$this->sliderID = $record["slider_id"];
		$this->slideOrder = @$record["slide_order"];
		
		$params = $record["params"];
		$params = (array)json_decode($params);
		
		$layers = $record["layers"];
		$layers = (array)json_decode($layers);
		$layers = RevSliderFunctions::convertStdClassToArray($layers);
		
		$settings = $record["settings"];
		$settings = (array)json_decode($settings);
		
		//$layers = $this->translateLayerSizes($layers);
		
		$imageID = RevSliderFunctions::getVal($params, "image_id");
		
		$imgResolution = RevSliderFunctions::getVal($params, 'image_source_type', 'full');
		
		//get image url and thumb url
		if(!empty($imageID)){
			$this->imageID = $imageID;
			
			$imageUrl = RevSliderFunctionsWP::getUrlAttachmentImage($imageID, $imgResolution);
			if(empty($imageUrl)){
				$imageUrl = RevSliderFunctions::getVal($params, "image");
				
				$imgID = RevSliderBase::get_image_id_by_url($imageUrl);
				if($imgID !== false){
					$imageUrl = RevSliderFunctionsWP::getUrlAttachmentImage($imgID, $imgResolution);
				}
			}
			
			$this->imageThumb = RevSliderFunctionsWP::getUrlAttachmentImage($imageID,RevSliderFunctionsWP::THUMB_MEDIUM);
			
		}else{
			$imageUrl = RevSliderFunctions::getVal($params, "image");
			
			$imgID = RevSliderBase::get_image_id_by_url($imageUrl);
			if($imgID !== false){
				$imageUrl = RevSliderFunctionsWP::getUrlAttachmentImage($imgID, $imgResolution);
			}
		}
		
		if(is_ssl()){
			$imageUrl = str_replace("http://", "https://", $imageUrl);
		}
		
		//dmp($imageUrl);exit();
		
		//set image path, file and url
		$this->imageUrl = $imageUrl;
		
		$this->imageFilepath = RevSliderFunctionsWP::getImagePathFromURL($this->imageUrl);
		$realPath = RevSliderFunctionsWP::getPathContent().$this->imageFilepath;
		
		if(file_exists($realPath) == false || is_file($realPath) == false)
			$this->imageFilepath = "";
		
		$this->imageFilename = basename($this->imageUrl);
		
		$this->params = $params;
		$this->arrLayers = $layers;	
		$this->settings = $settings;	
		
	}
	
	
	/* Provider:  - *
	 * set the image by image id
	 * @since: 5.0
	 */
	public function setImageByID($imageID, $size = 'full'){
		$imageUrl = RevSliderFunctionsWP::getUrlAttachmentImage($imageID, $size);
		
		if(!empty($imageUrl)){
			$this->imageID = $imageID;
			$this->imageUrl = $imageUrl;
			$this->imageThumb = RevSliderFunctionsWP::getUrlAttachmentImage($imageID,RevSliderFunctionsWP::THUMB_MEDIUM);
			$this->imageFilename = basename($this->imageUrl);
			$this->imageFilepath = RevSliderFunctionsWP::getImagePathFromURL($this->imageUrl);
			$realPath = RevSliderFunctionsWP::getPathContent().$this->imageFilepath;
			
			if(file_exists($realPath) == false || is_file($realPath) == false)
				$this->imageFilepath = "";
				
			return true;
		}
		
		return false;
	}
	
	
	/* Provider:  - *
	 * change the background_type parameter
	 * @since: 5.0
	 */
	public function setBackgroundType($new_param){
		$this->params['background_type'] = $new_param;
	}
	
	
	/* Provider:  - *
	 * 
	 * init by another slide
	 */
	private function initBySlide(RevSlide $slide){
		
		$this->id = "template";
		$this->templateID = $slide->getID();
		$this->sliderID = $slide->getSliderID();
		$this->slideOrder = $slide->getOrder();
		
		$this->imageUrl = $slide->getImageUrl();
		$this->imageID = $slide->getImageID();
		$this->imageThumb = $slide->getThumbUrl();
		$this->imageFilepath = $slide->getImageFilepath();
		$this->imageFilename = $slide->getImageFilename();
		
		$this->params = $slide->getParams();
		
		$this->arrLayers = $slide->getLayers();
		
		$this->settings = $slide->getSettings();
		
		$this->arrChildren = $slide->getArrChildrenPure();
	}
	
	
	/* Provider:  - *
	 * 
	 * init slide by post data
	 */
	public function initByStreamData($postData, $slideTemplate, $sliderID, $sourceType, $additions){
		
		$this->postData = array();
		$this->postData = (array)$postData;
		
		//init by global template
		$this->initBySlide($slideTemplate);
		
		switch($sourceType){
			case 'facebook':
				$this->initByFacebook($sliderID, $additions);
			break;
			case 'twitter':
				$this->initByTwitter($sliderID, $additions);
			break;
			case 'instagram':
				$this->initByInstagram($sliderID);
			break;
			case 'flickr':
				$this->initByFlickr($sliderID);
			break;
			case 'youtube':
				$this->initByYoutube($sliderID, $additions);
			break;
			case 'vimeo':
				$this->initByVimeo($sliderID, $additions);
			break;
			default:
				RevSliderFunctions::throwError(__("Source must be from Stream", REVSLIDER_TEXTDOMAIN));
			break;
		}
	}
	
	
	/* Provider:  - *
	 * init the data for facebook
	 * @since: 5.0
	 */
	private function initByFacebook($sliderID, $additions){
		//set some slide params
		$this->id = RevSliderFunctions::getVal($this->postData, 'id');
		
		$this->params["title"] = RevSliderFunctions::getVal($this->postData, 'name');
		
		if(@$this->params['enable_link'] == "true" && @$this->params['link_type'] == "regular"){
			$link = RevSliderFunctions::getVal($this->postData, 'link');
			$this->params["link"] = str_replace(array("%link%", '{{link}}'), $link, $this->params["link"]);
		}

		$this->params["state"] = "published";
		
		if($this->params["background_type"] == 'image'){ //if image is choosen, use featured image as background
			//facebook check which image size is choosen
			$img_sizes = RevSliderBase::get_all_image_sizes('facebook');
			
			$imgResolution = RevSliderFunctions::getVal($this->params, 'image_source_type', reset($img_sizes));
			$this->imageID = RevSliderFunctions::getVal($this->postData, 'id');
			if(!isset($img_sizes[$imgResolution])) $imgResolution = key($img_sizes);
			
			if($additions['fb_type'] == 'album'){
				$imgs = RevSliderFunctions::getVal($this->postData, 'images', array());
				$is = array();
				foreach($imgs as $k => $im){
					$is['size-'.$k] = $im->source;
				}
				
				$facebook_images_avail_sizes = array('size-6','size-4','size-3','size-5','size-2','size-1','size-0');
				$this->imageUrl = $this->find_biggest_photo($is, $imgResolution, $facebook_images_avail_sizes);
				$this->imageThumb = $this->find_biggest_photo($is, 'size-5', $facebook_images_avail_sizes);
			}else{
				$img = $this->get_facebook_timeline_image();
				$this->imageUrl = $img;
				$this->imageThumb = $img;
			}
			
			//if(empty($this->imageUrl))
			//	return(false);
			
			if(empty($this->imageUrl))
				$this->imageUrl = RS_PLUGIN_URL.'public/assets/assets/sources/fb.png';
			
			if(is_ssl()){
				$this->imageUrl = str_replace("http://", "https://", $this->imageUrl);
			}
			
			$this->imageFilename = basename($this->imageUrl);
		}
		
		//replace placeholders in layers:
		$this->setLayersByStreamData($sliderID, 'facebook', $additions);
	}
	
	
	/* Provider:  - *
	 * init the data for twitter
	 * @since: 5.0
	 */
	private function initByTwitter($sliderID, $additions){
		//set some slide params
		$this->id = RevSliderFunctions::getVal($this->postData, 'id');

		$this->params["title"] = RevSliderFunctions::getVal($this->postData, 'title');
		
		if(@$this->params['enable_link'] == "true" && @$this->params['link_type'] == "regular"){
			$link = 'https://twitter.com/'.$additions['twitter_user'].'/status/'.RevSliderFunctions::getVal($this->postData, 'id_str');
			$this->params["link"] = str_replace(array("%link%", '{{link}}'), $link, $this->params["link"]);
		}

		$this->params["state"] = "published";
		
		if($this->params["background_type"] == 'trans' || $this->params["background_type"] == 'image' || $this->params["background_type"] == 'streamtwitter' || $this->params["background_type"] == 'streamtwitterboth'){ //if image is choosen, use featured image as background
			$img_sizes = RevSliderBase::get_all_image_sizes('twitter');
			
			$imgResolution = RevSliderFunctions::getVal($this->params, 'image_source_type', reset($img_sizes));
			$this->imageID = RevSliderFunctions::getVal($this->postData, 'id');
			if(!isset($img_sizes[$imgResolution])) $imgResolution = key($img_sizes);
			
			$image_url_array = RevSliderFunctions::getVal($this->postData, 'media');
			$image_url_large = RevSliderFunctions::getVal($image_url_array, 'large');
			
			$img = RevSliderFunctions::getVal($image_url_large, 'media_url', '');
			$entities = RevSliderFunctions::getVal($this->postData, 'entities');
			
			if($img == ''){
				$image_url_array = RevSliderFunctions::getVal($entities, 'media');
				if(is_array($image_url_array) && isset($image_url_array[0])){
					if(is_ssl()){
						$img = RevSliderFunctions::getVal($image_url_array[0], 'media_url_https');
					}else{
						$img = RevSliderFunctions::getVal($image_url_array[0], 'media_url');
					}
					
				}
			}
			
			$urls = RevSliderFunctions::getVal($entities, 'urls');
			if(is_array($urls) && isset($urls[0])){
				$display_url = RevSliderFunctions::getVal($urls[0], 'display_url');
				
				
				//check if youtube or vimeo is inside
				if(strpos($display_url, 'youtu.be') !== false){
					$raw = explode('/', $display_url);
					$yturl = $raw[1];
					$this->params["slide_bg_youtube"] = $yturl; //set video for background video
				}elseif(strpos($display_url, 'vimeo.com') !== false){
					$raw = explode('/', $display_url);
					$vmurl = $raw[1];
					$this->params["slide_bg_vimeo"] = $vmurl; //set video for background video
				}
			}
			
			$image_url_array = RevSliderFunctions::getVal($entities, 'media');
			if(is_array($image_url_array) && isset($image_url_array[0])){
				$video_info = RevSliderFunctions::getVal($image_url_array[0], 'video_info');
				$variants = RevSliderFunctions::getVal($video_info, 'variants');
				if(is_array($variants) && isset($variants[0])){
					$mp4 = RevSliderFunctions::getVal($variants[0], 'url');

					$this->params["slide_bg_html_mpeg"] = $mp4; //set video for background video
				}
			}
			
			$entities = RevSliderFunctions::getVal($this->postData, 'extended_entities');
			if($img == ''){
				$image_url_array = RevSliderFunctions::getVal($entities, 'media');
				if(is_array($image_url_array) && isset($image_url_array[0])){
					if(is_ssl()){
						$img = RevSliderFunctions::getVal($image_url_array[0], 'media_url_https');
					}else{
						$img = RevSliderFunctions::getVal($image_url_array[0], 'media_url');
					}
					
				}
			}
			
			$urls = RevSliderFunctions::getVal($entities, 'urls');
			if(is_array($urls) && isset($urls[0])){
				$display_url = RevSliderFunctions::getVal($urls[0], 'display_url');
				
				
				//check if youtube or vimeo is inside
				if(strpos($display_url, 'youtu.be') !== false){
					$raw = explode('/', $display_url);
					$yturl = $raw[1];
					$this->params["slide_bg_youtube"] = $yturl; //set video for background video
				}elseif(strpos($display_url, 'vimeo.com') !== false){
					$raw = explode('/', $display_url);
					$vmurl = $raw[1];
					$this->params["slide_bg_vimeo"] = $vmurl; //set video for background video
				}
			}
			
			$image_url_array = RevSliderFunctions::getVal($entities, 'media');
			if(is_array($image_url_array) && isset($image_url_array[0])){
				$video_info = RevSliderFunctions::getVal($image_url_array[0], 'video_info');
				$variants = RevSliderFunctions::getVal($video_info, 'variants');
				if(is_array($variants) && isset($variants[0])){
					$mp4 = RevSliderFunctions::getVal($variants[0], 'url');
					$this->params["slide_bg_html_mpeg"] = $mp4; //set video for background video
				}
			}
			
			if($img !== ''){
				$this->imageUrl = $img;
				$this->imageThumb = $img;
			}
			
			//if(empty($this->imageUrl))
			//	return(false);
			
			if(empty($this->imageUrl))
				$this->imageUrl = RS_PLUGIN_URL.'public/assets/assets/sources/tw.png';
			
			if(is_ssl()){
				$this->imageUrl = str_replace("http://", "https://", $this->imageUrl);
			}
			
			$this->imageFilename = basename($this->imageUrl);
		}
		
		//replace placeholders in layers:
		$this->setLayersByStreamData($sliderID, 'twitter', $additions);
	}
	
	
	/* Provider:  - *
	 * init the data for instagram
	 * @since: 5.0
	 */
	private function initByInstagram($sliderID){
		//set some slide params
		$this->id = RevSliderFunctions::getVal($this->postData, 'id');
		
		$caption = RevSliderFunctions::getVal($this->postData, 'caption');
		
		$this->params["title"] = RevSliderFunctions::getVal($caption, 'text');
		
		$link = RevSliderFunctions::getVal($this->postData, 'link');
		
		if(@$this->params['enable_link'] == "true" && @$this->params['link_type'] == "regular"){
			$this->params["link"] = str_replace(array("%link%", '{{link}}'), $link, $this->params["link"]);
		}

		$this->params["state"] = "published";
		
		if($this->params["background_type"] == 'trans' || $this->params["background_type"] == 'image' || $this->params["background_type"] == 'streaminstagram' || $this->params["background_type"] == 'streaminstagramboth'){ //if image is choosen, use featured image as background
			$img_sizes = RevSliderBase::get_all_image_sizes('instagram');
			
			$imgResolution = RevSliderFunctions::getVal($this->params, 'image_source_type', reset($img_sizes));
			if(!isset($img_sizes[$imgResolution])) $imgResolution = key($img_sizes);
			
			$this->imageID = RevSliderFunctions::getVal($this->postData, 'id');
			$imgs = RevSliderFunctions::getVal($this->postData, 'images', array());
			$is = array();
			foreach($imgs as $k => $im){
				$is[$k] = $im->url;
			}
			
			$this->imageUrl = $is[$imgResolution];
			$this->imageThumb = $is['thumbnail'];
			
			//if(empty($this->imageUrl))
			//	return(false);
			
			if(empty($this->imageUrl))
				$this->imageUrl = RS_PLUGIN_URL.'public/assets/assets/sources/ig.png';
			
			if(is_ssl()){
				$this->imageUrl = str_replace("http://", "https://", $this->imageUrl);
			}
			
			$this->imageFilename = basename($this->imageUrl);
		}
		
		$videos = RevSliderFunctions::getVal($this->postData, 'videos');
		
		if(!empty($videos) && isset($videos->standard_resolution) && isset($videos->standard_resolution->url)){
			$this->params["slide_bg_instagram"] = $videos->standard_resolution->url; //set video for background video
			$this->params["slide_bg_html_mpeg"] = $videos->standard_resolution->url; //set video for background video
		}
		
		
		//replace placeholders in layers:
		$this->setLayersByStreamData($sliderID, 'instagram');	
	}
	
	
	/* Provider:  - *
	 * init the data for flickr
	 * @since: 5.0
	 */
	private function initByFlickr($sliderID){
		//set some slide params
		$this->id = RevSliderFunctions::getVal($this->postData, 'id');
		$this->params["title"] = RevSliderFunctions::getVal($this->postData, 'title');
		
		if(@$this->params['enable_link'] == "true" && @$this->params['link_type'] == "regular"){
			$link = 'http://flic.kr/p/'.$this->base_encode(RevSliderFunctions::getVal($this->postData, 'id'));
			$this->params["link"] = str_replace(array("%link%", '{{link}}'), $link, $this->params["link"]);
		}
		
		$this->params["state"] = "published";
		
		if($this->params["background_type"] == 'image'){ //if image is choosen, use featured image as background
			//facebook check which image size is choosen
			$img_sizes = RevSliderBase::get_all_image_sizes('flickr');
			
			$imgResolution = RevSliderFunctions::getVal($this->params, 'image_source_type', reset($img_sizes));

			$this->imageID = RevSliderFunctions::getVal($this->postData, 'id');
			if(!isset($img_sizes[$imgResolution])) $imgResolution = key($img_sizes);
			
			$is = @array(
				'square' 		=> 	RevSliderFunctions::getVal($this->postData, 'url_sq'),
				'large-square' 	=> 	RevSliderFunctions::getVal($this->postData, 'url_q'),
				'thumbnail' 	=> 	RevSliderFunctions::getVal($this->postData, 'url_t'),
				'small' 		=> 	RevSliderFunctions::getVal($this->postData, 'url_s'),
				'small-320' 	=> 	RevSliderFunctions::getVal($this->postData, 'url_n'),
				'medium' 		=> 	RevSliderFunctions::getVal($this->postData, 'url_m'),
				'medium-640' 	=> 	RevSliderFunctions::getVal($this->postData, 'url_z'),
				'medium-800' 	=> 	RevSliderFunctions::getVal($this->postData, 'url_c'),
				'large' 		=>	RevSliderFunctions::getVal($this->postData, 'url_l'),
				'original'		=>	RevSliderFunctions::getVal($this->postData, 'url_o')
			);
			
			$this->imageUrl = @$is[$imgResolution];
			$this->imageThumb = @$is['thumbnail'];
			
			//if(empty($this->imageUrl))
			//	return(false);
			
			if(empty($this->imageUrl))
				$this->imageUrl = RS_PLUGIN_URL.'public/assets/assets/sources/fr.png';
			
			if(is_ssl()){
				$this->imageUrl = str_replace("http://", "https://", $this->imageUrl);
			}
			
			$this->imageFilename = basename($this->imageUrl);
			
			if(!empty($thumbID))
				$this->setImageByImageURL($thumbID);
			
		}
		//replace placeholders in layers:
		$this->setLayersByStreamData($sliderID, 'flickr');
	}
	
	
	/* Provider:  - *
	 * init the data for youtube
	 * @since: 5.0
	 */
	private function initByYoutube($sliderID, $additions){
		
		//set some slide params
		$snippet = RevSliderFunctions::getVal($this->postData, 'snippet');
		$resource = RevSliderFunctions::getVal($snippet, 'resourceId');
		
		if($additions['yt_type'] == 'channel'){
			$link_raw = RevSliderFunctions::getVal($this->postData, 'id');
			$link = RevSliderFunctions::getVal($link_raw, 'videoId');
		}else{
			$link_raw = RevSliderFunctions::getVal($snippet, 'resourceId');
			$link = RevSliderFunctions::getVal($link_raw, 'videoId');
		}
		
		
		if(@$this->params['enable_link'] == "true" && @$this->params['link_type'] == "regular"){
			
			if($link !== '') $link = '//youtube.com/watch?v='.$link;
			
			$this->params["link"] = str_replace(array("%link%", '{{link}}'), $link, $this->params["link"]);
		}
		
		$this->params["slide_bg_youtube"] = $link; //set video for background video

		
		switch($additions['yt_type']){
			case 'channel':
				$id = RevSliderFunctions::getVal($this->postData, 'id');
				$this->id = RevSliderFunctions::getVal($id, 'videoId');
			break;
			case 'playlist':
				$this->id = RevSliderFunctions::getVal($resource, 'videoId');
			break;
		}
		if($this->id == '') $this->id = 'not-found';
		
		$this->params["title"] = RevSliderFunctions::getVal($snippet, 'title');
		
		$this->params["state"] = "published";
		
		if($this->params["background_type"] == 'trans' || $this->params["background_type"] == 'image' || $this->params["background_type"] == 'streamyoutube' || $this->params["background_type"] == 'streamyoutubeboth'){ //if image is choosen, use featured image as background
			//facebook check which image size is choosen
			$img_sizes = RevSliderBase::get_all_image_sizes('youtube');
			
			$imgResolution = RevSliderFunctions::getVal($this->params, 'image_source_type', reset($img_sizes));

			$this->imageID = RevSliderFunctions::getVal($resource, 'videoId');
			if(!isset($img_sizes[$imgResolution])) $imgResolution = key($img_sizes);
			
			$thumbs = RevSliderFunctions::getVal($snippet, 'thumbnails');
			$is = array();
			if(!empty($thumbs)){
				foreach($thumbs as $name => $vals){
					$is[$name] = RevSliderFunctions::getVal($vals, 'url');
				}
			}
			
			$this->imageUrl = @$is[$imgResolution];
			$this->imageThumb = @$is['medium'];
			
			//if(empty($this->imageUrl))
			//	return(false);
			
			if(empty($this->imageUrl))
				$this->imageUrl = RS_PLUGIN_URL.'public/assets/assets/sources/yt.png';
			
			if(is_ssl()){
				$this->imageUrl = str_replace("http://", "https://", $this->imageUrl);
			}
			
			$this->imageFilename = basename($this->imageUrl);
			
			if(!empty($thumbID))
				$this->setImageByImageURL($thumbID);
			
		}
		//replace placeholders in layers:
		$this->setLayersByStreamData($sliderID, 'youtube', $additions);
	}
	
	
	/* Provider:  - *
	 * init the data for vimeo
	 * @since: 5.0
	 */
	private function initByVimeo($sliderID, $additions){
		
		//set some slide params
		$this->id = RevSliderFunctions::getVal($this->postData, 'id');
		$this->params["title"] = RevSliderFunctions::getVal($this->postData, 'title');
		
		if(@$this->params['enable_link'] == "true" && @$this->params['link_type'] == "regular"){
			$link = RevSliderFunctions::getVal($this->postData, 'url');
			$this->params["link"] = str_replace(array("%link%", '{{link}}'), $link, $this->params["link"]);
		}
		
		$this->params["slide_bg_vimeo"] = RevSliderFunctions::getVal($this->postData, 'url');
		
		$this->params["state"] = "published";
		
		if($this->params["background_type"] == 'trans' || $this->params["background_type"] == 'image' || $this->params["background_type"] == 'streamvimeo' || $this->params["background_type"] == 'streamvimeoboth'){ //if image is choosen, use featured image as background
			//facebook check which image size is choosen
			$img_sizes = RevSliderBase::get_all_image_sizes('vimeo');
			$imgResolution = RevSliderFunctions::getVal($this->params, 'image_source_type', reset($img_sizes));

			$this->imageID = RevSliderFunctions::getVal($this->postData, 'id');
			if(!isset($img_sizes[$imgResolution])) $imgResolution = key($img_sizes);
			
			$is = array();
			
			foreach($img_sizes as $handle => $name){
				$is[$handle] = RevSliderFunctions::getVal($this->postData, $handle);
			}
			
			
			$this->imageUrl = @$is[$imgResolution];
			$this->imageThumb = @$is['thumbnail'];
			
			//if(empty($this->imageUrl))
			//	return(false);
			
			if(empty($this->imageUrl))
				$this->imageUrl = RS_PLUGIN_URL.'public/assets/assets/sources/vm.png';
			
			if(is_ssl()){
				$this->imageUrl = str_replace("http://", "https://", $this->imageUrl);
			}
			
			$this->imageFilename = basename($this->imageUrl);
			
			if(!empty($thumbID))
				$this->setImageByImageURL($thumbID);
			
		}
		//replace placeholders in layers:
		
		$this->setLayersByStreamData($sliderID, 'vimeo', $additions);
	}
	
	
	/* Provider:  - *
	 * replace layer placeholders by stream data
	 * @since: 5.0
	 */
	private function setLayersByStreamData($sliderID, $stream_type, $additions = array()){
		
		$attr = $this->return_stream_data($stream_type, $additions);
		
		foreach($this->arrLayers as $key=>$layer){
			
			$text = RevSliderFunctions::getVal($layer, "text");
			
			$text = apply_filters('revslider_mod_stream_meta', $text, $sliderID, $stream_type, $this->postData); //option to add your own filter here to modify meta to your likings
			
			$text = $this->set_stream_data($text, $attr, $stream_type, $additions);
			
			$layer["text"] = $text;
			
			//set link actions to the stream data
			$layer['layer_action'] = (array) $layer['layer_action'];
			if(isset($layer['layer_action'])){
				if(isset($layer['layer_action']['image_link']) && !empty($layer['layer_action']['image_link'])){
					foreach($layer['layer_action']['image_link'] as $jtsk => $jtsval){
						$layer['layer_action']['image_link'][$jtsk] = $this->set_stream_data($layer['layer_action']['image_link'][$jtsk], $attr, $stream_type, $additions, true);
					}
				}
			}
			
			
			$this->arrLayers[$key] = $layer;
		}
		
		//set params to the stream data
		for($mi=1;$mi<=10;$mi++){
			$pa = $this->getParam('params_'.$mi, '');
			$pa = $this->set_stream_data($pa, $attr, $stream_type, $additions);
			$this->setParam('params_'.$mi, $pa);
		}
		
	}
	
	
	public function set_stream_data($text, $attr, $stream_type, $additions = array(), $is_action = false){
		$img_sizes = RevSliderBase::get_all_image_sizes($stream_type);
		
		$text = str_replace(array('%title%', '{{title}}'), $attr['title'], $text);
		$text = str_replace(array('%excerpt%', '{{excerpt}}'), $attr['excerpt'], $text);
		$text = str_replace(array('%alias%', '{{alias}}'), $attr['alias'], $text);
		$text = str_replace(array('%content%', '{{content}}'), $attr['content'], $text);
		$text = str_replace(array('%link%', '{{link}}'), $attr['link'], $text);
		$text = str_replace(array('%date_published%', '{{date_published}}', '%date%', '{{date}}'), $attr['date'], $text);
		$text = str_replace(array('%date_modified%', '{{date_modified}}'), $attr['date_modified'], $text);
		$text = str_replace(array('%author_name%', '{{author_name}}'), $attr['author_name'], $text);
		$text = str_replace(array('%num_comments%', '{{num_comments}}'), $attr['num_comments'], $text);
		$text = str_replace(array('%catlist%', '{{catlist}}'), $attr['catlist'], $text);
		$text = str_replace(array('%taglist%', '{{taglist}}'), $attr['taglist'], $text);
		$text = str_replace(array('%likes%', '{{likes}}'), $attr['likes'], $text);
		$text = str_replace(array('%retweet_count%', '{{retweet_count}}'), $attr['retweet_count'], $text);
		$text = str_replace(array('%favorite_count%', '{{favorite_count}}'), $attr['favorite_count'], $text);
		$text = str_replace(array('%views%', '{{views}}'), $attr['views'], $text);
		
		if($stream_type == 'twitter' && $is_action === false){
			$text = RevSliderBase::add_wrap_around_url($text);
		}
		
		switch($stream_type){
			case 'facebook':
				foreach($img_sizes as $img_handle => $img_name){
					if($additions['fb_type'] == 'album'){
						$text = str_replace(array('%image_url_'.$img_handle.'%', '{{image_url_'.$img_handle.'}}'), @$attr['img_urls'][$img_handle]['url'], $text);
						$text = str_replace(array('%image_'.$img_handle.'%', '{{image_'.$img_handle.'}}'), @$attr['img_urls'][$img_handle]['tag'], $text);
					}else{
						$text = str_replace(array('%image_url_'.$img_handle.'%', '{{image_url_'.$img_handle.'}}'), @$attr['img_urls']['url'], $text);
						$text = str_replace(array('%image_'.$img_handle.'%', '{{image_'.$img_handle.'}}'), @$attr['img_urls']['tag'], $text);
					}
				}
			break;
			case 'youtube':
			case 'vimeo':
				//$text = str_replace(array('%image_url_'.$img_handle.'%', '{{image_url_'.$img_handle.'}}'), @$attr['img_urls'][$img_handle]['url'], $text);
				//$text = str_replace(array('%image_'.$img_handle.'%', '{{image_'.$img_handle.'}}'), @$attr['img_urls'][$img_handle]['tag'], $text);
			case 'twitter':
			case 'instagram':
			case 'flickr':
				foreach($img_sizes as $img_handle => $img_name){
					$text = str_replace(array('%image_url_'.$img_handle.'%', '{{image_url_'.$img_handle.'}}'), @$attr['img_urls'][$img_handle]['url'], $text);
					$text = str_replace(array('%image_'.$img_handle.'%', '{{image_'.$img_handle.'}}'), @$attr['img_urls'][$img_handle]['tag'], $text);
				}
			break;
		}
		
		return $text;
	}
	
	
	public function return_stream_data($stream_type, $additions = array()){
		$attr = array();
		$attr['title'] = '';
		$attr['excerpt'] = '';
		$attr['alias'] = '';
		$attr['content'] = '';
		$attr['link'] = '';
		$attr['date'] = '';
		$attr['date_modified'] = '';
		$attr['author_name'] = '';
		$attr['num_comments'] = '';
		$attr['catlist'] = '';
		$attr['taglist'] = '';
		$attr['likes'] = '';
		$attr['retweet_count'] = '';
		$attr['favorite_count'] = '';
		$attr['views'] = '';
		$attr['img_urls'] = array();
		
		$img_sizes = RevSliderBase::get_all_image_sizes($stream_type);
		
		switch($stream_type){
			case 'facebook':
				if($additions['fb_type'] == 'album'){
					$attr['title'] = RevSliderFunctions::getVal($this->postData, 'name');
					$attr['content'] = RevSliderFunctions::getVal($this->postData, 'name');
					$attr['link'] = RevSliderFunctions::getVal($this->postData, 'link');
					$attr['date'] = RevSliderFunctionsWP::convertPostDate(RevSliderFunctions::getVal($this->postData, 'created_time'), true);
					$attr['date_modified'] = RevSliderFunctionsWP::convertPostDate(RevSliderFunctions::getVal($this->postData, 'updated_time'), true);
					$author_name_raw = RevSliderFunctions::getVal($this->postData, 'from');
					$attr['author_name'] = $author_name_raw->name;
					$likes_data = RevSliderFunctions::getVal($this->postData, 'likes');
					$attr['likes'] = count(RevSliderFunctions::getVal($likes_data, 'data'));
					$fb_img = RevSliderFunctions::getVal($this->postData, 'images', array());
					foreach($fb_img as $key => $img){
						$attr['img_urls']['size-'.$key] = array(
							'url' => $img->source,
							'tag' => '<img src="'.$img->source.'" width="'.$img->width.'" height="'.$img->height.'" data-no-retina />'
						);
					}
				}else{
					$attr['title'] = RevSliderFunctions::getVal($this->postData, 'message');
					$attr['content'] = RevSliderFunctions::getVal($this->postData, 'message');
					$post_url = explode('_', RevSliderFunctions::getVal($this->postData, 'id'));
					$attr['link'] = 'https://www.facebook.com/'.$additions['fb_user_id'].'/posts/'.$post_url[1];
					$attr['date'] = RevSliderFunctionsWP::convertPostDate(RevSliderFunctions::getVal($this->postData, 'created_time'), true);
					$attr['date_modified'] = RevSliderFunctionsWP::convertPostDate(RevSliderFunctions::getVal($this->postData, 'updated_time'), true);
					$author_name_raw = RevSliderFunctions::getVal($this->postData, 'from');
					$attr['author_name'] = $author_name_raw->name;
					$likes_data = RevSliderFunctions::getVal($this->postData, 'likes');
					$attr['likes'] = count(RevSliderFunctions::getVal($likes_data, 'data'));
					$img = $this->get_facebook_timeline_image();
					$attr['img_urls'] = array(
						'url' => $img,
						'tag' => '<img src="'.$img.'" data-no-retina />'
					);
				}
			break;
			case 'twitter':
				$user = RevSliderFunctions::getVal($this->postData, 'user');
				$attr['title'] = RevSliderFunctions::getVal($this->postData, 'text');
				$attr['content'] = RevSliderFunctions::getVal($this->postData, 'text');
				$attr['link'] = 'https://twitter.com/'.$additions['twitter_user'].'/status/'.RevSliderFunctions::getVal($this->postData, 'id_str');
				$attr['date'] = RevSliderFunctionsWP::convertPostDate(RevSliderFunctions::getVal($this->postData, 'created_at'), true);
				$attr['author_name'] = RevSliderFunctions::getVal($user, 'screen_name');
				$attr['retweet_count'] = RevSliderFunctions::getVal($this->postData, 'retweet_count', '0');
				$attr['favorite_count'] = RevSliderFunctions::getVal($this->postData, 'favorite_count', '0');
				$image_url_array = RevSliderFunctions::getVal($this->postData, 'media');
				$image_url_large = RevSliderFunctions::getVal($image_url_array, 'large');
				$img = RevSliderFunctions::getVal($image_url_large, 'media_url', '');
				if($img == ''){
					$entities = RevSliderFunctions::getVal($this->postData, 'entities');
					$image_url_array = RevSliderFunctions::getVal($entities, 'media');
					if(is_array($image_url_array) && isset($image_url_array[0])){
						if(is_ssl()){
							$img = RevSliderFunctions::getVal($image_url_array[0], 'media_url_https');
						}else{
							$img = RevSliderFunctions::getVal($image_url_array[0], 'media_url');
						}
						
						$image_url_large = $image_url_array[0];
					}
				}
				if($img == ''){
					$entities = RevSliderFunctions::getVal($this->postData, 'extended_entities');
					$image_url_array = RevSliderFunctions::getVal($entities, 'media');
					if(is_array($image_url_array) && isset($image_url_array[0])){
						if(is_ssl()){
							$img = RevSliderFunctions::getVal($image_url_array[0], 'media_url_https');
						}else{
							$img = RevSliderFunctions::getVal($image_url_array[0], 'media_url');
						}
						
						$image_url_large = $image_url_array[0];
					}
				}
				if($img !== ''){
					$w = RevSliderFunctions::getVal($image_url_large, 'w', '');
					$h = RevSliderFunctions::getVal($image_url_large, 'h', '');
					$attr['img_urls']['large'] = array(
						'url' => $img,
						'tag' => '<img src="'.$img.'" width="'.$w.'" height="'.$h.'" data-no-retina />'
					);
				}
			break;
			case 'instagram':
				$caption = RevSliderFunctions::getVal($this->postData, 'caption');
				$user = RevSliderFunctions::getVal($this->postData, 'user');
				
				$attr['title'] = RevSliderFunctions::getVal($caption, 'text');
				$attr['content'] = RevSliderFunctions::getVal($caption, 'text');
				$attr['link'] = RevSliderFunctions::getVal($this->postData, 'link');
				$attr['date'] = RevSliderFunctionsWP::convertPostDate(RevSliderFunctions::getVal($this->postData, 'created_time'), true);
				$attr['author_name'] = RevSliderFunctions::getVal($user, 'username');
				
				$likes_raw = RevSliderFunctions::getVal($this->postData, 'likes');
				$attr['likes'] = RevSliderFunctions::getVal($likes_raw, 'count');
				
				$comments_raw = RevSliderFunctions::getVal($this->postData, 'comments');
				$attr['num_comments'] = RevSliderFunctions::getVal($comments_raw, 'count');
				
				$inst_img = RevSliderFunctions::getVal($this->postData, 'images', array());
				foreach($inst_img as $key => $img){
					$attr['img_urls'][$key] = array(
						'url' => $img->url,
						'tag' => '<img src="'.$img->url.'" width="'.$img->width.'" height="'.$img->height.'" data-no-retina />'
					);
				}
			break;
			case 'flickr':
				$attr['title'] = RevSliderFunctions::getVal($this->postData, 'title');
				$tc = RevSliderFunctions::getVal($this->postData, 'description');
				$attr['content'] = RevSliderFunctions::getVal($tc, '_content');
				$attr['date'] = RevSliderFunctionsWP::convertPostDate(RevSliderFunctions::getVal($this->postData, 'datetaken'));
				$attr['author_name'] = RevSliderFunctions::getVal($this->postData, 'ownername');
				$attr['link'] = 'http://flic.kr/p/'.$this->base_encode(RevSliderFunctions::getVal($this->postData, 'id'));
				$attr['views'] = RevSliderFunctions::getVal($this->postData, 'views');
				
				$attr['img_urls'] = @array(
					'square' 		=> 	array('url' => RevSliderFunctions::getVal($this->postData, 'url_sq'), 'tag' => '<img src="'.RevSliderFunctions::getVal($this->postData, 'url_sq').'" width="'.RevSliderFunctions::getVal($this->postData, 'width_sq').'" height="'.RevSliderFunctions::getVal($this->postData, 'height_sq').'" data-no-retina />'),
					'large-square' 	=> 	array('url' => RevSliderFunctions::getVal($this->postData, 'url_q'), 'tag' => '<img src="'.RevSliderFunctions::getVal($this->postData, 'url_q').'" width="'.RevSliderFunctions::getVal($this->postData, 'width_q').'" height="'.RevSliderFunctions::getVal($this->postData, 'height_q').'"  data-no-retina />'),
					'thumbnail' 	=> 	array('url' => RevSliderFunctions::getVal($this->postData, 'url_t'), 'tag' => '<img src="'.RevSliderFunctions::getVal($this->postData, 'url_t').'" width="'.RevSliderFunctions::getVal($this->postData, 'width_t').'" height="'.RevSliderFunctions::getVal($this->postData, 'height_t').'"  data-no-retina />'),
					'small' 		=> 	array('url' => RevSliderFunctions::getVal($this->postData, 'url_s'), 'tag' => '<img src="'.RevSliderFunctions::getVal($this->postData, 'url_s').'" width="'.RevSliderFunctions::getVal($this->postData, 'width_s').'" height="'.RevSliderFunctions::getVal($this->postData, 'height_s').'"  data-no-retina />'),
					'small-320' 	=> 	array('url' => RevSliderFunctions::getVal($this->postData, 'url_n'), 'tag' => '<img src="'.RevSliderFunctions::getVal($this->postData, 'url_n').'" width="'.RevSliderFunctions::getVal($this->postData, 'width_n').'" height="'.RevSliderFunctions::getVal($this->postData, 'height_n').'"  data-no-retina />'),
					'medium' 		=> 	array('url' => RevSliderFunctions::getVal($this->postData, 'url_m'), 'tag' => '<img src="'.RevSliderFunctions::getVal($this->postData, 'url_m').'" width="'.RevSliderFunctions::getVal($this->postData, 'width_m').'" height="'.RevSliderFunctions::getVal($this->postData, 'height_m').'"  data-no-retina />'),
					'medium-640' 	=> 	array('url' => RevSliderFunctions::getVal($this->postData, 'url_z'), 'tag' => '<img src="'.RevSliderFunctions::getVal($this->postData, 'url_z').'" width="'.RevSliderFunctions::getVal($this->postData, 'width_z').'" height="'.RevSliderFunctions::getVal($this->postData, 'height_z').'"  data-no-retina />'),
					'medium-800' 	=> 	array('url' => RevSliderFunctions::getVal($this->postData, 'url_c'), 'tag' => '<img src="'.RevSliderFunctions::getVal($this->postData, 'url_c').'" width="'.RevSliderFunctions::getVal($this->postData, 'width_c').'" height="'.RevSliderFunctions::getVal($this->postData, 'height_c').'"  data-no-retina />'),
					'large' 		=>	array('url' => RevSliderFunctions::getVal($this->postData, 'url_l'), 'tag' => '<img src="'.RevSliderFunctions::getVal($this->postData, 'url_l').'" width="'.RevSliderFunctions::getVal($this->postData, 'width_l').'" height="'.RevSliderFunctions::getVal($this->postData, 'height_l').'"  data-no-retina />'),
					'original'		=>	array('url' => RevSliderFunctions::getVal($this->postData, 'url_o'), 'tag' => '<img src="'.RevSliderFunctions::getVal($this->postData, 'url_o').'" width="'.RevSliderFunctions::getVal($this->postData, 'width_o').'" height="'.RevSliderFunctions::getVal($this->postData, 'height_o').'"  data-no-retina />')
				);
			break;
			case 'youtube':
				$snippet = RevSliderFunctions::getVal($this->postData, 'snippet');
				$attr['title'] = RevSliderFunctions::getVal($snippet, 'title');
				$attr['excerpt'] = RevSliderFunctions::getVal($snippet, 'description');
				$attr['content'] = RevSliderFunctions::getVal($snippet, 'description');
				$attr['date'] = RevSliderFunctionsWP::convertPostDate(RevSliderFunctions::getVal($snippet, 'publishedAt'));
				
				if($additions['yt_type'] == 'channel'){
					$link_raw = RevSliderFunctions::getVal($this->postData, 'id');
					$attr['link'] = RevSliderFunctions::getVal($link_raw, 'videoId');
					if($attr['link'] !== '') $attr['link'] = '//youtube.com/watch?v='.$attr['link'];
				}else{
					$link_raw = RevSliderFunctions::getVal($this->postData, 'resourceId');
					$attr['link'] = RevSliderFunctions::getVal($link_raw, 'videoId');
					if($attr['link'] !== '') $attr['link'] = '//youtube.com/watch?v='.$attr['link'];
				}
				
				$thumbs = RevSliderFunctions::getVal($snippet, 'thumbnails');
				$attr['img_urls'] = array();
				if(!empty($thumbs)){
					foreach($thumbs as $name => $vals){
						$attr['img_urls'][$name] = array(
							'url' => RevSliderFunctions::getVal($vals, 'url'),
						);
						switch($additions['yt_type']){
							case 'channel':
								$attr['img_urls'][$name]['tag'] = '<img src="'.RevSliderFunctions::getVal($vals, 'url').'" data-no-retina />';
							break;
							case 'playlist':
								$attr['img_urls'][$name]['tag'] = '<img src="'.RevSliderFunctions::getVal($vals, 'url').'" width="'.RevSliderFunctions::getVal($vals, 'width').'" height="'.RevSliderFunctions::getVal($vals, 'height').'" data-no-retina />';
							break;
						}
					}
				}
			break;
			case 'vimeo':
				$attr['title'] = RevSliderFunctions::getVal($this->postData, 'title');
				$attr['excerpt'] = RevSliderFunctions::getVal($this->postData, 'description');
				$attr['content'] = RevSliderFunctions::getVal($this->postData, 'description');
				$attr['date'] = RevSliderFunctionsWP::convertPostDate(RevSliderFunctions::getVal($this->postData, 'upload_date'));
				$attr['likes'] = RevSliderFunctions::getVal($this->postData, 'stats_number_of_likes');
				$attr['views'] = RevSliderFunctions::getVal($this->postData, 'stats_number_of_plays');
				$attr['num_comments'] = RevSliderFunctions::getVal($this->postData, 'stats_number_of_comments');
				$attr['link'] = RevSliderFunctions::getVal($this->postData, 'url');
				$attr['author_name'] = RevSliderFunctions::getVal($this->postData, 'user_name');
				
				$attr['img_urls'] = array();
				if(!empty($img_sizes)){
					foreach($img_sizes as $name => $vals){
						$attr['img_urls'][$name] = array(
							'url' => RevSliderFunctions::getVal($this->postData, $name),
							'tag' => '<img src="'.RevSliderFunctions::getVal($this->postData, $name).'" data-no-retina />'
						);
					}
				}
				
			break;
			
		}
		
		return $attr;
	}
	
	
	public function find_biggest_photo($image_urls, $wanted_size, $avail_sizes){
		if(!$this->isEmpty(@$image_urls[$wanted_size])) return $image_urls[$wanted_size];	
		$wanted_size_pos = array_search($wanted_size, $avail_sizes);
		for ($i=$wanted_size_pos; $i < 7; $i++) { 
			if(!$this->isEmpty(@$image_urls[$avail_sizes[$i]])) return $image_urls[$avail_sizes[$i]];	
		}
		for ($i=$wanted_size_pos; $i >= 0 ; $i--) { 
			if(!$this->isEmpty(@$image_urls[$avail_sizes[$i]])) return $image_urls[$avail_sizes[$i]];	
		}
	}

	
	public function isEmpty($stringOrArray) {
	    if(is_array($stringOrArray)) {
	        foreach($stringOrArray as $value) {
	            if(!$this->isEmpty($value)) {
	                return false;
	            }
	        }
	        return true;
	    }

	    return !strlen($stringOrArray);  // this properly checks on empty string ('')
	}
	
	
	public function get_facebook_timeline_image(){
		$object_id = RevSliderFunctions::getVal($this->postData, 'object_id', '');
		$picture = RevSliderFunctions::getVal($this->postData, 'picture', '');
		if(!empty($object_id))
			return 'https://graph.facebook.com/'.RevSliderFunctions::getVal($this->postData, 'object_id', '').'/picture';//$photo->picture;
		elseif (!empty($picture)) {
			
			$image_url = $this->decode_facebook_url(RevSliderFunctions::getVal($this->postData, 'picture', ''));
			$image_url = parse_str(parse_url($image_url, PHP_URL_QUERY), $array);
			$image_url = explode('&', $array['url']);
			return $image_url[0];
		}
		return '';
	}
	
	
	private function decode_facebook_url($url) {
		$url = str_replace('u00253A',':',$url);
		$url = str_replace('\u00255C\u00252F','/',$url);
		$url = str_replace('u00252F','/',$url);
		$url = str_replace('u00253F','?',$url);
		$url = str_replace('u00253D','=',$url);
		$url = str_replace('u002526','&',$url);
		return $url;
	}
	
	/* Provider:  - *
	 * Encode the flickr ID for URL (base58)
	 *
	 * @since    5.0
	 * @param    string    $num 	flickr photo id
	 */
	private function base_encode($num, $alphabet='123456789abcdefghijkmnopqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ') {
		$base_count = strlen($alphabet);
		$encoded = '';
		while ($num >= $base_count) {
			$div = $num/$base_count;
			$mod = ($num-($base_count*intval($div)));
			$encoded = $alphabet[$mod] . $encoded;
			$num = intval($div);
		}
		if ($num) $encoded = $alphabet[$num] . $encoded;
		return $encoded;
	}
	
	/* Provider:  - *
	 * 
	 * init slide by post data
	 */
	public function initByPostData($postData, RevSlide $slideTemplate, $sliderID){
		
		$this->postData = $postData;
		
		$postID = $postData["ID"];
		
		$slideTemplateID = get_post_meta($postID, 'slide_template', true);
		if($slideTemplateID == '') $slideTemplateID = 'default';
		
		if(!empty($slideTemplateID) && is_numeric($slideTemplateID)){
				//init by local template, if fail, init by global (slider) template
			try{
				
				$slideTemplateLocal = new RevSlide();
				$slideTemplateLocal->initByID($slideTemplateID);
				$this->initBySlide($slideTemplateLocal);
			}
			catch(Exception $e){
				$this->initBySlide($slideTemplate);
			}
			
		}else{
			//init by global template
			$this->initBySlide($slideTemplate);
		}
		
		//set some slide params
		$this->id = $postID;
		$this->params["title"] = RevSliderFunctions::getVal($postData, "post_title");
		
		if(@$this->params['enable_link'] == "true" && @$this->params['link_type'] == "regular"){
			$link = get_permalink($postID);
			$this->params["link"] = str_replace(array("%link%", '{{link}}'), $link, $this->params["link"]);
			$this->params["link"] = str_replace('-', '_REVSLIDER_', $this->params["link"]);
			
			//process meta tags:
			$arrMatches = array();
			preg_match('/%meta:\w+%/', $this->params["link"], $arrMatches);
			
			foreach($arrMatches as $match){
				$meta = str_replace("%meta:", "", $match);
				$meta = str_replace("%","",$meta);
				$meta = str_replace('_REVSLIDER_', '-', $meta);
				$metaValue = get_post_meta($postID,$meta,true);
				$this->params["link"] = str_replace($match,$metaValue,$this->params["link"]);
			}
			
			
			$arrMatches = array();
			preg_match('/{{meta:\w+}}/', $this->params["link"], $arrMatches);
			
			foreach($arrMatches as $match){
				$meta = str_replace("{{meta:", "", $match);
				$meta = str_replace("}}","",$meta);
				$meta = str_replace('_REVSLIDER_', '-', $meta);
				$metaValue = get_post_meta($postID,$meta,true);
				$this->params["link"] = str_replace($match,$metaValue,$this->params["link"]);
			}
			
			$this->params["link"] = str_replace('_REVSLIDER_','-',$this->params["link"]);
			
		}
		
		$status = $postData["post_status"];
		
		if($status == "publish")
			$this->params["state"] = "published";
		else
			$this->params["state"] = "unpublished";
		
		if($this->params["background_type"] == 'image'){ //if image is choosen, use featured image as background
			//set image
			$thumbID = RevSliderFunctionsWP::getPostThumbID($postID);
			
			if(!empty($thumbID))
				$this->setImageByImageID($thumbID);
			
		}
		
		//replace placeholders in layers:
		$this->setLayersByPostData($postData, $sliderID);
	}
	
	
	/* Provider:  - *
	 * 
	 * replace layer placeholders by post data
	 */
	private function setLayersByPostData($postData,$sliderID){
		
		$postID = $postData["ID"];
		
		$attr = array();
		$attr['title'] = RevSliderFunctions::getVal($postData, "post_title");
		
		$excerpt_limit = $this->getSliderParam($sliderID,"excerpt_limit",55,RevSlider::VALIDATE_NUMERIC);
		$excerpt_limit = (int)$excerpt_limit;
		$attr['excerpt'] = RevSliderFunctionsWP::getExcerptById($postID, $excerpt_limit);
		
		$attr['alias'] = RevSliderFunctions::getVal($postData, "post_name");
		
		$attr['content'] = RevSliderFunctions::getVal($postData, "post_content");
		
		$attr['link'] = get_permalink($postID);
		
		$postDate = RevSliderFunctions::getVal($postData, "post_date_gmt");
		$attr['postDate'] = RevSliderFunctionsWP::convertPostDate($postDate);
		
		$dateModified = RevSliderFunctions::getVal($postData, "post_modified");
		$attr['dateModified'] = RevSliderFunctionsWP::convertPostDate($dateModified);
		
		$authorID = RevSliderFunctions::getVal($postData, "post_author");
		$attr['authorName'] = RevSliderFunctionsWP::getUserDisplayName($authorID);
		
		$postCatsIDs = $postData["post_category"];
		$attr['catlist'] = RevSliderFunctionsWP::getCategoriesHtmlList($postCatsIDs);
		$attr['taglist'] = RevSliderFunctionsWP::getTagsHtmlList($postID);
		
		$ptid = get_post_thumbnail_id($postID);
		
		$img_sizes = RevSliderBase::get_all_image_sizes();
		$attr['img_urls'] = array();
		foreach($img_sizes as $img_handle => $img_name){
			$featured_image_url = wp_get_attachment_image_src($ptid, $img_handle);
			if($featured_image_url !== false){
				$attr['img_urls'][$img_handle] = array(
					'url' => $featured_image_url[0],
					'tag' => '<img src="'.$featured_image_url[0].'" width="'.$featured_image_url[1].'" height="'.$featured_image_url[2].'" data-no-retina />'
				);
			}
		}
		
		$numComments = RevSliderFunctions::getVal($postData, "comment_count");
		
		foreach($this->arrLayers as $key=>$layer){
			
			$text = RevSliderFunctions::getVal($layer, "text");
			$text = apply_filters('revslider_mod_meta', $text, $postID, $postData); //option to add your own filter here to modify meta to your likings
			
			$text = $this->set_post_data($text, $attr, $postID);
			
			$layer["text"] = $text;
			
			$all_actions = RevSliderFunctions::getVal($layer, 'layer_action', array());
			if(!empty($all_actions)){
				$a_image_link = RevSliderFunctions::cleanStdClassToArray(RevSliderFunctions::getVal($all_actions, 'image_link', array()));
				if(!empty($a_image_link)){
					foreach($a_image_link as $ik => $ilink){
						$ilink = $this->set_post_data($ilink, $attr, $postID);
						$a_image_link[$ik] = $ilink;
					}
					$layer['layer_action']->image_link = $a_image_link;
				}
			}
			
			$this->arrLayers[$key] = $layer;
		}
		
		for($mi=1;$mi<=10;$mi++){ //set params to the post data
			$pa = $this->getParam('params_'.$mi, '');
			$pa = $this->set_post_data($pa, $attr, $postID);
			$this->setParam('params_'.$mi, $pa);
		}
		
	}
	
	
	public function set_post_data($text, $attr, $post_id){
		$img_sizes = RevSliderBase::get_all_image_sizes();
		
		$text = str_replace(array('%title%', '{{title}}'), @$attr['title'], $text);
		$text = str_replace(array('%excerpt%', '{{excerpt}}'), @$attr['excerpt'], $text);
		$text = str_replace(array('%alias%', '{{alias}}'), @$attr['alias'], $text);
		$text = str_replace(array('%content%', '{{content}}'), @$attr['content'], $text);
		$text = str_replace(array('%link%', '{{link}}'), @$attr['link'], $text);
		$text = str_replace(array('%date%', '{{date}}'), @$attr['postDate'], $text);
		$text = str_replace(array('%date_modified%', '{{date_modified}}'), @$attr['dateModified'], $text);
		$text = str_replace(array('%author_name%', '{{author_name}}'), @$attr['authorName'], $text);
		$text = str_replace(array('%num_comments%', '{{num_comments}}'), @$attr['numComments'], $text);
		$text = str_replace(array('%catlist%', '{{catlist}}'), @$attr['catlist'], $text);
		$text = str_replace(array('%taglist%', '{{taglist}}'), @$attr['taglist'], $text);
		
		foreach($img_sizes as $img_handle => $img_name){
			$text = str_replace(array('%featured_image_url_'.$img_handle.'%', '{{featured_image_url_'.$img_handle.'}}'), @$attr['img_urls'][$img_handle]['url'], $text);
			$text = str_replace(array('%featured_image_'.$img_handle.'%', '{{featured_image_'.$img_handle.'}}'), @$attr['img_urls'][$img_handle]['tag'], $text);
		}
		
		//process meta tags:
		$text = str_replace('-', '_REVSLIDER_', $text);
		
		$arrMatches = array();
		preg_match_all('/%meta:\w+%/', $text, $arrMatches);

		foreach($arrMatches as $matched){
			
			foreach($matched as $match) {
			
				$meta = str_replace("%meta:", "", $match);
				$meta = str_replace("%","",$meta);
				$meta = str_replace('_REVSLIDER_', '-', $meta);
				$metaValue = get_post_meta($post_id,$meta,true);
				
				$text = str_replace($match,$metaValue,$text);	
			}
		}
		
		$arrMatches = array();
		preg_match_all('/{{meta:\w+}}/', $text, $arrMatches);

		foreach($arrMatches as $matched){
			foreach($matched as $match) {
				$meta = str_replace("{{meta:", "", $match);
				$meta = str_replace("}}","",$meta);
				$meta = str_replace('_REVSLIDER_', '-', $meta);
				$metaValue = get_post_meta($post_id,$meta,true);
				
				$text = str_replace($match,$metaValue,$text);	
			}
		}
		
		$text = str_replace('_REVSLIDER_','-',$text);
		
		//replace event's template
		if(RevSliderEventsManager::isEventsExists()){
			$eventData = RevSliderEventsManager::getEventPostData($post_id);
			if(!empty($eventData)){
				foreach($eventData as $eventKey=>$eventValue){
					$eventPlaceholder = "%event_".$eventKey."%";
					$eventPlaceholderNew = "{{event_".$eventKey."}}";
					if($eventKey == 'start_date' || $eventKey == 'end_date') $eventValue = RevSliderFunctionsWP::convertPostDate($eventValue);
					$text = str_replace(array($eventPlaceholder, $eventPlaceholderNew), $eventValue , $text);
				}
			}
		}
		
		return $text;
	}
	
	
	/* Provider:  - *
	 * init the slider by id
	 */
	public function initByID($slideid){
		try{
			if(strpos($slideid, 'static_') !== false){
				$this->static_slide = true;
				$sliderID = str_replace('static_', '', $slideid);
				
				RevSliderFunctions::validateNumeric($sliderID,"Slider ID");
				
				$sliderID = $this->db->escape($sliderID);
				$record = $this->db->fetch(RevSliderGlobals::$table_static_slides,"slider_id=$sliderID");
				
				if(empty($record)){
					try{
						//create a new static slide for the Slider and then use it
						$slide_id = $this->createSlide($sliderID,"",true);
						
						$record = $this->db->fetch(RevSliderGlobals::$table_static_slides,"slider_id=$sliderID");
						
						$this->initByData($record[0]);
					}catch(Exception $e){}
				}else{
					$this->initByData($record[0]);
				}
			}else{
				RevSliderFunctions::validateNumeric($slideid,"Slide ID");
				$slideid = $this->db->escape($slideid);
				$record = $this->db->fetchSingle(RevSliderGlobals::$table_slides,"id=$slideid");
				
				$this->initByData($record);
			}
		}catch(Exception $e){
			$message = $e->getMessage();
			echo $message;
			exit;
		}
	}
	
	
	/* Provider:  - *
	 * Check if Slide Exists with given ID
	 * @since: 5.0
	 */
	public static function isSlideByID($slideid){
		$db = new RevSliderDB();
		try{
			if(strpos($slideid, 'static_') !== false){
				
				$sliderID = str_replace('static_', '', $slideid);
				
				RevSliderFunctions::validateNumeric($sliderID,"Slider ID");
				
				$sliderID = $db->escape($sliderID);
				$record = $db->fetch(RevSliderGlobals::$table_static_slides,"slider_id=$sliderID");
				
				if(empty($record)) return false;
				
				return true;
				
			}else{
				
				$slideid = $db->escape($slideid);
				$record = $db->fetchSingle(RevSliderGlobals::$table_slides,"id=$slideid");
				
				if(empty($record)) return false;
				
				return true;
				
			}
		}catch(Exception $e){
			return false;
		}
	}
	
	
	/* Provider:  - *
	 * 
	 * init the slider by id
	 */
	public function initByStaticID($slideid){
	
		RevSliderFunctions::validateNumeric($slideid,"Slide ID");
		$slideid = $this->db->escape($slideid);
		$record = $this->db->fetchSingle(RevSliderGlobals::$table_static_slides,"id=$slideid");
		
		$this->initByData($record);
	}
	
	
	/* Provider:  - *
	 * 
	 * getStaticSlide
	 */
	public function getStaticSlideID($sliderID){
		
		RevSliderFunctions::validateNumeric($sliderID,"Slider ID");
		
		$sliderID = $this->db->escape($sliderID);
		$record = $this->db->fetch(RevSliderGlobals::$table_static_slides,"slider_id=$sliderID");
		
		if(empty($record)){
			return false;
		}else{
			return $record[0]['id'];
		}
	}
	
	
	/* Provider:  - *
	 * 
	 * set slide image by image id
	 */
	private function setImageByImageID($imageID){
		
		$imgResolution = RevSliderFunctions::getVal($this->params, 'image_source_type', 'full');
		
		$this->imageID = $imageID;
		
		$this->imageUrl = RevSliderFunctionsWP::getUrlAttachmentImage($imageID, $imgResolution);
		$this->imageThumb = RevSliderFunctionsWP::getUrlAttachmentImage($imageID,RevSliderFunctionsWP::THUMB_MEDIUM);
		
		if(empty($this->imageUrl))
			return(false);
		
		$this->params["background_type"] = "image";
		
		if(is_ssl()){
			$this->imageUrl = str_replace("http://", "https://", $this->imageUrl);
		}
		
		$this->imageFilepath = RevSliderFunctionsWP::getImagePathFromURL($this->imageUrl);
		$realPath = RevSliderFunctionsWP::getPathContent().$this->imageFilepath;
		
		if(file_exists($realPath) == false || is_file($realPath) == false)
			$this->imageFilepath = "";
		
		$this->imageFilename = basename($this->imageUrl);
	}
	
	
	/* Provider:  - *
	 * 
	 * set children array
	 */
	public function setArrChildren($arrChildren){
		$this->arrChildren = $arrChildren;
	}
	
	
	/* Provider:  - *
	 * 
	 * get children array
	 */
	public function getArrChildren(){
		
		$this->validateInited();
		
		if($this->arrChildren === null){
			$slider = new RevSlider();
			$slider->initByID($this->sliderID);
			$this->arrChildren = $slider->getArrSlideChildren($this->id);
		}
		
		return($this->arrChildren);				
	}
	
	/* Provider:  - *
	 * 
	 * return if the slide from post
	 */
	public function isFromPost(){
		return !empty($this->postData);
	}
	
	
	/* Provider:  - *
	 * 
	 * get post data
	 */
	public function getPostData(){
		return($this->postData);
	}
	
	
	/* Provider:  - *
	 * 
	 * get children array as is
	 */
	public function getArrChildrenPure(){
		return($this->arrChildren);
	}
	
	/* Provider:  - *
	 * 
	 * return if the slide is parent slide
	 */
	public function isParent(){
		$parentID = $this->getParam("parentid","");
		return(!empty($parentID));
	}
	
	
	/* Provider:  - *
	 * 
	 * get slide language
	 */
	public function getLang(){
		$lang = $this->getParam("lang","all");
		return($lang);
	}
	
	/* Provider:  - *
	 * 
	 * return parent slide. If the slide is parent, return this slide.
	 */
	public function getParentSlide(){
		$parentID = $this->getParam("parentid","");
		if(empty($parentID))
			return($this);
			
		$parentSlide = new RevSlide();
		$parentSlide->initByID($parentID);
		return($parentSlide);
	}
	
	/* Provider:  - *
	 * return parent slide id
	 * @since: 5.0
	 */
	public function getParentSlideID(){
		$parentID = $this->getParam("parentid","");
		
		return $parentID;
	}
	
	/* Provider:  - *
	 * 
	 * get array of children id's
	 */
	public function getArrChildrenIDs(){
		$arrChildren = $this->getArrChildren();
		$arrChildrenIDs = array();
		foreach($arrChildren as $child){
			$childID = $child->getID();
			$arrChildrenIDs[] = $childID;
		}
		
		return($arrChildrenIDs);
	}
	
	
	/* Provider:  - *
	 * 
	 * get array of children array and languages, the first is current language.
	 */
	public function getArrChildrenLangs($includeParent = true){
		$this->validateInited();
		$slideID = $this->id;
		
		if($includeParent == true){
			$lang = $this->getParam("lang","all");
			$arrOutput = array();
			$arrOutput[] = array("slideid"=>$slideID,"lang"=>$lang,"isparent"=>true);
		}
		
		$arrChildren = $this->getArrChildren();
		
		foreach($arrChildren as $child){
			$childID = $child->getID();
			$childLang = $child->getParam("lang","all");
			$arrOutput[] = array("slideid"=>$childID,"lang"=>$childLang,"isparent"=>false);
		}
		
		return($arrOutput);
	}
	
	/* Provider:  - *
	 * 
	 * get children language codes (including current slide lang code)
	 */
	public function getArrChildLangCodes($includeParent = true){
		$arrLangsWithSlideID = $this->getArrChildrenLangs($includeParent);
		$arrLangCodes = array();
		foreach($arrLangsWithSlideID as $item){
			$lang = $item["lang"];
			$arrLangCodes[$lang] = $lang;
		}
		
		return($arrLangCodes);
	}
	
	
	/* Provider:  - *
	 * get slide ID
	 */
	public function getID(){
		return($this->id);
	}
	
	
	/* Provider:  - *
	 * get slide order
	 */
	public function getOrder(){
		$this->validateInited();
		return($this->slideOrder);
	}
	
	
	/* Provider:  - *
	 * get layers in json format
	 */
	public function getLayers(){
		$this->validateInited();
		return($this->arrLayers);
	}
	
	/* Provider:  - *
	 * get layers in json format
	 * since: 5.0
	 */
	public function getLayerID_by_unique_id($unique_id){
		$this->validateInited();
		
		foreach($this->arrLayers as $l){
			
			$uid = RevSliderFunctions::getVal($l, 'unique_id');
			if($uid == $unique_id){
				return RevSliderFunctions::getVal($l, 'attrID');
			}
		}
		
		return '';
	}
	
	
	/* Provider:  - *
	 * save layers to the database
	 * @since: 5.0
	 */
	public function saveLayers(){
		$this->validateInited();
		$table = ($this->static_slide) ? RevSliderGlobals::$table_static_slides : RevSliderGlobals::$table_slides;
		$this->db->update($table, array('layers' => json_encode($this->arrLayers)),array('id'=>$this->id));
	}
	
	/* Provider:  - *
	 * save params to the database
	 * @since: 5.0
	 */
	public function saveParams(){
		$this->validateInited();
		$table = ($this->static_slide) ? RevSliderGlobals::$table_static_slides : RevSliderGlobals::$table_slides;
		$this->db->update($table, array('params' => json_encode($this->params)),array('id'=>$this->id));
	}
	
	
	/* Provider:  - *
	 * modify layer links for export
	 */
	public function getLayersForExport($useDummy = false){
		$this->validateInited();
		$arrLayersNew = array();
		foreach($this->arrLayers as $key=>$layer){
			$imageUrl = RevSliderFunctions::getVal($layer, "image_url");
			if(!empty($imageUrl))
				$layer["image_url"] = RevSliderFunctionsWP::getImagePathFromURL($layer["image_url"]);
			
			$arrLayersNew[] = $layer;
		}
		
		return($arrLayersNew);
	}
	
	
	/* Provider:  - *
	 * get params for export
	 */
	public function getParamsForExport(){
		$arrParams = $this->getParams();
		$urlImage = RevSliderFunctions::getVal($arrParams, "image");
		if(!empty($urlImage))
			$arrParams["image"] = RevSliderFunctionsWP::getImagePathFromURL($urlImage);
		
		return($arrParams);
	}
	
	
	/* Provider:  - *
	 * normalize layers text, and get layers
	 */
	public function getLayersNormalizeText(){
		$arrLayersNew = array();
		foreach ($this->arrLayers as $key=>$layer){
			$text = $layer["text"];
			$text = addslashes($text);
			$layer["text"] = $text;
			$arrLayersNew[] = $layer;
		}
		
		return($arrLayersNew);
	}
	

	/* Provider:  - *
	 * get slide params
	 */
	public function getParams(){
		$this->validateInited();
		return($this->params);
	}
	

	/* Provider:  - *
	 * get slide settings
	 * @since: 5.0
	 */
	public function getSettings(){
		$this->validateInited();
		return($this->settings);
	}

	
	/* Provider:  - *
	 * get parameter from params array. if no default, then the param is a must!
	 */
	function getParam($name,$default=null){
		
		if($default === null){
			if(!array_key_exists($name, $this->params))
				RevSliderFunctions::throwError("The param <b>$name</b> not found in slide params.");
			$default = "";
		}
		
		return RevSliderFunctions::getVal($this->params, $name,$default);
	}
	
	
	/* Provider:  - *
	 * set parameter
	 * @since: 5.0
	 */
	public function setParam($name, $value){
		
		$this->params[$name] = $value;
		
	}
	
	
	/* Provider:  - *
	 * get image filename
	 */
	public function getImageFilename(){
		return($this->imageFilename);
	}
	
	
	/* Provider:  - *
	 * get image filepath
	 */
	public function getImageFilepath(){
		return($this->imageFilepath);
	}
	
	
	/* Provider:  - *
	 * get image url
	 */
	public function getImageUrl(){
		
		return($this->imageUrl);
	}
	
	
	/* Provider:  - *
	 * get image id
	 */
	public function getImageID(){
		return($this->imageID);
	}
	
	/* Provider:  - *
	 * get thumb url
	 */
	public function getThumbUrl(){
		$thumbUrl = $this->imageUrl;
		if(!empty($this->imageThumb))
			$thumbUrl = $this->imageThumb;
			
		return($thumbUrl);
	}
	
	
	/* Provider:  - *
	 * get the slider id
	 */
	public function getSliderID(){
		return($this->sliderID);
	}
	
	/* Provider:  - *
	 * get slider param
	 */
	private function getSliderParam($sliderID,$name,$default,$validate=null){
		
		if(empty($this->slider)){
			$this->slider = new RevSlider();
			$this->slider->initByID($sliderID);
		}
		
		$param = $this->slider->getParam($name,$default,$validate);
		
		return($param);
	}
	
	
	/* Provider:  - *
	 * validate that the slider exists
	 */
	private function validateSliderExists($sliderID){
		$slider = new RevSlider();
		$slider->initByID($sliderID);
	}
	
	/* Provider:  - *
	 * validate that the slide is inited and the id exists.
	 */
	private function validateInited(){
		if(empty($this->id))
			RevSliderFunctions::throwError("The slide is not initialized!!!");
	}
	
	
	/* Provider:  - *
	 * create the slide (from image)
	 */
	public function createSlide($sliderID,$obj="",$static = false){
		
		$imageID = null;
		
		if(is_array($obj)){
			$urlImage = RevSliderFunctions::getVal($obj, "url");
			$imageID = RevSliderFunctions::getVal($obj, "id");
		}else{
			$urlImage = $obj;
		}
		
		//get max order
		$slider = new RevSlider();
		$slider->initByID($sliderID);
		$maxOrder = $slider->getMaxOrder();
		$order = $maxOrder+1;
		
		$params = array();
		if(!empty($urlImage)){
			$params["background_type"] = "image";
			$params["image"] = $urlImage;
			if(!empty($imageID))
				$params["image_id"] = $imageID;
				
		}else{	//create transparent slide
			
			$params["background_type"] = "trans";
		}
			
		$jsonParams = json_encode($params);
		
		
		$arrInsert = array(	
						"params"=>$jsonParams,
						"slider_id"=>$sliderID,
						"layers"=>""
					);
					
		if(!$static)
			$arrInsert["slide_order"] = $order;
		
		if(!$static)
			$slideID = $this->db->insert(RevSliderGlobals::$table_slides, $arrInsert);
		else
			$slideID = $this->db->insert(RevSliderGlobals::$table_static_slides, $arrInsert);
		
		return($slideID);
	}
	
	/* Provider:  - *
	 * 
	 * update slide image from data
	 */
	public function updateSlideImageFromData($data){
		
		$sliderID = RevSliderFunctions::getVal($data, "slider_id");
		$slider = new RevSlider();
		$slider->initByID($sliderID);
		
		$slideID = RevSliderFunctions::getVal($data, "slide_id");
		$urlImage = RevSliderFunctions::getVal($data, "url_image");
		RevSliderFunctions::validateNotEmpty($urlImage);
		$imageID = RevSliderFunctions::getVal($data, "image_id");
		if($slider->isSlidesFromPosts()){
			
			if(!empty($imageID))
				RevSliderFunctionsWP::updatePostThumbnail($slideID, $imageID);
			
		}elseif($slider->isSlidesFromStream() !== false){
			//do nothing
		}else{
			$this->initByID($slideID);
			
			$arrUpdate = array();
			$arrUpdate["image"] = $urlImage;			
			$arrUpdate["image_id"] = $imageID;
			
			$this->updateParamsInDB($arrUpdate);
		}
		
		return($urlImage);
	}
	
	
	
	/* Provider:  - *
	 * 
	 * update slide parameters in db
	 */
	protected function updateParamsInDB($arrUpdate = array()){
		$this->validateInited();
		$this->params = array_merge($this->params,$arrUpdate);
		$jsonParams = json_encode($this->params);
		
		$arrDBUpdate = array("params"=>$jsonParams);
		
		$this->db->update(RevSliderGlobals::$table_slides,$arrDBUpdate,array("id"=>$this->id));
	}
	
	
	/* Provider:  - *
	 * 
	 * update current layers in db
	 */
	protected function updateLayersInDB($arrLayers = null){
		$this->validateInited();
		
		if($arrLayers === null)
			$arrLayers = $this->arrLayers;
			
		$jsonLayers = json_encode($arrLayers);
		$arrDBUpdate = array("layers"=>$jsonLayers);
		
		$this->db->update(RevSliderGlobals::$table_slides,$arrDBUpdate,array("id"=>$this->id));
	} 
	
	
	/* Provider:  - *
	 * 
	 * update parent slideID 
	 */
	public function updateParentSlideID($parentID){
		$arrUpdate = array();
		$arrUpdate["parentid"] = $parentID;
		$this->updateParamsInDB($arrUpdate);
	}
	
	
	/* Provider:  - *
	 * 
	 * sort layers by order
	 */
	private function sortLayersByOrder($layer1,$layer2){
		$layer1 = (array)$layer1;
		$layer2 = (array)$layer2;
		
		$order1 = RevSliderFunctions::getVal($layer1, "order",1);
		$order2 = RevSliderFunctions::getVal($layer2, "order",2);
		if($order1 == $order2)
			return(0);
		
		return($order1 > $order2);
	}
	
	
	/* Provider:  - *
	 * 
	 * go through the layers and fix small bugs if exists
	 */
	private function normalizeLayers($arrLayers){
		
		usort($arrLayers,array($this,"sortLayersByOrder"));
		
		$arrLayersNew = array();
		foreach ($arrLayers as $key=>$layer){
			
			$layer = (array)$layer;
			
			//set type
			$type = RevSliderFunctions::getVal($layer, "type","text");
			$layer["type"] = $type;
			
			//normalize position:
			if(is_object($layer["left"])){
				foreach($layer["left"] as $key => $val){
					$layer["left"]->$key = round($val);
				}
			}else{
				$layer["left"] = round($layer["left"]);
			}
			if(is_object($layer["top"])){
				foreach($layer["top"] as $key => $val){
					$layer["top"]->$key = round($val);
				}
			}else{
				$layer["top"] = round($layer["top"]);
			}
			
			//unset order
			unset($layer["order"]);
			
			//modify text
			$layer["text"] = stripcslashes($layer["text"]);
			
			$arrLayersNew[] = $layer;
		}
		
		return($arrLayersNew);
	}  
	
	
	
	/* Provider:  - *
	 * 
	 * normalize params
	 */
	private function normalizeParams($params){
		
		$urlImage = RevSliderFunctions::getVal($params, "image_url");
		
		//init the id if absent
		$params["image_id"] = RevSliderFunctions::getVal($params, "image_id");
		
		$params["image"] = $urlImage;
		unset($params["image_url"]);
		
		if(isset($params["video_description"]))
			$params["video_description"] = RevSliderFunctions::normalizeTextareaContent($params["video_description"]);
		
		return($params);
	}
	
	
	/* Provider:  - *
	 * 
	 * update slide from data
	 * @param $data
	 */
	public function updateSlideFromData($data){
		
		$slideID = RevSliderFunctions::getVal($data, "slideid");
		$this->initByID($slideID);						
		
		//treat params
		$params = RevSliderFunctions::getVal($data, "params");
		$params = $this->normalizeParams($params);
		
		//preserve old data that not included in the given data
		$params = array_merge($this->params,$params);
		
		//treat layers
		$layers = RevSliderFunctions::getVal($data, "layers");
		
		if(gettype($layers) == "string"){
			$layersStrip = stripslashes($layers);
			$layersDecoded = json_decode($layersStrip);
			if(empty($layersDecoded))
				$layersDecoded = json_decode($layers);
			
			$layers = RevSliderFunctions::convertStdClassToArray($layersDecoded);
		}
		
		if(empty($layers) || gettype($layers) != "array")
			$layers = array();
		
		$layers = $this->normalizeLayers($layers);
		
		
		$settings = RevSliderFunctions::getVal($data, "settings");
		
		$arrUpdate = array();
		$arrUpdate["layers"] = json_encode($layers);
		$arrUpdate["params"] = json_encode($params);
		$arrUpdate["settings"] = json_encode($settings);
		
		$this->db->update(RevSliderGlobals::$table_slides,$arrUpdate,array("id"=>$this->id));
		
		//RevSliderOperations::updateDynamicCaptions();
	}
	
	
	/* Provider:  - *
	 * 
	 * update slide from data
	 * @param $data
	 */
	public function updateStaticSlideFromData($data){
		
		$slideID = RevSliderFunctions::getVal($data, "slideid");
		$this->initByStaticID($slideID);
		
		//treat layers
		$layers = RevSliderFunctions::getVal($data, "layers");
		
		if(gettype($layers) == "string"){
			$layersStrip = stripslashes($layers);
			$layersDecoded = json_decode($layersStrip);
			if(empty($layersDecoded))
				$layersDecoded = json_decode($layers);
			
			$layers = RevSliderFunctions::convertStdClassToArray($layersDecoded);
		}
		
		if(empty($layers) || gettype($layers) != "array")
			$layers = array();
		
		$layers = $this->normalizeLayers($layers);
		
		$settings = RevSliderFunctions::getVal($data, "settings");
		
		
		$arrUpdate = array();
		$arrUpdate["layers"] = json_encode($layers);
		$arrUpdate["settings"] = json_encode($settings);
		
		$this->db->update(RevSliderGlobals::$table_static_slides,$arrUpdate,array("id"=>$this->id));
		
		//RevSliderOperations::updateDynamicCaptions();
	}
	
	
	
	/* Provider:  - *
	 * 
	 * delete slide by slideid
	 */
	public function deleteSlide(){
		$this->validateInited();
		
		$this->db->delete(RevSliderGlobals::$table_slides,"id='".$this->id."'");
	}
	
	
	/* Provider:  - *
	 * 
	 * delete slide children
	 */
	public function deleteChildren(){
		$this->validateInited();
		$arrChildren = $this->getArrChildren();
		foreach($arrChildren as $child)
			$child->deleteSlide();
	}
	
	
	/* Provider:  - *
	 * 
	 * delete slide from data
	 */
	public function deleteSlideFromData($data){
		
		$sliderID = RevSliderFunctions::getVal($data, "sliderID");
		$slider = new RevSlider();
		$slider->initByID($sliderID); 			
		
		//delete slide
		$slideID = RevSliderFunctions::getVal($data, "slideID");
		$this->initByID($slideID);
		$this->deleteChildren();
		$this->deleteSlide();
		
	}
	
	
	/* Provider:  - *
	 * set params from client
	 */
	public function setParams($params){
		$params = $this->normalizeParams($params);
		$this->params = $params;
	}
	
	
	/* Provider:  - *
	 * 
	 * set layers from client
	 */
	public function setLayers($layers){
		$layers = $this->normalizeLayers($layers);
		$this->arrLayers = $layers;
		
	}
	
	
	
	/* Provider:  - *
	 * set layers from client, do not normalize as this results in loosing the order
	 * @since: 5.0
	 */
	public function setLayersRaw($layers){
		$this->arrLayers = $layers;
	}
	
	
	/* Provider:  - *
	 * update the title of a Slide by Slide ID
	 * @since: 5.0
	 **/
	public function updateTitleByID($data){
		if(!isset($data['slideID']) || !isset($data['slideTitle'])) return false;
		
		$this->initByID($data['slideID']);
		
		$arrUpdate = array();
		$arrUpdate['title'] = $data['slideTitle'];
		
		$this->updateParamsInDB($arrUpdate);
		
	}
	
	/* Provider:  - *
	 * toggle slide state from data
	 **/
	public function toggleSlideStatFromData($data){
		
		$sliderID = RevSliderFunctions::getVal($data, "slider_id");
		$slider = new RevSlider();
		$slider->initByID($sliderID);
		
		$slideID = RevSliderFunctions::getVal($data, "slide_id");
		
		if($slider->isSlidesFromPosts()){
			$postData = RevSliderFunctionsWP::getPost($slideID);
			
			$oldState = $postData["post_status"];
			$newState = ($oldState == RevSliderFunctionsWP::STATE_PUBLISHED)?RevSliderFunctionsWP::STATE_DRAFT:RevSliderFunctionsWP::STATE_PUBLISHED;
			
			//update the state in wp
			RevSliderFunctionsWP::updatePostState($slideID, $newState);
			
			//return state:
			$newState = ($newState == RevSliderFunctionsWP::STATE_PUBLISHED)?"published":"unpublished";
			
		}else{
			$this->initByID($slideID);
			
			$state = $this->getParam("state","published");
			$newState = ($state == "published")?"unpublished":"published";
			
			$arrUpdate = array();
			$arrUpdate["state"] = $newState;
			
			$this->updateParamsInDB($arrUpdate);
			
		}
		
		return($newState);
	}
	
	
	/* Provider:  - *
	 * 
	 * updatye slide language from data
	 */
	private function updateLangFromData($data){
		
		$slideID = RevSliderFunctions::getVal($data, "slideid");
		$this->initByID($slideID);
		
		$lang = RevSliderFunctions::getVal($data, "lang");
		
		$arrUpdate = array();
		$arrUpdate["lang"] = $lang;
		$this->updateParamsInDB($arrUpdate);
		
		$response = array();
		$response["url_icon"] = RevSliderWpml::getFlagUrl($lang);
		$response["title"] = RevSliderWpml::getLangTitle($lang);
		$response["operation"] = "update";
		
		return($response);
	}
	
	
	/* Provider:  - *
	 * 
	 * add language (add slide that connected to current slide) from data
	 */
	private function addLangFromData($data){
		$sliderID = RevSliderFunctions::getVal($data, "sliderid");
		$slideID = RevSliderFunctions::getVal($data, "slideid");
		$lang = RevSliderFunctions::getVal($data, "lang");
		
		//duplicate slide
		$slider = new RevSlider();
		$slider->initByID($sliderID);
		$newSlideID = $slider->duplicateSlide($slideID);
		
		//update new slide
		$this->initByID($newSlideID);
		
		$arrUpdate = array();
		$arrUpdate["lang"] = $lang;
		$arrUpdate["parentid"] = $slideID;
		$this->updateParamsInDB($arrUpdate);
		
		$urlIcon = RevSliderWpml::getFlagUrl($lang);
		$title = RevSliderWpml::getLangTitle($lang);
		
		$newSlide = new RevSlide();
		$newSlide->initByID($slideID);
		$arrLangCodes = $newSlide->getArrChildLangCodes();
		$isAll = RevSliderWpml::isAllLangsInArray($arrLangCodes);
		
		$html = "<li>
					<img id=\"icon_lang_".$newSlideID."\" class=\"icon_slide_lang\" src=\"".$urlIcon."\" title=\"".$title."\" data-slideid=\"".$newSlideID."\" data-lang=\"".$lang."\">
					<div class=\"icon_lang_loader loader_round\" style=\"display:none\"></div>								
				</li>";
		
		$response = array();
		$response["operation"] = "add";
		$response["isAll"] = $isAll;
		$response["html"] = $html;
		
		return($response);
	}
	
	
	/* Provider:  - *
	 * 
	 * delete slide from language menu data
	 */
	private function deleteSlideFromLangData($data){
		
		$slideID = RevSliderFunctions::getVal($data, "slideid");
		$this->initByID($slideID);
		$this->deleteSlide();
		
		$response = array();
		$response["operation"] = "delete";
		return($response);
	}
	
	
	/* Provider:  - *
	 * 
	 * add or update language from data
	 */
	public function doSlideLangOperation($data){
		
		$operation = RevSliderFunctions::getVal($data, "operation");
		switch($operation){
			case "add":
				$response = $this->addLangFromData($data);	
			break;
			case "delete":
				$response = $this->deleteSlideFromLangData($data);
			break;
			case "update":
			default:
				$response = $this->updateLangFromData($data);
			break;
		}
		
		return($response);
	}
	
	/* Provider:  - *
	 * 
	 * get thumb url
	 */
	public function getUrlImageThumb(){
		
		//get image url by thumb
		if(!empty($this->imageID)){
			$urlImage = RevSliderFunctionsWP::getUrlAttachmentImage($this->imageID, RevSliderFunctionsWP::THUMB_MEDIUM);
		}else{
			//get from cache
			if(!empty($this->imageFilepath)){
				$urlImage = RevSliderBase::getImageUrl($this->imageFilepath,200,100,true);
			}
			else 
				$urlImage = $this->imageUrl;
		}
		
		if(empty($urlImage))
			$urlImage = $this->imageUrl;
		
		return($urlImage);
	}
	
	public function get_image_attributes($slider_type){
		
		$params = $this->params;
		
		$bgType = RevSliderBase::getVar($params, "background_type","transparent");
		$bgColor = RevSliderBase::getVar($params, "slide_bg_color","transparent");

		$bgFit = RevSliderBase::getVar($params, "bg_fit","cover");
		$bgFitX = intval(RevSliderBase::getVar($params, "bg_fit_x","100"));
		$bgFitY = intval(RevSliderBase::getVar($params, "bg_fit_y","100"));

		$bgPosition = RevSliderBase::getVar($params, "bg_position","center top");
		$bgPositionX = intval(RevSliderBase::getVar($params, "bg_position_x","0"));
		$bgPositionY = intval(RevSliderBase::getVar($params, "bg_position_y","0"));

		$bgRepeat = RevSliderBase::getVar($params, "bg_repeat","no-repeat");

		$bgStyle = ' ';
		if($bgFit == 'percentage'){
			$bgStyle .= "background-size: ".$bgFitX.'% '.$bgFitY.'%;';
		}else{
			$bgStyle .= "background-size: ".$bgFit.";";
		}
		if($bgPosition == 'percentage'){
			$bgStyle .= "background-position: ".$bgPositionX.'% '.$bgPositionY.'%;';
		}else{
			$bgStyle .= "background-position: ".$bgPosition.";";
		}
		$bgStyle .= "background-repeat: ".$bgRepeat.";";
		
		$thumb = '';
		
		switch($slider_type){
			case 'gallery':
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
			break;
			case 'posts':
				$thumb = RS_PLUGIN_URL.'public/assets/assets/sources/post.png';
				$bgStyle = 'background-size: cover;';
			break;
			case 'facebook':
				$thumb = RS_PLUGIN_URL.'public/assets/assets/sources/fb.png';
				$bgStyle = 'background-size: cover;';
			break;
			case 'twitter':
				$thumb = RS_PLUGIN_URL.'public/assets/assets/sources/tw.png';
				$bgStyle = 'background-size: cover;';
			break;
			case 'instagram':
				$thumb = RS_PLUGIN_URL.'public/assets/assets/sources/ig.png';
				$bgStyle = 'background-size: cover;';
			break;
			case 'flickr':
				$thumb = RS_PLUGIN_URL.'public/assets/assets/sources/fr.png';
				$bgStyle = 'background-size: cover;';
			break;
			case 'youtube':
				$thumb = RS_PLUGIN_URL.'public/assets/assets/sources/yt.png';
				$bgStyle = 'background-size: cover;';
			break;
			case 'vimeo':
				$thumb = RS_PLUGIN_URL.'public/assets/assets/sources/vm.png';
				$bgStyle = 'background-size: cover;';
			break;
		}
		
		if($thumb == '') $thumb = RevSliderBase::getVar($params, "image");
		
		$bg_fullstyle ='';
		$bg_extraClass='';
		$data_urlImageForView='';

		if($bgType=="image" || $bgType=="streamvimeo" || $bgType=="streamyoutube" || $bgType=="streaminstagram") {
			$data_urlImageForView = $thumb;
			$bg_fullstyle = $bgStyle;
		}

		if($bgType=="solid")
			$bg_fullstyle ='background-color:'.$bgColor.';';
			
		if($bgType=="trans" || $bgType=="transparent")
			$bg_extraClass = 'mini-transparent';
		
		return array(
			'url' => $data_urlImageForView,
			'class' => $bg_extraClass,
			'style' => $bg_fullstyle
		);
	}
	
	/* Provider:  - *
	 * 
	 * replace image url's among slide image and layer images
	 */
	public function replaceImageUrls($urlFrom, $urlTo){
		
		$this->validateInited();
		
		$isUpdated = false;
		
		$urlImage = RevSliderFunctions::getVal($this->params, "image");
		if(strpos($urlImage, $urlFrom) !== false){
			$imageNew = str_replace($urlFrom, $urlTo, $urlImage);
			$this->params["image"] = $imageNew; 
			$isUpdated = true;
		}
		
		if($isUpdated == true)
			$this->updateParamsInDB();
		
		
		// update image url in layers
		$isUpdated = false;
		foreach($this->arrLayers as $key=>$layer){
			$type =  RevSliderFunctions::getVal($layer, "type");
			if($type == "image"){
				$urlImage = RevSliderFunctions::getVal($layer, "image_url");
				if(strpos($urlImage, $urlFrom) !== false){
					$newUrlImage = str_replace($urlFrom, $urlTo, $urlImage);
					$this->arrLayers[$key]["image_url"] = $newUrlImage;
					$isUpdated = true;
				}
			}
		}
		
		if($isUpdated == true)
			$this->updateLayersInDB();
		
	}
	
	
	
	/* Provider:  - *
	 * replace all css classes in all layers
	 * @since: 5.0
	 */
	public function replaceCssClass($css_from, $css_to){
		
		$this->validateInited();
		
		
		$isUpdated = false;
		
		if(!empty($this->arrLayers)){
			foreach($this->arrLayers as $key=>$layer){
				$caption = RevSliderFunctions::getVal($layer, 'style');
				if($caption == $css_from){
					$this->arrLayers[$key]['style'] = $css_to;
					$isUpdated = true;
				}
			}
		}
		
		if($isUpdated == true)
			$this->updateLayersInDB();
	}
	
	
	/* Provider:  - *
	 * reset Slide to certain values
	 * @since: 5.0
	 */
	public function reset_slide_values($values){
		$this->validateInited();
		
		foreach($values as $key => $val){
			$this->params[esc_attr($key)] = esc_attr($val);
		}
		
		$this->updateParamsInDB();
	}
	
	
	/* Provider:  - *
	 * return if current Slide is static Slide
	 */
	public function isStaticSlide(){
		return $this->static_slide;
	}
	
	/* Provider:  - *
	 * Returns all layer attributes that can have more than one setting due to desktop, tablet, mobile sizes
	 * @since: 5.0
	 */
	public static function translateIntoSizes(){

		return array(
			'align_hor',
			'align_vert',
			'top',
			'left',
			'font-size',
			'line-height',
			'font-weight',
			'color',
			'max_width',
			'max_height',
			'whitespace',
			'video_height',
			'video_width',
			'scaleX',
			'scaleY'
			);
			
	}
	
	
	/* Provider:  - *
	 * Translates all values that need more than one setting
	 * @since: 5.0
	 */
	public function translateLayerSizes($layers){
		$translation = self::translateIntoSizes();
		
		if(!empty($layers)){
			foreach($layers as $l => $layer){
				foreach($translation as $trans){
					if(isset($layers[$l][$trans])){
						if(!is_array($layers[$l][$trans])){
							$layers[$l][$trans] = array('desktop' => $layers[$l][$trans]);
						}
					}
				}
			}
		}
		
		return $layers;
	}
}

/* Provider:  - *
 * old classname extends new one (old classnames will be obsolete soon)
 * @since: 5.0
 **/
class RevSlide extends RevSliderSlide {}
?>