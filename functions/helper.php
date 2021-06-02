<?php if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) { exit; }

function gp_npwp_is_purchasable($product_id) {
  return !gp_npwp_get_truthy('_not_buyable', $product_id);
}

function gp_npwp_get_message_text($hideWrapper) {
  $text = get_option('gp_npwp_new_message_text');
  $wrapper = array(
    '<div class="woocommerce npwpMessage"><div class="woocommerce-info" style="margin-bottom: 0px;">',
    '</div></div>'
  );
  $cartButtonHide = '<style>form.variations_form .woocommerce-variation-add-to-cart { display:none!important; }</style>';

  if (!isset($text) || empty($text)) {
    $text = __('This item cannot be purchased online.', 'woocommerce');
  }

  return ($hideWrapper === 'yes' || (!!$hideWrapper && $hideWrapper !== 'no'))
    ? $text . $cartButtonHide
    : $wrapper[0] . $text . $wrapper[1] . $cartButtonHide;
}

function gp_npwp_hide_message() {
  return gp_npwp_get_truthy('gp_npwp_hide_message', null);
}

function gp_npwp_get_truthy($prop, $product) {
  $prop_value = !!$product ? get_post_meta($product, $prop, true) : get_option($prop);
  return ($prop_value === 'yes' || $prop_value === 'true' || (!!$prop_value && $prop_value !== 'no' && $prop_value !== 'false')) 
    ? true 
    : false;
}
