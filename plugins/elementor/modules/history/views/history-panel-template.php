<script type="text/template" id="tmpl-elementor-panel-history-page">
	<div id="elementor-panel-elements-navigation" class="elementor-panel-navigation">
		<div id="elementor-panel-elements-navigation-history" class="elementor-panel-navigation-tab active" data-view="history"><?php esc_html_e( 'Actions', 'elementor' ); ?></div>
		<div id="elementor-panel-elements-navigation-revisions" class="elementor-panel-navigation-tab" data-view="revisions"><?php esc_html_e( 'Revisions', 'elementor' ); ?></div>
	</div>
	<div id="elementor-panel-history-content"></div>
</script>

<script type="text/template" id="tmpl-elementor-panel-history-tab">
	<div class="elementor-panel-box">
		<div class="elementor-panel-box-content">
			<div id="elementor-history-list"></div>
			<div class="elementor-history-revisions-message"><?php esc_html_e( 'Switch to Revisions tab for older versions', 'elementor' ) ?></div>
		</div>
	</div>
</script>

<script type="text/template" id="tmpl-elementor-panel-history-no-items">
	<i class="elementor-panel-nerd-box-icon eicon-nerd"></i>
	<div class="elementor-panel-nerd-box-title"><?php esc_html_e( 'No History Yet', 'elementor' ); ?></div>
	<div class="elementor-panel-nerd-box-message"><?php esc_html_e( 'Once you start working, you\'ll be able to redo / undo any action you make in the editor.', 'elementor' ) ?></div>
	<div class="elementor-panel-nerd-box-message"><?php esc_html_e( 'Switch to Revisions tab for older versions', 'elementor' ) ?></div>
</script>

<script type="text/template" id="tmpl-elementor-panel-history-item">
	<div class="elementor-history-item elementor-history-item-{{ status }}">
		<div class="elementor-history-item__details">
			<span class="elementor-history-item__title">{{{ title }}} </span>
			<span class="elementor-history-item__subtitle">{{{ subTitle }}} </span>
			<span class="elementor-history-item__action">{{{ action }}}</span>
		</div>
		<div class="elementor-history-item__icon">
			<span class="fa"></span>
		</div>
	</div>
</script>
