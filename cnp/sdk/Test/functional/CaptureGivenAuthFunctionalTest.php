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
namespace cnp\sdk\Test\functional;

use cnp\sdk\CnpOnlineRequest;
use cnp\sdk\CommManager;
use cnp\sdk\XmlParser;

class CaptureGivenAuthFunctionalTest extends \PHPUnit_Framework_TestCase
{
    public static function setUpBeforeClass()
    {
        CommManager::reset();
    }

    public function test_simple_captureGivenAuth()
    {
        $hash_in = array('id' => 'id',
            'orderId' => '12344',
            'amount' => '106',
            'authInformation' => array(
                'authDate' => '2002-10-09', 'authCode' => '543216',
                'authAmount' => '12345'),
            'orderSource' => 'ecommerce',
            'card' => array(
                'type' => 'VI',
                'number' => '4100000000000000',
                'expDate' => '1210'));

        $initialize = new CnpOnlineRequest();
        $captureGivenAuthResponse = $initialize->captureGivenAuthRequest($hash_in);
        $message = XmlParser::getNode($captureGivenAuthResponse, 'message');
        $this->assertEquals('Approved', $message);
        $location = XmlParser::getNode($captureGivenAuthResponse, 'location');
        $this->assertEquals('sandbox', $location);
    }

    public function test_simple_captureGivenAuth_with_token()
    {
        $hash_in = array('id' => 'id',
            'orderId' => '12344',
            'amount' => '106',
            'authInformation' => array(
                'authDate' => '2002-10-09', 'authCode' => '543216',
                'authAmount' => '12345'),
            'orderSource' => 'ecommerce',
            'token' => array(
                'type' => 'VI',
                'cnpToken' => '123456789101112',
                'expDate' => '1210'));

        $initialize = new CnpOnlineRequest();
        $captureGivenAuthResponse = $initialize->captureGivenAuthRequest($hash_in);
        $message = XmlParser::getNode($captureGivenAuthResponse, 'message');
        $this->assertEquals('Approved', $message);
        $location = XmlParser::getNode($captureGivenAuthResponse, 'location');
        $this->assertEquals('sandbox', $location);
    }

    public function test_complex_captureGivenAuth()
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
                'expDate' => '1210'));

        $initialize = new CnpOnlineRequest();
        $captureGivenAuthResponse = $initialize->captureGivenAuthRequest($hash_in);
        $message = XmlParser::getNode($captureGivenAuthResponse, 'message');
        $this->assertEquals('Approved', $message);
        $location = XmlParser::getNode($captureGivenAuthResponse, 'location');
        $this->assertEquals('sandbox', $location);
    }

    public function test_authInfo()
    {
        $hash_in = array('id' => 'id',
            'orderId' => '12344',
            'amount' => '106',
            'authInformation' => array(
                'authDate' => '2002-10-09', 'authCode' => '543216',
                'authAmount' => '12345', 'fraudResult' => array('avsResult' => '12', 'cardValidationResult' => '123', 'authenticationResult' => '1',
                    'advancedAVSResult' => '123')),
            'orderSource' => 'ecommerce',
            'card' => array(
                'type' => 'VI',
                'number' => '4100000000000000',
                'expDate' => '1210'));

        $initialize = new CnpOnlineRequest();
        $captureGivenAuthResponse = $initialize->captureGivenAuthRequest($hash_in);
        $message = XmlParser::getNode($captureGivenAuthResponse, 'message');
        $this->assertEquals('Approved', $message);
        $location = XmlParser::getNode($captureGivenAuthResponse, 'location');
        $this->assertEquals('sandbox', $location);
    }

    public function test_simple_captureGivenAuth_secondary_amount()
    {
        $hash_in = array('id' => 'id',
            'orderId' => '12344',
            'amount' => '106',
            'secondaryAmount' => '2000',
            'authInformation' => array(
                'authDate' => '2002-10-09', 'authCode' => '543216',
                'authAmount' => '12345'),
            'orderSource' => 'ecommerce',
            'card' => array(
                'type' => 'VI',
                'number' => '4100000000000000',
                'expDate' => '1210'));

        $initialize = new CnpOnlineRequest();
        $captureGivenAuthResponse = $initialize->captureGivenAuthRequest($hash_in);
        $message = XmlParser::getNode($captureGivenAuthResponse, 'message');
        $this->assertEquals('Approved', $message);
        $location = XmlParser::getNode($captureGivenAuthResponse, 'location');
        $this->assertEquals('sandbox', $location);
    }

    public function test_simple_captureGivenAuth_with_processingType_orgntwtxnid_orgtxnamt()
    {
        $hash_in = array(
            'id' => 'id',
            'orderId' => '12344',
            'amount' => '106',
            'authInformation' => array(
                'authDate' => '2002-10-09',
                'authCode' => '543216',
                'authAmount' => '12345'
            ),
            'orderSource' => 'ecommerce',
            'card' => array(
                'type' => 'VI',
                'number' => '4100000000000000',
                'expDate' => '1210'),
            'processingType' => 'initialRecurring',
            'originalNetworkTransactionId' => 'abcdefghijklmnopqrstuvwxyz',
            'originalTransactionAmount' => '1000'
        );

        $initialize = new CnpOnlineRequest();
        $captureGivenAuthResponse = $initialize->captureGivenAuthRequest($hash_in);
        $message = XmlParser::getNode($captureGivenAuthResponse, 'message');
        $this->assertEquals('Approved', $message);
        $location = XmlParser::getNode($captureGivenAuthResponse, 'location');
        $this->assertEquals('sandbox', $location);
    }

        public function test_simple_capture_given_auth_with_tokenURL()
        {
            $hash_in = array(
            'merchantId' => '101',
          //  'version'=>'8.8',
            'id'=>'id',
            'reportGroup'=>'Planets',
            'orderId'=>'12344',
            'authInformation' => array(
                    'authDate'=>'2002-10-09','authCode'=>'543216', 'processingInstructions' => array ('bypassVelocityCheck'=>'true'),
            'authAmount'=>'12345'),
                      'amount'=>'106',
                      'orderSource'=>'ecommerce',
                      'token'=> array(
                          'tokenURL' => 'http://token.com/sales',
                          'expDate'=>'1210',
                          'cardValidationNum'=>'555',
                          'type'=>'VI'
                      ));
            $initialize = new CnpOnlineRequest();
            $captureGivenAuthResponse = $initialize->captureGivenAuthRequest($hash_in);
            $message = XmlParser::getNode($captureGivenAuthResponse, 'message');
            $this->assertEquals('Approved', $message);
            $location = XmlParser::getNode($captureGivenAuthResponse, 'location');
            $this->assertEquals('sandbox', $location);

        }




    public function test_simple_captureGivenAuth_with_MerchantCategoryCode()
    {
        $hash_in = array('id' => 'id',
            'orderId' => '12344',
            'amount' => '106',
            'authInformation' => array(
                'authDate' => '2002-10-09', 'authCode' => '543216',
                'authAmount' => '12345'),
            'orderSource' => 'ecommerce',
            'card' => array(
                'type' => 'VI',
                'number' => '4100000000000000',
                'expDate' => '1210'),
            'merchantCategoryCode' => '3535');

        $initialize = new CnpOnlineRequest();
        $captureGivenAuthResponse = $initialize->captureGivenAuthRequest($hash_in);
        $message = XmlParser::getNode($captureGivenAuthResponse, 'message');
        $this->assertEquals('Approved', $message);
        $location = XmlParser::getNode($captureGivenAuthResponse, 'location');
        $this->assertEquals('sandbox', $location);
    }

    public function test_simple_captureGivenAuth_with_token_with_MerchantCategoryCode()
    {
        $hash_in = array('id' => 'id',
            'orderId' => '12344',
            'amount' => '106',
            'authInformation' => array(
                'authDate' => '2002-10-09', 'authCode' => '543216',
                'authAmount' => '12345'),
            'orderSource' => 'ecommerce',
            'token' => array(
                'type' => 'VI',
                'cnpToken' => '123456789101112',
                'expDate' => '1210'),
            'merchantCategoryCode' => '2424');

        $initialize = new CnpOnlineRequest();
        $captureGivenAuthResponse = $initialize->captureGivenAuthRequest($hash_in);
        $message = XmlParser::getNode($captureGivenAuthResponse, 'message');
        $this->assertEquals('Approved', $message);
        $location = XmlParser::getNode($captureGivenAuthResponse, 'location');
        $this->assertEquals('sandbox', $location);
    }

    public function test_complex_captureGivenAuth_with_MerchantCategoryCode()
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
            'merchantCategoryCode' => '1234');

        $initialize = new CnpOnlineRequest();
        $captureGivenAuthResponse = $initialize->captureGivenAuthRequest($hash_in);
        $message = XmlParser::getNode($captureGivenAuthResponse, 'message');
        $this->assertEquals('Approved', $message);
        $location = XmlParser::getNode($captureGivenAuthResponse, 'location');
        $this->assertEquals('sandbox', $location);
    }

    public function test_authInfo_with_MerchantCategoryCode()
    {
        $hash_in = array('id' => 'id',
            'orderId' => '12344',
            'amount' => '106',
            'authInformation' => array(
                'authDate' => '2002-10-09', 'authCode' => '543216',
                'authAmount' => '12345', 'fraudResult' => array('avsResult' => '12', 'cardValidationResult' => '123', 'authenticationResult' => '1',
                    'advancedAVSResult' => '123')),
            'orderSource' => 'ecommerce',
            'card' => array(
                'type' => 'VI',
                'number' => '4100000000000000',
                'expDate' => '1210'),
            'merchantCategoryCode' => '2424');

        $initialize = new CnpOnlineRequest();
        $captureGivenAuthResponse = $initialize->captureGivenAuthRequest($hash_in);
        $message = XmlParser::getNode($captureGivenAuthResponse, 'message');
        $this->assertEquals('Approved', $message);
        $location = XmlParser::getNode($captureGivenAuthResponse, 'location');
        $this->assertEquals('sandbox', $location);
    }

    public function test_simple_captureGivenAuth_secondary_amount_with_MerchantCategoryCode()
    {
        $hash_in = array('id' => 'id',
            'orderId' => '12344',
            'amount' => '106',
            'secondaryAmount' => '2000',
            'authInformation' => array(
                'authDate' => '2002-10-09', 'authCode' => '543216',
                'authAmount' => '12345'),
            'orderSource' => 'ecommerce',
            'card' => array(
                'type' => 'VI',
                'number' => '4100000000000000',
                'expDate' => '1210'),
            'merchantCategoryCode' => '5678');

        $initialize = new CnpOnlineRequest();
        $captureGivenAuthResponse = $initialize->captureGivenAuthRequest($hash_in);
        $message = XmlParser::getNode($captureGivenAuthResponse, 'message');
        $this->assertEquals('Approved', $message);
        $location = XmlParser::getNode($captureGivenAuthResponse, 'location');
        $this->assertEquals('sandbox', $location);
    }

    public function test_simple_captureGivenAuth_with_processingType_orgntwtxnid_orgtxnamt_with_MerchantCategoryCode()
    {
        $hash_in = array(
            'id' => 'id',
            'orderId' => '12344',
            'amount' => '106',
            'authInformation' => array(
                'authDate' => '2002-10-09',
                'authCode' => '543216',
                'authAmount' => '12345'
            ),
            'orderSource' => 'ecommerce',
            'card' => array(
                'type' => 'VI',
                'number' => '4100000000000000',
                'expDate' => '1210'),
            'processingType' => 'initialRecurring',
            'originalNetworkTransactionId' => 'abcdefghijklmnopqrstuvwxyz',
            'originalTransactionAmount' => '1000',
            'merchantCategoryCode' => '1234'
        );

        $initialize = new CnpOnlineRequest();
        $captureGivenAuthResponse = $initialize->captureGivenAuthRequest($hash_in);
        $message = XmlParser::getNode($captureGivenAuthResponse, 'message');
        $this->assertEquals('Approved', $message);
        $location = XmlParser::getNode($captureGivenAuthResponse, 'location');
        $this->assertEquals('sandbox', $location);
    }

    public function test_simple_capture_given_auth_with_tokenURL_with_MerchantCategoryCode()
    {
        $hash_in = array(
            'merchantId' => '101',
            //  'version'=>'8.8',
            'id'=>'id',
            'reportGroup'=>'Planets',
            'orderId'=>'12344',
            'authInformation' => array(
                'authDate'=>'2002-10-09','authCode'=>'543216', 'processingInstructions' => array ('bypassVelocityCheck'=>'true'),
                'authAmount'=>'12345'),
            'amount'=>'106',
            'orderSource'=>'ecommerce',
            'token'=> array(
                'tokenURL' => 'http://token.com/sales',
                'expDate'=>'1210',
                'cardValidationNum'=>'555',
                'type'=>'VI'
            ),
            'merchantCategoryCode' => '4567');
        $initialize = new CnpOnlineRequest();
        $captureGivenAuthResponse = $initialize->captureGivenAuthRequest($hash_in);
        $message = XmlParser::getNode($captureGivenAuthResponse, 'message');
        $this->assertEquals('Approved', $message);
        $location = XmlParser::getNode($captureGivenAuthResponse, 'location');
        $this->assertEquals('sandbox', $location);

    }


    public function test_simple_captureGivenAuth_with_business_indicator()
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
                'expDate' => '1210'));

        $initialize = new CnpOnlineRequest();
        $captureGivenAuthResponse = $initialize->captureGivenAuthRequest($hash_in);
        $message = XmlParser::getNode($captureGivenAuthResponse, 'message');
        $this->assertEquals('Approved', $message);
        $location = XmlParser::getNode($captureGivenAuthResponse, 'location');
        $this->assertEquals('sandbox', $location);
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

    public function test_captureGivenAuth_with_passengerTransportData()
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


        $initialize = new CnpOnlineRequest();
        $authorizationResponse = $initialize->captureGivenAuthRequest($hash_in);
        $response = XmlParser::getNode($authorizationResponse, 'response');
        $this->assertEquals('000', $response);
        $location = XmlParser::getNode($authorizationResponse, 'location');
        $this->assertEquals('sandbox', $location);
    }

    public function test_captureGivenAuth_with_foreignRetailerIndicator()
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


        $initialize = new CnpOnlineRequest();
        $authorizationResponse = $initialize->captureGivenAuthRequest($hash_in);
        $response = XmlParser::getNode($authorizationResponse, 'response');
        $this->assertEquals('000', $response);
        $location = XmlParser::getNode($authorizationResponse, 'location');
        $this->assertEquals('sandbox', $location);
    }
}
