<?php
/**
 * @author   Twistpay
 * @version  1.0.1
 */

class ModelExtensionPaymentTwispay extends Model
{
    public function getMethod($address, $total)
    {
        $this->load->language('extension/payment/twispay');
        $testMode = $this->config->get('twispay_testMode');
        if (!empty($testMode)) {
            $siteId = trim($this->config->get('twispay_staging_site_id'));
            $privateKEY = trim($this->config->get('twispay_staging_site_key'));
        } else {
            $siteId = trim($this->config->get('twispay_live_site_id'));
            $privateKEY = trim($this->config->get('twispay_live_site_key'));
        }
        if (empty($siteId) || empty($privateKEY)) {
            return FALSE;
        }
        $method_data = array(
            'code'       => 'twispay',
            'title'      => $this->language->get('text_title'),
            'terms'      =>'',
            'sort_order' => $this->config->get('custom_sort_order')
        );

        return $method_data;
    }

    public function recurringPayments()
    {
        /*
         * Used by the checkout to state the module
         * supports recurring recurrings.
         */
        return TRUE;
    }

    public function checktransactions($_id=0)
    {
        $db_id = $this->db->escape($_id);
        $data = $this->db->query("SELECT * FROM `".DB_PREFIX."twispay_transactions` WHERE `transactionId`='".$db_id."'");
        if ($data->num_rows > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }


    public function loggTransaction($data) {
        $data =json_decode(json_encode($data),TRUE);
        $data['order_id'] = $data['externalOrderId'];
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
            if(is_array($data['timestamp'])){
              $data['date'] = date('Y-m-d H:i:s', strtotime($data['timestamp']['date']));
            } else {
              $data['date'] = date('Y-m-d H:i:s', $data['timestamp']);
            }
            unset($data['timestamp']);
        }

        /** Keep just the customer id from identifier */
        if (!empty($data['identifier']) && strpos($data['identifier'], '_') !== false) {
           $explodedVal = explode("_", $data['identifier'])[2];
           /** Check if customer id contains only digits and is not empty */
           if(!empty($explodedVal) && ctype_digit($explodedVal)){
             $data['identifier'] = $explodedVal;
           }
        }

        $query = "INSERT INTO `" . DB_PREFIX . "twispay_transactions` SET ";
        
        // transaction kind does not exist anymore and is renamed to transactionMethod
        foreach($data as $key => $value) {
            if(!in_array($key, $columns) && $key != 'transactionMethod') {
                unset($data[$key]);
            } else {
                $db_value = $this->db->escape($value);
                if ($key == 'transactionMethod') {
                    $key = 'transactionKind'; 
                }
                $query .= $key."="."'" . $db_value . "',";
            }
        }

        $query = rtrim($query,',');
        $this->db->query($query);

        return $query;
    }

    public function createInvoiceNo($order_id, $prefix)
    {
        $db_prefix = $this->db->escape($prefix);
        $db_order_id = $this->db->escape($order_id);
        $query = $this->db->query("SELECT MAX(invoice_no) AS invoice_no FROM `" . DB_PREFIX . "order` WHERE invoice_prefix = '" . $db_prefix . "'");
        if ($query->row['invoice_no']) {
            $invoice_no = $query->row['invoice_no'] + 1;
        } else {
            $invoice_no = 1;
        }
        $this->db->query("UPDATE `" . DB_PREFIX . "order` SET invoice_no = '" . (int)$invoice_no . "', invoice_prefix = '" . $db_prefix . "' WHERE order_id = '" . (int)$db_order_id . "'");

        return $db_prefix . $invoice_no;
    }
}
