<?php
/*
 * Plugin Name: AQBANK WooCommerce Custom Payment Gateway
 * Plugin URI: 
 * Description: Gateway
 * Author: AqBank Digital
 * Author URI:
 * Version: 1.0.1
 */

 /*
 * This action hook registers our PHP class as a WooCommerce payment gateway
 */
add_filter( 'woocommerce_payment_gateways', 'aqbank_add_gateway_class' );
function aqbank_add_gateway_class( $gateways ) {
    $gateways[] = 'Aqbank_Payment_Gateway'; // your class name is here
    return $gateways;
}

/*
 * The class itself, please note that it is inside plugins_loaded action hook
 */
add_action( 'plugins_loaded', 'aqbank_init_gateway_class' );
function aqbank_init_gateway_class() {
    // Include our payment gateway class file
    require_once 'aqbank_payment_gateway_class.php';
}