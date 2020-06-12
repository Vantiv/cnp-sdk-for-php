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
namespace cnp\sdk\Test\functional;

use cnp\sdk\CnpOnlineRequest;
use cnp\sdk\CommManager;
use cnp\sdk\XmlParser;

class CaptureGivenAuthFunctionalTest extends \PHPUnit_Framework_TestCase
{
    public static function setUpBeforeClass()
    {
        CommManager::reset();
    }

    public function test_simple_captureGivenAuth()
    {
        $hash_in = array('id' => 'id',
            'orderId' => '12344',
            'amount' => '106',
            'authInformation' => array(
                'authDate' => '2002-10-09', 'authCode' => '543216',
                'authAmount' => '12345'),
            'orderSource' => 'ecommerce',
            'card' => array(
                'type' => 'VI',
                'number' => '4100000000000000',
                'expDate' => '1210'));

        $initialize = new CnpOnlineRequest();
        $captureGivenAuthResponse = $initialize->captureGivenAuthRequest($hash_in);
        $message = XmlParser::getNode($captureGivenAuthResponse, 'message');
        $this->assertEquals('Approved', $message);
        $location = XmlParser::getNode($captureGivenAuthResponse, 'location');
        $this->assertEquals('sandbox', $location);
    }

    public function test_simple_captureGivenAuth_with_token()
    {
        $hash_in = array('id' => 'id',
            'orderId' => '12344',
            'amount' => '106',
            'authInformation' => array(
                'authDate' => '2002-10-09', 'authCode' => '543216',
                'authAmount' => '12345'),
            'orderSource' => 'ecommerce',
            'token' => array(
                'type' => 'VI',
                'cnpToken' => '123456789101112',
                'expDate' => '1210'));

        $initialize = new CnpOnlineRequest();
        $captureGivenAuthResponse = $initialize->captureGivenAuthRequest($hash_in);
        $message = XmlParser::getNode($captureGivenAuthResponse, 'message');
        $this->assertEquals('Approved', $message);
        $location = XmlParser::getNode($captureGivenAuthResponse, 'location');
        $this->assertEquals('sandbox', $location);
    }

    public function test_complex_captureGivenAuth()
    {
        $hash_in = array('id' => 'id',
            'orderId' => '12344',
            'amount' => '106',
            'authInformation' => array(
                'authDate' => '2002-10-09', 'authCode' => '543216',
                'authAmount' => '12345'),
            'billToAddress' => array('name' => 'Bob', 'city' => 'lowell', 'state' => 'MA', 'email' => 'vantiv.com'),
            'processingInstructions' => array('bypassVelocityCheck' => 'true'),
            'orderSource' => 'ecommerce',
            'card' => array(
                'type' => 'VI',
                'number' => '4100000000000000',
                'expDate' => '1210'));

        $initialize = new CnpOnlineRequest();
        $captureGivenAuthResponse = $initialize->captureGivenAuthRequest($hash_in);
        $message = XmlParser::getNode($captureGivenAuthResponse, 'message');
        $this->assertEquals('Approved', $message);
        $location = XmlParser::getNode($captureGivenAuthResponse, 'location');
        $this->assertEquals('sandbox', $location);
    }

    public function test_authInfo()
    {
        $hash_in = array('id' => 'id',
            'orderId' => '12344',
            'amount' => '106',
            'authInformation' => array(
                'authDate' => '2002-10-09', 'authCode' => '543216',
                'authAmount' => '12345', 'fraudResult' => array('avsResult' => '12', 'cardValidationResult' => '123', 'authenticationResult' => '1',
                    'advancedAVSResult' => '123')),
            'orderSource' => 'ecommerce',
            'card' => array(
                'type' => 'VI',
                'number' => '4100000000000000',
                'expDate' => '1210'));

        $initialize = new CnpOnlineRequest();
        $captureGivenAuthResponse = $initialize->captureGivenAuthRequest($hash_in);
        $message = XmlParser::getNode($captureGivenAuthResponse, 'message');
        $this->assertEquals('Approved', $message);
        $location = XmlParser::getNode($captureGivenAuthResponse, 'location');
        $this->assertEquals('sandbox', $location);
    }

    public function test_simple_captureGivenAuth_secondary_amount()
    {
        $hash_in = array('id' => 'id',
            'orderId' => '12344',
            'amount' => '106',
            'secondaryAmount' => '2000',
            'authInformation' => array(
                'authDate' => '2002-10-09', 'authCode' => '543216',
                'authAmount' => '12345'),
            'orderSource' => 'ecommerce',
            'card' => array(
                'type' => 'VI',
                'number' => '4100000000000000',
                'expDate' => '1210'));

        $initialize = new CnpOnlineRequest();
        $captureGivenAuthResponse = $initialize->captureGivenAuthRequest($hash_in);
        $message = XmlParser::getNode($captureGivenAuthResponse, 'message');
        $this->assertEquals('Approved', $message);
        $location = XmlParser::getNode($captureGivenAuthResponse, 'location');
        $this->assertEquals('sandbox', $location);
    }

    public function test_simple_captureGivenAuth_with_processingType_orgntwtxnid_orgtxnamt()
    {
        $hash_in = array(
            'id' => 'id',
            'orderId' => '12344',
            'amount' => '106',
            'authInformation' => array(
                'authDate' => '2002-10-09',
                'authCode' => '543216',
                'authAmount' => '12345'
            ),
            'orderSource' => 'ecommerce',
            'card' => array(
                'type' => 'VI',
                'number' => '4100000000000000',
                'expDate' => '1210'),
            'processingType' => 'initialRecurring',
            'originalNetworkTransactionId' => 'abcdefghijklmnopqrstuvwxyz',
            'originalTransactionAmount' => '1000'
        );

        $initialize = new CnpOnlineRequest();
        $captureGivenAuthResponse = $initialize->captureGivenAuthRequest($hash_in);
        $message = XmlParser::getNode($captureGivenAuthResponse, 'message');
        $this->assertEquals('Approved', $message);
        $location = XmlParser::getNode($captureGivenAuthResponse, 'location');
        $this->assertEquals('sandbox', $location);
    }

        public function test_simple_capture_given_auth_with_tokenURL()
        {
            $hash_in = array(
            'merchantId' => '101',
          //  'version'=>'8.8',
            'id'=>'id',
            'reportGroup'=>'Planets',
            'orderId'=>'12344',
            'authInformation' => array(
                    'authDate'=>'2002-10-09','authCode'=>'543216', 'processingInstructions' => array ('bypassVelocityCheck'=>'true'),
            'authAmount'=>'12345'),
                      'amount'=>'106',
                      'orderSource'=>'ecommerce',
                      'token'=> array(
                          'tokenURL' => 'http://token.com/sales',
                          'expDate'=>'1210',
                          'cardValidationNum'=>'555',
                          'type'=>'VI'
                      ));
            $initialize = new CnpOnlineRequest();
            $captureGivenAuthResponse = $initialize->captureGivenAuthRequest($hash_in);
            $message = XmlParser::getNode($captureGivenAuthResponse, 'message');
            $this->assertEquals('Approved', $message);
            $location = XmlParser::getNode($captureGivenAuthResponse, 'location');
            $this->assertEquals('sandbox', $location);

        }




    public function test_simple_captureGivenAuth_with_MerchantCategoryCode()
    {
        $hash_in = array('id' => 'id',
            'orderId' => '12344',
            'amount' => '106',
            'authInformation' => array(
                'authDate' => '2002-10-09', 'authCode' => '543216',
                'authAmount' => '12345'),
            'orderSource' => 'ecommerce',
            'card' => array(
                'type' => 'VI',
                'number' => '4100000000000000',
                'expDate' => '1210'),
            'merchantCategoryCode' => '3535');

        $initialize = new CnpOnlineRequest();
        $captureGivenAuthResponse = $initialize->captureGivenAuthRequest($hash_in);
        $message = XmlParser::getNode($captureGivenAuthResponse, 'message');
        $this->assertEquals('Approved', $message);
        $location = XmlParser::getNode($captureGivenAuthResponse, 'location');
        $this->assertEquals('sandbox', $location);
    }

    public function test_simple_captureGivenAuth_with_token_with_MerchantCategoryCode()
    {
        $hash_in = array('id' => 'id',
            'orderId' => '12344',
            'amount' => '106',
            'authInformation' => array(
                'authDate' => '2002-10-09', 'authCode' => '543216',
                'authAmount' => '12345'),
            'orderSource' => 'ecommerce',
            'token' => array(
                'type' => 'VI',
                'cnpToken' => '123456789101112',
                'expDate' => '1210'),
            'merchantCategoryCode' => '2424');

        $initialize = new CnpOnlineRequest();
        $captureGivenAuthResponse = $initialize->captureGivenAuthRequest($hash_in);
        $message = XmlParser::getNode($captureGivenAuthResponse, 'message');
        $this->assertEquals('Approved', $message);
        $location = XmlParser::getNode($captureGivenAuthResponse, 'location');
        $this->assertEquals('sandbox', $location);
    }

    public function test_complex_captureGivenAuth_with_MerchantCategoryCode()
    {
        $hash_in = array('id' => 'id',
            'orderId' => '12344',
            'amount' => '106',
            'authInformation' => array(
                'authDate' => '2002-10-09', 'authCode' => '543216',
                'authAmount' => '12345'),
            'billToAddress' => array('name' => 'Bob', 'city' => 'lowell', 'state' => 'MA', 'email' => 'vantiv.com'),
            'processingInstructions' => array('bypassVelocityCheck' => 'true'),
            'orderSource' => 'ecommerce',
            'card' => array(
                'type' => 'VI',
                'number' => '4100000000000000',
                'expDate' => '1210'),
            'merchantCategoryCode' => '1234');

        $initialize = new CnpOnlineRequest();
        $captureGivenAuthResponse = $initialize->captureGivenAuthRequest($hash_in);
        $message = XmlParser::getNode($captureGivenAuthResponse, 'message');
        $this->assertEquals('Approved', $message);
        $location = XmlParser::getNode($captureGivenAuthResponse, 'location');
        $this->assertEquals('sandbox', $location);
    }

    public function test_authInfo_with_MerchantCategoryCode()
    {
        $hash_in = array('id' => 'id',
            'orderId' => '12344',
            'amount' => '106',
            'authInformation' => array(
                'authDate' => '2002-10-09', 'authCode' => '543216',
                'authAmount' => '12345', 'fraudResult' => array('avsResult' => '12', 'cardValidationResult' => '123', 'authenticationResult' => '1',
                    'advancedAVSResult' => '123')),
            'orderSource' => 'ecommerce',
            'card' => array(
                'type' => 'VI',
                'number' => '4100000000000000',
                'expDate' => '1210'),
            'merchantCategoryCode' => '2424');

        $initialize = new CnpOnlineRequest();
        $captureGivenAuthResponse = $initialize->captureGivenAuthRequest($hash_in);
        $message = XmlParser::getNode($captureGivenAuthResponse, 'message');
        $this->assertEquals('Approved', $message);
        $location = XmlParser::getNode($captureGivenAuthResponse, 'location');
        $this->assertEquals('sandbox', $location);
    }

    public function test_simple_captureGivenAuth_secondary_amount_with_MerchantCategoryCode()
    {
        $hash_in = array('id' => 'id',
            'orderId' => '12344',
            'amount' => '106',
            'secondaryAmount' => '2000',
            'authInformation' => array(
                'authDate' => '2002-10-09', 'authCode' => '543216',
                'authAmount' => '12345'),
            'orderSource' => 'ecommerce',
            'card' => array(
                'type' => 'VI',
                'number' => '4100000000000000',
                'expDate' => '1210'),
            'merchantCategoryCode' => '5678');

        $initialize = new CnpOnlineRequest();
        $captureGivenAuthResponse = $initialize->captureGivenAuthRequest($hash_in);
        $message = XmlParser::getNode($captureGivenAuthResponse, 'message');
        $this->assertEquals('Approved', $message);
        $location = XmlParser::getNode($captureGivenAuthResponse, 'location');
        $this->assertEquals('sandbox', $location);
    }

    public function test_simple_captureGivenAuth_with_processingType_orgntwtxnid_orgtxnamt_with_MerchantCategoryCode()
    {
        $hash_in = array(
            'id' => 'id',
            'orderId' => '12344',
            'amount' => '106',
            'authInformation' => array(
                'authDate' => '2002-10-09',
                'authCode' => '543216',
                'authAmount' => '12345'
            ),
            'orderSource' => 'ecommerce',
            'card' => array(
                'type' => 'VI',
                'number' => '4100000000000000',
                'expDate' => '1210'),
            'processingType' => 'initialRecurring',
            'originalNetworkTransactionId' => 'abcdefghijklmnopqrstuvwxyz',
            'originalTransactionAmount' => '1000',
            'merchantCategoryCode' => '1234'
        );

        $initialize = new CnpOnlineRequest();
        $captureGivenAuthResponse = $initialize->captureGivenAuthRequest($hash_in);
        $message = XmlParser::getNode($captureGivenAuthResponse, 'message');
        $this->assertEquals('Approved', $message);
        $location = XmlParser::getNode($captureGivenAuthResponse, 'location');
        $this->assertEquals('sandbox', $location);
    }

    public function test_simple_capture_given_auth_with_tokenURL_with_MerchantCategoryCode()
    {
        $hash_in = array(
            'merchantId' => '101',
            //  'version'=>'8.8',
            'id'=>'id',
            'reportGroup'=>'Planets',
            'orderId'=>'12344',
            'authInformation' => array(
                'authDate'=>'2002-10-09','authCode'=>'543216', 'processingInstructions' => array ('bypassVelocityCheck'=>'true'),
                'authAmount'=>'12345'),
            'amount'=>'106',
            'orderSource'=>'ecommerce',
            'token'=> array(
                'tokenURL' => 'http://token.com/sales',
                'expDate'=>'1210',
                'cardValidationNum'=>'555',
                'type'=>'VI'
            ),
            'merchantCategoryCode' => '4567');
        $initialize = new CnpOnlineRequest();
        $captureGivenAuthResponse = $initialize->captureGivenAuthRequest($hash_in);
        $message = XmlParser::getNode($captureGivenAuthResponse, 'message');
        $this->assertEquals('Approved', $message);
        $location = XmlParser::getNode($captureGivenAuthResponse, 'location');
        $this->assertEquals('sandbox', $location);

    }

}
