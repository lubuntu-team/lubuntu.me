<?php
/* Provider:  - *
 * @author    ThemePunch <info@themepunch.com>
 * @link      http://www.themepunch.com/
 * @copyright 2015 ThemePunch
 */
 
if( !defined( 'ABSPATH') ) exit();

class RevSliderWpml{
	
	/* Provider:  - *
	 * 
	 * true / false if the wpml plugin exists
	 */
	public static function isWpmlExists(){
		
		if(class_exists("SitePress"))
			return(true);
		else
			return(false);
	}
	
	/* Provider:  - *
	 * 
	 * valdiate that wpml exists
	 */
	private static function validateWpmlExists(){
		if(!self::isWpmlExists())
			RevSliderFunctions::throwError("The wpml plugin is not activated");
	}
	
	/* Provider:  - *
	 * 
	 * get languages array
	 */
	public static function getArrLanguages($getAllCode = true){
		
		self::validateWpmlExists();
		$wpml = new SitePress();
		$arrLangs = $wpml->get_active_languages();
		
		$response = array();
		
		if($getAllCode == true)
			$response["all"] = __("All Languages",REVSLIDER_TEXTDOMAIN);
		
		foreach($arrLangs as $code=>$arrLang){
			$name = $arrLang["native_name"];
			$response[$code] = $name;
		}
		
		return($response);
	}
	
	/* Provider:  - *
	 * 
	 * get assoc array of lang codes
	 */
	public static function getArrLangCodes($getAllCode = true){
		
		$arrCodes = array();
		
		if($getAllCode == true)
			$arrCodes["all"] = "all";
			
		self::validateWpmlExists();
		$wpml = new SitePress();
		$arrLangs = $wpml->get_active_languages();
		foreach($arrLangs as $code=>$arr){
			$arrCodes[$code] = $code;
		}
		
		return($arrCodes);
	}
	
	
	/* Provider:  - *
	 * 
	 * check if all languages exists in the given langs array
	 */
	public static function isAllLangsInArray($arrCodes){
		$arrAllCodes = self::getArrLangCodes();
		$diff = array_diff($arrAllCodes, $arrCodes);
		return(empty($diff));
	}
	
	
	/* Provider:  - *
	 * 
	 * get langs with flags menu list
	 * @param $props
	 */
	public static function getLangsWithFlagsHtmlList($props = "",$htmlBefore = ""){
		$arrLangs = self::getArrLanguages();
		if(!empty($props))
			$props = " ".$props;
		
		$html = "<ul".$props.">"."\n";
		$html .= $htmlBefore;
	
		foreach($arrLangs as $code=>$title){
			$urlIcon = self::getFlagUrl($code);
			
			$html .= "<li data-lang='".$code."' class='item_lang'><a data-lang='".$code."' href='javascript:void(0)'>"."\n";
			$html .= "<img src='".$urlIcon."'/> $title"."\n";				
			$html .= "</a></li>"."\n";
		}

		$html .= "</ul>";
		
		
		return($html);
	}

	
	/* Provider:  - *
	 * get flag url
	 */
	public static function getFlagUrl($code){
		
		self::validateWpmlExists();
		$wpml = new SitePress();
		
		if(empty($code) || $code == "all")
			$url = RS_PLUGIN_URL.'admin/assets/images/icon-all.png';
		else
			$url = $wpml->get_flag_url($code);
		
		//default: show all
		if(empty($url))
			$url = RS_PLUGIN_URL.'admin/assets/images/icon-all.png';
		
		return($url);
	}
	
	
	/* Provider:  - *
	/* Provider:  -  get language details by code
	 */
	private function getLangDetails($code){
		global $wpdb;
		
		$details = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."icl_languages WHERE code='$code'");
		
		if(!empty($details))
			$details = (array)$details;
		
		return($details);
	}
	
	
	/* Provider:  - *
	 * 
	 * get language title by code
	 */
	public static function getLangTitle($code){
		
		$langs = self::getArrLanguages();
		
		if($code == "all")
			return(__("All Languages", REVSLIDER_TEXTDOMAIN));
		
		if(array_key_exists($code, $langs))
			return($langs[$code]);
			
		$details = self::getLangDetails($code);
		if(!empty($details))			
			return($details["english_name"]);
		
		return("");
	}
	
	
	/* Provider:  - *
	 * 
	 * get current language
	 */
	public static function getCurrentLang(){
		self::validateWpmlExists();
		$wpml = new SitePress();

		if(is_admin())
			$lang = $wpml->get_default_language();
		else
			$lang = RevSliderFunctionsWP::getCurrentLangCode();
		
		return($lang);
	}
}

/* Provider:  - *
 * old classname extends new one (old classnames will be obsolete soon)
 * @since: 5.0
 **/
class UniteWpmlRev extends RevSliderWpml {}
?>