<?php
$result = array();

$result['resource'] = $prefix. "/" . $uri . $separator.$resource;
$result['results'] = array();


for ($i = 0; $i < count($endpoint_apis); $i++) {
	$res = array();
	$endpoint = $endpoint_apis[$i];
	if ($endpoint_uis == null){
		$url = $endpoint;
	} else {
		$url = $endpoint_uis[$i];
	}

	$ep = array();
	$ep['ui'] = $url;
	$ep['api'] = $endpoint;

	$res['endpoint'] = $ep;

	$query1 = 'select ?p ?o {'.
	' <'. $prefix. "/" . $uri . $separator.$resource . '> ?p ?o.'.
	'} order by ?p';

	$json1 = read_query($endpoint, $query1);
	$data = $json1["results"]["bindings"];
	$refer = read_data($data, 'p', 'o');
	$res['refer'] = $refer;

	$query2 = 'select ?s ?p {'.
	' ?s ?p <'. $prefix. "/" . $uri . $separator.$resource . '>.'.
	'} order by ?p';

	$json2 = read_query($endpoint, $query2);
	$data = $json2["results"]["bindings"];
	$referred = read_data($data, 's', 'p');
	$res['referred'] = $referred;

	$result['results'][] = $res;

}

header('Content-type: application/json');
header("Access-Control-Allow-Origin: *");
echo json_encode($result);
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

?>
