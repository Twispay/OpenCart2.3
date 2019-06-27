<?php

class ModelExtensionPaymentTwispay extends Model {
    public function getMethod($address, $total) {
        $this->load->language('extension/payment/twispay');
        $testMode = $this->config->get('twispay_testMode');
        if(!empty($testMode)) {
            $siteId = trim($this->config->get('twispay_staging_site_id'));
            $privateKEY = trim($this->config->get('twispay_staging_site_key'));
        } else {
            $siteId = trim($this->config->get('twispay_live_site_id'));
            $privateKEY = trim($this->config->get('twispay_live_site_key'));

        }
        if(empty($siteId) || empty($privateKEY)){
            return false;
        }
        $method_data = array(
            'code'       => 'twispay',
            'title'      => $this->language->get('text_title'),
            'terms'      =>'',
            'sort_order' => $this->config->get('custom_sort_order')
        );

        return $method_data;
    }

    public function recurringPayments() {
        /*
         * Used by the checkout to state the module
         * supports recurring recurrings.
         */
        return true;
    }

    private function checktable(){
        $sql = "
            CREATE TABLE IF NOT EXISTS `". DB_PREFIX ."twispay_transactions` (
                `id_transaction` int(11) NOT NULL AUTO_INCREMENT,
                `status` varchar(16) NOT NULL,
                `invoice` varchar(30) NOT NULL,
                `order_id` int(11) NOT NULL,
                `identifier` int(11) NOT NULL,
                `customerId` int(11) NOT NULL,
                `orderId` int(11) NOT NULL,
                `cardId` int(11) NOT NULL,
                `transactionId` int(11) NOT NULL,
                `transactionKind` varchar(16) NOT NULL,
                `amount` float NOT NULL,
                `currency` varchar(8) NOT NULL,
                `date` DATETIME NOT NULL,
                `refund_date` DATETIME NOT NULL,
                PRIMARY KEY (`id_transaction`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
        $this->db->query($sql);
    }

    public function checktransactions($id=0){
        $this->checktable();
        $data = $this->db->query("SELECT * FROM `".DB_PREFIX."twispay_transactions` WHERE `transactionId`='".$id."'");
        if($data->num_rows > 0) {
            return true;
        } else {
            return false;
        }
    }
    public function loggTransaction($data) {
        $data =json_decode(json_encode($data),TRUE);

        $columns = array(
            'order_id',
            'status',
            'invoice',
            'identifier',
            'customerId',
            'orderId',
            'cardId',
            'transactionId',
            'transactionKind',
            'amount',
            'currency',
            'date',
        );
        if(!empty($data['timestamp'])) {
            $data['date'] = date('Y-m-d H:i:s', $data['timestamp']);
            unset($data['timestamp']);
        }
        if(!empty($data['identifier'])) {
            $data['identifier'] = (int)str_replace('_', '', $data['identifier']);
        }
        $query = "INSERT INTO `" . DB_PREFIX . "twispay_transactions` SET ";
        foreach($data as $key => $value) {
            if(!in_array($key, $columns)) {
                unset($data[$key]);
            } else {
                $query .= $key."="."'" . $value. "',";
            }
        }
        $query = rtrim($query,',');
        $this->db->query($query);

        return $query;
    }

    public function createInvoiceNo($order_id,$prefix) {
            $query = $this->db->query("SELECT MAX(invoice_no) AS invoice_no FROM `" . DB_PREFIX . "order` WHERE invoice_prefix = '" . $this->db->escape($prefix) . "'");

            if ($query->row['invoice_no']) {
                $invoice_no = $query->row['invoice_no'] + 1;
            } else {
                $invoice_no = 1;
            }

            $this->db->query("UPDATE `" . DB_PREFIX . "order` SET invoice_no = '" . (int)$invoice_no . "', invoice_prefix = '" . $this->db->escape($prefix) . "' WHERE order_id = '" . (int)$order_id . "'");

            return $prefix . $invoice_no;
        }
}
