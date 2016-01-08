<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?php echo $resource; ?></title>
<style>
.table {
	border: 1px #333333 solid;
}

.table th {
	background-color: #e0e0e0;
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


	$query1 = 'select ?p ?o {'.
	' <'. $prefix. "/" . $uri . $separator.$resource . '> ?p ?o.'.
	'} order by ?p LIMIT 100';

	$json1 = read_query($endpoint, $query1);

	$query2 = 'select ?s ?p {'.
	' ?s ?p <'. $prefix. "/" . $uri . $separator.$resource . '>.'.
	'} order by ?p LIMIT 100';

	$json2 = read_query($endpoint, $query2);

	if (count($json1["results"]["bindings"]) == 0 && count($json2["results"]["bindings"]) == 0){
		continue;
	}

	echo ('<div>'."\n");

	echo '<h2>ENDPOINT : <a href=' . $url . '>' . $url . '</a></h2>'."\n";
	echo '<h3>[' . $resource . ']が参照しているリソース一覧</h3>'."\n";


	$data = $json1["results"]["bindings"];

	echo ('<div>'."\n");
	view_data($data, 'p', 'Predicate', 'o', 'Object');
	echo ('</div>'."\n");


	echo '<h3>[' . $resource . ']を参照しているリソース一覧</h3>'."\n";

	$data = $json2["results"]["bindings"];

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

<div id="ep">
</div>
</html>
