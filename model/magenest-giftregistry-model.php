<?php
if (! defined ( 'ABSPATH' ))
	exit (); // Exit if accessed directly
class Magenest_Giftregistry_Model {
	public static function get_wishlist_id() {
		global $wpdb;
		$prefix = $wpdb->prefix;
		$rTb = "{$prefix}magenest_giftregistry_wishlist";
		
		$user_id = get_current_user_id();
		$row = $wpdb->get_row( "select * from {$rTb} where user_id = {$user_id} " );
		
		if ($row) {
			return $row->id;
		}
	}
	
	public static function get_all_giftregistry() {
		global $wpdb;
		$prefix = $wpdb->prefix;
		$rTb = "{$prefix}magenest_giftregistry_wishlist";
		
		$user_id = get_current_user_id ();
		$row = $wpdb->get_results( "select * from {$rTb}", ARRAY_A );
		
		if ($row) {
			return $row;
		}
	}
	
	public static function update_giftregistry_item($id, $qty) {
		global $wpdb;
		$prefix = $wpdb->prefix;
		$tbl = "{$prefix}magenest_giftregistry_item";
		$wpdb->update($tbl, array('quantity'=>$qty), array('id'=>$id));
		
	}
	public static function delete_giftregistry_item($id) { 
		global $wpdb;
		$prefix = $wpdb->prefix;
		$tbl = "{$prefix}magenest_giftregistry_item";
		$wpdb->delete($tbl, array('id' => $id));
	}
	public static function delete_giftregistry($id) { 
		global $wpdb;
		$prefix = $wpdb->prefix;
		$tbl = "{$prefix}magenest_giftregistry_wishlist";
		$wpdb->delete($tbl, array('id' => $id));
	}
	public static function get_items_in_giftregistry($wishlist_id) {
		global $wpdb;
		$prefix = $wpdb->prefix;
		$tbl = "{$prefix}magenest_giftregistry_item";
		
		$user_id = get_current_user_id ();
		$rows = $wpdb->get_results( "select * from {$tbl} where wishlist_id = {$wishlist_id}" , ARRAY_A);
		
		if ($rows) {
			return $rows;
		}
	}
	/*public static function get_items_in_giftregistry_wrt_wishlist($wishlist_id) {
	global $wpdb;
	$prefix = $wpdb->prefix;
	$tbl = "{$prefix}magenest_giftregistry_item";
	
	$user_id = get_current_user_id ();
	$rows = $wpdb->get_results( "select * from {$tbl} where wishlist_id = {$wishlist_id} and user_id = {$user_id}" , ARRAY_A);
	
    	if ($rows) {
    		return $rows;
    	}
	}*/
	public static function get_wishlist($wishlist_id) {
		global $wpdb;
		$prefix = $wpdb->prefix;
		$tbl = "{$prefix}magenest_giftregistry_wishlist";
		
		$rows = $wpdb->get_row( "select * from {$tbl} where id = {$wishlist_id} " , OBJECT );
		
		if ($rows) {
			
			return $rows;
		}
	}
	
	public static function get_wishlist_items_for_current_user() {
		$wid = self::get_wishlist_id();
		if ($wid) {
			return self::get_items_in_giftregistry($wid);
		}
	}
	
	
	public static function show_info_request($item,$wishlist_id) {
	
		//global $woocommerce;
		//$woocommerce->cart->add_to_cart($product_id);
		$request = unserialize($item['info_request']);

        $cart_page =wc_get_page_permalink( 'cart' );
        if (!strpos($cart_page,'?')) {
            $request_st =$cart_page. '?';
        } else {
            $request_st =$cart_page. '&';
        }

		if (! empty ( $request )) {
			$i = 0;
			foreach ( $request as $k => $v ) {
				$i ++;
				if ($k == 'add-to-giftregistry') {
					$k = 'add-to-cart';
				}
				if ($k != 'quantity')
					$request_st .= $k . '=' . $v;
					
				if ($i != count ( $request )) {
		
					$request_st .= '&';
				}
					
			}
			$request_st .='&buy_for_giftregistry_id='.$wishlist_id;
		}
		
		return $request_st;
	}
	
	public static function after_buy_gift($order_id) {
		global $wpdb;
		$prefix = $wpdb->prefix;
		$tbl = "{$prefix}magenest_giftregistry_item";
		
		if (isset ( $_SESSION ['buy_for_giftregistry_id'] ) && $_SESSION ['buy_for_giftregistry_id']) {
			$w_id = $_SESSION ['buy_for_giftregistry_id'];
			$order = wc_get_order ( $order_id );
			$order_items = $order->get_items ();
			
			if (! empty ( $order_items )) {
				foreach ( $order_items as $_item ) {
					$product_id = $_item ['item_meta'] ['_product_id'] [0];
					$variation = $_item ['item_meta'] ['_variation_id'] [0];
					
					$purchased_qty = $_item ['qty'];
					if (! $variation) {
						$variation_id = NULL;
					} else {
						$variation_id = $variation;
					}
					
					if (! $variation_id) {
						$query = "select * from {$tbl} where product_id = {$product_id} and variation_id is NULL and wishlist_id = {$w_id}";
					} else {
						$query = "select * from {$tbl} where product_id = {$product_id} and variation_id = {$variation_id} and wishlist_id = {$w_id}";
					}
					$item = $wpdb->get_row ( $query, ARRAY_A );
					
					if (is_array ( $item )) {
						$item_id = $item ['id'];
						$received_qty = $item ['received_qty'];
						$received_quantity = $received_qty + $purchased_qty;
						
						$received_order = $item ['received_order'];
						
						if ($received_order) {
							$received_order .= ';' . $order_id;
						} else {
							$received_order .= $order_id;
						}
						
						if ($item_id) {
							$wpdb->update ( $tbl, array (
									'received_qty' => $received_quantity,
									'received_order' => $received_order 
							), array (
									'id' => $item_id 
							) );
						}
					}
				}
			}
			////////////////////////////////////////////////////////////////
			///////////////////Send notification email ///////////////////
			////////////////////////////////////////////////////////////////
			$wishlist = self::get_wishlist($w_id);
			/*  send to owner*/
			$recipients = array();
			$is_send_owner = get_option('giftregistry_notify_owner');
			if ($is_send_owner =='yes') {
				$recipients[]= $w_id;
			}
			$is_send_registrant = get_option('giftregistry_notify_registrant');
			
			if ($is_send_registrant == 'yes') {
				$recipients[]= $wishlist->registrant_email;
			}
			$is_send_admin = get_option('giftregistry_notify_admin');
			
			if ($is_send_admin=='yes') {
				$recipients[] = get_option('woocommerce_email_from_address');
			}
			
			if (!empty($recipients)) {
				foreach ($recipients as $recipient) {
					self::sendNotificationEmail($recipient, $order_id);
				}
			} 
			//////////////////////////////////////////////
			
			unset($_SESSION ['buy_for_giftregistry_id']);
		}
	}
	public  static function get_order_items($orderId) {
		ob_start();
	
		$template_path = GIFTREGISTRY_PATH.'template/email/';
		$default_path = GIFTREGISTRY_PATH.'template/email/';
	
		$order = new WC_Order ( $orderId );
	
		wc_get_template( 'order-items.php', array(
		'order' 		=>$order,
		'order_id' => $orderId,
		),$template_path,$default_path
		);
		return ob_get_clean();
	}
	public static function sendNotificationEmail($to,$order_id) {
		$order = wc_get_order ( $order_id );
		$billing_email = $order->billing_email;
		$billing_phone = $order->billing_phone;
		
		$billing_lastname = $order->billing_last_name;
		$billing_firstname = $order->billing_first_name;
		$headers = array ();
		$headers [] = "Content-Type: text/html";
		$headers [] = 'From: ' . get_option ( 'woocommerce_email_from_name' ) . '<' . get_option('woocommerce_email_from_address') . '>';
		
		$subject = get_option('giftregistry_notify_email_subject') ;
		$content  = get_option('giftregistry_notify_email_content');
		$replaces = array (
				'{{buyer_name}}' => $billing_firstname . ' '.$billing_lastname ,
				'{{store_url}}' => get_permalink ( woocommerce_get_page_id ( 'shop' ) ),
				'{{store_name}}' => get_bloginfo ( 'name' ),
				'{{order_number}}' => $order->get_order_number (),
				'{{order_url}}' => $order->get_view_order_url () ,
				'{{order_items}}'=> self::get_order_items($order->id)
		);
		
		$content = strtr ( $content, $replaces );

		add_filter( 'wp_mail_content_type', array('Magenest_Giftregistry_Model','set_html_content_type' ));
		
		wp_mail ( $to, $subject, $content, $headers );
			
		remove_filter( 'wp_mail_content_type',  array('Magenest_Giftregistry_Model','set_html_content_type' ));
		
	}
	
	/**
	 * set html content type for email
	 */
	public static function set_html_content_type() {
		return 'text/html';
	}
}
