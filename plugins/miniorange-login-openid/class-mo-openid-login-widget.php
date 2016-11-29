<?php
if(mo_openid_is_customer_registered()) {
/*
* Login Widget
*
*/
class mo_openid_login_wid extends WP_Widget {

	public function __construct() {
		parent::__construct(
	 		'mo_openid_login_wid',
			'miniOrange Social Login Widget',
			array( 
				'description' => __( 'Login using Social Apps like Google, Facebook, LinkedIn, Microsoft, Instagram.', 'flw' ), 
				'customize_selective_refresh' => true,
			)
		);
	 }


	public function widget( $args, $instance ) {
		extract( $args );

		echo $args['before_widget'];
			$this->openidloginForm();

		echo $args['after_widget'];
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['wid_title'] = strip_tags( $new_instance['wid_title'] );
		return $instance;
	}

	
	public function openidloginForm(){
		global $post;
		//$this->error_message();
		$selected_theme = get_option('mo_openid_login_theme');
		$appsConfigured = get_option('mo_openid_google_enable') | get_option('mo_openid_salesforce_enable') | get_option('mo_openid_facebook_enable') | get_option('mo_openid_linkedin_enable') | get_option('mo_openid_instagram_enable') | get_option('mo_openid_amazon_enable') | get_option('mo_openid_windowslive_enable') | get_option('mo_openid_twitter_enable') | get_option('mo_openid_vkontakte_enable');
		$spacebetweenicons = get_option('mo_login_icon_space');
		$customWidth = get_option('mo_login_icon_custom_width');
		$customHeight = get_option('mo_login_icon_custom_height');
		$customSize = get_option('mo_login_icon_custom_size');
		$customBackground = get_option('mo_login_icon_custom_color');
		$customTheme = get_option('mo_openid_login_custom_theme');
		$customTextofTitle = get_option('mo_openid_login_button_customize_text');
		$customBoundary = get_option('mo_login_icon_custom_boundary');
		$customLogoutName = get_option('mo_openid_login_widget_customize_logout_name_text');
		$customLogoutLink = get_option('mo_openid_login_widget_customize_logout_text');
		
		if( ! is_user_logged_in() ) {

			if( $appsConfigured ) {
				$this->mo_openid_load_login_script();
			?>
			
				<a href="http://miniorange.com/cloud-identity-broker-service" style="display:none;"></a>
				<a href="http://miniorange.com/strong_auth" style="display:none;"></a>
				<a href="http://miniorange.com/single-sign-on-sso" style="display:none;"></a>
				<a href="http://miniorange.com/fraud" style="display:none;"></a>
			
				 <div class="mo-openid-app-icons">

				 <p><?php   echo get_option('mo_openid_login_widget_customize_text'); ?>
				</p>
			<?php
				if($customTheme == 'default'){
				if( get_option('mo_openid_facebook_enable') ) {
					if($selected_theme == 'longbutton'){
				?> <a onClick="moOpenIdLogin('facebook');" style="width:<?php echo $customWidth ?>px !important;padding-top:<?php echo $customHeight-29 ?>px !important;padding-bottom:<?php echo $customHeight-29 ?>px !important;margin-bottom:<?php echo $spacebetweenicons-5 ?>px !important;border-radius:<?php echo $customBoundary ?>px !important;" class="btn btn-block btn-social btn-facebook  btn-custom-size login-button"  > <i style="padding-top:<?php echo $customHeight-35 ?>px !important" class="fa fa-facebook"></i><?php
							echo get_option('mo_openid_login_button_customize_text'); 	?> Facebook</a>
				<?php }
				else{ ?>

					<a title="<?php echo $customTextofTitle ?> Facebook" onClick="moOpenIdLogin('facebook');"><img alt='Facebook' style="width:<?php echo $customSize?>px !important;height:<?php echo $customSize?>px !important;margin-left:<?php echo $spacebetweenicons-4?>px !important" src="<?php echo plugins_url( 'includes/images/icons/facebook.png', __FILE__ )?>" class="<?php echo $selected_theme; ?> login-button" ></a>

				<?php }

				}
				if( get_option('mo_openid_google_enable') ) {
					if($selected_theme == 'longbutton'){
				?>
					
				<a  onClick="moOpenIdLogin('google');" style='width:<?php echo $customWidth ?>px !important;padding-top:<?php echo $customHeight-29 ?>px !important;padding-bottom:<?php echo $customHeight-29 ?>px !important;margin-bottom:<?php echo $spacebetweenicons-5 ?>px !important;border-radius:<?php echo $customBoundary ?>px !important;' class='btn btn-block btn-social btn-google btn-custom-size login-button' > <i style='padding-top:<?php echo $customHeight-35 ?>px !important' class='fa fa-google-plus'></i><?php
							echo get_option('mo_openid_login_button_customize_text'); 	?> Google</a>
				<?php }
				else{ ?>
				<a onClick="moOpenIdLogin('google');"  title="<?php echo $customTextofTitle ?> Google" ><img alt='Google' style="width:<?php echo $customSize?>px !important;height:<?php echo $customSize?>px !important;margin-left:<?php echo $spacebetweenicons-4?>px !important" src="<?php echo plugins_url( 'includes/images/icons/google.png', __FILE__ )?>" class="<?php echo $selected_theme; ?> login-button" ></a>
				<?php
				}
				}

				if( get_option('mo_openid_vkontakte_enable') ) {
					if($selected_theme == 'longbutton'){
				?>
					
				<a  onClick="moOpenIdLogin('vkontakte');" style='width:<?php echo $customWidth ?>px !important;padding-top:<?php echo $customHeight-29 ?>px !important;padding-bottom:<?php echo $customHeight-29 ?>px !important;margin-bottom:<?php echo $spacebetweenicons-5 ?>px !important;border-radius:<?php echo $customBoundary ?>px !important;' class='btn btn-block btn-social btn-vk btn-custom-size login-button' > <i style='padding-top:<?php echo $customHeight-35 ?>px !important' class='fa fa-vk'></i><?php
							echo get_option('mo_openid_login_button_customize_text'); 	?> Vkontakte</a>
				<?php }
				else{ ?>
				<a onClick="moOpenIdLogin('vkontakte');"  title="<?php echo $customTextofTitle ?> Vkontakte" ><img alt='Vkontakte' style="width:<?php echo $customSize?>px !important;height:<?php echo $customSize?>px !important;margin-left:<?php echo $spacebetweenicons-4?>px !important" src="<?php echo plugins_url( 'includes/images/icons/vk.png', __FILE__ )?>" class="<?php echo $selected_theme; ?> login-button" ></a>
				<?php
				}
				}

				
				if( get_option('mo_openid_twitter_enable') ) {
					if($selected_theme == 'longbutton'){
				?> <a  onClick="moOpenIdLogin('twitter');" style="width:<?php echo $customWidth ?>px !important;padding-top:<?php echo $customHeight-29 ?>px !important;padding-bottom:<?php echo $customHeight-29 ?>px !important;margin-bottom:<?php echo $spacebetweenicons-5 ?>px !important;border-radius:<?php echo $customBoundary ?>px !important;" class="btn btn-block btn-social btn-twitter btn-custom-size login-button" > <i style="padding-top:<?php echo $customHeight-35 ?>px !important" class="fa fa-twitter"></i><?php
							echo get_option('mo_openid_login_button_customize_text'); 	?> Twitter</a>
				<?php }
				else{ ?>


				<a title="<?php echo $customTextofTitle ?> Twitter" onClick="moOpenIdLogin('twitter');"><img alt='Twitter' style="width:<?php echo $customSize?>px !important;height:<?php echo $customSize?>px !important;margin-left:<?php echo $spacebetweenicons-4?>px !important"  src="<?php echo plugins_url( 'includes/images/icons/twitter.png', __FILE__ )?>" class="<?php echo $selected_theme; ?> login-button"></a>
				<?php }
				}
				
			if( get_option('mo_openid_linkedin_enable') ) {
							if($selected_theme == 'longbutton'){ ?>
					<a  onClick="moOpenIdLogin('linkedin');" style="width:<?php echo $customWidth ?>px !important;padding-top:<?php echo $customHeight-29 ?>px !important;padding-bottom:<?php echo $customHeight-29 ?>px !important;margin-bottom:<?php echo $spacebetweenicons-5 ?>px !important;border-radius:<?php echo $customBoundary ?>px !important;" class="btn btn-block btn-social btn-linkedin btn-custom-size login-button" > <i style="padding-top:<?php echo $customHeight-35 ?>px !important" class="fa fa-linkedin"></i><?php
							echo get_option('mo_openid_login_button_customize_text'); 	?> LinkedIn</a>
				<?php }
				else{ ?>
					<a title="<?php echo $customTextofTitle ?> LinkedIn" onClick="moOpenIdLogin('linkedin');"><img alt='LinkedIn' style="width:<?php echo $customSize?>px !important;height:<?php echo $customSize?>px !important;margin-left:<?php echo $spacebetweenicons-4?>px !important" src="<?php echo plugins_url( 'includes/images/icons/linkedin.png', __FILE__ )?>" class="<?php echo $selected_theme; ?> login-button" ></a>
						<?php }
				}if( get_option('mo_openid_instagram_enable') ) {
					if($selected_theme == 'longbutton'){	?>
				 <a  onClick="moOpenIdLogin('instagram');" style="width:<?php echo $customWidth ?>px !important;padding-top:<?php echo $customHeight-29 ?>px !important;padding-bottom:<?php echo $customHeight-29 ?>px !important;margin-bottom:<?php echo $spacebetweenicons-5 ?>px !important;border-radius:<?php echo $customBoundary ?>px !important;" class="btn btn-block btn-social btn-instagram btn-custom-size login-button" > <i style="padding-top:<?php echo $customHeight-35 ?>px !important" class="fa fa-instagram"></i><?php
							echo get_option('mo_openid_login_button_customize_text'); 	?> Instagram</a>
				<?php }
				else{ ?>


				<a title="<?php echo $customTextofTitle ?> Instagram" onClick="moOpenIdLogin('instagram');"><img alt='Instagram' style="width:<?php echo $customSize?>px !important;height:<?php echo $customSize?>px !important;margin-left:<?php echo $spacebetweenicons-4?>px !important"  src="<?php echo plugins_url( 'includes/images/icons/instagram.png', __FILE__ )?>" class="<?php echo $selected_theme; ?> login-button"></a>
				<?php }
				}if( get_option('mo_openid_amazon_enable') ) {
					if($selected_theme == 'longbutton'){
				?> <a  onClick="moOpenIdLogin('amazon');" style="width:<?php echo $customWidth ?>px !important;padding-top:<?php echo $customHeight-29 ?>px !important;padding-bottom:<?php echo $customHeight-29 ?>px !important;margin-bottom:<?php echo $spacebetweenicons-5 ?>px !important;border-radius:<?php echo $customBoundary ?>px !important;" class="btn btn-block btn-social btn-soundcloud btn-custom-size login-button" > <i style="padding-top:<?php echo $customHeight-35 ?>px !important" class="fa fa-amazon"></i><?php
							echo get_option('mo_openid_login_button_customize_text'); 	?> Amazon</a>
				<?php }
				else{ ?>

				<a title="<?php echo $customTextofTitle ?> Amazon" onClick="moOpenIdLogin('amazon');"><img alt='Amazon' style="width:<?php echo $customSize?>px !important;height:<?php echo $customSize?>px !important;margin-left:<?php echo $spacebetweenicons-4?>px !important"  src="<?php echo plugins_url( 'includes/images/icons/amazon.png', __FILE__ )?>" class="<?php echo $selected_theme; ?> login-button"></a>
				<?php }
				}if( get_option('mo_openid_salesforce_enable') ) {
						if($selected_theme == 'longbutton'){
				?> <a  onClick="moOpenIdLogin('salesforce');" style="width:<?php echo $customWidth ?>px !important;padding-top:<?php echo $customHeight-29 ?>px !important;padding-bottom:<?php echo $customHeight-29 ?>px !important;margin-bottom:<?php echo $spacebetweenicons-5 ?>px !important;border-radius:<?php echo $customBoundary ?>px !important;" class="btn btn-block btn-social btn-vimeo btn-custom-size login-button" > <i style="padding-top:<?php echo $customHeight-35 ?>px !important" class="fa fa-cloud"></i> <?php
							echo get_option('mo_openid_login_button_customize_text'); 	?> Salesforce</a>
				<?php }
				else{ ?>


				<a title="<?php echo $customTextofTitle ?> Salesforce" onClick="moOpenIdLogin('salesforce');"><img alt='Salesforce' style="width:<?php echo $customSize?>px !important;height:<?php echo $customSize?>px !important;margin-left:<?php echo $spacebetweenicons-4?>px !important"  src="<?php echo plugins_url( 'includes/images/icons/salesforce.png', __FILE__ )?>" class="<?php echo $selected_theme; ?> login-button" ></a>
				<?php }
				}if( get_option('mo_openid_windowslive_enable') ) {
					if($selected_theme == 'longbutton'){
				?> <a  onClick="moOpenIdLogin('windowslive');" style="width:<?php echo $customWidth ?>px !important;padding-top:<?php echo $customHeight-29 ?>px !important;padding-bottom:<?php echo $customHeight-29 ?>px !important;margin-bottom:<?php echo $spacebetweenicons-5 ?>px !important;border-radius:<?php echo $customBoundary ?>px !important;" class="btn btn-block btn-social btn-microsoft btn-custom-size login-button" > <i style="padding-top:<?php echo $customHeight-35 ?>px !important" class="fa fa-windows"></i><?php
							echo get_option('mo_openid_login_button_customize_text'); 	?> Microsoft</a>
				<?php }
				else{ ?>


				<a title="<?php echo $customTextofTitle ?> Microsoft" onClick="moOpenIdLogin('windowslive');"><img alt='Windowslive' style="width:<?php echo $customSize?>px !important;height:<?php echo $customSize?>px !important;margin-left:<?php echo $spacebetweenicons-4?>px !important"  src="<?php echo plugins_url( 'includes/images/icons/windowslive.png', __FILE__ )?>" class="<?php echo $selected_theme; ?> login-button"></a>
				<?php }
				}
				
				}
				?>
				
				
				
				<?php
				if($customTheme == 'custom'){
				if( get_option('mo_openid_facebook_enable') ) {
					if($selected_theme == 'longbutton'){
				?> <a  onClick="moOpenIdLogin('facebook');" style="width:<?php echo $customWidth ?>px !important;padding-top:<?php echo $customHeight-29 ?>px !important;padding-bottom:<?php echo $customHeight-29 ?>px !important;margin-bottom:<?php echo $spacebetweenicons-5 ?>px !important;background:<?php echo "#".$customBackground?> !important;border-radius:<?php echo $customBoundary ?>px !important;" class="btn btn-block btn-social btn-facebook  btn-custom-size login-button" > <i style="padding-top:<?php echo $customHeight-35 ?>px !important" class="fa fa-facebook"></i><?php
							echo get_option('mo_openid_login_button_customize_text'); 	?> Facebook</a>
				<?php }
				else{ ?>

					<a  onClick="moOpenIdLogin('facebook');" title="<?php echo $customTextofTitle ?> Facebook"><i style="width:<?php echo $customSize?>px !important;height:<?php echo $customSize?>px !important;margin-left:<?php echo $spacebetweenicons-4?>px !important;background:<?php echo "#".$customBackground?> !important;font-size:<?php echo $customSize-16?>px !important;" class="fa fa-facebook custom-login-button <?php echo $selected_theme; ?>" ></i></a>

				<?php }

				}

				if( get_option('mo_openid_google_enable') ) {
					if($selected_theme == 'longbutton'){
				?>
					
					<a   onClick="moOpenIdLogin('google');" style="width:<?php echo $customWidth ?>px !important;padding-top:<?php echo $customHeight-29 ?>px !important;padding-bottom:<?php echo $customHeight-29 ?>px !important;margin-bottom:<?php echo $spacebetweenicons-5 ?>px !important; background:<?php echo "#".$customBackground?> !important;border-radius:<?php echo $customBoundary ?>px !important;" class="btn btn-block btn-social btn-customtheme btn-custom-size login-button" > <i style="padding-top:<?php echo $customHeight-35 ?>px !important" class="fa fa-google-plus"></i><?php
							echo get_option('mo_openid_login_button_customize_text'); 	?> Google</a>
				<?php }
				else{ ?>
				<a  onClick="moOpenIdLogin('google');" title="<?php echo $customTextofTitle ?> Google"><i style="width:<?php echo $customSize?>px !important;height:<?php echo $customSize?>px !important;margin-left:<?php echo $spacebetweenicons-4?>px !important;background:<?php echo "#".$customBackground?> !important;font-size:<?php echo $customSize-16?>px !important;"  class="fa fa-google-plus custom-login-button <?php echo $selected_theme; ?>" ></i></a>
				<?php
				}
				}

				if( get_option('mo_openid_vkontakte_enable') ) {
					if($selected_theme == 'longbutton'){
				?>
					
					<a   onClick="moOpenIdLogin('vkontakte');" style="width:<?php echo $customWidth ?>px !important;padding-top:<?php echo $customHeight-29 ?>px !important;padding-bottom:<?php echo $customHeight-29 ?>px !important;margin-bottom:<?php echo $spacebetweenicons-5 ?>px !important; background:<?php echo "#".$customBackground?> !important;border-radius:<?php echo $customBoundary ?>px !important;" class="btn btn-block btn-social btn-customtheme btn-custom-size login-button" > <i style="padding-top:<?php echo $customHeight-35 ?>px !important" class="fa fa-vk"></i><?php
							echo get_option('mo_openid_login_button_customize_text'); 	?> Vkontakte</a>
				<?php }
				else{ ?>
				<a  onClick="moOpenIdLogin('vkontakte');" title="<?php echo $customTextofTitle ?> Vkontakte"><i style="width:<?php echo $customSize?>px !important;height:<?php echo $customSize?>px !important;margin-left:<?php echo $spacebetweenicons-4?>px !important;background:<?php echo "#".$customBackground?> !important;font-size:<?php echo $customSize-16?>px !important;"  class="fa fa-vk custom-login-button <?php echo $selected_theme; ?>" ></i></a>
				<?php
				}
				}

				if( get_option('mo_openid_twitter_enable') ) {
					if($selected_theme == 'longbutton'){
				?>
					
					<a   onClick="moOpenIdLogin('twitter');" style="width:<?php echo $customWidth ?>px !important;padding-top:<?php echo $customHeight-29 ?>px !important;padding-bottom:<?php echo $customHeight-29 ?>px !important;margin-bottom:<?php echo $spacebetweenicons-5 ?>px !important; background:<?php echo "#".$customBackground?> !important;border-radius:<?php echo $customBoundary ?>px !important;" class="btn btn-block btn-social btn-customtheme btn-custom-size login-button" > <i style="padding-top:<?php echo $customHeight-35 ?>px !important" class="fa fa-twitter"></i><?php
							echo get_option('mo_openid_login_button_customize_text'); 	?> Twitter</a>
				<?php }
				else{ ?>
				<a  onClick="moOpenIdLogin('twitter');" title="<?php echo $customTextofTitle ?> Twitter"><i style="width:<?php echo $customSize?>px !important;height:<?php echo $customSize?>px !important;margin-left:<?php echo $spacebetweenicons-4?>px !important;background:<?php echo "#".$customBackground?> !important;font-size:<?php echo $customSize-16?>px !important;"  class="fa fa-twitter custom-login-button <?php echo $selected_theme; ?>" ></i></a>
				<?php
				}
				}
			if( get_option('mo_openid_linkedin_enable') ) {
							if($selected_theme == 'longbutton'){ ?>
					<a  onClick="moOpenIdLogin('linkedin');" style="width:<?php echo $customWidth ?>px !important;padding-top:<?php echo $customHeight-29 ?>px !important;padding-bottom:<?php echo $customHeight-29 ?>px !important;margin-bottom:<?php echo $spacebetweenicons-5 ?>px !important;background:<?php echo "#".$customBackground?> !important;border-radius:<?php echo $customBoundary ?>px !important;" class="btn btn-block btn-social btn-linkedin btn-custom-size login-button" > <i style="padding-top:<?php echo $customHeight-35 ?>px !important" class="fa fa-linkedin"></i><?php
							echo get_option('mo_openid_login_button_customize_text'); 	?> LinkedIn</a>
				<?php }
				else{ ?>
					<a  onClick="moOpenIdLogin('linkedin');" title="<?php echo $customTextofTitle ?> LinkedIn"><i style="width:<?php echo $customSize?>px !important;height:<?php echo $customSize?>px !important;margin-left:<?php echo $spacebetweenicons-4?>px !important;background:<?php echo "#".$customBackground?> !important;font-size:<?php echo $customSize-16?>px !important;"  class="fa fa-linkedin custom-login-button <?php echo $selected_theme; ?>" ></i></a>
						<?php }
				}if( get_option('mo_openid_instagram_enable') ) {
					if($selected_theme == 'longbutton'){	?>
				 <a  onClick="moOpenIdLogin('instagram');" style="width:<?php echo $customWidth ?>px !important;padding-top:<?php echo $customHeight-29 ?>px !important;padding-bottom:<?php echo $customHeight-29 ?>px !important;margin-bottom:<?php echo $spacebetweenicons-5 ?>px !important;background:<?php echo "#".$customBackground?> !important;background:<?php echo "#".$customBackground?> !important;border-radius:<?php echo $customBoundary ?>px !important;" class="btn btn-block btn-social btn-instagram btn-custom-size login-button" > <i style="padding-top:<?php echo $customHeight-35 ?>px !important" class="fa fa-instagram"></i><?php
							echo get_option('mo_openid_login_button_customize_text'); 	?> Instagram</a>
				<?php }
				else{ ?>


				<a  onClick="moOpenIdLogin('instagram');" title="<?php echo $customTextofTitle ?> Instagram"><i style="width:<?php echo $customSize?>px !important;height:<?php echo $customSize?>px !important;margin-left:<?php echo $spacebetweenicons-4?>px !important;background:<?php echo "#".$customBackground?> !important;font-size:<?php echo $customSize-16?>px !important;"   class="fa fa-instagram custom-login-button <?php echo $selected_theme; ?>"></i></a>
				<?php }
				}if( get_option('mo_openid_amazon_enable') ) {
					if($selected_theme == 'longbutton'){
				?> <a  onClick="moOpenIdLogin('amazon');" style="width:<?php echo $customWidth ?>px !important;padding-top:<?php echo $customHeight-29 ?>px !important;padding-bottom:<?php echo $customHeight-29 ?>px !important;margin-bottom:<?php echo $spacebetweenicons-5 ?>px !important;background:<?php echo "#".$customBackground?> !important;border-radius:<?php echo $customBoundary ?>px !important;" class="btn btn-block btn-social btn-linkedin btn-custom-size login-button" ><i style="padding-top:<?php echo $customHeight-35 ?>px !important" class="fa fa-amazon"></i><?php
							echo get_option('mo_openid_login_button_customize_text'); 	?> Amazon</a>
				<?php }
				else{ ?>

				<a  onClick="moOpenIdLogin('amazon');" title="<?php echo $customTextofTitle ?> Amazon"><i style="width:<?php echo $customSize?>px !important;height:<?php echo $customSize?>px !important;margin-left:<?php echo $spacebetweenicons-4?>px !important;background:<?php echo "#".$customBackground?> !important;font-size:<?php echo $customSize-16?>px !important;"   class="fa fa-amazon custom-login-button <?php echo $selected_theme; ?>"></i></a>
				<?php }
				}if( get_option('mo_openid_salesforce_enable') ) {
						if($selected_theme == 'longbutton'){
				?> <a  onClick="moOpenIdLogin('salesforce');" style="width:<?php echo $customWidth ?>px !important;padding-top:<?php echo $customHeight-29 ?>px !important;padding-bottom:<?php echo $customHeight-29 ?>px !important;margin-bottom:<?php echo $spacebetweenicons-5 ?>px !important;background:<?php echo "#".$customBackground?> !important;border-radius:<?php echo $customBoundary ?>px !important;" class="btn btn-block btn-social btn-linkedin btn-custom-size login-button" ><i style="padding-top:<?php echo $customHeight-35 ?>px !important" class="fa fa-cloud"></i> <?php
							echo get_option('mo_openid_login_button_customize_text'); 	?> Salesforce</a>
				<?php }
				else{ ?>


				<a  onClick="moOpenIdLogin('salesforce');" title="<?php echo $customTextofTitle ?> Salesforce"><i style="width:<?php echo $customSize?>px !important;height:<?php echo $customSize?>px !important;margin-left:<?php echo $spacebetweenicons-4?>px !important;background:<?php echo "#".$customBackground?> !important;font-size:<?php echo $customSize-16?>px " class="fa fa-cloud custom-login-button <?php echo $selected_theme; ?>" ></i></a>
				<?php }
				}if( get_option('mo_openid_windowslive_enable') ) {
					if($selected_theme == 'longbutton'){
				?> <a  onClick="moOpenIdLogin('windowslive');" style="width:<?php echo $customWidth ?>px !important;padding-top:<?php echo $customHeight-29 ?>px !important;padding-bottom:<?php echo $customHeight-29 ?>px !important;margin-bottom:<?php echo $spacebetweenicons-5 ?>px !important;background:<?php echo "#".$customBackground?> !important;border-radius:<?php echo $customBoundary ?>px !important;" class="btn btn-block btn-social btn-microsoft btn-custom-size login-button" > <i style="padding-top:<?php echo $customHeight-35 ?>px !important" class="fa fa-windows"></i><?php
							echo get_option('mo_openid_login_button_customize_text'); 	?> Microsoft</a>
				<?php }
				else{ ?>


				<a  onClick="moOpenIdLogin('windowslive');" title="<?php echo $customTextofTitle ?> Microsoft"><i style="width:<?php echo $customSize?>px !important;height:<?php echo $customSize?>px !important;margin-left:<?php echo $spacebetweenicons-4?>px !important;background:<?php echo "#".$customBackground?> !important;font-size:<?php echo $customSize-16?>px !important;"   class=" fa fa-windows custom-login-button <?php echo $selected_theme; ?>"></i></a>
				<?php }
				}
				
				
				}
				?>
				<br>
				</div> <br>
				<?php


			} else {
				?>
				<div>No apps configured. Please contact your administrator.</div>
			<?php
			}
		}else {
			global $current_user;
			$current_user = wp_get_current_user();
			$customLogoutName = str_replace('##username##', $current_user->display_name, $customLogoutName);
			$link_with_username = $customLogoutName;
			if (empty($customLogoutName)  || empty($customLogoutLink)) {
			?>
			<div id="logged_in_user" class="mo_openid_login_wid">
				<li><?php echo $link_with_username;?> <a href="<?php echo wp_logout_url( site_url() ); ?>" title="<?php _e('Logout','flw');?>"><?php _e($customLogoutLink,'flw');?></a></li>
			</div>
			<?php
				
			}
			else {
			?>
			<div id="logged_in_user" class="mo_openid_login_wid">
				<li><?php echo $link_with_username;?> <a href="<?php echo wp_logout_url( site_url() ); ?>" title="<?php _e('Logout','flw');?>"><?php _e($customLogoutLink,'flw');?></a></li>
			</div>
			<?php
			}		
		}
	}

	
	public function openidloginFormShortCode( $atts ){
		global $post;
		$html = '';
		//$this->error_message();
		$selected_theme = isset( $atts['shape'] )? $atts['shape'] : get_option('mo_openid_login_theme');
		$appsConfigured = get_option('mo_openid_google_enable') | get_option('mo_openid_salesforce_enable') | get_option('mo_openid_facebook_enable') | get_option('mo_openid_linkedin_enable') | get_option('mo_openid_instagram_enable') | get_option('mo_openid_amazon_enable') | get_option('mo_openid_windowslive_enable') |get_option('mo_openid_twitter_enable') | get_option('mo_openid_vkontakte_enable');
		$spacebetweenicons = isset( $atts['space'] )? $atts['space'] : get_option('mo_login_icon_space');
		$customWidth = isset( $atts['width'] )? $atts['width'] : get_option('mo_login_icon_custom_width');
		$customHeight = isset( $atts['height'] )? $atts['height'] : get_option('mo_login_icon_custom_height');
		$customSize = isset( $atts['size'] )? $atts['size'] : get_option('mo_login_icon_custom_size');
		$customBackground = isset( $atts['background'] )? $atts['background'] : get_option('mo_login_icon_custom_color');
		$customTheme = isset( $atts['theme'] )? $atts['theme'] : get_option('mo_openid_login_custom_theme');
		$customText = get_option('mo_openid_login_widget_customize_text');
		$buttonText = get_option('mo_openid_login_button_customize_text');
		$customTextofTitle = get_option('mo_openid_login_button_customize_text');
		$logoutUrl = wp_logout_url( site_url() );
		$customBoundary = isset( $atts['edge'] )? $atts['edge'] : get_option('mo_login_icon_custom_boundary');
		$customLogoutName = get_option('mo_openid_login_widget_customize_logout_name_text');
		$customLogoutLink = get_option('mo_openid_login_widget_customize_logout_text');
		
		if($selected_theme == 'longbuttonwithtext'){
			$selected_theme = 'longbutton';
		}
		if($customTheme == 'custombackground'){
			$customTheme = 'custom';
		}
		
		if( ! is_user_logged_in() ) {

			if( $appsConfigured ) {
				$this->mo_openid_load_login_script();
			$html .= "<a href='http://miniorange.com/cloud-identity-broker-service' style='display:none;'></a>
				<a href='http://miniorange.com/strong_auth' style='display:none;'></a>
				<a href='http://miniorange.com/single-sign-on-sso' style='display:none;'></a>
				<a href='http://miniorange.com/fraud' style='display:none;'></a>
			
				 <div class='mo-openid-app-icons'>
				
				 <p> $customText</p>";
				
				if($customTheme == 'default'){
				if( get_option('mo_openid_facebook_enable') ) {
					if($selected_theme == 'longbutton'){
				 $html .= "<a  style='width: " . $customWidth . "px !important;padding-top:" . ($customHeight-29) . "px !important;padding-bottom:" . ($customHeight-29) . "px !important;margin-bottom: " . ($spacebetweenicons-5)  . "px !important;border-radius: " .$customBoundary. "px !important;' class='btn btn-block btn-social btn-facebook btn-custom-dec login-button' onClick='moOpenIdLogin(" . '"facebook"' . ");'> <i style='padding-top:" . ($customHeight-35) . "px !important' class='fa fa-facebook'></i>" . $buttonText . " Facebook</a>"; }
				else{ 
						$html .= "<a title= ' ".$customTextofTitle." Facebook' onClick='moOpenIdLogin(" . '"facebook"' . ");' ><img alt='Facebook' style='width:" . $customSize ."px !important;height: " . $customSize ."px !important;margin-left: " . ($spacebetweenicons-4) ."px !important' src='" . plugins_url( 'includes/images/icons/facebook.png', __FILE__ ) . "' class='login-button " .$selected_theme . "' ></a>";
				}

				}

				if( get_option('mo_openid_google_enable') ) {
					if($selected_theme == 'longbutton'){
				 $html .= "<a  style='width: " . $customWidth . "px !important;padding-top:" . ($customHeight-29) . "px !important;padding-bottom:" . ($customHeight-29) . "px !important;margin-bottom: " . ($spacebetweenicons-5)  . "px !important;border-radius: " .$customBoundary ."px !important;' class='btn btn-block btn-social btn-google btn-custom-dec login-button' onClick='moOpenIdLogin(" . '"google"' . ");'> <i style='padding-top:" . ($customHeight-35) . "px !important' class='fa fa-google-plus'></i>" . $buttonText . " Google</a>"; 
				 }
				else{ 
				
				$html .= "<a  onClick='moOpenIdLogin(" . '"google"' . ");' title= ' ".$customTextofTitle." Google'><img alt='Google' style='width:" . $customSize ."px !important;height: " . $customSize ."px !important;margin-left: " . ($spacebetweenicons-4) ."px !important' src='" . plugins_url( 'includes/images/icons/google.png', __FILE__ ) . "' class='login-button " .$selected_theme . "' ></a>";
				
				}
				}

				if( get_option('mo_openid_vkontakte_enable') ) {
					if($selected_theme == 'longbutton'){
				 $html .= "<a  style='width: " . $customWidth . "px !important;padding-top:" . ($customHeight-29) . "px !important;padding-bottom:" . ($customHeight-29) . "px !important;margin-bottom: " . ($spacebetweenicons-5)  . "px !important;border-radius: " .$customBoundary ."px !important;' class='btn btn-block btn-social btn-vk btn-custom-dec login-button' onClick='moOpenIdLogin(" . '"vkontakte"' . ");'> <i style='padding-top:" . ($customHeight-35) . "px !important' class='fa fa-vk'></i>" . $buttonText . " Vkontakte</a>"; 
				 }
				else{ 
				
				$html .= "<a  onClick='moOpenIdLogin(" . '"vkontakte"' . ");' title= ' ".$customTextofTitle." Vkontakte'><img alt='Vkontakte' style='width:" . $customSize ."px !important;height: " . $customSize ."px !important;margin-left: " . ($spacebetweenicons-4) ."px !important' src='" . plugins_url( 'includes/images/icons/vk.png', __FILE__ ) . "' class='login-button " .$selected_theme . "' ></a>";
				
				}
				}

				if( get_option('mo_openid_twitter_enable') ) {
					if($selected_theme == 'longbutton'){
				 $html .= "<a  style='width: " . $customWidth . "px !important;padding-top:" . ($customHeight-29) . "px !important;padding-bottom:" . ($customHeight-29) . "px !important;margin-bottom: " . ($spacebetweenicons-5)  . "px !important;border-radius: " .$customBoundary ."px !important;' class='btn btn-block btn-social btn-twitter btn-custom-dec login-button' onClick='moOpenIdLogin(" . '"twitter"' . ");'> <i style='padding-top:" . ($customHeight-35) . "px !important' class='fa fa-twitter'></i>" . $buttonText . " Twitter</a>"; }
				else{ 
						$html .= "<a title= ' ".$customTextofTitle." Twitter' onClick='moOpenIdLogin(" . '"twitter"' . ");' ><img alt='Twitter' style='width:" . $customSize ."px !important;height: " . $customSize ."px !important;margin-left: " . ($spacebetweenicons-4) ."px !important' src='" . plugins_url( 'includes/images/icons/twitter.png', __FILE__ ) . "' class='login-button " .$selected_theme . "' ></a>";
				}

				}
			if( get_option('mo_openid_linkedin_enable') ) {
							if($selected_theme == 'longbutton'){ 
					 $html .= "<a  style='width: " . $customWidth . "px !important;padding-top:" . ($customHeight-29) . "px !important;padding-bottom:" . ($customHeight-29) . "px !important;margin-bottom: " . ($spacebetweenicons-5)  . "px !important;border-radius: " .$customBoundary. "px !important;' class='btn btn-block btn-social btn-linkedin btn-custom-dec login-button' onClick='moOpenIdLogin(" . '"linkedin"' . ");'> <i style='padding-top:" . ($customHeight-35) . "px !important' class='fa fa-linkedin'></i>" . $buttonText . " LinkedIn</a>";
				 }
				else{ 
						$html .= "<a title= ' ".$customTextofTitle." LinkedIn' onClick='moOpenIdLogin(" . '"linkedin"' . ");' ><img alt='LinkedIn' style='width:" . $customSize ."px !important;height: " . $customSize ."px !important;margin-left: " . ($spacebetweenicons-4) ."px !important' src='" . plugins_url( 'includes/images/icons/linkedin.png', __FILE__ ) . "' class='login-button " .$selected_theme . "' ></a>";
				 }
				}if( get_option('mo_openid_instagram_enable') ) {
					if($selected_theme == 'longbutton'){	
				 $html .= "<a  style='width: " . $customWidth . "px !important;padding-top:" . ($customHeight-29) . "px !important;padding-bottom:" . ($customHeight-29) . "px !important;margin-bottom: " . ($spacebetweenicons-5)  . "px !important;border-radius: " .$customBoundary. "px !important;' class='btn btn-block btn-social btn-instagram btn-custom-dec login-button' onClick='moOpenIdLogin(" . '"instagram"' . ");'> <i style='padding-top:" . ($customHeight-35) . "px !important' class='fa fa-instagram'></i>" . $buttonText . " Instagram</a>";
				 }
				else{ 
					$html .= "<a title= ' ".$customTextofTitle." Instagram' onClick='moOpenIdLogin(" . '"instagram"' . ");' ><img alt='Instagram' style='width:" . $customSize ."px !important;height: " . $customSize ."px !important;margin-left: " . ($spacebetweenicons-4) ."px !important' src='" . plugins_url( 'includes/images/icons/instagram.png', __FILE__ ) . "' class='login-button " .$selected_theme . "' ></a>";
				}
				}if( get_option('mo_openid_amazon_enable') ) {
					if($selected_theme == 'longbutton'){
				      $html .= "<a  style='width: " . $customWidth . "px !important;padding-top:" . ($customHeight-29) . "px !important;padding-bottom:" . ($customHeight-29) . "px !important;margin-bottom: " . ($spacebetweenicons-5)  . "px !important;border-radius: " .$customBoundary. "px !important;' class='btn btn-block btn-social btn-soundcloud btn-custom-dec login-button' onClick='moOpenIdLogin(" . '"amazon"' . ");'> <i style='padding-top:" . ($customHeight-35) . "px !important' class='fa fa-amazon'></i>" . $buttonText . " Amazon</a>"; 
				 }
				else{
					$html .= "<a title= ' ".$customTextofTitle." Amazon' onClick='moOpenIdLogin(" . '"amazon"' . ");' ><img alt='Amazon' style='width:" . $customSize ."px !important;height: " . $customSize ."px !important;margin-left: " . ($spacebetweenicons-4) ."px !important' src='" . plugins_url( 'includes/images/icons/amazon.png', __FILE__ ) . "' class='login-button " .$selected_theme . "' ></a>";
				}
				}if( get_option('mo_openid_salesforce_enable') ) {
						if($selected_theme == 'longbutton'){
				 $html .= "<a  style='width: " . $customWidth . "px !important;padding-top:" . ($customHeight-29) . "px !important;padding-bottom:" . ($customHeight-29) . "px !important;margin-bottom: " . ($spacebetweenicons-5)  . "px !important;border-radius: " .$customBoundary. "px !important;' class='btn btn-block btn-social btn-vimeo btn-custom-dec login-button' onClick='moOpenIdLogin(" . '"salesforce"' . ");'> <i style='padding-top:" . ($customHeight-35) . "px !important' class='fa fa-cloud'></i>" . $buttonText . " Salesforce</a>"; 
				 }
				else{ 
					$html .= "<a title= ' ".$customTextofTitle." Salesforce' onClick='moOpenIdLogin(" . '"salesforce"' . ");' ><img alt='Salesforce' style='width:" . $customSize ."px !important;height: " . $customSize ."px !important;margin-left: " . ($spacebetweenicons-4) ."px !important' src='" . plugins_url( 'includes/images/icons/salesforce.png', __FILE__ ) . "' class='login-button " .$selected_theme . "' ></a>";
				}
				}if( get_option('mo_openid_windowslive_enable') ) {
					if($selected_theme == 'longbutton'){
						$html .= "<a  style='width: " . $customWidth . "px !important;padding-top:" . ($customHeight-29) . "px !important;padding-bottom:" . ($customHeight-29) . "px !important;margin-bottom: " . ($spacebetweenicons-5)  . "px !important;border-radius: " .$customBoundary. "px !important;' class='btn btn-block btn-social btn-microsoft btn-custom-dec login-button' onClick='moOpenIdLogin(" . '"windowslive"' . ");'> <i style='padding-top:" . ($customHeight-35) . "px !important' class='fa fa-windows'></i>" . $buttonText . " Microsoft</a>";
					}
				else{ 
						$html .= "<a title= ' ".$customTextofTitle." Microsoft' onClick='moOpenIdLogin(" . '"windowslive"' . ");' ><img alt='Windowslive' style='width:" . $customSize ."px !important;height: " . $customSize ."px !important;margin-left: " . ($spacebetweenicons-4) ."px !important' src='" . plugins_url( 'includes/images/icons/windowslive.png', __FILE__ ) . "' class='login-button " .$selected_theme . "' ></a>";
				}
				}
				}
				
				
				
				if($customTheme == 'custom'){
				if( get_option('mo_openid_facebook_enable') ) {
					if($selected_theme == 'longbutton'){
				 $html .= "<a   onClick='moOpenIdLogin(" . '"facebook"' . ");' style='width:" . ($customWidth) . "px !important;padding-top:" . ($customHeight-29) . "px !important;padding-bottom:" . ($customHeight-29) . "px !important;margin-bottom:" . ($spacebetweenicons-5) . "px !important; background:#" . $customBackground . "!important;border-radius: " .$customBoundary. "px !important;' class='btn btn-block btn-social btn-customtheme btn-custom-dec login-button' > <i style='padding-top:" .($customHeight-35) . "px !important' class='fa fa-facebook'></i> " . $buttonText . " Facebook</a>"; 
				 }
				else{ 
					$html .= "<a title= ' ".$customTextofTitle." Facebook' onClick='moOpenIdLogin(" . '"facebook"' . ");' ><i style='width:" . $customSize . "px !important;height:" . $customSize . "px !important;margin-left:" . ($spacebetweenicons-4) . "px !important;background:#" . $customBackground . " !important;font-size: " . ($customSize-16) . "px !important;'  class='fa fa-facebook custom-login-button  " . $selected_theme . "' ></i></a>";
				}

				}

				if( get_option('mo_openid_google_enable') ) {
					if($selected_theme == 'longbutton'){
						$html .= "<a   onClick='moOpenIdLogin(" . '"google"' . ");' style='width:" . ($customWidth) . "px !important;padding-top:" . ($customHeight-29) . "px !important;padding-bottom:" . ($customHeight-29) . "px !important;margin-bottom:" . ($spacebetweenicons-5) . "px !important; background:#" . $customBackground . "!important;border-radius: " .$customBoundary. "px !important;' class='btn btn-block btn-social btn-customtheme btn-custom-dec login-button' > <i style='padding-top:" .($customHeight-35) . "px !important' class='fa fa-google-plus'></i> " . $buttonText . " Google</a>"; 
					}
				else{ 
						$html .= "<a title= ' ".$customTextofTitle." Google' onClick='moOpenIdLogin(" . '"google"' . ");' title= ' ". $customTextofTitle."  Google'><i style='width:" . $customSize . "px !important;height:" . $customSize . "px !important;margin-left:" . ($spacebetweenicons-4) . "px !important;background:#" . $customBackground . " !important;font-size: " . ($customSize-16) . "px !important;'  class='fa fa-google-plus custom-login-button  " . $selected_theme . "' ></i></a>";
				
					}
				}

				if( get_option('mo_openid_vkontakte_enable') ) {
					if($selected_theme == 'longbutton'){
						$html .= "<a   onClick='moOpenIdLogin(" . '"vkontakte"' . ");' style='width:" . ($customWidth) . "px !important;padding-top:" . ($customHeight-29) . "px !important;padding-bottom:" . ($customHeight-29) . "px !important;margin-bottom:" . ($spacebetweenicons-5) . "px !important; background:#" . $customBackground . "!important;border-radius: " .$customBoundary. "px !important;' class='btn btn-block btn-social btn-customtheme btn-custom-dec login-button' > <i style='padding-top:" .($customHeight-35) . "px !important' class='fa fa-vk'></i> " . $buttonText . " Vkontakte</a>"; 
					}
				else{ 
						$html .= "<a title= ' ".$customTextofTitle." Vkontakte' onClick='moOpenIdLogin(" . '"vkontakte"' . ");' title= ' ". $customTextofTitle."  Vkontakte'><i style='width:" . $customSize . "px !important;height:" . $customSize . "px !important;margin-left:" . ($spacebetweenicons-4) . "px !important;background:#" . $customBackground . " !important;font-size: " . ($customSize-16) . "px !important;'  class='fa fa-vk custom-login-button  " . $selected_theme . "' ></i></a>";
				
					}
				}

				if( get_option('mo_openid_twitter_enable') ) {
					if($selected_theme == 'longbutton'){
				 $html .= "<a   onClick='moOpenIdLogin(" . '"twitter"' . ");' style='width:" . ($customWidth) . "px !important;padding-top:" . ($customHeight-29) . "px !important;padding-bottom:" . ($customHeight-29) . "px !important;margin-bottom:" . ($spacebetweenicons-5) . "px !important; background:#" . $customBackground . "!important;border-radius: " .$customBoundary. "px !important;' class='btn btn-block btn-social btn-customtheme btn-custom-dec login-button' > <i style='padding-top:" .($customHeight-35) . "px !important' class='fa fa-twitter'></i> " . $buttonText . " Twitter</a>"; 
				 }
				else{ 
					$html .= "<a title= ' ".$customTextofTitle." Twitter' onClick='moOpenIdLogin(" . '"twitter"' . ");' ><i style='width:" . $customSize . "px !important;height:" . $customSize . "px !important;margin-left:" . ($spacebetweenicons-4) . "px !important;background:#" . $customBackground . " !important;font-size: " . ($customSize-16) . "px !important;'  class='fa fa-twitter custom-login-button  " . $selected_theme . "' ></i></a>";
				}

				}
			if( get_option('mo_openid_linkedin_enable') ) {
							if($selected_theme == 'longbutton'){ 
					 $html .= "<a   onClick='moOpenIdLogin(" . '"linkedin"' . ");' style='width:" . ($customWidth) . "px !important;padding-top:" . ($customHeight-29) . "px !important;padding-bottom:" . ($customHeight-29) . "px !important;margin-bottom:" . ($spacebetweenicons-5) . "px !important; background:#" . $customBackground . "!important;border-radius: " .$customBoundary. "px !important;' class='btn btn-block btn-social btn-customtheme btn-custom-dec login-button' > <i style='padding-top:" .($customHeight-35) . "px !important' class='fa fa-linkedin'></i> " . $buttonText . " LinkedIn</a>"; 
				 }
				else{ 
					$html .= "<a title= ' ".$customTextofTitle." LinkedIn' onClick='moOpenIdLogin(" . '"linkedin"' . ");' ><i style='width:" . $customSize . "px !important;height:" . $customSize . "px !important;margin-left:" . ($spacebetweenicons-4) . "px !important;background:#" . $customBackground . " !important;font-size: " . ($customSize-16) . "px !important;'  class='fa fa-linkedin custom-login-button  " . $selected_theme . "' ></i></a>";
				}
				}if( get_option('mo_openid_instagram_enable') ) {
					if($selected_theme == 'longbutton'){
						 $html .= "<a   onClick='moOpenIdLogin(" . '"instagram"' . ");' style='width:" . ($customWidth) . "px !important;padding-top:" . ($customHeight-29) . "px !important;padding-bottom:" . ($customHeight-29) . "px !important;margin-bottom:" . ($spacebetweenicons-5) . "px !important; background:#" . $customBackground . "!important;border-radius: " .$customBoundary. "px !important;' class='btn btn-block btn-social btn-customtheme btn-custom-dec login-button' > <i style='padding-top:" .($customHeight-35) . "px !important' class='fa fa-instagram'></i> " . $buttonText . " Instagram</a>"; 
				 }
				else{
					$html .= "<a title= ' ".$customTextofTitle." Instagram' onClick='moOpenIdLogin(" . '"instagram"' . ");' ><i style='width:" . $customSize . "px !important;height:" . $customSize . "px !important;margin-left:" . ($spacebetweenicons-4) . "px !important;background:#" . $customBackground . " !important;font-size: " . ($customSize-16) . "px !important;'  class='fa fa-instagram custom-login-button  " . $selected_theme . "' ></i></a>";
				}
				}if( get_option('mo_openid_amazon_enable') ) {
					if($selected_theme == 'longbutton'){
				 $html .= "<a   onClick='moOpenIdLogin(" . '"amazon"' . ");' style='width:" . ($customWidth) . "px !important;padding-top:" . ($customHeight-29) . "px !important;padding-bottom:" . ($customHeight-29) . "px !important;margin-bottom:" . ($spacebetweenicons-5) . "px !important; background:#" . $customBackground . "!important;border-radius: " .$customBoundary. "px !important;' class='btn btn-block btn-social btn-customtheme btn-custom-dec login-button' > <i style='padding-top:" .($customHeight-35) . "px !important' class='fa fa-amazon'></i> " . $buttonText . " Amazon</a>"; 
				 }
				else{ 
					$html .= "<a title= ' ".$customTextofTitle." Amazon'  onClick='moOpenIdLogin(" . '"amazon"' . ");' ><i style='width:" . $customSize . "px !important;height:" . $customSize . "px !important;margin-left:" . ($spacebetweenicons-4) . "px !important;background:#" . $customBackground . " !important;font-size: " . ($customSize-16) . "px !important;'  class='fa fa-amazon custom-login-button  " . $selected_theme . "' ></i></a>";
				}
				}if( get_option('mo_openid_salesforce_enable') ) {
						if($selected_theme == 'longbutton'){
				  $html .= "<a   onClick='moOpenIdLogin(" . '"salesforce"' . ");' style='width:" . ($customWidth) . "px !important;padding-top:" . ($customHeight-29) . "px !important;padding-bottom:" . ($customHeight-29) . "px !important;margin-bottom:" . ($spacebetweenicons-5) . "px !important; background:#" . $customBackground . "!important;border-radius: " .$customBoundary. "px !important;' class='btn btn-block btn-social btn-customtheme btn-custom-dec login-button' > <i style='padding-top:" .($customHeight-35) . "px !important' class='fa fa-cloud'></i> " . $buttonText . " Salesforce</a>"; 
				 }
				else{ 
					$html .= "<a title= ' ".$customTextofTitle." Salesforce' onClick='moOpenIdLogin(" . '"salesforce"' . ");' ><i style='width:" . $customSize . "px !important;height:" . $customSize . "px !important;margin-left:" . ($spacebetweenicons-4) . "px !important;background:#" . $customBackground . " !important;font-size: " . ($customSize-16) . "px !important;'  class='fa fa-cloud custom-login-button  " . $selected_theme . "' ></i></a>";
				}
				}if( get_option('mo_openid_windowslive_enable') ) {
					if($selected_theme == 'longbutton'){
				  $html .= "<a   onClick='moOpenIdLogin(" . '"windowslive"' . ");' style='width:" . ($customWidth) . "px !important;padding-top:" . ($customHeight-29) . "px !important;padding-bottom:" . ($customHeight-29) . "px !important;margin-bottom:" . ($spacebetweenicons-5) . "px !important; background:#" . $customBackground . "!important;border-radius: " .$customBoundary. "px !important;' class='btn btn-block btn-social btn-customtheme btn-custom-dec login-button' > <i style='padding-top:" .($customHeight-35) . "px !important' class='fa fa-windows'></i> " . $buttonText . " Microsoft</a>"; 
				 }
				else{ 
					$html .= "<a title= ' ".$customTextofTitle." Microsoft' onClick='moOpenIdLogin(" . '"windowslive"' . ");' ><i style='width:" . $customSize . "px !important;height:" . $customSize . "px !important;margin-left:" . ($spacebetweenicons-4) . "px !important;background:#" . $customBackground . " !important;font-size: " . ($customSize-16) . "px !important;'  class='fa fa-windows custom-login-button  " . $selected_theme . "' ></i></a>";
				}
				}
				}
				 $html .= '</div> <br>';


			} else {
				
				$html .= '<div>No apps configured. Please contact your administrator.</div>';
			
			}
		}else {
			global $current_user;
	     	$current_user = wp_get_current_user();
			$customLogoutName = str_replace('##username##', $current_user->display_name, $customLogoutName);
			$flw = __($customLogoutLink,"flw");
			if (empty($customLogoutName)  || empty($customLogoutLink)) {
				$html .= '<div id="logged_in_user" class="mo_openid_login_wid">' . $customLogoutName . ' <a href=' . $logoutUrl .' title=" ' . $flw . '"> ' . $flw . '</a></div>';
			}
			else {
				$html .= '<div id="logged_in_user" class="mo_openid_login_wid">' . $customLogoutName . ' <a href=' . $logoutUrl .' title=" ' . $flw . '"> ' . $flw . '</a></div>';
			}
		}
		
		return $html;
	}
	
	private function mo_openid_load_login_script() {
		?>
		<script type="text/javascript">
			function moOpenIdLogin(app_name) {
				<?php
					if(isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off'){
						$http = "https://";
					} else {
						$http =  "http://";
					}
					if ( strpos($_SERVER['REQUEST_URI'],'wp-login.php') !== FALSE){
						$redirect_url = site_url() . '/?option=getmosociallogin&app_name=';

					}else{
				    	$redirect_url = $http . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
						if(strpos($redirect_url, '?') !== false) {
							$redirect_url .= '&option=getmosociallogin&app_name=';
						} else {
							$redirect_url .= '?option=getmosociallogin&app_name=';
						}
					}
				?>
				window.location.href = '<?php echo $redirect_url; ?>' + app_name;
			}
		</script>
		<?php
	}

	/*public function error_message(){
		if(isset($_SESSION['msg']) and $_SESSION['msg']){
			echo '<div class="'.$_SESSION['msg_class'].'">'.$_SESSION['msg'].'</div>';
			unset($_SESSION['msg']);
			unset($_SESSION['msg_class']);
		}
	}*/

}


/**
 * Sharing Widget Horizontal
 *
 */
class mo_openid_sharing_hor_wid extends WP_Widget {

	public function __construct() {
		parent::__construct(
	 		'mo_openid_sharing_hor_wid',
			'miniOrange Sharing - Horizontal',
			array( 
				'description' => __( 'Share using horizontal widget. Lets you share with Social Apps like Google, Facebook, LinkedIn, Pinterest, Reddit.', 'flw' ),
				'customize_selective_refresh' => true,
			)
		);
	}


	public function widget( $args, $instance ) {
		extract( $args );

		echo $args['before_widget'];
			$this->show_sharing_buttons_horizontal();

		echo $args['after_widget'];
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['wid_title'] = strip_tags( $new_instance['wid_title'] );
		return $instance;
	}


    public function show_sharing_buttons_horizontal(){
		global $post;
		$title = str_replace('+', '%20', urlencode($post->post_title));
		$content=strip_shortcodes( strip_tags( get_the_content() ) );
		$post_content=$content;
		$excerpt = '';
		$landscape = 'horizontal';
		include( plugin_dir_path( __FILE__ ) . 'class-mo-openid-social-share.php');
	}

}


/**
 * Sharing Vertical Widget
 *
 */
class mo_openid_sharing_ver_wid extends WP_Widget {

	public function __construct() {
		parent::__construct(
	 		'mo_openid_sharing_ver_wid',
			'miniOrange Sharing - Vertical',
			array( 
				'description' => __( 'Share using a vertical floating widget. Lets you share with Social Apps like Google, Facebook, LinkedIn, Pinterest, Reddit.', 'flw' ), 
				'customize_selective_refresh' => true,
			)
		);
	 }


	public function widget( $args, $instance ) {
		extract( $args );
		extract( $instance );

		$wid_title = apply_filters( 'widget_title', $instance['wid_title'] );
		$alignment = apply_filters( 'alignment', isset($instance['alignment'])? $instance['alignment'] : 'left');
		$left_offset = apply_filters( 'left_offset', isset($instance['left_offset'])? $instance['left_offset'] : '20');
		$right_offset = apply_filters( 'right_offset', isset($instance['right_offset'])? $instance['right_offset'] : '0');
		$top_offset = apply_filters( 'top_offset', isset($instance['top_offset'])? $instance['top_offset'] : '100');
		$space_icons = apply_filters( 'space_icons', isset($instance['space_icons'])? $instance['space_icons'] : '10');

		echo $args['before_widget'];
		if ( ! empty( $wid_title ) )
			echo $args['before_title'] . $wid_title . $args['after_title'];
		
		echo "<div class='mo_openid_vertical' style='" .(isset($alignment) && $alignment != '' && isset($instance[$alignment.'_offset']) ? $alignment .': '. ( $instance[$alignment.'_offset'] == '' ? 0 : $instance[$alignment.'_offset'] ) .'px;' : '').(isset($top_offset) ? 'top: '. ( $top_offset == '' ? 0 : $top_offset ) .'px;' : '') ."'>";
		
		$this->show_sharing_buttons_vertical($space_icons);
		
		echo '</div>';

		echo $args['after_widget'];
	}

	/*Called when user changes configuration in Widget Admin Panel*/
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['wid_title'] = strip_tags( $new_instance['wid_title'] );
		$instance['alignment'] = $new_instance['alignment'];
		$instance['left_offset'] = $new_instance['left_offset'];
		$instance['right_offset'] = $new_instance['right_offset'];
		$instance['top_offset'] = $new_instance['top_offset'];
		$instance['space_icons'] = $new_instance['space_icons'];
		return $instance;
	}


    public function show_sharing_buttons_vertical($space_icons){
		global $post;
		if($post->post_title) {
			$title = str_replace('+', '%20', urlencode($post->post_title));
		} else {
			$title = get_bloginfo( 'name' );
		}
		$content=strip_shortcodes( strip_tags( get_the_content() ) );
		$post_content=$content;
		$excerpt = '';
		$landscape = 'vertical';
		
		include( plugin_dir_path( __FILE__ ) . 'class-mo-openid-social-share.php');
	}
	
	/** Widget edit form at admin panel */ 
	function form( $instance ) { 
		/* Set up default widget settings. */ 
		$defaults = array('alignment' => 'left', 'left_offset' => '20', 'right_offset' => '0', 'top_offset' => '100' , 'space_icons' => '10');
		
		foreach( $instance as $key => $value ){
			$instance[ $key ] = esc_attr( $value );
		}
		
		$instance = wp_parse_args( (array)$instance, $defaults );
		?> 
		<p> 
			<script>
			function moOpenIDVerticalSharingOffset(alignment){
				if(alignment == 'left'){
					jQuery('.moVerSharingLeftOffset').css('display', 'block');
					jQuery('.moVerSharingRightOffset').css('display', 'none');
				}else{
					jQuery('.moVerSharingLeftOffset').css('display', 'none');
					jQuery('.moVerSharingRightOffset').css('display', 'block');
				}
			}
			</script>
			<label for="<?php echo $this->get_field_id( 'alignment' ); ?>">Alignment</label> 
			<select onchange="moOpenIDVerticalSharingOffset(this.value)" style="width: 95%" id="<?php echo $this->get_field_id( 'alignment' ); ?>" name="<?php echo $this->get_field_name( 'alignment' ); ?>">
				<option value="left" <?php echo $instance['alignment'] == 'left' ? 'selected' : ''; ?>>Left</option>
				<option value="right" <?php echo $instance['alignment'] == 'right' ? 'selected' : ''; ?>>Right</option>
			</select>
			<div class="moVerSharingLeftOffset" <?php echo $instance['alignment'] == 'right' ? 'style="display: none"' : ''; ?>>
				<label for="<?php echo $this->get_field_id( 'left_offset' ); ?>">Left Offset</label> 
				<input style="width: 95%" id="<?php echo $this->get_field_id( 'left_offset' ); ?>" name="<?php echo $this->get_field_name( 'left_offset' ); ?>" type="text" value="<?php echo $instance['left_offset']; ?>" />px<br/>
			</div>
			<div class="moVerSharingRightOffset" <?php echo $instance['alignment'] == 'left' ? 'style="display: none"' : ''; ?>>
				<label for="<?php echo $this->get_field_id( 'right_offset' ); ?>">Right Offset</label> 
				<input style="width: 95%" id="<?php echo $this->get_field_id( 'right_offset' ); ?>" name="<?php echo $this->get_field_name( 'right_offset' ); ?>" type="text" value="<?php echo $instance['right_offset']; ?>" />px<br/>
			</div>
			<label for="<?php echo $this->get_field_id( 'top_offset' ); ?>">Top Offset</label> 
			<input style="width: 95%" id="<?php echo $this->get_field_id( 'top_offset' ); ?>" name="<?php echo $this->get_field_name( 'top_offset' ); ?>" type="text" value="<?php echo $instance['top_offset']; ?>" />px<br/>
			<label for="<?php echo $this->get_field_id( 'space_icons' ); ?>">Space between icons</label> 
			<input style="width: 95%" id="<?php echo $this->get_field_id( 'space_icons' ); ?>" name="<?php echo $this->get_field_name( 'space_icons' ); ?>" type="text" value="<?php echo $instance['space_icons']; ?>" />px<br/>
		</p>
	<?php
	}

}
 

	function mo_openid_start_session() {
		if( !session_id() ) {
			session_start();
		}
	}

	function mo_openid_end_session() {
		if( session_id() ) {
			session_destroy();
		}
	}

	function mo_openid_login_validate(){
		if( isset( $_REQUEST['option'] ) and strpos( $_REQUEST['option'], 'getmosociallogin' ) !== false ) {
			$client_name = "wordpress";
			$timestamp = round( microtime(true) * 1000 );
			$api_key = get_option('mo_openid_admin_api_key');
			$token = $client_name . ':' . number_format($timestamp, 0, '', ''). ':' . $api_key;

			$customer_token = get_option('mo_openid_customer_token');
			$blocksize = 16;
			$pad = $blocksize - ( strlen( $token ) % $blocksize );
			$token =  $token . str_repeat( chr( $pad ), $pad );
			$token_params_encrypt = mcrypt_encrypt( MCRYPT_RIJNDAEL_128, $customer_token, $token, MCRYPT_MODE_ECB );
			$token_params_encode = base64_encode( $token_params_encrypt );
			$token_params = urlencode( $token_params_encode );
			$userdata = get_option('moopenid_user_attributes')?'true':'false';
			
			$http = isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? "https://" : "http://";

			$parts = parse_url($http . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
			parse_str($parts['query'], $query);
			$post = isset( $query['p'] ) ? '?p=' . $query['p'] : '';
			
			$base_return_url =  $http . $_SERVER["HTTP_HOST"] . strtok($_SERVER["REQUEST_URI"],'?') . $post;

			$return_url = strpos($base_return_url, '?') !== false ? urlencode( $base_return_url . '&option=moopenid' ): urlencode( $base_return_url . '?option=moopenid' );

			$url = get_option('mo_openid_host_name') . '/moas/openid-connect/client-app/authenticate?token=' . $token_params . '&userdata=' . $userdata. '&id=' . get_option('mo_openid_admin_customer_key') . '&encrypted=true&app=' . $_REQUEST['app_name'] . '_oauth&returnurl=' . $return_url . '&encrypt_response=true';
			wp_redirect( $url );
			exit;
		}

		if( isset( $_REQUEST['option'] ) and strpos( $_REQUEST['option'], 'moopenid' ) !== false ){
			//Decrypt all entries
			$decrypted_email = isset($_POST['email']) ? mo_openid_decrypt($_POST['email']): '';
			$decrypted_user_name = isset($_POST['username']) ? mo_openid_decrypt($_POST['username']): '';
			$decrypted_user_picture = isset($_POST['profilePic']) ? mo_openid_decrypt($_POST['profilePic']): '';
			$decrypted_user_url = isset($_POST['profileUrl']) ? mo_openid_decrypt($_POST['profileUrl']): '';
			$decrypted_first_name = isset($_POST['firstName']) ? mo_openid_decrypt($_POST['firstName']): '';
			$decrypted_last_name = isset($_POST['lastName']) ? mo_openid_decrypt($_POST['lastName']): '';
			
			//Calculate user email
			if( isset( $decrypted_email ) && strcmp($decrypted_email,'')!=0 ) {
				$user_email = $decrypted_email;
			} else if( isset( $decrypted_user_name )){
				$user_email = str_replace(" ","_",$decrypted_user_name).'@social-user.com';
			}
			
			//Set Display Picture
			$user_picture = $decrypted_user_picture;
			
			//Check if username is equal to full name, set username as email
			if(empty($decrypted_user_name)){
				$email = array();
				$email = explode('@', $decrypted_email);
				$username = $email[0];
			} else {
				if(strpos($decrypted_user_name, ' ') !== FALSE){
					$username_split = explode("@", $decrypted_user_name);
					$username = $username_split[0];
				} else {
					$username = $decrypted_user_name;
				}
			}

			//Set User URL
			$user_url = $decrypted_user_url;
			
			//Set User Display Name
			if(isset( $_POST['firstName'] ) && isset( $_POST['lastName'] )){
				if(strcmp($decrypted_first_name, $decrypted_last_name)!=0)
					$user_full_name = $decrypted_first_name.' '.$decrypted_last_name;
				else
					$user_full_name = $decrypted_first_name;
				$first_name = $decrypted_first_name;
				$last_name = $decrypted_last_name;
			}	
			else
				$user_full_name = $username;
			
			if( $user_email ) {
				global $wpdb;
				$user_email = sanitize_email($user_email);
				$username = preg_replace('/[\x00-\x09\x0B\x0C\x0E-\x1F\x7F]/', '', $username);
				$username = strtolower(str_replace(" ","",$username));

				$orignal_username = ''; 
				$orignal_email = '';

				//Check for string in another language
				if(strlen($username) != mb_strlen($username, 'utf-8') || strlen($user_email) != mb_strlen($user_email, 'utf-8')){
					$orignal_username = $username;
					if(strlen($user_email) != mb_strlen($user_email, 'utf-8')) {
						$orignal_email = $user_email;
						$server_name = array();
						if(strlen($user_full_name) != mb_strlen($user_full_name, 'utf-8')) {
							$server_name = explode(".", $_SERVER['SERVER_NAME']);
							$index = count($server_name) >= 3 ? 1 : 0;
							$username = $server_name[$index] . '_' . get_option('mo_openid_user_number');
							$user_email = $server_name[$index] . '_' . get_option('mo_openid_user_number') . '@social-user.com';
							update_option('mo_openid_user_number', get_option('mo_openid_user_number') + 1);
						} else {
							$user_full_name = preg_replace('/[\x00-\x09\x0B\x0C\x0E-\x1F\x7F]/', '', $user_full_name);
							$username = strtolower(str_replace(" ","",$user_full_name));
							$user_email = str_replace(" ","_",$user_full_name).'@social-user.com';
						}
					} else {
						$email_explode = array();
						$email_explode = explode("@", $user_email);
						$username = $email_explode[0];
					}
				}

				//Checking if email or username already exist
				$email_user_id = $wpdb->get_var($wpdb->prepare("SELECT ID FROM $wpdb->users where user_email = %s", $user_email));
				$username_user_id = $wpdb->get_var($wpdb->prepare("SELECT ID FROM $wpdb->users where user_login = %s", $username));
				if( !isset($email_user_id) && !isset($username_user_id) && (!empty($orignal_username) || !empty($orignal_email))) {
					$email_user_id = $wpdb->get_var($wpdb->prepare("SELECT user_id FROM $wpdb->usermeta where meta_key = 'moopenid_orignal_email' and meta_value = %s", $orignal_email));
					$username_user_id = $wpdb->get_var($wpdb->prepare("SELECT user_id FROM $wpdb->usermeta where meta_key = 'moopenid_orignal_username' and meta_value = %s", $orignal_username));
				}

				mo_openid_start_session();
				if( isset($email_user_id)) { // user is a member
					  $user 	= get_user_by('id', $email_user_id );
					  $user_id 	= $user->ID;
					  if(get_option('moopenid_social_login_avatar') && isset($user_picture))
							update_user_meta($user_id, 'moopenid_user_avatar', $user_picture);
					  $_SESSION['mo_login'] = true;
					  do_action( 'wp_login', $user->user_login, $user );
					  wp_set_auth_cookie( $user_id, true );
				} else if( isset($username_user_id) ) { // user is a member
					  $user 	= get_user_by('id', $username_user_id );
					  $user_id 	= $user->ID;
					  if(get_option('moopenid_social_login_avatar') && isset($user_picture))
							update_user_meta($user_id, 'moopenid_user_avatar', $user_picture);
					  $_SESSION['mo_login'] = true;
					  do_action( 'wp_login', $user->user_login, $user );
					  wp_set_auth_cookie( $user_id, true );
				} else { // this user is a guest
					if(get_option('mo_openid_auto_register_enable')) {
						$random_password 	= wp_generate_password( 10, false );
						$userdata = array(
											'user_login'  =>  $username,
											'user_email'    =>  $user_email,
											'user_pass'   =>  $random_password,
											'display_name' => $user_full_name,
											'first_name' => $first_name,
											'last_name' => $last_name,
											'user_url' => $user_url,
										);
						
						  
						$user_id 	= wp_insert_user( $userdata);
						
						if(is_wp_error( $user_id )) {
							//print_r($user_id);
							echo '<br/>There was an error in registration. Please contact your administrator.';
							exit();
						}
						
						$user	= get_user_by('email', $user_email );
						if(get_option('mo_openid_login_role_mapping') && mo_openid_is_customer_valid()){
							$user->set_role( get_option('mo_openid_login_role_mapping') );
						}
						//Add meta if username in other language
						if(!empty($orignal_username)) {
							update_user_meta($user_id, 'moopenid_orignal_username', $orignal_username);
						}
						//Add meta if email in other language
						if(!empty($orignal_email)) {
							update_user_meta($user_id, 'moopenid_orignal_email', $orignal_email);
						}
						if(get_option('moopenid_social_login_avatar') && isset($user_picture)){
							update_user_meta($user_id, 'moopenid_user_avatar', $user_picture);
						}
						$_SESSION['mo_login'] = true;
						do_action( 'user_register', $user_id);
						do_action( 'wp_login', $user->user_login, $user );
						wp_set_auth_cookie( $user_id, true );
					}
				}
			}
			
			$redirect_url = mo_openid_get_redirect_url();
			wp_redirect($redirect_url);
			exit;

		}
		
		if(isset($_REQUEST['autoregister']) and strpos($_REQUEST['autoregister'],'false') !== false) {
			if(!is_user_logged_in()) {
				mo_openid_disabled_register_message();
			}
		}
	}

	function mo_openid_decrypt($param) {
		if(strcmp($param,'null')!=0 && strcmp($param,'')!=0){
			$customer_token = get_option('mo_openid_customer_token');
			$base64decoded = base64_decode($param);
			$token_params_decrypt = mcrypt_decrypt( MCRYPT_RIJNDAEL_128, $customer_token, $base64decoded, MCRYPT_MODE_ECB );

			return $token_params_decrypt;
		}else{
			return '';
		}
	}
	
	function mo_openid_disabled_register_message() {
		wp_enqueue_script('thickbox');
		wp_enqueue_style('thickbox');
		wp_enqueue_script( 'mo-wp-settings-script',plugins_url('includes/js/settings_popup.js', __FILE__), array('jquery'));
		add_thickbox();
		$script = '<script>
						function getAutoRegisterDisabledMessage() {
							var disabledMessage = "' . get_option('mo_openid_register_disabled_message') . '";
							return disabledMessage;
						}
					</script>';
		echo $script;
	}
		
	function mo_openid_get_redirect_url() {
		$option = get_option( 'mo_openid_login_redirect' );
		$redirect_url = site_url();
		if( $option == 'same' ) {
			if(isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off'){
				$http = "https://";
			} else {
				$http =  "http://";
			}
			$redirect_url = urldecode(html_entity_decode(esc_url($http . $_SERVER["HTTP_HOST"] . str_replace('option=moopenid','',$_SERVER['REQUEST_URI']))));
			if(html_entity_decode(esc_url(remove_query_arg('ss_message', $redirect_url))) == wp_login_url() || strpos($_SERVER['REQUEST_URI'],'wp-login.php') !== FALSE || strpos($_SERVER['REQUEST_URI'],'wp-admin') !== FALSE){
				$redirect_url = site_url().'/';
			}
		} else if( $option == 'homepage' ) {
			$redirect_url = site_url();
		} else if( $option == 'dashboard' ) {
			$redirect_url = admin_url();
		} else if( $option == 'custom' ) {
			$redirect_url = get_option('mo_openid_login_redirect_url');
		}
		if(strpos($redirect_url,'?') !== FALSE) {
			$redirect_url .= get_option('mo_openid_auto_register_enable') ? '' : '&autoregister=false';
		} else{
			$redirect_url .= get_option('mo_openid_auto_register_enable') ? '' : '?autoregister=false';
		}
		return $redirect_url;
	}
	
	function mo_openid_redirect_after_logout($logout_url) {
		if(get_option('mo_openid_logout_redirection_enable')){
			$option = get_option( 'mo_openid_logout_redirect' );
			$redirect_url = site_url();
			if( $option == 'homepage' ) {
				$redirect_url = $logout_url . '&redirect_to=' .home_url()  ;
			}
			else if($option == 'currentpage'){
				if(isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off'){
					$http = "https://";
				} else {
					$http =  "http://";
				}
				$redirect_url = $logout_url . '&redirect_to=' . $http . $_SERVER["HTTP_HOST"] . $_SERVER['REQUEST_URI'];
			}
			else if($option == 'login') {
				$redirect_url = $logout_url . '&redirect_to=' . site_url() . '/wp-admin' ;
			}
			else if($option == 'custom') {
				$redirect_url = $logout_url . '&redirect_to=' . site_url() . (null !== get_option('mo_openid_logout_redirect_url')?get_option('mo_openid_logout_redirect_url'):'');
			}
			return $redirect_url;
		}else{
			return $logout_url;
		}
			
	} 

	function mo_openid_login_redirect($username = '', $user = NULL){
		mo_openid_start_session();
		if(is_string($username) && $username && is_object($user) && !empty($user->ID) && ($user_id = $user->ID) && isset($_SESSION['mo_login']) && $_SESSION['mo_login']){
			$_SESSION['mo_login'] = false;
			wp_set_auth_cookie( $user_id, true );
			$redirect_url = mo_openid_get_redirect_url();
			wp_redirect($redirect_url);
			exit;
		}
	}  

if(get_option('mo_openid_logout_redirection_enable') == 1){
	add_filter( 'logout_url', 'mo_openid_redirect_after_logout',0,1);
}
add_action( 'widgets_init', create_function( '', 'register_widget( "mo_openid_login_wid" );' ) );
add_action( 'widgets_init', create_function( '', 'return register_widget( "mo_openid_sharing_ver_wid" );' ) );
add_action( 'widgets_init', create_function( '', 'return register_widget( "mo_openid_sharing_hor_wid" );' ) );

add_action( 'init', 'mo_openid_login_validate' );
//add_action( 'init', 'mo_openid_start_session' );
//add_action( 'wp_logout', 'mo_openid_end_session' );
add_action( 'wp_login', 'mo_openid_login_redirect', 9, 2);
}
?>