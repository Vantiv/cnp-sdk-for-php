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
namespace cnp\sdk;
 class AuthReversalUnitTest extends \PHPUnit_Framework_TestCase
{
     public static function setUpBeforeClass()
     {
         CommManager::reset();
     }

     public function test_capture()
    {
        $hash_in = array('cnpTxnId'=> '1234567890','reportGroup'=>'Planets', 'amount'=>'5000','id' => 'id',);
        $mock = $this->getMock('cnp\sdk\CnpXmlMapper');
        $mock	->expects($this->once())
        ->method('request')
        ->with($this->matchesRegularExpression('/.*<cnpTxnId>1234567890.*<amount>5000.*/'));

        $cnpTest = new CnpOnlineRequest();
        $cnpTest->newXML = $mock;
        $cnpTest->authReversalRequest($hash_in);
    }

    public function test_no_txnid()
    {
        $hash_in =array('reportGroup'=>'Planets','id' => 'id','amount'=>'106');
        $cnpTest = new CnpOnlineRequest();
        $this->setExpectedException('PHPUnit_Framework_Error_Warning');
        $retOb = $cnpTest->authReversalRequest($hash_in);
    }

    public function test_loggedInUser()
    {
        $hash_in = array(
                'cnpTxnId'=> '1234567890',
                'reportGroup'=>'Planets',
        		'id' => 'id',
                'amount'=>'5000',
                'merchantSdk'=>'PHP;8.14.0',
                'loggedInUser'=>'gdake'
        );
        $mock = $this->getMock('cnp\sdk\CnpXmlMapper');
        $mock	->expects($this->once())
        ->method('request')
        ->with($this->matchesRegularExpression('/.*merchantSdk="PHP;8.14.0".*loggedInUser="gdake" xmlns=.*>.*/'));

        $cnpTest = new CnpOnlineRequest();
        $cnpTest->newXML = $mock;
        $cnpTest->authReversalRequest($hash_in);
    }

    public function test_surchargeAmount()
    {
        $hash_in = array(
            'cnpTxnId'=>'3',
            'amount'=>'2',
        	'id' => 'id',
            'surchargeAmount'=>'1',
            'payPalNotes'=>'notes',
        );
        $mock = $this->getMock('cnp\sdk\CnpXmlMapper');
        $mock
            ->expects($this->once())
            ->method('request')
            ->with($this->matchesRegularExpression('/.*<amount>2<\/amount><surchargeAmount>1<\/surchargeAmount><payPalNotes>notes<\/payPalNotes>.*/'));

        $cnpTest = new CnpOnlineRequest();
        $cnpTest->newXML = $mock;
        $cnpTest->authReversalRequest($hash_in);
    }

    public function test_surchargeAmount_optional()
    {
        $hash_in = array(
                'cnpTxnId'=>'3',
        		'id' => 'id',
                'amount'=>'2',
                'payPalNotes'=>'notes',
        );
        $mock = $this->getMock('cnp\sdk\CnpXmlMapper');
        $mock
        ->expects($this->once())
        ->method('request')
        ->with($this->matchesRegularExpression('/.*<amount>2<\/amount><payPalNotes>notes<\/payPalNotes>.*/'));

        $cnpTest = new CnpOnlineRequest();
        $cnpTest->newXML = $mock;
        $cnpTest->authReversalRequest($hash_in);
    }

}
