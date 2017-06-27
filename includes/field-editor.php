<?php
wp_editor( $value, $id, array(
    'textarea_rows' => isset( $field['rows'] ) ? $field['rows'] : 10,
	'wpautop'       => true
) );
