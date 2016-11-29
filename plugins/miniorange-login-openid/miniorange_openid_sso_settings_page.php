<?php
function mo_register_openid() {
	if( isset( $_GET[ 'tab' ]) && $_GET[ 'tab' ] !== 'register' ) {
		$active_tab = $_GET[ 'tab' ];
	} else if(mo_openid_is_customer_registered()) {
		$active_tab = 'login';
	} else {
		$active_tab = 'register';
	}
	
	if(mo_openid_is_curl_installed()==0){ ?>
		<p style="color:red;">(Warning: <a href="http://php.net/manual/en/curl.installation.php" target="_blank">PHP CURL extension</a> is not installed or disabled) Please go to Troubleshooting for steps to enable curl.</p>
	<?php
	}?>
	<?php
	if(!mo_openid_is_extension_installed('mcrypt')) { ?>
		<div id="help_openid_mcrypt_title" class="mo_openid_title_panel">
			<div style="color:red;" class="mo_openid_help_title">(Warning: PHP mcrypt extension is not installed or disabled) (Why we need it?)</div>
		</div>
		<div id="help_openid_mcrypt" class="mo_openid_help_desc" hidden>
			PHP Mcrypt extension is required to Encrypt Social Login in such a way as to make it unreadable by anyone except those possessing special knowledge (usually referred to as a "key") that allows them to change the information back to its original, readable form.
			<br/>
			Encryption is important because it allows you to securely protect your users Social Login details that you don't want anyone else to have access to.
		</div>
	<?php
	}?>
<div id="tab">
	<h2 class="nav-tab-wrapper">
		<?php if(!mo_openid_is_customer_registered()) { ?>
			<a class="nav-tab <?php echo $active_tab == 'register' ? 'nav-tab-active' : ''; ?>" href="<?php echo add_query_arg( array('tab' => 'register'), $_SERVER['REQUEST_URI'] ); ?>">Account Setup</a>
		<?php } ?>
		<a class="nav-tab <?php echo $active_tab == 'login' ? 'nav-tab-active' : ''; ?>" href="<?php echo add_query_arg( array('tab' => 'login'), $_SERVER['REQUEST_URI'] ); ?>">Social Login</a>
		<a class="nav-tab <?php echo $active_tab == 'share' ? 'nav-tab-active' : ''; ?>" href="<?php echo add_query_arg( array('tab' => 'share'), $_SERVER['REQUEST_URI'] ); ?>">Social Sharing</a>
		<a class="nav-tab <?php echo $active_tab == 'comment' ? 'nav-tab-active' : ''; ?>" href="<?php echo add_query_arg( array('tab' => 'comment'), $_SERVER['REQUEST_URI'] ); ?>">Social Comments</a>
		<a class="nav-tab <?php echo $active_tab == 'shortcode' ? 'nav-tab-active' : ''; ?>" href="<?php echo add_query_arg( array('tab' => 'shortcode'), $_SERVER['REQUEST_URI'] ); ?>">Shortcode</a>
		<a class="nav-tab <?php echo $active_tab == 'pricing' ? 'nav-tab-active' : ''; ?>" href="<?php echo add_query_arg( array('tab' => 'pricing'), $_SERVER['REQUEST_URI'] ); ?>">Licensing Plans</a>
		<a class="nav-tab <?php echo $active_tab == 'help' ? 'nav-tab-active' : ''; ?>" href="<?php echo add_query_arg( array('tab' => 'help'), $_SERVER['REQUEST_URI'] ); ?>">Help & Troubleshooting</a>
	</h2>
</div>

<div id="mo_openid_settings">

	<div class="mo_container">
		<div id="mo_openid_msgs"></div>
			<table style="width:100%;">
				<tr>
					<!--td style="vertical-align:top;width:65%;"-->

						<?php
							if( $active_tab == 'share' ) {
								mo_openid_other_settings();
							} else if ( $active_tab == 'register') {
								if (get_option ( 'mo_openid_verify_customer' ) == 'true') {
									mo_openid_show_verify_password_page();
								} else if (trim ( get_option ( 'mo_openid_admin_email' ) ) != '' && trim ( get_option ( 'mo_openid_admin_api_key' ) ) == '' && get_option ( 'mo_openid_new_registration' ) != 'true') {
									mo_openid_show_verify_password_page();
								} else if(get_option('mo_openid_registration_status') == 'MO_OTP_DELIVERED_SUCCESS' || get_option('mo_openid_registration_status') == 'MO_OTP_VALIDATION_FAILURE' || get_option('mo_openid_registration_status') == 'MO_OTP_DELIVERED_FAILURE' ){
									mo_openid_show_otp_verification();
								}else if (! mo_openid_is_customer_registered()) {
									delete_option ( 'password_mismatch' );
									mo_openid_show_new_registration_page();
								}
							} else if($active_tab == 'login'){
								mo_openid_apps_config();
							} else if($active_tab == 'comment'){
								mo_openid_app_comment();
							} else if($active_tab == 'shortcode') {
								mo_openid_shortcode_info();
							}else if($active_tab == 'pricing') {
								mo_openid_pricing_info();
							}else if($active_tab == 'help') {
								mo_openid_troubleshoot_info();
							}
							
							

						?>
					<!--/td>
					<td style="vertical-align:top;padding-left:1%;">
						<?php echo miniorange_openid_support(); ?>
					</td-->
				</tr>
			</table>
		<?php

}
function mo_openid_show_new_registration_page() {
	update_option ( 'mo_openid_new_registration', 'true' );
	global $current_user;
	$current_user = wp_get_current_user();
	?>
		<td style="vertical-align:top;width:65%;">
		<!--Register with miniOrange-->
					<form name="f" method="post" action="" id="register-form">
								<input type="hidden" name="option" value="mo_openid_connect_register_customer" />
								
								
								
								<div class="mo_openid_table_layout">
									<?php if(!mo_openid_is_customer_registered()) { ?>
										<div style="display:block;margin-top:10px;color:red;background-color:rgba(251, 232, 0, 0.15);padding:5px;border:solid 1px rgba(255, 0, 9, 0.36);">
										Please <a href="<?php echo add_query_arg( array('tab' => 'register'), $_SERVER['REQUEST_URI'] ); ?>">Register or Login with miniOrange</a> to enable Social Login and Social Sharing. miniOrange takes care of creating applications for you so that you don't have to worry about creating applications in each social network.
										</div>
									<?php } ?>

										<h3>Register with miniOrange</h3>

										<p>Please enter a valid email that you have access to. You will be able to move forward after verifying an OTP that we will be sending to this email. <b>OR</b> Login using your miniOrange credentials.
										</p>
										<table class="mo_openid_settings_table">
											<tr>
												<td><b><font color="#FF0000">*</font>Email:</b></td>
												<td><input class="mo_openid_table_textbox" type="email" name="email"
													required placeholder="person@example.com"
													value="<?php echo $current_user->user_email;?>" /></td>
											</tr>
											<tr>
												<td><b><font color="#FF0000">*</font>Website/Company Name:</b></td>
												<td><input class="mo_openid_table_textbox" type="text" name="company"
													required placeholder="Enter website or company name" 
													value="<?php echo $_SERVER['SERVER_NAME']; ?>"/></td>
											</tr>
											<tr>
												<td><b>&nbsp;&nbsp;First Name:</b></td>
												<td><input class="mo_openid_table_textbox" type="text" name="fname"
													placeholder="Enter first name"
													value="<?php echo $current_user->user_firstname;?>" /></td>
											</tr>
											<tr>
												<td><b>&nbsp;&nbsp;Last Name:</b></td>
												<td><input class="mo_openid_table_textbox" type="text" name="lname"
													placeholder="Enter last name"
													value="<?php echo $current_user->user_lastname;?>" /></td>
											</tr>
											<tr>
												<td><b>&nbsp;&nbsp;Mobile number:</b></td>
												<td><input class="mo_openid_table_textbox" type="tel" id="phone"
													pattern="[\+]\d{10,14}|[\+]\d{1,4}[\s]\d{8,10}" name="phone"
													title="Mobile number with country code eg. +1xxxxxxxxxx"
													placeholder="Mobile number with country code eg. +1xxxxxxxxxx"
													value="<?php echo get_option('mo_openid_admin_phone');?>" /><br/>We will call only if you need support.</td>
												<td></td>
											</tr>
											<tr>
												<td><b><font color="#FF0000">*</font>Password:</b></td>
												<td><input class="mo_openid_table_textbox" required type="password"
													name="password" placeholder="Choose your password (Min. length 6)" /></td>
											</tr>
											<tr>
												<td><b><font color="#FF0000">*</font>Confirm Password:</b></td>
												<td><input class="mo_openid_table_textbox" required type="password"
													name="confirmPassword" placeholder="Confirm your password" /></td>
											</tr>
											<tr>
												<td>&nbsp;</td>
												<td><br /><input type="submit" name="submit" value="Next" style="width:100px;"
													class="button button-primary button-large" />
													<input type="button" value="Login Page" id="mo_openid_go_back_registration" style="width:150px;"
													class="button button-primary button-large" />
												</td>
											</tr>
										</table>
									<br/>By clicking Next, you agree to our <a href="http://miniorange.com/usecases/miniOrange_Privacy_Policy.pdf" target="_blank">Privacy Policy</a> and <a href="http://miniorange.com/usecases/miniOrange_User_Agreement.pdf" target="_blank">User Agreement</a>.
								</div>
				</form>
				<form name="f" method="post" action="" id="openidgobackloginform">
					<input type="hidden" name="option" value="mo_openid_go_back_registration"/>
				</form>
				<script>
						jQuery('#mo_openid_go_back_registration').click(function() {
							jQuery('#openidgobackloginform').submit();
						});
						var text = "&nbsp;&nbsp;We will call only if you need support."
						jQuery('.intl-number-input').append(text);

				</script>
		</td>
		<td style="vertical-align:top;padding-left:1%;">
			<?php echo miniorange_openid_support(); ?>
		</td>
		<?php
}

function mo_openid_show_verify_password_page() {
	?>
	<td style="vertical-align:top;width:65%;">
			<!--Verify password with miniOrange-->
		<form name="f" method="post" action="">
			<input type="hidden" name="option" value="mo_openid_connect_verify_customer" />
			<div class="mo_openid_table_layout">
				<?php if(!mo_openid_is_customer_registered()) { ?>
					<div style="display:block;margin-top:10px;color:red;background-color:rgba(251, 232, 0, 0.15);padding:5px;border:solid 1px rgba(255, 0, 9, 0.36);">
					Please <a href="<?php echo add_query_arg( array('tab' => 'register'), $_SERVER['REQUEST_URI'] ); ?>">Register or Login with miniOrange</a> to enable Social Login and Social Sharing. miniOrange takes care of creating applications for you so that you don't have to worry about creating applications in each social network.
					</div>
				<?php } ?>
			
				<h3>Login with miniOrange</h3>
				<p><b>It seems you already have an account with miniOrange. Please enter your miniOrange email and password. <a href="#forgot_password">Click here if you forgot your password?</a></b></p>
				<table class="mo_openid_settings_table">
					<tr>
						<td><b><font color="#FF0000">*</font>Email:</b></td>
						<td><input class="mo_openid_table_textbox" id="email" type="email" name="email"
							required placeholder="person@example.com"
							value="<?php echo get_option('mo_openid_admin_email');?>" /></td>
					</tr>
					<td><b><font color="#FF0000">*</font>Password:</b></td>
					<td><input class="mo_openid_table_textbox" required type="password"
						name="password" placeholder="Choose your password" /></td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td><input type="submit" name="submit"
							class="button button-primary button-large" />
						<input type="button" value="Registration Page" id="mo_openid_go_back"
													class="button button-primary button-large" />
						</td>
					</tr>
				</table>
			</div>
		</form>
		<form name="f" method="post" action="" id="openidgobackform">
				<input type="hidden" name="option" value="mo_openid_go_back_login"/>
			</form>
		<form name="forgotpassword" method="post" action="" id="openidforgotpasswordform">
			<input type="hidden" name="option" value="mo_openid_forgot_password"/>
			<input type="hidden" id="forgot_pass_email" name="email" value=""/>
		</form>
		<script>
			jQuery('#mo_openid_go_back').click(function() {
				jQuery('#openidgobackform').submit();
			});
			jQuery('a[href="#forgot_password"]').click(function(){
				jQuery('#forgot_pass_email').val(jQuery('#email').val());
				jQuery('#openidforgotpasswordform').submit();
			});
		</script>
		</td>
		<td style="vertical-align:top;padding-left:1%;">
			<?php echo miniorange_openid_support(); ?>
		</td>
		<?php
}

function mo_openid_apps_config() {
	?>
	<td style="vertical-align:top;width:65%;">
		<!-- Google configurations -->
				<form id="form-apps" name="form-apps" method="post" action="">
					<input type="hidden" name="option" value="mo_openid_enable_apps" />
					
					
					<div class="mo_openid_table_layout">
						
						<?php if(!mo_openid_is_customer_registered()) { ?>
							<div style="display:block;margin-top:10px;color:red;background-color:rgba(251, 232, 0, 0.15);padding:5px;border:solid 1px rgba(255, 0, 9, 0.36);">
							Please <a href="<?php echo add_query_arg( array('tab' => 'register'), $_SERVER['REQUEST_URI'] ); ?>">Register or Login with miniOrange</a> to enable Social Login and Social Sharing. miniOrange takes care of creating applications for you so that you don't have to worry about creating applications in each social network.
							</div>
						<?php } ?>
							
							<table>
									<tr>
										<td colspan="2">
											<h3>Social Login
											<input type="submit" name="submit" value="Save" style="float:right; margin-right:2%; margin-top: -3px;width:100px;" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?>
													class="button button-primary button-large" />
												</h3>
												
												<b>Select applications to enable login for your users. Customize your login icons using a range of shapes, themes and sizes. You can choose different places to display these icons and also customize redirect url after login.</b>
										</td>
										
									</tr>
								</table>
							
							<table>
							
							
								<h3>Select Apps</h3>
								<p>Select applications to enable social login</p>
							
								<tr>
									<td>
										<table style="width:100%">
											<tr>
												<td><input type="checkbox" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?> id="facebook_enable" class="app_enable" name="mo_openid_facebook_enable" value="1" onchange="previewLoginIcons();"
												<?php checked( get_option('mo_openid_facebook_enable') == 1 );?> /><strong>Facebook</strong>
												</td>
												<td>
												<input type="checkbox" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?> id="google_enable" class="app_enable" name="mo_openid_google_enable" value="1" onchange="previewLoginIcons();"
												<?php checked( get_option('mo_openid_google_enable') == 1 );?> /><strong>Google</strong>
												</td>
												<td>
												<input type="checkbox" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?>
												id="vkontakte_enable" class="app_enable" name="mo_openid_vkontakte_enable" value="1" onchange="previewLoginIcons();"
												<?php checked( get_option('mo_openid_vkontakte_enable') == 1 );?> /><strong>Vkontakte</strong>
												</td>
												<td>
												<input type="checkbox" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?>
												id="twitter_enable" class="app_enable" name="mo_openid_twitter_enable" value="1" onchange="previewLoginIcons();"
												<?php checked( get_option('mo_openid_twitter_enable') == 1 );?> /><strong>Twitter</strong>
												</td>
												<td>
												<input type="checkbox" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?>
												id="instagram_enable" class="app_enable" name="mo_openid_instagram_enable" value="1" onchange="previewLoginIcons();"
												<?php checked( get_option('mo_openid_instagram_enable') == 1 );?> /><strong>Instagram</strong>
												</td>
											</tr>
											<tr>
												<td>
												<input type="checkbox" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?> id="linkedin_enable" class="app_enable" name="mo_openid_linkedin_enable" value="1" onchange="previewLoginIcons();"
												<?php checked( get_option('mo_openid_linkedin_enable') == 1 );?> /><strong>LinkedIn</strong>
												</td>
												<td>
												<input type="checkbox" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?>
												id="amazon_enable" class="app_enable" name="mo_openid_amazon_enable" value="1" onchange="previewLoginIcons();"
												<?php checked( get_option('mo_openid_amazon_enable') == 1 );?> /><strong>Amazon</strong>
												</td>
												<td>
												<input type="checkbox" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?>
												id="salesforce_enable" class="app_enable" name="mo_openid_salesforce_enable" value="1" onchange="previewLoginIcons();"
												<?php checked( get_option('mo_openid_salesforce_enable') == 1 );?> /><strong>Salesforce</strong>
												</td>
												<td>
												<input type="checkbox" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?>
												id="windowslive_enable" class="app_enable" name="mo_openid_windowslive_enable" value="1" onchange="previewLoginIcons();"
												<?php checked( get_option('mo_openid_windowslive_enable') == 1 );?> /><strong>Windows Live</strong>
												</td>
											</tr>
										</table>
									</td>
								</td>
								<tr>
								
								
								
					<td>
						<br>
							<hr>
							<h3>Customize Login Icons</h3>
							<p>Customize shape, theme and size of social login icons</p>
							</td>
		</tr>
		<tr>
		<td>
			<b>Shape</b>
			<b style="margin-left:130px;">Theme</b>
			<b style="margin-left:130px;">Space between Icons</b>
			<b style="margin-left:86px;">Size of Icons</b>
			</td>
		</tr>
		<tr>
				
				<td class="mo_openid_table_td_checkbox">
					<input type="radio"    name="mo_openid_login_theme" value="circle" onclick="checkLoginButton();moLoginPreview(document.getElementById('mo_login_icon_size').value ,'circle',setLoginCustomTheme(),document.getElementById('mo_login_icon_custom_color').value,document.getElementById('mo_login_icon_space').value,document.getElementById('mo_login_icon_custom_boundary').value)"
						<?php if(!mo_openid_is_customer_registered()) echo 'disabled'?>
								<?php checked( get_option('mo_openid_login_theme') == 'circle' );?> />Round
						
				<span style="margin-left:106px;">
					<input type="radio" id="mo_openid_login_default_radio"  name="mo_openid_login_custom_theme" value="default" onclick="checkLoginButton();moLoginPreview(setSizeOfIcons(), setLoginTheme(),'default',document.getElementById('mo_login_icon_custom_color').value,document.getElementById('mo_login_icon_space').value,document.getElementById('mo_login_icon_height').value,document.getElementById('mo_login_icon_custom_boundary').value)"
								<?php checked( get_option('mo_openid_login_custom_theme') == 'default' );?> <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?>/>Default
					
				</span>
				
				<span  style="margin-left:111px;">
						<input style="width:50px" onkeyup="moLoginSpaceValidate(this)" id="mo_login_icon_space" name="mo_login_icon_space" type="text" value="<?php echo get_option('mo_login_icon_space')?>" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?>/>
						<input id="mo_login_space_plus" type="button" value="+" onmouseup="moLoginPreview(setSizeOfIcons() ,setLoginTheme(),setLoginCustomTheme(),document.getElementById('mo_login_icon_custom_color').value,document.getElementById('mo_login_icon_space').value,document.getElementById('mo_login_icon_custom_boundary').value)" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?>/>
						<input id="mo_login_space_minus" type="button" value="-" onmouseup="moLoginPreview(setSizeOfIcons()  ,setLoginTheme(),setLoginCustomTheme(),document.getElementById('mo_login_icon_custom_color').value,document.getElementById('mo_login_icon_space').value,document.getElementById('mo_login_icon_custom_boundary').value)" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?>/>
				</span>
					
					
				<span id="commontheme" style="margin-left:115px">
				<input style="width:50px" id="mo_login_icon_size" onkeyup="moLoginSizeValidate(this)" name="mo_login_icon_custom_size" type="text" value="<?php echo get_option('mo_login_icon_custom_size')?>" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?>>
				<input id="mo_login_size_plus" type="button" value="+" onmouseup="moLoginPreview(document.getElementById('mo_login_icon_size').value ,setLoginTheme(),setLoginCustomTheme(),document.getElementById('mo_login_icon_custom_color').value,document.getElementById('mo_login_icon_space').value,document.getElementById('mo_login_icon_custom_boundary').value)" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?>>
				<input id="mo_login_size_minus" type="button" value="-" onmouseup="moLoginPreview(document.getElementById('mo_login_icon_size').value ,setLoginTheme(),setLoginCustomTheme(),document.getElementById('mo_login_icon_custom_color').value,document.getElementById('mo_login_icon_space').value,document.getElementById('mo_login_icon_custom_boundary').value)" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?>>
				
				</span>
				<span style="margin-left:115px" class="longbuttontheme">Width:&nbsp;
				<input style="width:50px" id="mo_login_icon_width" onkeyup="moLoginWidthValidate(this)" name="mo_login_icon_custom_width" type="text" value="<?php echo get_option('mo_login_icon_custom_width')?>" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?>>
				<input id="mo_login_width_plus" type="button" value="+" onmouseup="moLoginPreview(document.getElementById('mo_login_icon_width').value ,setLoginTheme(),setLoginCustomTheme(),document.getElementById('mo_login_icon_custom_color').value,document.getElementById('mo_login_icon_space').value,document.getElementById('mo_login_icon_height').value,document.getElementById('mo_login_icon_custom_boundary').value)" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?>>
				<input id="mo_login_width_minus" type="button" value="-" onmouseup="moLoginPreview(document.getElementById('mo_login_icon_width').value ,setLoginTheme(),setLoginCustomTheme(),document.getElementById('mo_login_icon_custom_color').value,document.getElementById('mo_login_icon_space').value,document.getElementById('mo_login_icon_height').value,document.getElementById('mo_login_icon_custom_boundary').value)" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?>>
				
				</span>
				
				
				</td>			
		</tr>
	
		<tr>
				<td class="mo_openid_table_td_checkbox">
				<input type="radio"   name="mo_openid_login_theme"  value="oval" onclick="checkLoginButton();moLoginPreview(document.getElementById('mo_login_icon_size').value,'oval',setLoginCustomTheme(),document.getElementById('mo_login_icon_custom_color').value,document.getElementById('mo_login_icon_space').value,document.getElementById('mo_login_icon_size').value,document.getElementById('mo_login_icon_custom_boundary').value )"
						<?php if(!mo_openid_is_customer_registered()) echo 'disabled'?>
								<?php checked( get_option('mo_openid_login_theme') == 'oval' );?> />Rounded Edges	

				<span style="margin-left:50px;">
						<input type="radio" id="mo_openid_login_custom_radio"  name="mo_openid_login_custom_theme" value="custom" onclick="checkLoginButton();moLoginPreview(setSizeOfIcons(), setLoginTheme(),'custom',document.getElementById('mo_login_icon_custom_color').value,document.getElementById('mo_login_icon_space').value,document.getElementById('mo_login_icon_height').value,document.getElementById('mo_login_icon_custom_boundary').value)" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?>
								<?php checked( get_option('mo_openid_login_custom_theme') == 'custom' );?> />Custom Background*
								
						</span>	
						
						
						
					<span style="margin-left:248px" class="longbuttontheme" >Height:
				<input style="width:50px" id="mo_login_icon_height" onkeyup="moLoginHeightValidate(this)" name="mo_login_icon_custom_height" type="text" value="<?php echo get_option('mo_login_icon_custom_height')?>" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?>>
				<input id="mo_login_height_plus" type="button" value="+" onmouseup="moLoginPreview(document.getElementById('mo_login_icon_width').value,setLoginTheme(),setLoginCustomTheme(),document.getElementById('mo_login_icon_custom_color').value,document.getElementById('mo_login_icon_space').value,document.getElementById('mo_login_icon_height').value,document.getElementById('mo_login_icon_custom_boundary').value)" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?>>
				<input id="mo_login_height_minus" type="button" value="-" onmouseup="moLoginPreview(document.getElementById('mo_login_icon_width').value,setLoginTheme(),setLoginCustomTheme(),document.getElementById('mo_login_icon_custom_color').value,document.getElementById('mo_login_icon_space').value,document.getElementById('mo_login_icon_height').value,document.getElementById('mo_login_icon_custom_boundary').value)" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?>>
				
				</span>
				</td>
		</tr>
		
		<tr>
				<td class="mo_openid_table_td_checkbox">
						<input type="radio"   name="mo_openid_login_theme" value="square" onclick="checkLoginButton();moLoginPreview(document.getElementById('mo_login_icon_size').value ,'square',setLoginCustomTheme(),document.getElementById('mo_login_icon_custom_color').value,document.getElementById('mo_login_icon_space').value,document.getElementById('mo_login_icon_size').value,document.getElementById('mo_login_icon_custom_boundary').value )"
						<?php if(!mo_openid_is_customer_registered()) echo 'disabled'?>
								<?php checked( get_option('mo_openid_login_theme') == 'square' );?> />Square
					
						<span style="margin-left:113px;">
						<input id="mo_login_icon_custom_color" style="width:135px;" name="mo_login_icon_custom_color"  class="color" value="<?php echo get_option('mo_login_icon_custom_color')?>" onchange="moLoginPreview(setSizeOfIcons(), setLoginTheme(),'custom',document.getElementById('mo_login_icon_custom_color').value,document.getElementById('mo_login_icon_space').value,document.getElementById('mo_login_icon_custom_boundary').value)" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?>>
						</span>
						
						
						<span style="margin-left:255px" class="longbuttontheme">Curve:
						<input style="width:50px" id="mo_login_icon_custom_boundary" onkeyup="moLoginBoundaryValidate(this)" name="mo_login_icon_custom_boundary" type="text" value=
						"<?php echo get_option('mo_login_icon_custom_boundary')?>" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?>/>
						<input id="mo_login_boundary_plus" type="button" value="+" onmouseup="moLoginPreview(document.getElementById('mo_login_icon_width').value,setLoginTheme(),setLoginCustomTheme(),document.getElementById('mo_login_icon_custom_color').value,document.getElementById('mo_login_icon_space').value,document.getElementById('mo_login_icon_height').value,document.getElementById('mo_login_icon_custom_boundary').value)" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?>/>
				<input id="mo_login_boundary_minus" type="button" value="-" onmouseup="moLoginPreview(document.getElementById('mo_login_icon_width').value,setLoginTheme(),setLoginCustomTheme(),document.getElementById('mo_login_icon_custom_color').value,document.getElementById('mo_login_icon_space').value,document.getElementById('mo_login_icon_height').value,document.getElementById('mo_login_icon_custom_boundary').value)" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?>/>
                         </span> 
				</td>
		</tr>
		<tr>
				<td class="mo_openid_table_td_checkbox">
						<input type="radio" id="iconwithtext"   name="mo_openid_login_theme" value="longbutton" onclick="checkLoginButton();moLoginPreview(document.getElementById('mo_login_icon_width').value ,'longbutton',setLoginCustomTheme(),document.getElementById('mo_login_icon_custom_color').value,document.getElementById('mo_login_icon_space').value,document.getElementById('mo_login_icon_height').value,document.getElementById('mo_login_icon_custom_boundary').value)"
						<?php if(!mo_openid_is_customer_registered()) echo 'disabled'?>
								<?php checked( get_option('mo_openid_login_theme') == 'longbutton' );?> />Long Button with Text</td>
		</tr>
		<tr>
			<td>	<br><b>Preview : </b><br/><span hidden id="no_apps_text">No apps selected</span>
				<div>
					<img class="mo_login_icon_preview" id="mo_login_icon_preview_facebook" src="<?php echo plugins_url( 'includes/images/icons/facebook.png', __FILE__ )?>" />
					<img class="mo_login_icon_preview" id="mo_login_icon_preview_google" src="<?php echo plugins_url( 'includes/images/icons/google.png', __FILE__ )?>" />
					<img class="mo_login_icon_preview" id="mo_login_icon_preview_vkontakte" src="<?php echo plugins_url( 'includes/images/icons/vk.png', __FILE__ )?>" />
					<img class="mo_login_icon_preview" id="mo_login_icon_preview_twitter" src="<?php echo plugins_url( 'includes/images/icons/twitter.png', __FILE__ )?>" />
					<img class="mo_login_icon_preview" id="mo_login_icon_preview_instagram" src="<?php echo plugins_url( 'includes/images/icons/instagram.png', __FILE__ )?>" />
					<img class="mo_login_icon_preview" id="mo_login_icon_preview_linkedin" src="<?php echo plugins_url( 'includes/images/icons/linkedin.png', __FILE__ )?>" />
					<img class="mo_login_icon_preview" id="mo_login_icon_preview_amazon" src="<?php echo plugins_url( 'includes/images/icons/amazon.png', __FILE__ )?>" />
					<img class="mo_login_icon_preview" id="mo_login_icon_preview_salesforce" src="<?php echo plugins_url( 'includes/images/icons/salesforce.png', __FILE__ )?>" />
					<img class="mo_login_icon_preview" id="mo_login_icon_preview_windowslive" src="<?php echo plugins_url( 'includes/images/icons/windowslive.png', __FILE__ )?>" />
				</div>
				
				<div>
					<a id="mo_login_button_preview_facebook" class="btn btn-block btn-defaulttheme btn-social btn-facebook btn-custom-size"> <i class="fa fa-facebook"></i><?php
									echo get_option('mo_openid_login_button_customize_text'); 	?> Facebook</a>
					<a id="mo_login_button_preview_google" class="btn btn-block btn-defaulttheme btn-social btn-google btn-custom-size"> <i class="fa fa-google-plus"></i><?php
									echo get_option('mo_openid_login_button_customize_text'); 	?> Google</a>
					<a id="mo_login_button_preview_vkontakte" class="btn btn-block btn-defaulttheme btn-social btn-vk btn-custom-size"> <i class="fa fa-vk"></i><?php
									echo get_option('mo_openid_login_button_customize_text'); 	?> Vkontakte</a>
					<a id="mo_login_button_preview_twitter" class="btn btn-block btn-defaulttheme btn-social btn-twitter btn-custom-size"> <i class="fa fa-twitter"></i><?php
									echo get_option('mo_openid_login_button_customize_text'); 	?> Twitter</a>
					<a id="mo_login_button_preview_instagram" class="btn btn-block btn-defaulttheme btn-social btn-instagram btn-custom-size"> <i class="fa fa-instagram"></i><?php
									echo get_option('mo_openid_login_button_customize_text'); 	?> Instagram</a>
					<a id="mo_login_button_preview_linkedin" class="btn btn-block btn-defaulttheme btn-social btn-linkedin btn-custom-size"> <i class="fa fa-linkedin"></i><?php
									echo get_option('mo_openid_login_button_customize_text'); 	?> LinkedIn</a>
					<a id="mo_login_button_preview_amazon" class="btn btn-block btn-defaulttheme btn-social btn-soundcloud btn-custom-size"> <i class="fa fa-amazon"></i><?php
									echo get_option('mo_openid_login_button_customize_text'); 	?> Amazon</a>
					<a id="mo_login_button_preview_salesforce" class="btn btn-block btn-defaulttheme btn-social btn-vimeo btn-custom-size"> <i class="fa fa-cloud"></i><?php
									echo get_option('mo_openid_login_button_customize_text'); 	?> Salesforce</a>
					<a id="mo_login_button_preview_windowslive" class="btn btn-block btn-defaulttheme btn-social btn-microsoft btn-custom-size"> <i class="fa fa-windows"></i><?php
									echo get_option('mo_openid_login_button_customize_text'); 	?> Windows</a>
					
				</div>
				
				<div>
					<i class="mo_custom_login_icon_preview fa fa-facebook" id="mo_custom_login_icon_preview_facebook"  style="color:#ffffff;text-align:center;margin-top:5px;"></i>
					<i class="mo_custom_login_icon_preview fa fa-google-plus" id="mo_custom_login_icon_preview_google"  style="color:#ffffff;text-align:center;margin-top:5px;"></i>
					<i class="mo_custom_login_icon_preview fa fa-vk" id="mo_custom_login_icon_preview_vkontakte"  style="color:#ffffff;text-align:center;margin-top:5px;"></i>
					<i class="mo_custom_login_icon_preview fa fa-twitter" id="mo_custom_login_icon_preview_twitter" style="color:#ffffff;text-align:center;margin-top:5px;" ></i>
					<i class="mo_custom_login_icon_preview fa fa-instagram" id="mo_custom_login_icon_preview_instagram" style="color:#ffffff;text-align:center;margin-top:5px;"></i>
					<i class="mo_custom_login_icon_preview fa fa-linkedin" id="mo_custom_login_icon_preview_linkedin" style="color:#ffffff;text-align:center;margin-top:5px;"></i>
					<i class="mo_custom_login_icon_preview fa fa-amazon" id="amazoncustom" style="color:#ffffff;text-align:center;margin-top:5px;"></i>
					<i class="mo_custom_login_icon_preview fa fa-cloud" id="salesforcecustom" style="margin-bottom:-10px;color:#ffffff;text-align:center;margin-top:5px;" ></i>
					<i class="mo_custom_login_icon_preview fa fa-windows" id="mo_custom_login_icon_preview_windows" style="color:#ffffff;text-align:center;margin-top:5px;" ></i>
					
				</div>
				
				<div>
					<a id="mo_custom_login_button_preview_facebook"  class="btn btn-block btn-customtheme btn-social  btn-custom-size"> <i class="fa fa-facebook"></i><?php
									echo get_option('mo_openid_login_button_customize_text'); 	?> Facebook</a>
					<a id="mo_custom_login_button_preview_google" class="btn btn-block btn-customtheme btn-social   btn-custom-size"> <i class="fa fa-google-plus"></i><?php
									echo get_option('mo_openid_login_button_customize_text'); 	?> Google</a>
					<a id="mo_custom_login_button_preview_vkontakte" class="btn btn-block btn-customtheme btn-social   btn-custom-size"> <i class="fa fa-vk"></i><?php
									echo get_option('mo_openid_login_button_customize_text'); 	?> Vkontakte</a>
					<a id="mo_custom_login_button_preview_twitter" class="btn btn-block btn-customtheme btn-social  btn-custom-size"> <i class="fa fa-twitter"></i><?php
									echo get_option('mo_openid_login_button_customize_text'); 	?> Twitter</a>
					<a id="mo_custom_login_button_preview_instagram" class="btn btn-block btn-customtheme btn-social  btn-custom-size"> <i class="fa fa-instagram"></i><?php
									echo get_option('mo_openid_login_button_customize_text'); 	?> Instagram</a>
					<a id="mo_custom_login_button_preview_linkedin" class="btn btn-block btn-customtheme btn-social  btn-custom-size"> <i class="fa fa-linkedin"></i><?php
									echo get_option('mo_openid_login_button_customize_text'); 	?> LinkedIn</a>
					<a id="mo_custom_login_button_preview_amazon" class="btn btn-block btn-customtheme btn-social  btn-custom-size"><i class="fa fa-amazon"></i><?php
									echo get_option('mo_openid_login_button_customize_text'); 	?> Amazon</a>
					<a id="mo_custom_login_button_preview_salesforce" class="btn btn-block btn-customtheme btn-social  btn-custom-size"> <i class="fa fa-cloud"></i><?php
									echo get_option('mo_openid_login_button_customize_text'); 	?> Salesforce</a>
					<a id="mo_custom_login_button_preview_windows" class="btn btn-block btn-customtheme btn-social  btn-custom-size"> <i class="fa fa-windows"></i><?php
									echo get_option('mo_openid_login_button_customize_text'); 	?> Windows</a>
					
				</div>
		</td>
	</tr>
	<tr>
		<td><br>
		<strong>*NOTE:</strong><br/>Custom background: This will change the background color of login icons.
		</td>
	</tr>
	<tr>
	<tr>
		<td>
			<br>
			<hr>
			<h3>Customize Text For Social Login Buttons / Icons</h3>
		</td>
	</tr>
	<tr>
		<td><b>Enter text to show above login widget:</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<input class="mo_openid_table_textbox" style="width:50%" type="text" name="mo_openid_login_widget_customize_text" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?> value="<?php echo get_option('mo_openid_login_widget_customize_text'); ?>" /></td>
	</tr>
	<tr>
		<td><b>Enter text to show on your login buttons (If you have</b>
			<br/><b> selected shape 4 from 'Customize Login Icons' section):</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<input class="mo_openid_table_textbox" style="width:50%" type="text" name="mo_openid_login_button_customize_text" 
			<?php if(!mo_openid_is_customer_registered()) echo 'disabled'?> value="<?php echo get_option('mo_openid_login_button_customize_text'); ?>"  /></td>
	</tr>
	
	<tr>
		<td>
			<br>
			<hr>
			<h3>Customize Text to show user after Login</h3>
		</td>
	</tr>
	<tr>
		<td><b>Enter text to show before the logout link</b>
			<br/>Use ##username## to display current username:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<input class="mo_openid_table_textbox" style="width:50%" type="text" name="mo_openid_login_widget_customize_logout_name_text" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?> value="<?php echo get_option('mo_openid_login_widget_customize_logout_name_text'); ?>" /></td>
	</tr>
	<tr>
		<td><b>Enter text to show as logout link:</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<input class="mo_openid_table_textbox" style="width:50%" type="text" name="mo_openid_login_widget_customize_logout_text" 
			<?php if(!mo_openid_is_customer_registered()) echo 'disabled'?> value="<?php echo get_option('mo_openid_login_widget_customize_logout_text'); ?>"  /></td>
	</tr>
									<td>
									<br>
										<hr>
										<h3>Display Options</h3>
											
											<b>Select the options where you want to display the social login icons</b>
										</td>
								</tr>
		

											<tr>
												<td class="mo_openid_table_td_checkbox">
												<input type="checkbox" id="default_login_enable" name="mo_openid_default_login_enable" value="1"
													<?php if(!mo_openid_is_customer_registered()) echo 'disabled'?>	<?php checked( get_option('mo_openid_default_login_enable') == 1 );?> />Default Login Form</td>
											</tr>
											<tr>
												<td class="mo_openid_table_td_checkbox">
												<input type="checkbox" id="default_register_enable" name="mo_openid_default_register_enable" value="1"
												<?php if(!mo_openid_is_customer_registered()) echo 'disabled'?>	<?php checked( get_option('mo_openid_default_register_enable') == 1 );?> />Default Registration Form</td>
											</tr>
											<tr>
												<td class="mo_openid_table_td_checkbox">
													<input type="checkbox" id="default_comment_enable" name="mo_openid_default_comment_enable" value="1"
													<?php if(!mo_openid_is_customer_registered()) echo 'disabled'?>	<?php checked( get_option('mo_openid_default_comment_enable') == 1 );?> />Comment Form</td>
											</tr>
											<tr>
												<td class="mo_openid_table_td_checkbox">
													<input type="checkbox" id="woocommerce_login_form" name="mo_openid_woocommerce_login_form" value="1"
													<?php if(!mo_openid_is_customer_registered()) echo 'disabled'?>	<?php checked( get_option('mo_openid_woocommerce_login_form') == 1 );?> />WooCommerce Login Form</td>
											</tr>
								<tr><td>&nbsp;</td></tr>
								<tr>
									<td>
										<b>Redirect URL after login:</b>
									</td>
								</tr>
								<tr>
									<td>
										<input type="radio" id="login_redirect_same_page" name="mo_openid_login_redirect" value="same"
										<?php if(!mo_openid_is_customer_registered()) echo 'disabled'?> <?php checked( get_option('mo_openid_login_redirect') == 'same' );?> />Same page where user logged in
									</td>
								</tr>
								<tr>
									<td>
										<input type="radio" id="login_redirect_homepage" name="mo_openid_login_redirect" value="homepage"
										<?php if(!mo_openid_is_customer_registered()) echo 'disabled'?> <?php checked( get_option('mo_openid_login_redirect') == 'homepage' );?> />Homepage
									</td>
								</tr>
								<tr>
									<td>
										<input type="radio" id="login_redirect_dashboard" name="mo_openid_login_redirect" value="dashboard"
										<?php if(!mo_openid_is_customer_registered()) echo 'disabled'?> <?php checked( get_option('mo_openid_login_redirect') == 'dashboard' );?> />Account dashboard
									</td>
								</tr>
								<tr>
									<td>
										<input type="radio" id="login_redirect_customurl" name="mo_openid_login_redirect" value="custom"
										<?php if(!mo_openid_is_customer_registered()) echo 'disabled'?> <?php checked( get_option('mo_openid_login_redirect') == 'custom' );?> />Custom URL
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										<input type="url" id="login_redirect_url" style="width:50%" name="mo_openid_login_redirect_url" value="<?php echo get_option('mo_openid_login_redirect_url')?>" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?>/>
									</td>
								</tr>
								<tr><td>&nbsp;</td></tr>
								<tr>
									<td>
										<input type="checkbox" id="logout_redirection_enable" name="mo_openid_logout_redirection_enable" value="1"
												<?php if(!mo_openid_is_customer_registered()) echo 'disabled'?>	<?php checked( get_option('mo_openid_logout_redirection_enable') == 1 );?> />
										<b>Enable Logout Redirection</b>
									</td>
								</tr>
								<tr>
									<td>
										<input type="radio" id="logout_redirect_home" name="mo_openid_logout_redirect" value="homepage"
										<?php if(!mo_openid_is_customer_registered()) echo 'disabled'?> <?php checked( get_option('mo_openid_logout_redirect') == 'homepage' );?> />Home Page
									</td>
								</tr>
								<tr>
									<td>
										<input type="radio" id="logout_redirect_current" name="mo_openid_logout_redirect" value="currentpage"
										<?php if(!mo_openid_is_customer_registered()) echo 'disabled'?> <?php checked( get_option('mo_openid_logout_redirect') == 'currentpage' );?> />Current Page
									</td>
								</tr>
								<tr>
									<td>
										<input type="radio" id="logout_redirect_login" name="mo_openid_logout_redirect" value="login"
										<?php if(!mo_openid_is_customer_registered()) echo 'disabled'?> <?php checked( get_option('mo_openid_logout_redirect') == 'login' );?> />Login Page
									</td>
								</tr>
								<tr>
									<td>
										<input type="radio" id="logout_redirect_customurl" name="mo_openid_logout_redirect" value="custom"
										<?php if(!mo_openid_is_customer_registered()) echo 'disabled'?> <?php checked( get_option('mo_openid_logout_redirect') == 'custom' );?> />Relative URL
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										<?php echo site_url();?>
										<input type="text" id="logout_redirect_url" style="width:50%" name="mo_openid_logout_redirect_url" value="<?php echo get_option('mo_openid_logout_redirect_url')?>" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?>/>
									</td>
								</tr>
								<tr>
									<td>
										<br>
										<hr>
										<h3>Registration Options</h3>
									</td>
								</tr>
								<tr>
									<td>
										If Auto-register users is unchecked, users will not be able to register using Social Login. The users who already have an account will be able to login.  This setting stands true only when users are registering using Social Login. This will not interfere with users registering through the regular WordPress.
										<br/><br/>
										<input type="checkbox" id="auto_register_enable" name="mo_openid_auto_register_enable" value="1"
										<?php if(!mo_openid_is_customer_registered()) echo 'disabled'?>	<?php checked( get_option('mo_openid_auto_register_enable') == 1 );?> /><b>Auto-register users</b>
										<br/><br/>
										<b>Registration disabled message: </b>
										<textarea id="auto_register_disabled_message" style="width:80%" name="mo_openid_register_disabled_message" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?>><?php echo get_option('mo_openid_register_disabled_message')?></textarea>
									</td>
								</tr>
								<?php if(mo_openid_is_customer_registered() && mo_openid_is_customer_valid()) { ?>
									<tr>
										<td>
											<h3 style="font-size: 16px;">Role Mapping</h3>
										</td>
									</tr>
									<tr>
										<td>
											Use Role Mapping to assign this universal role to the all users registering through Social Login.
										</td>
									</tr>
									<tr>
										<td>
											<br/>
											<b>Universal Role: </b>
											<br>
											<select name="mapping_value_default" style="width:30%" id="default_group_mapping">
										   <?php
											 if(get_option('mo_openid_login_role_mapping'))
												$default_role = get_option('mo_openid_login_role_mapping');
											 else 
												$default_role = get_option('default_role');
											 wp_dropdown_roles($default_role); ?>
											</select>
										</td>
									</tr>
								<?php } ?>
				<script>
					var tempHorSize = '<?php echo get_option('mo_login_icon_custom_size') ?>';
					var tempHorTheme = '<?php echo get_option('mo_openid_login_theme') ?>';
					var tempHorCustomTheme = '<?php echo get_option('mo_openid_login_custom_theme') ?>';
					var tempHorCustomColor = '<?php echo get_option('mo_login_icon_custom_color') ?>';
					var tempHorSpace = '<?php echo get_option('mo_login_icon_space')?>';
					var tempHorHeight = '<?php echo get_option('mo_login_icon_custom_height') ?>';
					var tempHorBoundary='<?php echo get_option('mo_login_icon_custom_boundary')?>';
						function moLoginIncrement(e,t,r,a,i){
						var h,s,c=!1,_=a;s=function(){
							"add"==t&&r.value<60?r.value++:"subtract"==t&&r.value>20&&r.value--,h=setTimeout(s,_),_>20&&(_*=i),c||(document.onmouseup=function(){clearTimeout(h),document.onmouseup=null,c=!1,_=a},c=!0)},e.onmousedown=s}
					
						function moLoginSpaceIncrement(e,t,r,a,i){
						var h,s,c=!1,_=a;s=function(){
							"add"==t&&r.value<60?r.value++:"subtract"==t&&r.value>0&&r.value--,h=setTimeout(s,_),_>20&&(_*=i),c||(document.onmouseup=function(){clearTimeout(h),document.onmouseup=null,c=!1,_=a},c=!0)},e.onmousedown=s}
					
						function moLoginWidthIncrement(e,t,r,a,i){
						var h,s,c=!1,_=a;s=function(){
							"add"==t&&r.value<1000?r.value++:"subtract"==t&&r.value>140&&r.value--,h=setTimeout(s,_),_>20&&(_*=i),c||(document.onmouseup=function(){clearTimeout(h),document.onmouseup=null,c=!1,_=a},c=!0)},e.onmousedown=s}
					
						function moLoginHeightIncrement(e,t,r,a,i){
						var h,s,c=!1,_=a;s=function(){
							"add"==t&&r.value<50?r.value++:"subtract"==t&&r.value>35&&r.value--,h=setTimeout(s,_),_>20&&(_*=i),c||(document.onmouseup=function(){clearTimeout(h),document.onmouseup=null,c=!1,_=a},c=!0)},e.onmousedown=s}
							
							function moLoginBoundaryIncrement(e,t,r,a,i){
						var h,s,c=!1,_=a;s=function(){
							"add"==t&&r.value<25?r.value++:"subtract"==t&&r.value>0&&r.value--,h=setTimeout(s,_),_>20&&(_*=i),c||(document.onmouseup=function(){clearTimeout(h),document.onmouseup=null,c=!1,_=a},c=!0)},e.onmousedown=s}
							
					
					moLoginIncrement(document.getElementById('mo_login_size_plus'), "add", document.getElementById('mo_login_icon_size'), 300, 0.7);
					moLoginIncrement(document.getElementById('mo_login_size_minus'), "subtract", document.getElementById('mo_login_icon_size'), 300, 0.7);
					
					moLoginIncrement(document.getElementById('mo_login_size_plus'), "add", document.getElementById('mo_login_icon_size'), 300, 0.7);
					moLoginIncrement(document.getElementById('mo_login_size_minus'), "subtract", document.getElementById('mo_login_icon_size'), 300, 0.7);
					
					moLoginSpaceIncrement(document.getElementById('mo_login_space_plus'), "add", document.getElementById('mo_login_icon_space'), 300, 0.7);
					moLoginSpaceIncrement(document.getElementById('mo_login_space_minus'), "subtract", document.getElementById('mo_login_icon_space'), 300, 0.7);
					
					moLoginWidthIncrement(document.getElementById('mo_login_width_plus'), "add", document.getElementById('mo_login_icon_width'), 300, 0.7);
					moLoginWidthIncrement(document.getElementById('mo_login_width_minus'), "subtract", document.getElementById('mo_login_icon_width'), 300, 0.7);
					
					moLoginHeightIncrement(document.getElementById('mo_login_height_plus'), "add", document.getElementById('mo_login_icon_height'), 300, 0.7);
					moLoginHeightIncrement(document.getElementById('mo_login_height_minus'), "subtract", document.getElementById('mo_login_icon_height'), 300, 0.7);
					
					moLoginBoundaryIncrement(document.getElementById('mo_login_boundary_plus'), "add", document.getElementById('mo_login_icon_custom_boundary'), 300, 0.7);
					moLoginBoundaryIncrement(document.getElementById('mo_login_boundary_minus'), "subtract", document.getElementById('mo_login_icon_custom_boundary'), 300, 0.7);
					
					function setLoginTheme(){return jQuery('input[name=mo_openid_login_theme]:checked', '#form-apps').val();}
					function setLoginCustomTheme(){return jQuery('input[name=mo_openid_login_custom_theme]:checked', '#form-apps').val();}
					function setSizeOfIcons(){
							
								if((jQuery('input[name=mo_openid_login_theme]:checked', '#form-apps').val()) == 'longbutton'){
									return document.getElementById('mo_login_icon_width').value;
								}else{
									return document.getElementById('mo_login_icon_size').value;
								}
					}
					moLoginPreview(setSizeOfIcons(),tempHorTheme,tempHorCustomTheme,tempHorCustomColor,tempHorSpace,tempHorHeight,tempHorBoundary);	
					
					function moLoginPreview(t,r,l,p,n,h,k){
									
									if(l == 'default'){
										if(r == 'longbutton'){
											
											var a = "btn-defaulttheme";
										jQuery("."+a).css("width",t+"px");
										jQuery("."+a).css("padding-top",(h-29)+"px");
										jQuery("."+a).css("padding-bottom",(h-29)+"px");
										jQuery(".fa").css("padding-top",(h-35)+"px");
										jQuery("."+a).css("margin-bottom",(n-5)+"px");
										jQuery("."+a).css("border-radius",k+"px");
										}else{
											var a="mo_login_icon_preview";
											jQuery("."+a).css("margin-left",(n-4)+"px");
											
											if(r=="circle"){
												jQuery("."+a).css({height:t,width:t});
												jQuery("."+a).css("borderRadius","999px");
											}else if(r=="oval"){
												jQuery("."+a).css("borderRadius","5px");
												jQuery("."+a).css({height:t,width:t});
											}else if(r=="square"){
												jQuery("."+a).css("borderRadius","0px");
												jQuery("."+a).css({height:t,width:t});
											}
										}
									}
									else if(l == 'custom'){
										if(r == 'longbutton'){
											
												var a = "btn-customtheme";
												jQuery("."+a).css("width",(t)+"px");
												jQuery("."+a).css("padding-top",(h-29)+"px");
												jQuery("."+a).css("padding-bottom",(h-29)+"px");
												jQuery(".fa").css("padding-top",(h-35)+"px");
												jQuery("."+a).css("margin-bottom",(n-5)+"px");
												jQuery("."+a).css("background","#"+p);
												jQuery("."+a).css("border-radius",k+"px");
										}else{
											var a="mo_custom_login_icon_preview";
											jQuery("."+a).css({height:t-8,width:t});
											jQuery("."+a).css("padding-top","8px");
											jQuery("."+a).css("margin-left",(n-4)+"px");
											
											if(r=="circle"){
												jQuery("."+a).css("borderRadius","999px");
											}else if(r=="oval"){
												jQuery("."+a).css("borderRadius","5px");
												}else if(r=="square"){
												jQuery("."+a).css("borderRadius","0px");
											}
											jQuery("."+a).css("background","#"+p);
											jQuery("."+a).css("font-size",(t-16)+"px");
										}
									}
									
								
								previewLoginIcons();
								
					}
					
					function checkLoginButton(){
								if(document.getElementById('iconwithtext').checked) {
									if(setLoginCustomTheme() == 'default'){
										 jQuery(".mo_login_icon_preview").hide();
										 jQuery(".mo_custom_login_icon_preview").hide();
										 jQuery(".btn-customtheme").hide();
										 jQuery(".btn-defaulttheme").show();
									}else if(setLoginCustomTheme() == 'custom'){
										jQuery(".mo_login_icon_preview").hide();
										 jQuery(".mo_custom_login_icon_preview").hide();
										 jQuery(".btn-defaulttheme").hide();
											jQuery(".btn-customtheme").show();
									}
									jQuery("#commontheme").hide();
									jQuery(".longbuttontheme").show();
								}else {
									
									if(setLoginCustomTheme() == 'default'){
										jQuery(".mo_login_icon_preview").show();
										jQuery(".btn-defaulttheme").hide();
										jQuery(".btn-customtheme").hide();
										jQuery(".mo_custom_login_icon_preview").hide();
									}else if(setLoginCustomTheme() == 'custom'){
										jQuery(".mo_login_icon_preview").hide();
										 jQuery(".mo_custom_login_icon_preview").show();
										 jQuery(".btn-defaulttheme").hide();
										 jQuery(".btn-customtheme").hide();
									}
									jQuery("#commontheme").show();
									jQuery(".longbuttontheme").hide();
								}
								previewLoginIcons();
						}	
						
						function previewLoginIcons() {
								var flag = 0;
								if (document.getElementById('google_enable').checked)   {
									flag = 1;
										if(document.getElementById('mo_openid_login_default_radio').checked && !document.getElementById('iconwithtext').checked)
											jQuery("#mo_login_icon_preview_google").show();
										if(document.getElementById('mo_openid_login_custom_radio').checked && !document.getElementById('iconwithtext').checked)
											jQuery("#mo_custom_login_icon_preview_google").show();
										if(document.getElementById('mo_openid_login_default_radio').checked && document.getElementById('iconwithtext').checked)
											jQuery("#mo_login_button_preview_google").show();
										if(document.getElementById('mo_openid_login_custom_radio').checked && document.getElementById('iconwithtext').checked)
											jQuery("#mo_custom_login_button_preview_google").show();
								} else if(!document.getElementById('google_enable').checked){
									jQuery("#mo_login_icon_preview_google").hide();
									jQuery("#mo_custom_login_icon_preview_google").hide();
									jQuery("#mo_login_button_preview_google").hide();
									jQuery("#mo_custom_login_button_preview_google").hide();
									
								}
								
								if (document.getElementById('facebook_enable').checked) {
									flag = 1;
									if(document.getElementById('mo_openid_login_default_radio').checked && !document.getElementById('iconwithtext').checked)
										jQuery("#mo_login_icon_preview_facebook").show();
									if(document.getElementById('mo_openid_login_custom_radio').checked && !document.getElementById('iconwithtext').checked)
										jQuery("#mo_custom_login_icon_preview_facebook").show();
									if(document.getElementById('mo_openid_login_default_radio').checked && document.getElementById('iconwithtext').checked)
										jQuery("#mo_login_button_preview_facebook").show();
									if(document.getElementById('mo_openid_login_custom_radio').checked && document.getElementById('iconwithtext').checked)
										jQuery("#mo_custom_login_button_preview_facebook").show();
								}else if(!document.getElementById('facebook_enable').checked){
									jQuery("#mo_login_icon_preview_facebook").hide();
									jQuery("#mo_custom_login_icon_preview_facebook").hide();
									jQuery("#mo_login_button_preview_facebook").hide();
									jQuery("#mo_custom_login_button_preview_facebook").hide();
								}

								if (document.getElementById('vkontakte_enable').checked) {
									flag = 1;
									if(document.getElementById('mo_openid_login_default_radio').checked && !document.getElementById('iconwithtext').checked)
										jQuery("#mo_login_icon_preview_vkontakte").show();
									if(document.getElementById('mo_openid_login_custom_radio').checked && !document.getElementById('iconwithtext').checked)
										jQuery("#mo_custom_login_icon_preview_vkontakte").show();
									if(document.getElementById('mo_openid_login_default_radio').checked && document.getElementById('iconwithtext').checked)
										jQuery("#mo_login_button_preview_vkontakte").show();
									if(document.getElementById('mo_openid_login_custom_radio').checked && document.getElementById('iconwithtext').checked)
										jQuery("#mo_custom_login_button_preview_vkontakte").show();
								}else if(!document.getElementById('vkontakte_enable').checked){
									jQuery("#mo_login_icon_preview_vkontakte").hide();
									jQuery("#mo_custom_login_icon_preview_vkontakte").hide();
									jQuery("#mo_login_button_preview_vkontakte").hide();
									jQuery("#mo_custom_login_button_preview_vkontakte").hide();
								}
								
								if (document.getElementById('linkedin_enable').checked) {
									flag = 1;
									if(document.getElementById('mo_openid_login_default_radio').checked && !document.getElementById('iconwithtext').checked)
										jQuery("#mo_login_icon_preview_linkedin").show();
									if(document.getElementById('mo_openid_login_custom_radio').checked && !document.getElementById('iconwithtext').checked)
										jQuery("#mo_custom_login_icon_preview_linkedin").show();
									if(document.getElementById('mo_openid_login_default_radio').checked && document.getElementById('iconwithtext').checked)	
										jQuery("#mo_login_button_preview_linkedin").show();
									if(document.getElementById('mo_openid_login_custom_radio').checked && document.getElementById('iconwithtext').checked)	
										jQuery("#mo_custom_login_button_preview_linkedin").show();
								} else if(!document.getElementById('linkedin_enable').checked){
									jQuery("#mo_login_icon_preview_linkedin").hide();
									jQuery("#mo_custom_login_icon_preview_linkedin").hide();
									jQuery("#mo_login_button_preview_linkedin").hide();
									jQuery("#mo_custom_login_button_preview_linkedin").hide();
								}
								
								if (document.getElementById('instagram_enable').checked) {
									flag = 1;
									if(document.getElementById('mo_openid_login_default_radio').checked && !document.getElementById('iconwithtext').checked)
										jQuery("#mo_login_icon_preview_instagram").show();
									if(document.getElementById('mo_openid_login_custom_radio').checked && !document.getElementById('iconwithtext').checked)
										jQuery("#mo_custom_login_icon_preview_instagram").show();
									if(document.getElementById('mo_openid_login_default_radio').checked && document.getElementById('iconwithtext').checked)
										jQuery("#mo_login_button_preview_instagram").show();
									if(document.getElementById('mo_openid_login_custom_radio').checked && document.getElementById('iconwithtext').checked)
										jQuery("#mo_custom_login_button_preview_instagram").show();
								} else if(!document.getElementById('instagram_enable').checked){
									jQuery("#mo_login_icon_preview_instagram").hide();
									jQuery("#mo_custom_login_icon_preview_instagram").hide();
									jQuery("#mo_login_button_preview_instagram").hide();
									jQuery("#mo_custom_login_button_preview_instagram").hide();
								}
								
								if (document.getElementById('amazon_enable').checked) {
									flag = 1;
									if(document.getElementById('mo_openid_login_default_radio').checked && !document.getElementById('iconwithtext').checkedd)
										jQuery("#mo_login_icon_preview_amazon").show();
									if(document.getElementById('mo_openid_login_custom_radio').checked && !document.getElementById('iconwithtext').checked)
										jQuery("#amazoncustom").show();
									if(document.getElementById('mo_openid_login_default_radio').checked && document.getElementById('iconwithtext').checked) {
										jQuery("#mo_login_button_preview_amazon").show();
										jQuery("#mo_login_icon_preview_amazon").hide();
									}
									if(document.getElementById('mo_openid_login_custom_radio').checked && document.getElementById('iconwithtext').checked)
										jQuery("#mo_custom_login_button_preview_amazon").show();
								}else if(!document.getElementById('amazon_enable').checked){
									jQuery("#mo_login_icon_preview_amazon").hide();
									jQuery("#amazoncustom").hide();
									jQuery("#mo_login_button_preview_amazon").hide();
									jQuery("#mo_custom_login_button_preview_amazon").hide();
								}
								
								if (document.getElementById('salesforce_enable').checked) {
									flag = 1;
									if(document.getElementById('mo_openid_login_default_radio').checked && !document.getElementById('iconwithtext').checked)
										jQuery("#mo_login_icon_preview_salesforce").show();
									if(document.getElementById('mo_openid_login_custom_radio').checked && !document.getElementById('iconwithtext').checked)
										jQuery("#salesforcecustom").show();
									if(document.getElementById('mo_openid_login_default_radio').checked && document.getElementById('iconwithtext').checked)
										jQuery("#mo_login_button_preview_salesforce").show();
									if(document.getElementById('mo_openid_login_custom_radio').checked && document.getElementById('iconwithtext').checked)
										jQuery("#mo_custom_login_button_preview_salesforce").show();
								} else if(!document.getElementById('salesforce_enable').checked){
									jQuery("#mo_login_icon_preview_salesforce").hide();
									jQuery("#salesforcecustom").hide();
									jQuery("#mo_login_button_preview_salesforce").hide();
									jQuery("#mo_custom_login_button_preview_salesforce").hide();
								}
								
								if (document.getElementById('windowslive_enable').checked) {
									flag = 1;
									if(document.getElementById('mo_openid_login_default_radio').checked && !document.getElementById('iconwithtext').checked)
										jQuery("#mo_login_icon_preview_windowslive").show();
									if(document.getElementById('mo_openid_login_custom_radio').checked && !document.getElementById('iconwithtext').checked)
										jQuery("#mo_custom_login_icon_preview_windows").show();
									if(document.getElementById('mo_openid_login_default_radio').checked && document.getElementById('iconwithtext').checked)
										jQuery("#mo_login_button_preview_windowslive").show();
									if(document.getElementById('mo_openid_login_custom_radio').checked && document.getElementById('iconwithtext').checked)
										jQuery("#mo_custom_login_button_preview_windows").show();
								} else if(!document.getElementById('windowslive_enable').checked){
									jQuery("#mo_login_icon_preview_windowslive").hide();
									jQuery("#mo_custom_login_icon_preview_windows").hide();
									jQuery("#mo_login_button_preview_windowslive").hide();
									jQuery("#mo_custom_login_button_preview_windows").hide();
								}
								
								
								if (document.getElementById('twitter_enable').checked) {
									flag = 1;
									if(document.getElementById('mo_openid_login_default_radio').checked && !document.getElementById('iconwithtext').checked)
										jQuery("#mo_login_icon_preview_twitter").show();
									if(document.getElementById('mo_openid_login_custom_radio').checked && !document.getElementById('iconwithtext').checked)
										jQuery("#mo_custom_login_icon_preview_twitter").show();
									if(document.getElementById('mo_openid_login_default_radio').checked && document.getElementById('iconwithtext').checked)
										jQuery("#mo_login_button_preview_twitter").show();
									if(document.getElementById('mo_openid_login_custom_radio').checked && document.getElementById('iconwithtext').checked)
										jQuery("#mo_custom_login_button_preview_twitter").show();
								}else if(!document.getElementById('twitter_enable').checked){
									jQuery("#mo_login_icon_preview_twitter").hide();
									jQuery("#mo_custom_login_icon_preview_twitter").hide();
									jQuery("#mo_login_button_preview_twitter").hide();
									jQuery("#mo_custom_login_button_preview_twitter").hide();
								}
								
								if(flag) {
									jQuery("#no_apps_text").hide();
								} else {
									jQuery("#no_apps_text").show();
								}
						}
						checkLoginButton();
				</script>
		<tr>
			<td>
				<br/>
				<hr>
				<h3>Advanced Settings</h3>
			</td>
		</tr>
		<tr>
			<td><input type="checkbox" id="moopenid_social_login_avatar" name="moopenid_social_login_avatar" value="1" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?> <?php checked( get_option('moopenid_social_login_avatar') == 1 );?> /><b>Set Display Picture for User</b>
			</td>
		</tr>
		<?php if(mo_openid_is_customer_valid() && !mo_openid_get_customer_plan('Do It Yourself')) { ?>
		<tr><td>&nbsp;</td></tr>
		<tr>
			<td><input type="checkbox" id="moopenid_user_attributes" name="moopenid_user_attributes" value="1" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?> <?php checked( get_option('moopenid_user_attributes') == 1 );?> /><b>Extended User Attributes</b>
			</td>
		</tr>
		<?php } else { 
			if(get_option('moopenid_user_attributes')) update_option('moopenid_user_attributes', 0);
		} ?>
		</table>
		<table class="mo_openid_display_table">
			<tr>
				<td><br /><input type="submit" name="submit" value="Save" style="width:100px;" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?> class="button button-primary button-large" />
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<hr>
					<p>
						<h3>Add Login Icons</h3>
						You can add login icons in the following areas from <strong>Display Options</strong>. For other areas(widget areas), use Login Widget.
					<ol>
						<li>Default Login Form: This option places login icons below the default login form on wp-login.</li>
						<li>Default Registration Form: This option places login icons below the default registration form.</li>
						<li>Comment Form: This option places login icons above the comment section of all your posts.</li>
					</ol>
					
						<h3>Add Login Icons as Widget</h3>

					<ol>
						<li>Go to Appearance->Widgets. Among the available widgets you
							will find miniOrange Social Login Widget, drag it to the widget area where
							you want it to appear.</li>
						<li>Now logout and go to your site. You will see app icon for which you enabled login.</li>
						<li>Click that app icon and login with your existing app account to wordpress.</li>
					</ol>
					</p>
				</td>
			</tr>
		</table>
	</div>
</form>
<script>
jQuery(function() {
				jQuery('#tab2').removeClass('disabledTab');
});
</script>
</td>
		<td style="vertical-align:top;padding-left:1%;">
			<?php echo miniorange_openid_support(); ?>
		</td>
<?php
}

function mo_openid_app_comment() {
?>
	<td style="vertical-align:top;width:65%;">
		<form name="f" method="post" id="comment_settings_form" action="">
		<input type="hidden" name="option" value="mo_openid_save_comment_settings" />
		<div class="mo_openid_table_layout">
		
		<?php if(!mo_openid_is_customer_registered()) { ?>
			<div style="display:block;margin-top:10px;color:red;background-color:rgba(251, 232, 0, 0.15);padding:5px;border:solid 1px rgba(255, 0, 9, 0.36);">
			Please <a href="<?php echo add_query_arg( array('tab' => 'register'), $_SERVER['REQUEST_URI'] ); ?>">Register or Login with miniOrange</a> to enable Social Login and Social Sharing. miniOrange takes care of creating applications for you so that you don't have to worry about creating applications in each social network.
			</div>
		<?php } ?>
		
		<table class="mo_openid_display_table">
			<tr>
				<td colspan="2">
					<h3>Social Comments
					<input type="submit" name="submit" value="Save" style="width:100px;float:right;margin-right:2%"
					class="button button-primary button-large" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?>/>
					</h3>
					<b>Select applications to add Social Comments. These commenting applications will be added to your blog post pages at the location of your comments.</b>
				</td>
			</tr>
		</table>
		<table class="mo_openid_display_table">
			<tr>
				<td colspan="2">
					<br/>
					<hr>
					<h3>Select Applications</h3>
					If none of the below are selected, default WordPress comments will only be visible. Only selecting Default WordPress Comments will not result in any changes.
				</td>
			</tr>
			<tr><td>&nbsp;</td></tr>
			</tr>
				<td><input type="checkbox" id="mo_openid_social_comment_default" name="mo_openid_social_comment_default" value="1" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?> <?php checked( get_option('mo_openid_social_comment_default') == 1 );?> /><b>Default WordPress Comments</b>
				</td>
			</tr>
			<tr>
				<td><input type="checkbox" id="mo_openid_social_comment_fb" name="mo_openid_social_comment_fb" value="1" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?> <?php checked( get_option('mo_openid_social_comment_fb') == 1 );?> /><b>Facebook Comments</b>
				</td>
			</tr>
			<tr>
				<td><input type="checkbox" id="mo_openid_social_comment_google" name="mo_openid_social_comment_google" value="1" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?> <?php checked( get_option('mo_openid_social_comment_google') == 1 );?> /><b>Google+ Comments</b>
				</td>
			<tr>
		</table>
		<table class="mo_openid_display_table">
			<tr>
				<td colspan="2">
					<br>
					<hr>
					<h3>Display Options</h3>
					Select the options where you want to add social comments.
				</td>
			</tr>
			<tr><td>&nbsp;</td></tr>
			<tr>
				<td><input type="checkbox" id="mo_openid_social_comment_blogpost" name="mo_openid_social_comment_blogpost" value="1" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?> <?php checked( get_option('mo_openid_social_comment_blogpost') == 1 );?> /><b>Blog Post</b>
				</td>
			</tr>
			<tr>
				<td><input type="checkbox" id="mo_openid_social_comment_static" name="mo_openid_social_comment_static" value="1" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?> <?php checked( get_option('mo_openid_social_comment_static') == 1 );?> /><b>Static Pages</b>
				</td>
			<tr>
		</table>
		<table class="mo_openid_display_table">
			<tr>
				<td colspan="2">
					<br>
					<hr>
					<h3>Customize Text For Social Comment Labels</h3>
				</td>
			</tr>
			<tr>
				<td><b>Comment Section Heading:</b></td>
				<td><input class="mo_openid_table_textbox" type="text" name="mo_openid_social_comment_heading_label" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?> value="<?php echo get_option('mo_openid_social_comment_heading_label'); ?>" /></td>
			</tr>
			<tr>
				<td><b>Comments - Default Label:</b></td>
				<td><input class="mo_openid_table_textbox" type="text" name="mo_openid_social_comment_default_label" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?> value="<?php echo get_option('mo_openid_social_comment_default_label'); ?>" /></td>
			</tr>
			<tr>
				<td><b>Comments - Facebook Label:</b></td>
				<td><input class="mo_openid_table_textbox" type="text" name="mo_openid_social_comment_fb_label" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?> value="<?php echo get_option('mo_openid_social_comment_fb_label'); ?>" /></td>
			</tr>
			<tr>
				<td><b>Comments - Google Label:</b></td>
				<td><input class="mo_openid_table_textbox" type="text" name="mo_openid_social_comment_google_label" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?> value="<?php echo get_option('mo_openid_social_comment_google_label'); ?>" /></td>
			</tr>
		
			<tr>
				<td><br /><input type="submit" name="submit" value="Save" style="width:100px;" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?> class="button button-primary button-large" />
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<hr>
					<p>
						<h3>Enable Social Comments</h3>
						To enable Social Comments, please select either Facebook Comments and/or Google+ Comments from <strong>Select Applications</strong>. Also select one or both of the options from <strong>Display Options</strong>.

						<h3>Add Social Comments</h3>
						You can add social comments in the following areas from <strong>Display Options</strong>. If you require a shortcode, please contact us from the Support form on the right.
						<ol>
							<li>Blog Post: This option enables Social Comments on Posts / Blog Post.</li>
							<li>Static pages: This option places Social Comments on Pages / Static Pages with comments enabled.</li>
						</ol>
					</p>
				</td>
			</tr>
		</table>
	</form>
	</td>
	<td style="vertical-align:top;padding-left:1%;">
		<?php echo miniorange_openid_support(); ?>
	</td>
	<?php
}

function mo_openid_show_otp_verification(){
	?>
	<td style="vertical-align:top;width:65%;">
		<!-- Enter otp -->
		<form name="f" method="post" id="otp_form" action="">
			<input type="hidden" name="option" value="mo_openid_validate_otp" />
				<div class="mo_openid_table_layout">
					<table class="mo_openid_settings_table">
							<h3>Verify Your Email</h3>
							<tr>
								<td><b><font color="#FF0000">*</font>Enter OTP:</b></td>
								<td colspan="3"><input class="mo_openid_table_textbox" autofocus="true" type="text" name="otp_token" required placeholder="Enter OTP" style="width:40%;" />
								 &nbsp;&nbsp;<a style="cursor:pointer;" onclick="document.getElementById('resend_otp_form').submit();">Resend OTP ?</a></td>
							</tr>
							<tr><td colspan="3"></td></tr>
							<tr>

								<td>&nbsp;</td>
								<td style="width:17%">
								<input type="submit" name="submit" value="Validate OTP" class="button button-primary button-large" /></td>

		</form>
		<form name="f" method="post">
		<td style="width:18%">
						<input type="hidden" name="option" value="mo_openid_go_back"/>
						<input type="submit" name="submit"  value="Back" class="button button-primary button-large" /></td>
		</form>
		<form name="f" id="resend_otp_form" method="post" action="">
							<td>

							<input type="hidden" name="option" value="mo_openid_resend_otp"/>
							</td>
							</tr>
							
						
		</form>
		</table>
		<br>
				<hr>

				<h3>I did not recieve any email with OTP . What should I do ?</h3>
				<form id="phone_verification" method="post" action="">
					<input type="hidden" name="option" value="mo_openid_phone_verification" />
					 If you can't see the email from miniOrange in your mails, please check your <b>SPAM Folder</b>. If you don't see an email even in SPAM folder, verify your identity with our alternate method.
					 <br><br>
						<b>Enter your valid phone number here and verify your identity using one time passcode sent to your phone.</b>
						<br><br>
						<table class="mo_openid_settings_table">
						<tr>
						<td colspan="3">
						<input class="mo_openid_table_textbox" required  pattern="[0-9\+]{12,18}" autofocus="true" style="width:100%;" type="tel" name="phone_number" id="phone" placeholder="Enter Phone Number" value="<?php echo get_option('mo_openid_admin_phone'); ?>" title="Enter phone number(at least 10 digits) without any space or dashes."/>
						</td>
						<td>&nbsp;&nbsp;
					<a style="cursor:pointer;" onclick="document.getElementById('phone_verification').submit();">Resend OTP ?</a>
						</td>
						</tr>
						</table>
						<br><input type="submit" value="Send OTP" class="button button-primary button-large" />
				
				</form>
				<br>
				<h3>What is an OTP ?</h3>
				<p>OTP is a one time passcode ( a series of numbers) that is sent to your email or phone number to verify that you have access to your email account or phone. </p>
		</div>
		<script>
		jQuery("#phone").intlTelInput();
					
						
		</script>
</td>
		<td style="vertical-align:top;padding-left:1%;">
			<?php echo miniorange_openid_support(); ?>
		</td>

<?php
}
function mo_openid_other_settings(){
	
?>
<td style="vertical-align:top;width:65%;">
	<form name="f" method="post" id="settings_form" action="">
	<input type="hidden" name="option" value="mo_openid_save_other_settings" />
	<div class="mo_openid_table_layout">
	
	<?php if(!mo_openid_is_customer_registered()) { ?>
		<div style="display:block;margin-top:10px;color:red;background-color:rgba(251, 232, 0, 0.15);padding:5px;border:solid 1px rgba(255, 0, 9, 0.36);">
		Please <a href="<?php echo add_query_arg( array('tab' => 'register'), $_SERVER['REQUEST_URI'] ); ?>">Register or Login with miniOrange</a> to enable Social Login and Social Sharing. miniOrange takes care of creating applications for you so that you don't have to worry about creating applications in each social network.
		</div>
	<?php } ?>
	
								<table>
									<tr>
										<td colspan="2">
											<h3>Social Sharing
											<input type="submit" name="submit" value="Save" style="width:100px;float:right;margin-right:2%"
											class="button button-primary button-large" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?>/>
												</h3>
												<b>Select applications to add share icons. Customize sharing icons by using a range of shapes, themes and sizes to suit to your website. You can also choose different places to display these icons. Additionally, place vertical floating icons on your pages.</b>
										</td>
										
									</tr>
								</table>
	
	<table class="mo_openid_settings_table">
		<h3>Select Social Apps</h3>
		<p>Select applications to enable social sharing</p>
		<tr>
			<td class="mo_openid_table_td_checkbox">
				<table style="width:100%">
					<tr>
						<td style="width:20%">
							<input type="checkbox" id="facebook_share_enable" class="app_enable" name="mo_openid_facebook_share_enable" value="1" 
							onclick="addSelectedApps();" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?> <?php checked( get_option('mo_openid_facebook_share_enable') == 1 );?> />
							<strong>Facebook</strong>
						</td>
						<td style="width:20%">
							<input type="checkbox" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?>
									id="twitter_share_enable" class="app_enable" name="mo_openid_twitter_share_enable" value="1" onclick="addSelectedApps();"
								<?php checked( get_option('mo_openid_twitter_share_enable') == 1 );?> />
							<strong>Twitter </strong>
						</td>
						<td style="width:20%">
							<input type="checkbox" id="google_share_enable" class="app_enable" name="mo_openid_google_share_enable" value="1" onclick="addSelectedApps();"
							<?php if(!mo_openid_is_customer_registered()) echo 'disabled'?> <?php checked( get_option('mo_openid_google_share_enable') == 1 );?> />
							<strong>Google</strong>
						</td>
						
						<td style="width:20%">
							<input type="checkbox" id="vkontakte_share_enable" class="app_enable" name="mo_openid_vkontakte_share_enable" value="1" 
							onclick="addSelectedApps();" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?> <?php checked( get_option('mo_openid_vkontakte_share_enable') == 1 );?> />
							<strong>Vkontakte</strong>
						</td>
						<td style="width:20%">
							<input type="checkbox" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?>
							id="tumblr_share_enable" class="app_enable" name="mo_openid_tumblr_share_enable" value="1" onclick="addSelectedApps();"
							<?php checked( get_option('mo_openid_tumblr_share_enable') == 1 );?> />
							<strong>Tumblr </strong>
						</td>
					</tr>
					<tr>
						<td style="width:20%">
							<input type="checkbox" id="stumble_share_enable" class="app_enable" name="mo_openid_stumble_share_enable" value="1" onclick="addSelectedApps();" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?> <?php checked( get_option('mo_openid_stumble_share_enable') == 1 );?> />
							<strong>StumbleUpon</strong>
						</td>
						<td style="width:20%">
							<input type="checkbox" id="linkedin_share_enable" class="app_enable" name="mo_openid_linkedin_share_enable" value="1" onclick="addSelectedApps();" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?>
							<?php checked( get_option('mo_openid_linkedin_share_enable') == 1 );?> />
							<strong>LinkedIn</strong>
						</td>
						<td style="width:20%">
							<input type="checkbox" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?>
							id="reddit_share_enable" class="app_enable" name="mo_openid_reddit_share_enable" value="1" onclick="addSelectedApps();"
							<?php checked( get_option('mo_openid_reddit_share_enable') == 1 );?> />
							<strong>Reddit </strong>
						</td>
						<td style="width:20%">
							<input type="checkbox" id="pinterest_share_enable" class="app_enable" name="mo_openid_pinterest_share_enable" value="1" onclick="addSelectedApps();"
							<?php if(!mo_openid_is_customer_registered()) echo 'disabled'?>
							<?php checked( get_option('mo_openid_pinterest_share_enable') == 1 );?> />
							<strong>Pinterest </strong>
						</td>
						<td style="width:20%">
							<input type="checkbox" id="pocket_share_enable" class="app_enable" name="mo_openid_pocket_share_enable" value="1" onclick="addSelectedApps();" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?> <?php checked( get_option('mo_openid_pocket_share_enable') == 1 );?> />
							<strong>Pocket</strong>
						</td>
					</tr>
					<tr>
						<td style="width:20%">
							<input type="checkbox" id="digg_share_enable" class="app_enable" name="mo_openid_digg_share_enable" value="1" 
							onclick="addSelectedApps();" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?> <?php checked( get_option('mo_openid_digg_share_enable') == 1 );?> />
							<strong>Digg</strong>
						</td>
						<td style="width:20%">
						<input type="checkbox" id="delicious_share_enable" class="app_enable" name="mo_openid_delicious_share_enable" value="1" 
						onclick="addSelectedApps();" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?> <?php 	checked( get_option('mo_openid_delicious_share_enable') == 1 );?> />
						<strong>Delicious</strong></td>
						<td style="width:20%">
							<input type="checkbox" id="odnoklassniki_share_enable" class="app_enable" name="mo_openid_odnoklassniki_share_enable" value="1" 
							onclick="addSelectedApps();" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?> <?php checked( get_option('mo_openid_odnoklassniki_share_enable') == 1 );?> />
							<strong>Odnoklassniki</strong>
						</td>
						<td style="width:20%">
							<input type="checkbox" id="mail_share_enable" class="app_enable" name="mo_openid_mail_share_enable" value="1" 
							onclick="addSelectedApps();moSharingPreview();" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?> <?php checked( get_option('mo_openid_mail_share_enable') == 1 );?> />
							<strong>Email</strong>
						</td>
						<td style="width:20%">
							<input type="checkbox" id="print_share_enable" class="app_enable" name="mo_openid_print_share_enable" value="1" 
							onclick="addSelectedApps();moSharingPreview();" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?> <?php checked( get_option('mo_openid_print_share_enable') == 1 );?> />
							<strong>Print</strong>
						</td>
					</tr>
					<tr>
						<td style="width:20%">
							<input type="checkbox" id="whatsapp_share_enable" class="app_enable" name="mo_openid_whatsapp_share_enable" value="1" 
							onclick="addSelectedApps();moSharingPreview();" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?> <?php checked( get_option('mo_openid_whatsapp_share_enable') == 1 );?> />
							<strong>Whatsapp</strong>
						</td>
					</tr>
					<tr><td>(Only visible on Mobile Phones)</td></tr>
				</table>
			</td>
		</tr>
			
									

		
		
		<tr>
			<td>
				<br>
				<hr>
				<h3>Customize Sharing Icons</h3>
				<p>Customize shape, size and background for sharing icons</p>
			</td>
		</tr>
		<tr>
			<td>
				<table style="width:98%">
					<tr>
						<td><b>Shape</b></td>
						<td><b>Theme</b></td>
						<td><b>Space between Icons</b></td>
						<td><b>Size of Icons</b></td>
					</tr>
					<tr>
						<td style="width:inherit;"> <!-- Shape radio buttons -->
							<!-- Round -->
							<input type="radio" id="mo_openid_share_theme_circle"  <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?>  name="mo_openid_share_theme" value="circle" onclick="tempHorShape = 'circle';moSharingPreview('horizontal', document.getElementById('mo_sharing_icon_size').value, 'circle', setCustomTheme(), document.getElementById('mo_sharing_icon_custom_color').value, document.getElementById('mo_sharing_icon_space').value, document.getElementById('mo_sharing_icon_custom_font').value)" <?php checked( get_option('mo_openid_share_theme') == 'circle' );?> />Round
							
						</td>
						<td><!-- Theme radio buttons -->
							<!-- Default -->
							<input type="radio" id="mo_openid_default_background_radio"  name="mo_openid_share_custom_theme" value="default" onclick="tempHorTheme = 'default';moSharingPreview('horizontal', document.getElementById('mo_sharing_icon_size').value, setTheme(), 'default', document.getElementById('mo_sharing_icon_custom_color').value, document.getElementById('mo_sharing_icon_space').value, document.getElementById('mo_sharing_icon_custom_font').value)"
							<?php checked( get_option('mo_openid_share_custom_theme') == 'default' );?> <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?>/>Default
						</td>
						<td> <!-- Size between icons buttons-->
							<input style="width:50px" onkeyup="moSharingSpaceValidate(this)" id="mo_sharing_icon_space" name="mo_sharing_icon_space" type="text" value="<?php echo get_option('mo_sharing_icon_space')?>" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?>/>
							<input id="mo_sharing_space_plus" type="button" value="+" onmouseup="moSharingPreview('horizontal',document.getElementById('mo_sharing_icon_size').value ,setTheme(),setCustomTheme(),document.getElementById('mo_sharing_icon_custom_color').value,document.getElementById('mo_sharing_icon_space').value)" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?>/>
							<input id="mo_sharing_space_minus" type="button" value="-" onmouseup="moSharingPreview('horizontal',document.getElementById('mo_sharing_icon_size').value ,setTheme(),setCustomTheme(),document.getElementById('mo_sharing_icon_custom_color').value,document.getElementById('mo_sharing_icon_space').value)" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?>/>
						</td>
						<td> <!-- Size buttons-->
							<input style="width:50px" id="mo_sharing_icon_size" onkeyup="moSharingSizeValidate(this)" name="mo_sharing_icon_custom_size" type="text" value="<?php echo get_option('mo_sharing_icon_custom_size')?>" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?>>
				
							<input id="mo_sharing_size_plus" type="button" value="+" onmouseup="tempHorSize = document.getElementById('mo_sharing_icon_size').value;moSharingPreview('horizontal',document.getElementById('mo_sharing_icon_size').value , setTheme(), setCustomTheme(), document.getElementById('mo_sharing_icon_custom_color').value, document.getElementById('mo_sharing_icon_space').value,document.getElementById('mo_sharing_icon_custom_font').value)" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?>>
				
							<input id="mo_sharing_size_minus" type="button" value="-" onmouseup="tempHorSize = document.getElementById('mo_sharing_icon_size').value;moSharingPreview('horizontal',document.getElementById('mo_sharing_icon_size').value ,setTheme(), setCustomTheme(), document.getElementById('mo_sharing_icon_custom_color').value, document.getElementById('mo_sharing_icon_space').value, document.getElementById('mo_sharing_icon_custom_font').value)" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?>>
						</td>
					</tr>
					<tr>
						<td> <!-- Shape radio buttons -->
							<!-- Rounded Edges -->
							<input type="radio"   name="mo_openid_share_theme"  value="oval" onclick="tempHorShape = 'circle';moSharingPreview('horizontal', document.getElementById('mo_sharing_icon_size').value, 'oval', setCustomTheme(), document.getElementById('mo_sharing_icon_custom_color').value, document.getElementById('mo_sharing_icon_space').value)" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?> <?php checked( get_option('mo_openid_share_theme') == 'oval' );?> />Rounded Edges
						</td>
						<td> <!-- Theme radio buttons -->
							<!-- Custom background -->
							
							<input type="radio" id="mo_openid_custom_background_radio"  name="mo_openid_share_custom_theme" value="custom" onclick="tempHorTheme = 'custom';moSharingPreview('horizontal', document.getElementById('mo_sharing_icon_size').value, setTheme(),'custom',document.getElementById('mo_sharing_icon_custom_color').value,document.getElementById('mo_sharing_icon_space').value)" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?>
							<?php checked( get_option('mo_openid_share_custom_theme') == 'custom' );?> />Custom background*
						</td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td> <!-- Shape radio buttons -->
							<!-- Square -->
							<input type="radio"   name="mo_openid_share_theme" value="square" onclick="tempHorShape = 'square';moSharingPreview('horizontal', document.getElementById('mo_sharing_icon_size').value, setTheme(), setCustomTheme(), document.getElementById('mo_sharing_icon_custom_color').value, document.getElementById('mo_sharing_icon_space').value)" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?> <?php checked( get_option('mo_openid_share_theme') == 'square' );?> />Square
						</td>
						<td> <!-- Theme radio buttons -->
							<!-- Custom background textbox -->
							
							<input id="mo_sharing_icon_custom_color" name="mo_sharing_icon_custom_color" class="color" value="<?php echo get_option('mo_sharing_icon_custom_color')?>" onchange="moSharingPreview('horizontal', document.getElementById('mo_sharing_icon_size').value, setTheme(),setCustomTheme(),document.getElementById('mo_sharing_icon_custom_color').value,document.getElementById('mo_sharing_icon_space').value,document.getElementById('mo_sharing_icon_custom_font').value)" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?>>
						</td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td></td>
						<td> <!-- Theme radio buttons -->
							<!-- No background -->
							<input type="radio" id="mo_openid_no_background_radio"  name="mo_openid_share_custom_theme" value="customFont" onclick="tempHorTheme = 'custom';moSharingPreview('horizontal', document.getElementById('mo_sharing_icon_size').value, setTheme(),'customFont',document.getElementById('mo_sharing_icon_custom_color').value,document.getElementById('mo_sharing_icon_space').value,document.getElementById('mo_sharing_icon_custom_font').value)" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?> <?php checked( get_option('mo_openid_share_custom_theme') == 'customFont' );?> />No background*
						</td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td></td>
						<td> <!-- Theme radio buttons -->
							<!-- No background textbox-->
							<input id="mo_sharing_icon_custom_font" name="mo_sharing_icon_custom_font"  class="color" value="<?php echo get_option('mo_sharing_icon_custom_font')?>" onchange="moSharingPreview('horizontal', document.getElementById('mo_sharing_icon_size').value, setTheme(),setCustomTheme(),document.getElementById('mo_sharing_icon_custom_color').value,document.getElementById('mo_sharing_icon_space').value,document.getElementById('mo_sharing_icon_custom_font').value,document.getElementById('mo_sharing_icon_custom_font').value)" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?>/>
						</td>
						<td></td>
						<td></td>
					</tr>
				</table>
			</td>
		</tr>					
		
		<tr><td>&nbsp;</td></tr>		
		
		<tr>
			<td><b>Preview: </b><br/><span hidden id="no_apps_text">No apps selected</span></td>
		</tr>
		
		<tr>
			<td>
		
				<div>
					<img class="mo_sharing_icon_preview" id="mo_sharing_icon_preview_facebook" src="<?php echo plugins_url( 'includes/images/icons/facebook.png', __FILE__ )?>" />
					<img class="mo_sharing_icon_preview" id="mo_sharing_icon_preview_twitter" src="<?php echo plugins_url( 'includes/images/icons/twitter.png', __FILE__ )?>" />
					<img class="mo_sharing_icon_preview" id="mo_sharing_icon_preview_google" src="<?php echo plugins_url( 'includes/images/icons/google.png', __FILE__ )?>" />
					<img class="mo_sharing_icon_preview" id="mo_sharing_icon_preview_vk" src="<?php echo plugins_url( 'includes/images/icons/vk.png', __FILE__ )?>" />
					<img class="mo_sharing_icon_preview" id="mo_sharing_icon_preview_tumblr" src="<?php echo plugins_url( 'includes/images/icons/tumblr.png', __FILE__ )?>" />
					<img class="mo_sharing_icon_preview" id="mo_sharing_icon_preview_stumble" src="<?php echo plugins_url( 'includes/images/icons/stumble.png', __FILE__ )?>" />
					<img class="mo_sharing_icon_preview" id="mo_sharing_icon_preview_linkedin" src="<?php echo plugins_url( 'includes/images/icons/linkedin.png', __FILE__ )?>" />
					<img class="mo_sharing_icon_preview" id="mo_sharing_icon_preview_reddit" src="<?php echo plugins_url( 'includes/images/icons/reddit.png', __FILE__ )?>" />
					<img class="mo_sharing_icon_preview" id="mo_sharing_icon_preview_pinterest" src="<?php echo plugins_url( 'includes/images/icons/pininterest.png', __FILE__ )?>" />
					<img class="mo_sharing_icon_preview" id="mo_sharing_icon_preview_pocket" src="<?php echo plugins_url( 'includes/images/icons/pocket.png', __FILE__ )?>" />
					<img class="mo_sharing_icon_preview" id="mo_sharing_icon_preview_digg" src="<?php echo plugins_url( 'includes/images/icons/digg.png', __FILE__ )?>" />
					<img class="mo_sharing_icon_preview" id="mo_sharing_icon_preview_delicious" src="<?php echo plugins_url( 'includes/images/icons/delicious.png', __FILE__ )?>" />
					<img class="mo_sharing_icon_preview" id="mo_sharing_icon_preview_odnoklassniki" src="<?php echo plugins_url( 'includes/images/icons/odnoklassniki.png', __FILE__ )?>" />
					<img class="mo_sharing_icon_preview" id="mo_sharing_icon_preview_mail" src="<?php echo plugins_url( 'includes/images/icons/mail.png', __FILE__ )?>"/>
					<img class="mo_sharing_icon_preview" id="mo_sharing_icon_preview_print" src="<?php echo plugins_url( 'includes/images/icons/print.png', __FILE__ )?>"/>
					<img class="mo_sharing_icon_preview" id="mo_sharing_icon_preview_whatsapp" src="<?php echo plugins_url( 'includes/images/icons/whatsapp.png', __FILE__ )?>"/>
				</div>
		
				<div>
					<i class="mo_custom_sharing_icon_preview fa fa-facebook" id="mo_custom_sharing_icon_preview_facebook"  style="color:#ffffff;text-align:center;margin-top:5px;"></i>
					<i class="mo_custom_sharing_icon_preview fa fa-twitter" id="mo_custom_sharing_icon_preview_twitter" style="color:#ffffff;text-align:center;margin-top:5px;" ></i>
					<i class="mo_custom_sharing_icon_preview fa fa-google-plus" id="mo_custom_sharing_icon_preview_google"  style="color:#ffffff;text-align:center;margin-top:5px;"></i>
					<i class="mo_custom_sharing_icon_preview fa fa-vk" id="mo_custom_sharing_icon_preview_vk"  style="color:#ffffff;text-align:center;margin-top:5px;"></i>
					<i class="mo_custom_sharing_icon_preview fa fa-tumblr" id="mo_custom_sharing_icon_preview_tumblr"  style="color:#ffffff;text-align:center;margin-top:5px;"></i>
					<i class="mo_custom_sharing_icon_preview fa fa-stumbleupon" id="mo_custom_sharing_icon_preview_stumble"  style="color:#ffffff;text-align:center;margin-top:5px;"></i>
					<i class="mo_custom_sharing_icon_preview fa fa-linkedin" id="mo_custom_sharing_icon_preview_linkedin" style="color:#ffffff;text-align:center;margin-top:5px;"></i>
					<i class="mo_custom_sharing_icon_preview fa fa-reddit" id="mo_custom_sharing_icon_preview_reddit"  style="color:#ffffff;text-align:center;margin-top:5px;"></i>
					<i class="mo_custom_sharing_icon_preview fa fa-pinterest" id="mo_custom_sharing_icon_preview_pinterest"  style="color:#ffffff;text-align:center;margin-top:5px;"></i>
					<i class="mo_custom_sharing_icon_preview fa fa-get-pocket" id="mo_custom_sharing_icon_preview_pocket"  style="color:#ffffff;text-align:center;margin-top:5px;"></i>
					<i class="mo_custom_sharing_icon_preview fa fa-digg" id="mo_custom_sharing_icon_preview_digg"  style="color:#ffffff;text-align:center;margin-top:5px;"></i>
					<i class="mo_custom_sharing_icon_preview fa fa-delicious" id="mo_custom_sharing_icon_preview_delicious"  style="color:#ffffff;text-align:center;margin-top:5px;"></i>
					<i class="mo_custom_sharing_icon_preview fa fa-odnoklassniki" id="mo_custom_sharing_icon_preview_odnoklassniki"  style="color:#ffffff;text-align:center;margin-top:5px;"></i>
					<i class="mo_custom_sharing_icon_preview fa fa-envelope" id="mo_custom_sharing_icon_preview_mail"  style="color:#ffffff;text-align:center;  "></i>
					<i class="mo_custom_sharing_icon_preview fa fa-print" id="mo_custom_sharing_icon_preview_print"  style="color:#ffffff;text-align:center;  "></i>
					<i class="mo_custom_sharing_icon_preview fa fa-whatsapp" id="mo_custom_sharing_icon_preview_whatsapp"  style="color:#ffffff;text-align:center;  "></i>
				</div>
											
				<div>
					<i class="mo_custom_sharing_icon_font_preview fa fa-facebook" id="mo_custom_sharing_icon_font_preview_facebook"  style="text-align:center;margin-top:5px;"></i>
					<i class="mo_custom_sharing_icon_font_preview fa fa-twitter" id="mo_custom_sharing_icon_font_preview_twitter" style="text-align:center;margin-top:5px;" ></i>
					<i class="mo_custom_sharing_icon_font_preview fa fa-google-plus" id="mo_custom_sharing_icon_font_preview_google"  style="text-align:center;margin-top:5px;"></i>
					<i class="mo_custom_sharing_icon_font_preview fa fa-vk" id="mo_custom_sharing_icon_font_preview_vk"  style="text-align:center;margin-top:5px;"></i>
					<i class="mo_custom_sharing_icon_font_preview fa fa-tumblr" id="mo_custom_sharing_icon_font_preview_tumblr"  style="text-align:center;margin-top:5px;"></i>
					<i class="mo_custom_sharing_icon_font_preview fa fa-stumbleupon" id="mo_custom_sharing_icon_font_preview_stumble"  style="text-align:center;margin-top:5px;"></i>
					<i class="mo_custom_sharing_icon_font_preview fa fa-linkedin" id="mo_custom_sharing_icon_font_preview_linkedin" style="text-align:center;margin-top:5px;"></i>
					<i class="mo_custom_sharing_icon_font_preview fa fa-reddit" id="mo_custom_sharing_icon_font_preview_reddit"  style="text-align:center;margin-top:5px;"></i>
					<i class="mo_custom_sharing_icon_font_preview fa fa-pinterest" id="mo_custom_sharing_icon_font_preview_pinterest"  style="text-align:center;margin-top:5px;"></i>
					<i class="mo_custom_sharing_icon_font_preview fa fa-get-pocket" id="mo_custom_sharing_icon_font_preview_pocket"  style="text-align:center;margin-top:5px;"></i>
					<i class="mo_custom_sharing_icon_font_preview fa fa-digg" id="mo_custom_sharing_icon_font_preview_digg"  style="text-align:center;margin-top:5px;"></i>
					<i class="mo_custom_sharing_icon_font_preview fa fa-delicious" id="mo_custom_sharing_icon_font_preview_delicious"  style="text-align:center;margin-top:5px;"></i>
					<i class="mo_custom_sharing_icon_font_preview fa fa-odnoklassniki" id="mo_custom_sharing_icon_font_preview_odnoklassniki"  style="text-align:center;margin-top:5px;"></i>
					<i class="mo_custom_sharing_icon_font_preview fa fa-envelope" id="mo_custom_sharing_icon_font_preview_mail"  style="text-align:center;  "></i>
					<i class="mo_custom_sharing_icon_font_preview fa fa-print" id="mo_custom_sharing_icon_font_preview_print"  style="text-align:center;  "></i>
					<i class="mo_custom_sharing_icon_font_preview fa fa-whatsapp" id="mo_custom_sharing_icon_font_preview_whatsapp"  style="text-align:center;  "></i>
				</div>
	
			</td>
		</tr>
		
		<script>
					var tempHorSize = '<?php echo get_option('mo_sharing_icon_custom_size') ?>';
					var tempHorShape = '<?php echo get_option('mo_openid_share_theme') ?>';
					var tempHorTheme = '<?php echo get_option('mo_openid_share_custom_theme') ?>';
					var tempbackColor = '<?php echo get_option('mo_sharing_icon_custom_color')?>';
					var tempHorSpace = '<?php echo get_option('mo_sharing_icon_space')?>';
					var tempHorFontColor = '<?php echo get_option('mo_sharing_icon_custom_font')?>';
					function moSharingIncrement(e,t,r,a,i){
						var h,s,c=!1,_=a;s=function(){
							"add"==t&&r.value<60?r.value++:"subtract"==t&&r.value>10&&r.value--,h=setTimeout(s,_),_>20&&(_*=i),c||(document.onmouseup=function(){clearTimeout(h),document.onmouseup=null,c=!1,_=a},c=!0)},e.onmousedown=s}
					
					moSharingIncrement(document.getElementById('mo_sharing_size_plus'), "add", document.getElementById('mo_sharing_icon_size'), 300, 0.7);
					moSharingIncrement(document.getElementById('mo_sharing_size_minus'), "subtract", document.getElementById('mo_sharing_icon_size'), 300, 0.7);
					
					function moSharingSpaceIncrement(e,t,r,a,i){
						var h,s,c=!1,_=a;s=function(){
							"add"==t&&r.value<50?r.value++:"subtract"==t&&r.value>0&&r.value--,h=setTimeout(s,_),_>20&&(_*=i),c||(document.onmouseup=function(){clearTimeout(h),document.onmouseup=null,c=!1,_=a},c=!0)},e.onmousedown=s}
					moSharingSpaceIncrement(document.getElementById('mo_sharing_space_plus'), "add", document.getElementById('mo_sharing_icon_space'), 300, 0.7);
					moSharingSpaceIncrement(document.getElementById('mo_sharing_space_minus'), "subtract", document.getElementById('mo_sharing_icon_space'), 300, 0.7);
					
					
					function setTheme(){return jQuery('input[name=mo_openid_share_theme]:checked', '#settings_form').val();}
					function setCustomTheme(){return jQuery('input[name=mo_openid_share_custom_theme]:checked', '#settings_form').val();}
		</script>	
		
		

		<script type="text/javascript">
				
				var selectedApps = [];
		
					
				
						function moSharingPreview(e,t,r,w,h,n,x){
							
							if("default"==w){
								var a="mo_sharing_icon_preview";
								jQuery('.mo_sharing_icon_preview').show();
								jQuery('.mo_custom_sharing_icon_preview').hide();
								jQuery('.mo_custom_sharing_icon_font_preview').hide();
								jQuery("."+a).css({height:t,width:t});
								jQuery("."+a).css("font-size",(t-10)+"px");
								jQuery("."+a).css("margin-left",(n-4)+"px");
								
								if(r=="circle"){
								jQuery("."+a).css("borderRadius","999px");
								}else if(r=="oval"){
								jQuery("."+a).css("borderRadius","5px");
								}else if(r=="square"){
								jQuery("."+a).css("borderRadius","0px");
								}
								
							}
							else if(w == "custom"){
								var a="mo_custom_sharing_icon_preview";
								jQuery('.mo_sharing_icon_preview').hide();
								jQuery('.mo_custom_sharing_icon_font_preview').hide();
								jQuery('.mo_custom_sharing_icon_preview').show();
								jQuery("."+a).css("background","#"+h);
								jQuery("."+a).css("padding-top","8px");
								jQuery("."+a).css({height:t-8,width:t});
								jQuery("."+a).css("font-size",(t-16)+"px");
								
								if(r=="circle"){
								jQuery("."+a).css("borderRadius","999px");
								}else if(r=="oval"){
								jQuery("."+a).css("borderRadius","5px");
								}else if(r=="square"){
								jQuery("."+a).css("borderRadius","0px");
								}
								
								jQuery("."+a).css("margin-left",(n-4)+"px");
							}
							
							else if("customFont"==w){
								var a="mo_custom_sharing_icon_font_preview";
								jQuery('.mo_sharing_icon_preview').hide();
								jQuery('.mo_custom_sharing_icon_preview').hide();
								jQuery('.mo_custom_sharing_icon_font_preview').show();
								jQuery("."+a).css("font-size",t+"px");
								jQuery('.mo_custom_sharing_icon_font_preview').css("color","#"+x);
								jQuery("."+a).css("margin-left",(n-4)+"px");
								
								if(r=="circle"){
								jQuery("."+a).css("borderRadius","999px");
								
								}else if(r=="oval"){
								jQuery("."+a).css("borderRadius","5px");
								}else if(r=="square"){
								jQuery("."+a).css("borderRadius","0px");
								}
								
							}
							addSelectedApps();
							
							
							
						}
						moSharingPreview('horizontal',tempHorSize,tempHorShape,tempHorTheme,tempbackColor,tempHorSpace,tempHorFontColor);
						
						function addSelectedApps() {
							var flag = 0;
								if (document.getElementById('google_share_enable').checked)   {
									flag = 1;
										if(document.getElementById('mo_openid_default_background_radio').checked)
											jQuery("#mo_sharing_icon_preview_google").show();
										if(document.getElementById('mo_openid_custom_background_radio').checked)
											jQuery("#mo_custom_sharing_icon_preview_google").show();
										if(document.getElementById('mo_openid_no_background_radio').checked)
											jQuery("#mo_custom_sharing_icon_font_preview_google").show();
								} else if(!document.getElementById('google_share_enable').checked){
									jQuery("#mo_sharing_icon_preview_google").hide();
									jQuery("#mo_custom_sharing_icon_preview_google").hide();
									jQuery("#mo_custom_sharing_icon_font_preview_google").hide();
									
								}
								
								if (document.getElementById('facebook_share_enable').checked) {
									flag = 1;
									if(document.getElementById('mo_openid_default_background_radio').checked)
										jQuery("#mo_sharing_icon_preview_facebook").show();
									if(document.getElementById('mo_openid_custom_background_radio').checked)
										jQuery("#mo_custom_sharing_icon_preview_facebook").show();
									if(document.getElementById('mo_openid_no_background_radio').checked)
										jQuery("#mo_custom_sharing_icon_font_preview_facebook").show();
								}else if(!document.getElementById('facebook_share_enable').checked){
									jQuery("#mo_sharing_icon_preview_facebook").hide();
									jQuery("#mo_custom_sharing_icon_preview_facebook").hide();
									jQuery("#mo_custom_sharing_icon_font_preview_facebook").hide();
								}
								
								if (document.getElementById('linkedin_share_enable').checked) {
									flag = 1;
									if(document.getElementById('mo_openid_default_background_radio').checked)
										jQuery("#mo_sharing_icon_preview_linkedin").show();
									if(document.getElementById('mo_openid_custom_background_radio').checked)
										jQuery("#mo_custom_sharing_icon_preview_linkedin").show();
									if(document.getElementById('mo_openid_no_background_radio').checked)	
										jQuery("#mo_custom_sharing_icon_font_preview_linkedin").show();
								} else if(!document.getElementById('linkedin_share_enable').checked){
									jQuery("#mo_sharing_icon_preview_linkedin").hide();
									jQuery("#mo_custom_sharing_icon_preview_linkedin").hide();
									jQuery("#mo_custom_sharing_icon_font_preview_linkedin").hide();
								}
								
								if (document.getElementById('twitter_share_enable').checked) {
									flag = 1;
									if(document.getElementById('mo_openid_default_background_radio').checked)
										jQuery("#mo_sharing_icon_preview_twitter").show();
									if(document.getElementById('mo_openid_custom_background_radio').checked)
										jQuery("#mo_custom_sharing_icon_preview_twitter").show();
									if(document.getElementById('mo_openid_no_background_radio').checked)
										jQuery("#mo_custom_sharing_icon_font_preview_twitter").show();
								} else if(!document.getElementById('twitter_share_enable').checked){
									jQuery("#mo_sharing_icon_preview_twitter").hide();
									jQuery("#mo_custom_sharing_icon_preview_twitter").hide();
									jQuery("#mo_custom_sharing_icon_font_preview_twitter").hide();
								}
								
								if (document.getElementById('pinterest_share_enable').checked) {
									flag = 1;
									if(document.getElementById('mo_openid_default_background_radio').checked)
										jQuery("#mo_sharing_icon_preview_pinterest").show();
									if(document.getElementById('mo_openid_custom_background_radio').checked)
										jQuery("#mo_custom_sharing_icon_preview_pinterest").show();
									if(document.getElementById('mo_openid_no_background_radio').checked)
										jQuery("#mo_custom_sharing_icon_font_preview_pinterest").show();
								}else if(!document.getElementById('pinterest_share_enable').checked){
									jQuery("#mo_sharing_icon_preview_pinterest").hide();
									jQuery("#mo_custom_sharing_icon_preview_pinterest").hide();
									jQuery("#mo_custom_sharing_icon_font_preview_pinterest").hide();
								}
								
								if (document.getElementById('reddit_share_enable').checked) {
									flag = 1;
									if(document.getElementById('mo_openid_default_background_radio').checked)
										jQuery("#mo_sharing_icon_preview_reddit").show();
									if(document.getElementById('mo_openid_custom_background_radio').checked)
										jQuery("#mo_custom_sharing_icon_preview_reddit").show();
									if(document.getElementById('mo_openid_no_background_radio').checked)
										jQuery("#mo_custom_sharing_icon_font_preview_reddit").show();
									//}
								} else if(!document.getElementById('reddit_share_enable').checked){
									jQuery("#mo_sharing_icon_preview_reddit").hide();
									jQuery("#mo_custom_sharing_icon_preview_reddit").hide();
									jQuery("#mo_custom_sharing_icon_font_preview_reddit").hide();
								}
								
								if (document.getElementById('vkontakte_share_enable').checked) {
									flag = 1;
									if(document.getElementById('mo_openid_default_background_radio').checked)
										jQuery("#mo_sharing_icon_preview_vk").show();
									if(document.getElementById('mo_openid_custom_background_radio').checked)
										jQuery("#mo_custom_sharing_icon_preview_vk").show();
									if(document.getElementById('mo_openid_no_background_radio').checked)
										jQuery("#mo_custom_sharing_icon_font_preview_vk").show();
									//}
								} else if(!document.getElementById('vkontakte_share_enable').checked){
									jQuery("#mo_sharing_icon_preview_vk").hide();
									jQuery("#mo_custom_sharing_icon_preview_vk").hide();
									jQuery("#mo_custom_sharing_icon_font_preview_vk").hide();
								}
								
								if (document.getElementById('stumble_share_enable').checked) {
									flag = 1;
									if(document.getElementById('mo_openid_default_background_radio').checked)
										jQuery("#mo_sharing_icon_preview_stumble").show();
									if(document.getElementById('mo_openid_custom_background_radio').checked)
										jQuery("#mo_custom_sharing_icon_preview_stumble").show();
									if(document.getElementById('mo_openid_no_background_radio').checked)
										jQuery("#mo_custom_sharing_icon_font_preview_stumble").show();
									//}
								} else if(!document.getElementById('stumble_share_enable').checked){
									jQuery("#mo_sharing_icon_preview_stumble").hide();
									jQuery("#mo_custom_sharing_icon_preview_stumble").hide();
									jQuery("#mo_custom_sharing_icon_font_preview_stumble").hide();
								}
								
								if (document.getElementById('tumblr_share_enable').checked) {
									flag = 1;
									if(document.getElementById('mo_openid_default_background_radio').checked)
										jQuery("#mo_sharing_icon_preview_tumblr").show();
									if(document.getElementById('mo_openid_custom_background_radio').checked)
										jQuery("#mo_custom_sharing_icon_preview_tumblr").show();
									if(document.getElementById('mo_openid_no_background_radio').checked)
										jQuery("#mo_custom_sharing_icon_font_preview_tumblr").show();
									//}
								} else if(!document.getElementById('tumblr_share_enable').checked){
									jQuery("#mo_sharing_icon_preview_tumblr").hide();
									jQuery("#mo_custom_sharing_icon_preview_tumblr").hide();
									jQuery("#mo_custom_sharing_icon_font_preview_tumblr").hide();
								}
								
								if (document.getElementById('pocket_share_enable').checked) {
									flag = 1;
									if(document.getElementById('mo_openid_default_background_radio').checked)
										jQuery("#mo_sharing_icon_preview_pocket").show();
									if(document.getElementById('mo_openid_custom_background_radio').checked)
										jQuery("#mo_custom_sharing_icon_preview_pocket").show();
									if(document.getElementById('mo_openid_no_background_radio').checked)
										jQuery("#mo_custom_sharing_icon_font_preview_pocket").show();
									//}
								} else if(!document.getElementById('pocket_share_enable').checked){
									jQuery("#mo_sharing_icon_preview_pocket").hide();
									jQuery("#mo_custom_sharing_icon_preview_pocket").hide();
									jQuery("#mo_custom_sharing_icon_font_preview_pocket").hide();
								}
								if (document.getElementById('digg_share_enable').checked) {
									flag = 1;
									if(document.getElementById('mo_openid_default_background_radio').checked)
										jQuery("#mo_sharing_icon_preview_digg").show();
									if(document.getElementById('mo_openid_custom_background_radio').checked)
										jQuery("#mo_custom_sharing_icon_preview_digg").show();
									if(document.getElementById('mo_openid_no_background_radio').checked)
										jQuery("#mo_custom_sharing_icon_font_preview_digg").show();
									//}
								} else if(!document.getElementById('digg_share_enable').checked){
									jQuery("#mo_sharing_icon_preview_digg").hide();
									jQuery("#mo_custom_sharing_icon_preview_digg").hide();
									jQuery("#mo_custom_sharing_icon_font_preview_digg").hide();
								}
								if (document.getElementById('delicious_share_enable').checked) {
									flag = 1;
									if(document.getElementById('mo_openid_default_background_radio').checked)
										jQuery("#mo_sharing_icon_preview_delicious").show();
									if(document.getElementById('mo_openid_custom_background_radio').checked)
										jQuery("#mo_custom_sharing_icon_preview_delicious").show();
									if(document.getElementById('mo_openid_no_background_radio').checked)
										jQuery("#mo_custom_sharing_icon_font_preview_delicious").show();
									//}
								} else if(!document.getElementById('delicious_share_enable').checked){
									jQuery("#mo_sharing_icon_preview_delicious").hide();
									jQuery("#mo_custom_sharing_icon_preview_delicious").hide();
									jQuery("#mo_custom_sharing_icon_font_preview_delicious").hide();
								}
								if (document.getElementById('odnoklassniki_share_enable').checked) {
									flag = 1;
									if(document.getElementById('mo_openid_default_background_radio').checked)
										jQuery("#mo_sharing_icon_preview_odnoklassniki").show();
									if(document.getElementById('mo_openid_custom_background_radio').checked)
										jQuery("#mo_custom_sharing_icon_preview_odnoklassniki").show();
									if(document.getElementById('mo_openid_no_background_radio').checked)
										jQuery("#mo_custom_sharing_icon_font_preview_odnoklassniki").show();
									//}
								} else if(!document.getElementById('odnoklassniki_share_enable').checked){
									jQuery("#mo_sharing_icon_preview_odnoklassniki").hide();
									jQuery("#mo_custom_sharing_icon_preview_odnoklassniki").hide();
									jQuery("#mo_custom_sharing_icon_font_preview_odnoklassniki").hide();
								}
								if (document.getElementById('mail_share_enable').checked) {
									flag = 1;
									if(document.getElementById('mo_openid_default_background_radio').checked)
											jQuery("#mo_sharing_icon_preview_mail").show();
									if(document.getElementById('mo_openid_custom_background_radio').checked)
										jQuery("#mo_custom_sharing_icon_preview_mail").show();
									if(document.getElementById('mo_openid_no_background_radio').checked)
										jQuery("#mo_custom_sharing_icon_font_preview_mail").show();
								} else if(!document.getElementById('mail_share_enable').checked){
									jQuery("#mo_sharing_icon_preview_mail").hide();
									jQuery("#mo_custom_sharing_icon_preview_mail").hide();
									jQuery("#mo_custom_sharing_icon_font_preview_mail").hide();
								}
								if (document.getElementById('print_share_enable').checked) {
									flag = 1;
									if(document.getElementById('mo_openid_default_background_radio').checked)
											jQuery("#mo_sharing_icon_preview_print").show();
									if(document.getElementById('mo_openid_custom_background_radio').checked)
										jQuery("#mo_custom_sharing_icon_preview_print").show();
									if(document.getElementById('mo_openid_no_background_radio').checked)
										jQuery("#mo_custom_sharing_icon_font_preview_print").show();
								} else if(!document.getElementById('print_share_enable').checked){
									jQuery("#mo_sharing_icon_preview_print").hide();
									jQuery("#mo_custom_sharing_icon_preview_print").hide();
									jQuery("#mo_custom_sharing_icon_font_preview_print").hide();
								}
								if (document.getElementById('whatsapp_share_enable').checked) {
									flag = 1;
									if(document.getElementById('mo_openid_default_background_radio').checked)
											jQuery("#mo_sharing_icon_preview_whatsapp").show();
									if(document.getElementById('mo_openid_custom_background_radio').checked)
										jQuery("#mo_custom_sharing_icon_preview_whatsapp").show();
										jQuery("#mo_sharing_button_preview_custom_whatsapp").show();
									if(document.getElementById('mo_openid_no_background_radio').checked)
										jQuery("#mo_custom_sharing_icon_font_preview_whatsapp").show();
									
								} else if(!document.getElementById('whatsapp_share_enable').checked){
									jQuery("#mo_sharing_icon_preview_whatsapp").hide();
									jQuery("#mo_custom_sharing_icon_preview_whatsapp").hide();
									jQuery("#mo_custom_sharing_icon_font_preview_whatsapp").hide();
								}
								
								if(flag) {
									jQuery("#no_apps_text").hide();
								} else {
									jQuery("#no_apps_text").show();
								}
						}
						
				jQuery( document ).ready(function() {
						addSelectedApps();					
				});
		</script>
		<tr>
			<td>
				<br/>
				<strong>*NOTE:</strong><br/>Custom background: This will change the background color of sharing icons.
				<br/>No background: This will change the font color of icons without background.
			</td>
		</tr>
		<tr>
			<td>
			<br>
			<hr>
			<h3>Customize Text For Social Share Icons</h3>
			</td>
		</tr>
		<tr>
			<td>
				<b>Enter text to show above share widget:</b>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input class="mo_openid_table_textbox" style="width:50%;" type="text" name="mo_openid_share_widget_customize_text"
					value="<?php echo get_option('mo_openid_share_widget_customize_text'); ?>" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?> />
			</td>
		</tr>
		<tr>
			<td>
				<b>Enter your twitter Username (without @):</b>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input class="mo_openid_table_textbox" style="width:50%;" type="text" name="mo_openid_share_twitter_username"
					value="<?php echo get_option('mo_openid_share_twitter_username'); ?>" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?> />
			</td>
		</tr>
		<tr>
			<td>
				<b>Enter the Email subject (email share):</b>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input class="mo_openid_table_textbox" style="width:50%;" type="text" name="mo_openid_share_email_subject"
					value="<?php echo get_option('mo_openid_share_email_subject'); ?>" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?> />
			</td>
		</tr>
		<tr>
			<td>
				<b>Enter the Email body (add ##url## to place the URL):</b>
				&nbsp;&nbsp;&nbsp;&nbsp;
				<input class="mo_openid_table_textbox" style="width:50%;" type="text" name="mo_openid_share_email_body"
					value="<?php echo get_option('mo_openid_share_email_body'); ?>" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?> />
			</td>
		</tr>
		<tr>
			<td>
				<br>
				 <hr>
				<h3>Display Options</h3>
				<p><strong>Select the options where you want to display social share icons</strong></p>
			</td>
		</tr>
											
		<tr>
			<td>
				<input type="checkbox" id="mo_apps_home_page"  name="mo_share_options_home_page"  value="1" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?> <?php checked( get_option('mo_share_options_enable_home_page') == 1 );?>>
				Home Page
				<br/>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="mo_apps_posts_options"  name="mo_share_options_home_page_position" value="before" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?> <?php checked( get_option('mo_share_options_home_page_position') == 'before' );?>>
				Before content
				<br/>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="mo_apps_posts_options"  name="mo_share_options_home_page_position" value="after" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?> <?php checked( get_option('mo_share_options_home_page_position') == 'after' );?>>
				After content
				<br/>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="mo_apps_posts_options"  name="mo_share_options_home_page_position" value="both" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?> <?php checked( get_option('mo_share_options_home_page_position') == 'both' );?>>
				Both before and after content
			</td>
		</tr>
		<tr>
			<td>
				<input type="checkbox" id="mo_apps_posts"  name="mo_share_options_post" value="1" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?> <?php checked( get_option('mo_share_options_enable_post') == '1' );?>>
				Blog Post
				<br/>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="mo_apps_posts_options"  name="mo_share_options_enable_post_position" value="before" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?> <?php checked( get_option('mo_share_options_enable_post_position') == 'before' );?>>
				Before content
				<br/>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="mo_apps_posts_options"  name="mo_share_options_enable_post_position" value="after" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?> <?php checked( get_option('mo_share_options_enable_post_position') == 'after' );?>>
				After content
				<br/>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="mo_apps_posts_options"  name="mo_share_options_enable_post_position" value="both" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?> <?php checked( get_option('mo_share_options_enable_post_position') == 'both' );?>>
				Both before and after content
			</td>		
		</tr>
		<tr>
			<td>
				<input type="checkbox" id="mo_apps_static_page"  name="mo_share_options_static_pages"  value="1" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?> <?php checked( get_option('mo_share_options_enable_static_pages') == 1 );?>>
				Static Pages
				<br/>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="mo_apps_posts_options"  name="mo_share_options_static_pages_position" value="before" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?> <?php checked( get_option('mo_share_options_static_pages_position') == 'before' );?>>
				Before content
				<br/>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="mo_apps_posts_options"  name="mo_share_options_static_pages_position" value="after" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?> <?php checked( get_option('mo_share_options_static_pages_position') == 'after' );?>>
				After content
				<br/>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="mo_apps_posts_options"  name="mo_share_options_static_pages_position" value="both" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?> <?php checked( get_option('mo_share_options_static_pages_position') == 'both' );?>>
				Both before and after content
			</td>
		</tr>
		<tr>
			<td>
				<input type="checkbox" id="mo_apps_wc_sp_page_top"  name="mo_share_options_wc_sp_summary_top"  value="1" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?> <?php checked( get_option('mo_share_options_wc_sp_summary_top') == 1 );?>>
				WooCommerce Individual Product Page(Top)
			</td>
		</tr>
		<tr>
			<td>
				<input type="checkbox" id="mo_apps_wc_sp_page"  name="mo_share_options_wc_sp_summary"  value="1" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?> <?php checked( get_option('mo_share_options_wc_sp_summary') == 1 );?>>
				WooCommerce Individual Product Page
			</td>
		</tr>
		<tr>
			<td>
				<input type="checkbox" id="mo_apps_bb_forum"  name="mo_share_options_bb_forum"  value="1" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?> <?php checked( get_option('mo_share_options_bb_forum') == 1 );?>>
				BBPress Forums Page
				<br/>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="mo_apps_posts_options"  name="mo_share_options_bb_forum_position" value="before" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?> <?php checked( get_option('mo_share_options_bb_forum_position') == 'before' );?>>
				Before content
				<br/>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="mo_apps_posts_options"  name="mo_share_options_bb_forum_position" value="after" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?> <?php checked( get_option('mo_share_options_bb_forum_position') == 'after' );?>>
				After content
				<br/>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="mo_apps_posts_options"  name="mo_share_options_bb_forum_position" value="both" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?> <?php checked( get_option('mo_share_options_bb_forum_position') == 'both' );?>>
				Both before and after content
			</td>
		</tr>
		<tr>
			<td>
				<input type="checkbox" id="mo_apps_bb_topic"  name="mo_share_options_bb_topic"  value="1" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?> <?php checked( get_option('mo_share_options_bb_topic') == 1 );?>>
				BBPress Topic Page
				<br/>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="mo_apps_posts_options"  name="mo_share_options_bb_topic_position" value="before" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?> <?php checked( get_option('mo_share_options_bb_topic_position') == 'before' );?>>
				Before content
				<br/>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="mo_apps_posts_options"  name="mo_share_options_bb_topic_position" value="after" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?> <?php checked( get_option('mo_share_options_bb_topic_position') == 'after' );?>>
				After content
				<br/>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="mo_apps_posts_options"  name="mo_share_options_bb_topic_position" value="both" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?> <?php checked( get_option('mo_share_options_bb_topic_position') == 'both' );?>>
				Both before and after content
			</td>
		</tr>
		<tr>
			<td>
				<input type="checkbox" id="mo_apps_bb_reply"  name="mo_share_options_bb_reply"  value="1" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?> <?php checked( get_option('mo_share_options_bb_reply') == 1 );?>>
				BBPress Reply Page
				<br/>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="mo_apps_posts_options"  name="mo_share_options_bb_reply_position" value="before" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?> <?php checked( get_option('mo_share_options_bb_reply_position') == 'before' );?>>
				Before content
				<br/>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="mo_apps_posts_options"  name="mo_share_options_bb_reply_position" value="after" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?> <?php checked( get_option('mo_share_options_bb_reply_position') == 'after' );?>>
				After content
				<br/>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="mo_apps_posts_options"  name="mo_share_options_bb_reply_position" value="both" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?> <?php checked( get_option('mo_share_options_bb_reply_position') == 'both' );?>>
				Both before and after content
			</td>
		</tr>
				<tr>
			<td>
				<br/>
				<strong>NOTE:</strong>  The icons in above pages will be placed horizontally. For vertical icons, add <b>miniOrange Sharing - Vertical</b> widget from Appearance->Widgets.
			</td>
		</tr>
		<tr>
			<td>
			<br>
			<hr>
			<h3>Floating Vertical Social Share</h3>
			<p>Floating vertical share icons can be added in two ways.</p>
			<ul>
				<li><b>Widget</b>: Go to Appearance > Widgets. There are a few options which can be set like Alignment, left/right offset, top offset and space between icons</li>
				<li><b>Shortcode</b>: Add [miniorange_social_sharing_vertical] to your page or post to add vertical icons. Further details are available on >Shortcode tab</li>
			</ul>
			</td>
		</tr>
		<tr>
			<td>
				<input type="checkbox" id="mo_share_vertical_hide_mobile"  name="mo_share_vertical_hide_mobile"  value="1" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?> <?php checked( get_option('mo_share_vertical_hide_mobile') == 1 );?>>
				Hide Floating Vertical Share icons on mobile
			</td>
		</tr>
		<tr>
									
			<td>
				<br />
				<input type="submit" name="submit" value="Save" style="width:100px;"
					class="button button-primary button-large" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?>/>
			</td>
		</tr>

		<tr>
			<td colspan="2">
				<hr>
				<p>
					<h3>Add Sharing Icons</h3>
					You can add sharing icons in the following areas from <strong>Display Options</strong>. For other areas(widget areas) and vertical floating widget, use Sharing Widgets.
				<ol>
					<li>Home Page: This option places sharing icons in the homepage.</li>
					<li>Blog Post: This option places sharing icons in individual post pages.</li>
					<li>Static Pages: This option places sharing icons in all non-post pages.</li>
				</ol>
					<h3>Add Sharing Icons as Widget</h3>

				<ol>
					<li>Go to Appearance->Widgets. Among the available widgets you will find <b>miniOrange Sharing - Vertical</b> and <b>miniOrange Sharing - Horizontal</b>.</li>
					<li>Drag the one you want to a widget area. You can edit Vertical widget position.</li>
					<li>Now go to your site. You will see the icons for apps which you enabled for sharing.</li>
				</ol>
				</p>
			</td>
		</tr>					
    </table>		
	</div>

</form>
<script>
jQuery(function() {
				jQuery('#tab1').removeClass("nav-tab-active");
				jQuery('#tab2').addClass("nav-tab-active");
				
		});
</script>
</td>
		<td style="vertical-align:top;padding-left:1%;">
			<?php echo miniorange_openid_support(); ?>
		</td>
<?php
}
function mo_openid_shortcode_info(){
?>
<td style="vertical-align:top;width:65%;">
	<div class="mo_openid_table_layout">
	
	<?php if(!mo_openid_is_customer_registered()) { ?>
		<div style="display:block;margin-top:10px;color:red;background-color:rgba(251, 232, 0, 0.15);padding:5px;border:solid 1px rgba(255, 0, 9, 0.36);">
		Please <a href="<?php echo add_query_arg( array('tab' => 'register'), $_SERVER['REQUEST_URI'] ); ?>">Register or Login with miniOrange</a> to enable Social Login and Social Sharing. miniOrange takes care of creating applications for you so that you don't have to worry about creating applications in each social network.
		</div>
	<?php } ?>
		
	<table>
		<tr>
			<td colspan="2">
				<h3>Shortcode</h3>
				<b>If you are using Social login, Social Sharing by miniOrange plugin,  follow the steps mentioned below to enable social login/social sharing in the content of individual page/post/frontend login form.</b>
				<p>If any section is not opening, press CTRL + F5 to clear cache.<p>
					
			</td>
			
		</tr>
		
		<tr>
			<td>
				<h3><a id="openid_login_shortcode_title"  aria-expanded="false" >Social Login Shortcode</a></h3>
				
				<div hidden="" id="openid_login_shortcode" style="font-size:13px !important">
				Use social login Shortcode in the content of required page/post where you want to display Social Login Icons.<br>
				<b>Example:</b> <code>[miniorange_social_login]</code>
			
				<h4 style="margin-bottom:0 !important">For Icons</h4>
				You can use  different attribute to customize social login icons. All attributes are optional.<br>
				<b>Example:</b> <code>[miniorange_social_login  shape="square" theme="default" space="4" size="35"]</code><br>
		
				<h4 style="margin-bottom:0 !important">For Long-Buttons</h4>
				You can use different attribute to customize social login buttons. All attributes are optional.<br>
				<b>Example:</b> <code>[miniorange_social_login  shape="longbuttonwithtext" theme="default" space="4" width="300" height="50"]</code>
				<br>
				
				<h4 style="margin-bottom:0 !important">Available values for attributes</h4>
				<b>shape</b>: round, roundededges, square, longbuttonwithtext<br>
				<b>theme</b>: default, custombackground<br>
				<b>size</b>: Any value between 20 to 100<br> 
				<b>space</b>: Any value between 0 to 100<br>
				<b>width</b>: Any value between 200 to 1000<br>
				<b>height</b>: Any value between 35 to 50<br></div>
				<hr>
			</td>
		</tr>
		
		<tr>
			<td>
				<h3><a   id="openid_sharing_shortcode_title"  >Social Sharing Shortcode</a></h3>
				<div hidden="" id="openid_sharing_shortcode" style="font-size:13px !important">
				<b>Horizontal</b> --> <code>[miniorange_social_sharing]</code><br>
				<b>Vertical</b> --> <code>[miniorange_social_sharing_vertical]</code>
				<!--Use [miniorange_social_sharing] Shortcode in the content of required page/post where you want to display horizontal Social Sharing Icons. Use [miniorange_social_sharing_vertical] shortcode for vertical Social Sharing Icons.--><br>
				
			
				<h4>For Sharing Icons</h4>
				You can use  different attribute to customize social sharing icons. All attributes are optional.<br>
				<b>Example:</b> <code>[miniorange_social_sharing  shape="square" theme="default" space="4" size="30" url="http://miniorange.com"]</code>
				<br>
				
				<h4 style="margin-bottom:0 !important">Common attributes - horizontal and vertical</h4>
				<b>shape</b>: round, roundededges, square<br>
				<b>theme</b>: default, custombackground, nobackground<br>
				<b>size</b>: Any value between 20 to 100<br> 
				<b>space</b>: Any value between 0 to 50<br>
				<b>url</b>: Enter custom URL for sharing<br>
				<br>
				<b>Vertical attributes</b><br>
				<b>alignment</b>: left,right<br>
				<b>topoffset</b>: Any value(height from top) between 0 to 1000<br> 
				<b>rightoffset(Applicable if alignment is right)</b>: Any value between 0 to 200<br>
				<b>leftoffset(Applicable if alignment is left)</b>: Any value between 0 to 200<br>
				</div>
				<hr>
			</td>
		</tr>
								
		
		<tr>
			<td>
				<h3><a id="openid_shortcode_inphp_title">Shortcode in php file</a></h3>
				<div hidden="" id = "openid_shortcode_inphp" style="font-size:13px !important">
				You can use shortcode in PHP file as following: &nbsp;&nbsp;
				&nbsp;
				<code>&lt;&#63;php echo do_shortcode(‘SHORTCODE’) /&#63;&gt;</code>
				<br>
				Replace SHORTCODE in above code with the required shortcode like [miniorange_social_login theme="default"], so the final code looks like following :
				<br> 
				<code>&lt;&#63;php echo do_shortcode('[miniorange_social_login theme="default"]') &#63;&gt;</code></div>
				<hr>
				
			</td>
		</tr>
			
	</table>
	</div>
	</td>
	<td style="vertical-align:top;padding-left:1%;">
		<?php echo miniorange_openid_support(); ?>
	</td>
<?php	
}

function mo_openid_pricing_info(){ ?>
	<td style="vertical-align:top;width:100%;">
		<div class="mo_openid_table_layout">
			<table class="mo_openid_pricing_table">
		<h2>Licensing Plans For Social Login
		<span style="float:right">
			<input type="button" name="check_plan" id="check_plan" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?> class="button button-primary button-large" value="Check License" onclick="checkLicense();"/>
			<input type="button" name="ok_btn" id="ok_btn" class="button button-primary button-large" value="OK, Got It" onclick="window.location.href='admin.php?page=mo_openid_settings&tab=login'" />
		</span>
		</h2><hr>
		<tr>
			<?php if(!mo_openid_is_customer_valid()) { ?>
			<td><div class="mo_openid_thumbnail mo_openid_pricing_free_tab" >
				<h3 class="mo_openid_pricing_header">Free</h3>
				<h4 class="mo_openid_pricing_sub_header">(You are automatically on this plan)<br/><br/></h4>
				<hr>
				<p class="mo_openid_pricing_text">$0 - One Time Payment<br/><br/><br/><br/></p>
				<hr>
				<p class="mo_openid_pricing_text">Social Sharing (Free Forever)<br/>
					Social Comments (Free Forever)<br/>
					Social Login
					(Free for 30 days)<br/></p>
					<p><br/><br/></p>
					<p><br/></p><br/></p>
				<hr/>
				<p class="mo_openid_pricing_text"><br/><br/><br/></p>
				<hr>
				<p class="mo_openid_pricing_text">Basic Support by Email<br/><br/></p>
			</div></td>
			<?php } ?>
			<td><div class="mo_openid_thumbnail mo_openid_pricing_free_tab" <?php if(mo_openid_is_customer_valid()) { ?> style="width:365px" <?php } ?>>
				<h3 class="mo_openid_pricing_header">Do It Yourself</h3>
				<?php if(!mo_openid_is_customer_valid()) { ?>
				<h4 class="mo_openid_pricing_sub_header" style="padding-bottom:8px !important;"><a class="button button-primary button-large"
				 onclick="upgradeform('wp_social_login_basic_plan')" >Upgrade Now</a></h4>
				<?php } else { ?>
				<h4 class="mo_openid_pricing_sub_header" style="padding-bottom:8px !important;"><a class="button button-primary button-large"
				 onclick="upgradeform('social_login_recharge_plan')" >Recharge Now</a></h4>
				<?php } ?>
				<hr>
				<p class="mo_openid_pricing_text">$9 - One Time Payment<br/>+<br/>$0 - For First 10,000 Logins**<br/><br/>
					<?php if(mo_openid_is_customer_valid()) { ?>
						Recharge Plans<br/>
						<select class="mo_openid_license_select_option" <?php if(mo_openid_is_customer_valid()) { ?> style="padding-left:24% !important" <?php } ?>>
						    <option>&nbsp;&nbsp;&nbsp;&nbsp;$9 for 10k logins</option>
						    <option>&nbsp;&nbsp;&nbsp;$29 for 50k logins</option>
						    <option>&nbsp;&nbsp;$69 for 100k logins</option>
						    <option>&nbsp;$149 for 500k logins</option>
						    <option>$249 for 1million logins</option>
						</select>
					<?php } ?>
				</p>
				<hr>
				<p class="mo_openid_pricing_text">
					Social Sharing<br/>
					Social Comments<br/>
					Social Login</p>
					<p><br/><br/><br/></p>
					<p><br/><br/></p>
				<hr/>
				<p class="mo_openid_pricing_text">Get access to user data like Name, Email, Username, Display Picture<br/><br/></p>
				<hr>
				<p class="mo_openid_pricing_text">Basic Support by Email<br/><br/></p>
			</div></td>
			<td><div class="mo_openid_thumbnail mo_openid_pricing_paid_tab" <?php if(mo_openid_is_customer_valid()) { ?> style="width:365px" <?php } ?>>
				<h3 class="mo_openid_pricing_header">Best Value</h3>		
				<?php if(!mo_openid_is_customer_valid() || (mo_openid_is_customer_valid() && mo_openid_get_customer_plan('Do It Yourself'))) { ?>
				<h4 class="mo_openid_pricing_sub_header" style="padding-bottom:8px !important;"><a class="button button-primary button-large"
				onclick="upgradeform('wp_social_login_best_value_basic_plan')" >Upgrade Now</a></h4>
				<?php } else { ?>
				<h4 class="mo_openid_pricing_sub_header" style="padding-bottom:8px !important;"><a class="button button-primary button-large"
				onclick="upgradeform('social_login_recharge_plan')" >Recharge Now</a></h4>
				<?php } ?>
				<hr>
				<p class="mo_openid_pricing_text">$19 - One Time Payment<br/>+<br/>$0 - For First 10,000 Logins**<br/><br/>
					<?php if(mo_openid_is_customer_valid()) { ?>
						Recharge Plans<br/>
						<select class="mo_openid_license_select_option" <?php if(mo_openid_is_customer_valid()) { ?> style="padding-left:24% !important" <?php } ?>>
						    <option>&nbsp;&nbsp;&nbsp;&nbsp;$9 for 10k logins</option>
						    <option>&nbsp;&nbsp;&nbsp;$29 for 50k logins</option>
						    <option>&nbsp;&nbsp;$69 for 100k logins</option>
						    <option>&nbsp;$149 for 500k logins</option>
						    <option>$249 for 1million logins</option>
						</select>
					<?php } ?>
				<hr>
				<p class="mo_openid_pricing_text">
					Social Sharing<br/>
					Social Comments<br/>
					Social Login<br/>
					Extended Profile Data<br/>
					Social Analytics Dashboard Access<br/>
					Custom Apps***<br/></p>
					<p><br/><br/></p>
				<hr/>
				<p class="mo_openid_pricing_text">Get access to user data like Name, Email, Username, Display Picture and <a target="_blank" href="http://miniorange.com/social-data-from-social-sites" style="color:pink">*Extended Profile Data</a></p>
				<hr>
				<p class="mo_openid_pricing_text">Basic Support by Email<br/><br/></p>
			</div></td>
			<td><div class="mo_openid_thumbnail mo_openid_pricing_free_tab" <?php if(mo_openid_is_customer_valid()) { ?> style="width:365px" <?php } ?>>
				<h3 class="mo_openid_pricing_header">Premium</h3>
				<?php if(!mo_openid_is_customer_valid() || (mo_openid_is_customer_valid() && !mo_openid_get_customer_plan('Premium'))) { ?>
				<h4 class="mo_openid_pricing_sub_header" style="padding-bottom:8px !important;"><a class="button button-primary button-large"
				onclick="upgradeform('wp_social_login_premium_plan')" >Upgrade Now</a></h4>
				<?php } else { ?>
				<h4 class="mo_openid_pricing_sub_header" style="padding-bottom:8px !important;"><a class="button button-primary button-large"
				onclick="upgradeform('social_login_recharge_plan')" >Recharge Now</a></h4>
				<?php } ?>
				<hr>
				<p class="mo_openid_pricing_text">$29 - One Time Payment<br/>+<br/>$0 - For First 10,000 Logins**<br/><br/>
					<?php if(mo_openid_is_customer_valid()) { ?>
						Recharge Plans<br/>
						<select class="mo_openid_license_select_option" <?php if(mo_openid_is_customer_valid()) { ?> style="padding-left:24% !important" <?php } ?>>
						    <option>&nbsp;&nbsp;&nbsp;&nbsp;$9 for 10k logins</option>
						    <option>&nbsp;&nbsp;&nbsp;$29 for 50k logins</option>
						    <option>&nbsp;&nbsp;$69 for 100k logins</option>
						    <option>&nbsp;$149 for 500k logins</option>
						    <option>$249 for 1million logins</option>
						</select>
					<?php } ?>
				<hr>
				<p class="mo_openid_pricing_text">
					Social Sharing<br/>
					Social Comments<br/>
					Social Login<br/>
					Extended Profile Data<br/>
					Social Analytics Dashboard Access<br/>
					Custom Apps***<br/>
					Custom Integration<br/><br/>
				</p>
				<hr/>
				<p class="mo_openid_pricing_text">Get access to user data like Name, Email, Username, Display Picture  and <a target="_blank" href="http://miniorange.com/social-data-from-social-sites" style="color:pink">*Extended Profile Data</a></p>
				<hr>
				<p class="mo_openid_pricing_text">Premium Support<br/><br/></p>
			</div></td>
		</td>
		</tr>
		
		</table>
		<form style="display:none;" id="loginform" action="<?php echo get_option( 'mo_openid_host_name').'/moas/login'; ?>" 
		target="_blank" method="post">
		<input type="email" name="username" value="<?php echo get_option('mo_openid_admin_email'); ?>" />
		<input type="text" name="redirectUrl" value="<?php echo get_option( 'mo_openid_host_name').'/moas/initializepayment'; ?>" />
		<input type="text" name="requestOrigin" id="requestOrigin"  />
		</form>
		<form method="post" id="checkLicenseForm">
			<input type="hidden" name="option" value="mo_openid_check_license">
		</form>
		<script>
			function upgradeform(planType){
				jQuery('#requestOrigin').val(planType);
				jQuery('#loginform').submit();
			}
			function checkLicense(){
				jQuery("#checkLicenseForm").submit();
			}
		</script>
		<p><span style="color:rgba(255, 0, 0, 0.76);font-weight:bold;">* Free for 30 days</span> - The plugin uses miniOrange service for Social Login. This keeps the plugin light and delegates login to miniOrange servers thereby reducing the load on your website.</p>
		<p><span style="color:#da7587;font-weight:bold;">* Extended Profile Data</span> - Extended profile data feature requires additional configuration. You need to have your own social media app and permissions from social media providers to collect extended user data.</p>
		<p>** Recharge Plans for logins available in all paid plans. Refer to <a href="<?php echo add_query_arg( array('tab' => 'help'), $_SERVER['REQUEST_URI'] ); ?>">Help &amp; Troubleshooting</a> tab for more details.</p>
		<p>*** Configuring applications for Social Media is cumbersome due to which miniOrange takes care of configuring these apps. If you still wish you use your own applications for Social Login apps, custom apps can be configured for each Social Media in Best Value and Premium plans.</p>

		<h3>Steps to upgrade to premium plugin -</h3>
		<p>1. You will be redirected to miniOrange Login Console. Enter your password with which you created an account with us. After that you will be redirected to payment page.</p>
		<p>2. Enter you card details and complete the payment. On successful payment completion, you will see your payment listed in the Payment History.</p>
		<p>3. Coming back to the plugin, on License Plans page click on <i>Check License</i> on top-right of the listed payment plans.</p>
		<h3>Refund Policy -</h3>
		<p><b>At miniOrange, we want to ensure you are 100% happy with your purchase. If the premium plugin you purchased is not working as advertised and you've attempted to resolve any issues with our support team, which couldn't get resolved then we will refund the whole amount within 10 days of the purchase. Please email us at <a href="mailto:info@miniorange.com"><i>info@miniorange.com</i></a> for any queries regarding the return policy.</b></p>
		<b>Not applicable for -</b>
		<ol>
			<li>Returns that are because of features that are not advertised.</li>
			<li>Returns beyond 10 days.</li>
			<li>Returns for Do It Yourself plan where you don't know how to do it yourself.</li>
		</ol>
		<br>
		</div>
	</td>
<?php 
}

function mo_openid_troubleshoot_info(){ ?>
<td style="vertical-align:top;width:65%;">
	<div class="mo_openid_table_layout">
	
	<?php if(!mo_openid_is_customer_registered()) { ?>
		<div style="display:block;margin-top:10px;color:red;background-color:rgba(251, 232, 0, 0.15);padding:5px;border:solid 1px rgba(255, 0, 9, 0.36);">
		Please <a href="<?php echo add_query_arg( array('tab' => 'register'), $_SERVER['REQUEST_URI'] ); ?>">Register or Login with miniOrange</a> to enable Social Login and Social Sharing. miniOrange takes care of creating applications for you so that you don't have to worry about creating applications in each social network.
		</div>
	<?php } ?>
				<table width="100%">
		<tbody>

		<tr><td>
			<p>If any section is not opening, press CTRL + F5 to clear cache.<p>
			<h3><a  id="openid_question_plugin" class="mo_openid_title_panel" >Site Issue</a></h3>
			<div class="mo_openid_help_desc" hidden="" id="openid_question_plugin_desc">
				<h4><a  id="openid_question14">I installed the plugin and my website stopped working. How can I recover my site?</a></h4>
				<div  id="openid_question14_desc">
					There must have been a server error on your website. To get your website back online:<br/>
					<ol>
						<li>Open FTP access and look for plugins folder under wp-content.</li>
						<li>Change the extension folder name miniorange-login-openid to miniorange-login-openid1</li>
						<li>Check your website. It must have started working.</li>
						<li>Change the folder name back to miniorange-login-openid.</li>
					</ol>
				</div>
				For any further queries, please submit a query on right hand side in our <b>Support Section</b>.
			</div>
			<hr>
		</td></tr>

		<tr><td>				
			<h3><a  id="openid_question_curl" class="mo_openid_title_panel" >cURL</a></h3>
			<div class="mo_openid_help_desc" hidden="" id="openid_question_curl_desc">
			
			<h4><a  id="openid_question1"  >How to enable PHP cURL extension? (Pre-requisite)</a></h4>
			<div  id="openid_question1_desc">
			cURL is enabled by default but in case you have disabled it, follow the steps to enable it
			<ol>
				<li>Open php.ini(it's usually in /etc/ or in php folder on the server).</li>
				<li>Search for extension=php_curl.dll. Uncomment it by removing the semi-colon( ; ) in front of it.</li>
				<li>Restart the Apache Server.</li>
			</ol>
			For any further queries, please submit a query on right hand side in our <b>Support Section</b>.
			
			</div>
				<hr>

			<h4><a  id="openid_question9"  >I am getting error - curl_setopt(): CURLOPT_FOLLOWLOCATION cannot be activated when an open_basedir is set</a></h4>
			<div   id="openid_question9_desc">
				Just setsafe_mode = Off in your php.ini file (it's usually in /etc/ on the server). If that's already off, then look around for the open_basedir in the php.ini file, and change it to open_basedir = .
			</div>
						
			
			</div>
		<hr>
		</td></tr>
		
		 <tr><td>
				<h3><a  id="openid_question_otp" class="mo_openid_title_panel" >OTP and Forgot Password</a></h3>
				  <div class="mo_openid_help_desc" hidden="" id="openid_question_otp_desc">
					<h4><a  id="openid_question7"  >I did not recieve OTP. What should I do?</a></h4>
					<div  id="openid_question7_desc">
						The OTP is sent as an email to your email address with which you have registered with miniOrange. If you can't see the email from miniOrange in your mails, please make sure to check your SPAM folder. <br/><br/>If you don't see an email even in SPAM folder, please verify your account using your mobile number. You will get an OTP on your mobile number which you need to enter on the page. If none of the above works, please contact us using the Support form on the right.
					</div>
					<hr>
		
					<h4><a  id="openid_question8"  >After entering OTP, I get Invalid OTP. What should I do?</a></h4>
					<div  id="openid_question8_desc">
						Use the <b>Resend OTP</b> option to get an additional OTP. Plese make sure you did not enter the first OTP you recieved if you selected <b>Resend OTP</b> option to get an additional OTP. Enter the latest OTP since the previous ones expire once you click on Resend OTP. <br/><br/>If OTP sent on your email address are not working, please verify your account using your mobile number. You will get an OTP on your mobile number which you need to enter on the page. If none of the above works, please contact us using the Support form on the right.
					</div>
					<hr>
		
					<h4><a  id="openid_question5" >I forgot the password of my miniOrange account. How can I reset it?</a></h4>
					<div  id="openid_question5_desc">
						There are two cases according to the page you see -<br><br/>
						1. <b>Login with miniOrange</b> screen: You should click on <b>forgot password</b> link. You will get your new password on your email address which you have registered with miniOrange . Now you can login with the new password.<br><br/>
						2. <b>Register with miniOrange</b> screen: Enter your email ID and any random password in <b>password</b> and <b>confirm password</b> input box. This will redirect you to <b>Login with miniOrange</b> screen. Now follow first step.
					</div>
					
				</div>
				<hr>
		</td></tr>
		
		
				<tr><td>
					<h3><a  id="openid_question_login" class="mo_openid_title_panel" >Social Login</a></h3>
					<div class="mo_openid_help_desc" hidden="" id="openid_question_login_desc">
					<h4><a  id="openid_question2"  >How to add login icons to frontend login page?</a></h4>
					<div   id="openid_question2_desc">
					You can add social login icons to frontend login page using our shortcode [miniorange_social_login]. Refer to 'Shortcode' tab to add customizations to Shortcode.
						
					
					</div>
					<hr>
		
					<h4><a  id="openid_question4"  >How can I put social login icons on a page without using widgets?</a></h4>
					<div  id="openid_question4_desc">
					You can add social login icons to any page or custom login page using 'social login shortcode' [miniorange_social_login]. Refer to 'Shortcode' tab to add customizations to Shortcode.
					</div>
					<hr>
		
					<h4><a  id="openid_question12" >Social Login icons are not added to login/registration form.</a></h3>
					<div  id="openid_question12_desc">
					Your login/registration form may not be wordpress's default login/registration form. In this case you can add social login icons to custom login/registration form using 'social login shortcode' [miniorange_social_login]. Refer to 'Shortcode' tab to add customizations to Shortcode.  
					</div>
					<hr>
					<h4><a  id="openid_question3"  >How can I redirect to my blog page after login?</a></h4>
					<div  id="openid_question3_desc">
					You can select one of the options from <b>Redirect URL after login</b> of <b>Display Option</b> section under <b>Social Login</b> tab. <br>
					1. Same page where user logged in <br>
					2. Homepage <br>
					3. Account Dsahboard <br>
					4. Custom URL - Example: https://www.example.com <br>
					</div>

					<hr>
					<h4><a id="openid_question14">Can I configure my own apps for Facebook, Google+, Twitter etc.?</a></h4>
					<div id="openid_question14_desc">
					Yes, it is possible to configure your own app. That is available in Best Value and Premium plans (Custom apps).<br><br>
					If you have already purchased one of the above plans, please contact us using the Support form on the right and we will help you set it up.<br> 
					</div>

					<hr>
					<h4><a  id="openid_question11"  >After logout I am redirected to blank page</a></h4>
					<div  id="openid_question11_desc">
					Your theme and Social Login plugin may conflict during logout. To resolve it you need to uncheck <b>Enable Logout Redirection</b> checkbox under <b>Display Option</b> of <b>Social Login</b> tab. 
					</div>

				</div>
					<hr>
		</td></tr>
		<tr><td>
			<h3><a  id="openid_question_sharing" class="mo_openid_title_panel" >Social Sharing</a></h3>
				<div class="mo_openid_help_desc" hidden="" id="openid_question_sharing_desc">
				<h4><a  id="openid_question6"  >Is it possible to show sharing icons below the post content?</a></h4>
				<div  id="openid_question6_desc">
					You can put social sharing icons before the content, after the content or both before and after the content. Go to <b>Sharing tab</b> , check <b>Blog post</b> checkbox and select one of three(before, after, both) options available. Save settings.
				</div>
				<hr>
				
				<h4><a  id="openid_question10" >Why is sharing with some applications not working?</a></h4>
				<div  id="openid_question10_desc">
					This issue arises if your website is not publicly hosted. Facebook, for example looks for the URL to generate its preview for sharing. That does not work on localhost or any privately hosted URL.
				</div>
				<hr>

				<h4><a  id="openid_question13" >Facebook sharing is showing the wrong image. How do I change the image?</a></h4>
				<div  id="openid_question13_desc">
					The image is selected by Facebook and it is a part of Facebook sharing feature. We provide Facebook with webpage URL. It generates the entire preview of webpage using that URL.<br/><br/>
					To set an image for the page, set it as a meta tag in <head> of your webpage.<br/>
					<b>< meta property="og:image" content="http://example.com/image.jpg" ></b><br/><br/>
					You can further debug the issue with Facebook's tool - <a href="https://developers.facebook.com/tools/debug/og/object">https://developers.facebook.com/tools/debug/og/object</a>
					<br/><br/>
					If the problem still persists, please contact us using the Support form on the right.
				</div>
				<hr>
				
				<h4><a  id="openid_question18" >Email share is not working. Why?</a></h4>
				<div  id="openid_question18_desc">
					Email share in the plugin is enabled through <b>mailto</b>. mailto is generally configured through desktop or browser so if it is not working, mailto is not setup or improperly configured.<br><br>
					To set it up properly, search for "mailto settings " followed by your Operating System's name where you have your browser installed.
				</div>
				</div>
				<hr>
		</td></tr>
		
					
		
		
		<tr><td>
			<h3><a  id="openid_question_payment" class="mo_openid_title_panel">Payment</a></h3>
				<div class="mo_openid_help_desc" hidden="" id="openid_question_payment_desc">
					<h4><a id="openid_question16">What is a login? What is the validity of 10,000 logins?</a></h4>
					<div id="openid_question16_desc">
						Simply, when someone clicks on any Social Application button/icon to login or to register, that counts as a login.<br><br>
						10,000 logins are included in all the plans. Logins do not have a validity period and can be used as and when required. When they are used up, Recharge Plans are available to add more logins.
					</div>
					<hr>
					<h4><a id="openid_question15">What are the Recharge Plans for Social Login?</a></h4>
					<div id="openid_question15_desc">
						We have 5 Recharge Plans in Social Login for different volumes.<br><br>
						1. $9 for 10,000 logins<br>
						2. $29 for 50,000 logins<br>
						3. $69 for 100,000 logins<br>
						4. $149 for 500,000 logins<br>
						5. $249 for 1,000,000 logins<br><br>
						This data is also available on the Licensing Plans page after upgrade to any of the premium plans.
					</div>
					<hr>
					<h4><a id="openid_question17">I have some more doubts regarding payment.</a></h4>
					<div id="openid_question17_desc">
						Please contact us using the Support form on the right or mail us at <a href="mailto:info@miniorange.com"><i>info@miniorange.com</i></a>.
					</div>
					<hr>
				</div>
					<hr>
		</td></tr>
		
		
		
		
		</tbody>
		</table>
	</div>
	</td>
		<td style="vertical-align:top;padding-left:1%;">
			<?php echo miniorange_openid_support(); ?>
		</td>
	
<?php	
}

function mo_openid_is_customer_registered() {
			$email 			= get_option('mo_openid_admin_email');
			$customerKey 	= get_option('mo_openid_admin_customer_key');
			if( ! $email || ! $customerKey || ! is_numeric( trim( $customerKey ) ) ) {
				return 0;
			} else {
				return 1;
			}
}

function miniorange_openid_support(){
	global $current_user;
	$current_user = wp_get_current_user();
?>
	<div class="mo_openid_support_layout">

			<h3>Support</h3>
			<p>Need any help? Just send us a query so we can help you.</p>
			<form method="post" action="">
				<input type="hidden" name="option" value="mo_openid_contact_us_query_option" />
				<table class="mo_openid_settings_table">
					<tr>
						<td><input type="email" class="mo_openid_table_contact" required placeholder="Enter your Email" name="mo_openid_contact_us_email" value="<?php echo get_option("mo_openid_admin_email"); ?>"></td>
					</tr>
					<tr>
						<td><input type="tel" id="contact_us_phone" pattern="[\+]\d{11,14}|[\+]\d{1,4}[\s]\d{9,10}" placeholder="Enter your phone number with country code (+1)" class="mo_openid_table_contact" name="mo_openid_contact_us_phone" value="<?php echo get_option('mo_openid_admin_phone');?>"></td>
					</tr>
					<tr>
						<td><textarea class="mo_openid_table_contact" onkeypress="mo_openid_valid_query(this)" onkeyup="mo_openid_valid_query(this)" placeholder="Write your query here" onblur="mo_openid_valid_query(this)" required name="mo_openid_contact_us_query" rows="4" style="resize: vertical;"></textarea></td>
					</tr>
				</table>
				<br>
			<input type="submit" name="submit" value="Submit Query" style="width:110px;" class="button button-primary button-large" />

			</form>
			<p>If you want custom features in the plugin, just drop an email at <a href="mailto:info@miniorange.com">info@miniorange.com</a>.</p>
		</div>
	</div>
	</div>
	</div>
	<script>
		jQuery("#contact_us_phone").intlTelInput();
		function mo_openid_valid_query(f) {
			!(/^[a-zA-Z?,.\(\)\/@ 0-9]*$/).test(f.value) ? f.value = f.value.replace(
					/[^a-zA-Z?,.\(\)\/@ 0-9]/, '') : null;
		}
		
		function moSharingSizeValidate(e){
			var t=parseInt(e.value.trim());t>60?e.value=60:10>t&&(e.value=10)
		}
		function moSharingSpaceValidate(e){
			var t=parseInt(e.value.trim());t>50?e.value=50:0>t&&(e.value=0)
		}
		function moLoginSizeValidate(e){
			var t=parseInt(e.value.trim());t>60?e.value=60:20>t&&(e.value=20)
		}
		function moLoginSpaceValidate(e){
			var t=parseInt(e.value.trim());t>60?e.value=60:0>t&&(e.value=0)
		}
		function moLoginWidthValidate(e){
			var t=parseInt(e.value.trim());t>1000?e.value=1000:140>t&&(e.value=140)
		}
		function moLoginHeightValidate(e){
			var t=parseInt(e.value.trim());t>50?e.value=50:35>t&&(e.value=35)
		}
		
	</script>
<?php
}

function mo_openid_is_customer_valid(){
	$valid = get_option('mo_openid_admin_customer_valid');
	if(isset($valid) && get_option('mo_openid_admin_customer_plan'))
		return $valid;
	else
		return false;
}

function mo_openid_get_customer_plan($customerPlan){
	$plan = get_option('mo_openid_admin_customer_plan');
	$planName = isset($plan) ? base64_decode($plan) : 0;
	if($planName) {
		if(strpos($planName, $customerPlan) !== FALSE)
			return true;
		else
			return false;
	} else
		return false;
}

function mo_openid_is_extension_installed($name) {
	if  (in_array  ($name, get_loaded_extensions())) {
		return true;
	}
	else {
		return false;
	}
}

function mo_openid_is_curl_installed() {
    if (in_array ('curl', get_loaded_extensions())) {
        return 1;
    } else
        return 0;
}
?>