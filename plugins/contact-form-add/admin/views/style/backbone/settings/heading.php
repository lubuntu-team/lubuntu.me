<div class="panel-group">
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" href="#aformStyleHeading">
          <?php smuzform_translate_e( 'Form Heading' ) ?>
        </a>
      </h4>
    </div>
    <div id="aformStyleHeading" class="panel-collapse collapse">
      <div class="panel-body">

       <div class="form-group">

        <label for="styleHeadingFontSize"><?php smuzform_translate_e( 'Font Size' ) ?></label>
        <input type="number" value="<%- formHeading.fontSize %>" id="styleHeadingFontSize" class="form-control" />

       </div>
          
       <div class="form-group">

       	<label for="styleHeadingColor"><?php smuzform_translate_e( 'Color' ) ?></label>
        <input type="text" value="<%- formHeading.color %>" id="styleHeadingColor" class="colorpickerfield" />

       </div>

       <div class="form-group">

        <label for="styleHeadingBgColor"><?php smuzform_translate_e( 'Background Color' ) ?></label>
        <input type="text" value="<%- formHeading.bgColor %>" id="styleHeadingBgColor" class="colorpickerfield" />

       </div>

       <div class="form-group">

        <label for="styleHeadingMargin"><?php smuzform_translate_e( 'Margin' ) ?></label>
        <input type="number" value="<%- formHeading.margin %>" id="styleHeadingMargin" class="form-control" />
        <p class="description"><?php smuzform_translate_e( 'Leave it empty to use default value.' ) ?></p>

       </div>

       <div class="form-group">

        <label for="styleHeadingPadding"><?php smuzform_translate_e( 'Padding' ) ?></label>
        <input type="number" value="<%- formHeading.padding %>" id="styleHeadingPadding" class="form-control" />
        <p class="description"><?php smuzform_translate_e( 'Leave it empty to use default value.' ) ?></p>
       </div>

      </div>
      
      <div class="panel-footer">
        
      </div>

    </div>
  </div>
</div>