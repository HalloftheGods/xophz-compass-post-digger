<?php

/**
 * The Alphabet Soup API (Post Digger)
 *
 * Handles the REST API endpoints for configuring and spawning Custom Post Types.
 *
 * @since      1.1.0
 * @package    Xophz_Compass_Post_Digger
 * @subpackage Xophz_Compass_Post_Digger/includes
 */

class Xophz_Compass_Post_Digger_API {

	public function register_routes() {
		register_rest_route( 'compass/v1', '/alphabet-soup/cpts', array(
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_cpts' ),
				'permission_callback' => array( $this, 'check_permissions' ),
			),
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'create_cpt' ),
				'permission_callback' => array( $this, 'check_permissions' ),
				'args'                => array(
					'slug' => array(
						'required'          => true,
						'validate_callback' => function( $param ) {
							return is_string( $param ) && ! empty( $param );
						}
					),
					'singular_label' => array(
						'required'          => true,
						'validate_callback' => function( $param ) {
							return is_string( $param ) && ! empty( $param );
						}
					),
					'plural_label' => array(
						'required'          => true,
						'validate_callback' => function( $param ) {
							return is_string( $param ) && ! empty( $param );
						}
					),
					'icon' => array(
						'required'          => false,
						'validate_callback' => function( $param ) {
							return is_string( $param );
						}
					),
					'supports_categories' => array(
						'required'          => false,
						'validate_callback' => function( $param ) {
							return is_bool( $param );
						}
					),
					'supports_tags' => array(
						'required'          => false,
						'validate_callback' => function( $param ) {
							return is_bool( $param );
						}
					),
					'supports_custom_fields' => array(
						'required'          => false,
						'validate_callback' => function( $param ) {
							return is_bool( $param );
						}
					),
				),
			),
			array(
				'methods'             => WP_REST_Server::DELETABLE,
				'callback'            => array( $this, 'delete_cpt' ),
				'permission_callback' => array( $this, 'check_permissions' ),
				'args'                => array(
					'slug' => array(
						'required'          => true,
						'validate_callback' => function( $param ) {
							return is_string( $param ) && ! empty( $param );
						}
					),
				),
			),
		) );
	}

	/**
	 * Permissions check. Only admins/editors should define architectural elements.
	 */
	public function check_permissions() {
		return current_user_can( 'manage_options' );
	}

	/**
	 * GET: Retrieve all dynamic CPT definitions
	 */
	public function get_cpts() {
		$registered_cpts = get_option( 'xophz_compass_registered_cpts', array() );
		return rest_ensure_response( $registered_cpts );
	}

	/**
	 * POST: Append a new CPT schema to the core WP options database
	 */
	public function create_cpt( $request ) {
		$slug     = sanitize_key( $request->get_param( 'slug' ) );
		$singular = sanitize_text_field( $request->get_param( 'singular_label' ) );
		$plural   = sanitize_text_field( $request->get_param( 'plural_label' ) );
		$icon     = sanitize_text_field( $request->get_param( 'icon' ) );
		$fields   = $request->get_param( 'fields' );
		$supports_categories = filter_var( $request->get_param( 'supports_categories' ), FILTER_VALIDATE_BOOLEAN );
		$supports_tags       = filter_var( $request->get_param( 'supports_tags' ), FILTER_VALIDATE_BOOLEAN );
		
		$supports_custom_fields = $request->get_param( 'supports_custom_fields' );
		if ( $supports_custom_fields === null ) {
			$supports_custom_fields = true;
		} else {
			$supports_custom_fields = filter_var( $supports_custom_fields, FILTER_VALIDATE_BOOLEAN );
		}
		
		if ( empty( $icon ) ) {
			$icon = 'dashicons-admin-post';
		}
		
		if ( ! is_array( $fields ) ) {
			$fields = array();
		} else {
			// Sanitize array inside loop
			$sanitized_fields = array();
			foreach ( $fields as $field ) {
				if ( isset( $field['label'] ) && isset( $field['key'] ) ) {
					$sanitized_fields[] = array(
						'label' => sanitize_text_field( $field['label'] ),
						'key'   => sanitize_key( $field['key'] )
					);
				}
			}
			$fields = $sanitized_fields;
		}

		$registered_cpts = get_option( 'xophz_compass_registered_cpts', array() );

		// Check for duplicate slugs
		foreach ( $registered_cpts as $index => $cpt ) {
			if ( $cpt['slug'] === $slug ) {
				// Update existing
				$registered_cpts[ $index ] = array(
					'slug'                => $slug,
					'singular_label'      => $singular,
					'plural_label'        => $plural,
					'icon'                => $icon,
					'fields'              => $fields,
					'supports_categories' => $supports_categories,
					'supports_tags'       => $supports_tags,
					'supports_custom_fields' => $supports_custom_fields
				);
				update_option( 'xophz_compass_registered_cpts', $registered_cpts );
				flush_rewrite_rules(); // Ensure the newly updated slugs map properly
				
				return rest_ensure_response( array(
					'status'  => 'success',
					'message' => "CPT '{$slug}' updated successfully.",
					'data'    => $registered_cpts[ $index ]
				) );
			}
		}

		// Insert new
		$new_cpt = array(
			'slug'                => $slug,
			'singular_label'      => $singular,
			'plural_label'        => $plural,
			'icon'                => $icon,
			'fields'              => $fields,
			'supports_categories' => $supports_categories,
			'supports_tags'       => $supports_tags,
			'supports_custom_fields' => $supports_custom_fields
		);
		$registered_cpts[] = $new_cpt;

		update_option( 'xophz_compass_registered_cpts', $registered_cpts );
		
		// Flush rewrite rules dynamically after option updates.
		flush_rewrite_rules();

		return rest_ensure_response( array(
			'status'  => 'success',
			'message' => "CPT '{$slug}' appended successfully.",
			'data'    => $new_cpt
		) );
	}

	/**
	 * DELETE: Remove a CPT definition from WP_Options
	 */
	public function delete_cpt( $request ) {
		$slug = sanitize_key( $request->get_param( 'slug' ) );
		$registered_cpts = get_option( 'xophz_compass_registered_cpts', array() );
		
		$found = false;
		foreach ( $registered_cpts as $index => $cpt ) {
			if ( $cpt['slug'] === $slug ) {
				unset( $registered_cpts[ $index ] );
				$found = true;
				break;
			}
		}

		if ( $found ) {
			// Re-index array
			$registered_cpts = array_values( $registered_cpts );
			update_option( 'xophz_compass_registered_cpts', $registered_cpts );
			flush_rewrite_rules();

			return rest_ensure_response( array(
				'status'  => 'success',
				'message' => "CPT '{$slug}' deleted successfully."
			) );
		}

		return new WP_Error( 'not_found', 'No Custom Post Type found with that slug.', array( 'status' => 404 ) );
	}
}
