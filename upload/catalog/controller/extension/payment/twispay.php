<?php
// IN

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class ControllerExtensionPaymentTwispay extends Controller {
    var $log_file;
    var $siteID = '';
    var $secretKey = '';
    var $hostName = '';
    var $configuration = ['contact_email'=> 'rotarumichael@gmail.com','thankyou_page' => '','live_mode' => 0];

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////// SEND //////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

    public function index() {
        /* Load helpers */
        $this->load->helper('Twispay_TW_Helper_Notify');
        $this->load->model('checkout/order');
        $this->language->load('extension/payment/twispay');

        /* Get order info */
        $order_id = $this->session->data['order_id'];
        $order_info = $this->model_checkout_order->getOrder($order_id);

        // DEBUG
        // print_r('<pre>');
        // print_r($this->session);
        // print_r('</pre>');

        /* Get the Site ID and the Private Key. */
        // $hostName = ($this->configuration && (1 == $this->configuration['live_mode'])) ? ('https://secure.twispay.com' . '?lang=' . $lang) : ('https://secure-stage.twispay.com' . '?lang=' . $lang);
        // $testMode = $this->config->get('twispay_testMode');
        if ( $this->configuration['live_mode'] ) {
          $this->hostName = 'https://secure.twispay.com';
          $this->siteID = $this->config->get('twispay_live_site_id');
          $this->secretKey = $this->config->get('twispay_live_site_key');
        } else {
          $this->hostName = 'https://secure-stage.twispay.com';
          $this->siteID = $this->config->get('twispay_staging_site_id');
          $this->secretKey = $this->config->get('twispay_staging_site_key');
        }

        $phone = preg_replace('/[^\d|+]+/', '',$order_info['telephone']);
        $firstphone = substr($phone,0,1);
        $subphone = substr($phone,1);

        if ($order_info) {

            /* Extract the customer details. */
            $customer = [ 'identifier' => (0 == $order_info['customer_id']) ? ('_' . $order_id . '_' . date('YmdHis')) : ('_' . $order_info['customer_id'])
                      , 'firstName' => ($order_info['payment_firstname']) ? ($order_info['payment_firstname']) : ($order_info['shipping_firstname'])
                      , 'lastName' => ($order_info['payment_lastname']) ? ($order_info['payment_lastname']) : ($order_info['shipping_lastname'])
                      , 'country' => ($order_info['payment_iso_code_2']) ? ($order_info['payment_iso_code_2']) : ($order_info['shipping_iso_code_2'])
                      , 'state' => ($order_info['payment_zone_code']) ? ($order_info['payment_zone_code']) : ($order_info['shipping_zone_code'])

                      , 'city' => ($order_info['payment_city']) ? ($order_info['payment_city']) : ($order_info['shipping_city'])
                      , 'zipCode' => ($order_info['payment_postcode']) ? ($order_info['payment_postcode']) : ($order_info['shipping_postcode'])
                      , 'address' => ($order_info['payment_address_1']) ? ([$order_info['payment_address_1'], $order_info['payment_address_2']]) : ([$order_info['shipping_address_1'],$order_info['shipping_address_2']])
                      , 'phone' => $firstphone . str_replace('+','',$subphone)
                      , 'email' => $order_info['email']
                      /* , 'tags' => [] */
            ];

            /* Get items */
            $products = $this->cart->getProducts();
            /* Extract the items details. */
            $items = array();
            foreach ( $products as $item) {
                $items[] = [ 'item' => $item['name']
                           , 'units' =>  $item['quantity']
                           , 'unitPrice' => number_format( number_format( ( float )$item['total'], 2) / number_format( ( float )$item['quantity'], 2 ), 2 )
                           /* , 'type' => '' */
                           /* , 'code' => '' */
                           /* , 'vatPercent' => '' */
                           /* , 'itemDescription' => '' */
                           ];
            }

            /* Calculate the backUrl through which the server will provide the status of the order. */

            $backUrl = $this->url->link('extension/payment/twispay/callback');
            // $backUrl .= (FALSE == strpos($backUrl, '?')) ? ('?secure_key=' . 'to_be_added') : ('&secure_key=' . 'to_be_added');

            /* Build the data object to be posted to Twispay. */
            $orderData = [ 'siteId' => $this->siteID
                     , 'customer' => $customer
                     , 'order' => [ 'orderId' => (isset( $_GET['tw_reload'] ) && $_GET['tw_reload']) ? ($order_id . '_' . date('YmdHis')) : ($order_id)
                                  , 'type' => 'purchase'
                                  , 'amount' => $order_info['total']
                                  , 'currency' => $order_info['currency_code']
                                  , 'items' => $items
                                  ]
                     , 'cardTransactionMode' => 'authAndCapture'
                     /* , 'cardId' => 0 */
                     , 'invoiceEmail' => ''
                     , 'backUrl' => $backUrl
                     /* , 'customData' => [] */
            ];

            $base64JsonRequest = Twispay_TW_Helper_Notify::getBase64JsonRequest($orderData);
            $base64Checksum = Twispay_TW_Helper_Notify::getBase64Checksum($orderData, $this->secretKey);

            $htmlOutput = '<form action="'.$this->hostName.'" method="POST" accept-charset="UTF-8" id="twispay_payment_form">
                <input type="hidden" name="jsonRequest" value="'.$base64JsonRequest.'">
                <input type="hidden" name="checksum" value="'.$base64Checksum.'">
                <input type="submit" value="'.$this->lang('button_confirm').'" class="btn btn-primary" />
                <script>//document.getElementById( "twispay_payment_form" ).submit();</script>
            </form>';

            return $htmlOutput;
        }
    }


////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
//////////////////////////////// CALLBACK //////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

    public function callback() {
        /* Load helpers */
        $this->load->helper('Twispay_TW_Helper_Response');
        $this->load->helper('Twispay_TW_Status_Updater');
        $this->load->helper('Twispay_TW_Notification');

        $this->language->load('extension/payment/twispay');
        $this->load->model('checkout/order');

        // DEBUG
        // print_r('<pre>');
        // print_r($this->config);
        // print_r('</pre>');

        /* Get the Site ID and the Private Key. */
        // $hostName = ($this->configuration && (1 == $this->configuration['live_mode'])) ? ('https://secure.twispay.com' . '?lang=' . $lang) : ('https://secure-stage.twispay.com' . '?lang=' . $lang);
        if ( $this->configuration['live_mode'] ) {
          $this->hostName = 'https://secure.twispay.com';
          $this->siteID = $this->config->get('twispay_live_site_id');
          $this->secretKey = $this->config->get('twispay_live_site_key');
        } else {
          $this->hostName = 'https://secure-stage.twispay.com';
          $this->siteID = $this->config->get('twispay_staging_site_id');
          $this->secretKey = $this->config->get('twispay_staging_site_key');
        }

        if(!empty($_POST)){
            echo "Processing ... <br><br><br><br>";
            sleep(1);
            /* Check if the POST is corrupted: Doesn't contain the 'opensslResult' and the 'result' fields. */
                                                      /* OR */
            /* Check if the 'backUrl' is corrupted: Doesn't contain the 'secure_key' field. */
            if( ((FALSE == isset($_POST['opensslResult'])) && (FALSE == isset($_POST['result']))) || (FALSE == isset($_GET['secure_key'])) ) {
              $this->_log($this->lang['log_error_empty_response']);
              Twispay_TW_Notification::notice_to_cart($this);
              exit();
            }

            /* Check if there is NO secret key. */
            if ( '' == $this->secretKey ) {
                $this->_log($this->lang['log_error_invalid_private']);
                Twispay_TW_Notification::notice_to_cart($this);
                exit();
            }

            /* Extract the server response and decript it. */
            $decrypted = Twispay_TW_Helper_Response::twispay_tw_decrypt_message(/*tw_encryptedResponse*/(isset($_POST['opensslResult'])) ? ($_POST['opensslResult']) : ($_POST['result']), $this->secretKey);

            /* Check if decryption failed.  */
            if(FALSE === $decrypted){
              $this->_log($this->lang('log_error_decryption_error'));
              Twispay_TW_Notification::notice_to_cart($this);
              exit();
            } else {
              $this->_log($this->lang('log_ok_string_decrypted'). json_encode($decrypted));
            }

            /* Validate the decripted response. */
            $orderValidation = Twispay_TW_Helper_Response::twispay_tw_checkValidation($decrypted, $this->language);
            if(TRUE !== $orderValidation){
                $this->_log($this->lang('log_error_validating_failed'));
                Twispay_TW_Notification::notice_to_cart($this);
                exit();
            }

            /* Extract the order. */
            $orderId = explode('_', $decrypted['externalOrderId'])[0];
            $order = $this->model_checkout_order->getOrder($orderId);

            /* Check if the WooCommerce order extraction failed. */
            if( FALSE == $order ){
                $this->_log($this->lang('log_error_invalid_order'));
                Twispay_TW_Notification::notice_to_cart($this);
                exit();
            }

            /* Check if the order cart hash does NOT MATCH the one sent to the server. */
            // if ( $_GET['secure_key'] != 'to_be_added' ){
            //     $this->_log($this->lang('log_error_invalid_key'));
            //     Twispay_TW_Notification::notice_to_cart($this);
            //     exit();
            // }

            $this->load->model('extension/payment/twispay');
            /* Check if transactionId exist */
            if( FALSE == $decrypted['transactionId'] ){
              $this->_log($this->lang('log_error_empty_transaction'));
              Twispay_TW_Notification::notice_to_cart($this);
              exit();
            }

            // _TOUNCOMMENT
            // Check if transaction already exist
            $transaction = $this->model_extension_payment_twispay->checktransactions($decrypted['transactionId']);
            if( !empty($transaction) ){
              // $this->_log($this->lang('transaction_exists'));
              // Twispay_TW_Notification::notice_to_cart($this);
              // exit();
            }

            /* Reconstruct the checkout URL to use it to allow client to try again in case of error. */
            Twispay_TW_Status_Updater::updateStatus_backUrl($order, $decrypted, $this);
        } else {
            echo "No post";
            $this->_log($this->lang('no_post'));
            Twispay_TW_Notification::notice_to_cart($this);
        }
    }
    private function _log($string=''){
      $this->load->helper('Twispay_TW_Logger');
      return Twispay_TW_Logger::twispay_tw_log($string);
    }
    private function lang($string=''){
      return $this->language->get($string);
    }
}
?>
