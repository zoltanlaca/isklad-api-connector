<?php
/**
* @package IskladRestApi
* @author Zoltán Laca
* @version v1
*/

class IskladRestApi
{
	
	private static $apiUrl = 'https://api.isklad-egon.sk/rest/v1/';
	private static $authId;
	private static $authKey;
	private static $authToken;
	private static $request;
	private static $response;

	

	/**
	* Inicializácia triedy
	* @param autentifikačné id z Iskladu
	* @param autentifikačný kľúč z Iskladu
	* @param autentifikačný token z Iskladu
	*/
	static function Initialize($authId, $authKey, $authToken)
	{
		self::$authId 		= $authId;
		self::$authKey 		= $authKey;
		self::$authToken 	= $authToken;
	}

	/**
	* Dekódovanie chybového kódu
	* @param 	chybový kód
	* @return 	dekódovaný chybový kód
	*/
	private static function DecodeErrorCode($code)
	{
		switch ($code) {
			case '201':   return '201: Auth Accepted'; 		break;
			case '202':   return '202: Unauthorized'; 		break;
			case '301':   return '301: Bad Request'; 		break;
			case '302':   return '302: Method Not Found'; 	break;
			case '303':   return '303: Method Not Allowed'; break;
			case '401':   return '401: Entry Created'; 		break;
			case '402':   return '402: Entry Updated'; 		break;
			case '403':   return '403: Content Found'; 		break;
			case '404':   return '404: Content Not Found'; 	break;
		
			default:	  return 'CODE NOT FOUND';			break;
		}
	}

	/**
	* Vytvorenie requestu smerom k API
	* @param 	metóda
	* @param 	dáta požadované servrom
	* @return 	response zo servra 
	*/
	private static function CreateRequest($method, $data)
	{
		self::$request = array(
			'req' => json_encode(array(
								'auth' 		=> array(
												'auth_id' 		=> self::$authId,
												'auth_key' 		=> self::$authKey,
												'auth_token' 	=> self::$authToken,
												),
								'request' 	=> array(
												'req_method' 	=> $method,
												'req_data' 		=> $data,
												),
								)
					),
		);

        $ch = curl_init();
        	curl_setopt_array($ch, array(
        			CURLOPT_URL				=> self::$apiUrl.'?auth_id='.self::$authId,
        			CURLOPT_POST			=> 1,
        			CURLOPT_RETURNTRANSFER	=> true,
        			CURLOPT_POSTFIELDS		=> http_build_query(self::$request),
        			CURLOPT_SSL_VERIFYPEER	=> false,
        		));
        	$result  			= curl_exec($ch);
    		$header  			= curl_getinfo($ch);
    		$header['errno']  	= curl_errno($ch);
    		$header['errmsg'] 	= curl_error($ch);
        curl_close($ch);

  		$iskladErrorCodes = json_decode($result);

        $resultData = array(
        	'iskladAuthStatus'	=> isset($iskladErrorCodes->auth_status) ? self::DecodeErrorCode($iskladErrorCodes->auth_status) : self::DecodeErrorCode(0),
         	'iskladRespCode' 	=> isset($iskladErrorCodes->response->resp_code) ? self::DecodeErrorCode($iskladErrorCodes->response->resp_code) : self::DecodeErrorCode(0),
         	'response' 			=> $result, 
         	'decodedResponse' 	=> json_decode($result),
         	'responseHeaders' 	=> $header
         );
        
        return $resultData;

	}
	
	/**
	* test konektivity na server
	*/
	static function ConnectionTest()
	{
		self::$response = self::CreateRequest('ConnectionTest', array());
		if(isset(self::$response['iskladAuthStatus']))
		{
			if(self::$response['iskladAuthStatus']==self::DecodeErrorCode(201))
			{
				print '<pre style="background:green; padding:5px;">'.__CLASS__.': Prihlasenie na server prebehlo uspesne.</pre>';
			}else
			{
				print '<pre style="background:red; padding:5px;">'.__CLASS__.': CHYBA! Nespravne prihlasovacie udaje!</pre>';
			}
		}else
		{
			print '<pre style="background:red; padding:5px;">'.__CLASS__.': CHYBA! Nie je mozne ziskat status zo servra!</pre>';
		}
	}

	/**
	* vracia posledný requestu
	* @return 	posledný uskutočnený request
	*/
	static function GetRequest()
	{
		return self::$request;
	}

	/**
	* vracia posledný response
	* @return 	posledný prijatý response
	*/
	static function GetResponse()
	{
		return self::$response;
	}

	/**
	* vloženie alebo aktualizácia skladovej karty v systéme
	* @param 	dáta k metóde požadované servrom
	*/
	static function UpdateInventoryCard($data)
	{
		self::$response = self::CreateRequest('UpdateInventoryCard', $data);
	}

	/**
	* vloženie alebo aktualizácia skladovej karty v systéme
	* @param 	dáta k metóde požadované servrom
	*/
	static function CreateNewOrder($data)
	{
		self::$response = self::CreateRequest('CreateNewOrder', $data);
	}

	/**
	* Stornovanie objednávky
	* @param 	dáta k metóde požadované servrom
	*/
	static function StornoOrder($data)
	{
		self::$response = self::CreateRequest('StornoOrder', $data);
	}

	
}
