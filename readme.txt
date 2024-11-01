=== WooCommerce Order Product Count ===
Contributors: dkjensen,seattlewebco
Donate Link: https://dkjensen.com
Tags: woocommerce, order, export, pdf, bulk
Requires at least: 3.5.1
Requires PHP: 7.0
Tested up to: 6.3
Stable tag: 2.0.0
License: GPL v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.txt

Select WooCommerce orders in bulk to export the sum of each individual product.

== Description ==

For easy inventory gathering, generate a document with the quantity of each individual product in a neat PDF format.

**How to use**
On the WooCommerce Orders page, check each order you want to export the product count for, and then on the Bulk Actions drop down, select Orders Product Count and press Apply.

A PDF document will then be generated with your orders product count.

**Requirements**
WooCommerce 2.0 or later
WordPress 7.0 or later

== Installation ==

1. Upload `woocommerce-order-product-count` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= How do I use the plugin? =

On the WooCommerce Orders page, check each order you want to export the product count for, and then on the Bulk Actions drop down, select Orders Product Count and press Apply.

A PDF document will then be generated with your orders product count.

= How do I add a custom column to the exported PDF? =

Here is an example on how to display the product price in the PDF. Add the following to your theme functions.php file:

    function wcopcpdf_columns( $columns ) {
		// Remove the thumbnail column
		unset( $columns['thumbnail'] );

		// Add a column for product price
		$columns['price'] = array(
			'label' => 'Price',
			'size'  => '15%',
			'callback' => 'wcopcpdf_column_price' // Callback uses the function named below
		);

		return $columns;
	}
	add_filter( 'wc_order_product_count_columns', 'wcopcpdf_columns' );

	if( ! function_exists( 'wcopcpdf_column_price' ) ) {
		/**
		* Callback to display the product price
		*     
		* @param int $product_id
		* @param int $quantity
		* @param array $orders
		* @param WC_Product $product
		*/
		function wcopcpdf_column_price( $product_id, $quantity, $orders, $product ) {
			return wc_price( $product->get_price() );	
		}
	}

== Screenshots ==

1. Bulk Action dropdown to export product count
2. View of an example exported product count PDF

== Changelog ==

= 2.0.0 =
* BREAKING: Refactor plugin for compatibility with WP 6.3 and PHP 8+

= 1.3.2 =
* WordPress 5.2 compatibility

= 1.3.1 =
* Fix compatibility with variable products

= 1.3 =
* Rewrite plugin to make it more extensible
* Added several hooks for developers to modify, including modifying the columns displayed in the PDF

= 1.2 =
* Added support for WooCommerce 2.6

= 1.1 =
* Added support for variations
* Added thumbnails

= 1.0 =
* Initial plugin state


== Upgrade Notice ==

= 1.3.1 =
Fix compatibility with variable products

= 1.3 =
Rewrite plugin to make it more extensible
Added several hooks for developers to modify, including modifying the columns displayed in the PDF

= 1.2 =
Added support for WooCommerce 2.6

= 1.1 =
Added support for variations
Added thumbnails

= 1.0 =
Initial plugin state