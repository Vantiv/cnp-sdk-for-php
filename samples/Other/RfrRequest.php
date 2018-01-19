<?php
namespace cnp\sdk;
require_once realpath(__DIR__). '/../../vendor/autoload.php'; 
# use Auth batch to get the session Id
$request = new CnpRequest();
$batch = new BatchRequest();
	$hash_in = array(
		'card'=>array('type'=>'VI',
				'number'=>'4100000000000001',
				'expDate'=>'1213',
				'cardValidationNum' => '1213'),
		'orderId'=> '2111',
		'orderSource'=>'ecommerce',
		'id'=>'654',
		'amount'=>'123');
	$batch->addAuth($hash_in);
	$request->addBatchRequest($batch);
	$responseFileLoc=$request->sendToCnpStream();
	$resp = new CnpResponseProcessor($responseFileLoc);
	$xmlReader=$resp->getXmlReader();
	$sessionId=$xmlReader->getAttribute("litleSessionId");
	echo ("sessionId:" +$sessionId);
	
	
	
     #Process RFR request
	 $request = new CnpRequest();
	 $request->createRFRRequest(array('litleSessionId' => $sessionId));
	 $response_file = $request->sendToCnpStream();
	 $processor = new CnpResponseProcessor($response_file);
	 $res=$processor->nextTransaction(true);
	 echo $res;
	 $xml = simplexml_load_string($res);
	if($xml->message[0]!='Approved')
     throw new \Exception('RfrRequest does not get the right response');
	
  

	 // if($xmlReader->getAttribute("message")!='Approved')
 	  // throw new \Exception('RfrRequest does not get the right response');
//  


