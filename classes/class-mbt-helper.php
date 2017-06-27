<?php

// Helper class.
class MetaBox_Tabs_Helper {
    /**
	 * Returns an array of data for post types supported
	 * by module loop settings.
	 *
	 * @since 1.0.0
     * @param array $exclude Post types to be excluded.
	 * @return array
	 */
	static public function post_types( $exclude = array() )
	{
		$post_types = get_post_types(array(
			'public'	=> true,
			'show_ui'	=> true
		), 'objects');

		unset( $post_types['attachment'] );

        if ( count( $exclude ) ) {
            foreach ( $exclude as $type ) {
                if ( isset( $post_types[$type] ) ) {
                    unset( $post_types[$type] );
                }
            }
        }

		return $post_types;
	}

    /**
	 * Get an array of supported taxonomy data for a post type.
	 *
	 * @since 1.0.0
	 * @param string $post_type The post type to get taxonomies for.
	 * @param array $exclude Taxonomies to be excluded.
	 * @return array
	 */
	static public function taxonomies($post_type, $exclude = array())
	{
		$taxonomies = get_object_taxonomies( $post_type, 'objects' );
		$data		= array();

		foreach ( $taxonomies as $tax_slug => $tax ) {

			if ( ! $tax->public || ! $tax->show_ui ) {
				continue;
			}

            if ( in_array( $tax_slug, $exclude ) ) {
                continue;
            }

			$data[$tax_slug] = $tax;
		}

		return apply_filters( 'mbt_post_loop_taxonomies', $data, $taxonomies, $post_type );
	}
}
