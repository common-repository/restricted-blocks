<?php

if ( ! current_user_can( get_option( $this->shared->get( 'slug' ) . '_restrictions_menu_capability' ) ) ) {
	wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'restricted-blocks') );
}

?>

<!-- process data -->

<?php

//Initialize variables -------------------------------------------------------------------------------------------------
$dismissible_notice_a = [];

//Preliminary operations -----------------------------------------------------------------------------------------------
global $wpdb;

//Sanitization ---------------------------------------------------------------------------------------------

//Actions
$data['edit_id']        = isset( $_GET['edit_id'] ) ? intval( $_GET['edit_id'], 10 ) : null;
$data['delete_id']      = isset( $_POST['delete_id'] ) ? intval( $_POST['delete_id'], 10 ) : null;
$data['clone_id']       = isset( $_POST['clone_id'] ) ? intval( $_POST['clone_id'], 10 ) : null;
$data['update_id']      = isset( $_POST['update_id'] ) ? intval( $_POST['update_id'], 10 ) : null;
$data['form_submitted'] = isset( $_POST['form_submitted'] ) ? intval( $_POST['form_submitted'], 10 ) : null;

//Filter and search data
$data['s'] = isset( $_GET['s'] ) ? sanitize_text_field( $_GET['s'] ) : null;

//Form data
if ( ! is_null( $data['update_id'] ) or ! is_null( $data['form_submitted'] ) ) {

	//Main Form data
	$data['name']                        = isset( $_POST['name'] ) ? sanitize_text_field( $_POST['name'] ) : null;
	$data['description']                 = isset( $_POST['description'] ) ? sanitize_text_field( $_POST['description'] ) : null;
	$data['type']                        = isset( $_POST['type'] ) ? intval( $_POST['type'], 10 ) : null;
	$data['device']                      = isset( $_POST['device'] ) ? intval( $_POST['device'], 10 ) : null;
	$data['start_date']                  = isset( $_POST['start_date'] ) ? sanitize_text_field( $_POST['start_date'] ) : null;
	$data['end_date']                    = isset( $_POST['end_date'] ) ? sanitize_text_field( $_POST['end_date'] ) : null;
	$data['capabilities']                = isset( $_POST['capabilities'] ) ? sanitize_textarea_field( $_POST['capabilities'] ) : null;
	$data['ip_address']                  = isset( $_POST['ip_address'] ) ? sanitize_textarea_field( $_POST['ip_address'] ) : null;
	$data['cookie_name']                 = isset( $_POST['cookie_name'] ) ? sanitize_text_field( $_POST['cookie_name'] ) : null;
	$data['cookie_value']                = isset( $_POST['cookie_value'] ) ? sanitize_text_field( $_POST['cookie_value'] ) : null;
	$data['output_behavior']             = isset( $_POST['output_behavior'] ) ? sanitize_text_field( $_POST['output_behavior'] ) : null;
	$data['output_image']                = isset( $_POST['output_image'] ) ? esc_url_raw( $_POST['output_image'] ) : null;
	$data['output_title']                = isset( $_POST['output_title'] ) ? sanitize_text_field( $_POST['output_title'] ) : null;
	$data['output_description']          = isset( $_POST['output_description'] ) ? sanitize_text_field( $_POST['output_description'] ) : null;
	$data['output_button']               = isset( $_POST['output_button'] ) ? sanitize_text_field( $_POST['output_button'] ) : null;
	$data['output_validation']           = isset( $_POST['output_validation'] ) ? sanitize_text_field( $_POST['output_validation'] ) : null;
	$data['header_name']                 = isset( $_POST['header_name'] ) ? sanitize_text_field( $_POST['header_name'] ) : null;
	$data['header_value']                = isset( $_POST['header_value'] ) ? sanitize_textarea_field( $_POST['header_value'] ) : null;
	$data['password']                    = isset( $_POST['password'] ) ? sanitize_text_field( $_POST['password'] ) : null;
	$data['mode']                        = isset( $_POST['mode'] ) ? intval( $_POST['mode'], 10 ) : null;

	//Sanitize the single Capabilities
	$validated_capability_a = [];
	$capability_a           = preg_split( '/\r\n|[\r\n]/', $data['capabilities'] );
	foreach ( $capability_a as $key => $capability ) {
		$validated_capability_a[] = sanitize_key( $capability );
	}
	$data['capabilities'] = implode( PHP_EOL, $validated_capability_a );

	//Validate the single IP Addresses
	$validated_ip_address_a = [];
	$ip_address_a           = preg_split( '/\r\n|[\r\n]/', $data['ip_address'] );
	foreach ( $ip_address_a as $key => $ip_address ) {
		if ( rest_is_ip_address( $ip_address ) ) {
			$validated_ip_address_a[] = $ip_address;
		}
	}

	$data['ip_address'] = implode( PHP_EOL, $validated_ip_address_a );

}

//Validation -----------------------------------------------------------------------------------------------

if ( ! is_null( $data['update_id'] ) or ! is_null( $data['form_submitted'] ) ) {

	//validation on "name"
	if ( mb_strlen( trim( $data['name'] ) ) === 0 or mb_strlen( trim( $data['name'] ) ) > 100 ) {
		$dismissible_notice_a[] = [
			'message' => __( 'Please enter a valid value in the "Name" field.', 'restricted-blocks'),
			'class' => 'error'
		];
		$invalid_data         = true;
	}

	//validation on "description"
	if ( mb_strlen( trim( $data['description'] ) ) === 0 or mb_strlen( trim( $data['description'] ) ) > 255 ) {
		$dismissible_notice_a[] = [
			'message' => __( 'Please enter a valid value in the "Description" field.', 'restricted-blocks'),
			'class' => 'error'
		];
		$invalid_data         = true;
	}

}

//update ---------------------------------------------------------------
if ( ! is_null( $data['update_id'] ) and ! isset( $invalid_data ) ) {

	//update the database
	$table_name = $wpdb->prefix . $this->shared->get( 'slug' ) . "_restriction";
	$safe_sql   = $wpdb->prepare( "UPDATE $table_name SET 
                name = %s,
                description = %s,
                type = %d,
                mode = %d,
                device = %d,
                start_date = %s,    
                end_date = %s,
                capabilities = %s,
                ip_address = %s,
                cookie_name = %s,
                cookie_value = %s,
                output_behavior = %d,
                output_image = %s,
                output_title = %s,
                output_description = %s,
                output_button = %s,
                output_validation = %s,
                header_name = %s,
                header_value = %s,
                password = %s
                WHERE restriction_id = %d",
		$data['name'],
		$data['description'],
		$data['type'],
		$data['mode'],
		$data['device'],
		$data['start_date'],
		$data['end_date'],
		$data['capabilities'],
		$data['ip_address'],
		$data['cookie_name'],
		$data['cookie_value'],
		$data['output_behavior'],
		$data['output_image'],
		$data['output_title'],
		$data['output_description'],
		$data['output_button'],
		$data['output_validation'],
		$data['header_name'],
		$data['header_value'],
		$data['password'],
		$data['update_id'] );

	$query_result = $wpdb->query( $safe_sql );

	if ( $query_result !== false ) {
		$dismissible_notice_a[] = [
			'message' => __('The restriction has been successfully updated.', 'restricted-blocks'),
			'class' => 'updated'
		];
	}

} else {

	//add ------------------------------------------------------------------
	if ( ! is_null( $data['form_submitted'] ) and ! isset( $invalid_data ) ) {

		//insert into the database
		$table_name = $wpdb->prefix . $this->shared->get( 'slug' ) . "_restriction";
		$safe_sql   = $wpdb->prepare( "INSERT INTO $table_name SET 
                    name = %s,
                    description = %s,
                    type = %d,
                    mode = %d,
                    device = %d,
                    start_date = %s,    
                    end_date = %s,
                    capabilities = %s,
                    ip_address = %s,
                    cookie_name = %s,
                    cookie_value = %s,
                    output_behavior = %d,
                    output_image = %s,
                    output_title = %s,
                    output_description = %s,
                    output_button = %s,
                    output_validation = %s,
                    header_name = %s,
                    header_value = %s,
                    password = %s",
			$data['name'],
			$data['description'],
			$data['type'],
			$data['mode'],
			$data['device'],
			$data['start_date'],
			$data['end_date'],
			$data['capabilities'],
			$data['ip_address'],
			$data['cookie_name'],
			$data['cookie_value'],
			$data['output_behavior'],
			$data['output_image'],
			$data['output_title'],
			$data['output_description'],
			$data['output_button'],
			$data['output_validation'],
			$data['header_name'],
			$data['header_value'],
			$data['password'],
			);

		$query_result = $wpdb->query( $safe_sql );

		if ( $query_result !== false ) {
			$dismissible_notice_a[] = [
				'message' => __('The restriction has been successfully added.', 'restricted-blocks'),
				'class' => 'updated'
			];
		}

	}

}

//delete a restriction
if ( ! is_null( $data['delete_id'] ) ) {

	//delete this game
	$table_name = $wpdb->prefix . $this->shared->get( 'slug' ) . "_restriction";
	$safe_sql   = $wpdb->prepare( "DELETE FROM $table_name WHERE restriction_id = %d ", $data['delete_id'] );

	$query_result = $wpdb->query( $safe_sql );

	if ( $query_result !== false ) {
		$dismissible_notice_a[] = [
			'message' => __('The restriction has been successfully deleted.', 'restricted-blocks'),
			'class' => 'updated'
		];
	}

}

//clone the term group
if ( ! is_null( $data['clone_id'] ) ) {

	//clone the restriction
	$table_name = $wpdb->prefix . $this->shared->get( 'slug' ) . "_restriction";
	$wpdb->query( "CREATE TEMPORARY TABLE daextrebl_temporary_table SELECT * FROM $table_name WHERE restriction_id = " . $data['clone_id'] );
	$wpdb->query( "UPDATE daextrebl_temporary_table SET restriction_id = NULL" );
	$wpdb->query( "INSERT INTO $table_name SELECT * FROM daextrebl_temporary_table" );
	$wpdb->query( "DROP TEMPORARY TABLE IF EXISTS daextrebl_temporary_table" );

}

//get the restriction data
if ( ! is_null( $data['edit_id'] ) ) {
	$table_name     = $wpdb->prefix . $this->shared->get( 'slug' ) . "_restriction";
	$safe_sql       = $wpdb->prepare( "SELECT * FROM $table_name WHERE restriction_id = %d ", $data['edit_id'] );
	$restriction_obj = $wpdb->get_row( $safe_sql );
}

?>

<!-- output -->

<div class="wrap">

    <div id="daext-header-wrapper" class="daext-clearfix">

        <h2><?php esc_html_e( 'Restricted Blocks - Restrictions', 'restricted-blocks'); ?></h2>

        <form action="admin.php" method="get" id="daext-search-form">

            <input type="hidden" name="page" value="daextrebl-restrictions">

            <p><?php esc_html_e( 'Perform your Search', 'restricted-blocks'); ?></p>

			<?php
			if ( ! is_null( $data['s'] ) ) {
				if ( mb_strlen( trim( $data['s'] ) ) > 0 ) {
					$search_string = $data['s'];
				} else {
					$search_string = '';
				}
			} else {
				$search_string = '';
			}

			?>

            <input type="text" name="s"
                   value="<?php echo esc_attr( stripslashes( $search_string ) ); ?>" autocomplete="off" maxlength="255">
            <input type="submit" value="">

        </form>

    </div>

    <div id="daext-menu-wrapper">

	    <?php $this->dismissible_notice($dismissible_notice_a); ?>

        <!-- table -->

		<?php

		$filter = '';

		//create the query part used to filter the results when a search is performed
		if ( ! is_null( $data['s'] ) ) {

			if ( mb_strlen( trim( $data['s'] ) ) > 0 ) {

				//create the query part used to filter the results when a search is performed
				$filter = $wpdb->prepare( 'WHERE (name LIKE %s OR description LIKE %s)',
					'%' . $data['s'] . '%',
					'%' . $data['s'] . '%' );

			}

		}

		//retrieve the total number of restrictions
		$table_name  = $wpdb->prefix . $this->shared->get( 'slug' ) . "_restriction";
		$total_items = $wpdb->get_var( "SELECT COUNT(*) FROM $table_name $filter" );

		//Initialize the pagination class
		require_once( $this->shared->get( 'dir' ) . '/admin/inc/class-daextrebl-pagination.php' );
		$pag = new daextrebl_pagination();
		$pag->set_total_items( $total_items );//Set the total number of items
		$pag->set_record_per_page( 10 ); //Set records per page
		$pag->set_target_page( "admin.php?page=" . $this->shared->get( 'slug' ) . "-restrictions" );//Set target page
		$pag->set_current_page();//set the current page number from $_GET

		?>

        <!-- Query the database -->
		<?php
		$query_limit = $pag->query_limit();
		$results     = $wpdb->get_results( "SELECT * FROM $table_name $filter ORDER BY restriction_id DESC $query_limit",
			ARRAY_A ); ?>

		<?php if ( count( $results ) > 0 ) : ?>

            <div class="daext-items-container">

                <!-- list of tables -->
                <table class="daext-items">
                    <thead>
                    <tr>
                        <th>
                            <div><?php esc_html_e( 'Restriction ID', 'restricted-blocks'); ?></div>
                            <div class="help-icon" title="<?php esc_attr_e( 'The ID of the restriction.', 'restricted-blocks'); ?>"></div>
                        </th>
                        <th>
                            <div><?php esc_html_e( 'Name', 'restricted-blocks'); ?></div>
                            <div class="help-icon" title="<?php esc_attr_e( 'The name of the restriction.', 'restricted-blocks'); ?>"></div>
                        </th>
                        <th>
                            <div><?php esc_html_e( 'Description', 'restricted-blocks'); ?></div>
                            <div class="help-icon"
                                 title="<?php esc_attr_e( 'The description of the restriction.', 'restricted-blocks'); ?>"></div>
                        </th>
                        <th>
                            <div><?php esc_html_e( 'Type', 'restricted-blocks'); ?></div>
                            <div class="help-icon" title="<?php esc_attr_e( 'The type of restriction.', 'restricted-blocks'); ?>"></div>
                        </th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>

					<?php foreach ( $results as $result ) : ?>
                        <tr>
                            <td><?php echo intval( $result['restriction_id'], 10 ); ?></td>
                            <td><?php echo esc_html( stripslashes( $result['name'] ) ); ?></td>
                            <td><?php echo esc_html( stripslashes( $result['description'] ) ); ?></td>
                            <td><?php echo esc_html( $this->shared->get_restriction_type_name( $result['type'] ) ); ?></td>
                            <td class="icons-container">
                                <form method="POST"
                                      action="admin.php?page=<?php echo $this->shared->get( 'slug' ); ?>-restrictions">
                                    <input type="hidden" name="clone_id"
                                           value="<?php echo esc_attr( $result['restriction_id'] ); ?>">
                                    <input class="menu-icon clone help-icon" type="submit" value="">
                                </form>
                                <a class="menu-icon edit"
                                   href="admin.php?page=<?php echo esc_attr( $this->shared->get( 'slug' ) ); ?>-restrictions&edit_id=<?php echo esc_attr( $result['restriction_id'] ); ?>"></a>
                                <form id="form-delete-<?php echo esc_attr( $result['restriction_id'] ); ?>" method="POST"
                                      action="admin.php?page=<?php echo esc_attr( $this->shared->get( 'slug' ) ); ?>-restrictions">
                                    <input type="hidden" value="<?php echo esc_attr( $result['restriction_id'] ); ?>"
                                           name="delete_id">
                                    <input class="menu-icon delete" type="submit" value="">
                                </form>
                            </td>
                        </tr>
					<?php endforeach; ?>

                    </tbody>

                </table>

            </div>

            <!-- Display the pagination -->
			<?php if ( $pag->total_items > 0 ) : ?>
                <div class="daext-tablenav daext-clearfix">
                    <div class="daext-tablenav-pages">
                        <span class="daext-displaying-num"><?php echo esc_html( $pag->total_items ); ?>&nbsp<?php esc_html_e( 'items', 'restricted-blocks'); ?></span>
						<?php $pag->show(); ?>
                    </div>
                </div>
			<?php endif; ?>

		<?php else : ?>

			<?php

			if ( mb_strlen( trim( $filter ) ) > 0 ) {
				echo '<div class="error settings-error notice is-dismissible below-h2"><p>' . esc_html__( 'There are no results that match your filter.', 'restricted-blocks') . '</p></div>';
			}

			?>

		<?php endif; ?>

        <form method="POST" action="admin.php?page=<?php echo esc_attr( $this->shared->get( 'slug' ) ); ?>-restrictions"
              autocomplete="off">

            <input type="hidden" value="1" name="form_submitted">

			<?php if ( ! is_null( $data['edit_id'] ) ) : ?>

            <!-- Edit an Restriction -->

            <div class="daext-form-container">

                <h3 class="daext-form-title"><?php esc_html_e( 'Edit Restriction', 'restricted-blocks'); ?>&nbsp<?php echo esc_html( $restriction_obj->restriction_id ); ?></h3>

                <table class="daext-form daext-form-table">

                    <input type="hidden" name="update_id"
                           value="<?php echo esc_attr( $restriction_obj->restriction_id ); ?>"/>

                    <!-- Name -->
                    <tr valign="top">
                        <th><label for="name"><?php esc_html_e( 'Name', 'restricted-blocks'); ?></label></th>
                        <td>
                            <input value="<?php echo esc_attr( stripslashes( $restriction_obj->name ) ); ?>" type="text"
                                   id="name" maxlength="255" size="30" name="name"/>
                            <div class="help-icon"
                                 title="<?php esc_attr_e( 'The name of the restriction.', 'restricted-blocks'); ?>"></div>
                        </td>
                    </tr>

                    <!-- Description -->
                    <tr valign="top">
                        <th><label for="description"><?php esc_html_e( 'Description', 'restricted-blocks'); ?></label></th>
                        <td>
                            <input value="<?php echo esc_attr( stripslashes( $restriction_obj->description ) ); ?>"
                                   type="text"
                                   id="description" maxlength="255" size="30" name="description"/>
                            <div class="help-icon"
                                 title="<?php esc_attr_e( 'The description of the restriction.', 'restricted-blocks'); ?>"></div>
                        </td>
                    </tr>

                    <!-- Type -->
                    <tr>
                        <th scope="row"><label for="type"><?php esc_html_e( 'Type', 'restricted-blocks'); ?></label>
                        </th>
                        <td>
							<?php

							$html = '<select id="type" name="type" class="daext-display-none">';

                            $html .= '<option value="0" ' . selected( $restriction_obj->type, 0,
                                    false ) . '>' . esc_html__( 'Fixed', 'restricted-blocks') . '</option>';
                            $html .= '<option value="1" ' . selected( $restriction_obj->type, 1,
                                    false ) . '>' . esc_html__( 'Password', 'restricted-blocks') . '</option>';
							$html .= '<option value="2" ' . selected( $restriction_obj->type, 2,
									false ) . '>' . esc_html__( 'Device', 'restricted-blocks') . '</option>';
							$html .= '<option value="3" ' . selected( $restriction_obj->type, 3,
									false ) . '>' . esc_html__( 'Time Range', 'restricted-blocks') . '</option>';
							$html .= '<option value="4" ' . selected( $restriction_obj->type, 4,
									false ) . '>' . esc_html__( 'Capability', 'restricted-blocks') . '</option>';
							$html .= '<option value="5" ' . selected( $restriction_obj->type, 5,
									false ) . '>' . esc_html__( 'IP Address', 'restricted-blocks') . '</option>';
							$html .= '<option value="6" ' . selected( $restriction_obj->type, 6,
									false ) . '>' . esc_html__( 'Cookie', 'restricted-blocks') . '</option>';
							$html .= '<option value="7" ' . selected( $restriction_obj->type, 7,
									false ) . '>' . esc_html__( 'HTTP Headers', 'restricted-blocks') . '</option>';


							$html .= '</select>';
							$html .= '<div class="help-icon" title="' . esc_attr__( 'The type of restriction.', 'restricted-blocks') . '"></div>';

							echo $html;

							?>
                        </td>
                    </tr>

                    <!-- Password Options ------------------------------------------------------------ -->
                    <tr class="group-trigger" data-trigger-target="password-options">
                        <th class="group-title"><?php esc_html_e( 'Password', 'restricted-blocks'); ?></th>
                        <td>
                            <div class="expand-icon"></div>
                        </td>
                    </tr>

                    <tr valign="top" class="password-options">
                        <th scope="row"><label for="password-name"><?php esc_html_e( 'Password', 'restricted-blocks'); ?></label></th>
                        <td>
                            <input value="<?php echo esc_attr( stripslashes( $restriction_obj->password ) ); ?>"
                                   type="text" id="password" maxlength="255" name="password"/>
                            <div class="help-icon" title="<?php esc_attr_e( 'The password required to see the block.', 'restricted-blocks'); ?>"></div>
                        </td>
                    </tr>

                    <!-- Device Options ------------------------------------------------------------ -->
                    <tr class="group-trigger" data-trigger-target="device-options">
                        <th class="group-title"><?php esc_html_e( 'Device', 'restricted-blocks'); ?></th>
                        <td>
                            <div class="expand-icon"></div>
                        </td>
                    </tr>

                    <!-- Device -->
                    <tr class="device-options">
                        <th scope="row"><label for="type"><?php esc_html_e( 'Type', 'restricted-blocks'); ?></label></th>
                        <td>
                            <select id="device" name="device" class="daext-display-none">
                                <option value="0" <?php echo selected( $restriction_obj->device, 0,
									false ); ?>><?php esc_html_e( 'Desktop', 'restricted-blocks'); ?></option>
                                <option value="1" <?php echo selected( $restriction_obj->device, 1,
									false ); ?>><?php esc_html_e( 'Mobile', 'restricted-blocks'); ?></option>
                            </select>
                            <div class="help-icon"
                                 title="<?php esc_attr_e( 'Select if the block should be displayed with desktop or mobile devices.', 'restricted-blocks'); ?>"></div>
                        </td>
                    </tr>

                    <!-- Time Range Options ------------------------------------------------------------ -->
                    <tr class="group-trigger" data-trigger-target="time-range-options">
                        <th class="group-title"><?php esc_html_e( 'Time Range', 'restricted-blocks'); ?></th>
                        <td>
                            <div class="expand-icon"></div>
                        </td>
                    </tr>

                    <!-- Start Date -->
                    <tr valign="top" class="time-range-options">
                        <th scope="row"><label for="start-date"><?php esc_html_e( 'Start Date', 'restricted-blocks'); ?></label></th>
                        <td>
	                        <?php
	                        $start_date = substr( $restriction_obj->start_date, 0, 16 );
	                        if($start_date === '0000-00-00 00:00'){
	                        	$start_date = '';
	                        }
	                        ?>
                            <input value="<?php echo esc_attr( $start_date ); ?>"
                                   type="text" name="start_date" maxlength="255" size="30" id="start-date" readonly
                                   placeholder="<?php esc_attr_e( 'Pick up a date and a time', 'restricted-blocks'); ?>">
                            <div class="help-icon" title="<?php esc_attr_e( 'The start date of the range.', 'restricted-blocks'); ?>"></div>
                        </td>
                    </tr>

                    <!-- End Date -->
                    <tr valign="top" class="time-range-options">
                        <th scope="row"><label for="end-date"><?php esc_html_e( 'End Date', 'restricted-blocks'); ?></label></th>
                        <td>
	                        <?php
	                        $end_date = substr( $restriction_obj->end_date, 0, 16 );
	                        if($end_date === '0000-00-00 00:00'){
		                        $end_date = '';
	                        }
	                        ?>
                            <input value="<?php echo esc_attr( $end_date ); ?>"
                                   type="text" name="end_date" maxlength="255" size="30" id="end-date" readonly
                                   placeholder="<?php esc_attr_e( 'Pick up a date and a time', 'restricted-blocks'); ?>">
                            <div class="help-icon" title="<?php esc_attr_e( 'The end date of the range.', 'restricted-blocks'); ?>"></div>
                        </td>
                    </tr>

                    <!-- Capability Options ------------------------------------------------------------ -->
                    <tr class="group-trigger" data-trigger-target="capability-options">
                        <th class="group-title"><?php esc_html_e( 'Capability', 'restricted-blocks'); ?></th>
                        <td>
                            <div class="expand-icon"></div>
                        </td>
                    </tr>

                    <tr valign="top" class="capability-options">
                        <th scope="row"><label for="capabilities"><?php esc_html_e( 'Capability', 'restricted-blocks'); ?></label></th>
                        <td>
                            <textarea type="text" id="capabilities" maxlength="2000" size="30"
                                      name="capabilities"><?php echo esc_html( stripslashes( $restriction_obj->capabilities ) ); ?></textarea>
                            <div class="help-icon"
                                 title="<?php esc_attr_e( 'The user capability required to see the block. Please note that you can add multiple capabilities by entering them one per line.', 'restricted-blocks'); ?>"></div>
                        </td>
                    </tr>

                    <!-- IP Address Options ------------------------------------------------------------ -->
                    <tr class="group-trigger" data-trigger-target="ip-address-options">
                        <th class="group-title"><?php esc_html_e( 'IP Address', 'restricted-blocks'); ?></th>
                        <td>
                            <div class="expand-icon"></div>
                        </td>
                    </tr>

                    <tr valign="top" class="ip-address-options">
                        <th scope="row"><label for="ip-address"><?php esc_html_e( 'IP Address', 'restricted-blocks'); ?></label></th>
                        <td>
                            <textarea type="text" id="ip-address" maxlength="2000" size="30"
                                      name="ip_address"><?php echo esc_html( stripslashes( $restriction_obj->ip_address ) ); ?></textarea>
                            <div class="help-icon"
                                 title="<?php esc_attr_e( 'The IP address required to see the block. Please note that you can add multiple IP addresses by entering them one per line.', 'restricted-blocks'); ?>"></div>
                        </td>
                    </tr>

                    <!-- Cookie Options ------------------------------------------------------------ -->
                    <tr class="group-trigger" data-trigger-target="cookie-options">
                        <th class="group-title"><?php esc_html_e( 'Cookie', 'restricted-blocks'); ?></th>
                        <td>
                            <div class="expand-icon"></div>
                        </td>
                    </tr>

                    <tr valign="top" class="cookie-options">
                        <th scope="row"><label for="cookie-name"><?php esc_html_e( 'Name', 'restricted-blocks'); ?></label></th>
                        <td>
                            <input value="<?php echo esc_attr( stripslashes( $restriction_obj->cookie_name ) ); ?>"
                                   type="text" id="cookie-name" maxlength="2000" size="30" name="cookie_name"/>
                            <div class="help-icon"
                                 title="<?php esc_attr_e( 'The name of the cookie required to see the block.', 'restricted-blocks'); ?>"></div>
                        </td>
                    </tr>

                    <tr valign="top" class="cookie-options">
                        <th scope="row"><label for="cookie-value"><?php esc_html_e( 'Value', 'restricted-blocks'); ?></label></th>
                        <td>
                            <input value="<?php echo esc_attr( stripslashes( $restriction_obj->cookie_value ) ); ?>"
                                   type="text" id="cookie-value" maxlength="2000" size="30" name="cookie_value"/>
                            <div class="help-icon"
                                 title="<?php esc_attr_e( 'The value of the cookie required to see the block.', 'restricted-blocks'); ?>"></div>
                        </td>
                    </tr>

                    <!-- HTTP Headers Options ------------------------------------------------------------ -->
                    <tr class="group-trigger" data-trigger-target="http-headers-options">
                        <th class="group-title"><?php esc_html_e( 'HTTP Headers', 'restricted-blocks'); ?></th>
                        <td>
                            <div class="expand-icon"></div>
                        </td>
                    </tr>

                    <tr valign="top" class="http-headers-options">
                        <th scope="row"><label for="header-name"><?php esc_html_e( 'Name', 'restricted-blocks'); ?></label></th>
                        <td>
                            <input value="<?php echo esc_attr( stripslashes( $restriction_obj->header_name ) ); ?>"
                                   type="text" id="header-name" maxlength="2000" size="30" name="header_name"/>
                            <div class="help-icon"
                                 title="<?php esc_attr_e( 'The name of the HTTP header required to see the block.', 'restricted-blocks'); ?>"></div>
                        </td>
                    </tr>

                    <!-- Header Value -->
                    <tr valign="top" class="http-headers-options">
                        <th scope="row"><label for="header-value"><?php esc_html_e( 'Value', 'restricted-blocks'); ?></label></th>
                        <td>
                            <textarea type="text" id="header-value" maxlength="10000"
                                      name="header_value"><?php echo esc_html( stripslashes( $restriction_obj->header_value ) ); ?></textarea>
                            <div class="help-icon"
                                 title="<?php esc_attr_e( 'The value of the HTTP header required to see the block. Please note that you can add multiple HTTP headers by entering them one per line.', 'restricted-blocks'); ?>"></div>
                        </td>
                    </tr>

                    <!-- Output Options ------------------------------------------------------------ -->
                    <tr class="group-trigger" data-trigger-target="output-options">
                        <th class="group-title"><?php esc_html_e( 'Output', 'restricted-blocks'); ?></th>
                        <td>
                            <div class="expand-icon"></div>
                        </td>
                    </tr>

                    <!-- Output Behavior -->
                    <tr class="output-options">
                        <th scope="row"><label for="output-behavior"><?php esc_html_e( 'Behavior', 'restricted-blocks'); ?></label></th>
                        <td>
                            <select id="output-behavior" name="output_behavior" class="daext-display-none">
                                <option value="0" <?php echo selected( $restriction_obj->output_behavior, 0,
									false ); ?>><?php esc_html_e( 'Hide Block', 'restricted-blocks'); ?></option>
                                <option value="1" <?php echo selected( $restriction_obj->output_behavior, 1,
									false ); ?>><?php esc_html_e( 'Display Restriction', 'restricted-blocks'); ?></option>
                            </select>
                            <div class="help-icon"
                                 title="<?php esc_attr_e( 'Select the behavior of the plugin when the conditions to display the block are not met.', 'restricted-blocks'); ?>"></div>
                        </td>
                    </tr>

                    <!-- Output Image -->
                    <tr class="output-options">
                        <th scope="row"><label for="output-image"><?php esc_html_e( 'Image', 'restricted-blocks'); ?></label></th>
                        <td>

                            <div class="image-uploader">
                                <img class="selected-image"
                                     src="<?php echo esc_url( $restriction_obj->output_image ); ?>" <?php echo strlen( trim( $restriction_obj->output_image ) ) == 0 ? 'style="display: none;"' : ''; ?>>
                                <input value="<?php echo esc_url( stripslashes( $restriction_obj->output_image ) ); ?>"
                                       type="hidden" id="output-image" maxlength="1000" name="output_image">
                                <a class="button_add_media"
                                   data-set-remove="<?php echo strlen( trim( $restriction_obj->output_image ) ) == 0 ? 'set' : 'remove'; ?>"
                                   data-set="<?php esc_attr_e( 'Set image', 'restricted-blocks'); ?>"
                                   data-remove="<?php esc_attr_e( 'Remove Image', 'restricted-blocks'); ?>"><?php echo strlen( trim( $restriction_obj->output_image ) ) === 0 ? esc_html__( 'Set image', 'restricted-blocks') : esc_html__( 'Remove image', 'restricted-blocks'); ?></a>
                                <p class="description"><?php esc_html_e( "Select an image that represents this restriction.", 'restricted-blocks'); ?></p>
                            </div>
                            <div class="help-icon"
                                 title="<?php esc_attr_e( 'The image associated with the restricted block.', 'restricted-blocks'); ?>"></div>

                        </td>
                    </tr>

                    <!-- Output Title -->
                    <tr valign="top" class="output-options">
                        <th scope="row"><label for="output-title"><?php esc_html_e( 'Title', 'restricted-blocks'); ?></label></th>
                        <td>
                            <input value="<?php echo esc_attr( stripslashes( $restriction_obj->output_title ) ) ?>"
                                   type="text" id="output-title" maxlength="255" name="output_title">
                            <div class="help-icon"
                                 title="<?php esc_attr_e( 'The title of the restricted block.', 'restricted-blocks'); ?>"></div>
                        </td>
                    </tr>

                    <!-- Output Description -->
                    <tr valign="top" class="output-options">
                        <th scope="row"><label for="output-description"><?php esc_html_e( 'Description', 'restricted-blocks'); ?></label></th>
                        <td>
                            <textarea type="text" id="output-description" maxlength="10000"
                                      name="output_description"><?php echo esc_html( stripslashes( $restriction_obj->output_description ) ) ?></textarea>
                            <div class="help-icon"
                                 title="<?php esc_attr_e( 'The description of the restricted block.', 'restricted-blocks'); ?>"></div>
                        </td>
                    </tr>

                    <!-- Output Button -->
                    <tr valign="top" class="output-options">
                        <th scope="row"><label for="output-button"><?php esc_html_e( 'Button', 'restricted-blocks'); ?></label></th>
                        <td>
                            <textarea type="text" id="output-button" maxlength="10000"
                                      name="output_button"><?php echo esc_html( stripslashes( $restriction_obj->output_button ) ) ?></textarea>
                            <div class="help-icon"
                                 title="<?php esc_attr_e( 'The text of the button included in the restricted block. Please note that the button is displayed only with restrictions of type "Password".', 'restricted-blocks'); ?>"></div>
                        </td>
                    </tr>

                    <!-- Output Validation -->
                    <tr valign="top" class="output-options">
                        <th scope="row"><label for="output-validation"><?php esc_html_e( 'Validation', 'restricted-blocks'); ?></label></th>
                        <td>
                            <textarea type="text" id="output-validation" maxlength="10000"
                                      name="output_validation"><?php echo esc_html( stripslashes( $restriction_obj->output_validation ) ) ?></textarea>
                            <div class="help-icon"
                                 title="<?php esc_attr_e( 'The text of the validation message included in the restricted block. Please note that the validation message is displayed only with restrictions of type "Password".', 'restricted-blocks'); ?>"></div>
                        </td>
                    </tr>

                    <!-- Advanced Options ------------------------------------------------------------ -->
                    <tr class="group-trigger" data-trigger-target="advanced-options">
                        <th class="group-title"><?php esc_html_e( 'Advanced', 'restricted-blocks'); ?></th>
                        <td>
                            <div class="expand-icon"></div>
                        </td>
                    </tr>

                    <!-- Mode -->
                    <tr class="advanced-options">
                        <th scope="row"><label for="mode"><?php esc_html_e( 'Mode', 'restricted-blocks'); ?></label>
                        </th>
                        <td>
							<?php

							$html = '<select id="mode" name="mode" class="daext-display-none">';

							$html .= '<option value="0" ' . selected( $restriction_obj->mode, 0,
									false ) . '>' . esc_html__( 'Include', 'restricted-blocks') . '</option>';
							$html .= '<option value="1" ' . selected( $restriction_obj->mode, 1,
									false ) . '>' . esc_html__( 'Exclude', 'restricted-blocks') . '</option>';


							$html .= '</select>';
							$html .= '<div class="help-icon" title="' . esc_attr__( 'Whether to include or exclude the conditions associated with the restriction. Please note that this option is applied only to the following types of restriction: Device, Time Range, Capability, IP Address, Cookie, HTTP Headers.', 'restricted-blocks') . '"></div>';

							echo $html;

							?>
                        </td>
                    </tr>

                </table>

                <!-- submit button -->
                <div class="daext-form-action">
                    <input class="button" type="submit"
                           value="<?php esc_attr_e( 'Update Restriction', 'restricted-blocks'); ?>">
                    <input id="cancel" class="button" type="submit"
                           value="<?php esc_attr_e( 'Cancel', 'restricted-blocks'); ?>">
                </div>

				<?php else : ?>

                <!-- Create New Restriction -->

                <div class="daext-form-container">

                    <div class="daext-form-title"><?php esc_html_e( 'Create New Restriction', 'restricted-blocks'); ?></div>

                    <table class="daext-form daext-form-table">

                        <!-- Name -->
                        <tr valign="top">
                            <th scope="row"><label for="name"><?php esc_html_e( 'Name', 'restricted-blocks'); ?></label></th>
                            <td>
                                <input type="text" id="keyword" maxlength="100" size="30" name="name"/>
                                <div class="help-icon" title="<?php esc_attr_e( 'The name of the restriction.', 'restricted-blocks'); ?>"></div>
                            </td>
                        </tr>

                        <!-- Description -->
                        <tr valign="top">
                            <th scope="row"><label for="name"><?php esc_html_e( 'Description', 'restricted-blocks'); ?></label></th>
                            <td>
                                <input type="text" id="description" maxlength="255" size="30" name="description"/>
                                <div class="help-icon" title="<?php esc_attr_e( 'The description of the restriction.', 'restricted-blocks'); ?>"></div>
                            </td>
                        </tr>

                        <!-- Type -->
                        <tr>
                            <th scope="row"><label for="type"><?php esc_html_e( 'Type', 'restricted-blocks'); ?></label></th>
                            <td>
                                <select id="type" name="type" class="daext-display-none">
                                    <option value="0"><?php esc_html_e( 'Fixed', 'restricted-blocks'); ?></option>
                                    <option value="1"><?php esc_html_e( 'Password', 'restricted-blocks'); ?></option>
                                    <option value="2"><?php esc_html_e( 'Device', 'restricted-blocks'); ?></option>
                                    <option value="3"><?php esc_html_e( 'Time Range', 'restricted-blocks'); ?></option>
                                    <option value="4"><?php esc_html_e( 'Capability', 'restricted-blocks'); ?></option>
                                    <option value="5"><?php esc_html_e( 'IP Address', 'restricted-blocks'); ?></option>
                                    <option value="6"><?php esc_html_e( 'Cookie', 'restricted-blocks'); ?></option>
                                    <option value="7"><?php esc_html_e( 'HTTP Headers', 'restricted-blocks'); ?></option>

                                </select>
                                <div class="help-icon" title="<?php esc_attr_e( 'The type of restriction.', 'restricted-blocks'); ?>"></div>

                            </td>
                        </tr>

                        <!-- Password Options ------------------------------------------------------------ -->
                        <tr class="group-trigger" data-trigger-target="password-options">
                            <th class="group-title"><?php esc_html_e( 'Password', 'restricted-blocks'); ?></th>
                            <td>
                                <div class="expand-icon"></div>
                            </td>
                        </tr>

                        <tr valign="top" class="password-options">
                            <th scope="row"><label for="password-name"><?php esc_html_e( 'Password', 'restricted-blocks'); ?></label></th>
                            <td>
                                <input type="text" id="password" maxlength="255" name="password"/>
                                <div class="help-icon"
                                     title="<?php esc_attr_e( 'The password required to see the block.', 'restricted-blocks'); ?>"></div>
                            </td>
                        </tr>

                        <!-- Device Options ------------------------------------------------------------ -->
                        <tr class="group-trigger" data-trigger-target="device-options">
                            <th class="group-title"><?php esc_html_e( 'Device', 'restricted-blocks'); ?></th>
                            <td>
                                <div class="expand-icon"></div>
                            </td>
                        </tr>

                        <!-- Device -->
                        <tr class="device-options">
                            <th scope="row"><label for="type"><?php esc_html_e( 'Type', 'restricted-blocks'); ?></label></th>
                            <td>
                                <select id="device" name="device" class="daext-display-none">
                                    <option value="0"><?php esc_html_e( 'Desktop', 'restricted-blocks'); ?></option>
                                    <option value="1"><?php esc_html_e( 'Mobile', 'restricted-blocks'); ?></option>
                                </select>
                                <div class="help-icon"
                                     title="<?php esc_attr_e( 'Select if the block should be displayed with desktop or mobile devices.', 'restricted-blocks'); ?>"></div>
                            </td>
                        </tr>

                        <!-- Time Range Options ------------------------------------------------------------ -->
                        <tr class="group-trigger" data-trigger-target="time-range-options">
                            <th class="group-title"><?php esc_html_e( 'Time Range', 'restricted-blocks'); ?></th>
                            <td>
                                <div class="expand-icon"></div>
                            </td>
                        </tr>

                        <!-- Start Date -->
                        <tr valign="top" class="time-range-options">
                            <th scope="row"><label for="start-date"><?php esc_html_e( 'Start Date', 'restricted-blocks'); ?></label></th>
                            <td>
                                <input type="text" name="start_date" maxlength="255" size="30" id="start-date" readonly
                                       placeholder="<?php esc_attr_e( 'Pick up a date and a time', 'restricted-blocks'); ?>">
                                <div class="help-icon" title="<?php esc_attr_e( 'The start date of the range.', 'restricted-blocks'); ?>"></div>
                            </td>
                        </tr>

                        <!-- End Date -->
                        <tr valign="top" class="time-range-options">
                            <th scope="row"><label for="end-date"><?php esc_html_e( 'End Date', 'restricted-blocks'); ?></label></th>
                            <td>
                                <input type="text" name="end_date" maxlength="255" size="30" id="end-date" readonly
                                       placeholder="<?php esc_attr_e( 'Pick up a date and a time', 'restricted-blocks'); ?>">
                                <div class="help-icon" title="<?php esc_attr_e( 'The end date of the range.', 'restricted-blocks'); ?>"></div>
                            </td>
                        </tr>

                        <!-- Capability Options ------------------------------------------------------------ -->
                        <tr class="group-trigger" data-trigger-target="capability-options">
                            <th class="group-title"><?php esc_html_e( 'Capability', 'restricted-blocks'); ?></th>
                            <td>
                                <div class="expand-icon"></div>
                            </td>
                        </tr>

                        <tr valign="top" class="capability-options">
                            <th scope="row"><label for="capabilities"><?php esc_html_e( 'Capability', 'restricted-blocks'); ?></label></th>
                            <td>
                                <textarea type="text" id="capabilities" maxlength="2000" size="30"
                                          name="capabilities"></textarea>
                                <div class="help-icon"
                                     title="<?php esc_attr_e( 'The user capability required to see the block. Please note that you can add multiple capabilities by entering them one per line.', 'restricted-blocks'); ?>"></div>
                            </td>
                        </tr>

                        <!-- IP Address Options ------------------------------------------------------------ -->
                        <tr class="group-trigger" data-trigger-target="ip-address-options">
                            <th class="group-title"><?php esc_html_e( 'IP Address', 'restricted-blocks'); ?></th>
                            <td>
                                <div class="expand-icon"></div>
                            </td>
                        </tr>

                        <tr valign="top" class="ip-address-options">
                            <th scope="row"><label for="ip-address"><?php esc_html_e( 'IP Address', 'restricted-blocks'); ?></label></th>
                            <td>
                                <textarea type="text" id="ip-address" maxlength="2000" size="30"
                                          name="ip_address"></textarea>
                                <div class="help-icon"
                                     title="<?php esc_attr_e( 'The IP address required to see the block. Please note that you can add multiple IP addresses by entering them one per line.', 'restricted-blocks'); ?>"></div>
                            </td>
                        </tr>

                        <!-- Cookie Options ------------------------------------------------------------ -->
                        <tr class="group-trigger" data-trigger-target="cookie-options">
                            <th class="group-title"><?php esc_html_e( 'Cookie', 'restricted-blocks'); ?></th>
                            <td>
                                <div class="expand-icon"></div>
                            </td>
                        </tr>

                        <tr valign="top" class="cookie-options">
                            <th scope="row"><label for="cookie-name"><?php esc_html_e( 'Name', 'restricted-blocks'); ?></label></th>
                            <td>
                                <input type="text" id="cookie-name" maxlength="2000" size="30" name="cookie_name"/>
                                <div class="help-icon"
                                     title="<?php esc_attr_e( 'The name of the cookie required to see the block.', 'restricted-blocks'); ?>"></div>
                            </td>
                        </tr>

                        <tr valign="top" class="cookie-options">
                            <th scope="row"><label for="cookie-value"><?php esc_html_e( 'Value', 'restricted-blocks'); ?></label></th>
                            <td>
                                <input type="text" id="cookie-value" maxlength="2000" size="30" name="cookie_value"/>
                                <div class="help-icon"
                                     title="<?php esc_attr_e( 'The value of the cookie required to see the block.', 'restricted-blocks'); ?>"></div>
                            </td>
                        </tr>

                        <!-- HTTP Headers Options ------------------------------------------------------------ -->
                        <tr class="group-trigger" data-trigger-target="http-headers-options">
                            <th class="group-title"><?php esc_html_e( 'HTTP Headers', 'restricted-blocks'); ?></th>
                            <td>
                                <div class="expand-icon"></div>
                            </td>
                        </tr>

                        <tr valign="top" class="http-headers-options">
                            <th scope="row"><label for="header-name"><?php esc_html_e( 'Name', 'restricted-blocks'); ?></label></th>
                            <td>
                                <input type="text" id="header-name" maxlength="2000" size="30" name="header_name"/>
                                <div class="help-icon"
                                     title="<?php esc_attr_e( 'The name of the HTTP header required to see the block.', 'restricted-blocks'); ?>"></div>
                            </td>
                        </tr>

                        <!-- Header Value -->
                        <tr valign="top" class="http-headers-options">
                            <th scope="row"><label for="header-value"><?php esc_html_e( 'Value', 'restricted-blocks'); ?></label></th>
                            <td>
                                <textarea type="text" id="header-value" maxlength="10000"
                                          name="header_value"></textarea>
                                <div class="help-icon"
                                     title="<?php esc_attr_e( 'The value of the HTTP header required to see the block. Please note that you can add multiple HTTP headers by entering them one per line.', 'restricted-blocks'); ?>"></div>
                            </td>
                        </tr>

                        <!-- Output Options ------------------------------------------------------------ -->
                        <tr class="group-trigger" data-trigger-target="output-options">
                            <th class="group-title"><?php esc_html_e( 'Output', 'restricted-blocks'); ?></th>
                            <td>
                                <div class="expand-icon"></div>
                            </td>
                        </tr>

                        <!-- Output Behavior -->
                        <tr class="output-options">
                            <th scope="row"><label for="output-behavior"><?php esc_html_e( 'Behavior', 'restricted-blocks'); ?></label></th>
                            <td>
                                <select id="output-behavior" name="output_behavior" class="daext-display-none">
                                    <option value="0"><?php esc_html_e( 'Hide Block', 'restricted-blocks'); ?></option>
                                    <option value="1" selected="selected"><?php esc_html_e( 'Display Restriction', 'restricted-blocks'); ?></option>
                                </select>
                                <div class="help-icon"
                                     title="<?php esc_attr_e( 'Select the behavior of the plugin when the conditions to display the block are not met.', 'restricted-blocks'); ?>"></div>
                            </td>
                        </tr>

                        <!-- Output Image -->
                        <tr class="output-options">
                            <th scope="row"><label for="output-image"><?php esc_html_e( 'Image', 'restricted-blocks'); ?></label></th>
                            <td>

                                <div class="image-uploader">
                                    <img class="selected-image"
                                         src="<?php echo esc_url( $this->shared->get( 'url' ) . 'shared/assets/img/padlock.png' ); ?>">
                                    <input value="<?php echo esc_url( $this->shared->get( 'url' ) . 'shared/assets/img/padlock.png' ); ?>"
                                           type="hidden" id="output-image" maxlength="1000" name="output_image">
                                    <a class="button_add_media" data-set-remove="remove"
                                       data-set="<?php esc_attr_e( 'Set image', 'restricted-blocks'); ?>"
                                       data-remove="<?php esc_attr_e( 'Remove Image', 'restricted-blocks'); ?>"><?php esc_html_e( 'Remove image', 'restricted-blocks'); ?></a>
                                    <p class="description"><?php esc_html_e( "Select an image that represents this restriction.", 'restricted-blocks'); ?></p>
                                </div>
                                <div class="help-icon"
                                     title="<?php esc_attr_e( 'The image associated with the restricted block.', 'restricted-blocks'); ?>"></div>

                            </td>
                        </tr>

                        <!-- Output Title -->
                        <tr valign="top" class="output-options">
                            <th scope="row"><label for="output-title"><?php esc_html_e( 'Title', 'restricted-blocks'); ?></label></th>
                            <td>
                                <input value="Restricted Content" type="text" id="output-title" maxlength="255" name="output_title">
                                <div class="help-icon"
                                     title="<?php esc_attr_e( 'The title of the restricted block.', 'restricted-blocks'); ?>"></div>
                            </td>
                        </tr>

                        <!-- Output Description -->
                        <tr valign="top" class="output-options">
                            <th scope="row"><label for="output-description"><?php esc_html_e( 'Description', 'restricted-blocks'); ?></label></th>
                            <td>
                                <textarea type="text" id="output-description" maxlength="10000"
                                          name="output_description"><?php esc_html_e( "Sorry, this content isn't available right now.", 'restricted-blocks') ?></textarea>
                                <div class="help-icon"
                                     title="<?php esc_attr_e( 'The description of the restricted block.', 'restricted-blocks'); ?>"></div>
                            </td>
                        </tr>

                        <!-- Output Button -->
                        <tr valign="top" class="output-options">
                            <th scope="row"><label for="output-button"><?php esc_html_e( 'Button', 'restricted-blocks'); ?></label></th>
                            <td>
                                <textarea type="text" id="output-button" maxlength="10000"
                                          name="output_button"><?php esc_html_e( 'Submit', 'restricted-blocks') ?></textarea>
                                <div class="help-icon"
                                     title="<?php esc_attr_e( 'The text of the button included in the restricted block. Please note that the button is displayed only with restrictions of type "Password".', 'restricted-blocks'); ?>"></div>
                            </td>
                        </tr>

                        <!-- Output Validation -->
                        <tr valign="top" class="output-options">
                            <th scope="row"><label for="output-validation"><?php esc_html_e( 'Validation', 'restricted-blocks'); ?></label></th>
                            <td>
                                <textarea type="text" id="output-validation" maxlength="10000"
                                          name="output_validation"><?php esc_html_e( 'Please enter a valid value.', 'restricted-blocks') ?></textarea>
                                <div class="help-icon"
                                     title="<?php esc_attr_e( 'The text of the validation message included in the restricted block. Please note that the validation message is displayed only with restrictions of type "Password".', 'restricted-blocks'); ?>"></div>
                            </td>
                        </tr>

                        <!-- Advanced Options ------------------------------------------------------------ -->
                        <tr class="group-trigger" data-trigger-target="advanced-options">
                            <th class="group-title"><?php esc_html_e( 'Advanced', 'restricted-blocks'); ?></th>
                            <td>
                                <div class="expand-icon"></div>
                            </td>
                        </tr>

                        <!-- Mode -->
                        <tr class="advanced-options">
                            <th scope="row"><label for="mode"><?php esc_html_e( 'Mode', 'restricted-blocks'); ?></label></th>
                            <td>
                                <select id="mode" name="mode" class="daext-display-none">
                                    <option value="0"><?php esc_html_e( 'Include', 'restricted-blocks'); ?></option>
                                    <option value="1"><?php esc_html_e( 'Exclude', 'restricted-blocks'); ?></option>
                                </select>
                                <div class="help-icon"
                                     title="<?php esc_attr_e( 'Whether to include or exclude the conditions associated with the restriction. Please note that this option is applied only to the following types of restriction: Device, Time Range, Capability, IP Address, Cookie, HTTP Headers.', 'restricted-blocks'); ?>"></div>

                            </td>
                        </tr>

                    </table>

                    <!-- submit button -->
                    <div class="daext-form-action">
                        <input class="button" type="submit"
                               value="<?php esc_attr_e( 'Add Restriction', 'restricted-blocks'); ?>">
                    </div>

					<?php endif; ?>

                </div>

        </form>

    </div>

</div>

<!-- Dialog Confirm -->
<div id="dialog-confirm" title="<?php esc_attr_e( 'Delete the restriction?', 'restricted-blocks'); ?>"
     class="daext-display-none">
    <p><?php esc_html_e( 'This restriction will be permanently deleted and cannot be recovered. Are you sure?', 'restricted-blocks'); ?></p>
</div>