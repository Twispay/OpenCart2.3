<?php
/**
 * Twispay Helpers
 *
 * Redirects user to the order page.
 *
 * @package  Twispay/Front
 * @category Front
 * @author   @TODO
 * @version  0.0.1
 */

/* Security class check */
if ( ! class_exists( 'Twispay_TW_Notification' ) ) :
    /**
     * Twispay Helper Class
     *
     * @class   Twispay_TW_Default_Thankyou
     * @version 0.0.1
     *
     *
     * Class that redirects user to the order page.
     */
    class Twispay_TW_Notification {

      public static function notice_to_cart($that,$retry_url = '',$text = ''){
        if(!strlen($retry_url)){
          $retry_url = $that->url->link('checkout/cart').'&tw_reload=true';
        }
        Twispay_TW_Notification::print_notice($that,$retry_url,$text);
      }
      public static function notice_to_checkout($that,$retry_url = '',$text = ''){
        if(!strlen($retry_url)){
          $retry_url = $that->url->link('checkout/checkout').'&tw_reload=true';
          // $retry_url = $this->url->link('checkout/checkout').'&tw_reload=true';//wc_get_checkout_url() . 'order-pay/' . $orderId . '/?pay_for_order=true&key=' . $order->get_data()['order_key'] . '&tw_reload=true';
        }
        Twispay_TW_Notification::print_notice($that,$retry_url,$text);
      }
      private static function print_notice($that,$retry_url,$text){
        //Print error notice
        ?>
          <div class="error notice" style="margin-top: 20px;">
            <h3><?= $that->language->get('general_error_title'); ?></h3>
            <?php if(strlen($text)){ ?>
              <span><?= $text; ?></span>
            <?php } ?>
            <?php if('0' == $that->configuration['contact_email']){ ?>
              <p><?= $that->language->get('general_error_desc_f'); ?> <a href="<?= $retry_url; ?>"><?= $that->language->get('general_error_desc_try_again'); ?></a> <?= $that->language->get('general_error_desc_or') . $that->language->get('general_error_desc_contact') . $that->language->get('general_error_desc_s'); ?></p>
            <?php } else { ?>
              <p><?= $that->language->get('general_error_desc_f'); ?> <a href="<?= $retry_url; ?>"><?= $that->language->get('general_error_desc_try_again'); ?></a> <?= $that->language->get('general_error_desc_or'); ?> <a href="mailto:<?= $that->configuration['contact_email']; ?>"><?= $that->language->get('general_error_desc_contact'); ?></a> <?= $that->language->get('general_error_desc_s'); ?></p>
            <?php } ?>
          </div>
        <?php
      }
    }
endif; /* End if class_exists. */
