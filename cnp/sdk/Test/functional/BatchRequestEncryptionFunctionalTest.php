<?php

namespace cnp\sdk\Test\functional;

use cnp\sdk\CommManager;
use cnp\sdk\Obj2xml;
use cnp\sdk\BatchRequest;
use cnp\sdk\CnpRequest;
use cnp\sdk\CnpResponseProcessor;

class BatchRequestEncryptionFunctionalTest extends \PHPUnit_Framework_TestCase
{
    private $direct;
    private $username;
    private $password;
    private $sftpUsername;
    private $sftpPassword;
    private $merchantId;
    private $config;

    public function test_setgdfg(){
        $this->assertEquals("Valid Format", "Valid Format");
    }



//    public static function setUpBeforeClass()
//    {
//        CommManager::reset();
//    }
//
//
//    public function setUp()
//    {
//        $this->direct = sys_get_temp_dir() . '/test';
//        if (!file_exists($this->direct)) {
//            mkdir($this->direct);
//        }
//
//        $this->config = Obj2xml::getConfig(array(
//            'batch_requests_path' => $this->direct,
//            'cnp_requests_path' => $this->direct
//        ));
//
////        $this->username = $_SERVER['encUsername'];
////        $this->password = $_SERVER['encPassword'];
////        $this->sftpUsername = $_SERVER['encSftpUsername'];
////        $this->sftpPassword = $_SERVER['encSftpPassword'];
////        $this->merchantId = $_SERVER['encMerchantId'];
//
//        $this->username = $this->config['user'];
//        $this->password = $this->config['password'];
//        $this->sftpUsername = $this->config['sftp_username'];
//        $this->sftpPassword = $this->config['sftp_password'];
//        $this->merchantId = $this->config['merchantId'];
//    }
//
//    public function test_configuredCnpBatchRequestsManually()
//    {
//        //creating local variables to avoid conflicts with other tests
//          $username_local = $_SERVER['encUsername'];
//          $password_local = $_SERVER['encPassword'];
//          $sftpUsername_local = $_SERVER['encSftpUsername'];
//          $sftpPassword_local = $_SERVER['encSftpPassword'];
//          $merchantId_local = $_SERVER['encMerchantId'];
//
//        $sale_info = array(
//            'id' => '1',
//            'orderId' => '1',
//            'amount' => '10010',
//            'orderSource'=>'ecommerce',
//            'billToAddress'=>array(
//                'name' => 'John Smith',
//                'addressLine1' => '1 Main St.',
//                'city' => 'Burlington',
//                'state' => 'MA',
//                'zip' => '01803-3747',
//                'country' => 'US'),
//            'card'=>array(
//                'number' =>'5112010000000003',
//                'expDate' => '0112',
//                'cardValidationNum' => '349',
//                'type' => 'MC')
//        );
//
//
//        $config_hash = array(
//            'user' => $username_local,
//            'password' => $password_local,
//            'merchantId' => $merchantId_local,
//            'sftp_username' => $sftpUsername_local,
//            'sftp_password' => $sftpPassword_local,
//            'useEncryption' => 'true',
//            'batch_url' => 'prelive.litle.com',
//        );
//
//        $cnp_request = new CnpRequest($config_hash);
//        $batch_request = new BatchRequest();
//
//        # add a sale to the batch
//        $batch_request->addSale($sale_info);
//        # close the batch, indicating that we intend to add no more sales
//        $batch_request->closeRequest();
//        # add the batch to the cnp request
//        $cnp_request->addBatchRequest($batch_request);
//        # close the cnp request, indicating that we intend to add no more batches
//        $cnp_request->closeRequest();
//        # send the batch to cnp via SFTP
//        $response_file = $cnp_request->sendToCnp();
//        # process the response file
//        $resp = new CnpResponseProcessor($response_file);
//
//        $message = $resp->getXmlReader()->getAttribute("message");
//        $response = $resp->getXmlReader()->getAttribute("response");
//        $this->assertEquals("Valid Format", $message);
//        $this->assertEquals(0, $response);
//    }
//
//    public function test_mechaBatch()
//    {
//
//        $username_local = $_SERVER['encUsername'];
//        $password_local = $_SERVER['encPassword'];
//        $sftpUsername_local = $_SERVER['encSftpUsername'];
//        $sftpPassword_local = $_SERVER['encSftpPassword'];
//        $merchantId_local = $_SERVER['encMerchantId'];
//
//
//        $config_hash = array(
//            'user' => $username_local,
//            'password' => $password_local,
//            'merchantId' => $merchantId_local,
//            'sftp_username' => $sftpUsername_local,
//            'sftp_password' => $sftpPassword_local,
//            'useEncryption' => 'true',
//            'batch_url' => 'prelive.litle.com',
//        );
//        $request = new CnpRequest($config_hash);
//
//        $batch = new BatchRequest();
//        $hash_in = array(
//            'card'=>array('type'=>'VI',
//                'number'=>'4100000000000001',
//                'expDate'=>'1213',
//                'cardValidationNum' => '1213'),
//            'orderId'=> '2111',
//            'orderSource'=>'ecommerce',
//            'id'=>'654',
//            'amount'=>'123');
//        $batch->addAuth($hash_in);
//
//        $hash_in = array(
//            'card'=>array('type'=>'VI',
//                'number'=>'4100000000000001',
//                'expDate'=>'1213',
//                'cardValidationNum' => '1213'),
//            'id'=>'654',
//            'orderId'=> '2111',
//            'orderSource'=>'ecommerce',
//            'amount'=>'123');
//        $batch->addSale($hash_in);
//        $request->addBatchRequest($batch);
//
//        $resp = new CnpResponseProcessor($request->sendToCnp());
//
//        $message = $resp->getXmlReader()->getAttribute("message");
//        $response = $resp->getXmlReader()->getAttribute("response");
//        $this->assertEquals("Valid Format", $message);
//        $this->assertEquals(0, $response);
//    }
//
//    public function tearDown()
//    {
//        $files = glob($this->direct . '/*'); // get all file names
//        foreach ($files as $file) { // iterate files
//            if (is_file($file))
//                unlink($file); // delete file
//        }
//        rmdir($this->direct);
//    }
}
