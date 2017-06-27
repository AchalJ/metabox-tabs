<?php if ( empty( $field['label'] ) ) : ?>
<td class="mbt-field-control" colspan="2">
<?php else: ?>
<th class="mbt-field-label">
    <label for="<?php echo $id; ?>"><?php echo $field['label']; ?></label>
</th>
<td class="mbt-field-control">
<?php endif; ?>
    <div class="mbt-field-control-wrapper">
    <?php
    $field_file = MBT_DIR . 'includes/field-' . $field['type'] . '.php';

    if ( file_exists( $field_file ) ) {
        include $field_file;
    }
    ?>

    <?php if ( isset( $field['description'] ) ) : ?>
	<span class="mbt-field-description"><?php echo $field['description']; ?></span>
	<?php endif; ?>

    <?php if ( isset( $field['help'] ) ) : ?>
	<p class="description"><?php echo $field['help']; ?></p>
	<?php endif; ?>

    </div>
</td>
