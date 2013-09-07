<?php 

class PaypalController extends BaseController {

	var $data = array();
	var $paypal_configs = array();
	
	function __construct() {

		parent::__construct();

		// Get and merge the theme config defaults into the main data array
                $this->data = array_merge($this->data, Configurator::getDefaults('theme'));

		// Get and merge the paypal config defaults into the main data array
                $this->paypal_configs = array_merge($this->paypal_configs, Configurator::getDefaults('paypal'));
		
		$this->data['site_title'] = Config::get('defaults.base_site_title') . " - Subscribe with Paypal";

		$this->data['paypal_controller'] 	= true;
		$this->data['register_link_data_ajax']	= "false";
		$this->data['register_link']		= "register";
		$this->data['home_link_data_ajax']	= "false";
		$this->data['home_link']		= "login";
	}

	public function payments() {

                // Get and merge all the words we need for the base controller into the main data array
                $this->data = array_merge($this->data, Langurator::getLocalizedWords("paypal"));

		$insecure_checkout_url = Request::root().'/paypal/checkout'; 

		$this->data['secure_checkout_url'] = str_replace("http", "https", $insecure_checkout_url);

		//$this->firephp->log($this->data['secure_checkout_url'], "this->data['secure_checkout_url']");

		$this->data['new_users_email'] = Session::get('email');

		return View::make('payments', $this->data);
	}

	public function checkout() {

		if ( ( !isset( $this->paypal_configs['paypal_api_endpoint_url'] ) || ( $this->paypal_configs['paypal_api_endpoint_url'] == '' ) ) ||
		     ( !isset( $this->paypal_configs['paypal_url'] ) || ( $this->paypal_configs['paypal_url'] == '' ) ) ||
		     ( !isset( $this->paypal_configs['paypal_dg_url'] ) || ( $this->paypal_configs['paypal_dg_url'] == '' ) ) ||
		     ( !isset( $this->paypal_configs['paypal_api_username'] ) || ( $this->paypal_configs['paypal_api_username'] == '' ) ) ||
		     ( !isset( $this->paypal_configs['paypal_api_password'] ) || ( $this->paypal_configs['paypal_api_password'] == '' ) ) ||
		     ( !isset( $this->paypal_configs['paypal_api_signature'] ) || ( $this->paypal_configs['paypal_api_signature'] == '' ) ) ) {

	    		redirect('/paypal/confirm', 'location', 301);
		
		} else {

			$subscription_account_levels = $this->input->post('subscription_account_levels');
	        	//$this->firephp->log($subscription_account_levels, "subscription_account_levels");

			$currency_code = $this->input->post('currency_code');
	        	//$this->firephp->log($currency_code, "currency_code");

			//require_once('includes/php/library/paypal.inc.php');

			$PaymentOption = "PayPal";
			//$this->firephp->log($PaymentOption, "PaymentOption");

			if ($PaymentOption == "PayPal") {
				
			        // ==================================
			        // PayPal Express Checkout Module
			        // ==================================

			
			        
		        	//'------------------------------------
		        	//' The paymentAmount is the total value of 
		        	//' the purchase.
		        	//'
		        	//' TODO: Enter the total Payment Amount within the quotes.
		        	//' example : $paymentAmount = "15.00";
		        	//'------------------------------------

		        	// default to $10
		        	$paymentAmount = "4.99";
		        	$itemName = "$".$paymentAmount."/month for 50 GB of storage";
		        
		        	switch($subscription_account_levels) {
	
			                case 'MUSOTIC100GB' :
			                	$paymentAmount = "9.99";
			                	$itemName = "$".$paymentAmount."/month for 100 GB of storage";
			                	break;
		                	case 'MUSOTIC200GB' :
		                		$paymentAmount = "19.99";
		                		$itemName = "$".$paymentAmount."/month for 200 GB of storage";
		                		break;
		                	default:
		                		$paymentAmount = "4.99";
		                		$itemName = "$".$paymentAmount."/month for 50 GB of storage";
		                		break;
		        	}

		        	//$this->firephp->log($subscription_account_levels, "subscription_account_levels");
		        	//$this->firephp->log($paymentAmount, "paymentAmount");
		        	//$this->firephp->log($itemName, "itemName");

		        	//'------------------------------------
		        	//' The currencyCodeType  
		        	//' is set to the selections made on the Integration Assistant 
		        	//'------------------------------------
		        	$currencyCodeType = "USD";
		        	$paymentType = "Sale";

		        	//'------------------------------------
		        	//' The returnURL is the location where buyers return to when a
		        	//' payment has been succesfully authorized.
		        	//'
		        	//' This is set to the value entered on the Integration Assistant 
		        	//'------------------------------------
		        	$returnURL = $this->paypal_configs['paypal_return_url'];

		        	//'------------------------------------
		        	//' The cancelURL is the location buyers are sent to when they hit the
		        	//' cancel button during authorization of payment during the PayPal flow
		        	//'
		        	//' This is set to the value entered on the Integration Assistant 
		        	//'------------------------------------
		        	$cancelURL = $this->paypal_configs['paypal_cancel_url'];

		        	//'------------------------------------
		        	//' Calls the SetExpressCheckout API call
		        	//'
		        	//' The CallSetExpressCheckout function is defined in the file PayPalFunctions.php,
		        	//' it is included at the top of this file.
		        	//'-------------------------------------------------

		        
				$items = array();
				$items[] = array('name' => $itemName, 'amt' => $paymentAmount, 'qty' => 1);
			
				//::ITEMS::
				
				// to add anothe item, uncomment the lines below and comment the line above 
				// $items[] = array('name' => 'Item Name1', 'amt' => $itemAmount1, 'qty' => 1);
				// $items[] = array('name' => 'Item Name2', 'amt' => $itemAmount2, 'qty' => 1);
				// $paymentAmount = $itemAmount1 + $itemAmount2;
				
				// assign corresponding item amounts to "$itemAmount1" and "$itemAmount2"
				// NOTE : sum of all the item amounts should be equal to payment  amount 

				require_once('includes/php/library/paypal.inc.php');

				$resArray = SetExpressCheckoutDG(	$paymentAmount, 
									$currencyCodeType, 
									$paymentType, 
									$returnURL, 
									$cancelURL, 
									$items, 
									$this->paypal_configs, 
									$this->firephp	);

				//var_dump($resArray);

				//$this->firephp->log($resArray, "resArray");

		        	$ack = strtoupper($resArray["ACK"]);

		        	if ($ack == "SUCCESS" || $ack == "SUCCESSWITHWARNING") {
		        	
		        		$token = urldecode($resArray["TOKEN"]);
		            
		        		//echo "token: ".$token."<br />";
		        		//exit();

		            		RedirectToPayPal($token, $this->paypal_configs['paypal_url']);
		            		//RedirectToPayPalDG($token, $this->paypal_configs['paypal_dg_url']);
		        
		        	} else {

	                		//Display a user friendly Error on the page using any of the following error information returned by PayPal
	                		$ErrorCode = urldecode($resArray["L_ERRORCODE0"]);
	                		$ErrorShortMsg = urldecode($resArray["L_SHORTMESSAGE0"]);
	                		$ErrorLongMsg = urldecode($resArray["L_LONGMESSAGE0"]);
	                		$ErrorSeverityCode = urldecode($resArray["L_SEVERITYCODE0"]);
	                
	                		echo "SetExpressCheckout API call failed. ";
	                		echo "Detailed Error Message: " . $ErrorLongMsg;
	                		echo "Short Error Message: " . $ErrorShortMsg;
	                		echo "Error Code: " . $ErrorCode;
	                		echo "Error Severity Code: " . $ErrorSeverityCode;
		        	}
			}
		}
	}

	public function confirm() {

		if ( ( !isset( $this->paypal_configs['paypal_api_endpoint_url'] ) || ( $this->paypal_configs['paypal_api_endpoint_url'] == '' ) ) ||
		     ( !isset( $this->paypal_configs['paypal_url'] ) || ( $this->paypal_configs['paypal_url'] == '' ) ) ||
		     ( !isset( $this->paypal_configs['paypal_dg_url'] ) || ( $this->paypal_configs['paypal_dg_url'] == '' ) ) ||
		     ( !isset( $this->paypal_configs['paypal_api_username'] ) || ( $this->paypal_configs['paypal_api_username'] == '' ) ) ||
		     ( !isset( $this->paypal_configs['paypal_api_password'] ) || ( $this->paypal_configs['paypal_api_password'] == '' ) ) ||
		     ( !isset( $this->paypal_configs['paypal_api_signature'] ) || ( $this->paypal_configs['paypal_api_signature'] == '' ) ) ) {

	    		$this->data['error_name_i18n'] 		= $this->lang->line('configuration_error_name');
	    		$this->data['error_description_i18n'] 	= $this->lang->line('configuration_error_description');

	    		$this->data['view'] = 'error';
		
		} else {

			$this->data['new_users_email'] = $this->session->userdata('email');

			$token = $this->input->get('token');
			$this->firephp->log($token, "token");

			$payerID = $this->input->get('PayerID');
			$this->firephp->log($payerID, "payerID");

			require_once('includes/php/library/paypal.inc.php');

			$PaymentOption = "PayPal";

			if ( $PaymentOption == "PayPal" ) {

			    /*
			     '------------------------------------
			     ' this  step is required to get parameters to make DoExpressCheckout API call, 
			     ' this step is required only if you are not storing the SetExpressCheckout API call's request values in you database.
			     ' ------------------------------------
			     */
			    $res = GetExpressCheckoutDetails($token, $this->paypal_configs, $this->firephp);
			    
			    $this->firephp->log($res, "GetExpressCheckoutDetails res");

			    /*
			     '------------------------------------
			     ' The paymentAmount is the total value of
			     ' the purchase. 
			     '------------------------------------
			     */

			    $finalPaymentAmount =  $res["AMT"];

			    /*
			     '------------------------------------
			     ' Calls the DoExpressCheckoutPayment API call
			     '
			     ' The ConfirmPayment function is defined in the file PayPalFunctions.php,
			     ' that is included at the top of this file.
			     '-------------------------------------------------
			     */

			    //Format the  parameters that were stored or received from GetExperessCheckout call.
			    $paymentType        = 'Sale';

			    $currencyCodeType   = $res['CURRENCYCODE'];

			    $resArray = CreateRecurringPaymentsProfile($token, $paymentType, $currencyCodeType, $payerID, $finalPaymentAmount, $this->paypal_configs, $this->firephp);
			    
			    $this->firephp->log($resArray, "resArray");

			    $ack = strtoupper($resArray["ACK"]);
			    
			    if( $ack == "SUCCESS" || $ack == "SUCCESSWITHWARNING" ) {

			    	//$this->data['subscription_plan_i18n']			= $this->lang->line('');
				$this->data['payment_complete_i18n'] 			= $this->lang->line('paypal_payment_complete');
				$this->data['payment_complete_message_first_i18n'] 	= $this->lang->line('paypal_payment_complete_message_first');
				$this->data['payment_complete_message_second_i18n']	= $this->lang->line('paypal_payment_complete_message_second');

				// Unique transaction ID of the payment.
			        $transactionId      = $resArray["PAYMENTINFO_0_TRANSACTIONID"];
				
				// The type of transaction Possible values: l  cart l  express-checkout
			        $transactionType    = $resArray["PAYMENTINFO_0_TRANSACTIONTYPE"];

				// Indicates whether the payment is instant or delayed. Possible values: l  none l  echeck l  instant
			        $paymentType        = $resArray["PAYMENTINFO_0_PAYMENTTYPE"];

				// Time/date stamp of payment 
			        $orderTime          = $resArray["PAYMENTINFO_0_ORDERTIME"];

				// The final amount charged, including any  taxes from your Merchant Profile.
			        $amt                = $resArray["PAYMENTINFO_0_AMT"]; 

				// 3-character currency code for one of the currencies listed in PayPay-Supported Transactional Currencies. Default: USD.
			        $currencyCode       = $resArray["PAYMENTINFO_0_CURRENCYCODE"];

				// PayPal fee amount charged for the transaction 
			        $feeAmt             = $resArray["PAYMENTINFO_0_FEEAMT"];

				// Tax charged on the transaction.
			        $taxAmt             = $resArray["PAYMENTINFO_0_TAXAMT"];

			        $paymentStatus = $resArray["PAYMENTINFO_0_PAYMENTSTATUS"];

			        $pendingReason = $resArray["PAYMENTINFO_0_PENDINGREASON"];

			        $reasonCode = $resArray["PAYMENTINFO_0_REASONCODE"];
			    
			        $this->data['view'] = 'complete';

			    } else {

			    	$this->data['error_name_i18n'] 		= $this->lang->line('error_name');
			    	$this->data['error_description_i18n'] 	= $this->lang->line('error_description');

			    	$this->data['view'] = 'error';
			    }
			}
			// The view that should be loaded into the template
			//$this->data['view'] = 'registration_success';
		}

		$this->load->view('templates/base.php', $this->data);
	}

	public function error() {

    		$this->data['error_name_i18n'] 		= $this->lang->line('error_name');
    		$this->data['error_description_i18n'] 	= $this->lang->line('error_description');
	
    		$this->data['view'] = 'error';

    		$this->load->view('templates/base.php', $this->data);
	}

	public function cancel() {

		$this->data['paypal_payment_cancelled_name_i18n'] 		= $this->lang->line('paypal_payment_cancelled_name'); 
		$this->data['paypal_payment_cancelled_description_i18n'] 	= $this->lang->line('paypal_payment_cancelled_description'); 
	
		$this->data['view'] = 'cancel';

		$this->load->view('templates/base.php', $this->data);
	}

	public function direct_payments() {

		$this->data['registration_complete_i18n'] 		= $this->lang->line('registration_complete');
		$this->data['success_message_first_half_i18n'] 		= $this->lang->line('success_message_first_half');
		$this->data['success_message_second_half_i18n'] 	= $this->lang->line('success_message_second_half');
	
		$this->data['credit_card_type_i18n']			= "Credit Card Type";
		$this->data['credit_card_number_i18n']			= "Credit Card Number";
		$this->data['credit_card_expiration_date_i18n']		= "Expires";
		$this->data['credit_card_ccv_i18n']			= "CCV";

		$this->data['subscription_account_level_i18n']		= "Account Level";
		$this->data['agree_to_terms_and_conditions_i18n']	= "I agree to the terms and conditions";

		$this->data['credit_card_types']['visa'] 		= "Visa";
		$this->data['credit_card_types']['mastercard'] 		= "Mastercard";
		$this->data['credit_card_types']['discover'] 		= "Discover";
		$this->data['credit_card_types']['amex'] 		= "American Express";

		$this->data['credit_card_expiration_months']		= array();

		for ($i=1; $i<=12; $i++) {

			$this->data['credit_card_expiration_months'][$i] = str_pad($i, 2, '0', STR_PAD_LEFT);
		}
		
		$this->data['credit_card_expiration_years'] = array();

		$current_year = date('Y', strtotime("NOW"));

		for ($i=$current_year; $i < ($current_year + 10); $i++) {

			$this->data['credit_card_expiration_years'][$i] = str_pad($i, 2, '0', STR_PAD_LEFT);
		}

		$this->data['subscription_account_levels']['MUSOTIC50GB'] 	= "50 GB Limit : $10.00/month";
		$this->data['subscription_account_levels']['MUSOTIC100GB'] 	= "100 GB Limit : $20.00/month";
		$this->data['subscription_account_levels']['MUSOTIC200GB'] 	= "200 GB Limit : $30.00/month";

		$this->data['new_users_email'] = $this->session->userdata('email');

		// The view that should be loaded into the template
		$this->data['view'] = 'direct_payments';

		$this->load->view('templates/base.php', $this->data);
	}

	// Use of this function requires a paypal payments pro account with recurring payments option $60/month
	public function direct_payment() {

		$subscription_account_level 	= $this->input->post('subscription_account_level');
		$credit_card_type 		= $this->input->post('credit_card_type');
		$credit_card_number 		= $this->input->post('credit_card_number');
		$credit_card_expiration_month 	= $this->input->post('credit_card_expiration_month');
		$credit_card_expiration_year 	= $this->input->post('credit_card_expiration_year');
		$credit_card_ccv 		= $this->input->post('credit_card_ccv');
		$agree_to_terms_and_conditions 	= $this->input->post('agree_to_terms_and_conditions');

		$this->firephp->log($subscription_account_level, "subscription_account_level");
		$this->firephp->log($credit_card_type, "credit_card_type");
		$this->firephp->log($credit_card_number, "credit_card_number");
		$this->firephp->log($credit_card_expiration_month, "credit_card_expiration_month");
		$this->firephp->log($credit_card_expiration_year, "credit_card_expiration_year");
		$this->firephp->log($credit_card_ccv, "credit_card_ccv");
		$this->firephp->log($agree_to_terms_and_conditions, "agree_to_terms_and_conditions");

	    	// default to $10
	    	$paymentAmount = "4.99";
	    	$itemName = "$".$paymentAmount."/month for 50 GB of storage";
	    
	    	switch($subscription_account_levels) {

	        	case 'MUSOTIC100GB' :
	       			$paymentAmount = "9.99";
	       			$itemName = "$".$paymentAmount."/month for 100 GB of storage";
	            		break;
	            	case 'MUSOTIC200GB' :
	            		$paymentAmount = "19.99";
	            		$itemName = "$".$paymentAmount."/month for 200 GB of storage";
	            		break;
	            	default:
	            		$paymentAmount = "4.99";
	            		$itemName = "$".$paymentAmount."/month for 50 GB of storage";
	            		break;
	    	}

		require_once('includes/php/library/paypal.inc.php');

	    	$resArray = CreateRecurringPaymentsProfileDirectly ($token, $credit_card_type, 'USD', $payerID, $paymentAmount, $this->paypal_configs, $this->firephp);
	    
	    	$this->firephp->log($resArray, "resArray");

	    	$ack = strtoupper($resArray["ACK"]);
	    
	    	if( $ack == "SUCCESS" || $ack == "SUCCESSWITHWARNING" ) {

			// Unique transaction ID of the payment.
	        	$transactionId      = $resArray["PAYMENTINFO_0_TRANSACTIONID"];

			// The type of transaction Possible values: l  cart l  express-checkout
	        	$transactionType    = $resArray["PAYMENTINFO_0_TRANSACTIONTYPE"];

			// Indicates whether the payment is instant or delayed. Possible values: l  none l  echeck l  instant
	        	$paymentType        = $resArray["PAYMENTINFO_0_PAYMENTTYPE"];

			// Time/date stamp of payment 
	        	$orderTime          = $resArray["PAYMENTINFO_0_ORDERTIME"]; 

			// The final amount charged, including any  taxes from your Merchant Profile.
	        	$amt                = $resArray["PAYMENTINFO_0_AMT"];

			// 3-character currency code for one of the currencies listed in PayPay-Supported Transactional Currencies. Default: USD.
	        	$currencyCode       = $resArray["PAYMENTINFO_0_CURRENCYCODE"];

			// PayPal fee amount charged for the transaction
	        	$feeAmt             = $resArray["PAYMENTINFO_0_FEEAMT"];
			
			// Tax charged on the transaction.
	        	$taxAmt             = $resArray["PAYMENTINFO_0_TAXAMT"];
	
	        	$paymentStatus = $resArray["PAYMENTINFO_0_PAYMENTSTATUS"];

	        	$pendingReason = $resArray["PAYMENTINFO_0_PENDINGREASON"];

	        	$reasonCode = $resArray["PAYMENTINFO_0_REASONCODE"];
	    
	    	} else {

	    	}

		$this->data['registration_complete_i18n'] 	= $this->lang->line('registration_complete');
		$this->data['success_message_first_half_i18n'] 	= $this->lang->line('success_message_first_half');
		$this->data['success_message_second_half_i18n'] = $this->lang->line('success_message_second_half');

		$this->data['new_users_email'] = $this->session->userdata('email');

		// The view that should be loaded into the template
		$this->data['view'] = 'registration_success';

		$this->load->view('templates/base.php', $this->data);
	}
}
