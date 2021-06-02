<?php if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) { exit; }

// Create the settings section under "WooCommerce" > "Products" > "Non-Purchasable Products"
add_filter('woocommerce_get_sections_products', 'gp_npwp_create_settings_section');
function gp_npwp_create_settings_section($sections) {
	$sections['non-purchasable'] = __('Non-Purchasable Products', 'non-purchasable-woocommerce-products');
	return $sections;
}

// Add settings to the specific section we created before
add_filter('woocommerce_get_settings_products', 'gp_npwp_settings', 10, 2);
function gp_npwp_settings($settings, $current_section) {

	// Check the current section is what we want
	if ($current_section == 'non-purchasable') {
		$settings_npwp = array();

		// Add Title to the Settings
		$settings_npwp[] = array(
			'name' => __('Non-Purchasable Products', 'non-purchasable-woocommerce-products'), 
			'type' => 'title', 
			'desc' => __('Configure the global options for non-purchasable products.', 'non-purchasable-woocommerce-products'), 
			'id' => 'non-purchasable'
		);

		// Set button text
		$settings_npwp[] = array(
			'name' => __('"Add to cart" button text', 'non-purchasable-woocommerce-products'),
			'desc_tip' => __('Change the button text for non-purchasable products outside of the single product page.', 'non-purchasable-woocommerce-products'),
			'id' => 'gp_npwp_new_button_text',
			'type' => 'text',
			'default' => __('View product', 'non-purchasable-woocommerce-products'),
		);
		
		// Set message text
		$settings_npwp[] = array(
			'name' => __('Message on product page', 'non-purchasable-woocommerce-products'),
			'desc_tip' => __('Change the default message to display on the single product page for non-purchasable products.', 'non-purchasable-woocommerce-products'),
			'id' => 'gp_npwp_new_message_text',
			'type' => 'textarea',
			'default' => __('This item cannot be purchased online.', 'non-purchasable-woocommerce-products'),
		);

		// Hide the default WooCommerce message wrapper
		$settings_npwp[] = array(
			'name' => __('Hide the default WooCommerce message wrapper style?', 'non-purchasable-woocommerce-products'),
			'desc_tip' => __('Hide the default "woocommerce-info" message wrapper and just display the raw HTML in the message box above.', 'non-purchasable-woocommerce-products'),
			'id' => 'gp_npwp_hide_wrapper',
			'type' => 'checkbox',
		);

		// Hide the message
		$settings_npwp[] = array(
			'name' => __('Hide the product page message?', 'non-purchasable-woocommerce-products'),
			'id' => 'gp_npwp_hide_message',
			'type' => 'checkbox',
		);
		
		// Hide the "Purchasable" icon in product table
		$settings_npwp[] = array(
			'name' => __('Hide the "purchasable" icon in the admin product table?', 'non-purchasable-woocommerce-products'),
			'id' => 'gp_npwp_hide_admin_column',
			'type' => 'checkbox',
		);
		
		$settings_npwp[] = array(
			'type' => 'sectionend', 
			'id' => 'non-purchasable'
		);

		return $settings_npwp;
	} else {
		return $settings;
	}
}
