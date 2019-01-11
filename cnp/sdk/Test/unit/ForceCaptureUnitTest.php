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

class ForceCaptureUnitTest extends \PHPUnit_Framework_TestCase
{
    public static function setUpBeforeClass()
    {
        CommManager::reset();
    }

    public function test_simple_forcCapture()
    {
        $hash_in = array(
     'orderId'=>'123',
      'id' => 'id',
      'cnpTxnId'=>'123456',
      'amount'=>'106',
      'orderSource'=>'ecommerce',
      'token'=> array(
      'cnpToken'=>'123456789101112',
      'expDate'=>'1210',
      'cardValidationNum'=>'555',
      'type'=>'VI'));
        $mock = $this->getMock('cnp\sdk\CnpXmlMapper');
        $mock->expects($this->once())
        ->method('request')
        ->with($this->matchesRegularExpression('/.*<token><cnpToken>123456789101112.*<expDate>1210.*/'));

        $cnpTest = new CnpOnlineRequest();
        $cnpTest->newXML = $mock;
        $cnpTest->forceCaptureRequest($hash_in);
    }
    public function test_no_orderId()
    {
        $hash_in = array(
       'reportGroup'=>'Planets','id' => 'id',
       'cnpTxnId'=>'123456',
       'amount'=>'107',
       'orderSource'=>'ecommerce',
       'token'=> array(
       'cnpToken'=>'123456789101112',
       'expDate'=>'1210',
       'cardValidationNum'=>'555',
       'type'=>'VI'));
        $cnpTest = new CnpOnlineRequest();
        $this->setExpectedException('PHPUnit_Framework_Error_Warning');
        $retOb = $cnpTest->forceCaptureRequest($hash_in);
    }
    public function test_no_orderSource()
    {
        $hash_in = array(
           'reportGroup'=>'Planets','id' => 'id',
           'cnpTxnId'=>'123456',
           'amount'=>'107',
           'orderId'=>'123',
           'token'=> array(
           'cnpToken'=>'123456789101112',
           'expDate'=>'1210',
           'cardValidationNum'=>'555',
           'type'=>'VI'));
        $cnpTest = new CnpOnlineRequest();
        $this->setExpectedException('PHPUnit_Framework_Error_Warning');
        $retOb = $cnpTest->forceCaptureRequest($hash_in);
    }
    public function test_both_card_and_token()
    {
        $hash_in = array(

      'reportGroup'=>'Planets','id' => 'id',
      'cnpTxnId'=>'123456',
      'orderId'=>'12344',
      'amount'=>'106',
      'orderSource'=>'ecommerce',
      'fraudCheck'=>array('authenticationTransactionId'=>'123'),
      'cardholderAuthentication'=>array('authenticationTransactionId'=>'123'),
      'card'=>array(
      'type'=>'VI',
      'number' =>'4100000000000001',
      'expDate' =>'1210'),
      'token'=> array(
      'cnpToken'=>'1234',
      'expDate'=>'1210',
      'cardValidationNum'=>'555',
      'type'=>'VI'));
        $cnpTest = new CnpOnlineRequest();
        $this->setExpectedException('PHPUnit_Framework_Error_Warning');
        $retOb = $cnpTest->forceCaptureRequest($hash_in);
    }
    public function test_all_choices()
    {
        $hash_in = array(

          'reportGroup'=>'Planets','id' => 'id',
          'cnpTxnId'=>'123456',
          'orderId'=>'12344',
          'amount'=>'106',
          'orderSource'=>'ecommerce',
          'fraudCheck'=>array('authenticationTransactionId'=>'123'),
          'cardholderAuthentication'=>array('authenticationTransactionId'=>'123'),
          'card'=>array(
          'type'=>'VI',
          'number' =>'4100000000000001',
          'expDate' =>'1210'),
          'paypage'=> array(
          'paypageRegistrationId'=>'1234',
          'expDate'=>'1210',
          'cardValidationNum'=>'555',
          'type'=>'VI'),
          'token'=> array(
          'cnpToken'=>'1234',
          'expDate'=>'1210',
          'cardValidationNum'=>'555',
          'type'=>'VI'));
        $cnpTest = new CnpOnlineRequest();
        $this->setExpectedException('PHPUnit_Framework_Error_Warning');
        $retOb = $cnpTest->forceCaptureRequest($hash_in);
    }

    public function test_loggedInUser()
    {
        $hash_in = array(
                'loggedInUser'=>'gdake','id' => 'id',
                'merchantSdk'=>'PHP;10.1.0',
                'orderId'=>'123',
                'cnpTxnId'=>'123456',
                'amount'=>'106',
                'orderSource'=>'ecommerce',
                'token'=> array(
                        'cnpToken'=>'123456789101112',
                        'expDate'=>'1210',
                        'cardValidationNum'=>'555',
                        'type'=>'VI'));
        $mock = $this->getMock('cnp\sdk\CnpXmlMapper');
        $mock->expects($this->once())
        ->method('request')
        ->with($this->matchesRegularExpression('/.*merchantSdk="PHP;10.1.0".*loggedInUser="gdake" xmlns=.*>.*/'));

        $cnpTest = new CnpOnlineRequest();
        $cnpTest->newXML = $mock;
        $cnpTest->forceCaptureRequest($hash_in);
    }

    public function test_surchargeAmount()
    {
        $hash_in = array(
            'orderId'=>'3',
            'id' => 'id',
            'amount'=>'2',
            'surchargeAmount'=>'1',
            'orderSource'=>'ecommerce',
            'token'=> array(
                'cnpToken'=>'123456789101112',
                'expDate'=>'1210',
                'cardValidationNum'=>'555',
                'type'=>'VI')
        );
        $mock = $this->getMock('cnp\sdk\CnpXmlMapper');
        $mock
            ->expects($this->once())
            ->method('request')
            ->with($this->matchesRegularExpression('/.*<amount>2<\/amount><surchargeAmount>1<\/surchargeAmount><orderSource>ecommerce<\/orderSource>.*/'));

        $cnpTest = new CnpOnlineRequest();
        $cnpTest->newXML = $mock;
        $cnpTest->forceCaptureRequest($hash_in);
    }

    public function test_surchargeAmount_optional()
    {
        $hash_in = array(
            'orderId'=>'3','id' => 'id',
            'amount'=>'2',
            'orderSource'=>'ecommerce',
            'token'=> array(
                'cnpToken'=>'123456789101112',
                'expDate'=>'1210',
                'cardValidationNum'=>'555',
                'type'=>'VI')
        );
        $mock = $this->getMock('cnp\sdk\CnpXmlMapper');
        $mock
        ->expects($this->once())
        ->method('request')
        ->with($this->matchesRegularExpression('/.*<amount>2<\/amount><orderSource>ecommerce<\/orderSource>.*/'));

        $cnpTest = new CnpOnlineRequest();
        $cnpTest->newXML = $mock;
        $cnpTest->forceCaptureRequest($hash_in);
    }

    public function test_debtRepayment_true()
    {
        $hash_in = array(
                'amount'=>'2','id' => 'id',
                'orderSource'=>'ecommerce',
                'orderId'=>'3',
            'token'=> array(
                'cnpToken'=>'123456789101112',
                'expDate'=>'1210',
                'cardValidationNum'=>'555',
                'type'=>'VI'),
                'merchantData'=>array(
                        'campaign'=>'foo',
                ),
                'debtRepayment'=>'true'
        );
        $mock = $this->getMock('cnp\sdk\CnpXmlMapper');
        $mock
        ->expects($this->once())
        ->method('request')
        ->with($this->matchesRegularExpression('/.*<\/merchantData><debtRepayment>true<\/debtRepayment><\/forceCapture>.*/'));

        $cnpTest = new CnpOnlineRequest();
        $cnpTest->newXML = $mock;
        $cnpTest->forceCaptureRequest($hash_in);
    }

    public function test_debtRepayment_false()
    {
        $hash_in = array(
                'amount'=>'2','id' => 'id',
                'orderSource'=>'ecommerce',
                'orderId'=>'3',
            'token'=> array(
                'cnpToken'=>'123456789101112',
                'expDate'=>'1210',
                'cardValidationNum'=>'555',
                'type'=>'VI'),
                'merchantData'=>array(
                        'campaign'=>'foo',
                ),
                'debtRepayment'=>'false'
        );
        $mock = $this->getMock('cnp\sdk\CnpXmlMapper');
        $mock
        ->expects($this->once())
        ->method('request')
        ->with($this->matchesRegularExpression('/.*<\/merchantData><debtRepayment>false<\/debtRepayment><\/forceCapture>.*/'));

        $cnpTest = new CnpOnlineRequest();
        $cnpTest->newXML = $mock;
        $cnpTest->forceCaptureRequest($hash_in);
    }

    public function test_debtRepayment_optional()
    {
        $hash_in = array(
                'amount'=>'2','id' => 'id',
                'orderSource'=>'ecommerce',
                'orderId'=>'3',
            'token'=> array(
                'cnpToken'=>'123456789101112',
                'expDate'=>'1210',
                'cardValidationNum'=>'555',
                'type'=>'VI'),
                'merchantData'=>array(
                        'campaign'=>'foo',
                ),
        );
        $mock = $this->getMock('cnp\sdk\CnpXmlMapper');
        $mock
        ->expects($this->once())
        ->method('request')
        ->with($this->matchesRegularExpression('/.*<\/merchantData><\/forceCapture>.*/'));

        $cnpTest = new CnpOnlineRequest();
        $cnpTest->newXML = $mock;
        $cnpTest->forceCaptureRequest($hash_in);
    }
    public function test_simple_forceCapture_secondary_amount()
    {
    	$hash_in = array(
    			'orderId'=>'123','id' => 'id',
    			'cnpTxnId'=>'123456',
    			'amount'=>'106',
    			'secondaryAmount' => '2000',
    			'orderSource'=>'ecommerce',
    			'token'=> array(
    					'cnpToken'=>'123456789101112',
    					'expDate'=>'1210',
    					'cardValidationNum'=>'555',
    					'type'=>'VI'));
    	$mock = $this->getMock('cnp\sdk\CnpXmlMapper');
    	$mock->expects($this->once())
    	->method('request')
    	->with($this->matchesRegularExpression('/.*<token><cnpToken>123456789101112.*<expDate>1210.*/'));
    
    	$cnpTest = new CnpOnlineRequest();
    	$cnpTest->newXML = $mock;
    	$cnpTest->forceCaptureRequest($hash_in);
    }
    
    public function test_simple_forcCapture_withProcessingType()
    {
    	$hash_in = array(
    			'orderId'=>'123',
    			'id' => 'id',
    			'cnpTxnId'=>'123456',
    			'amount'=>'106',
    			'orderSource'=>'ecommerce',
    			'token'=> array(
    					'cnpToken'=>'123456789101112',
    					'expDate'=>'1210',
    					'cardValidationNum'=>'555',
    					'type'=>'VI'),
    			'processingType' => 'initialRecurring'
    			);
    	$mock = $this->getMock('cnp\sdk\CnpXmlMapper');
    	$mock->expects($this->once())
    	->method('request')
    	->with($this->matchesRegularExpression('/.*<processingType>initialRecurring.*/'));
    
    	$cnpTest = new CnpOnlineRequest();
    	$cnpTest->newXML = $mock;
    	$cnpTest->forceCaptureRequest($hash_in);
    }
    
}
