<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Xophz_Compass_Post_Digger
 * @subpackage Xophz_Compass_Post_Digger/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Xophz_Compass_Post_Digger
 * @subpackage Xophz_Compass_Post_Digger/admin
 * @author     Your Name <email@example.com>
 */
class Xophz_Compass_Post_Digger_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/xophz-compass-post-digger-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/xophz-compass-post-digger-admin.js', array( 'jquery' ), $this->version, false );


    wp_enqueue_script(
        $this->plugin_name.'-tinymce',
        'https://cdn.jsdelivr.net/npm/tinymce@5.0.4/tinymce.min.js'
    );
	}


	/**
	 * Add menu item 
	 *
	 * @since    1.0.0
	 */
	public function addToMenu(){
        Xophz_Compass::add_submenu($this->plugin_name);
	}


  public function getPosts(){
    $posts = get_posts([
      'numberposts' => 25,
      'post_status' => 'any',
    ]);
    Xophz_Compass::output_json($posts);
  }

  public function getPost(){
    $post = get_post($_REQUEST['post_id']);
    Xophz_Compass::output_json($post);
  }

  public function enqueue_gutenberg_dark_mode(){
    if (!isset($_GET['theme']) || $_GET['theme'] !== 'transparent') {
        return;
    }
    
    // Use wp-block-library as the handle to ensure it gets pulled into the iframe
    $styles_css = '
        :root {
            --bg-color-100: transparent;
            --text-color-100: rgba(255, 255, 255, 0.95);
            --link-color-100: #62c9ff;
        }
        
        /* Core Backgrounds */
        html, body, .wp-admin, #wpwrap, #wpbody, #wpcontent, #wpbody-content {
            background: transparent !important;
            background-color: transparent !important;
        }

        /* EDITOR WRAPPERS & IFRAME CANVAS */
        .interface-interface-skeleton,
        .interface-interface-skeleton__body,
        .interface-interface-skeleton__content,
        .interface-interface-skeleton__editor,
        .edit-post-layout__content,
        .edit-post-visual-editor,
        .edit-post-visual-editor__content-area,
        .block-editor__container,
        .block-editor-writing-flow,
        .editor-styles-wrapper,
        .is-root-container,
        iframe[name="editor-canvas"],
        .edit-post-visual-editor__editor-canvas {
            background: transparent !important;
            background-color: transparent !important;
            color: var( --text-color-100 ) !important;
        }

        /* PARAGRAPH & HEADINGS */
        .editor-styles-wrapper p,
        .editor-styles-wrapper h1,
        .editor-styles-wrapper h2,
        .editor-styles-wrapper h3,
        .editor-styles-wrapper h4,
        .editor-styles-wrapper h5,
        .editor-styles-wrapper h6,
        .editor-styles-wrapper ul,
        .editor-styles-wrapper li,
        .editor-post-title__block .editor-post-title__input,
        .wp-block {
            color: var( --text-color-100 ) !important;
        }

        /* LINKS */
        .editor-styles-wrapper a { color: var( --link-color-100 ) !important; }

        /* HEADER & SIDEBAR PANELS */
        .interface-interface-skeleton__header, 
        .edit-post-header,
        .interface-interface-skeleton__sidebar,
        .interface-complementary-area,
        .edit-post-sidebar,
        .edit-post-layout__sidebar,
        .components-panel {
            background: rgba(0, 0, 0, 0.6) !important;
            background-color: rgba(0, 0, 0, 0.6) !important;
            backdrop-filter: blur(12px) !important;
            border-color: rgba(255, 255, 255, 0.1) !important;
            color: var( --text-color-100 ) !important;
        }
        
        .components-panel__header,
        .components-panel__body {
            background: transparent !important;
            background-color: transparent !important;
            color: var( --text-color-100 ) !important;
            border-color: rgba(255, 255, 255, 0.1) !important;
        }

        /* META BOXES */
        .edit-post-meta-boxes-area, #poststuff {
            background: transparent !important;
            background-color: transparent !important;
        }
        .postbox {
            background: rgba(0, 0, 0, 0.6) !important;
            backdrop-filter: blur(12px) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            color: var( --text-color-100 ) !important;
        }
        .postbox-header {
            border-bottom: 1px solid rgba(255, 255, 255, 0.1) !important;
            color: var( --text-color-100 ) !important;
        }

        /* COMPONENTS BUTTON */
        .components-button {
            background: rgba(255, 255, 255, 0.05) !important;
            color: var( --text-color-100 ) !important;
        }
        .components-button.is-primary {
            background: #62c9ff !important;
            color: #000 !important;
        }
        .block-editor-inserter__toggle.components-button.has-icon {
            background: #62c9ff !important;
            color: #000 !important;
        }
        .block-editor-inserter__toggle.components-button.has-icon svg {
            color: #000 !important;
        }
        
        /* Inputs */
        input[type="text"], textarea, .components-text-control__input {
            background: rgba(0,0,0,0.5) !important;
            color: var( --text-color-100 ) !important;
            border: 1px solid rgba(255,255,255,0.2) !important;
        }
    ';
    
    wp_add_inline_style('wp-block-library', $styles_css);
    wp_add_inline_style('wp-edit-post', $styles_css);
    wp_add_inline_style('wp-edit-site', $styles_css);
    wp_add_inline_style('wp-editor', $styles_css);
    wp_add_inline_style('wp-components', $styles_css);
  }

  public function inject_gutenberg_iframe_styles( $settings, $context ) {
      if (!isset($_GET['theme']) || $_GET['theme'] !== 'transparent') {
          return $settings;
      }
      
      $custom_css = '
          html, body, .editor-styles-wrapper {
              background: transparent !important;
              background-color: transparent !important;
              color: rgba(255, 255, 255, 0.95) !important;
          }
          .editor-styles-wrapper p,
          .editor-styles-wrapper h1,
          .editor-styles-wrapper h2,
          .editor-styles-wrapper h3,
          .editor-styles-wrapper h4,
          .editor-styles-wrapper h5,
          .editor-styles-wrapper h6,
          .editor-styles-wrapper ul,
          .editor-styles-wrapper li,
          .wp-block {
              color: rgba(255, 255, 255, 0.95) !important;
          }
          .editor-styles-wrapper a {
              color: #62c9ff !important;
          }
      ';

      if ( !isset( $settings['styles'] ) ) {
          $settings['styles'] = array();
      }

      $settings['styles'][] = array(
          'css'    => $custom_css,
          'source' => 'xophz-compass-gutenberg-dark',
      );

      return $settings;
  }

}
