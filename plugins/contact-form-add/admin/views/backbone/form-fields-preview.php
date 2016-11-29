<script type="text/template" id="fieldsView-template">
	
	<% if ( required === true ){
						isRequired = 'required';
					} else { isRequired = '' } %>

	<% if ( type === 'singletext' ) { %>
	<div class="form-group">
		<label for="<%- cssID %>" class="smfieldlabel <%- isRequired %>"><%- label %></label>
		
		<input <%- isRequired %> readonly id="<%- cssID %>" type="text" value="<%- preValue %>" placeholder="<%- placeholderText %>" class="form-control <%- cssClasses %> smformfield fieldSize-<%- size %>" />
		
	</div>
	<% } %>

	<% if ( type === 'textarea' ) { %>
	<div class="form-group">
		<label for="<%- cssID %>" class="smfieldlabel <%- isRequired %>"><%- label %></label>
		
		<textarea rows="<%- textAreaRows %>" cols="<%- textAreaCols %>" readonly id="<%- cssID %>" placeholder="<%- placeholderText %>" class="form-control <%- cssClasses %> smformfield  fieldSize-<%- size %>"><%- preValue %></textarea>
	</div>
	<% } %>

	<% if ( type === 'radio' ) { %>
	<div class="form-group">
		<label for="<%- cssID %>" class="smfieldlabel <%- isRequired %>"><%- label %></label>
		
		<% _.each( choices, function( choice, index ) {

			if ( index === selectedChoice )
				isSelected = 'checked';
			else
				isSelected = ''
		%>
		
		<div class="radio disabled">
			
			<label><input <%- isSelected %> type="radio" disabled value="" /> <%- choice %></label>
		
		</div>
		
		<% }); %>

	</div>
	<% } %>

	<% if ( type === 'checkbox' ) { %>
	<div class="form-group">
		<label for="<%- cssID %>" class="smfieldlabel <%- isRequired %>"><%- label %></label>
		
		<% _.each( choices, function( choice, index ) {

			if ( index === selectedChoice )
				cisSelected = 'checked';
			else
				cisSelected = ''
		%>
		
		<div class="checkbox disabled">
			
			<label><input <%- cisSelected %> type="checkbox" disabled value="" /> <%- choice %></label>
		
		</div>
		
		<% }); %>

	</div>
	<% } %>

	<% if ( type === 'dropdown' ) { %>
	<div class="form-group">
		<label for="<%- cssID %>" class="smfieldlabel <%- isRequired %>"><%- label %></label>
		<select class="form-control fieldSize-<%- size %> smformfield" disabled>
		<% _.each( choices, function( choice, index ) {

			if ( index === selectedChoice )
				sisSelected = 'selected';
			else
				sisSelected = ''
		%>
		
			<option <%- sisSelected %>><%- choice %></option>
		
		
		<% }); %>

		</select>

	</div>
	<% } %>

	<% if ( type === 'linebreak' ) { %>
		<div class="fieldLineBreakView"><hr /></div>
	<% } %>

	<% if ( type === 'sectionbreak' ) { %>
	<legend>

		<h3 class="form-title">
			<%- sectionTitle %>
		</h3>

		<div class="form-description">
			<p>
				<%- sectionDescription %>
			</p>
		</div>

	</legend>
	<% } %>

	<% if ( type === 'number' ) { %>
	<div class="form-group">
		<label for="<%- cssID %>" class="smfieldlabel <%- isRequired %>"><%- label %></label>
		<input <%- isRequired %> readonly id="<%- cssID %>" type="number" value="<%- preValue %>" placeholder="<%- placeholderText %>" class="form-control <%- cssClasses %> smformfield  fieldSize-<%- size %>" />
	</div>
	<% } %>

	<% if ( type === 'fileupload' ) { %>
	<div class="form-group">
		<label for="<%- cssID %>" class="smfieldlabel <%- isRequired %>"><%- label %></label>
		<input <%- isRequired %> disabled id="<%- cssID %>" type="file" value="<%- preValue %>" placeholder="<%- placeholderText %>" class="form-control <%- cssClasses %> smformfield  fieldSize-<%- size %>" />
	</div>
	<% } %>


	<% if ( type === 'name' ) { %>
	<div class="form-group">
		<label for="<%- cssID %>" class="smfieldlabel <%- isRequired %>"><%- label %></label>
		
		<div class="row nameFieldRow">

			<div class="form-group col-xs-3">
				<input <%- isRequired %> readonly id="<%- cssID %>" type="text" value="" class="form-control <%- cssClasses %> smformfield  fieldSize-<%- size %>" /> <br />
				<label for="<%- cssID %>" class="nameFieldLabelInline"><?php smuzform_translate_e( 'First' ) ?></label>
			</div>
			<div class="form-group col-xs-4">
				<input readonly id="<%- cssID %>" type="text" value="" class="form-control <%- cssClasses %> smformfield  fieldSize-<%- size %>" /> <br />
				<label for="<%- cssID %>" class="nameFieldLabelInline"><?php smuzform_translate_e( 'Last' ) ?></label>
			</div>

		</div>

	</div>
	<% } %>


	<% if ( type === 'email' ) { %>
	<div class="form-group">
		<label for="<%- cssID %>" class="smfieldlabel <%- isRequired %>"><%- label %></label>
		
		<input <%- isRequired %> readonly id="<%- cssID %>" type="email" value="<%- preValue %>" placeholder="<%- placeholderText %>" class="form-control <%- cssClasses %> smformfield  fieldSize-<%- size %>" />
		
	</div>
	<% } %>
	
	<% if ( type === 'website' ) { %>
	<div class="form-group">
		<label for="<%- cssID %>" class="smfieldlabel <%- isRequired %>"><%- label %></label>
		
		<input <%- isRequired %> readonly id="<%- cssID %>" type="url" value="<%- preValue %>" placeholder="<%- placeholderText %>" class="form-control <%- cssClasses %> smformfield  fieldSize-<%- size %>" />
		
	</div>
	<% } %>

	<% if ( type === 'date' ) { %>
	<div class="form-group">
		<label for="<%- cssID %>" class="smfieldlabel <%- isRequired %>"><%- label %></label>
		
		<div class="row nameFieldRow">

			<div class="form-group col-xs-1">
				<input <%- isRequired %> readonly id="<%- cssID %>" type="text" value="<%- extraData.day %>" class="form-control <%- cssClasses %> smformfield  fieldSize-<%- size %>" /> <br /> 
				<label for="<%- cssID %>" class="nameFieldLabelInline"><?php smuzform_translate_e( 'DD' ) ?></label>
			</div>
			<div class="form-group col-xs-1">
				<input readonly id="<%- cssID %>" type="text" value="<%- extraData.month %>" class="form-control <%- cssClasses %> smformfield  fieldSize-<%- size %>" /> <br /> 
				<label for="<%- cssID %>" class="nameFieldLabelInline"><?php smuzform_translate_e( 'MM' ) ?></label>
			</div>
			<div class="form-group col-xs-2">
				<input readonly id="<%- cssID %>" type="text" value="<%- extraData.year %>" class="form-control <%- cssClasses %> smformfield  fieldSize-<%- size %>" /> <br />
				<label for="<%- cssID %>" class="nameFieldLabelInline"><?php smuzform_translate_e( 'YYYY' ) ?></label>
			</div>

		</div>

	</div>
	<% } %>

	<% if ( type === 'phone' ) { %>
	<div class="form-group">
		<label for="<%- cssID %>" class="smfieldlabel <%- isRequired %>"><%- label %></label>
		<input <%- isRequired %> readonly id="<%- cssID %>" type="text" value="<%- preValue %>" placeholder="<%- placeholderText %>" class="form-control <%- cssClasses %> smformfield  fieldSize-<%- size %>" />
	</div>
	<% } %>

	<% if ( type === 'address' ) { %>
	<div class="form-group">
		<label for="<%- cssID %>" class="smfieldlabel <%- isRequired %>"><%- label %></label>
		
		<div class="row addressFieldRow">

			<div class="form-group">
				<input <%- isRequired %> readonly id="<%- cssID %>" type="text" value="<%- extraData.firstAddress %>" class="form-control <%- cssClasses %> smformfield  fieldSize-<%- size %>" /> <br /> 
				<label for="<%- cssID %>" class="nameFieldLabelInline"><?php smuzform_translate_e( 'Street Address' ) ?></label>
			</div>

			<div class="form-group">
				<input <%- isRequired %> readonly id="<%- cssID %>" type="text" value="<%- extraData.secondAddress %>" class="form-control <%- cssClasses %> smformfield  fieldSize-<%- size %>" /> <br /> 
				<label for="<%- cssID %>" class="nameFieldLabelInline"><?php smuzform_translate_e( 'Address 2' ) ?></label>
			</div>
			

		</div>

		<div class="row addressFieldRow">

			<div class="form-group col-xs-4">
				<input <%- isRequired %> readonly id="<%- cssID %>" type="text" value="<%- extraData.city %>" class="form-control <%- cssClasses %> smformfield  fieldSize-<%- size %>" /> <br /> 
				<label for="<%- cssID %>" class="nameFieldLabelInline"><?php smuzform_translate_e( 'City' ) ?></label>
			</div>

			<div class="form-group col-xs-4">
				<input <%- isRequired %> readonly id="<%- cssID %>" type="text" value="<%- extraData.state %>" class="form-control <%- cssClasses %> smformfield  fieldSize-<%- size %>" /> <br /> 
				<label for="<%- cssID %>" class="nameFieldLabelInline"><?php smuzform_translate_e( 'State / Province' ) ?></label>
			</div>
			

		</div>

		<div class="row addressFieldRow">

			<div class="form-group col-xs-4">
				<input <%- isRequired %> readonly id="<%- cssID %>" type="text" value="<%- extraData.code %>" class="form-control <%- cssClasses %> smformfield  fieldSize-<%- size %>" /> <br /> 
				<label for="<%- cssID %>" class="nameFieldLabelInline"><?php smuzform_translate_e( 'Postal / Zip Code' ) ?></label>
			</div>

			<div class="form-group col-xs-4">
				<input <%- isRequired %> readonly id="<%- cssID %>" type="text" value="<%- extraData.country %>" class="form-control <%- cssClasses %> smformfield  fieldSize-<%- size %>" /> <br /> 
				<label for="<%- cssID %>" class="nameFieldLabelInline"><?php smuzform_translate_e( 'Country' ) ?></label>
			</div>
			

		</div>

	</div>
	<% } %>


	<% if ( type === 'customText' ) { %>
		<%= extraData.readyHtml %>
	<% } %>

	<% if ( type === 'customImage' ) { %>
		<%= extraData.readyHtml %>
	<% } %>

	<% if ( type === 'customHtml' ) { %>
		<%= extraData.readyHtml %>
	<% } %>

	<% if ( type === 'pagebreak' ) { %>
		<p>----------------------------------------------------------------<%- label %>-----------------------------------------------------------------</p>
	<% } %>

	

	<div>
		<a class="button removeMeField hidden">X</a>
	</div>
</script>