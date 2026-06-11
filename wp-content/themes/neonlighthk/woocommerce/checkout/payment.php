<?php
/**
 * Checkout Payment
 * @package NeonLightHK
 */

defined( 'ABSPATH' ) || exit;

if ( ! is_ajax() ) {
    do_action( 'woocommerce_review_order_before_payment' );
}
?>

<div id="payment" class="woocommerce-checkout-payment nl-checkout-payment">
    <?php if ( WC()->cart->needs_payment() ) : ?>
        <ul class="wc_payment_methods payment_methods methods">
            <?php
            if ( ! empty( $available_gateways ) ) {
                foreach ( $available_gateways as $gateway ) {
                    wc_get_template( 'checkout/payment-method.php', array( 'gateway' => $gateway ) );
                }
            } else {
                echo '<li class="woocommerce-notice woocommerce-notice--info woocommerce-info">' . apply_filters( 'woocommerce_no_available_payment_methods_message', WC()->cart->needs_payment() ? __( 'Sorry, it seems that there are no available payment methods for your state. Please contact us if you require assistance or wish to make alternate arrangements.', 'woocommerce' ) : __( 'Your order is free! No payment is required.', 'woocommerce' ) ) . '</li>';
            }
            ?>
        </ul>
    <?php endif; ?>

    <div class="form-row place-order">
        <noscript>
            <?php
            printf(
                esc_html__( 'Since your browser does not support JavaScript, or it is disabled, please ensure you click the %1$sUpdate Totals%2$s button before placing your order. You may be charged more than the amount stated above if you fail to do so.', 'woocommerce' ),
                '<em>',
                '</em>'
            );
            ?>
            <br/><input type="submit" class="button alt" name="woocommerce_checkout_update_totals" value="<?php esc_attr_e( 'Update totals', 'woocommerce' ); ?>" />
        </noscript>

        <?php wc_get_template( 'checkout/terms.php' ); ?>

        <?php do_action( 'woocommerce_review_order_before_submit' ); ?>

        <?php echo apply_filters( 'woocommerce_order_button_html', '<button type="submit" class="button alt" name="woocommerce_checkout_place_order" id="place_order" value="' . esc_attr( $order_button_text ) . '" data-value="' . esc_attr( $order_button_text ) . '" data-hktpl-gateway="' . esc_attr( wp_json_encode( array() ) ) . '" data-hktms-gateway="' . esc_attr( wp_json_encode( array() ) ) . '" data-gateway-providers="hktms,hktpl">' . esc_html( $order_button_text ) . '</button>' ); ?>

        <?php do_action( 'woocommerce_review_order_after_submit' ); ?>

        <?php wp_nonce_field( 'woocommerce-process_checkout', 'woocommerce-process-checkout-nonce' ); ?>
    </div>
</div>

<?php
if ( ! is_ajax() ) {
    do_action( 'woocommerce_review_order_after_payment' );
}
