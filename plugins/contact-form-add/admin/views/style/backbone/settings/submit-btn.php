<div class="panel-group">
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" href="#aformstyleSubmitBtn">
          <?php smuzform_translate_e( 'Submit Button' ) ?>
        </a>
      </h4>
    </div>
    <div id="aformstyleSubmitBtn" class="panel-collapse collapse">
      <div class="panel-body">

       <div class="form-group">

        <label for="styleSubmitBtnFontSize"><?php smuzform_translate_e( 'Font Size' ) ?></label>
        <input type="number" value="<%- submitBtn.fontSize %>" id="styleSubmitBtnFontSize" class="form-control" />

       </div>

       <div class="form-group">

        <label for="styleSubmitBtnFontWeight"> 
          <input type="checkbox" id="styleSubmitBtnFontWeight" <% if ( submitBtn.fontWeight === 'bold' ) { %> <%- 'checked' %> <% } %> />
         <?php smuzform_translate_e( 'Make Label Text Bold' ) ?></label>
        
        
       </div>

       <div class="form-group">

        <label for="styleSubmitBtnAlignCenter"> 
          <input type="checkbox" id="styleSubmitBtnAlignCenter" <% if ( submitBtn.alignCenter ) { %> <%- 'checked' %> <% } %> />
         <?php smuzform_translate_e( 'Center align submit button' ) ?></label>
        
        
       </div>
          
       <div class="form-group">

        <label for="styleSubmitBtnColor"><?php smuzform_translate_e( 'Color' ) ?></label>
        <input type="text" value="<%- submitBtn.color %>" id="styleSubmitBtnColor" class="colorpickerfield" />

       </div>

       <div class="form-group">

        <label for="styleSubmitBtnBgColor"><?php smuzform_translate_e( 'Background Color' ) ?></label>
        <input type="text" value="<%- submitBtn.bgColor %>" id="styleSubmitBtnBgColor" class="colorpickerfield" />

       </div>

       <div class="form-group">

        <label for="styleSubmitBtnBorderColor"><?php smuzform_translate_e( 'Border Color' ) ?></label>
        <input type="text" value="<%- submitBtn.borderColor %>" id="styleSubmitBtnBorderColor" class="colorpickerfield" />

       </div>

       <div class="form-group">

        <label for="styleSubmitBtnHoverBgColor"><?php smuzform_translate_e( 'Hover Background Color' ) ?></label>
        <input type="text" value="<%- submitBtn.hoverBgColor %>" id="styleSubmitBtnHoverBgColor" class="colorpickerfield" />

       </div>

       <div class="form-group">
        <label for="" class=""><?php smuzform_translate_e( 'Border' ) ?></label>
        
        <div class="row nameFieldRow">

          
          <div class="form-group col-xs-3">
            <input id="styleSubmitBtnBorderSize" type="number" value="<%- submitBtn.borderSize %>" class="form-control" /> <br /> 
            <label for="styleSubmitBtnBorderSize" class="nameFieldLabelInline"><?php smuzform_translate_e( 'Size' ) ?></label>
          </div>
          <div class="form-group col-xs-3">
            <input id="styleSubmitBtnBorderRadius" type="number" value="<%- submitBtn.borderRadius %>" class="form-control" /> <br /> 
            <label for="styleSubmitBtnBorderRadius" class="nameFieldLabelInline"><?php smuzform_translate_e( 'Radius' ) ?></label>
          </div>
          <div class="form-group col-xs-5">
            <select id="styleSubmitBtnBorderStyle" class="form-control">
              <option value="solid" <% if ( submitBtn.borderStyle === 'solid' ) { %> <%- 'selected' %> <% } %> ><?php smuzform_translate_e( 'Solid' ) ?></option>
              <option value="dotted" <% if ( submitBtn.borderStyle === 'dotted' ) { %> <%- 'selected' %> <% } %> ><?php smuzform_translate_e( 'Dotted' ) ?></option>
            </select>
            <br /> 
            <label for="styleSubmitBtnBorderStyle" class="nameFieldLabelInline"><?php smuzform_translate_e( 'Style' ) ?></label>
          </div>

        </div>
      </div>

       <div class="form-group">
        <label for="" class=""><?php smuzform_translate_e( 'Margin' ) ?></label>
        
        <div class="row nameFieldRow">

          <div class="form-group col-xs-3">
            <input id="styleSubmitBtnMarginLeft" type="number" value="<%- submitBtn.marginLeft %>" class="form-control" /> <br /> 
            <label for="styleSubmitBtnMarginLeft" class="nameFieldLabelInline"><?php smuzform_translate_e( 'Left' ) ?></label>
          </div>
          <div class="form-group col-xs-3">
            <input id="styleSubmitBtnMarginRight" type="number" value="<%- submitBtn.marginRight %>" class="form-control" /> <br /> 
            <label for="styleSubmitBtnMarginRight" class="nameFieldLabelInline"><?php smuzform_translate_e( 'Right' ) ?></label>
          </div>
          <div class="form-group col-xs-3">
            <input id="styleSubmitBtnMarginTop" type="number" value="<%- submitBtn.marginTop %>" class="form-control" /> <br /> 
            <label for="styleSubmitBtnMarginTop" class="nameFieldLabelInline"><?php smuzform_translate_e( 'Top' ) ?></label>
          </div>
          <div class="form-group col-xs-3">
            <input id="styleSubmitBtnMarginBottom" type="number" value="<%- submitBtn.marginRight %>" class="form-control" /> <br /> 
            <label for="styleSubmitBtnMarginBottom" class="nameFieldLabelInline"><?php smuzform_translate_e( 'Bottom' ) ?></label>
          </div>

        </div>

      </div>

       <div class="form-group">
        <label for="" class=""><?php smuzform_translate_e( 'Padding' ) ?></label>
        
        <div class="row nameFieldRow">

          <div class="form-group col-xs-3">
            <input id="styleSubmitBtnPaddingLeft" type="number" value="<%- submitBtn.paddingLeft %>" class="form-control" /> <br /> 
            <label for="styleSubmitBtnPaddingLeft" class="nameFieldLabelInline"><?php smuzform_translate_e( 'Left' ) ?></label>
          </div>
          <div class="form-group col-xs-3">
            <input id="styleSubmitBtnPaddingRight" type="number" value="<%- submitBtn.paddingRight %>" class="form-control" /> <br /> 
            <label for="styleSubmitBtnPaddingRight" class="nameFieldLabelInline"><?php smuzform_translate_e( 'Right' ) ?></label>
          </div>
          <div class="form-group col-xs-3">
            <input id="styleSubmitBtnPaddingTop" type="number" value="<%- submitBtn.paddingTop %>" class="form-control" /> <br /> 
            <label for="styleSubmitBtnPaddingTop" class="nameFieldLabelInline"><?php smuzform_translate_e( 'Top' ) ?></label>
          </div>
          <div class="form-group col-xs-3">
            <input id="styleSubmitBtnPaddingBottom" type="number" value="<%- submitBtn.paddingRight %>" class="form-control" /> <br /> 
            <label for="styleSubmitBtnPaddingBottom" class="nameFieldLabelInline"><?php smuzform_translate_e( 'Bottom' ) ?></label>
          </div>

        </div>

      </div>

      </div>
      
      <div class="panel-footer">
        
      </div>

    </div>
  </div>

</div>
