<div class="panel-group">
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" href="#aformStyleDescription">
          <?php smuzform_translate_e( 'Form Description' ) ?>
        </a>
      </h4>
    </div>
    <div id="aformStyleDescription" class="panel-collapse collapse">
      <div class="panel-body">

       <div class="form-group">

        <label for="styleDescriptionFontSize"><?php smuzform_translate_e( 'Font Size' ) ?></label>
        <input type="number" value="<%- formDescription.fontSize %>" id="styleDescriptionFontSize" class="form-control" />

       </div>
          
       <div class="form-group">

        <label for="styleDescriptionColor"><?php smuzform_translate_e( 'Color' ) ?></label>
        <input type="text" value="<%- formDescription.color %>" id="styleDescriptionColor" class="colorpickerfield" />

       </div>

       <div class="form-group">

        <label for="styleDescriptionBgColor"><?php smuzform_translate_e( 'Background Color' ) ?></label>
        <input type="text" value="<%- formDescription.bgcolor %>" id="styleDescriptionBgColor" class="colorpickerfield" />

       </div>

       <div class="form-group">

        <label for="styleDescriptionMargin"><?php smuzform_translate_e( 'Margin' ) ?></label>
        <input type="number" value="<%- formDescription.margin %>" id="styleDescriptionMargin" class="form-control" />
        <p class="description"><?php smuzform_translate_e( 'Leave it empty to use default value.' ) ?></p>

       </div>

       <div class="form-group">

        <label for="styleDescriptionPadding"><?php smuzform_translate_e( 'Padding' ) ?></label>
        <input type="number" value="<%- formDescription.padding %>" id="styleDescriptionPadding" class="form-control" />
        <p class="description"><?php smuzform_translate_e( 'Leave it empty to use default value.' ) ?></p>
       </div>

      </div>
      
      <div class="panel-footer">
        
      </div>

    </div>
  </div>
</div>