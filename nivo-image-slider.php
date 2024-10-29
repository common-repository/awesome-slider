<?php
/**
 * Plugin Name:       Awesome Slider
 * Description:       A WordPress plugin to include image slider into your theme.
 * Version:           1.4.1
 * Author:            Raihanul Islam
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       nivo-image-slider
 * Domain Path:       /languages/
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-nivo-image-slider-activator.php
 */
register_activation_hook( __FILE__, function() {
	flush_rewrite_rules();
});

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-nivo-image-slider-deactivator.php
 */

register_deactivation_hook( __FILE__, function(){
	flush_rewrite_rules();
});

/**
 * The core plugin class that is used to define internationalization,
 * dashboard-specific hooks, and public-facing site hooks.
 */
// require plugin_dir_path( __FILE__ ) . 'includes/Nivo_Image_Slider.php';

if ( ! class_exists('Nivo_Image_Slider')):

class Nivo_Image_Slider
{

    private $plugin_name    = 'nivo-image-slider';
    private $plugin_version = '1.4.1';
    private $plugin_path;
    private $plugin_url;

	public function __construct()
	{
		$this->includes();
		$this->frontend_includes();

        add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'load_scripts' ) );
	}

	public function includes()
	{
        include_once $this->plugin_path() . '/includes/Nivo_Image_Slider_Meta_Box.php';
		include_once $this->plugin_path() . '/includes/Nivo_Image_Slider_Admin.php';
        include_once $this->plugin_path() . '/includes/Nivo_Image_Slider_Backup.php';

        new Nivo_Image_Slider_Admin();
        new Nivo_Image_Slider_Backup();
	}

    public function admin_scripts( $hook )
    {
        global $post;
        wp_enqueue_media();

        wp_enqueue_script( $this->plugin_name, $this->plugin_url() . '/assets/js/admin.js', array( 'jquery' ), $this->plugin_version, true );
        wp_localize_script( $this->plugin_name, 'NivoImageSlider', array(
            'ajaxurl'           => admin_url( 'admin-ajax.php' ),
            'post_id'           => isset($post->ID) ? $post->ID : -1,
            'image_ids'         => isset($post->ID) ? get_post_meta( $post->ID, '_images_ids', true ) : null,
            'nonce'             => wp_create_nonce( 'nivo_image_slider_ajax_nonce' ),
            'create_btn_text'   => __('Create Gallery', 'nivo-image-slider'),
            'edit_btn_text'     => __('Edit Gallery', 'nivo-image-slider'),
            'progress_btn_text' => __('Saving...', 'nivo-image-slider'),
            'save_btn_text'     => __('Save Gallery', 'nivo-image-slider'),
        ));
    }

	/**
	 * Include frontend files.
	 */
	public function frontend_includes(){
		if( !is_admin() ){
            include_once $this->plugin_path() . '/includes/Nivo_Image_Slider_Shortcodes.php';
            new Nivo_Image_Slider_Shortcodes( $this->plugin_path() );
		}
	}

	public function load_scripts()
	{
		wp_enqueue_style( $this->plugin_name, $this->plugin_url() . '/assets/css/style.css', array(), $this->plugin_version, 'all' );
		wp_enqueue_script( $this->plugin_name, $this->plugin_url() . '/assets/js/jquery.nivo.slider.js', array( 'jquery' ), '3.2', true );
	}

    /**
     * Plugin path.
     *
     * @return string Plugin path
     */
    private function plugin_path() {
        if ( $this->plugin_path ) return $this->plugin_path;

        return $this->plugin_path = untrailingslashit( plugin_dir_path( __FILE__ ) );
    }

    /**
     * Plugin url.
     *
     * @return string Plugin url
     */
    private function plugin_url() {
        if ( $this->plugin_url ) return $this->plugin_url;

        return $this->plugin_url = untrailingslashit( plugin_dir_url( __FILE__ ) );
    }
}

endif;

new Nivo_Image_Slider();
