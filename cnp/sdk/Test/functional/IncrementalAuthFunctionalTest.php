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


class IncrementalAuthFunctionalTest extends \PHPUnit_Framework_TestCase
{

    public static function setUpBeforeClass()
    {
        CommManager::reset();
    }


    public function test_increamental_auth()
    {
        $hash_in = array('id' => 'id',
            'cnpTxnId' => '82935478257580213',
            'orderId' => '82364_cnpApiAuth',
            'amount' => '1001',
            'orderSource' => 'telephone',
            'billToAddress' => array(
                'name' => 'Jonathan Ross',
                'firstName' => 'wsdfaaa',
                'middleInitial' => 'middleInitial',
                'lastName' => 'lastName',
                'companyName' => 'companyName',
                'addressLine1' => '10th Floor',
                'addressLine2' => 'Tower 2',
                'addressLine3' => '900 Chelmsford Street',
                'city' => 'Lowell',
                'state' => 'MA',
                'zip' => '01851',
                'country' => 'USA',
                'email' => 'jross@litle.com<',
                'phone' => '800-548-5326',
                'url' => 'mail',
            ),
            'shipToAddress' => array(
                'name' => 'Jonathan Ross',
                'firstName' => 'wsdfaaa',
                'middleInitial' => 'middleInitial',
                'lastName' => 'lastName',
                'companyName' => 'companyName',
                'addressLine1' => '10th Floor',
                'addressLine2' => 'Tower 2',
                'addressLine3' => '900 Chelmsford Street',
                'city' => 'Lowell',
                'state' => 'MA',
                'zip' => '01851',
                'country' => 'USA',
                'email' => 'jross@litle.com<',
                'phone' => '800-548-5326',
                'url' => 'mail',
            ),
            'card' => array(
                'type' => 'VI',
                'number' => '4005518220000002',
                'expDate' => '0150',
                'cardValidationNum' => '987',
            ),
            'cardholderAuthentication' => array(
                'authenticationValue' => 'MTIzNDU2Nzg5MDEyMzQ1Njc4OTAxMjM0NTY3ODkwMTIzNDU2Nzg5MDEyMzQ1Njc4OTAxMjM0NTY3ODkw',
                'customerIpAddress' => '123'
            ),
            'customBilling' => array(
                'city' => 'Boston',
                'descriptor' => 'descriptor'),
            'allowPartialAuth' => 'true',
            'wallet' => array(
                'walletSourceType' => 'MasterPass',
                'walletSourceTypeId' => 'swyfwe',
            ),
            'originalNetworkTransactionId' => '12345',
            'merchantCategoryCode' => '1233',
            'originalRetrievalReferenceNumber' => 'sdwf',
            'cumulativeAmount' => '1230',
            'originalTransactionAmount' => '2345'
        );

        $initialize = new CnpOnlineRequest();
        $authorizationResponse = $initialize->realtimeIncrementalAuthorization($hash_in);

        $response = XmlParser::getNode($authorizationResponse, 'response');
        $this->assertEquals('000', $response);

        $location = XmlParser::getNode($authorizationResponse, 'location');
        $this->assertEquals('sandbox', $location);

        $message = XmlParser::getNode($authorizationResponse, 'message');
        $this->assertEquals('Approved', $message);
    }

}
