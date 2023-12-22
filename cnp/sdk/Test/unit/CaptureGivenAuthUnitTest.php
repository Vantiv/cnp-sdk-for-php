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
use cnp\sdk\XmlParser;

class CaptureGivenAuthUnitTest extends \PHPUnit_Framework_TestCase
{
    public static function setUpBeforeClass()
    {
        CommManager::reset();
    }

    public function test_simple_captureGivenAuth()
    {
        $hash_in = array(
       'amount'=>'123',
       'orderId'=>'12344',
       'id'=> 'id',
       'authInformation' => array(
       'authDate'=>'2002-10-09','authCode'=>'543216',
       'authAmount'=>'12345'),
       'orderSource'=>'ecommerce',
       'card'=>array(
       'type'=>'VI',
       'number' =>'4100000000000001',
       'expDate' =>'1210'));
        $mock = $this->getMock('cnp\sdk\CnpXmlMapper');
        $mock	->expects($this->once())
        ->method('request')
        ->with($this->matchesRegularExpression('/.*<authInformation><authDate>2002-10-09.*<authCode>543216.*><authAmount>12345.*/'));

        $cnpTest = new CnpOnlineRequest();
        $cnpTest->newXML = $mock;
        $cnpTest->captureGivenAuthRequest($hash_in);

    }

    public function test_simple_captureGivenAuth_with_merchantCategoryCode()
    {
        $hash_in = array(
            'amount'=>'123',
            'orderId'=>'12344',
            'id'=> 'id',
            'merchantCategoryCode' => '3535',
            'authInformation' => array(
                'authDate'=>'2002-10-09','authCode'=>'543216',
                'authAmount'=>'12345'),
            'orderSource'=>'ecommerce',
            'card'=>array(
                'type'=>'VI',
                'number' =>'4100000000000001',
                'expDate' =>'1210'));
        $mock = $this->getMock('cnp\sdk\CnpXmlMapper');
        $mock	->expects($this->once())
            ->method('request')
            ->with($this->matchesRegularExpression('/.*<authInformation><authDate>2002-10-09.*<authCode>543216.*><authAmount>12345.*/'));

        $cnpTest = new CnpOnlineRequest();
        $cnpTest->newXML = $mock;
        $cnpTest->captureGivenAuthRequest($hash_in);

    }

    public function test_no_amount()
    {
        $hash_in = array(
       'reportGroup'=>'Planets',
       'orderId'=>'12344',
       'id'=> 'id',
       'authInformation' => array(
       'authDate'=>'2002-10-09','authCode'=>'543216',
       'authAmount'=>'12345'),
       'orderSource'=>'ecommerce',
       'card'=>array(
       'type'=>'VI',
       'number' =>'4100000000000001',
       'expDate' =>'1210'));
        $cnpTest = new CnpOnlineRequest();
        $this->setExpectedException("PHPUnit_Framework_Error_Warning");
        $retOb = $cnpTest->captureGivenAuthRequest($hash_in);
    }

    public function test_both_choices_card_and_token()
    {
        $hash_in = array(
          'reportGroup'=>'Planets',
          'orderId'=>'1234',
         'id'=> 'id',
         'amount'=>'106',
         'orderSource'=>'ecommerce',
          'authInformation' => array(
          'authDate'=>'2002-10-09','authCode'=>'543216',
         'authAmount'=>'12345'),
          'token'=> array(
          'cnpToken'=>'123456789101112',
         'expDate'=>'1210',
        'cardValidationNum'=>'555',
        'type'=>'VI'),
         'card'=>array(
        'type'=>'VI',
        'number' =>'4100000000000001',
        'expDate' =>'1210'));
        $cnpTest = new CnpOnlineRequest();
        $this->setExpectedException('PHPUnit_Framework_Error_Warning');
        $retOb = $cnpTest->captureGivenAuthRequest($hash_in);
    }

    public function test_all_choices()
    {
        $hash_in = array(
                  'reportGroup'=>'Planets',
        		  'id'=> 'id',
                  'cnpTxnId'=>'123456',
                  'orderId'=>'12344',
                  'amount'=>'106',
                  'orderSource'=>'ecommerce',
                  'fraudCheck'=>array('authenticationTransactionId'=>'123'),
                  'cardholderAuthentication'=>array('authenticationTransactionId'=>'123'),
                  'card'=>array(
                  'type'=>'VI',
                  'number' =>'4100000000000001',
                  'expDate' =>'1210'),
                  'paypage'=> array(
                  'paypageRegistrationId'=>'1234',
                  'expDate'=>'1210',
                  'cardValidationNum'=>'555',
                  'type'=>'VI'),
                  'token'=> array(
                  'cnpToken'=>'1234',
                  'expDate'=>'1210',
                  'cardValidationNum'=>'555',
                  'type'=>'VI'));
        $cnpTest = new CnpOnlineRequest();
        $this->setExpectedException('PHPUnit_Framework_Error_Warning');
        $retOb = $cnpTest->captureGivenAuthRequest($hash_in);
    }

    public function test_loggedInUser()
    {
        $hash_in = array(
                'loggedInUser'=>'gdake',
        		'id'=> 'id',
                'merchantSdk'=>'PHP;10.1',
                'amount'=>'123',
                'orderId'=>'12344',
                'authInformation' => array(
                        'authDate'=>'2002-10-09','authCode'=>'543216',
                        'authAmount'=>'12345'),
                'orderSource'=>'ecommerce',
                'card'=>array(
                        'type'=>'VI',
                        'number' =>'4100000000000001',
                        'expDate' =>'1210'));
        $mock = $this->getMock('cnp\sdk\CnpXmlMapper');
        $mock	->expects($this->once())
        ->method('request')
        ->with($this->matchesRegularExpression('/.*merchantSdk="PHP;10.1".*loggedInUser="gdake" xmlns=.*>.*/'));

        $cnpTest = new CnpOnlineRequest();
        $cnpTest->newXML = $mock;
        $cnpTest->captureGivenAuthRequest($hash_in);

    }

    public function test_surchargeAmount()
    {
        $hash_in = array(
            'orderId'=>'3',
            'authInformation' => array(
                'authDate'=>'2002-10-09','authCode'=>'543216',
                'authAmount'=>'12345'),
            'amount'=>'2',
            'orderSource'=>'ecommerce',
            'card'=>array(
                'type'=>'VI',
                'number' =>'4100000000000001',
                'expDate' =>'1210'),
            'surchargeAmount'=>'1',
            'id'=>'id'
        );
        $mock = $this->getMock('cnp\sdk\CnpXmlMapper');
        $mock
            ->expects($this->once())
            ->method('request')
            ->with($this->matchesRegularExpression('/.*<amount>2<\/amount><surchargeAmount>1<\/surchargeAmount><orderSource>ecommerce<\/orderSource>.*/'));


        $cnpTest = new CnpOnlineRequest();
        $cnpTest->newXML = $mock;
        $cnpTest->captureGivenAuthRequest($hash_in);
    }

    public function test_surchargeAmount_optional()
    {
        $hash_in = array(
            'orderId'=>'3',
            'authInformation' => array(
                'authDate'=>'2002-10-09','authCode'=>'543216',
                'authAmount'=>'12345'),
            'amount'=>'2',
            'orderSource'=>'ecommerce',
            'card'=>array(
                'type'=>'VI',
                'number' =>'4100000000000001',
                'expDate' =>'1210'),
            'id'=>'id'
        );
        $mock = $this->getMock('cnp\sdk\CnpXmlMapper');
        $mock
        ->expects($this->once())
        ->method('request')
        ->with($this->matchesRegularExpression('/.*<amount>2<\/amount><orderSource>ecommerce<\/orderSource>.*/'));

        $cnpTest = new CnpOnlineRequest();
        $cnpTest->newXML = $mock;
        $cnpTest->captureGivenAuthRequest($hash_in);
    }

    public function test_debtRepayment_true()
    {

        $hash_in = array(
                'amount'=>'2',
        		'id'=> 'id',
                'orderSource'=>'ecommerce',
                'orderId'=>'3',
                'merchantData'=>array(
                    'campaign'=>'foo',
                ),
                'card'=>array(
                    'type'=>'VI',
                    'number' =>'4100000000000001',
                'expDate' =>'1210'),
                 'authInformation' => array(
                    'authDate'=>'2002-10-09','authCode'=>'543216',
                    'authAmount'=>'12345'),
                'debtRepayment'=>'true'
        );
        $mock = $this->getMock('cnp\sdk\CnpXmlMapper');
        $mock
        ->expects($this->once())
        ->method('request')
        ->with($this->matchesRegularExpression('/.*<\/merchantData><debtRepayment>true<\/debtRepayment><\/captureGivenAuth>.*/'));

        $cnpTest = new CnpOnlineRequest();
        $cnpTest->newXML = $mock;
        $cnpTest->captureGivenAuthRequest($hash_in);
    }

    public function test_debtRepayment_false()
    {
        $hash_in = array(
                'amount'=>'2',
        		'id'=> 'id',
                'orderSource'=>'ecommerce',
                'orderId'=>'3',
                'merchantData'=>array(
                    'campaign'=>'foo',
                ),
                'card'=>array(
                    'type'=>'VI',
                    'number' =>'4100000000000001',
                    'expDate' =>'1210'),
                'authInformation' => array(
                    'authDate'=>'2002-10-09','authCode'=>'543216',
                    'authAmount'=>'12345'),
                'debtRepayment'=>'false'
        );
        $mock = $this->getMock('cnp\sdk\CnpXmlMapper');
        $mock
        ->expects($this->once())
        ->method('request')
        ->with($this->matchesRegularExpression('/.*<\/merchantData><debtRepayment>false<\/debtRepayment><\/captureGivenAuth>.*/'));

        $cnpTest = new CnpOnlineRequest();
        $cnpTest->newXML = $mock;
        $cnpTest->captureGivenAuthRequest($hash_in);
    }

    public function test_debtRepayment_optional()
    {
        $hash_in = array(
                'amount'=>'2',
        		'id'=> 'id',
                'orderSource'=>'ecommerce',
                'orderId'=>'3',
                'merchantData'=>array(
                        'campaign'=>'foo',
                ),
                'card'=>array(
                    'type'=>'VI',
                    'number' =>'4100000000000001',
                    'expDate' =>'1210'),
                'authInformation' => array(
                    'authDate'=>'2002-10-09','authCode'=>'543216',
                    'authAmount'=>'12345'),
        );
        $mock = $this->getMock('cnp\sdk\CnpXmlMapper');
        $mock
        ->expects($this->once())
        ->method('request')
        ->with($this->matchesRegularExpression('/.*<\/merchantData><\/captureGivenAuth>.*/'));

        $cnpTest = new CnpOnlineRequest();
        $cnpTest->newXML = $mock;
        $cnpTest->captureGivenAuthRequest($hash_in);
    }
    
    public function test_simple_captureGivenAuth_secondaryAmount()
    {
    	$hash_in = array(
    			'amount'=>'123',
    			'id'=> 'id',
    			'secondaryAmount' => '2102',
    			'orderId'=>'12344',
    			'authInformation' => array(
    					'authDate'=>'2002-10-09','authCode'=>'543216',
    					'authAmount'=>'12345'),
    			'orderSource'=>'ecommerce',
    			'card'=>array(
    					'type'=>'VI',
    					'number' =>'4100000000000001',
    					'expDate' =>'1210'));
    	$mock = $this->getMock('cnp\sdk\CnpXmlMapper');
    	$mock	->expects($this->once())
    	->method('request')
    	->with($this->matchesRegularExpression('/.*<authInformation><authDate>2002-10-09.*<authCode>543216.*><authAmount>12345.*/'));
    
    	$cnpTest = new CnpOnlineRequest();
    	$cnpTest->newXML = $mock;
    	$cnpTest->captureGivenAuthRequest($hash_in);
    
    }
    
    public function test_simple_captureGivenAuth_processingType()
    {
    	$hash_in = array(
    			'amount'=>'123',
    			'orderId'=>'12344',
    			'id'=> 'id',
    			'authInformation' => array(
    					'authDate'=>'2002-10-09','authCode'=>'543216',
    					'authAmount'=>'12345'),
    			'orderSource'=>'ecommerce',
    			'card'=>array(
    					'type'=>'VI',
    					'number' =>'4100000000000001',
    					'expDate' =>'1210'),
    			'processingType' => 'accountFunding',
    			'originalNetworkTransactionId' => 'abcdefgh',
    			'originalTransactionAmount' => '1000'
    	);
    	$mock = $this->getMock('cnp\sdk\CnpXmlMapper');
    	$mock	->expects($this->once())
    	->method('request')
    	->with($this->matchesRegularExpression('/.*<processingType>accountFunding.*<originalNetworkTransactionId>abcdefgh.*<originalTransactionAmount>1000.*/'));
    
    	$cnpTest = new CnpOnlineRequest();
    	$cnpTest->newXML = $mock;
    	$cnpTest->captureGivenAuthRequest($hash_in);
    
    }
    public function test_captureGivenAuth_with_additionalCOFData()
    {

        $hash_in = array('id' => 'id',
            'orderId' => '12344',
            'amount' => '106',
            'authInformation' => array(
                'authDate' => '2002-10-09', 'authCode' => '543216',
                'authAmount' => '12345'),
            'billToAddress' => array('name' => 'Bob', 'city' => 'lowell', 'state' => 'MA', 'email' => 'vantiv.com'),
            'processingInstructions' => array('bypassVelocityCheck' => 'true'),
            'orderSource' => 'ecommerce',
            'card' => array(
                'type' => 'VI',
                'number' => '4100000000000000',
                'expDate' => '1210'),
            'crypto' => 'true',
            'billToAddress' => array(
                'name' => 'David Berman A',
                'addressLine1' => '10 Main Street',
                'city' => 'San Jose',
                'state' => 'ca',
                'zip' => '95032',
                'country' => 'USA',
                'email' => 'dberman@phoenixProcessing.com',
                'phone' => '781-270-1111',
                'sellerId' => '21234234A1',
                'url' => 'www.google.com',
            ),
            'shipToAddress' => array(
                'name' => 'Raymond J. Johnson Jr. B',
                'addressLine1' => '123 Main Street',
                'city' => 'McLean',
                'state' => 'VA',
                'zip' => '22102',
                'country' => 'USA',
                'email' => 'ray@rayjay.com',
                'phone' => '978-275-0000',
                'sellerId' => '21234234A2',
                'url' => 'www.google.com',
            ),
            'retailerAddress' => array(
                'name' => 'John doe',
                'addressLine1' => '123 Main Street',
                'addressLine2' => '123 Main Street',
                'addressLine3' => '123 Main Street',
                'city' => 'Cincinnati',
                'state' => 'OH',
                'zip' => '45209',
                'country' => 'USA',
                'email' => 'noone@abc.com',
                'phone' => '1234562783',
                'sellerId' => '21234234A',
                'companyName' => 'Google INC',
                'url' => 'www.google.com',
            ),
            'additionalCOFData' => array(
                'totalPaymentCount' => 'ND',
                'paymentType' => 'Fixed Amount',
                'uniqueId' => '234GTYH654RF13',
                'frequencyOfMIT' => 'Annually',
                'validationReference' => 'ANBH789UHY564RFC@EDB',
                'sequenceIndicator' => '86',
            ),
        );

        $initialize = new CnpOnlineRequest();
        //print_r($hash_in);
        $authorizationResponse = $initialize->captureGivenAuthRequest($hash_in);
        $response = XmlParser::getNode($authorizationResponse, 'response');
        $this->assertEquals('000', $response);
        $location = XmlParser::getNode($authorizationResponse, 'location');
        $this->assertEquals('sandbox', $location);
    }

    public function test_capture_given_auth_with_passengerTransportData()
    {
        $hash_in = array('id' => 'id',
            'orderId' => '12344',
            'amount' => '106',
            'businessIndicator' => 'consumerBillPayment',
            'authInformation' => array(
                'authDate' => '2002-10-09', 'authCode' => '543216',
                'authAmount' => '12345'),
            'orderSource' => 'ecommerce',
            'card' => array(
                'type' => 'VI',
                'number' => '4100000000000000',
                'expDate' => '1210'),
            'passengerTransportData' =>array(
                'passengerName' =>'Mrs. Huxley234567890123456789',
                'ticketNumber' =>'ATL456789012345' ,
                'issuingCarrier' =>'AMTK',
                'carrierName' =>'AMTK',
                'restrictedTicketIndicator' =>'99999',
                'numberOfAdults' =>'2',
                'numberOfChildren' =>'0',
                'customerCode' =>'Railway',
                'arrivalDate' =>'2022-09-20',
                'issueDate' =>'2022-09-10',
                'travelAgencyCode' =>'12345678',
                'travelAgencyName' =>'Travel R Us23456789012345',
                'computerizedReservationSystem' =>'STRT',
                'creditReasonIndicator' =>'P',
                'ticketChangeIndicator' =>'C',
                'ticketIssuerAddress' =>'99 Second St',
                'exchangeTicketNumber' =>'123456789012346',
                'exchangeAmount' =>'500046',
                'exchangeFeeAmount' =>'5046',
                'tripLegData' =>array(
                    'tripLegNumber' =>'10' ,
                    'serviceClass' =>'First',
                    'departureDate' =>'2022-09-20',
                    'originCity' =>'BOS')
            ));

        $mock = $this->getMock('cnp\sdk\CnpXmlMapper');
        $mock	->expects($this->once())
            ->method('request')
            ->with($this->matchesRegularExpression('/.*<passengerName>Mrs. Huxley234567890123456789.*<ticketNumber>ATL456789012345.*<exchangeAmount>500046.*<serviceClass>First.*<originCity>BOS.*/'));

        $cnpTest = new CnpOnlineRequest();
        $cnpTest->newXML = $mock;
        $cnpTest->captureGivenAuthRequest($hash_in);
    }
    public function test_capture_given_auth_with_foreignRetailerIndicator()
    {
        $hash_in = array('id' => 'id',
            'orderId' => '12344',
            'amount' => '106',
            'businessIndicator' => 'consumerBillPayment',
            'authInformation' => array(
                'authDate' => '2002-10-09', 'authCode' => '543216',
                'authAmount' => '12345'),
            'orderSource' => 'ecommerce',
            'card' => array(
                'type' => 'VI',
                'number' => '4100000000000000',
                'expDate' => '1210'),
            'passengerTransportData' =>array(
                'passengerName' =>'Mrs. Huxley234567890123456789',
                'ticketNumber' =>'ATL456789012345' ,
                'issuingCarrier' =>'AMTK',
                'carrierName' =>'AMTK',
                'restrictedTicketIndicator' =>'99999',
                'numberOfAdults' =>'2',
                'numberOfChildren' =>'0',
                'customerCode' =>'Railway',
                'arrivalDate' =>'2022-09-20',
                'issueDate' =>'2022-09-10',
                'travelAgencyCode' =>'12345678',
                'travelAgencyName' =>'Travel R Us23456789012345',
                'computerizedReservationSystem' =>'STRT',
                'creditReasonIndicator' =>'P',
                'ticketChangeIndicator' =>'C',
                'ticketIssuerAddress' =>'99 Second St',
                'exchangeTicketNumber' =>'123456789012346',
                'exchangeAmount' =>'500046',
                'exchangeFeeAmount' =>'5046',
                'tripLegData' =>array(
                    'tripLegNumber' =>'10' ,
                    'serviceClass' =>'First',
                    'departureDate' =>'2022-09-20',
                    'originCity' =>'BOS')
            ),                
            'foreignRetailerIndicator' => 'F'
        );

        $mock = $this->getMock('cnp\sdk\CnpXmlMapper');
        $mock	->expects($this->once())
            ->method('request')
            ->with($this->matchesRegularExpression('/.*<foreignRetailerIndicator>F.*/'));
        $cnpTest = new CnpOnlineRequest();
        $cnpTest->newXML = $mock;
        $cnpTest->captureGivenAuthRequest($hash_in);
    }

    public function test_capture_given_auth_with_subscription()
    {
        $hash_in = array('id' => 'id',
            'orderId' => '12344',
            'amount' => '106',
            'businessIndicator' => 'consumerBillPayment',
            'authInformation' => array(
                'authDate' => '2002-10-09', 'authCode' => '543216',
                'authAmount' => '12345'),
            'orderSource' => 'ecommerce',
            'card' => array(
                'type' => 'VI',
                'number' => '4100000000000000',
                'expDate' => '1210'),
            'enhancedData' => array(
                'customerReference' => 'cust ref sale1',
                'salesTax' => '1000',
                'discountAmount' => '0',
                'shippingAmount' => '0',
                'dutyAmount' => '0',
                'discountCode' => 'OneTimeDiscount11',
                'discountPercent' => '11',
                'fulfilmentMethodType' => 'DELIVERY',
                'shipmentId' => '12222222',
                'lineItemData' => array(
                    'itemSequenceNumber' => '1',
                    'itemDescription' => 'Clothes',
                    'productCode' => 'TB123',
                    'quantity' => '1',
                    'unitOfMeasure' => 'EACH',
                    'lineItemTotal' => '9900',
                    'lineItemTotalWithTax' => '10000',
                    'itemDiscountAmount' => '0',
                    'commodityCode' => '301',
                    'unitCost' => '31.02',
                    'itemCategory' => 'Aparel',
                    'itemSubCategory' => 'Clothing',
                    'shipmentId' => '12222222',
                    'subscription' => array(
                        'subscriptionId' => 'subscription',
                        'nextDeliveryDate' => '2023-01-01',
                        'periodUnit' => 'YEAR',
                        'numberOfPeriods' => '123',
                        'regularItemPrice' => '123',
                        'currentPeriod' => '123',
                    ))
            ));

        $mock = $this->getMock('cnp\sdk\CnpXmlMapper');
        $mock	->expects($this->once())
            ->method('request')
            ->with($this->matchesRegularExpression('/.*<shipmentId>12222222.*<subscription>.*<subscriptionId>subscription.*<nextDeliveryDate>2023-01-01.*<periodUnit>YEAR.*<numberOfPeriods>123.*<regularItemPrice>123.*<currentPeriod>123.*/'));
        $cnpTest = new CnpOnlineRequest();
        $cnpTest->newXML = $mock;
        $cnpTest->captureGivenAuthRequest($hash_in);
    }
}
