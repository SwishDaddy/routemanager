<?php
/*
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */

include ("includes.php");

//Make sure the user accessing this script is logged in
if (!logincheck()) {
	$arr["message"] = "Login Failed";
	echo json_encode($arr);
	die();
};

error_reporting(E_ALL | E_STRICT);
require('uploadhandler.php');

$arr["message"] = "success";

$filetype = "";
if (isset($_GET["filetype"])) {
	$filetype = $_GET["filetype"];
};

$recordid = "";
if (isset($_GET["recordid"])) {
	$recordid = $_GET["recordid"];
};

$filepath = $pathfortempfiles . '/' . $recordid . '/' . $filetype;

if(!is_dir($pathfortempfiles)){
  mkdir ($pathfortempfiles);
};

if(!is_dir($pathfortempfiles . '/' . $recordid)){
  mkdir ($pathfortempfiles . '/' . $recordid);
};

if(!is_dir($pathfortempfiles . '/' . $recordid . '/' . $filetype)){
  mkdir ($pathfortempfiles . '/' . $recordid . '/' . $filetype);
};

$filepath = $pathfortempfiles . '/' . $recordid . '/' . $filetype;

$upload_handler =  new UploadHandler(array(
	'upload_dir' => $pathfortempfiles . '/' . $recordid . '/' . $filetype . '/',
	'accept_file_types' => '/\.(jpe?g|png|pdf|xls|xlsx|txt|csv|doc|docx|gif)$/i',
));

/*

// The UploadHandler isn't a "regular" object, so convert it into json then back into a "regular" object
$arr1 = json_encode($upload_handler);
$arr2 = json_decode($arr1);

$arr_ret = [];

foreach ($arr2->response->files as $key=>$val) {
	foreach ($val as $key1=>$val1)
	if ($key1 == "name") {
		$filename = $val1;

		$filepath = $pathfortempfiles . '/' . $loginid . '/' . $filename;

		$arr_ret[$filename] = $filepath;



		};

	};
};

echo json_encode($arr_ret);
die();
*/

?>