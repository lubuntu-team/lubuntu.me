<div class="panel-group">
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" href="#rules<%= uniqID %>">
          <?php smuzform_translate_e( 'Conditional Logic' ) ?>
        </a>
      </h4>
    </div>
    <div id="rules<%= uniqID %>" class="panel-collapse collapse">
      <div class="panel-body">
          
        <div id="ruleBuilderCont">
          
          <div class="checkbox">
          <label><input type="checkbox" id="enableConditionalLogic" <% if ( ruleEnabled === true ) { %> <%- 'checked' %> <% } %> /> <?php smuzform_translate_e( 'Enable' ) ?></label></div>

          <ul id="rules">
            
            <li>
              
              <div class="rule">

                <div class="ruleInlineCont">
                  <strong>If</strong>

                  <select id="ruleFields">
                    <option value="">Select a field</option>
                    <?php foreach( $notificationManager->getFilterFields() as $field ): ?>
                      
                    
                    
                    <?php if ( $field['type'] !== 'address' && $field['type'] !== 'sectionbreak' && $field['type'] !== 'name' && $field['type'] !== 'fileupload' && $field['type'] !== 'checkbox' && $field['type'] !== 'customText' && $field['type'] !== 'customHtml' && $field['type'] !== 'customImage' && $field['type'] !== 'customLink' ) : ?>

                    <option <% if ( rules.field === '<?php echo esc_js( $field['cssID'] ) ?>' ) { %> <%- 'selected' %> <% } %> value="<?php echo esc_js( $field['cssID'] ) ?>"><?php echo esc_html( $field['label'] ) ?></option>
                    
                    

                    <?php endif; ?>

                    <?php endforeach; ?>

                  </select>

                  <select id="ruleFieldOperator">

                    <option <% if ( rules.operator === 'is' ) { %> <%- 'selected' %> <% } %> value="is">Is</option>
                    <option <% if ( rules.operator === 'isNot' ) { %> <%- 'selected' %> <% } %> value="isNot">Is not</option>

                  </select>
                </div>

                <div class="ruleInlineCont">
                  <input type="text" value="<%- rules.cmpValue %>" id="ruleFieldLogicValue" placeholder="Enter comparison value" />

                  <select id="ruleFieldAction">

                    <option <% if ( rules.action === 'send' ) { %> <%- 'selected' %> <% } %> value="send">Send</option>
                    <option <% if ( rules.action === 'stop' ) { %> <%- 'selected' %> <% } %> value="stop">Don't Send</option>

                  </select>
                </div>


              </div>

            </li>

          </ul>

        </div>

      </div>
      
      <div class="panel-footer">
        
      </div>

    </div>
  </div>
</div>