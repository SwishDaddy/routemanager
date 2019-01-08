<?php

include "includes.php";

//Make sure the user accessing this script is logged in
if (!logincheck()) {
	$arr["message"] = "Login Failed";
	echo json_encode($arr);
	die();
}

//Make sure the user accessing this script has the "comments" role
$arrroles = rolecheck();
if (!in_array ("comments" , $arrroles)) {
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

if ($action == "getroutes") {

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

if ($action == "savecomment") {

	$commentid = "";
	if (isset($post_obj["commentid"])) {
		$commentid = $post_obj["commentid"];
	};

	$adatetime = "";
	if (isset($post_obj["date_of_incident"])) {
		$adatetime = $post_obj["date_of_incident"];
	};

	$ndatetime = strtotime($adatetime);

	$customername = "";
	if (isset($post_obj["customer_name"])) {
		$customername = $post_obj["customer_name"];
	};

	$userid = "";
	if (isset($post_obj["userid"])) {
		$userid = $post_obj["userid"];
	};

	$routeid = "";
	if (isset($post_obj["routeid"])) {
		$routeid = $post_obj["routeid"];
	};

	$vehicleid = "";
	if (isset($post_obj["vehicleid"])) {
		$vehicleid = $post_obj["vehicleid"];
	};

	$commenttype = "";
	if (isset($post_obj["commenttype"])) {
		$commenttype = $post_obj["commenttype"];
	};

	$commenttext = "";
	if (isset($post_obj["commenttext"])) {
		$commenttext = $post_obj["commenttext"];
	};

	$resolutiontext = "";
	if (isset($post_obj["resolutiontext"])) {
		$resolutiontext = $post_obj["resolutiontext"];
	};

	if (strlen($commentid) < 1) {
		$sql = "insert into tblcomments (adatetimecreated, ndatetimecreated, createdbyuserid) values (?, ?, ?)";
		$stmt = $dbconn->prepare($sql);
		$stmt->execute(array($aDateTimeGlobal, $nDateTimeGlobal, $_SESSION["loginid"]));
		$commentid = $dbconn->lastInsertId();
	};

	$sql = "update tblcomments set adatetime = ?, ndatetime = ?, customername = ?, userid = ?, routeid = ?, vehicleid = ?, commenttype = ?, commenttext = ?, resolutiontext = ?, adatetimeupdated = ?, ndatetimeupdated = ?, updatedbyuserid = ? where id = ?";
	$stmt = $dbconn->prepare($sql);
	$stmt->execute(array($adatetime, $ndatetime, $customername, $userid, $routeid, $vehicleid, $commenttype, $commenttext, $resolutiontext, $aDateTimeGlobal, $nDateTimeGlobal, $_SESSION["loginid"], $commentid));

	echo json_encode($arr);
	die();

};

if ($action == "getcommentdata") {

	$commentid = "";
	if (isset($post_obj["commentid"])) {
		$commentid = $post_obj["commentid"];
	};

	$sql = "select adatetime, customername, userid, routeid, vehicleid, commenttype, commenttext, resolutiontext from tblcomments where id = ?";
	$stmt = $dbconn->prepare($sql);
	$stmt->execute(array($commentid));
	while ($row = $stmt->fetch()) {
		$arr["adatetime"] = $row["adatetime"];
		$arr["customername"] = $row["customername"];
		$arr["userid"] = $row["userid"];
		$arr["routeid"] = $row["routeid"];
		$arr["vehicleid"] = $row["vehicleid"];
		$arr["commenttype"] = $row["commenttype"];
		$arr["commenttext"] = $row["commenttext"];
		$arr["resolutiontext"] = $row["resolutiontext"];
	};

	echo json_encode($arr);
	die();
};

if ($action == "getcomments") {

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

	$sql = "select id, adatetime, ndatetime, customername, userid, routeid, vehicleid, commenttype, commenttext, resolutiontext from tblcomments where ndatetime >= ? and ndatetime <= ? and active = ?";
	$stmt = $dbconn->prepare($sql);
	$stmt->execute(array($nfromdate, $ntodate, 'true'));
	
	while ($row = $stmt->fetch()) {
		$id = $row["id"];
				
		$arr[$id]["adatetime"] = $row["adatetime"];
		$arr[$id]["customername"] = $row["customername"];

		$arr[$id]["employeename"] = "";
		$arr[$id]["routename"] = "";
		$arr[$id]["vehiclename"] = "";

		$arr[$id]["userid"] = $row["userid"];
		$sql1 = "select firstname, lastname from tblusers where id = ?";
		$stmt1 = $dbconn->prepare($sql1);
		$stmt1->execute(array($row["userid"]));
		while ($row1 = $stmt1->fetch()) {
			$arr[$id]["employeename"] = $row1["firstname"] . " " . $row1["lastname"];
		};

		$arr[$id]["routeid"] = $row["routeid"];
		$sql1 = "select routename from tblroutes where id = ?";
		$stmt1 = $dbconn->prepare($sql1);
		$stmt1->execute(array($row["routeid"]));
		while ($row1 = $stmt1->fetch()) {
			$arr[$id]["routename"] = $row1["routename"];
		};

		$arr[$id]["vehicleid"] = $row["vehicleid"];
		$sql1 = "select name from tblvehicles where id = ?";
		$stmt1 = $dbconn->prepare($sql1);
		$stmt1->execute(array($row["vehicleid"]));
		while ($row1 = $stmt1->fetch()) {
			$arr[$id]["vehiclename"] = $row1["name"];
		};
		
		// Replace the User Input... they often copy and pasted, and sometimes those characters aren't ut8-compliant, so this ignores those characters, whoich otherwise breaks the code by blanking the array when it is json_encoded		
		$arr[$id]["commenttype"] = $row["commenttype"]. "";
		$arr[$id]["commenttext"] = iconv("UTF-8","UTF-8//IGNORE", $row["commenttext"]);			
		$arr[$id]["resolutiontext"] = $row["resolutiontext"] . "";
		$arr[$id]["resolutiontext"] = iconv("UTF-8","UTF-8//IGNORE", $row["resolutiontext"]);
			
	};
	
	//echo("arr4:" . json_encode($arr));
	
	//error_log("arr1:" . json_encode($arr));

	echo json_encode($arr);
	die();
};

if ($action == "deletecomment") {
	$commentid = "";
	if (isset($post_obj["commentid"])) {
		$commentid = $post_obj["commentid"];
	};

	$sql = "update tblcomments set active = ? where id = ?";
	$stmt = $dbconn->prepare($sql);
	$stmt->execute(array('false', $commentid));



	echo json_encode($arr);
	die();
};

if ($action == "employeesummaryreport") {

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


	$sql = "select count(*) as count, userid, commenttype from tblcomments
	where ndatetime >= ? and ndatetime <= ? and active = ? and commenttype = ?
	group by userid, commenttype";
	$stmt = $dbconn->prepare($sql);
	$stmt->execute(array($nfromdate, $ntodate, 'true', 'compliment'));
	while ($row = $stmt->fetch()) {

		$sql1 = "select firstname, lastname from tblusers where id = ?";
		$stmt1 = $dbconn->prepare($sql1);
		$stmt1->execute(array($row["userid"]));
		while ($row1 = $stmt1->fetch()) {
			$fullname = $row1["firstname"] . " " . $row1["lastname"];
			$arr[$row["userid"]]["fullname"] = $fullname;
			$arr[$row["userid"]][$row["commenttype"]] = $row["count"];
		};


	};

	echo json_encode($arr);
	die();
};


$arr["message"] = "No valid \"action\" value was POSTed.";
echo json_encode($arr);
die();



?>