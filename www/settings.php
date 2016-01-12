<?php


// エンドポイントAPIのURL(設定するAPIが一つだけでも配列とすること)
$endpoint_apis = array(
	"http://lodcu.cs.chubu.ac.jp/SparqlEPCU/RDFServer.jsp?reqtype=api&project=zoo",
	"http://lodcu.cs.chubu.ac.jp/SparqlEPCU/RDFServer.jsp?reqtype=api&project=planetarium");

// エンドポイントUIのURL（APIのURLと同じ場合は設定不要（定義全体をコメントアウトする））
$endpoint_uis = array(
	"http://lodcu.cs.chubu.ac.jp/SparqlEPCU/project.jsp?projectID=zoo",
	"http://lodcu.cs.chubu.ac.jp/SparqlEPCU/project.jsp?projectID=planetarium");


?>