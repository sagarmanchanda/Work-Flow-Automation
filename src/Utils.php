<?php

namespace WFA;

/**
 * This class contains general utility functions.
 */

class Utils
{
	/**
	 * Simple utility function to copy values from source array to final array.
	 *
	 * @param $source
	 */
	public static function copyValues($source) {
		$final = array();
		foreach ($source as $key => $value) {
			$final[$key] = $value;
		}

		return $final;
	}

	/**
	 * Utility function to filter a string. Prevents XSS and SQL injections.
	 *
	 * @param $string
	 */

	public static function filterThis($string) {
		$string = mysql_real_escape_string($string);
		$string = htmlspecialchars($string);

		return $string;
	}
}