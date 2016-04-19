<?php

namespace WFA;

/**
 * This class contains general utility functions.
 */

class Utils
{
	public static function copyValues($source) {
		$final = array();
		foreach ($source as $key => $value) {
			$final[$key] = $value;
		}

		return $final;
	}
}
