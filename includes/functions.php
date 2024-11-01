<?php
/**
 * Functions.
 * 
 * @package Dkjensen\WCOPC
 */

namespace Dkjensen\WCOPC;

/**
 * Generate array of product IDs and product counts
 *
 * @param array $orders Array of orders.
 * @return array
 */
function get_order_product_count( array $orders ) {
    $total = array();

    foreach( $orders as $order ) {
        $order = new \WC_Order( $order );
        $items = $order->get_items();

        foreach( $items as $item ) {
            $product_id = ! empty( $item['variation_id'] ) ? $item['variation_id'] : $item['product_id'];

            $total[$product_id] = isset( $total[$product_id] ) ? $total[$product_id] + $item['qty'] : $item['qty'];
        }
    }
    
    return $total;
}