<?php

//Prevent direct access to this file
if ( ! defined( 'WPINC' ) ) {
	die();
}

/**
 * Enqueue the Gutenberg block assets for the backend.
 *
 * 'wp-blocks': includes block type registration and related functions.
 * 'wp-element': includes the WordPress Element abstraction for describing the structure of your blocks.
 */
function daextrebl_editor_assets() {

    $shared = daextrebl_Shared::get_instance();

    //Styles -----------------------------------------------------------------------------------------------------------
    wp_enqueue_style(
        'daextrebl-editor-css',
        $shared->get('url') . 'blocks/dist/editor.build.css',
        array( 'wp-edit-blocks' ),//Dependency to include the CSS after it.
        filemtime( $shared->get('dir') . 'blocks/dist/editor.build.css')
    );

	//Scripts ----------------------------------------------------------------------------------------------------------

	//Block
	wp_enqueue_script(
		'daextrebl-editor-js', // Handle.
		plugins_url( '/dist/blocks.build.js', dirname( __FILE__ ) ), //We register the block here.
		array( 'wp-blocks', 'wp-element' ), // Dependencies.
		false,
		true //Enqueue the script in the footer.
	);

}

add_action( 'enqueue_block_editor_assets', 'daextrebl_editor_assets' );