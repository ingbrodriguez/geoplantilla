<?php
/**
 * The template for displaying demo plugin content.
 *
 * Override this template by copying it to yourtheme/dc-product-vendor/emails/plain/vendor-new-order.php
 *
 * @author 		WC Marketplace
 * @package 	dc-product-vendor/Templates
 * @version   0.0.1
 */
 
 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $WCMp;
echo $email_heading . "\n\n";

echo sprintf( __( 'A new order was received and marked as completed from %s. Their order is as follows:',  'dc-woocommerce-multi-vendor' ), $order->get_billing_first_name() . ' ' . $order->get_billing_last_name() ) . "\n\n";

echo "****************************************************\n\n";

do_action( 'woocommerce_email_before_order_table', $order, $sent_to_admin, $plain_text );

echo sprintf( __( 'Order Number: %s',  'dc-woocommerce-multi-vendor'), $order->get_order_number() ) . "\n";
echo sprintf( __( 'Order Link: %s',  'dc-woocommerce-multi-vendor'), admin_url( 'post.php?post=' . $order->get_id() . '&action=edit' ) ) . "\n";
echo sprintf( __( 'Order Date: %s',  'dc-woocommerce-multi-vendor'), date_i18n( __( 'jS F Y',  'dc-woocommerce-multi-vendor' ), strtotime( $order->get_date_created() ) ) ) . "\n";

do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text );

$vendor = new WCMp_Vendor( absint( $vendor_id ) );
$vendor_items_dtl = $vendor->plain_vendor_order_item_table($order, $vendor_id); 
echo $vendor_items_dtl;

echo "----------\n\n";
$show_cust_order_calulations_field = apply_filters('show_cust_order_calulations_field', true);
if($WCMp->vendor_caps->vendor_capabilities_settings('show_cust_order_calulations') && $show_cust_order_calulations_field) {
	
	if ( $totals = $vendor->wcmp_vendor_get_order_item_totals($order, $vendor_id) ) {
		foreach ( $totals as $total ) {
			echo $total['label'] . "\t " . $total['value'] . "\n";
		}
	}
}

echo "\n****************************************************\n\n";

do_action( 'woocommerce_email_after_order_table', $order, $sent_to_admin, $plain_text );
$show_cust_add_field = apply_filters('show_cust_add_field', true);
$show_customer_detail = $WCMp->vendor_caps->vendor_capabilities_settings('show_cust_add');
if($show_customer_detail && $show_cust_add_field) {
	echo __( 'Customer Details',  'dc-woocommerce-multi-vendor' ) . "\n";

	if ( $order->get_billing_email() )
		echo __( 'Email:',  'dc-woocommerce-multi-vendor' ); echo $order->get_billing_email() . "\n";

	if ( $order->get_billing_phone() )
		echo __( 'Telephone:',  'dc-woocommerce-multi-vendor' ); ?> <?php echo $order->get_billing_phone() . "\n";
}

$show_cust_billing_add_field = apply_filters('show_cust_billing_add_field', true);
$show_cust_shipping_add_field = apply_filters('show_cust_shipping_add_field', true);
$show_cust_billing_add =  $WCMp->vendor_caps->vendor_capabilities_settings('show_cust_billing_add');
$show_cust_shipping_add =  $WCMp->vendor_caps->vendor_capabilities_settings('show_cust_shipping_add');
if($show_cust_billing_add && $show_cust_billing_add_field) {
	echo "\n" . __( 'Billing Address',  'dc-woocommerce-multi-vendor' ) . ":\n";
	echo $order->get_formatted_billing_address() . "\n\n";
}
if($show_cust_shipping_add && $show_cust_shipping_add_field) {
	if ( get_option( 'woocommerce_ship_to_billing_address_only' ) == 'no' && ( $shipping = $order->get_formatted_shipping_address() ) ) {
	
		echo __( 'Shipping Address',  'dc-woocommerce-multi-vendor' ) . ":\n";
	
		echo $shipping . "\n\n";
	
	}
}

echo "\n****************************************************\n\n";

echo apply_filters( 'wcmp_email_footer_text', get_option( 'wcmp_email_footer_text' ) );