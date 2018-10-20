<?php
/* >_ Developed by Vy Nghĩa */
session_start();
require_once 'SDK/Facebook/autoload.php';
require 'server/config.php';

if(isset($_SESSION["admin"]) && !empty(FB_APP_ID) && !empty(FB_APP_SR)){
	$fb = new Facebook\Facebook([
	  'app_id' => FB_APP_ID,
	  'app_secret' => FB_APP_SR,
	  'default_graph_version' => 'v2.10',
	  ]);

	$helper = $fb->getRedirectLoginHelper();
	$permissions = ['public_profile', 'manage_pages', 'publish_pages']; //optional

	try {
		if (isset($_SESSION['facebook_access_token'])) {
			$accessToken = $_SESSION['facebook_access_token'];
		} else {
			$accessToken = $helper->getAccessToken();
		}
	} catch(Facebook\Exceptions\FacebookResponseException $e) {
		// When Graph returns an error
		echo 'Graph returned an error: ' . $e->getMessage();

		exit;
	} catch(Facebook\Exceptions\FacebookSDKException $e) {
		// When validation fails or other local issues
		echo 'Facebook SDK returned an error: ' . $e->getMessage();
		;
	 }

	if (isset($accessToken)) {
		if (isset($_SESSION['facebook_access_token'])) {
			$fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
		} else {
			// getting short-lived access token
			$_SESSION['facebook_access_token'] = (string) $accessToken;

			// OAuth 2.0 client handler
			$oAuth2Client = $fb->getOAuth2Client();

			// Exchanges a short-lived access token for a long-lived one
			$longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken($_SESSION['facebook_access_token']);

			$_SESSION['facebook_access_token'] = (string) $longLivedAccessToken;

			// setting default access token to be used in script
			$fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
		}

		// redirect the user back to the same page if it has "code" GET variable
		if (isset($_GET['code'])) {
			mysql_query("UPDATE `facebook` SET `token` = '{$accessToken}' WHERE 1");
			
			if(isset($_SERVER['HTTP_REFERER'])){
			  if($_SERVER['HTTP_REFERER'] == 'https://www.facebook.com/'){
					  header("Location: admin/facebook");
				} else {
				  header("Location: {$_SERVER['HTTP_REFERER']}");  
				}
			} else {
				header("Location: admin/facebook");
			}
		}

		// getting basic info about user
		try {
			$profile_request = $fb->get('/me?fields=name,first_name,last_name,email');
			$profile = $profile_request->getGraphNode()->asArray();
		} catch(Facebook\Exceptions\FacebookResponseException $e) {
			// When Graph returns an error
			session_destroy();
			// redirecting user back to app login page
			echo '<script>window.location = "'.$_SERVER['HTTP_REFERER'].'";</script>';
			exit;
		} catch(Facebook\Exceptions\FacebookSDKException $e) {
			// When validation fails or other local issues
			echo 'Facebook SDK returned an error: ' . $e->getMessage();
			exit;
		}
		
		try {
			$profile_request = $fb->get('/me');
			$profile = $profile_request->getGraphNode()->asArray();
		} catch(Facebook\Exceptions\FacebookResponseException $e) {
			// When Graph returns an error
			echo 'Graph returned an error: ' . $e->getMessage();
			session_destroy();
			// redirecting user back to app login page
			header("Location: ./");
			exit;
		} catch(Facebook\Exceptions\FacebookSDKException $e) {
			// When validation fails or other local issues
			echo 'Facebook SDK returned an error: ' . $e->getMessage();
			exit;
		}
		} else {
		$loginUrl = $helper->getLoginUrl(trim(WEBURL, "/").'/login.php', $permissions);
	}
}
