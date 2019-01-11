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
}
