<?php
function my_theme_enqueue_styles() {
    $parent_style = 'shoptimizer'; // This is 'parent-style' for the Twenty Seventeen theme.
    wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( $parent_style ),
        wp_get_theme()->get('Version')
    );
    wp_enqueue_script(
        'checkout-script',
        get_stylesheet_directory_uri() . '/js/checkout.js',
        array(),
        time(),
        true
    );
    wp_localize_script(
        'checkout-script',
        'checkoutAjax',
        array(
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
            'checkoutnonce'=>wp_create_nonce('ggrv_checkout_nonce'),
            'cartpageurl'=>wc_get_cart_url(),
            'checkoutpage'=>wc_get_checkout_url()
        )
    );

}
add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );
require_once get_stylesheet_directory() . '/class/cls.woocommerce-checkout.php';
require_once get_stylesheet_directory() . '/class/cls.woocommerce-hooks.php';

?>