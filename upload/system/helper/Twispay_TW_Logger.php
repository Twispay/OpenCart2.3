<?php
/**
 * Twispay Helpers
 *
 * Logs messages and transactions.
 *
 * @package  Twispay/Front
 * @category Front
 * @author   @TODO
 * @version  0.0.1
 */

/* Security class check */
if ( ! class_exists( 'Twispay_TW_Logger' ) ) :
    /**
     * Twispay Helper Class
     *
     * @class   Twispay_TW_Logger
     * @version 0.0.1
     *
     *
     * Class that implements methods to log
     * messages and transactions.
     */
    class Twispay_TW_Logger{
        /**
         * Function that logs a transaction to the DB.
         *
         * @param data Array containing the transaction data.
         *
         * @return void
         */

         public static function twispay_tw_logTransaction( $data, $that ) {
            $that->load->model('extension/payment/twispay');
            /* Extract the order. */
            $order = $that->model_checkout_order->getOrder($data['id_cart']);
            if ( $that->model_extension_payment_twispay->checktransactions($data['transactionId']) ) {
              /* Update the DB with the transaction data. */
              $that->db->query( "UPDATE " . DB_PREFIX . "twispay_transactions SET status = '" . $data['status'] . "' WHERE transactionId = '%d'", $data['transactionId']);
            } else {
                //$checkout_url = '#';//((false !== $order) && (true !== $order)) ? (wc_get_checkout_url() . 'order-pay/' . explode('_', $data['id_cart'])[0] . '/?pay_for_order=true&key=' . $order->get_data()['order_key'] . '&tw_reload=true') : ("");
                $that->db->query( "INSERT INTO `" . DB_PREFIX . "twispay_transactions` (`status`, `id_cart`, `identifier`, `orderId`, `transactionId`, `customerId`, `cardId`) VALUES ('" . $data['status'] . "', '" . $data['id_cart'] . "', '" . $data['identifier'] . "', '" . $data['orderId'] . "', '" . $data['transactionId'] . "', '" . $data['customerId'] . "', '" . $data['cardId'] . "');" );
            }
         }

        /**
         * Function that updates a transaction's status in the DB.
         *
         * @param id The ID of the parent order.
         * @param status The new status of the transaction.
         *
         * @return void
         */
        public static function twispay_tw_updateTransactionStatus( $id, $status, $that) {
            $already = $that->db->query( "SELECT * FROM " . DB_PREFIX . "twispay_transactions WHERE id_cart = '" . $id . "'" );
            if ( $already ) {
                /* Update the DB with the transaction data. */
                $that->db->query( "UPDATE " . DB_PREFIX . "twispay_transactions SET status = '" . $status . "' WHERE id_cart = '%d'", $id ) ;
            }
        }

        /**
         * Function that logs a message to the log file.
         *
         * @param string - Message to log to file.
         *
         * @return void
         */
        public static function twispay_tw_log( $message = FALSE ) {
            $log_file = $_SERVER['DOCUMENT_ROOT'].'/twispay_logs/log';
            /* Build the log message. */
            $message = (!$message) ? (PHP_EOL . PHP_EOL) : ("[" . date( 'Y-m-d H:i:s' ) . "] " . $message);
            /* Try to append log to file and silence and PHP errors may occur. */
            @file_put_contents( $log_file, $message . PHP_EOL, FILE_APPEND );
        }
    }
endif; /* End if class_exists. */
