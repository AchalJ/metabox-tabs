<?php
    $attrs = '';
    $class = 'mbt-input-field';

    if ( isset( $field['multiple'] ) && $field['multiple'] ) {
        $attrs .= ' multiple="multiple"';
    }

    // Toggle data.
    if ( isset( $field['toggle'] ) ) {
        $attrs .= ' data-toggle="' . esc_attr( json_encode( $field['toggle'] ) ) . '"';
    }

    // Browser's Autocomplete Off.
    $attrs .= ' autocomplete="off"';

    // Custom field classes.
    if ( isset( $field['class'] ) ) {
        $class .= ' ' . $field['class'];
    }

    // Required.
    if ( isset( $field['required'] ) && $field['required'] ) {
        $class .= ' mbt-required-field';
    }
?>
<select id="<?php echo $id; ?>" name="<?php echo $id; ?>" class="<?php echo $class; ?>"<?php echo $attrs; ?>>
    <?php if ( ! isset( $field['options'] ) || empty( $field['options'] ) ) : ?>
        <option value=""></option>
    <?php else : ?>
        <?php foreach ( $field['options'] as $option_value => $option_label ) : ?>
            <option value="<?php echo $option_value; ?>" <?php selected( $value, $option_value ); ?>><?php echo $option_label; ?></option>
        <?php endforeach; ?>
    <?php endif; ?>
</select>
