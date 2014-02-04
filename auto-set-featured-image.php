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
 * Code artfully lifted from:
 * https://www.gavick.com/magazine/wordpress-quick-tip-4-automatically-set-the-first-post-image-as-a-featured-image.html
 *
 */

function auto_featured_image() {
    global $post;

    if ( !has_post_thumbnail( $post->ID ) ) {
        $attached_image = get_children( "post_parent=$post->ID&post_type=attachment&post_mime_type=image&numberposts=1" );

		if ( $attached_image ) {
			foreach ( $attached_image as $attachment_id => $attachment ) {
				set_post_thumbnail ($post->ID, $attachment_i );
			}
		}
	}
}

add_action( 'the_post', 'auto_featured_image' );