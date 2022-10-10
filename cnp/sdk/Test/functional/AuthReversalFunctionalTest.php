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

class AuthReversalFunctionalTest extends \PHPUnit_Framework_TestCase
{
    public static function setUpBeforeClass()
    {
        CommManager::reset();
    }

    public function test_simple_authReversal()
    {
        $hash_in = array('id' => 'id',
            'cnpTxnId' => '12345678000', 'amount' => '123',
            'payPalNotes' => 'Notes');

        $initialize = new CnpOnlineRequest();
        $authReversalResponse = $initialize->authReversalRequest($hash_in);
        $response = XmlParser::getNode($authReversalResponse, 'response');
        $this->assertEquals('000', $response);
        $location = XmlParser::getNode($authReversalResponse, 'location');
        $this->assertEquals('sandbox', $location);
    }

    function test_simple_authreversal_fields_out_of_order()
    {
        $hash_in = array('id' => 'id',
            'amount' => '123',
            'payPalNotes' => 'Notes', 'cnpTxnId' => '12345678000',);

        $initialize = new CnpOnlineRequest();
        $authReversalResponse = $initialize->authReversalRequest($hash_in);
        $response = XmlParser::getNode($authReversalResponse, 'response');
        $this->assertEquals('000', $response);
        $location = XmlParser::getNode($authReversalResponse, 'location');
        $this->assertEquals('sandbox', $location);
    }

    public function test_simple_authRev_with_additionalCOFData()
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
                'sequenceIndicator' => '86')

        );
        $initialize = new CnpOnlineRequest();
        $creditResponse = $initialize->authReversalRequest($hash_in);
        $message = XmlParser::getAttribute($creditResponse, 'cnpOnlineResponse', 'response');
        $this->assertEquals("0", $message);
        $location = XmlParser::getNode($creditResponse, 'location');
        $this->assertEquals('sandbox', $location);
    }

}
