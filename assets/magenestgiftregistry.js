function MagenestGiftRegistry() {
	
}
MagenestGiftRegistry.prototype.submitRegistry = function () {
   // console.log('right Here');
	//console.log('submit registry');
	if(jQuery('.addto-reg-id').val() !==''){
	    //console.log('addto-reg-id');
    	jQuery("input[name='add-to-cart']").attr('name' ,'add-to-giftregistry');
    	jQuery('#add-registry').val(1);
    	//jQuery('.cart').attr('url' ,'http://localhost/fuetest/index.php');
       jQuery('.cart').submit();
	} else {
	    //console.log('loadGiftRegistries');
	    loadGiftRegistries('null');
	}
}
var giftRegistry = new MagenestGiftRegistry();