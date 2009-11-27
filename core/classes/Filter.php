<?php
class Filter {

	public static function number($n) {
		return preg_match("/[-+]?[0-9]+/", $n)? $n : Filter::dieToInvalidInput();
	}	
	
	public static function valueIfIsset($s) {
		if (isset($s)) 
			return $s;
		else dieToInvalidInput();
	}
}
?>