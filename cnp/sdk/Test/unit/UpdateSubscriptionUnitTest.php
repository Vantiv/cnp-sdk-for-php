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
class UpdateSubscriptionUnitTest extends \PHPUnit_Framework_TestCase
{
    public function test_simple()
    {
        $hash_in = array(
            'subscriptionId'=>'1',
            'planCode'=> '2',
            'billToAddress'=> array (
                'addressLine1' => '3'
            ),
            'card' => array (
                'type'=>'VI',
                'number'=>'4100000000000000',
                'expDate'=>'1213',
                'cardValidationNum' => '1213'
            ),
            'billingDate'=>'2013-12-17');
        $mock = $this->getMock('cnp\sdk\CnpXmlMapper');
        $mock->expects($this->once())
        ->method('request')
        ->with($this->matchesRegularExpression('/.*<subscriptionId>1.*<planCode>2.*<billToAddress.*<addressLine1>3.*<card.*type.*VI.*billingDate.*2013-12-17.*/'));

        $cnpTest = new CnpOnlineRequest();
        $cnpTest->newXML = $mock;
        $cnpTest->updateSubscription($hash_in);
    }

    public function test_PlanCodeIsOptional()
    {
        $hash_in = array(
            'subscriptionId'=>'1',
            'billToAddress'=> array (
                'addressLine1' => '3'
            ),
            'card' => array (
                'type'=>'VI',
                'number'=>'4100000000000000',
                'expDate'=>'1213',
                'cardValidationNum' => '1213'
            ),
            'billingDate'=>'2013-12-17');
        $mock = $this->getMock('cnp\sdk\CnpXmlMapper');

        $mock->expects($this->once())
        ->method('request')
        ->with($this->logicalNot($this->matchesRegularExpression('/.*planCode.*/')));

        $cnpTest = new CnpOnlineRequest();
        $cnpTest->newXML = $mock;
        $cnpTest->updateSubscription($hash_in);
    }

    public function test_BillToAddressIsOptional()
    {
        $hash_in = array(
            'subscriptionId'=>'1',
            'card' => array (
                'type'=>'VI',
                'number'=>'4100000000000000',
                'expDate'=>'1213',
                'cardValidationNum' => '1213'
            ),
            'billingDate'=>'2013-12-17');
        $mock = $this->getMock('cnp\sdk\CnpXmlMapper');

        $mock->expects($this->once())
        ->method('request')
        ->with($this->logicalNot($this->matchesRegularExpression('/.*billToAddress.*/')));

        $cnpTest = new CnpOnlineRequest();
        $cnpTest->newXML = $mock;
        $cnpTest->updateSubscription($hash_in);
    }

    public function test_CardIsOptional()
    {
        $hash_in = array(
            'subscriptionId'=>'1',
            'billingDate'=>'2013-12-17');
        $mock = $this->getMock('cnp\sdk\CnpXmlMapper');

        $mock->expects($this->once())
        ->method('request')
        ->with($this->logicalNot($this->matchesRegularExpression('/.*card.*/')));

        $cnpTest = new CnpOnlineRequest();
        $cnpTest->newXML = $mock;
        $cnpTest->updateSubscription($hash_in);
    }

    public function test_BillingDateIsOptional()
    {
        $hash_in = array(
            'subscriptionId'=>'1');
        $mock = $this->getMock('cnp\sdk\CnpXmlMapper');

        $mock->expects($this->once())
        ->method('request')
        ->with($this->logicalNot($this->matchesRegularExpression('/.*billingDate.*/')));

        $cnpTest = new CnpOnlineRequest();
        $cnpTest->newXML = $mock;
        $cnpTest->updateSubscription($hash_in);
    }

    public function test_choice_card()
    {
        $hash_in = array(
            'subscriptionId'=>'1',
            'card' => array (
                'type'=>'VI',
                'number'=>'4100000000000000',
                'expDate'=>'1213',
                'cardValidationNum' => '1213'
            )
         );
        $mock = $this->getMock('cnp\sdk\CnpXmlMapper');

        $mock->expects($this->once())
        ->method('request')
        ->with($this->matchesRegularExpression('/.*card.*type.*VI.*number.*/'));

        $cnpTest = new CnpOnlineRequest();
        $cnpTest->newXML = $mock;
        $cnpTest->updateSubscription($hash_in);
    }

    public function test_choice_token()
    {
        $hash_in = array(
            'subscriptionId'=>'1',
            'token' => array (
                'litleToken'=>'1111222233334444',
                'expDate'=>'1213',
                'cardValidationNum' => '1213',
                'type'=>'VI'
            )
         );
        $mock = $this->getMock('cnp\sdk\CnpXmlMapper');

        $mock->expects($this->once())
        ->method('request')
        ->with($this->matchesRegularExpression('/.*litleToken.*1111222233334444.*type.*/'));

        $cnpTest = new CnpOnlineRequest();
        $cnpTest->newXML = $mock;
        $cnpTest->updateSubscription($hash_in);
    }

    public function test_choice_paypage()
    {
        $hash_in = array(
            'subscriptionId'=>'1',
            'paypage' => array (
                'paypageRegistrationId'=>'abc123',
                'expDate'=>'1213',
                'cardValidationNum' => '1213',
                'type'=>'VI',
            )
         );
        $mock = $this->getMock('cnp\sdk\CnpXmlMapper');

        $mock->expects($this->once())
        ->method('request')
        ->with($this->matchesRegularExpression('/.*paypage.*paypageRegistrationId.*abc123.*type.*VI.*/'));

        $cnpTest = new CnpOnlineRequest();
        $cnpTest->newXML = $mock;
        $cnpTest->updateSubscription($hash_in);
    }

}
