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
class VoidUnitTest extends \PHPUnit_Framework_TestCase
{
    public function test_simple_echeckRedeposit()
    {
        $hash_in = array('cnpTxnId' =>'123123','reportGroup'=>'Planets','id' => 'id',);
        $mock = $this->getMock('cnp\sdk\CnpXmlMapper');
        $mock->expects($this->once())
        ->method('request')
        ->with($this->matchesRegularExpression('/.*<cnpTxnId>123123.*/'));

        $cnpTest = new CnpOnlineRequest();
        $cnpTest->newXML = $mock;
        $cnpTest->voidRequest($hash_in);
    }
    public function test_no_cnpTxnId()
    {
        $hash_in = array('reportGroup'=>'Planets','id' => 'id');
        $cnpTest = new CnpOnlineRequest();
        $this->setExpectedException('InvalidArgumentException',"Missing Required Field: /cnpTxnId/");
        $retOb = $cnpTest->voidRequest($hash_in);
    }

    public function test_loggedInUser()
    {
        $hash_in = array('id' => 'id',
                'cnpTxnId' =>'123123',
                'merchantSdk'=>'PHP;8.14.0',
                'reportGroup'=>'Planets',
                'loggedInUser'=>'gdake');
        $mock = $this->getMock('cnp\sdk\CnpXmlMapper');
        $mock->expects($this->once())
        ->method('request')
        ->with($this->matchesRegularExpression('/.*merchantSdk="PHP;8.14.0".*loggedInUser="gdake" xmlns=.*>.*/'));

        $cnpTest = new CnpOnlineRequest();
        $cnpTest->newXML = $mock;
        $cnpTest->voidRequest($hash_in);
    }
}
