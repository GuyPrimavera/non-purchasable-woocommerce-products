<?php if ( __FILE__ == $_SERVER['SCRIPT_FILENAME'] ) { exit; }

// Add Settings link to Plugins page

function gp_npwp_action_links( $links ) {

   $gp_npwp_links[] = '<a href="'. esc_url( get_admin_url(null, 'admin.php?page=wc-settings&tab=products&section=non-purchasable') ) .'">Settings</a>';
   $gp_npwp_links[] = '<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=YVPWSJB4SPN5N" target="_blank">Donate</a>';

   return array_merge( $gp_npwp_links, $links );

}
// Create custom WooCommerce data tab

add_filter( 'woocommerce_product_data_tabs', 'gp_npwp_admin_tab' );
function gp_npwp_admin_tab( $tabs ) {
    $tabs['gp_npwp'] = array(
        'label'         => __( 'Non-Purchasable', 'non-purchasable-woocommerce-products' ),
        'target'        => 'gp_npwp_tab',
        'class'         => array( 'gp_npwp_tab', 'show_if_simple', 'show_if_variable', 'show_if_grouped', 'show_if_external', 'show_if_virtual', 'show_if_downloadable'),
        'priority'      => 80,
    );
    return $tabs;
}

add_action( 'woocommerce_product_data_panels', 'gp_npwp_tab_content' );
function gp_npwp_tab_content() { ?>

<div id='gp_npwp_tab' class='panel woocommerce_options_panel'>
    <div class="options_group">
        <?php
        woocommerce_wp_checkbox(
            array(
                'id'            => '_not_buyable',
                'label'         => __( 'Non-Purchasable', 'woocommerce' ),
                'description'   => __( '<a href="'. admin_url() .'admin.php?page=wc-settings&tab=products&section=non-purchasable" target="_blank">Settings</a>', 'woocommerce' )
                )
        );
        ?>
    </div>
</div>

<?php }

// Show the checkbox on product pages

/*
add_action( 'woocommerce_product_options_advanced', 'gp_npwp_show_checkbox' );

function gp_npwp_show_checkbox() {

    woocommerce_wp_checkbox(
        array(
            'id'            => '_not_buyable',
            'label'         => __( 'Non-Purchasable', 'woocommerce' ),
            'description'   => __( '', 'woocommerce' )
            )
    );

}
*/

// TODO: Custom product type
/*
add_filter("product_type_options", function ($product_type_options) {

    $non_purch_inherit = isset( $_POST['_not_buyable'] ) ? 'yes' : 'no'; 

    $product_type_options["_type_not_buyable"] = [
        "id"            => "_type_not_buyable",
        "wrapper_class" => "show_if_simple show_if_variable show_if_grouped show_if_external show_if_virtual show_if_downloadable",
        "label"         => "Non-Purchasable",
        "description"   => "Non-Purchasable Product?",
        "default"       => $non_purch_inherit,
    ];

    return $product_type_options;

});

// Save product type options

add_action("save_post_product", function ($post_ID, $product, $update) {

    update_post_meta(
          $product->ID
        , "_type_not_buyable"
        , isset($_POST["_type_not_buyable"]) ? "yes" : "no"
    );

}, 10, 3);
*/

// Save the checkbox value to the database

add_action( 'woocommerce_process_product_meta', 'gp_npwp_save_checkbox' );

function gp_npwp_save_checkbox( $post_id ) {

    $woocommerce_checkbox = isset( $_POST['_not_buyable'] ) ? 'yes' : 'no';
    update_post_meta( $post_id, '_not_buyable', $woocommerce_checkbox );

}

// Add column on "All Products" screen

$hideColumn = get_option( 'gp_npwp_hide_admin_column' );

if ( $hideColumn !== 'yes' ) {
  add_filter( 'manage_product_posts_columns', 'gp_npwp_add_column' );
  add_action( 'manage_product_posts_custom_column', 'gp_npwp_quickedit_custom_column_display', 10, 2 );
}

function gp_npwp_add_column( $posts_columns ) {
    $posts_columns['_not_buyable'] = __( 'Purchasable?', 'non-purchasable-woocommerce-products' );
    return $posts_columns;
}

function gp_npwp_quickedit_custom_column_display( $column_name, $post_id ) {
    if ( '_not_buyable' == $column_name ) {
        $_not_buyable = get_post_meta( $post_id, '_not_buyable', true );

        if ( $_not_buyable ) {
            //echo esc_html( $_not_buyable );
            echo '<span class="dashicons dashicons-no"></span>';
        } else {
            //esc_html_e( 'N/A', 'non-purchasable-woocommerce-products' );
            echo '<span class="dashicons dashicons-yes"></span>';
        }
    }
}

?>