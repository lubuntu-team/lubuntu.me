<div class="panel-group">
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" href="#aformStyleFieldInput">
          <?php smuzform_translate_e( 'Field Input' ) ?>
        </a>
      </h4>
    </div>
    <div id="aformStyleFieldInput" class="panel-collapse collapse">
      <div class="panel-body">

       <div class="form-group">

        <label for="styleFieldInputFontSize"><?php smuzform_translate_e( 'Font Size' ) ?></label>
        <input type="number" value="<%- fieldInput.fontSize %>" id="styleFieldInputFontSize" class="form-control" />

       </div>

       <div class="form-group">

        <label for="styleFieldInputFontWeight"> 
          <input type="checkbox" id="styleFieldInputFontWeight" <% if ( fieldInput.fontWeight === 'bold' ) { %> <%- 'checked' %> <% } %> />
         <?php smuzform_translate_e( 'Make Label Text Bold' ) ?></label>
        
        
       </div>
          
       <div class="form-group">

        <label for="styleFieldInputColor"><?php smuzform_translate_e( 'Color' ) ?></label>
        <input type="text" value="<%- fieldInput.color %>" id="styleFieldInputColor" class="colorpickerfield" />

       </div>

       <div class="form-group">

        <label for="styleFieldInputBgColor"><?php smuzform_translate_e( 'Background Color' ) ?></label>
        <input type="text" value="<%- fieldInput.bgColor %>" id="styleFieldInputBgColor" class="colorpickerfield" />

       </div>

       <div class="form-group">

        <label for="styleFieldInputColor"><?php smuzform_translate_e( 'Border Color' ) ?></label>
        <input type="text" value="<%- fieldInput.borderColor %>" id="styleFieldInputBorderColor" class="colorpickerfield" />

       </div>

       <div class="form-group">
        <label for="" class=""><?php smuzform_translate_e( 'Border' ) ?></label>
        
        <div class="row nameFieldRow">

          
          <div class="form-group col-xs-3">
            <input id="styleFieldInputBorderSize" type="number" value="<%- fieldInput.borderSize %>" class="form-control" /> <br /> 
            <label for="styleFieldInputBorderSize" class="nameFieldLabelInline"><?php smuzform_translate_e( 'Size' ) ?></label>
          </div>
          <div class="form-group col-xs-3">
            <input id="styleFieldInputBorderRadius" type="number" value="<%- fieldInput.borderRadius %>" class="form-control" /> <br /> 
            <label for="styleFieldInputBorderRadius" class="nameFieldLabelInline"><?php smuzform_translate_e( 'Radius' ) ?></label>
          </div>
          <div class="form-group col-xs-5">
            <select id="styleFieldInputBorderStyle" class="form-control">
              <option value="solid" <% if ( fieldInput.borderStyle === 'solid' ) { %> <%- 'selected' %> <% } %> ><?php smuzform_translate_e( 'Solid' ) ?></option>
              <option value="dotted" <% if ( fieldInput.borderStyle === 'dotted' ) { %> <%- 'selected' %> <% } %> ><?php smuzform_translate_e( 'Dotted' ) ?></option>
            </select>
            <br /> 
            <label for="styleFieldInputBorderStyle" class="nameFieldLabelInline"><?php smuzform_translate_e( 'Style' ) ?></label>
          </div>

        </div>
      </div>

       <div class="form-group">
        <label for="" class=""><?php smuzform_translate_e( 'Margin' ) ?></label>
        
        <div class="row nameFieldRow">

          <div class="form-group col-xs-3">
            <input id="styleFieldInputMarginLeft" type="number" value="<%- fieldInput.marginLeft %>" class="form-control" /> <br /> 
            <label for="styleFieldInputMarginLeft" class="nameFieldLabelInline"><?php smuzform_translate_e( 'Left' ) ?></label>
          </div>
          <div class="form-group col-xs-3">
            <input id="styleFieldInputMarginRight" type="number" value="<%- fieldInput.marginRight %>" class="form-control" /> <br /> 
            <label for="styleFieldInputMarginRight" class="nameFieldLabelInline"><?php smuzform_translate_e( 'Right' ) ?></label>
          </div>
          <div class="form-group col-xs-3">
            <input id="styleFieldInputMarginTop" type="number" value="<%- fieldInput.marginTop %>" class="form-control" /> <br /> 
            <label for="styleFieldInputMarginTop" class="nameFieldLabelInline"><?php smuzform_translate_e( 'Top' ) ?></label>
          </div>
          <div class="form-group col-xs-3">
            <input id="styleFieldInputMarginBottom" type="number" value="<%- fieldInput.marginRight %>" class="form-control" /> <br /> 
            <label for="styleFieldInputMarginBottom" class="nameFieldLabelInline"><?php smuzform_translate_e( 'Bottom' ) ?></label>
          </div>

        </div>
      </div>

       <div class="form-group">
        <label for="" class=""><?php smuzform_translate_e( 'Padding' ) ?></label>
        
        <div class="row nameFieldRow">

          <div class="form-group col-xs-3">
            <input id="styleFieldInputPaddingLeft" type="number" value="<%- fieldInput.paddingLeft %>" class="form-control" /> <br /> 
            <label for="styleFieldInputPaddingLeft" class="nameFieldLabelInline"><?php smuzform_translate_e( 'Left' ) ?></label>
          </div>
          <div class="form-group col-xs-3">
            <input id="styleFieldInputPaddingRight" type="number" value="<%- fieldInput.paddingRight %>" class="form-control" /> <br /> 
            <label for="styleFieldInputPaddingRight" class="nameFieldLabelInline"><?php smuzform_translate_e( 'Right' ) ?></label>
          </div>
          <div class="form-group col-xs-3">
            <input id="styleFieldInputPaddingTop" type="number" value="<%- fieldInput.paddingTop %>" class="form-control" /> <br /> 
            <label for="styleFieldInputPaddingTop" class="nameFieldLabelInline"><?php smuzform_translate_e( 'Top' ) ?></label>
          </div>
          <div class="form-group col-xs-3">
            <input id="styleFieldInputPaddingBottom" type="number" value="<%- fieldInput.paddingRight %>" class="form-control" /> <br /> 
            <label for="styleFieldInputPaddingBottom" class="nameFieldLabelInline"><?php smuzform_translate_e( 'Bottom' ) ?></label>
          </div>

        </div>

      </div>

      </div>
      
      <div class="panel-footer">
        
      </div>

    </div>
  </div>
</div>
