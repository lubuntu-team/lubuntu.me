<ol style="list-style-type: disc">
	<?php foreach ( $value['checkbox'] as $check ): ?>
		<li>
			<?php esc_html_e( $check ) ?>
		</li>
	<?php endforeach; ?>
<ol>