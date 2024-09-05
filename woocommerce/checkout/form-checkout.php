<?php
/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'woocommerce_before_checkout_form', $checkout );

// If checkout registration is disabled and not logged in, the user cannot checkout.
if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
	echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) ) );
	return;
}
$default_active = 'step1';
$step_number = 1;
if(isset($_GET['step'])  && is_numeric($_GET['step'])){
	$default_active = 'step'.$_GET['step'];
	$step_number = $_GET['step'];
}
?>

<form name="checkout" method="post" class="checkout woocommerce-checkout"
    action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">
    <div class="ggrv-loader"><span class="loader"></span></div>
    <div class="row">
        <?php if ( $checkout->get_checkout_fields() ) : ?>

        <?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>
        <div class="col-12 ggrvcheckout-tab ggrv-step1 <?php echo $default_active == 'step1' ? 'active' : 'ggrv-hide'; ?>"
            id="customer_details">
            <div class="ggrv_col2-set" id="customer_details">
                <div class="col-1">
                    <?php do_action( 'woocommerce_checkout_billing' ); ?>
                </div>

                <div class="col-2">
                    <?php do_action( 'woocommerce_checkout_shipping' ); ?>
                </div>
            </div>
        </div>

        <?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

        <?php endif; ?>
        <div
            class="col-12 ggrvcheckout-tab ggrv-step2 <?php echo $default_active == 'step2' ? 'active' : 'ggrv-hide'; ?>">
            <?php do_action( 'woocommerce_checkout_before_order_review_heading' ); ?>

            <h3 id="order_review_heading"><?php esc_html_e( 'Your order', 'woocommerce' ); ?></h3>

            <?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

            <div id="order_review" class="woocommerce-checkout-review-order">
                <?php do_action( 'woocommerce_checkout_order_review' ); ?>
            </div>
        </div>
        <div
            class="col-12 ggrvcheckout-tab ggrv-step3 <?php echo $default_active == 'step3' ? 'active' : 'ggrv-hide'; ?>">
            <?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
        </div>
    </div>
    <div class="ggrv-action-btn <?php echo $default_active == 'step3' ? 'ggrv-hide' :  'active'; ?>"
        step="<?php echo $step_number; ?>">
        <button type="button" class="button checkout-btn checkout-back-button btn" style="display:none;"><?php _e('Back','shoptimizer'); ?></button>
        <button type="button" class="button checkout-btn checkout-next-button btn"><?php _e('Next','shoptimizer'); ?></button>
    </div>
</form>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>