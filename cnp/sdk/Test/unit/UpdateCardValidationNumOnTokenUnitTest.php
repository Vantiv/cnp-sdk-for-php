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
class UpdateCardValidationNumOnTokenUnitTest extends \PHPUnit_Framework_TestCase
{
    public function test_simple()
    {
        $hash_in = array('id' => 'id',
            'orderId'=>'1',
            'cnpToken'=>'123456789101112',
            'cardValidationNum'=>'123');
        $mock = $this->getMock('cnp\sdk\CnpXmlMapper');
        $mock->expects($this->once())
        ->method('request')
        ->with($this->matchesRegularExpression('/.*<orderId>1.*<cnpToken>123456789101112.*<cardValidationNum>123.*/'));

        $cnpTest = new CnpOnlineRequest();
        $cnpTest->newXML = $mock;
        $cnpTest->updateCardValidationNumOnToken($hash_in);
    }

    public function test_orderIdIsOptional()
    {
        $hash_in = array('id' => 'id',
                'cnpToken'=>'123456789101112',
                'cardValidationNum'=>'123');
        $mock = $this->getMock('cnp\sdk\CnpXmlMapper');
        $mock->expects($this->once())
        ->method('request')
        ->with($this->matchesRegularExpression('/.*<cnpToken>123456789101112.*<cardValidationNum>123.*/'));

        $cnpTest = new CnpOnlineRequest();
        $cnpTest->newXML = $mock;
        $cnpTest->updateCardValidationNumOnToken($hash_in);
    }

    public function test_cnpTokenIsRequired()
    {
        $hash_in = array('id' => 'id',
                'cardValidationNum'=>'123');
        $cnpTest = new CnpOnlineRequest();
        $this->setExpectedException('PHPUnit_Framework_Error_Warning');
        $retOb = $cnpTest->updateCardValidationNumOnToken($hash_in);
    }

    public function test_cardValidationNumIsRequired()
    {
        $hash_in = array('id' => 'id',
                'cnpToken'=>'123456789101112');
        $cnpTest = new CnpOnlineRequest();
        $this->setExpectedException('PHPUnit_Framework_Error_Warning');
        $retOb = $cnpTest->updateCardValidationNumOnToken($hash_in);
    }

    public function test_loggedInUser()
    {
        $hash_in = array('id' => 'id',
                'loggedInUser'=>'gdake',
                'merchantSdk'=>'PHP;8.14.0',
                'orderId'=>'1',
                'cnpToken'=>'123456789101112',
                'cardValidationNum'=>'123');
        $mock = $this->getMock('cnp\sdk\CnpXmlMapper');
        $mock->expects($this->once())
        ->method('request')
        ->with($this->matchesRegularExpression('/.*merchantSdk="PHP;8.14.0".*loggedInUser="gdake" xmlns=.*>.*/'));

        $cnpTest = new CnpOnlineRequest();
        $cnpTest->newXML = $mock;
        $cnpTest->updateCardValidationNumOnToken($hash_in);
    }

}
