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

class FundingInstructionOnlineFunctionalTest extends \PHPUnit_Framework_TestCase
{
    public static function setUpBeforeClass()
    {
        CommManager::reset();
    }

    public function test_submerchant_credit()
    {
        $hash_in = array('id' => 'id',
            'fundingSubmerchantId' => '2111',
            'submerchantName' => '001',
            'fundsTransferId' => '12345678',
            'amount' => '13',
            'accountInfo' => array(
                'accType' => 'Checking',
                'accNum' => '12345657890',
                'routingNum' => '123456789',
                'checkNum' => '123455'
            ),
            'customIdentifier' => 'Identifier'
        );
        $initialize = new CnpOnlineRequest();
        $subMerchantCreditResponse = $initialize->subMerchantCredit($hash_in);
        $response = XmlParser::getNode($subMerchantCreditResponse, 'response');
        $this->assertEquals('000', $response);
    }

    public function test_submerchant_credit_negative()
    {
        $hash_in = array('id' => 'id',
            'fundingSubmerchantId' => '2111',
            'submerchantName' => '001',
            'fundsTransferId' => '12345678',
            'amount' => '940',
            'accountInfo' => array(
                'accType' => 'Checking',
                'accNum' => '12345657890',
                'routingNum' => '123456789',
                'checkNum' => '123455'
            ),
            'customIdentifier' => 'Identifier'
        );
        $initialize = new CnpOnlineRequest();
        $subMerchantCreditResponse = $initialize->subMerchantCredit($hash_in);
        $response = XmlParser::getNode($subMerchantCreditResponse, 'response');
        $this->assertEquals('940', $response);
    }

    public function test_submerchant_debit()
    {
        $hash_in = array('id' => 'id',
            'fundingSubmerchantId' => '2111',
            'submerchantName' => '001',
            'fundsTransferId' => '12345678',
            'amount' => '13',
            'accountInfo' => array(
                'accType' => 'Checking',
                'accNum' => '12345657890',
                'routingNum' => '123456789',
                'checkNum' => '123455'
            ),
            'customIdentifier' => 'Identifier'
        );
        $initialize = new CnpOnlineRequest();
        $subMerchantDebitResponse = $initialize->subMerchantDebit($hash_in);
        $response = XmlParser::getNode($subMerchantDebitResponse, 'response');
        $this->assertEquals('000', $response);
    }

    public function test_submerchant_debit_negative()
    {
        $hash_in = array('id' => 'id',
            'fundingSubmerchantId' => '2111',
            'submerchantName' => '001',
            'fundsTransferId' => '12345678',
            'amount' => '941',
            'accountInfo' => array(
                'accType' => 'Checking',
                'accNum' => '12345657890',
                'routingNum' => '123456789',
                'checkNum' => '123455'
            ),
            'customIdentifier' => 'Identifier'
        );
        $initialize = new CnpOnlineRequest();
        $subMerchantDebitResponse = $initialize->subMerchantDebit($hash_in);
        $response = XmlParser::getNode($subMerchantDebitResponse, 'response');
        $this->assertEquals('941', $response);
    }

    public function test_payfac_debit()
    {
        $hash_in = array('id' => 'id',
            'fundingSubmerchantId' => '2111',
            'fundsTransferId' => '12345678',
            'amount' => '13',
        );
        $initialize = new CnpOnlineRequest();
        $subMerchantDebitResponse = $initialize->payFacDebit($hash_in);
        $response = XmlParser::getNode($subMerchantDebitResponse, 'response');
        $this->assertEquals('000', $response);
    }

    public function test_payfac_debit_invalid_parent()
    {
        $hash_in = array('id' => 'id',
            'fundingSubmerchantId' => '2111',
            'fundsTransferId' => '12345678',
            'amount' => '360',
        );
        $initialize = new CnpOnlineRequest();
        $subMerchantDebitResponse = $initialize->payFacDebit($hash_in);
        $response = XmlParser::getNode($subMerchantDebitResponse, 'response');
        $this->assertEquals('360', $response);
    }

    public function test_payfac_credit()
    {
        $hash_in = array('id' => 'id',
            'fundingSubmerchantId' => '2111',
            'fundsTransferId' => '12345678',
            'amount' => '13',
        );
        $initialize = new CnpOnlineRequest();
        $subMerchantCreditResponse = $initialize->payFacCredit($hash_in);
        $response = XmlParser::getNode($subMerchantCreditResponse, 'response');
        $this->assertEquals('000', $response);
    }

    public function test_reserve_credit()
    {
        $hash_in = array('id' => 'id',
            'fundingSubmerchantId' => '2111',
            'fundsTransferId' => '12345678',
            'amount' => '13',
        );
        $initialize = new CnpOnlineRequest();
        $reserveCreditResponse = $initialize->reserveCredit($hash_in);
        $response = XmlParser::getNode($reserveCreditResponse, 'response');
        $this->assertEquals('000', $response);
    }

    public function test_reserve_debit()
    {
        $hash_in = array('id' => 'id',
            'fundingSubmerchantId' => '2111',
            'fundsTransferId' => '12345678',
            'amount' => '13',
        );
        $initialize = new CnpOnlineRequest();
        $reserveDebitResponse = $initialize->reserveDebit($hash_in);
        $response = XmlParser::getNode($reserveDebitResponse, 'response');
        $this->assertEquals('000', $response);
    }

    public function test_physical_check_debit()
    {
        $hash_in = array('id' => 'id',
            'fundingSubmerchantId' => '2111',
            'fundsTransferId' => '12345678',
            'amount' => '13',
        );
        $initialize = new CnpOnlineRequest();
        $physicalCheckDebitResponse = $initialize->physicalCheckDebit($hash_in);
        $response = XmlParser::getNode($physicalCheckDebitResponse, 'response');
        $this->assertEquals('000', $response);
    }

    public function test_physical_check_credit()
    {
        $hash_in = array('id' => 'id',
            'fundingSubmerchantId' => '2111',
            'fundsTransferId' => '12345678',
            'amount' => '13',
        );
        $initialize = new CnpOnlineRequest();
        $physicalCheckCreditResponse = $initialize->physicalCheckCredit($hash_in);
        $response = XmlParser::getNode($physicalCheckCreditResponse, 'response');
        $this->assertEquals('000', $response);
    }

    public function test_vendor_debit()
    {
        $hash_in = array('id' => 'id',
            'fundingSubmerchantId' => '2111',
            'vendorName' => 'Super Secret Tech Inc.',
            'fundsTransferId' => '12345678',
            'amount' => '13',
            'accountInfo' => array(
                'accType' => 'Checking',
                'accNum' => '12345657890',
                'routingNum' => '123456789',
                'checkNum' => '123455'
            ),
        );
        $initialize = new CnpOnlineRequest();
        $vendorDebitResponse = $initialize->vendorDebit($hash_in);
        $response = XmlParser::getNode($vendorDebitResponse, 'response');
        $this->assertEquals('000', $response);
    }

    public function test_vendor_credit()
    {
        $hash_in = array('id' => 'id',
            'fundingSubmerchantId' => '2111',
            'vendorName' => 'Super Secret Tech Inc.',
            'fundsTransferId' => '12345678',
            'amount' => '1000',
            'accountInfo' => array(
                'accType' => 'Checking',
                'accNum' => '12345657890',
                'routingNum' => '123456789',
                'checkNum' => '123455'
            ),
        );
        $initialize = new CnpOnlineRequest();
        $vendorCreditResponse = $initialize->vendorCredit($hash_in);
        $response = XmlParser::getNode($vendorCreditResponse, 'response');
        $this->assertEquals('000', $response);
    }

    public function test_funding_instruction_void()
    {
        $hash_in = array('id' => 'id',
            'cnpTxnId' => '966284951598164000',
        );
        $initialize = new CnpOnlineRequest();
        $fundingInstructionVoid = $initialize->fundingInstructionVoid($hash_in);
        $response = XmlParser::getNode($fundingInstructionVoid, 'response');
        $this->assertEquals('000', $response);
    }

    public function test_funding_instruction_void_already_settled()
    {
        $hash_in = array('id' => 'id',
            'cnpTxnId' => '966284951598164362',
        );
        $initialize = new CnpOnlineRequest();
        $fundingInstructionVoid = $initialize->fundingInstructionVoid($hash_in);
        $response = XmlParser::getNode($fundingInstructionVoid, 'response');
        $this->assertEquals('362', $response);
    }

    public function test_fastAccessFunding()
    {
        $hash_in = array('id' => 'id',
            'fundingSubmerchantId' => '2111',
            'submerchantName' => '001',
            'fundsTransferId' => '1234567891111111',
            'amount' => '13',
            'card' => array(
                'type' => 'VI',
                'number' => '4100000000000000',
                'expDate' => '1210'
            )
        );
        $initialize = new CnpOnlineRequest();
        $fastAccessFundingResponse = $initialize->fastAccessFunding($hash_in);
        $response = XmlParser::getNode($fastAccessFundingResponse, 'response');
        $this->assertEquals('000', $response);
    }
}
