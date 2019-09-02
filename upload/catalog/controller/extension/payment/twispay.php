<?php
/**
 * @author   Twistpay
 * @version  1.0.0
 */

class ControllerExtensionPaymentTwispay extends Controller
{
    private static $live_host_name = 'https://secure.twispay.com';
    private static $stage_host_name = 'https://secure-stage.twispay.com';

    /*
    ////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////
    //////////////////////////////////// INDEX /////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////
     *
     * Function that loads the message that needs to be sent to the server via ajax.
     */
    public function index(){
      $this->language->load('extension/payment/twispay');
      $htmlOutput = "<div id='submit_form_wp'><form id='twispay_payment_form'><input data-loading-text='".$this->lang('button_processing')."' type='button' value='".$this->lang('button_confirm')."' class='btn btn-primary' id='submit_form_button' /></form></div>
      <script type='text/javascript'>
        var $ = jQuery;
        $(document).on('click', '#submit_form_button', function() {
          $('#submit_form_button').button('loading');
          $.ajax({
            url: '".$this->url->link('extension/payment/twispay/send')."',
            success: function(data) {
              $('#submit_form_wp').html(data);
              $('#submit_form_button').button('loading');
              $('#twispay_payment_form').submit();
            },error: function(xhr, ajaxOptions, thrownError) {
              alert('".$this->lang('ajax_error')."'+thrownError);
              $('#submit_form_button').button('reset');
            }
          });
        });
      </script>";
      return $htmlOutput;
    }

    /*
    ////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////
    //////////////////////////////////// SEND //////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////
     *
     * Function that populates the message that needs to be sent to the server.
     */
    public function send()
    {
        /* Load helpers */
        $this->load->helper('Twispay_TW_Helper_Notify');
        $this->load->helper('Twispay_TW_Logger');
        $this->load->model('checkout/order');
        $this->language->load('extension/payment/twispay');

        /* Get order info */
        $order_id = $this->session->data['order_id'];
        $order_info = $this->model_checkout_order->getOrder($order_id);

        /* Get the Site ID and the Private Key. */
        if (!$this->config->get('twispay_testMode')) {
            $this->hostName = ControllerExtensionPaymentTwispay::$live_host_name;
            $this->siteID = $this->config->get('twispay_live_site_id');
            $this->secretKey = $this->config->get('twispay_live_site_key');
        } else {
            $this->hostName = ControllerExtensionPaymentTwispay::$stage_host_name;
            $this->siteID = $this->config->get('twispay_staging_site_id');
            $this->secretKey = $this->config->get('twispay_staging_site_key');
        }

        if ($order_info) {
            /* Extract the customer details. */
            $customer = [ 'identifier' => (0 == $order_info['customer_id']) ? ('_' . $order_id . '_' . date('YmdHis')) : ('_' . $order_info['customer_id'] . '_' . date('YmdHis'))
                        , 'firstName' => ($order_info['payment_firstname']) ? ($order_info['payment_firstname']) : ($order_info['shipping_firstname'])
                        , 'lastName' => ($order_info['payment_lastname']) ? ($order_info['payment_lastname']) : ($order_info['shipping_lastname'])
                        , 'country' => ($order_info['payment_iso_code_2']) ? ($order_info['payment_iso_code_2']) : ($order_info['shipping_iso_code_2'])
                        , 'city' => ($order_info['payment_city']) ? ($order_info['payment_city']) : ($order_info['shipping_city'])
                        , 'zipCode' => ($order_info['payment_postcode']) ? ($order_info['payment_postcode']) : ($order_info['shipping_postcode'])
                        , 'address' => ($order_info['payment_address_1']) ? ($order_info['payment_address_1'].' '.$order_info['payment_address_2']) : ($order_info['shipping_address_1'].' '.$order_info['shipping_address_2'])
                        , 'phone' => ((strlen($order_info['telephone']) && $order_info['telephone'][0] == '+') ? ('+') : ('')) . preg_replace('/([^0-9]*)+/', '', $order_info['telephone'])
                        , 'email' => $order_info['email']
                        ];

            /* Get items */
            $products = $this->cart->getProducts();

            /* Extract the items details. */
            $items = array();
            foreach ($products as $item) {
                $items[] = [ 'item' => $item['name']
                           , 'units' =>  $item['quantity']
                           , 'unitPrice' => $this->currency->format($item['price'], $order_info['currency_code'] , false, false)
                           ];
            }

            /* Calculate the backUrl through which the server will provide the status of the order. */
            $backUrl = $this->url->link('extension/payment/twispay/callback');

            /* Build the data object to be posted to Twispay. */
            $orderData = [ 'siteId' => $this->siteID
                     , 'customer' => $customer
                     , 'order' => [ 'orderId' => (isset($_GET['tw_reload']) && $_GET['tw_reload']) ? ($order_id . '_' . date('YmdHis')) : ($order_id)
                                  , 'type' => 'purchase'
                                  , 'amount' =>  $this->currency->format($order_info['total'], $order_info['currency_code'], false, false)
                                  , 'currency' => $order_info['currency_code']
                                  , 'items' => $items
                                  ]
                     , 'cardTransactionMode' => 'authAndCapture'
                     /* , 'cardId' => 0 */
                     , 'invoiceEmail' => ''
                     , 'backUrl' => $backUrl
                     /* , 'customData' => [] */
            ];

            $this->_log(json_encode($orderData));

            $base64JsonRequest = Twispay_TW_Helper_Notify::getBase64JsonRequest($orderData);
            $base64Checksum = Twispay_TW_Helper_Notify::getBase64Checksum($orderData, $this->secretKey);

            $htmlOutput = "<form action='".$this->hostName."' method='POST' accept-charset='UTF-8' id='twispay_payment_form'>
                <input type='hidden' name='jsonRequest' value='".$base64JsonRequest."'>
                <input type='hidden' name='checksum' value='".$base64Checksum."'>
                <input type='submit' data-loading-text='".$this->lang('button_processing')."' value='".$this->lang("button_retry")."' class='btn btn-primary disabled' disabled='disabled' id='submit_form_button' />
            </form>";

            echo $htmlOutput;
        }
    }

    /*
    ////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////
    //////////////////////////////// CALLBACK //////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////
     *
     * Function that processes the backUrl response of the server.
     */
    public function callback()
    {
        /* Load helpers */
        $this->load->helper('Twispay_TW_Helper_Response');
        $this->load->helper('Twispay_TW_Logger');
        $this->load->helper('Twispay_TW_Notification');
        $this->load->helper('Twispay_TW_Status_Updater');
        $this->load->helper('Twispay_TW_Thankyou');

        $this->language->load('extension/payment/twispay');
        $this->load->model('extension/payment/twispay');
        $this->load->model('checkout/order');

        /* Get the Site ID and the Private Key. */
        if (!$this->config->get('twispay_testMode')) {
            $this->secretKey = $this->config->get('twispay_live_site_key');
        } else {
            $this->secretKey = $this->config->get('twispay_staging_site_key');
        }

        if (!empty($_POST)) {
            echo $this->language->get('processing');
            sleep(1);

            /* Check if the POST is corrupted: Doesn't contain the 'opensslResult' and the 'result' fields. */
            if (((FALSE == isset($_POST['opensslResult'])) && (FALSE == isset($_POST['result'])))) {
                $this->_log($this->lang('log_error_empty_response'));
                Twispay_TW_Notification::notice_to_cart($this);
                die( $this->lang('log_error_empty_response') );
            }

            /* Check if there is NO secret key. */
            if ('' == $this->secretKey) {
                $this->_log($this->lang('log_error_invalid_private'));
                Twispay_TW_Notification::notice_to_cart($this);
                die( $this->lang('log_error_invalid_private') );
            }

            /* Extract the server response and decript it. */
            $decrypted = Twispay_TW_Helper_Response::twispay_tw_decrypt_message(/*tw_encryptedResponse*/(isset($_POST['opensslResult'])) ? ($_POST['opensslResult']) : ($_POST['result']), $this->secretKey);

            /* Check if decryption failed.  */
            if (FALSE === $decrypted) {
                $this->_log($this->lang('log_error_decryption_error'));
                Twispay_TW_Notification::notice_to_cart($this);
                die( $this->lang('log_error_decryption_error') );
            } else {
                $this->_log($this->lang('log_ok_string_decrypted'). json_encode($decrypted));
            }

            /* Validate the decripted response. */
            $orderValidation = Twispay_TW_Helper_Response::twispay_tw_checkValidation($decrypted, $this);
            if (TRUE !== $orderValidation) {
                $this->_log($this->lang('log_error_validating_failed'));
                Twispay_TW_Notification::notice_to_cart($this);
                die( $this->lang('log_error_validating_failed') );
            }

            /* Extract the order. */
            $orderId = explode('_', $decrypted['externalOrderId'])[0];
            $order = $this->model_checkout_order->getOrder($orderId);

            /* Check if the order extraction failed. */
            if (FALSE == $order) {
                $this->_log($this->lang('log_error_invalid_order'));
                Twispay_TW_Notification::notice_to_cart($this);
                die( $this->lang('log_error_invalid_order') );
            }

            /* If transaction already exists */
            $transaction = $this->model_extension_payment_twispay->checktransactions($decrypted['transactionId']);
            if(empty($transaction)){
                /* If there is no invoice created */
                if (!$order['invoice_no']) {
                    /* Create invoice */
                    $invoice = $this->model_extension_payment_twispay->createInvoiceNo($decrypted['externalOrderId'],$order['invoice_prefix']);
                    $decrypted['invoice'] = $invoice;
                    $this->model_extension_payment_twispay->loggTransaction($decrypted);
                }
            }
            Twispay_TW_Status_Updater::updateStatus_backUrl($orderId, $decrypted, $this);
        } else {
            $this->_log($this->lang('no_post'));
            Twispay_TW_Notification::notice_to_cart($this, '', $this->lang('no_post'));
        }
    }

    /*
    ////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////// Server to Server ////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////
     *
     * Function that processes the IPN (Instant Payment Notification) response of the server.
     */
    public function s2s()
    {
        /* Load helpers */
        $this->load->helper('Twispay_TW_Helper_Response');
        $this->load->helper('Twispay_TW_Logger');
        $this->load->helper('Twispay_TW_Status_Updater');

        $this->language->load('extension/payment/twispay');
        $this->load->model('checkout/order');

        /* Get the Site ID and the Private Key. */
        if (!$this->config->get('twispay_testMode')) {
            $this->secretKey = $this->config->get('twispay_live_site_key');
        } else {
            $this->secretKey = $this->config->get('twispay_staging_site_key');
        }

        if (!empty($_POST)) {
            /* Check if the POST is corrupted: Doesn't contain the 'opensslResult' and the 'result' fields. */
            if (((FALSE == isset($_POST['opensslResult'])) && (FALSE == isset($_POST['result'])))) {
                $this->_log($this->lang('log_error_empty_response'));
                die( $this->lang('log_error_empty_response') );
            }

            /* Check if there is NO secret key. */
            if ('' == $this->secretKey) {
                $this->_log($this->lang('log_error_invalid_private'));
                die( $this->lang('log_error_invalid_private') );
            }

            /* Extract the server response and decript it. */
            $decrypted = Twispay_TW_Helper_Response::twispay_tw_decrypt_message(/*tw_encryptedResponse*/(isset($_POST['opensslResult'])) ? ($_POST['opensslResult']) : ($_POST['result']), $this->secretKey);

            /* Check if decryption failed.  */
            if (FALSE === $decrypted) {
                $this->_log($this->lang('log_error_decryption_error'));
                die( $this->lang('log_error_decryption_error') );
            } else {
                $this->_log($this->lang('log_ok_string_decrypted'). json_encode($decrypted));
            }

            /* Validate the decripted response. */
            $orderValidation = Twispay_TW_Helper_Response::twispay_tw_checkValidation($decrypted, $this);
            if (TRUE !== $orderValidation) {
                $this->_log($this->lang('log_error_validating_failed'));
                die( $this->lang('log_error_validating_failed') );
            }

            /* Extract the order. */
            $orderId = explode('_', $decrypted['externalOrderId'])[0];
            $order = $this->model_checkout_order->getOrder($orderId);

            /* Check if the order extraction failed. */
            if (FALSE == $order) {
                $this->_log($this->lang('log_error_invalid_order'));
                die( $this->lang('log_error_invalid_order') );
            }

            $this->load->model('extension/payment/twispay');

            Twispay_TW_Status_Updater::updateStatus_IPN($orderId, $decrypted, $this);
            die('OK');
        } else {
            $this->_log($this->lang('no_post'));
            die( $this->lang('no_post') );
        }
    }

    /**
     * Log a message
     *
     * @param string: The message to be logged.
     *
     * @return void
     */
    private function _log($string='')
    {
        Twispay_TW_Logger::twispay_tw_log($string);
    }

    /**
     * Get a string from store language
     *
     * @param string: The string identifier.
     *
     * @return void
     */
    private function lang($string='')
    {
        return $this->language->get($string);
    }
}
