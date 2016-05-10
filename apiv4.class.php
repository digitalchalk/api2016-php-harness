<?php

class APIv4 {
	
	public static $SERVICE_NAME = 'v4';
	
	private $apiHost;
	private $public_key;
	private $private_key;
	private $useHttps = true;
	private $port = null;
	
	public function __construct($public_key, $private_key, $apiHost, $useHttps = TRUE, $port = NULL) {
		$this->public_key = $public_key;
		$this->private_key = $private_key;
		$this->useHttps = $useHttps;
		$this->port = $port;
		$this->apiHost = $apiHost;
	}
	
	public function getAvailableOfferings($includeDeliveryFee = false) {
		if($includeDeliveryFee) {
			return $this->getSOAPResponse('getAvailableOfferings', array('includeDeliveryFee' => '1'));
		} else {
			return $this->getSOAPResponse('getAvailableOfferings');
		}		
	}
	
	public function doesUserExist($username) {
		return $this->getSOAPResponse('doesUserExist', array('username' => $username));
	}
	
	public function createUser($user) {
		return $this->getSOAPResponse('createUser', $user);
	}
	
	private function getSOAPResponse($operationName, $dataToSend = NULL) {
		$wsdl_opts = $this->makeWsdlOpts();
		$wsdl_url = $this->makeWsdlEndpoint();
		
		$timestamp = $this->makeTimestamp();
		$signature = $this->makeSignature($operationName, $timestamp);
		
		$auth = array(
			'accessKey' => $this->public_key,
			'timestamp' => $timestamp,
			'signature' => $signature 	
		);
		
		$parameters = array('auth' => $auth);
		if(!empty($dataToSend)) {
			foreach($dataToSend as $dkey => $dval) {
				$parameters[$dkey] = $dval;
			}
		}
		
		try {
			$client = new SoapClient($wsdl_url, $wsdl_opts);
			$result = $client->$operationName($parameters);
			return $result;
		} catch(SoapFault $soapFault) {
			return $soapFault;
		}
		
		
	}
	
	private function makeTimestamp() {
		$timestamp = gmdate('c'); // RFC 8601 date format
		// Fix for some PHP's that use +00:00 for GMT
		$timestamp = str_replace('+00:00', 'Z', $timestamp);
		return $timestamp;
	}
	
	private function makeSignature($operation_name, $timestamp) {
		$toSign = self::$SERVICE_NAME . $operation_name . $timestamp;
		return $this->encodeSignature($toSign, $this->private_key);
	}
	
	private function encodeSignature($stringToSign, $key) {
    	$binary_hmac = pack("H40", hash_hmac('sha1', trim($stringToSign), $key));
    	return base64_encode($binary_hmac);
    }
    
    private function makeWsdlOpts() {
    	return array(
				'ssl' => array( 'allow_self_signed' => true ),
				'trace' => 1,
				'exceptions' => 1
		);
    }
    
    private function makeWsdlEndpoint() {
    	$result = '';
    	if($this->useHttps) {
    		$result = 'https://';
    	} else {
    		$result = 'http://';
    	}
    
    	$result .= $this->apiHost;
    
    	if(!empty($this->port)) {
    		$result .= ':' . $this->port;
    	}
    
    	$result .= '/dc/api/v4.wsdl';
    
    	return $result;
    
    }    
	
}