<?php /** @noinspection ALL */

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
require_once realpath(dirname(__FILE__)) . '/CnpOnline.php';

class CnpOnlineRequest
{
    private $useSimpleXml = false;

    public function __construct($treeResponse=false)
    {
        $this->useSimpleXml = $treeResponse;
        $this->newXML = new CnpXmlMapper();
    }

    /**
     * @param $code
     * @return mixed|string
     */
    public static function getAddressResponse($code)
    {
        $codes = array("00" => "5-Digit zip and address match",
                       "01" => "9-Digit zip and address match",
                       "02" => "Postal code and address match",
                       "10" => "5-Digit zip matches, address does not match",
                       "11" => "9-Digit zip matches, address does not match",
                       "12" => "Zip does not match, address matches",
                       "13" => "Postal code does not match, address matches",
                       "14" => "Postal code matches, address not verified",
                       "20" => "Neither zip nor address match",
                       "30" => "AVS service not supported by issuer",
                       "31" => "AVS system not available",
                       "32" => "Address unavailable",
                       "33" => "General error",
                       "34" => "AVS not performed",
                       "40" => "Address failed Vantiv eCommerce Inc. edit checks");

        return (isset($codes[$code]) ? $codes[$code] : "Unknown Address Response");
    }

    /**
     * @param $code
     * @return mixed|string
     */
    public static function getCardResponse($code)
    {
        $codes = array("M" => "Match",
                       "N" => "No Match",
                       "P" => "Not Processed",
                       "S" => "Security code should be on the card, but the merchant has indicated it is not present",
                       "U" => "Issuer is not certified for CVV2/CVC2/CID processing",
                       ""  => "Check was not done for an unspecified reason");

        return (isset($codes[$code]) ? $codes[$code] : "Unknown Address Response");
    }

    /**
     * @param $hash_in
     * @return \DOMDocument|\SimpleXMLElement
     * @throws exceptions\cnpSDKException
     */
    public function authorizationRequest($hash_in)
    {
        if (isset($hash_in['cnpTxnId'])) {
            $hash_out = array('cnpTxnId'=> (XmlFields::returnArrayValue($hash_in,'cnpTxnId')));
        } else {
            $hash_out = array(
            'orderId'=> XmlFields::returnArrayValue($hash_in,'orderId'),
            'amount'=>XmlFields::returnArrayValue($hash_in,'amount'),
            'id'=>XmlFields::returnArrayValue($hash_in,'id'),
            'secondaryAmount'=>XmlFields::returnArrayValue($hash_in,'secondaryAmount'),
            'surchargeAmount' =>XmlFields::returnArrayValue($hash_in,'surchargeAmount'),
            'orderSource'=>XmlFields::returnArrayValue($hash_in,'orderSource'),
            'customerInfo'=>(XmlFields::customerInfo(XmlFields::returnArrayValue($hash_in,'customerInfo'))),
            'billToAddress'=>(XmlFields::contact(XmlFields::returnArrayValue($hash_in,'billToAddress'))),
            'shipToAddress'=>(XmlFields::contact(XmlFields::returnArrayValue($hash_in,'shipToAddress'))),
            'card'=> (XmlFields::cardType(XmlFields::returnArrayValue($hash_in,'card'))),
            'paypal'=>(XmlFields::payPal(XmlFields::returnArrayValue($hash_in,'paypal'))),
            'token'=>(XmlFields::cardTokenType(XmlFields::returnArrayValue($hash_in,'token'))),
            'paypage'=>(XmlFields::cardPaypageType(XmlFields::returnArrayValue($hash_in,'paypage'))),
            'applepay'=>(XmlFields::applepayType(XmlFields::returnArrayValue($hash_in,'applepay'))),
            'mpos'=>(XmlFields::mposType(XmlFields::returnArrayValue($hash_in,'mpos'))),
            'billMeLaterRequest'=>(XmlFields::billMeLaterRequest(XmlFields::returnArrayValue($hash_in,'billMeLaterRequest'))),
            'cardholderAuthentication'=>(XmlFields::fraudCheckType(XmlFields::returnArrayValue($hash_in,'cardholderAuthentication'))),
            'processingInstructions'=>(XmlFields::processingInstructions(XmlFields::returnArrayValue($hash_in,'processingInstructions'))),
            'pos'=>(XmlFields::pos(XmlFields::returnArrayValue($hash_in,'pos'))),
            'customBilling'=>(XmlFields::customBilling(XmlFields::returnArrayValue($hash_in,'customBilling'))),
            'taxBilling'=>(XmlFields::taxBilling(XmlFields::returnArrayValue($hash_in,'taxBilling'))),
            'enhancedData'=>(XmlFields::enhancedData(XmlFields::returnArrayValue($hash_in,'enhancedData'))),
            'amexAggregatorData'=>(XmlFields::amexAggregatorData(XmlFields::returnArrayValue($hash_in,'amexAggregatorData'))),
            'allowPartialAuth'=>XmlFields::returnArrayValue($hash_in,'allowPartialAuth'),
            'healthcareIIAS'=>(XmlFields::healthcareIIAS(XmlFields::returnArrayValue($hash_in,'healthcareIIAS'))),
            'lodgingInfo'=>XmlFields::lodgingInfo(XmlFields::returnArrayValue($hash_in,'lodgingInfo')),
            'filtering'=>(XmlFields::filteringType(XmlFields::returnArrayValue($hash_in,'filtering'))),
            'merchantData'=>(XmlFields::merchantData(XmlFields::returnArrayValue($hash_in,'merchantData'))),
            'recyclingRequest'=>(XmlFields::recyclingRequestType(XmlFields::returnArrayValue($hash_in,'recyclingRequest'))),
            'fraudFilterOverride'=> XmlFields::returnArrayValue($hash_in,'fraudFilterOverride'),
            'recurringRequest'=>XmlFields::recurringRequestType(XmlFields::returnArrayValue($hash_in,'recurringRequest')),
            'debtRepayment' => XmlFields::returnArrayValue ( $hash_in, 'debtRepayment' ),
			'advancedFraudChecks' => XmlFields::advancedFraudChecksType ( XmlFields::returnArrayValue ( $hash_in, 'advancedFraudChecks' ) ),
			'processingType' => XmlFields::returnArrayValue ( $hash_in, 'processingType' ),
			'originalNetworkTransactionId' => XmlFields::returnArrayValue ( $hash_in, 'originalNetworkTransactionId' ),
			'originalTransactionAmount' => XmlFields::returnArrayValue ( $hash_in, 'originalTransactionAmount' ), 'lodgingInfo' => XmlFields::lodgingInfo(XmlFields::returnArrayValue($hash_in, 'lodgingInfo'))
            );
        }
        $choice_hash = array(XmlFields::returnArrayValue($hash_out,'card'),XmlFields::returnArrayValue($hash_out,'paypal'),XmlFields::returnArrayValue($hash_out,'token'),XmlFields::returnArrayValue($hash_out,'paypage'),XmlFields::returnArrayValue($hash_out,'applepay'),XmlFields::returnArrayValue($hash_out,'mpos'));
        $authorizationResponse = $this->processRequest($hash_out,$hash_in,'authorization',$choice_hash);

        return $authorizationResponse;
    }

    /**
     * @param $hash_in
     * @return \DOMDocument|\SimpleXMLElement
     * @throws exceptions\cnpSDKException
     */
    public function saleRequest($hash_in)
    {
        $hash_out = array(
            'cnpTxnId' => XmlFields::returnArrayValue($hash_in,'cnpTxnId'),
            'orderId' =>(XmlFields::returnArrayValue($hash_in,'orderId')),
            'amount' =>(XmlFields::returnArrayValue($hash_in,'amount')),
        	'id'=>XmlFields::returnArrayValue($hash_in,'id'),
            'secondaryAmount'=>XmlFields::returnArrayValue($hash_in,'secondaryAmount'),
            'surchargeAmount' =>XmlFields::returnArrayValue($hash_in,'surchargeAmount'),
            'orderSource'=>(XmlFields::returnArrayValue($hash_in,'orderSource')),
            'customerInfo'=>XmlFields::customerInfo(XmlFields::returnArrayValue($hash_in,'customerInfo')),
            'billToAddress'=>XmlFields::contact(XmlFields::returnArrayValue($hash_in,'billToAddress')),
            'shipToAddress'=>XmlFields::contact(XmlFields::returnArrayValue($hash_in,'shipToAddress')),
            'card'=> XmlFields::cardType(XmlFields::returnArrayValue($hash_in,'card')),
            'paypal'=>XmlFields::payPal(XmlFields::returnArrayValue($hash_in,'paypal')),
            'token'=>XmlFields::cardTokenType(XmlFields::returnArrayValue($hash_in,'token')),
            'paypage'=>XmlFields::cardPaypageType(XmlFields::returnArrayValue($hash_in,'paypage')),
        	'applepay'=>(XmlFields::applepayType(XmlFields::returnArrayValue($hash_in,'applepay'))),
        	'sepaDirectDebit'=>(XmlFields::sepaDirectDebitType(XmlFields::returnArrayValue($hash_in,'sepaDirectDebit'))),
        	'ideal'=>(XmlFields::idealType(XmlFields::returnArrayValue($hash_in,'ideal'))),
        	'giropay'=>(XmlFields::giropayType(XmlFields::returnArrayValue($hash_in,'giropay'))),
        	'sofort'=>(XmlFields::sofortType(XmlFields::returnArrayValue($hash_in,'sofort'))),
            'mpos'=>(XmlFields::mposType(XmlFields::returnArrayValue($hash_in,'mpos'))),
            'billMeLaterRequest'=>XmlFields::billMeLaterRequest(XmlFields::returnArrayValue($hash_in,'billMeLaterRequest')),
            'fraudCheck'=>XmlFields::fraudCheckType(XmlFields::returnArrayValue($hash_in,'fraudCheck')),
            'cardholderAuthentication'=>XmlFields::fraudCheckType(XmlFields::returnArrayValue($hash_in,'cardholderAuthentication')),
            'customBilling'=>XmlFields::customBilling(XmlFields::returnArrayValue($hash_in,'customBilling')),
            'taxBilling'=>XmlFields::taxBilling(XmlFields::returnArrayValue($hash_in,'taxBilling')),
            'enhancedData'=>XmlFields::enhancedData(XmlFields::returnArrayValue($hash_in,'enhancedData')),
            'processingInstructions'=>XmlFields::processingInstructions(XmlFields::returnArrayValue($hash_in,'processingInstructions')),
            'pos'=>XmlFields::pos(XmlFields::returnArrayValue($hash_in,'pos')),
            'payPalOrderComplete'=> XmlFields::returnArrayValue($hash_in,'paypalOrderComplete'),
            'payPalNotes'=> XmlFields::returnArrayValue($hash_in,'paypalNotesType'),
            'amexAggregatorData'=>XmlFields::amexAggregatorData(XmlFields::returnArrayValue($hash_in,'amexAggregatorData')),
            'allowPartialAuth'=>XmlFields::returnArrayValue($hash_in,'allowPartialAuth'),
            'healthcareIIAS'=>XmlFields::healthcareIIAS(XmlFields::returnArrayValue($hash_in,'healthcareIIAS')),
            'lodgingInfo' => XmlFields::lodgingInfo(XmlFields::returnArrayValue($hash_in, 'lodgingInfo')),
            'filtering'=>XmlFields::filteringType(XmlFields::returnArrayValue($hash_in,'filtering')),
            'merchantData'=>XmlFields::merchantData(XmlFields::returnArrayValue($hash_in,'merchantData')),
            'recyclingRequest'=>XmlFields::recyclingRequestType(XmlFields::returnArrayValue($hash_in,'recyclingRequest')),
            'fraudFilterOverride'=> XmlFields::returnArrayValue($hash_in,'fraudFilterOverride'),
            'recurringRequest'=>XmlFields::recurringRequestType(XmlFields::returnArrayValue($hash_in,'recurringRequest')),
            'cnpInternalRecurringRequest'=>XmlFields::cnpInternalRecurringRequestType(XmlFields::returnArrayValue($hash_in,'cnpInternalRecurringRequest')),
            'debtRepayment'=>XmlFields::returnArrayValue($hash_in,'debtRepayment'),
            'advancedFraudChecks'=>XmlFields::advancedFraudChecksType(XmlFields::returnArrayValue($hash_in,'advancedFraudChecks')),
            'wallet' => XmlFields::wallet( XmlFields::returnArrayValue ( $hash_in, 'wallet' )),
            'processingType' => XmlFields::returnArrayValue ( $hash_in, 'processingType' ),
        	'originalNetworkTransactionId' => XmlFields::returnArrayValue ( $hash_in, 'originalNetworkTransactionId' ),
        	'originalTransactionAmount' => XmlFields::returnArrayValue ( $hash_in, 'originalTransactionAmount' ),
            'pinlessDebitRequest' => XmlFields::pinlessDebitRequest(XmlFields::returnArrayValue ( $hash_in, 'pinlessDebitRequest' ))
        );

        $choice_hash = array($hash_out['card'],$hash_out['paypal'],$hash_out['token'],$hash_out['paypage'],$hash_out['applepay'],$hash_out['mpos']);
        $choice2_hash= array($hash_out['fraudCheck'],$hash_out['cardholderAuthentication']);
        $saleResponse = $this->processRequest($hash_out,$hash_in,'sale',$choice_hash,$choice2_hash);

        return $saleResponse;
    }

    /**
     * @param $hash_in
     * @return \DOMDocument|\SimpleXMLElement
     * @throws exceptions\cnpSDKException
     */
    public function authReversalRequest($hash_in)
    {
        $hash_out = array(
            'cnpTxnId' => (XmlFields::returnArrayValue($hash_in,'cnpTxnId')),
        	'id'=>XmlFields::returnArrayValue($hash_in,'id'),
            'amount' =>XmlFields::returnArrayValue($hash_in,'amount'),
            'surchargeAmount' =>XmlFields::returnArrayValue($hash_in,'surchargeAmount'),
            'payPalNotes'=>XmlFields::returnArrayValue($hash_in,'payPalNotes'),
            'actionReason'=>XmlFields::returnArrayValue($hash_in,'actionReason'));
        $authReversalResponse = $this->processRequest($hash_out,$hash_in,'authReversal');

        return $authReversalResponse;
    }

    /**
     * @param $hash_in
     * @return \DOMDocument|\SimpleXMLElement
     * @throws exceptions\cnpSDKException
     */
    public function giftCardAuthReversalRequest($hash_in)
    {
    	$hash_out = array( 
    			'cnpTxnId' => (XmlFields::returnArrayValue($hash_in,'cnpTxnId')),
    			'id'=>XmlFields::returnArrayValue($hash_in,'id'),
    			'card'=> XmlFields::giftCardCardType(XmlFields::returnArrayValue($hash_in,'card')),
    			'originalRefCode' =>XmlFields::returnArrayValue($hash_in,'originalRefCode'),
    			'originalAmount' =>XmlFields::returnArrayValue($hash_in,'originalAmount'),
    			'originalTxnTime'=>XmlFields::returnArrayValue($hash_in,'originalTxnTime'),
    			'originalSystemTraceId'=>XmlFields::returnArrayValue($hash_in,'originalSystemTraceId'),
    			'originalSequenceNumber'=>XmlFields::returnArrayValue($hash_in,'originalSequenceNumber')
    	);
    	$giftCardAuthReversalResponse = $this->processRequest($hash_out,$hash_in,'giftCardAuthReversal');

    	return $giftCardAuthReversalResponse;
    }

    /**
     * @param $hash_in
     * @return \DOMDocument|\SimpleXMLElement
     * @throws exceptions\cnpSDKException
     */
    public function giftCardCaptureRequest($hash_in) {
		$hash_out = array (
				'cnpTxnId' =>  ( XmlFields::returnArrayValue ( $hash_in, 'cnpTxnId' ) ),
				'id' =>  ( XmlFields::returnArrayValue ( $hash_in, 'id' ) ),
				'captureAmount' => XmlFields::returnArrayValue ( $hash_in, 'captureAmount' ),
				'card' => XmlFields::giftCardCardType ( XmlFields::returnArrayValue ( $hash_in, 'card' ) ),
				'originalRefCode' => XmlFields::returnArrayValue ( $hash_in, 'originalRefCode' ),
				'originalAmount' => XmlFields::returnArrayValue ( $hash_in, 'originalAmount' ),
				'originalTxnTime' => XmlFields::returnArrayValue ( $hash_in, 'originalTxnTime' )
		);
		$giftCardCaptureResponse = $this->processRequest ( $hash_out, $hash_in, 'giftCardCapture' );

		return $giftCardCaptureResponse;
	}

    /**
     * @param $hash_in
     * @return \DOMDocument|\SimpleXMLElement
     * @throws exceptions\cnpSDKException
     */
    public function creditRequest($hash_in)
    {
        $hash_out = array(
                    'cnpTxnId' => XmlFields::returnArrayValue($hash_in, 'cnpTxnId'),
        		    'id'=>XmlFields::returnArrayValue($hash_in,'id'),
                    'orderId' =>XmlFields::returnArrayValue($hash_in, 'orderId'),
                    'amount' =>XmlFields::returnArrayValue($hash_in, 'amount'),
        		    'secondaryAmount'=>XmlFields::returnArrayValue($hash_in,'secondaryAmount'),
                    'surchargeAmount' =>XmlFields::returnArrayValue($hash_in,'surchargeAmount'),
        			'pin' =>XmlFields::returnArrayValue($hash_in,'pin'),
                    'orderSource'=>XmlFields::returnArrayValue($hash_in, 'orderSource'),
                    'billToAddress'=>XmlFields::contact(XMLFields::returnArrayValue($hash_in, 'billToAddress')),
                    'card'=>XmlFields::cardType(XMLFields::returnArrayValue($hash_in, 'card')),
                    'paypal'=>XmlFields::credit_payPal(XMLFields::returnArrayValue($hash_in, 'paypal')),
                    'token'=>XmlFields::cardTokenType(XMLFields::returnArrayValue($hash_in, 'token')),
                    'paypage'=>XmlFields::cardPaypageType(XMLFields::returnArrayValue($hash_in, 'paypage')),
                    'mpos'=>(XmlFields::mposType(XmlFields::returnArrayValue($hash_in,'mpos'))),
                    'customBilling'=>XmlFields::customBilling(XMLFields::returnArrayValue($hash_in, 'customBilling')),
                    'taxBilling'=>XmlFields::taxBilling(XMLFields::returnArrayValue($hash_in, 'taxBilling')),
                    'billMeLaterRequest'=>XmlFields::billMeLaterRequest(XMLFields::returnArrayValue($hash_in, 'billMeLaterRequest')),
                    'enhancedData'=>XmlFields::enhancedData(XMLFields::returnArrayValue($hash_in, 'enhancedData')),
                    'lodgingInfo' => XmlFields::lodgingInfo(XmlFields::returnArrayValue($hash_in, 'lodgingInfo')),
                    'processingInstructions'=>XmlFields::processingInstructions(XMLFields::returnArrayValue($hash_in, 'processingInstructions')),
                    'pos'=>XmlFields::pos(XMLFields::returnArrayValue($hash_in, 'pos')),
                    'amexAggregatorData'=>XmlFields::amexAggregatorData(XMLFields::returnArrayValue($hash_in, 'amexAggregatorData')),
                    'payPalNotes' =>XmlFields::returnArrayValue($hash_in, 'payPalNotes'),
                    'actionReason'=>XmlFields::returnArrayValue($hash_in, 'actionReason')
        );

        $choice_hash = array($hash_out['card'],$hash_out['paypal'],$hash_out['token'],$hash_out['paypage'],$hash_out['mpos']);
        $creditResponse = $this->processRequest($hash_out,$hash_in,'credit',$choice_hash);

        return $creditResponse;
    }

    /**
     * @param $hash_in
     * @return \DOMDocument|\SimpleXMLElement
     * @throws exceptions\cnpSDKException
     */
    public function giftCardCreditRequest($hash_in)
    {
    	$hash_out = array(
    			'cnpTxnId' => XmlFields::returnArrayValue($hash_in, 'cnpTxnId'),
    			'orderId' =>XmlFields::returnArrayValue($hash_in, 'orderId'),
    			'id'=>XmlFields::returnArrayValue($hash_in,'id'),
    			'creditAmount' =>XmlFields::returnArrayValue($hash_in, 'creditAmount'),
    			'orderSource'=>XmlFields::returnArrayValue($hash_in, 'orderSource'),
    			'card'=>XmlFields::giftCardCardType(XMLFields::returnArrayValue($hash_in, 'card'))
    	);

    	$giftCardCreditResponse = $this->processRequest($hash_out,$hash_in,'giftCardCredit');

    	return $giftCardCreditResponse;
    }

    /**
     * @param $hash_in
     * @return \DOMDocument|\SimpleXMLElement
     * @throws exceptions\cnpSDKException
     */
    public function registerTokenRequest($hash_in)
    {
        $hash_out = array(
            // new
            'encryptionKeyId' => XmlFields::returnArrayValue($hash_in,'encryptionKeyId'),
            //
            'orderId'=>XmlFields::returnArrayValue($hash_in,'orderId'),
        	'id'=>XmlFields::returnArrayValue($hash_in,'id'),
            // new
            'mpos' => XmlFields::mposType(XmlFields::returnArrayValue($hash_in,'mpos')),
            //
            'accountNumber'=>XmlFields::returnArrayValue($hash_in,'accountNumber'),
            // new
            'encryptedAccountNumber' => XmlFields::returnArrayValue($hash_in,'encryptedAccountNumber'),
            //
            'echeckForToken'=>XmlFields::echeckForTokenType(XmlFields::returnArrayValue($hash_in,'echeckForToken')),
            'paypageRegistrationId'=>XmlFields::returnArrayValue($hash_in,'paypageRegistrationId'),
            'applepay'=>(XmlFields::applepayType(XmlFields::returnArrayValue($hash_in,'applepay'))),
            'cardValidationNum'=>XmlFields::returnArrayValue($hash_in,'cardValidationNum'),
            // new
            'encryptedCardValidationNum' => XmlFields::returnArrayValue($hash_in,'encryptedCardValidationNum')
            //
        );

        $choice_hash = array($hash_out['mpos'],$hash_out['accountNumber'],$hash_out['encryptedAccountNumber'],$hash_out['echeckForToken'],$hash_out['paypageRegistrationId'],$hash_out['applepay']);
        $registerTokenResponse = $this->processRequest($hash_out,$hash_in,'registerTokenRequest',$choice_hash);

        return $registerTokenResponse;
    }

    /**
     * @param $hash_in
     * @return \DOMDocument|\SimpleXMLElement
     * @throws exceptions\cnpSDKException
     */
    public function forceCaptureRequest($hash_in)
    {
        $hash_out = array(
            'orderId' =>(XmlFields::returnArrayValue($hash_in,'orderId')),
        	'id'=>XmlFields::returnArrayValue($hash_in,'id'),
            'amount' =>XmlFields::returnArrayValue($hash_in,'amount'),
        	'secondaryAmount'=>XmlFields::returnArrayValue($hash_in,'secondaryAmount'),
            'surchargeAmount' =>XmlFields::returnArrayValue($hash_in,'surchargeAmount'),
            'orderSource'=>(XmlFields::returnArrayValue($hash_in,'orderSource')),
            'billToAddress'=>XmlFields::contact(XmlFields::returnArrayValue($hash_in,'billToAddress')),
            'card'=> XmlFields::cardType(XmlFields::returnArrayValue($hash_in,'card')),
            'token'=>XmlFields::cardTokenType(XmlFields::returnArrayValue($hash_in,'token')),
            'paypage'=>XmlFields::cardPaypageType(XmlFields::returnArrayValue($hash_in,'paypage')),
            'mpos'=>(XmlFields::mposType(XmlFields::returnArrayValue($hash_in,'mpos'))),
            'customBilling'=>XmlFields::customBilling(XmlFields::returnArrayValue($hash_in,'customBilling')),
            'taxBilling'=>XmlFields::taxBilling(XmlFields::returnArrayValue($hash_in,'taxBilling')),
            'enhancedData'=>XmlFields::enhancedData(XmlFields::returnArrayValue($hash_in,'enhancedData')),
            'lodgingInfo' => XmlFields::lodgingInfo(XmlFields::returnArrayValue($hash_in, 'lodgingInfo')),
            'processingInstructions'=>XmlFields::processingInstructions(XmlFields::returnArrayValue($hash_in,'processingInstructions')),
            'pos'=>XmlFields::pos(XmlFields::returnArrayValue($hash_in,'pos')),
            'amexAggregatorData'=>XmlFields::amexAggregatorData(XmlFields::returnArrayValue($hash_in,'amexAggregatorData')),
            'merchantData'=>(XmlFields::merchantData(XmlFields::returnArrayValue($hash_in,'merchantData'))),
            'debtRepayment'=>XmlFields::returnArrayValue($hash_in,'debtRepayment'),
        	'processingType'=>XmlFields::returnArrayValue($hash_in,'processingType')
        );

        $choice_hash = array(XmlFields::returnArrayValue($hash_out,'card'),XmlFields::returnArrayValue($hash_out,'paypal'),XmlFields::returnArrayValue($hash_out,'token'),XmlFields::returnArrayValue($hash_out,'paypage'),XmlFields::returnArrayValue($hash_out,'mpos'));
        $forceCaptureResponse = $this->processRequest($hash_out,$hash_in,'forceCapture',$choice_hash);

        return $forceCaptureResponse;
    }

    /**
     * @param $hash_in
     * @return \DOMDocument|\SimpleXMLElement
     * @throws exceptions\cnpSDKException
     */
    public function captureRequest($hash_in)
    {
        $hash_out = array(
        'partial'=>XmlFields::returnArrayValue($hash_in,'partial'),
        'id'=>XmlFields::returnArrayValue($hash_in,'id'),
        'cnpTxnId' => (XmlFields::returnArrayValue($hash_in,'cnpTxnId')),
        'amount' =>(XmlFields::returnArrayValue($hash_in,'amount')),
        'surchargeAmount' =>XmlFields::returnArrayValue($hash_in,'surchargeAmount'),
        'enhancedData'=>XmlFields::enhancedData(XmlFields::returnArrayValue($hash_in,'enhancedData')),
        'processingInstructions'=>XmlFields::processingInstructions(XmlFields::returnArrayValue($hash_in,'processingInstructions')),
        'payPalOrderComplete'=>XmlFields::returnArrayValue($hash_in,'payPalOrderComplete'),
        'payPalNotes' =>XmlFields::returnArrayValue($hash_in,'payPalNotes'),
        'lodgingInfo' => XmlFields::lodgingInfo(XmlFields::returnArrayValue($hash_in, 'lodgingInfo')),
        'pin' =>XmlFields::returnArrayValue($hash_in,'pin')
        );
        $captureResponse = $this->processRequest($hash_out,$hash_in,'capture');

        return $captureResponse;
    }

    /**
     * @param $hash_in
     * @return \DOMDocument|\SimpleXMLElement
     * @throws exceptions\cnpSDKException
     */
    public function captureGivenAuthRequest($hash_in)
    {
        $hash_out = array(
            'orderId'=>(XmlFields::returnArrayValue($hash_in,'orderId')),
        	'id'=>XmlFields::returnArrayValue($hash_in,'id'),
            'authInformation'=>XmlFields::authInformation(XmlFields::returnArrayValue($hash_in,'authInformation')),
            'amount' =>(XmlFields::returnArrayValue($hash_in,'amount')),
        	'secondaryAmount'=>XmlFields::returnArrayValue($hash_in,'secondaryAmount'),
            'surchargeAmount' =>XmlFields::returnArrayValue($hash_in,'surchargeAmount'),
            'orderSource'=>(XmlFields::returnArrayValue($hash_in,'orderSource')),
            'billToAddress'=>XmlFields::contact(XmlFields::returnArrayValue($hash_in,'billToAddress')),
            'shipToAddress'=>XmlFields::contact(XmlFields::returnArrayValue($hash_in,'shipToAddress')),
            'card'=> XmlFields::cardType(XmlFields::returnArrayValue($hash_in,'card')),
            'token'=>XmlFields::cardTokenType(XmlFields::returnArrayValue($hash_in,'token')),
            'paypage'=>XmlFields::cardPaypageType(XmlFields::returnArrayValue($hash_in,'paypage')),
            'mpos'=>(XmlFields::mposType(XmlFields::returnArrayValue($hash_in,'mpos'))),
            'customBilling'=>XmlFields::customBilling(XmlFields::returnArrayValue($hash_in,'customBilling')),
            'taxBilling'=>XmlFields::taxBilling(XmlFields::returnArrayValue($hash_in,'taxBilling')),
            'billMeLaterRequest'=>XmlFields::billMeLaterRequest(XmlFields::returnArrayValue($hash_in,'billMeLaterRequest')),
            'enhancedData'=>XmlFields::enhancedData(XmlFields::returnArrayValue($hash_in,'enhancedData')),
            'lodgingInfo' => XmlFields::lodgingInfo(XmlFields::returnArrayValue($hash_in, 'lodgingInfo')),
            'processingInstructions'=>XmlFields::processingInstructions(XmlFields::returnArrayValue($hash_in,'processingInstructions')),
            'pos'=>XmlFields::pos(XmlFields::returnArrayValue($hash_in,'pos')),
            'amexAggregatorData'=>XmlFields::amexAggregatorData(XmlFields::returnArrayValue($hash_in,'amexAggregatorData')),
            'merchantData'=>(XmlFields::merchantData(XmlFields::returnArrayValue($hash_in,'merchantData'))),
            'debtRepayment'=>XmlFields::returnArrayValue($hash_in,'debtRepayment'),
        	'processingType' => XmlFields::returnArrayValue ( $hash_in, 'processingType' ),
        	'originalNetworkTransactionId' => XmlFields::returnArrayValue ( $hash_in, 'originalNetworkTransactionId' ),
        	'originalTransactionAmount' => XmlFields::returnArrayValue ( $hash_in, 'originalTransactionAmount' )
        );

        $choice_hash = array($hash_out['card'],$hash_out['token'],$hash_out['paypage'],$hash_out['mpos']);
        $captureGivenAuthResponse = $this->processRequest($hash_out,$hash_in,'captureGivenAuth',$choice_hash);

        return $captureGivenAuthResponse;
    }

    /**
     * @param $hash_in
     * @return \DOMDocument|\SimpleXMLElement
     * @throws exceptions\cnpSDKException
     */
    public function echeckRedepositRequest($hash_in)
    {
        $hash_out = array(
            'cnpTxnId' => (XmlFields::returnArrayValue($hash_in,'cnpTxnId')),
        	'id'=>XmlFields::returnArrayValue($hash_in,'id'),
            'echeck'=>XmlFields::echeckType(XmlFields::returnArrayValue($hash_in,'echeck')),
            'echeckToken'=>XmlFields::echeckTokenType(XmlFields::returnArrayValue($hash_in,'echeckToken')),
            'merchantData'=>(XmlFields::merchantData(XmlFields::returnArrayValue($hash_in,'merchantData'))),
        		'customIdentifier'=>XmlFields::returnArrayValue($hash_in,'customIdentifier')
        );

        $choice_hash = array($hash_out['echeck'],$hash_out['echeckToken']);
        $echeckRedepositResponse = $this->processRequest($hash_out,$hash_in,'echeckRedeposit',$choice_hash);

        return $echeckRedepositResponse;
    }

    /**
     * @param $hash_in
     * @return \DOMDocument|\SimpleXMLElement
     * @throws exceptions\cnpSDKException
     */
    public function echeckSaleRequest($hash_in)
    {
        $hash_out = array(
        'cnpTxnId'=>XmlFields::returnArrayValue($hash_in,'cnpTxnId'),
        'id'=>XmlFields::returnArrayValue($hash_in,'id'),
        'orderId'=>XmlFields::returnArrayValue($hash_in,'orderId'),
        'verify'=>XmlFields::returnArrayValue($hash_in,'verify'),
        'amount'=>XmlFields::returnArrayValue($hash_in,'amount'),
        'secondaryAmount'=>XmlFields::returnArrayValue($hash_in,'secondaryAmount'),
        'orderSource'=>XmlFields::returnArrayValue($hash_in,'orderSource'),
        'billToAddress'=>XmlFields::contact(XmlFields::returnArrayValue($hash_in,'billToAddress')),
        'shipToAddress'=>XmlFields::contact(XmlFields::returnArrayValue($hash_in,'shipToAddress')),
        'echeck'=>XmlFields::echeckType(XmlFields::returnArrayValue($hash_in,'echeck')),
        'echeckToken'=>XmlFields::echeckTokenType(XmlFields::returnArrayValue($hash_in,'echeckToken')),
        'customBilling'=>XmlFields::customBilling(XmlFields::returnArrayValue($hash_in,'customBilling')),
        'merchantData'=>XmlFields::merchantData(XmlFields::returnArrayValue($hash_in,'merchantData')),
        'customIdentifier'=>XmlFields::returnArrayValue($hash_in,'customIdentifier')
        );

        $choice_hash = array($hash_out['echeck'],$hash_out['echeckToken']);

        $echeckSaleResponse = $this->processRequest($hash_out,$hash_in,'echeckSale',$choice_hash);

        return $echeckSaleResponse;
    }

    /**
     * @param $hash_in
     * @return \DOMDocument|\SimpleXMLElement
     * @throws exceptions\cnpSDKException
     */
    public function echeckCreditRequest($hash_in)
    {
        $hash_out = array(
            'cnpTxnId'=>XmlFields::returnArrayValue($hash_in,'cnpTxnId'),
        	'id'=>XmlFields::returnArrayValue($hash_in,'id'),
            'orderId'=>XmlFields::returnArrayValue($hash_in,'orderId'),
            'amount'=>XmlFields::returnArrayValue($hash_in,'amount'),
        	'secondaryAmount'=>XmlFields::returnArrayValue($hash_in,'secondaryAmount'),
            'orderSource'=>XmlFields::returnArrayValue($hash_in,'orderSource'),
            'billToAddress'=>XmlFields::contact(XmlFields::returnArrayValue($hash_in,'billToAddress')),
            'echeck'=>XmlFields::echeckType(XmlFields::returnArrayValue($hash_in,'echeck')),
            'echeckToken'=>XmlFields::echeckTokenType(XmlFields::returnArrayValue($hash_in,'echeckToken')),
            'customBilling'=>XmlFields::customBilling(XmlFields::returnArrayValue($hash_in,'customBilling')),
        	'customIdentifier'=>XmlFields::returnArrayValue($hash_in,'customIdentifier')
        );

        $choice_hash = array($hash_out['echeck'],$hash_out['echeckToken']);
        $echeckCreditResponse = $this->processRequest($hash_out,$hash_in,'echeckCredit',$choice_hash);

        return $echeckCreditResponse;
    }

    /**
     * @param $hash_in
     * @return \DOMDocument|\SimpleXMLElement
     * @throws exceptions\cnpSDKException
     */
    public function echeckVerificationRequest($hash_in)
    {

        $hash_out = array(
        	'id'=>XmlFields::returnArrayValue($hash_in,'id'),
            'orderId'=>(XmlFields::returnArrayValue($hash_in,'orderId')),
            'amount'=>(XmlFields::returnArrayValue($hash_in,'amount')),
            'orderSource'=>(XmlFields::returnArrayValue($hash_in,'orderSource')),
            'billToAddress'=>XmlFields::contact(XmlFields::returnArrayValue($hash_in,'billToAddress')),
            'echeck'=>XmlFields::echeckType(XmlFields::returnArrayValue($hash_in,'echeck')),
            'echeckToken'=>XmlFields::echeckTokenType(XmlFields::returnArrayValue($hash_in,'echeckToken')),
            'merchantData'=>(XmlFields::merchantData(XmlFields::returnArrayValue($hash_in,'merchantData'))),
        );

        $choice_hash = array($hash_out['echeck'],$hash_out['echeckToken']);
        $echeckVerificationResponse = $this->processRequest($hash_out,$hash_in,'echeckVerification',$choice_hash);

        return $echeckVerificationResponse;
    }

    /**
     * @param $hash_in
     * @return \DOMDocument|\SimpleXMLElement
     * @throws exceptions\cnpSDKException
     */
    public function voidRequest($hash_in)
    {
        $hash_out = array(
        'cnpTxnId' => (XmlFields::returnArrayValue($hash_in,'cnpTxnId')),
        'id'=>XmlFields::returnArrayValue($hash_in,'id'),
        'processingInstructions'=>XmlFields::processingInstructions(XmlFields::returnArrayValue($hash_in,'processingInstructions')));
        $voidResponse = $this->processRequest($hash_out,$hash_in,'void');

        return $voidResponse;
    }

    /**
     * @param $hash_in
     * @return \DOMDocument|\SimpleXMLElement
     * @throws exceptions\cnpSDKException
     */
    public function echeckVoidRequest($hash_in)
    {
        $hash_out = array(
        'id'=>XmlFields::returnArrayValue($hash_in,'id'),
        'cnpTxnId' => (XmlFields::returnArrayValue($hash_in,'cnpTxnId')),
        );
        $echeckVoidResponse = $this->processRequest($hash_out,$hash_in,"echeckVoid");

        return $echeckVoidResponse;
    }

    /**
     * @param $hash_in
     * @return \DOMDocument|\SimpleXMLElement
     * @throws exceptions\cnpSDKException
     */
    public function depositReversalRequest($hash_in)
    {
        $hash_out = array(
        		'cnpTxnId' => (XmlFields::returnArrayValue($hash_in,'cnpTxnId')),
        		'id'=>XmlFields::returnArrayValue($hash_in,'id'),
        		'card'=> XmlFields::giftCardCardType(XmlFields::returnArrayValue($hash_in,'card')),
        		'originalRefCode'=>XmlFields::returnArrayValue($hash_in,'originalRefCode'),
        		'originalAmount'=>XmlFields::returnArrayValue($hash_in,'originalAmount'),
        		'originalTxnTime'=>XmlFields::returnArrayValue($hash_in,'originalTxnTime'),
        		'originalSystemTraceId'=>XmlFields::returnArrayValue($hash_in,'originalSystemTraceId'),
        		'originalSequenceNumber'=>XmlFields::returnArrayValue($hash_in,'originalSequenceNumber')
        );
        $response = $this->processRequest($hash_out,$hash_in,"depositReversal");

        return $response;
    }

    /**
     * @param $hash_in
     * @return \DOMDocument|\SimpleXMLElement
     * @throws exceptions\cnpSDKException
     */
    public function refundReversalRequest($hash_in)
    {
        $hash_out = array(
		        'cnpTxnId' => (XmlFields::returnArrayValue($hash_in,'cnpTxnId')),
		        'id'=>XmlFields::returnArrayValue($hash_in,'id'),
        		'card'=> XmlFields::giftCardCardType(XmlFields::returnArrayValue($hash_in,'card')),
        		'originalRefCode'=>XmlFields::returnArrayValue($hash_in,'originalRefCode'),
        		'originalAmount'=>XmlFields::returnArrayValue($hash_in,'originalAmount'),
        		'originalTxnTime'=>XmlFields::returnArrayValue($hash_in,'originalTxnTime'),
        		'originalSystemTraceId'=>XmlFields::returnArrayValue($hash_in,'originalSystemTraceId'),
        		'originalSequenceNumber'=>XmlFields::returnArrayValue($hash_in,'originalSequenceNumber')
        );
        $response = $this->processRequest($hash_out,$hash_in,"refundReversal");

        return $response;
    }

    /**
     * @param $hash_in
     * @return \DOMDocument|\SimpleXMLElement
     * @throws exceptions\cnpSDKException
     */
    public function activateReversalRequest($hash_in)
    {
        $hash_out = array(
		        'cnpTxnId' => (XmlFields::returnArrayValue($hash_in,'cnpTxnId')),
		        'id'=>XmlFields::returnArrayValue($hash_in,'id'),
        		'card'=> XmlFields::giftCardCardType(XmlFields::returnArrayValue($hash_in,'card')),
        		'originalRefCode'=>XmlFields::returnArrayValue($hash_in,'originalRefCode'),
        		'originalAmount'=>XmlFields::returnArrayValue($hash_in,'originalAmount'),
        		'originalTxnTime'=>XmlFields::returnArrayValue($hash_in,'originalTxnTime'),
        		'originalSystemTraceId'=>XmlFields::returnArrayValue($hash_in,'originalSystemTraceId'),
        		'originalSequenceNumber'=>XmlFields::returnArrayValue($hash_in,'originalSequenceNumber')
        );
        $response = $this->processRequest($hash_out,$hash_in,"activateReversal");

        return $response;
    }

    /**
     * @param $hash_in
     * @return \DOMDocument|\SimpleXMLElement
     * @throws exceptions\cnpSDKException
     */
    public function translateToLowValueTokenRequest($hash_in)
    {
        $hash_out = array(
                'orderId' => XmlFields::returnArrayValue($hash_in, 'orderId', 25),
                'token' => (XmlFields::returnArrayValue($hash_in, 'token', 512))
        );
        $response = $this->processRequest($hash_out, $hash_in, 'translateToLowValueTokenRequest');

        return $response;
    }

    /**
     * @param $hash_in
     * @return \DOMDocument|\SimpleXMLElement
     * @throws exceptions\cnpSDKException
     */
    public function deactivateReversalRequest($hash_in)
    {
        $hash_out = array(
		        'cnpTxnId' => (XmlFields::returnArrayValue($hash_in,'cnpTxnId')),
		        'id'=>XmlFields::returnArrayValue($hash_in,'id'),
        		'card'=> XmlFields::giftCardCardType(XmlFields::returnArrayValue($hash_in,'card')),
        		'originalRefCode'=>XmlFields::returnArrayValue($hash_in,'originalRefCode'),
        		'originalTxnTime'=>XmlFields::returnArrayValue($hash_in,'originalTxnTime'),
        		'originalSystemTraceId'=>XmlFields::returnArrayValue($hash_in,'originalSystemTraceId'),
        		'originalSequenceNumber'=>XmlFields::returnArrayValue($hash_in,'originalSequenceNumber')
        );
        $response = $this->processRequest($hash_out,$hash_in,"deactivateReversal");

        return $response;
    }

    /**
     * @param $hash_in
     * @return \DOMDocument|\SimpleXMLElement
     * @throws exceptions\cnpSDKException
     */
    public function loadReversalRequest($hash_in)
    {
        $hash_out = array(
		        'cnpTxnId' => (XmlFields::returnArrayValue($hash_in,'cnpTxnId')),
		        'id'=>XmlFields::returnArrayValue($hash_in,'id'),
        		'card'=> XmlFields::giftCardCardType(XmlFields::returnArrayValue($hash_in,'card')),
        		'originalRefCode'=>XmlFields::returnArrayValue($hash_in,'originalRefCode'),
        		'originalAmount'=>XmlFields::returnArrayValue($hash_in,'originalAmount'),
        		'originalTxnTime'=>XmlFields::returnArrayValue($hash_in,'originalTxnTime'),
        		'originalSystemTraceId'=>XmlFields::returnArrayValue($hash_in,'originalSystemTraceId'),
        		'originalSequenceNumber'=>XmlFields::returnArrayValue($hash_in,'originalSequenceNumber')
        );
        $response = $this->processRequest($hash_out,$hash_in,"loadReversal");

        return $response;
    }

    /**
     * @param $hash_in
     * @return \DOMDocument|\SimpleXMLElement
     * @throws exceptions\cnpSDKException
     */
    public function unloadReversalRequest($hash_in)
    {
        $hash_out = array(
		        'cnpTxnId' => (XmlFields::returnArrayValue($hash_in,'cnpTxnId')),
		        'id'=>XmlFields::returnArrayValue($hash_in,'id'),
        		'card'=> XmlFields::giftCardCardType(XmlFields::returnArrayValue($hash_in,'card')),
        		'originalRefCode'=>XmlFields::returnArrayValue($hash_in,'originalRefCode'),
        		'originalAmount'=>XmlFields::returnArrayValue($hash_in,'originalAmount'),
        		'originalTxnTime'=>XmlFields::returnArrayValue($hash_in,'originalTxnTime'),
        		'originalSystemTraceId'=>XmlFields::returnArrayValue($hash_in,'originalSystemTraceId'),
        		'originalSequenceNumber'=>XmlFields::returnArrayValue($hash_in,'originalSequenceNumber')
        );
        $response = $this->processRequest($hash_out,$hash_in,"unloadReversal");

        return $response;
    }

    /**
     * @param $hash_in
     * @return \DOMDocument|\SimpleXMLElement
     * @throws exceptions\cnpSDKException
     */
    public function updateCardValidationNumOnToken($hash_in)
    {
        $hash_out = array(
                'orderId'=>XmlFields::returnArrayValue($hash_in,'orderId'),
        		'id'=>XmlFields::returnArrayValue($hash_in,'id'),
                'cnpToken' => (XmlFields::returnArrayValue($hash_in,'cnpToken')),
                'cardValidationNum' => (XmlFields::returnArrayValue($hash_in,'cardValidationNum')),
        );
        $updateCardValidationNumOnTokenResponse = $this->processRequest($hash_out,$hash_in,"updateCardValidationNumOnToken");

        return $updateCardValidationNumOnTokenResponse;
    }

    /**
     * @param $hash_in
     * @return \DOMDocument|\SimpleXMLElement
     * @throws exceptions\cnpSDKException
     */
    public function updateSubscription($hash_in)
    {
        $hash_out = Transactions::createUpdateSubscriptionHash($hash_in);
        $choice_hash = array(XmlFields::returnArrayValue($hash_out,'card'),XmlFields::returnArrayValue($hash_out,'token'),XmlFields::returnArrayValue($hash_out,'paypage'));
        $updateSubscriptionResponse = $this->processRequest($hash_out,$hash_in,"updateSubscription");

        return $updateSubscriptionResponse;
    }

    /**
     * @param $hash_in
     * @return \DOMDocument|\SimpleXMLElement
     * @throws exceptions\cnpSDKException
     */
    public function cancelSubscription($hash_in)
    {
        $hash_out = Transactions::createCancelSubscriptionHash($hash_in);
        $cancelSubscriptionResponse = $this->processRequest($hash_out,$hash_in,"cancelSubscription");

        return $cancelSubscriptionResponse;
    }

    /**
     * @param $hash_in
     * @return \DOMDocument|\SimpleXMLElement
     * @throws exceptions\cnpSDKException
     */
    public function updatePlan($hash_in)
    {
        $hash_out = Transactions::createUpdatePlanHash($hash_in);
        $updatePlanResponse = $this->processRequest($hash_out,$hash_in,"updatePlan");

        return $updatePlanResponse;
    }

    /**
     * @param $hash_in
     * @return \DOMDocument|\SimpleXMLElement
     * @throws exceptions\cnpSDKException
     */
    public function createPlan($hash_in)
    {
        $hash_out = Transactions::createCreatePlanHash($hash_in);
        $createPlanResponse = $this->processRequest($hash_out,$hash_in,"createPlan");

        return $createPlanResponse;
    }

    /**
     * @param $hash_in
     * @return \DOMDocument|\SimpleXMLElement
     * @throws exceptions\cnpSDKException
     */
    public function activate($hash_in)
    {
        $hash_out = Transactions::createActivateHash($hash_in);
        $txnResponse = $this->processRequest($hash_out,$hash_in,"activate");

        return $txnResponse;
    }

    /**
     * @param $hash_in
     * @return \DOMDocument|\SimpleXMLElement
     * @throws exceptions\cnpSDKException
     */
    public function deactivate($hash_in)
    {
        $hash_out = Transactions::createDeactivateHash($hash_in);
        $txnResponse = $this->processRequest($hash_out,$hash_in,"deactivate");

        return $txnResponse;
    }

    /**
     * @param $hash_in
     * @return \DOMDocument|\SimpleXMLElement
     * @throws exceptions\cnpSDKException
     */
    public function load($hash_in)
    {
        $hash_out = Transactions::createLoadHash($hash_in);
        $txnResponse = $this->processRequest($hash_out,$hash_in,"load");

        return $txnResponse;
    }

    /**
     * @param $hash_in
     * @return \DOMDocument|\SimpleXMLElement
     * @throws exceptions\cnpSDKException
     */
    public function unload($hash_in)
    {
        $hash_out = Transactions::createUnloadHash($hash_in);
        $txnResponse = $this->processRequest($hash_out,$hash_in,"unload");

        return $txnResponse;
    }

    /**
     * @param $hash_in
     * @return \DOMDocument|\SimpleXMLElement
     * @throws exceptions\cnpSDKException
     */
    public function balanceInquiry($hash_in)
    {
        $hash_out = Transactions::createBalanceInquiryHash($hash_in);
        $txnResponse = $this->processRequest($hash_out,$hash_in,"balanceInquiry");

        return $txnResponse;
    }

    /**
     * @param $hash_in
     * @return \DOMDocument|\SimpleXMLElement
     * @throws exceptions\cnpSDKException
     */
    public function queryTransaction($hash_in)
    {
    	$hash_out = array(
    			'id'=>XmlFields::returnArrayValue($hash_in,'id'),
    			'origId'=>(XmlFields::returnArrayValue($hash_in,'origId')),
    			'origActionType'=>(XmlFields::returnArrayValue($hash_in,'origActionType')),
    			'origLitleTxnId'=>XmlFields::returnArrayValue($hash_in,'origLitleTxnId'),
                'showStatusOnly' => XmlFields::returnArrayValue($hash_in, 'showStatusOnly')
    	);
    	$queryTransactionResponse = $this->processRequest($hash_out,$hash_in,"queryTransaction");

    	return $queryTransactionResponse;
    }

    /**
     * @param $hash_in
     * @return \DOMDocument|\SimpleXMLElement
     * @throws exceptions\cnpSDKException
     */
    public function fraudCheck($hash_in)
    {
    	$hash_out = array(
    			'id'=>XmlFields::returnArrayValue($hash_in,'id'),
    			'advancedFraudChecks'=>(XmlFields::returnArrayValue($hash_in,'advancedFraudChecks')),
    			'billToAddress' => XmlFields::contact ( XmlFields::returnArrayValue ( $hash_in, 'billToAddress' ) ),
    			'shipToAddress' => XmlFields::contact ( XmlFields::returnArrayValue ( $hash_in, 'shipToAddress' ) ),
    			'amount' => ( XmlFields::returnArrayValue ( $hash_in, 'amount' ) ),
                'eventType' => XmlFields::returnArrayValue( $hash_in, 'eventType'),
                'accountLogin' => XmlFields::returnArrayValue($hash_in, 'accountLogin'),
                'accountPasshash' => XmlFields::returnArrayValue($hash_in, 'accountPasshash')
    	);
    	$fraudCheckResponse = $this->processRequest($hash_out,$hash_in,"fraudCheck");

    	return $fraudCheckResponse;
    }

    /**
     * @param $hash_in
     * @return \DOMDocument|\SimpleXMLElement
     * @throws exceptions\cnpSDKException
     */
    public function subMerchantCredit($hash_in)
    {
        $hash_out = array (
            'fundingSubmerchantId' => XmlFields::returnArrayValue ( $hash_in, 'fundingSubmerchantId' ),
            'id'=>XmlFields::returnArrayValue($hash_in,'id'),
            'submerchantName' => XmlFields::returnArrayValue ( $hash_in, 'submerchantName' ),
            'fundsTransferId' => XmlFields::returnArrayValue (  $hash_in, 'fundsTransferId'  ),
            'amount' =>  XmlFields::returnArrayValue ( $hash_in, 'amount' ) ,
            'accountInfo' => XmlFields::echeckType ( XmlFields::returnArrayValue ( $hash_in, 'accountInfo' ) ) ,
            'customIdentifier' =>  XmlFields::returnArrayValue ( $hash_in, 'customIdentifier' )

        );
        $subMerchantCreditResponse = $this ->processRequest($hash_out, $hash_in, "submerchantCredit");
        return $subMerchantCreditResponse;
    }

    public function subMerchantCreditCtx($hash_in)
    {
        $hash_out = array (
            'fundingSubmerchantId' => XmlFields::returnArrayValue ( $hash_in, 'fundingSubmerchantId' ),
            'id'=>XmlFields::returnArrayValue($hash_in,'id'),
            'submerchantName' => XmlFields::returnArrayValue ( $hash_in, 'submerchantName' ),
            'fundsTransferId' => XmlFields::returnArrayValue (  $hash_in, 'fundsTransferId'  ),
            'amount' =>  XmlFields::returnArrayValue ( $hash_in, 'amount' ) ,
            'accountInfo' => XmlFields::echeckTypeCtx ( XmlFields::returnArrayValue ( $hash_in, 'accountInfo' ) ) ,
            'customIdentifier' =>  XmlFields::returnArrayValue ( $hash_in, 'customIdentifier' )

        );
        $subMerchantCreditResponse = $this ->processRequest($hash_out, $hash_in, "submerchantCreditCtx");
        return $subMerchantCreditResponse;
    }

    /**
     * @param $hash_in
     * @return \DOMDocument|\SimpleXMLElement
     * @throws exceptions\cnpSDKException
     */
    public function subMerchantDebit($hash_in)
    {
        $hash_out = array (
            'fundingSubmerchantId' => XmlFields::returnArrayValue ( $hash_in, 'fundingSubmerchantId' ),
            'id'=>XmlFields::returnArrayValue($hash_in,'id'),
            'submerchantName' => XmlFields::returnArrayValue ( $hash_in, 'submerchantName' ),
            'fundsTransferId' => XmlFields::returnArrayValue (  $hash_in, 'fundsTransferId'  ),
            'amount' =>  XmlFields::returnArrayValue ( $hash_in, 'amount' ) ,
            'accountInfo' => XmlFields::echeckType ( XmlFields::returnArrayValue ( $hash_in, 'accountInfo' ) ) ,
            'customIdentifier' =>  XmlFields::returnArrayValue ( $hash_in, 'customIdentifier' )

        );
        $subMerchantDebitResponse = $this ->processRequest($hash_out, $hash_in, "submerchantDebit");
        return $subMerchantDebitResponse;
    }

    public function subMerchantDebitCtx($hash_in)
    {
        $hash_out = array (
            'fundingSubmerchantId' => XmlFields::returnArrayValue ( $hash_in, 'fundingSubmerchantId' ),
            'id'=>XmlFields::returnArrayValue($hash_in,'id'),
            'submerchantName' => XmlFields::returnArrayValue ( $hash_in, 'submerchantName' ),
            'fundsTransferId' => XmlFields::returnArrayValue (  $hash_in, 'fundsTransferId'  ),
            'amount' =>  XmlFields::returnArrayValue ( $hash_in, 'amount' ) ,
            'accountInfo' => XmlFields::echeckTypeCtx ( XmlFields::returnArrayValue ( $hash_in, 'accountInfo' ) ) ,
            'customIdentifier' =>  XmlFields::returnArrayValue ( $hash_in, 'customIdentifier' )

        );
        $subMerchantDebitResponse = $this ->processRequest($hash_out, $hash_in, "submerchantDebitCtx");
        return $subMerchantDebitResponse;
    }

    /**
     * @param $hash_in
     * @return \DOMDocument|\SimpleXMLElement
     * @throws exceptions\cnpSDKException
     */
    public function payFacDebit($hash_in)
    {
        $hash_out = array (
            'fundingSubmerchantId' => XmlFields::returnArrayValue ( $hash_in, 'fundingSubmerchantId' ),
            'fundsTransferId' => XmlFields::returnArrayValue (  $hash_in, 'fundsTransferId'  ),
            'amount' =>  XmlFields::returnArrayValue ( $hash_in, 'amount' ) ,
        );
        $payfacDebitResponse = $this ->processRequest($hash_out, $hash_in, 'payFacDebit');
        return $payfacDebitResponse;
    }

    /**
     * @param $hash_in
     * @return \DOMDocument|\SimpleXMLElement
     * @throws exceptions\cnpSDKException
     */
    public function payoutOrgDebit($hash_in)
    {
        $hash_out = array (
            'fundingCustomerId' => XmlFields::returnArrayValue ( $hash_in, 'fundingSubmerchantId' ),
            'fundsTransferId' => XmlFields::returnArrayValue (  $hash_in, 'fundsTransferId'  ),
            'amount' =>  XmlFields::returnArrayValue ( $hash_in, 'amount' ) ,
        );
        $payoutOrgDebitResponse = $this ->processRequest($hash_out, $hash_in, 'payoutOrgDebit');
        return $payoutOrgDebitResponse;
    }

    /**
     * @param $hash_in
     * @return \DOMDocument|\SimpleXMLElement
     * @throws exceptions\cnpSDKException
     */
    public function payFacCredit($hash_in)
    {
        $hash_out = array (
            'fundingSubmerchantId' => XmlFields::returnArrayValue ( $hash_in, 'fundingSubmerchantId' ),
            'fundsTransferId' => XmlFields::returnArrayValue (  $hash_in, 'fundsTransferId'  ),
            'amount' =>  XmlFields::returnArrayValue ( $hash_in, 'amount' ) ,
        );
        $payfacCreditResponse = $this ->processRequest($hash_out, $hash_in, "payFacCredit");
        return $payfacCreditResponse;
    }

    /**
     * @param $hash_in
     * @return \DOMDocument|\SimpleXMLElement
     * @throws exceptions\cnpSDKException
     */
    public function payoutOrgCredit($hash_in)
    {
        $hash_out = array (
            'fundingCustomerId' => XmlFields::returnArrayValue ( $hash_in, 'fundingSubmerchantId' ),
            'fundsTransferId' => XmlFields::returnArrayValue (  $hash_in, 'fundsTransferId'  ),
            'amount' =>  XmlFields::returnArrayValue ( $hash_in, 'amount' ) ,
        );
        $payoutOrgCreditResponse = $this ->processRequest($hash_out, $hash_in, 'payoutOrgCredit');
        return $payoutOrgCreditResponse;
    }

    /**
     * @param $hash_in
     * @return \DOMDocument|\SimpleXMLElement
     * @throws exceptions\cnpSDKException
     */
    public function reserveCredit($hash_in)
    {
        $hash_out = array (
            'fundingSubmerchantId' => XmlFields::returnArrayValue ( $hash_in, 'fundingSubmerchantId' ),
            'fundingCustomerId' => XmlFields::returnArrayValue ( $hash_in, 'fundingCustomerId' ),
            'fundsTransferId' => XmlFields::returnArrayValue (  $hash_in, 'fundsTransferId'  ),
            'amount' =>  XmlFields::returnArrayValue ( $hash_in, 'amount' ) ,
        );
        $reserveCreditResponse = $this ->processRequest($hash_out, $hash_in, "reserveCredit");
        return $reserveCreditResponse;
    }

    /**
     * @param $hash_in
     * @return \DOMDocument|\SimpleXMLElement
     * @throws exceptions\cnpSDKException
     */
    public function reserveDebit($hash_in)
    {
        $hash_out = array (
            'fundingSubmerchantId' => XmlFields::returnArrayValue ( $hash_in, 'fundingSubmerchantId' ),
            'fundingCustomerId' => XmlFields::returnArrayValue ( $hash_in, 'fundingCustomerId' ),
            'fundsTransferId' => XmlFields::returnArrayValue (  $hash_in, 'fundsTransferId'  ),
            'amount' =>  XmlFields::returnArrayValue ( $hash_in, 'amount' ) ,
        );
        $reserveDebitResponse = $this ->processRequest($hash_out, $hash_in, "reserveDebit");
        return $reserveDebitResponse;
    }

    /**
     * @param $hash_in
     * @return \DOMDocument|\SimpleXMLElement
     * @throws exceptions\cnpSDKException
     */
    public function physicalCheckDebit($hash_in)
    {
        $hash_out = array (
            'fundingSubmerchantId' => XmlFields::returnArrayValue ( $hash_in, 'fundingSubmerchantId' ),
            'fundingCustomerId' => XmlFields::returnArrayValue ( $hash_in, 'fundingCustomerId' ),
            'fundsTransferId' => XmlFields::returnArrayValue (  $hash_in, 'fundsTransferId'  ),
            'amount' =>  XmlFields::returnArrayValue ( $hash_in, 'amount' ) ,
        );
        $physicalCheckDebitResponse = $this ->processRequest($hash_out, $hash_in, "physicalCheckDebit");
        return $physicalCheckDebitResponse;
    }

    /**
     * @param $hash_in
     * @return \DOMDocument|\SimpleXMLElement
     * @throws exceptions\cnpSDKException
     */
    public function physicalCheckCredit($hash_in)
    {
        $hash_out = array (
            'fundingSubmerchantId' => XmlFields::returnArrayValue ( $hash_in, 'fundingSubmerchantId' ),
            'fundingCustomerId' => XmlFields::returnArrayValue ( $hash_in, 'fundingCustomerId' ),
            'fundsTransferId' => XmlFields::returnArrayValue (  $hash_in, 'fundsTransferId'  ),
            'amount' =>  XmlFields::returnArrayValue ( $hash_in, 'amount' ) ,
        );
        $physicalCheckCreditResponse = $this ->processRequest($hash_out, $hash_in, "physicalCheckCredit");
        return $physicalCheckCreditResponse;
    }

    /**
     * @param $hash_in
     * @return \DOMDocument|\SimpleXMLElement
     * @throws exceptions\cnpSDKException
     */
    public function vendorCredit($hash_in)
    {
        $hash_out = array (
            'fundingSubmerchantId' => XmlFields::returnArrayValue ( $hash_in, 'fundingSubmerchantId' ),
            'fundingCustomerId' => XmlFields::returnArrayValue ( $hash_in, 'fundingCustomerId' ),
            'vendorName' => XmlFields::returnArrayValue ( $hash_in, 'vendorName' ),
            'fundsTransferId' => XmlFields::returnArrayValue (  $hash_in, 'fundsTransferId'  ),
            'amount' =>  XmlFields::returnArrayValue ( $hash_in, 'amount' ) ,
            'accountInfo' => XmlFields::echeckType ( XmlFields::returnArrayValue ( $hash_in, 'accountInfo' ) ) ,
        );
        $vendorCreditResponse = $this ->processRequest($hash_out, $hash_in, "vendorCredit");
        return $vendorCreditResponse;
    }

    public function vendorCreditCtx($hash_in)
    {
        $hash_out = array (
            'fundingSubmerchantId' => XmlFields::returnArrayValue ( $hash_in, 'fundingSubmerchantId' ),
            'fundingCustomerId' => XmlFields::returnArrayValue ( $hash_in, 'fundingCustomerId' ),
            'vendorName' => XmlFields::returnArrayValue ( $hash_in, 'vendorName' ),
            'fundsTransferId' => XmlFields::returnArrayValue (  $hash_in, 'fundsTransferId'  ),
            'amount' =>  XmlFields::returnArrayValue ( $hash_in, 'amount' ) ,
            'accountInfo' => XmlFields::echeckTypeCtx ( XmlFields::returnArrayValue ( $hash_in, 'accountInfo' ) ) ,
        );
        $vendorCreditResponse = $this ->processRequest($hash_out, $hash_in, "vendorCreditCtx");
        return $vendorCreditResponse;
    }

    /**
     * @param $hash_in
     * @return \DOMDocument|\SimpleXMLElement
     * @throws exceptions\cnpSDKException
     */
    public function customerCredit($hash_in)
    {
        $hash_out = array (
            'fundingCustomerId' => XmlFields::returnArrayValue ( $hash_in, 'fundingCustomerId' ),
            'customerName' => XmlFields::returnArrayValue ( $hash_in, 'customerName' ),
            'fundsTransferId' => XmlFields::returnArrayValue (  $hash_in, 'fundsTransferId'  ),
            'amount' =>  XmlFields::returnArrayValue ( $hash_in, 'amount' ) ,
            'accountInfo' => XmlFields::echeckType ( XmlFields::returnArrayValue ( $hash_in, 'accountInfo' ) ) ,
        );
        $customerCreditResponse = $this ->processRequest($hash_out, $hash_in, "customerCredit");
        return $customerCreditResponse;
    }

    /**
     * @param $hash_in
     * @return \DOMDocument|\SimpleXMLElement
     * @throws exceptions\cnpSDKException
     */
    public function vendorDebit($hash_in)
    {
        $hash_out = array (
            'fundingSubmerchantId' => XmlFields::returnArrayValue ( $hash_in, 'fundingSubmerchantId' ),
            'vendorName' => XmlFields::returnArrayValue ( $hash_in, 'vendorName' ),
            'fundsTransferId' => XmlFields::returnArrayValue (  $hash_in, 'fundsTransferId'  ),
            'amount' =>  XmlFields::returnArrayValue ( $hash_in, 'amount' ) ,
            'accountInfo' => XmlFields::echeckType ( XmlFields::returnArrayValue ( $hash_in, 'accountInfo' ) ) ,
        );
        $vendorDebitResponse = $this ->processRequest($hash_out, $hash_in, "vendorDebit");
        return $vendorDebitResponse;
    }

    public function vendorDebitCtx($hash_in)
    {
        $hash_out = array (
            'fundingSubmerchantId' => XmlFields::returnArrayValue ( $hash_in, 'fundingSubmerchantId' ),
            'vendorName' => XmlFields::returnArrayValue ( $hash_in, 'vendorName' ),
            'fundsTransferId' => XmlFields::returnArrayValue (  $hash_in, 'fundsTransferId'  ),
            'amount' =>  XmlFields::returnArrayValue ( $hash_in, 'amount' ) ,
            'accountInfo' => XmlFields::echeckTypeCtx ( XmlFields::returnArrayValue ( $hash_in, 'accountInfo' ) ) ,
        );
        $vendorDebitResponse = $this ->processRequest($hash_out, $hash_in, "vendorDebitCtx");
        return $vendorDebitResponse;
    }

    /**
     * @param $hash_in
     * @return \DOMDocument|\SimpleXMLElement
     * @throws exceptions\cnpSDKException
     */
    public function customerDebit($hash_in)
    {
        $hash_out = array (
            'fundingCustomerId' => XmlFields::returnArrayValue ( $hash_in, 'fundingCustomerId' ),
            'customerName' => XmlFields::returnArrayValue ( $hash_in, 'customerName' ),
            'fundsTransferId' => XmlFields::returnArrayValue (  $hash_in, 'fundsTransferId'  ),
            'amount' =>  XmlFields::returnArrayValue ( $hash_in, 'amount' ) ,
            'accountInfo' => XmlFields::echeckType ( XmlFields::returnArrayValue ( $hash_in, 'accountInfo' ) ) ,
        );
        $customreDebitResponse = $this ->processRequest($hash_out, $hash_in, "customerDebit");
        return $customreDebitResponse;
    }

    /**
     * @param $hash_in
     * @return \DOMDocument|\SimpleXMLElement
     * @throws exceptions\cnpSDKException
     */
    public function fundingInstructionVoid($hash_in)
    {
        $hash_out = array (
            'cnpTxnId' => XmlFields::returnArrayValue ( $hash_in, 'cnpTxnId' ),
        );
        $fundingInstructionVoidResponse = $this ->processRequest($hash_out, $hash_in, "fundingInstructionVoid");
        return $fundingInstructionVoidResponse;
    }

    /**
     * @param $hash_in
     * @return \DOMDocument|\SimpleXMLElement
     * @throws exceptions\cnpSDKException
     */
    public function fastAccessFunding($hash_in)
    {
        $hash_out = array (
            'fundingSubmerchantId' => XmlFields::returnArrayValue ( $hash_in, 'fundingSubmerchantId' ),
            'submerchantName' => XmlFields::returnArrayValue ( $hash_in, 'submerchantName' ),
            'fundsTransferId' => XmlFields::returnArrayValue (  $hash_in, 'fundsTransferId'  ),
            'amount' =>  XmlFields::returnArrayValue ( $hash_in, 'amount' ) ,
            // new
            'disbursementType' =>  XmlFields::returnArrayValue ( $hash_in, 'disbursementType' ) ,
            //
            'card'=> (XmlFields::cardType(XmlFields::returnArrayValue($hash_in,'card'))),
            'token'=>(XmlFields::cardTokenType(XmlFields::returnArrayValue($hash_in,'token'))),
            'paypage'=>(XmlFields::cardPaypageType(XmlFields::returnArrayValue($hash_in,'paypage'))),
            'fundingCustomerId' => XmlFields::returnArrayValue ( $hash_in, 'fundingCustomerId' ),
            'customerName' => XmlFields::returnArrayValue ( $hash_in, 'customerName' ),
        );
        $fastAccessFunding = $this ->processRequest($hash_out, $hash_in, "fastAccessFunding");
        return $fastAccessFunding;
    }

    /**
     * @param $hash_in
     * @return array
     */
    private static function overrideConfig($hash_in)
    {
        $hash_config = array();
        $names = explode(',', CNP_CONFIG_LIST);

        foreach ($names as $name) {
            if (array_key_exists($name, $hash_in)) {
                $hash_config[$name] = XmlFields::returnArrayValue($hash_in, $name);
            }
        }

        return $hash_config;
    }

    /**
     * @param $hash_in
     * @param $hash_out
     * @return mixed
     */
    private static function getOptionalAttributes($hash_in,$hash_out)
    {
        if (isset($hash_in['merchantSdk'])) {
            $hash_out['merchantSdk'] = XmlFields::returnArrayValue($hash_in,'merchantSdk');
        } else {
            $hash_out['merchantSdk'] = CURRENT_SDK_VERSION;
        }
        if (isset($hash_in['id'])) {
            $hash_out['id'] = XmlFields::returnArrayValue($hash_in,'id');
        }
        if (isset($hash_in['customerId'])) {
            $hash_out['customerId'] = XmlFields::returnArrayValue($hash_in,'customerId');
        }
        if (isset($hash_in['loggedInUser'])) {
            $hash_out['loggedInUser'] = XmlFields::returnArrayValue($hash_in,'loggedInUser');
        }

        return $hash_out;
    }

    /**
     * @param $hash_out
     * @param $hash_in
     * @param $type
     * @param null $choice1
     * @param null $choice2
     * @return \DOMDocument|\SimpleXMLElement
     * @throws exceptions\cnpSDKException
     */
    private function processRequest($hash_out, $hash_in, $type, $choice1 = null, $choice2 = null)
    {
        $hash_config = CnpOnlineRequest::overrideConfig($hash_in);
        $hash = CnpOnlineRequest::getOptionalAttributes($hash_in,$hash_out);
        $request = Obj2xml::toXml($hash,$hash_config, $type);


        if(Checker::validateXML($request)){
            $request = str_replace ("submerchantDebitCtx","submerchantDebit",$request);
            $request = str_replace ("submerchantCreditCtx","submerchantCredit",$request);
            $request = str_replace ("vendorCreditCtx","vendorCredit",$request);
            $request = str_replace ("vendorDebitCtx","vendorDebit",$request);

            $cnpOnlineResponse = $this->newXML->request($request,$hash_config,$this->useSimpleXml);
        }

        return $cnpOnlineResponse;
    }
    
}
