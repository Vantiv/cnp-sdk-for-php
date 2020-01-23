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

class CaptureGivenAuthUnitTest extends \PHPUnit_Framework_TestCase
{
    public static function setUpBeforeClass()
    {
        CommManager::reset();
    }

    public function test_simple_captureGivenAuth()
    {
        $hash_in = array(
       'amount'=>'123',
       'orderId'=>'12344',
       'id'=> 'id',
       'authInformation' => array(
       'authDate'=>'2002-10-09','authCode'=>'543216',
       'authAmount'=>'12345'),
       'orderSource'=>'ecommerce',
       'card'=>array(
       'type'=>'VI',
       'number' =>'4100000000000001',
       'expDate' =>'1210'));
        $mock = $this->getMock('cnp\sdk\CnpXmlMapper');
        $mock	->expects($this->once())
        ->method('request')
        ->with($this->matchesRegularExpression('/.*<authInformation><authDate>2002-10-09.*<authCode>543216.*><authAmount>12345.*/'));

        $cnpTest = new CnpOnlineRequest();
        $cnpTest->newXML = $mock;
        $cnpTest->captureGivenAuthRequest($hash_in);

    }

    public function test_simple_captureGivenAuth_with_merchantCategoryCode()
    {
        $hash_in = array(
            'amount'=>'123',
            'orderId'=>'12344',
            'id'=> 'id',
            'merchantCategoryCode' => '3535',
            'authInformation' => array(
                'authDate'=>'2002-10-09','authCode'=>'543216',
                'authAmount'=>'12345'),
            'orderSource'=>'ecommerce',
            'card'=>array(
                'type'=>'VI',
                'number' =>'4100000000000001',
                'expDate' =>'1210'));
        $mock = $this->getMock('cnp\sdk\CnpXmlMapper');
        $mock	->expects($this->once())
            ->method('request')
            ->with($this->matchesRegularExpression('/.*<authInformation><authDate>2002-10-09.*<authCode>543216.*><authAmount>12345.*/'));

        $cnpTest = new CnpOnlineRequest();
        $cnpTest->newXML = $mock;
        $cnpTest->captureGivenAuthRequest($hash_in);

    }

    public function test_no_amount()
    {
        $hash_in = array(
       'reportGroup'=>'Planets',
       'orderId'=>'12344',
       'id'=> 'id',
       'authInformation' => array(
       'authDate'=>'2002-10-09','authCode'=>'543216',
       'authAmount'=>'12345'),
       'orderSource'=>'ecommerce',
       'card'=>array(
       'type'=>'VI',
       'number' =>'4100000000000001',
       'expDate' =>'1210'));
        $cnpTest = new CnpOnlineRequest();
        $this->setExpectedException("PHPUnit_Framework_Error_Warning");
        $retOb = $cnpTest->captureGivenAuthRequest($hash_in);
    }

    public function test_both_choices_card_and_token()
    {
        $hash_in = array(
          'reportGroup'=>'Planets',
          'orderId'=>'1234',
         'id'=> 'id',
         'amount'=>'106',
         'orderSource'=>'ecommerce',
          'authInformation' => array(
          'authDate'=>'2002-10-09','authCode'=>'543216',
         'authAmount'=>'12345'),
          'token'=> array(
          'cnpToken'=>'123456789101112',
         'expDate'=>'1210',
        'cardValidationNum'=>'555',
        'type'=>'VI'),
         'card'=>array(
        'type'=>'VI',
        'number' =>'4100000000000001',
        'expDate' =>'1210'));
        $cnpTest = new CnpOnlineRequest();
        $this->setExpectedException('PHPUnit_Framework_Error_Warning');
        $retOb = $cnpTest->captureGivenAuthRequest($hash_in);
    }

    public function test_all_choices()
    {
        $hash_in = array(
                  'reportGroup'=>'Planets',
        		  'id'=> 'id',
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
        $retOb = $cnpTest->captureGivenAuthRequest($hash_in);
    }

    public function test_loggedInUser()
    {
        $hash_in = array(
                'loggedInUser'=>'gdake',
        		'id'=> 'id',
                'merchantSdk'=>'PHP;10.1',
                'amount'=>'123',
                'orderId'=>'12344',
                'authInformation' => array(
                        'authDate'=>'2002-10-09','authCode'=>'543216',
                        'authAmount'=>'12345'),
                'orderSource'=>'ecommerce',
                'card'=>array(
                        'type'=>'VI',
                        'number' =>'4100000000000001',
                        'expDate' =>'1210'));
        $mock = $this->getMock('cnp\sdk\CnpXmlMapper');
        $mock	->expects($this->once())
        ->method('request')
        ->with($this->matchesRegularExpression('/.*merchantSdk="PHP;10.1".*loggedInUser="gdake" xmlns=.*>.*/'));

        $cnpTest = new CnpOnlineRequest();
        $cnpTest->newXML = $mock;
        $cnpTest->captureGivenAuthRequest($hash_in);

    }

    public function test_surchargeAmount()
    {
        $hash_in = array(
            'orderId'=>'3',
            'authInformation' => array(
                'authDate'=>'2002-10-09','authCode'=>'543216',
                'authAmount'=>'12345'),
            'amount'=>'2',
            'orderSource'=>'ecommerce',
            'card'=>array(
                'type'=>'VI',
                'number' =>'4100000000000001',
                'expDate' =>'1210'),
            'surchargeAmount'=>'1',
            'id'=>'id'
        );
        $mock = $this->getMock('cnp\sdk\CnpXmlMapper');
        $mock
            ->expects($this->once())
            ->method('request')
            ->with($this->matchesRegularExpression('/.*<amount>2<\/amount><surchargeAmount>1<\/surchargeAmount><orderSource>ecommerce<\/orderSource>.*/'));


        $cnpTest = new CnpOnlineRequest();
        $cnpTest->newXML = $mock;
        $cnpTest->captureGivenAuthRequest($hash_in);
    }

    public function test_surchargeAmount_optional()
    {
        $hash_in = array(
            'orderId'=>'3',
            'authInformation' => array(
                'authDate'=>'2002-10-09','authCode'=>'543216',
                'authAmount'=>'12345'),
            'amount'=>'2',
            'orderSource'=>'ecommerce',
            'card'=>array(
                'type'=>'VI',
                'number' =>'4100000000000001',
                'expDate' =>'1210'),
            'id'=>'id'
        );
        $mock = $this->getMock('cnp\sdk\CnpXmlMapper');
        $mock
        ->expects($this->once())
        ->method('request')
        ->with($this->matchesRegularExpression('/.*<amount>2<\/amount><orderSource>ecommerce<\/orderSource>.*/'));

        $cnpTest = new CnpOnlineRequest();
        $cnpTest->newXML = $mock;
        $cnpTest->captureGivenAuthRequest($hash_in);
    }

    public function test_debtRepayment_true()
    {

        $hash_in = array(
                'amount'=>'2',
        		'id'=> 'id',
                'orderSource'=>'ecommerce',
                'orderId'=>'3',
                'merchantData'=>array(
                    'campaign'=>'foo',
                ),
                'card'=>array(
                    'type'=>'VI',
                    'number' =>'4100000000000001',
                'expDate' =>'1210'),
                 'authInformation' => array(
                    'authDate'=>'2002-10-09','authCode'=>'543216',
                    'authAmount'=>'12345'),
                'debtRepayment'=>'true'
        );
        $mock = $this->getMock('cnp\sdk\CnpXmlMapper');
        $mock
        ->expects($this->once())
        ->method('request')
        ->with($this->matchesRegularExpression('/.*<\/merchantData><debtRepayment>true<\/debtRepayment><\/captureGivenAuth>.*/'));

        $cnpTest = new CnpOnlineRequest();
        $cnpTest->newXML = $mock;
        $cnpTest->captureGivenAuthRequest($hash_in);
    }

    public function test_debtRepayment_false()
    {
        $hash_in = array(
                'amount'=>'2',
        		'id'=> 'id',
                'orderSource'=>'ecommerce',
                'orderId'=>'3',
                'merchantData'=>array(
                    'campaign'=>'foo',
                ),
                'card'=>array(
                    'type'=>'VI',
                    'number' =>'4100000000000001',
                    'expDate' =>'1210'),
                'authInformation' => array(
                    'authDate'=>'2002-10-09','authCode'=>'543216',
                    'authAmount'=>'12345'),
                'debtRepayment'=>'false'
        );
        $mock = $this->getMock('cnp\sdk\CnpXmlMapper');
        $mock
        ->expects($this->once())
        ->method('request')
        ->with($this->matchesRegularExpression('/.*<\/merchantData><debtRepayment>false<\/debtRepayment><\/captureGivenAuth>.*/'));

        $cnpTest = new CnpOnlineRequest();
        $cnpTest->newXML = $mock;
        $cnpTest->captureGivenAuthRequest($hash_in);
    }

    public function test_debtRepayment_optional()
    {
        $hash_in = array(
                'amount'=>'2',
        		'id'=> 'id',
                'orderSource'=>'ecommerce',
                'orderId'=>'3',
                'merchantData'=>array(
                        'campaign'=>'foo',
                ),
                'card'=>array(
                    'type'=>'VI',
                    'number' =>'4100000000000001',
                    'expDate' =>'1210'),
                'authInformation' => array(
                    'authDate'=>'2002-10-09','authCode'=>'543216',
                    'authAmount'=>'12345'),
        );
        $mock = $this->getMock('cnp\sdk\CnpXmlMapper');
        $mock
        ->expects($this->once())
        ->method('request')
        ->with($this->matchesRegularExpression('/.*<\/merchantData><\/captureGivenAuth>.*/'));

        $cnpTest = new CnpOnlineRequest();
        $cnpTest->newXML = $mock;
        $cnpTest->captureGivenAuthRequest($hash_in);
    }
    
    public function test_simple_captureGivenAuth_secondaryAmount()
    {
    	$hash_in = array(
    			'amount'=>'123',
    			'id'=> 'id',
    			'secondaryAmount' => '2102',
    			'orderId'=>'12344',
    			'authInformation' => array(
    					'authDate'=>'2002-10-09','authCode'=>'543216',
    					'authAmount'=>'12345'),
    			'orderSource'=>'ecommerce',
    			'card'=>array(
    					'type'=>'VI',
    					'number' =>'4100000000000001',
    					'expDate' =>'1210'));
    	$mock = $this->getMock('cnp\sdk\CnpXmlMapper');
    	$mock	->expects($this->once())
    	->method('request')
    	->with($this->matchesRegularExpression('/.*<authInformation><authDate>2002-10-09.*<authCode>543216.*><authAmount>12345.*/'));
    
    	$cnpTest = new CnpOnlineRequest();
    	$cnpTest->newXML = $mock;
    	$cnpTest->captureGivenAuthRequest($hash_in);
    
    }
    
    public function test_simple_captureGivenAuth_processingType()
    {
    	$hash_in = array(
    			'amount'=>'123',
    			'orderId'=>'12344',
    			'id'=> 'id',
    			'authInformation' => array(
    					'authDate'=>'2002-10-09','authCode'=>'543216',
    					'authAmount'=>'12345'),
    			'orderSource'=>'ecommerce',
    			'card'=>array(
    					'type'=>'VI',
    					'number' =>'4100000000000001',
    					'expDate' =>'1210'),
    			'processingType' => 'accountFunding',
    			'originalNetworkTransactionId' => 'abcdefgh',
    			'originalTransactionAmount' => '1000'
    	);
    	$mock = $this->getMock('cnp\sdk\CnpXmlMapper');
    	$mock	->expects($this->once())
    	->method('request')
    	->with($this->matchesRegularExpression('/.*<processingType>accountFunding.*<originalNetworkTransactionId>abcdefgh.*<originalTransactionAmount>1000.*/'));
    
    	$cnpTest = new CnpOnlineRequest();
    	$cnpTest->newXML = $mock;
    	$cnpTest->captureGivenAuthRequest($hash_in);
    
    }
 
 
   
}
