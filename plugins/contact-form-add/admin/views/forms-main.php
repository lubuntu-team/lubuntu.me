<style>

.wp_smf_heading{
	font-size: 23px;
    font-weight: 400;
    padding: 9px 15px 4px 0;
    line-height: 29px;
    float:left;
    width: auto;
    height: 0px;
}
.wp_smf_heading2{
	font-size: 23px;
    font-weight: 400;
    padding: 0px 0px 4px 0;
    line-height: 29px;
    margin-left: -120px;
}
#slr_form_preview_box{
	width:620px;
	height: 360px;
	margin-left: 20%;
	background-color: gray;
}
.wp_smf_add_new_button{

	margin-top: 29px;
	margin-left: 0px;
    padding: 4px 8px;
    position: relative;
    top: -3px;
    text-decoration: none;
    border: none;
    -webkit-border-radius: 2px;
    border-radius: 2px;
    background: #e0e0e0;
    text-shadow: none;
    font-weight: 600;
    font-size: 13px;
    color: #0073aa;
    -webkit-transition-property: border,background,color;
    transition-property: border,background,color;
    -webkit-transition-duration: .05s;
    transition-duration: .05s;
    -webkit-transition-timing-function: ease-in-out;
    transition-timing-function: ease-in-out;
}
#wp_smf_form_preview{
	text-align: center;
}
.wp_smf_add_new_button:hover{
	background-color:#0073aa;
	cursor: pointer;
	color:white;
}
.wp_smf_heading_div{
	display: block;
    width: 100%;
    height: 50px;
 



}
#smf_forms_table .header{
	height: 28px;
    position: relative;
    line-height: 28px;
    border-radius: 3px 3px 0 0;
    font: normal normal bold 12px/29px Arial, serif;
    background: #F1F1F1;
}
.wp_smf_delete_img{
	width: 12px;

}
.wp_smf_edit_img{
	width: 24px;

}
.wp_smf_preview_img{
	width: 18px;

}
#smf_forms_table{
	width: 97%;
 
    border-spacing: 0px;
}
table a:link {
	
	font-weight: bold;
	text-decoration:none;
}
table a:visited {
	
	font-weight:bold;
	text-decoration:none;
}
table a:active,
table a:hover {
	
	text-decoration:underline;
}
table {
	font-family:Arial, Helvetica, sans-serif;
	color:#666;
	font-size:12px;
	text-shadow: 1px 1px 0px #fff;
	background:#eaebec;
	margin-top:20px;
	border:#ccc 1px solid;
	width: 90%;
	-moz-border-radius:3px;
	-webkit-border-radius:3px;
	border-radius:3px;

	-moz-box-shadow: 0 1px 2px #d1d1d1;
	-webkit-box-shadow: 0 1px 2px #d1d1d1;
	box-shadow: 0 1px 2px #d1d1d1;
}
table th {
	padding:15px 25px 15px 25px;
	border-top:1px solid #fafafa;
	border-bottom:1px solid #e0e0e0;

	background: #ededed;
	background: -webkit-gradient(linear, left top, left bottom, from(#ededed), to(#ebebeb));
	background: -moz-linear-gradient(top,  #ededed,  #ebebeb);
}
table th:first-child{
	text-align: left;
	padding-left:20px;
}
table tr:first-child th:first-child{
	-moz-border-radius-topleft:3px;
	-webkit-border-top-left-radius:3px;
	border-top-left-radius:3px;
}
table tr:first-child th:last-child{
	-moz-border-radius-topright:3px;
	-webkit-border-top-right-radius:3px;
	border-top-right-radius:3px;
}
table tr{
	text-align: center;
	padding-left:20px;
}
table tr td:first-child{
	text-align: left;
	padding-left:20px;
	border-left: 0;
}
table tr td {
	padding:12px;
	border-top: 1px solid #ffffff;
	border-bottom:1px solid #e0e0e0;
	border-left: 1px solid #e0e0e0;
	
	background: #fafafa;
	background: -webkit-gradient(linear, left top, left bottom, from(#fbfbfb), to(#fafafa));
	background: -moz-linear-gradient(top,  #fbfbfb,  #fafafa);
}
table tr .wp_smf_chkcx_width{
	width:23px;
}
table tr .wp_smf_slides_width{
	width:40px;
}
table tr .wp_smf_options_width{
	width:50px;
}
table tr.even td{
	background: #f6f6f6;
	background: -webkit-gradient(linear, left top, left bottom, from(#f8f8f8), to(#f6f6f6));
	background: -moz-linear-gradient(top,  #f8f8f8,  #f6f6f6);
}
table tr:last-child td{
	border-bottom:0;
}
table tr:last-child td:first-child{
	-moz-border-radius-bottomleft:3px;
	-webkit-border-bottom-left-radius:3px;
	border-bottom-left-radius:3px;
}
table tr:last-child td:last-child{
	-moz-border-radius-bottomright:3px;
	-webkit-border-bottom-right-radius:3px;
	border-bottom-right-radius:3px;
}
table tr:hover td{
	background: #f2f2f2;
	background: -webkit-gradient(linear, left top, left bottom, from(#f2f2f2), to(#f0f0f0));
	background: -moz-linear-gradient(top,  #f2f2f2,  #f0f0f0);	
}

.formtitle_link a strong{
	color: #000;
}

#forms_page_cont *{
	font-family: "Open Sans";
}
</style>
<div id="forms_page_cont">
	<div id="wp_smf_heading_div" class="wp_smf_heading_div">
	<h1 id="wp_smf_heading" class="wp_smf_heading">Forms</h1>
	<input id="wp_smf_add_new_button" class="wp_smf_add_new_button" type="button" value="Add New" onclick="window.location = '<?php echo admin_url('admin.php?page=smuz-forms&new_post=1') ?>'">
	</div>

	<div>
	<hr>
	<table border="0" id="smf_forms_table">
		
		<tr>
			<th>Name</th>
			<th>Fields</th>
			<th>Shortcode</th>
			<th>Created</th>
			<th>Options</th>
		</tr>

		<?php $forms = smuzform_get_forms(); $even_odd = true; ?>
			
		<?php foreach ( $forms as $form  ): $tmpForm = new SmuzForm_Form( $form->ID ) ?>

			<tr class='<?php if ( $even_odd ) echo 'even'; else echo 'odd';  ?>'>
				
				<td><a class="formtitle_link" href="<?php echo admin_url('admin.php?page=smuz-forms&form_id=' . $form->ID ) ?>"><strong><?php echo get_the_title( $form->ID ) ?></strong></a></td>
				
				<td class="wp_smf_slides_width"><?php echo count( $tmpForm->getFields() ) ?></td>
				<td id="wpsmfshortcodecopy">[sform id='<?php echo $form->ID ?>']</td>
				<td><?php echo get_the_date( 'l, F j, Y', $form->ID  ); ?></td>
				<td class="wp_smf_options_width">
				<a id="slr_edit_form" href="#">
					<img alt="Edit" onclick="window.location='<?php echo admin_url('admin.php?page=smuz-forms&form_id=' . $form->ID ) ?>'" class="wp_smf_edit_img" src="<?php echo smuzform_admin_asset( 'img/wp_smf_edit_form_img.png' ) ?>" />
				</a>/ 
				<a id="slr_delete_form" href="#">
					
					<img alt="Delete" class="wp_smf_delete_img" data-id="<?php echo intval($form->ID) ?>" src="<?php echo smuzform_admin_asset( 'img/wp_smf_delete_form_img.png' ) ?>" />
				</a>
				
				</td>
			</tr>

		<?php if ( $even_odd ) $even_odd = false; else $even_odd = true; 
		endforeach; ?>

		<?php if ( ! $forms ) : ?>

			<div id="noSlidesMessage" style="text-align: center">
				<h3>
					No forms. Click the + <a href="<?php echo admin_url('admin.php?page=smuz-forms&new_post=1') ?>">Add New</a> to create your first form.
				</h3>
			</div>

		<?php endif; ?>
	
	</table>
</div>

</div>
<br />
<p class="description">*Enter the shortcode in your post or page content editor to display the form.</p>

<script>
jQuery('.wp_smf_delete_img').click(function() {

		id = jQuery(this).data('id');

		url = '<?php echo admin_url() ?>' + 'admin.php?page=smuz-forms-main&action=delete_form&post_id=' + id;

		if ( confirm( 'Are you sure you want to remove this form ? ' ) ) {

			window.location = url;
		
		}


	});
</script>