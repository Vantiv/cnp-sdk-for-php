<?php
namespace cnp\sdk;
require_once realpath(__DIR__). '/../vendor/autoload.php';

exec("../vendor/phpunit/phpunit/composer/bin/phpunit --configuration ../phpunit.xml ",$output);
print_r($output);

require_once ("Authorization/AuthWithPaypageReID.php");
require_once ("Authorization/CnpAuthorizationTransaction.php");
require_once ("Authorization/CnpPaymentFullLifeCycleExample.php");
require_once ("Authorization/CnpAuthReversalTransaction.php");
require_once ("Authorization/CnpReAuthorizationTransaction.php");
require_once ("Authorization/CnpPartialAuthReversalTranasaction.php");
require_once ("Credit/CnpCreditTransaction.php");
require_once ("Credit/CnpRefundTransaction.php");
require_once ("Capture/CnpPartialCapture.php");
require_once ("Capture/CnpCaptureTransaction.php");
require_once ("Capture/CnpCaptureGivenAuthTransaction.php");
require_once ("Capture/CnpForceCaptureTransaction.php");
require_once ("Sale/CnpSaleTransaction.php");
require_once ("Other/CnpAvsOnlyTransaction.php");
require_once ("Other/CnpVoidTransaction.php");
require_once ("Other/RawProcessing.php");
require_once ("Other/RfrRequest.php");
require_once ("Token/CnpRegisterTokenTransaction.php");
require_once ("Token/CnpSaleWithTokenTransaction.php");
require_once ("Batch/SampleBatchDriver.php");
require_once ("Batch/MechaBatch.php");
require_once ("Batch/ConfiguredCnpBatchRequestsMaually.php");






