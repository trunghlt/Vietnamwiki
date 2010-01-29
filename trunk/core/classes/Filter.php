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
			if(preg_match("/\w+/", $n) && ($case == 3))
				return $n;
		return (header("location:$location"));
	}
	public static function filterInputText($n) {
		$n = str_replace("<script",'',$n);
		$n = str_replace("<?php",'',$n);
		return $n;
	}	
	public static function filterEmail($n) {
		$str = "/[a-zA-Z0-9._]+\@[a-zA-Z0-9]{2,}\.[a-zA-Z]{2,}/";
		if(preg_match($str,$n))
			return $n;
	}		
}
?>