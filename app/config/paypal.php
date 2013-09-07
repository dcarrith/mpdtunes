<?php
$config = array();

$config['paypal_enabled'] 			= false;

// accounts will be active immmediately
$config['open_registration']			= false;
$config['paypal_website_payments_standard']	= true;
$config['paypal_website_payments_pro']		= false;
$config['paypal_direct_payments']		= false;
$config['paypal_express_checkout']		= true;
$config['paypal_proxy_host'] 			= '127.0.0.1';
$config['paypal_proxy_port']			= '808';
$config['paypal_use_proxy'] 			= false;
$config['paypal_version']			= "84";

$config['paypal_checkout_return_url'] 		= Config::get('server.secure_protocol') . Config::get('server.base_domain') . "/paypal/confirm";
$config['paypal_checkout_cancel_url'] 		= Config::get('server.secure_protocol') . Config::get('server.base_domain') . "/paypal/cancel";

// Paypal sandbox urls - This is the URL that the buyer is first sent to do authorize payment with their paypal account
$config['paypal_sandbox_api_endpoint_url']	= "https://api-3t.sandbox.paypal.com/nvp/";
$config['paypal_sandbox_url']			= "https://www.sandbox.paypal.com/webscr?cmd=_express-checkout-mobile&token=";
$config['paypal_sandbox_dg_url'] 		= "https://www.sandbox.paypal.com/incontext?token=";

// Paypal urls - This is the URL that the buyer is first sent to do authorize payment with their paypal account
$config['paypal_api_endpoint_url'] 		= "https://api-3t.paypal.com/nvp/";
$config['paypal_url']				= "https://www.paypal.com/cgi-bin/webscr?cmd=_express-checkout-mobile&token=";
$config['paypal_dg_url'] 			= "https://www.paypal.com/incontext?token=";

// BN Codeis only applicable for partners
$config['paypal_bn_code']			= "PP-ECWizard";
