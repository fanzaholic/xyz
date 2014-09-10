<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
	app_session_name
	buat nama session userdata user login
 */
$config['app_session_name'] = 'ASOYdo327eydhskhy328172';

$config['app_social'] = array(
	'facebook' => array(
			'appId' => '',
			'secret' => '',
			'scope' => 'public_profile,email,publish_stream',
			'callback' => 'auth/facebook'
		),
	'twitter' => array(
			'token' => '',
			'secret' => ''
		)
);