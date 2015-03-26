<?php
class Validation {

	static public function length_interval($string, $length_min, $length_max = 255) {
		$result = true;
		if(strlen($string) < $length_min) {
			$result = false;
		}
		if(strlen($string) > $length_max) {
			$result = false;
		}
		return $result;
	}

	static public function email($email) {
		return (filter_var($email, FILTER_VALIDATE_EMAIL));
	}

}