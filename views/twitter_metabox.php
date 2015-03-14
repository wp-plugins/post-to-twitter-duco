<p>
	<label for="check_post_to_twitter">Post this to Twitter</label>&nbsp;<input type="checkbox" name="check_post_to_twitter" id="check_post_to_twitter" value="1" /><br />
	<label for="twitter_send_featured_image">Send "featured image" with your tweet</label>&nbsp;<input type="checkbox" name="twitter_send_featured_image" id="twitter_send_featured_image" value="1" /><br />
	<label for="twitter_tweet_text">Tweet text (if empty, the title will be used in the tweet, and think about the 140 character limit)</label><br />
	<input type="text" name="twitter_tweet_text" id="twitter_tweet_text" />
</p>
<?php
$message = get_post_meta($post->ID, "smc_twitter_message", true);
if($message != ""):
?>
<p>
	Last message: <strong><?php echo $message; ?></strong>
</p>
<?php endif; ?>