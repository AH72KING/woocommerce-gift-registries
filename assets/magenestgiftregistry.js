function MagenestGiftRegistry() {
	
}
MagenestGiftRegistry.prototype.submitRegistry = function () {
	console.log('i am running');
	if(jQuery('#add-to-registry').attr('data-loadedgiftregistries') == 'yes'){
		
	}else{
		jQuery('.addto-reg-id').val('');
	}
	if(jQuery('.addto-reg-id').val() !==''){
    	jQuery("input[name='add-to-cart']").attr('name' ,'add-to-giftregistry');
    	jQuery('#add-registry').val(1);
        $data = jQuery('.cart').serializeArray();
        $data['action'] = jQuery('.cart').attr('action');
        jQuery.ajax({
            url:the_ajax_script.ajaxurl,
            data:$data,
            type:"POST",
            beforeSend:function(){
                jQuery('#registries_wrapper').show();
                jQuery('#registries_wrapper .return-admin-ajax').remove();
            },
            success:function(data){
            	console.log(data);
            	jQuery('#registries_wrapper .added-to-cart').prepend('<div class="return-admin-ajax"></div>');
            	var data = data.trim().split("::");
                if(data[0] == 'OK'){
                	jQuery('#registries_wrapper .added-to-cart .return-admin-ajax').html(data[1]);
                	jQuery('#registries_wrapper').hide();
                	jQuery('#popup1').show();
                }
                jQuery('#add-to-registry').attr('data-loadedgiftregistries','no');
            } 
        });
	}else{
		jQuery('#add-to-registry').attr('data-loadedgiftregistries', 'yes');
		jQuery('#registries_wrapper .return-admin-ajax').remove();
	    loadGiftRegistries('null');
	}
}
var giftRegistry = new MagenestGiftRegistry();