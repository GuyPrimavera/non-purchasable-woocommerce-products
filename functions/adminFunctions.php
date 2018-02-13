<?php if ( __FILE__ == $_SERVER['SCRIPT_FILENAME'] ) { exit; }

// Add Settings link to Plugins page

function gp_npwp_action_links( $links ) {

   $gp_npwp_links[] = '<a href="'. esc_url( get_admin_url(null, 'admin.php?page=wc-settings&tab=products&section=non-purchasable') ) .'">Settings</a>';
   //$gp_npwp_links[] = '<a href="http://www.google.com" target="_blank">Google</a>'; // Test

   return array_merge( $gp_npwp_links, $links );

}


// Show the checkbox on product pages

add_action( 'woocommerce_product_options_general_product_data', 'gp_npwp_show_checkbox' );

function gp_npwp_show_checkbox() {

    woocommerce_wp_checkbox(
        array(
            'id'            => '_not_buyable',
            'label'         => __( 'Non-Purchasable', 'woocommerce' ),
            'description'   => __( '', 'woocommerce' )
            )
    );

}


// Save the checkbox value to the database

add_action( 'woocommerce_process_product_meta', 'gp_npwp_save_checkbox' );

function gp_npwp_save_checkbox( $post_id ) {

    $woocommerce_checkbox = isset( $_POST['_not_buyable'] ) ? 'yes' : 'no';
    update_post_meta( $post_id, '_not_buyable', $woocommerce_checkbox );

}

?>