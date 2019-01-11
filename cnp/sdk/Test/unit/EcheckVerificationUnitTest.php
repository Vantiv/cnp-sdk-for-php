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

class EcheckVerificationUnitTest extends \PHPUnit_Framework_TestCase
{
    public static function setUpBeforeClass()
    {
        CommManager::reset();
    }

    public function test_simple_echeckVerification()
    {
         $hash_in = array(
             'id' => 'id',
             'orderId'=>'123',
             'amount'=>'123',
             'orderSource'=>'ecommerce',
             'billToAddress' => array(),
             'echeckToken' => array('cnpToken'=>'1234565789012','routingNum'=>'123123123','accType'=>'Checking'));



         $mock = $this->getMock('cnp\sdk\CnpXmlMapper');
         $mock->expects($this->once())
         ->method('request')
         ->with($this->matchesRegularExpression('/.*<echeckToken>.*<accType>Checking.*/'));

         $cnpTest = new CnpOnlineRequest();
         $cnpTest->newXML = $mock;
         $cnpTest->echeckSaleRequest($hash_in);
    }
    public function test_no_amount()
    {
        $hash_in = array(
        'reportGroup'=>'Planets',
        'id' => 'id',
        'orderId'=>'12344');
        $cnpTest = new CnpOnlineRequest();
        $this->setExpectedException('PHPUnit_Framework_Error_Warning');
        $retOb = $cnpTest->echeckVerificationRequest($hash_in);
    }
    public function test_no_orderId()
    {
        $hash_in = array(
            'reportGroup'=>'Planets','id' => 'id',
            'amount'=>'123');
        $cnpTest = new CnpOnlineRequest();
        $this->setExpectedException('PHPUnit_Framework_Error_Warning');
        $retOb = $cnpTest->echeckVerificationRequest($hash_in);
    }
    public function test_no_orderSounce()
    {
        $hash_in = array(
            'reportGroup'=>'Planets','id' => 'id',
            'amount'=>'123',
            'orderId'=>'12344');
        $cnpTest = new CnpOnlineRequest();
        $this->setExpectedException('PHPUnit_Framework_Error_Warning');
        $retOb = $cnpTest->echeckVerificationRequest($hash_in);
    }
    public function test_both_choices()
    {
        $hash_in = array(
            'reportGroup'=>'Planets',
            'amount'=>'123',
            'orderId'=>'123',
            'orderSource'=>'ecommerce',
            'id' => 'id',
            'echeckToken' => array('accType'=>'Checking','routingNum'=>'123123','cnpToken'=>'1234565789012','checkNum'=>'123455'),
            'echeck' => array('accType'=>'Checking','routingNum'=>'123123','accNum'=>'12345657890','checkNum'=>'123455'));
        $cnpTest = new CnpOnlineRequest();
        $this->setExpectedException('PHPUnit_Framework_Error_Warning');
        $retOb = $cnpTest->echeckVerificationRequest($hash_in);
    }
    public function test_loggedInUser()
    {
        $hash_in = array(
            'id' => 'id',
            'loggedInUser'=>'gdake',
            'merchantSdk'=>'PHP;10.1.0',
            'orderId'=>'123',
            'amount'=>'123',
            'orderSource'=>'ecommerce',
            'billToAddress'=>array(),
            'echeckToken' => array('accType'=>'Checking','routingNum'=>'123123123','cnpToken'=>'1234565789012','checkNum'=>'123455'));
        $mock = $this->getMock('cnp\sdk\CnpXmlMapper');
        $mock->expects($this->once())
        ->method('request')
        ->with($this->matchesRegularExpression('/.*merchantSdk="PHP;10.1.0".*loggedInUser="gdake" xmlns=.*>.*/'));

        $cnpTest = new CnpOnlineRequest();
        $cnpTest->newXML = $mock;
        $cnpTest->echeckVerificationRequest($hash_in);
    }

    public function test_merchantData()
    {
        $hash_in = array(
            'id' => 'id',
            'loggedInUser'=>'gdake',
            'merchantSdk'=>'PHP;10.1.0',
            'orderId'=>'123',
            'amount'=>'123',
            'orderSource'=>'ecommerce',
            'billToAddress'=>array(),
            'echeckToken' => array('accType'=>'Checking','routingNum'=>'123123123','cnpToken'=>'1234565789012','checkNum'=>'123455'),
            'merchantData'=>array('campaign'=>'camping'),
        );
        $mock = $this->getMock('cnp\sdk\CnpXmlMapper');
        $mock->expects($this->once())
        ->method('request')
        ->with($this->matchesRegularExpression('/.*<\/echeckToken>.*<merchantData>.*<campaign>camping<\/campaign>.*<\/merchantData>/'));

        $cnpTest = new CnpOnlineRequest();
        $cnpTest->newXML = $mock;
        $cnpTest->echeckVerificationRequest($hash_in);
    }

}
