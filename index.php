<?php
/**
 * Plugin Name: Post To Twitter
 * Plugin URI: http://duco.cc
 * Description: A plugin which, as the name says, enables you to tweet a WordPress post. You can also send the featured image with your tweet.
 * Version: 0.0.1
 * Author: Duco Winterwerp
 * Author URI: http://duco.cc
 * License: x
 */
 
defined('ABSPATH') or die("No script kiddies please!");

$smc_path = plugin_dir_path(__FILE__);
$smc_message = "";
require_once($smc_path.'logic.php');

add_action('admin_menu', 'smc_create_menu');
add_action('add_meta_boxes', 'smc_init_metaboxes');
add_action("save_post", "smc_init_metabox_post");
add_action('admin_init', 'register_mysettings');

function smc_create_menu() {
	if(smc_check_site_admin()){
		add_options_page(
				'Post To Twitter', 
				'Post To Twitter', 
				'manage_options', 
				'post-to-twitter', 
				'smc_settings_page'
			);
	}
}

function register_mysettings() {
	if(smc_check_site_admin()){
		register_setting( 'smc-settings-group', 'smc_twitter_consumer_key' );
		register_setting( 'smc-settings-group', 'smc_twitter_consumer_secret' );
		register_setting( 'smc-settings-group', 'smc_twitter_access_token' );
		register_setting( 'smc-settings-group', 'smc_twitter_access_token_secret' );
		register_setting( 'smc-settings-group', 'smc_send_url' );
	}
}

function smc_settings_page() {
	global $smc_path;
	include($smc_path.'views/settings.php');
}

function smc_init_metaboxes(){
	if(smc_twitter_enabled() && smc_check_site_admin()){
		add_meta_box("smc_twitter_post", "Post To Twitter", "smc_twitter_metabox", "post");
	}
}

function smc_init_metabox_post($post_id){
	if(smc_twitter_enabled() && smc_check_site_admin()){
		smc_post_to_twitter($post_id);
	}
}

function smc_twitter_metabox($post){
	global $smc_path;
	include($smc_path.'views/twitter_metabox.php');
}

function smc_post_to_twitter($post_id){
	$post = get_post($post_id);
	if(get_post_status($post_id) === "publish"){
		$path = null;
		if(isset($_POST["check_post_to_twitter"]) && $_POST["check_post_to_twitter"] == "1"){
			if(isset($_POST["twitter_send_featured_image"]) && $_POST["twitter_send_featured_image"] == "1"){
				$path = smc_get_fi_path($post_id);
			}
			$text = isset($_POST['twitter_tweet_text']) ? $_POST['twitter_tweet_text'] : "";
			if($text == ""){
				$text = $post->post_title;
			}
			if(get_option('smc_send_url') == "1"){
				$text .= " ".get_permalink($post_id);
			}
			$reply = smc_twitter_post($text, $path);
			$message = "";
			if(isset($reply->httpstatus) && $reply->httpstatus == 200){
				$message = "The tweet has been sent succesfully on ".date("Y-m-d G:i:s");
			}
			else if(isset($reply->errors[0]->message)){
				$message = $reply->errors[0]->message;
			}
			update_post_meta($post_id, "smc_twitter_message", $message);
		}
	}
}

function smc_get_fi_path($post_id){
	$image_url = wp_get_attachment_image_src( get_post_thumbnail_id($post_id), "full");
	$upl_dir = wp_upload_dir();
	$basedir = $upl_dir["basedir"];
	$path = $basedir."/".substr($image_url[0], strpos($image_url[0], 'uploads'), strlen($image_url[0]));
	$path = str_replace("uploads/uploads", "uploads", $path);
	return $path;
}
?>