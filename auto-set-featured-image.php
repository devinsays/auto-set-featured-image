<?php
/**
 * Auto Set Featured Image
 *
 * @wordpress-plugin
 * Plugin Name: Auto Set Featured Image
 * Plugin URI:  http://wptheming.com/
 * Description: Automatically sets a featured image for the post if an image is attached.  Plugin should be enabled once and then deactivated.
 * Version:     0.1.0
 * Author:      Devin Price
 * Author URI:  http://www.wptheming.com/
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

/*
 * Sets the featured image if there is an image attached to the post.
 *
 * https://www.gavick.com/magazine/wordpress-quick-tip-4-automatically-set-the-first-post-image-as-a-featured-image.html
 *
 */

function asfi_auto_featured_image() {

    global $post;

    if ( has_post_thumbnail( $post->ID ) )
    	return;

	// Old themes would sometimes store a featured image url in a custom field
	// This section checks if a custom field is set for 'FeaturedImage'
	// If so, it fetches the image ID from the URL and sets this as the featured image
    $featured_image_meta = get_post_meta( $post->ID, 'FeaturedImage', true );
    if ( $featured_image_meta ) {
	    $featured_image_id = asfi_get_attachment_id_from_url( $featured_image_meta );
	    if ( $featured_image_id ) {
	    	set_post_thumbnail ( $post->ID, $featured_image_id );
	    }
		return;
    }

    $attached_images = get_children( "post_parent=$post->ID&post_type=attachment&post_mime_type=image&numberposts=1" );
	if ( $attached_images ) {
		// Sets the first attached image as the featured image
		$img = ( current((array)$attached_images)->ID );
		set_post_thumbnail ( $post->ID, $img );
	}
}

add_action( 'the_post', 'asfi_auto_featured_image' );

/*
 * Function returns the image ID from the URL.
 * Quite expensive, so run with care.
 *
 * http://philipnewcomer.net/2012/11/get-the-attachment-id-from-an-image-url-in-wordpress/
 *
 */
function asfi_get_attachment_id_from_url( $attachment_url = '' ) {

	global $wpdb;
	$attachment_id = false;

	// If there is no url, return.
	if ( '' == $attachment_url )
	    return;

	// Get the upload directory paths
	$upload_dir_paths = wp_upload_dir();

	// Make sure the upload path base directory exists in the attachment URL, to verify that we're working with a media library image
	if ( false !== strpos( $attachment_url, $upload_dir_paths['baseurl'] ) ) {

	    // If this is the URL of an auto-generated thumbnail, get the URL of the original image
	    $attachment_url = preg_replace( '/-\d+x\d+(?=\.(jpg|jpeg|png|gif)$)/i', '', $attachment_url );

	    // Remove the upload path base directory from the attachment URL
	    $attachment_url = str_replace( $upload_dir_paths['baseurl'] . '/', '', $attachment_url );

	    // Run a custom database query to get the attachment ID from the modified attachment URL
	    $attachment_id = $wpdb->get_var( $wpdb->prepare( "SELECT wposts.ID FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta WHERE wposts.ID = wpostmeta.post_id AND wpostmeta.meta_key = '_wp_attached_file' AND wpostmeta.meta_value = '%s' AND wposts.post_type = 'attachment'", $attachment_url ) );

	}
	return $attachment_id;
}