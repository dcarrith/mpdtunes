<?php

	/* An express checkout transaction starts with a token, that
	   identifies to PayPal your transaction
	   In this example, when the script sees a token, the script
	   knows that the buyer has already authorized payment through
	   paypal.  If no token was found, the action is to send the buyer
	   to PayPal to first authorize payment
	   */

	/*   
	'-------------------------------------------------------------------------------------------------------------------------------------------
	' Purpose: 	Prepares the parameters for the SetExpressCheckout API Call for a Digital Goods payment.
	' Inputs:  
	'		paymentAmount:  	Total value of the shopping cart
	'		currencyCodeType: 	Currency code value the PayPal API
	'		paymentType: 		paymentType has to be one of the following values: Sale or Order or Authorization
	'		returnURL:			the page where buyers return to after they are done with the payment review on PayPal
	'		cancelURL:			the page where buyers return to when they cancel the payment review on PayPal
	'--------------------------------------------------------------------------------------------------------------------------------------------	
	*/
	function SetExpressCheckoutDG($paymentAmount, $currencyCodeType, $paymentType, $returnURL, $cancelURL, $items, $paypal_configs, $firephp) {

		//$firephp->log($items, "items");

		$nvpstr = "&RETURNURL=" . $returnURL;
		$nvpstr .= "&CANCELURL=" . $cancelURL;
		$nvpstr .= "&REQCONFIRMSHIPPING=0";
		$nvpstr .= "&NOSHIPPING=1";
		$nvpstr .= "&PAYMENTREQUEST_0_CURRENCYCODE=" . $currencyCodeType;
		$nvpstr .= "&PAYMENTREQUEST_0_AMT=" . $items[0]['amt'];
		$nvpstr .= "&PAYMENTREQUEST_0_ITEMAMT=" . $items[0]['amt'];
		$nvpstr .= "&PAYMENTREQUEST_0_TAXAMT=0";
		$nvpstr .= "&PAYMENTREQUEST_0_DESC==" . $items[0]['name'];
		$nvpstr .= "&PAYMENTREQUEST_0_PAYMENTACTION=" . $paymentType;
		$nvpstr .= "&L_PAYMENTREQUEST_0_ITEMCATEGORY0=Digital";
		$nvpstr .= "&L_PAYMENTREQUEST_0_NAME0=" . $items[0]['name'];
		$nvpstr .= "&L_PAYMENTREQUEST_0_QTY0=" . $items[0]['qty'];
		$nvpstr .= "&L_PAYMENTREQUEST_0_AMT0=" . $items[0]['amt'];
		$nvpstr .= "&L_PAYMENTREQUEST_0_DESC0=" . $items[0]['name'];
		$nvpstr .= "&L_BILLINGAGREEMENTDESCRIPTION0=Musotic Monthly Subscription";
		$nvpstr .= "&L_BILLINGTYPE0=RecurringPayments";
	
		//$firephp->log($nvpstr, "nvpstr");

		//$firephp->log($paypal_configs, "Inside SetExpressCheckoutDG: paypal_configs");

		//'--------------------------------------------------------------------------------------------------------------- 
		//' Make the API call to PayPal
		//' If the API call succeded, then redirect the buyer to PayPal to begin to authorize payment.  
		//' If an error occured, show the resulting errors
		//'---------------------------------------------------------------------------------------------------------------
	    $resArray = hash_call("SetExpressCheckout", $nvpstr, $paypal_configs, $firephp);

	    //$firephp->log($resArray, "resArray");

		$ack = strtoupper($resArray["ACK"]);

		if ($ack == "SUCCESS" || $ack == "SUCCESSWITHWARNING") {
			
			$token = urldecode($resArray["TOKEN"]);
			$_SESSION['TOKEN'] = $token;
		}
		   
	    return $resArray;
	}
	
	/*
	'-------------------------------------------------------------------------------------------
	' Purpose: 	Prepares the parameters for the GetExpressCheckoutDetails API Call.
	'
	' Inputs:  
	'		None
	' Returns: 
	'		The NVP Collection object of the GetExpressCheckoutDetails Call Response.
	'-------------------------------------------------------------------------------------------
	*/
	function GetExpressCheckoutDetails($token, $paypal_configs, $firephp) {
		//'--------------------------------------------------------------
		//' At this point, the buyer has completed authorizing the payment
		//' at PayPal.  The function will call PayPal to obtain the details
		//' of the authorization, incuding any shipping information of the
		//' buyer.  Remember, the authorization is not a completed transaction
		//' at this state - the buyer still needs an additional step to finalize
		//' the transaction
		//'--------------------------------------------------------------
	   
	    //'---------------------------------------------------------------------------
		//' Build a second API request to PayPal, using the token as the
		//'  ID to get the details on the payment authorization
		//'---------------------------------------------------------------------------
	    $nvpstr = "&TOKEN=" . $token;

	    //$firephp->log($nvpstr, "nvpstr");

	    //$firephp->log($paypal_configs, "Inside GetExpressCheckoutDetails: paypal_configs");

		//'---------------------------------------------------------------------------
		//' Make the API call and store the results in an array.  
		//'	If the call was a success, show the authorization details, and provide
		//' 	an action to complete the payment.  
		//'	If failed, show the error
		//'---------------------------------------------------------------------------
	    $resArray = hash_call("GetExpressCheckoutDetails", $nvpstr, $paypal_configs, $firephp);
	    
	    //$firephp->log($resArray, "resArray");

	    $ack = strtoupper($resArray["ACK"]);
		
		if($ack == "SUCCESS" || $ack=="SUCCESSWITHWARNING") {	
		
			return $resArray;
		
		} else {
		
			return false;
		}
	}
	
	/*
	'-------------------------------------------------------------------------------------------------------------------------------------------
	' Purpose: 	Prepares the parameters for the GetExpressCheckoutDetails API Call.
	'
	' Inputs:  
	'		sBNCode:	The BN code used by PayPal to track the transactions from a given shopping cart.
	' Returns: 
	'		The NVP Collection object of the GetExpressCheckoutDetails Call Response.
	'--------------------------------------------------------------------------------------------------------------------------------------------	
	*/
	function ConfirmPayment( $token, $paymentType, $currencyCodeType, $payerID, $FinalPaymentAmt )
	{
		/* Gather the information to make the final call to
		   finalize the PayPal payment.  The variable nvpstr
		   holds the name value pairs
		   */
		$token 				= urlencode($token);
		$paymentType 		= urlencode($paymentType);
		$currencyCodeType 	= urlencode($currencyCodeType);
		$payerID 			= urlencode($payerID);
		$serverName 		= urlencode($_SERVER['SERVER_NAME']);

		$nvpstr  = '&TOKEN=' . $token . '&PAYERID=' . $payerID . '&PAYMENTREQUEST_0_PAYMENTACTION=' . $paymentType . '&PAYMENTREQUEST_0_AMT=' . $FinalPaymentAmt;
		$nvpstr .= '&PAYMENTREQUEST_0_CURRENCYCODE=' . $currencyCodeType . '&IPADDRESS=' . $serverName; 

		 /* Make the call to PayPal to finalize payment
		    If an error occured, show the resulting errors
		    */
		$resArray=hash_call("DoExpressCheckoutPayment", $nvpstr);

		/* Display the API response back to the browser.
		   If the response from PayPal was a success, display the response parameters'
		   If the response was an error, display the errors received using APIError.php.
		   */
		$ack = strtoupper($resArray["ACK"]);

		return $resArray;
	}

	/*
	'-------------------------------------------------------------------------------------------------------------------------------------------
	' Purpose: 	Prepares the parameters for the GetExpressCheckoutDetails API Call.
	'
	' Inputs:  
	'		sBNCode:	The BN code used by PayPal to track the transactions from a given shopping cart.
	' Returns: 
	'		The NVP Collection object of the GetExpressCheckoutDetails Call Response.
	'--------------------------------------------------------------------------------------------------------------------------------------------	
	*/
	function CreateRecurringPaymentsProfile($token, $paymentType, $currencyCodeType, $payerID, $FinalPaymentAmt, $paypal_configs, $firephp)
	{
		$token 				= urlencode($token);
		$paymentType 		= urlencode($paymentType);
		$currencyCodeType 	= urlencode($currencyCodeType);
		$payerID 			= urlencode($payerID);
		$serverName 		= urlencode($_SERVER['SERVER_NAME']);

		$profileStartDate 	= urlencode(date("Y-m-d", strtotime("NOW")) . 'T00:00:00Z');

		$billingPeriod 		= urlencode('Month');
		$billingFrequency 	= urlencode(1);

		$profileDesc   		= urlencode("Musotic Monthly Subscription");

		$nvpstr  = "&TOKEN=".$token;
		$nvpstr .= "&AMT=".$FinalPaymentAmt."&CURRENCYCODE=".$currencyCodeType;
		$nvpstr .= "&PROFILESTARTDATE=".$profileStartDate;
		$nvpstr .= "&BILLINGPERIOD=".$billingPeriod."&BILLINGFREQUENCY=".$billingFrequency;
		$nvpstr .= "&AUTOBILLAMT=AddToNextBilling"."&DESC=".$profileDesc;
		$nvpstr .= "&BILLINGTYPE=RecurringPayments&L_BILLINGTYPE0=RecurringPayments";

		//$firephp->log($nvpstr, "nvpstr");

	    //$firephp->log($paypal_configs, "Inside CreateRecurringPaymentsProfile: paypal_configs");

		/* Make the API call to PayPal, using API signature.
		   The API response is stored in an associative array called $resArray */
		$resArray = hash_call("CreateRecurringPaymentsProfile", $nvpstr, $paypal_configs, $firephp);

		/* Display the API response back to the browser.
		   If the response from PayPal was a success, display the response parameters'
		   If the response was an error, display the errors received using APIError.php.
		   */
		$ack = strtoupper($resArray["ACK"]);

		return $resArray;
	}


	/*
	'-------------------------------------------------------------------------------------------------------------------------------------------
	' Purpose: 	Prepares the parameters for the GetExpressCheckoutDetails API Call.
	'
	' Inputs:  
	'		sBNCode:	The BN code used by PayPal to track the transactions from a given shopping cart.
	' Returns: 
	'		The NVP Collection object of the GetExpressCheckoutDetails Call Response.
	'--------------------------------------------------------------------------------------------------------------------------------------------	
	*/
	function CreateRecurringPaymentsProfileDirectly($token, $paymentType, $currencyCodeType, $payerID, $FinalPaymentAmt, $paypal_configs, $firephp)
	{
		/**
		 * Get required parameters from the web form for the request
		 */
		$paymentType =urlencode($paymentType);
		$firstName =urlencode("Dave");
		$lastName =urlencode("Carrithers");
		$creditCardType =urlencode("Visa");
		$creditCardNumber = urlencode('4232169085032873');
		$expDateMonth =urlencode(03);

		// Month must be padded with leading zero
		$padDateMonth = str_pad($expDateMonth, 2, '0', STR_PAD_LEFT);

		$expDateYear =urlencode(2017);
		$cvv2Number = urlencode(926);
		$address1 = urlencode($_POST['address1']);
		$address2 = urlencode($_POST['address2']);
		$city = urlencode($_POST['city']);
		$state =urlencode( $_POST['state']);
		$zip = urlencode($_POST['zip']);
		$amount = urlencode($FinalPaymentAmt);
		$currencyCode="USD";

		$token 				= urlencode($token);
		$paymentType 		= urlencode($paymentType);
		$currencyCodeType 	= urlencode($currencyCodeType);
		$payerID 			= urlencode($payerID);
		$serverName 		= urlencode($_SERVER['SERVER_NAME']);

		$profileStartDate 	= urlencode(date("Y-m-d", strtotime("NOW")) . 'T00:00:00Z');

		$billingPeriod 		= urlencode('Month');
		$billingFrequency 	= urlencode(1);

		$profileDesc   		= urlencode("Musotic 50GB per Month");

		/* Construct the request string that will be sent to PayPal.
		   The variable $nvpstr contains all the variables and is a
		   name value pair string with & as a delimiter */
		$nvpstr = "&AMT=".$amount."&CREDITCARDTYPE=".$creditCardType."&ACCT=".$creditCardNumber;
		$nvpstr .= "&EXPDATE=".$padDateMonth.$expDateYear."&CVV2=".$cvv2Number.'&PAYERID=' . $payerID;
		$nvpstr .= "&FIRSTNAME=".$firstName."&LASTNAME=".$lastName."&STREET=".$address1."&CITY=".$city."&STATE=".$state;
		$nvpstr .= "&ZIP=".$zip."&COUNTRYCODE=US&CURRENCYCODE=".$currencyCode."&PROFILESTARTDATE=".$profileStartDate;
		$nvpstr .= "&DESC=".$profileDesc."&BILLINGPERIOD=".$billingPeriod."&BILLINGFREQUENCY=".$billingFrequency;

		//$firephp->log($nvpstr, "nvpstr");
		
		 /* Make the call to PayPal to finalize payment
		    If an error occured, show the resulting errors
		    */
		$resArray = hash_call ("CreateRecurringPaymentsProfile", $nvpstr, $paypal_configs, $firephp);

		/* Display the API response back to the browser.
		   If the response from PayPal was a success, display the response parameters'
		   If the response was an error, display the errors received using APIError.php.
		   */
		$ack = strtoupper($resArray["ACK"]);

		return $resArray;
	}

	/**
	  '-------------------------------------------------------------------------------------------------------------------------------------------
	  * hash_call: Function to perform the API call to PayPal using API signature
	  * @methodName is name of API  method.
	  * @nvpStr is nvp string.
	  * returns an associtive array containing the response from the server.
	  '-------------------------------------------------------------------------------------------------------------------------------------------
	*/
	function hash_call($methodName, $nvpStr, $paypal_configs, $firephp) {

		//$firephp->log($paypal_configs, "Inside hash_call: paypal_configs");

		//var_dump($paypal_configs);

		//echo "initializing the curl calls<br />";

		$ch = null;

		//echo "calling curl_init with the parameter: ".$paypal_configs['paypal_api_endpoint_url']."<br />";


		try {

			if (!function_exists('curl_init')) {

				echo 'cURL is not installed!<br />';
			
			} else {

				echo 'cURL seems to be installed.<br />';
			} 
	
			if (!($ch = @curl_init())) exit('cannot init curl<br />');

		} catch (Exception $e) {

			//echo 'Caught exception: ' . $e->getMessage() . '<br />';
		}

		//setting the curl parameters.
		/*if ($ch = curl_init($paypal_configs['paypal_api_endpoint_url'])) {

			echo "curl_init complete.  cURL handle: ".$ch."<br />";

		} else {

			echo "curl_init failed.  cURL handle: ".$ch."<br />";
		}*/

		//echo "calling curl_setopt(ch, CURLOPT_URL, ".$paypal_configs['paypal_api_endpoint_url']."<br />";
		curl_setopt($ch, CURLOPT_URL, $paypal_configs['paypal_api_endpoint_url']);
		//echo "calling curl_setopt(ch, CURLOPT_VERBOSE, 1)<br />";
		curl_setopt($ch, CURLOPT_VERBOSE, 1);

		//turning off the server and peer verification(TrustManager Concept).
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_POST, 1);
		
	    //if USE_PROXY constant set to TRUE in Constants.php, then only proxy will be enabled.
	   //Set proxy name to PROXY_HOST and port number to PROXY_PORT in constants.php 
		if($paypal_configs['paypal_use_proxy']) {
			curl_setopt ($ch, CURLOPT_PROXY, $paypal_configs['paypal_proxy_host']. ":" . $paypal_configs['paypal_proxy_port']); 
		}

		//NVPRequest for submitting to server
		$nvpreq = "USER=" . urlencode($paypal_configs['paypal_api_username']) . "&PWD=" . urlencode($paypal_configs['paypal_api_password']) . "&SIGNATURE=" . urlencode($paypal_configs['paypal_api_signature']);
		$nvpreq .= $nvpStr;
		$nvpreq .= "&METHOD=" . urlencode($methodName) . "&VERSION=" . urlencode($paypal_configs['paypal_version']);

		//echo "set up the nvpreq string<br />";

		//$firephp->log($nvpreq, "inside hash_call: nvpreq");

		//setting the nvpreq as POST FIELD to curl
		curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);

		//getting response from server
		$response = curl_exec($ch);

		//echo "curl response: ".$response."<br />";

		//$firephp->log($response, "inside hash_call: curl response");

		//convrting NVPResponse to an Associative Array
		$nvpResArray = deformatNVP($response);
		$nvpReqArray = deformatNVP($nvpreq);

		$_SESSION['nvpReqArray'] = $nvpReqArray;

		if (curl_errno($ch)) {

			// moving to display page to display curl errors
			$_SESSION['curl_error_no']=curl_errno($ch) ;
			$_SESSION['curl_error_msg']=curl_error($ch);

			//Execute the Error handling module to display errors. 
		
		} else {
		
			 //closing the curl
		  	curl_close($ch);
		}

		return $nvpResArray;
	}

	/*'----------------------------------------------------------------------------------
	 Purpose: Redirects to PayPal.com site.
	 Inputs:  NVP string.
	 Returns: 
	----------------------------------------------------------------------------------
	*/
	function RedirectToPayPal ($token, $paypal_url) {
		
		// Redirect to paypal.com here
		$payPalURL = $paypal_url . $token;
		header("Location: ".$payPalURL);
	}
	
	function RedirectToPayPalDG ($token, $paypal_dg_url) {

		// Redirect to paypal.com here
		$payPalURL = $paypal_dg_url . $token;
		header("Location: ".$payPalURL);
	}


	
	/*'----------------------------------------------------------------------------------
	 * This function will take NVPString and convert it to an Associative Array and it will decode the response.
	  * It is usefull to search for a particular key and displaying arrays.
	  * @nvpstr is NVPString.
	  * @nvpArray is Associative Array.
	   ----------------------------------------------------------------------------------
	  */
	function deformatNVP($nvpstr) {

		$intial=0;
	 	$nvpArray = array();

		while(strlen($nvpstr))
		{
			//postion of Key
			$keypos = strpos($nvpstr,'=');
			//position of value
			$valuepos = strpos($nvpstr,'&') ? strpos($nvpstr,'&'): strlen($nvpstr);

			/*getting the Key and Value values and storing in a Associative Array*/
			$keyval = substr($nvpstr,$intial,$keypos);
			$valval = substr($nvpstr,$keypos+1,$valuepos-$keypos-1);
			//decoding the respose
			$nvpArray[urldecode($keyval)] = urldecode( $valval);
			$nvpstr = substr($nvpstr,$valuepos+1,strlen($nvpstr));
	    }

		return $nvpArray;
	}

?>