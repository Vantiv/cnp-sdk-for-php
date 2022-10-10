<?php
/*
 * Copyright (c) 2011 Vantiv eCommerce Inc.
*
* Permission is hereby granted, free of charge, to any person
* obtaining a copy of this software and associated documentation
* files (the "Software"), to deal in the Software without
* restriction, including without limitation the rights to use,
* copy, modify, merge, publish, distribute, sublicense, and/or sell
* copies of the Software, and to permit persons to whom the
* Software is furnished to do so, subject to the following
* conditions:
*
* The above copyright notice and this permission notice shall be
* included in all copies or substantial portions of the Software.
* THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND
* EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
* OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
* NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
* HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
* WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
* FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
* OTHER DEALINGS IN THE SOFTWARE.
*/
namespace cnp\sdk\Test\unit;
use cnp\sdk\CnpOnlineRequest;
use cnp\sdk\CommManager;

class CreditUnitTest extends \PHPUnit_Framework_TestCase
{
    public static function setUpBeforeClass()
    {
        CommManager::reset();
    }

    public function test_credit()
    {
        $hash_in = array('cnpTxnId'=> '12312312','reportGroup'=>'Planets', 'amount'=>'123','id' => 'id',);
        $mock = $this->getMock('cnp\sdk\CnpXmlMapper');
        $mock->expects($this->once())
        ->method('request')
        ->with($this->matchesRegularExpression('/.*<cnpTxnId>12312312.*<amount>123.*/'));

        $cnpTest = new CnpOnlineRequest();
        $cnpTest->newXML = $mock;
        $cnpTest->creditRequest($hash_in);
    }

    public function test_credit_with_merchantCategoryCode()
    {

        $hash_in = array(
            'reportGroup'=>'Planets',
            'orderId'=>'12344',
            'id' => 'id',
            'amount'=>'106',
            'orderSource'=>'ecommerce',
            'merchantCategoryCode' => '3535',
            'card'=>array(
                'type'=>'VI',
                'number' =>'4100000000000001',
                'expDate' =>'1210'
            ));

        $mock = $this->getMock('cnp\sdk\CnpXmlMapper');
        $mock->expects($this->once())
            ->method('request')
            ->with($this->matchesRegularExpression('/.*<orderId>12344.*<amount>106.*/'));

        $cnpTest = new CnpOnlineRequest();
        $cnpTest->newXML = $mock;
        $cnpTest->creditRequest($hash_in);
    }

    public function test_both_choices_card_and_paypal()
    {
        $hash_in = array(
          'reportGroup'=>'Planets',
          'orderId'=>'12344',
          'id' => 'id',
          'amount'=>'106',
          'orderSource'=>'ecommerce',
          'card'=>array(
          'type'=>'VI',
          'number' =>'4100000000000001',
          'expDate' =>'1210'
        ),
          'paypal'=>array(
          'payerId'=>'1234',
          'token'=>'1234',
          'transactionId'=>'123456')
        );
        $cnpTest = new CnpOnlineRequest();
        $this->setExpectedException('PHPUnit_Framework_Error_Warning');
        $retOb = $cnpTest->creditRequest($hash_in);
    }

    public function test_three_choices_card_and_paypage_and_paypal()
    {
        $hash_in = array(
          'reportGroup'=>'Planets',
          'orderId'=>'12344',
          'amount'=>'106',
          'id' => 'id',
          'orderSource'=>'ecommerce',
          'card'=>array(
          'type'=>'VI',
          'number' =>'4100000000000001',
          'expDate' =>'1210'
        ),
          'paypage'=> array(
          'paypageRegistrationId'=>'1234',
          'expDate'=>'1210',
          'cardValidationNum'=>'555',
          'type'=>'VI'),
          'paypal'=>array(
          'payerId'=>'1234',
          'token'=>'1234',
          'transactionId'=>'123456')
        );
        $cnpTest = new CnpOnlineRequest();
        $this->setExpectedException('PHPUnit_Framework_Error_Warning');
        $retOb = $cnpTest->creditRequest($hash_in);

    }

    public function test_all_choices_card_and_paypage_and_paypal_and_token()
    {
        $hash_in = array(
          'reportGroup'=>'Planets',
          'id' => 'id',
          'cnpTxnId'=>'123456',
          'orderId'=>'12344',
          'amount'=>'106',
          'orderSource'=>'ecommerce',
          'fraudCheck'=>array('authenticationTransactionId'=>'123'),
          'bypassVelocityCheckcardholderAuthentication'=>array('authenticationTransactionId'=>'123'),
          'card'=>array(
          'type'=>'VI',
          'number' =>'4100000000000001',
          'expDate' =>'1210'
        ),
          'paypage'=> array(
          'paypageRegistrationId'=>'1234',
          'expDate'=>'1210',
          'cardValidationNum'=>'555',
          'type'=>'VI'),
          'paypal'=>array(
          'payerId'=>'1234',
          'token'=>'1234',
          'transactionId'=>'123456'),
          'token'=> array(
          'cnpToken'=>'1234',
          'expDate'=>'1210',
          'cardValidationNum'=>'555',
          'type'=>'VI')
        );
        $cnpTest = new CnpOnlineRequest();
        $this->setExpectedException('PHPUnit_Framework_Error_Warning');
        $retOb = $cnpTest->creditRequest($hash_in);
    }

    public function test_action_reason_on_orphaned_refund()
    {
        $hash_in = array(
                    'orderId'=> '2111',
        		     'id' => 'id',
                    'orderSource'=>'ecommerce',
                    'amount'=>'123',
                    'card'=>array(
                        'type'=>'VI',
                        'number' =>'4100000000000001',
                        'expDate' =>'1210'),
                    'actionReason'=>'SUSPECT_FRAUD'
        );
        $mock = $this->getMock('cnp\sdk\CnpXmlMapper');
        $mock->expects($this->once())
        ->method('request')
        ->with($this->matchesRegularExpression('/.*<actionReason>SUSPECT_FRAUD<\/actionReason>.*/'));

        $cnpTest = new CnpOnlineRequest();
        $cnpTest->newXML = $mock;
        $cnpTest->creditRequest($hash_in);
    }

    public function test_loggedInUser()
    {
        $hash_in = array(
                'cnpTxnId'=> '12312312',
        		'id' => 'id',
                'reportGroup'=>'Planets',
                'amount'=>'123',
                'merchantSdk'=>'PHP;10.1.0',
                'loggedInUser'=>'gdake');
        $mock = $this->getMock('cnp\sdk\CnpXmlMapper');
        $mock->expects($this->once())
        ->method('request')
        ->with($this->matchesRegularExpression('/.*merchantSdk="PHP;10.1.0".*loggedInUser="gdake" xmlns=.*>.*/'));

        $cnpTest = new CnpOnlineRequest();
        $cnpTest->newXML = $mock;
        $cnpTest->creditRequest($hash_in);
    }

    public function test_surchargeAmount_tied()
    {
        $hash_in = array(
                'amount'=>'2',
        		'id' => 'id',
                'surchargeAmount'=>'1',
                'cnpTxnId'=>'3',
                'processingInstructions'=>array(),
        );
        $mock = $this->getMock('cnp\sdk\CnpXmlMapper');
        $mock
        ->expects($this->once())
        ->method('request')
        ->with($this->matchesRegularExpression('/.*<cnpTxnId>3<\/cnpTxnId><amount>2<\/amount><surchargeAmount>1<\/surchargeAmount><processing.*/'));

        $cnpTest = new CnpOnlineRequest();
        $cnpTest->newXML = $mock;
        $cnpTest->creditRequest($hash_in);
    }

    public function test_surchargeAmount_tied_optional()
    {
        $hash_in = array(
                'amount'=>'2',
        		'id' => 'id',
                'cnpTxnId'=>'3',
                'processingInstructions'=>array(),
        );
        $mock = $this->getMock('cnp\sdk\CnpXmlMapper');
        $mock
        ->expects($this->once())
        ->method('request')
        ->with($this->matchesRegularExpression('/.*<cnpTxnId>3<\/cnpTxnId><amount>2<\/amount><processing.*/'));

        $cnpTest = new CnpOnlineRequest();
        $cnpTest->newXML = $mock;
        $cnpTest->creditRequest($hash_in);
    }

    public function test_surchargeAmount_orphan()
    {
        $hash_in = array(
                'amount'=>'2',
        		'id' => 'id',
                'surchargeAmount'=>'1',
                'orderId'=>'3',
                'card'=>array(
                    'type'=>'VI',
                    'number' =>'4100000000000001',
                    'expDate' =>'1210'),
                'orderSource'=>'ecommerce',
        );
        $mock = $this->getMock('cnp\sdk\CnpXmlMapper');
        $mock
        ->expects($this->once())
        ->method('request')
        ->with($this->matchesRegularExpression('/.*<amount>2<\/amount><surchargeAmount>1<\/surchargeAmount><orderSource>ecommerce<\/orderSource>.*/'));

        $cnpTest = new CnpOnlineRequest();
        $cnpTest->newXML = $mock;
        $cnpTest->creditRequest($hash_in);
    }

    public function test_surchargeAmount_orphan_optional()
    {
        $hash_in = array(
                'amount'=>'2',
        		'id' => 'id',
                'orderId'=>'3',
                'card'=>array(
                    'type'=>'VI',
                    'number' =>'4100000000000001',
                    'expDate' =>'1210'),
                'orderSource'=>'ecommerce',
        );
        $mock = $this->getMock('cnp\sdk\CnpXmlMapper');
        $mock
        ->expects($this->once())
        ->method('request')
        ->with($this->matchesRegularExpression('/.*<amount>2<\/amount><orderSource>ecommerce<\/orderSource>.*/'));

        $cnpTest = new CnpOnlineRequest();
        $cnpTest->newXML = $mock;
        $cnpTest->creditRequest($hash_in);
    }

    public function test_pos_tied()
    {
        $hash_in = array(
                'amount'=>'2',
        		'id' => 'id',
                'pos'=>array(
                    'terminalId'=>'abc123',
                    'capability'=>'notused',
                    'entryMode'=>'notused',
                    'cardholderId'=>'signature'
                ),
                'cnpTxnId'=>'3',
                'payPalNotes'=>'notes',
        );
        $mock = $this->getMock('cnp\sdk\CnpXmlMapper');
        $mock
        ->expects($this->once())
        ->method('request')
        ->with($this->matchesRegularExpression('/.*<cnpTxnId>3<\/cnpTxnId><amount>2<\/amount><pos>.*<terminalId>abc123<\/terminalId><\/pos><payPalNotes>.*/'));

        $cnpTest = new CnpOnlineRequest();
        $cnpTest->newXML = $mock;
        $cnpTest->creditRequest($hash_in);
    }

    public function test_pos_tied_optional()
    {
        $hash_in = array(
                'amount'=>'2',
        		'id' => 'id',
                'cnpTxnId'=>'3',
                'payPalNotes'=>'notes',
        );
        $mock = $this->getMock('cnp\sdk\CnpXmlMapper');
        $mock
        ->expects($this->once())
        ->method('request')
        ->with($this->matchesRegularExpression('/.*<cnpTxnId>3<\/cnpTxnId><amount>2<\/amount><payPalNotes>.*/'));

        $cnpTest = new CnpOnlineRequest();
        $cnpTest->newXML = $mock;
        $cnpTest->creditRequest($hash_in);
    }
    
    public function test_credit_with_secondaryAmount()
    {
    	$hash_in = array('cnpTxnId'=> '12312312','reportGroup'=>'Planets', 'amount'=>'123', 'secondaryAmount' => '3214','id' => 'id',);
    	$mock = $this->getMock('cnp\sdk\CnpXmlMapper');
    	$mock->expects($this->once())
    	->method('request')
    	->with($this->matchesRegularExpression('/.*<cnpTxnId>12312312.*<amount>123.*/'));
    
    	$cnpTest = new CnpOnlineRequest();
    	$cnpTest->newXML = $mock;
    	$cnpTest->creditRequest($hash_in);
    }
    
    public function test_pin_tied()
    {
    	$hash_in = array(
    			'cnpTxnId'=> '1234567890',
    			'id' => 'id',
    			'reportGroup'=>'Planets', 
    			'amount'=>'123', 
    			'secondaryAmount' => '3214',
    			'surchargeAmount'=>'1',
    			'pin' => '3333'
    	);
    	$mock = $this->getMock('cnp\sdk\CnpXmlMapper');
    	$mock
    	->expects($this->once())
    	->method('request')
    	->with($this->matchesRegularExpression('/.*<pin>3333*/'));
    
    	$cnpTest = new CnpOnlineRequest();
    	$cnpTest->newXML = $mock;
    	$cnpTest->creditRequest($hash_in);
    }
    
    public function test_pin_tied_optional()
    {
    	$hash_in = array(
    			'cnpTxnId'=> '1234567890',
    			'id' => 'id',
    			'reportGroup'=>'Planets',
    			'amount'=>'123',
    			'secondaryAmount' => '3214',
    			'surchargeAmount'=>'1'
    	);
    	$mock = $this->getMock('cnp\sdk\CnpXmlMapper');
    	$mock
    	->expects($this->once())
    	->method('request')
    	->with($this->matchesRegularExpression('/.*<cnpTxnId>1234567890.*<amount>123.*<secondaryAmount>3214.*<surchargeAmount>1.*/'));
    
    	$cnpTest = new CnpOnlineRequest();
    	$cnpTest->newXML = $mock;
    	$cnpTest->creditRequest($hash_in);
    }

    public function test_business_indicator()
    {
        $hash_in = array(
            'amount'=>'2',
            'id' => 'id',
            'cnpTxnId'=>'3',
            'payPalNotes'=>'notes',
            'businessIndicator'=>'consumerBillPayment',
        );
        $mock = $this->getMock('cnp\sdk\CnpXmlMapper');
        $mock
            ->expects($this->once())
            ->method('request')
            ->with($this->matchesRegularExpression('/.*<businessIndicator>consumerBillPayment<\/businessIndicator>.*/'));

        $cnpTest = new CnpOnlineRequest();
        $cnpTest->newXML = $mock;
        $cnpTest->creditRequest($hash_in);
    }
    public function test_simple_credit_with_optional_order_id()
    {
        $hash_in = array('id' => 'id',
            'cnpTxnId' => '1234567891234567891',
            'orderId' => '22@33123456789012345678901234567890',
            'amount' => '123');
        $mock = $this->getMock('cnp\sdk\CnpXmlMapper');
        $mock->expects($this->once())
            ->method('request')
            ->with($this->matchesRegularExpression('/.*<cnpTxnId>1234567891234567891.*<orderId>22@33123456789012345678901234567890.*<amount>123.*/'));

        $cnpTest = new CnpOnlineRequest();
        $cnpTest->newXML = $mock;
        $cnpTest->creditRequest($hash_in);
    }

    public function test_credit_with_passengerTransportData()
    {
        $hash_in = array(
            'id' => 'id',
            'cnpTxnId' => '64736458734657',
            'amount' => '1010',
            'passengerTransportData' => array(
                'passengerName' => 'Mrs. Huxley234567890123456789',
                'ticketNumber' => 'ATL456789012345',
                'issuingCarrier' => 'AMTK',
                'carrierName' => 'AMTK',
                'restrictedTicketIndicator' => 'refundable123456789',
                'numberOfAdults' => '2',
                'numberOfChildren' => '0',
                'customerCode' => 'Railway',
                'arrivalDate' => '2022-09-20',
                'issueDate' => '2022-09-10',
                'travelAgencyCode' => '12345678',
                'travelAgencyName' => 'Travel R Us23456789012345',
                'creditReasonIndicator' => 'A',
                'ticketChangeIndicator' => 'C',
                'ticketIssuerAddress' => '99 Second St',
                'exchangeTicketNumber' => '123456789012346',
                'exchangeAmount' => '500046',
                'exchangeFeeAmount' => '5046',
                'tripLegData0' => array(
                    'tripLegNumber' => '1',
                    'departureCode' => 'STL',
                    'carrierCode' => 'AT',
                    'serviceClass' => 'Business',
                    'stopOverCode' => 'X',
                    'destinationCode' => 'STL',
                    'fareBasisCode' => 'nonref',
                    'departureDate' => '2022-09-20',
                    'originCity' => 'BOS',
                    'travelNumber' => '123AB',
                    'departureTime' => '09:32',
                    'arrivalTime' => '15:56',
                    'remarks' => 'This is a max 80 chars',
                )
            )

        );

        $mock = $this->getMock('cnp\sdk\CnpXmlMapper');
        $mock	->expects($this->once())
            ->method('request')
            ->with($this->matchesRegularExpression('/.*<passengerName>Mrs. Huxley234567890123456789.*<ticketNumber>ATL456789012345.*<exchangeAmount>500046.*<serviceClass>Business.*<originCity>BOS.*/'));

        $cnpTest = new CnpOnlineRequest();
        $cnpTest->newXML = $mock;
        $cnpTest->creditRequest($hash_in);
    }

    public function test_credit_with_additionalCOFData()
    {
        $hash_in = array('id' => 'id',
            'amount' => '123',
            'payPalNotes' => 'Notes',
            'cnpTxnId' => '12345678000',
            'additionalCOFData' => array(
                'totalPaymentCount' => 'ND',
                'paymentType' => 'Fixed Amount',
                'uniqueId' => '234GTYH654RF13',
                'frequencyOfMIT' => 'Annually',
                'validationReference' => 'ANBH789UHY564RFC@EDB',
                'sequenceIndicator' => '86',
            ),
        );

        $mock = $this->getMock('cnp\sdk\CnpXmlMapper');
        $mock
            ->expects($this->once())
            ->method('request')
            ->with($this->matchesRegularExpression('/.*<amount>123.*<sequenceIndicator>86.*/'));

        $cnpTest = new CnpOnlineRequest();
        $cnpTest->newXML = $mock;
        $cnpTest->creditRequest($hash_in);;
    }

}
