<script type="text/javascript">
var $ = jQuery;
$(document).ready(function(){
	$('#test_twitter_button').click(function(){
		$('#smc_form').attr("action", "");
		$('input[name="twitter_test"]').val("1");
		$('#submit').click();
		return false;
	});
});
</script>
<div class="wrap">
<h2>Post To Twitter Settings</h2>
<form method="post" action="options.php" id="smc_form">
    <?php settings_fields( 'smc-settings-group' ); ?>
    <?php do_settings_sections( 'smc-settings-group' ); ?>
    <p>
    Problems with installing? Read this article: <a href="http://duco.cc/easily-post-to-twitter-through-wordpress/">http://duco.cc/easily-post-to-twitter-through-wordpress/</a>
    </p>
	<h3>Twitter</h3>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">Consumer Key</th>
        <td><input type="text" name="smc_twitter_consumer_key" value="<?php echo esc_attr( get_option('smc_twitter_consumer_key') ); ?>" /></td>
        </tr>
		
		<tr valign="top">
        <th scope="row">Consumer Secret</th>
        <td><input type="text" name="smc_twitter_consumer_secret" value="<?php echo esc_attr( get_option('smc_twitter_consumer_secret') ); ?>" /></td>
        </tr>
		
		<tr valign="top">
        <th scope="row">Access Token</th>
        <td><input type="text" name="smc_twitter_access_token" value="<?php echo esc_attr( get_option('smc_twitter_access_token') ); ?>" /></td>
        </tr>
		
		<tr valign="top">
        <th scope="row">Access Token Secret</th>
        <td><input type="text" name="smc_twitter_access_token_secret" value="<?php echo esc_attr( get_option('smc_twitter_access_token_secret') ); ?>" /></td>
        </tr>
    </table>
	<p class="submit">
		<input type="hidden" name="twitter_test" value="0" />
		When you click this button, a test post will be made to your Twitter account (if you've changed any settings, first click "Save Changes").<br />
		<a href="#" class="button button-primary" id="test_twitter_button">Test</a><br />
		<?php echo test_social_media(); ?>
	</p>
	
	<p>
		Send URL with Tweet<br />
		<input type="checkbox" name="smc_send_url" value="1"<?php echo (esc_attr( get_option('smc_send_url') ) === "1" ? " checked" : ""); ?> />
	</p>

	<p>
		Also allow editors and authors to post to Twitter<br />
		<input type="checkbox" name="smc_editor_author_post" value="1"<?php echo (esc_attr( get_option('smc_editor_author_post') ) === "1" ? " checked" : ""); ?> />
	</p>
    
    <?php submit_button(); ?>

</form>
<p>
	Created by <a href="http://duco.cc/">Duco Winterwerp</a>. Code to post to Twitter created by <a href="http://jublo.net">
Jublo Solutions</a>.
</p>
</div>