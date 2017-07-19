<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
		$order = wc_get_order( $order_id );
		$get_meta_datas = $order->get_meta_data();
	    $couple_notes = '';
	    if(!empty($get_meta_datas)){
		    foreach ($get_meta_datas as $key => $meta_datas) {
			    if($meta_datas->key == 'couple_notes' && !empty($meta_datas->value) ){
		    		$couple_notes = $meta_datas->value;
			    }
		    }
		}

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
					 <a href="<?= 'https://wrapistry.shop/shop';?>">
					 	Wrapistry
					 </a>
					 </span>
				 </li>
				 <li>
					<strong>Email:</strong>
					 <span class="text">info@wrapistry.shop.com</span>
				 </li>
			</ul>
		</div>
		<h2> Thank you. Wrapistry</h2>
