<?php
/**
 * Plugin Name:       WooCommerce Order Product Count
 * Description:       Export multiple orders product counts to a PDF
 * Requires at least: 3.5.1
 * Requires PHP:      7.0
 * WC tested up to:   7.9.0
 * Version:           2.0.0
 * Author:            David Jensen
 * Author URI:        https://dkjensen.com
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wc-order-product-count
 */

namespace Dkjensen\WCOPC;

if ( ! defined( 'ABSPATH' ) ) exit;

require_once 'includes/functions.php';
require_once 'includes/callbacks.php';
require_once 'vendor/autoload.php';

/**
 * Register the order actions
 *
 * @param array $bulk_actions Default bulk actions.
 * @return array
 */
function register_bulk_action( $bulk_actions ) {
	if ( current_user_can( 'edit_shop_orders' ) ) {
		$bulk_actions['weo_export'] = esc_html__( 'Export Product Count', 'wc-order-product-count' );
	}

	return $bulk_actions;
}
add_filter( 'bulk_actions-edit-shop_order', __NAMESPACE__ . '\register_bulk_action' );

function handle_bulk_action( $redirect, $action, $items ) {
	if ( 'weo_export' === $action && current_user_can( 'edit_shop_orders' ) ) {
		// Disable lazyloading images
		add_filter( 'wp_lazy_loading_enabled', '__return_false' );

		$count = get_order_product_count( $items );

		$columns = apply_filters( 'wc_order_product_count_columns', [
			'thumbnail' 	=> [
				'label'			=> esc_html__( 'Thumbnail', 'wc-order-product-count' ),
				'size'			=> '15%',
				'callback'		=> __NAMESPACE__ . '\column_thumbnail_callback'
			],
			'id' 			=> [
				'label'			=> esc_html__( 'ID', 'wc-order-product-count' ),
				'size'			=> '10%',
				'callback'		=> __NAMESPACE__ . '\column_id_callback'
			],
			'product' 		=> [
				'label'			=> esc_html__( 'Product', 'wc-order-product-count' ),
				'size'			=> '55%',
				'callback'		=> __NAMESPACE__ . '\column_product_callback'
			],
			'quantity' 		=> [
				'label'			=> esc_html__( 'Quantity', 'wc-order-product-count' ),
				'size'			=> '20%',
				'callback'		=> __NAMESPACE__ . '\column_quantity_callback'
			],
		] );

		$mpdf = new \Mpdf\Mpdf(
			apply_filters(
				'wc_order_product_count_mpdf_args',
				array(
					'mode'           => get_locale(),
					'format'         => 'A4',
					'default_font'   => 'dejavusans',
				)
			)
		);

		$mpdf->shrink_tables_to_fit 	= 1;
		$mpdf->simpleTables         	= true;
		$mpdf->packTableData        	= true;
		$mpdf->autoLangToFont       	= true;
		$mpdf->defaultheaderline 		= 0;
		$mpdf->defaultfooterline 		= 0;
		$mpdf->defaultheaderfontsize	= 20;
		$mpdf->defaultheaderfontstyle	= 'R';
		$mpdf->setAutoTopMargin 		= true;
		$mpdf->setAutoBottomMargin 		= true;

		ob_start();

		include 'templates/export.php';

		$content = ob_get_clean();

		$mpdf->SetHeader( esc_html__( 'Order Export', 'wc-order-product-count' ) );
		$mpdf->WriteHTML( $content, \Mpdf\HTMLParserMode::HTML_BODY );
		$mpdf->setFooter( '{PAGENO}' );
		$mpdf->Output( 'woocommerce-export-order-count-' . time() . '.pdf', 'D' );

		exit;
	}

	return $redirect;
}
add_filter( 'handle_bulk_actions-edit-shop_order', __NAMESPACE__ . '\handle_bulk_action', 10, 3 );