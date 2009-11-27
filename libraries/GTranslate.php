<?php
	function translateTexts($src_texts = array(), $src_lang, $dest_lang){
	  //setting language pair
	  $lang_pair = $src_lang.'|'.$dest_lang;

	  $src_texts_query = "";
	  foreach ($src_texts as $src_text){
		$src_texts_query .= "&q=".urlencode($src_text);
	  }

	  $url = "http://ajax.googleapis.com/ajax/services/language/translate?v=1.0".$src_texts_query."&langpair=".urlencode($lang_pair);

	  // sendRequest
	  // note how referer is set manually

	  $ch = curl_init();
	  curl_setopt($ch, CURLOPT_URL, $url);
	  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	  curl_setopt($ch, CURLOPT_REFERER, "http://www.vietnamwiki.net");
	  $body = curl_exec($ch);
	  curl_close($ch);

	  // now, process the JSON string
	  $json = json_decode($body, true);

	  if ($json['responseStatus'] != 200){
		return false;
	  }


	  $results = $json['responseData'];
	  
	  $return_array = array();
	  
	  foreach ($results as $result){
		if ($result['responseStatus'] == 200){
		  $return_array[] = $result['responseData']['translatedText'];
		} else {
		  $return_array[] = false;
		}
	  }
	  
	  //return translated text
	  return $return_array;
	}
?>