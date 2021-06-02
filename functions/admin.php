<?php if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) { exit; }

// Admin scripts
function gp_npwp_load_scripts() {
	wp_register_style('gp_npwp', plugins_url(GP_NPWP_DOMAIN . '/css/npwp.css'), array(), $GLOBALS['gp_npwp_version']);
	wp_enqueue_style('gp_npwp');
}
add_action('admin_enqueue_scripts', 'gp_npwp_load_scripts', 99);

// Add Settings link to Plugins page
function gp_npwp_action_links($links) {
  $gp_npwp_links[] = '<a href="'. esc_url(get_admin_url(null, 'admin.php?page=wc-settings&tab=products&section=non-purchasable')) .'">Settings</a>';
  
  return array_merge($gp_npwp_links, $links);
}

function np_npwp_row_meta($links, $file) {
  if ($file == plugin_basename(dirname(GP_NPWP_PLUGIN_FULL_PATH) .'/'.GP_NPWP_PLUGIN_FILE)) {
    $links[] = '<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=YVPWSJB4SPN5N" target="_blank">' . __('Support this plugin', 'non-purchasable-woocommerce-products') . '</a>';
    $links[] = '<a href="https://wordpress.org/plugins/' . GP_NPWP_DOMAIN . '/#developers" target="_blank">' . __('Changelog', 'non-purchasable-woocommerce-products') . '</a>';
  }

  return $links;
}

// Create custom WooCommerce data tab
add_filter('woocommerce_product_data_tabs', 'gp_npwp_admin_tab');
function gp_npwp_admin_tab($tabs) {
  $tabs['gp_npwp'] = array(
    'label' => __('Non-Purchasable', 'non-purchasable-woocommerce-products'),
    'target' => 'gp_npwp_tab',
    'class' => array('gp_npwp_tab', 'show_if_simple', 'show_if_variable', 'show_if_grouped', 'show_if_external', 'show_if_virtual', 'show_if_downloadable'),
    'priority' => 80,
  );
  return $tabs;
}

add_action('woocommerce_product_data_panels', 'gp_npwp_tab_content');
function gp_npwp_tab_content() { ?>

<div id='gp_npwp_tab' class='panel woocommerce_options_panel'>
  <div class="options_group">
    <?php
    woocommerce_wp_checkbox(array(
      'id' => '_not_buyable',
      'label' => __('Non-Purchasable', 'woocommerce'),
      'description' => __('<a href="'. admin_url() .'admin.php?page=wc-settings&tab=products&section=non-purchasable" target="_blank">Settings</a>', 'woocommerce')
    ));
    ?>
  </div>
</div>

<?php }

// Save the checkbox value to the database
add_action('woocommerce_process_product_meta', 'gp_npwp_save_checkbox');
function gp_npwp_save_checkbox($post_id) {
  $woocommerce_checkbox = isset($_POST['_not_buyable']) ? 'yes' : 'no';
  update_post_meta($post_id, '_not_buyable', $woocommerce_checkbox);
}

// Add icon on "All Products" screen
if (!gp_npwp_get_truthy('hide_admin_column', null)) {
  add_action('manage_product_posts_custom_column', 'gp_npwp_table_icon_display', 12, 2);
}

function gp_npwp_table_icon_display($column_name, $post_id) {
  if ($column_name === 'name') {
    if (gp_npwp_is_purchasable($post_id)) {
      echo '<span class="woocommerce-help-tip dashicons dashicons-yes gp_npwp" data-tip="'. __('Purchasable', 'non-purchasable-woocommerce-products') .'"></span>';
    } else {
      echo '<span class="woocommerce-help-tip dashicons dashicons-no gp_npwp" data-tip="'. __('Not purchasable', 'non-purchasable-woocommerce-products') .'"></span>';
    }
  }
}
