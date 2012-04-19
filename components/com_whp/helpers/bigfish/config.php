<?php

/**
 * BIGFISH_PAYMENTGATEWAY_STORE_NAME
 * Merchant ID used in the Payment Gateway system.
 */
define('BIGFISH_PAYMENTGATEWAY_STORE_NAME', 'fapadoskonyv');

/**
 * BIGFISH_PAYMENTGATEWAY_TEST_MODE
 * Specify to connect to the test or the production system of Payment Gateway.
 * Please change its value to false in your production environment.
 */
define('BIGFISH_PAYMENTGATEWAY_TEST_MODE', true);

/**
 * BIGFISH_PAYMENTGATEWAY_OUT_CHARSET
 * Payment Gateway sends all messages in UTF-8 character encoding.
 * If your system uses a different character encoding, this parameter should be changed.
 * (e.g. ISO-8859-2)
 */
define('BIGFISH_PAYMENTGATEWAY_OUT_CHARSET', 'UTF-8');

/**
 * BIGFISH_PAYMENTGATEWAY_OTP_PUBLIC_KEY
 * It is used with OTP two party payment to encrypt sensitive data.
 * Each merchant has unique private and public keys.
 */
define('BIGFISH_PAYMENTGATEWAY_OTP_PUBLIC_KEY', '');
?>