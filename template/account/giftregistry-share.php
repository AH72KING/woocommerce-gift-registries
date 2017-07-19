<?php

if (!$url) {
	return ;
}
$not_encode_url  = $url;
$url 			 = urlencode($url);
$title 			 = urlencode(get_option('giftregistry_share_title'));
$replace 		 = array('{wishlist_url}'=>$not_encode_url ) ;
$content 		 = strtr(get_option('giftregistry_share_text'), $replace);
$twitter_summary =	$content;
$summary 		 = urlencode($content);
$imageurl 		 = urlencode( get_option( 'giftregistry_share_image_url' ) );
$staticImageUrl  = 'https://wrap.phillco.co.za/wp-content/uploads/2017/02/banner_dkeoiz.jpg';
$facebook 		 = get_option('giftregistry_share_facebook', 'yes');

$google_share_link 	 = "https://plus.google.com/share?url=" . $url . '&amp;title=' . $title ;
$twitter_share_link  = "https://twitter.com/share?url=" . $not_encode_url . "&amp;text=" . $twitter_summary ;
$facebook_share_link =	"https://www.facebook.com/sharer.php?u=" . $not_encode_url . "&picture=" . $staticImageUrl ;

	if(is_user_logged_in()){  ?>
	<div class="giftregistry-share">
		<ul class="sociallisting">
			<?php if (get_option('giftregistry_share_facebook') =='yes') :?>
			<li style="list-style-type: none; display: inline-block;"><a target="_blank" class="facebook" href="<?php echo $facebook_share_link ?>"><?php echo __('Facebook' ,GIFTREGISTRY_TEXT_DOMAIN)?></a></li>
			<?php endif ?>
			<?php if (get_option('giftregistry_share_twitter') =='yes') :?>

			<li style="list-style-type: none; display: inline-block;"><a target="_blank" class="twitter" href="<?php echo $twitter_share_link?>"><?php echo __('Twitter' ,GIFTREGISTRY_TEXT_DOMAIN)?></a></li>
			<?php endif ?>
			<?php if (get_option('giftregistry_share_google_plus') =='yes') :?>

			<li style="list-style-type: none; display: inline-block;"><a target="_blank" class="googleplus" href="<?php echo $google_share_link ?>" onclick='javascript:window.open(this.href, "", "menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600");return false;'><?php echo __('Google plus' ,GIFTREGISTRY_TEXT_DOMAIN)?></a></li>
			<?php endif ?>
			<?php if (get_option('giftregistry_share_email') =='yes') :?>

			<li style="list-style-type: none; display: inline-block;">
				<a id ="share-email" class="email" href="#" onclick="showsharegiftregistryform()" ><?php echo __('Email' ,GIFTREGISTRY_TEXT_DOMAIN)?></a>
					<div>
						<form method="POST" id="share_via_email_form" class="form email" style="display: none">
							<input type="hidden" name="giftregistry-share-email" value="1" />
							<div class="form-field">
								<label for="recipient"><?php echo __('Recipient', GIFTREGISTRY_TEXT_DOMAIN) ?></label>
								<input name="recipient" id="recipient" type="text" size="40">
								<span class="note"><?php echo __("separate email by commas" , GIFTREGISTRY_TEXT_DOMAIN)?></span>	
							</div>
							<div class="form-field">
								<label for="email_subject"><?php echo __('Subject', GIFTREGISTRY_TEXT_DOMAIN) ?>
								</label>
								<input name="email_subject" id="email_subject" type="text" value="<?php echo get_option('giftregistry_notify_email_subject') ?>"
										size="40">
							</div>
							<div class="form-field">
								<label for="message"><?php echo __('Message', GIFTREGISTRY_TEXT_DOMAIN) ?></label>
								<textarea id="message" name="message" rows="" cols="">
									<?php 
										$re 	 = array('{wishlist_url}'=>$not_encode_url); 
										$content = strtr(get_option('giftregistry_share_text'),$re); 
										echo $content;  
									?>
								</textarea>
							</div>
							    <input type="submit" value="Send" />
						</form>
					</div>
			</li>
		<?php endif ?>
		</ul>
	</div>
<?php } ?>
<script type="text/javascript">
	function showsharegiftregistryform() {
		jQuery('#share_via_email_form').show();
		jQuery('html, body').animate({scrollTop:jQuery("#share-email").offset().top}, 2000);
		jQuery('#recipient').focus();
	}
</script>