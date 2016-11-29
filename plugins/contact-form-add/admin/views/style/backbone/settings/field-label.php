<div class="panel-group">
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" href="#aformStyleFieldLabel">
          <?php smuzform_translate_e( 'Field Label' ) ?>
        </a>
      </h4>
    </div>
    <div id="aformStyleFieldLabel" class="panel-collapse collapse">
      <div class="panel-body">

       <div class="form-group">

        <label for="styleFieldLabelFontSize"><?php smuzform_translate_e( 'Font Size' ) ?></label>
        <input type="number" value="<%- fieldLabel.fontSize %>" id="styleFieldLabelFontSize" class="form-control" />

       </div>

       <div class="form-group">

        <label for="styleFieldLabelFontWeight"> 
          <input type="checkbox" id="styleFieldLabelFontWeight" <% if ( fieldLabel.fontWeight === 'bold' ) { %> <%- 'checked' %> <% } %> />
         <?php smuzform_translate_e( 'Make Label Text Bold' ) ?></label>
        
        
       </div>
          
       <div class="form-group">

        <label for="styleFieldLabelColor"><?php smuzform_translate_e( 'Color' ) ?></label>
        <input type="text" value="<%- fieldLabel.color %>" id="styleFieldLabelColor" class="colorpickerfield" />

       </div>

       <div class="form-group">

        <label for="styleFieldLabelBgColor"><?php smuzform_translate_e( 'Background Color' ) ?></label>
        <input type="text" value="<%- fieldLabel.bgColor %>" id="styleFieldLabelBgColor" class="colorpickerfield" />

       </div>

       <div class="form-group">

        <label for="styleFieldLabelColor"><?php smuzform_translate_e( 'Border Color' ) ?></label>
        <input type="text" value="<%- fieldLabel.borderColor %>" id="styleFieldLabelBorderColor" class="colorpickerfield" />

       </div>

       <div class="form-group">
        <label for="" class=""><?php smuzform_translate_e( 'Border' ) ?></label>
        
        <div class="row nameFieldRow">

          
          <div class="form-group col-xs-3">
            <input id="styleFieldLabelBorderSize" type="number" value="<%- fieldLabel.borderSize %>" class="form-control" /> <br /> 
            <label for="styleFieldLabelBorderSize" class="nameFieldLabelInline"><?php smuzform_translate_e( 'Size' ) ?></label>
          </div>
          <div class="form-group col-xs-3">
            <input id="styleFieldLabelBorderRadius" type="number" value="<%- fieldLabel.borderRadius %>" class="form-control" /> <br /> 
            <label for="styleFieldLabelBorderRadius" class="nameFieldLabelInline"><?php smuzform_translate_e( 'Radius' ) ?></label>
          </div>
          <div class="form-group col-xs-5">
            <select id="styleFieldLabelBorderStyle" class="form-control">
              <option value="solid" <% if ( fieldLabel.borderStyle === 'solid' ) { %> <%- 'selected' %> <% } %> ><?php smuzform_translate_e( 'Solid' ) ?></option>
              <option value="dotted" <% if ( fieldLabel.borderStyle === 'dotted' ) { %> <%- 'selected' %> <% } %> ><?php smuzform_translate_e( 'Dotted' ) ?></option>
            </select>
            <br /> 
            <label for="styleFieldLabelBorderStyle" class="nameFieldLabelInline"><?php smuzform_translate_e( 'Style' ) ?></label>
          </div>

        </div>
      </div>

       <div class="form-group">
        <label for="" class=""><?php smuzform_translate_e( 'Margin' ) ?></label>
        
        <div class="row nameFieldRow">

          <div class="form-group col-xs-3">
            <input id="styleFieldLabelMarginLeft" type="number" value="<%- fieldLabel.marginLeft %>" class="form-control" /> <br /> 
            <label for="styleFieldLabelMarginLeft" class="nameFieldLabelInline"><?php smuzform_translate_e( 'Left' ) ?></label>
          </div>
          <div class="form-group col-xs-3">
            <input id="styleFieldLabelMarginRight" type="number" value="<%- fieldLabel.marginRight %>" class="form-control" /> <br /> 
            <label for="styleFieldLabelMarginRight" class="nameFieldLabelInline"><?php smuzform_translate_e( 'Right' ) ?></label>
          </div>
          <div class="form-group col-xs-3">
            <input id="styleFieldLabelMarginTop" type="number" value="<%- fieldLabel.marginTop %>" class="form-control" /> <br /> 
            <label for="styleFieldLabelMarginTop" class="nameFieldLabelInline"><?php smuzform_translate_e( 'Top' ) ?></label>
          </div>
          <div class="form-group col-xs-3">
            <input id="styleFieldLabelMarginBottom" type="number" value="<%- fieldLabel.marginRight %>" class="form-control" /> <br /> 
            <label for="styleFieldLabelMarginBottom" class="nameFieldLabelInline"><?php smuzform_translate_e( 'Bottom' ) ?></label>
          </div>

        </div>
      </div>

       <div class="form-group">
        <label for="" class=""><?php smuzform_translate_e( 'Padding' ) ?></label>
        
        <div class="row nameFieldRow">

          <div class="form-group col-xs-3">
            <input id="styleFieldLabelPaddingLeft" type="number" value="<%- fieldLabel.paddingLeft %>" class="form-control" /> <br /> 
            <label for="styleFieldLabelPaddingLeft" class="nameFieldLabelInline"><?php smuzform_translate_e( 'Left' ) ?></label>
          </div>
          <div class="form-group col-xs-3">
            <input id="styleFieldLabelPaddingRight" type="number" value="<%- fieldLabel.paddingRight %>" class="form-control" /> <br /> 
            <label for="styleFieldLabelPaddingRight" class="nameFieldLabelInline"><?php smuzform_translate_e( 'Right' ) ?></label>
          </div>
          <div class="form-group col-xs-3">
            <input id="styleFieldLabelPaddingTop" type="number" value="<%- fieldLabel.paddingTop %>" class="form-control" /> <br /> 
            <label for="styleFieldLabelPaddingTop" class="nameFieldLabelInline"><?php smuzform_translate_e( 'Top' ) ?></label>
          </div>
          <div class="form-group col-xs-3">
            <input id="styleFieldLabelPaddingBottom" type="number" value="<%- fieldLabel.paddingRight %>" class="form-control" /> <br /> 
            <label for="styleFieldLabelPaddingBottom" class="nameFieldLabelInline"><?php smuzform_translate_e( 'Bottom' ) ?></label>
          </div>

        </div>
      </div>


      <div class="form-group">

        <label for="styleFieldLabelAdvancedColor"><?php smuzform_translate_e( 'Advanced Field Label Color' ) ?></label>
        <input type="text" value="<%- fieldLabel.advancedColor %>" id="styleFieldAdvancedColor" class="colorpickerfield" />

       </div>


    </div>
      
      <div class="panel-footer">
        
      </div>

    </div>
  </div>
</div>