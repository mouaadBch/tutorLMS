<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ccavenue_model extends CI_Model {

	function __construct()
	{
	    parent::__construct();
 	}

	function check_ccavenue_payment($identifier = ""){
		if($payment_details['is_instructor_payout_user_id'] > 0){
			$instructor_details = $this->user_model->get_all_user($payment_details['is_instructor_payout_user_id'])->row_array();
			$keys = json_decode($instructor_details['payment_keys'], true);
			$keys = $keys[$payment_gateway['identifier']];
		}else{
			$keys = json_decode($payment_gateway['keys'], true);
		}

		define("CCA_WORKING_KEY", $keys['ccavenue_working_key']);
		error_reporting ( 0 );
		$order_status = "";
		$workingKey = CCA_WORKING_KEY; // Working Key should be provided here.
		$encResponse = $encResp; // This is the response sent by the CCAvenue Server
		$rcvdString = $this->decrypt($encResponse, $workingKey);//Crypto Decryption used as per the specified working key.
		$decryptValues = explode ( '&', $rcvdString );
		$dataSize = sizeof ( $decryptValues );

		for($i = 0; $i < $dataSize; $i++) 
		{
			$information=explode('=',$decryptValues[$i]);
			if($i==3)	$order_status=$information[1];
		}

		if($order_status==="Success"){
			return true;
		}else{
			return false;
		}
	}

	/*
	* @param1 : Plain String
	* @param2 : Working key provided by CCAvenue
	* @return : Decrypted String
	*/
	function encrypt($plainText,$key)
	{
		$key = $this->hextobin(md5($key));
		$initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
		$openMode = openssl_encrypt($plainText, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $initVector);
		$encryptedText = bin2hex($openMode);
		return $encryptedText;
	}

	/*
	* @param1 : Encrypted String
	* @param2 : Working key provided by CCAvenue
	* @return : Plain String
	*/
	function decrypt($encryptedText,$key)
	{
		$key = $this->hextobin(md5($key));
		$initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
		$encryptedText = $this->hextobin($encryptedText);
		$decryptedText = openssl_decrypt($encryptedText, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $initVector);
		return $decryptedText;
	}

	function hextobin($hexString) 
	 { 
		$length = strlen($hexString); 
		$binString="";   
		$count=0; 
		while($count<$length) 
		{       
		    $subString =substr($hexString,$count,2);           
		    $packedString = pack("H*",$subString); 
		    if ($count==0)
		    {
				$binString=$packedString;
		    } 
		    
		    else 
		    {
				$binString.=$packedString;
		    } 
		    
		    $count+=2; 
		} 
	        return $binString; 
	  } 
}