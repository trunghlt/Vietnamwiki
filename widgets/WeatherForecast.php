<?php 
class WeatherForecast {
	public $wholeContent;
	public $tendayForecast;
	public $todayForecast;
	public $forecaCode;
	
	public function WeatherForecast($forecaCode) {
		$this->forecaCode = $forecaCode;
		$this->wholeContent = file_get_contents("http://www.foreca.com/Vietnam/".$this->forecaCode."?tenday&quick_units=metric");
	}
	
	public function getTendayForecast() {
		$matchingPattern = "/<\!--\sSTART\s-->(.*)<\!--\sEND\s-->/s";
		if (preg_match($matchingPattern, $this->wholeContent, $tendayForecast)) {
			$this->tendayForecast = $tendayForecast[0];
			
			//delete "10 day forecast"
			$delPattern = "/10 day forecast/";
			$this->tendayForecast = preg_replace($delPattern, "", $this->tendayForecast);
			
			//delete hyperlink
			$delPattern = "/<a\s*href\s*.*>/i";
			$this->tendayForecast = preg_replace($delPattern, "", $this->tendayForecast);
			$delPattern = "/<\/a>/i";
			$this->tendayForecast = preg_replace($delPattern, "", $this->tendayForecast);
			
			//delete "detail"
			$delPattern = '/<span class="more">Details<\/span>/';
			$this->tendayForecast = preg_replace($delPattern, "", $this->tendayForecast);
			
			//add origrinal path to url
			$linkPattern = '/(src|href)=\"(.*)\"/i';
			$replacedLinkPattern = '$1="http://www.foreca.com$2"';
			$this->tendayForecast = preg_replace($linkPattern, $replacedLinkPattern, $this->tendayForecast);

			return $this->tendayForecast;
		}
		return false;
	}
	
	public function getTodayForecast() {
		$matchingPattern = "/<div class=\"c1\s.*\/div>/ismU";
		if (preg_match($matchingPattern, $this->tendayForecast, $todayForecast)) {
			$this->todayForecast = $todayForecast[0];
			return $this->todayForecast;
		}
		return false;
	}
}
?>
