<?php
$ref_result = array();
$reffed_result = array();


for ($i = 0; $i < count($endpoint_apis); $i++) {
	$res = array();
	$endpoint = $endpoint_apis[$i];
	if ($endpoint_uis == null){
		$url = $endpoint;
	} else {
		$url = $endpoint_uis[$i];
	}


	$query1 = 'select ?p ?o {'.
	' <'. $prefix. "/" . $uri . $separator.$resource . '> ?p ?o.'.
	'} order by ?p';

	$json1 = read_query($endpoint, $query1);
	$data = $json1["results"]["bindings"];
	$refer = read_data($data, 'p', 'o');

	foreach ($refer as $val){
		$ref_result[] = $val;
	}
//	$ref_result = array_unique($ref_result);

	$query2 = 'select ?s ?p {'.
	' ?s ?p <'. $prefix. "/" . $uri . $separator.$resource . '>.'.
	'} order by ?p';

	$json2 = read_query($endpoint, $query2);
	$data = $json2["results"]["bindings"];
	$referred = read_data($data, 's', 'p');

	foreach ($referred as $val){
		$reffed_result[] = $val;
	}
//	$reffed_result = array_unique($reffed_result);

}

header('Content-type: text/plain');
header("Access-Control-Allow-Origin: *");


if (count($ref_result) > 0){
	echo '<'. $prefix. "/" . $uri . $separator.$resource . '>'."\n";

	for ($i = 0; $i < count($ref_result); $i++){
		$values = $ref_result[$i];
		echo ' ' . resource_to_str($values['p']) . ' ' . resource_to_str($values['o']) . ' ';
		if ($i == (count($ref_result) - 1)){
			echo ".";
		} else {
			echo ";";
		}
		echo "\n";
	}
}
echo "\n";

if (count($reffed_result) > 0){
	for ($i = 0; $i < count($reffed_result); $i++){
		$values = $reffed_result[$i];
		echo resource_to_str($values['s']) . ' ' . resource_to_str($values['p']) . ' <'. $prefix. "/" . $uri . $separator.$resource . '> .' ."\n";
	}
}

exit();



function read_data($data, $c1, $c2){

	$ret = array();

	for ($i=0; $i<count($data); $i++){
		$item = array();
		if ($data[$i][$c1] != null){
			$item[$c1] = $data[$i][$c1];
		}
		if ($data[$i][$c2] != null){
			$item[$c2] = $data[$i][$c2];
		}
		$ret[] = $item;
	}

	return $ret;
}

function resource_to_str($res){
	if ($res['type'] == 'uri'){
			return '<' . $res['value'] . '>';
	}

	if ($res['type'] == 'literal'){
			$ret = '"' . $res['value'] . '"';
			if (isset($res['xml:lang'])){
				$ret .= '@' . $res['xml:lang'];
			}
			return $ret;
	}

	if ($res['type'] == 'typed-literal'){
			$ret = '"' . $res['value'] . '"';
			if (isset($res['datatype'])){
				$ret .= '^^<' . $res['datatype'] . '>';
			}
			return $ret;
	}



}

?>
