<?php
// Other
function test_social_media(){
	if(count($_POST) > 0){
		if(isset($_POST['twitter_test']) && $_POST['twitter_test'] == "1"){
			return print_r(smc_twitter_post("This is a test post by Post To Twitter #test"), true);
		}
	}
	return "";
}

function smc_check_site_admin($not_only_admin = false){
    $currentUser = wp_get_current_user();
    if($not_only_admin){
    	$roles_to_check = array("administrator", "editor", "author");
    	foreach($currentUser->roles as $role){
    		if(in_array($role, $roles_to_check)){
    			return true;
    		}
    	}
    }
    return in_array('administrator', $currentUser->roles);
}

// Twitter
function smc_twitter_post($text, $image_path = null){
	global $smc_path;
	require_once($smc_path.'codebird/codebird.php');
	\Codebird\Codebird::setConsumerKey(get_option('smc_twitter_consumer_key'), get_option('smc_twitter_consumer_secret'));
	$cb = \Codebird\Codebird::getInstance();
	$cb->setToken(get_option('smc_twitter_access_token'), get_option('smc_twitter_access_token_secret'));
	 
	$params = array(
	  'status' => $text
	);
	$reply = null;
	if($image_path != null){
		$params['media[]'] = $image_path;
		$reply = $cb->statuses_updateWithMedia($params);
	}
	else{
		$reply = $cb->statuses_update($params);
	}
	return $reply;
}

function smc_twitter_enabled(){
	return get_option('smc_twitter_consumer_key') !== false;
}
?>