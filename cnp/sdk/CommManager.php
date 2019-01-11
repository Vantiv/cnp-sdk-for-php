<?php

namespace cnp\sdk;


class CommManager
{
    public static $REQUEST_RESULT_RESPONSE_RECEIVED = 1;
    public static $REQUEST_RESULT_CONNECTION_FAILED = 2;
    public static $REQUEST_RESULT_RESPONSE_TIMEOUT = 3;

    protected $configuration;
    protected $doMultiSite = false;
    protected $errorCount = 0;
    protected $currentMultiSiteUrlIndex = 0;
    protected $multiSiteThreshold = 5;
    protected $lastSiteSwitchTime = 0;
    protected $maxHoursWithoutSwitch = 48;
    protected $printDebug = false;
    protected $multiSiteUrls = array();
    protected $legacyUrl = "";

    private static $manager = null;
    static function instance($config){
        if (!isset(static::$manager)){
            static::$manager = new CommManager($config);
        }
        return static::$manager;
    }

    static function reset(){
        static::$manager = null;
    }

    function __construct($config){
        $this->configuration = $config;
        $this->legacyUrl = $this->configuration['url'];
        $this->doMultiSite = $this->configuration['multiSite'];
        $this->printDebug = $this->configuration['printMultiSiteDebug'];

        if ($this->doMultiSite === 'true'){
            for ($i = 1; $i <3; $i++){
                $siteUrl = $this->configuration['multiSiteUrl' . $i];
                if ($siteUrl == null){
                    break;
                }
                array_push($this->multiSiteUrls, $siteUrl);
            }
            if (count($this->multiSiteUrls) == 0){
                $this->doMultiSite = false;
            }
            else {
                 shuffle($this->multiSiteUrls);
                 $this->currentMultiSiteUrlIndex = 0;
                 $this->errorCount = 0;
                 $threshold = $this->configuration['multiSiteErrorThreshold'];
                 if ($threshold!=null){
                     $t = $threshold;
                     if ($t> 0 && $t<100){
                         $this->multiSiteThreshold = $t;
                     }
                 }
                $maxHours = $this->configuration['maxHoursWithoutSwitch'];
                if ($maxHours!=null){
                    $t = $maxHours;
                    if ($t> 0 && $t<100){
                        $this->maxHoursWithoutSwitch = $t;
                    }
                }
                $this->lastSiteSwitchTime = intval(microtime(true) * 1000);
            }
        }

    }

    //using flock to implement java's "synchronized" keyword
    function synchronized(){
        $backtrace = debug_backtrace('DEBUG_BACKTRACE_IGNORE_ARGS');
        $tempname = md5(json_encode($backtrace[0]));
        $filename = sys_get_temp_dir().'/'.$tempname.'lock';
        $file = fopen($filename,'w');
        if ($file === false){
            return false;
        }
        $lock = flock($file, LOCK_EX);
        if (!$lock){
            fclose($file);
            return false;
        }
        $requestTarget = $this->findUrlHandler();

        $result = $requestTarget;
        flock($file, LOCK_UN);
        fclose($file);
        return $result;
    }

    function findUrlHandler(){
        $url = $this->legacyUrl;
        if ($this->doMultiSite === 'true') {
            $switchSite = false;
            $switchReason = "";
            $currentUrl = $this->multiSiteUrls[$this->currentMultiSiteUrlIndex];
            if ($this->errorCount < $this->multiSiteThreshold){
                if ($this->maxHoursWithoutSwitch > 0){
                    $diffSinceSwitch = (round(microtime(true) *1000) - $this->lastSiteSwitchTime) / 3600;
                    if ($diffSinceSwitch > $this->maxHoursWithoutSwitch){
                        $switchReason = "more than " . (string)$this->maxHoursWithoutSwitch . " hours since last switch";
                        $switchSite = true;
                    }
                }
            }
            else {
                $switchReason = "consecutive error count has reached threshold of " . (string)($this->multiSiteThreshold);
                $switchSite = true;
            }

            if ($switchSite){
                $this->currentMultiSiteUrlIndex += 1;
                if ($this->currentMultiSiteUrlIndex >= count($this->multiSiteUrls)){
                    $this->currentMultiSiteUrlIndex = 0;
                }
                $url = $this->multiSiteUrls[$this->currentMultiSiteUrlIndex];
                $this->errorCount = 0;

                if ($this->printDebug) {
                    $switchTime = time();
                    $switchDate = date("m-d-Y", $switchTime);
                    print($switchDate . "Switched to " . $url . " because " . $switchReason);
                }
            }
            else{
                $url = $currentUrl;
            }
        }
        if ($this->printDebug) {
            print( "Selected URL: " . $url );
        }

        $requestTarget = array(
            'targetUrl'=> $url,
            'urlIndex'=>$this->currentMultiSiteUrlIndex,
            'requestTime'=>(int)round(microtime(true) *1000)
        );
        return $requestTarget;
    }

    public function findUrl()
    {
        return $this->synchronized();
    }


    public function reportResult($target ,$result ,$statusCode){
        if (!$this->doMultiSite || $target["requestTime"] < $this->lastSiteSwitchTime){
            return;
        }

        if ($result == CommManager::$REQUEST_RESULT_RESPONSE_RECEIVED){
            if ($statusCode == 200){
                $this->errorCount = 0;
            }
            elseif ($statusCode >=400){
                $this->errorCount += 1;
            }
            return;
        }
        if ($result == CommManager::$REQUEST_RESULT_CONNECTION_FAILED || $result == CommManager::$REQUEST_RESULT_RESPONSE_TIMEOUT){
            $this->errorCount += 1;
            return;
        }

    }

    public function getLegacyUrl(){
        return $this->legacyUrl;
    }
    public function getDoMultiSite(){
        return $this->doMultiSite==='true';
    }
    public function getMultiSiteThreshold(){
        return (int)$this->multiSiteThreshold;
    }
    public function getMultiSiteUrls(){
        return $this->multiSiteUrls;
    }
    public function getMaxHoursWithoutSwitch(){
        return (int)$this->maxHoursWithoutSwitch;
    }
    public function getCurrentMultiSiteUrlIndex(){
        return (int)$this->currentMultiSiteUrlIndex;
    }
    public function getErrorCount() {
        return (int)$this->errorCount;
    }
    public function getLastSiteSwitchTime(){
        return (int)$this->lastSiteSwitchTime;
    }
    public function setLastSiteSwitchTime($milliseconds){
        $this->lastSiteSwitchTime = (string)$milliseconds;
    }
}
