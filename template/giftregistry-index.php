<?php    
wp_enqueue_style('thickbox');
wp_enqueue_script('thickbox');

$acount_page          = get_page_by_path('my-account');
$account_link         = get_permalink( get_option('woocommerce_myaccount_page_id'));
$my_account_page_url  = get_permalink( get_option('woocommerce_myaccount_page_id'));
$w_page               = get_permalink( get_option('follow_up_emailgiftregistry_page_id'));;
$http_schema          = 'http://';
  if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'])  {
  	$http_schema = 'https://';
  }
  $request_link  = $http_schema. $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] ;
  if(strpos($request_link, '?') > 0)  {
  	$buy_link = $w_page. '&giftregistry_id=';
  }else {
  	$buy_link =$w_page . '?giftregistry_id=';
  }
  $giftregistry_id = get_option('follow_up_emailgiftregistry_page_id');
  $giftregistry_page_path = get_permalink($giftregistry_id);
  $flow_img = GIFTREGISTRY_URL.'/assets/flow.jpg';
?>
  <img src="<?php //echo $flow_img?>" />
<?php 
    $collection = array();
    global $giftregistryresult;
      if(isset($_SESSION['registryresult'])){
        $collection = $_SESSION['registryresult'];
      }else{
        $collection = $giftregistryresult ;
      }
  if(!empty($collection)) { 
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
      <?php } ?>
  </table>
<?php } ?>
    <form id="searchgiftregistry" method="POST" action="<?php echo $giftregistry_page_path ?>">
    <input name="searchgiftregistry" value="1" type="hidden" />
      <table class="" style="margin-top: 10px; margin-bottom: 10px;">
        <tr>
          <td>
            <input style="width: 50%;" type="text" name="grname" placeholder="Type name of person to search" class="search-field"<?php if( isset($_SESSION['registrynamesearch'])) {?> value="<?php echo $_SESSION['registrynamesearch'] ?>" <?php }?>>
          </td>
        </tr>
      </table>
    </form>
  <div class="row" id="search-results" style="text-align: center;"></div>
<script>
  jQuery(document).ready( function(jQuery) {
    jQuery('.search-field').keypress(function(event) {
      jQuery(this).attr('autocomplete','off');
      var searchTerm = jQuery(this).val();
        if(searchTerm.length > 1){
          jQuery.ajax({
            url : the_ajax_script.ajaxurl,
            type:"POST",
            data:{
              'action':'dhemy_ajax_search',
              'term' :searchTerm
            },
            success:function(result){
              jQuery('div#search-results').fadeIn().html(result);
            }
          });
        }
    });
  });
</script>