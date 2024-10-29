<?php
if( ! class_exists('Nivo_Image_Slider_Shortcodes')):

class Nivo_Image_Slider_Shortcodes
{
	private $plugin_path;

	public function __construct( $plugin_path )
	{
		$this->plugin_path = $plugin_path;

		add_shortcode('nivo_image_slider', array( $this, 'nivo_image_slider' ) );

		// Depreciated at version 1.4.0
		add_shortcode('all-nivoslider', array( $this, 'all_nivoslider'));
		add_shortcode('nivo-slider', array( $this, 'nivo_slider'));
		add_shortcode('nivoslides', array( $this, 'nivoslides'));
	}

	/**
	 * A shortcode for rendering the nivo image slider.
	 *
	 * @param  array   $atts  		Shortcode attributes.
	 * @param  string  $content 	The text content for shortcode. Not used.
	 *
	 * @return string  The shortcode output
	 */
	public function nivo_image_slider( $atts, $content = null )
	{
		extract( shortcode_atts( array( 'id' =>'' ), $atts ) );
		if ( ! $id ) return;
		$theme = esc_attr( get_post_meta( $id, '_slide_type', true ) );

		ob_start();
		if ( $theme == 'image-slider-url' ) {
		    require $this->plugin_path . '/templates/url.php';
		} else {
		    require $this->plugin_path . '/templates/gallery.php';
		}
	    $html = ob_get_contents();
	    ob_end_clean();
	    return apply_filters( 'nivo_image_slider', $html, $id );
	}

	/**
	 * A shortcode for rendering the nivo image slider from post type.
	 *
	 * @param  array   $atts  		Shortcode attributes.
	 * @param  string  $content 	The text content for shortcode. Not used.
	 *
	 * @return string  The shortcode output
	 */
	public function all_nivoslider($atts, $content=null){

	    extract(shortcode_atts(array(
	        'id'                => uniqid(),
	        'theme'             =>'default',
	    ), $atts));

	    $args = array(
	    	'posts_per_page' 	=> -1,
	    	'post_type' 		=> 'slider',
	    	'post_status' 		=> 'publish',
	    );
	    $sliders = get_posts( $args );

	    ob_start();

		if ( count($sliders) > 0 ) : ?>
		<div class="slider-wrapper theme-<?php echo $theme; ?>">
			<div id="slider<?php echo $id; ?>" class="nivoSlider">
			<?php
				foreach ($sliders as $slider) :

		        $caption 	= get_post_meta( $slider->ID, '_nivo_image_slider_caption_value', true );
		        $transition = get_post_meta( $slider->ID, '_nivo_image_slider_transition_value', true );
				$slide_link = get_post_meta( $slider->ID, '_nivo_image_slider_slide_link_value', true );
				$slide_link = empty( $slide_link ) ? '#' : $slide_link;
				$link_target = get_post_meta( $slider->ID, '_nivo_image_slider_slide_link_target_value', true );
				$slider_image = wp_get_attachment_image_src( get_post_thumbnail_id( $slider->ID ), 'full' );

				echo sprintf('<a target="%1$s" href="%2$s"><img src="%3$s" data-thumb="%3$s" alt="" title="%4$s" data-transition="%5$s"></a>', $link_target, $slide_link, $slider_image[0], esc_textarea( $caption ), $transition );

				endforeach;
			?>
			</div>
		</div>
		<script>
			jQuery( window ).on("load", function(){
				jQuery("#slider<?php echo $id; ?>").nivoSlider();
			});
		</script>
		<?php
		endif;
	    $html = ob_get_contents();
	    ob_end_clean();
		return $html;
	}

	/**
	 * A shortcode for rendering the nivo image slider from URL.
	 *
	 * @param  array   $atts  		Shortcode attributes.
	 * @param  string  $content 	The text content for shortcode. Not used.
	 *
	 * @return string  The shortcode output
	 */
	public function nivo_slider( $atts, $content = null ) {
	    extract(shortcode_atts(array(
	        'theme'     => 'default',
	        'id'        => uniqid(),
	    ), $atts));

	    $theme = in_array($theme, array('default', 'dark', 'light', 'bar', 'smoothness')) ? $theme : 'default';
	    ob_start();
	    ?>
			<div class="slider-wrapper theme-<?php echo $theme; ?>">
				<div id="<?php echo $id; ?>" class="nivoSlider">
					<?php echo do_shortcode($content); ?>
				</div>
			</div>
			<script type="text/javascript">
				jQuery(window).on("load", function(){
					jQuery("#<?php echo $id; ?>").nivoSlider()
				});
			</script>
	    <?php
	    $html = ob_get_contents();
	    ob_end_clean();

	    return $html;
	}

	/**
	 * A shortcode for rendering the nivo image slider from URL.
	 *
	 * @param  array   $atts  		Shortcode attributes.
	 * @param  string  $content 	The text content for shortcode. Not used.
	 *
	 * @return string  The shortcode output
	 */
	public function nivoslides( $atts, $content = null ) {
	    extract(shortcode_atts(array(
	        'image_link'    =>'',
	        'caption'       =>'',
	        'alt'           =>'',
	    ), $atts));

	    $caption 	= esc_attr($caption);
	    $src 		= esc_url($image_link);
	    $alt 		= esc_attr($alt);

	    if ( empty($src) ) {
	    	return;
	    }
	    return sprintf('<img src="%1$s" alt="%2$s" title="%3$s" />', $src, $alt, $caption);
	}
}

endif;