<?php

namespace cnp\sdk\Test\functional;


use cnp\sdk\CommManager;
use cnp\sdk\Obj2xml;

require_once realpath(__DIR__) . '/../../../../vendor/autoload.php';

class test_commManager extends \PHPUnit_Framework_TestCase
{
    public $siteUrl1 = 'https://multisite1.com';
    public $siteUrl2 = 'https://multisite2.com';
    public $legacyUrl = 'https://legacy.com';

    public function testInstanceLegacy() {
        $config = Obj2xml::getConfig(array(
            'url' => $this->legacyUrl,
            'multiSite' => 'false',
            'printMultiSiteDebug','true'
        ));

        CommManager::reset();
        $cmg = CommManager::instance($config);
        $this->assertNotNull($cmg);
        $this->assertFalse($cmg->getDoMultiSite());
        $this->assertEquals($this->legacyUrl,$cmg->getLegacyUrl());

        $config2 = Obj2xml::getConfig(array(
            'url' => 'https://nowhere.com',
            'multiSite' => 'false',
            'printMultiSiteDebug','true'
        ));

        $cmg = CommManager::instance($config2);
        $this->assertEquals($this->legacyUrl,$cmg->getLegacyUrl());
    }

    public function testInstanceMultiSite() {
        $config = Obj2xml::getConfig(array(
            'url' => $this->legacyUrl,
            'multiSite' => 'true',
            'printMultiSiteDebug' => 'true',
            'multiSiteUrl1' => $this->siteUrl1,
            'multiSiteUrl2' => $this->siteUrl2,
            'multiSiteErrorThreshold' => '4',
            'maxHoursWithoutSwitch' => '48'
        ));
        CommManager::reset();
        $cmg = CommManager::instance($config);
        $this->assertNotNull($cmg);
        $this->assertTrue($cmg->getDoMultiSite());
        $this->assertEquals($cmg->getMultiSiteThreshold(),4);
        $this->assertEquals(count($cmg->getMultiSiteUrls()),2);
    }
    public function testInstanceMultiSiteNoUrls(){
        $config = Obj2xml::getConfig(array(
            'url' => $this->legacyUrl,
            'multiSite' => 'true',
            'printMultiSiteDebug','true'
        ));

        CommManager::reset();
        $cmg = CommManager::instance($config);
        $this->assertNotNull($cmg);
        $this->assertFalse($cmg->getDoMultiSite());
    }

    public function testInstanceMultiSiteDefaultProps() {
        $config = Obj2xml::getConfig(array(
            'url' => $this->legacyUrl,
            'multiSite' => 'true',
            'printMultiSiteDebug' => 'true',
            'multiSiteUrl1' => $this->siteUrl1,
            'multiSiteUrl2' => $this->siteUrl2,
            'multiSiteErrorThreshold' => '102',
            'maxHoursWithoutSwitch' => '500'
        ));
        CommManager::reset();
        $cmg = CommManager::instance($config);
        $this->assertNotNull($cmg);
        $this->assertTrue($cmg->getDoMultiSite());
        $this->assertEquals($cmg->getMultiSiteThreshold(),5);
        $this->assertEquals($cmg->getMaxHoursWithoutSwitch(), 48);
    }

    public function testInstanceMultiSiteOutOfRange(){
        $config = Obj2xml::getConfig(array(
            'url' => $this->legacyUrl,
            'multiSite' => 'true',
            'printMultiSiteDebug' => 'true',
            'multiSiteUrl1' => $this->siteUrl1,
            'multiSiteUrl2' => $this->siteUrl2
        ));
        CommManager::reset();
        $cmg = CommManager::instance($config);
        $this->assertNotNull($cmg);
        $this->assertTrue($cmg->getDoMultiSite());
    }

    public function testFindUrl_Legacy(){
        $config = Obj2xml::getConfig(array(
            'url' => $this->legacyUrl,
            'multiSite' => 'false',
            'printMultiSiteDebug'=>'false'
        ));
        CommManager::reset();
        $cmg = CommManager::instance($config);
        $this->assertNotNull($cmg);
        $this->assertFalse($cmg->getDoMultiSite());
        $requestTarget = $cmg->findUrl();
        $this->assertEquals($this->legacyUrl,$requestTarget['targetUrl']);
    }

    public function testFindUrl_MultiSite1() {
        $config = Obj2xml::getConfig(array(
            'url' => $this->legacyUrl,
            'multiSite' => 'true',
            'printMultiSiteDebug'=>'true',
            'multiSiteUrl1' => $this->siteUrl1,
            'multiSiteUrl2' => $this->siteUrl2,
            'multiSiteErrorThreshold'=> '4',
            'maxHoursWithoutSwitch'=> '48'
        ));
        CommManager::reset();
        $cmg = CommManager::instance($config);
        $this->assertNotNull($cmg);
        $this->assertTrue($cmg->getDoMultiSite());
        $requestTarget = $cmg->findUrl();
        $urls = $cmg->getMultiSiteUrls();
        $this->assertEquals($urls[$cmg->getCurrentMultiSiteUrlIndex()],$requestTarget['targetUrl']);
        $this->assertTrue($requestTarget['targetUrl']===$this->siteUrl1 || $requestTarget['targetUrl']===$this->siteUrl2);

    }
    public function testFindUrl_MultiSite2()
    {
        $config = Obj2xml::getConfig(array(
            'url' => $this->legacyUrl,
            'multiSite' => 'true',
            'printMultiSiteDebug' => 'false',
            'multiSiteUrl1' => $this->siteUrl1,
            'multiSiteUrl2' => $this->siteUrl2,
            'multiSiteErrorThreshold' => '3',
            'maxHoursWithoutSwitch' => '48'
        ));
        CommManager::reset();
        $cmg = CommManager::instance($config);
        $this->assertNotNull($cmg);
        $this->assertTrue($cmg->getDoMultiSite());
        $this->assertEquals($cmg->getMultiSiteThreshold(), 3);

        $requestTarget = $cmg->findUrl();
        $urls = $cmg->getMultiSiteUrls();
        $this->assertEquals($urls[$cmg->getCurrentMultiSiteUrlIndex()], $requestTarget['targetUrl']);
        $cmg->reportResult($requestTarget, CommManager::$REQUEST_RESULT_RESPONSE_TIMEOUT, 0);
        $requestTarget2 = $cmg->findUrl();
        $this->assertEquals($requestTarget['targetUrl'], $requestTarget2['targetUrl']);
        $cmg->reportResult($requestTarget2, CommManager::$REQUEST_RESULT_RESPONSE_TIMEOUT, 0);
        $requestTarget3 = $cmg->findUrl();
        $this->assertEquals($requestTarget['targetUrl'], $requestTarget3['targetUrl']);
        $cmg->reportResult($requestTarget3, CommManager::$REQUEST_RESULT_RESPONSE_TIMEOUT, 0);
        $this->assertEquals($cmg->getErrorCount(), 3);

        $requestTarget4 = $cmg->findUrl();
        $this->assertFalse($requestTarget4['targetUrl']===$requestTarget['targetUrl']);
    }

    public function testFindUrl_MultiSite3(){
        $config = Obj2xml::getConfig(array(
            'url' => $this->legacyUrl,
            'multiSite' => 'true',
            'printMultiSiteDebug' => 'false',
            'multiSiteUrl1' => $this->siteUrl1,
            'multiSiteUrl2' => $this->siteUrl2,
            'multiSiteErrorThreshold' => '3',
            'maxHoursWithoutSwitch' => '48'
        ));
        CommManager::reset();
        $cmg = CommManager::instance($config);
        $this->assertNotNull($cmg);
        $this->assertTrue($cmg->getDoMultiSite());
        $this->assertEquals($cmg->getMultiSiteThreshold(), 3);

        $requestTarget = $cmg->findUrl();
        $urls = $cmg->getMultiSiteUrls();
        $this->assertEquals($urls[$cmg->getCurrentMultiSiteUrlIndex()], $requestTarget['targetUrl']);
        $cmg->reportResult($requestTarget, CommManager::$REQUEST_RESULT_RESPONSE_TIMEOUT, 0);
        $requestTarget2 = $cmg->findUrl();
        $this->assertEquals($requestTarget['targetUrl'], $requestTarget2['targetUrl']);
        $cmg->reportResult($requestTarget2, CommManager::$REQUEST_RESULT_RESPONSE_TIMEOUT, 0);
        $requestTarget3 = $cmg->findUrl();
        $this->assertEquals($requestTarget['targetUrl'], $requestTarget3['targetUrl']);
        $cmg->reportResult($requestTarget3, CommManager::$REQUEST_RESULT_RESPONSE_TIMEOUT, 0);
        $this->assertEquals($cmg->getErrorCount(), 3);

        $requestTarget4 = $cmg->findUrl();
        $this->assertFalse($requestTarget4['targetUrl'] === $requestTarget['targetUrl']);

        $requestTarget10 = $cmg->findUrl();
        $this->assertEquals($urls[$cmg->getCurrentMultiSiteUrlIndex()], $requestTarget10['targetUrl']);
        $cmg->reportResult($requestTarget10, CommManager::$REQUEST_RESULT_RESPONSE_RECEIVED, 401);
        $requestTarget11 = $cmg->findUrl();
        $this->assertEquals( $requestTarget10['targetUrl'], $requestTarget11['targetUrl']);
        $cmg->reportResult($requestTarget11, CommManager::$REQUEST_RESULT_CONNECTION_FAILED, 0);
        $requestTarget12 = $cmg->findUrl();
        $this->assertEquals( $requestTarget11['targetUrl'], $requestTarget12['targetUrl']);
        $cmg->reportResult($requestTarget12, CommManager::$REQUEST_RESULT_RESPONSE_TIMEOUT, 0);
        $this->assertEquals($cmg->getErrorCount(), 3);

        $requestTarget13 = $cmg->findUrl();
        $this->assertFalse($requestTarget13['targetUrl']===$requestTarget11['targetUrl']);
        $this->assertTrue($requestTarget13['targetUrl']===$requestTarget['targetUrl']);
    }

    public function testFindUrl_MultiSite4(){
        $config = Obj2xml::getConfig(array(
            'url' => $this->legacyUrl,
            'multiSite' => 'true',
            'printMultiSiteDebug' => 'false',
            'multiSiteUrl1' => $this->siteUrl1,
            'multiSiteUrl2' => $this->siteUrl2,
            'multiSiteErrorThreshold' => '3',
            'maxHoursWithoutSwitch' => '0'
        ));

        CommManager::reset();
        $cmg = CommManager::instance($config);
        $this->assertNotNull($cmg);
        $this->assertTrue($cmg->getDoMultiSite());
        $this->assertEquals($cmg->getMultiSiteThreshold(), 3);

        $requestTarget = $cmg->findUrl();
        $urls = $cmg->getMultiSiteUrls();
        $this->assertEquals($urls[$cmg->getCurrentMultiSiteUrlIndex()], $requestTarget['targetUrl']);
        $cmg->reportResult($requestTarget, CommManager::$REQUEST_RESULT_RESPONSE_TIMEOUT, 0);
        $requestTarget2 = $cmg->findUrl();
        $this->assertEquals($requestTarget['targetUrl'], $requestTarget2['targetUrl']);
        $cmg->reportResult($requestTarget2, CommManager::$REQUEST_RESULT_RESPONSE_RECEIVED, 200);
        $this->assertEquals(0, $cmg->getErrorCount());

        $requestTarget3 = $cmg->findUrl();
        $this->assertEquals( $requestTarget['targetUrl'],$requestTarget3['targetUrl']);
        $cmg->reportResult($requestTarget3, CommManager::$REQUEST_RESULT_RESPONSE_RECEIVED, 301);
        $this->assertEquals(0, $cmg->getErrorCount());
    }

    public function testFindUrl_multiSiteMaxHours(){
        $config = Obj2xml::getConfig(array(
            'url' => $this->legacyUrl,
            'multiSite' => 'true',
            'printMultiSiteDebug' => 'false',
            'multiSiteUrl1' => $this->siteUrl1,
            'multiSiteUrl2' => $this->siteUrl2,
            'multiSiteErrorThreshold' => '3',
            'maxHoursWithoutSwitch' => '4'
        ));

        CommManager::reset();
        $cmg = CommManager::instance($config);
        $this->assertNotNull($cmg);
        $this->assertTrue($cmg->getDoMultiSite());
        $this->assertEquals($cmg->getMultiSiteThreshold(), 3);

        $requestTarget = $cmg->findUrl();
        $urls = $cmg->getMultiSiteUrls();
        $this->assertEquals($urls[$cmg->getCurrentMultiSiteUrlIndex()], $requestTarget['targetUrl']);
        $cmg->reportResult($requestTarget, CommManager::$REQUEST_RESULT_RESPONSE_TIMEOUT, 0);
        $requestTarget2 = $cmg->findUrl();
        $this->assertEquals($requestTarget['targetUrl'], $requestTarget2['targetUrl']);
        $cmg->reportResult($requestTarget2, CommManager::$REQUEST_RESULT_RESPONSE_RECEIVED, 200);

        $lastSwitchMilliseconds = $cmg->getLastSiteSwitchTime();
        $cmg->setLastSiteSwitchTime($lastSwitchMilliseconds-3600000); // set lastSiteSwitchTime to 6 hours earlier

        $requestTarget3 = $cmg->findUrl();
        $this->assertFalse( $requestTarget3['targetUrl']===$requestTarget['targetUrl']);
    }
    public function testReportResult_NotMultiSite(){
        $config = Obj2xml::getConfig(array(
            'url' => $this->legacyUrl,
            'multiSite' => 'false',
            'printMultiSiteDebug','true'
        ));
        CommManager::reset();
        $cmg = CommManager::instance($config);
        $this->assertNotNull($cmg);
        $this->assertFalse($cmg->getDoMultiSite());
        $this->assertEquals($this->legacyUrl,$cmg->getLegacyUrl());
        $cmg->reportResult(array('targetUrl'=>'','urlIndex'=>1,'requestTime'=>round(microtime(true) *1000)),1,0);
    }
}
