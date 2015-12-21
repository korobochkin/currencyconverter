<?php
namespace Korobochkin\CurrencyConverter\Models;

use Korobochkin\CurrencyConverter\Models\Currencies\Currencies;
use Korobochkin\CurrencyConverter\Plugin;

class Country {

	private $currency = null;

	private $currency_iso_code = null;

	private $country_iso_code = null;

	public function __construct() {

	}

	public function set_country_by_currency( $currency ) {
		$this->currency_iso_code = $currency;
		$this->prepare_country_iso_code_from_currency_iso_code();
		$currency = Currencies::get_currency($currency);
		if( $currency ) {
			$this->currency = $currency;
		}
	}

	public function get_flag_url( $size = 16, $style = 'flat' ) {
		switch( $size ) {
			case 0:
				return '';
				break;
			case 16:
			case 24:
			case 32:
			case 48:
			case 64:
				break;

			default:
				$size = 16;
				break;
		}

		switch( $style ) {
			case 'flat':
			case 'shiny':
				break;

			default:
				$style = 'flat';
				break;
		}

		$flag_url = $this->get_flag_url_by_fist_two_letter( $size, $style );
		if ( $flag_url  ) {
			return $flag_url;
		}
		return false;
	}

	private function get_flag_url_by_table_of_correspondences( $size = 16, $style = 'flat' ) {
		if( !empty( $this->currency['flag_name'] ) ) {
			$url = plugin_dir_url( $GLOBALS['CurrencyConverterPlugin']->plugin_path );
			$url .= 'libs/flags/flags-iso/flat/';
			switch( $size ) {
				case 0:
					return '';
					break;
				case 16:
				case 24:
				case 32:
				case 48:
				case 64:
					$url .= (string)$size;
					break;

				default:
					$url .= '16';
					break;
			}
			$url .= '/' . $this->currency['flag_name'] . '.png';
			return $url;
		}
		return false;
	}

	private function get_flag_url_by_fist_two_letter( $size = 16, $style = 'flat' ) {
		if( $this->country_iso_code ) {
			$path = dirname($GLOBALS['CurrencyConverterPlugin']->plugin_path);
			$path .= '/libs/flags/flags-iso/' . $style . '/'. $size . '/' . $this->country_iso_code . '.png';
			$flag_available = file_exists( $path );
			if( $flag_available ) {
				$url = plugin_dir_url( $GLOBALS['CurrencyConverterPlugin']->plugin_path );
				$url .= '/libs/flags/flags-iso/' . $style . '/'. $size . '/' . $this->country_iso_code . '.png';
				return $url;
			}
			else {
				$url = plugin_dir_url( $GLOBALS['CurrencyConverterPlugin']->plugin_path );
				$url .= '/libs/flags/flags-iso/' . $style . '/'. $size . '/_unknown.png';
				return $url;
			}
		}
		return false;
	}

	private function prepare_country_iso_code_from_currency_iso_code() {
		if($this->currency_iso_code) {
			$country_iso_code = substr($this->currency_iso_code, 0, 2);
			if($country_iso_code) {
				$this->country_iso_code = $country_iso_code;
			}
		}
	}

	public function is_flag_available() {
		if($this->currency_iso_code) {
			$country_iso_code = substr($this->currency_iso_code, 0, 2);
			if($country_iso_code) {
				$path = dirname($GLOBALS['CurrencyConverterPlugin']->plugin_path);
				$path .= '/libs/flags/flags-iso/flat/16/' . $country_iso_code . '.png';
				$flag_available = file_exists( $path );

				if($flag_available) {
					return true;
				}
			}
		}
		return false;
	}
}
