<?php
/**
 * Twispay Helpers
 *
 * Logs messages and transactions.
 *
 * @author   Twistpay
 * @version  1.0.0
 */

/* Security class check */
if (! class_exists('Twispay_TW_Logger')) :
    /**
     * Class that implements methods to log
     * messages and transactions.
     */
    class Twispay_TW_Logger
    {
        /**
         * Function that logs a transaction to the DB.
         *
         * @param data Array containing the transaction data.
         * @param that: Controller instance use for accessing runtime values like configuration, active language, etc.
         *
         * @return void
         */
        public static function twispay_tw_logTransaction($data, $that)
        {
            $that->load->model('extension/payment/twispay');
            /* Extract the order. */
            $order = $that->model_checkout_order->getOrder($data['id_cart']);
            if ($that->model_extension_payment_twispay->checktransactions($data['transactionId'])) {
                /* Update the DB with the transaction data. */
                $that->db->query("UPDATE " . DB_PREFIX . "twispay_transactions SET status = '" . $data['status'] . "' WHERE transactionId = '%d'", $data['transactionId']);
            } else {
                /* Insert transaction data into DB. */
                $that->db->query("INSERT INTO `" . DB_PREFIX . "twispay_transactions` (`status`, `id_cart`, `identifier`, `orderId`, `transactionId`, `customerId`, `cardId`) VALUES ('" . $data['status'] . "', '" . $data['id_cart'] . "', '" . $data['identifier'] . "', '" . $data['orderId'] . "', '" . $data['transactionId'] . "', '" . $data['customerId'] . "', '" . $data['cardId'] . "');");
            }
        }

        /**
         * Function that logs a message to the log file.
         *
         * @param string - Message to log to file.
         *
         * @return void
         */
        public static function twispay_tw_log($message = FALSE)
        {
            $log_file = DIR_LOGS.'/twispay_logs/transactions.log';
            /* Build the log message. */
            $message = (!$message) ? (PHP_EOL . PHP_EOL) : ("[" . date('Y-m-d H:i:s') . "] " . $message);
            /* Try to append log to file and silence and PHP errors may occur. */
            @file_put_contents($log_file, $message . PHP_EOL, FILE_APPEND);
        }
    }
endif; /* End if class_exists. */
