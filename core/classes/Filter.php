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
	public static function filterInput($n,$location,$case) {

			if (preg_match("/[-+]?[0-9]+/", $n) && ($case==1))
				return $n;
			if(preg_match("/\w{5,}/", $n) && ($case == 2))
				return $n;
		return (header("location:$location"));
	}	
}
?>