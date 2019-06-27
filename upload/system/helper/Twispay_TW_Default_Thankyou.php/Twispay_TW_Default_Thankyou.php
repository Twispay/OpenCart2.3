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
if ( ! class_exists( 'Twispay_TW_Default_Thankyou' ) ) :
    /**
     * Twispay Helper Class
     *
     * @class   Twispay_TW_Default_Thankyou
     * @version 0.0.1
     *
     *
     * Class that redirects user to the order page.
     */
    class Twispay_TW_Default_Thankyou {
      /**
       * Twispay Gateway Constructor
       *
       * @public
       * @return void
       */
      public function __construct( ) {
        //Page redirect
        /* Set host url */
        $base_url = (!empty($_SERVER['HTTPS'])) ? 'https://' : 'http://';
        $base_url .= $_SERVER['HTTP_HOST'];
        $page_to_redirect = $base_url.'/index.php?route=checkout/success';
        // echo '<meta http-equiv="refresh" content="1;url='. $page_to_redirect.'" />';
        // header('Refresh: 1;url=' . $page_to_redirect);
        print_r('redirect to default: '.$page_to_redirect);
      }
    }
endif; /* End if class_exists. */
