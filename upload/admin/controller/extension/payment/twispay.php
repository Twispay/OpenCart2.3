<?php
/**
 * @author   Twistpay
 * @version  1.0.1
 */

class ControllerExtensionPaymentTwispay extends Controller
{
    private $error = array();
    private $baseurl;

    public function index()
    {
        $this->baseurl = (!empty($_SERVER['HTTPS'])) ? 'https://' : 'http://';
        $this->baseurl .= $_SERVER['HTTP_HOST'];

        $this->load->language('extension/payment/twispay');
        $this->document->setTitle($this->language->get('heading_title'));
 
        $this->load->model('setting/setting');
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('twispay', $this->request->post);
            $this->session->data['success'] = $this->language->get('text_saved');
            $this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', true));
        }
        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/payment/twispay', 'token=' . $this->session->data['token'], true)
        );

        /* Labels */
        $data['heading_title'] = $this->language->get('heading_title');
        $data['button_save'] = $this->language->get('text_button_save');
        $data['button_cancel'] = $this->language->get('text_button_cancel');

        $data['text_edit'] = $this->language->get('text_edit');
        $data['text_testMode'] = $this->language->get('text_testMode');
        $data['text_live_site_id'] = $this->language->get('text_live_site_id');
        $data['text_live_site_key'] = $this->language->get('text_live_site_key');
        $data['text_staging_site_id'] = $this->language->get('text_staging_site_id');
        $data['text_staging_site_key'] = $this->language->get('text_staging_site_key');
        $data['text_s_t_s_notification'] = $this->language->get('text_s_t_s_notification');
        $data['text_redirect_page'] = $this->language->get('text_redirect_page');

        $data['desc_testMode'] = $this->language->get('desc_testMode');
        $data['desc_live_site_id'] = $this->language->get('desc_live_site_id');
        $data['desc_live_site_key'] = $this->language->get('desc_live_site_key');
        $data['desc_staging_site_id'] = $this->language->get('desc_staging_site_id');
        $data['desc_staging_site_key'] = $this->language->get('desc_staging_site_key');
        $data['desc_s_t_s_notification'] = $this->language->get('desc_s_t_s_notification');
        $data['desc_redirect_page'] = $this->language->get('desc_redirect_page');

        $data['entry_order_status'] = $this->language->get('entry_order_status');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_yes'] = $this->language->get('text_yes');
        $data['text_no'] = $this->language->get('text_no');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_sort_order'] = $this->language->get('entry_sort_order');
        $data['text_logs'] = $this->language->get('text_logs');

        $data['action'] = $this->url->link('extension/payment/twispay', 'token=' . $this->session->data['token'], true);
        $data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', true);

        $data['twispay_testMode'] = (isset($this->request->post['twispay_testMode'])) ? $this->request->post['twispay_testMode'] : $this->config->get('twispay_testMode');
        $data['twispay_live_site_id'] = (isset($this->request->post['twispay_live_site_id'])) ? $this->request->post['twispay_live_site_id'] : $this->config->get('twispay_live_site_id');
        $data['twispay_live_site_key'] = (isset($this->request->post['twispay_live_site_key'])) ? $this->request->post['twispay_live_site_key'] : $this->config->get('twispay_live_site_key');
        $data['twispay_staging_site_id'] = (isset($this->request->post['twispay_staging_site_id'])) ? $this->request->post['twispay_staging_site_id'] : $this->config->get('twispay_staging_site_id');
        $data['twispay_staging_site_key'] = (isset($this->request->post['twispay_staging_site_key'])) ? $this->request->post['twispay_staging_site_key'] : $this->config->get('twispay_staging_site_key');
        $data['twispay_s_t_s_notification'] = (isset($this->request->post['twispay_s_t_s_notification'])) ? $this->request->post['twispay_s_t_s_notification'] : $this->config->get('twispay_s_t_s_notification');
        $data['twispay_s_t_s_notification'] = $this->baseurl.'/index.php?route=extension/payment/twispay/s2s';
        $data['twispay_status'] = (isset($this->request->post['twispay_status'])) ? $this->request->post['twispay_status'] : $this->config->get('twispay_status');
        $data['twispay_logs'] = (isset($this->request->post['twispay_logs'])) ? $this->request->post['twispay_logs'] : $this->config->get('twispay_logs');
        $data['twispay_redirect_page'] = (isset($this->request->post['twispay_redirect_page'])) ? $this->request->post['twispay_redirect_page'] : $this->config->get('twispay_redirect_page');
        $data['twispay_sort_order'] = (isset($this->request->post['twispay_sort_order'])) ? $this->request->post['twispay_sort_order'] : $this->config->get('twispay_sort_order');

        $data['logs'] = $this->url->link('extension/payment/twispay/twispaylogs/', '&token=' . $this->session->data['token'], true);

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        $this->response->setOutput($this->load->view('extension/payment/twispay', $data));
    }

    protected function validate()
    {
        if (!$this->user->hasPermission('modify', 'extension/payment/twispay')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }
        return !$this->error;
    }


    private function makeDir($path)
    {
        return is_dir($path) || mkdir($path);
    }

    private function delTree($dir)
    {
        if (is_dir($dir)) {
            $files = array_diff(scandir($dir), array('.', '..'));

            foreach ($files as $file) {
                (is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file");
            }
            return rmdir($dir);
        }
        return false;
    }

    public function twispaylogs()
    {
        $route = $this->request->get;
        $id = str_replace(array('extension/payment/twispay/twispaylogs','/'), '', $route['route']);
        if (empty($id)) {
            $id = 0;
        }
        $this->language->load('extension/payment/twispay');
        $this->document->setTitle($this->language->get('heading_title'));
        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        $data['breadcrumbs'] = array();
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        );
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', true)
        );
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/payment/twispay', 'token=' . $this->session->data['token'], true)
        );
        $data['button_cancel'] = $this->language->get('text_button_cancel');
        $data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', true);
        $data['redir'] = $this->url->link('extension/payment/twispay/twispaylogs/');
        $data['refund'] = $this->url->link('extension/payment/twispay/twispay_refund');
        $this->load->model('extension/payment/twispay');
        $data['selected'] = $id;
        $data['token'] = $this->session->data['token'];
        $data['trans'] = $this->model_extension_payment_twispay->getTransactions($id);

        $data['customers'] = $this->model_extension_payment_twispay->getCustomers();
        $data['catalog'] = $this->request->server['HTTPS'] ? HTTPS_CATALOG : HTTP_CATALOG;

        // $this->load->model('user/api');
        // $api_info = $this->model_user_api->getApi($this->config->get('config_api_id'));
        // if ($api_info && $this->user->hasPermission('modify', 'sale/order')) {
        //     $session = new Session($this->config->get('session_engine'), $this->registry);
        //     $session->start();
        //     $this->model_user_api->deleteApiSessionBySessonId($session->getId());
        //     $this->model_user_api->addApiSession($api_info['api_id'], $session->getId(), $this->request->server['REMOTE_ADDR']);
        //     $session->data['api_id'] = $api_info['api_id'];
        //     $data['api_token'] = $session->getId();
        // } else {
        //     $data['api_token'] = '';
        // }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        $this->response->setOutput($this->load->view('extension/payment/twispay_logs', $data));
    }

    public function twispay_refund()
    {
        $this->load->helper('Twispay_TW_Logger');
        $transid = (!empty($this->request->post['transid'])) ? $this->request->post['transid'] : 0;

        $testMode = $this->config->get('twispay_testMode');

        if (!empty($testMode)) {
            $url = 'https://api-stage.twispay.com/transaction/' . $transid;
            $apiKey = $this->config->get('twispay_staging_site_key');
        } else {
            $url = 'https://api.twispay.com/transaction/' . $transid;
            $apiKey = $this->config->get('twispay_live_site_key');
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, array( "Authorization: Bearer " . $apiKey, "Accept: application/json" ));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $contents = curl_exec($ch);
        curl_close($ch);

        Twispay_TW_Logger::twispay_tw_r_log($contents);
        $json = json_decode($contents);

        if ($json->message == 'Success') {
            $data = array(
                'status'    => 'Success',
                'rawdata'   => $json,
                'transid'   => $json->data->transactionId,
            ) ;
            $this->load->model('extension/payment/twispay');
            $refund = $this->model_extension_payment_twispay->refund($transid);
            $data['refunded'] = ($refund == 1) ? '1' :'0';
        } else {
            $data = array(
                'status'    => $json->error[0]->message,
                'rawdata'   => $json,
                'transid'   => 0,
                'refunded'  => 0
            ) ;
        }
        echo json_encode($data);
    }

    public function install()
    {
        $path = DIR_LOGS.'/twispay_logs/';
        $this->makeDir($path);

        $this->load->model('setting/setting');
        $this->model_setting_setting->editSetting('payment_twispay', array('twispay_testMode' => '1','twispay_logs' => $path));

        $this->load->model('extension/payment/twispay');
        $this->model_extension_payment_twispay->createTransactionTable();
    }

    public function uninstall()
    {
        $this->load->model('setting/setting');
        $this->model_setting_setting->deleteSetting('payment_twispay');

        $this->load->model('extension/payment/twispay');
        $this->model_extension_payment_twispay->deleteTransactionTable();

        $this->delTree(DIR_LOGS.'/twispay_logs/');
    }
}
