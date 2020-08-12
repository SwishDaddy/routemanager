<?php

$ver = "1.47";

$sourceurl = "https://routemanager.swishersolutions.com/";

$location_name = "DIA";

$location_name_long = "Demo Location";

$pathforfiles =  "../../routemanagerdocs/demo";
$pathfortempfiles =  "../../routemanagerdocs/demo/temp";

date_default_timezone_set('America/Denver');

session_start();

$aDateTimeGlobal = date("F j, Y, g:i a");
$nDateTimeGlobal = time();

// Using PDO for DB Connections
$dbconn = new PDO('mysql:host=<hostname>;dbname=<dbname>', '<username>', '<password>', array(PDO::ATTR_PERSISTENT => true
));
$dbconn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$loginid = "";
if (isset($_SESSION["loginid"])) {
	$loginid = $_SESSION["loginid"];
};

function uniqueid($prefix) {
	$ret = $prefix . "_" . time() .  rand(100000000, 999999999);
	$ret = $ret .  rand(100000000, 999999999);
	return $ret;
};

function logincheck() {

	if (!isset($_SESSION["loginid"])) {
		return false;
		die();
	};

	$loginid = $_SESSION["loginid"];

	$logincheck = false;

	if (strlen($loginid) > 0) {
		global $dbconn;
		$sql = "select count(*) as count from tblusers where id = ? and status = 'active'";
		$stmt = $dbconn->prepare($sql);
		$stmt->execute(array($loginid));
		while ($row = $stmt->fetch()) {
			$count = $row["count"];
			if ($count > 0) {
				$logincheck = true;
			};
		};
	};

	return $logincheck;
};

function rolecheck() {
	$loginid = "";
	if (isset($_SESSION["loginid"])) {
		$loginid = $_SESSION["loginid"];
	};

	$arrroles = [];

	if (strlen($loginid) > 0) {
		global $dbconn;
		$sql = "select role from tblroles where userid = ?";
		$stmt = $dbconn->prepare($sql);
		$stmt->execute(array($loginid));
		while ($row = $stmt->fetch()) {
			$role = $row["role"];
			array_push($arrroles, $role);
		};
	};

	return $arrroles;
};

function logininfo() {

	$loginid = "";
	if (isset($_SESSION["loginid"])) {
		$loginid = $_SESSION["loginid"];
	};

	$arrinfo = [];

	if (strlen($loginid) > 0) {
		global $dbconn;
		$sql = "select firstname, lastname from tblusers where id = ?";
		$stmt = $dbconn->prepare($sql);
		$stmt->execute(array($loginid));
		while ($row = $stmt->fetch()) {
			$firstname = $row["firstname"];
			$lastname = $row["lastname"];
			$arrinfo["firstname"] = $firstname;
			$arrinfo["lastname"] = $lastname;
		};
	};

	return $arrinfo;

};

function globalnav() {

	globalmodals();

	$loginid = "";
	if (isset($_SESSION["loginid"])) {
		$loginid = $_SESSION["loginid"];
	};

	if (strlen($loginid) < 1) {

		echo '<nav class="navbar navbar-fixed-top nav" role="navigation">
			<div class="container">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed btn btn-primary" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					</button>
					
					<a href="./"><img src="img/logo.png" /></a>
					
				</div>
				<div id="navbar" class="navbar-collapse collapse">
					<form class="navbar-form navbar-right" role="form">
						<div class="form-group">
							<input id="abm_rm_login_email" type="text" placeholder="Email" class="form-control logininput" autofocus>
						</div>
						<div class="form-group">
							<input id="abm_rm_login_password" type="password" placeholder="Password" class="form-control logininput">
						</div>
						<a id="abm_rm_login_btnsignin" class="btn btn-primary" role="button">Sign in</a>
					</form>
				</div><!--/.navbar-collapse -->
			</div>
		</nav>';
	}
	else
	{

		//<h2><a href="./"><img src="img/logo.png" /></a> Route<span style="visibility:hidden;">.</span>Manager</h2>

		echo '<nav class="navbar navbar-fixed-top nav" role="navigation">
			<div class="container">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed btn btn-primary" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					</button>

					<a href="./"><img src="img/logo.png" /></a>

				</div>
				<div id="navbar" class="navbar-collapse collapse">
					<form class="navbar-form navbar-right" role="form">
						<div class="form-group">';

						$arrinfo = logininfo();

						echo '<span id="loggedin_userfullname" class="rolloverhand" style="margin-right:10px;">' . htmlspecialchars($arrinfo["firstname"]) . " " . htmlspecialchars($arrinfo["lastname"]) . '</span>';

						echo '</div>
						<a id="abm_rm_login_btnlogout" class="btn btn-primary" role="button">Log Out</a>


							<a href="./" class="btn btn-default btnhome" style="margin-left:10px;">Dashboard<span style="padding-left:5px;" class="glyphicon glyphicon-home btnhome"></span></a>

					</form>
				</div><!--/.navbar-collapse -->
			</div>
		</nav>';
	};

};

function globalmodals() {

	echo '<div class="modal fade" id="error_div" style="display:none;z-index:99999999;" tabindex="-1" role="dialog" aria-labelledby="error_div" aria-hidden="true" data-backdrop="static">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header" style="text-align:right;">
			<button id="error_btnclose" type="button" class="btn btn-default" style="font-size:18px;" data-dismiss="modal" aria-hidden="true">OK</button>
				<div class="modal-title centeralign">
					<h4 style="color:red;">Oops! Looks like something went wrong...</h4>
				</div>
			</div>
			<div id="error_message" class="modal-body bigfont bold centeralign">

			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="success_div" style="display:none;" tabindex="-1" role="dialog" aria-labelledby="success_div" aria-hidden="true" data-backdrop="static">
	<div class="modal-dialog">
		<div class="modal-content">
			<div style="float:left;">
				<div id="success_message" class="modal-body bigfont">

				</div>
			</div>
			<div style="float:right;text-align:right;">
				<button type="button" id="success_btnclose" style="font-size:18px;margin: 10px 10px;" class="btn btn-default" data-dismiss="modal" aria-hidden="true">OK</button>
			</div>
			<div style="clear:both;"></div>
		</div>
	</div>
</div>';

};

function headermarkup($title) {

	global $ver;
	global $sourceurl;

	echo '<!doctype html>
	<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
	<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
	<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
	<!--[if gt IE 8]><!--> <html class="no-js" lang=""> <!--<![endif]-->
	<head>
		<meta charset="utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<title>' . $title . '</title>
		<meta name="description" content="Route Manager" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<link rel="icon" type="image/png" href="' . $sourceurl . 'img/favicon.png?ver=' . $ver . '" />
		<link rel="stylesheet" href="' . $sourceurl . 'css/bootstrap.css?ver=' . $ver . '" />
		<link rel="stylesheet" href="' . $sourceurl . 'css/bootstrap-theme.min.css?ver=' . $ver . '" />
		<link rel="stylesheet" href="' . $sourceurl . 'js/vendor/jquery.datetimepicker.min.css?ver=' . $ver . '" />
		<link rel="stylesheet" href="' . $sourceurl . 'js/vendor/tablesorter/tablesorter.css?ver=' . $ver . '" />
		<link rel="stylesheet" href="' . $sourceurl . 'css/excel-table.css?ver=' . $ver . '" />
		<link rel="stylesheet" href="' . $sourceurl . 'css/tabular-input.css?ver=' . $ver . '" />
		<link rel="stylesheet" href="' . $sourceurl . 'css/jquery.fileupload.css?ver=' . $ver . '" />
		<link rel="stylesheet" href="' . $sourceurl . 'css/main.css?ver=' . $ver . '" />

		<style>
			body {
				padding-top: 50px;
				padding-bottom: 20px;
			}
		</style>

		<!--<script src="' . $sourceurl . 'js/vendor/modernizr-2.8.3-respond-1.4.2.min.js?ver=' . $ver . '"></script>-->
	</head>';
};

function jsscripts($scriptname) {

	global $ver;
	global $sourceurl;

	echo '<script type="text/javascript" src="' . $sourceurl . 'js/vendor/jquery-3.3.1.min.js?ver=' . $ver . '"></script>
<script type="text/javascript" src="' . $sourceurl . 'js/vendor/bootstrap.min.js?ver=' . $ver . '"></script>
<script type="text/javascript" src="' . $sourceurl . 'js/bootstrap-confirmation.js?ver=' . $ver . '"></script>
<script type="text/javascript" src="' . $sourceurl . 'js/vendor/jquery.datetimepicker.full.min.js?ver=' . $ver . '"></script>
<script type="text/javascript" src="' . $sourceurl . 'js/vendor/tablesorter/jquery.tablesorter.js?ver=' . $ver . '"></script>
<script type="text/javascript" src="' . $sourceurl . 'js/vendor/tablesorter/jquery.tablesorter.widgets.js?ver=' . $ver . '"></script>
<script type="text/javascript" src="' . $sourceurl . 'js/vendor/jquery.mask.js?ver=' . $ver . '"></script>
<script type="text/javascript" src="' . $sourceurl . 'js/vendor/jquery-tabbable.js?ver=' . $ver . '"></script>
<script type="text/javascript" src="' . $sourceurl . 'js/vendor/excel-table.js?ver=' . $ver . '"></script>
<script type="text/javascript" src="' . $sourceurl . 'js/vendor/tabular-input.min.js?ver=' . $ver . '"></script>
<script type="text/javascript" src="' . $sourceurl . 'js/vendor/jquery-ui.min.js?ver=' . $ver . '"></script>
<script type="text/javascript" src="' . $sourceurl . 'js/contenteditable.js?ver=' . $ver . '"></script>
<script type="text/javascript" src="' . $sourceurl . 'js/vendor/chartjs.js?ver=' . $ver . '"></script>
<script type="text/javascript" src="' . $sourceurl . 'js/jquery.fileupload.js?ver=' . $ver . '"></script>
<script type="text/javascript" src="' . $sourceurl . 'js/jquery.iframe-transport.js?ver=' . $ver . '"></script>
<script type="text/javascript" src="' . $sourceurl . 'js/main.js?ver=' . $ver . '"></script>
';

	if(strlen($scriptname) > 0) {
		echo '<script type="text/javascript" src="' . $sourceurl . 'js/' . $scriptname . '.js?ver=' . $ver . '"></script>';
	}
};

function footer() {

	return '<div class="footer navbar-fixed-bottom">
		<footer>
			<div class="row" style="padding:0 10px 0 10px;background-color:white;">
				<div class="col-xs-6">&copy; Swisher Solutions 2018</div>
				<div class="col-xs-6" style="text-align:right;">Created by Swisher Solutions</div>
			</div>
		</footer>
	</div>';
};


?>