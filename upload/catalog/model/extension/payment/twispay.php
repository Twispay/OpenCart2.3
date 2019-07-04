<?php

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
        $data = $this->db->query("SELECT * FROM `".DB_PREFIX."twispay_transactions` WHERE `transactionId`='".$this->db->escape($_id)."'");
        if ($data->num_rows > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function createInvoiceNo($order_id, $prefix)
    {
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
