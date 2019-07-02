<?php
/**
 * Twispay Language Configurator
 *
 * Twispay general language handler for front-store
 *
 * @package  Twispay/Language
 * @version  1.0.1
 */

/* General */
$_['text_title'] = 'Plătește în siguranță cu cardul | Twispay';
$_['button_confirm'] = 'Plătește';
$_['transaction_exists'] = '[RESPONSE-ERROR]: tranzacția există';
$_['no_post'] = '[RESPONSE-ERROR]: lipsa_post';

$_['general_error_title'] = 'S-a petrecut o eroare:';
$_['general_error_desc_f'] = 'Plata nu a putut fi procesată. Te rog';
$_['general_error_desc_try_again'] = ' încearcă din nou';
$_['general_error_desc_or'] = ' sau';
$_['general_error_desc_contact'] = ' contactează';
$_['general_error_desc_s'] = ' administratorul site-ului.';
$_['general_error_hold_notice'] = ' Plata este in asteptare.';
$_['general_error_invalid_key'] = ' Cheie de siguranță nevalidă.';
$_['general_error_invalid_order'] = ' Comanda nu există.';
$_['general_error_invalid_private'] = ' Cheie privată nevalidă.';

/* Order Notice */
$_['a_order_status_notice'] = 'Plata Twispay a fost finalizată cu succes';
$_['a_order_refunded_notice'] = 'Managerul site-ului a apăsat cu succes butonul de restituire';
$_['a_order_cancelled_notice'] = 'Managerul site-ului a apăsat cu succes butonul de anulare';
$_['a_order_failed_notice'] = 'Plata Twispay a fost finalizată cu eroare';
$_['a_order_hold_notice'] = 'Plata Twispay este in asteptare';

/* Validation LOG insertor */
$_['log_ok_decrypted_string'] = '[RESPONSE]: string decriptat: ';
$_['log_ok_response_data'] = '[RESPONSE]: Data: ';
$_['log_ok_status_complete'] = '[RESPONSE]: Status complet-ok';
$_['log_ok_status_refund'] = '[RESPONSE]: Status refund-ok pentru comanda cu ID-ul: ';
$_['log_ok_status_failed'] = '[RESPONSE]: Status failed pentru comanda cu ID-ul: ';
$_['log_ok_status_hold'] = '[RESPONSE]: Status on-hold pentru comanda cu ID-ul: ';
$_['log_ok_status_uncertain'] = '[RESPONSE]: Status uncertain pentru comanda cu ID-ul: ';
$_['log_ok_validating_complete'] = '[RESPONSE]: Validare cu succes pentru comanda cu ID-ul: %s';

$_['log_error_validating_failed'] = '[RESPONSE-ERROR]: Validare esuată pentru comanda cu ID-ul: ';
$_['log_error_decryption_error'] = '[RESPONSE-ERROR]: Decriptarea nu a funcționat.';
$_['log_error_invalid_order'] = '[RESPONSE-ERROR]: Comanda nu există.';
$_['log_error_wrong_status'] = '[RESPONSE-ERROR]: Status greșit: ';
$_['log_error_empty_status'] = '[RESPONSE-ERROR]: Status nul';
$_['log_error_empty_identifier'] = '[RESPONSE-ERROR]: Identificator nul';
$_['log_error_empty_external'] = '[RESPONSE-ERROR]: ExternalOrderId gol';
$_['log_error_empty_transaction'] = '[RESPONSE-ERROR]: TransactionID nul';
$_['log_error_empty_response'] = ' [RESPONSE-ERROR]: Răspunsul primit este nul.';
$_['log_error_invalid_private'] = '[RESPONSE-ERROR]: Cheie privată nevalidă.';
$_['log_error_invalid_key'] = '[RESPONSE-ERROR]: Cheie de identificare a comenzii nevalidă.';
$_['log_error_openssl'] = '[RESPONSE-ERROR]: opensslResult: ';

/* Settings Twispay tab */
// $_['s_title'] = 'Twispay';
// $_['s_description'] = 'Invită-ți clienții să folosească gateway-ul de plată Twispay.';
// $_['s_enable_disable_title'] = 'Activează/Dezactivează';
// $_['s_enable_disable_label'] = 'Activează plățile Twispay';
// $_['s_title_title'] = 'Titlu';
// $_['s_title_desc'] = 'Controlează titlul pe care îl vede clientul în timpul efectuării plății.';
// $_['s_description_title'] = 'Descriere';
// $_['s_description_desc'] = 'Controlează descrierea pe care clientul o vede în timpul efectuării plății.';
// $_['s_description_default'] = 'Plătește cu Twispay.';
// $_['s_enable_methods_title'] = 'Activează căile de expediere';
// $_['s_enable_methods_desc'] = 'Dacă Twispay este disponibil numai pentru anumite căi de expediere, configurează-le de aici. Lasă necompletat pentru a activa toate căile.';
// $_['s_enable_methods_placeholder'] = 'Selectează căile de expediere';
// $_['s_vorder_title'] = 'Acceptă comenzile virtuale';
// $_['s_vorder_desc'] = 'Acceptă Twispay în cazul comenzilor virtuale';

/* Order Recieved Confirmation title */
// $_['order_confirmation_title'] = 'Mulțumim. Tranzacția ta a fost aprobată.';

/* Twispay Processor( Redirect page to Twispay ) */
// $_['twispay_processor_error_general'] = 'Nu ai permisiunea de a accesa acest fișier.';
// $_['twispay_processor_error_no_item'] = 'Comanda nu are nici un produs.';
// $_['twispay_processor_error_more_items'] = 'Comenzile cu abonamente nu pot sa contina mai mult de un produs.';
// $_['twispay_processor_error_missing_configuration'] = 'Lipsa fisier de configurare pentru plugin.';

/* Subscriptions section */
// $_['subscriptions_sync_label'] = 'Sincronizeaza abonamentele';
// $_['subscriptions_sync_desc'] = 'Sincronizeaza starea locala cu starea de pe server a tuturor abonamentelor.';
// $_['subscriptions_sync_button'] = 'Sincronizeaza';
// $_['subscriptions_log_ok_set_status'] = '[RESPONSE]: Starea de pe server setata pentru comanda cu ID-ul: ';
// $_['subscriptions_log_error_set_status'] = '[RESPONSE-ERROR]: Eroare la setarea starii pentru comanda ci ID-ul: ';
// $_['subscriptions_log_error_get_status'] = '[RESPONSE-ERROR]: Eroare la extragerea starii de pe server pentru comanda cu ID-ul:A';
// $_['subscriptions_log_error_call_failed'] = '[RESPONSE-ERROR]: Eroare la apelarea server-ului: ';
// $_['subscriptions_log_error_http_code'] = '[RESPONSE-ERROR]: Cod HTTP neasteptat: ';
?>
