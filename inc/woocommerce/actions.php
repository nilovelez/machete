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

// Fix free shipping.
if ( in_array( 'free_shipping', $this->settings, true ) && ! is_admin() ) {

}

// Variable price from.
if ( in_array( 'price_from', $this->settings, true ) && ! is_admin() ) {

}
