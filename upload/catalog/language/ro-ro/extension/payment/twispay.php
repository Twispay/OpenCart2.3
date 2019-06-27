<?php
/**
 * Twispay Language Configurator
 *
 * Twispay general language handler for everything
 *
 * @package  Twispay/Language
 * @category Admin/Front
 * @version  1.0.1
 */
$_['text_title'] = 'Plătește în siguranță cu cardul | Twispay';
$_['button_confirm'] = 'Plătește';

/* Configuration panel from Administrator */
$_['no_s'] = 'aici';
$_['configuration_title'] = 'Configurație';
$_['configuration_edit_notice'] = 'Configurația a fost editată cu succes.';
$_['configuration_subtitle'] = 'Setări generale Twispay.';
$_['live_mode_label'] = 'Mod live';
$_['live_mode_desc'] = 'Selectează "Da" dacă dorești să folosești gateway-ul de plată în modul Live sau "Nu" dacă dorești să îl utilizezi în modul Staging.';
$_['staging_id_label'] = 'Staging Site ID';
$_['staging_id_desc'] = 'Introdu Site ID-ul pentru modul Staging. Poți obține unul de';
$_['staging_key_label'] = 'Staging Private Key';
$_['staging_key_desc'] = 'Introdu Private Key-ul pentru modul Staging. Poți obține unul de';
$_['live_id_label'] = 'Live Site ID';
$_['live_id_desc'] = 'Introdu Site ID-ul pentru modul Live. Poți obține unul de';
$_['live_key_label'] = 'Live Private Key';
$_['live_key_desc'] = 'Introdu Private Key-ul pentru modul Live. Poți obține unul de';
$_['s_t_s_notification_label'] = 'Adresă URL de notificare server-to-server';
$_['s_t_s_notification_desc'] = 'Introdu această adresă URL în contul tău Twispay.';
$_['r_custom_thankyou_label'] = 'Redirecționare la pagina personalizată de Thank You';
$_['r_custom_thankyou_desc_f'] = 'Dacă dorești să afișezi pagina personalizată de Thank You, configureaz-o aici. Poți crea o pagină personalizată nouă de';
$_['r_custom_thankyou_desc_s'] = 'aici';
$_['suppress_email_label'] = 'Dezactivează e-mailurile implicite de confirmare a plății';
$_['suppress_email_desc'] = 'Opțiunea de a dezactiva comunicarea trimisă de sistemul de e-commerce, pentru a o configura din interfața de comerciant Twispay.';
$_['configuration_save_button'] = 'Salvează modificările';
$_['live_mode_option_true'] = 'Da';
$_['live_mode_option_false'] = 'Nu';
$_['get_all_pages_default'] = 'Mod implicit';
$_['contact_email_o'] = 'E-mail de contact (Opțional)';
$_['contact_email_o_desc'] = 'Acest e-mail va fi folosit pe pagina de eroare de plată.';


/* Transaction list from Administrator */
$_['transaction_title'] = 'Lista de tranzacții';
$_['transaction_list_search_title'] = 'Caută comandă';
$_['transaction_list_all_views'] = 'Toate';
$_['transaction_list_refund_title'] = 'Tranzacție de restituire';
$_['transaction_list_recurring_title'] = 'Anulează recurența acestei comenzi';
$_['transaction_list_id'] = 'ID';
$_['transaction_list_id_cart'] = 'Numărul comenzii';
$_['transaction_list_customer_name'] = 'Numele clientului';
$_['transaction_list_transactionId'] = 'ID-ul tranzacției';
$_['transaction_list_status'] = 'Status';
$_['transaction_list_checkout_url'] = 'Checkout URL';
$_['transaction_list_refund_ptitle'] = 'Tranzacție de restituire a plății';
$_['transaction_list_refund_subtitle'] = 'Următoarea tranzație de plată va fi restituită:';
$_['transaction_list_confirm_title'] = 'Confirm';
$_['transaction_error_refund'] = 'Restituirea nu a putut fi procesată.';
$_['transaction_error_recurring'] = 'Plata recurentă nu a putut fi procesată.';
$_['transaction_success_refund'] = 'Restituirea a fost procesată cu succes. Reîncarcă pagina în câteva secunde pentru a vedea actualizarea.';
$_['transaction_success_recurring'] = 'Comandă recurentă procesată cu succes.';
$_['transaction_list_recurring_ptitle'] = 'Anulează o comandă recurentă';
$_['transaction_list_recurring_subtitle'] = 'Următoarea plată recurentă va fi anulată:';
$_['transaction_sync_finished'] = 'Sincronizarea abonamentelor terminata.';


/* Transaction log from Administrator */
$_['transaction_log_title'] = 'Jurnal de tranzacții';
$_['transaction_log_no_log'] = 'Nicio intrare înregistrată încă';
$_['transaction_log_subtitle'] = 'Jurnal de tranzacții în formă brută';


/* Administrator Dashboard left-side menu */
$_['menu_main_title'] = 'Twispay';
$_['menu_configuration_tab'] = 'Configurație';
$_['menu_transaction_tab'] = 'Lista tranzacțiilor';
$_['menu_transaction_log_tab'] = 'Jurnal de tranzacții';


/* Settings Twispay tab */
$_['s_title'] = 'Twispay';
$_['s_description'] = 'Invită-ți clienții să folosească gateway-ul de plată Twispay.';
$_['s_enable_disable_title'] = 'Activează/Dezactivează';
$_['s_enable_disable_label'] = 'Activează plățile Twispay';
$_['s_title_title'] = 'Titlu';
$_['s_title_desc'] = 'Controlează titlul pe care îl vede clientul în timpul efectuării plății.';
$_['s_description_title'] = 'Descriere';
$_['s_description_desc'] = 'Controlează descrierea pe care clientul o vede în timpul efectuării plății.';
$_['s_description_default'] = 'Plătește cu Twispay.';
$_['s_enable_methods_title'] = 'Activează căile de expediere';
$_['s_enable_methods_desc'] = 'Dacă Twispay este disponibil numai pentru anumite căi de expediere, configurează-le de aici. Lasă necompletat pentru a activa toate căile.';
$_['s_enable_methods_placeholder'] = 'Selectează căile de expediere';
$_['s_vorder_title'] = 'Acceptă comenzile virtuale';
$_['s_vorder_desc'] = 'Acceptă Twispay în cazul comenzilor virtuale';


/* Order Recieved Confirmation title */
$_['order_confirmation_title'] = 'Mulțumim. Tranzacția ta a fost aprobată.';


/* Twispay Processor( Redirect page to Twispay ) */
$_['twispay_processor_error_general'] = 'Nu ai permisiunea de a accesa acest fișier.';
$_['twispay_processor_error_no_item'] = 'Comanda nu are nici un produs.';
$_['twispay_processor_error_more_items'] = 'Comenzile cu abonamente nu pot sa contina mai mult de un produs.';
$_['twispay_processor_error_missing_configuration'] = 'Lipsa fisier de configurare pentru plugin.';


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


/* Subscriptions section */
$_['subscriptions_sync_label'] = 'Sincronizeaza abonamentele';
$_['subscriptions_sync_desc'] = 'Sincronizeaza starea locala cu starea de pe server a tuturor abonamentelor.';
$_['subscriptions_sync_button'] = 'Sincronizeaza';
$_['subscriptions_log_ok_set_status'] = '[RESPONSE]: Starea de pe server setata pentru comanda cu ID-ul: ';
$_['subscriptions_log_error_set_status'] = '[RESPONSE-ERROR]: Eroare la setarea starii pentru comanda ci ID-ul: ';
$_['subscriptions_log_error_get_status'] = '[RESPONSE-ERROR]: Eroare la extragerea starii de pe server pentru comanda cu ID-ul:A';
$_['subscriptions_log_error_call_failed'] = '[RESPONSE-ERROR]: Eroare la apelarea server-ului: ';
$_['subscriptions_log_error_http_code'] = '[RESPONSE-ERROR]: Cod HTTP neasteptat: ';


/* Administrator Order Notice */
$_['a_order_status_notice'] = 'Plata Twispay a fost finalizată cu succes';
$_['a_order_refunded_notice'] = 'Managerul site-ului a apăsat cu succes butonul de restituire';
$_['a_order_cancelled_notice'] = 'Managerul site-ului a apăsat cu succes butonul de anulare';
$_['a_order_failed_notice'] = 'Plata Twispay a fost finalizată cu eroare';
$_['a_order_hold_notice'] = 'Plata Twispay este in asteptare';


/* Others */
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
