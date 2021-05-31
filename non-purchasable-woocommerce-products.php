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

add_action('admin_init', 'require_woocommerce_install');
function require_woocommerce_install() {
  if (is_admin() && current_user_can('activate_plugins') &&  !is_plugin_active('woocommerce/woocommerce.php')) {
    add_action('admin_notices', 'no_woocommerce_notice');
    deactivate_plugins(plugin_basename(__FILE__)); 

    if (isset($_GET['activate'])) {
      unset($_GET['activate']);
    }
  }
}

function no_woocommerce_notice(){
  echo '<div class="error"><p>Non-Purchasable WooCommerce Products requires <a href="https://wordpress.org/plugins/woocommerce/" target="_blank">WooCommerce</a> to be installed and active to function.</p></div>';
}

include ('functions/adminFunctions.php');
include ('functions/frontFunctions.php');
include ('options/options.php');

// Plugin action links
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'gp_npwp_action_links');

// TODO: Purchasable column in products table 
// TODO: WPML string translation
// TODO: Currencyswitcher hide price
// TODO: Hide stock status option
// TODO: Option to reverse how it works?
// TODO: Test and update for latest WC versions
// TODO: Elementor: https://wordpress.org/support/topic/no-display-with-elementor-product-template-2/
// TODO: Shortcode for list of non-purch products

