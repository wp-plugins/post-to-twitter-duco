<?php
/**
 * Plugin Name: Post To Twitter
 * Plugin URI: http://duco.cc
 * Description: A plugin which, as the name says, enables you to tweet a WordPress post. You can also send the featured image with your tweet.
 * Version: 0.2
 * Author: Duco Winterwerp
 * Author URI: http://duco.cc
 * License: GPLv2
 */
 
defined('ABSPATH') or die("No script kiddies please!");

$smc_path = plugin_dir_path(__FILE__);
$smc_message = "";
require_once($smc_path.'logic.php');
$allow_editor_author_post = get_option("smc_editor_author_post") == "1" ? true : false;

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
        register_setting( 'smc-settings-group', 'smc_editor_author_post' );
    }
}

function smc_settings_page() {
    global $smc_path;
    include($smc_path.'views/settings.php');
}

function smc_init_metaboxes(){
    global $allow_editor_author_post;
    if(smc_twitter_enabled() && smc_check_site_admin($allow_editor_author_post)){
        add_meta_box("smc_twitter_post", "Post To Twitter", "smc_twitter_metabox", "post");
    }
}

function smc_init_metabox_post($post_id){
    global $allow_editor_author_post;
    if(smc_twitter_enabled() && smc_check_site_admin($allow_editor_author_post)){
        smc_setup_post_to_twitter($post_id);
    }
}

function smc_twitter_metabox($post){
    global $smc_path;
    include($smc_path.'views/twitter_metabox.php');
}

function smc_setup_post_to_twitter($post_id){
    $post_to_twitter = isset($_POST["check_post_to_twitter"]) && $_POST["check_post_to_twitter"] == "1";
    $send_featured_image = isset($_POST["twitter_send_featured_image"]) && $_POST["twitter_send_featured_image"] == "1";
    $tweet_text = isset($_POST['twitter_tweet_text']) ? $_POST['twitter_tweet_text'] : "";

    if($post_to_twitter){
        $post = get_post($post_id);
        $status = get_post_status($post_id);

        if($status === "publish"){
            smc_post_to_twitter($post_id, $send_featured_image, $tweet_text);
        }
        else if($status === "future"){
            $time = strtotime($post->post_date);
            smc_clear_old_schedule("smc_post_to_twitter_action");
            $args = array($post_id, $send_featured_image, $tweet_text);
            wp_schedule_single_event($time, "smc_post_to_twitter_action", $args);
            $message = "The tweet, with text '".$tweet_text."', is scheduled to be tweeted on '".$post->post_date."'.";
            if($send_featured_image){
                $message .= " The featured image will also be sent.";
            }
            update_post_meta($post_id, "smc_twitter_message", $message);
        }
    }
}

function smc_clear_old_schedule($hook_to_delete){
    $crons = _get_cron_array();
    if(count($crons) == 0){
        return;
    }
    foreach($crons as $timestamp => $cron){
        foreach($cron as $key => $hook){
            if($key == $hook_to_delete){
                unset($crons[$timestamp][$key]);
            }
        }
    }
    _set_cron_array($crons);
}

add_action("smc_post_to_twitter_action", "smc_post_to_twitter", 10, 3);
function smc_post_to_twitter($post_id, $send_featured_image, $tweet_text){
    $post = get_post($post_id);
    if($post !== null){
        $path = null;
        if($send_featured_image){
            $path = smc_get_fi_path($post_id);
        }
        if($tweet_text == ""){
            $tweet_text = $post->post_title;
        }
        if(get_option('smc_send_url') == "1"){
            $tweet_text .= " ".get_permalink($post_id);
        }
        $reply = smc_twitter_post($tweet_text, $path);
        $message = "";
        if(isset($reply->httpstatus) && $reply->httpstatus == 200){
            $message = "The tweet with text '".$tweet_text."' has been sent succesfully on ".date("Y-m-d G:i:s");
        }
        else if(isset($reply->errors[0]->message)){
            $message = $reply->errors[0]->message;
        }
        update_post_meta($post_id, "smc_twitter_message", $message);
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