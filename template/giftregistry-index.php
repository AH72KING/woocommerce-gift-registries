<?php    
wp_enqueue_style('thickbox');
wp_enqueue_script('thickbox');    

$acount_page = get_page_by_path('my-account');

$account_link = get_permalink( get_option('woocommerce_myaccount_page_id'));
$my_account_page_url = get_permalink( get_option('woocommerce_myaccount_page_id'));
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
<!-- Table of result -->
<?php 
$collection = array();
 		global $giftregistryresult;

if (isset($_SESSION['registryresult'])) $collection = $_SESSION['registryresult'];
$collection =$giftregistryresult ;

if (!empty($collection)) { 
?>
<table>
  <tr>
    <th><?php echo __('Name' ,GIFTREGISTRY_TEXT_DOMAIN) ?></th>
    <th><?php echo __('Co-Registrant name' ,GIFTREGISTRY_TEXT_DOMAIN) ?></th>
    <th><?php echo __('Email' ,GIFTREGISTRY_TEXT_DOMAIN) ?></th>
    <th><?php echo __('Co-Registrant email' ,GIFTREGISTRY_TEXT_DOMAIN) ?></th>
    <th><?php echo __('View' ,GIFTREGISTRY_TEXT_DOMAIN) ?></th>
  </tr>
  <?php foreach ($collection as $item) {
  	$link =$buy_link .$item['id'];
  ?>
  <tr>
      <td><?php echo $item['registrant_firstname'] . ' ' . $item['registrant_lastname'] ?></td>
  
  
      <td><?php echo $item['coregistrant_firstname'] . ' ' . $item['coregistrant_lastname'] ?></td>
  
  
      <td><?php echo $item['registrant_email']  ?></td>
  
 
      <td><?php echo $item['coregistrant_email']  ?></td>
  
  
  <td> <a href="<?php echo $link ?>"> <?php echo __("View",GIFTREGISTRY_TEXT_DOMAIN) ?></a></td>
  </tr>
  <?php  } ?>
</table>
<?php  } ?>
<!-- End of result -->
<!-- table of search -->
<form id="searchgiftregistry" method="POST" action="<?php echo $giftregistry_page_path ?>">
<input name="searchgiftregistry" value="1" type="hidden" />
<table class="" style="margin-top: 10px; margin-bottom: 10px;">
  <tr>
   
    <td><input style="width: 50%;" type="text" name="grname" placeholder="Type name of person to search" class="search-field"<?php if( isset($_SESSION['registrynamesearch'])) {?> value="<?php echo $_SESSION['registrynamesearch'] ?>" <?php }?>></td>
  </tr>
 
  <!--<tr>
    <td><label><?php //echo __('Email',GIFTREGISTRY_TEXT_DOMAIN) ?></label></td>
    <td><input type="text" name="email" <?php //if( isset($_SESSION['registryemailsearch'])) {?> value="<?php //echo $_SESSION['registryemailsearch'] ?>" <?php //}?> ></td>
  </tr>-->
 <!--- <tr>
    <td><input type="submit" name="submit" value="<?php //echo __('Search' , GIFTREGISTRY_TEXT_DOMAIN) ?>"></td>
  </tr>-->
</table>
</form>
<div class="row" id="search-results" style="text-align: center;"></div>
<!-- end table of search -->
<!---<ul>-->
<!---<li style="list-style-type: none; "><a href="<?php //echo $account_link  ?>" > <?php //echo __('Create gift registry',GIFTREGISTRY_TEXT_DOMAIN) ?></a> </li>-->

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


