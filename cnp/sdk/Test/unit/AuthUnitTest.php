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


class AuthUnitTest extends \PHPUnit_Framework_TestCase
{

    public static function setUpBeforeClass()
    {
        CommManager::reset();
    }

    public function test_simple_auth_with_lineItemData0()
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
                    ))

            );



            $mock = $this->getMock('cnp\sdk\CnpXmlMapper');
            $mock	->expects($this->once())
                ->method('request')
                ->with($this->matchesRegularExpression('/.*<itemCategory>Aparel.*<itemSubCategory>Clothing.*<productId>1001.*<productName>N1.*/'));

            $cnpTest = new CnpOnlineRequest();
            $cnpTest->newXML = $mock;
            $cnpTest->authorizationRequest($hash_in);
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

            $mock = $this->getMock('cnp\sdk\CnpXmlMapper');
            $mock	->expects($this->once())
                ->method('request')
                ->with($this->matchesRegularExpression('/.*<sellerId>21234234A.*<url>www.google.com.*<businessIndicator>buyOnlinePickUpInStore.*<orderChannel>IN_STORE_KIOSK.*<fraudCheckStatus>CLOSE.*/'));

            $cnpTest = new CnpOnlineRequest();
            $cnpTest->newXML = $mock;
            $cnpTest->authorizationRequest($hash_in);
    }



}
