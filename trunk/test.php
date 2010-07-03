<?php
class Article {
	public $title = "";
 	public $summary = ""; 
	public $body = "";

	public function write() {
		echo "<b>".$this->title."</b><p/>";
		echo $this->body;
	}
}
global $memcache;
$memcache = new Memcache;
$memcache->connect("localhost", 11211) or die("Could not connect to memcache server");
$article = new Article;
$article->title = "Hello world";
$article->body = "Testing...";

#$memcache->set(1,$article);
#echo "Successfully add hash pair {1,2} to the memory<br/>";

$result = $memcache->get("indexMenus");
if ($result != NULL) { echo "Data stored from the cache: <br/>"; }
else die("Empty cache");

print_r($result);

?>
