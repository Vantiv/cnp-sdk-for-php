= CnpOnline CHANGELOG

==Version 12.40.0 (v12.40.0)(October 25, 2024)
* Change: [cnpAPI v12.40] In authorization,sale and captureGivenAuth request new elements added-> 'typeOfDigitalCurrency' and 'conversionAffiliateId'.
* Change: [cnpAPI v12.40] In existing simple type transactionAmountType range added between minInclusive value -999999999999  and maxInclusive value 999999999999
* Change: [cnpAPI v12.40] In existing type authenticationProtocolVersionType new values '3','4','5','6','7','8' and '9' are added

==Version 12.39.0 (v12.39.0) (October 25, 2024)
* Change: [cnpAPI v12.39] In 'subMerchantCredit' 'payFacCredit','reserveCredit', 'payoutOrgCreditRequest' base changed from 'transactionTypeWithReportGroup' to 'transactionTypeWithReportGroupAndRtp'.
* Change: [cnpAPI v12.39] To support new changed extension base for above txns ,New Complextype 'transactionTypeWithReportGroupAndRtp' added.
* Change: [cnpAPI v12.39] New element 'fundingTransactionReferenceNumber' in authorization and sale response.

==Version 12.38.0 (v12.38.0) (October 25, 2024)
* Change: [cnpAPI v12.38] In existing Enum 'ActionTypeEnum' new value 'FIVD' added
* Change: [cnpAPI v12.38] In existing complex element 'baseRequest' two more choices are added 'encryptionKeyRequest' and 'encryptedPayload'
* Change: [cnpAPI v12.38] In existing complex element 'cnpOnlineResponse' one more choice is added 'encryptionKeyResponse'
* Change: [cnpAPI v12.38] New element 'encryptionKeyRequest' is added of type 'encryptionKeyRequestEnum'
* Change: [cnpAPI v12.38] To support 'encryptionKeyRequest' new Enum 'encryptionKeyRequestEnum' added with values 'CURRENT' and 'PREVIOUS'
* Change: [cnpAPI v12.38] New complex element 'encryptionKeyResponse' is added with simple elements ->'encryptionKeySequence' of type 'encryptionKeySequenceType' and 'encryptionKey' of type 'encryptionKeyType'
* Change: [cnpAPI v12.38] To support 'encryptionKeySequence' new simple type 'encryptionKeySequenceType' added with total Digits 19
* Change: [cnpAPI v12.38] To support 'encryptionKey' new simple type 'encryptionKeyType' of type string with maxLength 15000
* Change: [cnpAPI v12.38] New complex element 'encryptedPayload' is added with simple elements ->'encryptionKeySequence' of type 'encryptionKeySequenceType' and 'payload' of type 'payloadType'
* Change: [cnpAPI v12.38] To support 'payload' new simple type 'payloadType' of type string with maxLength 15000
* Change: [cnpAPI v12.38] In capture Request new complex element 'partialCapture' is added with elements-> 'partialCaptureSequenceNumber' of type 'integer' and 'partialCaptureTotalCount' of type 'partialCaptureCount'

==Version 12.37.1 (v12.37.1)(July 29, 2024)
Fix: [cnpAPI v12.37.1] Fix for Ordering issue for Fast Access Funding

==Version 12.37.0 (v12.37.0)(July 29, 2024)
* Change: [cnpAPI v12.37] New element added 'postCheckoutRedirectUrl' with min length '1' and maxlength '200'
* Change: [cnpAPI v12.37] New enum 'provider' is added with value 'AFFIRM'
* Change: [cnpAPI v12.37] New complex type 'inquiryResultType' is added with  simple element 'response' of 'responseType' and 'message' of 'messageType'.
* Change: [cnpAPI v12.37] New cnpRequest Added of complex type element 'BNPLAuthorizationRequest' is added with few simple elements and few complex elements-simple elements 'amount','orderId' ,'provider' of type 'provider' and complex elements 'customerInfo','billToAddress','shipToAddress','enhancedData','postCheckoutRedirectUrl'
* Change: [cnpAPI v12.37] New cnpResponse Added of complex type element 'BNPLAuthResponse' is added with few simple elements ,existing simple elements 'cnpTxnId','response','responseTime','message','location' and 'checkoutUrl'
* Change: [cnpAPI v12.37] New cnpRequest Added of complex type element 'BNPLCaptureRequest' is added with existing simple element 'amount','orderId','cnpTxnId'
* Change: [cnpAPI v12.37] New cnpResponse Added of complex type element 'BNPLCaptureResponse' is added with existing simple element 'cnpTxnId','response','responseTime','message','location'
* Change: [cnpAPI v12.37] New cnpRequest Added of complex type element 'BNPLRefundRequest' is added with existing simple element 'amount','orderId','cnpTxnId'
* Change: [cnpAPI v12.37] New cnpResponse Added of complex type element 'BNPLRefundResponse' is added with existing simple element 'cnpTxnId','response','responseTime','message','location'
* Change: [cnpAPI v12.37] New cnpRequest Added of complex type element 'BNPLCancelRequest' is added with existing simple element 'amount','orderId','cnpTxnId'
* Change: [cnpAPI v12.37] New complex element 'BNPLCancelResponse' is added with existing simple element 'cnpTxnId','response','responseTime','message','location'
* Change: [cnpAPI v12.37] New cnpRequest Added of complex type element 'BNPLInquiryRequest' is added with existing simple element 'orderId','cnpTxnId'
* Change: [cnpAPI v12.37] New cnpResponse Added of complex type element 'BNPLInquiryResponse' is added with existing simple element 'cnpTxnId','response','responseTime','message','location' and complex element 'inquiryResult' of 'inquiryResultType'
* Change: [cnpAPI v12.36] New complex type element Enum is added 'accountFundingTransactionTypeEnum' with new values 'accountToAccount','agentCashOut','bankInitiated','businessDisbursement','businessToBusinessTransfer','debitPrepaidAccount','fundsDisbursement','fundTransfer','governmentNonProfitDisbursement','paymentOfOwnCreditCardBill','payrollDisbursement','personToPerson','personToPersonCardAccount','rapidMerchantSettlement','topUp','walletTransfer'
* Change: [cnpAPI v12.36] New Enum is added 'accountFundingTransactionAccountNumberTypeEnum' with new values 'other','RTNAndBAN','IBAN','cardAccount','email','phoneNumber','BANAndBIC','walletID','socialNetworkID'
* Change: [cnpAPI v12.36] New cnpRequest Added of complex type 'finicityAccountRequest' element is added with simple element 'echeckCustomerId' of type 'string25Type'
* Change: [cnpAPI v12.36] New cnpResponse Added of complex type element 'finicityAccountResponse' element is added with simple elements 'cnpTxnId','response','responseTime','message','location' and new complex element 'finicityAccount' of type 'finicityAccountInfoType'
* Change: [cnpAPI v12.36] To support 'finicityAccount' new complex type 'finicityAccountInfoType' is added with simple element accountId' of 'string','accType' of type 'echeckAccountTypeEnum','realAccNum' of 'echeckAccountNumberType','routingNum' of 'routingNumberType'
* Change: [cnpAPI v12.36] new element 'fraudCheckAction' of 'fraudCheckActionEnum' is added in Authorization, sale request.
* Change: [cnpAPI v12.36] New values added 'APPROVED_SKIP_FRAUD_CHECK','DECLINED_NEED_FRAUD_CHECK' to existing 'fraudCheckActionEnum'
* Change: [cnpAPI v12.36] New simple type element 'accountId' of type 'string' is added in 'echeckType' existing complex type.
* Change: [cnpAPI v12.36] New simple type elements are added 'receiverAccountNumberType' of type 'accountFundingTransactionAccountNumberTypeEnum' and 'accountFundingTransactionType' of type 'accountFundingTransactionTypeEnum' in existing complex element accountFundingTransactionData.
* Change: [cnpAPI v12.35] In existing Enum businessIndicatorEnum new values 'personToPersonCardAccount','debitPrepaidAccount','paymentOfOwnCreditCardBill','businessDisbursement','businessToBusinessTransfer','governmentNonProfitDisbursement','rapidMerchantSettlement','agentCashOut' are added.
* Change: [cnpAPI v12.35] New Enum 'stateTypeEnum' is added with values 'AL','AK','AZ','AR','CA','CO','CT','DE','DC','FL','GA','HI','ID','IL','IN','IA','KS','KY','LA','ME','MD','MA','MI','MN','MS','MO','MT','NE','NV','NH','NJ','NM','NY','NC','ND','OH','OK','OR','PA','RI','SC','SD','TN','TX','UT','VT','VA','WA','WV','WI','WY','AB','BC','MB','NB','NL','NT','NS','ON','PE','QC','SK','YT','NU'
* Change: [cnpAPI v12.35] In existing Enum fulfilmentMethodTypeEnum new values 'STANDARD_SHIPPING' and 'EXPEDITED_SHIPPING'
* Change: [cnpAPI v12.35] In existing Enum orderChannelEnum new values 'SCAN_AND_GO' and 'SMART_TV'
* Change: [cnpAPI v12.35] New complex element 'accountFundingTransactionData' added with below simple elements receiverFirstName of 'string35Type' ,'receiverLastName' of 'string35Type','receiverState' of 'stateTypeEnum',''receiverCountry' of 'countryTypeEnum' and 'receiverAccountNumber' of 'string50Type'
* Change: [cnpAPI v12.35] New complex element 'accountFundingTransactionData' is added in Authorization,sale,credit,capturegiventAuth and FORCECapture request.
* Change: [cnpAPI v12.34] For existing simple element 'amount' simple type is changed from 'transactionAmountType' to new simple element type of Integer 'achTransactionAmountType' in transactions- vendorCredit,vendorDebit,submerchantCredit,submerchantDebit,payFacDebit,payFacCredit,reserveCredit,reserveDebit
* Change: [cnpAPI v12.34] Added simple element type of Integer 'achTransactionAmountType' to support 'amount'.
* Change: [cnpAPI v12.34] Added 'accountToAccount','bankInitiated','fundsDisbursement','payrollDisbursement','personToPerson','topUp' in existing enum businessIndicatorEnum.
* Change: [cnpAPI v12.34] For existing element 'origId' from 'queryTransaction' simpletype changed from 'string25Type' to 'stringMin1Max36CollapseWhiteSpaceType'.
* Change: [cnpAPI v12.34] New cnpRequest Added of complex type element 'finicityUrlRequest' is added with subfeilds-'firstName','lastName','phoneNumber','email'.
* Change: [cnpAPI v12.34] New cnpResponse Added of complex type element 'finicityUrlResponse' is added with subfeilds-'cnpTxnId','response','responseTime','message','location','echeckCustomerId','url'.
* Change: [cnpAPI v12.34] New simple element 'echeckCustomerId' is added existing complex type 'echeckType'.

==Version: 12.33.0(Dec 22, 2023)
Note: It contains changes from cnpAPI v12.32 and v12.33. In case you need any feature supported by cnpAPI v12.32 and v12.33.please use SDK version 12.33.0.
* Change: [cnpAPI v12.33] Added new simple types numberOfPeriods of type digit,string10Type
* Change: [cnpAPI v12.33] Added new simple element shipmentId
* Change: [cnpAPI v12.33] new complex element subscription is added with few simple elements subscriptionId ,nextDeliveryDate ,periodUnit ,numberOfPeriods ,regularItemPrice ,currentPeriod
* Change: [cnpAPI v12.33] Added new Enum periodUnit of type enum with values WEEK,MONTH,QUARTER,YEAR
* Change: [cnpAPI v12.32] simple type of customerIpAddress is changed from ipAddress to newly added simpletype stringipAddress


==Version 12.31.0 (Jul 08, 2023)
* Feature:  [cnpAPI v12.31] New element foreignRetailerIndicator of type enum added in capture, captureGivenAuth, forceCapture, sale request.
* Change:  [cnpAPI v12.31] phpseclib is upgraded to phpseclib3 to support modern ciphers during sftp sessions.

==Version 12.30.0 (Mar 14, 2023)
Note: It contains changes from cnpAPI v12.30 . In case you need any feature supported by cnpAPI v12.30, please use SDK version 12.30.0.

* Change:  [cnpAPI v12.30] New element authIndicator added in Authorization Request.
* Change:  [cnpAPI v12.30] New element amount added in Authorization Request.
* Change:  [cnpAPI v12.30] To support authIndicator new enum authIndicatorEnum added with values Incremental ,Estimated .

==Version 12.29.0 (Mar 08, 2023)
Note: It contains changes from cnpAPI v12.28 and v12.29. In case you need any feature supported by cnpAPI v12.28 and 12.29, please use SDK version 12.29.0.

* Change:  [cnpAPI v12.29] New element sellerInfo added in Authorization and sale Request.
* Change:  [cnpAPI v12.29] New elements  accountNumber, aggregateOrderCount, aggregateOrderDollars, sellerAddress, createdDate, domain, email,
                           lastUpdateDate, name, onboardingEmail, onboardingIpAddress, parentEntity, phone, sellerId,
                           sellerTags and username added in sellerInfo.
* Change:  [cnpAPI v12.29] New elements sellerStreetaddress, sellerUnit, sellerPostalcode, sellerCity, sellerProvincecode
                           and sellerCountrycode added in sellerAddress.
* Change:  [cnpAPI v12.29] The datatype for networkToken, authMaxResponseCode, authMaxResponseMessage has been changed, Previously it was String.
                           networkToken - ccAccountNumberType, authMaxResponseCode - responseType, authMaxResponseMessage- messageType.
* Change:  [cnpAPI v12.28] New value MIT added in existing enum order channel.

==Version 12.27.0 (Oct 10, 2022)
Note: It contains changes from cnpAPI v12.25, v12.26 and v12.27. In case you need any feature supported by cnpAPI v12.25, v12.26 or v12.27, please use SDK version 12.27.0.

* Change:  [cnpAPI v12.27] Added new element authMax in authorization/ sale response.
* Change:  [cnpAPI v12.27] Added new elements authMaxApplied, networkTokenApplied, networkToken, authMaxResponseCode, authMaxResponseMessage in new element authMax.
* Change:  [cnpAPI v12.27] Added new values FIFA, FIVC, FISC, FISD, FIPC, FIPD, FIRC, FIRD in existing actionTypeEnum Enum.

* Change:  [cnpAPI v12.26] Added new element passengerTransportData for authorization/ sale/ capture/ forceCapture/ captureGivenAuth/ credit/ depositTransactionReversal/ refundTransactionReversal requests.
* Change:  [cnpAPI v12.26] Added new elements passengerName, ticketNumber, issuingCarrier, carrierName, restrictedTicketIndicator, numberOfAdults, numberOfChildren, customerCode, arrivalDate, issueDate, travelAgencyCode, travelAgencyName, computerizedReservationSystem, creditReasonIndicator, ticketChangeIndicator, ticketIssuerAddress, exchangeTicketNumber, exchangeAmount, exchangeFeeAmount, tripLegData for new element passengerTransportData.
* Change:  [cnpAPI v12.26] Added new elements tripLegNumber, departureCode, carrierCode, serviceClass, stopOverCode, destinationCode, fareBasisCode, departureDate, originCity, travelNumber, departureTime, arrivalTime added in new element tripLegData.
* Change:  [cnpAPI v12.26] Added existing element additionalCOFData in authReversal/ credit requests.

* Change:  [cnpAPI v12.25] Added new elements to support guaranteed payments for Authorization/ Sale - overridePolicy, fsErrorCode, merchantAccountStatus, productEnrolled, decisionPurpose, fraudSwitchIndicator.
* Change:  [cnpAPI v12.25] Added a new Enum productEnrolledEnum (GUARPAY1, GUARPAY2, GUARPAY3) to support productEnrolled.
* Change:  [cnpAPI v12.25] Added a new Enum decisionPurposeEnum (CONSIDER_DECISION, INFORMATION_ONLY) to support decisionPurpose.
* Change:  [cnpAPI v12.25] Added a new Enum fraudSwitchIndicatorEnum (PRE, POST) to support fraudSwitchIndicator.
* Change:  [cnpAPI v12.25] Added new elements to support cruise lines for lodgingInfo element for Authorization/ Sale - bookingID, passengerName, propertyAddress (name, city, region, country), travelPackageIndicator, smokingPreference, numberOfRooms, tollFreePhoneNumber.
* Change:  [cnpAPI v12.25] Added a new Enum travelPackageIndicatorEnum (CarRentalReservation, AirlineReservation, Both, Unknown) to support travelPackageIndicator.

==Version 12.24.0 (April 20, 2022)
Note: It contains changes from cnpAPI v12.19, v12.20, v12.21, v12.22 & 12.23. In case you need any feature supported by cnpAPI v12.19, v12.20, v12.21, v12.22 or v12.23, please use SDK version 12.24.0.

* Feature: [cnpAPI v12.24] New Enum value highRiskSecuritiesPurchase, fundTransfer and walletTransfer included in businessIndicatorEnum.
* Feature: [cnpAPI v12.24] New element fraudCheckStatus is added for authorization and sale transactions.
* Feature: [cnpAPI v12.24] New element crypto is added for authorization, captureGivenAuth and  sale transactions.
* Feature: [cnpAPI v12.23] New Enum value buyOnlinePickUpInStore included in businessIndicatorEnum.
* Feature: [cnpAPI v12.23] sellerId & url elements are added for new element retailerAddress and for existing contact type elements.
* Feature: [cnpAPI v12.23] New element additionalCOFData is added for authorization, captureGivenAuth and  sale transactions.
* Feature: [cnpAPI v12.23] New element retailerAddress is added for authorization, captureGivenAuth and  sale transactions.
* Feature: [cnpAPI v12.23] New element orderChannel is added for authorization and  sale transactions.
* Feature: [cnpAPI v12.23] New elements accountUsername, userAccountNumber, userAccountEmail, membershipId, membershipPhone, membershipEmail, membershipName, accountCreatedDate and userAccountPhone added for custmerInfo element.
* Feature: [cnpAPI v12.23] New elements discountCode, discountPercent and fulfilmentMethodType added to enhancedData element.
* Feature: [cnpAPI v12.23] New elements itemCategory, itemSubCategory, productId and productName added to lineItemData element.
* Feature: [cnpAPI v12.22] New element vendorAddress is added in vendorCredit and vendorDebit transaction type.
* Feature: [cnpAPI v12.22] Optional element cardholderAddress is added to fastAccessFunding transaction type.
* Feature: [cnpAPI v12.22] New addressType is added to support vendorAddress and cardholderAddress.
* Feature: [cnpAPI v12.21] fraudCheck authenticationValue can support upto 512 characters now.
* Feature: [cnpAPI v12.20] New methodOfPaymentTypeEnum value IC for Interac Payment has been added.
* Feature: [cnpAPI v12.19] OrderID element now supports 256 characters.
* Feature: [cnpAPI v12.19] Optional OrderID element is supported in Capture and Credit transactions.
* Feature: [cnpAPI v12.19] transactionReversal transaction is not supported in and after cnpAPI v12.19. It has been split into two different transactions:
			- depositTransactionReversal
			- refundTransactionReversal
* Removed: sendToCnpStream() method is being removed as it is not supported by cnpAPI anymore. The support is removed from the platform, so, it will not work with any SDK version.

==Version 12.17.1 (April 19, 2021)
* BugFix: Fixed delete batch files flag in CnpResponseProcessor

==Version 12.17.0 (October 30, 2020)
* Feature: Added businessIndicator support to forceCapture, captureGivenAuth, sale, credit, authorization

==Version 12.16.0 (October 12, 2020)
* Feature: Added a new transaction type: Transaction Reversal

==Version 12.15.0 (October 5, 2020)
* Feature: Added an AuthenticationShopperID to cardTokenType
* Feature: Added a copayAmount to healthcareAmounts
* Feature: Created echeckTokenRoutingNumber and echeckTokenAccountTypeEnum types and set routingNum and accType in echeckTokenType to those new types
* Feature: Added debitResponse and debitMessage fields to pinlessDebitResponse

==Version 12.14.1 (October 6, 2020)
* BugFix: Added tokenAuthenticationValue as a PHP field

==Version 12.14.0 (September 28, 2020)
* Feature: Added PinlessDebitResponse as an optional child of AuthorizationResponse, AuthReversalResponse,
* captureResponse, saleResponse
* Feature: Added TokenAuthenticationValue as optional child of CardholderAuthentication

==Version 12.13.0 (June 12, 2020)
* Feature: Added location as optional element of all online responses

==Version 12.11.0 (January 23, 2020)
* Feature: Added merchantCatagoryCode support to forceCapture, captureGivenAuth, sale, credit, authorization
* Feature: Added authenticationProtocolVersion support to fraudCheckType

==Version 12.10.0 (November 11, 2019)
* Feature: Added skipRealtimeAU support to authorization and sale
* Feature: Added support for accountUpdateSource and numAccountUpdates in responses

==Version 12.9.0 (October 28, 2019)
* Feature: Added support for customerCredit, customerDebit
* Feature: Added support for payoutOrgCredit, payoutOrgDebit
* Feature: Added fundingCustomerID support
* Feature: Enhancements to fastAccessFunding
* Feature: Added configurable timeout for getting batch responses

==Version 12.8.0 (Jun 24, 2019)
* Feature: TokenURL support

==Version 12.7.5 (Jul 22, 2019)
* BugFix: Modified debug_backtrace parameter to be compatible with both PHP pre and post 5.4.0

==Version 12.7.4 (Jul 16, 2019)
* BugFix: Added recycleId

==Version 12.7.3 (Jun 24, 2019)
* BugFix: Added CheckoutId
* BugFix: Removed Proxy settings for cert tests
* BugFix: Removed unused unit tests

==Version 12.7.1 (Jan 14, 2019)
* BugFix: Removed debug statements

==Version 12.7.0 (Dec 27, 2018)
* Feature: Full functions for XML v12.7
* Feature: add support for new type: echeckTypeCtx
* Feature: add 4 new types in cnpBatch: vendorCreditCtx, vendorDebitCtx, submerchantCreditCtx, submerchantDebitCtx

==Version 12.5.4 (Dec 11, 2018)
* Feature: User Exception handling support

==Version 12.5.3 (Dec 11, 2018)
* BugFix: Created custom exception handler replacing a test framework

==Version 12.5.2 (Dec 10, 2018)
* Feature: Multibyte encoding support for XML requests
* BugFix: Validation to terminate current execution of script

==Version 12.5.1 (Nov 7, 2018)
* BugFix: Removed debug statements

==Version 12.5.0 (Oct 11, 2018)
* Feature: Full functions for XML v12.5
* Feature: add a new type: encryptedCcAccountNumberType
* Feature: add support for encryption on registerTokenRequestType
* Feature: add paymentAccountReferenceNumber in authorizationResponse and saleResponse
* Feature: add disbursementTypeEnum in fastAccessFunding

==Version 12.3.0 (May 14, 2018)
* Feature: Added support for lodging information in authorization, sale, capture, captureGivenAuth, forceCapture and credit
* Feature: Added translateToLowValueToken transaction type
* Feature: Replaced routingPreference with pinlessDebitRequest in sale
* Feature: Added support for showStatusOnly in queryTransaction
* Feature: Added eventType, accountLogin and accountPasshash to fraudCheck
* Bug Fix: Added createDiscount, updateDiscount, deleteDiscount, createAddOn, updateAddOn and deleteAddOn 
           fields to recurring transactions.

==Version 12.1.0 (March 26, 2018)
* Feature: Added support for Visa card on file transactions
* Feature: Added fastAccessFunding transaction

==Version 12.0.1 (March 16, 2018)
* Feature: PGP support for batches

==Version 11.0.1 (May 4, 2017)
* Feature: update phpseclib to v2.0.4

==Version 11.0 (Apr 10, 2017)
* Feature: implement Vantiv eCommerce XMLv11.0
* Feature: SEPA Support
* Feature: iDEAL support
* Feature: new giftCard transactions
* Feature: giroPay support
* Feature: Network Enhancements mandates
* Feature: add support for raw network response
* Bug fix: specify char encoding in HTTP header

==Version 9.3.3
Update SSL version to 6

==Version 9.3.2
HTTP timeout set to 500ms

==Version 9.3.1 (March 9, 2015)
*Feature: PFIF instruction transaction support was added

== Version 9.03.0 (February 17, 2015)
* Feature: Applepay support was added
* Feature: Secondary amount support was added
* Feature: Add support for new Batch transaction in version 9.3 schema

==version 8.29.0 (January 22,2014)
* Feature: Apple pay and secondary amounts now available.

==version 8.27.0 (Auguest 26,2014)
* Feature: Added samples for each type of transactions

== version 8.25.0 (March 7, 2014)
* Feature: Added support for ROAM: Authorization, ForceCapture, CaptureGivenAuth, Sale, Credit

== version 8.24.0 (February 13, 2014)
* Feature: add triggered rules for advancedFraudCheckResult

== Version 8.23.0 (December 18, 2013)
* Feature: advancedFraudChecks now available in authorization and sale 
* Feature: catLevel (Cardholder Activated Terminal) now available in pos
* Feature: advancedFraudResults now available in fraudResult

== Version 8.22.0 (December 18, 2013)
* Feature: Support for the following new gift card transactions:
ActivateReversal, DeactivateReversal, LoadReversal, RefundReversal,
UnloadReversal, DepositReversal
* Feature: UpdateSubscription now can take token or paypage updates, and
can return token in the response
* Feature: Support for virtualGiftCard in giftCard activations and can be returned in EnhancedAuthResponses
* Feature: Gift Card responses can be returned from AuthReversal

== Version 8.21.0 (December 18, 2013)
* Feature: Support for the following new recurring transactions:
CreatePlan, UpdatePlan
* Feature: Support for the following new gift card transactions:
Activate, Deactivate, Load, Unload, Balance Inquiry
* Feature: Gift card responses are now returned as part of
authorzationResponse, authReversalResponse, captureResponse,
forceCapture, captureGivenAuthResponse, saleResponse, creditResponse
* Feature: fraudResult is now returned as part of captureResponse,
forceCaptureResponse, captureGivenAuthResponse, creditResponse

== Version 8.20.0 (December 17, 2013)
* Feature: More initial support for Recurring, including the ability to update or cancel subscriptions
* Cleanup: Move phpseclib to composer

== Version 8.19.0 (July 30, 2013)
* Feature: Added deptRepayment as a choice for authorizations, forceCapture, captureGivenAuth, sale
* Feature: More initial support for Recurring, including a backwards incompatible changing a field from numberOfPaymentsRemaining to numberOfPayments
* Feature: Add merchantData to forceRequest and captureGivenAuth

== Version 8.18.0 (July 15, 2013)
* Feature: Add initial support for recurring to sales

== Version 8.17.0 (March 29, 2013)
* Feature: Add recyling to voidResponse
* Feature: Add support for surcharging credit card transactions
* Feature: Add support for specifying the terminalId on a pos transaction
* Feature: Add support for pos transactions on tied refunds

== Version 8.15.0 (January 31, 2013)

* Feature: Add merchantData to echeck verifications and echeck redeposits

== Version 8.14.0 (January 30, 2013)

* Feature: Merged pull request #12 from iToto to make convert DOMDocument to string for debugging
* Bugfix: Merged pull request #14 from johnholden to truncate strings that are too long, thus preventing some xml validation errors
* Feature: Temporary storage of card validation num on tokens
* Feature: Updating card validation numbers on tokens

== Version 8.13.1 (June 15, 2012)

* Feature: Add support for accessing response as a tree using simplexml.  Access is provided if $treeResponse=true is passed to the LitleOnlineRequest constructor

== Version 8.13 (May 18, 2012)

* Feature: Authorizations and Sales can accept an optional fraudFilterOverride parameter

== Version 8.12.2 (May 8, 2012)

* Bugfix: Add support for line item data and tax detail to enhanced data

== Version 8.12.1 (April 23, 2012)

* Bugfix: Add support for id, customerid and reportgroup

== Version 8.12 (April 17, 2012)

* Bugfix: Add support for MerchantData on auth and sale
* Feature: Add support for actionReason on credit
* Feature: Track SDK Usage

== Version 8.10.0 (April 2, 2012)

* Initial release
