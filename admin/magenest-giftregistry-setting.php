<?php
if (! defined ( 'ABSPATH' )) exit (); // Exit if accessed directly

if (!class_exists('WC_Settings_Page'))
	include_once dirname (GIFTREGISTRY_PATH) . '/woocommerce/includes/admin/settings/class-wc-settings-page.php';

class Magenest_Giftregistry_Setting extends WC_Settings_Page {
	public function __construct() {
		$this->id = 'giftregistry';
		$this->label = __ ( 'Gift registry', GIFTREGISTRY_TEXT_DOMAIN );
	
		add_filter ( 'woocommerce_settings_tabs_array', array ( $this, 'add_settings_page' ), 20 );
		add_action ( 'woocommerce_settings_' . $this->id, array ( $this, 'output' ) );
		add_action ( 'woocommerce_settings_save_' . $this->id, array ( $this, 'save' ) );
	}
	
	public function output() {
		parent::output();
	}
	/**
	 * Get settings array
	 *
	 * @return array
	 */
	public function get_settings() {
			
			
		$options = apply_filters ( 'woocommerce_giftregistry_settings', array (
					
				array (
						'title' => __ ( 'Gift registry Options', GIFTREGISTRY_TEXT_DOMAIN ),
						'type' => 'title',
						'id' => 'giftregistry_options_title'
				),
				array (
						'title' => __ ( 'Notify gift registry\'s  owner when someone buy gift', GIFTREGISTRY_TEXT_DOMAIN ),
						'desc' => __ ( 'Send email to registry\'s  owner when someone buy gift', GIFTREGISTRY_TEXT_DOMAIN ),
						'id' => 'giftregistry_notify_owner',
						'type' => 'checkbox',
						'autoload' => false ,
				) ,
				array (
						'title' => __ ( 'Notify gift registrant when someone buy gift', GIFTREGISTRY_TEXT_DOMAIN ),
						'desc' => __ ( 'Send email to registrant when someone buy gift', GIFTREGISTRY_TEXT_DOMAIN ),
						'id' => 'giftregistry_notify_registrant',
						'type' => 'checkbox',
						'autoload' => false ,
				) ,
				array (
						'title' => __ ( 'Notify admin when someone buy gift', GIFTREGISTRY_TEXT_DOMAIN ),
						'desc' => __ ( 'Send email to admin when someone buy gift', GIFTREGISTRY_TEXT_DOMAIN ),
						'id' => 'giftregistry_notify_admin',
						'type' => 'checkbox',
						'autoload' => false ,
				) ,
					
				array (
						'name' => __ ( 'Email subject', GIFTREGISTRY_TEXT_DOMAIN ),
						'id' => 'giftregistry_notify_email_subject',
						'type' => 'text',
				),
				array (
						'name' => __ ( 'Email content', GIFTREGISTRY_TEXT_DOMAIN ),
						'desc' =>(__('You can use variables {{buyer_name}},{{store_url}},{{store_name}},{{order_url}} ,{{order_items}}', GIFTREGISTRY_TEXT_DOMAIN)),
						'id' => 'giftregistry_notify_email_content',
						'type' => 'textarea',
				),
					
				array (
						'title' => __ ( 'Not allow adding items to gift registry until fulfill shipping address', GIFTREGISTRY_TEXT_DOMAIN ),
						'desc' => __ ( 'Not allow adding items to gift registry until fulfill shipping address. Recommend check this checkbox', GIFTREGISTRY_TEXT_DOMAIN ),
						'id' => 'giftregistry_shipping_restrict',
						'type' => 'checkbox',
						'default'=>'yes',
						'autoload' => false ,
				) ,
				array (
						'title' => __ ( 'Social title', GIFTREGISTRY_TEXT_DOMAIN ),
						'id' => 'giftregistry_share_title',
						'type' => 'text',
						'autoload' => false
				) ,
				array (
						'title' => __ ( 'Social text', GIFTREGISTRY_TEXT_DOMAIN ),
						'id' => 'giftregistry_share_text',
						'desc' => __('used by Facebook, Twitter and Pinterest. Use {wishlist_url} as placeholder for  URL of your giftregistry to appear', GIFTREGISTRY_PATH),
						'type' => 'textarea',
						'autoload' => false
				) ,
				array (
						'title' => __ ( 'Social image url', GIFTREGISTRY_TEXT_DOMAIN ),
						'id' => 'giftregistry_share_image_url',
						'type' => 'text',
						'autoload' => false
				) ,
				array (
						'title' => __ ( 'Share gift registry via facebook', GIFTREGISTRY_TEXT_DOMAIN ),
						'desc' => __ ( 'Enable this option allow gift registry\'s owner share his gift registry via facebook', GIFTREGISTRY_TEXT_DOMAIN ),
						'id' => 'giftregistry_share_facebook',
						'type' => 'checkbox',
						'autoload' => false
				) ,
				array (
						'title' => __ ( 'Share gift registry via google plus', GIFTREGISTRY_TEXT_DOMAIN ),
						'desc' => __ ( 'Enable this option allow gift registry\'s owner share his gift registry via google plus', GIFTREGISTRY_TEXT_DOMAIN ),
						'id' => 'giftregistry_share_google_plus',
						'type' => 'checkbox',
						'autoload' => false
				) ,
				
				array (
						'title' => __ ( 'Share gift registry via twitter', GIFTREGISTRY_TEXT_DOMAIN ),
						'desc' => __ ( 'Enable this option allow gift registry\'s owner share his gift registry via twitter', GIFTREGISTRY_TEXT_DOMAIN ),
						'id' => 'giftregistry_share_twitter',
						'type' => 'checkbox',
						'autoload' => false
				) ,
				array (
						'title' => __ ( 'Share gift registry via email', GIFTREGISTRY_TEXT_DOMAIN ),
						'desc' => __ ( 'Enable this option allow gift registry\'s owner share his gift registry via email', GIFTREGISTRY_TEXT_DOMAIN ),
						'id' => 'giftregistry_share_email',
						'type' => 'checkbox',
						'autoload' => false
				) ,
				
		)
		);
			
		return $options;
	}
}
return new Magenest_Giftregistry_Setting();
