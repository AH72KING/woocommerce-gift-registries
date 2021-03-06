<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Magenest_Giftregistry_Frontend {
	
	public function __construct() {
		add_action('woocommerce_before_cart', array($this,'gift_registry'));
		add_action('woocommerce_add_to_cart', array($this, 'set_gift_registry_cart'));
		add_action('woocommerce_before_checkout_shipping_form',  array($this, 'set_shipping_address_for_giftregistry'));
	}
	
	public function set_shipping_address_for_giftregistry() {
		if (isset ( $_SESSION ['buy_for_giftregistry_id'] )) {
			
			echo "<span id='note_shipping_giftregistry'>". __('All gifts will be shipped directly to the couple. If you would like the gift shipped to a different address, kindly fill in the details below.', GIFTREGISTRY_TEXT_DOMAIN) ." </span><br><br>";
			$w_id = $_SESSION ['buy_for_giftregistry_id'];
			$wishlist = Magenest_Giftregistry_Model::get_wishlist ( $w_id );
			$customer_id = $wishlist->user_id ;
			
			$name = 'shipping';
			$address = array(
					'first_name'  => get_user_meta( $customer_id, $name . '_first_name', true ),
					'last_name'   => get_user_meta( $customer_id, $name . '_last_name', true ),
					'company'     => get_user_meta( $customer_id, $name . '_company', true ),
					'address_1'   => get_user_meta( $customer_id, $name . '_address_1', true ),
					'address_2'   => get_user_meta( $customer_id, $name . '_address_2', true ),
					'city'        => get_user_meta( $customer_id, $name . '_city', true ),
					'state'       => get_user_meta( $customer_id, $name . '_state', true ),
					'postcode'    => get_user_meta( $customer_id, $name . '_postcode', true ),
					'country'     => get_user_meta( $customer_id, $name . '_country', true )
			);
			
		?>
<?php 
		}
	}
	public function gift_registry() {
		global $post;
		$http_schema = 'http://';
		if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'])  {
			$http_schema = 'https://';
		}
		$request_link  = $http_schema. $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] ;
		if (isset($_SESSION['buy_for_giftregistry_id'])) {
			
			$wishlist_id = $_SESSION['buy_for_giftregistry_id'];
			$wishlist = Magenest_Giftregistry_Model::get_wishlist($wishlist_id);

			$registrantname = $wishlist->registrant_firstname . ' '. $wishlist->registrant_lastname;
			$coregistrantname = $wishlist->coregistrant_firstname . ' '. $wishlist->coregistrant_lastname;
			
			$registry_name = $registrantname;
			
			$giftregistry_page = get_permalink( get_option('follow_up_emailgiftregistry_page_id'));
			if(strpos($request_link, '?') > 0)  {
				$giftregistry_link = $giftregistry_page . '&giftregistry_id='. $wishlist_id;
				$giftregistry_end_purchase = $giftregistry_page . '&end_buy_giftregistry='. $wishlist_id;
			}else{
				$giftregistry_link = $giftregistry_page . '?giftregistry_id='. $wishlist_id;
				$giftregistry_end_purchase = $giftregistry_page . '?end_buy_giftregistry='. $wishlist_id;
			}
			if($coregistrantname !=' '){
				$registry_name .= __(' and' , GIFTREGISTRY_TEXT_DOMAIN) . " ". $coregistrantname;
			}
		}
	}
	
	public function set_gift_registry_cart($cart_item_key) {
		error_log('buy gift');
		if (isset($_REQUEST['buy_for_giftregistry_id']) && isset($_REQUEST['add-to-cart'])) {
			error_log('buy gift 1');
			$_SESSION['buy_for_giftregistry_id'] = $_REQUEST['buy_for_giftregistry_id'];
			wc_add_notice ( __ ( 'You have add items for gift regisry', GIFTREGISTRY_TEXT_DOMAIN ), 'success' );
				
		}
	}
}

return new Magenest_Giftregistry_Frontend();