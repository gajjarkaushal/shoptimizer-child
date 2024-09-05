<?php 
if(class_exists('WC_Checkout')) {
    /**
     * Class GGRVCheckout
     *
     * @package GGRV\Woocommerce
     *
     * @since 1.0.0
     */
    class GGRVCheckout extends WC_Checkout {
        /**
         * Call a protected method from the parent class.
         *
         * @return mixed The value returned from the protected method.
         */
        public function call_protected_method() {
            // Call the protected method from the parent class
            return $this->protected_method_name();
        }
        /**
         * Validate checkout data and send an AJAX failure response if there are errors.
         *
         * @param array $posted_data Posted checkout data.
         * @param WP_Error $errors WP_Error object to store any errors.
         */
        function GGRV_Validate($posted_data,$errors){
            // Update session for customer and totals.
            $this->update_session( $posted_data );

            unset($posted_data['terms-field']);
            unset($posted_data['terms']);
            unset($posted_data['woocommerce_checkout_update_totals']);

            // Validate posted data and cart items before proceeding.
            $this->validate_checkout( $posted_data, $errors );

            foreach ( $errors->errors as $code => $messages ) {
                $data = $errors->get_error_data( $code );
                foreach ( $messages as $message ) {
                    wc_add_notice( $message, 'error', $data );
                }
            }
            if(0 === wc_notice_count( 'error' )){
                return true;
            }else{
                $this->send_ajax_failure_response();            
            }
            exit();
            
        }
    }    
}

