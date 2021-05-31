<?php if ( __FILE__ == $_SERVER['SCRIPT_FILENAME'] ) { exit; }

// Check if the product is purchasable

add_filter('woocommerce_is_purchasable', 'gp_npwp_set_non_purchasable', 99, 2);

function gp_npwp_set_non_purchasable($is_purchasable, $product) {

    $not_buyable = get_post_meta( get_the_ID(), '_not_buyable', true );

  if ( $not_buyable === 'yes' ) {
  	return false;
  } else {
  	return true;
  }

}


// Modify the button text for these products

add_filter( 'woocommerce_product_add_to_cart_text', 'gp_npwp_button_text' );

function gp_npwp_button_text( $buttonText ) {

    $not_buyable = get_post_meta( get_the_ID(), '_not_buyable', true );

    $newButtonText = get_option( 'gp_npwp_new_button_text' );

  	if ( isset( $newButtonText ) && !empty( $newButtonText ) ) {
    	$addtocartButtonText = $newButtonText;
    } else {
    	$addtocartButtonText = __( 'View product', 'woocommerce' );
    }

    if ( $not_buyable === 'yes' ) {
        $buttonText = $addtocartButtonText;
    }

    return $buttonText;

}


// Display a custom (or default if none set) message to visitors

add_action( 'woocommerce_single_product_summary', 'gp_npwp_custom_message', 30 );

function gp_npwp_custom_message() {

  $cartButtonHide = '<style>form.variations_form .woocommerce-variation-add-to-cart { display:none!important;}</style>';

    $not_buyable = get_post_meta( get_the_ID(), '_not_buyable', true );

    $newMessageText = get_option( 'gp_npwp_new_message_text' );
    $hideMessage = get_option( 'gp_npwp_hide_message' );
    $hideWrapper = get_option( 'gp_npwp_hide_wrapper' );

  	if ( isset( $newMessageText ) && !empty( $newMessageText ) ) {
    	$newMessage = $newMessageText;
    } else {
    	$newMessage = __( 'This item cannot be purchased online.', 'woocommerce' );
    }


  	if ( $hideMessage !== 'yes' ) {

	    if ( $not_buyable === 'yes' ) {

        if ( $hideWrapper !== 'yes' ) {
	        echo '<div class="woocommerce npwpMessage"><div class="woocommerce-info" style="margin-bottom: 0px;">' . $newMessage . '</div></div>' . $cartButtonHide;
        } else {
          echo $newMessage . $cartButtonHide;
        }

	    }

  	}
}

?>