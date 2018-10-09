<?php 
	/*
	Plugin Name: Uploader media library
	Plugin URI: 
	Description: Media library testy z metaboxem
	Version: 1.0
	Author: Rafał Herszkowicz
	Author URI:
	*/

// Register admin scripts for custom fields
function spgallery_load_wp_admin_style() {
        wp_enqueue_media();
        wp_enqueue_style( 'dashicons' );
	    wp_enqueue_script('media-upload');
	    wp_enqueue_style( 'spgallery', plugin_dir_url(dirname(__FILE__)) . 'spgallery/spgallery.css' ); 
        // admin always last
        wp_enqueue_script( 'shift8_portfolio_admin_script', plugin_dir_url(dirname(__FILE__)) . 'spgallery/spgallery.js' );
}
add_action( 'admin_enqueue_scripts', 'spgallery_load_wp_admin_style' );



//Dodanie metaboxa
function spgallery_add_custom_meta_box() {
    add_meta_box(
        'spgallery_media', // $id
        'Galeria zdjęć', // $title
        'spgallery_show_custom_meta_box', // $callback
        'page', // $page
        'normal', // $context
        'high'); // $priority
}
add_action('add_meta_boxes', 'spgallery_add_custom_meta_box');



function spgallery_show_custom_meta_box() {

		global $post;
		$savedImgMetabox = get_post_meta( $post->ID, 'spgallery_media', true );
		?>


				<div>
					<input type="hidden" class="large-text" name="spgallery_media" id="spgallery_media_ids" value="<?php echo esc_attr( $savedImgMetabox ); ?>"><br>
					<div id="sgallery-thumb">
						<ul>
						<?php
							$imgArr = explode(",", $savedImgMetabox);
								if (is_array($imgArr)){
									foreach($imgArr as $img){
									$imgURL = wp_get_attachment_image_src($img, 'thumbnail');
									echo '<li><img  id="thumb_' .$img. '"src="' . $imgURL[0] .'"></li>';
								}
							}
						?>
						</ul>
					</div>

					<button type="button" class="button" id="spgallery_upload_button"  ><?php _e( 'Wczytaj galerię' )?></button>
				</div>


			

		<?php
		wp_nonce_field( 'spgallery_form_metabox_nonce', 'spgallery_form_metabox_process' );
    
}

function spgallery_save_custom_meta($post_id) {
	// Verify nonce
	if (isset($_POST['at_nonce']) && !wp_verify_nonce($_POST['spgallery_media_nonce'], basename(__FILE__)))
	        return $post_id;
	// Check autosave
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return $post_id;
	// Check permissions
	if (isset($_POST['post_type']) && 'page' == $_POST['post_type']) {
	        if (!current_user_can('edit_page', $post_id))
                return $post_id;
	} elseif (!current_user_can('edit_post', $post_id)) {
        return $post_id;
	}

	if (isset($_POST['spgallery_media'])) {
		$old = get_post_meta( $post_id, 'spgallery_media', true );
	
			$new = $_POST['spgallery_media'];
		if ( $new && $new !== $old ) {
				print("<b>ID POSTA Update" . $post_id . "</b>");
			update_post_meta( $post_id, 'spgallery_media', $new );
		} elseif ( '' === $new && $old ) {
			delete_post_meta( $post_id, 'spgallery_media', $old );
		}
	}
}

add_action('save_post', 'spgallery_save_custom_meta');

?>