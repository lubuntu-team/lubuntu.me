<script type="text/template" id="notificationCollectionView-template">

<% if ( type === 'email' ) { %>

<div class="panel-group" id="accordion" role="tablist">
  
  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="heading<%= uniqID %>">
    <span class="glyphicon glyphicon-remove-circle pull-right removeNotification"></span>
      <h4 class="panel-title">
        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#<%= uniqID %>">
          <%- label %>
        </a>
      </h4>
    </div>
    <div id="<%= uniqID %>" class="panel-collapse collapse" role="tabpanel">
      <div class="panel-body">
        
        <form onsubmit="return false;">

          <legend>
            <h4><?php smuzform_translate_e( 'Configure Email Notification' ) ?></h4>
            <p class="description"><?php smuzform_translate_e( 'Receive an email when someone fills your form.' ) ?></p>
          </legend>
          
          <fieldset>

          <div class="form-group">

            <label for="notificationLabel"><?php smuzform_translate_e( 'Label' ) ?></label>

            <input class="form-control" type="text" value="<%- label %>" id="notificationLabel" />

            <small>Set it to unique so you can manage and find multiple notifications easily.</small>
           
          </div>

          <div class="form-inline">
            <div class="form-group">

              <label class="control-label inlineLabel" for="emailSenderName"><?php smuzform_translate_e( 'Sender Name' ) ?></label>

              <input id="emailSenderName" type="text" class="form-control inlineField" value="<%= extraData.fromText %>" />
            </div>

             <div class="form-group">

              <label class="control-label inlineLabel" for="emailSenderAddress"><?php smuzform_translate_e( 'Send Email To' ) ?></label>

              <% if ( extraData.emailAddress == '' ) { %>
              <input id="emailSenderAddress" type="text" class="form-control inlineField" value="<?php echo get_option('admin_email') ?>" />
              <% } else { %>
              <input id="emailSenderAddress" type="text" class="form-control inlineField" value="<%= extraData.emailAddress %>" />
              <% } %>

            </div>

            <div class="form-group">

              <label class="control-label inlineLabel" for="emailReplyToEmail"><?php smuzform_translate_e( 'Reply-To' ) ?></label>

              <input id="emailReplyToEmail" type="text" class="form-control inlineField" value="<%- extraData.replyToEmail %>" />
              <p class="help-block"><strong>Format:</strong> Name &ltemailAddress&gt</p>

            </div>

            <div class="form-group">

              <label class="control-label inlineLabel" for="emailSubject"><?php smuzform_translate_e( 'Subject' ) ?></label>

              <input id="emailSubject" type="text" class="form-control inlineField" value="<%- extraData.subject %>" />

            </div>
          </div>

          <div class="form-group">

            <div class="checkbox">
              <label><input type="checkbox" id="emailUseHTML" <% if ( extraData.useHTML )  { %> <%= 'checked' %> <% } %> /> <?php smuzform_translate_e( 'Send HTML Emails' ) ?></label>
            </div>

          </div>

          <div class="form-group">

            <label for="emailTemplate"><?php smuzform_translate_e( 'Template Editor' ) ?></label>

            <p>
            <small>Use<code>[smfield print="all"]</code> to add all fields data and labels in template. You can replace the <code>all</code> with field label to only display specific field value.</small></p>

            <textarea rows="12" class="form-control" id="emailTemplate"><%= extraData.template %></textarea>
           
          </div>

          <div class="form-group">
            <?php include smuzform_admin_view( 'notification/backbone/rule-builder.php' ); ?>
          </div>

          </fieldset>

        </form>

      </div>
    </div>
  </div>

<% } %>

<% if ( type === 'confirmationEmail' ) { %>

<div class="panel-group" id="accordion" role="tablist">
  
  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="heading<%= uniqID %>">
    <span class="glyphicon glyphicon-remove-circle pull-right removeNotification"></span>
      <h4 class="panel-title">
        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#<%= uniqID %>">
          <%= label %>
        </a>
      </h4>
    </div>
    <div id="<%= uniqID %>" class="panel-collapse collapse" role="tabpanel">
      <div class="panel-body">
        
        <form onsubmit="return false;">

          <legend>
            <h4><?php smuzform_translate_e( 'Configure Email Notification' ) ?></h4>
            <p class="description"><?php smuzform_translate_e( 'Send Confirmation email to the user who filled form.' ) ?></p>
          </legend>
          
          <fieldset>

          <div class="form-group">

            <label for="notificationLabel"><?php smuzform_translate_e( 'Label' ) ?></label>

            <input class="form-control" type="text" value="<%- label %>" id="notificationLabel" />

            <small>Set it to unique so you can manage and find multiple notifications easily.</small>
           
          </div>

          <div class="pull-left">

            <div class="form-group">

              <label class="control-label inlineLabel" for="emailSenderAddress"><?php smuzform_translate_e( 'Send Email To' ) ?></label>

              <select id="emailSenderAddress" class="form-control">
              <?php foreach( $notificationManager->getFilterFields() as $field ): ?>
                <?php  if ( $field['type'] === 'email' ): ?>
                <option value="<?php echo esc_attr( $field['cssID'] ) ?>" <% if ( extraData.emailAddress === '<?php echo esc_js( $field['cssID'] ) ?>' ) { %> <%- 'selected' %> <% } %>><?php echo esc_attr( $field['label'] ) ?></option>
                <?php endif; ?>
              <?php endforeach; ?>
              </select>
              <p class="help-block"><?php smuzform_translate_e( 'Your form must have an email field.' ) ?></p>
              

            </div>

            <div class="form-group">

              <label class="control-label inlineLabel" for="emailSenderName"><?php smuzform_translate_e( 'Your Name' ) ?></label>

              <input id="emailSenderName" type="text" class="form-control inlineField" value="<%- extraData.fromText %>" />

            </div>

             

            <div class="form-group">

              <label class="control-label inlineLabel" for="emailReplyToEmail"><?php smuzform_translate_e( 'Reply-To' ) ?></label>

              <input id="emailReplyToEmail" type="email" class="form-control inlineField" value="<%- extraData.replyToEmail %>" />

            </div>

            <div class="form-group">

              <label class="control-label inlineLabel" for="emailSubject"><?php smuzform_translate_e( 'Subject' ) ?></label>

              <input id="emailSubject" type="text" class="form-control inlineField" value="<%- extraData.subject %>" />

            </div>
          </div>

          <div class="form-group">

           

          </div>

          <div class="form-group pull-right" id="emailRightSidebarCont">

            <div class="checkbox">
              <label><input type="checkbox" id="emailUseHTML" <% if ( extraData.useHTML )  { %> <%= 'checked' %> <% } %> /> <?php smuzform_translate_e( 'Send HTML Emails' ) ?></label>
            </div>

            <label for="emailTemplate"><?php smuzform_translate_e( 'Message' ) ?></label>

            <p>
            <small>Use<code>[smfield print="replaceWithFieldLabel"]</code> to display specific field value. Do not use print <code>all</code> when sending confirmation emails.</small></p>

            <textarea spellcheck="false" rows="10" class="form-control" id="emailTemplate"><%= extraData.template %></textarea>
           
          </div>

          <br style="clear: both" />

          <div class="form-group">
            <?php include smuzform_admin_view( 'notification/backbone/rule-builder.php' ); ?>
          </div>

          </fieldset>

        </form>

      </div>
    </div>
  </div>

<% } %>  
  
</div>

</script>