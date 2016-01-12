<?php


function read_query($url, $query){
	$sep = '?';
	if (strpos('?', $url) >= 0){
		$sep = '&';
	}

	$uri = $url . $sep . 'query='. urlencode($query) . '&output=json';

	$html = file_get_contents($uri);

	return json_decode($html, true);
}
