<?php
    $class = 'mbt-input-field';

    // Custom field classes.
    if ( isset( $field['class'] ) ) {
        $class .= ' ' . $field['class'];
    }

    // Required.
    if ( isset( $field['required'] ) && $field['required'] ) {
        $class .= ' mbt-required-field';
    }

    if ( is_object( $value ) ) {
		$value = (array) $value;
	}

	$prepend    = array('00', '01','02','03','04','05','06','07','08','09');
    $days       = array_merge( $prepend, range( 10, 31 ) );
	$hours      = array_merge( $prepend, range( 10, 23 ) );
	$minutes    = array_merge( $prepend, range( 10, 59 ) );
	$seconds    = array_merge( $prepend, range( 10, 59 ) );
?>
<select name="<?php echo $id . '[days]'; ?>" class="mbt-time-field-days <?php echo $class; ?>" title="<?php _e('Days', 'mbt'); ?>" style="width: 65px;">
    <option value=""><?php _e('Days', 'mbt'); ?></option>
<?php foreach( $days as $day ) : ?>
	<?php $selected = isset( $value['days'] ) && $value['days'] == $day ? ' selected="selected"' : ''; ?>
	<option value="<?php echo $day ?>"<?php echo $selected ?>><?php echo $day ?></option>
<?php endforeach; ?>
</select>
<select name="<?php echo $id . '[hours]'; ?>" class="mbt-time-field-hours <?php echo $class; ?>" title="<?php _e('Hours', 'mbt'); ?>" style="width: 65px;">
    <option value=""><?php _e('Hours', 'mbt'); ?></option>
<?php foreach( $hours as $hour ) : ?>
	<?php $selected = isset( $value['hours'] ) && $value['hours'] == $hour ? ' selected="selected"' : ''; ?>
	<option value="<?php echo $hour ?>"<?php echo $selected ?>><?php echo $hour ?></option>
<?php endforeach; ?>
</select>
<select name="<?php echo $id . '[minutes]'; ?>" class="mbt-time-field-minutes <?php echo $class; ?>" title="<?php _e('Minutes', 'mbt'); ?>" style="width: 65px;">
    <option value=""><?php _e('Minutes', 'mbt'); ?></option>
<?php foreach( $minutes as $minute ) : ?>
	<?php $selected = isset( $value['minutes'] ) && $value['minutes'] == $minute ? ' selected="selected"' : ''; ?>
	<option value="<?php echo $minute ?>"<?php echo $selected ?>><?php echo $minute ?></option>
<?php endforeach; ?>
</select>
<select name="<?php echo $id . '[seconds]'; ?>" class="mbt-time-field-seconds <?php echo $class; ?>" title="<?php _e('Seconds', 'mbt'); ?>" style="width: 65px;">
    <option value=""><?php _e('Seconds', 'mbt'); ?></option>
<?php foreach( $seconds as $second ) : ?>
	<?php $selected = isset( $value['seconds'] ) && $value['seconds'] == $second ? ' selected="selected"' : ''; ?>
	<option value="<?php echo $second ?>"<?php echo $selected ?>><?php echo $second ?></option>
<?php endforeach; ?>
</select>
