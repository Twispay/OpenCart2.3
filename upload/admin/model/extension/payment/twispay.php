<?php
/**
 * @author   Twistpay
 * @version  1.0.1
 */

class ModelExtensionPaymentTwispay extends Model
{
    public function createTransactionTable()
    {
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
              `amount` decimal NOT NULL,
              `currency` varchar(8) NOT NULL,
              `date` DATETIME NOT NULL,
              `refund_date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
              PRIMARY KEY (`id_transaction`)
          ) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
        return $this->db->query($sql);
    }
 
    public function deleteTransactionTable()
    {
        $this->db->query("DROP TABLE IF EXISTS `". DB_PREFIX ."twispay_transactions`");
    }

    public function getTransactions($user='0')
    {
        $db_user = $this->db->escape($user);
        $query = (empty($db_user)) ? "SELECT t.*,s.`store_id` FROM `" . DB_PREFIX . "twispay_transactions` as t LEFT JOIN `".DB_PREFIX."order` AS s ON t.`order_id`=s.`order_id` ORDER BY t.`date` DESC" : "SELECT t.*,s.`store_id` FROM `" . DB_PREFIX . "twispay_transactions` as t LEFT JOIN `".DB_PREFIX."order` AS s ON t.`order_id`=s.`order_id`  WHERE `identifier`='".$db_user."' ORDER BY `date` DESC";
        $data = $this->db->query($query);
         $trans = array();
        if($data->num_rows){
            foreach($data->rows as $dt){
                array_push($trans, $dt);
            }
        }
        return $trans;
    }

    public function getCustomers()
    {
        $query = "SELECT `customer_id`,(CONCAT_WS(' ',`firstname`,`lastname`)) AS name,`email` FROM `" . DB_PREFIX . "customer`";
        $data = $this->db->query($query);
        $customers = array();
        if ($data->num_rows) {
            foreach ($data->rows as $dt) {
                array_push($customers, $dt);
            }
        }
        return $customers;
    }

    private function getOrderByTransaction($transid = 0, $status='')
    {
        $db_transid = $this->db->escape($transid);
        $db_status = $this->db->escape($status);
        $query = (empty($db_status)) ? "SELECT `orderId` FROM `" . DB_PREFIX . "twispay_transactions` WHERE `transactionId`='".$db_transid."'" : "SELECT `orderId` FROM `" . DB_PREFIX . "twispay_transactions` WHERE `transactionId`='".$db_transid."' AND `status`='" . $db_status. "'";
        $data = $this->db->query($query);
        $trans = array();
        if ($data->num_rows) {
            foreach ($data->rows as $dt) {
                array_push($trans, $dt);
            }
        }
        return $trans;
    }

    public function refund($transid=0)
    {
        $this->load->helper('Twispay_TW_Logger');
        $orders = $this->getOrderByTransaction($transid);
        $db_order_id = $this->db->escape($orders[0]['orderId']);
        if (!empty($orders)) {
            $query = "UPDATE `" . DB_PREFIX. "twispay_transactions` SET `status`='refunded',`refund_date`='" . date('Y-m-d H:i:s'). "' WHERE `orderId`='" . $db_order_id . "' AND `status`!='refunded'";
            $this->db->query($query);
            $affected = $this->db->countAffected();
            $array = array(
                'query' => $query,
                'affected'  => $affected
            );
            Twispay_TW_Logger::twispay_tw_r_log(json_encode($array));
            return $affected;
        } else {
            return json_encode(0);
        }
    }
}
