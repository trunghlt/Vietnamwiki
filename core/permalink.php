<?php

function replaceSpace($str) {
	$s = preg_replace("/[^a-zA-Z0-9\s]/", "", $str);
	$s = str_replace(" ", "_", $s);
	return $s;
	
}

function getPostPermalink($postId) {
	$p = new PostElement;
	$p->query($postId);
	
	$i = new IndexElement;
	$i->query($p->indexId);	
	
	$d = new DestinationElement;
	$d->query($i->destId);
	
	return 	"/" . replaceSpace($d->engName) 
			."-". replaceSpace($i->name) 
			."-". replaceSpace($p->title)
			."-". "P". $postId;
}

function getIndexPermalink($indexId) {
	$i = new IndexElement;
	$i->query($indexId);
	
	$d = new DestinationElement;
	$d->query($i->destId);
	
	return "/". replaceSpace($d->engName)
			."-". replaceSpace($i->name)
			."-". "I". $indexId;
}
?>