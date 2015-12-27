<?php
// If uninstall is not called from WordPress, exit
if( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

delete_option( \Korobochkin\CurrencyConverter\Models\Settings\General::$option_name );
delete_option( \Korobochkin\CurrencyConverter\Plugin::NAME . '_rates' );

delete_transient( \Korobochkin\CurrencyConverter\Plugin::NAME . '_providers' );

//wp_unschedule_event();
// TODO: Удалять крон