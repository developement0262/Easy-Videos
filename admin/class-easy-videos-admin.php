<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       google.com
 * @since      1.0.0
 *
 * @package    Easy_Videos
 * @subpackage Easy_Videos/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Easy_Videos
 * @subpackage Easy_Videos/admin
 * @author     Vaibhav <development0262@gmail.com>
 */
class Easy_Videos_Admin {

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
		 * defined in Easy_Videos_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Easy_Videos_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/easy-videos-admin.css', array(), $this->version, 'all' );

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
		 * defined in Easy_Videos_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Easy_Videos_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		//wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/easy-videos-admin.js', array( 'jquery' ), $this->version, false );

		wp_enqueue_script( 'admin_script', plugin_dir_url( __FILE__ ) . 'js/admin_script.js', array( 'jquery' ) );
		wp_localize_script( 'admin_script', 'ev_ajax_script', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
		
	}

	public function ev_custom_post_type_video(){
		
		/**
		 * Registering Custom Post Type 'Videos'
		 */

		$labels = array(
			'name'                => __( 'Videos' ),
			'singular_name'       => __( 'Video'),
			'menu_name'           => __( 'Videos'),
			'all_items'           => __( 'All Videos'),
			'view_item'           => __( 'View Video'),
			'add_new_item'        => __( 'Add New Video'),
			'add_new'             => __( 'Add New'),
			'edit_item'           => __( 'Edit Video'),
			'update_item'         => __( 'Update Video'),
			'search_items'        => __( 'Search Video'),
			'not_found'           => __( 'Not Found'),
			'not_found_in_trash'  => __( 'Not found in Trash')
		);
		$args = array(
			'label'               => __( 'Videos'),
			'description'         => __( 'YouTube Videos'),
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor', 'thumbnail'),
			'public'              => true,
			'hierarchical'        => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'has_archive'         => true,
			'can_export'          => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'page'
		);
	
		register_post_type( 'videos', $args );

	}

	public function ev_custom_taxonomy_video(){
		
		$labels = array(
			'name' => _x( 'Video Categories', 'taxonomy general name' ),
			'singular_name' => _x( 'Video Category', 'taxonomy singular name' ),
			'search_items' =>  __( 'Search Video Category' ),
			'all_items' => __( 'All Video Categories' ),
			'edit_item' => __( 'Edit Video Category' ), 
			'update_item' => __( 'Update Video Category' ),
			'add_new_item' => __( 'Add New Video Category' ),
			'menu_name' => __( 'Video Categories' ),
		); 	
		 
		register_taxonomy('video-categories', array('videos'), array(
			'hierarchical' => true,
			'labels' => $labels,
			'show_ui' => true,
			'show_admin_column' => true,
			'query_var' => true,
			'rewrite' => array( 'slug' => 'video-categories' ),
		));

	}

	public function ev_admin_menu(){
		
		add_menu_page( 'Easy Videos Settings', 'Easy Videos Settings', 'manage_options', 'easy-vidoes-settings', array($this, 'ev_easy_video_settings'), 'dashicons-format-video', 80 );

		add_submenu_page( 'easy-vidoes-settings', 'Import Videos', 'Import Videos', 'manage_options', 'ev-import-videos', array($this, 'ev_sub_menu_page') );

	}

	public function ev_easy_video_settings(){
		include ( plugin_dir_path( __FILE__ ) . 'partials/easy_video_menu_settings.php' );
	}

	public function ev_save_youtube_api(){
		register_setting( 'ev_youtube_api_settings', 'youtube_api_key' );
	}

	public function ev_sub_menu_page(){
		include ( plugin_dir_path( __FILE__ ) . 'partials/easy_video_sub_menu_settings.php' );
	}

	public function ev_easy_video_ajax(){
		include ( plugin_dir_path( __FILE__ ) . 'partials/easy_video_ajax.php' );
	}

	public function ev_init_files(){
		include ( plugin_dir_path( __FILE__ ) . 'partials/easy_video_api.php' );
		include ( plugin_dir_path( __FILE__ ) . 'partials/easy_video_functions.php' );
	}

}
