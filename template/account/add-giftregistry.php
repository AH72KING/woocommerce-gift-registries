<?php 
$jquery_version = isset ( $wp_scripts->registered ['jquery-ui-core']->ver ) ? $wp_scripts->registered ['jquery-ui-core']->ver : '1.9.2';
wp_enqueue_style( 'jquery-ui', 'https://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css' );
wp_enqueue_style( 'jquery-ui-style');
wp_enqueue_style( 'jquery-ui-core');
wp_enqueue_style( 'jquery-ui-accordion');
wp_enqueue_script('jquery-ui-core');
wp_enqueue_script('jquery-ui-datepicker');
wp_enqueue_script('jquery-ui-accordion');
$wishlist = '';
if ($wid) {
	$wishlist = Magenest_Giftregistry_Model::get_wishlist($wid);
} 

/* customization start */
global $wpdb;
$prefix = $wpdb->prefix;
$tbl = $prefix.'gift_bg_img';
$user_id = get_current_user_id();
$record = $wpdb->get_row("SELECT * FROM {$tbl} WHERE user_id={$user_id}");
$images = json_decode($record->images);
/* customization end */
 if(is_admin()){ ?>
<div id="1a" class="redux-group-tab tab-pane active" data-rel="1">
	<h2>General</h2>
	<div id="accordion-giftregisty">
		<h3> <?php echo __('Gift Registry Info', GIFTREGISTRY_TEXT_DOMAIN)?></h3>
		<div id="accordion-giftregisty-content" <?php if (is_admin() ) :?> class="admin-gift-table" <?php  endif;?>>
			<form class="giftregistry-form"  method="POST">
			    <input type="hidden" name="giftregistry_id" id="giftregistry_id" value="<?php if (is_object($wishlist)) : echo $wishlist->id ; endif;?>"/>
			    <input name="create_giftregistry" id="create_giftregistry" type="hidden" value="1"/>
				<div class="form-group">
					<label for="title"><?php echo __('Title', GIFTREGISTRY_TEXT_DOMAIN) ?></label>
					<input name="title" id="title" type="text" value="<?php if (is_object($wishlist)) : echo $wishlist->title ; endif;?>" size="40">
				</div>
				<h3> <?php echo __('Registrant', GIFTREGISTRY_TEXT_DOMAIN) ?></h3>
				<div class="form-group">
					<label for="registrant_firstname"><?php echo __('First name', GIFTREGISTRY_TEXT_DOMAIN) ?><span class="required">*</span></label>
					<input name="registrant_firstname" id="registrant_firstname"
						type="text"  size="40" value="<?php if (is_object($wishlist)) : echo $wishlist->registrant_firstname ; endif;?>">
				</div>
				<div class="form-group">
					<label for="registrant_lastname"><?php echo __('Last name', GIFTREGISTRY_TEXT_DOMAIN) ?><span class="required">*</span></label>
					<input name="registrant_lastname" id="registrant_lastname" value="<?php if (is_object($wishlist)) : echo $wishlist->registrant_lastname ; endif;?>" type="text"
						value="" size="40">
				</div>
				<div class="form-group">
					<label for="registrant_email"><?php echo __('Email', GIFTREGISTRY_TEXT_DOMAIN) ?><span class="required">*</span></label>
					<input name="registrant_email" id="registrant_firstname" type="text" value="<?php if (is_object($wishlist)) : echo $wishlist->registrant_email ; endif;?>"
						value="" size="40">
				</div>
				<div class="form-group">
					<label for="registrant_lastname"><?php echo __('', GIFTREGISTRY_TEXT_DOMAIN) ?>
						<span class="required">*</span>
					</label>
					<div class="radios-type">
					    <input type="radio" name="registrant_type" value="bride" id="r1" <?php if (is_object($wishlist)) : echo ($wishlist->registrant_type== 'bride') ?  "checked" : "" ;  ; endif;?> />
					    <label class="radio" for="r1">BRIDE</label>
					    <input type="radio" name="registrant_type" value="groom" id="r2" <?php if (is_object($wishlist)) : echo ($wishlist->registrant_type== 'groom') ?  "checked" : "" ;  ; endif;?>  />
					    <label class="radio" for="r2">GROOM</label>
					</div>
				</div>
				<h3> <?php echo __('CoRegistrant', GIFTREGISTRY_TEXT_DOMAIN) ?></h3>
				<div class="form-group">
					<label for="coregistrant_firstname"><?php echo __('First name', GIFTREGISTRY_TEXT_DOMAIN) ?></label>
					<input name="coregistrant_firstname" id="coregistrant_firstname"
						type="text" value="<?php if (is_object($wishlist)) : echo $wishlist->coregistrant_firstname ; endif;?>" size="40">
				</div>
				<div class="form-group">
					<label for="coregistrant_lastname"><?php echo __('Last name', GIFTREGISTRY_TEXT_DOMAIN) ?></label>
					<input name="coregistrant_lastname" id="coregistrant_lastname"
						type="text" value="<?php if (is_object($wishlist)) : echo $wishlist->coregistrant_lastname ; endif;?>" size="40">
				</div>
				<div class="form-group">
					<label for="coregistrant_email"><?php echo __('Email', GIFTREGISTRY_TEXT_DOMAIN) ?></label>
				    <input name="coregistrant_email" id="coregistrant_email"
						type="text" value="<?php if (is_object($wishlist)) : echo $wishlist->coregistrant_email ; endif;?>" size="40">
				</div>
				<div class="form-group">
					<label for="registrant_lastname"><?php echo __('', GIFTREGISTRY_TEXT_DOMAIN) ?>
						<span class="required">*</span>
					</label>
					<div class="radios-type">
					    <input type="radio" name="coregistrant_type" value="bride" id="pg1" <?php if (is_object($wishlist)) : echo ($wishlist->coregistrant_type== 'bride') ?  "checked" : "" ;  ; endif;?> />
					    <label class="radio" for="pg1">BRIDE</label>
					    <input type="radio" name="coregistrant_type" value="groom" id="pg2" <?php if (is_object($wishlist)) : echo ($wishlist->coregistrant_type== 'groom') ?  "checked" : "" ;  ; endif;?>  />
					    <label class="radio" for="pg2">GROOM</label>
					</div>
				</div>
				<hr>
				<h3> <?php echo __('Event', GIFTREGISTRY_TEXT_DOMAIN) ?></h3>
				<div class="form-group">
					<label for="event_datetime"><?php echo __('Event date', GIFTREGISTRY_TEXT_DOMAIN) ?></label>
					<input name="event_date_time" id="event_date_time" type="text"
						value="<?php if (is_object($wishlist)) {
							$eventdate = new DateTime($wishlist->event_date_time);
						echo $eventdate ->format('m-d-Y') ;

						}?>" size="40">
				</div>
				<div class="form-group">
					<label for="event_location"><?php echo __('Event location', GIFTREGISTRY_TEXT_DOMAIN) ?></label>
					<input name="event_location" id="event_location" type="text" value="<?php if (is_object($wishlist)) : echo $wishlist->event_location ; endif;?>"
						size="40">
				</div>
				<div class="form-group">
					<label for="message"><?php echo __('Message for guests', GIFTREGISTRY_TEXT_DOMAIN) ?></label>
					<textarea id="message" name="message" rows="" cols=""><?php if (is_object($wishlist)) : echo $wishlist->message ; endif;?> </textarea>
				</div>
				<div class="form-group">
					<input type="submit" class="button button-primary" value="Save">
				</div>
			</form>
		</div>
	</div>
</div>
<?php } else{?>

<form class="giftregistry-form create-registry"  method="POST" id="msform">
	<ul id="progressbar">
	    <li data-related="fieldset_1" class="active"><span class="text"><?php if(!is_user_logged_in()){  ?>LOGIN / REGISTER<?php } else {?>YOUR ACCOUNT <?php }?></span></li>
	    <li data-related="fieldset_2"><span class="text">YOUR INFO</span></li>
	    <li data-related="fieldset_3"><span class="text">WEDDING INFO</span></li>
		<li></li>
	 </ul>
 <fieldset id="fieldset_1" >
<?php echo do_shortcode('[woocommerce_my_account]'); ?>
<?php if(is_user_logged_in()){  ?>
<input type="button" name="next" class="next action-button trigger-click" value="Next" />
<?php  } ?>
 </fieldset>
  <?php if(is_user_logged_in()){  ?>
  <fieldset id="fieldset_2" >
        <!--input type="hidden" name="giftregistry_id" id="giftregistry_id" value="<?php if (is_object($wishlist)) : echo $wishlist->id ; endif;?>"/-->
		<input name="create_giftregistry" id="create_giftregistry" type="hidden" value="1"/>
  <?php
 $rid =  $wishlist->id;
  if(empty($rid)){
	  echo "<div class='woocommerce-info'>Please enter your information before adding gifts to your registry.</div>";
	
  } else{
	 
  }?>
	<div class="form-field">
		<label for="title"><?php echo __('REGISTRY TITLE', GIFTREGISTRY_TEXT_DOMAIN) ?></label>
		<input name="title" id="title" type="text" value="<?php if (is_object($wishlist)) : echo $wishlist->title ; endif;?>" size="40" placeholder="Ryan & Jo’s Registry">
	</div>
	<div class = "row">
	<!---<h3 style=" text-align: left;">   //echo __('YOUR INFO', GIFTREGISTRY_TEXT_DOMAIN)  </h3>-->
	<div class="col-md-4">
		<label for="registrant_firstname"><?php echo __('YOUR INFO', GIFTREGISTRY_TEXT_DOMAIN) ?><span class="required">*</span></label>
		<input name="registrant_firstname" placeholder="First name" id="registrant_firstname" class="col-md-4" type="text"  size="40" value="<?php if (is_object($wishlist)) : echo $wishlist->registrant_firstname ; endif;?>">
	</div>
	<div class="col-md-4">
		<label for="registrant_lastname"><?php echo __('', GIFTREGISTRY_TEXT_DOMAIN) ?><span class="required">*</span></label>
		<input name="registrant_lastname" placeholder="Last name" id="registrant_lastname" value="<?php if (is_object($wishlist)) : echo $wishlist->registrant_lastname ; endif;?>" type="text"
			value="" size="40">
	</div>
	<div class="col-md-4">
		<label for="registrant_lastname"><?php echo __('', GIFTREGISTRY_TEXT_DOMAIN) ?><span class="required">*</span></label>
		<div class="radios-type">
    <input type="radio" name="registrant_type" value="bride" id="r1" <?php if (is_object($wishlist)) : echo ($wishlist->registrant_type== 'bride') ?  "checked" : "" ;  ; endif;?> />
    <label class="radio" for="r1">BRIDE</label>
    <input type="radio" name="registrant_type" value="groom" id="r2" <?php if (is_object($wishlist)) : echo ($wishlist->registrant_type== 'groom') ?  "checked" : "" ;  ; endif;?>  />
    <label class="radio" for="r2">GROOM</label>
  
</div>
	</div>
	<!--<div class="form-field">
		<label for="registrant_email"><?php //echo __('Email', GIFTREGISTRY_TEXT_DOMAIN) ?><span class="required">*</span></label>
		<input name="registrant_email" id="registrant_firstname" type="text" value="<?php //if (is_object($wishlist)) : echo $wishlist->registrant_email ; endif;?>"
			value="" size="40">
	</div>-->
</div>
	<div class="row">
	<h3> <?php// echo __('YOUR PARTNER', GIFTREGISTRY_TEXT_DOMAIN) ?></h3>
	<div class="col-md-4">
		<label for="coregistrant_firstname"><?php echo __('YOUR PARTNER', GIFTREGISTRY_TEXT_DOMAIN) ?></label>
		<input name="coregistrant_firstname" placeholder="First name" id="coregistrant_firstname" type="text" value="<?php if (is_object($wishlist)) : echo $wishlist->coregistrant_firstname ; endif;?>" size="40">
	</div>
	<div class="col-md-4">
		<label for="coregistrant_lastname"><?php echo __('', GIFTREGISTRY_TEXT_DOMAIN) ?><span class="required">*</span></label>
		<input name="coregistrant_lastname" placeholder="Last name" id="coregistrant_lastname"
			type="text" value="<?php if (is_object($wishlist)) : echo $wishlist->coregistrant_lastname ; endif;?>" size="40">
	</div>
	<div class="col-md-4">
		<label for="registrant_lastname"><?php echo __('', GIFTREGISTRY_TEXT_DOMAIN) ?><span class="required">*</span></label>
		<div class="radios-type">
    <input type="radio" name="coregistrant_type" value="bride" id="pg1" <?php if (is_object($wishlist)) : echo ($wishlist->coregistrant_type== 'bride') ?  "checked" : "" ;  ; endif;?> />
    <label class="radio" for="pg1">BRIDE</label>
    <input type="radio" name="coregistrant_type" value="groom" id="pg2" <?php if (is_object($wishlist)) : echo ($wishlist->coregistrant_type== 'groom') ?  "checked" : "" ;  ; endif;?>  />
    <label class="radio" for="pg2">GROOM</label>
  
</div>
	</div>
	</div>
	<div class="row">
	<div class="col-md-4" >
		<p style="text-align: left; margin: 3% 0;">Invite your partner to manage your registry</p>
	</div>
	<div class="col-md-4">
		<label for="coregistrant_email"><?php echo __('', GIFTREGISTRY_TEXT_DOMAIN) ?></label>
	    <input name="coregistrant_email" placeholder="Partner Email (optional)" id="coregistrant_email"
			type="text" value="<?php if (is_object($wishlist)) : echo $wishlist->coregistrant_email ; endif;?>" size="40">
	</div>
	</div>
	
	<div class="row">
	<div class="col-md-4 col-sm-5 col-xs-12 text-left">
		<label for="upload_image"></label>
<input id="upload_image" type="text" size="36"  name="pro_image" value="<?php if (is_object($wishlist)) : echo $wishlist->image ; endif;?>" style="display:none;" />
<input id="upload_image_button" class="action-button" type="button" value="Upload Couple Photo"  style="width:100%" />
<img src="<?php if (is_object($wishlist)) : echo $wishlist->image ; endif;?>" id="img_src" style="display:none">

<?php if (is_object($wishlist)) :
	if(!empty($wishlist->image)):?>
<input id="remove_image_button" class="action-button" type="button" value="Remove" />
	<?php endif;?>
<?php endif;?>
<style>
#dimension_error p{
	margin-bottom: 0px;
	color: red;
}
.bg_imgs img {width:100%; height: 110px;}
 </style>
<div id="dimension_error"></div>
	</div>
	<div class="col-md-4 col-sm-5 col-xs-12 text-left">
	<a class="btn action-button" data-popup-open="popup-1" href="#" style="width: 100%;padding: 10px 0;margin-top: 4.5%;text-transform: capitalize;
	font-size:13px;font-family: inherit; color:#000; background-color: #fff; border: 2px #69c7ee solid !important ">Upload Registry Background </a>
	    <img src="<?php if (is_object($wishlist)) : echo $wishlist->image ; endif;?>" id="bg_img_src" style="display:none">
	</div>
	</div>
	<br>
	
	<div class="popup" data-popup="popup-1">
    <div class="popup-inner">
	<div class = "row">
		<div class="col-md-12 bg_imgs">
		<label style="text-align: left;" >Please select a background header image for your unique registry!</label>
		            
					<?php $option_1 = get_option('upload_image'); 
								if(!empty($option_1)):?>
								<div class="col-md-4 col-sm-4 img_cont">
									<label>
									 <input type="radio" class="styled_radio" name="upload_image" value="upload_image" <?php if (is_object($wishlist)) : echo ($wishlist->banner_image== 'upload_image') ?  "checked" : "" ;  ; endif;?> /> 
									 <img src="<?php echo $option_1; ?>"><br>
									 </label>
								 </div>
					 <?php endif; ?>
					 
					 <?php $option_2 = get_option('upload_image2'); 
								if(!empty($option_2)):?>
								<div class="col-md-4 col-sm-4 img_cont">
									<label>
										 <input type="radio" class="styled_radio" name="upload_image" value="upload_image2" <?php if (is_object($wishlist)) : echo ($wishlist->banner_image== 'upload_image2') ?  "checked" : "" ;  ; endif;?> /> 
										 <img src="<?php echo $option_2; ?>"><br>
									 <label>
								</div>
					 <?php endif; ?>

					 <?php $option_3 = get_option('upload_image3'); 
								if(!empty($option_3)):?>
								<div class="col-md-4 col-sm-4 img_cont">
									<label>
										 <input type="radio" class="styled_radio" name="upload_image" value="upload_image3" <?php if (is_object($wishlist)) : echo ($wishlist->banner_image== 'upload_image3') ?  "checked" : "" ;  ; endif;?> /> 
										 <img src="<?php echo $option_3; ?>"><br>
									 </label>
								</div>
					 <?php endif; ?>

					 <?php $option_4 = get_option('upload_image4'); 
								if(!empty($option_4)):?>
								<div class="col-md-4 col-sm-4 img_cont">
									<label>
										 <input type="radio" class="styled_radio" name="upload_image" value="upload_image4" <?php if (is_object($wishlist)) : echo ($wishlist->banner_image== 'upload_image4') ?  "checked" : "" ;  ; endif;?>/> 
										 <img src="<?php echo $option_4; ?>"><br>
									</label>
								</div>
					 <?php endif; ?>
					 
					   <?php $option_5 = get_option('upload_image5'); 
								if(!empty($option_5)):?>
								<div class="col-md-4 col-sm-4 img_cont">
								<label>
									 <input type="radio" class="styled_radio" name="upload_image" value="upload_image5" <?php if (is_object($wishlist)) : echo ($wishlist->banner_image== 'upload_image5') ?  "checked" : "" ;  ; endif;?>/> 
									 <img src="<?php echo $option_5; ?>"><br>
								</label>
								</div>
					 <?php endif; ?>
					
					  <?php 
					   for($i=6; $i<=25; $i++){
					       $option = get_option('upload_image'.$i); 
								if(!empty($option)):?>
								<div class="col-md-4 col-sm-4 img_cont">
								<label>
									 <input type="radio" class="styled_radio" name="upload_image" value="upload_image<?=$i?>" <?php if (is_object($wishlist)) : echo ($wishlist->banner_image== 'upload_image'.$i) ?  "checked" : "" ;  ; endif;?>/> 
									 <img src="<?php echo $option; ?>"><br>
								</label>
								</div>
					        <?php endif; 
					   }
					  
					 /* customization start 
					 loop through gift registry background images ,fetched from db
					 */
					 if(isset($images) && is_array($images) && !empty($images)){
					     foreach($images as $image){ ?>
					         <div class="col-md-4 col-sm-4 img_cont" data-id="<?=$image?>">
					             <span title="Remove Image" class="remove-bg-img" data-toggle="tooltip" data-placement="top"><i class="fa fa-times-circle-o" aria-hidden="true"></i></span>
								<label>
									 <input type="radio" class="styled_radio" name="upload_image" value="<?=$image?>" <?php if (is_object($wishlist)) : echo ($wishlist->banner_image== $image) ?  "checked" : "" ;  ; endif;?>/> 
									 <img src="<?php echo get_site_url().'/wp-content/uploads/2017/05/'.$image; ?>"><br>
								</label>
							 </div>
					     <?php } //end of foreach
					 }// end of if
					 /* customization end */
					 
					 ?>
					 
					 
				 </div>
				     <div class="col-md-8 notify-msg"></div>
				     <div class="col-md-4"><input id="upload_bg_img_btn" class="action-button" type="button" value="Upload Background Image"  style="background-color: #69c7ee;border-color:#69c7ee;color:white" /></div>
				
				 <br>
	</div>
 <!--<p><a data-popup-close="popup-1" href="#">Close</a></p>-->
        <a class="popup-close" data-popup-close="popup-1" href="#">x</a>
    </div>
</div>
	 <input type="button" name="previous" class="previous action-button" value="Previous" />
	<input type="button" name="next" class="next action-button" value="Next" />
	</fieldset>
 <fieldset id="fieldset_3" >
 <div class= "row">
	<h3> <?php //echo __('Event', GIFTREGISTRY_TEXT_DOMAIN) ?></h3>
	<div class="col-md-6">
		<label for="event_datetime"><?php echo __('Wedding date', GIFTREGISTRY_TEXT_DOMAIN) ?></label>
		<input name="event_date_time" id="event_date_time" type="text"
			value="<?php if (is_object($wishlist)) {
				$eventdate = new DateTime($wishlist->event_date_time);
			echo $eventdate ->format('m-d-Y') ;

			}?>" size="40">
	</div>

	</div>
	<div class="row">
	<div class="col-md-6">
		<label for="message"><?php echo __('Message for guests', GIFTREGISTRY_TEXT_DOMAIN) ?></label>
		<textarea id="message" name="message" rows="10" cols=""><?php if (is_object($wishlist) && !empty($wishlist->message) ) {echo $wishlist->message ;  }else { echo "Our Wrapistry registry will help make it easier and more convenient for you to choose something you know we will love. We are forever grateful!"; } ?> </textarea>
	</div>
	</div>
	<div class ="row">
	 <input type="button" name="previous" class="previous action-button" value="Previous" />
	<input class="action-button" name="submit" type="submit" value="CREATE REGISTRY">
	</div>
	</fieldset>
	
	<fieldset id="fieldset_4" >
	
	<h2 style=" color: #ddd; font-family: inherit;">Taking you to the Product Page </h2>
	
	
	</fieldset>
	
	 <?php wp_enqueue_media(); ?>
	 <script>
	 jQuery("document").ready(function() {
        jQuery(".trigger-click").trigger('click');	
});
	 jQuery(document).ready(function() {
 
   jQuery('#upload_image_button').click(function(e) {
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
            var width = uploaded_image.toJSON().width;
			var height = uploaded_image.toJSON().height;
			if ( height > 600 || height > 600 )
			{
				console.log("image size is greater");
					jQuery("p.success").remove();
				 jQuery('<p class="error">Image dimension exceeded</p>').appendTo('#dimension_error');
				return false;
			}
			jQuery("p.error").remove();
			jQuery('<p class="success" style="color:green;">Updated Successfully</p>').appendTo('#dimension_error');
			var image_url = uploaded_image.toJSON().url;
            // Let's assign the url value to the input field
            jQuery('#upload_image').val(image_url);
            jQuery('#img_src').css('display','block');
			jQuery("#img_src").attr("src", image_url);
        });
    });
	

	jQuery('#remove_image_button').click(function(e) {
		
		jQuery('#img_src').removeAttr('src');
		jQuery('#upload_image').removeAttr('value');
        jQuery('#img_src').css('display','none');
		
 });

	// gift registry background image upload
	jQuery('#upload_bg_img_btn').click(function(e) {
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

            var width = uploaded_image.toJSON().width;
			var height = uploaded_image.toJSON().height;

			jQuery("p.success").remove();
			jQuery("p.error").remove();
			if ( width < 500 || height < 200 ){
				jQuery('<p class="error">Image Dimensions are very small.</p>').appendTo('.notify-msg');
				return false;
			}else if ( height > 600 ){
				jQuery('<p class="error">Image dimension exceeded</p>').appendTo('.notify-msg');
				return false;
			}
			jQuery('<p class="success" style="color:green;">Image Uploaded Successfully</p>').appendTo('.notify-msg');
			var image_url = uploaded_image.toJSON().url;
			var startIndex = image_url.lastIndexOf("/")+1;
			var img = image_url.substring(startIndex, image_url.length);
			var htmlData='<div class="col-md-4 col-sm-4 img_cont" data-id="'+img+'"><span title="Remove Image" class="remove-bg-img" data-toggle="tooltip" data-placement="top"><i class="fa fa-times-circle-o" aria-hidden="true"></i></span>'+
									'<label>'+
										 '<input type="radio" class="styled_radio" name="upload_image" value="'+img+'" /> '+
										 '<img src="'+image_url+'"><br>'+
									'</label>'+
								'</div>';
			jQuery('.bg_imgs').append(htmlData);
			jQuery.ajax({
			    url:"<?=get_template_directory_uri()?>/upload_gift_reg_img.php",
			    type:"POST",
			    data:{img:img},
			    success:function(data){
			        
			    }
			})
        });
    });
	
	
	
	// remove bg image
	jQuery(document).on('click', '.remove-bg-img', function(e){
	    var parent = jQuery(this).parents('.img_cont');
	    var img = parent.attr('data-id');
	    jQuery.ajax({
			    url:"<?=get_template_directory_uri()?>/upload_gift_reg_img.php",
			    type:"POST",
			    data:{image:img, action:'del'},
			    success:function(){
			      parent.remove();  
			    }
			})
	})
	
	// on select image close select image popup and dipslay image 
	jQuery(document).on('click', '.styled_radio', function(e){
	    jQuery(this).parents('.popup').css('display', 'none');
	    var imgUrl = jQuery(this).parents('.img_cont').find('img').attr('src');
	    jQuery('#bg_img_src').attr('src', imgUrl).css('display','block');
	})
});
</script>
<?php }?>
</form>
<?php } ?>
<script type="text/javascript">
jQuery(document).ready(function(){
	jQuery("#accordion-giftregisty").accordion({
      collapsible: true
    });
    jQuery('#event_date_time').datepicker();
}) ;
</script>