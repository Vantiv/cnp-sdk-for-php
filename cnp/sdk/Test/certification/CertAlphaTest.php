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

namespace cnp\sdk\Test\certification;

use cnp\sdk\CnpOnlineRequest;
use cnp\sdk\CommManager;
use cnp\sdk\XmlParser;

require_once realpath(__DIR__) . '/../../../../vendor/autoload.php';

define('PRELIVE_URL', 'https://payments.vantivprelive.com/vap/communicator/online');

//comment to see if can commit

class CertAlphaTest extends \PHPUnit_Framework_TestCase
{
    public static function setUpBeforeClass()
    {
        CommManager::reset();
    }

    function test_1_Auth()
    {
        $auth_hash = array('id' => '1211',
            #'user'=> '12312',
            'orderId' => '1',
            'amount' => '10100',
            'orderSource' => 'ecommerce',
            'billToAddress' => array(
                'name' => 'John & Mary Smith',
                'addressLine1' => '1 Main St.',
                'city' => 'Burlington',
                'state' => 'MA',
                'zip' => '01803-3747',
                'country' => 'US'),
            'card' => array(
                'number' => '4457010000000009',
                'expDate' => '0121',
                'cardValidationNum' => '349',
                'type' => 'VI'),
        'url' => PRELIVE_URL
        ,'proxy'=>'');
        echo 'Test with no proxy manual ......';
        $initialize = new CnpOnlineRequest();
        $authorizationResponse = $initialize->authorizationRequest($auth_hash);
        $this->assertEquals('000', XmlParser::getNode($authorizationResponse, 'response'));
        $this->assertEquals('Approved', XmlParser::getNode($authorizationResponse, 'message'));
        $this->assertEquals('11111', trim(XmlParser::getNode($authorizationResponse, 'authCode')));
        $this->assertEquals('1', XmlParser::getNode($authorizationResponse, 'avsResult'));
        $this->assertEquals('M', XmlParser::getNode($authorizationResponse, 'cardValidationResult'));

        //test 1A
        $capture_hash = array(
            'cnpTxnId' => (XmlParser::getNode($authorizationResponse, 'cnpTxnId')),
            'reportGroup' => 'planets', 'id' => '1211',
            'url' => PRELIVE_URL);
        $initialize = new CnpOnlineRequest();
        $captureResponse = $initialize->captureRequest($capture_hash);
        $this->assertEquals('000', XmlParser::getNode($captureResponse, 'response'));
        $this->assertEquals('Approved', XmlParser::getNode($captureResponse, 'message'));

        //test 1B
        $credit_hash = array(
            'cnpTxnId' => (XmlParser::getNode($captureResponse, 'cnpTxnId')),
            'reportGroup' => 'planets', 'id' => '1211',
            'url' => PRELIVE_URL);
        $initialize = new CnpOnlineRequest();
        $creditResponse = $initialize->creditRequest($credit_hash);
        $this->assertEquals('000', XmlParser::getNode($creditResponse, 'response'));
        $this->assertEquals('Approved', XmlParser::getNode($creditResponse, 'message'));

        //test 1C
        $void_hash = array(
            'cnpTxnId' => (XmlParser::getNode($creditResponse, 'cnpTxnId')),
            'reportGroup' => 'planets', 'id' => '1211',
            'url' => PRELIVE_URL);
        $initialize = new CnpOnlineRequest();
        $voidResponse = $initialize->voidRequest($void_hash);
        $this->assertEquals('000', XmlParser::getNode($voidResponse, 'response'));
        $this->assertEquals('Approved', XmlParser::getNode($voidResponse, 'message'));
    }

    function test_1_avs()
    {
        $auth_hash = array('id' => '1211',
            'orderId' => '1',
            'amount' => '0',
            'orderSource' => 'ecommerce',
            'billToAddress' => array(
                'name' => 'John & Mary Smith',
                'addressLine1' => '1 Main St.',
                'city' => 'Burlington',
                'state' => 'MA',
                'zip' => '01803-3747',
                'country' => 'US'),
            'card' => array(
                'number' => '4457010000000009',
                'expDate' => '0121',
                'cardValidationNum' => '349',
                'type' => 'VI'),
            'url' => PRELIVE_URL);
        $initialize = new CnpOnlineRequest();
        $authorizationResponse = $initialize->authorizationRequest($auth_hash);
        $this->assertEquals('000', XmlParser::getNode($authorizationResponse, 'response'));
        $this->assertEquals('Approved', XmlParser::getNode($authorizationResponse, 'message'));
        $this->assertEquals('11111', trim(XmlParser::getNode($authorizationResponse, 'authCode')));
        $this->assertEquals('1', XmlParser::getNode($authorizationResponse, 'avsResult'));
        $this->assertEquals('M', XmlParser::getNode($authorizationResponse, 'cardValidationResult'));
    }

    function test_1_sale()
    {
        $sale_hash = array('id' => '1211',
            'orderId' => '1',
            'amount' => '10100',
            'orderSource' => 'ecommerce',
            'billToAddress' => array(
                'name' => 'John & Mary Smith',
                'addressLine1' => '1 Main St.',
                'city' => 'Burlington',
                'state' => 'MA',
                'zip' => '01803-3747',
                'country' => 'US'),
            'card' => array(
                'number' => '4457010000000009',
                'expDate' => '0121',
                'cardValidationNum' => '349',
                'type' => 'VI'),
            'url' => PRELIVE_URL);
        $initialize = new CnpOnlineRequest();
        $saleResponse = $initialize->saleRequest($sale_hash);
        $this->assertEquals('000', XmlParser::getNode($saleResponse, 'response'));
        $this->assertEquals('Approved', XmlParser::getNode($saleResponse, 'message'));
        $this->assertEquals('11111', trim(XmlParser::getNode($saleResponse, 'authCode')));
        $this->assertEquals('1', XmlParser::getNode($saleResponse, 'avsResult'));
        $this->assertEquals('M', XmlParser::getNode($saleResponse, 'cardValidationResult'));

        $credit_hash = array(
            'cnpTxnId' => (XmlParser::getNode($saleResponse, 'cnpTxnId')),
            'reportGroup' => 'planets', 'id' => '1211',
            'url' => PRELIVE_URL);
        $initialize = new CnpOnlineRequest();
        $creditResponse = $initialize->creditRequest($credit_hash);
        $this->assertEquals('000', XmlParser::getNode($creditResponse, 'response'));
        $this->assertEquals('Approved', XmlParser::getNode($creditResponse, 'message'));

        $void_hash = array(
            'cnpTxnId' => (XmlParser::getNode($creditResponse, 'cnpTxnId')),
            'reportGroup' => 'planets', 'id' => '1211',
            'url' => PRELIVE_URL);
        $initialize = new CnpOnlineRequest();
        $voidResponse = $initialize->voidRequest($void_hash);
        $this->assertEquals('000', XmlParser::getNode($voidResponse, 'response'));
        $this->assertEquals('Approved', XmlParser::getNode($voidResponse, 'message'));
    }

    function test_2_Auth()
    {
        $auth_hash = array('id' => '1211',
            'orderId' => '2',
            'amount' => '20200',
            'orderSource' => 'ecommerce',
            'billToAddress' => array(
                'name' => 'Mike J. Hammer',
                'addressLine1' => '2 Main St.',
                'addressLine2' => 'Apt. 222',
                'city' => 'Riverside',
                'state' => 'RI',
                'zip' => '02915',
                'country' => 'US'),
            'card' => array(
                'number' => '5112010000000003',
                'expDate' => '0221',
                'cardValidationNum' => '261',
                'type' => 'MC'),
            'url' => PRELIVE_URL
            //TODO 3-D Secure transaction not supported by merchant
            //'cardholderAuthentication' => array('authenticationValue'=>'BwABBJQ1AgAAAAAgJDUCAAAAAAA=' )
        );

        $initialize = new CnpOnlineRequest();
        $authorizationResponse = $initialize->authorizationRequest($auth_hash);
        $this->assertEquals('000', XmlParser::getNode($authorizationResponse, 'response'));
        $this->assertEquals('Approved', XmlParser::getNode($authorizationResponse, 'message'));
        $this->assertEquals('22222', trim(XmlParser::getNode($authorizationResponse, 'authCode')));
        $this->assertEquals('10', XmlParser::getNode($authorizationResponse, 'avsResult'));
        $this->assertEquals('M', XmlParser::getNode($authorizationResponse, 'cardValidationResult'));

        //test 2A
        $capture_hash = array('id' => '1211',
            'cnpTxnId' => (XmlParser::getNode($authorizationResponse, 'cnpTxnId')),
            'reportGroup' => 'planets',
            'url' => PRELIVE_URL);
        $initialize = new CnpOnlineRequest();
        $captureResponse = $initialize->captureRequest($capture_hash);
        $this->assertEquals('000', XmlParser::getNode($captureResponse, 'response'));
        $this->assertEquals('Approved', XmlParser::getNode($captureResponse, 'message'));

        //test 2B
        $credit_hash = array('id' => '1211',
            'cnpTxnId' => (XmlParser::getNode($captureResponse, 'cnpTxnId')),
            'reportGroup' => 'planets',
            'url' => PRELIVE_URL);
        $initialize = new CnpOnlineRequest();
        $creditResponse = $initialize->creditRequest($credit_hash);
        $this->assertEquals('000', XmlParser::getNode($creditResponse, 'response'));
        $this->assertEquals('Approved', XmlParser::getNode($creditResponse, 'message'));

        //test 2C
        $void_hash = array('id' => '1211',
            'cnpTxnId' => (XmlParser::getNode($creditResponse, 'cnpTxnId')),
            'reportGroup' => 'planets',
            'url' => PRELIVE_URL);
        $initialize = new CnpOnlineRequest();
        $voidResponse = $initialize->voidRequest($void_hash);
        $this->assertEquals('000', XmlParser::getNode($voidResponse, 'response'));
        $this->assertEquals('Approved', XmlParser::getNode($voidResponse, 'message'));
    }

    function test_2_avs()
    {
        $auth_hash = array('id' => '1211',
            'orderId' => '2',
            'amount' => '0',
            'orderSource' => 'ecommerce',
            'billToAddress' => array(
                'name' => 'Mike J. Hammer',
                'addressLine1' => '2 Main St.',
                'addressLine2' => 'Apt. 222',
                'city' => 'Riverside',
                'state' => 'RI',
                'zip' => '02915',
                'country' => 'US'),
            'card' => array(
                'number' => '5112010000000003',
                'expDate' => '0221',
                'cardValidationNum' => '261',
                'type' => 'MC'),
            'url' => PRELIVE_URL
            //TODO run against prelive for certification
            //'cardholderAuthentication' => array('authenticationValue'=>'BwABBJQ1AgAAAAAgJDUCAAAAAAA=' )
        );

        $initialize = new CnpOnlineRequest();
        $authorizationResponse = $initialize->authorizationRequest($auth_hash);
        $this->assertEquals('000', XmlParser::getNode($authorizationResponse, 'response'));
        $this->assertEquals('Approved', XmlParser::getNode($authorizationResponse, 'message'));
        $this->assertEquals('22222', trim(XmlParser::getNode($authorizationResponse, 'authCode')));
        $this->assertEquals('10', XmlParser::getNode($authorizationResponse, 'avsResult'));
        $this->assertEquals('M', XmlParser::getNode($authorizationResponse, 'cardValidationResult'));
    }

    function test_2_sale()
    {
        $sale_hash = array('id' => '1211',
            'orderId' => '2',
            'amount' => '20200',
            'orderSource' => 'ecommerce',
            'billToAddress' => array(
                'name' => 'Mike J. Hammer',
                'addressLine1' => '2 Main St.',
                'addressLine2' => 'Apt. 222',
                'city' => 'Riverside',
                'state' => 'RI',
                'zip' => '02915',
                'country' => 'US'),
            'card' => array(
                'number' => '5112010000000003',
                'expDate' => '0221',
                'cardValidationNum' => '261',
                'type' => 'MC'),
            'cardholderAuthentication' => array('authenticationValue' => 'BwABBJQ1AgAAAAAgJDUCAAAAAAA='),
            'url' => PRELIVE_URL);

        $initialize = new CnpOnlineRequest();
        $saleResponse = $initialize->saleRequest($sale_hash);
        $this->assertEquals('000', XmlParser::getNode($saleResponse, 'response'));
        $this->assertEquals('Approved', XmlParser::getNode($saleResponse, 'message'));

        $this->assertEquals('22222', trim(XmlParser::getNode($saleResponse, 'authCode')));
        $this->assertEquals('10', XmlParser::getNode($saleResponse, 'avsResult'));
       $this->assertEquals('M', XmlParser::getNode($saleResponse, 'cardValidationResult'));

        //test 2B
        $credit_hash = array('id' => '1211',
            'cnpTxnId' => (XmlParser::getNode($saleResponse, 'cnpTxnId')),
            'reportGroup' => 'planets',
            'url' => PRELIVE_URL);
        $initialize = new CnpOnlineRequest();
        $creditResponse = $initialize->creditRequest($credit_hash);
        $this->assertEquals('000', XmlParser::getNode($creditResponse, 'response'));
        $this->assertEquals('Approved', XmlParser::getNode($creditResponse, 'message'));

        //test 2C
        $void_hash = array('id' => '1211',
            'cnpTxnId' => (XmlParser::getNode($creditResponse, 'cnpTxnId')),
            'reportGroup' => 'planets',
            'url' => PRELIVE_URL);
        $initialize = new CnpOnlineRequest();
        $voidResponse = $initialize->voidRequest($void_hash);
        $this->assertEquals('000', XmlParser::getNode($voidResponse, 'response'));
        $this->assertEquals('Approved', XmlParser::getNode($voidResponse, 'message'));
    }

    function test_3_Auth()
    {
        $auth_hash = array(
            'orderId' => '3', 'id' => '1211',
            'amount' => '30300',
            'orderSource' => 'ecommerce',
            'billToAddress' => array(
                'name' => 'Eileen Jones',
                'addressLine1' => '3 Main St.',
                'city' => 'Bloomfield',
                'state' => 'CT',
                'zip' => '06002',
                'country' => 'US'),
            'card' => array(
                'number' => '6011010000000003',
                'expDate' => '0321',
                'type' => 'DI',
                'cardValidationNum' => '758'),
            'url' => PRELIVE_URL);

        $initialize = new CnpOnlineRequest();
        $authorizationResponse = $initialize->authorizationRequest($auth_hash);
        $this->assertEquals('000', XmlParser::getNode($authorizationResponse, 'response'));
        $this->assertEquals('Approved', XmlParser::getNode($authorizationResponse, 'message'));
        $this->assertEquals('33333', trim(XmlParser::getNode($authorizationResponse, 'authCode')));
        $this->assertEquals('10', XmlParser::getNode($authorizationResponse, 'avsResult'));
        $this->assertEquals('M', XmlParser::getNode($authorizationResponse, 'cardValidationResult'));

        //test 3A
        $capture_hash = array('id' => '1211',
            'cnpTxnId' => (XmlParser::getNode($authorizationResponse, 'cnpTxnId')),
            'reportGroup' => 'planets', 'id' => '1211',
            'url' => PRELIVE_URL);
        $initialize = new CnpOnlineRequest();
        $captureResponse = $initialize->captureRequest($capture_hash);
        $this->assertEquals('000', XmlParser::getNode($captureResponse, 'response'));
        $this->assertEquals('Approved', XmlParser::getNode($captureResponse, 'message'));

        //test 3B
        $credit_hash = array('id' => '1211',
            'cnpTxnId' => (XmlParser::getNode($captureResponse, 'cnpTxnId')),
            'reportGroup' => 'planets',
            'url' => PRELIVE_URL);
        $initialize = new CnpOnlineRequest();
        $creditResponse = $initialize->creditRequest($credit_hash);
        $this->assertEquals('000', XmlParser::getNode($creditResponse, 'response'));
        $this->assertEquals('Approved', XmlParser::getNode($creditResponse, 'message'));

        //test 3C
        $void_hash = array('id' => '1211',
            'cnpTxnId' => (XmlParser::getNode($creditResponse, 'cnpTxnId')),
            'reportGroup' => 'planets',
            'url' => PRELIVE_URL);
        $initialize = new CnpOnlineRequest();
        $voidResponse = $initialize->voidRequest($void_hash);
        $this->assertEquals('000', XmlParser::getNode($voidResponse, 'response'));
        $this->assertEquals('Approved', XmlParser::getNode($voidResponse, 'message'));
    }

    function test_3_avs()
    {
        $auth_hash = array('id' => '1211',
            'orderId' => '3',
            'amount' => '0',
            'orderSource' => 'ecommerce',
            'billToAddress' => array(
                'name' => 'Eileen Jones',
                'addressLine1' => '3 Main St.',
                'city' => 'Bloomfield',
                'state' => 'CT',
                'zip' => '06002',
                'country' => 'US'),
            'card' => array(
                'number' => '6011010000000003',
                'expDate' => '0321',
                'type' => 'DI',
                'cardValidationNum' => '758'),
            'url' => PRELIVE_URL);
        $initialize = new CnpOnlineRequest();
        $authorizationResponse = $initialize->authorizationRequest($auth_hash);
        $this->assertEquals('000', XmlParser::getNode($authorizationResponse, 'response'));
        $this->assertEquals('Approved', XmlParser::getNode($authorizationResponse, 'message'));
        $this->assertEquals('33333', trim(XmlParser::getNode($authorizationResponse, 'authCode')));
        $this->assertEquals('10', XmlParser::getNode($authorizationResponse, 'avsResult'));
        $this->assertEquals('M', XmlParser::getNode($authorizationResponse, 'cardValidationResult'));
    }

    function test_3_sale()
    {
        $sale_hash = array('id' => '1211',
            'orderId' => '3',
            'amount' => '30300',
            'orderSource' => 'ecommerce',
            'billToAddress' => array(
                'name' => 'Eileen Jones',
                'addressLine1' => '3 Main St.',
                'city' => 'Bloomfield',
                'state' => 'CT',
                'zip' => '06002',
                'country' => 'US'),
            'card' => array(
                'number' => '6011010000000003',
                'expDate' => '0321',
                'type' => 'DI',
                'cardValidationNum' => '758'),
            'url' => PRELIVE_URL);

        $initialize = new CnpOnlineRequest();
        $saleResponse = $initialize->saleRequest($sale_hash);
       $this->assertEquals('000', XmlParser::getNode($saleResponse, 'response'));
        $this->assertEquals('Approved', XmlParser::getNode($saleResponse, 'message'));
        $this->assertEquals('33333', trim(XmlParser::getNode($saleResponse, 'authCode')));
        $this->assertEquals('10', XmlParser::getNode($saleResponse, 'avsResult'));
        $this->assertEquals('M', XmlParser::getNode($saleResponse, 'cardValidationResult'));

        //test 3B
        $credit_hash = array('id' => '1211',
            'cnpTxnId' => (XmlParser::getNode($saleResponse, 'cnpTxnId')),
            'reportGroup' => 'planets',
            'url' => PRELIVE_URL);
        $initialize = new CnpOnlineRequest();
        $creditResponse = $initialize->creditRequest($credit_hash);
        $this->assertEquals('000', XmlParser::getNode($creditResponse, 'response'));
        $this->assertEquals('Approved', XmlParser::getNode($creditResponse, 'message'));

        //test 3C
        $void_hash = array('id' => '1211',
            'cnpTxnId' => (XmlParser::getNode($creditResponse, 'cnpTxnId')),
            'reportGroup' => 'planets',
            'url' => PRELIVE_URL);
        $initialize = new CnpOnlineRequest();
        $voidResponse = $initialize->voidRequest($void_hash);
        $this->assertEquals('000', XmlParser::getNode($voidResponse, 'response'));
        $this->assertEquals('Approved', XmlParser::getNode($voidResponse, 'message'));
    }

    function test_4_Auth()
    {
        $auth_hash = array('id' => '1211',
            'orderId' => '4',
            'amount' => '40040',
            'orderSource' => 'ecommerce',
            'billToAddress' => array(
                'name' => 'Bob Black',
                'addressLine1' => '4 Main St.',
                'city' => 'Laurel',
                'state' => 'MD',
                'zip' => '20708',
                'country' => 'US'),
            'card' => array(
                'number' => '375001000000005',
                'expDate' => '0421',
                'type' => 'AX'),
            'url' => PRELIVE_URL);

        $initialize = new CnpOnlineRequest();
        $authorizationResponse = $initialize->authorizationRequest($auth_hash);
        //TODO run against prelive for certification
        $this->assertEquals('000',XmlParser::getNode($authorizationResponse,'response'));
        $this->assertEquals('Approved',XmlParser::getNode($authorizationResponse,'message'));
        //$this->assertEquals('44444',XmlParser::getNode($authorizationResponse,'authCode'));
        //$this->assertEquals('12',XmlParser::getNode($authorizationResponse,'avsResult'));

        //test 4A
        $capture_hash = array('id' => '1211',
            'cnpTxnId' => (XmlParser::getNode($authorizationResponse, 'cnpTxnId')),
            'reportGroup' => 'planets',
            'url' => PRELIVE_URL);
        $initialize = new CnpOnlineRequest();
        $captureResponse = $initialize->captureRequest($capture_hash);
        $this->assertEquals('000',XmlParser::getNode($captureResponse,'response'));
        $this->assertEquals('Approved', XmlParser::getNode($captureResponse, 'message'));

        //test 4B
        $credit_hash = array('id' => '1211',
            'cnpTxnId' => (XmlParser::getNode($captureResponse, 'cnpTxnId')),
            'reportGroup' => 'planets',
            'url' => PRELIVE_URL);
        $initialize = new CnpOnlineRequest();
        $creditResponse = $initialize->creditRequest($credit_hash);
        $this->assertEquals('000', XmlParser::getNode($creditResponse, 'response'));
        $this->assertEquals('Approved', XmlParser::getNode($creditResponse, 'message'));

        //test 4C
        $void_hash = array('id' => '1211',
            'cnpTxnId' => (XmlParser::getNode($creditResponse, 'cnpTxnId')),
            'reportGroup' => 'planets',
            'url' => PRELIVE_URL);
        $initialize = new CnpOnlineRequest();
        $voidResponse = $initialize->voidRequest($void_hash);
        $this->assertEquals('000', XmlParser::getNode($voidResponse, 'response'));
        $this->assertEquals('Approved', XmlParser::getNode($voidResponse, 'message'));
    }

    function test_4_avs()
    {
        $auth_hash = array('id' => '1211',
            'orderId' => '4',
            'amount' => '0',
            'orderSource' => 'ecommerce',
            'billToAddress' => array(
                'name' => 'Bob Black',
                'addressLine1' => '4 Main St.',
                'city' => 'Laurel',
                'state' => 'MD',
                'zip' => '20708',
                'country' => 'US'),
            'card' => array(
                'number' => '375001000000005',
                'expDate' => '0421',
                'type' => 'AX'),
            'url' => PRELIVE_URL);

        $initialize = new CnpOnlineRequest();
        $authorizationResponse = $initialize->authorizationRequest($auth_hash);
        $this->assertEquals('000',XmlParser::getNode($authorizationResponse,'response'));
        $this->assertEquals('Approved',XmlParser::getNode($authorizationResponse,'message'));
//        $this->assertEquals('44444 ',XmlParser::getNode($authorizationResponse,'authCode'));
//        $this->assertEquals('12',XmlParser::getNode($authorizationResponse,'avsResult'));

    }

    function test_4_sale()
    {
        $sale_hash = array('id' => '1211',
            'orderId' => '4',
            'amount' => '40040',
            'orderSource' => 'ecommerce',
            'billToAddress' => array(
                'name' => 'Bob Black',
                'addressLine1' => '4 Main St.',
                'city' => 'Laurel',
                'state' => 'MD',
                'zip' => '20708',
                'country' => 'US'),
            'card' => array(
                'number' => '375001000000005',
                'expDate' => '0421',
                'type' => 'AX'),
            'url' => PRELIVE_URL);

        $initialize = new CnpOnlineRequest();
        $saleResponse = $initialize->saleRequest($sale_hash);
        //TODO run against prelive for certification
        $this->assertEquals('000',XmlParser::getNode($saleResponse,'response'));
        $this->assertEquals('Approved',XmlParser::getNode($saleResponse,'message'));
        //$this->assertEquals('44444',XmlParser::getNode($saleResponse,'authCode'));
        //TODO Returning 13
        //$this->assertEquals('12',XmlParser::getNode($saleResponse,'avsResult'));

        //test 4B
        $credit_hash = array('id' => '1211',
            'cnpTxnId' => (XmlParser::getNode($saleResponse, 'cnpTxnId')),
            'reportGroup' => 'planets',
            'url' => PRELIVE_URL);
        $initialize = new CnpOnlineRequest();
        $creditResponse = $initialize->creditRequest($credit_hash);
        $this->assertEquals('000', XmlParser::getNode($creditResponse, 'response'));
        $this->assertEquals('Approved', XmlParser::getNode($creditResponse, 'message'));

        //test 4C
        $void_hash = array('id' => '1211',
            'cnpTxnId' => (XmlParser::getNode($creditResponse, 'cnpTxnId')),
            'reportGroup' => 'planets',
            'url' => PRELIVE_URL);
        $initialize = new CnpOnlineRequest();
        $voidResponse = $initialize->voidRequest($void_hash);
        $this->assertEquals('000', XmlParser::getNode($voidResponse, 'response'));
        $this->assertEquals('Approved', XmlParser::getNode($voidResponse, 'message'));
    }

    function test_5_auth()
    {
        $auth_hash = array('id' => '1211',
            'orderId' => '5',
            'amount' => '50050',
            'orderSource' => 'ecommerce',
            'card' => array(
                'number' => '4457010200000007',
                'expDate' => '0521',
                'cardValidationNum' => '463',
                'type' => 'VI'),
            'cardholderAuthentication' => array('authenticationValue'=> 'BwABBJQ1AgAAAAAgJDUCAAAAAAA='),
            'url' => PRELIVE_URL
        );

        $initialize = new CnpOnlineRequest();
        $authorizationResponse = $initialize->authorizationRequest($auth_hash);
        $this->assertEquals('000', XmlParser::getNode($authorizationResponse, 'response'));
        $this->assertEquals('Approved', XmlParser::getNode($authorizationResponse, 'message'));
        $this->assertEquals('55555', trim(XmlParser::getNode($authorizationResponse, 'authCode')));
        $this->assertEquals('32', XmlParser::getNode($authorizationResponse, 'avsResult'));
        $this->assertEquals('M', XmlParser::getNode($authorizationResponse, 'cardValidationResult'));

        //test 5A
        $capture_hash = array('id' => '1211',
            'cnpTxnId' => (XmlParser::getNode($authorizationResponse, 'cnpTxnId')),
            'reportGroup' => 'planets',
            'url' => PRELIVE_URL);
        $initialize = new CnpOnlineRequest();
        $captureResponse = $initialize->captureRequest($capture_hash);
        $this->assertEquals('000', XmlParser::getNode($captureResponse, 'response'));
        $this->assertEquals('Approved', XmlParser::getNode($captureResponse, 'message'));

        //test 5B
        $credit_hash = array('id' => '1211',
            'cnpTxnId' => (XmlParser::getNode($captureResponse, 'cnpTxnId')),
            'reportGroup' => 'planets',
            'url' => PRELIVE_URL);
        $initialize = new CnpOnlineRequest();
        $creditResponse = $initialize->creditRequest($credit_hash);
        $this->assertEquals('000', XmlParser::getNode($creditResponse, 'response'));
        $this->assertEquals('Approved', XmlParser::getNode($creditResponse, 'message'));

        //test 5C
        $void_hash = array('id' => '1211',
            'cnpTxnId' => (XmlParser::getNode($creditResponse, 'cnpTxnId')),
            'reportGroup' => 'planets',
            'url' => PRELIVE_URL);
        $initialize = new CnpOnlineRequest();
        $voidResponse = $initialize->voidRequest($void_hash);
        $this->assertEquals('000', XmlParser::getNode($voidResponse, 'response'));
        $this->assertEquals('Approved', XmlParser::getNode($voidResponse, 'message'));
    }

    function test_5_avs()
    {
        $auth_hash = array('id' => '1211',
            'orderId' => '5',
            'amount' => '0',
            'orderSource' => 'ecommerce',
            'card' => array(
                'number' => '4457010200000007',
                'expDate' => '0521',
                'cardValidationNum' => '463',
                'type' => 'VI'),
            'cardholderAuthentication' => array('authenticationValue'=> 'BwABBJQ1AgAAAAAgJDUCAAAAAAA='),
            'url' => PRELIVE_URL
        );

        $initialize = new CnpOnlineRequest();
        $authorizationResponse = $initialize->authorizationRequest($auth_hash);
        $this->assertEquals('000', XmlParser::getNode($authorizationResponse, 'response'));
        $this->assertEquals('Approved', XmlParser::getNode($authorizationResponse, 'message'));
        $this->assertEquals('55555 ', XmlParser::getNode($authorizationResponse, 'authCode'));
        $this->assertEquals('32', XmlParser::getNode($authorizationResponse, 'avsResult'));
        $this->assertEquals('M', XmlParser::getNode($authorizationResponse, 'cardValidationResult'));
    }

    function test_5_sale()
    {
        $sale_hash = array('id' => '1211',
            'orderId' => '5',
            'amount' => '50050',
            'orderSource' => 'ecommerce',
            'card' => array(
                'number' => '4457010200000007',
                'expDate' => '0521',
                'cardValidationNum' => '463',
                'type' => 'VI'),
            'cardholderAuthentication' => array('authenticationValue'=> 'BwABBJQ1AgAAAAAgJDUCAAAAAAA='),
            'url' => PRELIVE_URL
        );

        $initialize = new CnpOnlineRequest();
        $saleResponse = $initialize->saleRequest($sale_hash);
        $this->assertEquals('000', XmlParser::getNode($saleResponse, 'response'));
        $this->assertEquals('Approved', XmlParser::getNode($saleResponse, 'message'));
        $this->assertEquals('55555 ', XmlParser::getNode($saleResponse, 'authCode'));
        $this->assertEquals('32', XmlParser::getNode($saleResponse, 'avsResult'));
        $this->assertEquals('M', XmlParser::getNode($saleResponse, 'cardValidationResult'));

        //test 5B
        $credit_hash = array('id' => '1211',
            'cnpTxnId' => (XmlParser::getNode($saleResponse, 'cnpTxnId')),
            'reportGroup' => 'planets',
            'url' => PRELIVE_URL);
        $initialize = new CnpOnlineRequest();
        $creditResponse = $initialize->creditRequest($credit_hash);
        $this->assertEquals('000', XmlParser::getNode($creditResponse, 'response'));
        $this->assertEquals('Approved', XmlParser::getNode($creditResponse, 'message'));

        //test 5C
        $void_hash = array('id' => '1211',
            'cnpTxnId' => (XmlParser::getNode($creditResponse, 'cnpTxnId')),
            'reportGroup' => 'planets',
            'url' => PRELIVE_URL);
        $initialize = new CnpOnlineRequest();
        $voidResponse = $initialize->voidRequest($void_hash);
        $this->assertEquals('000', XmlParser::getNode($voidResponse, 'response'));
        $this->assertEquals('Approved', XmlParser::getNode($voidResponse, 'message'));
    }

    //TODO: incorrect responses for p1 sale

//    function test_p1_idealSale()
//    {
//        $sale_hash = array('id' => '1211',
//            'orderId' => 'p1_idealSale',
//            'amount' => '10011',
//            'orderSource' => 'ecommerce',
//            'billToAddress' => array('name' => 'David Berman',
//                'country' => 'NL'
//            ),
//            'ideal' => array(),
//            'url' => PRELIVE_URL
//        );
//
//        $initialize = new CnpOnlineRequest();
//        $saleResponse = $initialize->saleRequest($sale_hash);
//        $this->assertEquals('000', XmlParser::getNode($saleResponse, 'response'));
//        $this->assertEquals('Approved', XmlParser::getNode($saleResponse, 'message'));
//        $this->assertEquals('Cert bank page ', XmlParser::getNode($saleResponse, 'redirectUrl'));
//        $this->assertEquals('Dynamically Generated', XmlParser::getNode($saleResponse, 'redirectToken'));
    }

//    function test_n10_idealSale()
//    {
//        $sale_hash = array('id' => '1211',
//            'orderId' => 'p1_idealSale',
//            'amount' => '10011',
//            'orderSource' => 'ecommerce',
//            'billToAddress' => array('name' => 'David Berman',
//                'country' => 'US'
//            ),
//            'ideal' => array(),
//            'url' => PRELIVE_URL
//        );
//
//        $initialize = new CnpOnlineRequest();
//        $saleResponse = $initialize->saleRequest($sale_hash);
//        $this->assertEquals('917', XmlParser::getNode($saleResponse, 'response'));
//        $this->assertEquals('Invalid billing country code', XmlParser::getNode($saleResponse, 'message'));
////        $this->assertEquals('Cert bank page ', XmlParser::getNode($saleResponse, 'redirectUrl'));
////        $this->assertEquals('Dynamically Generated', XmlParser::getNode($saleResponse, 'redirectToken'));
//    }

//    function test_p1_giropaySale()
//    {
//        $sale_hash = array('id' => '1211',
//            'orderId' => 'p1_giropaySale',
//            'amount' => '10011',
//            'orderSource' => 'ecommerce',
//            'billToAddress' => array('name' => 'David Berman',
//                'country' => 'DE'
//            ),
//            'giropay' => array(),
//            'url' => PRELIVE_URL
//        );
//
//        $initialize = new CnpOnlineRequest();
//        $saleResponse = $initialize->saleRequest($sale_hash);
//        $this->assertEquals('000', XmlParser::getNode($saleResponse, 'response'));
//        $this->assertEquals('Approved', XmlParser::getNode($saleResponse, 'message'));
//        $this->assertEquals('Cert bank page ', XmlParser::getNode($saleResponse, 'redirectUrl'));
//        $this->assertEquals('Dynamically Generated', XmlParser::getNode($saleResponse, 'redirectToken'));
//    }

//    function test_n10_giropaySale()
//    {
//        $sale_hash = array('id' => '1211',
//            'orderId' => 'n10_giropaySale',
//            'amount' => '20100',
//            'orderSource' => 'ecommerce',
//            'billToAddress' => array('name' => 'David Berman',
//                'country' => 'US'
//            ),
//            'giropay' => array(),
//            'url' => PRELIVE_URL
//        );
//
//        $initialize = new CnpOnlineRequest();
//        $saleResponse = $initialize->saleRequest($sale_hash);
//        $this->assertEquals('917', XmlParser::getNode($saleResponse, 'response'));
//        $this->assertEquals('Invalid billing country code', XmlParser::getNode($saleResponse, 'message'));
//    }

//    function test_p1_sofortSale()
//    {
//        $sale_hash = array('id' => '1211',
//            'orderId' => 'p1_sofortSale',
//            'amount' => '10011',
//            'orderSource' => 'ecommerce',
//            'billToAddress' => array('name' => 'David Berman',
//                'country' => 'NL'
//            ),
//            'sofort' => array(),
//            'url' => PRELIVE_URL
//        );
//
//        $initialize = new CnpOnlineRequest();
//        $saleResponse = $initialize->saleRequest($sale_hash);
////        $this->assertEquals('000', XmlParser::getNode($saleResponse, 'response'));
////        $this->assertEquals('Approved', XmlParser::getNode($saleResponse, 'message'));
////        $this->assertEquals('Cert bank page ', XmlParser::getNode($saleResponse, 'redirectUrl'));
////        $this->assertEquals('Dynamically Generated', XmlParser::getNode($saleResponse, 'redirectToken'));
//    }
//
//    function test_n10_sofortSale()
//    {
//        $sale_hash = array('id' => '1211',
//            'orderId' => 'n10_sofortSale',
//            'amount' => '20100',
//            'orderSource' => 'ecommerce',
//            'billToAddress' => array('name' => 'David Berman',
//                'country' => 'US'
//            ),
//            'sofort' => array(),
//            'url' => PRELIVE_URL
//        );
//
//        $initialize = new CnpOnlineRequest();
//        $saleResponse = $initialize->saleRequest($sale_hash);
//        $this->assertEquals('917', XmlParser::getNode($saleResponse, 'response'));
//        $this->assertEquals('Invalid billing country code', XmlParser::getNode($saleResponse, 'message'));
//    }
//}
