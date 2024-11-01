<?php
/**
 * Callbacks.
 * 
 * @package Dkjensen\WCOPC
 */

namespace Dkjensen\WCOPC;

/**
 * Product thumbnail column
 *
 * @param int $product_id       Product ID.
 * @param int $count            Product count.
 * @param array $orders         Orders array.
 * @param WC_Product $product   Product object.
 * @return string
 */
function column_thumbnail_callback( $product_id, $count, $orders, $product ) {
    $attrs = array( 'width' => 60, 'height' => 'auto' );

    if ( ! $product || ! method_exists( $product, 'get_image' ) ) {
        return \wc_placeholder_img( 'woocommerce_thumbnail', $attrs );
    }

    $thumbnail = $product->get_image( 'woocommerce_thumbnail', $attrs );

    return $thumbnail;
}

/**
 * Product ID column
 *
 * @param int $product_id       Product ID.
 * @param int $count            Product count.
 * @param array $orders         Orders array.
 * @param WC_Product $product   Product object.
 * @return int
 */
function column_id_callback( $product_id, $count, $orders, $product ) {
    return $product_id ?: '-';
}

/**
 * Product title column
 *
 * @param int $product_id       Product ID.
 * @param int $count            Product count.
 * @param array $orders         Orders array.
 * @param WC_Product $product   Product object.
 * @return string
 */
function column_product_callback( $product_id, $count, $orders, $product ) {
    if ( ! $product || ! method_exists( $product, 'get_image' ) ) {
        return '<em>' . esc_html__( 'Product does not exist', 'wc-order-product-count' ) . '</em>';
    }

    return $product->get_title();
}

/**
 * Product quantity column
 *
 * @param int $product_id       Product ID.
 * @param int $count            Product count.
 * @param array $orders         Orders array.
 * @param WC_Product $product   Product object.
 * @return int
 */
function column_quantity_callback( $product_id, $count, $orders, $product ) {
    return $count;
}