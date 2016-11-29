<div class="panel-group">
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" href="#aformStyleCont">
          <?php smuzform_translate_e( 'Form Container' ) ?>
        </a>
      </h4>
    </div>
    <div id="aformStyleCont" class="panel-collapse collapse">
      <div class="panel-body">

       <div class="form-group">

        <label for="styleContFontSize"><?php smuzform_translate_e( 'Font Size' ) ?></label>
        <input type="number" value="<%- formCont.fontSize %>" id="styleContFontSize" class="form-control" />

       </div>
          
       <div class="form-group">

       	<label for="styleContColor"><?php smuzform_translate_e( 'Color' ) ?></label>
        <input type="text" value="<%- formCont.color %>" id="styleContColor" class="colorpickerfield" />

       </div>

       <div class="form-group">

        <label for="styleContBgColor"><?php smuzform_translate_e( 'Background Color' ) ?></label>
        <input type="text" value="<%- formCont.bgColor %>" id="styleContBgColor" class="colorpickerfield" />

       </div>

       <div class="form-group">
        <label for="styleContBgImageUrl"><?php smuzform_translate_e( 'Background Image Url' ) ?></label>
        <input type="url" value="<%- formCont.bgImageUrl %>" id="styleContBgImageUrl" class="form-control" />
        <p class="description"><?php smuzform_translate_e( 'Leave it empty to use background color.' ) ?></p>
       </div>

       <div class="form-group">

        <label for="styleContMargin"><?php smuzform_translate_e( 'Margin' ) ?></label>
        <input type="number" value="<%- formCont.margin %>" id="styleContMargin" min="0" class="form-control" />
        <p class="description"><?php smuzform_translate_e( 'Leave it empty to use default value.' ) ?></p>

       </div>

       <div class="form-group">

        <label for="styleContPadding"><?php smuzform_translate_e( 'Padding' ) ?></label>
        <input type="number" value="<%- formCont.padding %>" id="styleContPadding" min="0" class="form-control" />
        <p class="description"><?php smuzform_translate_e( 'Leave it empty to use default value.' ) ?></p>
       </div>

       <div class="form-group">
        <div class="checkbox">
          <label><input type="checkbox" id="hideHeadingSection" <% if ( formCont.hideHeadingSection ) { %> <%- 'checked' %> <% } %> > <?php smuzform_translate_e( 'Hide Heading and Description' ) ?></label>
        </div>
        <p class="description"><?php smuzform_translate_e( 'Hide form heading and description.' ) ?></p>
       </div>

      </div>
      
      <div class="panel-footer">
        
      </div>

    </div>
  </div>
</div>