<?php
/**
 * Optimization actions for the cleanup module.
 *
 * @package WordPress
 * @subpackage Machete
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
Actions:
- free_shipping
- price_from
- trailing_zeros
- no_unique_sku
- disable_skus
*/


// Fix free shipping.
if ( in_array( 'free_shipping', $this->settings, true ) ) {
	// Shipping rates can't be live motified if their transient cache is active
	// https://github.com/woocommerce/woocommerce/issues/22100#issuecomment-705037440 .
	add_filter( 'transient_shipping-transient-version', '__return_false', 10, 2 );

	add_filter(
		'woocommerce_package_rates',
		function( $rates ) {
			$filtered_rates = array();
			foreach ( $rates as $rate_id => $rate ) {
				if ( in_array( $rate->method_id, array( 'free_shipping', 'local_pickup' ), true ) ) {
					$filtered_rates[ $rate_id ] = $rate;
				}
			}
			if ( ! empty( $filtered_rates ) ) {
				return $filtered_rates;
			}
			return $rates;
		},
		100
	);
}

// Variable price from.
if ( in_array( 'price_from', $this->settings, true ) && ! is_admin() ) {
	/**
	 * Converts price range from "20€-30€"" to "From 20€"
	 *
	 * @param string     $price   The wc format price range.
	 * @param WC_Product $product the product instance.
	 */
	function machete_precio_desde( $price, $product ) {

		// Price on sale. Must be treieved before so we can compare later.
		$saleprices = array(
			$product->get_variation_regular_price( 'min', true ),
			$product->get_variation_regular_price( 'max', true ),
		);
		sort( $saleprices );

		$saleprice = wc_price( $saleprices[0] );

		if ( $saleprices[0] !== $saleprices[1] ) {
			// Translators: From 99$ .
			$saleprice_compare = sprintf( __( 'From %1$s', 'machete' ), wc_price( $saleprices[0] ) );
		} else {
			$saleprice_compare = wc_price( $saleprices[0] );
		}

		// Normal price.
		$prices = array(
			$product->get_variation_price( 'min', true ),
			$product->get_variation_price( 'max', true ),
		);
		sort( $prices );

		if ( $prices[0] !== $prices[1] ) {
			$price = sprintf(
				// Translators: From 99$ .
				__( 'From %1$s', 'machete' ),
				wc_price( $prices[0] )
			);
			if ( $price !== $saleprice_compare ) {
				$price = sprintf(
					// Translators: From 99$ .
					__( 'From %1$s', 'machete' ),
					'<del>' . $saleprice . '</del> <ins>' . wc_price( $prices[0] ) . '</ins>'
				);
			}
		} else {
			$price = wc_price( $prices[0] );
		}

		return $price;
	}

	add_filter( 'woocommerce_variable_sale_price_html', 'machete_precio_desde', 10, 2 );
	add_filter( 'woocommerce_variable_price_html', 'machete_precio_desde', 10, 2 );

}

// Hide trailing zeros.
if ( in_array( 'trailing_zeros', $this->settings, true ) && ! is_admin() ) {
	add_filter( 'woocommerce_price_trim_zeros', '__return_true' );
}

// Disable unique SKU.
if ( in_array( 'no_unique_sku', $this->settings, true ) ) {
	add_filter( 'wc_product_has_unique_sku', '__return_false' );
}

// Disable SKUs.
if ( in_array( 'disable_skus', $this->settings, true ) ) {
	add_filter( 'wc_product_sku_enabled', '__return_false' );
}
