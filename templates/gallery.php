<?php
$img_size    	= esc_attr( get_post_meta( $id, '_image_size', true ) );
$theme    		= esc_attr( get_post_meta( $id, '_theme', true ) );
$image_ids   	= explode(',', get_post_meta( $id, '_images_ids', true) );
?>

<?php if( count($image_ids) > 0 ): ?>

<div class="nivo-image-slider">
	<div class="slider-wrapper theme-<?php echo $theme; ?>">
		<div id="ID-<?php echo $id; ?>" class="nivoSlider">
			<?php
			foreach ( $image_ids as $image )
			{
				if( ! $image ) continue;

				$image_info = get_post( $image );
				$src 		= wp_get_attachment_image_src( $image, $img_size );
				$thumb 		= wp_get_attachment_image_src( $image, array(50, 50) );
				$caption 	= $image_info->post_excerpt ? $image_info->post_excerpt : '';
				$description = $image_info->post_content ? $image_info->post_content : '';
				
				if (!filter_var($description, FILTER_VALIDATE_URL) === false) {

					echo sprintf('<a href="%1$s"><img src="%2$s" width="%3$s" height="%4$s" data-thumb="%5$s" title="%6$s"></a>',
						$description, $src[0], $src[1], $src[2], $thumb[0], $caption
					);

				} else {

					echo sprintf('<img src="%1$s" width="%2$s" height="%3$s" data-thumb="%4$s" title="%5$s">',
						$src[0], $src[1], $src[2], $thumb[0], $caption
					);
				}
			}
			?>
		</div><!-- .nivoSlider -->
	</div><!-- .slider-wrapper -->
</div><!-- .nivo-image-slider -->

<?php endif;

add_action('wp_footer', function() use ( $id ){

	$transition    	= esc_attr( get_post_meta( $id, '_effect', true ) );
	$slices    		= intval( get_post_meta( $id, '_slices', true ) );
	$boxcols    	= intval( get_post_meta( $id, '_box_cols', true ) );
	$boxrows    	= intval( get_post_meta( $id, '_box_rows', true ) );
	$anim_speed    	= intval( get_post_meta( $id, '_anim_speed', true ) );
	$pause_time    	= intval( get_post_meta( $id, '_pause_time', true ) );
	$start    		= intval( get_post_meta( $id, '_start_slide', true ) );
	$dir_nav    	= ( get_post_meta( $id, '_direction_nav', true ) == 'on') ? 'true' : 'false';
	$ctrl_nav    	= ( get_post_meta( $id, '_control_nav', true ) == 'on') ? 'true' : 'false';
	$thumb_nav    	= ( get_post_meta( $id, '_control_nav_thumbs', true ) == 'on' ) ? 'true' : 'false';
	$hover_pause    = ( get_post_meta( $id, '_pause_on_hover', true ) == 'on') ? 'true' : 'false';
	$transition_man = ( get_post_meta( $id, '_manual_advance', true ) == 'on') ? 'true' : 'false';
	$start_rand    	= ( get_post_meta( $id, '_random_start', true ) == 'on') ? 'true' : 'false';
	?>
	<script type="text/javascript">
		jQuery( window ).on('load', function() {
			jQuery("#ID-<?php echo $id; ?>").nivoSlider({
				effect: "<?php echo $transition; ?>",
				slices: <?php echo $slices; ?>,
				boxCols: <?php echo $boxcols; ?>,
				boxRows: <?php echo $boxrows; ?>,
				animSpeed: <?php echo $anim_speed; ?>,
				pauseTime: <?php echo $pause_time; ?>,
				startSlide: <?php echo $start; ?>,
				directionNav: <?php echo $dir_nav; ?>,
				controlNav: <?php echo $ctrl_nav; ?>,
				controlNavThumbs: <?php echo $thumb_nav; ?>,
				pauseOnHover: <?php echo $hover_pause; ?>,
				manualAdvance: <?php echo $transition_man; ?>,
				randomStart: <?php echo $start_rand; ?>,
			});
		});
	</script>
	<?php
}, 60);

?>