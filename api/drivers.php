<?php

include "includes.php";

//Make sure the user accessing this script is logged in
if (!logincheck()) {
	$arr["message"] = "Login Failed";
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

if ($action == "getuserlist") {

	$status = "";
	if (isset($post_obj["status"])) {
		$status = $post_obj["status"];
	};
	if (strlen($status) < 1) {
		$arr["message"] = "The 'status' value cannot be blank";
		echo json_encode($arr);
		die();
	};

	$sql = "select id, firstname, lastname from tblusers where status = ?";
	$stmt = $dbconn->prepare($sql);
	$stmt->execute(array($status));
	while ($row = $stmt->fetch()) {
		$userid = $row["id"];
		$firstname = $row["firstname"];
		$lastname = $row["lastname"];
		$arr[$userid] = $firstname . " " . $lastname;

	};

	echo json_encode($arr);
	die;

}

if ($action == "getvehicletypes") {

	$sql = "select id, type from tblvehicletypes where status = 'active'";
	$stmt = $dbconn->prepare($sql);
	$stmt->execute();
	while ($row = $stmt->fetch()) {
		$typeid = $row["id"];
		$type = $row["type"];
		$arr[$typeid] = $type;
	};

	echo json_encode($arr);
	die();
};

if ($action == "getvehicles") {

	/*
	$vehicletype = "";
	if (isset($post_obj["vehicletype"])) {
		$vehicletype = $post_obj["vehicletype"];
	};


	if (strlen($vehicletype) < 1) {
		$arr["message"] = "Invalid Vehicle Type value";
		echo json_encode($arr);
		die();
	};

	*/

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

if ($action == "getroutes") {

	/*
	$routetype = "";
	if (isset($post_obj["routetype"])) {
		$routetype = $post_obj["routetype"];
	};
	*/

	//$sql = "select id, routename from tblroutes where status = ? and routetype = ?";
	$sql = "select id, routename, listorder from tblroutes where status = ? order by listorder";
	$stmt = $dbconn->prepare($sql);
	//$stmt->execute(array('active', $routetype));
	$stmt->execute(array('active'));
	while ($row = $stmt->fetch()) {
		$routeid = $row["id"];
		$routename = $row["routename"];
		$listorder = $row["listorder"];
		$arr[$listorder]["routeid"] = $routeid;
		$arr[$listorder]["routename"] = $routename;
	};

	echo json_encode($arr);
	die();
};

if ($action == "getmileagevalue") {

	$routeid = "";
	if (isset($post_obj["routeid"])) {
		$routeid = $post_obj["routeid"];
	};

	if (strlen($routeid) < 1) {
		$arr["message"] = "Invalid Route Name value";
		echo json_encode($arr);
		die();
	};

	$mileagetype = "";
	if (isset($post_obj["mileagetype"])) {
		$mileagetype = $post_obj["mileagetype"];
	};

	if (strlen($mileagetype) < 1) {
		$arr["message"] = "Invalid Mileage Type value";
		echo json_encode($arr);
		die();
	};

	$sql = "select mileagevalue from tblmileages where routeid = ? and mileagetype = ?";
	$stmt = $dbconn->prepare($sql);
	$stmt->execute(array($routeid, $mileagetype));
	while ($row = $stmt->fetch()) {
		$arr["mileagevalue"] = $row["mileagevalue"];
	};

	echo json_encode($arr);
	die();
};

if ($action == "getroutes") {

	/*
	$routetype = "";
	if (isset($post_obj["routetype"])) {
		$routetype = $post_obj["routetype"];
	};
	*/

	//$sql = "select id, routename from tblroutes where status = ? and routetype = ?";
	$sql = "select id, routename from tblroutes where status = ?";
	$stmt = $dbconn->prepare($sql);
	//$stmt->execute(array('active', $routetype));
	$stmt->execute(array('active'));
	while ($row = $stmt->fetch()) {
		$routeid = $row["id"];
		$routename = $row["routename"];
		$arr[$routeid] = $routename;
	};

	echo json_encode($arr);
	die();
};

if ($action == "savedriversheet") {

	$post_obj = json_decode($post_obj, true);

	$date = "";
	if (isset($post_obj["date"])) {
		$date = $post_obj["date"];
	};

	unset($post_obj["date"]);

	if (strlen($date) < 1) {
		$arr["message"] = "Invalid Date value";
		echo json_encode($arr);
		die();
	};

	$ndate = strtotime($date);

	$vehicleid = "";
	if (isset($post_obj["vehicleid"])) {
		$vehicleid = $post_obj["vehicleid"];
	};

	if (strlen($vehicleid) < 1) {
		$arr["message"] = "Invalid Vehicle Name value";
		echo json_encode($arr);
		die();
	};

	unset($post_obj["vehicleid"]);

	$driversheetid = "";

	$sql = "select id from tbldriversheets where adate = ? and vehicleid = ? and active = ?";
	$stmt = $dbconn->prepare($sql);
	$stmt->execute(array($date, $vehicleid, "true"));
	while ($row = $stmt->fetch()) {
		$driversheetid = $row["id"];
	};

	if (strlen($driversheetid) < 1) {
		$sql = "insert into tbldriversheets (createdbyuserid, adatetimecreated, ndatetimecreated) values (?, ?, ?) ";
		$stmt = $dbconn->prepare($sql);
		$stmt->execute(array($loginid, $aDateTimeGlobal, $nDateTimeGlobal));
		$driversheetid = $dbconn->lastInsertId();
	};

	$sql = "update tbldriversheets set adate = ?, ndate = ?, vehicleid = ?, lastupdatedbyuserid = ?, ndatetimeupdated = ?, adatetimeupdated = ? where id = ?";
	$stmt = $dbconn->prepare($sql);
	$stmt->execute(array($date, $ndate, $vehicleid, $loginid, $aDateTimeGlobal, $nDateTimeGlobal, $driversheetid));

	$gridid = "";

	$arr1 = [];

	$sql = "delete from tbldriversheetdata where driversheetid = ?";
	$stmt = $dbconn->prepare($sql);
	$stmt->execute(array($driversheetid));

	//error_log(json_encode($post_obj));

	foreach($post_obj as $key=>$val) {

		$gridid = $key;

		foreach($val as $key2=>$val2) {

			$routeid = $key2;

			$lot_to_terminal_mileage = "";
			$terminal_to_lot_mileage = "";

			$sql1 = "select * from tblmileages where routeid = ?";
			$stmt1 = $dbconn->prepare($sql1);
			$stmt1->execute(array($routeid));
			while ($row1 = $stmt1->fetch()) {

				if ($row1["mileagetype"] == "lot_to_terminal") {
					$lot_to_terminal_mileage = $row1["mileagevalue"];
				};

				if ($row1["mileagetype"] == "terminal_to_lot") {
					$terminal_to_lot_mileage = $row1["mileagevalue"];
				};
			};

			foreach($val[$routeid] as $key1=>$val1) {

				$gridrowid = $key1;

				$passengercount_lot_turnstile = $val1["passengercount_lot_turnstile"];
				$passengercount_terminal_concourse = $val1["passengercount_terminal_concourse"];
				$tripcount_lot_turnstile = $val1["tripcount_lot_turnstile"];
				$tripcount_terminal_concourse = $val1["tripcount_terminal_concourse"];

				$out_of_service_time = $val1["out_of_service_time"];
				$out_of_service_time = str_replace(":", "", $out_of_service_time);
				$back_in_service_time = $val1["back_in_service_time"];
				$back_in_service_time = str_replace(":", "", $back_in_service_time);
				$non_operational_reason = $val1["non_operational_reason"];
				$billable = $val1["billable"];

				$hour = $val1["hour"];

				$sql = "insert into tbldriversheetdata (driversheetid, gridrowid, passengercount_lot_turnstile, passengercount_terminal_concourse, tripcount_lot_turnstile, tripcount_terminal_concourse, out_of_service_time, back_in_service_time, non_operational_reason, hour, gridid, routeid, lot_to_terminal_mileage, terminal_to_lot_mileage, billable) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ";
				$stmt = $dbconn->prepare($sql);
				$stmt->execute(array($driversheetid, $gridrowid, $passengercount_lot_turnstile, $passengercount_terminal_concourse, $tripcount_lot_turnstile, $tripcount_terminal_concourse, $out_of_service_time, $back_in_service_time, $non_operational_reason, $hour, $gridid, $routeid, $lot_to_terminal_mileage, $terminal_to_lot_mileage, $billable));

			};

		};

	};

	echo json_encode($arr);
	die();

};

if ($action == "getdriversheet") {

	$date = "";
	if (isset($post_obj["date"])) {
		$date = $post_obj["date"];
	};

	if (strlen($date) < 1) {
		$arr["message"] = "Invalid Date value";
		echo json_encode($arr);
		die();
	};

	$ndate = strtotime($date);

	$vehicleid = "";
	if (isset($post_obj["vehicleid"])) {
		$vehicleid = $post_obj["vehicleid"];
	};

	if (strlen($vehicleid) < 1) {
		$arr["message"] = "Invalid Vehicle Name value";
		echo json_encode($arr);
		die();
	};

	$arr_d = [];

	$sql = "select id from tbldriversheets where adate = ? and vehicleid = ? and active = ?";
	$stmt = $dbconn->prepare($sql);
	$stmt->execute(array($date, $vehicleid, "true"));
	while ($row = $stmt->fetch()) {
		$driversheetid = $row["id"];

		$sql1 = "select * from tbldriversheetdata where driversheetid = ?";
		$stmt1 = $dbconn->prepare($sql1);
		$stmt1->execute(array($driversheetid));
		while ($arr_data = $stmt1->fetchAll(PDO::FETCH_OBJ)) {

			$arr_d = $arr_data;

		};

	};

	$arr_ret = [];

	foreach ($arr_d as $key=>$val) {

		//error_log(json_encode($val));
		$gridid = $val->gridid;
		unset ($val->gridid);

		//$routeid = $val->routeid;
		//unset ($val->routeid);

		if (!isset($arr_ret[$gridid])) {
			$arr_ret[$gridid] = array();
		};
		array_push($arr_ret[$gridid], $val);

	};

	//error_log(json_encode($arr_ret));

	$arr["grid_data"] = $arr_ret;

	echo json_encode($arr);
	die();

};


$arr["message"] = "No valid \"action\" value was POSTed.";
echo json_encode($arr);
die();



?>