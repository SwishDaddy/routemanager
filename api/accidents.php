<?php

include "includes.php";

$arr["message"] = "success";

//Make sure the user accessing this script is logged in
if (!logincheck()) {	
	$arr["message"] = "Login Failed";
	echo json_encode($arr);
	die();
}

//Make sure the user accessing this script has the "fluids" role
$arrroles = rolecheck();
if (!in_array ("accidents" , $arrroles)) {
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

if ($action == "saveaccident") {

	$loginid = $_SESSION["loginid"];

	$accidentid = $post_obj["accidentid"];

	//error_log(json_encode($post_obj));

	if (isset($post_obj["otherdriver"])) {

		$sql = "DELETE FROM tblaccidentsotherdrivers WHERE accidentid = ?";
		$stmt = $dbconn->prepare($sql);
		$stmt->execute(array($accidentid));

		foreach($post_obj["otherdriver"] as $key=>$val) {

			$sqlfields = "";
			$sqlvals = array();
			$sqlplaceholders = "";

			$val = json_decode($val, true);
			$otherdriverid = $val["id"];
			$val["vehicle_damage_diagram_coordinates"] = json_encode($val["vehicle_damage_diagram_coordinates"]);
			foreach($val as $key1=>$val1) {
				$sqlfields = $sqlfields . $key1 . ",";
				array_push($sqlvals, $val1);
				$sqlplaceholders = $sqlplaceholders . "?,";
			};

			$sqlfields = $sqlfields . "adatetimecreated,ndatetimecreated,createdbyuserid";
			$sqlplaceholders = $sqlplaceholders . "'$aDateTimeGlobal', '$nDateTimeGlobal','$loginid'";

			//error_log($sqlfields);

			$sql = "INSERT INTO tblaccidentsotherdrivers
			($sqlfields)
			VALUES ($sqlplaceholders)";
			$stmt = $dbconn->prepare($sql);
			$stmt->execute($sqlvals);

			//Move temp damage diagrams to final place
			$tempfilepath = $pathfortempfiles . "/" . $loginid . "/" . $otherdriverid . ".png";
			$destpath = $pathforfiles . "/" . $accidentid . "/" . $otherdriverid . ".png";

			if(!is_dir($pathforfiles)){
			  mkdir ($pathforfiles);
			};

			if(!is_dir($pathforfiles . "/" . $accidentid)){
			  mkdir ($pathforfiles . "/" . $accidentid);
			};

			if (file_exists($tempfilepath)) {
				rename($tempfilepath, $destpath);
			};

		};

		unset($post_obj["otherdriver"]);
	};

	if (isset($post_obj["injury"])) {

		$sql = "DELETE FROM tblaccidentsinjuries WHERE accidentid = ?";
		$stmt = $dbconn->prepare($sql);
		$stmt->execute(array($accidentid));

		foreach($post_obj["injury"] as $key=>$val) {

			$sqlfields = "";
			$sqlvals = array();
			$sqlplaceholders = "";

			$val = json_decode($val, true);
			$injuryid = $val["id"];
			foreach($val as $key1=>$val1) {
				$sqlfields = $sqlfields . $key1 . ",";
				array_push($sqlvals, $val1);
				$sqlplaceholders = $sqlplaceholders . "?,";
			};

			$sqlfields = $sqlfields . "adatetimecreated,ndatetimecreated,createdbyuserid";
			$sqlplaceholders = $sqlplaceholders . "'$aDateTimeGlobal', '$nDateTimeGlobal','$loginid'";

			$sql = "INSERT INTO tblaccidentsinjuries
			($sqlfields)
			VALUES ($sqlplaceholders)";
			$stmt = $dbconn->prepare($sql);
			$stmt->execute($sqlvals);

		};

		unset($post_obj["injury"]);
	};

	if (isset($post_obj["witness"])) {

		$sql = "DELETE FROM tblaccidentswitnesses WHERE accidentid = ?";
		$stmt = $dbconn->prepare($sql);
		$stmt->execute(array($accidentid));

		foreach($post_obj["witness"] as $key=>$val) {

			$sqlfields = "";
			$sqlvals = array();
			$sqlplaceholders = "";

			$val = json_decode($val, true);
			$witnessid = $val["id"];
			foreach($val as $key1=>$val1) {
				$sqlfields = $sqlfields . $key1 . ",";
				array_push($sqlvals, $val1);
				$sqlplaceholders = $sqlplaceholders . "?,";
			};

			$sqlfields = $sqlfields . "adatetimecreated,ndatetimecreated,createdbyuserid";
			$sqlplaceholders = $sqlplaceholders . "'$aDateTimeGlobal', '$nDateTimeGlobal','$loginid'";

			$sql = "INSERT INTO tblaccidentswitnesses
			($sqlfields)
			VALUES ($sqlplaceholders)";
			$stmt = $dbconn->prepare($sql);
			$stmt->execute($sqlvals);

		};

		unset($post_obj["witness"]);
	};

	$post_obj["id"] = $accidentid;
	unset($post_obj["accidentid"]);

	$post_obj["ndate_of_accident"] = strtotime($post_obj["date_of_accident"]);

	$sqlfields = "";
	$sqlvals = array();
	$sqlplaceholders = "";

	foreach($post_obj as $key=>$val) {

		$sqlfields = $sqlfields . $key . ",";
		array_push($sqlvals, $val);
		$sqlplaceholders = $sqlplaceholders . "?,";

	};

	$sqlfields = $sqlfields . "adatetimecreated,ndatetimecreated,createdbyuserid";
	$sqlplaceholders = $sqlplaceholders . "'$aDateTimeGlobal', '$nDateTimeGlobal','$loginid'";

	$sql = "DELETE FROM tblaccidents WHERE id = ?";
	$stmt = $dbconn->prepare($sql);
	$stmt->execute(array($accidentid));

	$sql = "INSERT INTO tblaccidents
	($sqlfields)
	VALUES ($sqlplaceholders)";
	$stmt = $dbconn->prepare($sql);
	$stmt->execute($sqlvals);

	// Copy over the Employee Vehicle Damage Diagram, if there is one
	$tempfilepath = $pathfortempfiles . "/" . $loginid . "/" . $accidentid . ".png";
	$destpath = $pathforfiles . "/" . $accidentid . "/" . $accidentid . ".png";

	if(!is_dir($pathforfiles)){
	  mkdir ($pathforfiles);
	};

	if(!is_dir($pathforfiles . "/" . $accidentid)){
	  mkdir ($pathforfiles . "/" . $accidentid);
	};

	if (file_exists($tempfilepath)) {
		rename($tempfilepath, $destpath);
	};

	//Move over uploaded files

	$filetype = "photos_taken";
	if(is_dir($pathfortempfiles . '/' . $accidentid . '/' . $filetype)){

		$fcount = 0;
		$d = dir( $pathfortempfiles . '/' . $accidentid . '/' . $filetype); // dir to scan
		while (false !== ($entry = $d->read())) { // mind the strict bool check!
			if ($entry[0] == '.') continue; // ignore anything starting with a dot
			if ($entry == 'thumbnail') continue; // ignore anything starting with a dot

			if(!is_dir($pathforfiles)){
			  mkdir ($pathforfiles);
			};

			if(!is_dir($pathforfiles . '/' . $accidentid)){
			  mkdir ($pathforfiles . '/' . $accidentid);
			};

			if(!is_dir($pathforfiles . '/' . $accidentid . '/' . $filetype)){
			  mkdir ($pathforfiles . '/' . $accidentid . '/' . $filetype);
			};

			rename($pathfortempfiles . '/' . $accidentid . '/' . $filetype . '/' . $entry, $pathforfiles . '/' . $accidentid . '/' . $filetype . '/' . $entry);

		}
		$d->close();
	};

	$filetype = "other_documents";
	if(is_dir($pathfortempfiles . '/' . $accidentid . '/' . $filetype)){

		$fcount = 0;
		$d = dir( $pathfortempfiles . '/' . $accidentid . '/' . $filetype); // dir to scan
		while (false !== ($entry = $d->read())) { // mind the strict bool check!
			if ($entry[0] == '.') continue; // ignore anything starting with a dot
			if ($entry == 'thumbnail') continue; // ignore anything starting with a dot

			if(!is_dir($pathforfiles)){
			  mkdir ($pathforfiles);
			};

			if(!is_dir($pathforfiles . '/' . $accidentid)){
			  mkdir ($pathforfiles . '/' . $accidentid);
			};

			if(!is_dir($pathforfiles . '/' . $accidentid . '/' . $filetype)){
			  mkdir ($pathforfiles . '/' . $accidentid . '/' . $filetype);
			};

			rename($pathfortempfiles . '/' . $accidentid . '/' . $filetype . '/' . $entry, $pathforfiles . '/' . $accidentid . '/' . $filetype . '/' . $entry);

		}
		$d->close();

	};




	echo json_encode($arr);
	die();
};

if ($action == "getuniqueid") {
	$prefix = "notprovided";
	if (isset($post_obj["prefix"])) {
		$prefix = $post_obj["prefix"];
	};
	$uniqueid = uniqueid($prefix);
	$arr["uniqueid"] = $uniqueid;
	echo json_encode($arr);
	die();
};

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

	$vehicletypeid = "";
	if (isset($post_obj["vehicletypeid"])) {
		$vehicletypeid = $post_obj["vehicletypeid"];
	};
	$sql = "select id, name from tblvehicles where status = ?";
	$sqlarray = array("active");

	if (strlen($vehicletypeid) > 0) {
		$sql = "select id, name from tblvehicles where vehicletypeid = ? and status = ?";
		$sqlarray = array($vehicletypeid, "active");
	};

	$stmt = $dbconn->prepare($sql);
	$stmt->execute($sqlarray);
	while ($row = $stmt->fetch()) {
		$nameid = $row["id"];
		$name = $row["name"];
		$arr[$nameid] = $name;
	};

	echo json_encode($arr);
	die();
};

if ($action == "getroutes") {

	$status = "active";
	if (isset($post_obj["status"])) {
		$status = $post_obj["status"];
	};

	$sql = "select id, routename, listorder from tblroutes where status = ? order by listorder asc";
	$stmt = $dbconn->prepare($sql);
	$stmt->execute(array($status));
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

if ($action == "getaccidents") {

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

	$sql = "select id, date_of_accident, ndate_of_accident, employee_driver, vehiclename from tblaccidents where ndate_of_accident >= ? and ndate_of_accident <= ? and active = ? order by ndate_of_accident desc";
	$stmt = $dbconn->prepare($sql);
	$stmt->execute(array($nfromdate, $ntodate, 'true'));
	while ($row = $stmt->fetch()) {
		$id = $row["id"];
		$arr[$id]["adatetime"] = $row["date_of_accident"];
		$arr[$id]["employeedrivername"] = "";
		$arr[$id]["vehiclename"] = "";

		$arr[$id]["employeedriverid"] = $row["employee_driver"];
		$sql1 = "select firstname, lastname from tblusers where id = ?";
		$stmt1 = $dbconn->prepare($sql1);
		$stmt1->execute(array($row["employee_driver"]));
		while ($row1 = $stmt1->fetch()) {
			$arr[$id]["employeedrivername"] = $row1["firstname"] . " " . $row1["lastname"];
		};

		$arr[$id]["vehicleid"] = $row["vehiclename"];
		$sql1 = "select name from tblvehicles where id = ?";
		$stmt1 = $dbconn->prepare($sql1);
		$stmt1->execute(array($row["vehiclename"]));
		while ($row1 = $stmt1->fetch()) {
			$arr[$id]["vehiclename"] = $row1["name"];
		};

	};


	echo json_encode($arr);
	die();
};

if ($action == "getemployeeinfo") {

	$userid = "";
	if (isset($post_obj["userid"])) {
		$userid = $post_obj["userid"];
	};

	$sql = "select badgenumber, badgeexpirationdate, driverlicensenumber, driverlicenseexpiration, address, city, state, zip, phone from tblusers where id = ?";
	$stmt = $dbconn->prepare($sql);
	$stmt->execute(array($userid));
	while ($row = $stmt->fetch()) {
		$arr["badgenumber"] = $row["badgenumber"];
		$arr["badgeexpirationdate"] = $row["badgeexpirationdate"];
		$arr["driverlicensenumber"] = $row["driverlicensenumber"];
		$arr["driverlicenseexpiration"] = $row["driverlicenseexpiration"];
		$arr["address"] = $row["address"] . " " . $row["city"] . " " . $row["state"] . " " . $row["zip"];
		$arr["phone"] = $row["phone"];
	};

	echo json_encode($arr);
	die();
};

if ($action == "getvehicleinfo") {

	$vehicleid = "";
	if (isset($post_obj["vehicleid"])) {
		$vehicleid = $post_obj["vehicleid"];
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

if ($action == "getvehicletypes") {

	$status = "";
	if (isset($post_obj["status"])) {
		$status = $post_obj["status"];
	};
	$sql = "select id, type from tblvehicletypes where status = ?";

	$stmt = $dbconn->prepare($sql);
	$stmt->execute(array($status));
	while ($row = $stmt->fetch()) {
		$nameid = $row["id"];
		$name = $row["type"];
		$arr[$nameid] = $name;
	};

	echo json_encode($arr);
	die();
};

if ($action == "savethumbnail") {

	$filename = "";
	if (isset($post_obj["filename"])) {
		$filename = $post_obj["filename"];
	};

	$img = "";
	if (isset($post_obj["img"])) {
		$img = $post_obj["img"];
	};

	$loginid = $_SESSION["loginid"];

	if (!is_dir($pathfortempfiles . "/" . $loginid) ) {
		mkdir ($pathfortempfiles . "/" . $loginid);
	};

	file_put_contents($pathfortempfiles . "/" . $loginid . "/" . $filename, base64_decode($img));

	echo json_encode($arr);
	die();
};

if ($action == "deletethumbnails") {

	if (is_dir($pathfortempfiles . "/" . $loginid) ) {

		foreach(glob($pathfortempfiles . "/" . $loginid . "/*.*") as $v){
			unlink($v);
		}
	};

	echo json_encode($arr);
	die();
};

if ($action == "getaccidentdata") {

	$accidentid = "";
	if (isset($post_obj["accidentid"])) {
		$accidentid = $post_obj["accidentid"];
	};

	if (strlen($accidentid) < 1) {
		$arr["message"] = "Invalid Accident ID";
		echo json_encode($arr);
		die();
	};

	$sql = "SELECT COUNT(*) as count
	FROM tblaccidents
	WHERE id = ?";
	$stmt = $dbconn->prepare($sql);
	$stmt->execute(array($accidentid));
	while ($row = $stmt->fetch()) {
		$count = $row["count"];
		if ($count < 1) {
			$arr["message"] = "Invalid Accident ID";
			echo json_encode($arr);
			die();
		};
	};

	$sql = "select * from tblaccidents where id = ?";
	$stmt = $dbconn->prepare($sql);
	$stmt->execute(array($accidentid));

	$arr_maindata = $stmt->fetchAll(PDO::FETCH_OBJ);

	//error_log(json_encode($arr_maindata));

	$arr["maindata"] = $arr_maindata;

	$arr_otherdriver = [];

	$sql = "select * from tblaccidentsotherdrivers where accidentid = ?";
	$stmt = $dbconn->prepare($sql);
	$stmt->execute(array($accidentid));

	while ($row = $stmt->fetch()) {

		$id = $row["id"];

		$arr_otherdriver[$id]["id"] = $row["id"];
		$arr_otherdriver[$id]["name"] = $row["name"];
		$arr_otherdriver[$id]["address"] = $row["address"];
		$arr_otherdriver[$id]["city"] = $row["city"];
		$arr_otherdriver[$id]["state"] = $row["state"];
		$arr_otherdriver[$id]["zip"] = $row["zip"];
		$arr_otherdriver[$id]["homephone"] = $row["homephone"];
		$arr_otherdriver[$id]["workphone"] = $row["workphone"];
		$arr_otherdriver[$id]["driverlicense"] = $row["driverlicense"];
		$arr_otherdriver[$id]["driverlicenseexpiration"] = $row["driverlicenseexpiration"];
		$arr_otherdriver[$id]["nameofinsurance"] = $row["nameofinsurance"];
		$arr_otherdriver[$id]["insurancephone"] = $row["insurancephone"];
		$arr_otherdriver[$id]["insurancepolicynumber"] = $row["insurancepolicynumber"];
		$arr_otherdriver[$id]["driverinjured"] = $row["driverinjured"];
		$arr_otherdriver[$id]["drivercited"] = $row["drivercited"];
		$arr_otherdriver[$id]["occupantcount"] = $row["occupantcount"];
		$arr_otherdriver[$id]["vehicletype"] = $row["vehicletype"];
		$arr_otherdriver[$id]["vehicleyear"] = $row["vehicleyear"];
		$arr_otherdriver[$id]["vehiclevin"] = $row["vehiclevin"];
		$arr_otherdriver[$id]["vehiclelicenseplate"] = $row["vehiclelicenseplate"];
		$arr_otherdriver[$id]["vehicledamaged"] = $row["vehicledamaged"];
		$arr_otherdriver[$id]["vehicledrivable"] = $row["vehicledrivable"];
		$arr_otherdriver[$id]["vehicle_damage_diagram_coordinates"] = $row["vehicle_damage_diagram_coordinates"];
		$arr_otherdriver[$id]["drivercomments"] = $row["drivercomments"];
		$arr_otherdriver[$id]["vehiclecomments"] = $row["vehiclecomments"];
		$arr_otherdriver[$id]["accidentid"] = $row["accidentid"];

	};

	$arr["otherdriver"] = $arr_otherdriver;

	$arr_injury = [];
	$sql = "select * from tblaccidentsinjuries where accidentid = ?";
	$stmt = $dbconn->prepare($sql);
	$stmt->execute(array($accidentid));
	while ($row = $stmt->fetch()) {

		$id = $row["id"];
		$arr_injury[$id]["id"] = $row["id"];
		$arr_injury[$id]["name"] = $row["name"];
		$arr_injury[$id]["age"] = $row["age"];
		$arr_injury[$id]["phone"] = $row["phone"];
		$arr_injury[$id]["role"] = $row["role"];
		$arr_injury[$id]["address"] = $row["address"];
		$arr_injury[$id]["city"] = $row["city"];
		$arr_injury[$id]["state"] = $row["state"];
		$arr_injury[$id]["zip"] = $row["zip"];
		$arr_injury[$id]["comments"] = $row["comments"];
		$arr_injury[$id]["accidentid"] = $row["accidentid"];
	};
	$arr["injury"] = $arr_injury;

	$arr_witness = [];
	$sql = "select * from tblaccidentswitnesses where accidentid = ?";
	$stmt = $dbconn->prepare($sql);
	$stmt->execute(array($accidentid));
	while ($row = $stmt->fetch()) {

		$id = $row["id"];
		$arr_witness[$id]["id"] = $row["id"];
		$arr_witness[$id]["name"] = $row["name"];
		$arr_witness[$id]["dayphone"] = $row["dayphone"];
		$arr_witness[$id]["eveningphone"] = $row["eveningphone"];
		$arr_witness[$id]["address"] = $row["address"];
		$arr_witness[$id]["city"] = $row["city"];
		$arr_witness[$id]["state"] = $row["state"];
		$arr_witness[$id]["zip"] = $row["zip"];
		$arr_witness[$id]["comments"] = $row["comments"];
		$arr_witness[$id]["accidentid"] = $row["accidentid"];
	};
	$arr["witness"] = $arr_witness;

	echo json_encode($arr);
	die();
};

if ($action == "deletefile") {

	$type = "";
	if (isset($post_obj["type"])) {
		$type = $post_obj["type"];
	};

	$accidentid = "";
	if (isset($post_obj["accidentid"])) {
		$accidentid = $post_obj["accidentid"];
	};

	$filetype = "";
	if (isset($post_obj["filetype"])) {
		$filetype = $post_obj["filetype"];
	};

	$filename = "";
	if (isset($post_obj["filename"])) {
		$filename = $post_obj["filename"];
	};

	$filepath = "";

	if ($type == "new") {
		$filepath = $pathfortempfiles . "/" . $accidentid . "/" . $filetype . "/" . $filename;
	};

	if ($type == "existing") {
			$filepath = $pathforfiles . "/" . $accidentid . "/" . $filetype . "/" . $filename;
	};

	if (file_exists($filepath)) {
		unlink($filepath);
	};


	echo json_encode($arr);
	die();
};

if ($action == "getlinkedfiles") {

	$accidentid = "";
	if (isset($post_obj["accidentid"])) {
		$accidentid = $post_obj["accidentid"];
	};

	$filetype = "";
	if (isset($post_obj["filetype"])) {
		$filetype = $post_obj["filetype"];
	};

	$arr_existing = [];
	$arr_new = [];

	if(is_dir($pathforfiles . '/' . $accidentid . '/' . $filetype)){

		$fcount = 0;
		$d = dir( $pathforfiles . '/' . $accidentid . '/' . $filetype); // dir to scan
		while (false !== ($entry = $d->read())) { // mind the strict bool check!
			if ($entry[0] == '.') continue; // ignore anything starting with a dot
			if ($entry == 'thumbnail') continue;

			$arr_existing[$fcount]["filename"] = $entry;
			$arr_existing[$fcount]["filepath"] = $pathforfiles . '/' . $accidentid . '/' . $filetype . '/' . $entry;

			$fcount = $fcount + 1;
		}
		$d->close();

	};

	if(is_dir($pathfortempfiles . '/' . $accidentid . '/' . $filetype)){

		$fcount = 0;
		$d = dir( $pathfortempfiles . '/' . $accidentid . '/' . $filetype); // dir to scan
		while (false !== ($entry = $d->read())) { // mind the strict bool check!
			if ($entry[0] == '.') continue; // ignore anything starting with a dot
			if ($entry == 'thumbnail') continue;

			$arr_new[$fcount]["filename"] = $entry;
			$arr_new[$fcount]["filepath"] = $pathfortempfiles . '/' . $accidentid . '/' . $filetype . '/' . $entry;

			$fcount = $fcount + 1;
		}
		$d->close();

	};

	$arr["new"] = $arr_new;
	$arr["existing"] = $arr_existing;

	//error_log(json_encode($arr));

	echo json_encode($arr);
	die();
};

if ($action == "deleteaccident") {

	$accidentid = "";
	if (isset($post_obj["accidentid"])) {
		$accidentid = $post_obj["accidentid"];
	};

	$sql = "UPDATE tblaccidents
	SET active = ?
	WHERE id = ?";
	$stmt = $dbconn->prepare($sql);
	$stmt->execute(array('false', $accidentid));

	echo json_encode($arr);
	die();
};



$arr["message"] = "No valid \"action\" value was POSTed.";
echo json_encode($arr);
die();



?>