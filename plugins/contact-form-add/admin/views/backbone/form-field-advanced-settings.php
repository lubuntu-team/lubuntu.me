<div class="panel-group" id="fieldAdvancedCont">
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" href="#collapseAdvanced<%= cssID %>">
          <?php smuzform_translate_e( 'Advance Settings' ) ?>
        </a>
      </h4>
    </div>
    <div id="collapseAdvanced<%= cssID %>" class="panel-collapse collapse">
      <div class="panel-body">
      	
      	<div class="form-group">
			<label for="fieldCssClasses"><?php smuzform_translate_e('CSS Classes') ?></label>
			<input id="fieldCssClasses" type="text" value="<%- cssClasses %>" class="form-control" />
			<p class="description"><?php smuzform_translate_e( 'Classes should be separated by whitespaces.' ) ?></p>
		</div>

      </div>
      
      <div class="panel-footer">
        
      </div>

    </div>
  </div>
</div>