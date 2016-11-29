<script type="text/template" id="formSettings-template">

<form id="formSettingsForm">
	
	<div class="form-group">
		<label for="formTitle"><?php smuzform_translate_e('Title') ?></label>
		<input id="formTitle" type="text" value="<%- title %>" class="form-control" />
	</div>
	<div class="form-group">
		<label for="formDescription"><?php smuzform_translate_e('Description') ?></label>
		<input id="formDescription" type="text" value="<%- description %>" class="form-control" />
	</div>

	<div class="form-inline form-group">

		<div class="form-group form-cont-lang">
			<label for="formLang"><?php smuzform_translate_e('Language') ?></label>
			
			<select id="formLang" class="form-control">
				<option value="en" <% if ( lang === 'en' ) { %> <%- 'selected' %> <% } %> >English</option>
			</select>

		</div>

		<div class="form-group form-cont-placement">
			<label for="formLabelPlacement"><?php smuzform_translate_e('Label Placement') ?></label>
			<select id="formLabelPlacement" class="form-control">
				<option value="top" <% if ( labelPlacement === 'top' ) { %> <%- 'selected' %> <% } %> >Top Aligned</option>
				<option value="left" <% if ( labelPlacement === 'left' ) { %> <%- 'selected' %> <% } %> >Left Aligned</option>
			</select>
		</div>
	
	</div>

	<div class="form-group">

		<label class="radio-inline">
			<input type="radio" name="optConfOption" value="text" <% if ( confirmationOption === 'text' ) { %> <%- 'checked' %> <% } %> /> Show Text
		</label>

		<label class="radio-inline">
			<input type="radio" name="optConfOption" value="redirect" <% if ( confirmationOption === 'redirect' ) { %> <%- 'checked' %> <% } %> /> Redirect to Website
		</label>

		<% if ( confirmationOption === 'text' ) { %>
		<div class="form-group">

			<textarea id="formTextMessage" class="form-control"><%- textMessage %></textarea>

		</div>
		<% } %>

		<% if ( confirmationOption === 'redirect' ) { %>
		<div class="form-group">

			<input id="formRedirectUrl" type="url" class="form-control" value="<%- redirectUrl %>" />

		</div>
		<% } %>

		

	</div>

	<div class="form-group">
		<label for="formSubmitBtnText"><?php smuzform_translate_e('Submit Button Text') ?></label>
		<input type="text" id="formSubmitBtnText" value="<%- submitBtnText %>" class="form-control" />
	</div>

	<div class="form-group checkbox">
		<label>
			<input id="formCaptcha" type="checkbox" class="form-control" value="true" <% if ( captcha ) { %> <%- 'checked' %> <% } %> /> Captcha
			<p class="description"><?php smuzform_translate_e( 'Captcha feature will only work if the captcha extension is installed.' ) ?></p>
		</label>
	</div>

</form>

</script>