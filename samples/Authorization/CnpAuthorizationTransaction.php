<?php
namespace cnp\sdk;
require_once realpath(__DIR__). '/../../vendor/autoload.php';
 
#Authorization
$auth_info = array(
        	      'orderId' => '1',
		      'amount' => '10010',
                      'id'=> '456',
		      'orderSource'=>'ecommerce',
		      'billToAddress'=>array(
		      'name' => 'John Smith',
		      'addressLine1' => '1 Main St.',
		      'city' => 'Burlington',
		      'state' => 'MA',
		      'zip' => '01803-3747',
		      'country' => 'US'),
		      'card'=>array(
		      'number' =>'4457010000000009',
		      'expDate' => '0112',
		      'cardValidationNum' => '349',
		      'type' => 'VI')
			);
 
$initialize = new CnpOnlineRequest();
$authResponse = $initialize->authorizationRequest($auth_info);
 
#display results
echo ("Response: " . (XmlParser::getNode($authResponse,'response')) . "<br>");
echo ("Message: " . XmlParser::getNode($authResponse,'message') . "<br>");
echo ("Vantiv Transaction ID: " . XmlParser::getNode($authResponse,'litleTxnId'));

if(XmlParser::getNode($authResponse,'message')!='Approved')
 throw new \Exception('CnpAuthorizationTransaction does not get the right response');