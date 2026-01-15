<?php
/**
 * Localization file for the woocommerce module.
 *
 * @package WordPress
 * @subpackage Machete
 */

if ( ! defined( 'MACHETE_ADMIN_INIT' ) ) {
	exit;
}

$this->params['title']       = __( 'WooCommerce', 'machete' );
$this->params['full_title']  = __( 'WooCommerce Utils', 'machete' );
$this->params['description'] = __( 'Utilities and fixes that make your life a little easier when working with WooCommerce.', 'machete' );


$this->woo_array['free_shipping'] = array(
    'title'       => __( 'Fix free shipping', 'machete' ),
    'description' => __( 'Hides all paid shipping methods from checkout when free shipping is available. Keeps "Free shipping" and "local Pickup".', 'machete' ),
);

$this->woo_array['price_from'] = array(
    'title'       => __( 'Variable price from', 'machete' ),
    'description' => __( 'Replaces the price interval on variable products with a "Price from" label.', 'machete' ),
);

$this->woo_array['trailing_zeros'] = array(
    'title'       => __( 'Hide trailing zeros', 'machete' ),
    'description' => __( 'Hides trailing zeros on prices. Shows $5.00 as $5', 'machete' ),
);

$this->woo_array['no_unique_sku'] = array(
    'title'       => __( 'Disable unique SKU', 'machete' ),
    'description' => __( 'Allows you to use the same SKU in multiple products or prodcut variations', 'machete' ),
 );

$this->woo_array['disable_skus'] = array(
    'title'       => __( 'Disable SKUs', 'machete' ),
    'description' => __( 'Removes the SKU field in both the backend and frontend of your store.', 'machete' ),
);