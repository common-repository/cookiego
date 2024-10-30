<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * Plugin Name: CookieGo
 * Plugin URI: https://myprivacylock.io/
 * Version: 1.0.5
 * Author: Privacy Lock
 * Author URI: https://myprivacylock.io/
 * Description: Tool for enhancing Cookie Consent and Streamlining Website Scanning
 * License: GPL2
 *
 * @package CookieGo
 */

/*
	Copyright 2024 Privacy Lock

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

class CookieGo_Plugin {
	/**
	 * Constructor
	 */
	public function __construct() {

		$this->plugin                           = new stdClass();
		$this->plugin->name                     = 'CookieGo';
		$this->plugin->displayName              = 'CookieGo';
		$this->plugin->version                  = '1.0.1';
		$this->plugin->folder                   = plugin_dir_path( __FILE__ );
		$this->plugin->url                      = plugin_dir_url( __FILE__ );
		$this->plugin->db_welcome_dismissed_key = 'cookiego_' . $this->plugin->name . '_welcome_dismissed_key';

		add_action( 'admin_init', array( $this, 'registersettings' ) );
		add_action( 'admin_menu', array( $this, 'adminpanelsandmetaboxes' ) );
		add_action( 'wp_head', array( $this, 'frontendheader' ), 1 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );
		add_action( 'wp_ajax_store_user_session', array( $this, 'store_user_session' ) );
		add_action( 'wp_ajax_nopriv_store_user_session', array( $this, 'store_user_session' ) );
		add_action( 'admin_init', array( $this, 'handle_session_clearance' ) );
	}

	public function enqueue_assets() {
		wp_register_style('wpcp', $this->plugin->url . 'assets/css/style.css', [], '1.0');
		wp_enqueue_style('wpcp');
		if (!is_admin()) {
	
			wp_register_script('cookiego_base_script', $this->plugin->url . 'assets/js/base.js', [], '1.0', true);
			wp_enqueue_script('cookiego_base_script');
		
			
			$widgetId = get_option('cookiego_widget_id');
			if ($widgetId) {
				$fullWidgetId = 'cookies-widget-' . esc_attr($widgetId);
				$inline_script = "(function(w, d, s, o, f, js, fjs) {
					w['Privacy-Lock-Widget'] = o;
					w[o] = w[o] || function() {
						(w[o].q = w[o].q || []).push(arguments);
					};
					js = d.createElement(s);
					js.id = o;
					js.src = f + '?e=' + encodeURIComponent('+9/RDCIWYQCDCwIrbswcNUn9EX7JrZRvcFPkg0lPEA6bpen80BxYqnPIEaLhX0T9w3qSeiid9U+AftqeJB0O/amAIJoDwV7AQhm4mrAvwXDL536dW3yybtHuyBRSGTUOTKyrFuZm0qhuTMQ4YKYZKnrkrHwyQPwb/wMLK9k4qa+pG4EJ6BHeuMylDcbVZlJJ88+bFrfwbt6Br6MBNw8xmGyNZLamX9q37IhWKbH3wOk=') + '&iv=' + encodeURIComponent('qKdUl72D6hM5ZGGYc/w6Hw==');
					js.async = true;
					fjs = d.getElementsByTagName(s)[0];
					fjs.parentNode.insertBefore(js, fjs);
				}(window, document, 'script', '{$fullWidgetId}', 'https://cookiego.myprivacylock.io/assets/cookies.js'));";
				wp_add_inline_script('cookiego_base_script', $inline_script);
			}
		}
	}
	
	


	public function handle_session_clearance() {
		if ( isset( $_GET['action'] ) && $_GET['action'] == 'clear_session' ) {
			if ( current_user_can( 'manage_options' ) ) {
				delete_option( 'cookiego_user_session' ); 
				wp_redirect( esc_url( admin_url( 'admin.php?page=CookieGo&view=settings' ) ) ); 
				exit;
			}
		}
	}

	public function frontendheader() {
		if (is_admin() || is_feed() || is_robots() || is_trackback()) {
			return;
		}
	}
	

	private function checkEmailExists() {
		$admin_email = sanitize_email( get_option( 'admin_email' ) );
		$response = wp_remote_post( 'https://obcb26vr7i.execute-api.us-east-1.amazonaws.com/check-email', [
			'method'      => 'POST',
			'timeout'     => 45,
			'redirection' => 5,
			'httpversion' => '1.0',
			'blocking'    => true,
			'headers'     => [
				'Content-Type' => 'application/json; charset=utf-8'
			],
			'body'        => wp_json_encode( [ 'email' => $admin_email ] ),
			'cookies'     => []
		] );

		if ( is_wp_error( $response ) ) {
			error_log( 'API call failed: ' . $response->get_error_message() );
			return false;
		} else {
			$body = wp_remote_retrieve_body( $response );
			$data = json_decode( $body, true );
			return ! empty( $data['exists'] ) ? $data['exists'] : false;
		}
	}

	public function store_user_session() {
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'cookiego_nonce' ) ) {
			echo esc_html( 'Invalid nonce' );
			wp_die();
		}
		

		if ( ! current_user_can( 'manage_options' ) ) {
			echo esc_html( 'You do not have permission to perform this action' );
			wp_die();
		}

		if ( ! empty( $_POST['sessionToken'] ) ) {
			update_option( 'cookiego_user_session', sanitize_text_field( $_POST['sessionToken'] ) );
			echo esc_html( 'Session token stored successfully' );
		} else {
			echo esc_html( 'No session token provided' );
		}
		wp_die();
	}

	public function registersettings() {
		register_setting($this->plugin->name, 'cookiego_header_settings', 'trim');
	}

	public function adminpanel() {
		if ( ! current_user_can( 'administrator' ) ) {
			echo '<p>' . esc_html__( 'Access Denied', 'cookiego' ) . '</p>';
			return;
		}

		$sessionToken = sanitize_text_field( get_option( 'cookiego_user_session' ) );
		$pageView     = isset( $_GET['view'] ) ? sanitize_text_field( $_GET['view'] ) : 'settings';
		$pageView     = in_array( $pageView, [ 'settings', 'email_exists', 'connected' ] ) ? $pageView : 'settings';

		if ( ! empty( $sessionToken ) ) {

			include_once $this->plugin->folder . 'views/connected.php';
		} else {
			// No session token, decide based on the page view
			switch ( $pageView ) {
				case 'email_exists':
					include_once $this->plugin->folder . 'views/email-exists.php';
					break;
				case 'settings':
				default:
					include_once $this->plugin->folder . 'views/settings.php';
					break;
			}
		}
	}

	public function adminpanelsandmetaboxes() {
		add_submenu_page( 'options-general.php', esc_html( $this->plugin->displayName ), esc_html( $this->plugin->displayName ), 'manage_options', $this->plugin->name, array( $this, 'adminpanel' ) );
	}

	public function on_activation() {
		if ( get_option( 'cookiego_widget_id' ) ) {
			return;
		}

		$admin_email = sanitize_email( get_option( 'admin_email' ) );
		$site_url       = esc_url( get_option( 'siteurl' ) );
		$bannerResponse = wp_remote_post( 'https://obcb26vr7i.execute-api.us-east-1.amazonaws.com/wordpress/createCookiesBanner', [
			'method'      => 'POST',
			'timeout'     => 45,
			'redirection' => 5,
			'httpversion' => '1.0',
			'blocking'    => true,
			'headers'     => [
				'Content-Type' => 'application/json; charset=utf-8'
			],
			'body'        => wp_json_encode( [ 'url' => $site_url ] ),
			'cookies'     => []
		] );

		if ( is_wp_error( $bannerResponse ) ) {
			error_log( 'Failed to create cookies banner: ' . $bannerResponse->get_error_message() );
		} else {
			$bannerBody = wp_remote_retrieve_body( $bannerResponse );
			$bannerData = json_decode( $bannerBody, true );

			if ( ! empty( $bannerData['widgetId'] ) ) {
				update_option( 'cookiego_widget_id', sanitize_text_field( $bannerData['widgetId'] ) );
			} else {
				error_log( 'No widget ID returned from API' );
			}
		}
	}

	public function output( $setting ) {
		if ( is_admin() || is_feed() || is_robots() || is_trackback() ) {
			return;
		}
		if ( apply_filters( 'cookiego_disable_CookieGo', false ) ) {
			return;
		}
		if ( 'CookieGo_header' === $setting && apply_filters( 'CookieGo_header', false ) ) {
			return;
		}
		$myopt = get_option( $setting );
		if ( empty( $myopt ) ) {
			return;
		}
		if ( trim( $myopt ) === '' ) {
			return;
		}
		echo wp_kses_post( $myopt );
	}
}

$CookieGo = new CookieGo_Plugin();
register_activation_hook( __FILE__, array( $CookieGo, 'on_activation' ) );
?>
