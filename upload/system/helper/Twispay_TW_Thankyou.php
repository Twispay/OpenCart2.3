<?php
/**
 * Twispay Helpers
 *
 * Redirects user to the thank you page.
 *
 * @author   Twistpay
 * @version  1.0.0
 */

/* Security class check */
if (! class_exists('Twispay_TW_Thankyou')) :
    /**
     *Class that redirects user to the thank you page.
     */
    class Twispay_TW_Thankyou
    {
        /**
         * Twispay Gateway
         *
         * @public
         * @return void
         */
         public static function redirect()
         {
             $page_to_redirect = HTTPS_SERVER.'index.php?route=checkout/success';
             echo '<meta http-equiv="refresh" content="1;url='. $page_to_redirect.'" />';
             header('Refresh: 1;url=' . $page_to_redirect);
         }
    }
endif; /* End if class_exists. */
