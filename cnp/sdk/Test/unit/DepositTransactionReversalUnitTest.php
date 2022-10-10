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


class DepositTransactionReversalUnitTest extends \PHPUnit_Framework_TestCase
{
    public static function setUpBeforeClass()
    {
        CommManager::reset();
    }

    public function test_simple_depositTransactionReversal()
    {
        $hash_in = array(
            'id' => 'id',
            'cnpTxnId' => '12345678000',
            'amount' => '123',
            'pin' => '1234',
            'surchargeAmount' => '4321'
        );



        $mock = $this->getMock('cnp\sdk\CnpXmlMapper');
        $mock	->expects($this->once())
            ->method('request')
            ->with($this->matchesRegularExpression('/.*<cnpTxnId>12345678000.*<amount>123.*<pin>1234.*/'));


        $cnpTest = new CnpOnlineRequest();
        $cnpTest->newXML = $mock;
        $cnpTest->DepositTransactionReversal($hash_in);
    }

    public function test_depositeTranRever_with_passengerTransportData()
    {
        $hash_in = array(
            'id' => 'id',
            'cnpTxnId' => '12345678000',
            'passengerTransportData' => array(
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
                'tripLegData' => array(
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
                    'remarks' => 'This is a max 80 chars'
                )
            )
        );

        $mock = $this->getMock('cnp\sdk\CnpXmlMapper');
        $mock	->expects($this->once())
            ->method('request')
            ->with($this->matchesRegularExpression('/.*<passengerName>Mrs. Huxley234567890123456789.*<ticketNumber>ATL456789012345.*<exchangeAmount>500046.*<serviceClass>Business.*<originCity>BOS.*/'));

        $cnpTest = new CnpOnlineRequest();
        $cnpTest->newXML = $mock;
        $cnpTest->DepositTransactionReversal($hash_in);
    }

}
