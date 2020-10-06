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
#require_once realpath(dirname(__FILE__)) . "/CnpOnline.php";

class XmlFields
{
    public static function returnArrayValue($hash_in, $key, $maxlength = null)
    {
        $retVal = array_key_exists($key, $hash_in)? $hash_in[$key] : null;
        if ($maxlength && !is_null($retVal)) {
            $retVal = mb_substr($retVal, 0, $maxlength);
        }

        return $retVal;
    }

    public static function contact($hash_in)
    {
        if (isset($hash_in)) {
            $hash_out = array(
                        "name"=>XmlFields::returnArrayValue($hash_in, "name", 100),
                        "firstName" =>XmlFields::returnArrayValue($hash_in, "firstName", 25),
                        "middleInitial"=>XmlFields::returnArrayValue($hash_in, "middleInitial", 1),
                        "lastName"=>XmlFields::returnArrayValue($hash_in, "lastName", 25),
                        "companyName"=>XmlFields::returnArrayValue($hash_in, "companyName", 40),
                        "addressLine1"=>XmlFields::returnArrayValue($hash_in, "addressLine1", 35),
                        "addressLine2"=>XmlFields::returnArrayValue($hash_in, "addressLine2", 35),
                        "addressLine3"=>XmlFields::returnArrayValue($hash_in, "addressLine3", 35),
                        "city"=>XmlFields::returnArrayValue($hash_in, "city", 35),
                        "state"=>XmlFields::returnArrayValue($hash_in, "state", 30),
                        "zip"=>XmlFields::returnArrayValue($hash_in, "zip", 20),
                        "country"=>XmlFields::returnArrayValue($hash_in, "country", 3),
                        "email"=>XmlFields::returnArrayValue($hash_in, "email", 100),
                        "phone"=>XmlFields::returnArrayValue($hash_in, "phone", 20)
            );

            return $hash_out;
        }

    }

    public static function customerInfo($hash_in)
    {
        if (isset($hash_in)) {
            $hash_out=	array(
                        "ssn"=>XmlFields::returnArrayValue($hash_in, "ssn"),
                        "dob"=>XmlFields::returnArrayValue($hash_in, "dob"),
                        "customerRegistrationDate"=>XmlFields::returnArrayValue($hash_in, "customerRegistrationDate"),
                        "customerType"=>XmlFields::returnArrayValue($hash_in, "customerType"),
                        "incomeAmount"=>XmlFields::returnArrayValue($hash_in, "incomeAmount"),
                        "incomeCurrency"=>XmlFields::returnArrayValue($hash_in, "incomeCurrency"),
                        "customerCheckingAccount"=>XmlFields::returnArrayValue($hash_in, "customerCheckingAccount"),
                        "customerSavingAccount"=>XmlFields::returnArrayValue($hash_in, "customerSavingAccount"),
                        "customerWorkTelephone"=>XmlFields::returnArrayValue($hash_in, "customerWorkTelephone"),
                        "residenceStatus"=>XmlFields::returnArrayValue($hash_in, "residenceStatus"),
                        "yearsAtResidence"=>XmlFields::returnArrayValue($hash_in, "yearsAtResidence"),
                        "yearsAtEmployer"=>XmlFields::returnArrayValue($hash_in, "yearsAtEmployer")
            );

            return $hash_out;
        }
    }

    public static function billMeLaterRequest($hash_in)
    {
        if (isset($hash_in)) {
            $hash_out = array(
                        "bmlMerchantId"=>XmlFields::returnArrayValue($hash_in, "bmlMerchantId"),
                        "termsAndConditions"=>XmlFields::returnArrayValue($hash_in, "termsAndConditions"),
                        "preapprovalNumber"=>XmlFields::returnArrayValue($hash_in, "preapprovalNumber"),
                        "merchantPromotionalCode"=>XmlFields::returnArrayValue($hash_in, "merchantPromotionalCode"),
                        "customerPasswordChanged"=>XmlFields::returnArrayValue($hash_in, "customerPasswordChanged"),
                        "customerEmailChanged"=>XmlFields::returnArrayValue($hash_in, "customerEmailChanged"),
                        "customerPhoneChanged"=>XmlFields::returnArrayValue($hash_in, "customerPhoneChanged"),
                        "secretQuestionCode"=>XmlFields::returnArrayValue($hash_in, "secretQuestionCode"),
                        "secretQuestionAnswer"=>XmlFields::returnArrayValue($hash_in, "secretQuestionAnswer"),
                        "virtualAuthenticationKeyPresenceIndicator"=>XmlFields::returnArrayValue($hash_in, "virtualAuthenticationKeyPresenceIndicator"),
                        "virtualAuthenticationKeyData"=>XmlFields::returnArrayValue($hash_in, "virtualAuthenticationKeyData"),
                        "itemCategoryCode"=>XmlFields::returnArrayValue($hash_in, "itemCategoryCode"),
                        "authorizationSourcePlatform"=>XmlFields::returnArrayValue($hash_in, "authorizationSourcePlatform")
            );

            return $hash_out;
        }
    }

    public static function fraudCheckType($hash_in)
    {
        if (isset($hash_in)) {
            $hash_out =	array(
                        "authenticationValue"=>XmlFields::returnArrayValue($hash_in, "authenticationValue"),
                        "authenticationTransactionId"=>XmlFields::returnArrayValue($hash_in, "authenticationTransactionId"),
                        "customerIpAddress"=>XmlFields::returnArrayValue($hash_in, "customerIpAddress"),
                        "authenticatedByMerchant"=>XmlFields::returnArrayValue($hash_in, "authenticatedByMerchant"),
                        "authenticationProtocolVersion"=>XmlFields::returnArrayValue($hash_in,"authenticationProtocolVersion"),
                        "tokenAuthenticationValue"=>XmlFields::returnArrayValue($hash_in,"tokenAuthenticationValue")
            );

            return $hash_out;
        }
    }

    public static function merchantData($hash_in)
    {
        if (isset($hash_in)) {
            $hash_out =	array(
                        "campaign"=>XmlFields::returnArrayValue($hash_in, "campaign"),
                        "affiliate"=>XmlFields::returnArrayValue($hash_in, "affiliate"),
                        "merchantGroupingId"=>XmlFields::returnArrayValue($hash_in, "merchantGroupingId")
            );

            return $hash_out;
        }
    }

    public static function authInformation($hash_in)
    {
        if (isset($hash_in)) {
            $hash_out = array(
                        "authDate"=>XmlFields::returnArrayValue($hash_in, "authDate"),
                        "authCode"=>XmlFields::returnArrayValue($hash_in, "authCode"),
                        "fraudResult"=>XmlFields::fraudResult(XmlFields::returnArrayValue($hash_in,"fraudResult")),
                        "authAmount"=>XmlFields::returnArrayValue($hash_in,'authAmount')
            );

            return $hash_out;
        }
    }

    public static function fraudResult($hash_in)
    {
        if (isset($hash_in)) {
            $hash_out= 	array(
                        "avsResult"=>XmlFields::returnArrayValue($hash_in, "avsResult"),
                        "cardValidationResult"=>XmlFields::returnArrayValue($hash_in, "cardValidationResult"),
                        "authenticationResult"=>XmlFields::returnArrayValue($hash_in, "authenticationResult"),
                        "advancedAVSResult"=>XmlFields::returnArrayValue($hash_in, "advancedAVSResult")
            );

            return $hash_out;
        }
    }

    public static function healthcareIIAS($hash_in)
    {
        if (isset($hash_in)) {
            $hash_out = array(
                        "healthcareAmounts"=>(XmlFields::healthcareAmounts(XmlFields::returnArrayValue($hash_in, "healthcareAmounts"))),
                        "IIASFlag"=>XmlFields::returnArrayValue($hash_in, "IIASFlag")
            );

            return $hash_out;
        }
    }

    public static function healthcareAmounts($hash_in)
    {
        if (isset($hash_in)) {
            $hash_out = array(
                "totalHealthcareAmount"=>XmlFields::returnArrayValue($hash_in, "totalHealthcareAmount"),
                "RxAmount"=>XmlFields::returnArrayValue($hash_in, "RxAmount"),
                "visionAmount"=>XmlFields::returnArrayValue($hash_in, "visionAmount"),
                "clinicOtherAmount"=>XmlFields::returnArrayValue($hash_in, "clinicOtherAmount"),
                "dentalAmount"=>XmlFields::returnArrayValue($hash_in, "dentalAmount")
            );

            return $hash_out;
        }
    }

    public static function wallet($hash_in)
    {
        if (isset($hash_in)) {
            $hash_out = array(
                "walletSourceType"=>XmlFields::returnArrayValue($hash_in, "walletSourceType"),
                "walletSourceTypeId"=>XmlFields::returnArrayValue($hash_in, "walletSourceTypeId")
            );

            return $hash_out;
        }
    }

    public static function walletSourceType($hash_in)
    {
        if (isset($hash_in)) {
            $hash_out = array(
                "totalHealthcareAmount"=>XmlFields::returnArrayValue($hash_in, "totalHealthcareAmount"),
                "RxAmount"=>XmlFields::returnArrayValue($hash_in, "RxAmount"),
                "visionAmount"=>XmlFields::returnArrayValue($hash_in, "visionAmount"),
                "clinicOtherAmount"=>XmlFields::returnArrayValue($hash_in, "clinicOtherAmount"),
                "dentalAmount"=>XmlFields::returnArrayValue($hash_in, "dentalAmount")
            );

            return $hash_out;
        }
    }

    public static function pos($hash_in)
    {
        if (isset($hash_in)) {
            $hash_out = array(
                        "capability"=>XmlFields::returnArrayValue($hash_in, "capability"),
                        "entryMode"=>XmlFields::returnArrayValue($hash_in, "entryMode"),
                        "cardholderId"=>XmlFields::returnArrayValue($hash_in, "cardholderId"),
                        "terminalId"=>XmlFields::returnArrayValue($hash_in,"terminalId"),
                        "catLevel"=>XmlFields::returnArrayValue($hash_in,"catLevel"),
            );

            return $hash_out;
        }
    }

    public static function detailTax($hash_in)
    {
        if (isset($hash_in)) {
            $hash_out = array(
                        "taxIncludedInTotal"=>XmlFields::returnArrayValue($hash_in, "taxIncludedInTotal"),
                        "taxAmount"=>XmlFields::returnArrayValue($hash_in, "taxAmount"),
                        "taxRate"=>XmlFields::returnArrayValue($hash_in, "taxRate"),
                        "taxTypeIdentifier"=>XmlFields::returnArrayValue($hash_in, "taxTypeIdentifier"),
                        "cardAcceptorTaxId"=>XmlFields::returnArrayValue($hash_in, "cardAcceptorTaxId")
            );

            return $hash_out;
        }
    }

    public static function lineItemData($hash_in)
    {
        if (isset($hash_in)) {
            $hash_out = array(
                        "itemSequenceNumber"=>XmlFields::returnArrayValue($hash_in, "itemSequenceNumber"),
                        "itemDescription"=>XmlFields::returnArrayValue($hash_in, "itemDescription", 26),
                        "productCode"=>XmlFields::returnArrayValue($hash_in, "productCode", 12),
                        "quantity"=>XmlFields::returnArrayValue($hash_in, "quantity"),
                        "unitOfMeasure"=>XmlFields::returnArrayValue($hash_in, "unitOfMeasure"),
                        "taxAmount"=>XmlFields::returnArrayValue($hash_in, "taxAmount"),
                        "lineItemTotal"=>XmlFields::returnArrayValue($hash_in, "lineItemTotal"),
                        "lineItemTotalWithTax"=>XmlFields::returnArrayValue($hash_in, "lineItemTotalWithTax"),
                        "itemDiscountAmount"=>XmlFields::returnArrayValue($hash_in, "itemDiscountAmount"),
                        "commodityCode"=>XmlFields::returnArrayValue($hash_in, "commodityCode"),
                        "unitCost"=>XmlFields::returnArrayValue($hash_in, "unitCost"),
                        "detailTax"=>(XmlFields::detailTax(XmlFields::returnArrayValue($hash_in, "detailTax")))
            );

            return $hash_out;
        }
    }

    public static function enhancedData($hash_in)
    {
        if (isset($hash_in)) {
            $hash_out = array(
                "customerReference"=>XmlFields::returnArrayValue($hash_in, "customerReference"),
                "salesTax"=>XmlFields::returnArrayValue($hash_in, "salesTax"),
                "deliveryType"=>XmlFields::returnArrayValue($hash_in, "deliveryType"),
                "taxExempt"=>XmlFields::returnArrayValue($hash_in, "taxExempt"),
                "discountAmount"=>XmlFields::returnArrayValue($hash_in, "discountAmount"),
                "shippingAmount"=>XmlFields::returnArrayValue($hash_in, "shippingAmount"),
                "dutyAmount"=>XmlFields::returnArrayValue($hash_in, "dutyAmount"),
                "shipFromPostalCode"=>XmlFields::returnArrayValue($hash_in, "shipFromPostalCode"),
                "destinationPostalCode"=>XmlFields::returnArrayValue($hash_in, "destinationPostalCode"),
                "destinationCountryCode"=>XmlFields::returnArrayValue($hash_in, "destinationCountryCode"),
                "invoiceReferenceNumber"=>XmlFields::returnArrayValue($hash_in, "invoiceReferenceNumber"),
                "orderDate"=>XmlFields::returnArrayValue($hash_in, "orderDate")
            );
            $lineItem = array();
            $detailtax = array();
            foreach ($hash_in as $key => $value) {
                if (($key == 'lineItemData' || $key == ('lineItemData'.count($lineItem))) && $key != NULL) {
                    $lineItem[] = $value;
                } elseif (($key == 'detailTax' || $key == ('detailTax'.count($detailtax))) && $key != NULL) {
                    $detailtax[] = $value;
                }
            }
            for ($j=0; $j<count($detailtax); $j++) {
                $outIndex = ('detailTax') . (string) $j;
                $hash_out[$outIndex] = XmlFields::detailTax(XmlFields::returnArrayValue($detailtax,$j));
            }
            for ($j=0; $j<count($lineItem); $j++) {
                $outIndex = ('lineItemData') . (string) $j;
                $hash_out[$outIndex] = XmlFields::lineItemData(XmlFields::returnArrayValue($lineItem,$j));
            }
            return $hash_out;
        }
    }


    public static function createDiscount($hash_in)
    {
        if (isset($hash_in)){
            $hash_out = array(
                'discountCode' => XmlFields::returnArrayValue($hash_in, "discountCode", 25),
                'name' => XmlFields::returnArrayValue($hash_in, 'name', 100),
                'amount' => XmlFields::returnArrayValue($hash_in, 'amount', 12),
                'startDate' => XmlFields::returnArrayValue($hash_in, 'startDate'),
                'endDate' => XmlFields::returnArrayValue($hash_in, 'endDate')
            );
            return $hash_out;
        }
    }


    public static function updateDiscount($hash_in)
    {
        if (isset($hash_in)){
            $hash_out = array(
                'discountCode' => (XmlFields::returnArrayValue($hash_in, "discountCode", 25)),
                'name' => (XmlFields::returnArrayValue($hash_in, 'name', 100)),
                'amount' => (XmlFields::returnArrayValue($hash_in, 'amount', 12)),
                'startDate' => (XmlFields::returnArrayValue($hash_in, 'startDate')),
                'endDate' => (XmlFields::returnArrayValue($hash_in, 'endDate'))
            );
            return $hash_out;
        }
    }


    public static function deleteDiscount($hash_in)
    {
        if (isset($hash_in)){
            $hash_out = array(
                'discountCode' => (XmlFields::returnArrayValue($hash_in, "discountCode", 25))
            );
            return $hash_out;
        }
    }


    public static function createAddOn($hash_in)
    {
        if (isset($hash_in)){
            $hash_out = array(
                'addOnCode'=> (XmlFields::returnArrayValue($hash_in, "addOnCode", 25)),
                'name' => (XmlFields::returnArrayValue($hash_in, 'name', 100)),
                'amount' => (XmlFields::returnArrayValue($hash_in, 'amount', 12)),
                'startDate' => (XmlFields::returnArrayValue($hash_in, 'startDate')),
                'endDate' => (XmlFields::returnArrayValue($hash_in, 'endDate'))
            );
            return $hash_out;
        }
    }

    public static function updateAddOn($hash_in)
    {
        if (isset($hash_in)){
            $hash_out = array(
                'addOnCode' => (XmlFields::returnArrayValue($hash_in, "addOnCode", 25)),
                'name' => (XmlFields::returnArrayValue($hash_in, 'name', 100)),
                'amount' => (XmlFields::returnArrayValue($hash_in, 'amount', 12)),
                'startDate' => (XmlFields::returnArrayValue($hash_in, 'startDate')),
                'endDate' => (XmlFields::returnArrayValue($hash_in, 'endDate'))
            );
            return $hash_out;
        }
    }


    public static function deleteAddOn($hash_in)
    {
        if (isset($hash_in)){
            $hash_out = array(
                'addOnCode' => (XmlFields::returnArrayValue($hash_in, "addOnCode", 25))
            );
            return $hash_out;
        }
    }


    public static function amexAggregatorData($hash_in)
    {
        if (isset($hash_in)) {
            $hash_out = array(
                        "sellerId"=>XmlFields::returnArrayValue($hash_in, "sellerId"),
                        "sellerMerchantCategoryCode"=>XmlFields::returnArrayValue($hash_in, "sellerMerchantCategoryCode")
            );

            return $hash_out;
        }
    }

    public static function cardType($hash_in)
    {
        if (isset($hash_in)) {
            $hash_out= 	array(
                        "type"=>XmlFields::returnArrayValue($hash_in, "type"),
                        "track"=>XmlFields::returnArrayValue($hash_in, "track"),
                        "number"=>XmlFields::returnArrayValue($hash_in, "number"),
                        "expDate"=>XmlFields::returnArrayValue($hash_in, "expDate"),
                        "cardValidationNum"=>XmlFields::returnArrayValue($hash_in, "cardValidationNum"),
            			"pin"=>XmlFields::returnArrayValue($hash_in, "pin")
            );

            return $hash_out;
        }
    }

    public static function lodgingCharge($hash_in)
    {
        if (isset($hash_in)) {
            $hash_out = array(
                        "name" => (XmlFields::returnArrayValue($hash_in, 'name'))
            );

            return $hash_out;
        }
    }


    public static function lodgingInfo($hash_in)
    {
        if (isset($hash_in)){
            $hash_out = array(
                        "hotelFolioNumber" => XmlFields::returnArrayValue($hash_in, 'hotelFolioNumber', 25),
                        "checkInDate" => XmlFields::returnArrayValue($hash_in, 'checkInDate'),
                        "checkOutDate" => XmlFields::returnArrayValue($hash_in, 'checkOutDate'),
                        "duration" => XmlFields::returnArrayValue($hash_in, 'duration', 4),
                        "customerServicePhone" => XmlFields::returnArrayValue($hash_in, 'customerServicePhone', 17),
                        "programCode" => XmlFields::returnArrayValue($hash_in, 'programCode'),
                        "roomRate" => XmlFields::returnArrayValue($hash_in, 'roomRate', 12),
                        "roomTax" => XmlFields::returnArrayValue($hash_in, 'roomTax', 12),
                        "numAdults" => XmlFields::returnArrayValue($hash_in, 'numAdults', 2),
                        "propertyLocalPhone" => XmlFields::returnArrayValue($hash_in, 'propertyLocalPhone', 17),
                        "fireSafetyIndicator" => XmlFields::returnArrayValue($hash_in, 'fireSafetyIndicator'),
            );

            $lodgingCharge = array();
            foreach ($hash_in as $key => $value) {
                if (($key == 'lodgingCharge' || $key == ('lodgingCharge' . count($lodgingCharge))) && $key != NULL) {
                    $lodgingCharge[] = $value;
                }
            }
            for ($j=0; $j<count($lodgingCharge) and $j < 6; $j++) {
                $outIndex = ('lodgingCharge') . (string) $j;
                $hash_out[$outIndex] = XmlFields::lodgingCharge(XmlFields::returnArrayValue($lodgingCharge,$j));
            }

            return $hash_out;
        }
    }

    public static function pinlessDebitRequest($hash_in)
    {
        if (isset($hash_in)) {
            $hash_out = array(
                    "routingPreference" => XmlFields::returnArrayValue($hash_in, 'routingPreference'),
                    'preferredDebitNetworks' => XmlFields::preferredDebitNetworks(XmlFields::returnArrayValue($hash_in, 'preferredDebitNetworks'))
            );

            return $hash_out;
        }
    }

    public static function preferredDebitNetworks($hash_in)
    {
        if (isset($hash_in)) {
            $hash_out = array();

            $debitNetworkName = array();
            foreach ($hash_in as $key => $value) {
                if (($key == 'debitNetworkName' || $key == ('debitNetworkName' . count($debitNetworkName))) && $key != NULL) {
                    $debitNetworkName[] = $value;
                }
            }
            for ($j=0; $j<count($debitNetworkName) and $j < 12; $j++) {
                $outIndex = ('debitNetworkName') . (string) $j;
                $hash_out[$outIndex] = XmlFields::returnArrayValue($debitNetworkName,$j);
            }

            return $hash_out;
        }
    }
    
    public static function giftCardCardType($hash_in)
    {
    	if (isset($hash_in)) {
    		$hash_out= 	array(
    				"type"=>XmlFields::returnArrayValue($hash_in, "type"),
    				"track"=>XmlFields::returnArrayValue($hash_in, "track"),
    				"number"=>XmlFields::returnArrayValue($hash_in, "number"),
    				"expDate"=>XmlFields::returnArrayValue($hash_in, "expDate"),
    				"cardValidationNum"=>XmlFields::returnArrayValue($hash_in, "cardValidationNum"),
    				"pin"=>XmlFields::returnArrayValue($hash_in, "pin")
    		);
    
    		return $hash_out;
    	}
    }

    public static function cardTokenType($hash_in)
    {
        if (isset($hash_in)) {
            $hash_out = array(
                        "cnpToken"=>XmlFields::returnArrayValue($hash_in, "cnpToken"),
                        "tokenURL"=>XmlFields::returnArrayValue($hash_in, "tokenURL"),
                        "expDate"=>XmlFields::returnArrayValue($hash_in, "expDate"),
                        "cardValidationNum"=>XmlFields::returnArrayValue($hash_in, "cardValidationNum"),
                        "type"=>XmlFields::returnArrayValue($hash_in, "type"),
                        "checkoutId"=>XmlFields::returnArrayValue($hash_in, "checkoutId")

            );

            return $hash_out;
        }
    }

    public static function cardPaypageType($hash_in)
    {
        if (isset($hash_in)) {
            $hash_out = array(
                        "paypageRegistrationId"=>XmlFields::returnArrayValue($hash_in, "paypageRegistrationId"),
                        "expDate"=>XmlFields::returnArrayValue($hash_in, "expDate"),
                        "cardValidationNum"=>XmlFields::returnArrayValue($hash_in, "cardValidationNum"),
                        "type"=>XmlFields::returnArrayValue($hash_in, "type")
            );

            return $hash_out;
        }
    }

    public static function paypal($hash_in)
    {
        if (isset($hash_in)) {
            $hash_out = array(
                        "payerId"=>XmlFields::returnArrayValue($hash_in, "payerId"),
                        "token"=>XmlFields::returnArrayValue($hash_in, "token"),
                        "transactionId"=>XmlFields::returnArrayValue($hash_in, "transactionId")
            );

            return $hash_out;
        }
    }

    #paypal field for credit transaction
    public static function credit_paypal($hash_in)
    {
        if (isset($hash_in)) {
            $hash_out = array(
                        "payerId"=>XmlFields::returnArrayValue($hash_in, "payerId"),
                        "payerEmail" =>XmlFields::returnArrayValue($hash_in, "payerEmail")
            );

            return $hash_out;
        }
    }

    public static function customBilling($hash_in)
    {
        if (isset($hash_in)) {
            $hash_out = array(
                        "phone"=>XmlFields::returnArrayValue($hash_in, "phone", 13),
                        "city" =>XmlFields::returnArrayValue($hash_in, "city", 35),
                        "url" =>XmlFields::returnArrayValue($hash_in, "url", 13),
                        "descriptor" =>XmlFields::returnArrayValue($hash_in, "descriptor", 25)
            );

            return $hash_out;
        }
    }

    public static function taxBilling($hash_in)
    {
        if (isset($hash_in)) {
            $hash_out = array(
                        "taxAuthority"=>XmlFields::returnArrayValue($hash_in, "taxAuthority"),
                        "state" =>XmlFields::returnArrayValue($hash_in, "state"),
                        "govtTxnType" =>XmlFields::returnArrayValue($hash_in, "govtTxnType")
            );

            return $hash_out;
        }
    }




    public static function processingInstructions($hash_in)
    {
        if (isset($hash_in)) {
            $hash_out = array(
                        "bypassVelocityCheck"=>XmlFields::returnArrayValue($hash_in, "bypassVelocityCheck")
            );

            return $hash_out;
        }
    }

    public static function echeckForTokenType($hash_in)
    {
        if (isset($hash_in)) {
            $hash_out = array(
                        "accNum"=>XmlFields::returnArrayValue($hash_in, "accNum"),
                        "routingNum" =>XmlFields::returnArrayValue($hash_in, "routingNum")
            );

            return $hash_out;
        }
    }

    public static function filteringType($hash_in)
    {
        if (isset($hash_in)) {
            $hash_out = array(
                        "prepaid"=>XmlFields::returnArrayValue($hash_in, "prepaid"),
                        "international" =>XmlFields::returnArrayValue($hash_in, "international"),
                        "chargeback" =>XmlFields::returnArrayValue($hash_in, "chargeback")
            );

            return $hash_out;
        }
    }

    public static function echeckType($hash_in)
    {
        if (isset($hash_in)) {
            $hash_out = array(
                        "accType"=>XmlFields::returnArrayValue($hash_in, "accType"),
                        "accNum" =>XmlFields::returnArrayValue($hash_in, "accNum"),
                        "routingNum" =>XmlFields::returnArrayValue($hash_in, "routingNum"),
                        "checkNum" =>XmlFields::returnArrayValue($hash_in, "checkNum"),
            			"ccdPaymentInformation" =>XmlFields::returnArrayValue($hash_in, "ccdPaymentInformation")
            		
            );

            return $hash_out;
        }
    }

    public static function echeckTypeCtx($hash_in)
    {
        if (isset($hash_in)) {
            $hash_out = array(
                "accType"=>XmlFields::returnArrayValue($hash_in, "accType"),
                "accNum" =>XmlFields::returnArrayValue($hash_in, "accNum"),
                "routingNum" =>XmlFields::returnArrayValue($hash_in, "routingNum"),
                "checkNum" =>XmlFields::returnArrayValue($hash_in, "checkNum"),
                "ccdPaymentInformation" =>XmlFields::returnArrayValue($hash_in, "ccdPaymentInformation"),
                "ctxPaymentInformation" =>XmlFields::returnArrayValue($hash_in, "ctxPaymentInformation")

            );

            return $hash_out;
        }
    }

    public static function echeckTokenType($hash_in)
    {
        if (isset($hash_in)) {
            $hash_out = array(
                        "cnpToken"=>XmlFields::returnArrayValue($hash_in, "cnpToken"),
                        "routingNum" =>XmlFields::returnArrayValue($hash_in, "routingNum"),
                        "accType" =>XmlFields::returnArrayValue($hash_in, "accType"),
                        "checkNum" =>XmlFields::returnArrayValue($hash_in, "checkNum")
            );

            return $hash_out;
        }
    }

    public static function recyclingRequestType($hash_in)
    {
        if (isset($hash_in)) {
            $hash_out = array(
                        "recycleBy"=>XmlFields::returnArrayValue($hash_in, "recycleBy"),
                        "recycleId"=>XmlFields::returnArrayValue($hash_in, "recycleId")
            );

            return $hash_out;
        }
    }

    public static function recurringRequestType($hash_in)
    {
        if (isset($hash_in)) {
            $hash_out = array(
                    "createSubscription"=>(XmlFields::recurringSubscriptionType(XmlFields::returnArrayValue($hash_in,"createSubscription")))
            );

            return $hash_out;
        }
    }

    public static function recurringSubscriptionType($hash_in)
    {
        if (isset($hash_in)) {
            $hash_out = array(
                    "planCode"=>XmlFields::returnArrayValue($hash_in, "planCode"),
                    "numberOfPayments"=>(XmlFields::returnArrayValue($hash_in, "numberOfPayments")),
                    "startDate"=>(XmlFields::returnArrayValue($hash_in, "startDate")),
                    "amount"=>(XmlFields::returnArrayValue($hash_in, "amount")),
                    "createDiscount"=>(XmlFields::createDiscountType(XmlFields::returnArrayValue($hash_in, "createDiscount"))),
                    "createAddOn"=>(XmlFields::createAddOnType(XmlFields::returnArrayValue($hash_in, "createAddOn"))),
            );

            return $hash_out;
        }
    }

    public static function createDiscountType($hash_in){
        if (isset($hash_in)) {
            $hash_out = array(
                "discountCode"=>XmlFields::returnArrayValue($hash_in, "discountCode"),
                "name"=>(XmlFields::returnArrayValue($hash_in, "name")),
                "amount"=>(XmlFields::returnArrayValue($hash_in, "amount")),
                "startDate"=>(XmlFields::returnArrayValue($hash_in, "startDate")),
                "endDate"=>(XmlFields::returnArrayValue($hash_in, "endDate")),
            );

            return $hash_out;
        }
    }

    public static function createAddOnType($hash_in){
        if (isset($hash_in)) {
            $hash_out = array(
                "addOnCode"=>XmlFields::returnArrayValue($hash_in, "addOnCode"),
                "name"=>(XmlFields::returnArrayValue($hash_in, "name")),
                "amount"=>(XmlFields::returnArrayValue($hash_in, "amount")),
                "startDate"=>(XmlFields::returnArrayValue($hash_in, "startDate")),
                "endDate"=>(XmlFields::returnArrayValue($hash_in, "endDate")),
            );

            return $hash_out;
        }
    }

    public static function cnpInternalRecurringRequestType($hash_in)
    {
        if (isset($hash_in)) {
            $hash_out = array(
                    "subscriptionId"=>XmlFields::returnArrayValue($hash_in, "subscriptionId"),
                    "recurringTxnId"=>XmlFields::returnArrayValue($hash_in, "recurringTxnId"),
                    "finalPayment"=>XmlFields::returnArrayValue($hash_in, "finalPayment")
            );

            return $hash_out;
        }
    }

    public static function advancedFraudChecksType($hash_in)
    {
        if (isset($hash_in)) {
            $hash_out = array(
                "threatMetrixSessionId"=>XmlFields::returnArrayValue($hash_in, "threatMetrixSessionId", 128),
            	"customAttribute1"=>(XmlFields::returnArrayValue($hash_in, "customAttribute1")),
            	"customAttribute2"=>(XmlFields::returnArrayValue($hash_in, "customAttribute2")),
            	"customAttribute3"=>(XmlFields::returnArrayValue($hash_in, "customAttribute3")),
            	"customAttribute4"=>(XmlFields::returnArrayValue($hash_in, "customAttribute4")),
            	"customAttribute5"=>(XmlFields::returnArrayValue($hash_in, "customAttribute5")),
                "webSessionId"=>(XmlFields::returnArrayValue($hash_in, "webSessionId"))
            );

            return $hash_out;
        }
    }

    public static function mposType($hash_in)
    {
        if (isset($hash_in)) {
            $hash_out = array(
            "ksn"=>XmlFields::returnArrayValue($hash_in, "ksn", 1028),
            "formatId"=>XmlFields::returnArrayValue($hash_in, "formatId", 1028),
            "encryptedTrack"=>XmlFields::returnArrayValue($hash_in, "encryptedTrack", 1028),
            "track1Status"=>XmlFields::returnArrayValue($hash_in, "track1Status", 1028),
            "track2Status"=>XmlFields::returnArrayValue($hash_in, "track2Status", 1028)
            );

            return $hash_out;
        }

    }
    
    public static function applePayType($hash_in)
    {
    	if (isset($hash_in)) {
    		$hash_out = array(
    				"data"=>(XmlFields::returnArrayValue($hash_in, "data")),
    				"header"=>(XmlFields::returnArrayValue($hash_in, "header")),
    				"signature"=>XmlFields::returnArrayValue($hash_in, "signature"),
    				"version"=>XmlFields::returnArrayValue($hash_in, "version")
    		);
    
    		return $hash_out;
    	}
    }
    
    public static function sepaDirectDebitType($hash_in)
    {
    	if (isset($hash_in)) {
    		$hash_out = array(
    				"mandateProvider"=>(XmlFields::returnArrayValue($hash_in, "mandateProvider")),
    				"sequenceType"=>(XmlFields::returnArrayValue($hash_in, "sequenceType")),
    				"mandateReference"=>XmlFields::returnArrayValue($hash_in, "mandateReference"),
    				"mandateUrl"=>XmlFields::returnArrayValue($hash_in, "mandateUrl"),
    				"mandateSignatureDate"=>XmlFields::returnArrayValue($hash_in, "mandateSignatureDate"),
    				"iban"=>XmlFields::returnArrayValue($hash_in, "iban"),
    				"preferredLanguage"=>XmlFields::returnArrayValue($hash_in, "preferredLanguage")
    
    		);
    
    		return $hash_out;
    	}
    }

    public static function idealType($hash_in)
    {
        if (isset($hash_in)) {
            $hash_out = array(
                "preferredLanguage"=>XmlFields::returnArrayValue($hash_in, "preferredLanguage")
            );

            return $hash_out;
        }
    }

    public static function giropayType($hash_in)
    {
        if (isset($hash_in)) {
            $hash_out = array(
                "preferredLanguage"=>XmlFields::returnArrayValue($hash_in, "preferredLanguage")
            );

            return $hash_out;
        }
    }

    public static function sofortType($hash_in)
    {
        if (isset($hash_in)) {
            $hash_out = array(
                "preferredLanguage"=>XmlFields::returnArrayValue($hash_in, "preferredLanguage")
            );

            return $hash_out;
        }
    }

}
