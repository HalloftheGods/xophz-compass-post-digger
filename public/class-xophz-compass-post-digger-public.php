<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Xophz_Compass_Post_Digger
 * @subpackage Xophz_Compass_Post_Digger/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Xophz_Compass_Post_Digger
 * @subpackage Xophz_Compass_Post_Digger/public
 * @author     Your Name <email@example.com>
 */
class Xophz_Compass_Post_Digger_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Xophz_Compass_Post_Digger_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Xophz_Compass_Post_Digger_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/xophz-compass-post-digger-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Xophz_Compass_Post_Digger_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Xophz_Compass_Post_Digger_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/xophz-compass-post-digger-public.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Register this spark with the YouMeOS Registry.
	 */
	public function register_spark( $sparks ) {
		$sparks[] = array(
			'id' => 'xophz-lemonade-stand',
			'meta' => array(
				'title' => 'Lemonade',
				'icon' => 'fal fa-glass-citrus',
				'color' => '#ffff00',
				'dimensions' => array( 'width' => 1000, 'height' => 700 ),
			),
			'category' => 'picnic'
		);
		return $sparks;
	}

	/**
	 * Retrieve the full manifest for this spark.
	 */
	public function get_spark_manifest( $manifest, $id ) {
		if ( 'xophz-lemonade-stand' !== $id ) {
			return $manifest;
		}

		return array(
			'id' => 'xophz-lemonade-stand',
			'meta' => array(
				'title' => 'Lemonade',
				'icon' => 'fal fa-glass-citrus',
				'dimensions' => array( 'width' => 1000, 'height' => 700 ),
			),
			'navigation' => array(
				'items' => array(
					array( 'id' => 'feed', 'title' => 'News Feed', 'icon' => 'fal fa-newspaper' ),
					array( 'id' => 'sources', 'title' => 'Sources', 'icon' => 'fal fa-rss' ),
				),
				'defaultActive' => 'feed',
			),
			'views' => array(
				'feed' => array(
					'type' => 'layout',
					'root' => array(
						'type' => 'v-container',
						'props' => array( 'fluid' => true, 'class' => 'pa-4' ),
						'children' => array(
							array(
								'type' => 'v-row',
								'children' => array(
									array(
										'type' => 'v-col',
										'props' => array( 'cols' => 12 ),
										'children' => array(
											array(
												'type' => 'x-card',
												'props' => array(
													'title' => 'Incoming Transmissions',
													'subtitle' => 'Latest updates from tracked news sources.',
													'variant' => 'glass',
													'prepend-icon' => 'fal fa-glass-citrus'
												),
												'children' => array(
													array(
														'type' => 'v-card-text',
														'content' => 'Fresh Lemonade: RSS integration loading... Stirring the news feeds for you.'
													)
												)
											)
										)
									)
								)
							)
						)
					)
				),
				'sources' => array(
					'type' => 'html',
					'content' => '<div class="text-center pa-10"><i class="fal fa-rss fa-3x mb-4 text-warning"></i><h3>Refresh Your Sources</h3><p>Manage your RSS lemon squeeze.</p></div>',
				)
			)
		);
	}

}
