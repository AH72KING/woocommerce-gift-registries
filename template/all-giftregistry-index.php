<?php    
//exit;
wp_enqueue_style('thickbox');
wp_enqueue_script('thickbox');    

$acount_page = get_page_by_path('my-account');

$account_link = get_permalink( get_option('woocommerce_myaccount_page_id'));
$my_account_page_url = get_permalink( get_option('woocommerce_myaccount_page_id'));
//echo 'My Account Page Url'.$my_account_page_url; exit;  // http://wrap.phillco.co.za/my-account/
$w_page = get_permalink( get_option('follow_up_emailgiftregistry_page_id'));;
$http_schema = 'http://';
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'])  {
	$http_schema = 'https://';
}
	
$request_link  = $http_schema. $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] ;
	
if (strpos($request_link, '?') > 0)  {
	$buy_link = $w_page. '&giftregistry_id=';
} else {
	$buy_link =$w_page . '?giftregistry_id=';

}

$giftregistry_id = get_option('follow_up_emailgiftregistry_page_id');

$giftregistry_page_path = get_permalink($giftregistry_id);



$flow_img = GIFTREGISTRY_URL.'/assets/flow.jpg';



?>

<img src="<?php //echo $flow_img?>" />

 <!--guest-greeting-->
 <!-- <div class=" col-md-12" style="text-align:center;">
    <p style="
    color: #333;
    font-family: Georgia,Times New Roman,serif;
    font-size: 1.125em;
    font-style: italic;
    line-height: 1.5em;"><span style="color: #1aada3">What to do for users</span><br>
      <br>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
<br>
    </p>
  </div>-->
  <!--/guest-greeting-->
  
<!-- Table of result -->
<?php 
//code adde by dev732
if($_GET['registry_id']){
    $registry_id	=	$_GET['registry_id'];
	// DELTING SINGLE REGISTRY (WISHLIST TABLE)
	global $wpdb;
	$prefix = $wpdb->prefix;
	$tbl = "{$prefix}magenest_giftregistry_wishlist";
	$wpdb->delete($tbl, array('id' => $registry_id));
	
	// DELETING ALL ITEMS IN THAT REGISTRY (ITEM TABLE)
	global $wpdb;
	$prefix 		= $wpdb->prefix;
	$tbl 			= "{$prefix}magenest_giftregistry_item";
	$wpdb->delete($tbl, array('wishlist_id' => $registry_id));
} 

//echo 'All registry';
$collection = array();
 		global $giftregistryresult;

if (isset($_SESSION['registryresult'])) $collection = $_SESSION['registryresult'];
$collection =$giftregistryresult ;

// code added by dev732
global $wpdb;
		$prefix = $wpdb->prefix;
		$rTb = "{$prefix}magenest_giftregistry_wishlist";
		$user_id = get_current_user_id();
        $query = "select * from {$rTb} where user_id = {$user_id}";
        $collection = $wpdb->get_results($query);
//echo '<pre>'; print_r($collection); echo '</pre>';exit;
if (!empty($collection)) { 
?>
<table class="manage-registry-table">
  <tr>
    <th><?php echo __('Registry Title' ,GIFTREGISTRY_TEXT_DOMAIN) ?></th>
    <th><?php echo __('Name' ,GIFTREGISTRY_TEXT_DOMAIN) ?></th>
    <th><?php echo __('Co-Registrant name' ,GIFTREGISTRY_TEXT_DOMAIN) ?></th>
    <th class="hidden-xs"><?php echo __('Date' ,GIFTREGISTRY_TEXT_DOMAIN) ?></th>
    <!--<th><?php echo __('Share' ,GIFTREGISTRY_TEXT_DOMAIN) ?></th>-->
    <th><?php echo __('Remove' ,GIFTREGISTRY_TEXT_DOMAIN) ?></th>
    <th><?php echo __('Manage' ,GIFTREGISTRY_TEXT_DOMAIN) ?></th>
  </tr>
  <?php foreach ($collection as $item) {
  	//echo '<pre>'; print_r($item); echo '</pre>';exit;
  	$link =$buy_link .$item->id; //http://wrap.phillco.co.za/giftregistry/?giftregistry_id=19
  	//echo $link; exit;
  ?>
  <tr>
      <td><?php echo $item->title; ?></td>
      
      <td><?php echo $item->registrant_firstname . ' ' . $item->registrant_lastname ?></td>
  
  
      <td><?php echo $item->coregistrant_firstname . ' ' . $item->coregistrant_lastname ?></td>
  
  
      <td class="hidden-xs"><?php echo date('d M, y h:m:s', strtotime($item->created_at));  ?></td>
  
      <!--<td><a href="#" > <?php echo __("Share",GIFTREGISTRY_TEXT_DOMAIN) ?></a></td>-->    
 
      <td><a class="confirm_del" title="Remove" style="cursor:pointer" data-id="<?=$item->id?>" > <?php echo __("X",GIFTREGISTRY_TEXT_DOMAIN) ?></a></td>
  
  
  <td> <a class="col_blue" href="<?php echo get_home_url().'/manage-single-registry?giftregistry_id='.$item->id;?>"> <?php echo __("Manage",GIFTREGISTRY_TEXT_DOMAIN) ?></a></td>
  </tr>
  <?php  } ?>
</table>
<?php  } ?>
<!-- End of result  href="<?php //echo get_home_url().'/my-account/manage-registry?registry_id='.$item->id;?>"-->
<!-- table of search -->
<form id="searchgiftregistry" method="POST" action="<?php echo $giftregistry_page_path ?>">
<input name="searchgiftregistry" value="1" type="hidden" />
<table class="" style="margin-top: 10px; margin-bottom: 10px;">
  <tr>
   
    <td><input style="width: 50%;display:none" type="text" name="grname"  placeholder="Type name of person to search" class="search-field"<?php if( isset($_SESSION['registrynamesearch'])) {?> value="<?php echo $_SESSION['registrynamesearch'] ?>" <?php }?>></td>
  </tr>
 
  <!----<tr>
    <td><label><?php //echo __('Email',GIFTREGISTRY_TEXT_DOMAIN) ?></label></td>
    <td><input type="text" name="email" <?php //if( isset($_SESSION['registryemailsearch'])) {?> value="<?php //echo $_SESSION['registryemailsearch'] ?>" <?php //}?> ></td>
  </tr>---->
 <!--- <tr>
    <td><input type="submit" name="submit" value="<?php //echo __('Search' , GIFTREGISTRY_TEXT_DOMAIN) ?>"></td>
  </tr>---->
</table>
</form>
<div class="row" id="search-results" style="text-align: center;"></div>

 <div class="clear"></div>
	
<!-- end table of search -->
<!---<ul>-->
<!---<li style="list-style-type: none; "><a href="<?php //echo $account_link  ?>" > <?php //echo __('Create gift registry',GIFTREGISTRY_TEXT_DOMAIN) ?></a> </li>---->

<?php  /*if (!class_exists('Magenest_Giftregistry_Model'))  include_once GIFTREGISTRY_PATH.'model/magenest-giftregistry-model.php';

$gr_id = Magenest_Giftregistry_Model::get_wishlist_id();
if ($gr_id)  {
{ ?>
<li style="list-style-type: none; "><a href="<?php echo $buy_link .$gr_id ?>" > <?php echo __('View my gift registry',GIFTREGISTRY_TEXT_DOMAIN) ?></a></li>
<?php  }  } ?>
<!-- end my gift registry -->
<?php if (isset($_SESSION['buy_for_giftregistry_id'])) :?>
<li style="list-style-type: none; "><a href="<?php echo $buy_link ?><?php echo $_SESSION['buy_for_giftregistry_id']?>" > <?php echo __('View my gift registry',GIFTREGISTRY_TEXT_DOMAIN) ?></a></li>

<?php endif;?>
</ul>--->
*/
?>
<script>

jQuery(document).ready( function(jQuery) {
jQuery('.search-field').keypress(function(event) {
 
// prevent browser autocomplete
jQuery(this).attr('autocomplete','off');
 
// get search term
var searchTerm = jQuery(this).val();
console.log("length is");
console.log(searchTerm);
if(searchTerm.length > 1){
  
jQuery.ajax({
url : the_ajax_script.ajaxurl,
type:"POST",
data:{
 
'action':'dhemy_ajax_search',
'term' :searchTerm
},
success:function(result){
 
// We'll redit the code here later
jQuery('div#search-results').fadeIn().html(result);
//console.log(result);
}
});
}
});
});


</script>


