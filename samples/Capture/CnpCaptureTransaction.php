<?php
namespace cnp\sdk;
require_once realpath(__DIR__). '/../../vendor/autoload.php';
 
#Capture
#cnpTxnId contains the Vantiv Transaction Id returned on the authorization
 
$capture_info = array(
        'cnpTxnId'=>'100000000000000001',
        'id'=> '456',
	);
 
$initialize = new CnpOnlineRequest();
$captureResponse = $initialize->captureRequest($capture_info);
 
#display results
echo ("Response: " . (XmlParser::getNode($captureResponse,'response')) . "<br>");
echo ("Message: " . XmlParser::getNode($captureResponse,'message') . "<br>");
echo ("Vantiv Transaction ID: " . XmlParser::getNode($captureResponse,'cnpTxnId'));

if(XmlParser::getNode($captureResponse,'message')!='Approved')
 throw new \Exception('CnpCaptureTransaction does not get the right response');
