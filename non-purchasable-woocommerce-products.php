<?php
/**
 * Plugin Name: Non-Purchasable WooCommerce Products
 * Plugin URI: https://wordpress.org/plugins/non-purchasable-woocommerce-products/
 * Description: Allow selected WooCommerce products to be non-purchasable, display-only products, with a custom message shown to the customer.
 * Author: Guy Primavera
 * Author URI: https://guyprimavera.com/
 * Version: 1.4
 * WC requires at least: 3.0.0
 * WC tested up to: 5.3
 * Text Domain: non-purchasable-woocommerce-products
 * License: GPL2
 *
 */

if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) { exit; }

define('GP_NPWP_PLUGIN_FULL_PATH', __FILE__);
define('GP_NPWP_PLUGIN_FILE', basename(__FILE__));
define('GP_NPWP_DOMAIN', 'non-purchasable-woocommerce-products');

$gp_npwp_version = 0;

function gp_npwp_get_plugin_info() {
  if (function_exists('get_plugins')) {
    $gp_npwp = get_plugins()[GP_NPWP_DOMAIN . '/' . GP_NPWP_PLUGIN_FILE];
    $gp_npwp_version = $gp_npwp['Version'];
  }
}
add_action( 'plugins_loaded', 'gp_npwp_get_plugin_info' );

function gp_npwp_require_woocommerce_install() {
  if (is_admin() && current_user_can('activate_plugins') &&  !is_plugin_active('woocommerce/woocommerce.php')) {
    add_action('admin_notices', 'no_woocommerce_notice');
    deactivate_plugins(plugin_basename(__FILE__)); 

    if (isset($_GET['activate'])) {
      unset($_GET['activate']);
    }
  }
}
add_action('admin_init', 'gp_npwp_require_woocommerce_install');

function no_woocommerce_notice() {
  echo '<div class="error"><p>Non-Purchasable WooCommerce Products requires <a href="https://wordpress.org/plugins/woocommerce/" target="_blank">WooCommerce</a> to be installed and active to function.</p></div>';
}

include ('functions/helper.php');
include ('functions/admin.php');
include ('functions/front.php');
include ('options/options.php');

// Plugin action links
function gp_npwp_load_meta_links() {
  if (is_plugin_active(GP_NPWP_DOMAIN . '/' . GP_NPWP_PLUGIN_FILE)) {
    add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'gp_npwp_action_links');
    add_filter('plugin_row_meta', 'np_npwp_row_meta', 10, 2);
  }
}
add_action('admin_init', 'gp_npwp_load_meta_links');
