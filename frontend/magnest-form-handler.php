<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Magenest_Giftregistry_Form_Handler {
	
	public static function init() {

		add_action('init', array( __CLASS__, 'update_giftregistry_item_action' ) );
		add_action('init', array( __CLASS__, 'add_to_giftregistry_action' ) );
		add_action('init', array( __CLASS__, 'create_giftregistry_action' ) );
		add_action('init', array( __CLASS__, 'end_buy_giftregistry' ) );
		add_action('init', array( __CLASS__, 'giftregistry_share_email' ) );
		add_action('init', array( __CLASS__, 'searchgiftregistry' ) );
		add_action('init', array( __CLASS__, 'ajax_init' ) );
	}
	public static function ajax_init() {
		add_action( 'wp_ajax_update_giftregistry_item_action', 'update_giftregistry_item_action' );
		add_action( 'wp_ajax_nopriv_update_giftregistry_item_action', 'update_giftregistry_item_action' );

		add_action( 'wp_ajax_add_to_giftregistry_action', 'add_to_giftregistry_action' );
		add_action( 'wp_ajax_nopriv_add_to_giftregistry_action', 'add_to_giftregistry_action' );

		add_action( 'wp_ajax_end_buy_giftregistry', 'end_buy_giftregistry' );
		add_action( 'wp_ajax_nopriv_end_buy_giftregistry', 'end_buy_giftregistry' );

		add_action( 'wp_ajax_giftregistry_share_email', 'giftregistry_share_email' );
		add_action( 'wp_ajax_nopriv_giftregistry_share_email', 'giftregistry_share_email' );

		add_action( 'wp_ajax_searchgiftregistry', 'searchgiftregistry' );
		add_action( 'wp_ajax_nopriv_searchgiftregistry', 'searchgiftregistry' );
	}
	public static function searchgiftregistry() {
		global $giftregistryresult;
		$collection = array();
		if (isset($_REQUEST['searchgiftregistry'])) {
			
			$request = $_REQUEST;
			global $wpdb;
			$prefix = $wpdb->prefix;
			$rTb = "{$prefix}magenest_giftregistry_wishlist";
			$name = '';
			$email = '';
			if (isset($_REQUEST['grname']))
			$name = $_REQUEST['grname'];
			
			if (isset($_REQUEST['email']))
			$email = $_REQUEST['email'];
			if ($name || $email) {
				if ($name) {
					$_SESSION ['registrynamesearch'] = $name;
					$query = "select * from {$rTb} where registrant_firstname like \"%{$name}%\" or registrant_lastname like \"%{$name}%\" or coregistrant_firstname like \"%{$name}%\" or coregistrant_lastname like \"%{$name}%\" or title like \"%{$name}%\"";
					
					if($email) {
						$_SESSION ['registryemailsearch'] = $email;
						
						$query .= "or registrant_email like \"%{$email}%\" or coregistrant_email like \"%{$email}%\"";
					}
				}else{
					if ($email) {
						$_SESSION ['registryemailsearch'] = $email;
						
						$query = "select * from {$rTb} where registrant_email like \"%{$email}%\" or coregistrant_email like \"%{$email}%\"";
					}
				}
				
				
				error_log($query);
				$collection = $wpdb->get_results($query,ARRAY_A);
				$giftregistryresult = $collection;
				
			}
		} 
		$giftregistryresult = $collection;
		$_SESSION['registryresult'] = $collection;
	}
	public static function giftregistry_share_email() {
		if (isset ( $_REQUEST['giftregistry-share-email'] ) && isset($_REQUEST['recipient'])&& isset($_REQUEST['email_subject'])&& isset($_REQUEST['message'])) {
			
			$wishlist_id = Magenest_Giftregistry_Model::get_wishlist_id();
			if (!$wishlist_id) {
				return;
			}
			
			$recipients = array();
			$receivers = array();
			if ($_REQUEST['recipient']) {
				$recipients = explode(';', $_REQUEST['recipient']);
			}
			if (!empty($recipients)) {
				foreach ($recipients as $email) {
					if (is_email($email)) {
						$receivers[] = $email;
					}
					
				}
			}
			
			if (empty($receivers)) return;
			//shared part
			$rp = get_permalink( get_option('follow_up_emailgiftregistry_page_id'));
			$http_schema = 'http://';
			if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'])  {
				$http_schema = 'https://';
			}
				
			$request_link  = $http_schema. $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] ;
				
			if (strpos($request_link, '?') > 0)  {
				$wishlist_url  = $rp . '&giftregistry_id='.$wishlist_id;
				
			} else {
			      $wishlist_url  =$rp . '?giftregistry_id='.$wishlist_id;
			}
			
			$headers = array ();
			$headers [] = "Content-Type: text/html";
			$headers [] = 'From: ' . get_option ( 'woocommerce_email_from_name' ) . '<' . get_option('woocommerce_email_from_address') . '>';
			
			$subject = $_REQUEST['email_subject'] ;
			$content  = $_REQUEST['message'];
			$replaces = array (
					'{wishlist_url}' => $wishlist_url
					
			);
			//$replace = array('{wishlist_url}'=>$not_encode_url ) ;
			
			$content = strtr ( $content, $replaces );
			
			add_filter( 'wp_mail_content_type', array('Magenest_Giftregistry_Form_Handler','set_html_content_type' ));
			
			foreach ($receivers as $to) {
			 wp_mail ( $to, $subject, $content, $headers );
			}
				
			remove_filter( 'wp_mail_content_type',  array('Magenest_Giftregistry_Form_Handler','set_html_content_type' ));
		}
	}
	/**
	 * set html content type for email
	 */
	public static function set_html_content_type() {
		return 'text/html';
	}
	public static function end_buy_giftregistry() {
		if (isset($_REQUEST['end_buy_giftregistry'])) {
			unset($_SESSION['buy_for_giftregistry_id']);
		}
		
	}
	/**
	 *This version 1.1 only allow one customer create a gift registry
	 */
	public static function is_valid_to_create_giftregistry() {
		
		$wishlist_id = Magenest_Giftregistry_Model::get_wishlist_id();
		
		if (is_numeric($wishlist_id) && $wishlist_id > 0)  {
			return false;
		}
		return true;
	}
	/**
	 * 
	 */
	public static function create_giftregistry_action() {

		global $wpdb;
		$prefix = $wpdb->prefix;
		$rTb = "{$prefix}magenest_giftregistry_wishlist";
		$is_edit = false;
		if(isset($_REQUEST['create_giftregistry'])){

			if(isset($_REQUEST['giftregistry_id'])){
				$id = $_REQUEST['giftregistry_id'];
				if(is_numeric($id) && $id > 0){ 
					$is_edit = true;

				}
			}	
			if(is_user_logged_in()){

					$data = array();
					$user_id = get_current_user_id();
					$data['user_id'] = $user_id;
					//$data['status'] = $_REQUEST['status'];
					$url = get_home_url().'/shop';

					$data['title'] = stripslashes_deep((isset($_REQUEST['title'])) ? stripslashes_deep($_REQUEST['title']) :'');
					
					$data['registrant_firstname'] = stripslashes_deep((isset($_REQUEST['registrant_firstname'])) ? $_REQUEST['registrant_firstname'] :'');
					$data['registrant_lastname'] = stripslashes_deep((isset($_REQUEST['registrant_lastname'])) ? $_REQUEST['registrant_lastname'] :'');
					$data['registrant_email'] = (isset($_REQUEST['registrant_email'])) ? $_REQUEST['registrant_email'] :'';
					$data['registrant_type'] = (isset($_REQUEST['registrant_type'])) ? $_REQUEST['registrant_type'] :'';
					
					$data['coregistrant_firstname'] = stripslashes_deep((isset($_REQUEST['coregistrant_firstname'])) ? $_REQUEST['coregistrant_firstname'] :'');
					$data['coregistrant_lastname'] = stripslashes_deep((isset($_REQUEST['coregistrant_lastname'])) ? $_REQUEST['coregistrant_lastname'] :'');
					$data['coregistrant_email'] = (isset($_REQUEST['coregistrant_email'])) ? $_REQUEST['coregistrant_email'] :'';
					$data['banner_image'] = (isset($_REQUEST['upload_image'])) ? $_REQUEST['upload_image'] :'';
					$data['coregistrant_type'] = (isset($_REQUEST['coregistrant_type'])) ? $_REQUEST['coregistrant_type'] :'';
					
					$data['event_date_time'] = (isset($_REQUEST['event_date_time'])) ? $_REQUEST['event_date_time'] :'';
					$data['event_date_time'] = date('Y-m-d H:i:s' ,strtotime($data['event_date_time'] ));
					

					$data['event_location'] = (isset($_REQUEST['event_location'])) ? $_REQUEST['event_location'] :'';
					
					$data['message'] = stripslashes_deep((isset($_REQUEST['message'])) ? $_REQUEST['message'] :'');
					$data['image'] = (isset($_REQUEST['pro_image'])) ? $_REQUEST['pro_image'] :'';
					if($is_edit == false) {	
						$data['created_at'] = date('Y-m-d H:i:s');
						$data['registry_unique_number'] = time();
						$wpdb->insert($rTb, $data);
						$registryId = $wpdb->insert_id;

	    				// 	code to add default gift voucher start
	    				$post_tbl = $wpdb->prefix . 'posts';
	    				//$product = $wpdb->get_row( "SELECT * FROM $post_tbl WHERE post_name = 'gift-voucher'" );
						$productId = 6206;
	    				$item_tbl = $wpdb->prefix . 'magenest_giftregistry_item';
	    				$data = array ();
	    				$data['product_id'] = $productId;
	    				$data['quantity'] = 1;
	    				$data['wishlist_id'] = $registryId ;
	    				
						$wpdb->insert ( $item_tbl, $data );
						// code to add default gift voucher ends
						//echo $url; exit;
						// update registry counter in session
						if(session_id() == '') session_start();
						$ipquery=  $wpdb->get_var("SELECT COUNT(*) FROM $rTb  WHERE user_id = $user_id");
	                    $_SESSION['registryCount'] = $ipquery;
						// redirect changed by hamid.creativetech
						$url = get_home_url().'/registry-dashboard';
						
					//	wp_redirect( $url."?registry_unique_number=".$data['registry_unique_number'] );
				    	wp_redirect( $url);
					 	exit;
					}elseif($is_edit) {
						unset($data['user_id']);
						$data['update_at'] = date('Y-m-d H:i:s');
						if($data['banner_image'] == ''){
							unset($data['banner_image']);
						}
						if($data['image'] == ''){
							unset($data['image']);
						}
						$wpdb->update($rTb, $data, array('id' => $id));
						$url = get_home_url().'/wp-admin/admin.php?page=gift_registry&edit=1&id='.$id;
						wp_redirect($url);
					}
			}
		}
	}
	public static function add_to_giftregistry_action() {
		$return = array();
		if( isset ( $_REQUEST['add-registry'] ) 
		&& $_REQUEST['add-registry'] == 1 
		&& !isset($_REQUEST['buy_for_giftregistry_id'])){

			global $wpdb;
			$item_tbl = $wpdb->prefix . 'magenest_giftregistry_item';
			if(isset($_REQUEST['registryId']) && !empty($_REQUEST['registryId'])){
				$r_id = $_REQUEST['registryId'];
			}else{
				$r_id = self::get_giftregistry_id();
			}
			if($r_id){ 
				$Registry = Magenest_Giftregistry_Model::get_wishlist($r_id);
				$giftRegistryTitle = 'no name';
				if(!empty($Registry)){
					if(isset($Registry->title) && !empty($Registry->title)){
						$giftRegistryTitle = $Registry->title;
					}
				}
				$customer_id = get_current_user_id();
				$addr_1 = get_user_meta( $customer_id,'shipping_address_1', true );
				$addr_2 = get_user_meta( $customer_id,'shipping_address_2', true );
				if(!$addr_1  && (get_option('giftregistry_shipping_restrict','yes') =='yes')){
					//wc_add_notice ( __ ( 'You have to fulfill shipping address before adding item to gift registry', GIFTREGISTRY_TEXT_DOMAIN ), 'notice' );
					$return['shipping'] = 'You have to fulfill shipping address before adding item to gift registry';
						return;
				}
				$current_pid = $_POST['add-to-giftregistry'];

				if(isset($_REQUEST['variation_id'])){
					$current_variation_id = $_REQUEST['variation_id'];
				}	
				if(isset($current_variation_id) &&!empty($current_variation_id)){
				   	$if_product_exist = $wpdb->get_results("SELECT * FROM  wp_magenest_giftregistry_item WHERE `wishlist_id` = ".$r_id." AND `product_id` = ".$current_pid." AND `variation_id` = ".$current_variation_id."");
				}else{
				    $if_product_exist = $wpdb->get_results("SELECT * FROM  wp_magenest_giftregistry_item WHERE `wishlist_id` = ".$r_id." AND `product_id` = ".$current_pid."");
				}
				     
				
				$total_reocrds = count($if_product_exist);

				if($total_reocrds > 0){
					global $wpdb;
						$row_id = $if_product_exist[0]->id;
						$current_quantity 	= $_REQUEST['quantity'];
						$previous_quantity 	= $if_product_exist[0]->quantity;
						$total_quantity 	= $current_quantity + $previous_quantity;

					$data = array();
						$data['wishlist_id'] 	= $r_id;
						//$data['product'] 		= (isset($_REQUEST['product'])) ? $_REQUEST['product']:'';
						$data['product_id'] 	= $_POST['add-to-giftregistry'];
						$data['quantity'] 		= $total_quantity ;
						$data['product_cat_id'] = $_REQUEST['product_category'];
					
					if(isset($_REQUEST['gcp']) && !empty($_REQUEST['gcp'])){
					    $data['amount'] = $_REQUEST['gcp'];
					}
					
					if(isset($_REQUEST['variation_id'])){
						$data['variation_id'] = $_REQUEST['variation_id'];
					}
					
					/*if(isset($_REQUEST['variation'])){
						$data['variation'] = $_REQUEST['variation'];
					}*/
					
					$info = serialize($_REQUEST);
					$data['info_request'] = $info;

					$update_item = $wpdb->update($item_tbl, $data, array('id' => $row_id));
					//echo $wpdb->last_query;

					$return['success'] = 'Product was successfully added to gift registry ( '.$giftRegistryTitle.' )';
				}else{
					///////////////////////////////////
					$data = array();
					$data['product'] = (isset($_REQUEST['product'])) ? $_REQUEST['product']:'';
					$data['product_id'] = $_REQUEST['add-to-giftregistry'];
					$data['product_cat_id'] = $_REQUEST['product_category'];
					$data['quantity'] = $_REQUEST['quantity'];
						
					if(isset($_REQUEST['gcp']) && !empty($_REQUEST['gcp'])){
					    $data['amount'] = $_REQUEST['gcp'];
					}
					if(isset($_REQUEST['variation_id'])){
						$data['variation_id'] = $_REQUEST['variation_id'];
						//echo $data['variation_id'];
					}
					if(isset($_REQUEST['variation'])){
						$data['variation'] = $_REQUEST['variation'];
					}

					$info = serialize($_REQUEST);
					$data['info_request'] = $info;

					if(isset($_REQUEST['registryId']) && !empty($_REQUEST['registryId'])){
					    $data['wishlist_id'] = $_REQUEST['registryId'];
					}else{
					    $data['wishlist_id'] = $r_id;
					}

					if($data['product_id'] > 0 && (!isset($data['amount'])) || $data['amount'] >= 100 )  {
							$wpdb->insert($item_tbl, $data);
						if(!session_id()){
		                         session_start();
							}
								$_SESSION['prod_add'] = true;
						//wc_add_notice ( __ ( 'Product was successfully added to gift registry ( '.$giftRegistryTitle.' )', GIFTREGISTRY_TEXT_DOMAIN ), 'success' );
						$return['success'] = 'Product was successfully added to gift registry ( '.$giftRegistryTitle.' )';
					}else if(isset($data['amount']) && $data['amount'] < 100){
					   	//wc_add_notice ( __ ( 'Minimum price should be 100', GIFTREGISTRY_TEXT_DOMAIN ), 'notice' );
					}
					else{
						//wc_add_notice ( __ ( 'You have to select item', GIFTREGISTRY_TEXT_DOMAIN ), 'notice' );
							
					}
				}
				echo 'OK::'.$return['success'].'::success';
				exit;
			}else{
				//wc_add_notice ( __ ( 'You have to enter gift registry information before adding item to gift registry', GIFTREGISTRY_TEXT_DOMAIN ), 'notice' );
				$url = site_url( '/create-registry/');
				wp_redirect( $url );
				exit;				
			}
		}else{
			//wc_add_notice ( __ ( 'You have to enter gift registry information before adding item to gift registry', GIFTREGISTRY_TEXT_DOMAIN ), 'notice' );
				
		}
		
		
	}
	public static function update_giftregistry_item_action() {
		if (isset($_REQUEST['update_giftregistry_item'])) {
			if(isset($_REQUEST['remove_item']) && isset($_REQUEST['item_id'])) {
				Magenest_Giftregistry_Model::delete_giftregistry_item ( $_REQUEST['item_id'] );
			}
			if(isset($_REQUEST['wishlist_item']) && is_array($_REQUEST['wishlist_item']) && !empty($_REQUEST['wishlist_item'])) {
				foreach ($_REQUEST['wishlist_item'] as $item_id=>$qty) {
					if($qty > 0){
						Magenest_Giftregistry_Model::update_giftregistry_item ( $item_id, $qty );
					}else{
						Magenest_Giftregistry_Model::delete_giftregistry_item ( $item_id );
					}
				}
			}
		}
	}
	
	public static function get_giftregistry_id() {
		global $wpdb;
		$prefix = $wpdb->prefix;
		$rTb = "{$prefix}magenest_giftregistry_wishlist";
		
		$user_id = get_current_user_id();
		$row = $wpdb->get_row("select * from {$rTb} where user_id = {$user_id} ");

		if($row){
			return $row->id;
		}
	}
}

Magenest_Giftregistry_Form_Handler::init();