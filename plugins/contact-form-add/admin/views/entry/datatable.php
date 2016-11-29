<table id="entryDataTable" class="table table-striped table-bordered">
	<thead>
		<tr>
			<?php foreach( $entryManager->getFilterFields() as $field ): ?>
			<th id="<?php echo $field['cssID'] ?>"><?php echo esc_html( $field['label'] ) ?></th>
			<?php endforeach; ?>
		</tr>
	</thead>
</table>