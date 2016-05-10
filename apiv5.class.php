<?php 

class APIv5 {
	
	public $token;
	public $apiHost;
	public $useHttps = true;
	public $port = null;
	public $autoNext = TRUE;
	
	function __construct($token, $apiHost) {
		$this->token = $token;
		$this->apiHost = $apiHost;
	}
	
	public function apiGetForList($endpoint) {

		return $this->apiGetForFilter($endpoint, null);
		
	}
	
	public function apiGetForId($endpoint, $id) {
		return $this->makeApiV5Call($endpoint . '/' . $id, 'GET');
	}
	
	public function apiGetForFilter($endpoint, $data) {

		$originalQuery = null;
		if(!empty($data)) {
			$originalQuery = $data;
		}
		$response = $this->makeApiV5Call($endpoint, 'GET', $data);
		$oneResponse = $response;
		while(!empty($oneResponse['nextOffset']) && $this->autoNext) {
			$oneResponse = $this->makeApiV5Call($endpoint, 'GET', $data, $oneResponse['nextOffset']);
			if(!empty($oneResponse['results'])) {
				foreach($oneResponse['results'] as $oneResult) {
					array_push($response['results'], $oneResult);
				}
			}	
		}
		$response['auto_next'] = $this->autoNext;
		if($this->autoNext) {
			unset($response['next']);
			unset($response['nextOffset']);
			unset($response['previous']);
			unset($response['prevOffset']);
		
		}
		return $response;
		
	}
	
	public function apiPost($endpoint, $data) {
		return $this->makeApiV5Call($endpoint, 'POST', $data);
	}
	
	public function apiDelete($endpoint, $id) {
		return $this->makeApiV5Call($endpoint . '/' . $id, 'DELETE');
	}
	
	public function apiPut($endpoint, $id, $data) {
		return $this->makeApiV5Call($endpoint . '/' . $id, 'PUT', $data);
	}
	
	private function makeApiV5Call($path, $method, $dataToSend = NULL, $offset = NULL, $limit = NULL) {					
		
		$url = $this->makeFullEndpoint($path);
		$this->debug_log("Making API v5 " . $method . " call to " . $url);

		$qarray = array();		
		
		if(strtoupper($method) == 'GET') {
			if(!empty($dataToSend)) {
				foreach($dataToSend as $dkey => $dval) {
					$qarray[$dkey] = $dval;
				}
			}
		}
		
		if(!empty($limit)) {
			$qarray['limit'] = $limit;
		}
		
		if(!empty($offset)) {
			$qarray['offset'] = $offset;
		}
		
		if(!empty($qarray)) {
			$url .= '?' . http_build_query($qarray);
		}
			
			
			
		try {
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
			curl_setopt($ch, CURLOPT_HEADER, 1);
			if(strtoupper($method) == 'POST' || strtoupper($method) == "PUT") {
				if($dataToSend) {
					$jsonToSend = json_encode($dataToSend);
					curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonToSend);
				}
			}
			// The following two lines allow self-signed and wildcard SSL certificates
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // since we are separating the headers and body anyway
	
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Accept: application/json',
			'Authorization: Bearer ' . $this->token)
			);
	
			$curlResult = curl_exec($ch);
	
			$result = array();
			$result['api_request_url'] = $url;
	
			if($curlResult == FALSE) {
				$result['error'] = curl_error($ch);
				$result['api_result'] = 'failed';
			} else {
				$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
				$headerpart = substr($curlResult, 0, $header_size);
				$body = substr($curlResult, $header_size);
				$result['response_headers'] = $this->http_parse_headers($headerpart);
	
				if($body) {
					try {
						$bodyJson = json_decode($body);
						$result['body'] = $bodyJson;
						if(isset($bodyJson->error)) {
							$result['error'] = $bodyJson->error;
						}
						if(isset($bodyJson->error_description)) {
							$result['error_description'] = $bodyJson->error_description;
						}
						if(isset($bodyJson->errors)) {
							$result['errors'] = (array)$bodyJson->errors;
						}
						if(isset($bodyJson->fieldErrors)) {
							$result['fieldErrors'] = (array)$bodyJson->fieldErrors;
						}
						if(isset($bodyJson->results)) {
							$result['results'] = (array)$bodyJson->results;
						}
						if(isset($bodyJson->previous)) {
							$result['previous'] = $bodyJson->previous;
							$prevParts = explode('?', $bodyJson->previous);
							if(count($prevParts) > 1) {
								parse_str($prevParts[1], $qarray);
								if(array_key_exists("offset", $qarray)) {
									$result['prevOffset'] = $qarray['offset'];
								}
							}
						}
						if(isset($bodyJson->next)) {
							$result['next'] = $bodyJson->next;
							$nextParts = explode('?', $bodyJson->next);
							if(count($nextParts) > 1) {
								parse_str($nextParts[1], $qarray);
								if(array_key_exists("offset", $qarray)) {
									$result['nextOffset'] = $qarray['offset'];
								}
							}
						}
					} catch(Exception $e) {
						$result['bodyexception'] = $e;
					}
				}
			}
	
	
			$httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			$result['http_status_code'] = $httpStatus;
	
			switch(strtoupper($method)) {
				case 'POST':
					if($httpStatus == '201') {
						$result['api_result'] = 'success';
					} else {
						$result['api_result'] = 'failed';
					}
					break;
				case 'PUT':
				case 'DELETE':
					if($httpStatus == '204') {
						$result['api_result'] = 'success';
					} else {
						$result['api_result'] = 'failed';
					}
					break;
				default:
					if($httpStatus == '200') {
						$result['api_result'] = 'success';
					} else {
						$result['api_result'] = 'failed';
					}
			}
	
			// result vs results
			if($result['api_result'] == 'success' && !isset($result['results']) && isset($result['body'])) {
				$result['results'] = array();
				$result['results'][] = (array)$result['body'];
			}
	
			unset($result['body']);
				
			return $result;
		} catch(Exception $curlEx) {
			$this->debug_log("An exception occurred during API v5 call: " . $curlEx->getMessage());
			$result = array();
			$result['api_result'] = 'failed';
			$result['error'] = $curlEx->getMessage();
			return $result;
		}
	}
	
	function http_parse_headers( $header )
	{
		$retVal = array();
		$fields = explode("\r\n", preg_replace('/\x0D\x0A[\x09\x20]+/', ' ', $header));
		foreach( $fields as $field ) {
			if( preg_match('/([^:]+): (.+)/m', $field, $match) ) {
				$match[1] = preg_replace('/(?<=^|[\x09\x20\x2D])./e', 'strtoupper("\0")', strtolower(trim($match[1])));
				if( isset($retVal[$match[1]]) ) {
					$retVal[$match[1]] = array($retVal[$match[1]], $match[2]);
				} else {
					$retVal[$match[1]] = trim($match[2]);
				}
			}
		}
		return $retVal;
	}	
	
	private function makeFullEndpoint($endpoint) {
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
		
		$result .= '/dc/api/v5/';
		
		$result .= $endpoint;
		
		return $result;
		
	}
	
	private function debug_log($message) {
		echo "DEBUG: " . $message . '</br>';
	}	
	
}

?>