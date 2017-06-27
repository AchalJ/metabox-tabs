<?php

class MetaBox_Tabs {

    const VERSION = '1.0';

    /**
	 * Current post_id.
	 *
	 * @since 1.0
	 * @var $post_id int
	 */
    static public $post_id;

    /**
	 * Metabox arguments.
	 *
	 * @since 1.0
	 * @var $args array
	 */
    static private $args = array();

    static private $object_types = array();

    static private $metabox = array();

    /**
	 * Default values of metabox.
	 *
	 * @since 1.0
	 * @var $defaults array
	 */
    static private $defaults = array(
        'id'            => '',
        'title'         => '',
        'object_types'  => array(),
        'context'       => 'normal',
        'priority'      => 'low',
        'show_header'   => true,
        'fields_prefix' => ''
    );

    /**
	 * Initialize hooks and filters.
	 *
	 * @since 1.0
	 * @return void
	 */
    static public function init()
    {
        define( 'MBT_DIR', self::get_dir() );
        define( 'MBT_URL', self::get_url() );

        add_action( 'admin_init', __CLASS__ .'::init_hooks' );
    }

    static public function init_hooks()
    {
        if ( empty( self::$args ) ) {
            return;
        }

        add_action( 'admin_enqueue_scripts',    __CLASS__ . '::enqueue_scripts', 15 );
        add_action( 'admin_head',               __CLASS__ . '::inline_styles' );
        add_action( 'save_post',                __CLASS__ . '::save_metabox' );
    }

    /**
	 * Get the directory.
	 *
	 * @since 1.0
	 * @return string
	 */
    static public function get_dir()
    {
        return trailingslashit( dirname( __FILE__ ) );
    }

    /**
	 * Get URL of the directory.
	 *
	 * @since 1.0
	 * @return string
	 */
    static public function get_url()
    {
        $real_path = str_replace( '\\', '/', dirname( __FILE__ ) );
        $url = str_replace( $_SERVER['DOCUMENT_ROOT'], '', $real_path );
		$url = ( isset( $_SERVER['HTTPS'] ) ? 'https' : 'http' ) . '://' . $_SERVER['HTTP_HOST'] . $url;

		return trailingslashit( $url );
    }

    /**
	 * Enqueue styles and scripts for metabox and fields.
	 *
	 * @since 1.0
	 * @return void
	 */
    static public function enqueue_scripts( $hook )
    {
        global $post_type;

        $object_types   = self::$object_types;

        if ( $hook == 'post-new.php' || $hook == 'post.php' ) {
            if ( in_array( $post_type, $object_types ) ) {
                wp_enqueue_style( 'mbt-metabox-style', MBT_URL . '/assets/css/meta.css', array() );
                wp_enqueue_script( 'mbt-metabox-script', MBT_URL . '/assets/js/meta.js', array('jquery'), true );
                //wp_enqueue_style( 'select2-style', 'assets/vendor/select2/select2.css', array() );
        		//wp_enqueue_script( 'select2-script', 'assets/vendor/select2/select2.full.js', array('jquery') );
            }
        }
    }

    /**
	 * Metabox inline styles.
	 *
	 * @since 1.0
	 * @return void
	 */
    static public function inline_styles()
    {
        global $post_type;

        $object_types   = self::$object_types;
        ?>

        <?php if ( in_array( $post_type, $object_types ) ) { ?>
            <style id="mbt-metabox-style">

            <?php foreach ( self::$args as $args ) {
                $metabox_id     = $args['id'];
                $show_header    = $args['show_header'];
            ?>
            <?php if ( ! $show_header ) { ?>
                <?php echo '#' . $metabox_id; ?> .hndle,
                <?php echo '#' . $metabox_id; ?> .handlediv {
                    display: none !important;
                }
            <?php } ?>
                <?php echo '#' . $metabox_id; ?> .inside {
                    padding: 0;
                    margin: 0;
                }
            <?php } ?>
            </style>
        <?php }
    }

    /**
	 * Triggers a hook to register metabox.
	 *
	 * @since 1.0
	 * @return void
	 */
    static public function add_meta_box( $args )
    {
        $args = wp_parse_args( $args, self::$defaults );

        self::$args[] = $args;
        self::$metabox = $args;

        foreach ( (array)$args['object_types'] as $object_type ) {
            if ( ! in_array( $object_type, self::$object_types ) ) {
                self::$object_types[] = $object_type;
            }
        }

        add_action( 'add_meta_boxes', __CLASS__ . '::add_meta_boxes' );
    }

    /**
	 * Registers a metabox.
	 *
	 * @since 1.0
	 * @return void
	 */
    static public function add_meta_boxes()
    {
        foreach ( self::$args as $args ) {
            add_meta_box( $args['id'], $args['title'], __CLASS__ . '::render_metabox', $args['object_types'], $args['context'], $args['priority'] );
        }
    }

    /**
	 * Render metabox.
	 *
	 * @since 1.0
	 * @return void
	 */
    static public function render_metabox( $post )
    {
        self::$post_id = $post->ID;

        $tabs = self::$metabox['config'];
        $metabox_id = self::$metabox['id'];

        wp_nonce_field( $metabox_id, $metabox_id . '_nonce' );

        include self::get_dir() . 'includes/metabox.php';
    }

    /**
	 * Renders a field in the current metabox.
	 *
	 * @since 1.0
	 * @return void
	 */
    static public function render_metabox_field( $name, $field )
    {
        if ( ! isset( $field['type'] ) || empty( $field['type'] ) ) {
            return;
        }

        $post_id    = self::$post_id;
        $prefix     = isset( self::$metabox['fields_prefix'] ) ? self::$metabox['fields_prefix'] : '';
        $id         = $prefix . $name;
        $default    = isset( $field['default'] ) ? $field['default'] : '';

        if ( metadata_exists( 'post', $post_id, $id ) ) {
            $value  = get_post_meta( $post_id, $id, true );
        } else {
            $value  = $default;
        }

        echo '<tr id="mbt-field-' . $name . '" class="mbt-field" data-type="' . $field['type'] . '">';
        include MBT_DIR . 'includes/field.php';
        echo '</tr>';
    }

    /**
	 * Returns an array of fields in a metabox.
	 *
	 * @since 1.0
	 * @return array
	 */
	static public function get_metabox_fields()
	{
		$fields = array();
        $config = self::$metabox['config'];

		foreach ( $config as $tab ) {
			if ( isset( $tab['sections'] ) ) {
				foreach ( $tab['sections'] as $section ) {
					if ( isset( $section['fields'] ) ) {
						foreach ( $section['fields'] as $name => $field ) {
							$fields[ $name ] = $field;
						}
					}
				}
			}
		}

		return $fields;
	}

    /**
	 * Save metabox fields.
	 *
	 * @since 1.0
	 * @return void
	 */
    static public function save_metabox( $post_id )
    {
        $metabox_id = self::$metabox['id'];
        $object_types = (array)self::$metabox['object_types'];

        // Verify the nonce.
        if ( ! isset( $_POST[$metabox_id . '_nonce'] ) || ! wp_verify_nonce( $_POST[$metabox_id . '_nonce'], $metabox_id ) ) {
            return $post_id;
        }

        // Verify if this is an auto save routine.
        if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
            return $post_id;
        }

        // Check permissions to edit pages and/or posts
        if ( in_array( $_POST['post_type'], $object_types ) ) {
            if ( ! current_user_can( 'edit_page', $post_id ) || ! current_user_can( 'edit_post', $post_id ) ) {
                return $post_id;
            }
        }

        $fields = self::get_metabox_fields();

        foreach ( $fields as $name => $field ) {
            $field_id = self::$metabox['fields_prefix'] . $name;

            if ( isset( $_POST[$field_id] ) ) {
                $value = self::sanitize_field( $field, $_POST[$field_id] );
                update_post_meta( $post_id, $field_id, $value );
            }
        }
    }

    /**
	 * Sanitize metabox fields.
	 *
	 * @since 1.0
	 * @return mixed
	 */
    static public function sanitize_field( $field, $value )
    {
        if ( isset( $field['sanitize'] ) && ! $field['sanitize'] ) {
            return $value;
        }

        switch ( $field['type'] ) {
            case 'text':
                $value = sanitize_text_field( $value );
            break;
            case 'textarea':
                $value = sanitize_textarea_field( $value );
            break;
            case 'email':
                $value = sanitize_email( $value );
            break;
        }

        return $value;
    }
}
MetaBox_Tabs::init();

MetaBox_Tabs::add_meta_box( array(
    'id'            => 'mbt_custom_box',
    'title'         => 'Custom Metabox',
    'object_types'  => array('post'),
    'context'       => 'normal',
    'priority'      => 'high',
    'show_header'   => false,
    'fields_prefix' => 'mbt_',
    'config'        => array(
        'first_tab'     => array(
            'title'         => 'First Tab',
            'sections'      => array(
                'first_section' => array(
                    'title'         => 'First Section',
                    'description'   => 'Section description',
                    'fields'        => array(
                        'first_field'   => array(
                            'type'          => 'text',
                            'label'         => 'First Field',
                            'default'       => 'Default Value of First Field',
                            'description'   => 'px',
                            'help'          => 'Field description'
                        ),
                        'second_field'  => array(
                            'type'          => 'select',
                            'label'         => 'Second Field',
                            'default'       => 'option_2',
                            'options'       => array(
                                'option_1'      => 'Option 1',
                                'option_2'      => 'Option 2',
                                'option_3'      => 'Option 3',
                            ),
                            'toggle'        => array(
                                'option_1'      => array(
                                    'tabs'      => array('second_tab')
                                )
                            )
                        )
                    )
                ),
                'second_section'    => array(
                    'title'             => 'Second Section',
                    'fields'            => array(
                        'third_field'   => array(
                            'type'          => 'text',
                            'label'         => 'Third Field',
                            'default'       => 'Third Field',
                            'description'   => 'px',
                            'help'          => 'Field description',
                        ),
                    )
                )
            )
        ),
        'second_tab'    => array(
            'title'         => 'Second Tab',
            'sections'      => array(
                'another_section'   => array(
                    'title'             => 'Another Section',
                    'fields'            => array(
                        'another_field'     => array(
                            'type'              => 'textarea',
                            'label'             => 'Another Field',
                            'default'           => 'So this is another field! Yeah!',
                            'rows'              => 5
                        )
                    )
                )
            )
        )
    )
) );

MetaBox_Tabs::add_meta_box( array(
    'id'            => 'mbt_custom_box_2',
    'title'         => 'Custom Metabox',
    'object_types'  => array('post'),
    'context'       => 'normal',
    'priority'      => 'high',
    'show_header'   => false,
    'fields_prefix' => 'mbt_',
    'config'        => array(
        'first_tab'     => array(
            'title'         => 'First Tab',
            'sections'      => array(
                'first_section' => array(
                    'title'         => 'First Section',
                    'description'   => 'Section description',
                    'fields'        => array(
                        'first_field'   => array(
                            'type'          => 'text',
                            'label'         => 'First Field',
                            'default'       => 'Default Value of First Field',
                            'description'   => 'px',
                            'help'          => 'Field description'
                        ),
                        'second_field'  => array(
                            'type'          => 'select',
                            'label'         => 'Second Field',
                            'default'       => 'option_2',
                            'options'       => array(
                                'option_1'      => 'Option 1',
                                'option_2'      => 'Option 2',
                                'option_3'      => 'Option 3',
                            ),
                        )
                    )
                ),
                'second_section'    => array(
                    'title'             => 'Second Section',
                    'fields'            => array(
                        'third_field'   => array(
                            'type'          => 'text',
                            'label'         => 'Third Field',
                            'default'       => 'Third Field',
                            'description'   => 'px',
                            'help'          => 'Field description',
                        ),
                    )
                )
            )
        ),
    )
) );
