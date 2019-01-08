<?php

include "includes.php";

//Make sure the user accessing this script is logged in
if (!logincheck()) {
	$arr["message"] = "Login Failed";
	echo json_encode($arr);
	die();
};

$loginid = $_SESSION["loginid"];

$arr["message"] = "success";

$filename = "";
if (isset($_GET["file"])) {
	$filename = $_GET["file"];
};

$type = "";
if (isset($_GET["type"])) {
	$type = $_GET["type"];
};

$filetype = "";
if (isset($_GET["filetype"])) {
	$filetype = $_GET["filetype"];
};

$recordid = "";
if (isset($_GET["recordid"])) {
	$recordid = $_GET["recordid"];
};

$default = "";
if (isset($_GET["d"])) {
	$default = $_GET["d"];
};

$file = "";

if ($type == "templinkedfile") {
	$file = $pathfortempfiles . "/" . $recordid . "/" . $filetype . "/" .$filename;
};

if ($type == "linkedfile") {
	$file = $pathforfiles . "/" . $recordid . "/" . $filetype . "/" .$filename;
};

if ($type == "templinkedfilethumbnail") {
	$file = $pathfortempfiles . "/" . $recordid . "/" . $filetype . "/thumbnail/" . $filename;
};

if ($type == "tempthumbnail") {
	$file = $pathfortempfiles . "/" . $loginid . "/" . $filename;
};

if ($type == "thumbnail") {
	$file = $pathforfiles . "/" . $recordid . "/" . $filename;
};

//error_log($file);

if (strlen($file) > 0) {
	if (!file_exists($file)) {
		$file = "../img/" . $default;
	};

	if (!file_exists($file)) {
		$file = "../img/notfound.png";
	};

	$mime = _mime_content_type($file);
	header('Content-Description: File Transfer');
	header('Content-Type: ' . $mime);
	header('Content-Disposition: inline; filename='.basename($file));
	header('Content-Transfer-Encoding: binary');
	header('Expires: 0');
	header('Cache-Control: must-revalidate');
	header('Pragma: public');
	header('Content-Length: ' . filesize($file));
	ob_clean();
	flush();
	readfile($file);
	exit;


}
else {

	echo "Not Found";
	die();

};

function _mime_content_type($filename) {
    $result = new finfo();

    if (is_resource($result) === true) {
        return $result->file($filename, FILEINFO_MIME_TYPE);
    };

    return false;
};


	?>