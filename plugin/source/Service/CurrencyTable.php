<?php
namespace Korobochkin\Currency\Service;

use HtmlTableGenerator\Table;
use Korobochkin\Currency\Plugin;

class CurrencyTable {

	public $parameters;

	/**
	 * Список валют
	 * Базовая валюта
	 * Показывать процент или нет
	 *
	 */

	public $table;

	public function get_table() {
		if( !empty( $this->parameters['currency_list'] ) && \Korobochkin\Currency\Service\Rates::is_available() ) {

			$this->table = new Table();
			$this->table->set_heading(
				array(
					__( 'Currency', Plugin::NAME ),
					__( 'Rate', Plugin::NAME ) . ' ' . $this->parameters['base_currency'],
					'%'
				)
			);
			$rates = get_option( \Korobochkin\Currency\Plugin::NAME . '_rates' );

			foreach( $this->parameters['currency_list'] as $currency ) {
				$rate = \Korobochkin\Currency\Service\Rates::get_rate( $currency, $this->parameters['base_currency'] );
				$rate = number_format_i18n($rate, 2);
				$percentage = \Korobochkin\Currency\Service\Rates::get_change_rate_percentage( $currency );
				$percentage = number_format_i18n($percentage, 2);

				if( $rate ) {
					$flag = '';
					if( $this->parameters['flag_size'] > 0 ) {
						$flag = '<img src="' . \Korobochkin\Currency\Service\Rates::get_currency_flag( $currency, $this->parameters['flag_size'] ) . '">';
					}

					$this->table->add_row(
						esc_html( $currency ) . $flag,
						$rate,
						$percentage
					);
				}
			}
			return $this->table->generate();
		}

		return '';
	}
}
