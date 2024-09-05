(function ($) {
    var GgrvCheckout = {
        init: function () {
            var self = this;
            GgrvCheckout.NouEvent();
        },
        NouEvent: function () {
            setTimeout(function(){
                jQuery(document).on('click', '.checkout-next-button', GgrvCheckout.GgrvNextEvent);
                jQuery(document).on('click', '.checkout-back-button', GgrvCheckout.GgrvBackEvent);
            },200)
        },
        GgrvNextEvent: function (e) {
            jQuery('.ggrv-loader').show();
            jQuery('.woocommerce-notices-wrapper').html('');
            jQuery.ajax({
                type: "POST",
                url: checkoutAjax.ajaxurl,
                data: {
                    action: 'ggrv_checkout_next',
                    form: jQuery('form[name="checkout"]').serialize(),
                    step:jQuery('.ggrv-action-btn').attr('step'),
                    nonce: checkoutAjax.checkoutnonce
                },
                success: function (data) {
                    if (data == 'success') {
                        // window.location.href = window.location.href + '?step=2';
                    }
                    if (data.success) {
                        if(data.data.step){
                            jQuery('.ggrv-action-btn').attr('step',data.data.step);
                            jQuery('.ggrvcheckout-tab').hide();
                            jQuery('.ggrv-step'+data.data.step).show();
                        }
                        if(data.data.step == 2){
                            jQuery('.ggrv-action-btn').hide();
                            jQuery('.ggrv-submit-btn').removeClass('ggrv-hide');
                            jQuery('.woocommerce-NoticeGroup.woocommerce-NoticeGroup-updateOrderReview').html('');

                        }
                        jQuery("html, body").animate({ scrollTop: $('form.checkout.woocommerce-checkout').offset().top}, "slow");
                    } else {
                        if ( data && 'failure' === data.result ) {

                            var $form = $( 'form.checkout' );
    
                            // Remove notices from all sources
                            $( '.woocommerce-error, .woocommerce-message, .is-error, .is-success' ).remove();
    
                            // Add new errors returned by this event
                            if ( data.messages ) {
                                $form.prepend( '<div class="woocommerce-NoticeGroup woocommerce-NoticeGroup-updateOrderReview">' + data.messages + '</div>' ); // eslint-disable-line max-len
                            } else {
                                $form.prepend( data );
                            }
    
                            // Lose focus for all fields
                            $form.find( '.input-text, select, input:checkbox' ).trigger( 'validate' ).trigger( 'blur' );
    
                            GgrvCheckout.scroll_to_notices();
                        }
                    }
                    jQuery('.ggrv-loader').hide();
                }
            })
        },
        GgrvBackEvent: function (e) {
            jQuery('.ggrv-loader').show();
            if(jQuery('.ggrv-action-btn').attr('step') > 1){
                var prevnum = parseInt(jQuery('.ggrv-action-btn').attr('step')) - 1
                window.location.href = checkoutAjax.checkoutpage + '?step='+prevnum;
            }else{
                window.location.href = checkoutAjax.cartpageurl;
            }
            jQuery('.ggrv-loader').hide();
        },
        scroll_to_notices: function() {
			var scrollElement           = $( '.woocommerce-NoticeGroup-updateOrderReview, .woocommerce-NoticeGroup-checkout' );

			if ( ! scrollElement.length ) {
				scrollElement = $( 'form.checkout' );
			}
			$.scroll_to_notices( scrollElement );
		}
    }
    GgrvCheckout.init();
})(jQuery);
