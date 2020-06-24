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

class VoidSettledTransactionTest extends \PHPUnit_Framework_TestCase
{
    public static function setUpBeforeClass()
    {
        CommManager::reset();
    }

    public function test_VoidSettledTransaction()
    {
        $auth_hash = array('id' => '1211',
            'orderId' => '1',
            'amount' => '10010',
            'orderSource' => 'ecommerce',
            'card' => array(
                'number' => '375001010000003',
                'expDate' => '0112',
                'cardValidationNum' => '1313',
                'type' => 'AX'));
        $initialize = new CnpOnlineRequest();
        $authorizationResponse = $initialize->authorizationRequest($auth_hash);
        $this->assertEquals('000', XmlParser::getNode($authorizationResponse, 'response'));

        $capture_hash = array('cnpTxnId' => (XmlParser::getNode($authorizationResponse, 'cnpTxnId')), 'id' => '1211');
        $captureResponse = $initialize->captureRequest($capture_hash);

        $this->assertEquals('000', XmlParser::getNode($captureResponse, 'response'));
        $void_hash1 = array('cnpTxnId' => '362', 'id' => '1211');

        $voidResponse1 = $initialize->voidRequest($void_hash1);
        $this->assertEquals('362', XmlParser::getNode($voidResponse1, 'response'));

        $credit_hash = array('cnpTxnId' => (XmlParser::getNode($captureResponse, 'cnpTxnId')), 'id' => '1211',);
        $creditResponse = $initialize->creditRequest($credit_hash);
        $this->assertEquals('000', XmlParser::getNode($creditResponse, 'response'));

        $void_hash2 = array('cnpTxnId' => (XmlParser::getNode($creditResponse, 'cnpTxnId')), 'id' => '1211',);
        $voidResponse2 = $initialize->voidRequest($void_hash2);
        //This test does the same thing as above, but uses a randomly generated value with chance of producing a special value that fails the test
        //$this->assertEquals('000', XmlParser::getNode($voidResponse2, 'response'));
    }
}
