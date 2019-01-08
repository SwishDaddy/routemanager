<?php

include "includes.php";

//Make sure the user accessing this script is logged in
if (!logincheck()) {
	$arr["message"] = "Login Failed";
	echo json_encode($arr);
	die();
}

//Make sure the user accessing this script has the "fluids" role
$arrroles = rolecheck();
if (!in_array ("fluids" , $arrroles)) {
	$arr["message"] = "Role Check Failed";
	echo json_encode($arr);
	die();
}

$action = $_POST["action"];
if (strlen($action) < 1) {
	$arr["message"] = "Invalid Action";
	echo json_encode($arr);
	die;
}

if (!isset($_POST["obj"])) {
	$arr["message"] = "No 'obj' POST value was provided";
	echo json_encode($arr);
	die();
}

$post_obj = $_POST["obj"];

$arr["message"] = "success";


if ($action == "getusers") {

	$sql = "select id, firstname, lastname from tblusers where status = ? order by lastname, firstname";
	$stmt = $dbconn->prepare($sql);
	$stmt->execute(array('active'));
	while ($row = $stmt->fetch()) {
		$userid = $row["id"];
		$firstname = $row["firstname"];
		$lastname = $row["lastname"];
		$arr[$userid] = $firstname . " " . $lastname;
	};

	echo json_encode($arr);
	die();
};

if ($action == "getvehicles") {

	$sql = "select id, name from tblvehicles where status = ?";
	$stmt = $dbconn->prepare($sql);
	$stmt->execute(array('active'));
	while ($row = $stmt->fetch()) {
		$nameid = $row["id"];
		$name = $row["name"];
		$arr[$nameid] = $name;
	};

	echo json_encode($arr);
	die();
};

if ($action == "saveentry") {

	$entryid = "";
	if (isset($post_obj["entryid"])) {
		$entryid = $post_obj["entryid"];
	};

	$adatetime = "";
	if (isset($post_obj["adatetime"])) {
		$adatetime = $post_obj["adatetime"];
	};

	$ndatetime = strtotime($adatetime);

	$fuelerid = "";
	if (isset($post_obj["fuelerid"])) {
		$fuelerid = $post_obj["fuelerid"];
	};

	$vehicleid = "";
	if (isset($post_obj["vehicleid"])) {
		$vehicleid = $post_obj["vehicleid"];
	};

	$shift = "";
	if (isset($post_obj["shift"])) {
		$shift = $post_obj["shift"];
	};

	$mileage = "";
	if (isset($post_obj["mileage"])) {
		$mileage = $post_obj["mileage"];
	};

	$hours = "";
	if (isset($post_obj["hours"])) {
		$hours = $post_obj["hours"];
	};

	$cng = "";
	if (isset($post_obj["cng"])) {
		$cng = $post_obj["cng"];
	};

	$oil = "";
	if (isset($post_obj["oil"])) {
		$oil = $post_obj["oil"];
	};

	$coolant = "";
	if (isset($post_obj["coolant"])) {
		$coolant = $post_obj["coolant"];
	};

	$washerfluid = "";
	if (isset($post_obj["washerfluid"])) {
		$washerfluid = $post_obj["washerfluid"];
	};



	if (strlen($entryid) < 1) {
		$sql = "insert into tblfluids (adatetimecreated, ndatetimecreated, createdbyuserid) values (?, ?, ?)";
		$stmt = $dbconn->prepare($sql);
		$stmt->execute(array($aDateTimeGlobal, $nDateTimeGlobal, $_SESSION["loginid"]));
		$entryid = $dbconn->lastInsertId();
	};

	$sql = "update tblfluids set adatetime = ?, ndatetime = ?, fuelerid = ?, vehicleid = ?, shift = ?, mileage = ?, hours = ?, cng = ?, oil = ?, coolant = ?, washerfluid = ?, adatetimeupdated = ?, ndatetimeupdated = ?, updatedbyuserid = ? where id = ?";
	$stmt = $dbconn->prepare($sql);
	$stmt->execute(array($adatetime, $ndatetime, $fuelerid, $vehicleid, $shift, $mileage, $hours, $cng, $oil, $coolant, $washerfluid, $aDateTimeGlobal, $nDateTimeGlobal, $_SESSION["loginid"], $entryid));

	$arr["entryid"] = $entryid;

	echo json_encode($arr);
	die();

};

if ($action == "getentrydata") {

	$entryid = "";
	if (isset($post_obj["entryid"])) {
		$entryid = $post_obj["entryid"];
	};

	$sql = "select adatetime, vehicleid, fuelerid, shift, mileage, hours, cng, oil, coolant, washerfluid from tblfluids where id = ?";
	$stmt = $dbconn->prepare($sql);
	$stmt->execute(array($entryid));
	while ($row = $stmt->fetch()) {
		$arr["entryid"] = $entryid;
		$arr["adatetime"] = $row["adatetime"];
		$arr["vehicleid"] = $row["vehicleid"];
		$arr["fuelerid"] = $row["fuelerid"];
		$arr["shift"] = $row["shift"];
		$arr["mileage"] = $row["mileage"];
		$arr["hours"] = $row["hours"];
		$arr["cng"] = $row["cng"];
		$arr["oil"] = $row["oil"];
		$arr["coolant"] = $row["coolant"];
		$arr["washerfluid"] = $row["washerfluid"];
	};

	echo json_encode($arr);
	die();
};

if ($action == "getfluids") {

	$fromdate = "";
	if (isset($post_obj["fromdate"])) {
		$fromdate = $post_obj["fromdate"];
	};
	if (strlen($fromdate) < 1) {
		echo json_encode($arr);
		die();
	};

	$nfromdate = strtotime($fromdate);

	$todate = "";
	if (isset($post_obj["todate"])) {
		$todate = $post_obj["todate"];
	};
	if (strlen($todate) < 1) {
		echo json_encode($arr);
		die();
	};

	$ntodate = strtotime($todate);

	$sql = "select id, adatetime, ndatetime, fuelerid, vehicleid, shift, mileage, hours, cng, oil, coolant, washerfluid from tblfluids where ndatetime >= ? and ndatetime <= ? and active = ? order by ndatetime desc";
	$stmt = $dbconn->prepare($sql);
	$stmt->execute(array($nfromdate, $ntodate, 'true'));
	while ($row = $stmt->fetch()) {
		$id = $row["id"];
		$arr[$id]["adatetime"] = $row["adatetime"];
		$arr[$id]["shift"] = $row["shift"];
		$arr[$id]["mileage"] = $row["mileage"];
		$arr[$id]["hours"] = $row["hours"];
		$arr[$id]["cng"] = $row["cng"];
		$arr[$id]["oil"] = $row["oil"];
		$arr[$id]["coolant"] = $row["coolant"];
		$arr[$id]["washerfluid"] = $row["washerfluid"];

		$arr[$id]["fuelername"] = "";
		$arr[$id]["vehiclename"] = "";

		$arr[$id]["fuelerid"] = $row["fuelerid"];
		$sql1 = "select firstname, lastname from tblusers where id = ?";
		$stmt1 = $dbconn->prepare($sql1);
		$stmt1->execute(array($row["fuelerid"]));
		while ($row1 = $stmt1->fetch()) {
			$arr[$id]["fuelername"] = $row1["firstname"] . " " . $row1["lastname"];
		};

		$arr[$id]["vehicleid"] = $row["vehicleid"];
		$sql1 = "select name from tblvehicles where id = ?";
		$stmt1 = $dbconn->prepare($sql1);
		$stmt1->execute(array($row["vehicleid"]));
		while ($row1 = $stmt1->fetch()) {
			$arr[$id]["vehiclename"] = $row1["name"];
		};

	};


	echo json_encode($arr);
	die();
};

if ($action == "deleteentry") {

	$entryid = "";
	if (isset($post_obj["entryid"])) {
		$entryid = $post_obj["entryid"];
	};

	$sql = "update tblfluids set active = ? where id = ?";
	$stmt = $dbconn->prepare($sql);
	$stmt->execute(array('false', $entryid));

	echo json_encode($arr);
	die();
};


$arr["message"] = "No valid \"action\" value was POSTed.";
echo json_encode($arr);
die();


?>