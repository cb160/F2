<?php
	$apps = $_REQUEST["params"];
	$apps = get_magic_quotes_gpc() ? stripslashes($apps) : $apps;
	$app = json_decode($apps);  
	$app = $app[0]; // this App doesn't support batchedRequests

	$serverPath = 
		((!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] != "off") ? "https://" : "http://") .
		$_SERVER["SERVER_NAME"] .
		str_replace("index.php", "", $_SERVER["SCRIPT_NAME"]);

	// create the AppManifest object
	$a = array(
		"scripts" => array($serverPath . "app.js"),
		"apps" => array(array("html" => renderAppHtml($app)))
	);

	function renderAppHtml($app) {
		$isSecure = array_key_exists('isSecure', $app) && $app->isSecure
			? 'Secure'
			: '';

		return <<<HTML
<div class="well">
	<div class="f2-app-view" data-f2-view="home">
		<p>PHP $isSecure Hello World!</p>
		<ul>
			<li><a href="#" class="testAlert">Test Alert</a></li>
			<li><a href="#" class="testConfirm">Test Confirm</a></li>
		</ul>
	</div>
</div>
HTML;
	}

	// output the jsonp
	header("Content-type: application/javascript");
	echo "F2_jsonpCallback_" . $app->appId . "(" . json_encode($a, JSON_HEX_TAG) . ")";
?>