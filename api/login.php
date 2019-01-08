<?php

include "includes.php";

$arr["message"] = "success";

if (!isset($_POST["action"])) {
	$arr["message"] = "No 'action' POST value was provided";
	echo json_encode($arr);
	die();
};
$action = $_POST["action"];
if (strlen($action) < 1) {
	$arr["message"] = "The 'action' value cannot be blank";
	echo json_encode($arr);
	die();
};

if (!isset($_POST["obj"])) {
	$arr["message"] = "No 'obj' POST value was provided";
	echo json_encode($arr);
	die();
};

$post_obj = $_POST["obj"];

if ($action == "login") {

	unset($_SESSION["loginid"]);

	$email = "";
	if (isset($post_obj["email"])) {
		$email = $post_obj["email"];
	};
	if (strlen($email) < 1) {
		$arr["message"] = "Invalid Email Address";
		echo json_encode($arr);
		die;
	};

	$password = "";
	if (isset($post_obj["password"])) {
		$password = $post_obj["password"];
	};
	if (strlen($password) < 1) {
		$arr["message"] = "Invalid Password";
	};

	$pwd = md5($password . $email);

	$luid = "";

	$sql = "select id from tblusers where email = ? and password = ? and status = ?";
	$stmt = $dbconn->prepare($sql);
	$stmt->execute(array($email, $pwd, 'active'));
	while ($row = $stmt->fetch()) {
		$luid = $row["id"];
	};

	if (strlen($luid) > 0) {
		$_SESSION["loginid"] = $luid;
	}
	else
	{
		$arr["message"] = "Login Failed";
		echo json_encode($arr);
		die;
	}

	echo json_encode($arr);
	die;

};

if ($action == "logout") {
	//remove PHPSESSID from browser
	if ( isset( $_COOKIE[session_name()] ) )
	setcookie( session_name(), "", time()-3600, "/" );
	//clear session from globals
	$_SESSION = array();
	//clear session from disk
	session_destroy();

	echo json_encode($arr);
	die();
}

if ($action == "setpassword") {

	$userid = "";
	if (isset($_SESSION["loginid"])) {
		$userid = $_SESSION["loginid"];
	};
	if (strlen($userid) < 1) {
		$arr["message"] = "Invalid Userid";
		echo json_encode($arr);
		die();
	};

	$sql = "select email from tblusers where id = ?";
	$stmt = $dbconn->prepare($sql);
	$stmt->execute(array($userid));
	while ($row = $stmt->fetch()) {
		$email = $row["email"];
	};

	if (strlen($email) < 1) {
		$arr["message"] = "Invalid Email";
		echo json_encode($arr);
		die();
	};

	$password = "";
	if (isset($post_obj["password"])) {
		$password = $post_obj["password"];
	};
	if (strlen($password) < 1) {
		$arr["message"] = "Invalid Password";
		echo json_encode($arr);
		die();
	};

	$pwd = md5($password . $email);

	$sql = "update tblusers set password = ? where id = ?";
	$stmt = $dbconn->prepare($sql);
	$stmt->execute(array($pwd, $userid));

	echo json_encode($arr);
	die();

};

?>