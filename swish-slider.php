<?php
/*
Plugin Name:  Swish Slider (LITE)
Plugin URI:   https://southdevondigital.com/plugins/swish-slider
Description:  A lightweight, responsive, mobile friendly WordPress slider with touch/swipe support, overlays, multiple themes & options. <a href="https://southdevondigital.com" target="_blank">Upgrade to the PRO version</a> for more features & customisation options, full documentation & support directly from the developer.
Version:      1.0
Author:       South Devon Digital
Author URI:   https://southdevondigital.com
Text Domain:  sdd-sns
*/

// Register the Swish cpt
function sns_register_post_type() {
	$labels = array(
		'name'               => _x( 'Sliders', 'post type general name', 'sdd-sns' ),
		'singular_name'      => _x( 'Slider', 'post type singular name', 'sdd-sns' ),
		'menu_name'          => _x( 'Swish Slider (LITE)', 'admin menu', 'sdd-sns' ),
		'name_admin_bar'     => _x( 'Slider', 'add new on admin bar', 'sdd-sns' ),
		'add_new'            => _x( 'Add New', 'Slider', 'sdd-sns' ),
		'add_new_item'       => __( 'Add New Slider', 'sdd-sns' ),
		'new_item'           => __( 'New Slider', 'sdd-sns' ),
		'edit_item'          => __( 'Edit Slider', 'sdd-sns' ),
		'view_item'          => __( 'View Slider', 'sdd-sns' ),
		'all_items'          => __( 'All Sliders', 'sdd-sns' ),
		'search_items'       => __( 'Search Sliders', 'sdd-sns' ),
		'parent_item_colon'  => __( 'Parent Sliders:', 'sdd-sns' ),
		'not_found'          => __( 'No Sliders found.', 'sdd-sns' ),
		'not_found_in_trash' => __( 'No Sliders found in Trash.', 'sdd-sns' )
	);

	$args = array(
		'labels'             => $labels,
		'description'        => __( 'Description.', 'sdd-sns' ),
		'public'             => false,
		'publicly_queryable' => false,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'swish-slider' ),
		'capability_type'    => 'page',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
    'menu_icon'          => 'dashicons-images-alt2',
		'supports'           => array( 'title' )
	);

	register_post_type( 'swish-slider', $args );
}
add_action( 'init', 'sns_register_post_type' );

// Build the sns meta box
function sns_build_meta_box( $post ){

	wp_nonce_field( basename( __FILE__ ), 'sns_meta_box_nonce' );

	$admin_html = get_post_meta( $post->ID, 'sns_admin__html', true );
	$output_html = get_post_meta( $post->ID, 'sns_output__html', true );

	?>
  <textarea name="sns-admin-html" id="sns-admin-html" style="display: none;"><?php echo esc_html($admin_html); ?></textarea>
  <textarea name="sns-output-html" id="sns-output-html" style="display: none;"><?php echo esc_html($output_html); ?></textarea>
  <div class="sns-overlay">
    <div class="sns-overlay-bg"></div>
    <div class="sns-dialog">
      <span class="sns-dialog-close dashicons dashicons-no"></span>
      <h1 class="sns-dialog-title">Are you sure you want to delete this image?</h1>
      <div class="sns-dialog-message">Tip: you can skip this dialog by holding the <em>alt</em> key when deleting images</div>
      <div class="sns-dialog-accept sns-dialog-btn"><span class="dashicons dashicons-yes"></span> Yes</div>
      <div class="sns-dialog-deny sns-dialog-btn"><span class="dashicons dashicons-no-alt"></span> No</div>
    </div>
  </div>
	<div class="sns-upload-link" style="display: none;">
		<?php
			$upload_link = esc_url( get_upload_iframe_src( 'image', $post->ID ) );
			echo $upload_link;
		?>
	</div>
  <div class="sns-builder-wrapper">

  </div>
  <div class="sns-builder-bottom-toolbar">
    <div class="sns-no-images-tip">
			Click here to get started!
		</div>
    <div class="sns-add-slider-image"><span class="dashicons dashicons-plus-alt"></span>Add a slider image</div>
  </div>
	<?php
}

// Slider options metabox
function sns_build_side_meta_box( $post ){
	$style = get_post_meta( $post->ID, 'sns_style', true );
	$arrows = get_post_meta( $post->ID, 'sns_arrows', true );
	$dots = get_post_meta( $post->ID, 'sns_dots', true );
	$fade = get_post_meta( $post->ID, 'sns_fade', true );
	$loop = get_post_meta( $post->ID, 'sns_loop', true );
	$overlays = get_post_meta( $post->ID, 'sns_overlays', true );
	$lazyload = get_post_meta( $post->ID, 'sns_lazy_load', true );
	$autoplay = get_post_meta( $post->ID, 'sns_autoplay', true );
	$swipe = get_post_meta( $post->ID, 'sns_swipe', true );
	$autoplaySpeed = get_post_meta( $post->ID, 'sns_autoplay_speed', true );
	if ( empty($autoplaySpeed) ) {
		$autoplaySpeed = 3000;
	}
	$numSlides = get_post_meta( $post->ID, 'sns_num_slides', true );
	if ( empty($numSlides) ) {
		$numSlides = 1;
	}
	$sliderSpeed = get_post_meta( $post->ID, 'sns_slider_speed', true );
	if ( empty($sliderSpeed) ) {
		$sliderSpeed = 500;
	}
	$shortcode = get_post_meta( $post->ID, 'sns_shortcode', true );
	if ( empty($shortcode) ) {
		$shortcode = '[swish-slider id="'. get_the_ID() . '"]';
	}
	?>
	<input id="sns-post-id" type="text" value="<?php echo get_the_ID() ?>" />
	<label for="sns-style-select">Style</label>
	<br />
	<select name="sns-style-select" id="sns-style-select">
		<option value="default" <?php if($style=="default") echo selected; ?>>Default</option>
		<option value="dark" <?php if($style=="dark") echo selected; ?>>Dark</option>
		<option value="blue" <?php if($style=="blue") echo selected; ?> disabled>Blue</option>
		<option value="wide-light" <?php if($style=="wide-light") echo selected; ?> disabled>Wide (Light)</option>
		<option value="wide-dark" <?php if($style=="wide-dark") echo selected; ?> disabled>Wide (Dark)</option>
		<option value="minimal-light" <?php if($style=="minimal-light") echo selected; ?> disabled>Minimal (Light)</option>
		<option value="minimal-dark" <?php if($style=="minimal-dark") echo selected; ?> disabled>Minimal (Dark)</option>
	</select>
	<br />
	<label for="sns-arrows-toggle">Navigation Arrows</label>
	<br />
	<select name="sns-arrows-toggle" id="sns-arrows-toggle">
		<option value="true" <?php if($arrows=="true") echo selected; ?>>Visible</option>
		<option value="false" <?php if($arrows=="false") echo selected; ?>>Hidden</option>
	</select>
	<br />
	<label for="sns-dots-toggle">Pagination Dots</label>
	<br />
	<select name="sns-dots-toggle" id="sns-dots-toggle">
		<option value="true" <?php if($dots=="true") echo selected; ?>>Visible</option>
		<option value="false" <?php if($dots=="false") echo selected; ?>>Hidden</option>
	</select>
	<br />
	<a style="float: right; position: relative; top: 16px;" href="https://www.southdevondigital.com/shop/swish-slider" target="_blank">Upgrade</a>
	<h3 style="display: block; width: auto;">PRO Options</h3>
	<label for="sns-overlays-toggle">Overlays</label>
	<br />
	<select name="sns-overlays-toggle" id="sns-overlays-toggle" disabled>
		<option value="true" <?php if($overlays=="true") echo selected; ?>>Visible</option>
		<option value="false" <?php if($overlays=="false") echo selected; ?>>Hidden</option>
	</select>
	<br />
	<label for="sns-autoplay-toggle">Autoplay</label>
	<br />
	<select name="sns-autoplay-toggle" id="sns-autoplay-toggle" disabled>
		<option value="true" <?php if($loop=="true") echo selected; ?>>True</option>
		<option value="false" <?php if($loop=="false") echo selected; ?>>False</option>
	</select>
	<br />
	<label for="sns-autoplay-speed">Autoplay Speed</label>
	<br />
	<input type="number" id="sns-autoplay-speed" name="sns-autoplay-speed" value="<?php echo $autoplaySpeed ?>" disabled>
	<br />
	<label for="sns-loop-toggle">Infinite Loop</label>
	<br />
	<select name="sns-loop-toggle" id="sns-loop-toggle" disabled>
		<option value="true" <?php if($loop=="true") echo selected; ?>>True</option>
		<option value="false" <?php if($loop=="false") echo selected; ?>>False</option>
	</select>
	<br />
	<label for="sns-slider-speed">Transition Speed</label>
	<br />
	<input type="number" id="sns-slider-speed" name="sns-slider-speed" value="<?php echo $sliderSpeed ?>" disabled>
	<br />
	<label for="sns-swipe-toggle">Swipe</label>
	<br />
	<select name="sns-swipe-toggle" id="sns-swipe-toggle" disabled>
		<option value="true" <?php if($swipe=="true") echo selected; ?>>Enabled</option>
		<option value="false" <?php if($swipe=="false") echo selected; ?>>Disabled</option>
	</select>
	<br />
	<label for="sns-num-slides">Images per slide</label>
	<br />
	<input type="number" id="sns-num-slides" name="sns-num-slides" value="<?php echo $numSlides ?>" disabled>
	<br />
	<label for="sns-fade-toggle">Fade Images</label>
	<br />
	<select name="sns-fade-toggle" id="sns-fade-toggle" disabled>
		<option value="false" <?php if($fade=="false") echo selected; ?>>False</option>
		<option value="true" <?php if($fade=="true") echo selected; ?>>True</option>
	</select>
	<br />
	<label for="sns-lazy-load">Lazy Load</label>
	<br />
	<select name="sns-lazy-load" id="sns-lazy-load" disabled>
		<option value="off" <?php if($lazyload=="off") echo selected; ?>>Off</option>
		<option value="progressive" <?php if($lazyload=="progressive") echo selected; ?>>Progressive</option>
		<option value="ondemand" <?php if($lazyload=="ondemand") echo selected; ?>>On Demand</option>
	</select>
	<br />
	<label for="sns-slider-shortcode">Shortcode</label>
	<br />
	<input readonly="readonly" type="text" class="sns-slider-shortcode" name="sns-slider-shortcode" value='<?php echo $shortcode ?>' />
	<div class="sns-shortcode-message">Copied!</div>
	<span class="sns-copy-shortcode dashicons dashicons-media-default"></span>
	<br />
	<p style="font-style: italic; color: #666;">Tip: You can override the options in the shortcode on individual sliders. See the <a href="https://southdevondigital.com/documentation/swish-slider" target="_blank">documentation</a> for more information.</p>
	<?php
}

// Upgrade meta box
function sns_build_thanks_meta_box( $post ){
	?>
	<p><a class="button button-primary" style="float: right; margin-left: 10px;" href="https://www.southdevondigital.com" target="_blank">Upgrade</a>Please consider upgrading to the PRO version to access all the features & customisation options listed above, and support further development of this plugin & <a href="https://southdevondigital.com/shop" target="_blank">more</a>.</p>
	<hr />
	<p>Follow us on Facebook for updates about our latest offers, plugins, products & services.</p>
	<div id="fb-root"></div>
	<script>(function(d, s, id) {
	  var js, fjs = d.getElementsByTagName(s)[0];
	  if (d.getElementById(id)) return;
	  js = d.createElement(s); js.id = id;
	  js.src = 'https://connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v3.0&appId=759596830864125&autoLogAppEvents=1';
	  fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));</script>
	<div class="fb-page" data-href="https://www.facebook.com/SouthDevonDigital/" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="false">
		<blockquote cite="https://www.facebook.com/SouthDevonDigital/" class="fb-xfbml-parse-ignore">
			<a href="https://www.facebook.com/SouthDevonDigital/">South Devon Digital</a>
		</blockquote>
	</div>
	<?php
}

// add the sns builder meta boxes
function sns_add_meta_boxes( $post ){
	add_meta_box('sns-content', 'Slider Content', 'sns_build_meta_box', 'swish-slider', 'normal');
	add_meta_box('sns-side', 'Slider Options', 'sns_build_side_meta_box', 'swish-slider', 'side');
	add_meta_box('sns-thanks', 'Please consider upgrading!', 'sns_build_thanks_meta_box', 'swish-slider', 'side');
}
add_action( 'add_meta_boxes', 'sns_add_meta_boxes' );

// Save the values
function sns_save_meta_box_data( $post_id ){

  // verify meta box nonce
	if ( !isset( $_POST['sns_meta_box_nonce'] ) || !wp_verify_nonce( $_POST['sns_meta_box_nonce'], basename( __FILE__ ) ) ) {
		return;
	}

	// return if autosave
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ){
		return;
	}

	// Check the user's permissions.
	if ( ! current_user_can( 'edit_post', $post_id ) ){
		return;
	}
	
	$allowedAdminTags = array(
													'div' => array(
																				 'class'=>array()
																				),
													'span' => array(
																					'class'=>array()
																					),
													'label' => array(
																					'for' => array()
																					),
													'h3' => array(
																					'class'=>array()
																				),
													'br' => array(),
													'input' => array(
																					'type' => array(),
																					'name' => array(),
																					'value' => array(),
																					'class' => array(),
																					'placeholder' => array(),
																					),
													'a' => array(
																			'class' => array(),
																			'href' => array(),
																			),
													'select' => array(
																						'type' => array(),
																						'class' => array(),
																						'name' => array(),
																						),
													'option' => array(
																						'value' => array(),
																						'selected' => array(),
																						),
													'textarea' => array(
																						'type' => array(),
																						'class' => array(),
																						'name' => array(),
																						),
													);
	
	if ( isset( $_REQUEST['sns-admin-html'] ) ) {
		update_post_meta( $post_id, 'sns_admin__html', $_POST['sns-admin-html'].wp_kses($str,$allowedAdminTags) );
	}
	
	$allowedOutputTags = array(
														'div' => array(
																					 'class' => array(),
																					),
														'a' => array(
																				'class' => array(),
																				'href' => array(),
																				'target' => array(),
																				),
														'img' => array(
																					'src' => array(),
																					'alt' => array(),
																					),
														);
	
	if ( isset( $_REQUEST['sns-output-html'] ) ) {
		update_post_meta( $post_id, 'sns_output__html', $_POST['sns-output-html'].wp_kses($str,$allowedAdminTags) );
	}

	if ( isset( $_REQUEST['sns-style-select'] ) ) {
		if ( $_REQUEST['sns-style-select'] == "default" || $_REQUEST['sns-style-select'] == "dark") {
			update_post_meta( $post_id, 'sns_style', wp_unslash( $_POST['sns-style-select'] ));
		}
	}

	if ( isset( $_REQUEST['sns-arrows-toggle'] ) ) {
		if ( $_REQUEST['sns-style-select'] == "true" || $_REQUEST['sns-style-select'] == "false" ) {
			update_post_meta( $post_id, 'sns_arrows', wp_unslash( $_POST['sns-arrows-toggle'] ));
		}
	}

	if ( isset( $_REQUEST['sns-dots-toggle'] ) ) {
		if ( $_REQUEST['sns-dots-toggle'] == "true" || $_REQUEST['sns-dots-toggle'] == "false" ) {
			update_post_meta( $post_id, 'sns_dots', wp_unslash( $_POST['sns-dots-toggle'] ));
		}
	}

	if ( isset( $_REQUEST['sns-slider-shortcode'] ) ) {
		update_post_meta( $post_id, 'sns_shortcode', sanitize_text_field( $_POST['sns-slider-shortcode'] ));
	}

}
add_action( 'save_post', 'sns_save_meta_box_data' );

/* Load the scripts on the mbc admin pages */
function sns_load_admin_scripts(){
		global $post_type;
		global $pagenow;
    if( 'swish-slider' == $post_type && $pagenow == 'post.php' || 'swish-slider' == $post_type && $pagenow == 'post-new.php' ) {
			wp_enqueue_media();
			wp_enqueue_style( 'mbc-admin-styles', plugins_url('/styles/admin-styles.css', __FILE__) );
			wp_enqueue_script( 'mbc-admin-script', plugins_url('/scripts/admin-script.js', __FILE__), 'jquery' );
		}
}
add_action('admin_enqueue_scripts', 'sns_load_admin_scripts');

// sns shortcode
function sns_shortcode( $attributes ) {
	global $post;

	extract( shortcode_atts( array(
		'id' => '',
		'style' => '',
		'arrows' => '',
		'dots' => '',
	), $attributes ) );

	if ( !$id ) return;
	$sliderhtml = get_post_meta( $id, 'sns_output__html', true );
	if ( $sliderhtml ) {
		wp_enqueue_script( 'slick-js', plugins_url('/incs/slick/slick.min.js', __FILE__), 'jQuery' );
		wp_enqueue_style( 'slick-css',  plugins_url('/incs/slick/slick.min.css', __FILE__) );
		wp_enqueue_style( 'slick-theme-css',  plugins_url('/incs/slick/slick-theme.min.css', __FILE__) );
		if ($overlays == 'false') {
			wp_enqueue_style( 'hide-overlays',  plugins_url('/styles/hide-overlays.css', __FILE__) );
		}
		
		$slickScript = 'jQuery(function($){' .
										  '$(".sns-wrapper").slick({' .
										    ' dots: ';
												if ($dots) {
													$slickScript .= $dots . ',';
												} else {
													$slickScript .= 'true,';
												}
				$slickScript .= ' arrows: ';
												if ($arrows) {
													$slickScript .= $arrows . ',';
												} else {
													$slickScript .= 'true,';
												}
				$slickScript .= ' adaptiveHeight: true' .
										  '});' .
										'});';
		wp_add_inline_script('slick-js',$slickScript);
		
		wp_enqueue_style( 'sns-front-end',  plugins_url('/styles/front-end-styles.css', __FILE__) );
		if ($style != 'default') {
			wp_enqueue_style( 'sns-theme',  plugins_url('/styles/theme-' . $style . '.css', __FILE__) );
		}
		if ($arrows == 'false') {
			wp_enqueue_style( 'hide-arrows',  plugins_url('/styles/hide-arrows.css', __FILE__) );
		}
		if ($dots == 'false') {
			wp_enqueue_style( 'hide-dots',  plugins_url('/styles/hide-dots.css', __FILE__) );
		}
		$output = wp_kses_post('<div class="sns-wrapper sns-style-'. $style .'">'. $sliderhtml .'</div>');
		return balanceTags($output,true);
	}
}
add_shortcode( 'swish-slider', 'sns_shortcode' );

?>
