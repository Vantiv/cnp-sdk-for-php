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
class EcheckRedepositUnitTest extends \PHPUnit_Framework_TestCase
{
    public function test_simple_echeckRedeposit()
    {
        $hash_in = array('litleTxnId' =>'123123','id' => 'id');
        $mock = $this->getMock('cnp\sdk\CnpXmlMapper');
        $mock->expects($this->once())
        ->method('request')
        ->with($this->matchesRegularExpression('/.*<litleTxnId>123123.*/'));

        $cnpTest = new CnpOnlineRequest();
        $cnpTest->newXML = $mock;
        $cnpTest->echeckRedepositRequest($hash_in);
    }

    public function test_no_litleTxnId()
    {
        $hash_in = array('reportGroup'=>'Planets','id' => 'id');
        $cnpTest = new CnpOnlineRequest();
        $this->setExpectedException('InvalidArgumentException',"Missing Required Field: /litleTxnId/");
        $retOb = $cnpTest->echeckRedepositRequest($hash_in);
    }

    public function test_no_routingNum_echeck()
    {
        $hash_in = array('reportGroup'=>'Planets','litleTxnId'=>'123456','id' => 'id',
         'echeck' => array('accType'=>'Checking','accNum'=>'12345657890','checkNum'=>'123455'));
        $cnpTest = new CnpOnlineRequest();
        $this->setExpectedException('InvalidArgumentException',"Missing Required Field: /routingNum/");
        $retOb = $cnpTest->echeckRedepositRequest($hash_in);
    }

    public function test_no_routingNum_echeckToken()
    {
        $hash_in = array('reportGroup'=>'Planets','litleTxnId'=>'123456','id' => 'id',
        'echeckToken' => array('accType'=>'Checking','litleToken'=>'1234565789012','checkNum'=>'123455'));
        $cnpTest = new CnpOnlineRequest();
        $this->setExpectedException('InvalidArgumentException',"Missing Required Field: /routingNum/");
        $retOb = $cnpTest->echeckRedepositRequest($hash_in);
    }

    public function test_both_choices()
    {
        $hash_in = array('reportGroup'=>'Planets','litleTxnId'=>'123456','id' => 'id',
        'echeckToken' => array('accType'=>'Checking','routingNum'=>'123123','litleToken'=>'1234565789012','checkNum'=>'123455'),
        'echeck' => array('accType'=>'Checking','routingNum'=>'123123','accNum'=>'12345657890','checkNum'=>'123455'));
        $cnpTest = new CnpOnlineRequest();
        $this->setExpectedException('InvalidArgumentException',"Entered an Invalid Amount of Choices for a Field, please only fill out one Choice!!!!");
        $retOb = $cnpTest->echeckRedepositRequest($hash_in);
    }

    public function test_loggedInUser()
    {
        $hash_in = array(
                'litleTxnId' =>'123123',
        		'id' => 'id',
                'merchantSdk'=>'PHP;10.1.0',
                'loggedInUser'=>'gdake');
        $mock = $this->getMock('cnp\sdk\CnpXmlMapper');
        $mock->expects($this->once())
        ->method('request')
        ->with($this->matchesRegularExpression('/.*merchantSdk="PHP;10.1.0".*loggedInUser="gdake" xmlns=.*>.*/'));

        $cnpTest = new CnpOnlineRequest();
        $cnpTest->newXML = $mock;
        $cnpTest->echeckRedepositRequest($hash_in);
    }

    public function test_merchantData()
    {
        $hash_in = array(
                'litleTxnId' =>'123123',
        		'id' => 'id',
                'merchantData'=>array('campaign'=>'camping'));
        $mock = $this->getMock('cnp\sdk\CnpXmlMapper');
        $mock->expects($this->once())
        ->method('request')
        ->with($this->matchesRegularExpression('/.*<litleTxnId>123123<\/litleTxnId>.*<merchantData>.*<campaign>camping<\/campaign>.*<\/merchantData>/'));

        $cnpTest = new CnpOnlineRequest();
        $cnpTest->newXML = $mock;
        $cnpTest->echeckRedepositRequest($hash_in);
    }
    
    public function test_customIdentifier()
    {
    	$hash_in = array('litleTxnId' =>'123123','id' => 'id', 'customIdentifier' => 'customer');
    	$mock = $this->getMock('cnp\sdk\CnpXmlMapper');
    	$mock->expects($this->once())
    	->method('request')
    	->with($this->matchesRegularExpression('/.*<litleTxnId>123123.*<customIdentifier>customer.*/'));
    
    	$cnpTest = new CnpOnlineRequest();
    	$cnpTest->newXML = $mock;
    	$cnpTest->echeckRedepositRequest($hash_in);
    }
    

}
