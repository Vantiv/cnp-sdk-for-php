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


class AuthFunctionalTest extends \PHPUnit_Framework_TestCase
{

    public static function setUpBeforeClass()
    {
        CommManager::reset();
    }

    public function test_simple_auth_with_card()
    {
        $hash_in = array('id' => 'id',
            'card' => array('type' => 'VI',
                'number' => '4100000000000000',
                'expDate' => '1213',
                'cardValidationNum' => '1213'),
            'id' => '1211',
            'orderId' => '22@33',
            'reportGroup' => 'Planets',
            'orderSource' => 'ecommerce',
            'amount' => '0');

        $initialize = new CnpOnlineRequest();
        $authorizationResponse = $initialize->authorizationRequest($hash_in);
        $response = XmlParser::getNode($authorizationResponse, 'response');
        $this->assertEquals('000', $response);
        $location = XmlParser::getNode($authorizationResponse, 'location');
        $this->assertEquals('sandbox', $location);
    }

    public function test_simple_auth_with_detail_tax()
    {
        $hash_in = array('id' => 'id',
            'card' => array('type' => 'VI',
                'number' => '4100000000000000',
                'expDate' => '1213',
                'cardValidationNum' => '1213'),
            'id' => '1211',
            'orderId' => '22@33',
            'reportGroup' => 'Planets',
            'orderSource' => 'ecommerce',
            'amount' => '0',
            'enhancedData' => array(
                'detailTax0' => array(
                    'taxAmount' => '200',
                    'taxRate' => '0.06',
                    'taxIncludedInTotal' => true
                ),
                'detailTax1' => array(
                    'taxAmount' => '300',
                    'taxRate' => '0.10',
                    'taxIncludedInTotal' => true
                ),'lineItemData0' => array(
                    'itemSequenceNumber' => '1',
                    'itemDescription' => 'product 1',
                    'productCode' => '123',
                    'quantity' => 3,
                    'unitOfMeasure' => 'unit',
                    'taxAmount' => 200,
                    'detailTax' => array(
                        'taxIncludedInTotal' => true,
                        'taxAmount' => 200
                    ),
                    'itemCategory' => 'Aparel',
                    'itemSubCategory' => 'Clothing',
                    'productId' => '1001',
                    'productName' => 'N1',
                ),
                'lineItemData1' => array(
                    'itemSequenceNumber' => '2',
                    'itemDescription' => 'product 2',
                    'productCode' => '456',
                    'quantity' => 1,
                    'unitOfMeasure' => 'unit',
                    'taxAmount' => 300,
                    'detailTax' => array(
                        'taxIncludedInTotal' => true,
                        'taxAmount' => 300
                    )
                ),
                'salesTax' => '500',
                'taxExempt' => false
            ),
        );

        $initialize = new CnpOnlineRequest();
        $authorizationResponse = $initialize->authorizationRequest($hash_in);
        $response = XmlParser::getNode($authorizationResponse, 'response');
        $this->assertEquals('000', $response);
        $location = XmlParser::getNode($authorizationResponse, 'location');
        $this->assertEquals('sandbox', $location);
    }

    public function test_simple_auth_with_paypal()
    {
        $hash_in = array('id' => 'id',
            'paypal' => array("payerId" => '123@litle.com', "token" => '12321312',
                "transactionId" => '123123'),
            'id' => '1211',
            'orderId' => '2111',
            'reportGroup' => 'Planets',
            'orderSource' => 'ecommerce',
            'amount' => '123');

        $initialize = new CnpOnlineRequest();
        $authorizationResponse = $initialize->authorizationRequest($hash_in);
        $message = XmlParser::getNode($authorizationResponse, 'message');
        $this->assertEquals('Approved', $message);
        $location = XmlParser::getNode($authorizationResponse, 'location');
        $this->assertEquals('sandbox', $location);
    }

    public function test_simple_auth_with_cnpTxnId()
    {
        $hash_in = array('id' => 'id', 'reportGroup' => 'planets', 'cnpTxnId' => '1234567891234567891');

        $initialize = new CnpOnlineRequest();
        $authorizationResponse = $initialize->authorizationRequest($hash_in);
        $message = XmlParser::getAttribute($authorizationResponse, 'cnpOnlineResponse', 'message');
        $this->assertEquals("Valid Format", $message);
        $location = XmlParser::getNode($authorizationResponse, 'location');
        $this->assertEquals('sandbox', $location);
    }

    public function test_illegal_orderSource()
    {
        $hash_in = array('id' => 'id',
            'paypal' => array("payerId" => '123', "token" => '12321312',
                "transactionId" => '123123'),
            'id' => '1211',
            'orderId' => '2111',
            'reportGroup' => 'Planets',
            'orderSource' => 'notecommerce',
            'amount' => '123');

        $initialize = new CnpOnlineRequest();
        $this->setExpectedException('PHPUnit_Framework_Error_Warning');
        $authorizationResponse = $initialize->authorizationRequest($hash_in);
        $message = XmlParser::getAttribute($authorizationResponse, 'cnpOnlineResponse', 'message');
        $location = XmlParser::getNode($authorizationResponse, 'location');
        $this->assertEquals('sandbox', $location);
    }

    public function test_fields_out_of_order()
    {
        $hash_in = array('id' => 'id',
            'paypal' => array("payerId" => '123', "token" => '12321312',
                "transactionId" => '123123'),
            'id' => '1211',
            'orderId' => '2111',
            'reportGroup' => 'Planets',
            'orderSource' => 'ecommerce',
            'amount' => '123');

        $initialize = new CnpOnlineRequest();
        $authorizationResponse = $initialize->authorizationRequest($hash_in);
        $message = XmlParser::getNode($authorizationResponse, 'message');
        $this->assertEquals('Approved', $message);
        $location = XmlParser::getNode($authorizationResponse, 'location');
        $this->assertEquals('sandbox', $location);
    }

    public function test_invalid_field()
    {
        $hash_in = array('id' => 'id',
            'paypal' => array("payerId" => '123', "token" => '12321312',
                "transactionId" => '123123'),
            'id' => '1211',
            'orderId' => '2111',
            'nonexistant' => 'novalue',
            'reportGroup' => 'Planets',
            'orderSource' => 'ecommerce',
            'amount' => '123');

        $initialize = new CnpOnlineRequest();
        $authorizationResponse = $initialize->authorizationRequest($hash_in);
        $message = XmlParser::getNode($authorizationResponse, 'message');
        $this->assertEquals('Approved', $message);
        $location = XmlParser::getNode($authorizationResponse, 'location');
        $this->assertEquals('sandbox', $location);
    }

    public function test_pos_missing_field()
    {
        $hash_in = array('id' => 'id',
            'reportGroup' => 'Planets',
            'orderId' => '12344',
            'amount' => '106',
            'orderSource' => 'ecommerce',
            'pos' => array('entryMode' => '123'),
            'card' => array(
                'type' => 'VI',
                'number' => '4100000000000000',
                'expDate' => '1210'));
        $cnpTest = new CnpOnlineRequest();
        $this->setExpectedException('PHPUnit_Framework_Error_Warning');
        $retOb = $cnpTest->authorizationRequest($hash_in);
    }

    public function test_auth_with_applepay()
    {
        $hash_in = array('id' => 'id',
            'applepay' => array(
                'data' => 'string data here',
                'header' => array('applicationData' => '454657413164',
                    'ephemeralPublicKey' => '1',
                    'publicKeyHash' => '1234',
                    'transactionId' => '12345'),
                'signature' => 'signature',
                'version' => 'version 1'),
            'orderId' => '2111',
            'orderSource' => 'ecommerce',
            'id' => '654',
            'amount' => '1000');

        $initialize = new CnpOnlineRequest();
        $authorizationResponse = $initialize->authorizationRequest($hash_in);
        $response = XmlParser::getNode($authorizationResponse, 'response');
        $this->assertEquals('000', $response);
        $location = XmlParser::getNode($authorizationResponse, 'location');
        $this->assertEquals('sandbox', $location);
    }

    public function test_auth_with_applepay_issuer_unavailable()
    {
        $hash_in = array('id' => 'id',
            'applepay' => array(
                'data' => 'string data here',
                'header' => array('applicationData' => '454657413164',
                    'ephemeralPublicKey' => '1',
                    'publicKeyHash' => '1234',
                    'transactionId' => '12345'),
                'signature' => 'signature',
                'version' => 'version 1'),
            'orderId' => '2111',
            'orderSource' => 'ecommerce',
            'id' => '654',
            'amount' => '1101');

        $initialize = new CnpOnlineRequest();
        $authorizationResponse = $initialize->authorizationRequest($hash_in);
        $response = XmlParser::getNode($authorizationResponse, 'response');
        $this->assertEquals('101', $response);
        $location = XmlParser::getNode($authorizationResponse, 'location');
        $this->assertEquals('sandbox', $location);
    }

    public function test_auth_with_applepay_approved()
    {
        $hash_in = array('id' => 'id',
            'applepay' => array(
                'data' => 'string data here',
                'header' => array('applicationData' => '454657413164',
                    'ephemeralPublicKey' => '1',
                    'publicKeyHash' => '1234',
                    'transactionId' => '12345'),
                'signature' => 'signature',
                'version' => 'version 1'),
            'orderId' => '2111',
            'orderSource' => 'ecommerce',
            'id' => '654',
            'amount' => '12312');

        $initialize = new CnpOnlineRequest();
        $authorizationResponse = $initialize->authorizationRequest($hash_in);
        $response = XmlParser::getNode($authorizationResponse, 'response');
        $this->assertEquals('312', $response);
        $location = XmlParser::getNode($authorizationResponse, 'location');
        $this->assertEquals('sandbox', $location);
    }

    public function test_simple_auth_with_card_processingType_originalNetworkTransactionId_originalTransactionAmount()
    {
        $hash_in = array(
            'id' => '1211',
            'orderId' => '2111',
            'amount' => '0',
            'orderSource' => 'ecommerce',
            'card' => array('type' => 'VI',
                'number' => '4100000000000000',
                'expDate' => '1213',
                'cardValidationNum' => '1213')
        );

        $initialize = new CnpOnlineRequest();
        $authorizationResponse = $initialize->authorizationRequest($hash_in);
        $response = XmlParser::getNode($authorizationResponse, 'response');
        $this->assertEquals('000', $response);
        $location = XmlParser::getNode($authorizationResponse, 'location');
        $this->assertEquals('sandbox', $location);
    }

    public function test_simple_auth_with_networkTransactionId()
    {
        $hash_in = array(
            'id' => 'id',
            'card' => array('type' => 'VI',
                'number' => '4100000000000000',
                'expDate' => '1213',
                'cardValidationNum' => '1213'),
            'id' => '1211',
            'orderId' => '2111',
            'reportGroup' => 'Planets',
            'orderSource' => 'ecommerce',
            'amount' => '0',
            'processingType' => 'initialRecurring',
            'originalNetworkTransactionId' => 'abcdefghijklmnopqrstuvwxyz',
            'originalTransactionAmount' => '1000'
        );

        $initialize = new CnpOnlineRequest();
        $authorizationResponse = $initialize->authorizationRequest($hash_in);
        $this->assertEquals("000", XmlParser::getNode($authorizationResponse, 'response'));
        $this->assertEquals("Approved", XmlParser::getNode($authorizationResponse, 'message'));
        $this->assertEquals("sandbox", XmlParser::getNode($authorizationResponse, 'location'));
    }

    public function test_simple_auth_with_enhancedAuthResponse()
    {
        $hash_in = array(
            'card' => array(
                'type' => 'VI',
                'number' => '4100300000100000',
                'expDate' => '1213',
                'cardValidationNum' => '1213'

            ),
            'id' => '1211',
            'orderId' => '2111',
            'reportGroup' => 'Planets',
            'orderSource' => 'ecommerce',
            'amount' => '0',
            'processingType' => 'initialRecurring',
            'originalNetworkTransactionId' => 'abcdefghijklmnopqrstuvwxyz',
            'originalTransactionAmount' => '1000'
        );

        $initialize = new CnpOnlineRequest();
        $authorizationResponse = $initialize->authorizationRequest($hash_in);
        $endpoint = XmlParser::getNode($authorizationResponse, 'endpoint');
        $fieldValue = XmlParser::getNode($authorizationResponse, 'fieldValue');
        $fieldNumber = XmlParser::getAttribute($authorizationResponse, 'networkField', 'fieldNumber');
        $fieldName = XmlParser::getAttribute($authorizationResponse, 'networkField', 'fieldName');
        $location = XmlParser::getNode($authorizationResponse, 'location');

        $this->assertEquals('visa', $endpoint);
        $this->assertEquals('135798642', $fieldValue);
        $this->assertEquals('4', $fieldNumber);
        $this->assertEquals('Transaction Amount', $fieldName);
        $this->assertEquals('sandbox', $location);

    }

    public function test_simple_auth_with_card_pin()
    {
        $hash_in = array(
            'card' => array('type' => 'VI',
                'number' => '4100000000000000',
                'expDate' => '1213',
                'cardValidationNum' => '1213',
                'pin' => '34'
            ),
            'id' => '1211',
            'orderId' => '2111',
            'reportGroup' => 'Planets',
            'orderSource' => 'ecommerce',
            'amount' => '0');

        $initialize = new CnpOnlineRequest();
        $this->setExpectedException('PHPUnit_Framework_Error_Warning');
        $authorizationResponse = $initialize->authorizationRequest($hash_in);
        $message = XmlParser::getAttribute($authorizationResponse, 'cnpOnlineResponse', 'message');

    }

    public function test_simple_auth_with_card_Id_restrictions()
    {
        $hash_in = array(
            'card' => array('type' => 'VI',
                'number' => '4100000000000000',
                'expDate' => '1213',
                'cardValidationNum' => '1213'),
            'id' => '1234567890123456123456789012345678901234',
            'orderId' => '2111',
            'reportGroup' => 'Planets',
            'orderSource' => 'ecommerce',
            'amount' => '0');

        $initialize = new CnpOnlineRequest();
        $this->setExpectedException('PHPUnit_Framework_Error_Warning');
        $authorizationResponse = $initialize->authorizationRequest($hash_in);
        $message = XmlParser::getAttribute($authorizationResponse, 'cnpOnlineResponse', 'message');
    }

    public function test_simple_auth_with_lodging()
    {
        $hash_in = array(
            'card' => array('type' => 'VI',
                'number' => '4100000000000000',
                'expDate' => '1213',
                'cardValidationNum' => '1213'),
            'id' => '1231234',
            'orderId' => '2111',
            'reportGroup' => 'Planets',
            'orderSource' => 'ecommerce',
            'amount' => '0',
            'lodgingInfo' => array(
                'roomRate' => '1234',
                'roomTax' => '12',
                'numAdults' => '5',
                'lodgingCharge0' => array('name' => 'OTHER'),
                'lodgingCharge1' => array('name' => 'GIFTSHOP')
            ));

        $initialize = new CnpOnlineRequest();
        $authorizationResponse = $initialize->authorizationRequest($hash_in);
        $response = XmlParser::getAttribute($authorizationResponse, 'cnpOnlineResponse', 'response');
        $this->assertEquals('000', $response);
        $location = XmlParser::getNode($authorizationResponse, 'location');
        $this->assertEquals('sandbox', $location);
    }

    public function test_recurring_request()
    {
        $hash_in = array('id' => 'id',
            'card' => array(
                'type' => 'VI',
                'number' => '4100000000000001',
                'expDate' => '1213'
            ),
            'orderId' => '12344',
            'amount' => '2',
            'orderSource' => 'ecommerce',
            'fraudFilterOverride' => 'true',
            'recurringRequest' => array(
                'createSubscription' => array(
                    'planCode' => 'abc123',
                    'numberOfPayments' => 12
                )
            )
        );

        $initialize = new CnpOnlineRequest();
        $authorizationResponse = $initialize->authorizationRequest($hash_in);
        $response = XmlParser::getAttribute($authorizationResponse, 'cnpOnlineResponse', 'response');
        $this->assertEquals('000', $response);
        $location = XmlParser::getNode($authorizationResponse, 'location');
        $this->assertEquals('sandbox', $location);
    }

    public function test_simple_auth_with_card_skip_realtime_au_true()
    {
        $hash_in = array('id' => 'id',
            'card' => array('type' => 'VI',
                'number' => '4100000000000000',
                'expDate' => '1213',
                'cardValidationNum' => '1213'),
            'id' => '1211',
            'orderId' => '22@33',
            'reportGroup' => 'Planets',
            'orderSource' => 'ecommerce',
            'amount' => '0',
            'skipRealtimeAU' => 'true');

        $initialize = new CnpOnlineRequest();
        $authorizationResponse = $initialize->authorizationRequest($hash_in);
        $response = XmlParser::getNode($authorizationResponse, 'response');
        $this->assertEquals('000', $response);
        $location = XmlParser::getNode($authorizationResponse, 'location');
        $this->assertEquals('sandbox', $location);
    }

    public function test_simple_auth_with_card_skip_realtime_au_false()
    {
        $hash_in = array('id' => 'id',
            'card' => array('type' => 'VI',
                'number' => '4100000000000000',
                'expDate' => '1213',
                'cardValidationNum' => '1213'),
            'id' => '1211',
            'orderId' => '22@33',
            'reportGroup' => 'Planets',
            'orderSource' => 'ecommerce',
            'amount' => '0',
            'skipRealtimeAU' => 'false');

        $initialize = new CnpOnlineRequest();
        $authorizationResponse = $initialize->authorizationRequest($hash_in);
        $response = XmlParser::getNode($authorizationResponse, 'response');
        $this->assertEquals('000', $response);
        $location = XmlParser::getNode($authorizationResponse, 'location');
        $this->assertEquals('sandbox', $location);
    }


    public function test_simple_auth_with_with_MerchantCategoryCode()
    {
        $hash_in = array('id' => 'id',
            'card' => array('type' => 'VI',
                'number' => '4100000000000000',
                'expDate' => '1213',
                'cardValidationNum' => '1213'),
            'id' => '1211',
            'orderId' => '22@33',
            'reportGroup' => 'Planets',
            'orderSource' => 'ecommerce',
            'amount' => '0',
            'skipRealtimeAU' => 'false',
            'merchantCategoryCode' => '6770');

        $initialize = new CnpOnlineRequest();
        $authorizationResponse = $initialize->authorizationRequest($hash_in);
        $response = XmlParser::getNode($authorizationResponse, 'response');
        $this->assertEquals('000', $response);
        $location = XmlParser::getNode($authorizationResponse, 'location');
        $this->assertEquals('sandbox', $location);
    }

    public function test_simple_auth_with_business_indicator()
    {
        $hash_in = array('id' => 'id',
            'card' => array('type' => 'VI',
                'number' => '4100000000000000',
                'expDate' => '1213',
                'cardValidationNum' => '1213'),
            'id' => '1211',
            'orderId' => '22@33',
            'reportGroup' => 'Planets',
            'orderSource' => 'ecommerce',
            'amount' => '0',
            'businessIndicator' => 'consumerBillPayment');

        $initialize = new CnpOnlineRequest();
        $authorizationResponse = $initialize->authorizationRequest($hash_in);
        $response = XmlParser::getNode($authorizationResponse, 'response');
        $this->assertEquals('000', $response);
        $location = XmlParser::getNode($authorizationResponse, 'location');
        $this->assertEquals('sandbox', $location);
    }

    public function test_simple_auth_with_business_indicator_buyOnlinePickUpInStore()
    {
        $hash_in = array('id' => 'id',
            'card' => array('type' => 'VI',
                'number' => '4100000000000000',
                'expDate' => '1213',
                'cardValidationNum' => '1213'),
            'id' => '1211',
            'orderId' => '22@33123456789012345678901234567890',
            'reportGroup' => 'Planets',
            'orderSource' => 'ecommerce',
            'amount' => '0',
            'businessIndicator' => 'buyOnlinePickUpInStore');

        $initialize = new CnpOnlineRequest();
        $authorizationResponse = $initialize->authorizationRequest($hash_in);
        $response = XmlParser::getNode($authorizationResponse, 'response');
        $this->assertEquals('000', $response);
        $location = XmlParser::getNode($authorizationResponse, 'location');
        $this->assertEquals('sandbox', $location);
    }

    public function test_auth_with_additionalCOFData()
    {
        $hash_in = array(
            'id'=>'0001',
            'orderId' => '82364_cnpApiAuth',
            'amount' => '2870',
            'orderSource' => 'telephone',
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
            'crypto' => 'true',
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
            'card' => array(
                'type' => 'VI',
                'number' => '4005518220000002',
                'expDate' => '0150',
                'cardValidationNum' => '987',
            ),
            'businessIndicator' => 'buyOnlinePickUpInStore',
            'orderChannel' => 'IN_STORE_KIOSK',
            'fraudCheckStatus' => 'CLOSE',

        );

        $initialize = new CnpOnlineRequest();
        //print_r($hash_in);
        $authorizationResponse = $initialize->authorizationRequest($hash_in);
        $response = XmlParser::getNode($authorizationResponse, 'response');
        $this->assertEquals('000', $response);
        $location = XmlParser::getNode($authorizationResponse, 'location');
        $this->assertEquals('sandbox', $location);
    }

    public function test_auth_with_authmax_true()
    {
        $hash_in = array('id' => 'id',
            'card' => array('type' => 'VI',
                'number' => '4100000000000000',
                'expDate' => '1213',
                'cardValidationNum' => '1213'),
            'id' => '1211',
            'orderId' => '22@401',
            'reportGroup' => 'Planets',
            'orderSource' => 'ecommerce',
            'amount' => '0');

        $initialize = new CnpOnlineRequest();
        $authorizationResponse = $initialize->authorizationRequest($hash_in);

        $response = XmlParser::getNode($authorizationResponse, 'response');
        $this->assertEquals('000', $response);

        $location = XmlParser::getNode($authorizationResponse, 'location');
        $this->assertEquals('sandbox', $location);

        foreach ($authorizationResponse->getElementsByTagName('authMax') as $child) {
            $authMaxApplied = XmlParser::getNode($child, 'authMaxApplied');
            $networkTokenApplied = XmlParser::getNode($child, 'networkTokenApplied');
            $networkToken = XmlParser::getNode($child, 'networkToken');
            $authMaxResponseCode = XmlParser::getNode($child, 'authMaxResponseCode');
            $authMaxResponseMessage = XmlParser::getNode($child, 'authMaxResponseMessage');
            $this->assertEquals('true', $authMaxApplied);
            $this->assertEquals('true', $networkTokenApplied);
            $this->assertEquals('1112000199940085', $networkToken);
            $this->assertEquals('000', $authMaxResponseCode);
            $this->assertEquals('Approved', $authMaxResponseMessage);
        }
    }

    public function test_auth_with_authmax_false()
    {
        $hash_in = array('id' => 'id',
            'card' => array('type' => 'VI',
                'number' => '4100000000000000',
                'expDate' => '1213',
                'cardValidationNum' => '1213'),
            'id' => '1211',
            'orderId' => '22@402',
            'reportGroup' => 'Planets',
            'orderSource' => 'ecommerce',
            'amount' => '0');

        $initialize = new CnpOnlineRequest();
        $authorizationResponse = $initialize->authorizationRequest($hash_in);

        $response = XmlParser::getNode($authorizationResponse, 'response');
        $this->assertEquals('000', $response);

        $location = XmlParser::getNode($authorizationResponse, 'location');
        $this->assertEquals('sandbox', $location);

        foreach ($authorizationResponse->getElementsByTagName('authMax') as $child) {
            $authMaxApplied = XmlParser::getNode($child, 'authMaxApplied');
            $this->assertEquals('false', $authMaxApplied);
        }
    }

    public function test_auth_with_authmax_not_applied()
    {
        $hash_in = array('id' => 'id',
            'card' => array('type' => 'VI',
                'number' => '4100000000000000',
                'expDate' => '1213',
                'cardValidationNum' => '1213'),
            'id' => '1211',
            'orderId' => '22@403',
            'reportGroup' => 'Planets',
            'orderSource' => 'ecommerce',
            'amount' => '0');

        $initialize = new CnpOnlineRequest();
        $authorizationResponse = $initialize->authorizationRequest($hash_in);

        $response = XmlParser::getNode($authorizationResponse, 'response');
        $this->assertEquals('000', $response);

        $location = XmlParser::getNode($authorizationResponse, 'location');
        $this->assertEquals('sandbox', $location);

        $authMax = XmlParser::getNode($authorizationResponse, 'authMax');
        $this->assertEquals('', $authMax);
    }

    public function test_auth_with_elements_to_support_gp()
    {
        $hash_in = array(
            'id' => 'id',
            'orderId' => '82364_cnpApiAuth',
            'amount' => '1001',
            'orderSource' => 'telephone',
            'customerInfo' => array(
                'accountUsername' => 'username123',
                'userAccountNumber' => '7647326235897',
                'userAccountEmail' => 'dummtemail@abc.com',
                'membershipId' => '23874682304',
                'membershipPhone' => '16818807607551094758',
                'membershipEmail' => 'email@abc.com',
                'membershipName' => 'member123',
                'accountCreatedDate' => '2050-07-17',
                'userAccountPhone' => '1392345678',
            ),
            'card' => array(
                'type' => 'VI',
                'number' => '4005518220000002',
                'expDate' => '0150',
                'cardValidationNum' => '987',
            ),
            'enhancedData' => array(
                'customerReference' => 'cust ref sale1',
                'salesTax' => '1000',
                'discountAmount' => '0',
                'shippingAmount' => '0',
                'dutyAmount' => '0',
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
                ),
                'discountCode' => 'OneTimeDiscount11',
                'discountPercent' => '11',
                'fulfilmentMethodType' => 'DELIVERY',
            ),
            'lodgingInfo' => array(
                'bookingID' => 'book1234512341',
                'passengerName' => 'john cena',
                'propertyAddress' => array(
                    'name' => 'property1',
                    'city' => 'nyc',
                    'region' => 'KBA',
                    'country' => 'USA',
                ),
                'travelPackageIndicator' => 'AirlineReservation',
                'smokingPreference' => 'N',
                'numberOfRooms' => '13',
                'tollFreePhoneNumber' => '1981876578076548',
            ),
            'orderChannel' => 'PHONE',
            'fraudCheckStatus' => 'CLOSE',
            'overridePolicy' => 'merchantPolicyToDecline',
            'fsErrorCode' => 'error123',
            'merchantAccountStatus' => 'activeAccount',
            'productEnrolled' => 'GUARPAY2',
            'decisionPurpose' => 'INFORMATION_ONLY',
            'fraudSwitchIndicator' => 'PRE',
        );

        $initialize = new CnpOnlineRequest();
        $authorizationResponse = $initialize->authorizationRequest($hash_in);

        $response = XmlParser::getNode($authorizationResponse, 'response');
        $this->assertEquals('000', $response);

        $location = XmlParser::getNode($authorizationResponse, 'location');
        $this->assertEquals('sandbox', $location);

        $message = XmlParser::getNode($authorizationResponse, 'message');
        $this->assertEquals('Approved', $message);
    }

    public function test_auth_with_lodging_info()
    {
        $hash_in = array(
            'id' => 'id',
            'orderId' => '82364_cnpApiAuth',
            'amount' => '1001',
            'orderSource' => 'telephone',
            'customerInfo' => array(
                'accountUsername' => 'username123',
                'userAccountNumber' => '7647326235897',
                'userAccountEmail' => 'dummtemail@abc.com',
                'membershipId' => '23874682304',
                'membershipPhone' => '16818807607551094758',
                'membershipEmail' => 'email@abc.com',
                'membershipName' => 'member123',
                'accountCreatedDate' => '2050-07-17',
                'userAccountPhone' => '1392345678',
            ),
            'card' => array(
                'type' => 'VI',
                'number' => '4005518220000002',
                'expDate' => '0150',
                'cardValidationNum' => '987',
            ),
            'enhancedData' => array(
                'customerReference' => 'cust ref sale1',
                'salesTax' => '1000',
                'discountAmount' => '0',
                'shippingAmount' => '0',
                'dutyAmount' => '0',
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
                ),
                'discountCode' => 'OneTimeDiscount11',
                'discountPercent' => '11',
                'fulfilmentMethodType' => 'DELIVERY',
            ),
            'lodgingInfo' => array(
                'bookingID' => 'book1234512341',
                'passengerName' => 'john cena',
                'propertyAddress' => array(
                    'name' => 'property1',
                    'city' => 'nyc',
                    'region' => 'KBA',
                    'country' => 'USA',
                ),
                'travelPackageIndicator' => 'Both',
                'smokingPreference' => 'N',
                'numberOfRooms' => '13',
                'tollFreePhoneNumber' => '1981876578076548',
            ),
            'orderChannel' => 'PHONE',
            'fraudCheckStatus' => 'CLOSE',
            'overridePolicy' => 'merchantPolicyToDecline',
            'fsErrorCode' => 'error123',
            'merchantAccountStatus' => 'activeAccount',
            'productEnrolled' => 'GUARPAY3',
            'decisionPurpose' => 'CONSIDER_DECISION',
            'fraudSwitchIndicator' => 'POST',
        );

        $initialize = new CnpOnlineRequest();
        $authorizationResponse = $initialize->authorizationRequest($hash_in);

        $response = XmlParser::getNode($authorizationResponse, 'response');
        $this->assertEquals('000', $response);

        $location = XmlParser::getNode($authorizationResponse, 'location');
        $this->assertEquals('sandbox', $location);

        $message = XmlParser::getNode($authorizationResponse, 'message');
        $this->assertEquals('Approved', $message);
    }

    public function test_simple_auth_with_passengerTransportData()
    {
        $hash_in = array('id' => 'id',
            'card' => array('type' => 'VI',
                'number' => '4100000000000000',
                'expDate' => '1213',
                'cardValidationNum' => '1213'),
            'id' => '1211',
            'orderId' => '22@403',
            'reportGroup' => 'Planets',
            'orderSource' => 'ecommerce',
            'amount' => '0',
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
        $authorizationResponse = $initialize->authorizationRequest($hash_in);

        $response = XmlParser::getNode($authorizationResponse, 'response');
        $this->assertEquals('000', $response);

        $location = XmlParser::getNode($authorizationResponse, 'location');
        $this->assertEquals('sandbox', $location);

        $authMax = XmlParser::getNode($authorizationResponse, 'authMax');
        $this->assertEquals('', $authMax);
    }

}
