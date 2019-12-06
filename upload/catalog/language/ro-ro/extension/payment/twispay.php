<?php
/**
 * Twispay Language Configurator
 *
 * Twispay general language handler for front-store
 *
 * @author   Twistpay
 * @version  1.0.1
 */

/* General */
$_['text_title']                    = 'Plătește în siguranță cu cardul | Twispay';
$_['button_confirm']                = 'Plătește';
$_['button_processing']             = 'Se procesează ...';
$_['button_retry']                  = 'Încearcă din nou';
$_['ajax_error']                    = 'A intervenit o eroare: ';
$_['processing']                    = 'Se procesează ...';
$_['no_post']                       = '[RESPONSE-ERROR]: lipsa_post';

$_['general_error_title']           = 'S-a petrecut o eroare:';
$_['general_error_desc_f']          = 'Plata nu a putut fi procesată. Te rog';
$_['general_error_desc_try_again']  = ' încearcă din nou';
$_['general_error_desc_or']         = ' sau';
$_['general_error_desc_contact']    = ' contactează';
$_['general_error_desc_s']          = ' administratorul site-ului.';
$_['general_error_hold_notice']     = ' Plata este in asteptare.';

/* Order Notice */
$_['a_order_status_notice']         = 'Plata Twispay a fost finalizată cu succes';
$_['a_order_refunded_notice']       = 'Managerul site-ului a apăsat cu succes butonul de restituire';
$_['a_order_failed_notice']         = 'Plata Twispay a fost finalizată cu eroare';
$_['a_order_hold_notice']           = 'Plata Twispay este in asteptare';

/* Validation LOG insertor */
$_['log_ok_string_decrypted']       = '[RESPONSE]: string decriptat: ';
$_['log_ok_response_data']          = '[RESPONSE]: Data: ';
$_['log_ok_status_complete']        = '[RESPONSE]: Status complet-ok';
$_['log_ok_status_refund']          = '[RESPONSE]: Status refund-ok pentru comanda cu ID-ul: ';
$_['log_ok_status_failed']          = '[RESPONSE]: Status failed pentru comanda cu ID-ul: ';
$_['log_ok_status_voided']          = '[RESPONSE]: Status voided pentru comanda cu ID-ul: ';
$_['log_ok_status_cenceled']        = '[RESPONSE]: Status canceled pentru comanda cu ID-ul: ';
$_['log_ok_status_charged_back']    = '[RESPONSE]: Status charged back pentru comanda cu ID-ul: ';
$_['log_ok_status_hold']            = '[RESPONSE]: Status on-hold pentru comanda cu ID-ul: ';
$_['log_ok_validating_complete']    = '[RESPONSE]: Validare cu succes pentru comanda cu ID-ul: %s';

$_['log_error_validating_failed']   = '[RESPONSE-ERROR]: Validare esuată pentru comanda cu ID-ul: ';
$_['log_error_decryption_error']    = '[RESPONSE-ERROR]: Decriptarea nu a funcționat.';
$_['log_error_invalid_order']       = '[RESPONSE-ERROR]: Comanda nu există.';
$_['log_error_wrong_status']        = '[RESPONSE-ERROR]: Status greșit: ';
$_['log_error_empty_status']        = '[RESPONSE-ERROR]: Status nul';
$_['log_error_empty_identifier']    = '[RESPONSE-ERROR]: Identificator nul';
$_['log_error_empty_external']      = '[RESPONSE-ERROR]: ExternalOrderId gol';
$_['log_error_empty_transaction']   = '[RESPONSE-ERROR]: TransactionID nul';
$_['log_error_empty_response']      = ' [RESPONSE-ERROR]: Răspunsul primit este nul.';
$_['log_error_invalid_private']     = '[RESPONSE-ERROR]: Cheie privată nevalidă.';
