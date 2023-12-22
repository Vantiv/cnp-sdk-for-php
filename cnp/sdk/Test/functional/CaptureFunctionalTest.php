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

class CaptureFunctionalTest extends \PHPUnit_Framework_TestCase
{
    public static function setUpBeforeClass()
    {
        CommManager::reset();
    }

    public function test_simple_capture()
    {
        $hash_in = array('id' => 'id',
            'cnpTxnId' => '1234567891234567891',
            'amount' => '123');

        $initialize = new CnpOnlineRequest();
        $captureResponse = $initialize->captureRequest($hash_in);
        $message = XmlParser::getAttribute($captureResponse, 'cnpOnlineResponse', 'response');
        $this->assertEquals('0', $message);
        $location = XmlParser::getNode($captureResponse, 'location');
        $this->assertEquals('sandbox', $location);
    }

    public function test_complex_capture()
    {
        $hash_in = array('id' => 'id',
            'cnpTxnId' => '1234567891234567891',
            'amount' => '123', 'enhancedData' => array(
                'customerReference' => 'Litle',
                'salesTax' => '50',
                'deliveryType' => 'TBD'),
            'payPalOrderComplete' => 'true');

        $initialize = new CnpOnlineRequest();
        $captureResponse = $initialize->captureRequest($hash_in);
        $message = XmlParser::getAttribute($captureResponse, 'cnpOnlineResponse', 'response');
        $this->assertEquals('0', $message);
        $location = XmlParser::getNode($captureResponse, 'location');
        $this->assertEquals('sandbox', $location);
    }

    public function test_simple_capture_with_partial()
    {
        $hash_in = array('id' => 'id',
            'cnpTxnId' => '1234567891234567891',
            'patial' => 'true',
            'amount' => '123');

        $initialize = new CnpOnlineRequest();
        $captureResponse = $initialize->captureRequest($hash_in);
        $message = XmlParser::getAttribute($captureResponse, 'cnpOnlineResponse', 'response');
        $this->assertEquals('0', $message);
        $location = XmlParser::getNode($captureResponse, 'location');
        $this->assertEquals('sandbox', $location);
    }

    public function test_simple_capture_with_pin()
    {
        $hash_in = array('id' => 'id',
            'cnpTxnId' => '1234567891234567891',
            'amount' => '123', 'pin' => '2139');

        $initialize = new CnpOnlineRequest();
        $captureResponse = $initialize->captureRequest($hash_in);
        $message = XmlParser::getAttribute($captureResponse, 'cnpOnlineResponse', 'response');
        $this->assertEquals('0', $message);
        $location = XmlParser::getNode($captureResponse, 'location');
        $this->assertEquals('sandbox', $location);
    }

    public function test_simple_capture_with_optional_order_id()
    {
        $hash_in = array('id' => 'id',
            'cnpTxnId' => '1234567891234567891',
            'orderId' => '22@33123456789012345678901234567890',
            'amount' => '123');

        $initialize = new CnpOnlineRequest();
        $captureResponse = $initialize->captureRequest($hash_in);
        $message = XmlParser::getAttribute($captureResponse, 'cnpOnlineResponse', 'response');
        $this->assertEquals('0', $message);
        $location = XmlParser::getNode($captureResponse, 'location');
        $this->assertEquals('sandbox', $location);
    }

    public function test_simple_capture_passengerTransportData()
    {
        $hash_in = array('id' => 'id',
            'cnpTxnId' => '1234567891234567891',
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
        $captureResponse = $initialize->captureRequest($hash_in);
        $message = XmlParser::getAttribute($captureResponse, 'cnpOnlineResponse', 'response');
        $this->assertEquals('0', $message);
        $location = XmlParser::getNode($captureResponse, 'location');
        $this->assertEquals('sandbox', $location);
    }

    public function test_simple_capture_foreignRetailerIndicator()
    {
        $hash_in = array('id' => 'id',
            'cnpTxnId' => '1234567891234567891',
            'passengerTransportData' => array(
                'passengerName' => 'Mrs. Huxley234567890123456789',
                'ticketNumber' => 'ATL456789012345',
                'issuingCarrier' => 'AMTK',
                'carrierName' => 'AMTK',
                'restrictedTicketIndicator' => '99999',
                'numberOfAdults' => '2',
                'numberOfChildren' => '0',
                'customerCode' => 'Railway',
                'arrivalDate' => '2022-09-20',
                'issueDate' => '2022-09-10',
                'travelAgencyCode' => '12345678',
                'travelAgencyName' => 'Travel R Us23456789012345',
                'computerizedReservationSystem' => 'STRT',
                'creditReasonIndicator' => 'P',
                'ticketChangeIndicator' => 'C',
                'ticketIssuerAddress' => '99 Second St',
                'exchangeTicketNumber' => '123456789012346',
                'exchangeAmount' => '500046',
                'exchangeFeeAmount' => '5046',
                'tripLegData' => array(
                    'tripLegNumber' => '10',
                    'serviceClass' => 'First',
                    'departureDate' => '2022-09-20',
                    'originCity' => 'BOS')),
        'foreignRetailerIndicator' => 'F'
        );

        $initialize = new CnpOnlineRequest();
        $captureResponse = $initialize->captureRequest($hash_in);
        $message = XmlParser::getAttribute($captureResponse, 'cnpOnlineResponse', 'response');
        $this->assertEquals('0', $message);
        $location = XmlParser::getNode($captureResponse, 'location');
        $this->assertEquals('sandbox', $location);
    }

    public function test_simple_capture_with_subscription()
    {
        $hash_in = array('id' => 'id',
            'cnpTxnId' => '1234567891234567891',
            'amount' => '123', 'enhancedData' => array(
                'customerReference' => 'Litle',
                'salesTax' => '50',
                'deliveryType' => 'TBD',
            'payPalOrderComplete' => 'true',
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
                )))

        );

        $initialize = new CnpOnlineRequest();
        $captureResponse = $initialize->captureRequest($hash_in);
        $message = XmlParser::getAttribute($captureResponse, 'cnpOnlineResponse', 'response');
        $this->assertEquals('0', $message);
        $location = XmlParser::getNode($captureResponse, 'location');
        $this->assertEquals('sandbox', $location);
    }
}
