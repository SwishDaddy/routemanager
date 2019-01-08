<?php

include "includes.php";

//Make sure the user accessing this script is logged in
if (!logincheck()) {
	$arr["message"] = "Login Failed";
	echo json_encode($arr);
	die();
}

//Make sure the user accessing this script has the "admin" role
$arrroles = rolecheck();
if (!in_array ("admin" , $arrroles)) {
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

if ($action == "getuserinfo") {

	$arr["firstname"] = "";
	$arr["lastname"] = "";
	$arr["email"] = "";
	$arr["address"] = "";
	$arr["city"] = "";
	$arr["state"] = "";
	$arr["zip"] = "";
	$arr["phone"] = "";
	$arr["driverlicensenumber"] = "";
	$arr["driverlicenseexpiration"] = "";
	$arr["roles"] = array();

	$userid = "";
	if (isset($post_obj["userid"])) {
		$userid = $post_obj["userid"];
	};
	if (strlen($userid) < 1) {
		echo json_encode($arr);
		die();
	};

	$sql = "select firstname, lastname, email, address, city, state, zip, phone, driverlicensenumber, driverlicenseexpiration, position, hireddate, senioritydate, birthdate, dotexpirationdate, badgenumber, badgeexpirationdate, badgecolor from tblusers where id = ?";
	$stmt = $dbconn->prepare($sql);
	$stmt->execute(array($userid));
	while ($row = $stmt->fetch()) {
		$arr["firstname"] = $row["firstname"];
		$arr["lastname"] = $row["lastname"];
		$arr["email"] = $row["email"];
		$arr["address"] = $row["address"];
		$arr["city"] = $row["city"];
		$arr["state"] = $row["state"];
		$arr["zip"] = $row["zip"];
		$arr["phone"] = $row["phone"];
		$arr["driverlicensenumber"] = $row["driverlicensenumber"];
		$arr["driverlicenseexpiration"] = $row["driverlicenseexpiration"];
		$arr["position"] = $row["position"];
		$arr["hireddate"] = $row["hireddate"];
		$arr["senioritydate"] = $row["senioritydate"];
		$arr["birthdate"] = $row["birthdate"];
		$arr["dotexpirationdate"] = $row["dotexpirationdate"];
		$arr["badgenumber"] = $row["badgenumber"];
		$arr["badgeexpirationdate"] = $row["badgeexpirationdate"];
		$arr["badgecolor"] = $row["badgecolor"];
	};

	$sql = "select role from tblroles where userid = ?";
	$stmt = $dbconn->prepare($sql);
	$stmt->execute(array($userid));
	while ($row = $stmt->fetch()) {
		$role = $row["role"];
		array_push($arr["roles"],  $role);
	};

	echo json_encode($arr);
	die;
}

if ($action == "setpassword") {

	$userid = "";
	if (isset($post_obj["userid"])) {
		$userid = $post_obj["userid"];
	};
	if (strlen($userid) < 1) {
		$arr["message"] = "An error has occured. The password was not updated.";
		echo json_encode($arr);
		die();
	};

	$password = "";
	if (isset($post_obj["password"])) {
		$password = $post_obj["password"];
	};
	if (strlen($password) < 1) {
		$arr["message"] = "An error has occured. The password was not updated.";
		echo json_encode($arr);
		die();
	};

	$sql = "select email from tblusers where id = ?";
	$stmt = $dbconn->prepare($sql);
	$stmt->execute(array($userid));
	while ($row = $stmt->fetch()) {
		$email = $row["email"];
	};

	$pwd = md5($password . $email);
	$sql = "update tblusers set password = ? where id = ?";
	$stmt = $dbconn->prepare($sql);
	$stmt->execute(array($pwd, $userid));

	echo json_encode($arr);
	die;
}

if ($action == "saveuserinfo") {

	$userid = "";
	if (isset($post_obj["userid"])) {
		$userid = $post_obj["userid"];
	};

	$firstname = "";
	if (isset($post_obj["firstname"])) {
		$firstname = $post_obj["firstname"];
	};

	$lastname = "";
	if (isset($post_obj["lastname"])) {
		$lastname = $post_obj["lastname"];
	};

	$email = "";
	if (isset($post_obj["email"])) {
		$email = $post_obj["email"];
	};

	$isdriver = false;
	if (isset($post_obj["isdriver"])) {
		$isdriver = $post_obj["isdriver"];
	};

	$ismanager = false;
	if (isset($post_obj["ismanager"])) {
		$ismanager = $post_obj["ismanager"];
	};

	$isadmin = false;
	if (isset($post_obj["isadmin"])) {
		$isadmin = $post_obj["isadmin"];
	};

	$iscomments = false;
	if (isset($post_obj["iscomments"])) {
		$iscomments = $post_obj["iscomments"];
	};

	$isfluids = false;
	if (isset($post_obj["isfluids"])) {
		$isfluids = $post_obj["isfluids"];
	};

	$isaccidents = false;
	if (isset($post_obj["isaccidents"])) {
		$isaccidents = $post_obj["isaccidents"];
	};

	$address = "";
	if (isset($post_obj["address"])) {
		$address = $post_obj["address"];
	};

	$city = "";
	if (isset($post_obj["city"])) {
		$city = $post_obj["city"];
	};

	$state = "";
	if (isset($post_obj["state"])) {
		$state = $post_obj["state"];
	};

	$zip = "";
	if (isset($post_obj["zip"])) {
		$zip = $post_obj["zip"];
	};

	$phone = "";
	if (isset($post_obj["phone"])) {
		$phone = $post_obj["phone"];
	};

	$driverlicensenumber = "";
	if (isset($post_obj["driverlicensenumber"])) {
		$driverlicensenumber = $post_obj["driverlicensenumber"];
	};

	$driverlicenseexpiration = "";
	if (isset($post_obj["driverlicenseexpiration"])) {
		$driverlicenseexpiration = $post_obj["driverlicenseexpiration"];
	};
	$ndriverlicenseexpiration = "";
	if (strtotime($driverlicenseexpiration)) {
		$ndriverlicenseexpiration = strtotime($driverlicenseexpiration);
	};

	$position = "";
	if (isset($post_obj["position"])) {
		$position = $post_obj["position"];
	};

	$dotexpiration = "";
	if (isset($post_obj["dotexpiration"])) {
		$dotexpiration = $post_obj["dotexpiration"];
	};
	$ndotexpiration = "";
	if (strtotime($dotexpiration)) {
		$ndotexpiration = strtotime($dotexpiration);
	};

	$hireddate = "";
	if (isset($post_obj["hireddate"])) {
		$hireddate = $post_obj["hireddate"];
	};
	$nhireddate = "";
	if (strtotime($hireddate)) {
		$nhireddate = strtotime($hireddate);
	};

	$senioritydate = "";
	if (isset($post_obj["senioritydate"])) {
		$senioritydate = $post_obj["senioritydate"];
	};
	$nsenioritydate = "";
	if (strtotime($senioritydate)) {
		$nsenioritydate = strtotime($senioritydate);
	};

	$birthdate = "";
	if (isset($post_obj["birthdate"])) {
		$birthdate = $post_obj["birthdate"];
	};
	$nbirthdate = "";
	if (strtotime($birthdate)) {
		$nbirthdate = strtotime($birthdate);
	};

	$badgenumber = "";
	if (isset($post_obj["badgenumber"])) {
		$badgenumber = $post_obj["badgenumber"];
	};

	$badgeexpirationdate = "";
	if (isset($post_obj["badgeexpirationdate"])) {
		$badgeexpirationdate = $post_obj["badgeexpirationdate"];
	};
	$nbadgeexpirationdate = "";
	if (strtotime($badgeexpirationdate)) {
		$nbadgeexpirationdate = strtotime($badgeexpirationdate);
	};

	$badgecolor = "";
	if (isset($post_obj["badgecolor"])) {
		$badgecolor = $post_obj["badgecolor"];
	};

	if (strlen($userid) < 1) {
		$userid = uniqueid("userid");
		$sql = "insert into tblusers (id, email, firstname, lastname, status, datetimecreated, ndatetimecreated, createdbyuserid, lastupdatedbyuserid, datetimelastupdated, ndatetimelastupdated, address, city, state, zip, phone, driverlicensenumber, driverlicenseexpiration, ndriverlicenseexpiration, position, dotexpirationdate, ndotexpirationdate, hireddate, nhireddate, senioritydate, nsenioritydate, birthdate, nbirthdate, badgenumber, badgeexpirationdate, nbadgeexpirationdate, badgecolor) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? ,?, ?, ?, ?, ?, ?, ?, ?, ?)";
		$stmt = $dbconn->prepare($sql);
		$stmt->execute(array($userid, $email, $firstname, $lastname, 'active', $aDateTimeGlobal, $nDateTimeGlobal, $loginid, $loginid, $aDateTimeGlobal, $nDateTimeGlobal, $address, $city, $state, $zip, $phone, $driverlicensenumber, $driverlicenseexpiration, $ndriverlicenseexpiration, $position, $dotexpiration, $ndotexpiration, $hireddate, $nhireddate, $senioritydate, $nsenioritydate, $birthdate, $nbirthdate, $badgenumber, $badgeexpirationdate, $nbadgeexpirationdate, $badgecolor ));
	}
	else
	{
		$sql = "update tblusers set email = ?, firstname = ?, lastname = ?, status = ?, lastupdatedbyuserid = ?, datetimelastupdated = ?, ndatetimelastupdated = ?, address = ?, city = ?, state = ?, zip = ?, phone = ?, driverlicensenumber = ?, driverlicenseexpiration = ?, ndriverlicenseexpiration = ?, position = ?, dotexpirationdate = ?, ndotexpirationdate = ?, hireddate = ?, nhireddate = ?, senioritydate = ?, nsenioritydate = ?, birthdate = ?, nbirthdate = ?, badgenumber = ?, badgeexpirationdate = ?, nbadgeexpirationdate = ?, badgecolor = ? where id = ?";
		$stmt = $dbconn->prepare($sql);
		$stmt->execute(array($email, $firstname, $lastname, 'active', $loginid, $aDateTimeGlobal, $nDateTimeGlobal, $address, $city, $state, $zip, $phone, $driverlicensenumber, $driverlicenseexpiration, $ndriverlicenseexpiration, $position, $dotexpiration, $ndotexpiration, $hireddate, $nhireddate, $senioritydate, $nsenioritydate, $birthdate, $nbirthdate, $badgenumber, $badgeexpirationdate, $nbadgeexpirationdate, $badgecolor, $userid));
	};

	$sql = "delete from tblroles where userid = ?";
	$stmt = $dbconn->prepare($sql);
	$stmt->execute(array($userid));

	if ($isdriver == "true") {
		$sql = "insert into tblroles (userid, role) values (?, ?)";
		$stmt = $dbconn->prepare($sql);
		$stmt->execute(array($userid, 'driver'));
	};

	if ($ismanager == "true") {
		$sql = "insert into tblroles (userid, role) values (?, ?)";
		$stmt = $dbconn->prepare($sql);
		$stmt->execute(array($userid, 'manager'));
	};

	if ($isadmin == "true") {
		$sql = "insert into tblroles (userid, role) values (?, ?)";
		$stmt = $dbconn->prepare($sql);
		$stmt->execute(array($userid, 'admin'));
	};

	if ($iscomments == "true") {
		$sql = "insert into tblroles (userid, role) values (?, ?)";
		$stmt = $dbconn->prepare($sql);
		$stmt->execute(array($userid, 'comments'));
	};

	if ($isfluids == "true") {
		$sql = "insert into tblroles (userid, role) values (?, ?)";
		$stmt = $dbconn->prepare($sql);
		$stmt->execute(array($userid, 'fluids'));
	};

	if ($isaccidents == "true") {
		$sql = "insert into tblroles (userid, role) values (?, ?)";
		$stmt = $dbconn->prepare($sql);
		$stmt->execute(array($userid, 'accidents'));
	};

	echo json_encode($arr);
	die();

};

if ($action == "deleteuser") {

	$userid = "";
	if (isset($post_obj["userid"])) {
		$userid = $post_obj["userid"];
	};

	$sql = "update tblusers set status = ?, lastupdatedbyuserid = ?,  datetimelastupdated = ?, ndatetimelastupdated = ? where id = ?";
	$stmt = $dbconn->prepare($sql);
	$stmt->execute(array("inactive", $loginid, $aDateTimeGlobal, $nDateTimeGlobal, $userid));

	echo json_encode($arr);
	die();
};

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

	$vehicletype = "";
	if (isset($post_obj["vehicletype"])) {
		$vehicletype = $post_obj["vehicletype"];
	};

	if (strlen($vehicletype) < 1) {
		$arr["message"] = "Invalid Vehicle Type value";
		echo json_encode($arr);
		die();
	};

	$sql = "select id, name from tblvehicles where vehicletypeid = ? and status = ?";
	$stmt = $dbconn->prepare($sql);
	$stmt->execute(array($vehicletype, 'active'));
	while ($row = $stmt->fetch()) {
		$nameid = $row["id"];
		$name = $row["name"];
		$arr[$nameid] = $name;
	};

	echo json_encode($arr);
	die();
};

if ($action == "savevehicletype") {

	$vehicletype = "";
	if (isset($post_obj["vehicletype"])) {
		$vehicletype = $post_obj["vehicletype"];
	};

	if (strlen($vehicletype) < 1) {
		$arr["message"] = "Invalid Vehicle Type value";
		echo json_encode($arr);
		die();
	};

	$sql = "select count(*) as count from tblvehicletypes where type = ? and status = ?";
	$stmt = $dbconn->prepare($sql);
	$stmt->execute(array($vehicletype, "active"));
	while ($row = $stmt->fetch()) {
		$count = $row["count"];
		if ($count > 0) {
			$arr["message"] = "The provided Vehicle Type already exists in the system";
			echo json_encode($arr);
			die();
		};
	};

	$sql = "insert into tblvehicletypes (type, status) values (?, ?)";
	$stmt = $dbconn->prepare($sql);
	$stmt->execute(array($vehicletype, "active"));

	echo json_encode($arr);
	die();

};

if ($action == "deletevehicletype") {

	$vehicletypeid = "";
	if (isset($post_obj["vehicletypeid"])) {
		$vehicletypeid = $post_obj["vehicletypeid"];
	};

	if (strlen($vehicletypeid) < 1) {
		$arr["message"] = "Invalid Vehicle Type value";
		echo json_encode($arr);
		die();
	};

	$sql = "update tblvehicletypes set status = ?, datedeleted = ?, ndatedeleted = ?, deletedbyuserid = ? where id = ?";
	$stmt = $dbconn->prepare($sql);
	$stmt->execute(array('deleted', $aDateTimeGlobal, $nDateTimeGlobal, $loginid, $vehicletypeid));

	$sql = "update tblvehicles set status = ?, datedeleted = ?, ndatedeleted = ?, deletedbyuserid = ? where vehicletypeid = ?";
	$stmt = $dbconn->prepare($sql);
	$stmt->execute(array('deleted', $aDateTimeGlobal, $nDateTimeGlobal, $loginid, $vehicletypeid));

	echo json_encode($arr);
	die();

};

if ($action == "savevehiclename") {

	$vehicletype = "";
	if (isset($post_obj["vehicletype"])) {
		$vehicletype = $post_obj["vehicletype"];
	};

	if (strlen($vehicletype) < 1) {
		$arr["message"] = "Invalid Vehicle Type value";
		echo json_encode($arr);
		die();
	};

	$vehiclename = "";
	if (isset($post_obj["vehiclename"])) {
		$vehiclename = $post_obj["vehiclename"];
	};

	if (strlen($vehiclename) < 1) {
		$arr["message"] = "Invalid Vehicle Name/Number value";
		echo json_encode($arr);
		die();
	};

	$sql = "select count(*) as count from tblvehicles where name = ? and vehicletypeid = ? and status = ?";
	$stmt = $dbconn->prepare($sql);
	$stmt->execute(array($vehiclename, $vehicletype, "active"));
	while ($row = $stmt->fetch()) {
		$count = $row["count"];
		if ($count > 0) {
			$arr["message"] = "The provided Vehicle Name for the provided Vehicle Type already exists in the system";
			echo json_encode($arr);
			die();
		};
	};

	$sql = "insert into tblvehicles (name, status, vehicletypeid) values (?, ?, ?)";
	$stmt = $dbconn->prepare($sql);
	$stmt->execute(array($vehiclename, "active", $vehicletype));

	echo json_encode($arr);
	die();

};

if ($action == "deletevehiclename") {

	$vehiclenameid = "";
	if (isset($post_obj["vehiclenameid"])) {
		$vehiclenameid = $post_obj["vehiclenameid"];
	};

	if (strlen($vehiclenameid) < 1) {
		$arr["message"] = "Invalid Vehicle Name/Number value";
		echo json_encode($arr);
		die();
	};

	$sql = "update tblvehicles set status = ?, datedeleted = ?, ndatedeleted = ?, deletedbyuserid = ? where id = ?";
	$stmt = $dbconn->prepare($sql);
	$stmt->execute(array('deleted', $aDateTimeGlobal, $nDateTimeGlobal, $loginid, $vehiclenameid));

	echo json_encode($arr);
	die();


};

if ($action == "getroutes") {

	$routetype = "";
	if (isset($post_obj["routetype"])) {
		$routetype = $post_obj["routetype"];
	};

	$status = "active";
	if (isset($post_obj["status"])) {
		$status = $post_obj["status"];
	};

	$sql = "select id, routename, listorder from tblroutes where status = ? and routetype = ? order by listorder";
	$stmt = $dbconn->prepare($sql);
	$stmt->execute(array($status, $routetype));
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

if ($action == "saveroutename") {

	$routename = "";
	if (isset($post_obj["routename"])) {
		$routename = $post_obj["routename"];
	};

	if (strlen($routename) < 1) {
		$arr["message"] = "Invalid Route Name value";
		echo json_encode($arr);
		die();
	};

	$routetype = "";
	if (isset($post_obj["routetype"])) {
		$routetype = $post_obj["routetype"];
	};

	if (strlen($routetype) < 1) {
		$arr["message"] = "Invalid Route Type value";
		echo json_encode($arr);
		die();
	};

	$sql = "select count(*) as count from tblroutes where routename = ? and status = ?";
	$stmt = $dbconn->prepare($sql);
	$stmt->execute(array($routename, "active"));
	while ($row = $stmt->fetch()) {
		$count = $row["count"];
		if ($count > 0) {
			$arr["message"] = "The provided Route Name already exists in the system";
			echo json_encode($arr);
			die();
		};
	};

	$sql = "insert into tblroutes (routename, routetype, status) values (?, ?, ?)";
	$stmt = $dbconn->prepare($sql);
	$stmt->execute(array($routename, $routetype, "active"));

	echo json_encode($arr);
	die();

};

if ($action == "deleteroute") {

	$routeid = "";
	if (isset($post_obj["routeid"])) {
		$routeid = $post_obj["routeid"];
	};

	if (strlen($routeid) < 1) {
		$arr["message"] = "Invalid Route Name value";
		echo json_encode($arr);
		die();
	};

	$sql = "update tblroutes set status = ?, datedeleted = ?, ndatedeleted = ?, deletedbyuserid = ? where id = ?";
	$stmt = $dbconn->prepare($sql);
	$stmt->execute(array('deleted', $aDateTimeGlobal, $nDateTimeGlobal, $loginid, $routeid));

	echo json_encode($arr);
	die();

};

if ($action == "deleteroute") {

	$routeid = "";
	if (isset($post_obj["routeid"])) {
		$routeid = $post_obj["routeid"];
	};

	if (strlen($routeid) < 1) {
		$arr["message"] = "Invalid Route Name value";
		echo json_encode($arr);
		die();
	};

	$sql = "update tblroutes set status = ?, datedeleted = ?, ndatedeleted = ?, deletedbyuserid = ? where id = ?";
	$stmt = $dbconn->prepare($sql);
	$stmt->execute(array('deleted', $aDateTimeGlobal, $nDateTimeGlobal, $loginid, $routeid));

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

if ($action == "savemileage") {

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

	$mileagevalue = "";
	if (isset($post_obj["mileagevalue"])) {
		$mileagevalue = $post_obj["mileagevalue"];
	};

	$sql = "delete from tblmileages where routeid = ? and mileagetype = ?";
	$stmt = $dbconn->prepare($sql);
	$stmt->execute(array($routeid, $mileagetype));


	$sql = "insert into tblmileages (routeid, mileagetype, mileagevalue) values (?, ?, ?)";
	$stmt = $dbconn->prepare($sql);
	$stmt->execute(array($routeid, $mileagetype, $mileagevalue));

	echo json_encode($arr);
	die();

};

if ($action == "getroutelistorder") {

	$status = "active";
	if (isset($post_obj["status"])) {
		$status = $post_obj["status"];
	};

	$sql = "select id, routename, listorder from tblroutes where status = ? order by listorder asc";
	$stmt = $dbconn->prepare($sql);
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

if ($action == "saveroutelistorder") {

	foreach($post_obj as $key=>$val) {

		$key = str_replace("lo_", "", $key);

		//error_log($key . " - " . $val);
		$sql = "update tblroutes set listorder = ? where id = ?";
		$stmt = $dbconn->prepare($sql);
		$stmt->execute(array($val, $key));
	};



	echo json_encode($arr);
	die();

};

if ($action == "getvehicleinfo") {

	$vehicleid = "";
	if (isset($post_obj["vehicleid"])) {
		$vehicleid = $post_obj["vehicleid"];
	};

	if (strlen($vehicleid) < 1) {
		$arr["message"] = "Invalid Vehicle Name value";
		echo json_encode($arr);
		die();
	};

	$sql = "select vin, licenseplate from tblvehicles where id = ?";
	$stmt = $dbconn->prepare($sql);
	$stmt->execute(array($vehicleid));
	while ($row = $stmt->fetch()) {
		$arr["vin"] = $row["vin"];
		$arr["licenseplate"] = $row["licenseplate"];
	};

	echo json_encode($arr);
	die();
};

if ($action == "savevehicleinfo") {

	$vehicleid = "";
	if (isset($post_obj["vehicleid"])) {
		$vehicleid = $post_obj["vehicleid"];
	};

	if (strlen($vehicleid) < 1) {
		$arr["message"] = "Invalid Vehicle Name value";
		echo json_encode($arr);
		die();
	};

	$vin = "";
	if (isset($post_obj["vin"])) {
		$vin = $post_obj["vin"];
	};

	$licenseplate = "";
	if (isset($post_obj["licenseplate"])) {
		$licenseplate = $post_obj["licenseplate"];
	};

	$sql = "update tblvehicles set vin = ?, licenseplate = ? where id = ?";
	$stmt = $dbconn->prepare($sql);
	$stmt->execute(array($vin, $licenseplate, $vehicleid));

	echo json_encode($arr);
	die();
};

if ($action == "savenewroutegroup") {

	$groupname = "";
	if (isset($post_obj["groupname"])) {
		$groupname = $post_obj["groupname"];
	};

	if (strlen($groupname) < 1) {
		$arr["message"] = "Invalid Group Name value";
		echo json_encode($arr);
		die();
	};

	$sql = "select count(*) as count from tblroutegroups where groupname = ?";
	$stmt = $dbconn->prepare($sql);
	$stmt->execute(array($groupname));
	while ($row = $stmt->fetch()) {
		$count = $row["count"];
		if ($count > 0) {
			$arr["message"] = "The provided Group Name already exists in the system";
			echo json_encode($arr);
			die();
		};
	};

	$sql = "insert into tblroutegroups (groupname) values (?)";
	$stmt = $dbconn->prepare($sql);
	$stmt->execute(array($groupname));

	echo json_encode($arr);
	die();
};

if ($action == "getroutegroups") {

	$sql = "select id, groupname from tblroutegroups order by groupname";
	$stmt = $dbconn->prepare($sql);
	$stmt->execute();
	while ($row = $stmt->fetch()) {
		$id = $row["id"];
		$groupname = $row["groupname"];
		$arr[$groupname]["id"] = $id;
		$arr[$groupname]["groupname"] = $groupname;
	};

	echo json_encode($arr);
	die();
};

if ($action == "getroutegroupmembers") {

	$groupname = "";
	if (isset($post_obj["groupname"])) {
		$groupname = $post_obj["groupname"];
	};

	if (strlen($groupname) < 1) {
		$arr["message"] = "Invalid Group Name value";
		echo json_encode($arr);
		die();
	};

	$sql = "SELECT tblroutegroups.routeid as routeid, tblroutes.routename as routename FROM tblroutegroups
	JOIN tblroutes on tblroutegroups.routeid = tblroutes.id where tblroutegroups.groupname = ? and tblroutes.status = ?";
	$stmt = $dbconn->prepare($sql);
	$stmt->execute(array($groupname, 'active'));
	while ($row = $stmt->fetch()) {
		$routeid = $row["routeid"];
		$routename = $row["routename"];
		$arr[$routeid] = $routename;
	};

	echo json_encode($arr);
	die();
};

if ($action == "deleteroutegroup") {

	$groupname = "";
	if (isset($post_obj["groupname"])) {
		$groupname = $post_obj["groupname"];
	};

	$sql = "delete from tblroutegroups where groupname = ?";
	$stmt = $dbconn->prepare($sql);
	$stmt->execute(array($groupname));

	echo json_encode($arr);
	die();
};

if ($action == "addroutegroupmember") {

	$groupname = "";
	if (isset($post_obj["groupname"])) {
		$groupname = $post_obj["groupname"];
	};

	$routeid = "";
	if (isset($post_obj["routeid"])) {
		$routeid = $post_obj["routeid"];
	};

	$sql = "delete from tblroutegroups where routeid = ? and groupname = ?";
	$stmt = $dbconn->prepare($sql);
	$stmt->execute(array($routeid, $groupname));

	$sql = "insert into tblroutegroups (routeid, groupname) values (?, ?)";
	$stmt = $dbconn->prepare($sql);
	$stmt->execute(array($routeid, $groupname));

	echo json_encode($arr);
	die();
};

if ($action == "getroutesforgroup") {

	$groupname = "";
	if (isset($post_obj["groupname"])) {
		$groupname = $post_obj["groupname"];
	};

	$routeids = "";

	$sql = "SELECT tblroutes.routename as routename,
	tblroutes.id as routeid
	FROM tblroutes
	WHERE tblroutes.status = ?";
	$stmt = $dbconn->prepare($sql);
	$stmt->execute(array('active'));
	while ($row = $stmt->fetch()) {
		$routeid = $row["routeid"];
		$routename = $row["routename"];
		$arr[$routeid] = $routename;
		$routeids = $routeids . "','" . $routeid;
	};

	$routeids = ltrim($routeids, "','");
	$routeids = "'" . $routeids . "'";

	$sql = "SELECT routeid as rid
	FROM tblroutegroups
	WHERE groupname = ?";
	$stmt = $dbconn->prepare($sql);
	$stmt->execute(array($groupname));
	while ($row = $stmt->fetch()) {
		$rid = $row["rid"];
		unset($arr[$rid]);
	};


	echo json_encode($arr);
	die();
};

if ($action == "removeroutegroupmember") {

	$groupname = "";
	if (isset($post_obj["groupname"])) {
		$groupname = $post_obj["groupname"];
	};

	$routeid = "";
	if (isset($post_obj["routeid"])) {
		$routeid = $post_obj["routeid"];
	};

	$sql = "DELETE FROM tblroutegroups
	WHERE routeid = ?
	AND groupname = ?";
	$stmt = $dbconn->prepare($sql);
	$stmt->execute(array($routeid, $groupname));

	echo json_encode($arr);
	die();

};

if ($action == "savenewvehiclegroup") {

	$groupname = "";
	if (isset($post_obj["groupname"])) {
		$groupname = $post_obj["groupname"];
	};

	if (strlen($groupname) < 1) {
		$arr["message"] = "Invalid Group Name value";
		echo json_encode($arr);
		die();
	};

	$sql = "select count(*) as count from tblvehiclegroups where groupname = ?";
	$stmt = $dbconn->prepare($sql);
	$stmt->execute(array($groupname));
	while ($row = $stmt->fetch()) {
		$count = $row["count"];
		if ($count > 0) {
			$arr["message"] = "The provided Group Name already exists in the system";
			echo json_encode($arr);
			die();
		};
	};

	$sql = "insert into tblvehiclegroups (groupname) values (?)";
	$stmt = $dbconn->prepare($sql);
	$stmt->execute(array($groupname));

	echo json_encode($arr);
	die();
};

if ($action == "getvehiclegroups") {

	$sql = "select id, groupname from tblvehiclegroups order by groupname";
	$stmt = $dbconn->prepare($sql);
	$stmt->execute();
	while ($row = $stmt->fetch()) {
		$id = $row["id"];
		$groupname = $row["groupname"];
		$arr[$groupname]["id"] = $id;
		$arr[$groupname]["groupname"] = $groupname;
	};

	echo json_encode($arr);
	die();
};

if ($action == "getvehiclegroupmembers") {

	$groupname = "";
	if (isset($post_obj["groupname"])) {
		$groupname = $post_obj["groupname"];
	};

	if (strlen($groupname) < 1) {
		$arr["message"] = "Invalid Group Name value";
		echo json_encode($arr);
		die();
	};

	$sql = "SELECT tblvehiclegroups.vehicleid as vehicleid, tblvehicles.name as vehiclename FROM tblvehiclegroups
	JOIN tblvehicles on tblvehiclegroups.vehicleid = tblvehicles.id where tblvehiclegroups.groupname = ? and tblvehicles.status = ?";
	$stmt = $dbconn->prepare($sql);
	$stmt->execute(array($groupname, 'active'));
	while ($row = $stmt->fetch()) {
		$vehicleid = $row["vehicleid"];
		$vehiclename = $row["vehiclename"];
		$arr[$vehicleid] = $vehiclename;
	};

	echo json_encode($arr);
	die();
};

if ($action == "deletevehiclegroup") {

	$groupname = "";
	if (isset($post_obj["groupname"])) {
		$groupname = $post_obj["groupname"];
	};

	$sql = "delete from tblvehiclegroups where groupname = ?";
	$stmt = $dbconn->prepare($sql);
	$stmt->execute(array($groupname));

	echo json_encode($arr);
	die();
};

if ($action == "addvehiclegroupmember") {

	$groupname = "";
	if (isset($post_obj["groupname"])) {
		$groupname = $post_obj["groupname"];
	};

	$vehicleid = "";
	if (isset($post_obj["vehicleid"])) {
		$vehicleid = $post_obj["vehicleid"];
	};

	$sql = "delete from tblvehiclegroups where vehicleid = ? and groupname = ?";
	$stmt = $dbconn->prepare($sql);
	$stmt->execute(array($vehicleid, $groupname));

	$sql = "insert into tblvehiclegroups (vehicleid, groupname) values (?, ?)";
	$stmt = $dbconn->prepare($sql);
	$stmt->execute(array($vehicleid, $groupname));

	echo json_encode($arr);
	die();
};

if ($action == "getvehiclesforgroup") {

	$groupname = "";
	if (isset($post_obj["groupname"])) {
		$groupname = $post_obj["groupname"];
	};

	$vehicleids = "";

	$sql = "SELECT tblvehicles.name as vehiclename,
	tblvehicles.id as vehicleid
	FROM tblvehicles
	WHERE tblvehicles.status = ?";
	$stmt = $dbconn->prepare($sql);
	$stmt->execute(array('active'));
	while ($row = $stmt->fetch()) {
		$vehicleid = $row["vehicleid"];
		$vehiclename = $row["vehiclename"];
		$arr[$vehicleid] = $vehiclename;
		$vehicleids = $vehicleids . "','" . $vehicleid;
	};

	$vehicleids = ltrim($vehicleids, "','");
	$vehicleids = "'" . $vehicleids . "'";

	$sql = "SELECT vehicleid as vid
	FROM tblvehiclegroups
	WHERE groupname = ?";
	$stmt = $dbconn->prepare($sql);
	$stmt->execute(array($groupname));
	while ($row = $stmt->fetch()) {
		$vid = $row["vid"];
		unset($arr[$vid]);
	};


	echo json_encode($arr);
	die();
};

if ($action == "removevehiclegroupmember") {

	$groupname = "";
	if (isset($post_obj["groupname"])) {
		$groupname = $post_obj["groupname"];
	};

	$vehicleid = "";
	if (isset($post_obj["vehicleid"])) {
		$vehicleid = $post_obj["vehicleid"];
	};

	$sql = "DELETE FROM tblvehiclegroups
	WHERE vehicleid = ?
	AND groupname = ?";
	$stmt = $dbconn->prepare($sql);
	$stmt->execute(array($vehicleid, $groupname));

	echo json_encode($arr);
	die();

};


$arr["message"] = "No valid \"action\" value was POSTed.";
echo json_encode($arr);
die();



?>