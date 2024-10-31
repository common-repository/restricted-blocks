<?php

if ( ! current_user_can( 'manage_options' ) ) {
	wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'restricted-blocks' ) );
}

?>

<!-- output -->

<div class="wrap">

    <h2><?php esc_html_e( 'Restricted Blocks - Pro Version', 'restricted-blocks' ); ?></h2>

    <div id="daext-menu-wrapper">

        <p><?php echo esc_html__( 'For professional users, we distribute a',
					'restricted-blocks' ) . ' <a href="https://daext.com/restricted-blocks/">' . esc_attr__( 'Pro Version',
					'restricted-blocks' ) . '</a> ' . esc_attr__( 'of this plugin.',
					'restricted-blocks' ) . '</p>'; ?>
        <h2><?php esc_html_e( 'Additional Features Included in the Pro Version', 'restricted-blocks' ); ?></h2>
        <ul>
            <li><?php echo esc_html__( 'Display blocks only if the user meets specific age requirements with the restriction of type "Age".','restricted-blocks' ); ?></li>
            <li><?php echo esc_html__( 'Display or hide blocks based on the location of the user with the restriction of type "Geolocation".','restricted-blocks' ); ?></li>
        </ul>
        <h2><?php esc_html_e( 'Additional Benefits of the Pro Version', 'restricted-blocks' ); ?></h2>
        <ul>
            <li><?php esc_html_e( '24 hours support provided 7 days a week', 'restricted-blocks' ); ?></li>
            <li><?php echo esc_html__( '30 day money back guarantee (more information is available in the',
						'restricted-blocks' ) . ' <a href="https://daext.com/refund-policy/">' . esc_html__( 'Refund Policy',
						'restricted-blocks' ) . '</a> ' . esc_html__( 'page', 'restricted-blocks' ) . ')'; ?></li>
        </ul>
        <h2><?php esc_html_e( 'Get Started', 'restricted-blocks' ); ?></h2>
        <p><?php echo esc_html__( 'Download the',
					'restricted-blocks' ) . ' <a href="https://daext.com/restricted-blocks/">' . esc_html__( 'Pro Version',
					'restricted-blocks' ) . '</a> ' . esc_html__( 'now by selecting one of the available licenses.',
					'restricted-blocks' ); ?></p>
    </div>

</div>

