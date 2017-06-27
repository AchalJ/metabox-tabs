<?php
$attrs = '';
$class = 'mbt-input-field';

if ( isset( $field['rows'] ) && !empty( $field['rows'] ) ) {
    $attrs .= ' rows="' . $field['rows'] . '"';
}
if ( isset( $field['placeholder'] ) ) {
    $attrs .= ' placeholder="' . $field['placeholder'] . '"';
}
if ( isset( $field['class'] ) && !empty( $field['class'] ) ) {
    $class .= ' ' . $field['class'];
}
// Required.
if ( isset( $field['required'] ) && $field['required'] ) {
    $class .= ' mbt-required-field';
}
?>
<textarea id="<?php echo $id; ?>" name="<?php echo $id; ?>" class="<?php echo $class; ?>"<?php echo $attrs; ?>><?php echo $value; ?></textarea>
