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
namespace cnp\sdk;
class Communication
{
    public static function httpRequest($req,$hash_config=NULL)
    {
        $config = Obj2xml::getConfig($hash_config);


        if ((int)$config['print_xml']) {
            Communication::print_xml($req, $config);
            #echo $req;
        }
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_PROXY, $config['proxy']);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: text/xml; charset=UTF-8','Expect: '));
        $commManager = CommManager::instance($config);
        $requestTarget = $commManager->findUrl();

        curl_setopt($ch, CURLOPT_URL, $requestTarget['targetUrl']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
        curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $config['timeout']);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,2);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSLVERSION, 6);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);


        $output = curl_exec($ch);


        $responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if (! $output) {
            if ($responseCode == 'CURLE_OPERATION_TIMEDOUT'){
                $commManager->reportResult($requestTarget,CommManager::$REQUEST_RESULT_RESPONSE_TIMEOUT,0);
            }
            else {
                $commManager->reportResult($requestTarget, CommManager::$REQUEST_RESULT_CONNECTION_FAILED, 0);
            }
            throw new \Exception (curl_error($ch));
        } else {
            curl_close($ch);
            if ((int) $config['print_xml']) {
                echo $output;
            }
            $commManager->reportResult($requestTarget,CommManager::$REQUEST_RESULT_RESPONSE_RECEIVED,$responseCode);
            return $output;
        }

    }

    public static function neuter_xml($xml)
    {
        $neuter_str = "NEUTERED";
        if ($xml === null) {
            return $xml;
        }
        $xml = preg_replace("/<accNum>.*?<\/accNum>/", "<accNum>$neuter_str</accNum>", $xml);
        $xml = preg_replace("/<user>.*?<\/user>/", "<user>$neuter_str</user>", $xml);
        $xml = preg_replace("/<password>.*?<\/password>/", "<password>$neuter_str</password>", $xml);
        $xml = preg_replace("/<track>.*?<\/track>/", "<track>$neuter_str</track>", $xml);
        $xml = preg_replace("/<number>.*?<\/number>/", "<number>$neuter_str</number>", $xml);
        $xml = preg_replace("/<cardValidationNum>.*?<\/cardValidationNum>/", "<cardValidationNum>$neuter_str</cardValidationNum>", $xml);

        return $xml;
    }

    //If neuter_xml flag enabled then neuter the sensitive data from the request xml
    public static function print_xml($xml_request, $config)
    {
        $xml_to_log = $xml_request;
        if (isset($config['neuter_xml']) and (int)$config['neuter_xml'] == 1) {
            $xml_to_log = Communication::neuter_xml($xml_to_log);
        }
        echo "Request XML: $xml_to_log\n";

        return $xml_to_log;
    }

}
