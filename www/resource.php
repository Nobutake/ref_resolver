<?php


// エンドポイントAPIのURL
$endpoint_apis = array(
	"http://lodcu.cs.chubu.ac.jp/SparqlEPCU/RDFServer.jsp?reqtype=api&project=zoo",
	"http://lodcu.cs.chubu.ac.jp/SparqlEPCU/RDFServer.jsp?reqtype=api&project=planetarium");

// エンドポイントUIのURL（APIのURLと同じ場合は設定不要）
$endpoint_uis = array(
	"http://lodcu.cs.chubu.ac.jp/SparqlEPCU/project.jsp?projectID=zoo",
	"http://lodcu.cs.chubu.ac.jp/SparqlEPCU/project.jsp?projectID=planetarium");


require_once('read_query.php');

$uri = $_SERVER["REQUEST_URI"];
$resource = $_REQUEST["res"];
$separator = empty($_REQUEST["sep"]) ? "/" : empty($_REQUEST["sep"]);
$prefix = (empty($_SERVER["HTTPS"]) ? "http://" : "https://") . $_SERVER["HTTP_HOST"];

$type = isset($_REQUEST["output"]) ? $_REQUEST["output"] : 'html';

$pos = strpos($uri, "/");
if ($pos === false){
	return;
}
$uri = substr($uri, $pos+1);

$pos = strpos($uri, $separator);
if ($pos === false){
	return;
}
$uri = substr($uri, 0, $pos);

if ($type == 'html'){
	include 'resource_html.php';
} else {
	include 'resource_json.php';
}

?>
