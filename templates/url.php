<?php
$theme    		= esc_attr( get_post_meta( $id, '_theme', true ) );
$images_url   	= explode(',', get_post_meta( $id, '_images_url', true) );
?>

<?php if( count($images_url) > 0 ): ?>

<div class="nivo-image-slider">
	<div class="slider-wrapper theme-<?php echo $theme; ?>">
		<div id="ID-<?php echo $id; ?>" class="nivoSlider">
			<?php
			foreach ( $images_url as $url ) {
				if ( filter_var($url, FILTER_VALIDATE_URL) ) {
					echo sprintf('<img src="%s" />', esc_url($url));
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