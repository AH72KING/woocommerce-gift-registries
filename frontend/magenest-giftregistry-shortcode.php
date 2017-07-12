<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Magenest_Giftregistry_Shortcode {
	public static function init() {
		// Define shortcodes
		$shortcodes = array (
				
				'magenest_giftregistry' => __CLASS__ . '::show_gift_registry_page',
				
				'magenest_manage_all_giftregistries' => __CLASS__ . '::show_manage_all_giftregistries_page',
				
				'magenest_public_giftregistry' => __CLASS__ . '::testAddEmailQueuefororder' 
		);
		
		foreach ( $shortcodes as $shortcode => $function ) {
			add_shortcode ( apply_filters ( "{$shortcode}", $shortcode ), $function );
		}
		
		;
	}
	/**
	 * @return string
	 */
	public static function show_gift_registry_page() {
		
		$template_path = GIFTREGISTRY_PATH . 'template/';
		$default_path = GIFTREGISTRY_PATH . 'template/';
		if (! isset ( $_REQUEST ['giftregistry_id'] )) {
			
			ob_start ();
			wc_get_template ( 'giftregistry-index.php', array ( 'order' => 'r', 'order_id' => '2' ), $template_path, $default_path );
			return ob_get_clean ();
		} else {

			ob_start ();
			wc_get_template ( 'public-view-giftregistry.php', array ('id'=>$_REQUEST ['giftregistry_id']  ), $template_path, $default_path );
			return ob_get_clean ();
		}
	}
	
	public static function show_manage_all_giftregistries_page() {
		
		$template_path = GIFTREGISTRY_PATH . 'template/';
		$default_path = GIFTREGISTRY_PATH . 'template/';
		//echo 'Helllo'; 
		if (! isset ( $_REQUEST ['giftregistry_id'] )) {
			//echo 'Hi';
			ob_start ();
			wc_get_template ( 'all-giftregistry-index.php', array ( 'order' => 'r', 'order_id' => '2' ), $template_path, $default_path );
			return ob_get_clean ();
		} else {

			ob_start ();
			wc_get_template ( 'public-view-giftregistry.php', array ('id'=>$_REQUEST ['giftregistry_id']  ), $template_path, $default_path );
			return ob_get_clean ();
		}
	}
	
	

}
