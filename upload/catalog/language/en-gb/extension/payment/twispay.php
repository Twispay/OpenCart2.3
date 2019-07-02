<?php
/**
 * Twispay Language Configurator
 *
 * Twispay general language handler for front-store
 *
 * @package  Twispay/Language
 * @version  1.0.1
 */

/* General */
$_['text_title'] = 'Credit card secure payment| Twispay';
$_['button_confirm'] = 'Pay now';
$_['transaction_exists'] = '[RESPONSE-ERROR]: transactions exists';
$_['no_post'] = '[RESPONSE-ERROR]: no_post';

$_['general_error_title'] = 'An error occurred:';
$_['general_error_desc_f'] = 'The payment could not be processed. Please';
$_['general_error_desc_try_again'] = ' try again';
$_['general_error_desc_or'] = ' or';
$_['general_error_desc_contact'] = ' contact';
$_['general_error_desc_s'] = ' the website administrator.';
$_['general_error_hold_notice'] = ' Payment is on hold.';
$_['general_error_invalid_key'] = ' Invalid secure key.';
$_['general_error_invalid_order'] = ' Order does not exist.';
$_['general_error_invalid_private'] = ' Private key is not valid.';

/* Administrator Order Notice */
$_['a_order_status_notice'] = 'Twispay payment finalised successfully';
$_['a_order_refunded_notice'] = 'Website manager pressed on refund button successfully';
$_['a_order_cancelled_notice'] = 'Website manager pressed on cancel button successfully';
$_['a_order_failed_notice'] = 'Twispay payment failed';
$_['a_order_hold_notice'] = 'Twispay payment is on hold';

/* Validation LOG insertor */
$_['log_ok_string_decrypted'] = '[RESPONSE]: decrypted string: ';
$_['log_ok_response_data'] = '[RESPONSE]: Data: ';
$_['log_ok_status_complete'] = '[RESPONSE]: Status complete-ok for order ID: ';
$_['log_ok_status_refund'] = '[RESPONSE]: Status refund-ok for order ID: ';
$_['log_ok_status_failed'] = '[RESPONSE]: Status failed for order ID: ';
$_['log_ok_status_hold'] = '[RESPONSE]: Status on-hold for order ID: ';
$_['log_ok_status_uncertain'] = '[RESPONSE]: Status uncertain for order ID: ';
$_['log_ok_validating_complete'] = '[RESPONSE]: Validating completed for order ID: ';

$_['log_error_validating_failed'] = '[RESPONSE-ERROR]: Validation failed.';
$_['log_error_decryption_error'] = '[RESPONSE-ERROR]: Decryption failed.';
$_['log_error_invalid_order'] = '[RESPONSE-ERROR]: Order does not exist.';
$_['log_error_wrong_status'] = '[RESPONSE-ERROR]: Wrong status: ';
$_['log_error_empty_status'] = '[RESPONSE-ERROR]: Empty status';
$_['log_error_empty_identifier'] = '[RESPONSE-ERROR]: Empty identifier';
$_['log_error_empty_external'] = '[RESPONSE-ERROR]: Empty externalOrderId';
$_['log_error_empty_transaction'] = '[RESPONSE-ERROR]: Empty transactionId';
$_['log_error_empty_response'] = ' [RESPONSE-ERROR]: Received empty response.';
$_['log_error_invalid_private'] = '[RESPONSE-ERROR]: Private key is not valid.';
$_['log_error_invalid_key'] = '[RESPONSE-ERROR]: Invalid order identification key.';
$_['log_error_openssl'] = '[RESPONSE-ERROR]: opensslResult: ';

/* Settings Twispay tab */
// $_['s_title'] = 'Twispay';
// $_['s_description'] = 'Have your customers pay with Twispay payment gateway.';
// $_['s_enable_disable_title'] = 'Enable/Disable';
// $_['s_enable_disable_label'] = 'Enable Twispay Payments';
// $_['s_title_title'] = 'Title';
// $_['s_title_desc'] = 'This controls the title which the customer sees during checkout.';
// $_['s_description_title'] = 'Description';
// $_['s_description_desc'] = 'This controls the description which the customer sees during checkout.';
// $_['s_description_default'] = 'Pay with Twispay.';
// $_['s_enable_methods_title'] = 'Enable for shipping methods';
// $_['s_enable_methods_desc'] = 'If Twispay is only available for certain shipping methods, set it up here. Leave blank to enable for all methods.';
// $_['s_enable_methods_placeholder'] = 'Select shipping methods';
// $_['s_vorder_title'] = 'Accept for virtual orders';
// $_['s_vorder_desc'] = 'Accept Twispay if the order is virtual';

/* Order Recieved Confirmation title */
// $_['order_confirmation_title'] = 'Thank you. Your transaction is approved.';

/* Twispay Processor( Redirect page to Twispay ) */
// $_['twispay_processor_error_general'] = 'You are not allowed to access this file.';
// $_['twispay_processor_error_no_item'] = 'The order has no items.';
// $_['twispay_processor_error_more_items'] = 'Orders with subscriptions cannot have other products too.';
// $_['twispay_processor_error_missing_configuration'] = 'Missing configuration for plugin.';

/* Subscriptions section */
// $_['subscriptions_sync_label'] = 'Synchronize subscriptions';
// $_['subscriptions_sync_desc'] = 'Synchronize the local status of all subscriptions with the server status.';
// $_['subscriptions_sync_button'] = 'Synchronize';
// $_['subscriptions_log_ok_set_status'] = '[RESPONSE]: Server status set for order ID: ';
// $_['subscriptions_log_error_set_status'] = '[RESPONSE-ERROR]: Failed to set server status for order ID: ';
// $_['subscriptions_log_error_get_status'] = '[RESPONSE-ERROR]: Failed to get server status for order ID: ';
// $_['subscriptions_log_error_call_failed'] = '[RESPONSE-ERROR]: Failed to call server: ';
// $_['subscriptions_log_error_http_code'] = '[RESPONSE-ERROR]: Unexpected HTTP response code: ';
?>
