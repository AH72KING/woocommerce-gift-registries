<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
class Magenest_Giftregistry_MyAccount {
	
	public function __construct() {
		add_action('woocommerce_after_add_to_cart_button', array($this,'show_gift_registry_link'));
		
		add_action('woocommerce_after_shop_loop_item', array($this,'show_registry_link'));
		
		//account page
		//add_action('woocommerce_after_my_account', array($this,'show_my_registry'));
		add_shortcode( 'create_registry', array($this,'show_my_registry'));
		
		add_shortcode( 'show_registry_selection', array($this,'show_my_registry_items'));
	}
	public function show_gift_registry_link() {
	global $product;
		?>
<script type="text/javascript">
		var giftRegistry = new MagenestGiftRegistry();		</script>
			<?php  
		$prod_all_cat =array();
		$terms = get_the_terms( $product->ID, 'product_cat' );
					foreach ($terms as $term) {
						$product_cat_id = $term->term_id;
						array_push($prod_all_cat, $product_cat_id);
		}
		$str_cat = implode (", ", $prod_all_cat);
		if(empty($str_cat))
		{
			$str_cat = 0;
		}
		
?>
<div class="add-gift-registry" style="margin-top: 10px">
	<input type="hidden" name="add-registry" id="add-registry" /> 
	<input type="hidden" name="product_category" value="<?php echo $str_cat; ?>" readonly /> 
	<a href="#" onclick="giftRegistry.submitRegistry()" class="button" id="add-to-registry"> <?php echo __('Add to registry') ?></a>
	<input type="hidden" name="registryId" class="addto-reg-id" />
</div>
<?php
		
	}
	
	
	public function show_registry_link() {
		global $product;  global $post; 
?>
		
<script type="text/javascript">
		var giftRegistry = new MagenestGiftRegistry();		</script>
		
<div class="add-gift-registry" style="margin-top: 10px">
<?php  if( $product->get_price() == 0 ||  $product->get_price() == '') { ?>



<?php } else {?>
	<input type="hidden" name="add-registry" id="add-registry-hover" /> 
	<input type="hidden" name="add-registry_hover" class="p_id" value="<?php echo $product->id;  ?> "/> 
	<div data-id="" class="button registry_hover add_registry_01" id="registry_hover" style="cursor:pointer"> <?php echo __('Add to registry') ?></div>
	<?php }?>
</div>
			

<?php
		
	}
	
	public function show_my_registry() {
		
	echo $this->show_create_giftregistry_part();

	}
	
	
	public function show_my_registry_items() {
		
		
			$wl_items = Magenest_Giftregistry_Model::get_wishlist_items_for_current_user();
			//echo "<pre>  ";  print_r( $wl_items );  echo "</pre>";  exit;
	
	echo $this->show_my_giftregistry_part($wl_items);
	
	//shared part
	$giftregistry_page_url = get_permalink( get_option('follow_up_emailgiftregistry_page_id'));
	//echo "<pre>  ";  print_r( $giftregistry_page_url );  echo "</pre>";  exit;
	$wid = Magenest_Giftregistry_Model::get_wishlist_id();
	
	$http_schema = 'http://';
	if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'])  {
		$http_schema = 'https://';
	}
		
	$request_link = $http_schema . $_SERVER ["HTTP_HOST"] . $_SERVER ["REQUEST_URI"];
	
		if (strpos ( $request_link, '?' ) > 0) {
			$giftregistry_page_url = $giftregistry_page_url. '&giftregistry_id=' . $wid;
		} else {
			$giftregistry_page_url = $giftregistry_page_url . '?giftregistry_id=' . $wid;
		}
		
		echo $this->share_links($giftregistry_page_url);
		
		
		
		
	}
	
	/**
	 * @return string
	 */
	public  function show_create_giftregistry_part() {
		
		$wid = Magenest_Giftregistry_Model::get_wishlist_id();
		
		//if (is_numeric($wid))
		ob_start();
	
		$template_path = GIFTREGISTRY_PATH.'template/account/';
		$default_path = GIFTREGISTRY_PATH.'template/account/';
	
	
		wc_get_template( 'add-giftregistry.php', array(
		'wid' 		=>$wid,
		'order_id' => '2',
		),$template_path,$default_path
		);
		return ob_get_clean();
	}
	
	/**
	 * 
	 * @param unknown $items
	 * @return string
	 */
	public function show_my_giftregistry_part($items) {
		$wid = Magenest_Giftregistry_Model::get_wishlist_id();
			//echo "<pre>  ";  print_r( $items );  echo "</pre>";  exit;
		ob_start();
		
		$template_path = GIFTREGISTRY_PATH.'template/account/';
		$default_path = GIFTREGISTRY_PATH.'template/account/';
		
		
		wc_get_template( 'my-giftregistry.php', array(
		'items' 		=>$items,
		'wid' 		=>$wid
		),$template_path,$default_path
		);
		return ob_get_clean();
	}
	public function share_links($url) {
		
		
		ob_start();
		
		$template_path = GIFTREGISTRY_PATH.'template/account/';
		$default_path = GIFTREGISTRY_PATH.'template/account/';
		
		
		wc_get_template( 'giftregistry-share.php', array(
		'url' 		=>$url,
		),$template_path,$default_path
		);
		return  ob_get_clean();
	}
	

	
 }

return new Magenest_Giftregistry_MyAccount();
?>

		