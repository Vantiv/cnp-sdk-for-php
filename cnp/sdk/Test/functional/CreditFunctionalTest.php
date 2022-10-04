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

class CreditFunctionalTest extends \PHPUnit_Framework_TestCase
{
    public static function setUpBeforeClass()
    {
        CommManager::reset();
    }

    public function test_simple_credit_with_card()
    {
        $hash_in = array(
            'card' => array('type' => 'VI', 'id' => 'id',
                'number' => '4100000000000000',
                'expDate' => '1213',
                'cardValidationNum' => '1213'),
            'id' => '1211',
            'orderId' => '2111',
            'reportGroup' => 'Planets',
            'orderSource' => 'ecommerce',
            'amount' => '123');

        $initialize = new CnpOnlineRequest();
        $creditResponse = $initialize->creditRequest($hash_in);
        $response = XmlParser::getNode($creditResponse, 'response');
        $this->assertEquals('000', $response);
        $location = XmlParser::getNode($creditResponse, 'location');
        $this->assertEquals('sandbox', $location);
    }

    public function test_simple_credit_with_paypal()
    {
        $hash_in = array('id' => 'id',
            'paypal' => array("payerId" => '123', 'payerEmail' => '12321321',
                "transactionId" => '123123'),
            'id' => '1211',
            'orderId' => '2111',
            'reportGroup' => 'Planets',
            'orderSource' => 'ecommerce',
            'amount' => '123');

        $initialize = new CnpOnlineRequest();
        $this->setExpectedException('PHPUnit_Framework_Error_Warning');
        $creditResponse = $initialize->creditRequest($hash_in);
        $message = XmlParser::getAttribute($creditResponse, 'cnpOnlineResponse', 'message');
    }

    public function test_simple_credit_with_cnpTxnId()
    {
        $hash_in = array('id' => 'id', 'reportGroup' => 'planets', 'cnpTxnId' => '1234567891234567891');

        $initialize = new CnpOnlineRequest();
        $creditResponse = $initialize->creditRequest($hash_in);
        $message = XmlParser::getAttribute($creditResponse, 'cnpOnlineResponse', 'response');
        $this->assertEquals("0", $message);
        $location = XmlParser::getNode($creditResponse, 'location');
        $this->assertEquals('sandbox', $location);
    }

    public function test_paypal_notes()
    {
        $hash_in = array('id' => 'id',
            'card' => array('type' => 'VI',
                'number' => '4100000000000000',
                'expDate' => '1213',
                'cardValidationNum' => '1213'),
            'id' => '1211',
            'payPalNotes' => 'hello',
            'orderId' => '2111',
            'reportGroup' => 'Planets',
            'orderSource' => 'ecommerce',
            'amount' => '123');

        $initialize = new CnpOnlineRequest();
        $creditResponse = $initialize->creditRequest($hash_in);
        $response = XmlParser::getNode($creditResponse, 'response');
        $this->assertEquals('000', $response);
        $location = XmlParser::getNode($creditResponse, 'location');
        $this->assertEquals('sandbox', $location);
    }

    public function test_simple_credit_with_secondary_amount()
    {
        $hash_in = array('id' => 'id',
            'card' => array('type' => 'VI',
                'number' => '4100000000000000',
                'expDate' => '1213',
                'cardValidationNum' => '1213'),
            'id' => '1211',
            'orderId' => '2111',
            'reportGroup' => 'Planets',
            'orderSource' => 'ecommerce',
            'amount' => '123',
            'secondaryAmount' => '1234');

        $initialize = new CnpOnlineRequest();
        $creditResponse = $initialize->creditRequest($hash_in);
        $response = XmlParser::getNode($creditResponse, 'response');
        $this->assertEquals('000', $response);
        $location = XmlParser::getNode($creditResponse, 'location');
        $this->assertEquals('sandbox', $location);
    }

    public function test_simple_credit_with_cnpTxnId_AndSecondaryAmount()
    {
        $hash_in = array('id' => 'id', 'reportGroup' => 'planets', 'cnpTxnId' => '1234567891234567891', 'secondaryAmount' => '100');

        $initialize = new CnpOnlineRequest();
        $creditResponse = $initialize->creditRequest($hash_in);
        $message = XmlParser::getAttribute($creditResponse, 'cnpOnlineResponse', 'response');
        $this->assertEquals("0", $message);
        $location = XmlParser::getNode($creditResponse, 'location');
        $this->assertEquals('sandbox', $location);
    }

    public function test_simple_credit_with_pin()
    {
        $hash_in = array(
            'cnpTxnId' => '12312312',
            'id' => 'id',
            'reportGroup' => 'Planets',
            'amount' => '123',
            'secondaryAmount' => '3214',
            'surchargeAmount' => '1',
            'pin' => '3333'
        );

        $initialize = new CnpOnlineRequest();
        $creditResponse = $initialize->creditRequest($hash_in);
        $message = XmlParser::getAttribute($creditResponse, 'cnpOnlineResponse', 'response');
        $this->assertEquals("0", $message);
        $location = XmlParser::getNode($creditResponse, 'location');
        $this->assertEquals('sandbox', $location);
    }

    public function test_simple_credit_with_card_with_MerchantCategoryCode()
    {
        $hash_in = array(
            'card' => array('type' => 'VI', 'id' => 'id',
                'number' => '4100000000000000',
                'expDate' => '1213',
                'cardValidationNum' => '1213'),
            'id' => '1211',
            'orderId' => '2111',
            'reportGroup' => 'Planets',
            'orderSource' => 'ecommerce',
            'amount' => '123',
            'merchantCategoryCode' => '4567');

        $initialize = new CnpOnlineRequest();
        $creditResponse = $initialize->creditRequest($hash_in);
        $response = XmlParser::getNode($creditResponse, 'response');
        $this->assertEquals('000', $response);
        $location = XmlParser::getNode($creditResponse, 'location');
        $this->assertEquals('sandbox', $location);
    }

    public function test_simple_credit_with_business_indicator()
    {
        $hash_in = array(
            'card' => array('type' => 'VI', 'id' => 'id',
                'number' => '4100000000000000',
                'expDate' => '1213',
                'cardValidationNum' => '1213'),
            'id' => '1211',
            'orderId' => '2111',
            'reportGroup' => 'Planets',
            'orderSource' => 'ecommerce',
            'amount' => '123',
            'businessIndicator' => 'consumerBillPayment');

        $initialize = new CnpOnlineRequest();
        $creditResponse = $initialize->creditRequest($hash_in);
        $response = XmlParser::getNode($creditResponse, 'response');
        $this->assertEquals('000', $response);
        $location = XmlParser::getNode($creditResponse, 'location');
        $this->assertEquals('sandbox', $location);
    }

    public function test_simple_credit_with_pin_and_optional_order_id()
    {
        $hash_in = array(
            'cnpTxnId' => '12312312',
            'orderId' => '22@33123456789012345678901234567890',
            'id' => 'id',
            'reportGroup' => 'Planets',
            'amount' => '123',
            'secondaryAmount' => '3214',
            'surchargeAmount' => '1',
            'pin' => '3333'
        );

        $initialize = new CnpOnlineRequest();
        $creditResponse = $initialize->creditRequest($hash_in);
        $message = XmlParser::getAttribute($creditResponse, 'cnpOnlineResponse', 'response');
        $this->assertEquals("0", $message);
        $location = XmlParser::getNode($creditResponse, 'location');
        $this->assertEquals('sandbox', $location);
    }

    public function test_simple_credit_with_additionalCOFData()
    {
        $hash_in = array(
            'id' => 'id',
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
                'sellerId' => '21234234A1'
            ),
            'additionalCOFData' => array(
                'totalPaymentCount' => '10',
                'paymentType' => 'Fixed Amount',
                'uniqueId' => '12',
                'frequencyOfMIT' => 'Daily',
                'validationReference' => 'AB',
                'sequenceIndicator' => '1',
            ),
            'card' => array(
            'type' => 'VI',
            'number' => '4100101411234567',
            'expDate' => '1112',
            'cardValidationNum' => '987',
        ),
        'businessIndicator' => 'buyOnlinePickUpInStore',

        );
        $initialize = new CnpOnlineRequest();
        $creditResponse = $initialize->creditRequest($hash_in);
        $message = XmlParser::getAttribute($creditResponse, 'cnpOnlineResponse', 'response');
        $this->assertEquals("0", $message);
        $location = XmlParser::getNode($creditResponse, 'location');
        $this->assertEquals('sandbox', $location);
    }

    public function test_simple_credit_with_PassengerTransportData()
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
                ),
                'tripLegData1' => array(
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
        $initialize = new CnpOnlineRequest();
        $creditResponse = $initialize->creditRequest($hash_in);
        $message = XmlParser::getAttribute($creditResponse, 'cnpOnlineResponse', 'response');
        $this->assertEquals("0", $message);
        $location = XmlParser::getNode($creditResponse, 'location');
        $this->assertEquals('sandbox', $location);
    }



}
