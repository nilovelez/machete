<?php
/**
 * Machete Maintenance Page Module front-end content
 *
 * @package WordPress
 * @subpackage Machete
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( 'maintenance' === $this->settings['site_status'] ) {
	header( 'HTTP/1.1 503 Service Temporarily Unavailable' );
	header( 'Status: 503 Service Temporarily Unavailable' );
	header( 'Retry-After: 86400' ); // Retry in a day.
}

?><!DOCTYPE html>
<html><head>
<title><?php echo esc_html( $html_content['title'] ); ?></title>
<meta charset="UTF-8">
<?php if ( 'coming_soon' === $this->settings['site_status'] ) : ?>
<meta name="robots" content="noindex,follow" />
<?php endif; ?>

<style>
<?php $this->readfile( MACHETE_BASE_PATH . 'css/maintenance-style.css' ); ?>
</style>

<?php
$this->readfile( MACHETE_DATA_PATH . 'header.html' );
// wp_head not needed.
?>
</head><body id="maintenance_page" class="<?php echo esc_attr( $html_content['body_class'] ); ?>">

	<?php $this->readfile( MACHETE_DATA_PATH . 'body.html' ); ?>
	<div id="content" class="<?php echo esc_attr( $html_content['content_class'] ); ?>">
	<?php echo wp_kses_post( force_balance_tags( $html_content['body'] ) ); ?>
	</div>

	<?php $this->readfile( MACHETE_DATA_PATH . 'footer.html' ); ?>

	<?php // wp_footer not needed. ?>
</body></html>
