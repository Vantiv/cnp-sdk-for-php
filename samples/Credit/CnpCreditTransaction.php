<?php
namespace cnp\sdk;
require_once realpath(__DIR__). '/../../vendor/autoload.php';
#PHP SDK- Cnp Credit Transaction
#Credit
#cnpTxnId contains the Vantiv Transaction Id returned on
#the capture or sale transaction being credited
#the amount is optional, if it isn't submitted the full amount will be credited
 
$credit_info = array(
		'cnpTxnId'=>'100000000000000002',
                 'id'=> '456',
       		'amount'=>'1010'
		);
$initialize = new CnpOnlineRequest(); 
$creditResponse = $initialize->creditRequest($credit_info);
 
#display results
echo ("Response: " . (XmlParser::getNode($creditResponse,'response')) . "<br>");
echo ("Message: " . XmlParser::getNode($creditResponse,'message') . "<br>");
echo ("Vantiv Transaction ID: " . XmlParser::getNode($creditResponse,'cnpTxnId'));

if(XmlParser::getNode($creditResponse,'message')!='Approved')
 throw new \Exception('CnpCreditTransaction does not get the right response');
