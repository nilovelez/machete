<?php
/**
 * Class used by the Machete maintenance module.
 *
 * @package WordPress
 * @subpackage Machete
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Class used for rendering the maintenance page when needed.
 */
class MACHETE_MAINTENANCE_PAGE {
	/**
	 * Module settings, populated by the construct method.
	 *
	 * @var array
	 */
	private $settings;
	/**
	 * Module constructor, detemines if the maintenance page must be shown
	 *
	 * @param array $settings settings passed from the maintenance module class.
	 */
	public function __construct( $settings ) {

		$this->settings = $settings;

		$mct_preview = filter_input( INPUT_GET, 'mct_preview' );

		if (
			( 'maintenance' === $this->settings['site_status'] ) ||
			( 'coming_soon' === $this->settings['site_status'] ) ||
			(
				( null !== $mct_preview ) &&
				wp_verify_nonce( $mct_preview, 'maintenance_preview_nonce' )
			)
		) {
			if ( function_exists( 'bp_is_active' ) ) {
				add_action( 'template_redirect', array( $this, 'render_comingsoon_page' ), 9 );
			} else {
				add_action( 'template_redirect', array( $this, 'render_comingsoon_page' ) );
			}
		}

	}
	/**
	 * Used to do the actual rendering of the maintenance page.
	 */
	public function render_comingsoon_page() {

		// Check if Preview.
		$is_preview = false;
		if ( null !== filter_input( INPUT_GET, 'mct_preview' ) ) {
			$is_preview = true;
		}

		if ( ! $is_preview ) {
			// Exit if a custom login page.
			if ( preg_match(
				'/login|admin|dashboard|account/i',
				filter_input( INPUT_SERVER, 'REQUEST_URI' )
			) > 0 ) {
				return false;
			}

			// Exit if user is logged in.
			if ( is_user_logged_in() && current_user_can( 'publish_posts' ) ) {
				return false;
			}
		}

		$mct_token  = filter_input( INPUT_GET, 'mct_token' );
		$mct_cookie = filter_input( INPUT_COOKIE, 'mct_cookie' );

		if ( ( 'logout' === $mct_token ) && ( null !== $mct_cookie ) ) {
			$mct_cookie = null;
			setcookie( 'mct_cookie', '', time() - 3600 );
		}

		// checks magic link cookie.
		if ( $mct_cookie === $this->settings['token'] ) {
			return false;
		}
		// checks magic link.
		if ( $mct_token === $this->settings['token'] ) {
			// Saves session cookie.
			setcookie( 'mct_cookie', $mct_token );
			return false;
		}

		if ( 'coming_soon' === $this->settings['site_status'] ) {
			// Coming soon default content.
			$html_content = array(
				'title'         => __( 'Coming soon', 'machete' ),
				'body'          => __( '<h1>Under construction</h1><h3>New content is coming soon...</h3>', 'machete' ),
				'content_class' => 'default',
			);
		} else {
			// Maintenance default content.
			$html_content = array(
				'title'         => __( 'Maintenance', 'machete' ),
				'body'          => __( '<h1>Under maintenance</h1><h3>We will be back shortly</h3>', 'machete' ),
				'content_class' => 'default',
			);
		}

		$page_id = $this->settings['page_id'];
		if ( $is_preview ) {
			$preview_page_id = filter_input( INPUT_GET, 'mct_page_id', FILTER_VALIDATE_INT );
			if ( null !== $preview_page_id ) {
				$page_id = $preview_page_id;
			}
		}

		if ( null !== $page_id ) {
			$page = get_post( $page_id );
			if ( null !== $page ) {
				$html_content = array(
					'title'         => str_replace( ']]>', ']]&gt;', apply_filters( 'the_title', $page->post_title ) ),
					'body'          => str_replace( ']]>', ']]&gt;', apply_filters( 'the_content', $page->post_content ) ),
					'content_class' => 'custom',
				);
			}
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
<?php
if ( 'coming_soon' === $this->settings['site_status'] ) {
	echo '<meta name="robots" content="noindex,follow" />';
}

echo '<style>';
$this->readfile( MACHETE_BASE_PATH . 'css/maintenance-style.css' );
echo '</style>';

$this->readfile( MACHETE_DATA_PATH . 'header.html' );
// wp_head not needed.
?>
</head><body id="maintenance_page">

		<?php $this->readfile( MACHETE_DATA_PATH . 'body.html' ); ?>
		<div id="content" class="<?php echo esc_attr( $html_content['content_class'] ); ?>">
		<?php echo wp_kses_post( force_balance_tags( $html_content['body'] ) ); ?>
		</div>

		<?php $this->readfile( MACHETE_DATA_PATH . 'footer.html' ); ?>

		<?php // wp_footer not needed. ?>
		</body></html>
		<?php
		exit();
	}
	/**
	 * File pass-through
	 *
	 * @param string $file      Path to the file.
	 * @return bool false on failure.
	 */
	private function readfile( $file ) {
		if ( ! file_exists( $file ) ) {
			return false;
		}
		readfile( $file );
	}
}
