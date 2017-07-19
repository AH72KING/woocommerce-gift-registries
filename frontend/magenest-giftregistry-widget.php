<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Magenest_Giftregistry_Widget extends WP_Widget {

	public function __construct() {
		$this->widget_cssclass    = 'woocommerce widget_price_filter';
		$this->widget_description = __( 'Shows a price filter slider in a widget which lets you narrow down the list of shown products when viewing product categories.', 'woocommerce' );
		$this->widget_id          = 'woocommerce_price_filter';
		$this->widget_name        = __( 'WooCommerce Price Filter', 'woocommerce' );
		$this->settings           = array(
			'title'  => array(
				'type'  => 'text',
				'std'   => __( 'Filter by price', 'woocommerce' ),
				'label' => __( 'Title', 'woocommerce' )
			)
		);
		parent::__construct();
	} 
}