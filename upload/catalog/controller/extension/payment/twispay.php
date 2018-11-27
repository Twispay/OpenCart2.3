<?php
class ControllerExtensionPaymentTwispay extends Controller {
    var $log_file;
    public function index() {
        $this->language->load('extension/payment/twispay');
        $testMode = $this->config->get('twispay_testMode');


        $this->load->model('checkout/order');
        $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
        $products = $this->cart->getProducts();

        if ($order_info) {
            $postfields = array();
            if(!empty($testMode)) {
                $action = 'https://secure-stage.twispay.com';
                $postfields['siteId'] = $this->config->get('twispay_staging_site_id');
                $privateKEY = $this->config->get('twispay_staging_site_key');
            } else {
                $action = 'https://secure.twispay.com';
                $postfields['siteId'] = $this->config->get('twispay_live_site_id');
                $privateKEY = $this->config->get('twispay_live_site_key');
            }
                if(empty($postfields['siteId']) || empty($privateKEY)){
                    return '<p style="color:#FF0000;font-weight: bold"></p>';
                }
            $langPayNow = $this->language->get('button_confirm');

            /* Define data for form inputs */

            $postfields['identifier'] = '_' . $order_info['customer_id'];
            $postfields['currency'] = $order_info['currency_code'];
            $postfields['amount'] = $this->currency->format($order_info['total'], $postfields['currency'] , false, false);
            $postfields['backUrl'] = $this->url->link('extension/payment/twispay/callback');

            $postfields['description'] = (empty(html_entity_decode($order_info['payment_company'], ENT_QUOTES, 'UTF-8'))) ? trim(ucwords(html_entity_decode($order_info['payment_firstname'], ENT_QUOTES, 'UTF-8') . ' ' . html_entity_decode($order_info['payment_lastname'], ENT_QUOTES, 'UTF-8'))) :  html_entity_decode($order_info['payment_company'], ENT_QUOTES, 'UTF-8');
            $postfields['orderType'] = 'purchase';
            $postfields['orderId'] = $this->session->data['order_id'] ;
            $postfields['orderId'] .= '_' . time();
            $postfields['firstName'] = html_entity_decode($order_info['payment_firstname'], ENT_QUOTES, 'UTF-8');
            $postfields['lastName'] = html_entity_decode($order_info['payment_lastname'], ENT_QUOTES, 'UTF-8');
            $postfields['country'] = html_entity_decode($order_info['payment_iso_code_2'], ENT_QUOTES, 'UTF-8');
            $postfields['city'] = html_entity_decode($order_info['payment_city'], ENT_QUOTES, 'UTF-8');
            $postfields['zipCode'] = html_entity_decode($order_info['payment_postcode'], ENT_QUOTES, 'UTF-8');
            $postfields['address'] = html_entity_decode($order_info['payment_address_1'], ENT_QUOTES, 'UTF-8');
            $postfields['address'] .= (!empty(html_entity_decode($order_info['payment_address_2'], ENT_QUOTES, 'UTF-8'))) ?', ' . html_entity_decode($order_info['payment_address_2'], ENT_QUOTES, 'UTF-8') : '';
            $phone = preg_replace('/[^\d|+]+/', '',html_entity_decode($order_info['telephone'], ENT_QUOTES, 'UTF-8'));
            $first = substr($phone,0,1);
            $subphone = substr($phone,1);
            $postfields['phone'] = $first . str_replace('+','',$subphone);
            $postfields['email'] = html_entity_decode($order_info['email'], ENT_QUOTES, 'UTF-8');

            $i= 0;
            $subtotal = 0;

            foreach($products as $item){
                    $postfields['custom[cartId]'] = $item['cart_id'];
                    $postfields['item[' . $i . ']'] = str_replace(array('"','&quot;'),"''",stripslashes($item['name'])) . ', model: '. $item['model'];
                    $postfields['units[' . $i . ']'] = $item['quantity'];
                    $postfields['unitPrice[' . $i . ']'] = $this->currency->format($item['price'], $postfields['currency'] , false, false);
                    $postfields['subTotal[' . $i . ']'] = $this->currency->format($item['total'], $postfields['currency'] , false, false);
                    $subtotal += $this->currency->format($item['total'], $postfields['currency'] , false, false);
                    ++$i;
            }

            if(!empty($this->session->data['shipping_method'])){
                $postfields['item[' . $i . ']'] = 'Shipping: ' . $this->session->data['shipping_method']['title'];
                $postfields['units[' . $i . ']'] = '1';
                $postfields['unitPrice[' . $i . ']'] = $postfields['subTotal[' . $i . ']'] = $this->currency->format($this->session->data['shipping_method']['cost'], $postfields['currency'] , false, false);
                ++$i;
            }

            if(!empty($this->session->data['coupon'])) {
                $this->load->model('extension/total/coupon');
                $coupon = $this->model_extension_total_coupon->getCoupon($this->session->data['coupon']);
                if(!empty($coupon)){
                    $postfields['item[' . $i . ']'] = 'Coupon ('.$coupon['code'].'):';
                    $postfields['units[' . $i . ']'] = '1';
                    $value = ($coupon['type'] == 'P') ? ($subtotal * $coupon['discount']/100) : $coupon['discount'];
                    $postfields['unitPrice[' . $i . ']'] = $postfields['subTotal[' . $i . ']'] = $this->currency->format(('-'. $value), $postfields['currency'] , false, false);
                    ++$i;
                }
           }

            if(!empty($this->session->data['voucher'])) {
                $this->load->model('extension/total/voucher');
                $voucher = $this->model_extension_total_voucher->getVoucher($this->session->data['voucher']);
                if(!empty($voucher)){
                    $postfields['item[' . $i . ']'] = 'Gift Certificate ('.$voucher['code'].'):';
                    $postfields['units[' . $i . ']'] = '1';
                    $postfields['unitPrice[' . $i . ']'] = $postfields['subTotal[' . $i . ']'] = $this->currency->format(('-'. $voucher['amount']), $postfields['currency'] , false, false);
                    ++$i;
                }
            }


            $page = explode('/',$_SERVER['PHP_SELF']);
            $page = trim($page[count($page)-1]);
            /* CardTransactionMode */
            $postfields['cardTransactionMode'] = 'authAndCapture';
            /* Checksum */
            ksort($postfields);
            $query = http_build_query($postfields);
            $encoded = hash_hmac('sha512', $query, $privateKEY, true);
            $checksum = base64_encode($encoded);

            $htmlOutput = '<form accept-charset="UTF-8" id="twispay_payment_form" method="POST" action="' . $action . '">';
            foreach ($postfields as $k => $v) {
                $htmlOutput .= '<input type="hidden" name="' . $k . '" value="' . $v . '" />';
            }
            $htmlOutput .= '<input type="hidden" name="checksum" value="' . $checksum . '" />';
            $htmlOutput .= '<div class="buttons">
                            <div class="pull-right"><input type="submit" value="' . $langPayNow. '" class="btn btn-primary" /></div>
                            </div>';
            return $htmlOutput;
        }
    }


    private function getResultStatuses() {
        return array("complete-ok");
    }

    private function twispay_log($string = false) {
        if(!$string) {
            $string = PHP_EOL.PHP_EOL;
        } else {
            $string = "[".date('Y-m-d H:i:s')."] ".$string;
        }
        @file_put_contents($this->log_file, PHP_EOL.$string.PHP_EOL, FILE_APPEND);
    }

    private function twispayDecrypt($encrypted)
    {
        $apiKey = (!empty($this->config->get('twispay_testMode'))) ? $this->config->get('twispay_staging_site_key') : $this->config->get('twispay_live_site_key');

        $encrypted = (string)$encrypted;
        if(!strlen($encrypted)) {
            return null;
        }
        if(strpos($encrypted, ',') !== false) {
            $encryptedParts = explode(',', $encrypted, 2);
            $iv = base64_decode($encryptedParts[0]);
            if(false === $iv) {
                throw new Exception("Invalid encryption iv");
            }
            $encrypted = base64_decode($encryptedParts[1]);
            if(false === $encrypted) {
                throw new Exception("Invalid encrypted data");
            }
            $decrypted = openssl_decrypt($encrypted, 'aes-256-cbc', $apiKey, OPENSSL_RAW_DATA, $iv);
            if(false === $decrypted) {
                throw new Exception("Data could not be decrypted");
            }
            return $decrypted;
        }
        return null;
    }
    private function tl($string=''){
        return $string;
    }
    private function checkValidation($json){
        $this->twispay_log();
        $this->twispay_log();
        $this->twispay_log('[RESPONSE] decrypted string: '.json_encode($json));
        $_errors = array();
        $wrong_status = array();
        if(!in_array($json->status, $this->getResultStatuses())) {
            $_errors[] = sprintf(tl('[RESPONSE-ERROR] Wrong status (%s)'), $json->status);
            twispay_log();
        }
        if(empty($json->externalOrderId)) {
            $_errors[] = 'Empty externalOrderId';
        } else {
            $this->load->model('checkout/order');
            $order_id = explode('_',$json->externalOrderId);
            $order_id = $order_id[0];
            $order_info = $this->model_checkout_order->getOrder($order_id);
            if(empty($order_info)){
                $_errors[] = sprintf('Order #%s not found',$order_id);
            }
        }
        if(empty($json->status) && empty($json->transactionStatus)) {
            $_errors[] = 'Empty status';
        }
        if(empty($json->amount)) {
            $_errors[] = 'Empty amount';
        }
        if(empty($json->currency)) {
            $_errors[] = 'Empty currency';
        }
        if(empty($json->identifier)) {
            $_errors[] = 'Empty identifier';
        }
        if(empty($json->orderId)) {
            $_errors[] = 'Empty orderId';
        }
        if(empty($json->transactionId)) {
            $_errors[] = 'Empty transactionId';
        }
        if(empty($json->transactionKind) && empty($json->transactionMethod)) {
            $_errors[] = 'Empty transactionKind';
        }

        if(sizeof($_errors)) {
            foreach($_errors as $err) {
                $this->twispay_log('[RESPONSE-ERRORS] '.$err);
                $this->twispay_log();

            }
            $this->twispay_log('err '.json_encode($_errors));
            return false;
        } else {
            $data = array(
                'invoice' => '',
                'order_id' => $order_id,
                'status' => (empty($json->status)) ? $json->transactionStatus : $json->status,
                'amount' => (float)$json->amount,
                'currency' => $json->currency,
                'identifier' => $json->identifier,
                'orderId' => (int)$json->orderId,
                'transactionId' => (int)$json->transactionId,
                'customerId' => (int)$json->customerId,
                'transactionKind' => (empty($json->transactionKind)) ? $json->transactionMethod : $json->transactionKind,
                'cardId' => (!empty($json->cardId)) ? (int)$json->cardId : 0,
                'timestamp' => (is_object($json->timestamp)) ? time() : $json->timestamp,
            );
            $this->twispay_log();
            $this->twispay_log('[RESPONSE] Data: '.json_encode($data));

            if(!in_array($data['status'], $this->getResultStatuses())) {
                $wrong_status['status'] = $data['status'];
                $wrong_status['message'] = $json->message;
                $wrong_status['code'] = $json->code;
                $this->twispay_log(sprintf($this->tl('[RESPONSE-ERROR] Wrong status (%s)'), json_encode($wrong_status)));
                $this->twispay_log();

                return false;
            }
            $this->twispay_log('[RESPONSE] Status complete-ok');

        }
        return json_decode(json_encode($data));

    }

    public function callback() {
        $baseurl = (!empty($_SERVER['HTTPS'])) ? 'https://' : 'http://';
        $baseurl .= $_SERVER['HTTP_HOST'];
        if(empty(trim($this->config->get('twispay_redirect_page')))){
            $page_to_redirect = $baseurl. '/index.php?route=checkout/success';
        } else {
            $page_to_redirect = trim($this->config->get('twispay_redirect_page'));
            if(stripos($page_to_redirect,'/') !==0){
                $page_to_redirect = '/'. $page_to_redirect;
            }
            $page_to_redirect = $baseurl. $page_to_redirect;
            stream_context_set_default(
                array(
                    'http' => array(
                        'method' => 'HEAD'
                    )
                )
            );
            $headers = @get_headers($page_to_redirect);
            $status = substr($headers[0], 9, 3);
            if (!($status >= 200 && $status < 400 )) {
                $page_to_redirect = $baseurl. '/index.php?route=checkout/success';
            }

        }
        $this->log_file = $this->config->get('twispay_logs').'/twispay_log.txt';

        if(file_exists($this->log_file) && filesize($this->log_file) > 2097152){
            @file_put_contents($this->log_file, PHP_EOL.PHP_EOL);
        }
        if(!empty($_POST)){
            echo "Processing ...";
            sleep(1);
            $datas = (!empty($_POST['opensslResult'])) ? json_decode($this->twispayDecrypt($_POST['opensslResult'])) : json_decode($this->twispayDecrypt($_POST['result']));
            if(!empty($datas)){
                $result = $this->checkValidation($datas);
                if(!empty($result)){
                    $this->load->model('extension/payment/twispay');
                    $this->load->model('checkout/order');
                    $transaction = $this->model_extension_payment_twispay->checktransactions($result->transactionId);
                    if(empty($transaction)){
                        $order_info = $this->model_checkout_order->getOrder($result->order_id);
                        if ($order_info && !$order_info['invoice_no']) {
                            $invoice = $this->model_extension_payment_twispay->createInvoiceNo($result->order_id,$order_info['invoice_prefix']);
                            $result->invoice = $invoice;
                            $this->model_extension_payment_twispay->loggTransaction($result);
                            $order_status_id = (!empty($this->config->get('twispay_order_status_id'))) ? $this->config->get('twispay_order_status_id') : $this->config->get('config_order_status_id');
                            $this->model_checkout_order->addOrderHistory($result->order_id, $order_status_id, 'Paid Twispay #'.$result->transactionId, true);

                        }
                    } else {
                        $this->twispay_log("[RESPONSE ERROR] : transactions exists ");
                        $this->twispay_log();
                    }
                } else {
                    $this->twispay_log("[RESPONSE ERROR] : no result ");
                    $this->twispay_log();
                }
            } else {
                $this->twispay_log("[RESPONSE ERROR] : no datas ");
                $this->twispay_log();
            }

        } else {
            echo "No post";
            $this->twispay_log("NO POST");
        }
        echo '<meta http-equiv="refresh" content="1;url='. $page_to_redirect.'" />';
        header('Refresh: 1;url=' . $page_to_redirect);
    }

    public function s2s(){
        $this->log_file = $this->config->get('twispay_logs').'/twispay_s_log.txt';
        if(file_exists($this->log_file) && filesize($this->log_file) > 2097152){
            @file_put_contents($this->log_file, PHP_EOL.PHP_EOL);
        }
        if(!empty($_POST)){
            $datas = (!empty($_POST['opensslResult'])) ? json_decode($this->twispayDecrypt($_POST['opensslResult'])) : json_decode($this->twispayDecrypt($_POST['result']));
            if(!empty($datas)){
                $result = $this->checkValidation($datas);
                if(!empty($result)){
                    $this->load->model('extension/payment/twispay');
                    $this->load->model('checkout/order');
                    $transaction = $this->model_extension_payment_twispay->checktransactions($result->transactionId);
                    if(empty($transaction)){
                            $order_info = $this->model_checkout_order->getOrder($result->order_id);
                        if ($order_info && !$order_info['invoice_no']) {
                            $invoice = $this->model_extension_payment_twispay->createInvoiceNo($result->order_id,$order_info['invoice_prefix']);
                            $result->invoice = $invoice;
                            $this->model_extension_payment_twispay->loggTransaction($result);
                            $order_status_id = (!empty($this->config->get('twispay_order_status_id'))) ? $this->config->get('twispay_order_status_id') : $this->config->get('config_order_status_id');
                            $this->model_checkout_order->addOrderHistory($result->order_id, $order_status_id,'Paid Twispay #'.$result->transactionId,true);
                            $this->twispay_log('[RESPONSE] : order registered');
                            die('OK');
                        }
                        die("[RESPONSE ERROR]: INVOICE ALREADY EXISTS");
                    } else {
                        $this->twispay_log("[RESPONSE ERROR] : transactions exists ");
                        $this->twispay_log();
                        die('OK');
                    }
                } else {
                    $this->twispay_log("[RESPONSE ERROR] : no result ");
                    $this->twispay_log();
                    die("[RESPONSE ERROR] : Validating data ");
                }
            } else {
                $this->twispay_log("[RESPONSE ERROR] : no datas ");
                $this->twispay_log();
                die("[RESPONSE ERROR] : NO DATA AFTER DECRYPT");
            }

        } else {
            $this->twispay_log("NO POST");
            die("[RESPONSE ERROR] : NO POST DATA");
        }
        $this->twispay_log('[RESPONSE] : EOF');
        die('OK');
    }

}
?>