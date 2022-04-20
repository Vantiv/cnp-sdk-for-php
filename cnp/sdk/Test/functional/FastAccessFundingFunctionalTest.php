<?php
namespace cnp\sdk\Test\functional;

use cnp\sdk\CnpOnlineRequest;
use cnp\sdk\CommManager;
use cnp\sdk\XmlParser;

class FastAccessFundingFunctionalTest extends \PHPUnit_Framework_TestCase
{
    public static function setUpBeforeClass()
    {
        CommManager::reset();
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
        $location = XmlParser::getNode($fastAccessFundingResponse, 'location');
        $this->assertEquals('sandbox', $location);
    }

    public function test_fastAccessFunding_with_cardholderAddress()
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
            ),
            'cardholderAddress' => array(
                'addressLine1' => '2 Main St.',
                'addressLine2' => 'Apt. 222',
                'addressLine3' => 'NA',
                'city' => 'Riverside',
                'state' => 'RI',
                'zip' => '02915',
                'country' => 'US')
        );
        $initialize = new CnpOnlineRequest();
        $fastAccessFundingResponse = $initialize->fastAccessFunding($hash_in);
        $response = XmlParser::getNode($fastAccessFundingResponse, 'response');
        $this->assertEquals('000', $response);
        $location = XmlParser::getNode($fastAccessFundingResponse, 'location');
        $this->assertEquals('sandbox', $location);
    }
}