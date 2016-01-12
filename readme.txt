○LOD Reference Resolverとは

　LODをLODたらしめる要素の一つに、リソースにユニークなURIが付与され、
そのURIにアクセスすることでリソースの情報を取得できることが挙げられます。
　しかし、世に出回っているLODは案外参照解決できないものも多いため、
リソースの付随データを取得することができず、Endpointを連鎖的に辿って
関連データを探索することも難しくなっています。

　LOD Reference Resolverがあれば、
SparqlEPCUなどのフリーで利用できるSPARQL Endpointと、
PHPが動作するレンタルWebサーバを利用することで、
簡単に参照解決可能なリソースを持つLODの提供環境を構築することができます。



○LOD Reference Resolver設置手順
　LOD Reference Resolverの設置手順は以下の通りです。
SPARQL Endpointにて、リソースが公開されている必要があります。


1. Endpoint設定
　settings.phpの、エンドポイントAPIのURL、および、エンドポイントUIのURLの
設定を行います。



2. PHPファイル設置
　以下のPHPを、Webサーバにて公開します。
（格納するサーバのドメインは、LODリソースのドメインと
　一致している必要があります）

・read_query.php
・resource.php
・resource_json.php
・resource_php.php
・settings.php


3. サーバのURL書き換え設定
　サーバのURL書き換え設定を行い、リソースURLへのアクセスが、
本サービスへのURLパラメータ付きアクセスとなるよう設定します。

リソースURI
　http://example.com/xxxx/リソース名
へのアクセスが、
　(手順1にて設置したresource.phpファイルのURL)?res=xxxx/リソース名
に書き換わるように設定を行います。


.htaccess設定例

#Apacheサーバにて、
#http://www.example.com/instance/リソース名
#http://www.example.com/resource/リソース名
#http://www.example.com/class/リソース名
#を、それぞれ
#http://www.example.com/resource.php?res=instance/リソース名
#http://www.example.com/resource.php?res=resource/リソース名
#http://www.example.com/resource.php?res=class/リソース名
#に書き換える設定
#（さくらインターネットのレンタルサーバにて動作確認）

#-----------------------------
RewriteEngine on
RewriteCond %{HTTP_HOST} ^(www\.)?example\.com$ [NC]
RewriteRule instance/(.*)$ /resource.php?res=$1 [QSA,L]
RewriteRule resource/(.*)$ /resource.php?res=$1 [QSA,L]
RewriteRule class/(.*)$ /resource.php?res=$1 [QSA,L]
#-----------------------------



○LOD Reference Resolver利用方法
　設置が成功すれば、以下の手順にて利用することができます。

1. WebブラウザからHTML表示する場合
　Webブラウザから、リソースのURLにアクセスします。
例：
Webブラウザから以下のURLにアクセス
http://www.museums-info.net/class/トラ


2. JavaScriptからajaxにてJSONを取得する場合
　JavaScriptから、ajaxにてリソースのURLにアクセスします。
その際、URLパラメータとして"output=json"を追加します。

例：
　JavaScriptから以下のアクセス

// 「%E3%83%88%E3%83%A9」は「トラ」のURLエンコード結果
$.post("http://www.museums-info.net/class/%E3%83%88%E3%83%A9",
	"output=json", function(response){
		// responseに結果が格納される
		// POSTだけでなくGETでも取得可能
	}, "JSON");
});


以上
