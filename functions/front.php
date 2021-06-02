<?php if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) { exit; }

// Check if the product is purchasable
function gp_npwp_set_non_purchasable() {
  return gp_npwp_is_purchasable(get_the_ID());
}
add_filter('woocommerce_is_purchasable', 'gp_npwp_set_non_purchasable', 99, 2);

// Modify the button text for these products
function gp_npwp_button_text($buttonText) {
  $newButtonText = get_option('gp_npwp_new_button_text');

  if (isset($newButtonText) && !empty($newButtonText)) {
    $addtocartButtonText = $newButtonText;
  } else {
    $addtocartButtonText = __('View product', 'woocommerce');
  }

  if (!gp_npwp_is_purchasable(get_the_ID())) {
    $buttonText = $addtocartButtonText;
  }

  return $buttonText;
}
add_filter('woocommerce_product_add_to_cart_text', 'gp_npwp_button_text');

// Display a custom (or default if none set) message to visitors
function gp_npwp_custom_message() {
  if (!gp_npwp_hide_message() && !gp_npwp_is_purchasable(get_the_ID())) {
    echo gp_npwp_get_message_text(get_option('gp_npwp_hide_wrapper'));
  }
}
add_action('woocommerce_single_product_summary', 'gp_npwp_custom_message', 30);
