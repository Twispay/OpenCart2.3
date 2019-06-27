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
         * Update the status of an Woocommerce order according to the received server status.
         *
         * @param orderId: The id of the order for which to update the status.
         * @param decrypted: Decrypted order message.
         * @param that: Object controller
         *
         * @return void
         */
        public static function updateStatus_backUrl($order, $decrypted, $that){

            /* Extract the order id. */
            $orderId = $order['order_id'];
            /* Extract the status received from server. */
            $serverStatus = (empty($decrypted['status'])) ? ($decrypted['transactionStatus']) : ($decrypted['status']);

            // DEBUG
            // print_r('<pre>');
            // print_r($that->config);
            // print_r('</pre>');

            switch ($serverStatus) {
                case Twispay_TW_Status_Updater::$RESULT_STATUSES['COMPLETE_FAIL']:
                    /* Mark order as failed. */
                    $that->model_checkout_order->update($orderId, 10/*Failed*/, $that->language->get('a_order_failed_notice'), true);

                    // if(class_exists('WC_Subscriptions') && wcs_order_contains_subscription($order)){
                    //     WC_Subscriptions_Manager::process_subscription_payment_failure_on_order($order);
                    // }

                    Twispay_TW_Logger::twispay_tw_log($that->language->get('log_ok_status_failed') . $orderId);
                    Twispay_TW_Notification::notice_to_checkout($that);
                break;

                case Twispay_TW_Status_Updater::$RESULT_STATUSES['THREE_D_PENDING']:
                    /* Mark order as pending. */
                    $that->model_checkout_order->update($orderId, 1/*Pending*/, $that->language->get('a_order_hold_notice'), true);

                    Twispay_TW_Logger::twispay_tw_log($that->language->get('log_ok_status_hold') . $orderId);
                    Twispay_TW_Notification::notice_to_checkout($that,'',$that->lang('general_error_hold_notice'));
                break;

                case Twispay_TW_Status_Updater::$RESULT_STATUSES['IN_PROGRESS']:
                case Twispay_TW_Status_Updater::$RESULT_STATUSES['COMPLETE_OK']:

                    $that->load->model('checkout/order');
                    $that->load->model('extension/payment/twispay');

                    // Create invoice
                    if (!$order['invoice_no']) {
                      $invoice = $that->model_extension_payment_twispay->createInvoiceNo($orderId,$order['invoice_prefix']);
                      $decrypted['invoice'] = $invoice;
                      $that->model_extension_payment_twispay->loggTransaction($decrypted);
                    }

                    /* Mark order as completed. */
                    $order_status_id = (!empty($that->config->get('twispay_order_status_id'))) ? $that->config->get('twispay_order_status_id') : $that->config->get('config_order_status_id');
                    $that->model_checkout_order->addOrderHistory($orderId, $order_status_id, 'Paid Twispay #'.$decrypted['transactionId'], true);

                    // if(class_exists('WC_Subscriptions') && wcs_order_contains_subscription($order)){
                    //   $subscription = wcs_get_subscriptions_for_order($order);
                    //   $subscription = reset($subscription);
                    //
                    //   /* First payment on order, process payment & activate subscription. */
                    //   if ( 0 == $subscription->get_completed_payment_count() ) {
                    //       $order->payment_complete();
                    //
                    //       if(class_exists('WC_Subscriptions') ){
                    //           WC_Subscriptions_Manager::activate_subscriptions_for_order( $order );
                    //       }
                    //   } else {
                    //       if(class_exists('WC_Subscriptions') ){
                    //           WC_Subscriptions_Manager::process_subscription_payments_on_order( $order );
                    //       }
                    //   }
                    // }

                    Twispay_TW_Logger::twispay_tw_log($that->language->get('log_ok_status_complete') . $orderId);

                    /* Redirect to Twispay "Thank you Page" if it is set, if not, redirect to default "Thank you Page" */
                    if ( isset($that->configuration['thankyou_page']) && strlen($that->configuration['thankyou_page'])) {
                      // echo '<meta http-equiv="refresh" content="1;url='. $that->configuration['thankyou_page'] .'" />';
                      // header('Refresh: 1;url=' . $that->configuration['thankyou_page']);
                      print_r('redirect to custom: '.$that->configuration['thankyou_page']);
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
         * Update the status of an Woocommerce subscription according to the received server status.
         *
         * @param orderId: The ID of the order to be updated.
         * @param serverStatus: The status received from server.
         * @param tw_lang: The array of available messages.
         *
         * @return void
         */
        public static function updateStatus_IPN($orderId, $serverStatus, $that){
            /* Extract the order. */
            $that->load->model('checkout/order');
                        /* Extract the order. */
                        $order = $that->model_checkout_order->getOrder($orderId);
            // $order = wc_get_order($orderId);

            switch ($serverStatus) {
                case Twispay_TW_Status_Updater::$RESULT_STATUSES['COMPLETE_FAIL']:
                    /* Mark order as failed. */
                    $order->update_status('failed', __( $that->language->get('wa_order_failed_notice'), 'woocommerce' ));

                    if(class_exists('WC_Subscriptions') && wcs_order_contains_subscription($order)){
                        WC_Subscriptions_Manager::process_subscription_payment_failure_on_order($order);
                    }

                    Twispay_TW_Logger::twispay_tw_log($that->language->get('log_ok_status_failed') . $orderId);
                break;

                case Twispay_TW_Status_Updater::$RESULT_STATUSES['CANCEL_OK']:
                case Twispay_TW_Status_Updater::$RESULT_STATUSES['REFUND_OK']:
                case Twispay_TW_Status_Updater::$RESULT_STATUSES['VOID_OK']:
                case Twispay_TW_Status_Updater::$RESULT_STATUSES['CHARGE_BACK']:
                    /* Mark order as refunded. */
                    $order->update_status('refunded', __( $that->language->get('wa_order_refunded_notice'), 'woocommerce' ));

                    if(class_exists('WC_Subscriptions') && wcs_order_contains_subscription($order)){
                        WC_Subscriptions_Manager::cancel_subscriptions_for_order($order);
                    }

                    Twispay_TW_Logger::twispay_tw_log($that->language->get('log_ok_status_refund') . $orderId);
                break;

                case Twispay_TW_Status_Updater::$RESULT_STATUSES['THREE_D_PENDING']:
                    /* Mark order as on-hold. */
                    $order->update_status('on-hold', __( $that->language->get('wa_order_hold_notice'), 'woocommerce' ));

                    Twispay_TW_Logger::twispay_tw_log($that->language->get('log_ok_status_hold') . $orderId);
                break;

                case Twispay_TW_Status_Updater::$RESULT_STATUSES['IN_PROGRESS']:
                case Twispay_TW_Status_Updater::$RESULT_STATUSES['COMPLETE_OK']:
                    /* Mark order as completed. */
                    $order->update_status('processing', __( $that->language->get('wa_order_status_notice'), 'woocommerce' ));

                    if(class_exists('WC_Subscriptions') && wcs_order_contains_subscription($order)){
                      $subscription = wcs_get_subscriptions_for_order($order);
                      $subscription = reset($subscription);

                      /* First payment on order, process payment & activate subscription. */
                      if ( 0 == $subscription->get_completed_payment_count() ) {
                          $order->payment_complete();

                          if(class_exists('WC_Subscriptions') ){
                              WC_Subscriptions_Manager::activate_subscriptions_for_order( $order );
                          }
                      } else {
                        if(class_exists('WC_Subscriptions') ){
                            WC_Subscriptions_Manager::process_subscription_payments_on_order( $order );
                        }
                      }
                    }

                    Twispay_TW_Logger::twispay_tw_log($that->language->get('log_ok_status_complete') . $orderId);
                break;

                default:
                  Twispay_TW_Logger::twispay_tw_log($that->language->get('log_error_wrong_status') . $serverStatus);
                break;
            }
        }


















        /**
        * Update the status of an Woocommerce subscription according to the received server status.
        *
        * @param orderId: The ID of the order that is the parent of the subscription.
        * @param serverStatus: The status received from server.
        * @param tw_lang: The array of available messages.
        *
        * @return void
        */
        public static function updateSubscriptionStatus($orderId, $serverStatus, $that){
            /* Check that the subscriptions plugin is installed. */
            if(!class_exists('WC_Subscriptions') ){
              return;
            }
            $that->load->model('checkout/order');
            /* Extract the order. */
            $order = $that->model_checkout_order->getOrder($orderId);
            /* Extract the subscription. */
            $subscription = wcs_get_subscriptions_for_order($order);
            $subscription = reset($subscription);

            switch ($serverStatus) {
                case Twispay_TW_Status_Updater::$RESULT_STATUSES['COMPLETE_FAIL']: /* The subscription has payment failure. */
                case Twispay_TW_Status_Updater::$RESULT_STATUSES['THREE_D_PENDING']: /* The subscription has a 3D pending payment. */
                    if($subscription->can_be_updated_to('on-hold')){
                        /* Mark subscription as 'ON-HOLD'. */
                        $subscription->update_status('on-hold');
                        Twispay_TW_Logger::twispay_tw_updateTransactionStatus($orderId, $serverStatus);
                    }
                break;

                case Twispay_TW_Status_Updater::$RESULT_STATUSES['COMPLETE_OK']: /* The subscription has been completed. */
                case Twispay_TW_Status_Updater::$RESULT_STATUSES['CANCEL_OK']: /* The subscription has been canceled. */
                case Twispay_TW_Status_Updater::$RESULT_STATUSES['REFUND_OK']: /* The subscription has been refunded. */
                case Twispay_TW_Status_Updater::$RESULT_STATUSES['VOID_OK']: /*  */
                case Twispay_TW_Status_Updater::$RESULT_STATUSES['CHARGE_BACK']: /* The subscription has been forced back. */
                    if($subscription->can_be_updated_to('canceled')){
                        /* Mark subscription as 'CANCELED'. */
                        $subscription->update_status('canceled');
                        Twispay_TW_Logger::twispay_tw_updateTransactionStatus($orderId, $serverStatus);
                    }
                break;

                case Twispay_TW_Status_Updater::$RESULT_STATUSES['EXPIRING']: /* The subscription will expire soon. */
                case Twispay_TW_Status_Updater::$RESULT_STATUSES['IN_PROGRESS']: /* The subscription is in progress. */
                    if($subscription->can_be_updated_to('active')){
                        /* Mark subscription as 'ACTIVE'. */
                        $subscription->update_status('active');
                        Twispay_TW_Logger::twispay_tw_updateTransactionStatus($orderId, $serverStatus);
                    }
                break;

                default:
                  Twispay_TW_Logger::twispay_tw_log($that->language->get('log_error_wrong_status') . $serverStatus);
                break;
            }
        }
    }
endif; /* End if class_exists. */
