<?php
/**
* Plugin Name: WooCommerce Gift Registries
* Plugin URI: https://github.com/AH72KING/woocommerce-gift-registries
* Description:Add gift registry function to website
* Author: Ahsan Khan
* Author URI: http://ahsandev.com
* Version: 1.0
* Text Domain: woocommerce-gift-registries
* Domain Path: /languages/
*
* Copyright: (c) 2011-2015 Creative Tech Solutions. (info@ahsandev.com)
*
*
* @package   woocommerce-gift-registries
* @author    Ahsan
* @category  Gift registries
* @copyright Copyright (c) 2014, Creative Tech Solutions, Inc.
*/
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


if (! defined ('GIFTREGISTRY_TEXT_DOMAIN'))
	define ( 'GIFTREGISTRY_TEXT_DOMAIN', 'giftregistry' );

// Plugin Folder Path
if (! defined ('GIFTREGISTRY_PATH'))
	define ('GIFTREGISTRY_PATH', plugin_dir_path ( __FILE__ ) );

// Plugin Folder URL
if (! defined ('GIFTREGISTRY_URL'))
	define ('GIFTREGISTRY_URL', plugins_url ( 'woocommerce-gift-registries', 'woocommerce-gift-registries.php' ) );

// Plugin Root File
if (! defined ('GIFTREGISTRY_FILE'))
	define ('GIFTREGISTRY_FILE', plugin_basename ( __FILE__ ) );

if ( ! class_exists( 'Magenest_Giftregistry_Admin' ) ) {
	require_once( 'admin/magenest-giftregistry-admin.php');
}

/*ajax function for showing all categories on manage registries page for select list ends here*/
class Magenest_Giftregistry{
		static $giftregistry_instance;
		public $registries_obj;
		/** plugin version number */
		const VERSION = '1.8';
		
		/** plugin text domain */
		const TEXT_DOMAIN = 'giftregistry';
		
		public function __construct() {
			global $wpdb;

					$this->registries_obj = new Magenest_Giftregistry_Admin();
				register_activation_hook ( GIFTREGISTRY_FILE, array ($this,'install' ) );
				add_action ( 'init', array ($this,'load_text_domain' ), 1 );
					
				//add_action( 'init', array($this,'add_label_taxonomies'), 5 );
				add_action('wp_enqueue_scripts', array($this,'load_custom_scripts'));
				add_action('wp_print_scripts', array($this,'test_ajax_load_scripts'));
				//add_action('wp_print_scripts', array($this,'add_media_script'));
		        $this->include_for_frontend();
		        add_action( 'init', array('Magenest_Giftregistry_Shortcode','init'), 5 );
		        add_action( 'init', array('Magenest_Giftregistry_Form_Handler','init'), 5 );
		        add_action('init',array($this,'register_session'));
	         
		        if (is_admin ()) {
		        	add_action ( 'admin_enqueue_scripts', array ($this,'load_admin_scripts' ), 99 );
		        	require_once plugin_dir_path ( __FILE__ ). 'admin/magenest-giftregistry-setting.php';
		        	
			        //add_action ( 'admin_menu', array ( $this, 'admin_menu' ), 5 );
			        add_filter( 'set-screen-option', [ __CLASS__, 'set_screen' ], 10, 3 );
					add_action( 'admin_menu', [ $this, 'plugin_menu' ] );
		        }
		        //update information after a guest buy gift registry
		        add_action('woocommerce_checkout_order_processed', array('Magenest_Giftregistry_Model','after_buy_gift'), 5 );
		}

		public static function set_screen( $status, $option, $value ) {
			return $value;
		}
		public function plugin_menu() {

		$hook = add_menu_page(
			'Gift Registry',
			'Gift Registry',
			'manage_options',
			'gift_registry',
			[ $this, 'plugin_settings_page' ]
		);

		add_action( "load-$hook", [ $this, 'screen_option' ] );
		}
		public function plugin_settings_page() {

			if (isset($_REQUEST['delete'])) {
				if (isset($_REQUEST['id'])){
					$this->registries_obj->delete($_REQUEST['id']);
				}
			}elseif (isset($_REQUEST['edit'])) {
				if (isset($_REQUEST['id'] )){
					$this->registries_obj->edit($_REQUEST['id']);
				}
			}else{ 
			
			 ?>
				<div class="wrap">
					<h2>Gift Registry</h2>

					<div id="poststuff">
						<div id="post-body" class="metabox-holder columns-1">
							<div id="post-body-content">
								<div class="meta-box-sortables ui-sortable">
									<form method="post">
										<?php
											$this->registries_obj->prepare_items();
											$this->registries_obj->display(); 
										?>
									</form>
								</div>
							</div>
						</div>
						<br class="clear">
					</div>
				</div>
				<?php
			}
		}

		/**
		* Screen options
		*/
		public function screen_option() {

			$option = 'per_page';
			$args   = [
				'label'   => 'Registries',
				'default' => 20,
				'option'  => 'registries_per_page'
			];

			add_screen_option( $option, $args );
			$this->registries_obj = new Magenest_Giftregistry_Admin();
		}

		/**
		 * Load the Text Domain for i18n
		 *
		 * @return void
		 * @access public
		 */
		function load_text_domain(){
			load_plugin_textdomain ( GIFTREGISTRY_TEXT_DOMAIN, false, 'woocommerce-gift-registries/languages/' );
		}
		public function register_session(){
			if(!session_id()){
				session_start();
			}
		}
		public function load_admin_scripts(){
			global $woocommerce;
			
			if (is_object($woocommerce)){
				wp_enqueue_style ( 'woocommerce_admin_styles', $woocommerce->plugin_url () . '/assets/css/admin.css' );
			}
			wp_enqueue_style('giftregistryadmin', GIFTREGISTRY_URL. '/assets/magenestgiftregistry.css');
		}
		public function load_custom_scripts($hook){	
			wp_enqueue_style('magenestgiftregistry' , GIFTREGISTRY_URL .'/assets/magenestgiftregistry.css');
			wp_enqueue_script('magenestgiftregistryjs' , GIFTREGISTRY_URL .'/assets/magenestgiftregistry.js');
		}
		public function test_ajax_load_scripts(){
			// load our jquery file that sends the $.post request
			wp_enqueue_script( "ajax-test", plugin_dir_url( __FILE__ ) . 'assets/registry_finder.js' );
			// make the ajaxurl var available to the above script
			wp_localize_script( 'ajax-test', 'the_ajax_script', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );	
		}
		public function include_for_frontend() {
			
			include_once GIFTREGISTRY_PATH .'model/magenest-giftregistry-model.php';
			include_once GIFTREGISTRY_PATH .'frontend/magenest-giftregistry-shortcode.php';
			include_once GIFTREGISTRY_PATH . 'frontend/magenest-giftregistry-frontend.php';
			include_once GIFTREGISTRY_PATH . 'frontend/magenest-giftregistry-myaccount.php';
			include_once GIFTREGISTRY_PATH . 'frontend/magnest-form-handler.php';
		}
		public function install() {
			global $wpdb;
			// get current version to check for upgrade
			$installed_version = get_option( 'magenest_giftregistry_version' );
				if (!function_exists('dbDelta')) {
					include_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		
				}
				$prefix = $wpdb->prefix;

				$query = "CREATE TABLE IF NOT EXISTS `{$prefix}magenest_giftregistry_wishlist` (
				`id` int(11) unsigned NOT NULL auto_increment,
				`user_id` varchar (255)  NOT NULL,
				`sharing_code` varchar (255)  NOT NULL,
				`shared` tinyint  NOT NULL,
				`created_at` timestamp NULL,
				`update_at` timestamp NULL,
				`status` VARCHAR(50) NOT NULL,
				`title` VARCHAR(250)  NULL,
				`registrant_firstname` VARCHAR(250)  NULL,
				`registrant_lastname` VARCHAR(250)  NULL,
				`registrant_email` VARCHAR(250)  NULL,
				`coregistrant_firstname` VARCHAR(250)  NULL,
				`coregistrant_lastname` VARCHAR(250)  NULL,
				`coregistrant_email` VARCHAR(250)  NULL,
				`event_date_time` DATETIME  NULL,
				`event_location` VARCHAR(250)  NULL,
				`message` text null,
				`image` varchar (255)   NULL,
				PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;";
				/**
				 *  $product_id, $quantity = 1, $variation_id = '', $variation = '', $cart_item_data = array()
				 */
				dbDelta($query);

				$query ="ALTER TABLE {$prefix}magenest_giftregistry_wishlist
						ADD COLUMN  `registrant_type` VARCHAR(250)  NULL after `registrant_email`,
						ADD COLUMN  `coregistrant_type` VARCHAR(250)  NULL after `coregistrant_email`";
				dbDelta($query);

				$query = "CREATE TABLE IF NOT EXISTS `{$prefix}magenest_giftregistry_item` (
				`id` int(11) unsigned NOT NULL auto_increment,
				`wishlist_id` int(11)NOT NULL,
				`product` varchar(255) NULL,
				`product_id` int(11) NOT NULL,
				`quantity` int(11) NOT NULL,
				`received_qty` int(11)  NULL,
				`received_order` TEXT NULL,
				`variation_id` int(11) NULL,
				`variation` varchar(255) NULL,
				`cart_item_data` text NULL,
				`description` varchar (255)  NOT NULL,
				`info_request` text,
				`add_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
				PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;";
					
				dbDelta( $query );

				$query = "CREATE TABLE IF NOT EXISTS `{$prefix}magenest_giftregistry_event` (
				`id` int(11) unsigned NOT NULL auto_increment,
				`name` varchar (255) NULL,
				`image` varchar (255) NULL,
				`created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
				PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;";
					
				dbDelta( $query );
				
				$this->create_pages();
				update_option( 'magenest_giftregistry_version', self::VERSION );
			// upgrade if installed version lower than plugin version
			if( -1 === version_compare( $installed_version, self::VERSION)){
				$this->upgrade( $installed_version );
			}
		}
		public function upgrade() {
			
		}
		/**
		 * create gift registry pages for plugin
		 */
		public function create_pages() {
			if (!function_exists('wc_create_page'))  {
			   include_once dirname ( __DIR__ ) . '/woocommerce/includes/admin/wc-admin-functions.php';
			}
			$pages =  array (
				'giftregistry' => array (
					'name'    => _x ( 'giftregistry', 'Page slug', 'woocommerce' ),
					'title'   => _x ( 'Gift Registry', 'Page title', 'woocommerce' ),
					'content' => '[magenest_giftregistry]'
						)
			);
	
			foreach ( $pages as $key => $page ) {
				wc_create_page ( esc_sql ( $page ['name'] ), 'follow_up_email' . $key . '_page_id', $page ['title'], $page ['content'], ! empty ( $page ['parent'] ) ? wc_get_page_id ( $page ['parent'] ) : '' );
			}
		}
	
		/**
		 * add menu items
		 */
		public function admin_menu() {
			global $menu;
			include_once GIFTREGISTRY_PATH .'admin/magenest-giftregistry-admin.php';
			
			$admin = new Magenest_Giftregistry_Admin();
			add_menu_page(__('Gift registry'), __('Gift registry'), 'manage_woocommerce','gift_registry', array($admin,'giftregistry_manage' ));
		
		}
		public static function getInstance() {
			if (! self::$giftregistry_instance) {
				self::$giftregistry_instance = new Magenest_Giftregistry();
			}
		
			return self::$giftregistry_instance;
		}
}

$magenest_giftregistry_loaded = Magenest_Giftregistry::getInstance();
$GLOBAl['giftregistryresult'] = array();

function addScriptToTheme(){
	$registryCountSession = 0;
	//Magenest_Giftregistry_Model::get
	if(isset($_SESSION['registryCount']) && !empty($_SESSION['registryCount'])){
		$registryCountSession = $_SESSION['registryCount'];  
	}
?>
<script>
	jQuery(document).ready( function($) {
		$(document).on('click', 'body .registry_hover',function(event){
			// get search term
			var thisButton = $(this);

			var registryId = thisButton.attr('data-id');
			
			var product_id = thisButton.parent().children('.p_id').val();
			var registries = '<?= $registryCountSession;?>';

			if(registries > 0){
				if(registryId!='' || registries == 1){

					var sPageURL = window.location;
					var myhref   = sPageURL['href'];
					var equal_sign  = myhref.indexOf("=");
					var registry_unique_number   = myhref.substr((equal_sign+1));

					$.ajax({
						url : the_ajax_script.ajaxurl,
						type:"POST",
						data:{
						'action':'ajax_registry',
						'term' :product_id,
						'registryId':registryId,
						'registry_unique_number': registry_unique_number
						},
						beforeSend:function(){
						    thisButton.html('Add To Registry <i class="fa fa-circle-o-notch fa-spin fa-fw"></i>');
						    jQuery('.add-register-after').html('ADD TO REGISTRY <i class="fa fa-circle-o-notch fa-spin fa-fw"></i>');
						},
						success:function(result){
						    thisButton.html('Add To Registry');
						    jQuery('.add-register-after').html('ADD TO REGISTRY');
							 if(result == 'success_added' || result == 'success_added0'){

							     jQuery('#registries_wrapper').hide();
							     jQuery('.add_registry_01').attr('data-id', '');
							     jQuery("#popup1").css("display", "block");
							      
						     }
						}
					});

				}else{
				    loadGiftRegistries(thisButton, product_id);
				}
			}else{ 
			 window.location.assign('<?=get_site_url()?>/create-registry/');
			}
		});
	});
</script>
	 
<?php } 

function addScriptToManageRegistry(){
 	global $post;
?>
<script>
	jQuery(document).ready( function($) {
		jQuery('#category_filter').change(function() {
			var id = jQuery( "select#category_filter option:checked" ).val();
			var w_id = jQuery( "input#giftregistry_id" ).val();
				if(id==0){
					$.ajax({
						url : the_ajax_script.ajaxurl,
						type:"POST",
						data:{
						'action':'ajax_all_cat',
						'term' : id,
						'wishlist_id': w_id, 
						'cuser_id': '<?php echo  get_current_user_id (); ?>',
						'requested_page_id': '<?php echo  $post->ID; ?>'
						},
						success:function(result){
							jQuery('#ajax_results').fadeIn().html(result);
						}
					});
				}else{
					$.ajax({
							url : the_ajax_script.ajaxurl,
							type:"POST",
							data:{
							'action':'ajax_cat_filter',
							'term' : id,
							'wishlist_id': w_id, 
							'cuser_id': '<?php echo  get_current_user_id (); ?>',
							'requested_page_id': '<?php echo  $post->ID; ?>'
							},
							success:function(result){
								jQuery('#ajax_results').fadeIn().html(result);
							}
					});
				}
		});
	});
 </script>	 
<?php }

function addScriptTochechkout(){
	global $post;
	$cpid =$post->ID;
} 

	add_action( 'wp_footer', 'addScriptToTheme' );
	add_action( 'wp_footer', 'addScriptTochechkout' );
	add_action( 'wp_footer', 'addScriptToManageRegistry' );

	/***ajax search for registry on find registy page***/
	add_action('wp_ajax_dhemy_ajax_search','dhemy_ajax_search');
	add_action('wp_ajax_nopriv_dhemy_ajax_search','dhemy_ajax_search');

function dhemy_ajax_search(){
	global $wpdb;
		$search_term = $_POST['term'];
		$searchResult = $wpdb->get_results("SELECT * FROM wp_magenest_giftregistry_wishlist
		WHERE `registrant_firstname` LIKE '". $search_term ."%' 
		OR `registrant_lastname` LIKE '". $search_term ."%' 
		OR `coregistrant_firstname`LIKE '". $search_term ."%' 
		OR `coregistrant_lastname`LIKE '". $search_term ."%'");
		if(!empty($searchResult)){ ?>
			<h1>We have found <?php echo count($searchResult); ?> results</h1>
				<div class="col-md-12">
					<div class="col-md-2" id="couple-photo">
						<h3>Profile Photo</h3>
					</div>
					<div class="col-md-3 search-row" id="couple-name" >
						<h3>Couple Name</h3>
					</div>
					<div class="col-md-3 search-row" id="couple-name" >
						<h3>Wedding Date</h3>
					</div>
					<div class="col-md-4 search-row" id="couple-details">
						<h3>Action</h3>
					</div>
				</div>
				</br></br></br></br></br>
			<?php foreach($searchResult as $record){ ?>
				<div class="col-md-12">
					<?php 
						$profile_photo = $record->image;
						if(empty($profile_photo)){
							$profile_photo=  get_template_directory_uri().'/images/favicon.png';
						}
					?>
					<div class="col-md-2" id="couple-photo">
						<img src= "<?php echo $profile_photo ;?>">
					</div>
					<div class="col-md-3 search-row" id="couple-name" >
						<p>
							<?php 
								echo $record->registrant_firstname; 
								echo ' '.$record->registrant_lastname; 
								echo "&nbsp"; 
								echo "and"; 
								echo "&nbsp";
								echo $record->coregistrant_firstname;
								echo ' '.$record->coregistrant_lastname;
							?> 
						</p>
					</div>
					<div class="col-md-3 search-row" id="couple-name" >
						<p>
							<?php echo date('d-m-Y',strtotime($record->event_date_time));?> 
						</p>
					</div>
					<div class="col-md-4 search-row" id="couple-details">
						<a style="margin: 15% 0;" class="btn" id="add-to-registry" href="<?php echo site_url(); ?>/giftregistry/?giftregistry_id=<?php echo $record->id; ?>">
							See Registry
						</a>
					</div>
				</div>
				</br></br></br></br></br>
		<?php
			} // foreach end 
		}	// if end
	exit;
}
		
/*ajax function for Add to registry button on product hover*/
add_action('wp_ajax_ajax_registry','ajax_registry');
add_action('wp_ajax_nopriv_ajax_registry','ajax_registry');

function ajax_registry() {
	global $wpdb;
		$prefix = $wpdb->prefix;
		$rTb = "{$prefix}magenest_giftregistry_wishlist";
	    $registry_unique_number = $_POST['registry_unique_number']; 
		$user_id = get_current_user_id();
		if(isset($_POST['registryId']) && !empty($_POST['registryId'])){
		    $whislistId = $_POST['registryId'];
		    $row = $wpdb->get_row("select * from {$rTb} where id = {$whislistId}");
		}else{
		    $row = $wpdb->get_row("select * from {$rTb} where user_id = {$user_id} ORDER BY id desc LIMIT 1");
		}
		if($row->id) {
			$r_id = $row->id;
			$item_tbl = $wpdb->prefix . 'magenest_giftregistry_item';
				///////////check the shipping address
				$customer_id = get_current_user_id ();
				$addr_1 = get_user_meta($customer_id,'shipping_address_1', true);
				$addr_2 = get_user_meta($customer_id,'shipping_address_2', true);
				if (!$addr_1 && (get_option('giftregistry_shipping_restrict','yes') =='yes')) {
					echo $r_data = 'notice_shipping_address';
					return;
				}
				$data = array();
				$data['product'] = (isset($_REQUEST['product'])) ? $_REQUEST['product']:'';
				$data['product_id'] = $_POST['term'];
				$data['quantity'] = 1;
				
				if(isset($_REQUEST['variation_id'])){
					$data['variation_id'] = $_REQUEST['variation_id'];
				}
				
				if(isset($_REQUEST['variation'])){
					$data ['variation'] = $_REQUEST ['variation'];
				}
				
				$ajax_request =array();
				$ajax_request['quantity'] = 1;
				$ajax_request['add-to-giftregistry'] = $_POST['term'];
				$ajax_request['add-registry'] = 1;

				$info = serialize ($ajax_request);
				$data['info_request'] = $info;
				$data['wishlist_id'] = $r_id;
				if(isset($data['product_id']) &&!empty($data['product_id'])){ 
				    $if_product_exist = $wpdb->get_results("SELECT * FROM  wp_magenest_giftregistry_item WHERE `wishlist_id` = ".$r_id." AND `product_id` = ".$data['product_id']."");
				}
				$total_reocrds = count($if_product_exist);

				if($total_reocrds > 0){
					$row_id = $if_product_exist[0]->id;
					$current_quantity 	= 1;
					$previous_quantity 	= $if_product_exist[0]->quantity;
					$total_quantity 	= $current_quantity + $previous_quantity;
					$data['quantity'] 	= $total_quantity ;

					$update_item = $wpdb->update($item_tbl, $data, array('id' => $row_id));

				}else{
					$wpdb->insert($item_tbl, $data);
					echo $r_data = 'success_added';
				}
		}
	exit;
}

	/*other ajax function ends here*/
	/*ajax function for category filter on manage registry page for select list starts here*/
	add_action('wp_ajax_ajax_cat_filter','ajax_cat_filter');
	add_action('wp_ajax_nopriv_ajax_cat_filter','ajax_cat_filter');

function ajax_cat_filter() {
		global $wpdb;
			$current_user_id = $_POST['cuser_id'];
			$selected_term = $_POST['term'];
			$wishlist_id = $_POST['wishlist_id'];
			$current_page_id = $_POST['requested_page_id'];
			$query_results = $wpdb -> get_results("SELECT * FROM wp_magenest_giftregistry_item INNER JOIN wp_magenest_giftregistry_wishlist ON wp_magenest_giftregistry_wishlist.id=wp_magenest_giftregistry_item.wishlist_id WHERE wp_magenest_giftregistry_wishlist.user_id = {$current_user_id}");
			$simple_array = json_decode(json_encode($query_results), True);
			$all_cat =array();
			foreach($simple_array as $result){
				$available_categories = $result['product_cat_id'];
				if (preg_match('/\b' . $selected_term . '\b/', $available_categories)) { 
				array_push($all_cat, $result);
				}
			}
			if(!empty($all_cat) && $current_page_id == 399){
				foreach($all_cat as $item){

					$_product = wc_get_product ( $item ['product_id'] );
					$http_schema = 'http://';
					if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'])  {
						$http_schema = 'https://';
					}
					$request_link  = $http_schema. $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];

					if(strpos($request_link, '?') > 0){
					   $delete_link = $http_schema. $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] . '&update_giftregistry_item=1&remove_item=1&item_id='. $item['id'];
					}else{
					   $delete_link = $http_schema. $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] . '?update_giftregistry_item=1&remove_item=1&item_id='. $item['id'];
					}
		?>
					<tr>
						<td>
							<a href="<?php echo $delete_link ?>" class="remove" title="s">&times;</a>
						</td>
						<td class="product-thumbnail">
							<?php
								printf ( '<a href="%s">%s</a>', $_product->get_permalink(), $thumbnail );
							?>
						</td>
						<td class="product-name">
							<?php
								echo sprintf ( '<a href="%s">%s</a>', $_product->get_permalink(), $_product->get_title());
							?>
						</td>
						<td class="product-price">
							<?php
								echo $_product->get_price_html();
							?>
						</td>
						<td class="product-quantity">
							<input type="text" name="wishlist_item[<?php echo $item['id']?>]"  id="wishlist_item[<?php echo $item['id']?>]" value="<?php if (isset( $item['quantity']))  echo $item['quantity']?>" />	
				        </td>
						<td class="received-quantity">
							<?php 
								if (isset( $item['received_qty'])) {  
									echo $item['received_qty']; 
								}else{
									echo 0;
								}
							?>
				        </td>
					</tr>
		<?php 
				}
			
			}elseif( !empty($all_cat) && $current_page_id == 385){
				foreach($all_cat as $item){
					$_product = wc_get_product( $item['product_id']);
		?>
					<tr>
						<td class="product-thumbnail" style="width: 25%">
							<?php
								$thumbnail = $_product->get_image();
								printf ( '<a href="%s">%s</a>', $_product->get_permalink(), $thumbnail );
							?>
						</td>
						<td class="product-name">
							<?php 
								echo sprintf( '<a href="%s">%s</a>', $_product->get_permalink(), $_product->get_title() )
							?>
						</td>
						<td class="product-price">
							<?php
								echo $_product->get_price_html();
							?>
						</td>
						<td style="width: 30px;" class="product-quantity">
							<?php 
								if(isset( $item['quantity'])){
									$receive_qty=0;
										if (isset($item['received_qty'])){
												$receive_qty =$item['received_qty'];
										}
											$remain_qty = $item['quantity'] - $receive_qty;
										if($remain_qty < 0){
											 $remain_qty = 0; 
										}
										echo $remain_qty;
								}
							?>
						</td>
						<td>
							<input style="width: 40px" type="text" id="<?php echo $item['id']?>" />
						</td>
						<td>
							<button style="background-color: #1aada3; color: white;" data-buy="<?php echo $request_st ?>"  name="<?php echo $item['id']?>"  class="single_add_to_cart_button button alt" onclick="giftit(this)">
								<?php echo __('Buy') ?>
							</button>
						</td>
					</tr>
		<?php  } 
			}else{ 
		?>
				<tr>
					<td colspan='12'>
						<h3>No Products found against this category.</h3>
					</td>
				</tr>
		<?php } 
	}

/*ajax function for category filter on manage registry page for select list ends here*/
/*ajax function for showing all categories on manage registry page for select list starts here*/
	add_action('wp_ajax_ajax_all_cat','ajax_all_cat');
	add_action('wp_ajax_nopriv_ajax_all_cat','ajax_all_cat');

function ajax_all_cat(){

		global $wpdb;
			$current_user_id 	= $_POST['cuser_id'];
			$selected_term 		= $_POST['term'];
			$wishlist_id 		= $_POST['wishlist_id'];
			$current_page_id 	= $_POST['requested_page_id'];
			$query_results 		= $wpdb -> get_results("SELECT * FROM wp_magenest_giftregistry_item INNER JOIN wp_magenest_giftregistry_wishlist ON wp_magenest_giftregistry_wishlist.id=wp_magenest_giftregistry_item.wishlist_id WHERE wp_magenest_giftregistry_wishlist.user_id = {$current_user_id}");
			$simple_array = json_decode(json_encode($query_results), True);
		if(!empty($simple_array) && $current_page_id == 399){
			foreach($simple_array as $item){
				$_product = wc_get_product ( $item ['product_id'] );
				$http_schema = 'http://';
				if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']){
					$http_schema = 'https://';
				}
				$request_link  = $http_schema. $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] ;
			
				if(strpos($request_link, '?') > 0) {
				   $delete_link = $http_schema. $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] . '&update_giftregistry_item=1&remove_item=1&item_id='. $item['id'];
				}else{
				   $delete_link = $http_schema. $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] . '?update_giftregistry_item=1&remove_item=1&item_id='. $item['id'];
				}
			?>
				<tr>
					<td>
						<a href="<?php echo $delete_link ?>" class="remove" title="s">&times;</a>
					</td>
					<td class="product-thumbnail">
						<?php
							printf( '<a href="%s">%s</a>', $_product->get_permalink(), $thumbnail );
						?>
					</td>
					<td class="product-name">
						<?php
							echo sprintf( '<a href="%s">%s</a>', $_product->get_permalink (), $_product->get_title());
						?>
					</td>
					<td class="product-price">
						<?php
							echo $_product->get_price_html();
						?>
					</td>
					<td class="product-quantity">
						<input type="text" name="wishlist_item[<?php echo $item['id']?>]"  id="wishlist_item[<?php echo $item['id']?>]" value="<?php if (isset( $item['quantity']))  echo $item['quantity']?>" />		
			        </td>
					<td class="received-quantity">
						<?php 
							if(isset( $item['received_qty'])){  
								echo $item['received_qty']; 
							}else{
								echo 0;
							}
						?>
			        </td>
				</tr>
		<?php 
			}	
		}elseif(!empty($simple_array) && $current_page_id == 385){
			foreach($simple_array as $item){
				$_product = wc_get_product ( $item ['product_id'] );
		?>
					<tr>
						<td class="product-thumbnail" style="width: 25%">
							<?php
								$thumbnail = $_product->get_image();
								printf ( '<a href="%s">%s</a>', $_product->get_permalink(), $thumbnail );
							?>
						</td>
						<td class="product-name">
							<?php 
								echo sprintf( '<a href="%s">%s</a>', $_product->get_permalink(), $_product->get_title() )
							?>
						</td>
						<td class="product-price">
							<?php
								echo $_product->get_price_html();
							?>
						</td>
						<td style="width: 30px;" class="product-quantity">
							<?php 
								if(isset( $item['quantity'])){
									$receive_qty=0;
										if(isset($item['received_qty'])){ 
											$receive_qty =$item['received_qty'];
										}
											$remain_qty = $item['quantity'] - $receive_qty;
										if($remain_qty < 0){ 
											$remain_qty = 0;
										}
									echo $remain_qty;
								}
							?>
						</td>
						<td>
							<input style="width: 40px" type="text" id="<?php echo $item['id']?>" /></td>
						<td>
							<button style="background-color: #1aada3; color: white;" data-buy="<?php echo $request_st ?>" name="<?php echo $item['id']?>" class="single_add_to_cart_button button alt" onclick="giftit(this)">
								<?php echo __('Buy') ?>	
							</button>
						</td>
					</tr>
				
	<?php 
			} 
		}else{ 
	?>
			<tr>
				<td colspan='12'><h3>No Products found.</h3></td>
			</tr>
	<?php 
		}
}