<?php
/**
 * Twispay Language Configurator
 *
 * Twispay general language handler for everything
 *
 * @package  Twispay/Language
 * @category Admin/Front
 * @version  1.0.1
 */

/* Added from Opencart */
$_['text_title'] = 'Credit card secure payment| Twispay';
$_['button_confirm'] = 'Pay now';
$_['transaction_exists'] = '[RESPONSE-ERROR]: transactions exists';
$_['no_post'] = '[RESPONSE-ERROR]: no_post';

/* Configuration panel from Administrator */
$_['no_s'] = 'here';
$_['configuration_title'] = 'Configuration';
$_['configuration_edit_notice'] = 'Configuration has been edited successfully.';
$_['configuration_subtitle'] = 'Twispay general settings.';
$_['live_mode_label'] = 'Live mode';
$_['live_mode_desc'] = 'Select "Yes" if you want to use the payment gateway in Production Mode or "No" if you want to use it in Staging Mode.';
$_['staging_id_label'] = 'Staging Site ID';
$_['staging_id_desc'] = 'Enter the Site ID for Staging Mode. You can get one from';
$_['staging_key_label'] = 'Staging Private Key';
$_['staging_key_desc'] = 'Enter the Private Key for Staging Mode. You can get one from';
$_['live_id_label'] = 'Live Site ID';
$_['live_id_desc'] = 'Enter the Site ID for Live Mode. You can get one from';
$_['live_key_label'] = 'Live Private Key';
$_['live_key_desc'] = 'Enter the Private Key for Live Mode. You can get one from';
$_['s_t_s_notification_label'] = 'Server-to-server notification URL';
$_['s_t_s_notification_desc'] = 'Put this URL in your Twispay account.';
$_['r_custom_thankyou_label'] = 'Redirect to custom Thank you page';
$_['r_custom_thankyou_desc_f'] = 'If you want to display custom Thank you page, set it up here. You can create new custom page from';
$_['r_custom_thankyou_desc_s'] = 'here';
$_['suppress_email_label'] = 'Suppress default payment receipt emails';
$_['suppress_email_desc'] = 'Option to suppress the communication sent by the ecommerce system, in order to configure it from Twispay’s Merchant interface.';
$_['configuration_save_button'] = 'Save changes';
$_['live_mode_option_true'] = 'Yes';
$_['live_mode_option_false'] = 'No';
$_['get_all_pages_default'] = 'Default';
$_['contact_email_o'] = 'Contact email(Optional)';
$_['contact_email_o_desc'] = 'This email will be used on the payment error page.';


/* Transaction list from Administrator */
$_['transaction_title'] = 'Transaction list';
$_['transaction_list_search_title'] = 'Search Order';
$_['transaction_list_all_views'] = 'All';
$_['transaction_list_refund_title'] = 'Refund transaction';
$_['transaction_list_recurring_title'] = 'Cancel recurring on this order';
$_['transaction_list_id'] = 'ID';
$_['transaction_list_id_cart'] = 'Order reference';
$_['transaction_list_customer_name'] = 'Customer name';
$_['transaction_list_transactionId'] = 'Transaction ID';
$_['transaction_list_status'] = 'Status';
$_['transaction_list_checkout_url'] = 'Checkout url';
$_['transaction_list_refund_ptitle'] = 'Refund Payment Transaction';
$_['transaction_list_refund_subtitle'] = 'Following payment transaction will be refunded:';
$_['transaction_list_confirm_title'] = 'Confirm';
$_['transaction_error_refund'] = 'Refund could not been processed.';
$_['transaction_error_recurring'] = 'Recurring could not been processed.';
$_['transaction_success_refund'] = 'Refund processed successfully. Refresh the page in seconds to see the update.';
$_['transaction_success_recurring'] = 'Recurring processed successfully.';
$_['transaction_list_recurring_ptitle'] = 'Cancel a recurring order';
$_['transaction_list_recurring_subtitle'] = 'Following recurring order will be canceled:';
$_['transaction_sync_finished'] = 'Subscriptions synchronization finished.';


/* Transaction log from Administrator */
$_['transaction_log_title'] = 'Transaction log';
$_['transaction_log_no_log'] = 'No log recorded yet.';
$_['transaction_log_subtitle'] = 'Transaction log in raw form.';


/* Administrator Dashboard left-side menu */
$_['menu_main_title'] = 'Twispay';
$_['menu_configuration_tab'] = 'Configuration';
$_['menu_transaction_tab'] = 'Transaction list';
$_['menu_transaction_log_tab'] = 'Transaction log';


/* Settings Twispay tab */
$_['s_title'] = 'Twispay';
$_['s_description'] = 'Have your customers pay with Twispay payment gateway.';
$_['s_enable_disable_title'] = 'Enable/Disable';
$_['s_enable_disable_label'] = 'Enable Twispay Payments';
$_['s_title_title'] = 'Title';
$_['s_title_desc'] = 'This controls the title which the customer sees during checkout.';
$_['s_description_title'] = 'Description';
$_['s_description_desc'] = 'This controls the description which the customer sees during checkout.';
$_['s_description_default'] = 'Pay with Twispay.';
$_['s_enable_methods_title'] = 'Enable for shipping methods';
$_['s_enable_methods_desc'] = 'If Twispay is only available for certain shipping methods, set it up here. Leave blank to enable for all methods.';
$_['s_enable_methods_placeholder'] = 'Select shipping methods';
$_['s_vorder_title'] = 'Accept for virtual orders';
$_['s_vorder_desc'] = 'Accept Twispay if the order is virtual';


/* Order Recieved Confirmation title */
$_['order_confirmation_title'] = 'Thank you. Your transaction is approved.';


/* Twispay Processor( Redirect page to Twispay ) */
$_['twispay_processor_error_general'] = 'You are not allowed to access this file.';
$_['twispay_processor_error_no_item'] = 'The order has no items.';
$_['twispay_processor_error_more_items'] = 'Orders with subscriptions cannot have other products too.';
$_['twispay_processor_error_missing_configuration'] = 'Missing configuration for plugin.';


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


/* Subscriptions section */
$_['subscriptions_sync_label'] = 'Synchronize subscriptions';
$_['subscriptions_sync_desc'] = 'Synchronize the local status of all subscriptions with the server status.';
$_['subscriptions_sync_button'] = 'Synchronize';
$_['subscriptions_log_ok_set_status'] = '[RESPONSE]: Server status set for order ID: ';
$_['subscriptions_log_error_set_status'] = '[RESPONSE-ERROR]: Failed to set server status for order ID: ';
$_['subscriptions_log_error_get_status'] = '[RESPONSE-ERROR]: Failed to get server status for order ID: ';
$_['subscriptions_log_error_call_failed'] = '[RESPONSE-ERROR]: Failed to call server: ';
$_['subscriptions_log_error_http_code'] = '[RESPONSE-ERROR]: Unexpected HTTP response code: ';


/* Administrator Order Notice */
$_['a_order_status_notice'] = 'Twispay payment finalised successfully';
$_['a_order_refunded_notice'] = 'Website manager pressed on refund button successfully';
$_['a_order_cancelled_notice'] = 'Website manager pressed on cancel button successfully';
$_['a_order_failed_notice'] = 'Twispay payment failed';
$_['a_order_hold_notice'] = 'Twispay payment is on hold';


/* Others */
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
