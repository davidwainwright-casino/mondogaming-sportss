<?php defined('BASEPATH') OR exit('No direct script access allowed');

/* ----------------------------------------------------------------------------
/* HybridAuth Guide: http://hybridauth.github.io/hybridauth/userguide.html
/* ------------------------------------------------------------------------- */

$config =
	array(

		// For godady Shared Hosting Server uncomment the line below
		// Please update the callback url too
		'Hauth_base_url' => 'social_auth/endpoint',

		// Please comment this if you have uncommented the above
		// 'Hauth_base_url' => 'social_auth/endpoint',

		"providers" => array (

			"Google" => array (
				"enabled" => false,
				"keys"    => array ( "id" => "", "secret" => "" ),
			),

			"Facebook" => array (
				"enabled" => false,
				"keys"    => array ( "id" => "", "secret" => "" ),
				"scope"   => "email, public_profile, user_birthday",
			),

			"Twitter" => array (
				"enabled" => false,
				"keys"    => array ( "key" => "", "secret" => "" )
			),

			"Yahoo" => array (
				"enabled" => false,
				"keys"    => array ( "id" => "", "secret" => "" ),
			),

			"Live" => array (
				"enabled" => false,
				"keys"    => array ( "id" => "", "secret" => "" )
			),

			"MySpace" => array (
				"enabled" => false,
				"keys"    => array ( "key" => "", "secret" => "" )
			),

			"OpenID" => array (
				"enabled" => false
			),

			"LinkedIn" => array (
				"enabled" => false,
				"keys"    => array ( "key" => "", "secret" => "" )
			),

			"Foursquare" => array (
				"enabled" => false,
				"keys"    => array ( "id" => "", "secret" => "" )
			),

			"AOL"  => array (
				"enabled" => false
			),
		),

		"debug_mode" => false,

		"debug_file" => APPPATH.'logs/hybridauth.php',
	);
