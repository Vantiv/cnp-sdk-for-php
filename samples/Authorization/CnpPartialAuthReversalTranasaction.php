<?php
namespace cnp\sdk;
require_once realpath(__DIR__). '/../../vendor/autoload.php';
 
#Partial Auth Reversal
#cnpTxnId contains the Vantiv Transaction Id returned on the authorization
 
$authRev_info = array(
  'cnpTxnId'=>'350000000000000001',
  'amount'=>'20020'
);
 
$initialize = new CnpOnlineRequest();
$reversalResponse = $initialize->authReversalRequest($authRev_info);
#display results
echo ("Response: " . (XmlParser::getNode($reversalResponse,'response')) . "<br>");
echo ("Message: " . XmlParser::getNode($reversalResponse,'message') . "<br>");
echo ("Vantiv Transaction ID: " . XmlParser::getNode($reversalResponse,'cnpTxnId'));

if(XmlParser::getNode($reversalResponse,'message')!='Approved')
 throw new \Exception('CnpPartialAuthReversalTranasaction does not get the right response');