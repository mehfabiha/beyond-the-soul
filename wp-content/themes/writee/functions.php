<?php 
/*
File Name: functions.php
Description: Core functions file of Writty theme.
Writty theme directories setup

*/


define('WRT_TEMPLATE_DIRECTORY_URI', get_template_directory_uri()); 
define('WRT_INC_DIR', get_template_directory() . '/inc' ); 
define('WRT_IMAGE_URL', WRT_TEMPLATE_DIRECTORY_URI . '/images' );

/*********************************************************/
## Writty theme functions files
/**********************************************************/
/**
 * Writee only works in WordPress 4.5 or later.
 */
if ( version_compare( $GLOBALS['wp_version'], '4.5', '<' ) ) {
	require_once(WRT_INC_DIR.'/functions/back-compat.php');
	return;
}

require_once(WRT_INC_DIR.'/functions/tgm-activation.php');
require_once(WRT_INC_DIR.'/functions/customizer.php');
require_once(WRT_INC_DIR.'/functions/theme-functions.php');
require_once(WRT_INC_DIR.'/functions/navigations.php');
require_once(WRT_INC_DIR.'/functions/sidebars.php');
require_once(WRT_INC_DIR.'/functions/widgets.php');
require_once(WRT_INC_DIR.'/functions/featured-media.php');
require_once(WRT_INC_DIR.'/functions/custom-css-js.php');

if ( ! isset( $content_width ) ) {
	$content_width = 1080;
}
// Hook into WPForms submission action to create a new post
add_action( 'wpforms_process_complete', 'create_post_from_wpforms', 10, 4 );

function create_post_from_wpforms( $fields, $entry, $form_data, $entry_id ) {
    // Replace '123' with your WPForms form ID
    if ( $form_data['id'] == 94 ) {
        
        // Retrieve the values from each field
        $name = ! empty( $fields[1]['value'] ) ? sanitize_text_field( $fields[1]['value'] ) : ''; // Name Field
        $email = ! empty( $fields[2]['value'] ) ? sanitize_email( $fields[2]['value'] ) : ''; // Email Field
        $story_title = ! empty( $fields[3]['value'] ) ? sanitize_text_field( $fields[3]['value'] ) : ''; // Story Title Field
        $story_content = ! empty( $fields[4]['value'] ) ? sanitize_textarea_field( $fields[4]['value'] ) : ''; // Story Content Field

        // Prepare the new post data
        $new_post = array(
            'post_title'   => wp_strip_all_tags( $story_title ),
            'post_content' => $story_content,
            'post_status'  => 'pending', // Set to 'publish' to auto-publish; 'pending' for admin review
            'post_author'  => 1, // You can specify a user ID or leave as 1 for admin
            'post_type'    => 'post',
            'post_category' => array( get_cat_ID( 'Stories/Thoughts' ) ) // Make sure 'Stories' category exists
        );

        // Insert the post into the database
        wp_insert_post( $new_post );
    }
}

?>