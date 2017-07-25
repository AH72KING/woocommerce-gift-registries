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

	public static function is_wishlist_id_belong_to_current_user($id){
		global $wpdb;
		$prefix = $wpdb->prefix;
		$rTb = "{$prefix}magenest_giftregistry_wishlist";
		
		$user_id = get_current_user_id();
		$row = $wpdb->get_row( "select * from {$rTb} where user_id = {$user_id} AND id = {$id}" );
		
		if ($row) {
			return $row->id;
		}
	}
	
	public static function get_all_giftregistry() {
		global $wpdb;
		$prefix = $wpdb->prefix;
		$rTb = "{$prefix}magenest_giftregistry_wishlist";
		
		$user_id = get_current_user_id();
		$row = $wpdb->get_results( "select * from {$rTb}", ARRAY_A );
		
		if($row){
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
		
		$user_id = get_current_user_id();
		$rows = $wpdb->get_results( "select * from {$tbl} where wishlist_id = {$wishlist_id}" , ARRAY_A);
		
		if ($rows) {
			return $rows;
		}
	}
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
	
		$request = unserialize($item['info_request']);
        $cart_page = wc_get_page_permalink( 'cart' );
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
		$order = new WC_Order( $order_id );
	    $order_items = $order->get_items();
	    $w_id = 0;
	    $registryIDs = array();
	    $registryData = array();
	    foreach($order_items as $item_data){
	    	$meta_datas = $item_data->get_meta_data();
	    	foreach ($meta_datas as $key => $meta_data){
	    		if(
	    			(isset($meta_data->key)) && 
	    			($meta_data->key == '_registry_id') && 
	    			(!empty($meta_data->value))
	    		){
	    			$w_id 	= $meta_data->value;
	    			array_push($registryIDs, $w_id);
	    			$ProductData 	= $item_data->get_data();

					$product_id 	= $ProductData['product_id'];
					$variation  	= $ProductData['variation_id'];
					$purchased_qty 	= $ProductData['quantity'];

					$registryData[$w_id][$product_id] = $ProductData;

					if(! $variation) {
						$variation_id = NULL;
					}else{
						$variation_id = $variation;
					}
					if(!empty($w_id)){
						if (!$variation_id) {
							$query = "select * from {$tbl} where product_id = {$product_id} and variation_id is NULL and wishlist_id = {$w_id}";
						}else{
							$query = "select * from {$tbl} where product_id = {$product_id} and variation_id = {$variation_id} and wishlist_id = {$w_id}";
						}
						$item = $wpdb->get_row($query, ARRAY_A);
						
						if(is_array($item)){
							$item_id = $item ['id'];
							$quantityDB = $item ['quantity'];
							$quantityToSave = $quantityDB - $purchased_qty;
							$received_qty = $item ['received_qty'];
							$received_quantity = $received_qty + $purchased_qty;
							$received_order = $item['received_order'];
							
							if($received_order){
								$received_order .= ';' . $order_id;
							}else{
								$received_order .= $order_id;
							}
							
							if($item_id) {
								$wpdb->update ( $tbl, array (
										'quantity' => $quantityToSave,
										'received_qty' => $received_quantity,
										'received_order' => $received_order 
								), array (
										'id' => $item_id 
								) );
							}
						}
					}
				}
			}
		}


			////////////////////////////////////////////////////////////////
			///////////////////Send Notification Email ///////////////////
			////////////////////////////////////////////////////////////////

			$recipients = array();
			$today = date("F j, Y, g:i a");
			$subject = 'Your Wrapistry order receipt from '.$today;
			if(!empty($registryIDs)){
				$subject = 'Wrapistry '. get_option('giftregistry_notify_email_subject').' at '.$today;
				foreach ($registryIDs as $key => $registryID){
					if(!empty($registryID)){
						$wishlist = self::get_wishlist($registryID);
						/*  Send to Registry Owner*/
						$is_send_owner = get_option('giftregistry_notify_owner');
						$registryDataToSend = array();
						$registryDataToSend[$registryID] = $registryData[$registryID];
						if($is_send_owner =='yes') {
							if(!empty($wishlist->user_id)){
								$registryOwnerData = get_userdata($wishlist->user_id);
								if(!empty($registryOwnerData->user_email)){
									if(!in_array($registryOwnerData->user_email, $recipients)){
										$recipients[]= $registryOwnerData->user_email;
										self::sendNotificationEmail($registryOwnerData->user_email, $order_id, 'owner', $subject, $registryDataToSend);
									}
								}
							}
						}

						$is_send_registrant = get_option('giftregistry_notify_registrant');
						if($is_send_registrant == 'yes') {
							if(!empty($wishlist->registrant_email)){
								if(!in_array($wishlist->registrant_email, $recipients)){
									$recipients[]= $wishlist->registrant_email;
									self::sendNotificationEmail($wishlist->registrant_email, $order_id, 'owner', $subject,  $registryDataToSend);
								}
							}
						}
					}
				}
			}

			$is_send_admin = get_option('giftregistry_notify_admin');
			if($is_send_admin=='yes') {
				$adminEmail = get_option('woocommerce_email_from_address');
				if(!empty($adminEmail)){
					if(!in_array($adminEmail, $recipients)){
						$recipients[]= $adminEmail;
						self::sendNotificationEmail($adminEmail, $order_id,'admin', $subject);
					}
				}
			}

			if(isset($order->billing_email) && !empty($order->billing_email)){
				if(!in_array($order->billing_email, $recipients)){
					$recipients[]= $order->billing_email;
					self::sendNotificationEmail($order->billing_email, $order_id,'customer', $subject);
				}
			}
			
			unset($_SESSION ['buy_for_giftregistry_id']);
	}	
	
	public static function sendNotificationEmail($to,$order_id,$template_type, $subject, $registryDataToSend = array()) {
		
		$headers = array();
		$headers [] = "Content-Type: text/html; charset=UTF-8";
		$headers [] = 'From: ' . get_option( 'woocommerce_email_from_name' ) . ' <' . get_option('woocommerce_email_from_address') . '>';

		$content = self::get_email_html_content_by_template($order_id, $template_type, $registryDataToSend);	

		add_filter( 'wp_mail_content_type', array('Magenest_Giftregistry_Model','set_html_content_type' ));

		self::send_email_woocommerce_style($to, $subject, $content, $headers);
			
		remove_filter( 'wp_mail_content_type',  array('Magenest_Giftregistry_Model','set_html_content_type' ));
		
	}
	public static function send_email_woocommerce_style($email, $subject, $message, $headers) {
	  // Get woocommerce mailer from instance
	  $mailer = WC()->mailer();
	  // Wrap message using woocommerce html email template
	  $wrapped_message = $mailer->wrap_message('Order details', $message);
	  // Create new WC_Email instance
	  $wc_email = new WC_Email;
	  // Style the wrapped message with woocommerce inline styles
	  $html_message = $wc_email->style_inline($wrapped_message);
	  // Send the email using wordpress mail function
	  wp_mail( $email, $subject, $html_message, $headers);
	}
	public static function get_email_html_content_by_template($orderId, $template_type, $registryDataToSend = array()) {
		ob_start();
		$email_template_type = '';
		switch ($template_type) {
			case 'customer':
				$email_template_type = 'customer.php';
			break;

			case 'owner':
				$email_template_type = 'owner.php';
			break;

			case 'admin':
				$email_template_type = 'admin.php';
			break;

			default:
				$email_template_type = 'email-content.php';
			break;
		}
		$template_path = GIFTREGISTRY_PATH.'template/email/';
		$default_path = GIFTREGISTRY_PATH.'template/email/';
	
		$order = new WC_Order ( $orderId );
	
		wc_get_template($email_template_type , array(
		'order' =>$order,
		'order_id' => $orderId,
		'registryData' => $registryDataToSend,
		),$template_path,$default_path
		);
		return ob_get_clean();
	}
	public static function get_order_items($orderId) {
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
	
	/**
	 * set html content type for email
	 */
	public static function set_html_content_type() {
		return 'text/html';
	}

	/*public static function wc_custom_order_items( $table, $order ) {
  
	ob_start();
		
		$template = $plain_text ? 'emails/plain/email-order-items.php' : 'emails/email-order-items.php';
		wc_get_template( $template, array(
			'order'                 => $order,
			'items'                 => $order->get_items(),
			'show_download_links'   => $show_download_links,
			'show_sku'              => $show_sku,
			'show_purchase_note'    => $show_purchase_note,
			'show_image'            => true,
			'image_size'            => $image_size
		) );
	   
		return ob_get_clean();
	}
	add_filter( 'woocommerce_email_order_items_table', 'sww_add_wc_order_email_images', 10, 2 );*/


	public static function get_custom_email_content($order_id){
		$order = wc_get_order( $order_id );
		$couple_notes = $order->couple_notes;
	 ?>
		<p>
		<?php _e( "Your order has been received and is now being processed. Your order details are shown below for your reference:", 'woocommerce' ); ?>
		</p>
		<?php $text_align = is_rtl() ? 'right' : 'left'; ?>
		<table class="td" cellspacing="0" cellpadding="6" style="width: 100%; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif; margin-bottom: 40px;" border="1">
			<thead>
				<tr>
					<th class="td" scope="col" style="text-align:<?php echo $text_align; ?>;"><?php _e( 'Product', 'woocommerce' ); ?></th>
					<th class="td" scope="col" style="text-align:<?php echo $text_align; ?>;"><?php _e( 'Quantity', 'woocommerce' ); ?></th>
					<th class="td" scope="col" style="text-align:<?php echo $text_align; ?>;"><?php _e( 'Price', 'woocommerce' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php echo wc_get_email_order_items( $order, array(
					'show_sku'      => true,
					'show_image'    => false,
					'image_size'    => array( 32, 32 ),
					'plain_text'    => false,
					'sent_to_admin' => true,
				) ); ?>
			</tbody>
			<tfoot>
				<?php
					if($totals = $order->get_order_item_totals() ) {
						$i = 0;
						foreach ( $totals as $total ) {
							$i++;
							?><tr>
								<th class="td" scope="row" colspan="2" style="text-align:<?php echo $text_align; ?>; <?php echo ( 1 === $i ) ? 'border-top-width: 4px;' : ''; ?>"><?php echo $total['label']; ?></th>
								<td class="td" style="text-align:<?php echo $text_align; ?>; <?php echo ( 1 === $i ) ? 'border-top-width: 4px;' : ''; ?>"><?php echo $total['value']; ?></td>
							</tr><?php
						}
					}
					if($order->get_customer_note() ) {
						?><tr>
							<th class="td" scope="row" colspan="2" style="text-align:<?php echo $text_align; ?>;"><?php _e( 'Note:', 'woocommerce' ); ?></th>
							<td class="td" style="text-align:<?php echo $text_align; ?>;"><?php echo wptexturize( $order->get_customer_note() ); ?></td>
						</tr><?php
					}?>
					<tr>
						<th class="td" scope="row" colspan="2" style="text-align:<?php echo $text_align; ?>;">
						Couple Note:
						</th>
						<td class="td" style="text-align:<?php echo $text_align; ?>;">
						<?php echo wptexturize($couple_notes); ?>
						</td>
					</tr>
				?>
			</tfoot>
		</table>
		<div style="font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif; margin-bottom: 40px;">
			<h2><?php _e( 'Customer details', 'woocommerce' ); ?></h2>
			<ul>
				<li>
					<strong>Name:</strong>
					 <span class="text"><?= $order->billing_first_name.' '.$order->billing_last_name; ?></span>
				 </li>
				 <li>
					<strong>Email:</strong>
					 <span class="text"><?= $order->billing_email; ?></span>
				 </li>
				 <li>
					<strong>Tel:</strong>
					 <span class="text"><?= $order->billing_phone; ?></span>
				 </li>
			</ul>
		</div>
		<div style="font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif; margin-bottom: 40px;">
			<h2>Store Details</h2>
			<ul>
				<li>
					<strong>Name:</strong>
					 <span class="text">
					 <a href="<?= get_permalink( woocommerce_get_page_id ( 'shop' ) );?>">
					 	<?= get_bloginfo( 'name' ); ?>
					 </a>
					 </span>
				 </li>
				 <li>
					<strong>Email:</strong>
					 <span class="text"><?= $order->billing_email; ?></span>
				 </li>
			</ul>
		</div>
		<div style="font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif; margin-bottom: 40px;">
			<?php self::get_order_items($order->id);?>	
		</div>
	<?php
	}
}
