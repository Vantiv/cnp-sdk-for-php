<?php

namespace cnp\sdk\Test\functional;

use cnp\sdk\CommManager;
use cnp\sdk\Obj2xml;
use cnp\sdk\CnpResponseProcessor;
use cnp\sdk\CnpRequest;
use cnp\sdk\BatchRequest;

class CnpResponseProcessorFunctionalTest extends \PHPUnit_Framework_TestCase
{
    private $direct;
    private $config;
    private $sale;

    public static function setUpBeforeClass()
    {
        CommManager::reset();
    }


    public function setUp()
    {
        //$this->direct = sys_get_temp_dir() . '/test' . CURRENT_SDK_VERSION;
        $this->direct = __DIR__ . '/../../batchRequest';
        if (!file_exists($this->direct)) {
            mkdir($this->direct);
        }
        $this->config = Obj2xml::getConfig(array(
            'batch_requests_path' => $this->direct,
            'cnp_requests_path' => $this->direct
        ));
        $this->sale = array('id' => 'id',
            'orderId' => '1864',
            'amount' => '10010',
            'orderSource' => 'ecommerce',
            'billToAddress' => array(
                'name' => 'John Smith',
                'addressLine1' => '1 Main St.',
                'city' => 'Burlington',
                'state' => 'MA',
                'zip' => '01803-3747',
                'country' => 'US'
            ),
            'card' => array(
                'number' => '4457010000000009',
                'expDate' => '0112',
                'cardValidationNum' => '349',
                'type' => 'VI'
            ),
            'reportGroup' => 'Planets'
        );
    }

    public function test_badResponse()
    {
        $malformed_resp = '<cnpResponse version="8.20" xmlns="http://www.vantivcnp.com/schema" response="1" message="Test test tes test" cnpSessionId="819799340147507212">
            <batchResponse cnpBatchId="819799340147507220" merchantId="07103229">
            <saleResponse reportGroup="Planets">
                <cnpTxnId>819799340147507238</cnpTxnId>
                <orderId>1864</orderId>
                <response>000</response>
                <responseTime>2013-08-08T13:11:01</responseTime>
                <message>Approved</message>
            </saleResponse>
            </batchResponse>
            </cnpResponse>';

        file_put_contents($this->direct . '/pizza.tmp', $malformed_resp);

        $this->setExpectedException('RuntimeException', "Response file $this->direct/pizza.tmp indicates error: Test test tes test");
        $proc = new CnpResponseProcessor ($this->direct . '/pizza.tmp');
    }

}
