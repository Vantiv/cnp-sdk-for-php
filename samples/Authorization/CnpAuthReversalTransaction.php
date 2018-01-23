<?php
namespace cnp\sdk;
require_once realpath(__DIR__). '/../../vendor/autoload.php';
#Auth Reversal
#cnpTxnId contains the Vantiv Transaction Id returned on the authorization
 
$authRev_info = array(
  'cnpTxnId'=>'350000000000000001',
  'id'=> '456'
);
 
$initialize = new CnpOnlineRequest();
$reversalResponse = $initialize->authReversalRequest($authRev_info);
#display results
echo ("Response: " . (XmlParser::getNode($reversalResponse,'response')) . "<br>");
echo ("Message: " . XmlParser::getNode($reversalResponse,'message') . "<br>");
echo ("Vantiv Transaction ID: " . XmlParser::getNode($reversalResponse,'cnpTxnId'));

if(XmlParser::getNode($reversalResponse,'message')!='Approved')
 throw new \Exception('CnpAuthReversalTransaction does not get the right response');