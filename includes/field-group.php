<?php

if ( is_object( $value ) ) {
    $value = (array)$value;
}

$group_fields   = $field['fields'];
$group_title    = isset( $field['title'] ) ? $field['title'] : '';
$group_field_info = array();
?>
<div class="mbt-fields-group-wrapper">

    <script type="text/html" class="mbt-fields-group-template">
        <div class="mbt-fields-group" data-group-id="1" data-group-title="<?php echo $group_title; ?>" data-field-name="<?php echo $name; ?>">
            <h4 class="mbt-fields-group-title"><?php echo $group_title . ' 1'; ?></h4>
            <div class="mbt-fields-group-inner">
                <table class="mbt-metabox-form-table form-table">
                    <?php
                        foreach ( $group_fields as $field_name => $field_data ) : // Fields

                            $group_field_name = $name . '[1]' . '[' . $field_name . ']';

                            $group_field_info['group_field'] = $name;
                            $group_field_info['group_sub_fields'][] = array(
                                'field_name'    => $group_field_name,
                                'original_name' => $field_name,
                            );

                            MetaBox_Tabs::render_metabox_field( $group_field_name, $field_data );

                        endforeach;
                    ?>
                </table>
                <div class="mbt-fields-group-info" data-info="<?php echo esc_attr(json_encode($group_field_info)); ?>"></div>
                <div class="mbt-fields-group-footer wp-clearfix">
                    <div class="mbt-fields-group-order">
                        <a href="javascript:void(0)" class="button mbt-fields-group-up"><span class="dashicons dashicons-arrow-up-alt2"></span></a>
                        <a href="javascript:void(0)" class="button mbt-fields-group-down"><span class="dashicons dashicons-arrow-down-alt2"></span></a>
                    </div>
                    <div class="mbt-fields-group-remove">
                        <a href="javascript:void(0)" class="button mbt-fields-group-remove"><?php esc_html_e('Remove', 'mbt'); ?></a>
                    </div>
                </div>
            </div>
        </div>
    </script>

    <?php if ( ! is_array( $value ) ) : $group_field_info = array(); ?>
        <div class="mbt-fields-group" data-group-id="1" data-group-title="<?php echo $group_title; ?>" data-field-name="<?php echo $name; ?>">
            <h4 class="mbt-fields-group-title"><?php echo $group_title . ' 1'; ?></h4>
            <div class="mbt-fields-group-inner">
                <table class="mbt-metabox-form-table form-table">
                    <?php
                        foreach ( $group_fields as $field_name => $field_data ) : // Fields

                            $group_field_name = $name . '[1]' . '[' . $field_name . ']';

                            $group_field_info['group_field'] = $name;
                            $group_field_info['group_sub_fields'][] = array(
                                'field_name'    => $group_field_name,
                                'original_name' => $field_name,
                            );

                            MetaBox_Tabs::render_metabox_field( $group_field_name, $field_data );

                        endforeach;
                    ?>
                </table>
                <div class="mbt-fields-group-info" data-info="<?php echo esc_attr(json_encode($group_field_info)); ?>"></div>
                <div class="mbt-fields-group-footer wp-clearfix" style="display: none;">
                    <div class="mbt-fields-group-order">
                        <a href="javascript:void(0)" class="button mbt-fields-group-up"><span class="dashicons dashicons-arrow-up-alt2"></span></a>
                        <a href="javascript:void(0)" class="button mbt-fields-group-down"><span class="dashicons dashicons-arrow-down-alt2"></span></a>
                    </div>
                    <div class="mbt-fields-group-remove">
                        <a href="javascript:void(0)" class="button mbt-fields-group-remove"><?php esc_html_e('Remove', 'mbt'); ?></a>
                    </div>
                </div>
            </div>
        </div>
    <?php else : ?>
        <?php foreach ( $value as $group_id => $field_value ) : $group_field_info = array(); ?>
            <div class="mbt-fields-group" data-group-id="<?php echo $group_id; ?>" data-group-title="<?php echo $group_title; ?>" data-field-name="<?php echo $name; ?>">
                <h4 class="mbt-fields-group-title"><?php echo $group_title . ' ' . $group_id; ?></h4>
                <div class="mbt-fields-group-inner">
                    <table class="mbt-metabox-form-table form-table">
                        <?php
                            foreach ( $group_fields as $field_name => $field_data ) : // Fields

                                $group_field_name = $name . '['.$group_id.']' . '[' . $field_name . ']';

                                $group_field_value = isset( $field_value[$field_name] ) ? $field_value[$field_name] : '';

                                $group_field_info['group_field'] = $name;
                                $group_field_info['group_sub_fields'][] = array(
                                    'field_name'    => $group_field_name,
                                    'original_name' => $field_name,
                                );

                                MetaBox_Tabs::render_metabox_field( $group_field_name, $field_data, $group_field_value );

                            endforeach;
                        ?>
                    </table>
                    <div class="mbt-fields-group-info" data-info="<?php echo esc_attr(json_encode($group_field_info)); ?>"></div>
                    <?php if ( count( $value ) > 1 ) : ?>
                        <div class="mbt-fields-group-footer wp-clearfix">
                            <div class="mbt-fields-group-order">
                                <a href="javascript:void(0)" class="button mbt-fields-group-up"><span class="dashicons dashicons-arrow-up-alt2"></span></a>
                                <a href="javascript:void(0)" class="button mbt-fields-group-down"><span class="dashicons dashicons-arrow-down-alt2"></span></a>
                            </div>
                            <div class="mbt-fields-group-remove">
                                <a href="javascript:void(0)" class="button mbt-fields-group-remove" data-remove-group="<?php echo $group_id; ?>"><?php esc_html_e('Remove', 'mbt'); ?></a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
    <div class="mbt-fields-group-action">
        <a href="javascript:void(0)" class="button button-primary mbt-fields-group-add"><?php esc_html_e('Add New', 'mbt'); ?></a>
    </div>
</div>
