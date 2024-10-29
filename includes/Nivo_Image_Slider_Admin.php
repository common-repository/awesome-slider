<?php

if( ! class_exists('Nivo_Image_Slider_Admin') ):

class Nivo_Image_Slider_Admin
{
	public function __construct()
	{
		if (is_admin()) {
			add_action( 'init', array ($this, 'post_type') );
			add_action( 'add_meta_boxes', array( $this, 'meta_box' ) );
	        add_filter( 'post_row_actions', array( $this, 'post_row_actions'), 10, 2 );
			add_filter( 'manage_edit-nivo_image_slider_columns', array( $this, 'columns_head') );
			add_filter( 'manage_nivo_image_slider_posts_custom_column', array( $this, 'columns_content'), 10, 2 );
		}
	}

	/**
	 * Register a slide post type.
	 * @package nivo-image-slider
	 * @link http://codex.wordpress.org/Function_Reference/register_post_type
	 */
	public static function post_type() {

		$labels = array(
			'name'                => _x( 'Sliders', 'Post Type General Name', 'nivo-image-slider' ),
			'singular_name'       => _x( 'Slider', 'Post Type Singular Name', 'nivo-image-slider' ),
			'menu_name'           => __( 'Nivo Slider', 'nivo-image-slider' ),
			'name_admin_bar'      => __( 'Nivo Slider', 'nivo-image-slider' ),
			'parent_item_colon'   => __( 'Parent Slider:', 'nivo-image-slider' ),
			'all_items'           => __( 'All Sliders', 'nivo-image-slider' ),
			'add_new_item'        => __( 'Add New Slider', 'nivo-image-slider' ),
			'add_new'             => __( 'Add New', 'nivo-image-slider' ),
			'new_item'            => __( 'New Slider', 'nivo-image-slider' ),
			'edit_item'           => __( 'Edit Slider', 'nivo-image-slider' ),
			'update_item'         => __( 'Update Slider', 'nivo-image-slider' ),
			'view_item'           => __( 'View Slider', 'nivo-image-slider' ),
			'search_items'        => __( 'Search Slider', 'nivo-image-slider' ),
			'not_found'           => __( 'Not found', 'nivo-image-slider' ),
			'not_found_in_trash'  => __( 'Not found in Trash', 'nivo-image-slider' ),
		);
		$args = array(
			'label'               => __( 'Slider', 'nivo-image-slider' ),
			'description'         => __( 'Create slide for your site', 'nivo-image-slider' ),
			'labels'              => $labels,
			'supports'            => array( 'title' ),
			'hierarchical'        => false,
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 35,
			'menu_icon'           => 'dashicons-images-alt2',
			'show_in_admin_bar'   => false,
			'show_in_nav_menus'   => false,
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => true,
			'rewrite'             => false,
			'capability_type'     => 'post',
		);
		register_post_type( 'nivo_image_slider', $args );
	}

	public function meta_box()
	{
		$meta_box = array(
		    'id' => 'nivo_image_slider_metabox',
		    'title' => __('Nivo Image Slider', 'nivo-image-slider'),
		    'description' => sprintf(
		    	__('To use this slider in your posts or pages use the following shortcode: %s', 'nivo-image-slider'),
		    	'<input type="text" onmousedown="this.clicked = 1;" onfocus="if (!this.clicked) this.select(); else this.clicked = 2;" onclick="if (this.clicked == 2) this.select(); this.clicked = 0;" value=\'[nivo_image_slider id="'.get_the_ID().'"]\' style="background-color: #f1f1f1; width: 100%; padding: 8px;" />'
		    ),
		    'screen' => 'nivo_image_slider',
		    'context' => 'normal',
		    'priority' => 'high',
		    'fields' => array(
		        array(
		            'name' => __('Slide Type', 'nivo-image-slider'),
		            'desc' => __('Choose slide type. If you are going to use images only from media library, choose "Slider from Media Library". Or if you want to use image from cross site or want to mix image with media library and cross site, choose "Slider from URL"', 'nivo-image-slider'),
		            'id' => '_slide_type',
		            'type' => 'select',
		            'std' => 'image-slider-gallery',
		            'options' => array(
		            	'image-slider-gallery' 	=> __('Slider from Media Library', 'nivo-image-slider'),
		            	'image-slider-url' 		=> __('Slider from URL', 'nivo-image-slider'),
		            )
		        ),
		        array(
		            'name' 		=> __('Images from URLs', 'nivo-image-slider'),
		            'desc' 		=> sprintf('%s<br>%s<br>http://lorempixel.com/900/300/sports/1,http://lorempixel.com/900/300/sports/2,http://lorempixel.com/900/300/sports/3,http://lorempixel.com/900/300/sports/5', __('Choose images from gallery by pressing Browse button.(Hold Ctrl button and click on images for sellecting multiple images) or', 'nivo-image-slider'), __('Enter images URLs separating by comma. e.g. ', 'nivo-image-slider')),
		            'id' 		=> '_images_url',
		            'type' 		=> 'file',
		            'multiple' 	=> true,
		        ),
		        array(
		            'name' 	=> __('Images from Media', 'nivo-image-slider'),
		            'desc' 	=> __('Choose slider images.', 'nivo-image-slider'),
		            'id' 	=> '_images_ids',
		            'type' 	=> 'images',
		            'std' 	=> __('Upload Images', 'nivo-image-slider')
		        ),
		        array(
		            'name' => __('Slider Image Size', 'nivo-image-slider'),
		            'desc' => __('Select image size from available image size. Use full for original image size.', 'nivo-image-slider'),
		            'id' => '_image_size',
		            'type' => 'select',
		            'std' => 'full',
		            'options' => $this->available_img_size()
		        ),
		        array(
		            'name' => __('Slider Theme', 'nivo-image-slider'),
		            'desc' => __('Use a pre-built theme. To use your own theme select "None".', 'nivo-image-slider'),
		            'id' => '_theme',
		            'type' => 'select',
		            'std' => 'smooth',
		            'options' => array(
		            	'custom' 	=> __('None', 'nivo-image-slider'),
		            	'default' 	=> __('Default', 'nivo-image-slider'),
		            	'light' 	=> __('Light', 'nivo-image-slider'),
		            	'dark' 		=> __('Dark', 'nivo-image-slider'),
		            	'bar' 		=> __('Bar', 'nivo-image-slider'),
		            	'smooth' 	=> __('Smooth', 'nivo-image-slider'),
		            )
		        ),
		        array(
		            'name' 		=> __('Transition Effect', 'nivo-image-slider'),
		            'desc' 		=> __('Select transition for for this slide.', 'nivo-image-slider'),
		            'id' 		=> '_effect',
		            'type' 		=> 'select',
		            'std' 		=> 'random',
		            'multiple' 	=> true,
		            'options' 	=> array(
		            	'random' 			=> __('random', 'nivo-image-slider'),
		            	'sliceDown' 		=> __('sliceDown', 'nivo-image-slider'),
		            	'sliceDownLeft' 	=> __('sliceDownLeft', 'nivo-image-slider'),
		            	'sliceUp' 			=> __('sliceUp', 'nivo-image-slider'),
		            	'sliceUpLeft' 		=> __('sliceUpLeft', 'nivo-image-slider'),
		            	'sliceUpDown' 		=> __('sliceUpDown', 'nivo-image-slider'),
		            	'sliceUpDownLeft' 	=> __('sliceUpDownLeft', 'nivo-image-slider'),
		            	'fold' 				=> __('fold', 'nivo-image-slider'),
		            	'fade' 				=> __('fade', 'nivo-image-slider'),
		            	'slideInRight' 		=> __('slideInRight', 'nivo-image-slider'),
		            	'slideInLeft' 		=> __('slideInLeft', 'nivo-image-slider'),
		            	'boxRandom' 		=> __('boxRandom', 'nivo-image-slider'),
		            	'boxRain' 			=> __('boxRain', 'nivo-image-slider'),
		            	'boxRainReverse' 	=> __('boxRainReverse', 'nivo-image-slider'),
		            	'boxRainGrow' 		=> __('boxRainGrow', 'nivo-image-slider'),
		            	'boxRainGrowReverse'	=> __('boxRainGrowReverse', 'nivo-image-slider')
		            )
		        ),
		        array(
		            'name' => __('Slices', 'nivo-image-slider'),
		            'desc' => __('The number of slices to use in the "Slice" transitions (eg 15)', 'nivo-image-slider'),
		            'id' => '_slices',
		            'type' => 'text',
		            'std' => '15'
		        ),
		        array(
		            'name' => __('Box Cols', 'nivo-image-slider'),
		            'desc' => __('The number of columns to use in the "Box" transitions (eg 8)', 'nivo-image-slider'),
		            'id' => '_box_cols',
		            'type' => 'text',
		            'std' => '8'
		        ),
		        array(
		            'name' => __('boxRows', 'nivo-image-slider'),
		            'desc' => __('The number of rows to use in the "Box" transitions (eg 4)', 'nivo-image-slider'),
		            'id' => '_box_rows',
		            'type' => 'text',
		            'std' => '4'
		        ),
		        array(
		            'name' => __('Animation Speed', 'nivo-image-slider'),
		            'desc' => __('The speed of the transition animation in milliseconds (eg 500)', 'nivo-image-slider'),
		            'id' => '_anim_speed',
		            'type' => 'text',
		            'std' => '500'
		        ),
		        array(
		            'name' => __('Pause Time', 'nivo-image-slider'),
		            'desc' => __('The amount of time to show each slide in milliseconds (eg 3000)', 'nivo-image-slider'),
		            'id' => '_pause_time',
		            'type' => 'text',
		            'std' => '3000'
		        ),
		        array(
		            'name' => __('Start Slide', 'nivo-image-slider'),
		            'desc' => __('Set which slide the slider starts from (zero based index: usually 0)', 'nivo-image-slider'),
		            'id' => '_start_slide',
		            'type' => 'text',
		            'std' => '0'
		        ),
		        array(
		            'name' => __('Enable Direction Navigation', 'nivo-image-slider'),
		            'desc' => __('Prev & Next arrows', 'nivo-image-slider'),
		            'id' => '_direction_nav',
		            'type' => 'checkbox',
		            'std' => true
		        ),
		        array(
		            'name' 	=> __('Enable Control Navigation', 'nivo-image-slider'),
		            'desc' 	=> __('eg 1,2,3... navigation', 'nivo-image-slider'),
		            'id' 	=> '_control_nav',
		            'type' 	=> 'checkbox',
		            'std' 	=> true
		        ),
		        array(
		            'name' 	=> __('Enable Thumbnail Navigation', 'nivo-image-slider'),
		            'desc' 	=> 'Use thumbnails for Control Nav',
		            'id' 	=> '_control_nav_thumbs',
		            'type' 	=> 'checkbox',
		            'std' 	=> ''
		        ),
		        array(
		            'name' 	=> __('Pause the Slider on Hover', 'nivo-image-slider'),
		            'desc' 	=> 'Stop animation while hovering',
		            'id' 	=> '_pause_on_hover',
		            'type' 	=> 'checkbox',
		            'std' 	=> 'on'
		        ),
		        array(
		            'name' 	=> __('Manual Transitions', 'nivo-image-slider'),
		            'desc' 	=> __('Slider is always paused', 'nivo-image-slider'),
		            'id' 	=> '_manual_advance',
		            'type' 	=> 'checkbox',
		            'std' 	=> 'off'
		        ),
		        array(
		            'name' 	=> __('Random Start', 'nivo-image-slider'),
		            'desc' 	=> __('Start on a random slide', 'nivo-image-slider'),
		            'id' 	=> '_random_start',
		            'type' 	=> 'checkbox',
		            'std' 	=> 'off'
		        ),
		    )
		);
		$nivoSlideMeta = new Nivo_Image_Slider_Meta_Box();
		$nivoSlideMeta->add($meta_box);
	}

	private function available_img_size()
	{
		global $_wp_additional_image_sizes;

		$sizes = array();

	    foreach ( get_intermediate_image_sizes() as $_size ) {
			if ( in_array( $_size, array('thumbnail', 'medium', 'medium_large', 'large') ) ) {

				$width 		= get_option( "{$_size}_size_w" );
				$height 	= get_option( "{$_size}_size_h" );
				$crop 		= (bool) get_option( "{$_size}_crop" ) ? 'hard' : 'soft';

				$sizes[$_size]   = "{$_size} - {$width}x{$height}";

			} elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {

				$width 		= $_wp_additional_image_sizes[ $_size ]['width'];
				$height 	= $_wp_additional_image_sizes[ $_size ]['height'];
				$crop 		= $_wp_additional_image_sizes[ $_size ]['crop'] ? 'hard' : 'soft';

				$sizes[$_size]   = "{$_size} - {$width}x{$height}";
			}
		}

		$sizes = array_merge($sizes, array('full' => __('original uploaded image', 'nivo-image-slider')));

	    return $sizes;
	}

	public function post_row_actions( $actions, $post )
	{
		global $current_screen;
	    if( $current_screen->post_type != 'nivo_image_slider' ){
			return $actions;
	    }

	    unset( $actions['view'] );
	    unset( $actions['inline hide-if-no-js'] );
		return $actions;
	}

	public function columns_head(){
	    
	    $columns = array(
	        'cb' 			=> '<input type="checkbox">',
	        'title' 		=> __('Slide Title', 'nivo-image-slider'),
	        'usage' 		=> __('Shortcode', 'nivo-image-slider'),
	    );

	    return $columns;

	}

	public function columns_content($column, $post_id) {
	    switch ($column) {

	        case 'usage':
	            ?>
					<input
						type="text"
						onmousedown="this.clicked = 1;"
						onfocus="if (!this.clicked) this.select(); else this.clicked = 2;"
						onclick="if (this.clicked == 2) this.select(); this.clicked = 0;"
						value="[nivo_image_slider id='<?php echo $post_id; ?>']"
						style="background-color: #f1f1f1;font-family: monospace;min-width: 250px;padding: 5px 8px;"
					>
	            <?php
	            break;
	        default :
	            break;
	    }
	}
}

endif;