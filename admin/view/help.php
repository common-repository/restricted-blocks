<?php

if ( ! current_user_can( 'manage_options' ) ) {
	wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'restricted-blocks') );
}

?>

<!-- output -->

<div class="wrap">

    <h2><?php esc_html_e( 'Restricted Blocks - Help', 'restricted-blocks'); ?></h2>

    <div id="daext-menu-wrapper">

        <p><?php esc_html_e( 'Visit the resources below to find your answers or to ask questions directly to the plugin developers.', 'restricted-blocks'); ?></p>
        <ul>
            <li><a href="https://daext.com/doc/restricted-blocks/"><?php esc_html_e( 'Plugin Documentation', 'restricted-blocks'); ?></a></li>
            <li><a href="https://daext.com/support/"><?php esc_html_e( 'Support Conditions', 'restricted-blocks'); ?></li>
            <li><a href="https://daext.com"><?php esc_html_e( 'Developer Website', 'restricted-blocks'); ?></a></li>
            <li><a href="https://daext.com/restricted-blocks/"><?php esc_html_e( 'Pro Version',
                        'restricted-blocks' ); ?></a></li>
            <li>
                <a href="https://wordpress.org/plugins/restricted-blocks/"><?php esc_html_e( 'WordPress.org Plugin Page',
                        'restricted-blocks' ); ?></a></li>
            <li>
                <a href="https://wordpress.org/support/plugin/restricted-blocks/"><?php esc_html_e( 'WordPress.org Support Forum',
                        'restricted-blocks' ); ?></a></li>
        </ul>
        <p>

    </div>

</div>