<?php

namespace cnp\sdk\Test\functional;

use cnp\sdk\BatchRequest;
use cnp\sdk\CnpRequest;
use cnp\sdk\CnpResponseProcessor;
use cnp\sdk\CommManager;
use cnp\sdk\Obj2xml;

require_once realpath(dirname(__FILE__)) . '../../../CnpOnline.php';

class BatchRequestFunctionalTest extends \PHPUnit_Framework_TestCase
{
    private $direct;
    private $config;
    private $preliveStatus;


    public static function setUpBeforeClass()
    {
        CommManager::reset();
    }


    public function setUp()
    {
        $this->direct = sys_get_temp_dir() . '/test' . CURRENT_SDK_VERSION;
        $this->preliveStatus =  $_SERVER['preliveStatus'];
        if (!file_exists($this->direct)) {
            mkdir($this->direct);
        }
        $this->config = Obj2xml::getConfig(array(
            'batch_requests_path' => $this->direct,
            'cnp_requests_path' => $this->direct
        ));
    }


    public function test_simpleAddTransaction()
    {
        if(strtolower($this->preliveStatus) == 'down'){
            $this->markTestSkipped('Prelive is not available');
        }

        $hash_in = array(
            'card' => array(
                'type' => 'VI',
                'number' => '4100000000000000',
                'expDate' => '1213',
                'cardValidationNum' => '1213'
            ),
            'id' => '1211',
            'orderId' => '2111',
            'reportGroup' => 'Planets',
            'orderSource' => 'ecommerce',
            'amount' => '123'
        );
        $batch_request = new BatchRequest ($this->direct);
        $batch_request->addSale($hash_in);

        $this->assertTrue(file_exists($batch_request->batch_file));
        $this->assertEquals(1, $batch_request->total_txns);

        $cts = $batch_request->getCountsAndAmounts();
        $this->assertEquals(1, $cts ['sale'] ['count']);
        $this->assertEquals(123, $cts ['sale'] ['amount']);
    }

    function test_addSale()
    {
        if(strtolower($this->preliveStatus) == 'down'){
            $this->markTestSkipped('Prelive is not available');
        }

        $hash_in = array(
            'card' => array(
                'type' => 'VI',
                'number' => '4100000000000000',
                'expDate' => '1213',
                'cardValidationNum' => '1213'
            ),
            'id' => '1211',
            'orderId' => '2111',
            'reportGroup' => 'Planets',
            'orderSource' => 'ecommerce',
            'amount' => '123'
        );
        $batch_request = new BatchRequest ($this->direct);
        $batch_request->addSale($hash_in);

        $this->assertTrue(file_exists($batch_request->batch_file));
        $this->assertEquals(1, $batch_request->total_txns);

        $cts = $batch_request->getCountsAndAmounts();
        $this->assertEquals(1, $cts ['sale'] ['count']);
        $this->assertEquals(123, $cts ['sale'] ['amount']);
    }

    public function test_addAuth()
    {
        if(strtolower($this->preliveStatus) == 'down'){
            $this->markTestSkipped('Prelive is not available');
        }

        $hash_in = array(
            'card' => array(
                'type' => 'VI',
                'number' => '4100000000000000',
                'expDate' => '1213',
                'cardValidationNum' => '1213'
            ),
            'id' => '1211',
            'cnpTxnId' => '12345678000',
            'orderId' => '2111',
            'reportGroup' => 'Planets',
            'orderSource' => 'ecommerce',
            'amount' => '123'
        );
        $batch_request = new BatchRequest ($this->direct);
        $batch_request->addAuth($hash_in);

        $this->assertTrue(file_exists($batch_request->batch_file));
        $this->assertEquals(1, $batch_request->total_txns);

        $cts = $batch_request->getCountsAndAmounts();
        $this->assertEquals(1, $cts ['auth'] ['count']);
        $this->assertEquals(123, $cts ['auth'] ['amount']);
    }

    public function test_addAuthReversal()
    {
        if(strtolower($this->preliveStatus) == 'down'){
            $this->markTestSkipped('Prelive is not available');
        }

        $hash_in = array(
            'card' => array(
                'type' => 'VI',
                'number' => '4100000000000000',
                'expDate' => '1213',
                'cardValidationNum' => '1213'
            ),
            'id' => '1211',
            'cnpTxnId' => '12345678000',
            'orderId' => '2111',
            'reportGroup' => 'Planets',
            'orderSource' => 'ecommerce',
            'amount' => '123'
        );
        $batch_request = new BatchRequest ($this->direct);
        $batch_request->addAuthReversal($hash_in);

        $this->assertTrue(file_exists($batch_request->batch_file));
        $this->assertEquals(1, $batch_request->total_txns);

        $cts = $batch_request->getCountsAndAmounts();
        $this->assertEquals(1, $cts ['authReversal'] ['count']);
        $this->assertEquals(123, $cts ['authReversal'] ['amount']);
    }

    public function test_addGiftCardAuthReversal()
    {
        if(strtolower($this->preliveStatus) == 'down'){
            $this->markTestSkipped('Prelive is not available');
        }

        $hash_in = array(
            'id' => 'id',
            'cnpTxnId' => '12345678000',
            'captureAmount' => '123',
            'card' => array(
                'type' => 'GC',
                'number' => '4100000000000001',
                'expDate' => '0118',
                'pin' => '1234',
                'cardValidationNum' => '411'
            ),
            'originalRefCode' => '101',
            'originalAmount' => '123',
            'originalTxnTime' => '2017-01-24T09:00:00',
            'originalSystemTraceId' => '33',
            'originalSequenceNumber' => '111111'
        );
        $batch_request = new BatchRequest ($this->direct);
        $batch_request->addGiftCardAuthReversal($hash_in);

        $this->assertTrue(file_exists($batch_request->batch_file));
        $this->assertEquals(1, $batch_request->total_txns);

        $cts = $batch_request->getCountsAndAmounts();
        $this->assertEquals(1, $cts ['giftCardAuthReversal'] ['count']);
        $this->assertEquals(123, $cts ['giftCardAuthReversal'] ['amount']);
    }

    public function test_addCredit()
    {
        if(strtolower($this->preliveStatus) == 'down'){
            $this->markTestSkipped('Prelive is not available');
        }

        $hash_in = array(
            'card' => array(
                'type' => 'VI',
                'number' => '4100000000000000',
                'expDate' => '1213',
                'cardValidationNum' => '1213'
            ),
            'id' => '1211',
            'orderId' => '2111',
            'reportGroup' => 'Planets',
            'orderSource' => 'ecommerce',
            'amount' => '123'
        );
        $batch_request = new BatchRequest ($this->direct);
        $batch_request->addCredit($hash_in);

        $this->assertTrue(file_exists($batch_request->batch_file));
        $this->assertEquals(1, $batch_request->total_txns);

        $cts = $batch_request->getCountsAndAmounts();
        $this->assertEquals(1, $cts ['credit'] ['count']);
        $this->assertEquals(123, $cts ['credit'] ['amount']);
    }

    public function test_addGiftCardCredit()
    {
        if(strtolower($this->preliveStatus) == 'down'){
            $this->markTestSkipped('Prelive is not available');
        }

        $hash_in = array(
            'cnpTxnId' => '12312312',
            'reportGroup' => 'Planets',
            'creditAmount' => '123',
            'id' => '1211',
            'card' => array(
                'type' => 'GC',
                'number' => '4100521234567000',
                'expDate' => '0118',
                'pin' => '1234',
                'cardValidationNum' => '411'
            )
        );
        $batch_request = new BatchRequest ($this->direct);
        $batch_request->addGiftCardCredit($hash_in);

        $this->assertTrue(file_exists($batch_request->batch_file));
        $this->assertEquals(1, $batch_request->total_txns);

        $cts = $batch_request->getCountsAndAmounts();
        $this->assertEquals(1, $cts ['giftCardCredit'] ['count']);
        $this->assertEquals(123, $cts ['giftCardCredit'] ['amount']);
    }

    public function test_addRegisterToken()
    {
        if(strtolower($this->preliveStatus) == 'down'){
            $this->markTestSkipped('Prelive is not available');
        }

        $hash_in = array(
            'card' => array(
                'type' => 'VI',
                'number' => '4100000000000000',
                'expDate' => '1213',
                'cardValidationNum' => '1213'
            ),
            'id' => '1211',
            'orderId' => '2111',
            'reportGroup' => 'Planets',
            'orderSource' => 'ecommerce',
            'amount' => '123'
        );
        $batch_request = new BatchRequest ($this->direct);
        $batch_request->addRegisterToken($hash_in);

        $this->assertTrue(file_exists($batch_request->batch_file));
        $this->assertEquals(1, $batch_request->total_txns);

        $cts = $batch_request->getCountsAndAmounts();
        $this->assertEquals(1, $cts ['tokenRegistration'] ['count']);
    }

    public function test_addForceCapture()
    {
        if(strtolower($this->preliveStatus) == 'down'){
            $this->markTestSkipped('Prelive is not available');
        }

        $hash_in = array(
            'card' => array(
                'type' => 'VI',
                'number' => '4100000000000000',
                'expDate' => '1213',
                'cardValidationNum' => '1213'
            ),
            'id' => '1211',
            'orderId' => '2111',
            'reportGroup' => 'Planets',
            'orderSource' => 'ecommerce',
            'amount' => '123'
        );
        $batch_request = new BatchRequest ($this->direct);
        $batch_request->addForceCapture($hash_in);

        $this->assertTrue(file_exists($batch_request->batch_file));
        $this->assertEquals(1, $batch_request->total_txns);

        $cts = $batch_request->getCountsAndAmounts();
        $this->assertEquals(1, $cts ['forceCapture'] ['count']);
        $this->assertEquals(123, $cts ['forceCapture'] ['amount']);
    }

    public function test_addCapture()
    {
        if(strtolower($this->preliveStatus) == 'down'){
            $this->markTestSkipped('Prelive is not available');
        }

        $hash_in = array(
            'card' => array(
                'type' => 'VI',
                'number' => '4100000000000000',
                'expDate' => '1213',
                'cardValidationNum' => '1213'
            ),
            'id' => '1211',
            'orderId' => '2111',
            'cnpTxnId' => '12345678000',
            'reportGroup' => 'Planets',
            'orderSource' => 'ecommerce',
            'amount' => '123'
        );
        $batch_request = new BatchRequest ($this->direct);
        $batch_request->addCapture($hash_in);

        $this->assertTrue(file_exists($batch_request->batch_file));
        $this->assertEquals(1, $batch_request->total_txns);

        $cts = $batch_request->getCountsAndAmounts();
        $this->assertEquals(1, $cts ['capture'] ['count']);
        $this->assertEquals(123, $cts ['capture'] ['amount']);
    }

    public function test_addGiftCardCapture()
    {
        if(strtolower($this->preliveStatus) == 'down'){
            $this->markTestSkipped('Prelive is not available');
        }

        $hash_in = array(
            'id' => 'id',
            'cnpTxnId' => '12345678000',
            'captureAmount' => '123',
            'card' => array(
                'type' => 'GC',
                'number' => '4100100000000000',
                'expDate' => '0118',
                'pin' => '1234',
                'cardValidationNum' => '411'
            ),
            'originalRefCode' => '101',
            'originalAmount' => '34561',
            'originalTxnTime' => '2017-01-24T09:00:00'
        );
        $batch_request = new BatchRequest ($this->direct);
        $batch_request->addGiftCardCapture($hash_in);

        $this->assertTrue(file_exists($batch_request->batch_file));
        $this->assertEquals(1, $batch_request->total_txns);

        $cts = $batch_request->getCountsAndAmounts();
        $this->assertEquals(1, $cts ['giftCardCapture'] ['count']);
        $this->assertEquals(123, $cts ['giftCardCapture'] ['amount']);
    }

    public function test_addCaptureGivenAuth()
    {
        if(strtolower($this->preliveStatus) == 'down'){
            $this->markTestSkipped('Prelive is not available');
        }

        $hash_in = array(
            'card' => array(
                'type' => 'VI',
                'number' => '4100000000000000',
                'expDate' => '1213',
                'cardValidationNum' => '1213'
            ),
            'id' => '1211',
            'orderId' => '2111',
            'reportGroup' => 'Planets',
            'orderSource' => 'ecommerce',
            'amount' => '123'
        );
        $batch_request = new BatchRequest ($this->direct);
        $batch_request->addCaptureGivenAuth($hash_in);

        $this->assertTrue(file_exists($batch_request->batch_file));
        $this->assertEquals(1, $batch_request->total_txns);

        $cts = $batch_request->getCountsAndAmounts();
        $this->assertEquals(1, $cts ['captureGivenAuth'] ['count']);
        $this->assertEquals(123, $cts ['captureGivenAuth'] ['amount']);
    }

    public function test_addEcheckRedeposit()
    {
        if(strtolower($this->preliveStatus) == 'down'){
            $this->markTestSkipped('Prelive is not available');
        }

        $hash_in = array(
            'card' => array(
                'type' => 'VI',
                'number' => '4100000000000000',
                'expDate' => '1213',
                'cardValidationNum' => '1213'
            ),
            'id' => '1211',
            'orderId' => '2111',
            'cnpTxnId' => '12345678000',
            'reportGroup' => 'Planets',
            'orderSource' => 'ecommerce',
            'amount' => '123'
        );
        $batch_request = new BatchRequest ($this->direct);
        $batch_request->addEcheckRedeposit($hash_in);

        $this->assertTrue(file_exists($batch_request->batch_file));
        $this->assertEquals(1, $batch_request->total_txns);

        $cts = $batch_request->getCountsAndAmounts();
        $this->assertEquals(1, $cts ['echeckRedeposit'] ['count']);
    }

    public function test_addEcheckSale()
    {
        $hash_in = array(
            'card' => array(
                'type' => 'VI',
                'number' => '4100000000000000',
                'expDate' => '1213',
                'cardValidationNum' => '1213'
            ),
            'id' => '1211',
            'orderId' => '2111',
            'reportGroup' => 'Planets',
            'orderSource' => 'ecommerce',
            'amount' => '123'
        );
        $batch_request = new BatchRequest ($this->direct);
        $batch_request->addEcheckSale($hash_in);

        $this->assertTrue(file_exists($batch_request->batch_file));
        $this->assertEquals(1, $batch_request->total_txns);

        $cts = $batch_request->getCountsAndAmounts();
        $this->assertEquals(1, $cts ['echeckSale'] ['count']);
        $this->assertEquals(123, $cts ['echeckSale'] ['amount']);
    }

    public function test_addEcheckPreNoteSale()
    {
        if(strtolower($this->preliveStatus) == 'down'){
            $this->markTestSkipped('Prelive is not available');
        }

        $hash_in = array('id' => 'id',
            'orderId' => '2111',
            'orderSource' => 'ecommerce',
            'billToAddress' => array(
                'addressLine1' => '3'
            ),
            'echeck' => array(
                'accType' => 'Checking',
                'accNum' => '12345657890',
                'routingNum' => '123456789',
                'checkNum' => '123455'
            )
        );
        $batch_request = new BatchRequest ($this->direct);
        $batch_request->addEcheckPreNoteSale($hash_in);

        $this->assertTrue(file_exists($batch_request->batch_file));
        $this->assertEquals(1, $batch_request->total_txns);

        $cts = $batch_request->getCountsAndAmounts();
        $this->assertEquals(1, $cts ['echeckPreNoteSale'] ['count']);
    }

    public function test_addEcheckPreNoteCredit()
    {
        if(strtolower($this->preliveStatus) == 'down'){
            $this->markTestSkipped('Prelive is not available');
        }

        $hash_in = array('id' => 'id',
            'orderId' => '2111',
            'orderSource' => 'ecommerce',
            'billToAddress' => array(
                'addressLine1' => '3'
            ),
            'echeck' => array(
                'accType' => 'Checking',
                'accNum' => '12345657890',
                'routingNum' => '123456789',
                'checkNum' => '123455'
            )
        );
        $batch_request = new BatchRequest ($this->direct);
        $batch_request->addEcheckPreNoteCredit($hash_in);

        $this->assertTrue(file_exists($batch_request->batch_file));
        $this->assertEquals(1, $batch_request->total_txns);

        $cts = $batch_request->getCountsAndAmounts();
        $this->assertEquals(1, $cts ['echeckPreNoteCredit'] ['count']);
    }

    // TODO: check content
    public function test_addSubmerchantCredit()
    {
        if(strtolower($this->preliveStatus) == 'down'){
            $this->markTestSkipped('Prelive is not available');
        }

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
        $batch_request = new BatchRequest ($this->direct);
        $batch_request->addSubmerchantCredit($hash_in);

        $this->assertTrue(file_exists($batch_request->batch_file));
        $this->assertEquals(1, $batch_request->total_txns);

        $cts = $batch_request->getCountsAndAmounts();
        $this->assertEquals(1, $cts ['submerchantCredit'] ['count']);
        $this->assertEquals(13, $cts ['submerchantCredit'] ['amount']);
    }

    public function test_addSubmerchantCreditCtx()
    {
        if(strtolower($this->preliveStatus) == 'down'){
            $this->markTestSkipped('Prelive is not available');
        }

        $hash_in = array('id' => 'id',
            'fundingSubmerchantId' => '2111',
            'submerchantName' => '001',
            'fundsTransferId' => '12345678',
            'amount' => '13',
            'accountInfo' => array(
                'accType' => 'Checking',
                'accNum' => '12345657890',
                'routingNum' => '123456789',
                'checkNum' => '123455',
                'ctxPaymentInformation' => array('payment info 1', 'payment info 2')
            ),
            'customIdentifier' => 'Identifier'
        );
        $batch_request = new BatchRequest ($this->direct);
        $batch_request->addSubmerchantCreditCtx($hash_in);

        $this->assertTrue(file_exists($batch_request->batch_file));
        $this->assertEquals(1, $batch_request->total_txns);

        $cts = $batch_request->getCountsAndAmounts();
        $this->assertEquals(1, $cts ['submerchantCredit'] ['count']);
        $this->assertEquals(13, $cts ['submerchantCredit'] ['amount']);
    }

    public function test_addVendorCredit()
    {
        if(strtolower($this->preliveStatus) == 'down'){
            $this->markTestSkipped('Prelive is not available');
        }

        $hash_in = array('id' => 'id',
            'fundingSubmerchantId' => '2111',
            'vendorName' => '001',
            'fundsTransferId' => '12345678',
            'amount' => '13',
            'accountInfo' => array(
                'accType' => 'Checking',
                'accNum' => '12345657890',
                'routingNum' => '123456789',
                'checkNum' => '123455'
            )

        );
        $batch_request = new BatchRequest ($this->direct);
        $batch_request->addVendorCredit($hash_in);

        $this->assertTrue(file_exists($batch_request->batch_file));
        $this->assertEquals(1, $batch_request->total_txns);

        $cts = $batch_request->getCountsAndAmounts();
        $this->assertEquals(1, $cts ['vendorCredit'] ['count']);
        $this->assertEquals(13, $cts ['vendorCredit'] ['amount']);
    }

    public function test_addCustomerCredit()
    {
        if(strtolower($this->preliveStatus) == 'down'){
            $this->markTestSkipped('Prelive is not available');
        }

        $hash_in = array('id' => 'id',
            'fundingCustomerId' => '2111',
            'customerName' => '001',
            'fundsTransferId' => '12345678',
            'amount' => '13',
            'accountInfo' => array(
                'accType' => 'Checking',
                'accNum' => '12345657890',
                'routingNum' => '123456789',
                'checkNum' => '123455'
            )

        );
        $batch_request = new BatchRequest ($this->direct);
        $batch_request->addCustomerCredit($hash_in);

        $this->assertTrue(file_exists($batch_request->batch_file));
        $this->assertEquals(1, $batch_request->total_txns);

        $cts = $batch_request->getCountsAndAmounts();
        $this->assertEquals(1, $cts ['customerCredit'] ['count']);
        $this->assertEquals(13, $cts ['customerCredit'] ['amount']);
    }

    public function test_addVendorCreditCtx()
    {
        if(strtolower($this->preliveStatus) == 'down'){
            $this->markTestSkipped('Prelive is not available');
        }

        $hash_in = array('id' => 'id',
            'fundingSubmerchantId' => '2111',
            'vendorName' => '001',
            'fundsTransferId' => '12345678',
            'amount' => '13',
            'accountInfo' => array(
                'accType' => 'Checking',
                'accNum' => '12345657890',
                'routingNum' => '123456789',
                'checkNum' => '123455',
                'ctxPaymentInformation' => array('payment info 1', 'payment info 2')
            )

        );
        $batch_request = new BatchRequest ($this->direct);
        $batch_request->addVendorCreditCtx($hash_in);

        $this->assertTrue(file_exists($batch_request->batch_file));
        $this->assertEquals(1, $batch_request->total_txns);

        $cts = $batch_request->getCountsAndAmounts();
        $this->assertEquals(1, $cts ['vendorCredit'] ['count']);
        $this->assertEquals(13, $cts ['vendorCredit'] ['amount']);
    }

    public function test_addPayFacCredit()
    {
        if(strtolower($this->preliveStatus) == 'down'){
            $this->markTestSkipped('Prelive is not available');
        }

        $hash_in = array('id' => 'id',
            'fundingSubmerchantId' => '2111',
            'fundsTransferId' => '12345678',
            'amount' => '13'
        );
        $batch_request = new BatchRequest ($this->direct);
        $batch_request->addPayFacCredit($hash_in);

        $this->assertTrue(file_exists($batch_request->batch_file));
        $this->assertEquals(1, $batch_request->total_txns);

        $cts = $batch_request->getCountsAndAmounts();
        $this->assertEquals(1, $cts ['payFacCredit'] ['count']);
        $this->assertEquals(13, $cts ['payFacCredit'] ['amount']);
    }

    public function test_addPayoutOrgCredit()
    {
        if(strtolower($this->preliveStatus) == 'down'){
            $this->markTestSkipped('Prelive is not available');
        }

        $hash_in = array('id' => 'id',
            'fundingCustomerId' => '2111',
            'fundsTransferId' => '12345678',
            'amount' => '13'
        );
        $batch_request = new BatchRequest ($this->direct);
        $batch_request->addPayoutOrgCredit($hash_in);

        $this->assertTrue(file_exists($batch_request->batch_file));
        $this->assertEquals(1, $batch_request->total_txns);

        $cts = $batch_request->getCountsAndAmounts();
        $this->assertEquals(1, $cts ['payoutOrgCredit'] ['count']);
        $this->assertEquals(13, $cts ['payoutOrgCredit'] ['amount']);
    }

    public function test_addReserveCredit()
    {
        if(strtolower($this->preliveStatus) == 'down'){
            $this->markTestSkipped('Prelive is not available');
        }

        $hash_in = array('id' => 'id',
            'fundingSubmerchantId' => '2111',
            'fundsTransferId' => '12345678',
            'amount' => '13'
        );
        $batch_request = new BatchRequest ($this->direct);
        $batch_request->addReserveCredit($hash_in);

        $this->assertTrue(file_exists($batch_request->batch_file));
        $this->assertEquals(1, $batch_request->total_txns);

        $cts = $batch_request->getCountsAndAmounts();
        $this->assertEquals(1, $cts ['reserveCredit'] ['count']);
        $this->assertEquals(13, $cts ['reserveCredit'] ['amount']);
    }

    public function test_addPhysicalCheckCredit()
    {
        if(strtolower($this->preliveStatus) == 'down'){
            $this->markTestSkipped('Prelive is not available');
        }

        $hash_in = array('id' => 'id',
            'fundingSubmerchantId' => '2111',
            'fundsTransferId' => '12345678',
            'amount' => '13'
        );
        $batch_request = new BatchRequest ($this->direct);
        $batch_request->addPhysicalCheckCredit($hash_in);

        $this->assertTrue(file_exists($batch_request->batch_file));
        $this->assertEquals(1, $batch_request->total_txns);

        $cts = $batch_request->getCountsAndAmounts();
        $this->assertEquals(1, $cts ['physicalCheckCredit'] ['count']);
        $this->assertEquals(13, $cts ['physicalCheckCredit'] ['amount']);
    }

    public function test_addSubmerchantDebit()
    {
        if(strtolower($this->preliveStatus) == 'down'){
            $this->markTestSkipped('Prelive is not available');
        }

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
        $batch_request = new BatchRequest ($this->direct);
        $batch_request->addSubmerchantDebit($hash_in);

        $this->assertTrue(file_exists($batch_request->batch_file));
        $this->assertEquals(1, $batch_request->total_txns);

        $cts = $batch_request->getCountsAndAmounts();
        $this->assertEquals(1, $cts ['submerchantDebit'] ['count']);
        $this->assertEquals(13, $cts ['submerchantDebit'] ['amount']);
    }

    public function test_addSubmerchantDebitCtx()
    {
        if(strtolower($this->preliveStatus) == 'down'){
            $this->markTestSkipped('Prelive is not available');
        }

        $hash_in = array('id' => 'id',
            'fundingSubmerchantId' => '2111',
            'submerchantName' => '001',
            'fundsTransferId' => '12345678',
            'amount' => '13',
            'accountInfo' => array(
                'accType' => 'Checking',
                'accNum' => '12345657890',
                'routingNum' => '123456789',
                'checkNum' => '123455',
                'ctxPaymentInformation' => array('payment info 1', 'payment info 2')

            ),
            'customIdentifier' => 'Identifier'
        );
        $batch_request = new BatchRequest ($this->direct);
        $batch_request->addSubmerchantDebitCtx($hash_in);
        $this->assertTrue(file_exists($batch_request->batch_file));
        $this->assertEquals(1, $batch_request->total_txns);

        $cts = $batch_request->getCountsAndAmounts();
        $this->assertEquals(1, $cts ['submerchantDebit'] ['count']);
        $this->assertEquals(13, $cts ['submerchantDebit'] ['amount']);
    }

    public function test_addVendorDebit()
    {
        if(strtolower($this->preliveStatus) == 'down'){
            $this->markTestSkipped('Prelive is not available');
        }

        $hash_in = array('id' => 'id',
            'fundingSubmerchantId' => '2111',
            'vendorName' => '001',
            'fundsTransferId' => '12345678',
            'amount' => '13',
            'accountInfo' => array(
                'accType' => 'Checking',
                'accNum' => '12345657890',
                'routingNum' => '123456789',
                'checkNum' => '123455'
            )
        );
        $batch_request = new BatchRequest ($this->direct);
        $batch_request->addVendorDebit($hash_in);

        $this->assertTrue(file_exists($batch_request->batch_file));
        $this->assertEquals(1, $batch_request->total_txns);

        $cts = $batch_request->getCountsAndAmounts();
        $this->assertEquals(1, $cts ['vendorDebit'] ['count']);
        $this->assertEquals(13, $cts ['vendorDebit'] ['amount']);
    }

    public function test_addVendorDebitCtx()
    {
        if(strtolower($this->preliveStatus) == 'down'){
            $this->markTestSkipped('Prelive is not available');
        }

        $hash_in = array('id' => 'id',
            'fundingSubmerchantId' => '2111',
            'vendorName' => '001',
            'fundsTransferId' => '12345678',
            'amount' => '13',
            'accountInfo' => array(
                'accType' => 'Checking',
                'accNum' => '12345657890',
                'routingNum' => '123456789',
                'checkNum' => '123455',
                'ctxPaymentInformation' => array('payment info 1', 'payment info 2')
            )
        );
        $batch_request = new BatchRequest ($this->direct);
        $batch_request->addVendorDebitCtx($hash_in);

        $this->assertTrue(file_exists($batch_request->batch_file));
        $this->assertEquals(1, $batch_request->total_txns);

        $cts = $batch_request->getCountsAndAmounts();
        $this->assertEquals(1, $cts ['vendorDebit'] ['count']);
        $this->assertEquals(13, $cts ['vendorDebit'] ['amount']);
    }

    public function test_addCustomerDebit()
    {
        if(strtolower($this->preliveStatus) == 'down'){
            $this->markTestSkipped('Prelive is not available');
        }

        $hash_in = array('id' => 'id',
            'fundingCustomerId' => '2111',
            'customerName' => '001',
            'fundsTransferId' => '12345678',
            'amount' => '13',
            'accountInfo' => array(
                'accType' => 'Checking',
                'accNum' => '12345657890',
                'routingNum' => '123456789',
                'checkNum' => '123455'
            )
        );
        $batch_request = new BatchRequest ($this->direct);
        $batch_request->addCustomerDebit($hash_in);

        $this->assertTrue(file_exists($batch_request->batch_file));
        $this->assertEquals(1, $batch_request->total_txns);

        $cts = $batch_request->getCountsAndAmounts();
        $this->assertEquals(1, $cts ['customerDebit'] ['count']);
        $this->assertEquals(13, $cts ['customerDebit'] ['amount']);
    }

    public function test_addPayFacDebit()
    {
        if(strtolower($this->preliveStatus) == 'down'){
            $this->markTestSkipped('Prelive is not available');
        }

        $hash_in = array('id' => 'id',
            'fundingSubmerchantId' => '2111',
            'fundsTransferId' => '12345678',
            'amount' => '13'
        );
        $batch_request = new BatchRequest ($this->direct);
        $batch_request->addPayFacDebit($hash_in);

        $this->assertTrue(file_exists($batch_request->batch_file));
        $this->assertEquals(1, $batch_request->total_txns);

        $cts = $batch_request->getCountsAndAmounts();
        $this->assertEquals(1, $cts ['payFacDebit'] ['count']);
        $this->assertEquals(13, $cts ['payFacDebit'] ['amount']);
    }

    public function test_addPayoutOrgDebit()
    {
        if(strtolower($this->preliveStatus) == 'down'){
            $this->markTestSkipped('Prelive is not available');
        }

        $hash_in = array('id' => 'id',
            'fundingCustomerId' => '2111',
            'fundsTransferId' => '12345678',
            'amount' => '13'
        );
        $batch_request = new BatchRequest ($this->direct);
        $batch_request->addPayoutOrgDebit($hash_in);

        $this->assertTrue(file_exists($batch_request->batch_file));
        $this->assertEquals(1, $batch_request->total_txns);

        $cts = $batch_request->getCountsAndAmounts();
        $this->assertEquals(1, $cts ['payoutOrgDebit'] ['count']);
        $this->assertEquals(13, $cts ['payoutOrgDebit'] ['amount']);
    }

    public function test_addReserveDebit()
    {
        if(strtolower($this->preliveStatus) == 'down'){
            $this->markTestSkipped('Prelive is not available');
        }

        $hash_in = array('id' => 'id',
            'fundingSubmerchantId' => '2111',
            'fundsTransferId' => '12345678',
            'amount' => '13'
        );
        $batch_request = new BatchRequest ($this->direct);
        $batch_request->addReserveDebit($hash_in);

        $this->assertTrue(file_exists($batch_request->batch_file));
        $this->assertEquals(1, $batch_request->total_txns);

        $cts = $batch_request->getCountsAndAmounts();
        $this->assertEquals(1, $cts ['reserveDebit'] ['count']);
        $this->assertEquals(13, $cts ['reserveDebit'] ['amount']);
    }

    public function test_addPhysicalCheckDebit()
    {
        if(strtolower($this->preliveStatus) == 'down'){
            $this->markTestSkipped('Prelive is not available');
        }

        $hash_in = array('id' => 'id',
            'fundingSubmerchantId' => '2111',
            'fundsTransferId' => '12345678',
            'amount' => '13'
        );
        $batch_request = new BatchRequest ($this->direct);
        $batch_request->addPhysicalCheckDebit($hash_in);

        $this->assertTrue(file_exists($batch_request->batch_file));
        $this->assertEquals(1, $batch_request->total_txns);

        $cts = $batch_request->getCountsAndAmounts();
        $this->assertEquals(1, $cts ['physicalCheckDebit'] ['count']);
        $this->assertEquals(13, $cts ['physicalCheckDebit'] ['amount']);
    }

    public function test_addEcheckCredit()
    {
        if(strtolower($this->preliveStatus) == 'down'){
            $this->markTestSkipped('Prelive is not available');
        }

        $hash_in = array('id' => 'id',
            'card' => array(
                'type' => 'VI',
                'number' => '4100000000000000',
                'expDate' => '1213',
                'cardValidationNum' => '1213'
            ),
            'id' => '1211',
            'orderId' => '2111',
            'reportGroup' => 'Planets',
            'orderSource' => 'ecommerce',
            'amount' => '123'
        );
        $batch_request = new BatchRequest ($this->direct);
        $batch_request->addEcheckCredit($hash_in);

        $this->assertTrue(file_exists($batch_request->batch_file));
        $this->assertEquals(1, $batch_request->total_txns);

        $cts = $batch_request->getCountsAndAmounts();
        $this->assertEquals(1, $cts ['echeckCredit'] ['count']);
        $this->assertEquals(123, $cts ['echeckCredit'] ['amount']);
    }

    public function test_addEcheckVerification()
    {
        if(strtolower($this->preliveStatus) == 'down'){
            $this->markTestSkipped('Prelive is not available');
        }

        $hash_in = array(
            'card' => array(
                'type' => 'VI',
                'number' => '4100000000000000',
                'expDate' => '1213',
                'cardValidationNum' => '1213'
            ),
            'id' => '1211',
            'orderId' => '2111',
            'reportGroup' => 'Planets',
            'orderSource' => 'ecommerce',
            'amount' => '123'
        );
        $batch_request = new BatchRequest ($this->direct);
        $batch_request->addEcheckVerification($hash_in);

        $this->assertTrue(file_exists($batch_request->batch_file));
        $this->assertEquals(1, $batch_request->total_txns);

        $cts = $batch_request->getCountsAndAmounts();
        $this->assertEquals(1, $cts ['echeckVerification'] ['count']);
        $this->assertEquals(123, $cts ['echeckVerification'] ['amount']);
    }

    public function test_addUpdateCardValidationNumOnTokenHash()
    {
        if(strtolower($this->preliveStatus) == 'down'){
            $this->markTestSkipped('Prelive is not available');
        }

        $hash_in = array(
            'card' => array(
                'type' => 'VI',
                'number' => '4100000000000000',
                'expDate' => '1213',
                'cardValidationNum' => '1213'
            ),
            'id' => '1211',
            'orderId' => '2111',
            'cnpToken' => '123456789101112',
            'reportGroup' => 'Planets',
            'orderSource' => 'ecommerce',
            'amount' => '123',
            'cardValidationNum' => '123'
        );
        $batch_request = new BatchRequest ($this->direct);
        $batch_request->addUpdateCardValidationNumOnToken($hash_in);

        $this->assertTrue(file_exists($batch_request->batch_file));
        $this->assertEquals(1, $batch_request->total_txns);

        $cts = $batch_request->getCountsAndAmounts();
        $this->assertEquals(1, $cts ['updateCardValidationNumOnToken'] ['count']);
    }

    public function test_addUpdateSubscriptionHash()
    {
        if(strtolower($this->preliveStatus) == 'down'){
            $this->markTestSkipped('Prelive is not available');
        }

        $hash_in = array(
            'subscriptionId' => '1',
            'planCode' => '2',
            'billToAddress' => array(
                'addressLine1' => '3'
            ),
            'card' => array(
                'type' => 'VI',
                'number' => '4100000000000000',
                'expDate' => '1213',
                'cardValidationNum' => '1213'
            ),
            'billingDate' => '2013-12-17',
            'updateDiscount0' => array(
                'discountCode' => 'qwertyui',
                'name' => 'asdfg',
                'amount' => '123',
                'startDate' => '2018-05-11',
                'endDate' => '2018-06-11'
            ),
            'updateDiscount1' => array(
                'discountCode' => 'dis1',
                'name' => 'asdfg2',
                'amount' => '1234',
                'startDate' => '2018-05-15',
                'endDate' => '2018-06-16'
            )
        );
        $batch_request = new BatchRequest ($this->direct);
        //$request = new CnpRequest ($this->config);
        $batch_request->addUpdateSubscription($hash_in);
        $batch_request->closeRequest();
        //$request->addBatchRequest($batch_request);
        //$request->closeRequest();

        $this->assertTrue(file_exists($batch_request->batch_file));
        $this->assertEquals(1, $batch_request->total_txns);

        $cts = $batch_request->getCountsAndAmounts();
        $this->assertEquals(1, $cts ['updateSubscription'] ['count']);
    }

    public function test_addCancelSubscriptionHash()
    {
        if(strtolower($this->preliveStatus) == 'down'){
            $this->markTestSkipped('Prelive is not available');
        }

        $hash_in = array(
            'subscriptionId' => '1'
        );
        $batch_request = new BatchRequest ($this->direct);
        $batch_request->addCancelSubscription($hash_in);

        $this->assertTrue(file_exists($batch_request->batch_file));
        $this->assertEquals(1, $batch_request->total_txns);

        $cts = $batch_request->getCountsAndAmounts();
        $this->assertEquals(1, $cts ['cancelSubscription'] ['count']);
    }

    public function test_addCreatePlanHash()
    {
        if(strtolower($this->preliveStatus) == 'down'){
            $this->markTestSkipped('Prelive is not available');
        }

        $hash_in = array(
            'planCode' => '1',
            'name' => '2',
            'intervalType' => 'MONTHLY',
            'amount' => '1000'
        );
        $batch_request = new BatchRequest ($this->direct);
        $batch_request->addCreatePlan($hash_in);

        $this->assertTrue(file_exists($batch_request->batch_file));
        $this->assertEquals(1, $batch_request->total_txns);

        $cts = $batch_request->getCountsAndAmounts();
        $this->assertEquals(1, $cts ['createPlan'] ['count']);
    }

    public function test_addUpdatePlanHash()
    {
        if(strtolower($this->preliveStatus) == 'down'){
            $this->markTestSkipped('Prelive is not available');
        }

        $hash_in = array(
            'planCode' => '1',
            'active' => 'false'
        );
        $batch_request = new BatchRequest ($this->direct);
        $batch_request->addUpdatePlan($hash_in);

        $this->assertTrue(file_exists($batch_request->batch_file));
        $this->assertEquals(1, $batch_request->total_txns);

        $cts = $batch_request->getCountsAndAmounts();
        $this->assertEquals(1, $cts ['updatePlan'] ['count']);
    }

    public function test_addActivateHash()
    {
        if(strtolower($this->preliveStatus) == 'down'){
            $this->markTestSkipped('Prelive is not available');
        }

        $hash_in = array('id' => 'id',
            'orderId' => '1',
            'amount' => '2',
            'orderSource' => 'ECOMMERCE',
            'card' => array(
                'type' => 'VI',
                'number' => '4100000000000000',
                'expDate' => '1213',
                'cardValidationNum' => '1213'
            )
        );
        $batch_request = new BatchRequest ($this->direct);
        $batch_request->addActivate($hash_in);

        $this->assertTrue(file_exists($batch_request->batch_file));
        $this->assertEquals(1, $batch_request->total_txns);

        $cts = $batch_request->getCountsAndAmounts();
        $this->assertEquals(1, $cts ['activate'] ['count']);
        $this->assertEquals(2, $cts ['activate'] ['amount']);
    }

    public function test_addDeactivateHash()
    {
        if(strtolower($this->preliveStatus) == 'down'){
            $this->markTestSkipped('Prelive is not available');
        }

        $hash_in = array('id' => 'id',
            'orderId' => '1',
            'orderSource' => 'ECOMMERCE',
            'card' => array(
                'type' => 'VI',
                'number' => '4100000000000000',
                'expDate' => '1213',
                'cardValidationNum' => '1213'
            )
        );
        $batch_request = new BatchRequest ($this->direct);
        $batch_request->addDeactivate($hash_in);

        $this->assertTrue(file_exists($batch_request->batch_file));
        $this->assertEquals(1, $batch_request->total_txns);

        $cts = $batch_request->getCountsAndAmounts();
        $this->assertEquals(1, $cts ['deactivate'] ['count']);
    }

    public function test_addLoadHash()
    {
        if(strtolower($this->preliveStatus) == 'down'){
            $this->markTestSkipped('Prelive is not available');
        }

        $hash_in = array('id' => 'id',
            'orderId' => '1',
            'amount' => '2',
            'orderSource' => 'ECOMMERCE',
            'card' => array(
                'type' => 'VI',
                'number' => '4100000000000000',
                'expDate' => '1213',
                'cardValidationNum' => '1213'
            )
        );
        $batch_request = new BatchRequest ($this->direct);
        $batch_request->addLoad($hash_in);

        $this->assertTrue(file_exists($batch_request->batch_file));
        $this->assertEquals(1, $batch_request->total_txns);

        $cts = $batch_request->getCountsAndAmounts();
        $this->assertEquals(1, $cts ['load'] ['count']);
        $this->assertEquals(2, $cts ['load'] ['amount']);
    }

    public function test_addUnloadHash()
    {
        if(strtolower($this->preliveStatus) == 'down'){
            $this->markTestSkipped('Prelive is not available');
        }

        $hash_in = array('id' => 'id',
            'orderId' => '1',
            'amount' => '2',
            'orderSource' => 'ECOMMERCE',
            'card' => array(
                'type' => 'VI',
                'number' => '4100000000000000',
                'expDate' => '1213',
                'cardValidationNum' => '1213'
            )
        );
        $batch_request = new BatchRequest ($this->direct);
        $batch_request->addUnload($hash_in);

        $this->assertTrue(file_exists($batch_request->batch_file));
        $this->assertEquals(1, $batch_request->total_txns);

        $cts = $batch_request->getCountsAndAmounts();
        $this->assertEquals(1, $cts ['unload'] ['count']);
        $this->assertEquals(2, $cts ['unload'] ['amount']);
    }

    public function test_addBalanceInquiryHash()
    {
        if(strtolower($this->preliveStatus) == 'down'){
            $this->markTestSkipped('Prelive is not available');
        }

        $hash_in = array('id' => 'id',
            'orderId' => '1',
            'orderSource' => 'ECOMMERCE',
            'card' => array(
                'type' => 'VI',
                'number' => '4100000000000000',
                'expDate' => '1213',
                'cardValidationNum' => '1213'
            )
        );
        $batch_request = new BatchRequest ($this->direct);
        $batch_request->addBalanceInquiry($hash_in);

        $this->assertTrue(file_exists($batch_request->batch_file));
        $this->assertEquals(1, $batch_request->total_txns);

        $cts = $batch_request->getCountsAndAmounts();
        $this->assertEquals(1, $cts ['balanceInquiry'] ['count']);
    }

    public function test_mechaBatch()
    {
        if(strtolower($this->preliveStatus) == 'down'){
            $this->markTestSkipped('Prelive is not available');
        }

        $batch = new BatchRequest ($this->direct);
        $hash_in = array('id' => 'id',
            'card' => array(
                'type' => 'VI',
                'number' => '4100000000000001',
                'expDate' => '1213',
                'cardValidationNum' => '1213'
            ),
            'orderId' => '2111',
            'orderSource' => 'ecommerce',
            'id' => '654',
            'amount' => '123'
        );
        $batch->addAuth($hash_in);

        $hash_in = array('id' => 'id',
            'cnpTxnId' => '1234567890',
            'reportGroup' => 'Planets',
            'amount' => '5000'
        );
        $batch->addAuthReversal($hash_in);

        $hash_in = array('id' => 'id',
            'cnpTxnId' => '12312312',
            'amount' => '123'
        );
        $batch->addCapture($hash_in);

        $hash_in = array('id' => 'id',
            'amount' => '123',
            'orderId' => '12344',
            'authInformation' => array(
                'authDate' => '2002-10-09',
                'authCode' => '543216',
                'authAmount' => '12345'
            ),
            'orderSource' => 'ecommerce',
            'card' => array(
                'type' => 'VI',
                'number' => '4100000000000001',
                'expDate' => '1210'
            )
        );
        $batch->addCaptureGivenAuth($hash_in);

        $hash_in = array('id' => 'id',
            'cnpTxnId' => '12312312',
            'reportGroup' => 'Planets',
            'amount' => '123'
        );
        $batch->addCredit($hash_in);

        $hash_in = array('id' => 'id',
            'cnpTxnId' => '123123'
        );
        $batch->addEcheckCredit($hash_in);

        $hash_in = array(
            'cnpTxnId' => '123123', 'id' => 'id',
        );
        $batch->addEcheckRedeposit($hash_in);

        $hash_in = array('id' => 'id',
            'amount' => '123456',
            'verify' => 'true',
            'orderId' => '12345',
            'orderSource' => 'ecommerce',
            'echeck' => array(
                'accType' => 'Checking',
                'accNum' => '12345657890',
                'routingNum' => '123456789',
                'checkNum' => '123455'
            ),
            'billToAddress' => array(
                'name' => 'Bob',
                'city' => 'lowell',
                'state' => 'MA',
                'email' => 'vantiv.com'
            )
        );
        $batch->addEcheckSale($hash_in);

        $hash_in = array('id' => 'id',
            'amount' => '123456',
            'verify' => 'true',
            'orderId' => '12345',
            'orderSource' => 'ecommerce',
            'echeck' => array(
                'accType' => 'Checking',
                'accNum' => '12345657890',
                'routingNum' => '123456789',
                'checkNum' => '123455'
            ),
            'billToAddress' => array(
                'name' => 'Bob',
                'city' => 'lowell',
                'state' => 'MA',
                'email' => 'vantiv.com'
            )
        );
        $batch->addEcheckVerification($hash_in);

        $hash_in = array('id' => 'id',
            'orderId' => '123',
            'cnpTxnId' => '123456',
            'amount' => '106',
            'orderSource' => 'ecommerce',
            'token' => array(
                'cnpToken' => '123456789101112',
                'expDate' => '1210',
                'cardValidationNum' => '555',
                'type' => 'VI'
            )
        );
        $batch->addForceCapture($hash_in);

        $hash_in = array(
            'card' => array(
                'type' => 'VI',
                'number' => '4100000000000001',
                'expDate' => '1213',
                'cardValidationNum' => '1213'
            ),
            'id' => '654',
            'orderId' => '2111',
            'orderSource' => 'ecommerce',
            'amount' => '123'
        );
        $batch->addSale($hash_in);

        $hash_in = array('id' => 'id',
            'orderId' => '1',
            'accountNumber' => '123456789101112'
        );
        $batch->addRegisterToken($hash_in);

        $hash_in = array('id' => 'id',
            'orderId' => '1',
            'cnpToken' => '123456789101112',
            'cardValidationNum' => '123'
        );
        $batch->addUpdateCardValidationNumOnToken($hash_in);

        $hash_in = array('id' => 'id',
            'reportGroup' => 'Planets',
            'orderId' => '1',
            'token' => 'fhsdjkffyriof093909'
        );
        $batch->addTranslateToLowValueTokenRequest($hash_in);

        $this->assertEquals(14, $batch->total_txns);
        $cts = $batch->getCountsAndAmounts();
        $this->assertEquals(1, $cts ['sale'] ['count']);
        $this->assertEquals(1, $cts ['auth'] ['count']);
        $this->assertEquals(1, $cts ['credit'] ['count']);
        $this->assertEquals(1, $cts ['tokenRegistration'] ['count']);
        $this->assertEquals(1, $cts ['capture'] ['count']);
        $this->assertEquals(1, $cts ['forceCapture'] ['count']);
        $this->assertEquals(1, $cts ['echeckRedeposit'] ['count']);
        $this->assertEquals(1, $cts ['echeckSale'] ['count']);
        $this->assertEquals(1, $cts ['echeckCredit'] ['count']);
        $this->assertEquals(1, $cts ['echeckVerification'] ['count']);
        $this->assertEquals(1, $cts ['updateCardValidationNumOnToken'] ['count']);
        $this->assertEquals(1, $cts ['translateToLowValueTokenRequest'] ['count']);

        $this->assertEquals(123, $cts ['sale'] ['amount']);
        $this->assertEquals(123, $cts ['auth'] ['amount']);
        $this->assertEquals(123, $cts ['credit'] ['amount']);
        $this->assertEquals(123, $cts ['capture'] ['amount']);
        $this->assertEquals(106, $cts ['forceCapture'] ['amount']);
        $this->assertEquals(123456, $cts ['echeckSale'] ['amount']);
        $this->assertEquals(123456, $cts ['echeckVerification'] ['amount']);
    }

    public function test_addAccountUpdate()
    {
        if(strtolower($this->preliveStatus) == 'down'){
            $this->markTestSkipped('Prelive is not available');
        }

        $hash_in = array(
            'card' => array(
                'type' => 'VI',
                'number' => '4100000000000000',
                'expDate' => '1213',
                'cardValidationNum' => '1213'
            ),
            'id' => '1211',
            'orderId' => '2111',
            'reportGroup' => 'Planets',
            'orderSource' => 'ecommerce',
            'amount' => '123'
        );
        $batch_request = new BatchRequest ($this->direct);
        $batch_request->addAccountUpdate($hash_in);

        $this->assertTrue(file_exists($batch_request->batch_file));
        $cts = $batch_request->getCountsAndAmounts();
        $this->assertEquals(1, $cts ['accountUpdate'] ['count']);
    }

    public function test_addAccountUpdate_negative_with_transaction_before_accountUpdate()
    {
        if(strtolower($this->preliveStatus) == 'down'){
            $this->markTestSkipped('Prelive is not available');
        }

        try {
            $hash_in = array(
                'card' => array(
                    'type' => 'VI',
                    'number' => '4100000000000000',
                    'expDate' => '1213',
                    'cardValidationNum' => '1213'
                ),
                'id' => '1211',
                'orderId' => '2111',
                'reportGroup' => 'Planets',
                'orderSource' => 'ecommerce',
                'amount' => '123'
            );
            $batch_request = new BatchRequest ($this->direct);
            $batch_request->addSale($hash_in);
            $batch_request->addAccountUpdate($hash_in);
        } catch (\RuntimeException $expected) {
            $this->assertEquals($expected->getMessage(), "The transaction could not be added to the batch. The transaction type accountUpdate cannot be mixed with non-Account Updates.");

            return;
        }

        $this->fail("test_addAccountUpdate_negative_with_transaction_before_accountUpdate is expected to fail");
    }

    public function test_addAccountUpdate_negative_with_transaction_after_accountUpdate()
    {
        if(strtolower($this->preliveStatus) == 'down'){
            $this->markTestSkipped('Prelive is not available');
        }

        try {
            $hash_in = array(
                'card' => array(
                    'type' => 'VI',
                    'number' => '4100000000000000',
                    'expDate' => '1213',
                    'cardValidationNum' => '1213'
                ),
                'id' => '1211',
                'orderId' => '2111',
                'reportGroup' => 'Planets',
                'orderSource' => 'ecommerce',
                'amount' => '123'
            );
            $batch_request = new BatchRequest ($this->direct);
            $batch_request->addAccountUpdate($hash_in);
            $batch_request->addSale($hash_in);
        } catch (\RuntimeException $expected) {
            $this->assertEquals($expected->getMessage(), "The transaction could not be added to the batch. The transaction type sale cannot be mixed with AccountUpdates.");

            return;
        }

        $this->fail("test_addAccountUpdate_negative_with_transaction_after_accountUpdate is expected to fail");
    }

    public function test_isFull()
    {
        if(strtolower($this->preliveStatus) == 'down'){
            $this->markTestSkipped('Prelive is not available');
        }

        $hash_in = array(
            'card' => array(
                'type' => 'VI',
                'number' => '4100000000000000',
                'expDate' => '1213',
                'cardValidationNum' => '1213'
            ),
            'id' => '1211',
            'orderId' => '2111',
            'reportGroup' => 'Planets',
            'orderSource' => 'ecommerce',
            'amount' => '123'
        );

        $this->setExpectedException('RuntimeException', 'The transaction could not be added to the batch. It is full.');
        $batch_request = new BatchRequest ($this->direct);
        $batch_request->total_txns = MAX_TXNS_PER_BATCH;
        $batch_request->addSale($hash_in);
    }

    public function test_addTooManyTransactions()
    {
        if(strtolower($this->preliveStatus) == 'down'){
            $this->markTestSkipped('Prelive is not available');
        }

        $hash_in = array(
            'card' => array(
                'type' => 'VI',
                'number' => '4100000000000000',
                'expDate' => '1213',
                'cardValidationNum' => '1213'
            ),
            'id' => '1211',
            'orderId' => '2111',
            'reportGroup' => 'Planets',
            'orderSource' => 'ecommerce',
            'amount' => '123'
        );
        $batch_request = new BatchRequest ($this->direct);
        $batch_request->total_txns = MAX_TXNS_PER_BATCH;

        $this->setExpectedException('RuntimeException', 'The transaction could not be added to the batch. It is full.');

        $batch_request->addSale($hash_in);
    }

    public function test_addToClosedBatch()
    {
        if(strtolower($this->preliveStatus) == 'down'){
            $this->markTestSkipped('Prelive is not available');
        }

        $hash_in = array(
            'card' => array(
                'type' => 'VI',
                'number' => '4100000000000000',
                'expDate' => '1213',
                'cardValidationNum' => '1213'
            ),
            'id' => '1211',
            'orderId' => '2111',
            'reportGroup' => 'Planets',
            'orderSource' => 'ecommerce',
            'amount' => '123'
        );
        $batch_request = new BatchRequest ($this->direct);
        $batch_request->closed = TRUE;
        $this->setExpectedException('RuntimeException', 'Could not add the transaction. This batchRequest is closed.');

        $batch_request->addSale($hash_in);
    }

    public function test_closeRequest()
    {
        if(strtolower($this->preliveStatus) == 'down'){
            $this->markTestSkipped('Prelive is not available');
        }

        $hash_in = array(
            'card' => array(
                'type' => 'VI',
                'number' => '4100000000000000',
                'expDate' => '1213',
                'cardValidationNum' => '1213'
            ),
            'id' => '1211',
            'orderId' => '2111',
            'reportGroup' => 'Planets',
            'orderSource' => 'ecommerce',
            'amount' => '123'
        );
        $batch_request = new BatchRequest ($this->direct);
        $batch_request->addSale($hash_in);
        $batch_request->closeRequest();

        $data = file_get_contents($batch_request->batch_file);
        $this->assertTrue(!(!strpos($data, 'numSales="1"')));
        $this->assertTrue($batch_request->closed);
    }

    public function test_closeRequest_badFile()
    {
        if(strtolower($this->preliveStatus) == 'down'){
            $this->markTestSkipped('Prelive is not available');
        }

        $batch_request = new BatchRequest ($this->direct);
        $batch_request->transaction_file = "/usr/NOPERMS";

        $this->setExpectedException('RuntimeException', 'Could not open transactions file at /usr/NOPERMS. Please check your privilege.');
        $batch_request->closeRequest();
    }

    public function test_getCountsAndAmounts()
    {
        if(strtolower($this->preliveStatus) == 'down'){
            $this->markTestSkipped('Prelive is not available');
        }

        $hash_in = array(
            'card' => array(
                'type' => 'VI',
                'number' => '4100000000000000',
                'expDate' => '1213',
                'cardValidationNum' => '1213'
            ),
            'id' => '1211',
            'orderId' => '2111',
            'reportGroup' => 'Planets',
            'orderSource' => 'ecommerce',
            'amount' => '123'
        );
        $batch_request = new BatchRequest ($this->direct);
        $batch_request->addSale($hash_in);

        $cts = $batch_request->getCountsAndAmounts();
        $this->assertNotNull($cts);
    }

    public function test_mechaBatchSFTP()
    {
        if(strtolower($this->preliveStatus) == 'down'){
            $this->markTestSkipped('Prelive is not available');
        }

        $request = new CnpRequest();

        $batch = new BatchRequest();
        $hash_in = array(
            'card'=>array('type'=>'VI',
                'number'=>'4100000000000001',
                'expDate'=>'1213',
                'cardValidationNum' => '1213'),
            'orderId'=> '2111',
            'orderSource'=>'ecommerce',
            'id'=>'654',
            'amount'=>'123');
        $batch->addAuth($hash_in);

        $hash_in = array(
            'card'=>array('type'=>'VI',
                'number'=>'4100000000000001',
                'expDate'=>'1213',
                'cardValidationNum' => '1213'),
            'id'=>'654',
            'orderId'=> '2111',
            'orderSource'=>'ecommerce',
            'amount'=>'123');
        $batch->addSale($hash_in);
        $request->addBatchRequest($batch);

        $resp = new CnpResponseProcessor($request->sendToCnp());

        $message = $resp->getXmlReader()->getAttribute("message");
        $response = $resp->getXmlReader()->getAttribute("response");
        $this->assertEquals("Valid Format", $message);
        $this->assertEquals(0, $response);
    }

//    public function test_fastAccessFundingSFTP()
//    {
//        $request = new CnpRequest();
//
//        $batch = new BatchRequest();
//
//        $hash_in = array(
//            'card'=>array('type'=>'VI',
//                'number'=>'4100000000000001',
//                'expDate'=>'1213',
//                'cardValidationNum' => '1213'),
//            'id'=>'654',
//            'customerId'=> '2111',
//            'reportGroup' => 'Planets',
//            'fundingSubmerchantId'=>'2111',
//            'submerchantName'=>'submerchant',
//            'fundsTransferId'=>'1234567891111111',
//            'amount'=>'123');
//        $batch->addFastAccessFunding($hash_in);
//        $request->addBatchRequest($batch);
//
//        $resp = new CnpResponseProcessor($request->sendToCnp());
//
//        $message = $resp->getXmlReader()->getAttribute("message");
//        $response = $resp->getXmlReader()->getAttribute("response");
//        $this->assertEquals("Valid Format", $message);
//        $this->assertEquals(0, $response);
//    }

    public function test_sendToCnpStream()
    {
        if(strtolower($this->preliveStatus) == 'down'){
            $this->markTestSkipped('Prelive is not available');
        }

        $sale_info = array(
            'id' => '1',
            'orderId' => '1',
            'amount' => '10010',
            'orderSource'=>'ecommerce',
            'billToAddress'=>array(
                'name' => 'John Smith',
                'addressLine1' => '1 Main St.',
                'city' => 'Burlington',
                'state' => 'MA',
                'zip' => '01803-3747',
                'country' => 'US'),
            'card'=>array(
                'number' =>'5112010000000003',
                'expDate' => '0112',
                'cardValidationNum' => '349',
                'type' => 'MC')
        );

        $cnp_request = new CnpRequest();
        $batch_request = new BatchRequest();

        # add a sale to the batch
        $batch_request->addSale($sale_info);
        # close the batch, indicating that we intend to add no more sales
        $batch_request->closeRequest();
        # add the batch to the cnp request
        $cnp_request->addBatchRequest($batch_request);
        # close the cnp request, indicating that we intend to add no more batches
        $cnp_request->closeRequest();
        # send the batch to cnp via SFTP
        $response_file = $cnp_request->sendToCnpStream();
        # process the response file
        $resp = new CnpResponseProcessor($response_file);

        $message = $resp->getXmlReader()->getAttribute("message");
        $response = $resp->getXmlReader()->getAttribute("response");
        $this->assertEquals("Valid Format", $message);
        $this->assertEquals(0, $response);
    }

    public function tearDown()
    {
        $files = glob($this->direct . '/*'); // get all file names
        foreach ($files as $file) { // iterate files
            if (is_file($file))
                unlink($file); // delete file
        }
        rmdir($this->direct);
    }
}
