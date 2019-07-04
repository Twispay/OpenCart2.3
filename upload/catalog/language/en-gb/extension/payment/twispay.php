<?php
/**
 * Twispay Language Configurator
 *
 * Twispay general language handler for front-store
 *
 * @author   Twistpay
 * @version  1.0.0
 */

/* General */
$_['text_title']                    = 'Credit card secure payment| Twispay';
$_['button_confirm']                = 'Pay now';
$_['processing']                    = 'Processing ...';
$_['no_post']                       = '[RESPONSE-ERROR]: no_post';

$_['general_error_title']           = 'An error occurred:';
$_['general_error_desc_f']          = 'The payment could not be processed. Please';
$_['general_error_desc_try_again']  = ' try again';
$_['general_error_desc_or']         = ' or';
$_['general_error_desc_contact']    = ' contact';
$_['general_error_desc_s']          = ' the website administrator.';
$_['general_error_hold_notice']     = ' Payment is on hold.';

/* Order Notice */
$_['a_order_status_notice']         = 'Twispay payment finalised successfully';
$_['a_order_refunded_notice']       = 'Website manager pressed on refund button successfully';
$_['a_order_failed_notice']         = 'Twispay payment failed';
$_['a_order_hold_notice']           = 'Twispay payment is on hold';

/* Validation LOG insertor */
$_['log_ok_string_decrypted']       = '[RESPONSE]: decrypted string: ';
$_['log_ok_response_data']          = '[RESPONSE]: Data: ';
$_['log_ok_status_complete']        = '[RESPONSE]: Status complete-ok for order ID: ';
$_['log_ok_status_refund']          = '[RESPONSE]: Status refund-ok for order ID: ';
$_['log_ok_status_failed']          = '[RESPONSE]: Status failed for order ID: ';
$_['log_ok_status_voided']          = '[RESPONSE]: Status voided for order ID: ';
$_['log_ok_status_cenceled']        = '[RESPONSE]: Status canceled for order ID: ';
$_['log_ok_status_charged_back']    = '[RESPONSE]: Status charged back for order ID: ';
$_['log_ok_status_hold']            = '[RESPONSE]: Status on-hold for order ID: ';
$_['log_ok_validating_complete']    = '[RESPONSE]: Validating completed for order ID: ';

$_['log_error_validating_failed']   = '[RESPONSE-ERROR]: Validation failed.';
$_['log_error_decryption_error']    = '[RESPONSE-ERROR]: Decryption failed.';
$_['log_error_invalid_order']       = '[RESPONSE-ERROR]: Order does not exist.';
$_['log_error_wrong_status']        = '[RESPONSE-ERROR]: Wrong status: ';
$_['log_error_empty_status']        = '[RESPONSE-ERROR]: Empty status';
$_['log_error_empty_identifier']    = '[RESPONSE-ERROR]: Empty identifier';
$_['log_error_empty_external']      = '[RESPONSE-ERROR]: Empty externalOrderId';
$_['log_error_empty_transaction']   = '[RESPONSE-ERROR]: Empty transactionId';
$_['log_error_empty_response']      = ' [RESPONSE-ERROR]: Received empty response.';
$_['log_error_invalid_private']     = '[RESPONSE-ERROR]: Private key is not valid.';
