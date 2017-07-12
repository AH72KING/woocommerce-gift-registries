<?php
//exit;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
wp_enqueue_style( 'jquery-ui', 'https://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css' );
wp_enqueue_script('jquery-ui-datepicker');
wp_enqueue_media();


global $post;
$wishlist = '';
$wid  = $_GET['giftregistry_id'];
if(empty($wid)){
	$wid  = $_GET['id'];
}
if ($wid) {
	$wishlist = Magenest_Giftregistry_Model::get_wishlist($wid);
	$registrantname = $wishlist->registrant_firstname . ' '. $wishlist->registrant_lastname;
    $coregistrantname = $wishlist->coregistrant_firstname . ' '. $wishlist->coregistrant_lastname;

    //$registry_name = $registrantname;
    $registry_name = $wishlist->title;

}
?>
<?php if(is_user_logged_in()){ ?>
<?php $banner_image = $wishlist->banner_image;
		
		if(!empty($banner_image))
		{
			$img_url = get_option($banner_image);
			if(empty($img_url)){
			    $img_url = get_site_url().'/wp-content/uploads/2017/05/'.$banner_image;
			}
		} else {
			
			//	$img_url =  get_template_directory_uri().'/images/default-couple-banner.jpg';
	  	$img_url =  get_site_url().'/wp-content/uploads/2017/05/wrapistry-registry-background-flowers.png';
		}
		$profile_photo = $wishlist->image;
		if(empty($profile_photo))
		{
			$profile_photo=  get_template_directory_uri().'/images/favicon.png';
			
		}
		?>
<style>
    .edit-photo i{background-color: #69c7ee;padding: 8px;border-radius: 100%;color: white;cursor:pointer;}
    .edit-photo{display:none;position: absolute;left: 83px;right: 0;top: 25px;}
    .avatar-wrapper .avatar:hover + .edit-photo, .edit-photo:hover {display:block;}
    .edit-photo i:hover{display:inline-block;}
    .redux-container .nav-pills>li {
    	float: none;
    }
    .popup {
	    width: 100%;
	    height: 100%;
	    display: none;
	    position: fixed;
	    top: 20px;
	}
</style>

<div class="tab-pane redux-group-tab" id="2a">
<h2>General</h2>
<div class="row registry-header-wrapper  registry-header-wrapper-1" style= "background-image: url(<?php echo $img_url; ?>);">
	    <a class="btn action-button" data-popup-open="popup-1" href="#" style="background: rgba(0,0,0,0.6);color:white">
	    	<i class="fa fa-pencil"></i> 
	    	Edit Background
    	</a>
        <div class="col-md-12 registry-header group">
            <small><span>Our registry</span></small>
            <br>

            <h1><span class="reg_title"><?php echo $registry_name?></span>
            <span class="edit_reg_title" title="Edit Title" style="position: absolute;right: 2px;font-size: 14px; background-color: #7bb1c5;padding: 3px 8px;top: 2px;cursor: pointer;">
                <i class="fa fa-pencil"></i>
            </span>
            
            <input type="text" value="<?=$registry_name?>" style="background-color: transparent;border: transparent;font-size: inherit;display:none"></h1><br>

            <p class="registry-header__date">
                <span class="event_date"><?php echo date('j F, Y' ,strtotime($wishlist->event_date_time  )); ?> </span>
                <span class="edit_date" style="cursor:pointer">
                    <i class="fa fa-pencil"></i>
                    <input type="text" style="height: 0px; width:0px; border: 0px; padding: 0;" id="event_date" />
                </span>
            </p><br>

            <div class="avatar-wrapper col-md-12">
                <img src="<?php echo $profile_photo; ?>" class="avatar" name="<?php echo $registry_name?>" style="width:140px;height:140px">
                <span class="edit-photo"><i class="fa fa-pencil"></i></span>
            </div>
        </div>
    </div> 
    <br><br>
    <?php
		/**
		 * woocommerce_before_main_content hook
		 *
		 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
		 * @hooked woocommerce_breadcrumb - 20 
		 */
		do_action( 'woocommerce_before_main_content' );
	?>

		<?php do_action( 'woocommerce_archive_description' ); ?>
			
		<div class="shop-loop-head">
			
			<?php woocommerce_breadcrumb(); ?>

			<?php
				/**
				 * woocommerce_before_shop_loop hook
				 *
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
								<a data-range="0-3370" class=""><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">R</span>0</span> - <span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">R</span>3,370</span></a>
							</li>
							<li>
								<a data-range="3370-6740" class=""><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">R</span>3,370</span> - <span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">R</span>6,740</span></a>
							</li>
							<li>
								<a data-range="6740-10110" class=""><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">R</span>6,740</span> - <span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">R</span>10,110</span></a>
							</li>
							<li>
								<a data-range="10110-13480" class=""><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">R</span>10,110</span> - <span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">R</span>13,480</span></a>
							</li>
							<li>
								<a data-range="13480" class=""><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">R</span>13,480</span> +</a>
							</li>
						</ul>
				</div>
		</div>
	</div><!-- .filters-inner-area -->
   </div>  
	<div style="width:1190px;margin: 3% auto;">   
	    <a href="<?=get_site_url().'/shop' ?>" class="col-md-12 text-center add_gift">
	        <span><i class="fa fa-plus-circle fa-3x"></i></span>
	        <br>
	        <span>Add Gifts</span>
	    </a>
	<?php $tax_terms = get_terms( 'product_cat'); ?>
		<form id="giftregistry-item-form" method="POST"  >
		  <input type="hidden" name="giftregistry_id" id="giftregistry_id" value="<?php if (is_object($wishlist)) : echo $wishlist->id ; endif;?>"/>
		  <input type="hidden" name="update_giftregistry_item" value="1" />
		<table class="shop_table cart" cellspacing="0" <?php if (is_admin() ) :?> id="admin-gift-registry" <?php endif;?>>
			<thead>
				<tr>
					<th class="product-remove">&nbsp;</th>
					<th class="product-thumbnail">&nbsp;</th>
					<th class="product-name"><?php _e( 'Product', 'woocommerce' ); ?></th>
					<th class="product-price"><?php _e( 'Price', 'woocommerce' ); ?></th>
					<th class="product-quantity"><?php _e( 'Desired Quantity', 'woocommerce' ); ?></th>
					<th class="product-buy"><?php _e( 'Received Quantity', 'woocommerce' ); ?></th>
				</tr>
			</thead>
			<tbody id ="ajax_results">
			    
			<?php 
			$items = Magenest_Giftregistry_Model::get_items_in_giftregistry($wid);
			if (! empty ( $items )) {
				foreach ( $items as $item ) {
				     if( isset($item['variation_id']) && !empty($item['variation_id']) ){
						$item['product_id'] = $item['variation_id'];
					}
					$_product = wc_get_product($item['product_id']);
					$http_schema = 'http://';
					if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'])  {
						$http_schema = 'https://';
					}
					$request_link  = $http_schema. $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] ; 
					if (strpos($request_link, '?') > 0)  {
					   $delete_link = $http_schema. $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] . '&update_giftregistry_item=1&remove_item=1&item_id='. $item['id'];
					}else{
					   $delete_link = $http_schema. $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] . '?update_giftregistry_item=1&remove_item=1&item_id='. $item['id'];
						
					}
					if(!empty($_product)){
					?>
			<tr>
					<td><a href="<?php echo $delete_link ?>" class="remove" title="s">&times;</a></td>
					<td class="product-thumbnail">
					<?php
				    $thumbnail = $_product->get_image();
				    printf ( '<a href="%s">%s</a>', $_product->get_permalink (), $thumbnail );
					?>
					</td>
					<td class="product-name">
					<?php
					echo sprintf ( '<a href="%s">%s</a>', $_product->get_permalink (), $_product->get_title () )?>
					</td>
					<td class="product-price">
					<?php
								if(isset($item['amount']) && !empty($item['amount'])){
								    echo '<span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">R</span>'.$item['amount'].'</span>';
								} else 
					echo $_product->get_price_html();
					?>
					</td>
					
					<td class="product-quantity">
								<input type="text" name="wishlist_item[<?php echo $item['id']?>]"  id="wishlist_item[<?php echo $item['id']?>]" value="<?php if (isset( $item['quantity']))  echo $item['quantity']?>" />
					
								
			        </td>
			        
					<td class="received-quantity">
								<?php if (isset( $item['received_qty'])) {  echo $item['received_qty']; } else {echo 0;}?>
			        </td>
			    </tr>
			<?php   } } } ?>
			</tbody>
		</table>
		<input id="wishlist_id_for_items" name="wishlist_id" type="hidden" />
		<input  type="submit" value="<?php echo __('Save' , GIFTREGISTRY_TEXT_DOMAIN)?>" title="<?php echo __('Save' , GIFTREGISTRY_TEXT_DOMAIN)?>" />
		</form>
	</div>
<?php }else{
	echo "<p class='woocommerce-info'>You must have to login to view yo registry. Please <a href=".get_home_url().'/my-account/'.">Login</a> to continue</p>";
	}
?>
</div>
</div><!--section 1-->
<div class="tab-pane redux-group-tab" id="3a">
<!-- Customization Start -->
	<?php
    global $wpdb;
    $prefix = $wpdb->prefix;
    $tbl = $prefix.'gift_bg_img';
    $user_id = get_current_user_id();
    $record = $wpdb->get_row("SELECT * FROM {$tbl} WHERE user_id={$user_id}");
    $images = json_decode($record->images);
   
	?>
	<div class="popup registry-banner-popup" data-popup="popup-1">
    <div class="popup-inner">
    <label style="text-align: left;" >
		Please select a background header image for your unique registry!
	</label>
	<?php 
		$bannerColClass = "col-md-4";
		if(is_admin()){
			$bannerColClass = "col-md-12";
		}
	?>
	<div class = "row">
		<div class="col-md-12 bg_imgs">
				<div class="<?= $bannerColClass;?> bg_imgs img_cont">
					<?php $option_1 = get_option('upload_image'); 
					if(!empty($option_1)):?>
							<label>
							<input type="radio" class="styled_radio" name="upload_image" value="upload_image" <?php if (is_object($wishlist)) : echo ($wishlist->banner_image== 'upload_image') ?  "checked" : "" ;  ; endif;?> /> 
							<img class="img-responsive" src="<?php echo $option_1; ?>"><br>
							</label>
					 <?php endif; ?>
				</div>
				<div class="<?= $bannerColClass;?> bg_imgs img_cont">	 
					 <?php $option_2 = get_option('upload_image2'); 
								if(!empty($option_2)):?>
									<label>
										 <input type="radio" class="styled_radio" name="upload_image" value="upload_image2" <?php if (is_object($wishlist)) : echo ($wishlist->banner_image== 'upload_image2') ?  "checked" : "" ;  ; endif;?> /> 
										 <img class="img-responsive" src="<?php echo $option_2; ?>"><br>
									 <label>
					 <?php endif; ?>
				</div>
				<div class="<?= $bannerColClass;?> bg_imgs img_cont">	
					 <?php $option_3 = get_option('upload_image3'); 
								if(!empty($option_3)):?>
								
									<label>
										 <input type="radio" class="styled_radio" name="upload_image" value="upload_image3" <?php if (is_object($wishlist)) : echo ($wishlist->banner_image== 'upload_image3') ?  "checked" : "" ;  ; endif;?> /> 
										 <img class="img-responsive" src="<?php echo $option_3; ?>"><br>
									 </label>
					 <?php endif; ?>
				</div>
				<div class="<?= $bannerColClass;?> bg_imgs img_cont">	
					 <?php $option_4 = get_option('upload_image4'); 
								if(!empty($option_4)):?>
								
									<label>
										 <input type="radio" class="styled_radio" name="upload_image" value="upload_image4" <?php if (is_object($wishlist)) : echo ($wishlist->banner_image== 'upload_image4') ?  "checked" : "" ;  ; endif;?>/> 
										 <img class="img-responsive" src="<?php echo $option_4; ?>"><br>
									</label>
					 <?php endif; ?>
				</div>
				<div class="<?= $bannerColClass;?> bg_imgs img_cont">		 
					   <?php $option_5 = get_option('upload_image5'); 
								if(!empty($option_5)):?>
								<label>
									 <input type="radio" class="styled_radio" name="upload_image" value="upload_image5" <?php if (is_object($wishlist)) : echo ($wishlist->banner_image== 'upload_image5') ?  "checked" : "" ;  ; endif;?>/> 
									 <img class="img-responsive" src="<?php echo $option_5; ?>"><br>
								</label>
					 <?php endif; ?>
				</div>
					  <?php 
					   for($i=6; $i<=25; $i++){
					       $option = get_option('upload_image'.$i); 
								if(!empty($option)):?>
								<div class="<?= $bannerColClass;?> bg_imgs img_cont">	
								<label>
									 <input type="radio" class="styled_radio" name="upload_image" value="upload_image<?=$i?>" <?php if (is_object($wishlist)) : echo ($wishlist->banner_image== 'upload_image'.$i) ?  "checked" : "" ;  ; endif;?>/> 
									 <img class="img-responsive" src="<?php echo $option; ?>"><br>
								</label>
								</div>
					        <?php endif; 
					   }
				?>
				<?php	  
					 /* customization start 
					 loop through gift registry background images ,fetched from db
					 */
					 if(isset($images) && is_array($images) && !empty($images)){
					     foreach($images as $image){ ?>
					         <div class="<?= $bannerColClass;?> bg_imgs img_cont" data-id="<?=$image?>">
					             <span title="Remove Image" class="remove-bg-img" data-toggle="tooltip" data-placement="top"><i class="fa fa-times-circle-o" aria-hidden="true"></i></span>
								<label>
									 <input type="radio" class="styled_radio" name="upload_image" value="<?=$image?>" <?php if (is_object($wishlist)) : echo ($wishlist->banner_image== $image) ?  "checked" : "" ;  ; endif;?>/> 
									 <img class="img-responsive" src="<?php echo get_site_url().'/wp-content/uploads/2017/05/'.$image; ?>"><br>
								</label>
							 </div>
					     <?php } //end of foreach
					 }// end of if
					 /* customization end */
					 
					 ?>
		</div>
				     
				 <br>
	</div>
        <a class="popup-close" data-popup-close="popup-1" href="#">x</a>
    </div>
   </div>

<script>
    jQuery(document).on('click','#BASEL_Widget_Sorting li a', function(e){
        var order = jQuery(this).attr('data-order');
        jQuery.ajax({
            url:"<?=get_template_directory_uri()?>/manage_registry_items.php",
            data:{order:order, wishlistId:'<?=$wid?>'},
            type:"POST",
            beforeSend:function(){
                jQuery(".spinner").show();
            },
            success:function(data){
                jQuery(".spinner").hide();
                jQuery('#ajax_results').html(data);
            }
        });// end of ajax
    })
    
    
    jQuery(document).on('click','#BASEL_Widget_Price_Filter li a', function(e){
        var range = jQuery(this).attr('data-range');
        //var range = JSON.stringify(range);
        jQuery.ajax({
            url:"<?=get_template_directory_uri()?>/manage_registry_items.php",
            data:{range:range, wishlistId:'<?=$wid?>'},
            type:"POST",
            beforeSend:function(){
                jQuery(".spinner").show();
            },
            success:function(data){
                jQuery(".spinner").hide();
                jQuery('#ajax_results').html(data);
            }
        });// end of ajax
    })
    
    // on select registry banner image,  edit registry banner image
    
    jQuery(document).on('click','.img_cont img', function(e){

        var input = jQuery(this).parents('.img_cont').find('input');
        var image = input.val();
        var img_src = jQuery(this).attr('src');;
        jQuery('#3a').removeClass('active');
        jQuery('#2a').addClass('active');
        jQuery.ajax({
            url:"<?=get_template_directory_uri()?>/upload_gift_reg_img.php",
            data:{image:image, wishlistId:"<?= $wid; ?>", action:"edit_image"},
            type:"POST",
            success:function(data){
                var data = data.split('::');
                if(data[1]=='success'){
                    jQuery('.registry-header-wrapper').css('background-image','url('+img_src+')');
                }
            },
            error:function(){}
        })
    })
    
    // edit registry name
    jQuery(document).on('click','.edit_reg_title', function(e){
        jQuery(this).parents('h1').find('.reg_title').hide();
        jQuery(this).removeClass('edit_reg_title').addClass('save_reg_title').html('<i class="fa fa-floppy-o"></i>').attr('title','Save Title');
        jQuery(this).parents('h1').find('input').show();
    })
    
    // save registry name
    jQuery(document).on('click','.save_reg_title', function(e){
        var ele = jQuery(this);
        ele.removeClass('save_reg_title').addClass('edit_reg_title');
        ele.parents('h1').find('.reg_title').show();
        ele.parents('h1').find('input').hide();
        var reg_title = ele.parents('h1').find('input').val();
        jQuery.ajax({
            url:"<?=get_template_directory_uri()?>/upload_gift_reg_img.php",
            data:{reg_title:reg_title, action:"edit_reg_title", wishlistId:"<?=$wid?>"},
            type:"POST",
            beforeSend:function(){
                ele.html('<i class="fa fa-spinner fa-spin"></i>');
                ele.attr('title','Saving');
            },
            success:function(data){
                ele.html('<i class="fa fa-pencil"></i>');
                ele.attr('title','Edit Title');
                var data = data.split('::');
                if(data[1]=='success'){
                    ele.parents('h1').find('.reg_title').html(reg_title);
                }
            },
            error:function(){}
        })
    })
    
    // edit registry event date
    jQuery(document).ready(function() {
           jQuery('#event_date').datepicker({
               dateFormat: 'yy-mm-dd',
               onSelect: function(dateText, inst) {
                var ele = jQuery(this);
                var event_date = ele.val();
                console.log(event_date)
                jQuery.ajax({
                    url:"<?=get_template_directory_uri()?>/upload_gift_reg_img.php",
                    data:{event_date:event_date, action:"reg_event_date", wishlistId:"<?=$wid?>"},
                    type:"POST",
                    beforeSend:function(){
                        ele.parents('.edit_date').find('i').removeClass('fa fa-pencil').addClass('fa fa-spinner fa-spin');
                    },
                    success:function(data){
                        ele.parents('.edit_date').find('i').removeClass('fa fa-spinner fa-spin').addClass('fa fa-pencil');
                        var data = data.split('::');
                        if(data[1]=='success'){
                            ele.parents('.registry-header__date').find('.event_date').html(data[2]);
                        }
                    },
                    error:function(){}
                })
               }
           });
	}) ;
	//
    jQuery(document).on('click','.edit_date',function(e){
        jQuery('#event_date').datepicker("show");
    })
    

    // edit registry couple photo 
	jQuery('.edit-photo i').click(function(e) {
         e.preventDefault();
        var image = wp.media({ 
            title: 'Upload Image',
			 library: { 
		      type: 'upload' // limits the frame to show only images
		   },
            // mutiple: true if you want to upload multiple files at once
            multiple: false
        }).open()
        .on('select', function(e){
            // This will return the selected image from the Media Uploader, the result is an object
            var uploaded_image = image.state().get('selection').first();
            // We convert uploaded_image to a JSON object to make accessing it easier
            // Output to the console uploaded_image
            var imageJson = uploaded_image.toJSON();
			jQuery("p.error").remove();
			jQuery('<p class="success" style="color:green;">Updated Successfully</p>').appendTo('#dimension_error');
			var image_url = imageJson.sizes.thumbnail.url;
			jQuery.ajax({
			    url:"<?=get_template_directory_uri()?>/upload_gift_reg_img.php",
			    type:"POST",
			    data:{img_url:image_url, wishlistId:"<?=$wid?>", action:'edit_couple_photo'},
			    beforeSend:function(){
			    	jQuery('.edit-photo').show();
			        jQuery('.edit-photo i').removeClass('fa-pencil').addClass('fa-spin fa-spinner');
			    },
			    success:function(data){
			    	//console.log(data);
			        var data = data.split("::");
			        if(data[1]=='success'){
			            jQuery(".avatar-wrapper").find(".avatar").attr("src", image_url);
			        } // end of if
			        jQuery('.edit-photo').css('display', '')
			        jQuery('.edit-photo i').removeClass('fa-spin fa-spinner').addClass('fa-pencil');
			    } // end of function success
			}) // end of ajax
        });
    });
</script>
<style>
	.registry-banner-popup.popup {
	    top: 20px!important;
	}
	.main-page-wrapper{
		z-index: 99999;
	}
</style>