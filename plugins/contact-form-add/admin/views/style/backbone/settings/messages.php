<div class="panel-group">
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" href="#aformMsgCont">
          <?php smuzform_translate_e( 'Form Messages' ) ?>
        </a>
      </h4>
    </div>
    <div id="aformMsgCont" class="panel-collapse collapse">

      <div class="panel-body">

        <div class="form-group">

        <label for="styleMsgErrorColor"><?php smuzform_translate_e( 'Error Message Color' ) ?></label>
        <input type="text" value="<%- formCont.errorMsgColor %>" id="styleMsgErrorColor" class="colorpickerfield" />

       </div>

       <div class="form-group">

        <label for="styleMsgSuccessColor"><?php smuzform_translate_e( 'Success Message Color' ) ?></label>
        <input type="text" value="<%- formCont.successMsgColor %>" id="styleMsgSuccessColor" class="colorpickerfield" />

       </div>

       <div class="form-group">

        <label for="styleSuccessMsgFontSize"><?php smuzform_translate_e( 'Success Message Font Size' ) ?></label>
        <input type="number" value="<%- formCont.successMsgFontSize %>" id="styleSuccessMsgFontSize" class="form-control" />

       </div>

       <div class="form-group">

        <label for="styleHideFormOnSuccessForSuccessMsg"> 
          <input type="checkbox" id="styleHideFormOnSuccessForSuccessMsg" <% if ( formCont.hideFormOnSuccessForSuccessMsg ) { %> <%- 'checked' %> <% } %> />
         <?php smuzform_translate_e( 'Hide form and only show success message.' ) ?></label>
          <p class="description"><?php smuzform_translate_e( 'If enabled form will be replaced with the success message on successfull form submission.' ) ?> </p>
        
       </div>
        
      </div>
      
      <div class="panel-footer">
        <small><?php smuzform_translate_e( 'To view the changes test the live form.' ) ?></small>
      </div>

    </div>
  </div>
</div>