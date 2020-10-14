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

class TransactionReversalFunctionalTest extends \PHPUnit_Framework_TestCase
{
    public static function setUpBeforeClass()
    {
        CommManager::reset();
    }

    public function test_simple_transactionReversal()
    {
        $hash_in = array(
            'id' => 'id',
            'cnpTxnId' => '12345678000',
            'amount' => '123',
            'pin' => '1234',
            'surchargeAmount' => '4321'
        );

        $initilaize = new CnpOnlineRequest();
        $transactionReversalResponse = $initilaize->transactionReversal($hash_in);

        $response = XmlParser::getNode($transactionReversalResponse, 'response');
        $this->assertEquals('000', $response);

        $response = XmlParser::getNode($transactionReversalResponse, 'message');
        $this->assertEquals('Approved', $response);

        $location = XmlParser::getNode($transactionReversalResponse, 'location');
        $this->assertEquals('sandbox', $location);

        $cnpTxnId = XmlParser::getNode($transactionReversalResponse, 'creditCnpTxnId');
        this->assertEquals('12345678000', $cnpTxnId);
    }

}
