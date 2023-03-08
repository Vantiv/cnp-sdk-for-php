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
use cnp\sdk\XmlParser;

class SaleUnitTest extends \PHPUnit_Framework_TestCase
{
    public static function setUpBeforeClass()
    {
        CommManager::reset();
    }

    public function test_sale_with_card()
    {
        $hash_in = array(
            'card'=>array('type'=>'VI',
                    'number'=>'4100000000000001',
                    'expDate'=>'1213',
                    'cardValidationNum' => '1213'),
            'id'=>'654',
            'orderId'=> '2111',
            'orderSource'=>'ecommerce',
            'amount'=>'123');
        $mock = $this->getMock('cnp\sdk\CnpXmlMapper');
        $mock->expects($this->once())
        ->method('request')
        ->with($this->matchesRegularExpression('/.*<card><type>VI.*<number>4100000000000001.*<expDate>1213.*<cardValidationNum>1213.*/'));

        $cnpTest = new CnpOnlineRequest();
        $cnpTest->newXML = $mock;
        $cnpTest->saleRequest($hash_in);
    }

    public function test_sale_with_card_with_merchantCategoryCode()
    {
        $hash_in = array(
            'card'=>array('type'=>'VI',
                'number'=>'4100000000000001',
                'expDate'=>'1213',
                'cardValidationNum' => '1213'),
            'id'=>'654',
            'orderId'=> '2111',
            'orderSource'=>'ecommerce',
            'amount'=>'123',
            'merchantCategoryCode' => '3535');
        $mock = $this->getMock('cnp\sdk\CnpXmlMapper');
        $mock->expects($this->once())
            ->method('request')
            ->with($this->matchesRegularExpression('/.*<card><type>VI.*<number>4100000000000001.*<expDate>1213.*<cardValidationNum>1213.*/'));

        $cnpTest = new CnpOnlineRequest();
        $cnpTest->newXML = $mock;
        $cnpTest->saleRequest($hash_in);
    }
    
    
    public function test_sale_with_AdvancedFraudCheckWithCustomAttribute()
    {
    	$hash_in = array(
    			'card'=>array('type'=>'VI',
    					'number'=>'4100000000000001',
    					'expDate'=>'1213',
    					'cardValidationNum' => '1213'),
    			'id'=>'654',
    			'orderId'=> '2111',
    			'orderSource'=>'ecommerce',
    			'amount'=>'123',
    			'advancedFraudChecks'=>array(
    				'threatMetrixSessionId' => 'abc123',
    				'customAttribute1'=>'1',
    				'customAttribute2'=>'2',
    				'customAttribute3'=>'3',
    				'customAttribute4'=>'4',
    				'customAttribute5'=>'5',
    			)
    	);
    	$mock = $this->getMock('cnp\sdk\CnpXmlMapper');
    	$mock->expects($this->once())
    	->method('request')
    	->with($this->matchesRegularExpression('/.*<advancedFraudChecks><threatMetrixSessionId>abc123<\/threatMetrixSessionId><customAttribute1>1<\/customAttribute1>.*?<\/advancedFraudChecks><\/sale>.*/'));
    
    	$cnpTest = new CnpOnlineRequest();
    	$cnpTest->newXML = $mock;
    	$cnpTest->saleRequest($hash_in);
    }

    public function test_no_orderId()
    {
        $hash_in = array('merchantId' => '101',
          'version'=>'8.8','id' => 'id',
          'reportGroup'=>'Planets',
          'cnpTxnId'=>'123456',
          'amount'=>'106',
          'orderSource'=>'ecommerce',
          'card'=>array(
          'type'=>'VI',
          'number' =>'4100000000000001',
          'expDate' =>'1210')
        );
        $cnpTest = new CnpOnlineRequest();
        $this->setExpectedException('PHPUnit_Framework_Error_Warning');
        $retOb = $cnpTest->saleRequest($hash_in);
    }

    public function test_no_amount()
    {
        $hash_in = array('merchantId' => '101',
          'version'=>'8.8','id' => 'id',
          'reportGroup'=>'Planets',
          'cnpTxnId'=>'123456',
          'orderId'=>'12344',
          'orderSource'=>'ecommerce',
          'card'=>array(
          'type'=>'VI',
          'number' =>'4100000000000001',
          'expDate' =>'1210')
        );
        $cnpTest = new CnpOnlineRequest();
        $this->setExpectedException('PHPUnit_Framework_Error_Warning');
        $retOb = $cnpTest->saleRequest($hash_in);
    }

    public function test_no_orderSource()
    {
        $hash_in = array('merchantId' => '101',
          'version'=>'8.8','id' => 'id',
          'reportGroup'=>'Planets',
          'cnpTxnId'=>'123456',
          'orderId'=>'12344',
          'amount'=>'106',
          'card'=>array(
          'type'=>'VI',
          'number' =>'4100000000000001',
          'expDate' =>'1210')
        );
        $cnpTest = new CnpOnlineRequest();
        $this->setExpectedException('PHPUnit_Framework_Error_Warning');
        $retOb = $cnpTest->saleRequest($hash_in);
    }

    public function test_both_choices_card_and_paypal()
    {
        $hash_in = array('merchantId' => '101',
          'version'=>'8.8','id' => 'id',
          'reportGroup'=>'Planets',
          'orderId'=>'12344',
          'amount'=>'106',
          'orderSource'=>'ecommerce',
          'card'=>array(
          'type'=>'VI',
          'number' =>'4100000000000001',
          'expDate' =>'1210'
        ),
          'paypal'=>array(
          'payerId'=>'1234',
          'token'=>'1234',
          'transactionId'=>'123456')
        );
        $cnpTest = new CnpOnlineRequest();
        $this->setExpectedException('PHPUnit_Framework_Error_Warning');
        $retOb = $cnpTest->saleRequest($hash_in);
    }

    public function test_three_choices_card_and_paypage_and_paypal()
    {
        $hash_in = array('merchantId' => '101',
          'version'=>'8.8','id' => 'id',
          'reportGroup'=>'Planets',
          'orderId'=>'12344',
          'amount'=>'106',
          'orderSource'=>'ecommerce',
          'card'=>array(
          'type'=>'VI',
          'number' =>'4100000000000001',
          'expDate' =>'1210'
        ),
          'paypage'=> array(
          'paypageRegistrationId'=>'1234',
          'expDate'=>'1210',
          'cardValidationNum'=>'555',
          'type'=>'VI'),
          'paypal'=>array(
          'payerId'=>'1234',
          'token'=>'1234',
          'transactionId'=>'123456')
        );
        $cnpTest = new CnpOnlineRequest();
        $this->setExpectedException('PHPUnit_Framework_Error_Warning');
        $retOb = $cnpTest->saleRequest($hash_in);
    }

    public function test_all_choices_card_and_paypage_and_paypal_and_token()
    {
        $hash_in = array('merchantId' => '101',
          'version'=>'8.8','id' => 'id',
          'reportGroup'=>'Planets',
          'cnpTxnId'=>'123456',
          'orderId'=>'12344',
          'amount'=>'106',
          'orderSource'=>'ecommerce',
          'fraudCheck'=>array('authenticationTransactionId'=>'123'),
          'bypassVelocityCheckcardholderAuthentication'=>array('authenticationTransactionId'=>'123'),
          'card'=>array(
          'type'=>'VI',
          'number' =>'4100000000000001',
          'expDate' =>'1210'
        ),
          'paypage'=> array(
          'paypageRegistrationId'=>'1234',
          'expDate'=>'1210',
          'cardValidationNum'=>'555',
          'type'=>'VI'),
          'paypal'=>array(
          'payerId'=>'1234',
          'token'=>'1234',
          'transactionId'=>'123456'),
          'token'=> array(
          'cnpToken'=>'1234',
          'expDate'=>'1210',
          'cardValidationNum'=>'555',
          'type'=>'VI')
        );
        $cnpTest = new CnpOnlineRequest();
        $this->setExpectedException('PHPUnit_Framework_Error_Warning');
        $retOb = $cnpTest->saleRequest($hash_in);
    }

    public function test_merchant_data()
    {
        $hash_in = array(
                    'orderId'=> '2111','id' => 'id',
                    'orderSource'=>'ecommerce',
                    'amount'=>'123',
            'token'=> array(
                'cnpToken'=>'1234567890123',
                'expDate'=>'1210',
                'cardValidationNum'=>'555',
                'type'=>'VI'),
                    'merchantData'=>array(
                        'affiliate'=>'bar'
        )
        );
        $mock = $this->getMock('cnp\sdk\CnpXmlMapper');
        $mock->expects($this->once())
        ->method('request')
        ->with($this->matchesRegularExpression('/.*<merchantData>.*?<affiliate>bar<\/affiliate>.*?<\/merchantData>.*/'));

        $cnpTest = new CnpOnlineRequest();
        $cnpTest->newXML = $mock;
        $cnpTest->saleRequest($hash_in);
    }

    public function test_fraud_filter_override()
    {
        $hash_in = array(
                'card'=>array(
                    'type'=>'VI',
                    'number'=>'4100000000000001',
                    'expDate'=>'1213',
                    'cardValidationNum' => '1213'
                ),
                'orderId'=> '2111','id' => 'id',
                'orderSource'=>'ecommerce',
                'id'=>'64575',
                'amount'=>'123',
                'fraudFilterOverride'=>'false'
        );
        $mock = $this->getMock('cnp\sdk\CnpXmlMapper');
        $mock	->expects($this->once())
        ->method('request')
        ->with($this->matchesRegularExpression('/.*<sale.*?<fraudFilterOverride>false<\/fraudFilterOverride>.*?<\/sale>.*/'));

        $cnpTest = new CnpOnlineRequest();
        $cnpTest->newXML = $mock;
        $cnpTest->saleRequest($hash_in);
    }

    public function test_loggedInUser()
    {
        $hash_in = array(
                'loggedInUser'=>'gdake',
                'merchantSdk'=>'PHP;8.14.0','id' => 'id',
                'card'=>array('type'=>'VI',
                        'number'=>'4100000000000001',
                        'expDate'=>'1213',
                        'cardValidationNum' => '1213'),
                'id'=>'654',
                'orderId'=> '2111',
                'orderSource'=>'ecommerce',
                'amount'=>'123');
        $mock = $this->getMock('cnp\sdk\CnpXmlMapper');
        $mock->expects($this->once())
        ->method('request')
        ->with($this->matchesRegularExpression('/.*merchantSdk="PHP;8.14.0".*loggedInUser="gdake" xmlns=.*>.*/'));

        $cnpTest = new CnpOnlineRequest();
        $cnpTest->newXML = $mock;
        $cnpTest->saleRequest($hash_in);
    }

    public function test_surchargeAmount()
    {
        $hash_in = array(
                'card'=>array(
                        'type'=>'VI',
                        'number'=>'4100000000000001',
                        'expDate'=>'1213'
                ),
                'orderId'=>'12344','id' => 'id',
                'amount'=>'2',
                'surchargeAmount'=>'1',
                'orderSource'=>'ecommerce',
        );
        $mock = $this->getMock('cnp\sdk\CnpXmlMapper');
        $mock
        ->expects($this->once())
        ->method('request')
        ->with($this->matchesRegularExpression('/.*<amount>2<\/amount><surchargeAmount>1<\/surchargeAmount><orderSource>ecommerce<\/orderSource>.*/'));

        $cnpTest = new CnpOnlineRequest();
        $cnpTest->newXML = $mock;
        $cnpTest->saleRequest($hash_in);
    }

    public function test_surchargeAmount_Optional()
    {
        $hash_in = array(
                'card'=>array(
                        'type'=>'VI',
                        'number'=>'4100000000000001',
                        'expDate'=>'1213'
                ),
                'orderId'=>'12344','id' => 'id',
                'amount'=>'2',
                'orderSource'=>'ecommerce',
        );
        $mock = $this->getMock('cnp\sdk\CnpXmlMapper');
        $mock
        ->expects($this->once())
        ->method('request')
        ->with($this->matchesRegularExpression('/.*<amount>2<\/amount><orderSource>ecommerce<\/orderSource>.*/'));

        $cnpTest = new CnpOnlineRequest();
        $cnpTest->newXML = $mock;
        $cnpTest->saleRequest($hash_in);
    }

    public function test_recurringRequest()
    {
        $hash_in = array(
                'card'=>array(
                        'type'=>'VI',
                        'number'=>'4100000000000001',
                        'expDate'=>'1213'
                ),
                'orderId'=>'12344','id' => 'id',
                'amount'=>'2',
                'orderSource'=>'ecommerce',
                'fraudFilterOverride'=>'true',
                'recurringRequest'=>array(
                        'createSubscription'=>array(
                                'planCode'=>'abc123',
                                'numberOfPayments'=>'12'
                        )
                )
        );
        $mock = $this->getMock('cnp\sdk\CnpXmlMapper');
        $mock
        ->expects($this->once())
        ->method('request')
        ->with($this->matchesRegularExpression('/.*<fraudFilterOverride>true<\/fraudFilterOverride><recurringRequest><createSubscription><planCode>abc123<\/planCode><numberOfPayments>12<\/numberOfPayments><\/createSubscription><\/recurringRequest>.*/'));

        $cnpTest = new CnpOnlineRequest();
        $cnpTest->newXML = $mock;
        $cnpTest->saleRequest($hash_in);
    }

    public function test_recurringRequest_Optional()
    {
        $hash_in = array(
                'card'=>array(
                        'type'=>'VI',
                        'number'=>'4100000000000001',
                        'expDate'=>'1213'
                ),
                'orderId'=>'12344','id' => 'id',
                'amount'=>'2',
                'orderSource'=>'ecommerce',
                'fraudFilterOverride'=>'true'
        );
        $mock = $this->getMock('cnp\sdk\CnpXmlMapper');
        $mock
        ->expects($this->once())
        ->method('request')
        ->with($this->matchesRegularExpression('/.*<fraudFilterOverride>true<\/fraudFilterOverride><\/sale>.*/'));

        $cnpTest = new CnpOnlineRequest();
        $cnpTest->newXML = $mock;
        $cnpTest->saleRequest($hash_in);
    }

    public function test_cnpInternalRecurringRequest()
    {
        $hash_in = array(
            'orderId'=>'12344',
            'id' => 'id',
            'amount'=>'2',
            'orderSource'=>'ecommerce',
            'card'=>array(
                    'type'=>'VI',
                    'number'=>'4100000000000001',
                    'expDate'=>'1213'
            ),

            'fraudCheck'=>array(),
            'fraudFilterOverride'=>'true',
            'cnpInternalRecurringRequest'=>array(
                    'subscriptionId'=>'123',
                    'recurringTxnId'=>'456',
                    'finalPayment'=>'true'
            )
        );
        $mock = $this->getMock('cnp\sdk\CnpXmlMapper');
        $mock
        ->expects($this->once())
        ->method('request')
        ->with($this->matchesRegularExpression('/.*<fraudFilterOverride>true<\/fraudFilterOverride><cnpInternalRecurringRequest><subscriptionId>123<\/subscriptionId><recurringTxnId>456<\/recurringTxnId><finalPayment>true<\/finalPayment><\/cnpInternalRecurringRequest>.*/'));

        $cnpTest = new CnpOnlineRequest();
        $cnpTest->newXML = $mock;
        $cnpTest->saleRequest($hash_in);

    }

    public function test_cnpInternalRecurringRequest_Optional()
    {
        $hash_in = array(
            'card'=>array(
                    'type'=>'VI',
                    'number'=>'4100000000000001',
                    'expDate'=>'1213'
            ),
            'orderId'=>'12344','id' => 'id',
            'amount'=>'2',
            'orderSource'=>'ecommerce',
            'fraudFilterOverride'=>'true'
        );
        $mock = $this->getMock('cnp\sdk\CnpXmlMapper');
        $mock
        ->expects($this->once())
        ->method('request')
        ->with($this->matchesRegularExpression('/.*<fraudFilterOverride>true<\/fraudFilterOverride><\/sale>.*/'));

        $cnpTest = new CnpOnlineRequest();
        $cnpTest->newXML = $mock;
        $cnpTest->saleRequest($hash_in);
    }

    public function test_debtRepayment_true()
    {
        $hash_in = array(
            'orderId'=>'12344',
            'id' => 'id',
            'amount'=>'2',
            'orderSource'=>'ecommerce',
            'card'=>array(
                'type'=>'VI',
                'number'=>'4100000000000001',
                'expDate'=>'1213'
            ),

            'fraudCheck'=>array(),
            'fraudFilterOverride'=>'true',
            'cnpInternalRecurringRequest'=>array(
                'subscriptionId'=>'123',
                'recurringTxnId'=>'456',
                'finalPayment'=>'true'
            ),
            'debtRepayment'=>'true'
        );
        $mock = $this->getMock('cnp\sdk\CnpXmlMapper');
        $mock
        ->expects($this->once())
        ->method('request')
        ->with($this->matchesRegularExpression('/.*<\/cnpInternalRecurringRequest><debtRepayment>true<\/debtRepayment><\/sale>.*/'));

        $cnpTest = new CnpOnlineRequest();
        $cnpTest->newXML = $mock;
        $cnpTest->saleRequest($hash_in);
    }

    public function test_debtRepayment_false()
    {
        $hash_in = array(
            'orderId'=>'12344',
            'id' => 'id',
            'amount'=>'2',
            'orderSource'=>'ecommerce',
            'card'=>array(
                'type'=>'VI',
                'number'=>'4100000000000001',
                'expDate'=>'1213'
            ),

            'fraudCheck'=>array(),
            'fraudFilterOverride'=>'true',
            'cnpInternalRecurringRequest'=>array(
                'subscriptionId'=>'123',
                'recurringTxnId'=>'456',
                'finalPayment'=>'true'
            ),
                'debtRepayment'=>'false'
        );
        $mock = $this->getMock('cnp\sdk\CnpXmlMapper');
        $mock
        ->expects($this->once())
        ->method('request')
        ->with($this->matchesRegularExpression('/.*<\/cnpInternalRecurringRequest><debtRepayment>false<\/debtRepayment><\/sale>.*/'));

        $cnpTest = new CnpOnlineRequest();
        $cnpTest->newXML = $mock;
        $cnpTest->saleRequest($hash_in);
    }

    public function test_debtRepayment_optional()
    {
        $hash_in = array(
            'orderId'=>'12344',
            'id' => 'id',
            'amount'=>'2',
            'orderSource'=>'ecommerce',
            'card'=>array(
                'type'=>'VI',
                'number'=>'4100000000000001',
                'expDate'=>'1213'
            ),

            'fraudCheck'=>array(),
            'fraudFilterOverride'=>'true',
            'cnpInternalRecurringRequest'=>array(
                'subscriptionId'=>'123',
                'recurringTxnId'=>'456',
                'finalPayment'=>'true'
            )
        );
        $mock = $this->getMock('cnp\sdk\CnpXmlMapper');
        $mock
        ->expects($this->once())
        ->method('request')
        ->with($this->matchesRegularExpression('/.*<\/cnpInternalRecurringRequest><\/sale>.*/'));

        $cnpTest = new CnpOnlineRequest();
        $cnpTest->newXML = $mock;
        $cnpTest->saleRequest($hash_in);
    }

       function test_advancedFraudChecks()
       {
        $hash_in = array(
            'card'=>array(
                    'type'=>'VI',
                    'number'=>'4100000000000001',
                    'expDate'=>'1213'
            ),
            'orderId'=>'12344','id' => 'id',
            'amount'=>'2',
            'orderSource'=>'ecommerce',
            'debtRepayment'=>'true',
            'advancedFraudChecks'=>array(
                'threatMetrixSessionId' => 'abc123'
            )
        );
        $mock = $this->getMock('cnp\sdk\CnpXmlMapper');
        $mock
        ->expects($this->once())
        ->method('request')
        ->with($this->matchesRegularExpression('/.*<debtRepayment>true<\/debtRepayment><advancedFraudChecks><threatMetrixSessionId>abc123<\/threatMetrixSessionId><\/advancedFraudChecks><\/sale>.*/'));
        $cnpTest = new CnpOnlineRequest();
        $cnpTest->newXML = $mock;
        $cnpTest->saleRequest($hash_in);
    }

//    public function test_advancedFraudChecks_withoutThreatMetrixSessionId()
//    {
//        //In 8.23, threatMetrixSessionId is optional, but really should be required.
//        //It will be required in 8.24, so I'm making it required here in the schema.
//        //There is no good reason to send an advancedFraudChecks element without a threatMetrixSessionId.
//        $hash_in = array(
//            'card'=>array(
//                'type'=>'VI',
//                'number'=>'4100000000000001',
//                'expDate'=>'1213'
//            ),
//            'orderId'=>'12344','id' => 'id',
//            'amount'=>'2',
//            'orderSource'=>'ecommerce',
//            'debtRepayment'=>'true',
//            'advancedFraudChecks'=>array(
//            )
//        );
//
//        $cnpTest = new CnpOnlineRequest();
//        $this->setExpectedException('InvalidArgumentException','Missing Required Field: /threatMetrixSessionId/');
//        $retOb = $cnpTest->saleRequest($hash_in);
//    }
    
    public function test_sale_with_card_secondaryAmount()
    {
    	$hash_in = array(
    			'card'=>array('type'=>'VI',
    					'number'=>'4100000000000001',
    					'expDate'=>'1213',
    					'cardValidationNum' => '1213'),
    			'id'=>'654',
    			'orderId'=> '2111',
    			'orderSource'=>'ecommerce',
    			'amount'=>'123',
    			'secondaryAmount' => '1234');
    	$mock = $this->getMock('cnp\sdk\CnpXmlMapper');
    	$mock->expects($this->once())
    	->method('request')
    	->with($this->matchesRegularExpression('/.*<card><type>VI.*<number>4100000000000001.*<expDate>1213.*<cardValidationNum>1213.*/'));
    
    	$cnpTest = new CnpOnlineRequest();
    	$cnpTest->newXML = $mock;
    	$cnpTest->saleRequest($hash_in);
    }

    public function test_sale_with_applepay()
    {
    	$hash_in = array(
    			'applepay'=>array(
    					'data'=>'string data here',
    					'header'=> array(
    					    'ephemeralPublicKey'=>"123",
                            'publicKeyHash'=>'123',
                            'transactionId'=>'123'
                        ),
    					'signature'=>'signature',
    					'version' => 'version 1'),
    			'orderId'=> '2111',
    			'orderSource'=>'ecommerce',
    			'id'=>'654',
    			'amount'=>'123');
    	$mock = $this->getMock('cnp\sdk\CnpXmlMapper');
    	$mock	->expects($this->once())
    	->method('request')
    	->with($this->matchesRegularExpression('/.*<applepay><data>string data here.*<header>.*<signature>signature.*<version>version 1.*/'));
    
    	$cnpTest = new CnpOnlineRequest();
    	$cnpTest->newXML = $mock;
    	$cnpTest->saleRequest($hash_in);
    }
    
    public function test_sale_with_sepaDirectDebit()
    {
    	$hash_in = array(
    			'sepaDirectDebit'=>array(
    					'mandateProvider'=>'Merchant',
    					'sequenceType'=> 'FirstRecurring',
    					'mandateReference'=>'some string here',
    					'mandateUrl' => 'some string here',
    					'iban' => 'string with min of 15 char',
    					'preferredLanguage'=> 'USA'
    			),
    			'orderId'=> '2111',
    			'orderSource'=>'ecommerce',
    			'id'=>'654',
    			'amount'=>'123');
    	$mock = $this->getMock('cnp\sdk\CnpXmlMapper');
    	$mock	->expects($this->once())
    	->method('request')
    	->with($this->matchesRegularExpression('/.*<sepaDirectDebit><mandateProvider>Merchant.*<sequenceType>FirstRecurring.*<mandateReference>some string here.*<mandateUrl>some string here.*<iban>string with min of 15 char.*<preferredLanguage>USA.*/'));
    
    	$cnpTest = new CnpOnlineRequest();
    	$cnpTest->newXML = $mock;
    	$cnpTest->saleRequest($hash_in);
    }
    
    public function test_sale_with_processingType_orgTxnNtwId_orgTxnAmt()
    {
    	$hash_in = array(
    			'card'=>array('type'=>'VI',
    					'number'=>'4100000000000001',
    					'expDate'=>'1213',
    					'cardValidationNum' => '1213'),
    			'id'=>'654',
    			'orderId'=> '2111',
    			'orderSource'=>'ecommerce',
    			'amount'=>'123',
    			'processingType' => 'accountFunding',
    			'originalNetworkTransactionId' => 'abcdefgh',
    			'originalTransactionAmount' => '1000'
    			);
    	$mock = $this->getMock('cnp\sdk\CnpXmlMapper');
    	$mock->expects($this->once())
    	->method('request')
    	->with($this->matchesRegularExpression('/.*<card><type>VI.*<number>4100000000000001.*<expDate>1213.*<cardValidationNum>1213.*<processingType>accountFunding.*<originalNetworkTransactionId>abcdefgh.*<originalTransactionAmount>1000.*/'));
    
    	$cnpTest = new CnpOnlineRequest();
    	$cnpTest->newXML = $mock;
    	$cnpTest->saleRequest($hash_in);
    }

    public function test_sale_with_processingType_COF()
    {
        $hash_in = array(
            'card'=>array('type'=>'VI',
                'number'=>'4100000000000001',
                'expDate'=>'1213',
                'cardValidationNum' => '1213'),
            'id'=>'654',
            'orderId'=> '2111',
            'orderSource'=>'ecommerce',
            'amount'=>'123',
            'processingType' => 'initialCOF'
        );
        $mock = $this->getMock('cnp\sdk\CnpXmlMapper');
        $mock->expects($this->once())
            ->method('request')
            ->with($this->matchesRegularExpression('/.*<processingType>initialCOF.*processingType>.*/'));

        $cnpTest = new CnpOnlineRequest();
        $cnpTest->newXML = $mock;
        $cnpTest->saleRequest($hash_in);
    }

    public function test_sale_with_PinlessDebit()
    {
        $hash_in = array(
            'card'=>array('type'=>'VI',
                'number'=>'4100000000000001',
                'expDate'=>'1213',
                'cardValidationNum' => '1213'),
            'id'=>'654',
            'orderId'=> '2111',
            'orderSource'=>'ecommerce',
            'amount'=>'123',
            'pinlessDebitRequest' => array(
                'routingPreference' => 'pinlessDebitOnly',
                'preferredDebitNetworks' => array(
                    'debitNetworkName0' => 'VI',
                    'debitNetworkName1' => 'MC'
                )
            )
        );
        $mock = $this->getMock('cnp\sdk\CnpXmlMapper');
        $mock->expects($this->once())
            ->method('request')
            ->with($this->matchesRegularExpression('/.*<card><type>VI.*<number>4100000000000001.*<expDate>1213.*<cardValidationNum>1213.*<pinlessDebitRequest>.*<routingPreference>pinlessDebitOnly.*<debitNetworkName>VI.*<debitNetworkName>MC.*/'));

        $cnpTest = new CnpOnlineRequest();
        $cnpTest->newXML = $mock;
        $cnpTest->saleRequest($hash_in);
    }
    public function test_sale_with_additionalCOFData()
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
            'merchantCategoryCode' => '6770',
            'billToAddress' => array(
                'name' => 'David Berman A',
                'addressLine1' => '10 Main Street',
                'city' => 'San Jose',
                'state' => 'ca',
                'zip' => '95032',
                'country' => 'USA',
                'email' => 'dberman@phoenixProcessing.com',
                'phone' => '781-270-1111',
                'sellerId' => '21234234A1',
                'url' => 'www.google.com',
            ),
            'shipToAddress' => array(
                'name' => 'Raymond J. Johnson Jr. B',
                'addressLine1' => '123 Main Street',
                'city' => 'McLean',
                'state' => 'VA',
                'zip' => '22102',
                'country' => 'USA',
                'email' => 'ray@rayjay.com',
                'phone' => '978-275-0000',
                'sellerId' => '21234234A2',
                'url' => 'www.google.com',
            ),
            'crypto' => 'true',
            'retailerAddress' => array(
                'name' => 'John doe',
                'addressLine1' => '123 Main Street',
                'addressLine2' => '123 Main Street',
                'addressLine3' => '123 Main Street',
                'city' => 'Cincinnati',
                'state' => 'OH',
                'zip' => '45209',
                'country' => 'USA',
                'email' => 'noone@abc.com',
                'phone' => '1234562783',
                'sellerId' => '21234234A',
                'companyName' => 'Google INC',
                'url' => 'www.google.com',
            ),
            'additionalCOFData' => array(
                'totalPaymentCount' => 'ND',
                'paymentType' => 'Fixed Amount',
                'uniqueId' => '234GTYH654RF13',
                'frequencyOfMIT' => 'Annually',
                'validationReference' => 'ANBH789UHY564RFC@EDB',
                'sequenceIndicator' => '86',
            ),
                'orderChannel' => 'IN_STORE_KIOSK',
                'fraudCheckStatus' => 'CLOSE',

        );


        $mock = $this->getMock('cnp\sdk\CnpXmlMapper');
        $mock->expects($this->once())
            ->method('request')
            ->with($this->matchesRegularExpression('/.*<sellerId>21234234A.*<url>www.google.com.*<orderChannel>IN_STORE_KIOSK.*<fraudCheckStatus>CLOSE.*/'));

        $cnpTest = new CnpOnlineRequest();
        $cnpTest->newXML = $mock;
        $cnpTest->saleRequest($hash_in);


    }
    public function test_simple_sale_with_authenticationValue()
    {
        $hash_in = array(
            'card' => array('type' => 'IC',
                'number' => '4100000000000000',
                'expDate' => '1213',
                'cardValidationNum' => '1213'),
            'cardholderAuthentication' => array(
                /// base64 value for dummy number '123456789012345678901234567890123456789012345678901234567890'
                /// System should accept the request with length 60 of authenticationValueType
                'authenticationValue' => 'MTIzNDU2Nzg5MDEyMzQ1Njc4OTAxMjM0NTY3ODkwMTIzNDU2Nzg5MDEyMzQ1Njc4OTAxMjM0NTY3ODkw'
            ),
            'id' => '1211',
            'orderId' => '2111',
            'reportGroup' => 'Planets',
            'orderSource' => 'ecommerce',
            'amount' => '123');

        $mock = $this->getMock('cnp\sdk\CnpXmlMapper');
        $mock->expects($this->once())
            ->method('request')
            ->with($this->matchesRegularExpression('/.*<type>IC.*<authenticationValue>MTIzNDU2Nzg5MDEyMzQ1Njc4OTAxMjM0NTY3ODkwMTIzNDU2Nzg5MDEyMzQ1Njc4OTAxMjM0NTY3ODkw.*/'));

        $cnpTest = new CnpOnlineRequest();
        $cnpTest->newXML = $mock;
        $cnpTest->saleRequest($hash_in);

    }

    public function test_sale_support_gp_with_lodging()
    {
        $hash_in = array(
            'id' => 'id',
            'orderId' => '82364_cnpApiAuth',
            'amount' => '1001',
            'orderSource' => 'telephone',
            'customerInfo' => array(
                'accountUsername' => 'username123',
                'userAccountNumber' => '7647326235897',
                'userAccountEmail' => 'dummtemail@abc.com',
                'membershipId' => '23874682304',
                'membershipPhone' => '16818807607551094758',
                'membershipEmail' => 'email@abc.com',
                'membershipName' => 'member123',
                'accountCreatedDate' => '2050-07-17',
                'userAccountPhone' => '1392345678',
            ),
            'card' => array(
                'type' => 'VI',
                'number' => '4005518220000002',
                'expDate' => '0150',
                'cardValidationNum' => '987',
            ),
            'enhancedData' => array(
                'customerReference' => 'cust ref sale1',
                'salesTax' => '1000',
                'discountAmount' => '0',
                'shippingAmount' => '0',
                'dutyAmount' => '0',
                'lineItemData' => array(
                    'itemSequenceNumber' => '1',
                    'itemDescription' => 'Clothes',
                    'productCode' => 'TB123',
                    'quantity' => '1',
                    'unitOfMeasure' => 'EACH',
                    'lineItemTotal' => '9900',
                    'lineItemTotalWithTax' => '10000',
                    'itemDiscountAmount' => '0',
                    'commodityCode' => '301',
                    'unitCost' => '31.02',
                    'itemCategory' => 'Aparel',
                    'itemSubCategory' => 'Clothing',
                ),
                'discountCode' => 'OneTimeDiscount11',
                'discountPercent' => '11',
                'fulfilmentMethodType' => 'DELIVERY',
            ),
            'lodgingInfo' => array(
                'bookingID' => 'book1234512341',
                'passengerName' => 'john cena',
                'propertyAddress' => array(
                    'name' => 'property1',
                    'city' => 'nyc',
                    'region' => 'KBA',
                    'country' => 'USA',
                ),
                'travelPackageIndicator' => 'Both',
                'smokingPreference' => 'N',
                'numberOfRooms' => '13',
                'tollFreePhoneNumber' => '1981876578076548',
            ),
            'orderChannel' => 'PHONE',
            'fraudCheckStatus' => 'CLOSE',
            'overridePolicy' => 'merchantPolicyToDecline',
            'fsErrorCode' => 'error123',
            'merchantAccountStatus' => 'activeAccount',
            'productEnrolled' => 'GUARPAY3',
            'decisionPurpose' => 'CONSIDER_DECISION',
            'fraudSwitchIndicator' => 'POST',
        );

        $mock = $this->getMock('cnp\sdk\CnpXmlMapper');
        $mock	->expects($this->once())
            ->method('request')
            ->with($this->matchesRegularExpression('/.*<lodgingInfo>.*<bookingID>book1234512341.*<passengerName>john cena.*<propertyAddress>.*<name>property1.*<city>nyc.*<region>KBA.*<country>USA.*<travelPackageIndicator>Both.*<smokingPreference>N.*<numberOfRooms>13.*<tollFreePhoneNumber>1981876578076548.*<overridePolicy>merchantPolicyToDecline.*<fsErrorCode>error123.*<merchantAccountStatus>activeAccount.*<productEnrolled>GUARPAY3.*<decisionPurpose>CONSIDER_DECISION.*<fraudSwitchIndicator>POST.*/'));

        $cnpTest = new CnpOnlineRequest();
        $cnpTest->newXML = $mock;
        $cnpTest->saleRequest($hash_in);
    }

    public function test_auth_with_passengerTransportData()
    {
        $hash_in = array('id' => 'id',
            'card' => array('type' => 'VI',
                'number' => '4100000000000000',
                'expDate' => '1213',
                'cardValidationNum' => '1213'),
            'id' => '1211',
            'orderId' => '22@403',
            'reportGroup' => 'Planets',
            'orderSource' => 'ecommerce',
            'amount' => '0',
            'passengerTransportData' =>array(
                'passengerName' =>'Mrs. Huxley234567890123456789',
                'ticketNumber' =>'ATL456789012345' ,
                'issuingCarrier' =>'AMTK',
                'carrierName' =>'AMTK',
                'restrictedTicketIndicator' =>'99999',
                'numberOfAdults' =>'2',
                'numberOfChildren' =>'0',
                'customerCode' =>'Railway',
                'arrivalDate' =>'2022-09-20',
                'issueDate' =>'2022-09-10',
                'travelAgencyCode' =>'12345678',
                'travelAgencyName' =>'Travel R Us23456789012345',
                'computerizedReservationSystem' =>'STRT',
                'creditReasonIndicator' =>'P',
                'ticketChangeIndicator' =>'C',
                'ticketIssuerAddress' =>'99 Second St',
                'exchangeTicketNumber' =>'123456789012346',
                'exchangeAmount' =>'500046',
                'exchangeFeeAmount' =>'5046',
                'tripLegData' =>array(
                    'tripLegNumber' =>'10' ,
                    'serviceClass' =>'First',
                    'departureDate' =>'2022-09-20',
                    'originCity' =>'BOS')
            ));

        $mock = $this->getMock('cnp\sdk\CnpXmlMapper');
        $mock	->expects($this->once())
            ->method('request')
            ->with($this->matchesRegularExpression('/.*<passengerName>Mrs. Huxley234567890123456789.*<ticketNumber>ATL456789012345.*<exchangeAmount>500046.*<serviceClass>First.*<originCity>BOS.*/'));

        $cnpTest = new CnpOnlineRequest();
        $cnpTest->newXML = $mock;
        $cnpTest->saleRequest($hash_in);
    }

    public function test_sale_with_sellerInfo()
    {
        $hash_in = array(
            'id' => 'id',
            'orderId' => '82364_cnpApiAuth',
            'amount' => '1001',
            'orderSource' => 'telephone',
            'customerInfo' => array(
                'accountUsername' => 'username123',
                'userAccountNumber' => '7647326235897',
                'userAccountEmail' => 'dummtemail@abc.com',
                'membershipId' => '23874682304',
                'membershipPhone' => '16818807607551094758',
                'membershipEmail' => 'email@abc.com',
                'membershipName' => 'member123',
                'accountCreatedDate' => '2050-07-17',
                'userAccountPhone' => '1392345678',
            ),
            'sellerInfo' => array(
                'accountNumber' => '4485581000000005',
                'aggregateOrderCount' => '4005518220000002',
                'aggregateOrderDollars' => '100',
                'sellerAddress' => array(
                    'sellerStreetaddress' => '15 Main Street',
                    'sellerUnit' => '100 AB',
                    'sellerPostalcode' => '12345',
                    'sellerCity' => 'San Jose',
                    'sellerProvincecode' => 'MA',
                    'sellerCountrycode' => 'US'),
                'createdDate' => '2015-11-12T20:33:09',
                'domain' => 'VAP',
                'email' => 'bob@example.com',
                'lastUpdateDate' => '2015-11-12T20:33:09',
                'name' => 'bob',
                'onboardingEmail' => 'bob@example.com',
                'onboardingIpAddress' => '75.100.88.78',
                'parentEntity' => 'abc',
                'phone' => '9785510040',
                'sellerId' => '123456789',
                'sellerTags' => array(
                    'tag' => '1',
                    'tag' => '2',
                    'tag' => '3'
                ),
                'username' => 'bob143'
            ),
            'sellerInfo' => array(
                'accountNumber' => '4485581000000005',
                'aggregateOrderCount' => '4005518220000002',
                'aggregateOrderDollars' => '100',
                'sellerAddress' => array(
                    'sellerStreetaddress1' => '15 Main Street',
                    'sellerUnit' => '100 AB',
                    'sellerPostalcode' => '12345',
                    'sellerCity' => 'San Jose',
                    'sellerProvincecode' => 'MA',
                    'sellerCountrycode' => 'US'
                ),
                'createdDate' => '2015-11-12T20:33:09',
                'domain' => 'VAP',
                'email' => 'bob@example.com',
                'lastUpdateDate' => '2015-11-12T20:33:09',
                'name' => 'bob',
                'onboardingEmail' => 'bob@example.com',
                'onboardingIpAddress' => '75.100.88.78',
                'parentEntity' => 'abc',
                'phone' => '9785510040',
                'sellerId' => '123456789',
                'sellerTags' => array(
                    'tag' => '1',
                    'tag' => '2',
                    'tag' => '3'
                ),
                'username' => 'bob143'
            ),
            'card' => array('type' => 'VI',
                'number' => '4100000000000000',
                'expDate' => '1213',
                'cardValidationNum' => '1213')
        );

        $mock = $this->getMock('cnp\sdk\CnpXmlMapper');
        $mock	->expects($this->once())
            ->method('request')
            ->with($this->matchesRegularExpression('/.*<accountNumber>4485581000000005.*<aggregateOrderDollars>100.*/'));

        $cnpTest = new CnpOnlineRequest();
        $cnpTest->newXML = $mock;
        $cnpTest->saleRequest($hash_in);
    }

}
