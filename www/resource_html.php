<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?php echo $resource; ?></title>
<style>
h1 {
	background-color: #e0e0e0;
	padding:0.5em;
	width:95%;
}

.table {
	border: 1px #333333 solid;
}

.table th {
	background-color: #e0e0e0;
}

div#footer {
	margin-top:2em;
	background-color: #e0e0e0;
	width:95%;
	padding:0.5em;

}
div#footer span {
	padding:0;
	margin:0;
	font-weight:bold;
	font-size:larger;
}

div#footer h3 {
	font-size:smaller;
	font-weight:normal;
}

</style>
</head>
<h1>About : <?php echo ($prefix. "/" . $uri . $separator.$resource); ?></h1>


<?php


for ($i = 0; $i < count($endpoint_apis); $i++) {
	$endpoint = $endpoint_apis[$i];
	if ($endpoint_uis == null){
		$url = $endpoint;
	} else {
		$url = $endpoint_uis[$i];
	}

	$query0 = 'select ?lat ?lng {'.
	' <'. $prefix. "/" . $uri . $separator.$resource . '> '.
    '<http://www.w3.org/2003/01/geo/wgs84_pos#lat> ?lat;'.
    '<http://www.w3.org/2003/01/geo/wgs84_pos#long> ?lng.'.
	'}';
	$json0 = read_query($endpoint, $query0);


	$query_refer = 'select ?p ?o {'.
	' <'. $prefix. "/" . $uri . $separator.$resource . '> ?p ?o.'.
	'} order by ?p';

	$json_refer = read_query($endpoint, $query_refer);

	$query_referred = 'select ?s ?p {'.
	' ?s ?p <'. $prefix. "/" . $uri . $separator.$resource . '>.'.
	'} order by ?p';

	$json_referred = read_query($endpoint, $query_referred);

	if (count($json_refer["results"]["bindings"]) == 0 && count($json_referred["results"]["bindings"]) == 0){
		continue;
	}

	if (count($json0["results"]["bindings"]) != 0){
		$data = $json0["results"]["bindings"];
		$lat = $data[0]["lat"]["value"].trim();
		$lng = $data[0]["lng"]["value"].trim();
		$lat = floatval($lat);
		$lng = floatval($lng);

		echo '<div>';
		echo '<iframe width="350" height="300" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" ';
		echo 'src="http://www.openstreetmap.org/export/embed.html?bbox=' . ($lng - 0.1) . '%2C' . ($lat - 0.1) . '%2C' . ($lng + 0.1) . '%2C' . ($lat + 0.1) . '&amp;';
		echo 'layer=mapnik&amp;marker=' . $lat . '%2C' . $lng . '" style="border: 1px solid black"></iframe>';
		echo '<br/><small><a href="http://www.openstreetmap.org/?mlat=' . $lat .  '&amp;mlon=' . $lng . '#map=15/'. $lat .'/' . $lng . '" target="_blank">';
		echo '大きな地図を表示</a></small>';
		echo '</div>';

	}

	echo ('<div>'."\n");

	echo '<h2>ENDPOINT : <a href=' . $url . '>' . $url . '</a></h2>'."\n";
	echo '<h3>[' . $resource . ']が参照しているリソース一覧</h3>'."\n";


	$data = $json_refer["results"]["bindings"];

	echo ('<div>'."\n");
	view_data($data, 'p', 'Predicate', 'o', 'Object');
	echo ('</div>'."\n");


	echo '<h3>[' . $resource . ']を参照しているリソース一覧</h3>'."\n";

	$data = $json_referred["results"]["bindings"];

	echo ('<div>'."\n");
	view_data($data, 's', 'Subject', 'p', 'Predicate');
	echo ('</div>'."\n");
	echo ('</div>'."\n");
}



function view_data($data, $c1, $c1n, $c2, $c2n){
	if(count($data) == 0){
		echo 'なし'."\n";
		return;
	}

	echo '<table class="table">'."\n";
	echo '<tr><th>' . $c1n . '</th><th>' . $c2n .'</th></tr>'."\n";
	for ($i=0; $i<count($data); $i++){
		echo '<tr>'."\n";
		if ($data[$i][$c1] != null){
			echo '<td>';
			if ($data[$i][$c1]["type"] == 'uri'){
				echo '&lt;<a href="' . $data[$i][$c1]["value"] . '">' . $data[$i][$c1]["value"] . '</a>&gt; ';
			} else {
				echo '"' + $data[$i][$c1]["value"] + '"';
			}
			echo  '</td>'."\n";
		}
		if ($data[$i][$c2] != null){
			echo '<td>';
			if ($data[$i][$c2]["type"] == 'uri'){
				echo '&lt;<a href="' . $data[$i][$c2]["value"] . '">' . $data[$i][$c2]["value"] . '</a>&gt; ';
			} else {
				echo '"' . $data[$i][$c2]["value"] . '"';
			}
			echo '</td>'. "\n";
		}
		echo '</tr>'. "\n";
	}
	echo '</table><br/>';
}

?>

<div id="footer">
This page is generated by <span>LOD Reference Resolver</span>.
<h3>add URL Param "output=json" to get the data by JSON format. </h3>
[<a href="https://github.com/Nobutake/ref_resolver" target="_blank">View this project on GitHub</a>]
</div>
</html>
