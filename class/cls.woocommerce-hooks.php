<?php

if(!class_exists('GGWoocommerceHooks')) {
    /**
     * Class GGWoocommerceHooks
     *
     * Hooks into ajax call for checkout next button and cart needs payment filter
     *
     * @package GGRV\Woocommerce
     *
     * @since 1.0.0
     */
    class GGWoocommerceHooks {
        /**
         * Constructor
         *
         * Hooks into ajax call for checkout next button
         *
         * @return void
         */
        function __construct() {
            add_action('wp_ajax_ggrv_checkout_next', array($this, 'ggrv_checkout_next_callback'), 1);
            add_action('wp_ajax_nopriv_ggrv_checkout_next', array($this, 'ggrv_checkout_next_callback'), 1);
            add_filter( 'woocommerce_cart_needs_payment', array($this,'ggrv_woocommerce_cart_needs_payment'), 10, 2 );
            // add_filter( 'woocommerce_payment_complete_order_status', array($this, 'ggrv_woocommerce_payment_complete_order_status'), 10, 3 );
            add_filter( 'woocommerce_checkout_no_payment_needed_redirect', array($this, 'ggrv_woocommerce_checkout_no_payment_needed_redirect'), 10, 2 );
            add_filter( 'woocommerce_valid_order_statuses_for_payment', array($this,'ggrv_woocommerce_valid_order_statuses_for_payment'), 10, 2 );
        }
        /**
         * Filters the valid order statuses for payment.
         *
         * @param string      $status     The current status.
         * @param WC_Order    $orderobj   The order object.
         *
         * @return string The new status.
         */

        function ggrv_woocommerce_valid_order_statuses_for_payment($status,$orderobj) { 
            

            if( empty($orderobj->get_payment_method()) && empty($orderobj->get_payment_method_title())){
                $status[] = 'processing';
            }
            return $status;
        }
        /**
         * Filters the redirect URL for the no payment needed case.
         *
         * @param string $redirectUrl The current redirect URL.
         * @param WC_Order $order      The order object.
         *
         * @return string The new redirect URL.
         */
        function ggrv_woocommerce_checkout_no_payment_needed_redirect($redirectUrl, $order){
            return $order->get_checkout_payment_url();
        }
        /**
         * Filters the order status after payment complete.
         *
         * Checks if the current status is pending and returns 'pending' if true.
         * Otherwise, returns the original status.
         *
         * @param string $payment_Status The original status.
         * @param int    $order_id       The order ID.
         * @param WC_Order $orderdata     The order object.
         *
         * @return string The new status.
         */
        function ggrv_woocommerce_payment_complete_order_status($payment_Status, $order_id, $orderdata){
            
            if($orderdata->get_status() == 'pending'){
                return 'pending';
            }
            return $payment_Status;
        }

        /**
         * Ajax callback function for checkout next button
         *
         * @return void
         */
        public function ggrv_checkout_next_callback() {
            // Verify nonce
            check_ajax_referer('ggrv_checkout_nonce', 'nonce');
            $step = isset($_POST['step']) ? $_POST['step'] : 0;
            parse_str($_POST['form'], $_POST);
            
            $ggrvCout = new GGRVCheckout();
            $errors      = new WP_Error();
            
            // Get posted data
            $posted_data = $ggrvCout->get_posted_data();
            
            // Validate posted data
            if($ggrvCout->GGRV_Validate($posted_data, $errors)){
                $step++;
                wp_send_json_success(array('step'=>$step));
            }
            
            // Exit ajax request
            exit();
        }
        /**
         * Always return false for the cart needs payment filter.
         *
         * @param bool   $needs_payment Does the cart need payment.
         * @param object $cart          The cart object.
         *
         * @return bool False.
         */
        function ggrv_woocommerce_cart_needs_payment($needs_payment, $cart) {
            return false;
        }
    }
    new GGWoocommerceHooks();
}