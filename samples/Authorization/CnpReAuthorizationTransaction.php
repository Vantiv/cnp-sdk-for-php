<?php
namespace cnp\sdk;
require_once realpath(__DIR__). '/../../vendor/autoload.php';
 
#Re authorization using the cnpTxnId of a previous auth
 
$auth_info = array(
        'cnpTxnId'=>'1234567891234567891',
        'id'=> '456'
	);
 
$initialize = new CnpOnlineRequest();
$authResponse = $initialize->authorizationRequest($auth_info );
 
#display results
echo ("Response: " . (XmlParser::getNode($authResponse ,'response')) . "<br>");
echo ("Message: " . XmlParser::getNode($authResponse ,'message') . "<br>");
echo ("Vantiv Transaction ID: " . XmlParser::getNode($authResponse,'cnpTxnId'));

if(XmlParser::getNode($authResponse,'message')!='Approved')
 throw new \Exception('CnpReAuthorizationTransaction does not get the right response');