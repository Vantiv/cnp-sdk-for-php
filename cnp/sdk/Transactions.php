<?php
namespace cnp\sdk;
class Transactions {
    public static function createSaleHash($hash_in) {
        $hash_out = array (
            'cnpTxnId' => XmlFields::returnArrayValue ( $hash_in, 'cnpTxnId' ),
            'orderId' => Checker::requiredField ( XmlFields::returnArrayValue ( $hash_in, 'orderId' ) ),
            'id'=>Checker::requiredField(XmlFields::returnArrayValue($hash_in,'id')),
            'amount' => Checker::requiredField ( XmlFields::returnArrayValue ( $hash_in, 'amount' ) ),
            'secondaryAmount' => XmlFields::returnArrayValue ( $hash_in, 'secondaryAmount' ),
            'surchargeAmount' => XmlFields::returnArrayValue ( $hash_in, 'surchargeAmount' ),
            'orderSource' => Checker::requiredField ( XmlFields::returnArrayValue ( $hash_in, 'orderSource' ) ),
            'customerInfo' => XmlFields::customerInfo ( XmlFields::returnArrayValue ( $hash_in, 'customerInfo' ) ),
            'billToAddress' => XmlFields::contact ( XmlFields::returnArrayValue ( $hash_in, 'billToAddress' ) ),
            'shipToAddress' => XmlFields::contact ( XmlFields::returnArrayValue ( $hash_in, 'shipToAddress' ) ),
            'card' => XmlFields::cardType ( XmlFields::returnArrayValue ( $hash_in, 'card' ) ),
            'paypal' => XmlFields::payPal ( XmlFields::returnArrayValue ( $hash_in, 'paypal' ) ),
            'token' => XmlFields::cardTokenType ( XmlFields::returnArrayValue ( $hash_in, 'token' ) ),
            'paypage' => XmlFields::cardPaypageType ( XmlFields::returnArrayValue ( $hash_in, 'paypage' ) ),
            'applepay' => (XmlFields::applepayType ( XmlFields::returnArrayValue ( $hash_in, 'applepay' ) )),
            'sepaDirectDebit'=>(XmlFields::sepaDirectDebitType(XmlFields::returnArrayValue($hash_in,'sepaDirectDebit'))),
            'ideal'=>(XmlFields::idealType(XmlFields::returnArrayValue($hash_in,'ideal'))),
            'giropay'=>(XmlFields::giropayType(XmlFields::returnArrayValue($hash_in,'giropay'))),
            'sofort'=>(XmlFields::sofortType(XmlFields::returnArrayValue($hash_in,'sofort'))),
            'billMeLaterRequest' => XmlFields::billMeLaterRequest ( XmlFields::returnArrayValue ( $hash_in, 'billMeLaterRequest' ) ),
            'fraudCheck' => XmlFields::fraudCheckType ( XmlFields::returnArrayValue ( $hash_in, 'fraudCheck' ) ),
            'cardholderAuthentication' => XmlFields::fraudCheckType ( XmlFields::returnArrayValue ( $hash_in, 'cardholderAuthentication' ) ),
            'customBilling' => XmlFields::customBilling ( XmlFields::returnArrayValue ( $hash_in, 'customBilling' ) ),
            'taxBilling' => XmlFields::taxBilling ( XmlFields::returnArrayValue ( $hash_in, 'taxBilling' ) ),
            'enhancedData' => XmlFields::enhancedData ( XmlFields::returnArrayValue ( $hash_in, 'enhancedData' ) ),
            'processingInstructions' => XmlFields::processingInstructions ( XmlFields::returnArrayValue ( $hash_in, 'processingInstructions' ) ),
            'pos' => XmlFields::pos ( XmlFields::returnArrayValue ( $hash_in, 'pos' ) ),
            'payPalOrderComplete' => XmlFields::returnArrayValue ( $hash_in, 'paypalOrderComplete' ),
            'payPalNotes' => XmlFields::returnArrayValue ( $hash_in, 'paypalNotesType' ),
            'amexAggregatorData' => XmlFields::amexAggregatorData ( XmlFields::returnArrayValue ( $hash_in, 'amexAggregatorData' ) ),
            'allowPartialAuth' => XmlFields::returnArrayValue ( $hash_in, 'allowPartialAuth' ),
            'healthcareIIAS' => XmlFields::healthcareIIAS ( XmlFields::returnArrayValue ( $hash_in, 'healthcareIIAS' ) ),
            'lodgingInfo' => XmlFields::lodgingInfo(XmlFields::returnArrayValue($hash_in, 'lodgingInfo')),
            'filtering' => XmlFields::filteringType ( XmlFields::returnArrayValue ( $hash_in, 'filtering' ) ),
            'merchantData' => XmlFields::merchantData ( XmlFields::returnArrayValue ( $hash_in, 'merchantData' ) ),
            'recyclingRequest' => XmlFields::recyclingRequestType ( XmlFields::returnArrayValue ( $hash_in, 'recyclingRequest' ) ),
            'fraudFilterOverride' => XmlFields::returnArrayValue ( $hash_in, 'fraudFilterOverride' ),
            'recurringRequest' => XmlFields::recurringRequestType ( XmlFields::returnArrayValue ( $hash_in, 'recurringRequest' ) ),
            'cnpInternalRecurringRequest' => XmlFields::cnpInternalRecurringRequestType ( XmlFields::returnArrayValue ( $hash_in, 'cnpInternalRecurringRequest' ) ),
            'debtRepayment' => XmlFields::returnArrayValue ( $hash_in, 'debtRepayment' ),
            'advancedFraudChecks'=>XmlFields::advancedFraudChecksType(XmlFields::returnArrayValue($hash_in,'advancedFraudChecks')),
            'wallet' => XmlFields::wallet( XmlFields::returnArrayValue ( $hash_in, 'wallet' )),
            'processingType' => XmlFields::returnArrayValue ( $hash_in, 'processingType' ),
            'originalNetworkTransactionId' => XmlFields::returnArrayValue ( $hash_in, 'originalNetworkTransactionId' ),
            'originalTransactionAmount' => XmlFields::returnArrayValue ( $hash_in, 'originalTransactionAmount' ),
            'pinlessDebitRequest' => XmlFields::pinlessDebitRequest(XmlFields::returnArrayValue ( $hash_in, 'pinlessDebitRequest' ))
        );

        return $hash_out;
    }
    public static function createAuthHash($hash_in) {
        $hash_out = array (
            'orderId' => Checker::requiredField ( XmlFields::returnArrayValue ( $hash_in, 'orderId' ) ),
            'amount' => Checker::requiredField ( XmlFields::returnArrayValue ( $hash_in, 'amount' ) ),
            'id'=>Checker::requiredField(XmlFields::returnArrayValue($hash_in,'id')),
            'surchargeAmount' => XmlFields::returnArrayValue ( $hash_in, 'surchargeAmount' ),
            'secondaryAmount' => XmlFields::returnArrayValue ( $hash_in, 'secondaryAmount' ),
            'orderSource' => Checker::requiredField ( XmlFields::returnArrayValue ( $hash_in, 'orderSource' ) ),
            'customerInfo' => (XmlFields::customerInfo ( XmlFields::returnArrayValue ( $hash_in, 'customerInfo' ) )),
            'billToAddress' => (XmlFields::contact ( XmlFields::returnArrayValue ( $hash_in, 'billToAddress' ) )),
            'shipToAddress' => (XmlFields::contact ( XmlFields::returnArrayValue ( $hash_in, 'shipToAddress' ) )),
            'card' => (XmlFields::cardType ( XmlFields::returnArrayValue ( $hash_in, 'card' ) )),
            'paypal' => (XmlFields::payPal ( XmlFields::returnArrayValue ( $hash_in, 'paypal' ) )),
            'token' => (XmlFields::cardTokenType ( XmlFields::returnArrayValue ( $hash_in, 'token' ) )),
            'paypage' => (XmlFields::cardPaypageType ( XmlFields::returnArrayValue ( $hash_in, 'paypage' ) )),
            'applepay' => (XmlFields::applepayType ( XmlFields::returnArrayValue ( $hash_in, 'applepay' ) )),
            'billMeLaterRequest' => (XmlFields::billMeLaterRequest ( XmlFields::returnArrayValue ( $hash_in, 'billMeLaterRequest' ) )),
            'cardholderAuthentication' => (XmlFields::fraudCheckType ( XmlFields::returnArrayValue ( $hash_in, 'cardholderAuthentication' ) )),
            'processingInstructions' => (XmlFields::processingInstructions ( XmlFields::returnArrayValue ( $hash_in, 'processingInstructions' ) )),
            'pos' => (XmlFields::pos ( XmlFields::returnArrayValue ( $hash_in, 'pos' ) )),
            'customBilling' => (XmlFields::customBilling ( XmlFields::returnArrayValue ( $hash_in, 'customBilling' ) )),
            'taxBilling' => (XmlFields::taxBilling ( XmlFields::returnArrayValue ( $hash_in, 'taxBilling' ) )),
            'enhancedData' => (XmlFields::enhancedData ( XmlFields::returnArrayValue ( $hash_in, 'enhancedData' ) )),
            'amexAggregatorData' => (XmlFields::amexAggregatorData ( XmlFields::returnArrayValue ( $hash_in, 'amexAggregatorData' ) )),
            'allowPartialAuth' => XmlFields::returnArrayValue ( $hash_in, 'allowPartialAuth' ),
            'healthcareIIAS' => (XmlFields::healthcareIIAS ( XmlFields::returnArrayValue ( $hash_in, 'healthcareIIAS' ) )),
            'lodgingInfo' => XmlFields::lodgingInfo(XmlFields::returnArrayValue($hash_in, 'lodgingInfo')),
            'filtering' => (XmlFields::filteringType ( XmlFields::returnArrayValue ( $hash_in, 'filtering' ) )),
            'merchantData' => (XmlFields::merchantData ( XmlFields::returnArrayValue ( $hash_in, 'merchantData' ) )),
            'recyclingRequest' => (XmlFields::recyclingRequestType ( XmlFields::returnArrayValue ( $hash_in, 'recyclingRequest' ) )),
            'fraudFilterOverride' => XmlFields::returnArrayValue ( $hash_in, 'fraudFilterOverride' ),
            'recurringRequest' => XmlFields::recurringRequestType ( XmlFields::returnArrayValue ( $hash_in, 'recurringRequest' ) ),
            'debtRepayment' => XmlFields::returnArrayValue ( $hash_in, 'debtRepayment' ),
            'advancedFraudChecks'=>XmlFields::advancedFraudChecksType(XmlFields::returnArrayValue($hash_in,'advancedFraudChecks'))
        );

        return $hash_out;
    }
    public static function createAuthReversalHash($hash_in) {
        $hash_out = array (
            'cnpTxnId' => Checker::requiredField ( XmlFields::returnArrayValue ( $hash_in, 'cnpTxnId' ) ),
            'id'=>Checker::requiredField(XmlFields::returnArrayValue($hash_in,'id')),
            'amount' => XmlFields::returnArrayValue ( $hash_in, 'amount' ),
            'surchargeAmount' => XmlFields::returnArrayValue ( $hash_in, 'surchargeAmount' ),
            'payPalNotes' => XmlFields::returnArrayValue ( $hash_in, 'payPalNotes' ),
            'actionReason' => XmlFields::returnArrayValue ( $hash_in, 'actionReason' )
        );

        return $hash_out;
    }
    public static function createGiftCardAuthReversalHash($hash_in) {
        $hash_out = array (
            'cnpTxnId' => Checker::requiredField(XmlFields::returnArrayValue($hash_in,'cnpTxnId')),
            'id'=>Checker::requiredField(XmlFields::returnArrayValue($hash_in,'id')),
            'card'=> XmlFields::giftCardCardType(XmlFields::returnArrayValue($hash_in,'card')),
            'originalRefCode' =>XmlFields::returnArrayValue($hash_in,'originalRefCode'),
            'originalAmount' =>XmlFields::returnArrayValue($hash_in,'originalAmount'),
            'originalTxnTime'=>XmlFields::returnArrayValue($hash_in,'originalTxnTime'),
            'originalSystemTraceId'=>XmlFields::returnArrayValue($hash_in,'originalSystemTraceId'),
            'originalSequenceNumber'=>XmlFields::returnArrayValue($hash_in,'originalSequenceNumber')
        );

        return $hash_out;
    }
    public static function createCreditHash($hash_in) {
        $hash_out = array (
            'cnpTxnId' => XmlFields::returnArrayValue ( $hash_in, 'cnpTxnId' ),
            'id'=>Checker::requiredField(XmlFields::returnArrayValue($hash_in,'id')),
            'orderId' => XmlFields::returnArrayValue ( $hash_in, 'orderId' ),
            'amount' => XmlFields::returnArrayValue ( $hash_in, 'amount' ),
            'secondaryAmount' => XmlFields::returnArrayValue ( $hash_in, 'secondaryAmount' ),
            'surchargeAmount' => XmlFields::returnArrayValue ( $hash_in, 'surchargeAmount' ),
            'orderSource' => XmlFields::returnArrayValue ( $hash_in, 'orderSource' ),
            'billToAddress' => XmlFields::contact ( XMLFields::returnArrayValue ( $hash_in, 'billToAddress' ) ),
            'card' => XmlFields::cardType ( XMLFields::returnArrayValue ( $hash_in, 'card' ) ),
            'paypal' => XmlFields::credit_payPal ( XMLFields::returnArrayValue ( $hash_in, 'paypal' ) ),
            'token' => XmlFields::cardTokenType ( XMLFields::returnArrayValue ( $hash_in, 'token' ) ),
            'paypage' => XmlFields::cardPaypageType ( XMLFields::returnArrayValue ( $hash_in, 'paypage' ) ),
            'customBilling' => XmlFields::customBilling ( XMLFields::returnArrayValue ( $hash_in, 'customBilling' ) ),
            'taxBilling' => XmlFields::taxBilling ( XMLFields::returnArrayValue ( $hash_in, 'taxBilling' ) ),
            'billMeLaterRequest' => XmlFields::billMeLaterRequest ( XMLFields::returnArrayValue ( $hash_in, 'billMeLaterRequest' ) ),
            'enhancedData' => XmlFields::enhancedData ( XMLFields::returnArrayValue ( $hash_in, 'enhancedData' ) ),
            'lodgingInfo' => XmlFields::lodgingInfo(XmlFields::returnArrayValue($hash_in, 'lodgingInfo')),
            'processingInstructions' => XmlFields::processingInstructions ( XMLFields::returnArrayValue ( $hash_in, 'processingInstructions' ) ),
            'pos' => XmlFields::pos ( XMLFields::returnArrayValue ( $hash_in, 'pos' ) ),
            'amexAggregatorData' => XmlFields::amexAggregatorData ( XMLFields::returnArrayValue ( $hash_in, 'amexAggregatorData' ) ),
            'payPalNotes' => XmlFields::returnArrayValue ( $hash_in, 'payPalNotes' ),
            'actionReason' => XmlFields::returnArrayValue ( $hash_in, 'actionReason' )
        );

        return $hash_out;
    }
    public static function createGiftCardCreditHash($hash_in) {
        $hash_out = array(
            'cnpTxnId' => XmlFields::returnArrayValue($hash_in, 'cnpTxnId'),
            'orderId' =>XmlFields::returnArrayValue($hash_in, 'orderId'),
            'id'=>Checker::requiredField(XmlFields::returnArrayValue($hash_in,'id')),
            'creditAmount' =>XmlFields::returnArrayValue($hash_in, 'creditAmount'),
            'orderSource'=>XmlFields::returnArrayValue($hash_in, 'orderSource'),
            'card'=>XmlFields::giftCardCardType(XMLFields::returnArrayValue($hash_in, 'card'))
        );

        return $hash_out;
    }
    public static function createRegisterTokenHash($hash_in) {
        $hash_out = array (
            'orderId' => XmlFields::returnArrayValue ( $hash_in, 'orderId' ),
            'id'=>Checker::requiredField(XmlFields::returnArrayValue($hash_in,'id')),
            'accountNumber' => XmlFields::returnArrayValue ( $hash_in, 'accountNumber' ),
            'echeckForToken' => XmlFields::echeckForTokenType ( XmlFields::returnArrayValue ( $hash_in, 'echeckForToken' ) ),
            'paypageRegistrationId' => XmlFields::returnArrayValue ( $hash_in, 'paypageRegistrationId' ),
            'applepay' => (XmlFields::applepayType ( XmlFields::returnArrayValue ( $hash_in, 'applepay' ) )),
            'cardValidationNum' => XmlFields::returnArrayValue ( $hash_in, 'cardValidationNum' )
        );

        return $hash_out;
    }
    public static function createForceCaptureHash($hash_in) {
        $hash_out = array (
            'orderId' => Checker::requiredField ( XmlFields::returnArrayValue ( $hash_in, 'orderId' ) ),
            'id'=>Checker::requiredField(XmlFields::returnArrayValue($hash_in,'id')),
            'amount' => XmlFields::returnArrayValue ( $hash_in, 'amount' ),
            'secondaryAmount' => XmlFields::returnArrayValue ( $hash_in, 'secondaryAmount' ),
            'surchargeAmount' => XmlFields::returnArrayValue ( $hash_in, 'surchargeAmount' ),
            'orderSource' => Checker::requiredField ( XmlFields::returnArrayValue ( $hash_in, 'orderSource' ) ),
            'billToAddress' => XmlFields::contact ( XmlFields::returnArrayValue ( $hash_in, 'billToAddress' ) ),
            'card' => XmlFields::cardType ( XmlFields::returnArrayValue ( $hash_in, 'card' ) ),
            'token' => XmlFields::cardTokenType ( XmlFields::returnArrayValue ( $hash_in, 'token' ) ),
            'paypage' => XmlFields::cardPaypageType ( XmlFields::returnArrayValue ( $hash_in, 'paypage' ) ),
            'customBilling' => XmlFields::customBilling ( XmlFields::returnArrayValue ( $hash_in, 'customBilling' ) ),
            'taxBilling' => XmlFields::taxBilling ( XmlFields::returnArrayValue ( $hash_in, 'taxBilling' ) ),
            'enhancedData' => XmlFields::enhancedData ( XmlFields::returnArrayValue ( $hash_in, 'enhancedData' ) ),
            'lodgingInfo' => XmlFields::lodgingInfo(XmlFields::returnArrayValue($hash_in, 'lodgingInfo')),
            'processingInstructions' => XmlFields::processingInstructions ( XmlFields::returnArrayValue ( $hash_in, 'processingInstructions' ) ),
            'pos' => XmlFields::pos ( XmlFields::returnArrayValue ( $hash_in, 'pos' ) ),
            'amexAggregatorData' => XmlFields::amexAggregatorData ( XmlFields::returnArrayValue ( $hash_in, 'amexAggregatorData' ) ),
            'merchantData' => (XmlFields::merchantData ( XmlFields::returnArrayValue ( $hash_in, 'merchantData' ) )),
            'debtRepayment' => XmlFields::returnArrayValue ( $hash_in, 'debtRepayment' )
        );

        return $hash_out;
    }
    public static function createCaptureHash($hash_in) {
        $hash_out = array (
            'partial' => XmlFields::returnArrayValue ( $hash_in, 'partial' ),
            'id'=>Checker::requiredField(XmlFields::returnArrayValue($hash_in,'id')),
            'cnpTxnId' => Checker::requiredField ( XmlFields::returnArrayValue ( $hash_in, 'cnpTxnId' ) ),
            'amount' => (XmlFields::returnArrayValue ( $hash_in, 'amount' )),
            'surchargeAmount' => XmlFields::returnArrayValue ( $hash_in, 'surchargeAmount' ),
            'enhancedData' => XmlFields::enhancedData ( XmlFields::returnArrayValue ( $hash_in, 'enhancedData' ) ),
            'processingInstructions' => XmlFields::processingInstructions ( XmlFields::returnArrayValue ( $hash_in, 'processingInstructions' ) ),
            'payPalOrderComplete' => XmlFields::returnArrayValue ( $hash_in, 'payPalOrderComplete' ),
            'payPalNotes' => XmlFields::returnArrayValue ( $hash_in, 'payPalNotes' ),
            'lodgingInfo' => XmlFields::lodgingInfo(XmlFields::returnArrayValue($hash_in, 'lodgingInfo')),
            'pin' => XmlFields::returnArrayValue($hash_in, 'pin', 12)
        );

        return $hash_out;
    }
    public static function createGiftCardCaptureHash($hash_in) {
        $hash_out = array (
            'cnpTxnId' => Checker::requiredField ( XmlFields::returnArrayValue ( $hash_in, 'cnpTxnId' ) ),
            'id' => Checker::requiredField ( XmlFields::returnArrayValue ( $hash_in, 'id' ) ),
            'captureAmount' => XmlFields::returnArrayValue ( $hash_in, 'captureAmount' ),
            'card' => XmlFields::giftCardCardType ( XmlFields::returnArrayValue ( $hash_in, 'card' ) ),
            'originalRefCode' => XmlFields::returnArrayValue ( $hash_in, 'originalRefCode' ),
            'originalAmount' => XmlFields::returnArrayValue ( $hash_in, 'originalAmount' ),
            'originalTxnTime' => XmlFields::returnArrayValue ( $hash_in, 'originalTxnTime' )
        );

        return $hash_out;
    }
    public static function createCaptureGivenAuthHash($hash_in) {
        $hash_out = array (
            'orderId' => Checker::requiredField ( XmlFields::returnArrayValue ( $hash_in, 'orderId' ) ),
            'id'=>Checker::requiredField(XmlFields::returnArrayValue($hash_in,'id')),
            'authInformation' => XmlFields::authInformation ( XmlFields::returnArrayValue ( $hash_in, 'authInformation' ) ),
            'amount' => Checker::requiredField ( XmlFields::returnArrayValue ( $hash_in, 'amount' ) ),
            'secondaryAmount' => XmlFields::returnArrayValue ( $hash_in, 'secondaryAmount' ),
            'surchargeAmount' => XmlFields::returnArrayValue ( $hash_in, 'surchargeAmount' ),
            'orderSource' => Checker::requiredField ( XmlFields::returnArrayValue ( $hash_in, 'orderSource' ) ),
            'billToAddress' => XmlFields::contact ( XmlFields::returnArrayValue ( $hash_in, 'billToAddress' ) ),
            'shipToAddress' => XmlFields::contact ( XmlFields::returnArrayValue ( $hash_in, 'shipToAddress' ) ),
            'card' => XmlFields::cardType ( XmlFields::returnArrayValue ( $hash_in, 'card' ) ),
            'token' => XmlFields::cardTokenType ( XmlFields::returnArrayValue ( $hash_in, 'token' ) ),
            'paypage' => XmlFields::cardPaypageType ( XmlFields::returnArrayValue ( $hash_in, 'paypage' ) ),
            'customBilling' => XmlFields::customBilling ( XmlFields::returnArrayValue ( $hash_in, 'customBilling' ) ),
            'taxBilling' => XmlFields::taxBilling ( XmlFields::returnArrayValue ( $hash_in, 'taxBilling' ) ),
            'billMeLaterRequest' => XmlFields::billMeLaterRequest ( XmlFields::returnArrayValue ( $hash_in, 'billMeLaterRequest' ) ),
            'enhancedData' => XmlFields::enhancedData ( XmlFields::returnArrayValue ( $hash_in, 'enhancedData' ) ),
            'lodgingInfo' => XmlFields::lodgingInfo(XmlFields::returnArrayValue($hash_in, 'lodgingInfo')),
            'processingInstructions' => XmlFields::processingInstructions ( XmlFields::returnArrayValue ( $hash_in, 'processingInstructions' ) ),
            'pos' => XmlFields::pos ( XmlFields::returnArrayValue ( $hash_in, 'pos' ) ),
            'amexAggregatorData' => XmlFields::amexAggregatorData ( XmlFields::returnArrayValue ( $hash_in, 'amexAggregatorData' ) ),
            'merchantData' => (XmlFields::merchantData ( XmlFields::returnArrayValue ( $hash_in, 'merchantData' ) )),
            'debtRepayment' => XmlFields::returnArrayValue ( $hash_in, 'debtRepayment' )
        );

        return $hash_out;
    }
    public static function createEcheckRedepositHash($hash_in) {
        $hash_out = array (
            'cnpTxnId' => Checker::requiredField ( XmlFields::returnArrayValue ( $hash_in, 'cnpTxnId' ) ),
            'id'=>Checker::requiredField(XmlFields::returnArrayValue($hash_in,'id')),
            'echeck' => XmlFields::echeckType ( XmlFields::returnArrayValue ( $hash_in, 'echeck' ) ),
            'echeckToken' => XmlFields::echeckTokenType ( XmlFields::returnArrayValue ( $hash_in, 'echeckToken' ) ),
            'merchantData' => (XmlFields::merchantData ( XmlFields::returnArrayValue ( $hash_in, 'merchantData' ) ))
        );

        return $hash_out;
    }
    public static function createEcheckSaleHash($hash_in) {
        $hash_out = array (
            'cnpTxnId' => XmlFields::returnArrayValue ( $hash_in, 'cnpTxnId' ),
            'id'=>Checker::requiredField(XmlFields::returnArrayValue($hash_in,'id')),
            'orderId' => XmlFields::returnArrayValue ( $hash_in, 'orderId' ),
            'verify' => XmlFields::returnArrayValue ( $hash_in, 'verify' ),
            'amount' => XmlFields::returnArrayValue ( $hash_in, 'amount' ),
            'secondaryAmount' => XmlFields::returnArrayValue ( $hash_in, 'secondaryAmount' ),
            'orderSource' => XmlFields::returnArrayValue ( $hash_in, 'orderSource' ),
            'billToAddress' => XmlFields::contact ( XmlFields::returnArrayValue ( $hash_in, 'billToAddress' ) ),
            'shipToAddress' => XmlFields::contact ( XmlFields::returnArrayValue ( $hash_in, 'shipToAddress' ) ),
            'echeck' => XmlFields::echeckType ( XmlFields::returnArrayValue ( $hash_in, 'echeck' ) ),
            'echeckToken' => XmlFields::echeckTokenType ( XmlFields::returnArrayValue ( $hash_in, 'echeckToken' ) ),
            'customBilling' => XmlFields::customBilling ( XmlFields::returnArrayValue ( $hash_in, 'customBilling' ) )
        );

        return $hash_out;
    }
    public static function createEcheckCreditHash($hash_in) {
        $hash_out = array (
            'cnpTxnId' => XmlFields::returnArrayValue ( $hash_in, 'cnpTxnId' ),
            'id'=>Checker::requiredField(XmlFields::returnArrayValue($hash_in,'id')),
            'orderId' => XmlFields::returnArrayValue ( $hash_in, 'orderId' ),
            'amount' => XmlFields::returnArrayValue ( $hash_in, 'amount' ),
            'secondaryAmount' => XmlFields::returnArrayValue ( $hash_in, 'secondaryAmount' ),
            'orderSource' => XmlFields::returnArrayValue ( $hash_in, 'orderSource' ),
            'billToAddress' => XmlFields::contact ( XmlFields::returnArrayValue ( $hash_in, 'billToAddress' ) ),
            'echeck' => XmlFields::echeckType ( XmlFields::returnArrayValue ( $hash_in, 'echeck' ) ),
            'echeckToken' => XmlFields::echeckTokenType ( XmlFields::returnArrayValue ( $hash_in, 'echeckToken' ) ),
            'customBilling' => XmlFields::customBilling ( XmlFields::returnArrayValue ( $hash_in, 'customBilling' ) )
        );

        return $hash_out;
    }
    public static function createEcheckPreNoteSaleHash($hash_in) {
        $hash_out = array (

            'orderId' => XmlFields::returnArrayValue ( $hash_in, 'orderId' ),
            'id'=>Checker::requiredField(XmlFields::returnArrayValue($hash_in,'id')),
            'orderSource' => XmlFields::returnArrayValue ( $hash_in, 'orderSource' ),
            'billToAddress' => XmlFields::contact ( XmlFields::returnArrayValue ( $hash_in, 'billToAddress' ) ),
            'echeck' => XmlFields::echeckType ( XmlFields::returnArrayValue ( $hash_in, 'echeck' ) ),
            'merchantData' => XmlFields::merchantData ( XmlFields::returnArrayValue ( $hash_in, 'merchantData' ) )
        );
        return $hash_out;
    }
    public static function createEcheckPreNoteCreditHash($hash_in) {
        $hash_out = array (

            'orderId' => XmlFields::returnArrayValue ( $hash_in, 'orderId' ),
            'id'=>Checker::requiredField(XmlFields::returnArrayValue($hash_in,'id')),
            'orderSource' => XmlFields::returnArrayValue ( $hash_in, 'orderSource' ),
            'billToAddress' => XmlFields::contact ( XmlFields::returnArrayValue ( $hash_in, 'billToAddress' ) ),
            'echeck' => XmlFields::echeckType ( XmlFields::returnArrayValue ( $hash_in, 'echeck' ) ),
            'merchantData' => XmlFields::merchantData ( XmlFields::returnArrayValue ( $hash_in, 'merchantData' ) )
        );
        return $hash_out;
    }
    public static function createSubmerchantCreditHash($hash_in) {
        $hash_out = array (
            'fundingSubmerchantId' => XmlFields::returnArrayValue ( $hash_in, 'fundingSubmerchantId' ),
            'id'=>Checker::requiredField(XmlFields::returnArrayValue($hash_in,'id')),
            'submerchantName' => XmlFields::returnArrayValue ( $hash_in, 'submerchantName' ),
            'fundsTransferId' => XmlFields::returnArrayValue (  $hash_in, 'fundsTransferId'  ),
            'amount' =>  XmlFields::returnArrayValue ( $hash_in, 'amount' ) ,
            'accountInfo' => XmlFields::echeckType ( XmlFields::returnArrayValue ( $hash_in, 'accountInfo' ) ) ,
            'customIdentifier' =>  XmlFields::returnArrayValue ( $hash_in, 'customIdentifier' )

        );
        return $hash_out;
    }
    public static function createSubmerchantDebitHash($hash_in) {
        $hash_out = array (
            'fundingSubmerchantId' => XmlFields::returnArrayValue ( $hash_in, 'fundingSubmerchantId' ),
            'id'=>Checker::requiredField(XmlFields::returnArrayValue($hash_in,'id')),
            'submerchantName' => XmlFields::returnArrayValue ( $hash_in, 'submerchantName' ),
            'fundsTransferId' => XmlFields::returnArrayValue (  $hash_in, 'fundsTransferId'  ),
            'amount' =>  XmlFields::returnArrayValue ( $hash_in, 'amount' ) ,
            'accountInfo' => XmlFields::echeckType ( XmlFields::returnArrayValue ( $hash_in, 'accountInfo' ) ) ,
            'customIdentifier' =>  XmlFields::returnArrayValue ( $hash_in, 'customIdentifier' )

        );
        return $hash_out;
    }
    public static function createTranslateToLowValueTokenHash($hash_in) {
        $hash_out = array(
            'id'=>Checker::requiredField(XmlFields::returnArrayValue($hash_in,'id')),
            'orderId' => XmlFields::returnArrayValue($hash_in, 'orderId', 25),
            'token' => Checker::requiredField(XmlFields::returnArrayValue($hash_in, 'token', 512))
        );
        return $hash_out;
    }
    public static function createVendorCreditHash($hash_in) {
        $hash_out = array (
            'fundingSubmerchantId' => XmlFields::returnArrayValue ( $hash_in, 'fundingSubmerchantId' ),
            'id'=>Checker::requiredField(XmlFields::returnArrayValue($hash_in,'id')),
            'vendorName' => XmlFields::returnArrayValue ( $hash_in, 'vendorName' ),
            'fundsTransferId' => XmlFields::returnArrayValue ( $hash_in, 'amount' ),
            'amount' => XmlFields::returnArrayValue ( $hash_in, 'amount' ) ,
            'accountInfo' => XmlFields::echeckType ( XmlFields::returnArrayValue ( $hash_in, 'accountInfo' ) )
        );
        return $hash_out;
    }
    public static function createPayFacCreditHash($hash_in) {
        $hash_out = array (
            'fundingSubmerchantId' => XmlFields::returnArrayValue ( $hash_in, 'fundingSubmerchantId' ),
            'id'=>Checker::requiredField(XmlFields::returnArrayValue($hash_in,'id')),
            'fundsTransferId' => XmlFields::returnArrayValue ( $hash_in, 'amount' ),
            'amount' => XmlFields::returnArrayValue ( $hash_in, 'amount' )
        );
        return $hash_out;
    }
    public static function createReserveCreditHash($hash_in) {
        $hash_out = array (
            'fundingSubmerchantId' => XmlFields::returnArrayValue ( $hash_in, 'fundingSubmerchantId' ),
            'id'=>Checker::requiredField(XmlFields::returnArrayValue($hash_in,'id')),
            'fundsTransferId' => XmlFields::returnArrayValue ( $hash_in, 'amount' ),
            'amount' => XmlFields::returnArrayValue ( $hash_in, 'amount' )
        );
        return $hash_out;
    }
    public static function createPhysicalCheckCreditHash($hash_in) {
        $hash_out = array (
            'fundingSubmerchantId' => XmlFields::returnArrayValue ( $hash_in, 'fundingSubmerchantId' ),
            'id'=>Checker::requiredField(XmlFields::returnArrayValue($hash_in,'id')),
            'fundsTransferId' => XmlFields::returnArrayValue ( $hash_in, 'amount' ),
            'amount' => XmlFields::returnArrayValue ( $hash_in, 'amount' )
        );
        return $hash_out;
    }
    public static function createVendorDebitHash($hash_in) {
        $hash_out = array (
            'fundingSubmerchantId' => XmlFields::returnArrayValue ( $hash_in, 'fundingSubmerchantId' ),
            'id'=>Checker::requiredField(XmlFields::returnArrayValue($hash_in,'id')),
            'vendorName' => XmlFields::returnArrayValue ( $hash_in, 'vendorName' ),
            'fundsTransferId' => XmlFields::returnArrayValue ( $hash_in, 'amount' ),
            'amount' => XmlFields::returnArrayValue ( $hash_in, 'amount' ) ,
            'accountInfo' => XmlFields::echeckType ( XmlFields::returnArrayValue ( $hash_in, 'accountInfo' ) )
        );
        return $hash_out;
    }
    public static function createPayFacDebitHash($hash_in) {
        $hash_out = array (
            'fundingSubmerchantId' => XmlFields::returnArrayValue ( $hash_in, 'fundingSubmerchantId' ),
            'id'=>Checker::requiredField(XmlFields::returnArrayValue($hash_in,'id')),
            'fundsTransferId' => XmlFields::returnArrayValue ( $hash_in, 'amount' ),
            'amount' => XmlFields::returnArrayValue ( $hash_in, 'amount' )
        );
        return $hash_out;
    }
    public static function createReserveDebitHash($hash_in) {
        $hash_out = array (
            'fundingSubmerchantId' => XmlFields::returnArrayValue ( $hash_in, 'fundingSubmerchantId' ),
            'id'=>Checker::requiredField(XmlFields::returnArrayValue($hash_in,'id')),
            'fundsTransferId' => XmlFields::returnArrayValue ( $hash_in, 'amount' ),
            'amount' => XmlFields::returnArrayValue ( $hash_in, 'amount' )
        );
        return $hash_out;
    }
    public static function createPhysicalCheckDebitHash($hash_in) {
        $hash_out = array (
            'fundingSubmerchantId' => XmlFields::returnArrayValue ( $hash_in, 'fundingSubmerchantId' ),
            'id'=>Checker::requiredField(XmlFields::returnArrayValue($hash_in,'id')),
            'fundsTransferId' => XmlFields::returnArrayValue ( $hash_in, 'amount' ),
            'amount' => XmlFields::returnArrayValue ( $hash_in, 'amount' )
        );
        return $hash_out;
    }
    public static function createEcheckVerificationHash($hash_in) {
        $hash_out = array (
            'cnpTxnId' => XmlFields::returnArrayValue ( $hash_in, 'cnpTxnId' ),
            'id'=>Checker::requiredField(XmlFields::returnArrayValue($hash_in,'id')),
            'orderId' => Checker::requiredField ( XmlFields::returnArrayValue ( $hash_in, 'orderId' ) ),
            'amount' => Checker::requiredField ( XmlFields::returnArrayValue ( $hash_in, 'amount' ) ),
            'orderSource' => Checker::requiredField ( XmlFields::returnArrayValue ( $hash_in, 'orderSource' ) ),
            'billToAddress' => XmlFields::contact ( XmlFields::returnArrayValue ( $hash_in, 'billToAddress' ) ),
            'echeck' => XmlFields::echeckType ( XmlFields::returnArrayValue ( $hash_in, 'echeck' ) ),
            'echeckToken' => XmlFields::echeckTokenType ( XmlFields::returnArrayValue ( $hash_in, 'echeckToken' ) ),
            'merchantData' => (XmlFields::merchantData ( XmlFields::returnArrayValue ( $hash_in, 'merchantData' ) ))
        );

        return $hash_out;
    }
    public static function createUpdateCardValidationNumOnTokenHash($hash_in) {
        $hash_out = array (
            'orderId' => XmlFields::returnArrayValue ( $hash_in, 'orderId' ),
            'id'=>Checker::requiredField(XmlFields::returnArrayValue($hash_in,'id')),
            'cnpToken' => Checker::requiredField ( XmlFields::returnArrayValue ( $hash_in, 'cnpToken' ) ),
            'cardValidationNum' => Checker::requiredField ( XmlFields::returnArrayValue ( $hash_in, 'cardValidationNum' ) )
        );

        return $hash_out;
    }
    public static function createUpdateSubscriptionHash($hash_in) {
        $hash_out = array (
            'subscriptionId' => Checker::requiredField ( XmlFields::returnArrayValue ( $hash_in, 'subscriptionId' ) ),
            'planCode' => XmlFields::returnArrayValue ( $hash_in, 'planCode' ),
            'billToAddress' => XmlFields::contact ( XmlFields::returnArrayValue ( $hash_in, 'billToAddress' ) ),
            'card' => XmlFields::cardType ( XmlFields::returnArrayValue ( $hash_in, 'card' ) ),
            'token' => XmlFields::cardTokenType ( XmlFields::returnArrayValue ( $hash_in, 'token' ) ),
            'paypage' => XmlFields::cardPaypageType ( XmlFields::returnArrayValue ( $hash_in, 'paypage' ) ),
            'billingDate' => XmlFields::returnArrayValue ( $hash_in, 'billingDate' )
        );

        $createDiscount = array();
        $updateDiscount = array();
        $deleteDiscount = array();
        $createAddOn = array();
        $updateAddon = array();
        $deleteAddOn = array();


        foreach ($hash_in as $key => $value) {
            if (($key == 'createDiscount' || $key == ('createDiscount'.count($createDiscount))) && $key != NULL) {
                $createDiscount[] = $value;
            } elseif (($key == 'updateDiscount' || $key == ('updateDiscount'.count($updateDiscount))) && $key != NULL) {
                $updateDiscount[] = $value;
            } elseif (($key == 'deleteDiscount' || $key == ('deleteDiscount'.count($deleteDiscount))) && $key != NULL) {
                $deleteDiscount[] = $value;
            } elseif (($key == 'createAddOn' || $key == ('createAddOn'.count($createAddOn))) && $key != NULL) {
                $createAddOn[] = $value;
            } elseif (($key == 'updateAddon' || $key == ('updateAddon'.count($updateAddon))) && $key != NULL) {
                $updateAddon[] = $value;
            } elseif (($key == 'deleteAddOn' || $key == ('deleteAddOn'.count($deleteAddOn))) && $key != NULL) {
                $deleteAddOn[] = $value;
            }
        }

        for ($j=0; $j<count($createDiscount); $j++) {
            $outIndex = ('createDiscount') . (string) $j;
            $hash_out[$outIndex] = XmlFields::createDiscount(XmlFields::returnArrayValue($createDiscount,$j));
        }
        for ($j=0; $j<count($updateDiscount); $j++) {
            $outIndex = ('updateDiscount') . (string) $j;
            $hash_out[$outIndex] = XmlFields::updateDiscount(XmlFields::returnArrayValue($updateDiscount,$j));
        }
        for ($j=0; $j<count($deleteDiscount); $j++) {
            $outIndex = ('deleteDiscount') . (string) $j;
            $hash_out[$outIndex] = XmlFields::deleteDiscount(XmlFields::returnArrayValue($deleteDiscount,$j));
        }
        for ($j=0; $j<count($createAddOn); $j++) {
            $outIndex = ('createAddOn') . (string) $j;
            $hash_out[$outIndex] = XmlFields::createAddOn(XmlFields::returnArrayValue($createAddOn,$j));
        }
        for ($j=0; $j<count($updateAddon); $j++) {
            $outIndex = ('updateAddon') . (string) $j;
            $hash_out[$outIndex] = XmlFields::updateAddon(XmlFields::returnArrayValue($updateAddon,$j));
        }
        for ($j=0; $j<count($deleteAddOn); $j++) {
            $outIndex = ('deleteAddOn') . (string) $j;
            $hash_out[$outIndex] = XmlFields::deleteAddOn(XmlFields::returnArrayValue($deleteAddOn,$j));
        }

        return $hash_out;
    }


    public static function createCancelSubscriptionHash($hash_in) {
        $hash_out = array (
            'subscriptionId' => Checker::requiredField ( XmlFields::returnArrayValue ( $hash_in, 'subscriptionId' ) ),
        );

        return $hash_out;
    }
    public static function createCreatePlanHash($hash_in) {
        $hash_out = array (
            'planCode' => Checker::requiredField ( XmlFields::returnArrayValue ( $hash_in, 'planCode' ) ),
            'name' => Checker::requiredField ( XmlFields::returnArrayValue ( $hash_in, 'name' ) ),
            'description' => XmlFields::returnArrayValue ( $hash_in, 'description' ),
            'intervalType' => Checker::requiredField ( XmlFields::returnArrayValue ( $hash_in, 'intervalType' ) ),
            'amount' => Checker::requiredField ( XmlFields::returnArrayValue ( $hash_in, 'amount' ) ),
            'numberOfPayments' => XmlFields::returnArrayValue ( $hash_in, 'numberOfPayments' ),
            'trialNumberOfIntervals' => XmlFields::returnArrayValue ( $hash_in, 'trialNumberOfIntervals' ),
            'trialIntervalType' => XmlFields::returnArrayValue ( $hash_in, 'trialIntervalType' ),
            'active' => XmlFields::returnArrayValue ( $hash_in, 'active' )
        );

        return $hash_out;
    }
    public static function createUpdatePlanHash($hash_in) {
        $hash_out = array (
            'planCode' => Checker::requiredField ( XmlFields::returnArrayValue ( $hash_in, 'planCode' ) ),
            'active' => XmlFields::returnArrayValue ( $hash_in, 'active' )
        );

        return $hash_out;
    }
    public static function createActivateHash($hash_in) {
        $hash_out = array (
            'orderId' => Checker::requiredField ( XmlFields::returnArrayValue ( $hash_in, 'orderId' ) ),
            'id'=>Checker::requiredField(XmlFields::returnArrayValue($hash_in,'id')),
            'amount' => Checker::requiredField ( XmlFields::returnArrayValue ( $hash_in, 'amount' ) ),
            'orderSource' => Checker::requiredField ( XmlFields::returnArrayValue ( $hash_in, 'orderSource' ) ),
            'card' => Checker::requiredField ( XmlFields::giftCardCardType ( XmlFields::returnArrayValue ( $hash_in, 'card' ) ) )
        );

        return $hash_out;
    }
    public static function createDeactivateHash($hash_in) {
        $hash_out = array (
            'orderId' => Checker::requiredField ( XmlFields::returnArrayValue ( $hash_in, 'orderId' ) ),
            'id'=>Checker::requiredField(XmlFields::returnArrayValue($hash_in,'id')),
            'orderSource' => Checker::requiredField ( XmlFields::returnArrayValue ( $hash_in, 'orderSource' ) ),
            'card' => Checker::requiredField ( XmlFields::giftCardCardType ( XmlFields::returnArrayValue ( $hash_in, 'card' ) ) )
        );

        return $hash_out;
    }
    public static function createLoadHash($hash_in) {
        $hash_out = array (
            'orderId' => Checker::requiredField ( XmlFields::returnArrayValue ( $hash_in, 'orderId' ) ),
            'id'=>Checker::requiredField(XmlFields::returnArrayValue($hash_in,'id')),
            'amount' => Checker::requiredField ( XmlFields::returnArrayValue ( $hash_in, 'amount' ) ),
            'orderSource' => Checker::requiredField ( XmlFields::returnArrayValue ( $hash_in, 'orderSource' ) ),
            'card' => Checker::requiredField ( XmlFields::giftCardCardType ( XmlFields::returnArrayValue ( $hash_in, 'card' ) ) )
        );

        return $hash_out;
    }
    public static function createUnloadHash($hash_in) {
        $hash_out = array (
            'orderId' => Checker::requiredField ( XmlFields::returnArrayValue ( $hash_in, 'orderId' ) ),
            'id'=>Checker::requiredField(XmlFields::returnArrayValue($hash_in,'id')),
            'amount' => Checker::requiredField ( XmlFields::returnArrayValue ( $hash_in, 'amount' ) ),
            'orderSource' => Checker::requiredField ( XmlFields::returnArrayValue ( $hash_in, 'orderSource' ) ),
            'card' => Checker::requiredField ( XmlFields::giftCardCardType ( XmlFields::returnArrayValue ( $hash_in, 'card' ) ) )
        );

        return $hash_out;
    }
    public static function createBalanceInquiryHash($hash_in) {
        $hash_out = array (
            'orderId' => Checker::requiredField ( XmlFields::returnArrayValue ( $hash_in, 'orderId' ) ),
            'id'=>Checker::requiredField(XmlFields::returnArrayValue($hash_in,'id')),
            'orderSource' => Checker::requiredField ( XmlFields::returnArrayValue ( $hash_in, 'orderSource' ) ),
            'card' => Checker::requiredField ( XmlFields::giftCardCardType ( XmlFields::returnArrayValue ( $hash_in, 'card' ) ) )
        );

        return $hash_out;
    }
    public static function createAccountUpdate($hash_in) {
        $hash_out = array (
            'orderId' => Checker::requiredField ( XmlFields::returnArrayValue ( $hash_in, 'orderId' ) ),
            'id'=>Checker::requiredField(XmlFields::returnArrayValue($hash_in,'id')),
            'card' => XmlFields::cardType ( XmlFields::returnArrayValue ( $hash_in, 'card' ) ),
            'token' => XmlFields::cardTokenType ( XmlFields::returnArrayValue ( $hash_in, 'token' ) )
        );

        return $hash_out;
    }

    public static function createFraudCheckHash($hash_in) {
        $hash_out = array (
            'id'=>Checker::requiredField(XmlFields::returnArrayValue($hash_in,'id')),
            'advancedFraudChecks'=>Checker::requiredField(XmlFields::returnArrayValue($hash_in,'advancedFraudChecks')),
            'billToAddress' => XmlFields::contact ( XmlFields::returnArrayValue ( $hash_in, 'billToAddress' ) ),
            'shipToAddress' => XmlFields::contact ( XmlFields::returnArrayValue ( $hash_in, 'shipToAddress' ) ),
            'amount' => ( XmlFields::returnArrayValue ( $hash_in, 'amount' ) ),
            'eventType' => XmlFields::returnArrayValue( $hash_in, 'eventType'),
            'accountLogin' => XmlFields::returnArrayValue($hash_in, 'accountLogin'),
            'accountPasshash' => XmlFields::returnArrayValue($hash_in, 'accountPasshash')
        );

        return $hash_out;
    }

    public static function createFundingInstructionVoidHash($hash_in) {
        $hash_out = array (
            'id'=>Checker::requiredField(XmlFields::returnArrayValue($hash_in,'id')),
            'cnpTxnId' => XmlFields::returnArrayValue ( $hash_in, 'cnpTxnId' )
        );
        return $hash_out;
    }

    public static function createFastAccessFundingHash($hash_in) {
        $hash_out = array (
            'fundingSubmerchantId' => XmlFields::returnArrayValue ( $hash_in, 'fundingSubmerchantId' ),
            'id'=>Checker::requiredField(XmlFields::returnArrayValue($hash_in,'id')),
            'submerchantName' => XmlFields::returnArrayValue ( $hash_in, 'submerchantName' ),
            'fundsTransferId' => XmlFields::returnArrayValue (  $hash_in, 'fundsTransferId'  ),
            'amount' =>  XmlFields::returnArrayValue ( $hash_in, 'amount' ) ,
            'card' => XmlFields::cardType ( XmlFields::returnArrayValue ( $hash_in, 'card' ) ),
            'token' => XmlFields::cardTokenType ( XmlFields::returnArrayValue ( $hash_in, 'token' ) ),
            'paypage' => XmlFields::cardPaypageType ( XmlFields::returnArrayValue ( $hash_in, 'paypage' ) )
        );
        return $hash_out;
    }
}