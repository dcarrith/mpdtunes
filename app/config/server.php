<?php

$config = array();

$config['debug']		= FALSE;

//$config['environment'] 		= "development";
$config['environment'] 		= "production";

// This is in case you want to email the site admin when PHP errors are encountered
$config['admin_email']		= "admin@mpdtunes.com";

// This should be http or https
$config['base_protocol']	= "http://";

$config['secure_protocol']	= "https://";

$config['base_domain']		= "demo.mpdtunes.com";

$config['base_site_title']	= "MPDTunes - free your music, own your cloud";

$environment = App::environment();

if ($environment == "testing") {

	// This is so we can set this to whatever we want and not have to change 100 includes
	$config['document_root']	= $_SERVER['PWD'].'/public/';

} else {

	// This is so we can set this to whatever we want and not have to change 100 includes
	$config['document_root']	= $_SERVER['DOCUMENT_ROOT'];
}

return $config;

?>
