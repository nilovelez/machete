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
	 */
	public function __construct( $settings ) {

		$this->settings = $settings;
		extract( $this->settings );

		if (
			(
				( ! empty( $site_status ) && ( 'maintenance' === $site_status ) ) ||
				( ! empty( $site_status ) && ( 'coming_soon' === $site_status ) )
			) || (
				isset( $_GET['mct_preview'] ) &&
				wp_verify_nonce( $_GET['mct_preview'], 'maintenance_preview_nonce' )
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
	private function render_comingsoon_page() {

		extract($this->settings);

		// Check if Preview
		$is_preview = false;
		if ( isset( $_GET['mct_preview'] ) ) {
			$is_preview = true;
		}

		if ( false === $is_preview ) {
			// Exit if a custom login page
			if ( preg_match( "/login|admin|dashboard|account/i", $_SERVER['REQUEST_URI'] ) > 0 ) {
				return false;
			}

			// Check if user is logged in.
			if ( is_user_logged_in() && current_user_can( 'publish_posts' ) ) {
				return false;
			}
		}

		// check magic link
		session_start();
		if ( ( isset( $_GET['mct_token'] ) && $_GET['mct_token'] == 'logout' ) ) {
			if ( isset( $_SESSION['mct_token'] ) ) {
				$_SESSION['mct_token'] = '';
			}
		}

		if ( ( isset( $_SESSION['mct_token'] ) && $_SESSION['mct_token'] === $token ) ) {
			return false;
		}

		if ( ( isset( $_GET['mct_token'] ) && $_GET['mct_token'] === $token ) ) {
			$_SESSION['mct_token'] = $_GET['mct_token'];
			return false;
		}

		if ( 'coming_soon' === $site_status ) {
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

		if ( isset( $_GET['mct_page_id'] ) && ( intval( $_GET['mct_page_id'] ) > 0 ) ) {
			$page_id = intval( $_GET['mct_page_id'] );
		} elseif ( $is_preview ) {
			$page_id = null;
		}

		if ( ! empty( $page_id ) && $page = get_post( $page_id ) ) {
			$html_content = array(
				'title'         => str_replace( ']]>', ']]&gt;', apply_filters( 'the_title', $page->post_title ) ),
				'body'          => str_replace( ']]>', ']]&gt;', apply_filters( 'the_content', $page->post_content ) ),
				'content_class' => 'custom',
			);
		}

		if ( 'maintenance' === $site_status ) {
			header( 'HTTP/1.1 503 Service Temporarily Unavailable' );
			header( 'Status: 503 Service Temporarily Unavailable' );
			header( 'Retry-After: 86400' ); // Retry in a day.
		}

		?><!DOCTYPE html>
<html><head>
<title><?php echo esc_html( $html_content['title'] ); ?></title>
<?php
if ( 'coming_soon' === $site_status ) {
	echo '<meta name="robots" content="noindex,follow" />';
}
?>
<link rel="stylesheet" href="<?php echo esc_attr( MACHETE_BASE_URL . 'css/maintenance-style.css' ); ?>" >

<?php
if ( file_exists( MACHETE_DATA_PATH . 'header.html' ) ) {
	readfile( MACHETE_DATA_PATH . 'header.html' );
}
//wp_head();
?>
</head><body id="maintenance_page">

		<?php
		if ( file_exists( MACHETE_DATA_PATH . 'body.html' ) ) {
			readfile( MACHETE_DATA_PATH . 'body.html' );
		}
		?>
		<div id="content" class="<?php echo $html_content['content_class']; ?>">
		<?php echo $html_content['body']; ?>
		</div>

		<?php
		if ( file_exists( MACHETE_DATA_PATH . 'footer.html' ) ) {
			readfile( MACHETE_DATA_PATH . 'footer.html' );
		};
		?>

		<?php //wp_footer(); ?>
		</body></html>
		<?php
		exit();
	}
}