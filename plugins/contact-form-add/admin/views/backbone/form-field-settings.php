<script type="text/template" id="fieldSettings-template">

<form id="fieldSettingsForm">

<% if ( type !== 'sectionbreak' && type !== 'linebreak' && type !== 'customText' && type !== 'customHtml' && type !== 'customImage' && type !== 'customLink' && type !== 'pagebreak'  ) { %>
	<div class="form-group">
		<label for="fieldLabel"><?php smuzform_translate_e('Label') ?></label>
		<input id="fieldLabel" type="text" value="<%- label %>" class="form-control" />
	</div>

	<div class="form-inline">

		<div class="form-group field-cont-type">
			<label for="fieldType"><?php smuzform_translate_e('Field Type') ?></label>

			<select id="fieldType" class="form-control" disabled>
				<option value="singletext" <% if ( type === 'singletext' ) { %> <%- 'selected' %> <% } %>>Single Line Text</option>
				<option value="textarea" <% if ( type === 'textarea' ) { %> <%- 'selected' %> <% } %>>Text Area</option>
				<option value="number" <% if ( type === 'number' ) { %> <%- 'selected' %> <% } %>>Number</option>
				<option value="dropdown" <% if ( type === 'dropdown' ) { %> <%- 'selected' %> <% } %>>Dropdown</option>
				<option value="radio" <% if ( type === 'radio' ) { %> <%- 'selected' %> <% } %>>Radio Buttons</option>
				<option value="checkbox" <% if ( type === 'checkbox' ) { %> <%- 'selected' %> <% } %>>Checkboxes</option>
				<option value="fileupload" <% if ( type === 'fileupload' ) { %> <%- 'selected' %> <% } %>>File Upload</option>
				<option value="fileupload" <% if ( type === 'customText' ) { %> <%- 'selected' %> <% } %>>File Upload</option>
			</select>
		</div>

		<div class="form-group field-cont-size">
			<label for=""><?php smuzform_translate_e('Field Size') ?></label>
			<select id="fieldSize" class="form-control">
				<option value="small" <% if ( size === 'small' ) { %> <%- 'selected' %> <% } %> >Small</option>
				<option value="medium" <% if ( size === 'medium' ) { %> <%- 'selected' %> <% } %> >Medium</option>
				<option value="large" <% if ( size === 'large' ) { %> <%- 'selected' %> <% } %> >Large</option>
			</select>
		</div>
	
	</div>

	<br />

	<div class="form-inline">

		<div class="form-group field-cont-options">
			<label class="field-cont-options-label"><?php smuzform_translate_e('Options') ?></label>
			<div class="checkbox">
				<label>
					
					<% if ( required === true ){
						isRequiredChecked = 'checked';
					} else { isRequiredChecked = '' } %>

					<input <%- isRequiredChecked %> type="checkbox" value="true" id="optionRequired" />
					 Required
				</label> 
			</div>

			<% if ( type !== 'checkbox' && type !== 'radio' && type !== 'dropdown' && type !== 'fileupload' && type !== 'address' && type !== 'date' && type !== 'likert' ) { %>
 			<div class="checkbox">
				<label>
					<input type="checkbox" value="true" id="optionNoDuplicate" <% if ( noDuplicates ) { %> <%- 'checked' %> <% } %> />
					 No Duplicate
				</label> 
			</div>
			<% } %>
		</div>

		<div class="form-group field-cont-size">
			<label class="field-cont-options-label"><?php smuzform_translate_e('Label visibility') ?></label>
			<div class="checkbox">
				<% 
					
					var visibleChecked = '',
						 hiddenChecked = '';

					if ( labelVisible === true ){
						 visibleChecked = 'checked';
					} else { hiddenChecked = 'checked' }
				%>
				<label>
					<input <%- visibleChecked %> type="radio" name="fieldOptionRadio" value="visible" id="optionVisible" />
					 Visible
				</label> 
			</div>
			<div class="checkbox">
				<label>
					<input <%- hiddenChecked %> type="radio" name="fieldOptionRadio" value="hidden" id="optionHidden" />
					 Hidden
				</label> 
			</div>
		</div>
	
	</div>

	<br />
	
	<% if ( type !== 'checkbox' && type !== 'radio' && type !== 'dropdown' && type !== 'fileupload' && type !== 'likert' && type !== 'address' && type !== 'name' ) { %>
	<div class="form-group">
		<label for="fieldValue"><?php smuzform_translate_e('Value') ?></label>
		<input id="fieldValue" type="text" value="<%- preValue %>" placeholder="Enter pre-defined value" class="form-control" />
		<% if ( type == 'date' ) { %>
			<small><?php smuzform_translate_e( 'dd/mm/yyyy' ) ?></small>
		<% } %>
	</div>

	<% if ( type !== 'date' ) { %>
	<div class="form-group">
		<label for="fieldPlaceholder"><?php smuzform_translate_e('Placeholder Text') ?></label>
		<input id="fieldPlaceholder" type="text" value="<%- placeholderText %>" placeholder="This is placeholder text" class="form-control" />
	</div>
	<% } %>

	<% } %>

	<% if ( type === 'radio' ) { %>

	<div class="form-group">
		<label><?php smuzform_translate_e('Choices') ?></label>
		
		<% _.each( choices, function( choice, index ) { %>
			
			<div class="form-inline">
				<div class="form-group">
					<input type="radio" name="optChoice" class="optChoiceSelect" data-index="<%- index %>" <% if ( index === selectedChoice ) { %> <%- 'checked' %> <% } %> />
				</div>
				<div class="form-group">
					<input class="fieldOptChoice form-control input-sm" data-index="<%- index %>" type="text" value="<%- choice %>" />
					<a class="optChoiceRemove button" data-index="<%- index %>">X</a>
				</div>
			</div>

		<% }); %>

		<a class="button" id="addNewChoice">+</a>

	</div>

	<% } %>

	<% if ( type === 'checkbox' ) { %>

	<div class="form-group">
		<label><?php smuzform_translate_e('Choices') ?></label>
		
		<% _.each( choices, function( choice, index ) { %>
			
			<div class="form-inline">
				<div class="form-group">
					<input type="radio" name="optChoice" class="optChoiceSelect" data-index="<%- index %>" <% if ( index === selectedChoice ) { %> <%- 'checked' %> <% } %> />
				</div>
				<div class="form-group">
					<input class="fieldOptChoice form-control input-sm" data-index="<%- index %>" type="text" value="<%- choice %>" />
					<a class="optChoiceRemove button" data-index="<%- index %>">X</a>
				</div>
			</div>

		<% }); %>

		<a class="button" id="addNewChoice">+</a>

	</div>

	<% } %>

	<% if ( type === 'dropdown' ) { %>

	<div class="form-group">
		<label><?php smuzform_translate_e('Choices') ?></label>
		
		<% _.each( choices, function( choice, index ) { %>
			
			<div class="form-inline">
				<div class="form-group">
					<input type="radio" name="optChoice" class="optChoiceSelect" data-index="<%- index %>" <% if ( index === selectedChoice ) { %> <%- 'checked' %> <% } %> />
				</div>
				<div class="form-group">
					<input class="fieldOptChoice form-control input-sm" data-index="<%- index %>" type="text" value="<%- choice %>" />
					<a class="optChoiceRemove button" data-index="<%- index %>">X</a>
				</div>
			</div>

		<% }); %>

		<a class="button" id="addNewChoice">+</a>

	</div>

	<% } %>

<% } %>


<% if ( type === 'sectionbreak' ) { %>

	<div class="form-group">
		<label for="fieldSectionTitle"><?php smuzform_translate_e('Section Title') ?></label>
		<input id="fieldSectionTitle" type="text" value="<%- sectionTitle %>" class="form-control" />
	</div>

	<div class="form-group">
		<label for="fieldSectionDescription"><?php smuzform_translate_e('Section Description') ?></label>
		<input id="fieldSectionDescription" type="text" value="<%- sectionDescription %>" class="form-control" />
	</div>

<% } %>

<% if ( type === 'fileupload' ) { %>

	<div class="form-group">
		<label for="fieldFileAllowed"><?php smuzform_translate_e('File Types Allowed') ?></label>
		<input id="fieldFileAllowed" type="text" value="<%- file.allowed %>" class="form-control" />
	</div>

	<div class="form-group">
		<label for="fieldFileMaxSize"><?php smuzform_translate_e('Max Size (MB)') ?></label>
		<input id="fieldFileMaxSize" type="text" value="<%- file.maxSize %>" class="form-control fieldSize-small" />
		<small><?php smuzform_translate_e( 'PHP System Limit is ' ); echo ini_get('upload_max_filesize') ?></small>
	</div>

<% } %>


<% if ( type === 'singletext' || type === 'textarea' || type === 'email' || type === 'number' || type === 'textarea' ) { %>


<div class="panel-group">
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" href="#collapse1">Range</a>
      </h4>
    </div>
    <div id="collapse1" class="panel-collapse collapse">
      <div class="panel-body">
      	<div class="form-group">

		<label for="fieldMinLength"><?php smuzform_translate_e( 'Min Length' ) ?></label>
		<input id="fieldMinLength" type="number" class="form-control" value="<%- rangeMin %>" />
		
		</div>

		<div class="form-group">

			<label for="fieldMaxLength"><?php smuzform_translate_e( 'Max Length' ) ?></label>
			<input id="fieldMaxLength" type="number" class="form-control" value="<%- rangeMax %>" />
		</div>
      </div>

      	<div class="panel-footer">
      	<small>
			<?php smuzform_translate_e( 'Leave blank to disable.' ) ?>
		</small>
		</div>
    </div>
  </div>
</div>
	
	

<% } %>

<% if ( type === 'address' ) { %>

<% } %>

<% if ( type === 'customText' ) { %>

	<div class="form-group">
		<label for="customTextValue"><?php smuzform_translate_e('Text') ?></label>
		<input id="customTextValue" type="text" value="<%- extraData.html %>" class="form-control" />
	</div>

	<div class="form-group">
		<label for="customTextTag"><?php smuzform_translate_e('HTML Tag') ?></label>
		
		<select id="customTextTag" class="form-control">
			<option value="p" <% if ( extraData.tag === 'p' ) { %> <%= 'selected' %> <% } %> >P</option>
			<option value="h1" <% if ( extraData.tag === 'h1' ) { %> <%= 'selected' %> <% } %> >h1</option>
			<option value="h2" <% if ( extraData.tag === 'h2' ) { %> <%= 'selected' %> <% } %> >h2</option>
			<option value="h3" <% if ( extraData.tag === 'h3' ) { %> <%= 'selected' %> <% } %> >h3</option>
			<option value="h4" <% if ( extraData.tag === 'h4' ) { %> <%= 'selected' %> <% } %> >h4</option>
			<option value="h5" <% if ( extraData.tag === 'h5' ) { %> <%= 'selected' %> <% } %> >h5</option>
			<option value="em" <% if ( extraData.tag === 'em' ) { %> <%= 'selected' %> <% } %> >em</option>
		</select>
	</div>

	<div class="form-group">
		<label for="customTextColor"><?php smuzform_translate_e('Color') ?></label>
		<input id="customTextColor" type="color" value="<%- extraData.color %>" class="form-control" />
	</div>

	<div class="form-group">
		<label for="customTextFontSize"><?php smuzform_translate_e('Font size (px)') ?></label>
		<input id="customTextFontSize" type="number" value="<%- extraData.size %>" class="form-control" />
	</div>

	<div class="form-group">
		<div class="checkbox">
			<label> <input type="checkbox" id="customTextBold" <% if ( extraData.bold ) { %> <%= 'checked' %> <% } %> /> <?php smuzform_translate_e('Make Text Bold') ?></label>
		</div>
	</div>

<% } %>


<% if ( type === 'customImage' ) { %>

	<div class="form-group">
		<label for="customImageUrl"><?php smuzform_translate_e('Image Url') ?></label>
		<input id="customImageUrl" type="text" value="<%- extraData.imgUrl %>" class="form-control" />
	</div>

	<div class="form-group">
		<label for="customImageWidth"><?php smuzform_translate_e('Width') ?></label>
		<input id="customImageWidth" type="number" value="<%- extraData.width %>" class="form-control" />
	</div>

	<div class="form-group">
		<label for="customImageHeight"><?php smuzform_translate_e('Height') ?></label>
		<input id="customImageHeight" type="number" value="<%- extraData.height %>" class="form-control" />
	</div>

<% } %>

<% if ( type === 'customHtml' ) { %>

	<div class="form-group">
		<label for="customHtmlSource"><?php smuzform_translate_e('Code') ?></label>
		<textarea rows="10" id="customHtmlSource" class="form-control"><%= extraData.readyHtml %></textarea>
	</div>

	

<% } %>

<% if ( type === 'pagebreak' ) { %>
	<div class="form-group">
		<label for="fieldLabel"><?php smuzform_translate_e('Label') ?></label>
		<input id="fieldLabel" type="text" value="<%- label %>" class="form-control" />
	</div>
<% } %>


<?php include smuzform_admin_view( 'backbone/form-field-settings-rules.php' ) ?>

<?php include smuzform_admin_view( 'backbone/form-field-advanced-settings.php' ) ?>

</form>
</script>