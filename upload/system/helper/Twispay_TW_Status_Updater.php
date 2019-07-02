<?php
/**
 * Twispay Helpers
 *
 * Updates the statused of orders and subscriptions based
 *  on the status read from the server response.
 *
 * @package  Twispay/Front
 * @category Front
 * @author   @TODO
 * @version  0.0.1
 */

/* Security class check */
if ( ! class_exists( 'Twispay_TW_Status_Updater' ) ) :
    /**
     * Twispay Helper Class
     *
     * @class   Twispay_TW_Status_Updater
     * @version 0.0.1
     *
     *
     * Class that implements methods to update the statuses
     * of orders and subscriptions based on the status received
     * from the server.
     */
    require_once('Twispay_TW_Default_Thankyou.php');
    class Twispay_TW_Status_Updater{
        /* Array containing the possible result statuses. */
        public static $RESULT_STATUSES = [ 'UNCERTAIN' => 'uncertain' /* No response from provider */
                                         , 'IN_PROGRESS' => 'in-progress' /* Authorized */
                                         , 'COMPLETE_OK' => 'complete-ok' /* Captured */
                                         , 'COMPLETE_FAIL' => 'complete-failed' /* Not authorized */
                                         , 'CANCEL_OK' => 'cancel-ok' /* Capture reversal */
                                         , 'REFUND_OK' => 'refund-ok' /* Settlement reversal */
                                         , 'VOID_OK' => 'void-ok' /* Authorization reversal */
                                         , 'CHARGE_BACK' => 'charge-back' /* Charge-back received */
                                         , 'THREE_D_PENDING' => '3d-pending' /* Waiting for 3d authentication */
                                         , 'EXPIRING' => 'expiring' /* The recurring order has expired */
                                         ];

        /**
         * Update the status of an order according to the received server status.
         *
         * @param orderId: The id of the order for which to update the status.
         * @param decrypted: Decrypted order message.
         * @param that: Controller instance use for accessing runtime values like configuration, active language, etc.
         *
         * @return void
         */
        public static function updateStatus_backUrl($orderId, $decrypted, $that){
            $that->load->model('extension/payment/twispay');
            /* Extract the order. */
            $order = $that->model_checkout_order->getOrder($orderId);

            /* Extract the status received from server. */
            $serverStatus = (empty($decrypted['status'])) ? ($decrypted['transactionStatus']) : ($decrypted['status']);

            switch ($serverStatus) {
                case Twispay_TW_Status_Updater::$RESULT_STATUSES['COMPLETE_FAIL']:
                    /* Mark order as Failed. */
                    $that->model_checkout_order->addOrderHistory($orderId, 10/*Failed*/, $that->language->get('a_order_failed_notice'), true);

                    Twispay_TW_Logger::twispay_tw_log($that->language->get('log_ok_status_failed') . $orderId);
                    Twispay_TW_Notification::notice_to_checkout($that);
                break;

                case Twispay_TW_Status_Updater::$RESULT_STATUSES['THREE_D_PENDING']:
                    /* Mark order as Pending. */
                    $that->model_checkout_order->addOrderHistory($orderId, 1/*Pending*/, $that->language->get('a_order_hold_notice'), true);

                    Twispay_TW_Logger::twispay_tw_log($that->language->get('log_ok_status_hold') . $orderId);
                    Twispay_TW_Notification::notice_to_checkout($that,'',$that->lang('general_error_hold_notice'));
                break;

                case Twispay_TW_Status_Updater::$RESULT_STATUSES['IN_PROGRESS']:
                case Twispay_TW_Status_Updater::$RESULT_STATUSES['COMPLETE_OK']:
                    /* Create invoice */
                    if (!$order['invoice_no']) {
                      $invoice = $that->model_extension_payment_twispay->createInvoiceNo($orderId,$order['invoice_prefix']);
                    }

                    /* Mark order as Processing. */
                    $order_status_id = (!empty($that->config->get('twispay_order_status_id'))) ? $that->config->get('twispay_order_status_id') : 2;
                    $that->model_checkout_order->addOrderHistory($orderId, $order_status_id /*Twistpay Config Or Processing*/, 'Paid Twispay #'.$decrypted['transactionId'], true);

                    Twispay_TW_Logger::twispay_tw_log($that->language->get('log_ok_status_complete') . $orderId);

                    /* Redirect to Twispay "Thank you Page" if it is set, if not, redirect to default "Thank you Page" */
                    if ( $that->config->get('twispay_redirect_page') != null && strlen($that->config->get('twispay_redirect_page'))) {
                      $base_url = HTTPS_SERVER;
                      $page_to_redirect = $base_url.$that->config->get('twispay_redirect_page');
                      echo '<meta http-equiv="refresh" content="1;url='. $page_to_redirect .'" />';
                      header('Refresh: 1;url=' . $page_to_redirect);
                      // DEBUG
                      // print_r('redirect to custom: '.$page_to_redirect);
                    } else {
                        new Twispay_TW_Default_Thankyou();
                    }

                break;

                default:
                    Twispay_TW_Notification::notice_to_checkout($that);
                    Twispay_TW_Logger::twispay_tw_log($that->language->get('log_error_wrong_status') . $serverStatus);
                    break;
            }
        }

        /**
         * Update the status of an subscription according to the received server status.
         *
         * @param orderId: The ID of the order to be updated.
         * @param serverStatus: The status received from server.
         * @param that: Controller instance use for accessing runtime values like configuration, active language, etc.
         *
         * @return void
         */

        public static function updateStatus_IPN($orderId, $decrypted, $that){
            $that->load->model('extension/payment/twispay');
            /* Extract the order. */
            $order = $that->model_checkout_order->getOrder($orderId);

            /* Extract the status received from server. */
            $serverStatus = (empty($decrypted['status'])) ? ($decrypted['transactionStatus']) : ($decrypted['status']);

            switch ($serverStatus) {
                case Twispay_TW_Status_Updater::$RESULT_STATUSES['COMPLETE_FAIL']:
                    /* Mark order as Failed. */
                    $that->model_checkout_order->addOrderHistory($orderId, 10/*Failed*/, $that->language->get('a_order_failed_notice'), true);
                    Twispay_TW_Logger::twispay_tw_log($that->language->get('log_ok_status_failed') . $orderId);
                break;

                case Twispay_TW_Status_Updater::$RESULT_STATUSES['CANCEL_OK']:
                case Twispay_TW_Status_Updater::$RESULT_STATUSES['REFUND_OK']:
                case Twispay_TW_Status_Updater::$RESULT_STATUSES['VOID_OK']:
                case Twispay_TW_Status_Updater::$RESULT_STATUSES['CHARGE_BACK']:
                    /* Mark order as refunded. */
                    $that->model_checkout_order->addOrderHistory($orderId, 11/*Refounded*/, $that->language->get('a_order_refunded_notice'), true);
                    Twispay_TW_Logger::twispay_tw_log($that->language->get('log_ok_status_refund') . $orderId);
                break;

                case Twispay_TW_Status_Updater::$RESULT_STATUSES['THREE_D_PENDING']:
                    /* Mark order as on-hold. */
                    $that->model_checkout_order->addOrderHistory($orderId, 1/*`Pending`*/, $that->language->get('a_order_hold_notice'), true);

                    Twispay_TW_Logger::twispay_tw_log($that->language->get('log_ok_status_hold') . $orderId);
                break;

                case Twispay_TW_Status_Updater::$RESULT_STATUSES['IN_PROGRESS']:
                case Twispay_TW_Status_Updater::$RESULT_STATUSES['COMPLETE_OK']:
                    /* Mark order as completed. */
                    $that->model_checkout_order->addOrderHistory($orderId, 2/*Processing*/, $that->language->get('a_order_status_notice'), true);
                    Twispay_TW_Logger::twispay_tw_log($that->language->get('log_ok_status_complete') . $orderId);
                break;

                default:
                  Twispay_TW_Logger::twispay_tw_log($that->language->get('log_error_wrong_status') . $serverStatus);
                break;
            }
        }
    }
endif; /* End if class_exists. */
