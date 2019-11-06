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

class CnpOnlineRequestUnitTest extends \PHPUnit_Framework_TestCase
{
    public static function setUpBeforeClass()
    {
        CommManager::reset();
    }

    public function test_set_merchant_sdk_integration()
    {
        $hash_in = array(
            'id'=>'654',
            'merchantSdk'=>'Magento;8.14.3',
            'orderId'=> '2111',
            'amount'=>'123',
            'orderSource'=>'ecommerce',
            'card'=>array(
                'type'=>'VI',
                'number' =>'4100000000000001',
                'expDate' =>'1210')

            );
        $mock = $this->getMock('cnp\sdk\CnpXmlMapper');
        $mock->expects($this->once())
        ->method('request')
        ->with($this->matchesRegularExpression('/.*merchantSdk="Magento;8.14.3".*/'));

        $cnpTest = new CnpOnlineRequest();
        $cnpTest->newXML = $mock;
        $cnpTest->authorizationRequest($hash_in);
    }

    public function test_set_merchant_sdk_default()
    {
        $hash_in = array(
                'orderId'=> '2111',
                'id'=>'654',
                'orderSource'=>'ecommerce',
                'amount'=>'123',
                'card'=>array(
                    'type'=>'VI',
                    'number' =>'4100000000000001',
                    'expDate' =>'1210')
            );
        $mock = $this->getMock('cnp\sdk\CnpXmlMapper');
        $mock->expects($this->once())
        ->method('request')
        ->with($this->matchesRegularExpression('/.*merchantSdk="PHP;12.*/'));

        $cnpTest = new CnpOnlineRequest();
        $cnpTest->newXML = $mock;
        $cnpTest->authorizationRequest($hash_in);
    }

    public function test_sale_with_realtime_account_updater()
    {
        $hash_in = array(
            'card' => array('type' => 'VI',
                'number' => '4100000000000000',
                'expDate' => '1213',
                'cardValidationNum' => '1213'),
            'id' => '1211',
            'orderId' => '2111',
            'reportGroup' => 'Planets',
            'orderSource' => 'ecommerce',
            'amount' => '123',
            'skipRealtimeAU' => 'true');
        
        $mock = $this->getMock('cnp\sdk\CnpXmlMapper');
        $mock->expects($this->once())
            ->method('request')
            ->with($this->matchesRegularExpression('/.*\<skipRealtimeAU\>true\<\/skipRealtimeAU\>.*/'));

        $cnpTest = new CnpOnlineRequest();
        $cnpTest->newXML = $mock;
        $cnpTest->saleRequest($hash_in);
    }

    public function test_sale_without_realtime_account_updater()
    {
        $hash_in = array(
            'card' => array('type' => 'VI',
                'number' => '4100000000000000',
                'expDate' => '1213',
                'cardValidationNum' => '1213'),
            'id' => '1211',
            'orderId' => '2111',
            'reportGroup' => 'Planets',
            'orderSource' => 'ecommerce',
            'amount' => '123',
            'skipRealtimeAU' => 'false');

        $mock = $this->getMock('cnp\sdk\CnpXmlMapper');
        $mock->expects($this->once())
            ->method('request')
            ->with($this->matchesRegularExpression('/.*\<skipRealtimeAU\>false\<\/skipRealtimeAU\>.*/'));

        $cnpTest = new CnpOnlineRequest();
        $cnpTest->newXML = $mock;
        $cnpTest->saleRequest($hash_in);
    }

}
