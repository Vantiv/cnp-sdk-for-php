<?php

namespace cnp\sdk\Test\functional;


use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\Filesystem\Exception\IOException;

class TestCommManagerMultiThreaded
{
    public $siteUrl1 = 'https://multisite1.com';
    public $siteUrl2 = 'https://multisite2.com';
    public $legacyUrl = 'https://legacy.com';

    private $testPool = array();
    private $threadCount = 100;
    private $cycleCount = 1000;

    public function testMultiThreaded()
    {

        $config = Obj2xml::getConfig(array(
            'url' => $this->legacyUrl,
            'multiSite' => 'true',
            'printMultiSiteDebug' => 'false',
            'multiSiteUrl1' => $this->siteUrl1,
            'multiSiteUrl2' => $this->siteUrl2,
            'multiSiteErrorThreshold' => '3',
            'maxHoursWithoutSwitch' => '4'
        ));

        CommManager::instance($config);

        for ($x = 0; $x < $this->threadCount; $x++) {
            $t = new PerformanceTestThread(1000 + $x, $config);
            array_push($testPool, $t);
        }
        $this->PerformTest();
    }

    public function PerformTest()
    {
        foreach ($this->testPool as &$t) {
            $t->start();
        }

        $allDone = false;
        while (!$allDone) {
            $doneCount = 0;
            foreach ($this->testPool as &$t) {
                if ($t->is_alive() == false) {
                    $doneCount++;
                }
            }
            if ($doneCount == count($this->testPool)) {
                $allDone = true;
            } else {
                sleep(1);
            }
        }
        print("All test threads have completed");
    }

}

namespace TestCommManagerMultiThreaded;

use cnp\sdk\CommManager;
use Thread;

class PerformanceTestThread extends Thread {
    protected $threadId;
    protected $requestCount = 0;
    protected $successCount = 0;
    protected $failedCount = 0;
    protected $cycleCount = 0;

    protected $config = null;

    function __construct($idNumber, $properties,$cycleCount){
        $this->threadId = $idNumber;
        $this->config = $properties;
        $this->cycleCount = $cycleCount;
    }

    function run(){
        $startTime = round(microtime(true)*1000);
        $totalTransactionTime = 0;
        for ($n=0; $n<$this->cycleCount; $n++){
            $this->requestCount++;
            $requestTarget = CommManager::instance($this->config)->findUrl();
            $sleepTime = 100 + rand(0, 500);
            $totalTransactionTime += $sleepTime;
            usleep($sleepTime*1000);
            CommManager::instance($this->config)->reportResult($requestTarget,CommManager::$REQUEST_RESULT_RESPONSE_RECEIVED,200);
        }
        $duration = round(microtime(true)*1000); - $startTime;
    print("Thread ".$this->threadId." completed. Total Requests: ".$this->requestCount." Elapsed Time: ".($duration/1000)." seconds. Average Txn Time".($totalTransactionTime/$this->requestCount));
    }


}

