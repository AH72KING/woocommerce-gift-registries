<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
  global $woocommerce;

  $cart_url = $woocommerce->cart->get_cart_url();
  if(!$id) return;

  $registry_id 		= $id;
  $wishlist 			= Magenest_Giftregistry_Model::get_wishlist($id);
  $registrantname 	= $wishlist->registrant_firstname . ' '. $wishlist->registrant_lastname;
  $coregistrantname 	= $wishlist->coregistrant_firstname . ' '. $wishlist->coregistrant_lastname;
  $registry_name = $registrantname;

  if($coregistrantname !=' '){
  	$registry_name .= __(' and' , GIFTREGISTRY_TEXT_DOMAIN) . " ". $coregistrantname;
  }

  $items = Magenest_Giftregistry_Model::get_items_in_giftregistry($id);
?>
  <div id="thongbao" class="woocommerce-message hidden">
    <a class="button wc-forward" href="<?php echo wc_get_page_permalink( 'cart' )?>">
    View Cart
    </a> 
    Items has been added to your cart.
  </div>
<?php 
    $banner_image = $wishlist->banner_image;
  		if(!empty($banner_image)){
  			$img_url = get_option($banner_image);
  			if(empty($img_url)){
          $img_url = get_site_url().'/wp-content/uploads/2017/05/'.$banner_image;
  			}
  		}else{
  	  	$img_url =  get_site_url().'/wp-content/uploads/2017/05/wrapistry-registry-background-flowers.png';
  		}
		$profile_photo = $wishlist->image;
  		if(empty($profile_photo)){
  			$profile_photo=  get_template_directory_uri().'/images/favicon.png';
  		}
?>
  <div class="row registry-header-wrapper  testing registry-header-wrapper-1" style= "background-image: url(<?= $img_url; ?>);">
      <div class=" col-md-12 registry-header  group"> <small><span>Our registry</span></small> <br>
        <h1><?= $registry_name; ?></h1><br>
        <p class="registry-header__date">
          <?= date('F j, Y' ,strtotime($wishlist->event_date_time)); ?>
        </p><br>
        <div class="avatar-wrapper col-md-12"> 
          <img src="<?= $profile_photo; ?>" class="avatar img-circle" style="width:140px;height:140px;" name="<?= $registry_name?>" > 
        </div>
      </div>
  </div>
  <div class="row registry-guest-wrapper"> 
    <div class=" col-md-12 guest-greeting">
       <p>
        <?= $wishlist->message ?> <br>
      </p>
    </div>
    <div class="gift-buying-callout">
        <h2 class="section-heading">
            <span>Are you buying a gift?</span>
        </h2>
        <div class="row  group ">
            <div class=" col-md-4 col-sm-12 step  column  column--1-3  ">
                <h4>Select gift below</h4> 
            </div>
            <div class=" col-md-4 col-sm-12 step  column  column--1-3 ">
                <h4>Add to cart and pay</h4>
                  <p>using any of our payment methods</p>
                  <span>
                    <i class="fa fa-angle-double-right fa-2x" aria-hidden="true"></i>
                  </span>
            </div>
            <div class="col-md-4 col-sm-12 step  column  column--1-3  column--last ">
                <h4>We’ll deliver directly</h4>
                <p>to the couple on your behalf</p>
                <span>
                  <i class="fa fa-angle-double-right fa-2x" aria-hidden="true"></i>
                </span>
            </div>
        </div>
        <p class="gift-buying-callout__incentive"><b></b></p>
    </div>
  </div>

<?php
		/**
		 * woocommerce_before_main_content hook
		 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
		 * @hooked woocommerce_breadcrumb - 20 
		 */
		do_action( 'woocommerce_before_main_content' );

    do_action( 'woocommerce_archive_description' ); 
?>
		<div class="shop-loop-head">
<?php 
        woocommerce_breadcrumb();
				/**
				 * woocommerce_before_shop_loop hook
				 * @hooked woocommerce_result_count - 20
				 * @hooked woocommerce_catalog_ordering - 30
				 */
				do_action( 'woocommerce_before_shop_loop' );
?>
		</div>
	  <div class="filters-area" style="display:none">
			<div class="filters-inner-area row">
					<div id="BASEL_Widget_Sorting" class="filter-widget widget-count-3 col-xs-12 col-sm-6 col-md-6">
              <h5 class="widget-title">Sort by</h5>
            <form class="woocommerce-ordering with-list" method="get">
  			       <ul>
                  <li>
        					   <a data-order="category" class="">Category</a>
        				  </li>
        				  <li>
        					   <a data-order="price" class="">Price: low to high</a>
        				  </li>
  								<li>
          					<a data-order="price-desc" class="">Price: high to low</a>
          				</li>
  					   </ul>
  	       </form>
          </div>
          <div id="BASEL_Widget_Price_Filter" class="filter-widget widget-count-3 col-xs-12 col-sm-6 col-md-6">
              <h5 class="widget-title">Price filter</h5>					
            <div class="basel-price-filter">
						  <ul>
                <li>
									 <a class="">All</a>
								</li>
								<li>
  									<a data-range="0-3370" class="">
                    <span class="woocommerce-Price-amount amount">
                      <span class="woocommerce-Price-currencySymbol">R</span>0
                    </span> - 
                    <span class="woocommerce-Price-amount amount">
                      <span class="woocommerce-Price-currencySymbol">R</span>3,370
                    </span>
                    </a>
								</li>
								<li>
  									<a data-range="3370-6740" class="">
                      <span class="woocommerce-Price-amount amount">
                        <span class="woocommerce-Price-currencySymbol">R</span>3,370
                      </span> - 
                      <span class="woocommerce-Price-amount amount">
                        <span class="woocommerce-Price-currencySymbol">R</span>6,740
                      </span>
                    </a>
								</li>
								<li>
  									<a data-range="6740-10110" class="">
                    <span class="woocommerce-Price-amount amount">
                        <span class="woocommerce-Price-currencySymbol">R</span>6,740
                    </span> - 
                    <span class="woocommerce-Price-amount amount">
                        <span class="woocommerce-Price-currencySymbol">R</span>10,110
                    </span>
                    </a>
								</li>
								<li>
  									<a data-range="10110-13480" class="">
                    <span class="woocommerce-Price-amount amount">
                      <span class="woocommerce-Price-currencySymbol">R</span>10,110
                    </span> - 
                    <span class="woocommerce-Price-amount amount">
                      <span class="woocommerce-Price-currencySymbol">R</span>13,480
                    </span>
                    </a>
								</li>
								<li>
  									<a data-range="13480" class="">
                      <span class="woocommerce-Price-amount amount">
                        <span class="woocommerce-Price-currencySymbol">R</span>13,480
                      </span> +
                    </a>
								</li>
							</ul>
					</div>
				</div>
			</div><!-- .filters-inner-area -->
		</div><!-- .filters-area -->

<div class="row">
  <div class="col-md-12">
    <?php 
        $tax_terms = get_terms( 'product_cat'); 
				if(!empty($tax_terms)){
    ?>
					<select name="categoryfilter" id="category_filter" style="width: 20%;float: right;display:none" >
          <option value="0" >Show All...</option>
    <?php
  				foreach($tax_terms as $term) {  
    				$term_id = $term->term_id; //Define the term ID
    				$term_link = get_term_link( $term, $taxonomy ); //Get the link to the archive page for that term
    				$term_name = $term->name;
    				$thumb_id = get_woocommerce_term_meta($term->term_id, 'thumbnail_id', true);
    				$term_img = wp_get_attachment_url($thumb_id);
    				//echo '<a class="ccats" href="' . $term_link . '"><span class="label">' . $term_name . '</span></a>';
    				echo '<option value="' . $term->term_id . '">' . $term->name . '</option>';
    			 
  			  } //end foreach
    ?>
				</select>
    <?php } ?>
  </div>
</div>
  <style>
      table.shop_table .wp-post-image{
          max-width: 60% !important;
      }
  </style>
<div class= "row items-container">
  <div class="col-md-12">
    <table class="shop_table cart" cellspacing="0">
      <tbody id ="ajax_results">
 <?php
          $registryCats = array();
          if(!empty($items)){
              foreach($items as $item){
                $product_cats = wp_get_post_terms( $item['product_id'], 'product_cat' );
                if(is_array($product_cats) && !empty($product_cats)){
                  foreach($product_cats as $key => $category){
                    $taxonomy_term_meta = get_option( "taxonomy_".$category->term_id);
                    if(is_array($taxonomy_term_meta) && !empty($taxonomy_term_meta)){
                      if(isset($taxonomy_term_meta['registry_cat']) && $taxonomy_term_meta['registry_cat'] != 0){
                        array_push($registryCats, $category->name);
                      }
                    }
                  }
                }
              }
          }  
        $itemsByCat = array();
        if(!empty($items)){
          foreach($items as $key => $item){
                $disableProductFromBuying = false;
              if(isset($item['quantity']) && ($item['quantity'] < 1)){
                    $disableProductFromBuying = true;
              }
              $product_cats = wp_get_post_terms( $item['product_id'], 'product_cat' );
              if(is_array($product_cats) && !empty($product_cats)){
                foreach($product_cats as $key => $category){
                    if(in_array($category->name, $registryCats)){
                        if(isset($item['variation_id']) && !empty($item['variation_id'])){
                          if(isset($itemsByCat['others'][$item['variation_id']])){
                             unset($itemsByCat['others'][$item['variation_id']]);
                          }
                          $itemsByCat[$category->name][$item['variation_id']] = $item;
                        }else{
                          if(isset($itemsByCat['others'][$item['product_id']])){
                              unset($itemsByCat['others'][$item['product_id']]);
                          }
                          $itemsByCat[$category->name][$item['product_id']] = $item;
                        }
                        break;
                    }else{
                        if(isset($item['variation_id']) && !empty($item['variation_id'])){
                            $itemsByCat['others'][$item['variation_id']] = $item;
                        }else{
                            $itemsByCat['others'][$item['product_id']] = $item;
                        }
                    }
                }
              }else{
                if(isset($item['variation_id']) && !empty($item['variation_id'])){
                  $itemsByCat['others'][$item['variation_id']] = $item;
                }else{
                  $itemsByCat['others'][$item['product_id']] = $item;
                }
              }

          }
        }
        if(!empty($itemsByCat)){
          asort($itemsByCat);
          if(isset($itemsByCat['others'])){
            $other = $itemsByCat['others'];
            unset($itemsByCat['others']);
            $itemsByCat['others'] = $other;
          }
          $countItems = 0;
          foreach ($itemsByCat as $cat => $catArray){
            $countCatItems = 0;
            if(!empty($catArray)){              
              foreach($catArray as $key => $item){
                
                $countCatItems++;
                $disableProductFromBuying = false;
      				  if(isset($item['quantity']) && ($item['quantity'] < 1)){
                    $disableProductFromBuying = true;
                }    
                        
                      $productToGet = $item['product_id'];
            				  if(isset($item['variation_id']) && !empty($item['variation_id'])){
                        $productToGet = $item['variation_id'];
                      }     
            					$_product = wc_get_product($productToGet);
            					$request = unserialize($item['info_request']);
            					$request_st = Magenest_Giftregistry_Model::show_info_request($item, $id);
            					$request_st = str_replace(' ', '', $request_st);
                      $statusClassOfProduct = ''; 
                      if($disableProductFromBuying == true){
                        $statusClassOfProduct = 'disable disable-product-buy'; 
                      } 
                if(!empty($_product)){
                  $countItems++;
                  if($countCatItems == 1){ ?>    
                         <tr class="">
                            <td class="parent-cat main-cat" colspan="8">
                               <h3 class="main-cat-heading">
                                <?= $cat; ?>
                              </h3>
                            </td>
                        </tr>

  <?php                  
                      }
                  ?>
                      <tr class="<?= $statusClassOfProduct;?>">
                        <td class="num-item"><?=$countItems;?></td>
                            <td class="product-thumbnail registry_imgs">
          <?php
                            if($_product->get_image()){
                              $thumbnail = $_product->get_image();
                    					printf('<a href="%s">%s</a>', $_product->get_permalink(), $thumbnail);
                            }
          ?>
                            </td>
                            <td class="product-name">
          <?php 
                  					echo sprintf( '<a href="%s">%s</a>', $_product->get_permalink(), $_product->get_title());
                            global $post;                               
                            $arrayCheck = array();
                            $terms = get_the_terms( $item['product_id'], 'product_cat' );
                            foreach ($terms as $term) {
                                array_push($arrayCheck,$term->term_id);
                            }
                            if(in_array(134,$arrayCheck)){ ?>
                              <br>
                              <p class="shp_txt">Cash Funds</p>
          <?php   
                            }else{ ?>
                          	 <br><p class="shp_txt">Free Shipping</p> 
          <?php   
                            } 
          ?>
                  		      </td>
          <?php 
                            $isgcp = false;
                            if((new ign_gc_pricer)->is_gcp($item['product_id'])){
                                $isgcp = true;
                            }
          ?>
                         <td class="product-price" data-gcp="<?=$isgcp;?>">
          <?php 
                             if(isset($item['amount']) && !empty($item['amount'])){ 
          ?>
                                <span class="woocommerce-Price-amount amount">
                                  <span class="woocommerce-Price-currencySymbol">R</span>
                                  <span class="woocommerce-Price-current-amount"><?=$item['amount'];?></span>
                                </span>
          <?php 
                             }elseif(!empty($_product->get_price_html())){
                                echo $_product->get_price_html();
                             }else{
                                global $product;
                                  // go through a few options to find the $price we should display in the input (typically will be the suggested price)
                                  if( isset( $_POST['nyp'] ) &&  floatval( $_POST['nyp'] ) >= 0 ) {
                                    $num_decimals = ( int ) get_option( 'woocommerce_price_num_decimals' );
                                    $price = round( floatval( $_POST['nyp'] ), $num_decimals );
                                  }elseif ( $product->suggested && floatval( $product->suggested ) > 0 ) {
                                    $price = $product->suggested;
                                  }elseif ( $product->minimum && floatval( $product->minimum ) > 0 ) {
                                    $price =  $product->minimum;
                                  }else {
                                    $price = '';
                                  }
          ?>
                                <div class="gcp">
         <?php 
                                  echo ign_gc_pricer::price_input_helper( esc_attr( $price ), array( 'name' => 'nyp' ) ); ?>
                                </div> 
          <?php 
                              } 
          ?>
                		      </td>
    <?php
                    $colspan='';
                    if($disableProductFromBuying == true){
                      $colspan = 'colspan="3"';
                    }
    ?>
                        <td class="product-quantity" <?=$colspan;?>>
    <?php 
                              if(isset( $item['quantity'])) {
                    						$receive_qty=0;
                    						if(isset($item['received_qty'])){
                                  $receive_qty = $item['received_qty'];
                                }
                    						$remain_qty = $item['quantity'] - $receive_qty;
                    						if($remain_qty < 0){
                                  $remain_qty = 0;
                                }
                                if($disableProductFromBuying == true){ 
    ?>        
                                      <span>Sold</span>
                                      <span class="vc_icon_element-icon fa fa-gift"></span>
    <?php
                                }else{
                                  echo $item['quantity'];
                                }
                    					}
    ?>          
                        </td>
    <?php
                    if($disableProductFromBuying == false){
    ?>
                        <td>
                            <input style="width: 40px" type="text" id="<?php echo $item['id']?>" />
                        </td>
                        <td>
                          <button style="background-color: #1aada3; color: white;"
                                data-product-id="<?= $item['product_id']; ?>"
                                data-variation-id="<?= $item['variation_id'];?>" 
                                data-registry-id="<?= $registry_id;?>" 
                                data-buy="<?= $request_st; ?>"  
                                name="<?= $item['id']?>"  
                                class="single_add_to_cart_button button alt" 
                                onclick="giftit(this)">
                                <?php echo __('BUY GIFT') ?>
                          </button>
                        </td>
    <?php 
                    } 
    ?>
                    </tr>
    <?php  
              }// checkproduct
            }
            }
            }//foreach
          }	//if
  
                echo '<div class="hidden"><pre>'; print_r($uniqueItems);  echo '</pre></div>';?>
      </tbody>
    </table>
  </div>
</div>
<div class="spinner"></div>
<style>
  td.main-cat {
    background: #555; border: none; 
    font-size: 1em; 
    font-weight: bold; 
    text-align: left;
    margin-top: 2em; 
    padding: 14px 19px 0px; 
    text-transform: uppercase;
  }
  td h3.main-cat-heading{
    color: #fff;
    margin: 5px 20px 10px!important
  }
  td.product-thumbnail.registry_imgs{
    max-width: 280px;
    text-align: left;
  }
  td.product-name{
    width:48%;
  }
  td.product-quantity{
    width: 30px;
  }
  td.num-item{
    width: 10px;
    background: #555;
    color: #fff;
  }
  .spinner {
    background: url('/wp-admin/images/wpspin_light.gif') no-repeat;
    background-size: 16px 16px;
    display: none;
    float: right;
    opacity: .7;
    filter: alpha(opacity=70);
    width: 16px;
    height: 16px;
    margin: 5px 5px 0;
  }
  tr.disable.disable-product-buy {
      background: #eee;
      opacity: 0.7;
  }
  tr.disable.disable-product-buy td.product-quantity{
    color: #000;
  }
  tr.disable.disable-product-buy td.product-quantity span{
    color: #000;
    font-size: 20px;
    margin: 2px 5px;
    vertical-align: middle;
  }
  tr.disable.disable-product-buy td.product-quantity span.vc_icon_element-icon{
    font-size: 26px;
  }

/* added by Hamid Raza */
.gift-buying-callout .step h4 {
    margin: 0 0 5px;
    color: #fff;
    font-family: Georgia,"Times New Roman",Times,serif;
    font-weight: normal;
    font-size: 1.25em;
    font-style: italic;
    line-height: 1em;
    text-align: center;
}
.gift-buying-callout {
    width: 1000px;
    margin: 0 auto 40px; 
}
.gift-buying-callout .section-heading {
    margin-bottom: 0.6em;
    color: #7bb1c5;
    font-size: 1.125em;
}
.section-heading {
    position: relative;
/*    overflow: hidden;*/
    margin: 0;
    text-align: center;
	font-family: Georgia, "Times New Roman", Times, serif;
    font-size: 1.125em;
    font-weight: normal;
    font-style: italic;
    letter-spacing: 0.025em;
	line-height: normal;
}
 

.group:before, .group:after {
    content: " ";
    display: table;
    visibility: hidden;
    height: 0;
}
.group:after {
    clear: both;
}
 .gift-buying-callout .step {
    position: relative;
    padding: 19px 20px 0px 19px;
    background: #69c7ee;
    color: #fff;
    font-style: italic;
    text-align: center;
    min-height: 78px;
}
.column--1-3, .one-third, .single-post-content .column--1-2 {
    width: 320px;
}
.column {
    position: relative;
    display: inline;
    float: left;
    margin-right: 20px;
    box-sizing: border-box;
}
.column--last {
    margin-right: 0;
}
.gift-buying-callout__incentive {
    margin: 20px 0 0;
    font-size: 1em;
    text-align: center;
}
 
.gift-buying-callout .step span {
    position: absolute;
    top: 25px;
    left: -25px;
    width: 30px;
    height: 30px;
    background: #fff;
    border-radius: 50%;
    color: #7bb1c5;
    font-size: 0.75em;
    line-height: 30px;
    text-align: center;
}
.gift-buying-callout span i {
        position: absolute;
    right: 5px;
    top: 2px;
}
@media (max-width:1000px){
    .gift-buying-callout{width:100%;}
}
@media only screen and (min-device-width: 601px) and (max-device-width: 969px) {
     .column{width:31%;height: 80px;}
}

@media (max-width:600px){
    .column{width:100%;margin-top:2px;}
}
</style>

<script type="text/javascript">
function giftit(obj) {

  var qty= jQuery(obj).parent().prev('td').find('input').val();
  var submit_link = jQuery(obj).attr('data-buy') + '&quantity=' + qty;
  var product_id = jQuery(obj).attr('data-product-id');
  var variation_id = jQuery(obj).attr('data-variation-id');
  var registry_id = jQuery(obj).attr('data-registry-id');
  var addTocartLink = '<?=get_template_directory_uri().'/gift_product_add_to_cart.php';?>';
  var itemPrice = '';
  var gcp = false;

  if(jQuery(obj).parents('tr').find('.gcp').length > 0){
      itemPrice = jQuery(obj).parents('tr').find(".gcp").find("input[name='gcp']").val();
      gcp = itemPrice;
  }else{
      itemPrice = jQuery(obj).parents('tr').find('.woocommerce-Price-amount.amount').text();
      //gcp = itemPrice;
  }
  itemPrice = itemPrice.replace(',' , '');
  itemPrice = itemPrice.replace('R' , '');
  var isgcp = jQuery(obj).parents('tr').find('.product-price').attr('data-gcp');
  if(isgcp == "1" || isgcp == 1){
    gcp = itemPrice;
  }
    jQuery.ajax({
        type: "POST",
        url: addTocartLink,
        data:{
          product_id:product_id, 
          qty:qty,
          variation_id:variation_id,
          registry_id:registry_id,
          itemPrice:itemPrice,
          gcp:gcp
        },
        beforeSend:function(){
            jQuery(obj).html('BUY GIFT <i class="fa fa-spinner fa-pulse fa-fw"></i>');
        },
        success: function(response) {
           jQuery(obj).html('BUY GIFT');
           var totalPrice = jQuery('.basel-cart-totals').find('.woocommerce-Price-amount').html();
           var startIndex= totalPrice.indexOf('</span>')+7;
           totalPrice = totalPrice.substr(startIndex);
           totalPrice = totalPrice.replace(',' , '');
           totalPrice = parseInt(totalPrice) + parseInt(itemPrice); 
           console.log(totalPrice);
           var cartCount = jQuery('.basel-cart-totals').find('.basel-cart-number').html();
           jQuery('.basel-cart-totals').find('.basel-cart-number').html(parseInt(cartCount)+1); // increment cart counter
           jQuery('.basel-cart-totals').find('.basel-cart-subtotal').find('.woocommerce-Price-amount').html('<span class="woocommerce-Price-currencySymbol">R</span>'+totalPrice); 
           jQuery('#popup2').show();
          
        }
    });
  
}

    jQuery(document).on('click','#BASEL_Widget_Sorting li a', function(e){
        var order = jQuery(this).attr('data-order');
        jQuery.ajax({
            url:"<?=get_template_directory_uri()?>/gift_registry_items.php",
            data:{order:order, wishlistId:<?=$id?>},
            type:"POST",
            beforeSend:function(){
                jQuery(".spinner").show();
            },
            success:function(data){
                jQuery(".spinner").hide();
                jQuery('.items-container').html(data);
            }
        });// end of ajax
    })
    
    
    jQuery(document).on('click','#BASEL_Widget_Price_Filter li a', function(e){
        var range = jQuery(this).attr('data-range');
        //var range = JSON.stringify(range);
        jQuery.ajax({
            url:"<?=get_template_directory_uri()?>/gift_registry_items.php",
            data:{range:range, wishlistId:<?=$id?>},
            type:"POST",
            beforeSend:function(){
                jQuery(".spinner").show();
            },
            success:function(data){
                jQuery(".spinner").hide();
                jQuery('.items-container').html(data);
            }
        });// end of ajax
    })
    
</script>
