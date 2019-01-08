<?php

include "includes.php";

//Make sure the user accessing this script is logged in
if (!logincheck()) {
	$arr["message"] = "Login Failed";
	echo json_encode($arr);
	die();
}

//Make sure the user accessing this script has the "manager" role
$arrroles = rolecheck();
if (!in_array ("manager" , $arrroles)) {
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

	$sql = $sql = "select id, name from tblvehicles where vehicletypeid = ? and status = ?";
	$sqlarray = array($vehicletype, 'active');

	if ($vehicletype == "all") {
		$sql = "select id, name from tblvehicles where status = ?";
		$sqlarray = array('active');
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

if ($action == "getresults") {

	$report_type = "";
	if (isset($post_obj["report_type"])) {
		$report_type = $post_obj["report_type"];
	};
	if (strlen($report_type) < 1) {
		$arr["message"] = "Invalid Report Type value";
		echo json_encode($arr);
		die();
	};

	$report_subtype = "";
	if (isset($post_obj["report_subtype"])) {
		$report_subtype = $post_obj["report_subtype"];
	};
	if (strlen($report_subtype) < 1) {
		$arr["message"] = "Invalid Report Subtype value";
		echo json_encode($arr);
		die();
	};

	$billable = "all";
	if (isset($post_obj["billable"])) {
		$billable = $post_obj["billable"];
	};

	$report_fromdate = "";
	if (isset($post_obj["report_fromdate"])) {
		$report_fromdate = $post_obj["report_fromdate"];
	};
	if (strlen($report_fromdate) < 1) {
		$arr["message"] = "Invalid Report From Date value";
		echo json_encode($arr);
		die();
	};

	$report_todate = "";
	if (isset($post_obj["report_todate"])) {
		$report_todate = $post_obj["report_todate"];
	};
	if (strlen($report_todate) < 1) {
		$arr["message"] = "Invalid Report To Date value";
		echo json_encode($arr);
		die();
	};

	$route_group = "all";
	if (isset($post_obj["route_group"])) {
		$route_group = $post_obj["route_group"];
	};
	$vehicle_group = "all";
	if (isset($post_obj["vehicle_group"])) {
		$vehicle_group = $post_obj["vehicle_group"];
	};

	$report_criteria = "";
	if (isset($post_obj["report_criteria"])) {
		$report_criteria = $post_obj["report_criteria"];
	};

	$report_group_by = "reports_group_by_day";
	if (isset($post_obj["report_group_by"])) {
		$report_group_by = $post_obj["report_group_by"];
	};

	$arrfromdatetime = explode(" ", $report_fromdate);
	$fromdate = $arrfromdatetime[0];
	//$fromtime = $arrfromdatetime[1];
	$nfromdate = strtotime($fromdate);

	$arrtodatetime = explode(" ", $report_todate);
	$todate = $arrtodatetime[0];
	//$totime = $arrtodatetime[1];
	$ntodate = strtotime($todate);

	$sqldaterange = " and (tbldriversheets.ndate >= '$nfromdate' and tbldriversheets.ndate <= '$ntodate') ";

	$arr_results = [];

	$routegroupjoin = "";
	$routegroupwhere = "";
	if($route_group != "all") {
		$routegroupjoin = " JOIN tblroutegroups on tbldriversheetdata.routeid = tblroutegroups.routeid ";
		$routegroupwhere = " AND tblroutegroups.groupname = '$route_group' ";
	};

	$vehiclegroupjoin = "";
	$vehiclegroupwhere = "";
	if($vehicle_group != "all") {
		$vehiclegroupjoin = " JOIN tblvehiclegroups on tbldriversheets.vehicleid = tblvehiclegroups.vehicleid ";
		$vehiclegroupwhere = " AND tblvehiclegroups.groupname = '$vehicle_group' ";
	};

	$billablewhere = " and tbldriversheetdata.billable <> 'False' ";
	if ($billable == "False") {
		$billablewhere = " and tbldriversheetdata.billable = 'False' ";
	};
	if ($billable == "all") {
		$billablewhere = "";
	};

	//############  total_number_of_passengers
	if($report_type == "total_number_of_passengers") {

		if($report_subtype == "hourly_totals") {

			$arr2 = [];

			$sql = "SELECT tbldriversheets.vehicleid as vehicleid,
			tbldriversheets.adate as adate,
			tbldriversheets.ndate as ndate,
			tbldriversheetdata.tripcount_lot_turnstile as tc1,
			tbldriversheetdata.tripcount_terminal_concourse as tc2,
			tbldriversheetdata.passengercount_lot_turnstile as pc1,
			tbldriversheetdata.passengercount_terminal_concourse as pc2,
			tbldriversheetdata.hour as hour
			FROM tbldriversheets
			INNER JOIN tbldriversheetdata on tbldriversheets.id = tbldriversheetdata.driversheetid
			$routegroupjoin
			$vehiclegroupjoin
			where 1=1 $sqldaterange
			$routegroupwhere
			$vehiclegroupwhere
			and tbldriversheets.active = 'true'";

			$stmt = $dbconn->prepare($sql);
			$stmt->execute();
			while ($row = $stmt->fetch()) {

				$ndate = $row["ndate"];
				$adate = $row["adate"];
				$hour = $row["hour"];
				$tc1 = $row["tc1"];
				$tc2 = $row["tc2"];
				$pc1 = $row["pc1"];
				$pc2 = $row["pc2"];

				$arr2[$ndate][$ndate.$hour]["Date"] = $adate;

				$hour_with_colon = substr($hour,0,2).':'.substr($hour,2,2);

				$hour = "" . $hour;

				$arr2[$ndate][$ndate.$hour]["Hour"] = $hour_with_colon;

				if (!isset($arr2[$ndate][$ndate.$hour]["Lot/Turnstile Trip Count"])) {
					$arr2[$ndate][$ndate.$hour]["Lot/Turnstile Trip Count"] = 0;
				};
				$arr2[$ndate][$ndate.$hour]["Lot/Turnstile Trip Count"] = floatval($arr2[$ndate][$ndate.$hour]["Lot/Turnstile Trip Count"]) + floatval($tc1);

				if (!isset($arr2[$ndate][$ndate.$hour]["Terminal/Concourse Trip Count"])) {
					$arr2[$ndate][$ndate.$hour]["Terminal/Concourse Trip Count"] = 0;
				};
				$arr2[$ndate][$ndate.$hour]["Terminal/Concourse Trip Count"] = floatval($arr2[$ndate][$ndate.$hour]["Terminal/Concourse Trip Count"]) + floatval($tc2);

				if (!isset($arr2[$ndate][$ndate.$hour]["Lot/Turnstile Passengers"])) {
					$arr2[$ndate][$ndate.$hour]["Lot/Turnstile Passengers"] = 0;
				};
				$arr2[$ndate][$ndate.$hour]["Lot/Turnstile Passengers"] = floatval($arr2[$ndate][$ndate.$hour]["Lot/Turnstile Passengers"]) + floatval($pc1);

				if (!isset($arr2[$ndate][$ndate.$hour]["Terminal/Concourse Passengers"])) {
					$arr2[$ndate][$ndate.$hour]["Terminal/Concourse Passengers"] = 0;
				};
				$arr2[$ndate][$ndate.$hour]["Terminal/Concourse Passengers"] = floatval($arr2[$ndate][$ndate.$hour]["Terminal/Concourse Passengers"]) + floatval($pc2);


			};

			$arr_results["report_group_by"] = $report_group_by;

			$arr["arr_results"] = $arr2;

			if ($report_group_by == "reports_group_by_day") {

				$arr3 = [];

				foreach($arr2 as $key=>$val) {
					foreach($val as $key1=>$val1) {
						//$arr3[$key]["Date"] = $val1["Date"];
						$arr3[$key]["Lot/Turnstile Passengers"][$val1["Hour"]] = $val1["Lot/Turnstile Passengers"];
						$arr3[$key]["Terminal/Concourse Passengers"][$val1["Hour"]] = $val1["Terminal/Concourse Passengers"];
						$arr3[$key]["Lot/Turnstile Trip Count"][$val1["Hour"]] = $val1["Lot/Turnstile Trip Count"];
						$arr3[$key]["Terminal/Concourse Trip Count"][$val1["Hour"]] = $val1["Terminal/Concourse Trip Count"];
					};
				};

				$arr3["report_group_by"] = $report_group_by;

				$arr["arr_results"] = $arr3;
			};

			echo json_encode($arr);
			die();

		};

		$total_pc1 = 0;
		$total_pc2 = 0;
		$total_tc1 = 0;
		$total_tc2 = 0;

		$total_lot_to_terminal = 0;
		$total_terminal_to_lot = 0;

		$lot_to_terminal_mileagevalue = 0;
		$terminal_to_lot_mileagevalue = 0;

		$total_lot_to_terminal_mileagevalue = 0;
		$total_terminal_to_lot_mileagevalue = 0;

		$sqlwhere = "where 1=1";
		$groupby = "";

		if($report_subtype == "datetime") {
			$groupby = "group by tbldriversheets.ndate";
		};

		if($report_subtype == "vehicle_totals") {
			$groupby = "group by tbldriversheets.vehicleid";
		};

		if($report_subtype == "route_total" || $report_subtype == "from_lot" || $report_subtype == "from_terminal") {
			if(strlen($report_criteria) > 0 && $report_criteria != "all") {
				$sqlwhere = "where tbldriversheetdata.routeid = '$report_criteria' ";
			};
		};

		if($report_subtype == "specific_day") {
			$sqlwhere = "where dayname(FROM_UNIXTIME(tbldriversheets.ndate)) = '$report_criteria'";
		};

		if($report_subtype == "vehicle_number") {
			if(strlen($report_criteria) > 0 && $report_criteria != "all") {
				$sqlwhere = "where tbldriversheets.vehicleid = '$report_criteria'";
			};
		};

		$sqlreporttype = "select tbldriversheetdata.id as id,
		tbldriversheetdata.routeid as routeid,
		tbldriversheets.vehicleid as vehicleid,
		tblroutes.routename as routename,
		tblvehicles.name as vehiclename,
		tbldriversheets.adate as adate,
		tbldriversheets.ndate as ndate,
		tbldriversheetdata.hour as hour,
		tbldriversheetdata.lot_to_terminal_mileage as lot_to_terminal_mileage,
		tbldriversheetdata.terminal_to_lot_mileage as terminal_to_lot_mileage,
		tbldriversheetdata.tripcount_lot_turnstile as tc1,
		(tbldriversheetdata.tripcount_lot_turnstile) * (tbldriversheetdata.lot_to_terminal_mileage ) as lot_to_terminal_mileage,
		tbldriversheetdata.tripcount_terminal_concourse as tc2,
		(tbldriversheetdata.tripcount_terminal_concourse) * (tbldriversheetdata.terminal_to_lot_mileage) as terminal_to_lot_mileage,
		tbldriversheetdata.passengercount_lot_turnstile as pc1,
		tbldriversheetdata.passengercount_terminal_concourse as pc2
		from tbldriversheetdata
		inner join tbldriversheets on tbldriversheetdata.driversheetid = tbldriversheets.id
		JOIN tblroutes on tbldriversheetdata.routeid = tblroutes.id
		JOIN tblvehicles on tbldriversheets.vehicleid = tblvehicles.id
		$routegroupjoin
		$vehiclegroupjoin
		$sqlwhere
		$sqldaterange
		$routegroupwhere
		$vehiclegroupwhere
		and tbldriversheets.active = 'true'
		order by tbldriversheets.ndate asc";


		if ($report_group_by == "reports_group_by_day") {
			$sqlreporttype = "select tbldriversheetdata.id as id,
			tbldriversheetdata.routeid as routeid,
			tbldriversheets.vehicleid as vehicleid,
			tblroutes.routename as routename,
			tblvehicles.name as vehiclename,
			tbldriversheets.adate as adate,
			tbldriversheets.ndate as ndate,
			tbldriversheetdata.hour as hour,
			tbldriversheetdata.lot_to_terminal_mileage as lot_to_terminal_mileage,
			tbldriversheetdata.terminal_to_lot_mileage as terminal_to_lot_mileage,
			sum(tbldriversheetdata.tripcount_lot_turnstile) as tc1,
			sum((tbldriversheetdata.tripcount_lot_turnstile) * (tbldriversheetdata.lot_to_terminal_mileage )) as lot_to_terminal_mileage,
			sum(tbldriversheetdata.tripcount_terminal_concourse) as tc2,
			sum((tbldriversheetdata.tripcount_terminal_concourse) * (tbldriversheetdata.terminal_to_lot_mileage)) as terminal_to_lot_mileage,
			sum(tbldriversheetdata.passengercount_lot_turnstile) as pc1,
			sum(tbldriversheetdata.passengercount_terminal_concourse) as pc2
			from tbldriversheetdata
			inner join tbldriversheets on tbldriversheetdata.driversheetid = tbldriversheets.id
			JOIN tblroutes on tbldriversheetdata.routeid = tblroutes.id
			JOIN tblvehicles on tbldriversheets.vehicleid = tblvehicles.id
			$routegroupjoin
			$vehiclegroupjoin
			$sqlwhere
			$sqldaterange
			$routegroupwhere
			$vehiclegroupwhere
			and tbldriversheets.active = 'true'
			$groupby
			order by tbldriversheets.ndate asc";
		};

		$stmt = $dbconn->prepare($sqlreporttype);
		$stmt->execute();
		while ($row = $stmt->fetch()) {

			$pc1 = $row["pc1"];
			$pc2 = $row["pc2"];
			$tc1 = $row["tc1"];
			$tc2 = $row["tc2"];
			$id = $row["id"];
			$routeid = $row["routeid"];
			$routename = $row["routename"];
			$adate = $row["adate"];
			$ndate = $row["ndate"];
			$vehicleid = $row["vehicleid"];
			$vehiclename = $row["vehiclename"];
			$hour = $row["hour"];

			$mv1 = $row["lot_to_terminal_mileage"];
			$mv2 = $row["terminal_to_lot_mileage"];

			if (strlen($pc1) > 0) {
				if(is_numeric($pc1)) {
					$total_pc1 = floatval($total_pc1) + floatval($pc1);
				};
			};

			if (strlen($pc2) > 0) {
				if(is_numeric($pc2)) {
					$total_pc2 = floatval($total_pc2) + floatval($pc2);
				};
			};

			if (strlen($tc1) > 0) {
				if(is_numeric($tc1)) {
					$total_tc1 = floatval($total_tc1) + floatval($tc1);
				};
			};

			if (strlen($tc2) > 0) {
				if(is_numeric($tc2)) {
					$total_tc2 = floatval($total_tc2) + floatval($tc2);
				};
			};

			if (strlen($mv1) > 0) {
				if(is_numeric($mv1)) {
					$lot_to_terminal_mileagevalue = floatval($mv1);
					$total_lot_to_terminal_mileagevalue = floatval($total_lot_to_terminal_mileagevalue) + floatval($lot_to_terminal_mileagevalue);
				};
			};

			if (strlen($mv2) > 0) {
				if(is_numeric($mv2)) {
					$terminal_to_lot_mileagevalue = floatval($mv2);
					$total_terminal_to_lot_mileagevalue = floatval($total_terminal_to_lot_mileagevalue) + floatval($terminal_to_lot_mileagevalue);
				};
			};

			if ((strlen($pc1) > 0 && floatval($pc1) > 0) || (strlen($pc2) > 0 && floatval($pc2) > 0) || (strlen($tc1) > 0 && floatval($tc1) > 0) || (strlen($tc2) > 0 && floatval($tc2) > 0)) {

				$arr_results[$id][$ndate.$hour]["Route"] = $routename;
				//$arr_results[$id][$ndate.$hour]["Vehicle"] = $vehiclename;
				if (($report_subtype == "vehicle_totals") || ($report_group_by != "reports_group_by_day")) {
					$arr_results[$id][$ndate.$hour]["Vehicle Name"] = $vehiclename;
				};
				if (($report_subtype == "datetime" ) || ($report_group_by != "reports_group_by_day")) {
					$arr_results[$id][$ndate.$hour]["Date"] = $adate;
				};

				//add the colon to the middle of military time in ordr to make it not numeric so they don't get stripped by export
				$hour_with_colon = substr($hour,0,2).':'.substr($hour,2,2);
				$arr_results[$id][$ndate.$hour]["Hour"] = $hour_with_colon;

				if($report_subtype != "from_terminal") {
					$arr_results[$id][$ndate.$hour]["Lot/Turnstile Passengers"] = $pc1;
				};
				if($report_subtype != "from_lot") {
					$arr_results[$id][$ndate.$hour]["Terminal/Concourse Passengers"] = $pc2;
				};
				if($report_subtype != "from_terminal") {
					$arr_results[$id][$ndate.$hour]["Lot/Turnstile Trip Count"] = $tc1;
				};
				if($report_subtype != "from_lot") {
					$arr_results[$id][$ndate.$hour]["Terminal/Concourse Trip Count"] = $tc2;
				};
				if($report_subtype != "from_terminal") {
					$arr_results[$id][$ndate.$hour]["Lot/Turnstile Miles"] = number_format($lot_to_terminal_mileagevalue, 2);
				};
				if($report_subtype != "from_lot") {
					$arr_results[$id][$ndate.$hour]["Terminal/Concourse Miles"] = number_format($terminal_to_lot_mileagevalue, 2);
				};

			};

		};

		if($report_subtype != "from_terminal") {
			$arr_results["total"]["total"]["Grand Total Lot/Turnstile Passengers"] = $total_pc1;
			$arr_results["total"]["total"]["Grand Total Lot/Turnstile Trip Count"] = $total_tc1;
			$arr_results["total"]["total"]["Grand Total Lot/Turnstile Miles"] = number_format($total_lot_to_terminal_mileagevalue, 2);
		};

		if($report_subtype != "from_lot") {
			$arr_results["total"]["total"]["Grand Total Terminal/Concourse Passengers"] = $total_pc2;
			$arr_results["total"]["total"]["Grand Total Terminal/Concourse Trip Count"] = $total_tc2;
			$arr_results["total"]["total"]["Grand Total Terminal/Concourse Miles"] = number_format($total_terminal_to_lot_mileagevalue, 2);
		};

		$arr_results["report_group_by"] = $report_group_by;

		$arr["arr_results"] = $arr_results;

		echo json_encode($arr);
		die();

	};

	//############  total_in_service_hours
	if($report_type == "total_in_service_hours") {

		//####### total_in_service_hours-> vehicle_number
		if($report_subtype == "vehicle_number") {

			$arr_times = [];

			$arrdatetotal = [];

			$total_hours_out_of_service = 0;
			$total_hours_in_service = 0;

			$grand_total_out_of_service = 0;

			$sql1 = "SELECT tblvehicles.id AS vehicleid,
			tbldriversheetdata.id as id,
			tbldriversheetdata.routeid as routeid,
			tbldriversheetdata.driversheetid,
			tbldriversheets.ndate as ndate,
			tbldriversheets.adate as adate,
			tbldriversheetdata.out_of_service_time as out_of_service_time,
			tbldriversheetdata.back_in_service_time as back_in_service_time,
			tbldriversheetdata.non_operational_reason as non_operational_reason
			FROM tblvehicles
			JOIN tbldriversheets on tbldriversheets.vehicleid = tblvehicles.id
			JOIN tbldriversheetdata on tbldriversheetdata.driversheetid = tbldriversheets.id
			$routegroupjoin
			$vehiclegroupjoin
			where 1=1 $sqldaterange
			$billablewhere
			$routegroupwhere
			$vehiclegroupwhere
			and tbldriversheets.vehicleid = '$report_criteria'

			and tbldriversheets.active = ?
			ORDER BY tbldriversheets.ndate, tbldriversheetdata.driversheetid";

			//AND tbldriversheetdata.out_of_service_time != ''
			//AND tbldriversheetdata.back_in_service_time != ''

			$stmt1 = $dbconn->prepare($sql1);
			$stmt1->execute(array('true'));
			while ($row1 = $stmt1->fetch()) {

				$id = $row1["id"];

				$ndate = $row1["ndate"];

				//route
				$routeid = $row1["routeid"];
				$sql2 = "select routename from tblroutes where id = ?";
				$stmt2 = $dbconn->prepare($sql2);
				$stmt2->execute(array($routeid));
				while ($row2 = $stmt2->fetch()) {
					$arr_times[$ndate][$id]["Route Name"] = $row2["routename"];
				};


				$vehicleid = $row1["vehicleid"];
				$sql2 = "select name from tblvehicles where id = ?";
				$stmt2 = $dbconn->prepare($sql2);
				$stmt2->execute(array($vehicleid));
				while ($row2 = $stmt2->fetch()) {
					$arr_times[$ndate][$id]["Vehicle Name"] = $row2["name"];
				};

				$arr_times[$ndate][$id]["Date"] = $row1["adate"];
				$hour_with_colon = substr($row1["out_of_service_time"],0,2).':'.substr($row1["out_of_service_time"],2,2);
				$arr_times[$ndate][$id]["Out of Service"] = $hour_with_colon;

				$hour_with_colon = substr($row1["back_in_service_time"],0,2).':'.substr($row1["back_in_service_time"],2,2);
				$arr_times[$ndate][$id]["Back in Service"] = $hour_with_colon;
				$arr_times[$ndate][$id]["Outage Reason"] = $row1["non_operational_reason"];

				$out_of_service_time = $row1["out_of_service_time"];
				if(strlen($out_of_service_time) == 4) {
					$out_of_service_time  = strtotime($out_of_service_time);
				};

				$back_in_service_time = $row1["back_in_service_time"];
				if(strlen($back_in_service_time) == 4) {
					$back_in_service_time  = strtotime($back_in_service_time);
				};

				$difference = abs($back_in_service_time - $out_of_service_time) / 3600;

				$total_hours_out_of_service = floatval($difference);

				$arr_times[$ndate][$id]["Hours Out"] = $total_hours_out_of_service;

				if (!isset($arrdatetotal[$ndate])) {
					$arrdatetotal[$ndate] = [];
				};

				if (!isset($arrdatetotal[$ndate][$vehicleid]["Total Day Hours"])) {
					$arrdatetotal[$ndate][$vehicleid]["Total Day Hours"] = 0;
				};

				if (!isset($arrdatetotal[$ndate][$vehicleid]["Total Out of Service Hours"])) {
					$arrdatetotal[$ndate][$vehicleid]["Total Out of Service Hours"] = 0;
				};

				$arrdatetotal[$ndate][$vehicleid]["Total Out of Service Hours"] = floatval($arrdatetotal[$ndate][$vehicleid]["Total Out of Service Hours"]) + floatval($arr_times[$ndate][$id]["Hours Out"]);

			};

			$grandtotalinforday = 0;
			$grandtotaloutforday = 0;

			foreach ($arrdatetotal as $key=>$val) {

				$totaloutforday = 0;
				$daycount = 0;

				foreach($val as $key2=>$val1) {
					$totaloutforday = $totaloutforday + $val1["Total Out of Service Hours"];
					$daycount = $daycount + 1;
				};

				$totalinforday = ((floatval(24 * $daycount)) - floatval($totaloutforday));

				$grandtotalinforday = floatval($grandtotalinforday) + floatval($totalinforday);
				$grandtotaloutforday = floatval($grandtotaloutforday) + floatval($totaloutforday);
			};

			$arr_times["total"]["total"]["Grand Total Hours Out"] = $grandtotaloutforday;


			$arr_times["report_group_by"] = $report_group_by;
			$arr["arr_results"] = $arr_times;

			if ($report_group_by == "reports_group_by_day") {

				$arr2["report_group_by"] = $report_group_by;

				foreach ($arr_times as $key=>$val) {

					if ($key == "total") {
						continue;
					};

					if ($key != "report_group_by") {

						$arr2[$key] = [];
						foreach ($val as $key1=>$val1) {

							$total_hours_out_of_service = 0;
							$total_hours_in_service = 0;

							$vehiclename = $val1["Vehicle Name"];

							$arr2[$key][$vehiclename]["Date"] = $val1["Date"];

							$arr2[$key][$vehiclename]["Vehicle Name"] = $vehiclename;

							$out_of_service_time = str_replace(":", "", $val1["Out of Service"]);
							if(strlen($out_of_service_time) == 4) {
								$out_of_service_time  = strtotime($out_of_service_time);
							};

							$back_in_service_time = str_replace(":", "", $val1["Back in Service"]);
							if(strlen($back_in_service_time) == 4) {
								$back_in_service_time  = strtotime($back_in_service_time);
							};

							$difference = abs($back_in_service_time - $out_of_service_time) / 3600;

							$total_hours_out_of_service = floatval($total_hours_out_of_service) + floatval($difference);

							if (!isset($arr2[$key][$vehiclename]["Total Out of Service"])) {
								$arr2[$key][$vehiclename]["Total Out of Service"] = 0;
							};

							$arr2[$key][$vehiclename]["Total Out of Service"] = floatval($arr2[$key][$vehiclename]["Total Out of Service"]) + floatval($total_hours_out_of_service);

							$total_hours_in_service = floatval(24) - floatval($arr2[$key][$vehiclename]["Total Out of Service"]);

							$arr2[$key][$vehiclename]["Total In Service"] = $total_hours_in_service;

						};

					};

				};

				$arr2["total"]["total"]["Grand Total In Service"] = $grandtotalinforday;
				$arr2["total"]["total"]["Grand Total Out of Service"] = $grandtotaloutforday;

				$arr["arr_results"] = $arr2;
			};

			echo json_encode($arr);
			die();

		};

		//####### total_in_service_hours-> datetime
		if($report_subtype == "hourly_totals") {

			$arr_times = [];

			$total_hours_out_of_service = 0;
			$total_hours_in_service = 0;

			$grand_total_out_of_service = 0;

			$sql = "SELECT tblvehicles.name AS vehicleid,
			tbldriversheetdata.id as id,
			tbldriversheetdata.routeid as routeid,
			tbldriversheets.ndate as ndate,
			tbldriversheets.adate as adate,
			tbldriversheetdata.out_of_service_time as out_of_service_time,
			tbldriversheetdata.back_in_service_time as back_in_service_time,
			tbldriversheetdata.non_operational_reason as non_operational_reason
			FROM tbldriversheets
			JOIN tblvehicles on tblvehicles.id = tbldriversheets.vehicleid
			JOIN tbldriversheetdata on tbldriversheetdata.driversheetid = tbldriversheets.id
			$routegroupjoin
			$vehiclegroupjoin
			where 1=1 $sqldaterange
			$billablewhere
			$routegroupwhere
			$vehiclegroupwhere

			and tbldriversheets.active = ?
			ORDER BY tbldriversheets.ndate";

			//AND tbldriversheetdata.out_of_service_time != ''
			//AND tbldriversheetdata.back_in_service_time != ''



			$stmt = $dbconn->prepare($sql);
			$stmt->execute(array('true'));

			$counter = 0;

			while ($row = $stmt->fetch()) {

				$arr_out_in = [];

				//$out_of_service_time = "";
				//if (strlen($row["out_of_service_time"]) == 4) {
					$out_of_service_time = $row["out_of_service_time"];
				//};

				//$back_in_service_time = "";
				//if (strlen($row["back_in_service_time"]) == 4) {
					$back_in_service_time = $row["back_in_service_time"];
				//};

				$arr_out_in["out_of_service_time"] = $out_of_service_time;
				$arr_out_in["back_in_service_time"] = $back_in_service_time;

				$adate = $row["adate"];
				$ndate = $row["ndate"];
				$vehicleid = $row["vehicleid"];

				$arr_times[$ndate][$vehicleid][$counter] = $arr_out_in;

				$counter = $counter + 1;

			};

			$arr_out_of_service =[];

			foreach($arr_times as $key=>$val) { //$key = ndate

				$vehiclecount = count($val);

				$arr_out_of_service[$key]["vehiclecount"] = $vehiclecount;

				foreach($val as $key1=>$val1) { // $key1 = vehicleid

					foreach($val1 as $key2=>$val2) { // $key2 = hour

						$x = 0;

						for ($x = 0; $x < 24; $x++) {

							if (strlen($x) == 1) {
								$hour_to_check = "0" . $x . "00";
							};

							if (strlen($x) == 2) {
								$hour_to_check = $x . "00";
							};

							if (strlen($val2["out_of_service_time"]) == 4 && strlen($val2["back_in_service_time"]) == 4) {

								if ( (floatval($hour_to_check) >= floatval($val2["out_of_service_time"])) && (floatval($hour_to_check) <= floatval($val2["back_in_service_time"])) ) {

									if ( (floatval($val2["back_in_service_time"]) - floatval($hour_to_check) ) > 59 ) {
										$arr_out_of_service[$key][floatval($hour_to_check)][$key1] = true;
									};
								};
							};

							if (!isset($arr_out_of_service[$key][floatval($hour_to_check)])) {
								$arr_out_of_service[$key][floatval($hour_to_check)] = [];
							};
						};
					};
				};
			};

			$final_arr_out_of_service = [];

			foreach ($arr_out_of_service as $key=>$val) {  //ndate

				$vehiclecount = $val["vehiclecount"];

				unset($val["vehiclecount"]);

				foreach ($val as $key1=>$val1) { 	//hour

					$hourvehiclecount = floatval($vehiclecount) - floatval(count($val1));

					$hour_to_check = $key1;

					if (strlen($key1) == 3) {
						$hour_to_check = "0" . $key1;
					};

					if (strlen($key1) == 1) {
						$hour_to_check = "000" . $key1;
					};

					$hour_with_colon = substr($hour_to_check ,0 ,2).':'.substr($hour_to_check, 2, 2);

					$date =  date("m/d/Y", $key);

					$final_arr_out_of_service[$key][$key1]["Date"] = $date;
					$final_arr_out_of_service[$key][$key1]["Time"] = $hour_with_colon;
					$final_arr_out_of_service[$key][$key1]["Vehicles In Service"] = $hourvehiclecount;

				};

			};

			$final_arr_out_of_service["report_group_by"] = $report_group_by;
			$arr["arr_results"] = $final_arr_out_of_service;

			echo json_encode($arr);
			die();

		};


		//####### total_in_service_hours-> datetime_by_date
		if($report_subtype == "datetime_by_date" || $report_subtype == "vehicle_totals" || $report_subtype == "route") {
			$arr_times = [];

			$grand_total_out_of_service = 0;

			$onlyvalidtimeswhere = "";

			if ($report_group_by == "reports_group_by_hour") {
				$onlyvalidtimeswhere = " AND tbldriversheetdata.out_of_service_time != ''
				AND tbldriversheetdata.back_in_service_time != '' ";

			};

			$routewhere = "";
			$routejoin = "";

			if ( $report_subtype == "route" ) {
				$routegroupjoin = " JOIN tblroutegroups on tbldriversheetdata.routeid = tblroutegroups.routeid ";
				$routewhere = " AND tbldriversheetdata.routeid = '$report_criteria' ";
				if ($report_criteria == "all") {
					$routewhere = "";
				};
			};

			$sql1 = "SELECT tblvehicles.id AS vehicleid,
			tbldriversheetdata.id as id,
			tbldriversheetdata.routeid as routeid,
			tbldriversheetdata.driversheetid,
			tbldriversheets.ndate as ndate,
			tbldriversheets.adate as adate,
			tbldriversheetdata.out_of_service_time as out_of_service_time,
			tbldriversheetdata.back_in_service_time as back_in_service_time,
			tbldriversheetdata.non_operational_reason as non_operational_reason
			FROM tblvehicles
			JOIN tbldriversheets on tbldriversheets.vehicleid = tblvehicles.id
			JOIN tbldriversheetdata on tbldriversheetdata.driversheetid = tbldriversheets.id
			$routegroupjoin
			$vehiclegroupjoin
			where 1=1 $sqldaterange
			$billablewhere
			$routegroupwhere
			$vehiclegroupwhere
			$onlyvalidtimeswhere
			$routewhere
			and tbldriversheets.active = ?
			ORDER BY tbldriversheets.ndate, tbldriversheetdata.driversheetid";



			$stmt1 = $dbconn->prepare($sql1);
			$stmt1->execute(array('true'));
			while ($row1 = $stmt1->fetch()) {

				$id = $row1["id"];

				$ndate = $row1["ndate"];

				//route
				$routeid = $row1["routeid"];
				$sql2 = "select routename from tblroutes where id = ?";
				$stmt2 = $dbconn->prepare($sql2);
				$stmt2->execute(array($routeid));
				while ($row2 = $stmt2->fetch()) {
					$arr_times[$ndate][$id]["Route Name"] = $row2["routename"];
				};

				$vehicleid = $row1["vehicleid"];
				$sql2 = "select name from tblvehicles where id = ?";
				$stmt2 = $dbconn->prepare($sql2);
				$stmt2->execute(array($vehicleid));
				while ($row2 = $stmt2->fetch()) {
					$arr_times[$ndate][$id]["Vehicle Name"] = $row2["name"];
				};

				$arr_times[$ndate][$id]["Date"] = $row1["adate"];

				$hour_with_colon = substr($row1["out_of_service_time"],0,2).':'.substr($row1["out_of_service_time"],2,2);
				$arr_times[$ndate][$id]["Out of Service"] = $hour_with_colon;

				$hour_with_colon = substr($row1["back_in_service_time"],0,2).':'.substr($row1["back_in_service_time"],2,2);
				$arr_times[$ndate][$id]["Back in Service"] = $hour_with_colon;

				$arr_times[$ndate][$id]["Outage Reason"] = $row1["non_operational_reason"];

				$out_of_service_time = $row1["out_of_service_time"];
				if(strlen($out_of_service_time) == 4) {
					$out_of_service_time  = strtotime($out_of_service_time);
				}
				else
				{
					$out_of_service_time  = 0;
				};

				$back_in_service_time = $row1["back_in_service_time"];
				if(strlen($back_in_service_time) == 4) {
					$back_in_service_time  = strtotime($back_in_service_time);
				}
				else
				{
					$back_in_service_time  = 0;
				};

				$difference = abs($back_in_service_time - $out_of_service_time) / 3600;

				$total_hours_out_of_service = floatval($difference);

				$arr_times[$ndate][$id]["Hours Out"] = $total_hours_out_of_service;

				$grand_total_out_of_service = floatval($grand_total_out_of_service) + floatval($total_hours_out_of_service);
			};

			$arr_times["total"]["total"]["Grand Total Hours Out"] = $grand_total_out_of_service;

			$total_hours_out_of_service = 0;
			$total_hours_in_service = 0;

			$arr_times["report_group_by"] = $report_group_by;
			$arr["arr_results"] = $arr_times;

			if ($report_group_by == "reports_group_by_day") {

				$arr2["report_group_by"] = $report_group_by;

				$super_grand_total_hours_out_of_service = 0;
				$super_grand_total_hours_in_service = 0;

				$arr_vehiclecount = [];

				foreach ($arr_times as $key=>$val) {

					if ($key == "total") {
						continue;
					};

					if ($key != "report_group_by") {

						$grand_total_hours_out_of_service = 0;
						$grand_total_hours_in_service = 0;

						$arr2[$key] = [];
						foreach ($val as $key1=>$val1) {


							$total_hours_out_of_service = 0;
							$total_hours_in_service = 0;

							$arr2[$key][$key]["Date"] = $val1["Date"];

							$arr2[$key][$key]["Total Out of Service"] = "";
							$arr2[$key][$key]["Total In Service"] = "";

							$vehiclename = $val1["Vehicle Name"];

							//$arr_vehiclecount[$vehiclename] = $vehiclename;

							$out_of_service_time = str_replace(":", "", $val1["Out of Service"]);
							if(strlen($out_of_service_time) == 4) {
								$out_of_service_time  = strtotime($out_of_service_time);
							}
							else
							{
								$out_of_service_time = 0;
							};

							$back_in_service_time = str_replace(":", "", $val1["Back in Service"]);
							if(strlen($back_in_service_time) == 4) {
								$back_in_service_time  = strtotime($back_in_service_time);
							}
							else
							{
								$back_in_service_time = 0;
							};

							$difference = abs($back_in_service_time - $out_of_service_time) / 3600;

							$total_hours_out_of_service = floatval($total_hours_out_of_service) + floatval($difference);

							if (!isset($arr_vehiclecount[$key][$vehiclename])) {
								$arr_vehiclecount[$key][$vehiclename] = 0;
							};

							$arr_vehiclecount[$key][$vehiclename] = floatval($arr_vehiclecount[$key][$vehiclename]) + floatval($total_hours_out_of_service);
						};

					};

				};

				if ($report_subtype == "datetime_by_date") {
					$total_vehiclecount = 0;

					$key2 = "";

					foreach($arr_vehiclecount as $key2=>$val2) {

						$daily_vehiclecount = 0;

						foreach($val2 as $key3=>$val3) {

							if (!isset($arr2[$key2][$key2]["Total Out of Service"])) {
								$arr2[$key2][$key2]["Total Out of Service"] = 0;
							};

							$arr2[$key2][$key2]["Total Out of Service"] = floatval($arr2[$key2][$key2]["Total Out of Service"]) + floatval($val3);

							$super_grand_total_hours_out_of_service = floatval($super_grand_total_hours_out_of_service) + floatval($val3);

							$total_vehiclecount = floatval($total_vehiclecount) + 1;

							$daily_vehiclecount = floatval($daily_vehiclecount) + 1;

						};

						$arr2[$key2][$key2]["Total In Service"] = (floatval(24) * floatval($daily_vehiclecount)) - floatval($arr2[$key2][$key2]["Total Out of Service"]);

						$arr2[$key2][$key2]["Vehicle Count"] = $daily_vehiclecount;
					};

					$arr2[$key2][$key2]["Grand Total Vehicle Count"] = $total_vehiclecount;

					$super_grand_total_hours_in_service = (floatval(24) * floatval($total_vehiclecount)) - floatval($super_grand_total_hours_out_of_service);

					$arr2["total"]["total"]["Grand Total Out of Service"] = $super_grand_total_hours_out_of_service;
					$arr2["total"]["total"]["Grand Total In Service"] = $super_grand_total_hours_in_service;

				};


				if ($report_subtype == "vehicle_totals" || $report_subtype == "route") {

					$arr2 = [];

					$vehiclecount = 0;

					$super_grand_total_hours_out_of_service = 0;

					foreach($arr_vehiclecount as $key2=>$val2) {

						foreach($val2 as $key3=>$val3) {


							$arr2[$key2][$key3]["Date"] = date("m/d/Y", $key2);
							$arr2[$key2][$key3]["Vehicle Name"] = $key3;

							if (!isset($arr2[$key2][$key3]["Total Out of Service"])) {
								$arr2[$key2][$key3]["Total Out of Service"] = 0;
							};
							$arr2[$key2][$key3]["Total Out of Service"] = floatval($arr2[$key2][$key3]["Total Out of Service"]) + floatval($val3);

							$arr2[$key2][$key3]["Total In Service"] = floatval(24) - floatval($arr2[$key2][$key3]["Total Out of Service"]) ;
							$super_grand_total_hours_out_of_service = floatval($super_grand_total_hours_out_of_service) + floatval($arr2[$key2][$key3]["Total Out of Service"]);

							$vehiclecount = floatval($vehiclecount) + 1;


						};
					};

					$super_grand_total_hours_in_service = (floatval(24) * floatval($vehiclecount)) - floatval($super_grand_total_hours_out_of_service);

					$arr2["total"]["total"]["Grand Total Out of Service"] = $super_grand_total_hours_out_of_service;
					$arr2["total"]["total"]["Grand Total In Service"] = $super_grand_total_hours_in_service;

				};

				$arr["arr_results"] = $arr2;
			};

			echo json_encode($arr);
			die();

		};

		//####### total_in_service_hours-> route
		if($report_subtype == "route1") {

			$sql_routefind = "";

			$arrdatetotal = [];

			if (strlen($report_criteria) > 0 && $report_criteria != "all") {
				$sql_routefind = " and tbldriversheetdata.routeid = '$report_criteria' ";
			};

			$arr_times = [];

			$sql1 = "SELECT tblvehicles.id AS vehicleid,
			tbldriversheetdata.id as id,
			tbldriversheetdata.routeid as routeid,
			tbldriversheetdata.driversheetid,
			tbldriversheets.ndate as ndate,
			tbldriversheets.adate as adate,
			tbldriversheetdata.out_of_service_time as out_of_service_time,
			tbldriversheetdata.back_in_service_time as back_in_service_time,
			tbldriversheetdata.non_operational_reason as non_operational_reason
			FROM tblvehicles
			JOIN tbldriversheets on tbldriversheets.vehicleid = tblvehicles.id
			JOIN tbldriversheetdata on tbldriversheetdata.driversheetid = tbldriversheets.id
			$routegroupjoin
			$vehiclegroupjoin
			where 1=1 $sqldaterange
			$billablewhere
			$routegroupwhere
			$vehiclegroupwhere
			$sql_routefind

			and tbldriversheets.active = ?
			ORDER BY tbldriversheets.ndate, tbldriversheetdata.driversheetid";

			//AND tbldriversheetdata.out_of_service_time != ''
			//AND tbldriversheetdata.back_in_service_time != ''

			$stmt1 = $dbconn->prepare($sql1);
			$stmt1->execute(array('true'));
			while ($row1 = $stmt1->fetch()) {

				$id = $row1["id"];

				$ndate = $row1["ndate"];

				//route
				$routeid = $row1["routeid"];
				$sql2 = "select routename from tblroutes where id = ?";
				$stmt2 = $dbconn->prepare($sql2);
				$stmt2->execute(array($routeid));
				while ($row2 = $stmt2->fetch()) {
					$arr_times[$ndate][$id]["Route Name"] = $row2["routename"];
				};


				$vehicleid = $row1["vehicleid"];
				$sql2 = "select name from tblvehicles where id = ?";
				$stmt2 = $dbconn->prepare($sql2);
				$stmt2->execute(array($vehicleid));
				while ($row2 = $stmt2->fetch()) {
					$arr_times[$ndate][$id]["Vehicle Name"] = $row2["name"];
				};

				$arr_times[$ndate][$id]["Date"] = $row1["adate"];

				$hour_with_colon = substr($row1["out_of_service_time"],0,2).':'.substr($row1["out_of_service_time"],2,2);
				$arr_times[$ndate][$id]["Out of Service"] = $hour_with_colon;

				$hour_with_colon = substr($row1["back_in_service_time"],0,2).':'.substr($row1["back_in_service_time"],2,2);
				$arr_times[$ndate][$id]["Back in Service"] = $hour_with_colon;

				$arr_times[$ndate][$id]["Outage Reason"] = $row1["non_operational_reason"];

				$out_of_service_time = $row1["out_of_service_time"];
				if(strlen($out_of_service_time) == 4) {
					$out_of_service_time  = strtotime($out_of_service_time);
				};

				$back_in_service_time = $row1["back_in_service_time"];
				if(strlen($back_in_service_time) == 4) {
					$back_in_service_time  = strtotime($back_in_service_time);
				};

				$difference = abs($back_in_service_time - $out_of_service_time) / 3600;

				$total_hours_out_of_service = floatval($difference);

				$arr_times[$ndate][$id]["Hours Out"] = $total_hours_out_of_service;

				if (!isset($arrdatetotal[$ndate])) {
					$arrdatetotal[$ndate] = [];
				};

				if (!isset($arrdatetotal[$ndate][$vehicleid]["Total Day Hours"])) {
					$arrdatetotal[$ndate][$vehicleid]["Total Day Hours"] = 0;
				};

				if (!isset($arrdatetotal[$ndate][$vehicleid]["Total Out of Service Hours"])) {
					$arrdatetotal[$ndate][$vehicleid]["Total Out of Service Hours"] = 0;
				};

				$arrdatetotal[$ndate][$vehicleid]["Total Out of Service Hours"] = floatval($arrdatetotal[$ndate][$vehicleid]["Total Out of Service Hours"]) + floatval($arr_times[$ndate][$id]["Hours Out"]);
			};

			$grandtotalinforday = 0;
			$grandtotaloutforday = 0;

			foreach ($arrdatetotal as $key=>$val) {

				$totaloutforday = 0;
				$daycount = 0;

				foreach($val as $key2=>$val1) {
					$totaloutforday = $totaloutforday + $val1["Total Out of Service Hours"];
					$daycount = $daycount + 1;
				};

				$totalinforday = ((floatval(24 * $daycount)) - floatval($totaloutforday));

				$grandtotalinforday = floatval($grandtotalinforday) + floatval($totalinforday);
				$grandtotaloutforday = floatval($grandtotaloutforday) + floatval($totaloutforday);
			};

			$arr_times["total"]["total"]["Grand Total Hours Out"] = $grandtotaloutforday;

			$arr_times["report_group_by"] = $report_group_by;
			$arr["arr_results"] = $arr_times;

			if ($report_group_by == "reports_group_by_day") {

				$arr2["report_group_by"] = $report_group_by;

				foreach ($arr_times as $key=>$val) {

					if ($key != "report_group_by") {

						if ($key == "total") {
							continue;
						};

						$arr2[$key] = [];
						foreach ($val as $key1=>$val1) {

							$total_hours_out_of_service = 0;
							$total_hours_in_service = 0;

							$vehiclename = $val1["Vehicle Name"];

							$arr2[$key][$vehiclename]["Date"] = $val1["Date"];

							$arr2[$key][$vehiclename]["Vehicle Name"] = $vehiclename;

							$out_of_service_time = str_replace(":", "", $val1["Out of Service"]);
							if(strlen($out_of_service_time) == 4) {
								$out_of_service_time  = strtotime($out_of_service_time);
							};

							$back_in_service_time = str_replace(":", "", $val1["Back in Service"]);
							if(strlen($back_in_service_time) == 4) {
								$back_in_service_time  = strtotime($back_in_service_time);
							};

							$difference = abs($back_in_service_time - $out_of_service_time) / 3600;

							$total_hours_out_of_service = floatval($total_hours_out_of_service) + floatval($difference);

							if (!isset($arr2[$key][$vehiclename]["Total Out of Service"])) {
								$arr2[$key][$vehiclename]["Total Out of Service"] = 0;
							};

							$arr2[$key][$vehiclename]["Total Out of Service"] = floatval($arr2[$key][$vehiclename]["Total Out of Service"]) + floatval($total_hours_out_of_service);

							$total_hours_in_service = floatval(24) - floatval($arr2[$key][$vehiclename]["Total Out of Service"]);

							unset($arr2[$key][$vehiclename]["Total Out of Service"]);

							$arr2[$key][$vehiclename]["Total In Service"] = $total_hours_in_service;

						};

					};

				};

				$arr2["total"]["total"]["Grand Total In Service"] = $grandtotalinforday;
				//$arr2["total"]["total"]["Grand Total Out of Service"] = $grandtotaloutforday;

				$arr["arr_results"] = $arr2;
			};

			echo json_encode($arr);
			die();

		};

		//####### total_in_service_hours-> vehicle_type
		if($report_subtype == "vehicle_type") {

			$arr_times = [];

			$arrdatetotal = [];

			$total_hours_out_of_service = 0;
			$total_hours_in_service = 0;

			$grand_total_out_of_service = 0;

			$sql1 = "SELECT tblvehicles.id AS vehicleid,
			tbldriversheetdata.id as id,
			tbldriversheetdata.routeid as routeid,
			tbldriversheetdata.driversheetid,
			tbldriversheets.ndate as ndate,
			tbldriversheets.adate as adate,
			tbldriversheetdata.out_of_service_time as out_of_service_time,
			tbldriversheetdata.back_in_service_time as back_in_service_time,
			tbldriversheetdata.non_operational_reason as non_operational_reason
			FROM tblvehicles
			JOIN tbldriversheets on tbldriversheets.vehicleid = tblvehicles.id
			JOIN tbldriversheetdata on tbldriversheetdata.driversheetid = tbldriversheets.id
			$routegroupjoin
			$vehiclegroupjoin
			where 1=1 $sqldaterange
			$billablewhere
			$routegroupwhere
			$vehiclegroupwhere
			and tblvehicles.vehicletypeid = '$report_criteria'

			and tbldriversheets.active = ?
			ORDER BY tbldriversheets.ndate, tbldriversheetdata.driversheetid";

			//AND tbldriversheetdata.out_of_service_time != ''
			//AND tbldriversheetdata.back_in_service_time != ''

			$stmt1 = $dbconn->prepare($sql1);
			$stmt1->execute(array('true'));
			while ($row1 = $stmt1->fetch()) {

				$id = $row1["id"];

				$ndate = $row1["ndate"];

				//route
				$routeid = $row1["routeid"];
				$sql2 = "select routename from tblroutes where id = ?";
				$stmt2 = $dbconn->prepare($sql2);
				$stmt2->execute(array($routeid));
				while ($row2 = $stmt2->fetch()) {
					$arr_times[$ndate][$id]["Route Name"] = $row2["routename"];
				};


				$vehicleid = $row1["vehicleid"];
				$sql2 = "select name from tblvehicles where id = ?";
				$stmt2 = $dbconn->prepare($sql2);
				$stmt2->execute(array($vehicleid));
				while ($row2 = $stmt2->fetch()) {
					$arr_times[$ndate][$id]["Vehicle Name"] = $row2["name"];
				};

				$arr_times[$ndate][$id]["Date"] = $row1["adate"];
				$hour_with_colon = substr($row1["out_of_service_time"],0,2).':'.substr($row1["out_of_service_time"],2,2);
				$arr_times[$ndate][$id]["Out of Service"] = $hour_with_colon;

				$hour_with_colon = substr($row1["back_in_service_time"],0,2).':'.substr($row1["back_in_service_time"],2,2);
				$arr_times[$ndate][$id]["Back in Service"] = $hour_with_colon;
				$arr_times[$ndate][$id]["Outage Reason"] = $row1["non_operational_reason"];

				$out_of_service_time = $row1["out_of_service_time"];
				if(strlen($out_of_service_time) == 4) {
					$out_of_service_time  = strtotime($out_of_service_time);
				};

				$back_in_service_time = $row1["back_in_service_time"];
				if(strlen($back_in_service_time) == 4) {
					$back_in_service_time  = strtotime($back_in_service_time);
				};

				$difference = abs(floatval($back_in_service_time) - floatval($out_of_service_time)) / 3600;

				$total_hours_out_of_service = floatval($difference);

				$arr_times[$ndate][$id]["Hours Out"] = $total_hours_out_of_service;

				if (!isset($arrdatetotal[$ndate])) {
					$arrdatetotal[$ndate] = [];
				};

				if (!isset($arrdatetotal[$ndate][$vehicleid]["Total Day Hours"])) {
					$arrdatetotal[$ndate][$vehicleid]["Total Day Hours"] = 0;
				};

				if (!isset($arrdatetotal[$ndate][$vehicleid]["Total Out of Service Hours"])) {
					$arrdatetotal[$ndate][$vehicleid]["Total Out of Service Hours"] = 0;
				};

				$arrdatetotal[$ndate][$vehicleid]["Total Out of Service Hours"] = floatval($arrdatetotal[$ndate][$vehicleid]["Total Out of Service Hours"]) + floatval($arr_times[$ndate][$id]["Hours Out"]);

			};

			$grandtotalinforday = 0;
			$grandtotaloutforday = 0;

			foreach ($arrdatetotal as $key=>$val) {

				$totaloutforday = 0;
				$daycount = 0;

				foreach($val as $key2=>$val1) {
					$totaloutforday = $totaloutforday + $val1["Total Out of Service Hours"];
					$daycount = $daycount + 1;
				};

				$totalinforday = ((floatval(24 * $daycount)) - floatval($totaloutforday));

				$grandtotalinforday = floatval($grandtotalinforday) + floatval($totalinforday);
				$grandtotaloutforday = floatval($grandtotaloutforday) + floatval($totaloutforday);
			};

			$arr_times["total"]["total"]["Grand Total Hours Out"] = $grandtotaloutforday;


			$arr_times["report_group_by"] = $report_group_by;
			$arr["arr_results"] = $arr_times;

			if ($report_group_by == "reports_group_by_day") {

				$arr2["report_group_by"] = $report_group_by;

				foreach ($arr_times as $key=>$val) {

					if ($key == "total") {
						continue;
					};

					if ($key != "report_group_by") {

						$arr2[$key] = [];
						foreach ($val as $key1=>$val1) {

							$total_hours_out_of_service = 0;
							$total_hours_in_service = 0;

							$vehiclename = $val1["Vehicle Name"];

							$arr2[$key][$vehiclename]["Date"] = $val1["Date"];

							$arr2[$key][$vehiclename]["Vehicle Name"] = $vehiclename;

							$out_of_service_time = str_replace(":", "", $val1["Out of Service"]);
							if(strlen($out_of_service_time) == 4) {
								$out_of_service_time  = strtotime($out_of_service_time);
							};

							$back_in_service_time = str_replace(":", "", $val1["Back in Service"]);
							if(strlen($back_in_service_time) == 4) {
								$back_in_service_time  = strtotime($back_in_service_time);
							};

							$difference = abs(floatval($back_in_service_time) - floatval($out_of_service_time)) / 3600;

							$total_hours_out_of_service = floatval($total_hours_out_of_service) + floatval($difference);

							if (!isset($arr2[$key][$vehiclename]["Total Out of Service"])) {
								$arr2[$key][$vehiclename]["Total Out of Service"] = 0;
							};

							$arr2[$key][$vehiclename]["Total Out of Service"] = floatval($arr2[$key][$vehiclename]["Total Out of Service"]) + floatval($total_hours_out_of_service);

							$total_hours_in_service = floatval(24) - floatval($arr2[$key][$vehiclename]["Total Out of Service"]);

							$arr2[$key][$vehiclename]["Total In Service"] = $total_hours_in_service;

						};

					};

				};

				$arr2["total"]["total"]["Grand Total In Service"] = $grandtotalinforday;
				$arr2["total"]["total"]["Grand Total Out of Service"] = $grandtotaloutforday;

				$arr["arr_results"] = $arr2;
			};

			echo json_encode($arr);
			die();

		};

	};

	//############  total_accidents
	if($report_type == "total_accidents") {

		$sqldaterange = " and (tblaccidents.ndate_of_accident >= '$nfromdate' and tblaccidents.ndate_of_accident <= '$ntodate') ";

		$routegroupjoin = "";
		$routegroupwhere = "";
		if($route_group != "all") {
			$routegroupjoin = " JOIN tblroutegroups on tblaccidents.routeid = tblroutegroups.routeid ";
			$routegroupwhere = " AND tblroutegroups.groupname = '$route_group' ";
		};

		$vehiclegroupjoin = "";
		$vehiclegroupwhere = "";
		if($vehicle_group != "all") {
			$vehiclegroupjoin = " JOIN tblvehiclegroups on tblaccidents.vehiclename = tblvehiclegroups.vehicleid ";
			$vehiclegroupwhere = " AND tblvehiclegroups.groupname = '$vehicle_group' ";
		};

		$arr_accidents = [];

		$reportcriteriasql = "";

		error_log($report_criteria);

		if ($report_subtype == "contributing_factors") {
			$reportcriteriasql = " and tblaccidents." . $report_criteria . " = 'true' ";
		};
		if ($report_subtype == "vehicle_number") {
			$reportcriteriasql = " and tblaccidents.vehiclename = '$report_criteria' ";
		};
		if ($report_subtype == "driver") {
			$reportcriteriasql = " and tblaccidents.employee_driver = '$report_criteria' ";
		};


		$sql1 = "SELECT tblaccidents.id as id,
		tblaccidents.date_of_accident as date_of_accident,
		tblaccidents.ndate_of_accident as ndate_of_accident,
		tblaccidents.time_of_accident as time_of_accident,
		tblaccidents.employee_driver as employeeid,
		tblaccidents.vehiclename as vehicleid,
		tblaccidents.contributing_factors_backing as contributing_factors_backing,
		tblaccidents.contributing_factors_turning as contributing_factors_turning,
		tblaccidents.contributing_factors_road_conditions as contributing_factors_road_conditions,
		tblaccidents.contributing_factors_speed as contributing_factors_speed,
		tblaccidents.contributing_factors_mechanical as contributing_factors_mechanical,
		tblaccidents.contributing_factors_spacing as contributing_factors_spacing,
		tblaccidents.contributing_factors_other as contributing_factors_other,
		tblaccidents.contributing_factors_fixed_object as contributing_factors_fixed_object,
		tblaccidents.contributing_factors_moving_vehicle as contributing_factors_moving_vehicle,
		tblaccidents.contributing_factors_parked_vehicle as contributing_factors_parked_vehicle,
		tblaccidents.contributing_factors_head_on as contributing_factors_head_on,
		tblaccidents.contributing_factors_pedestrian as contributing_factors_pedestrian,
		tblaccidents.routeid as routeid,
		tblaccidents.vehicle_damaged as vehicle_damaged,
		tblaccidents.vehicle_drivable as vehicle_drivable,
		tblaccidents.employee_driver_drug_test as employee_driver_drug_test,
		tblaccidents.photos_taken as photos_taken,
		tblaccidents.other_documents as other_documents
		FROM tblaccidents
		$routegroupjoin
		$vehiclegroupjoin
		where 1=1 $sqldaterange
		$routegroupwhere
		$vehiclegroupwhere
		$reportcriteriasql
		and tblaccidents.active = 'true'
		ORDER BY tblaccidents.ndate_of_accident DESC";

		// OR tblaccidents.active <> 'true'

		$stmt1 = $dbconn->prepare($sql1);
		$stmt1->execute();
		while ($row1 = $stmt1->fetch()) {

			$id = $row1["id"];

			$date_of_accident = $row1["date_of_accident"];
			$ndate = $row1["ndate_of_accident"];

			$arr_accidents[$ndate][$id]["Date and Time"] = $row1["date_of_accident"] . '<span style="visibility:hidden;">.</span>' . $row1["time_of_accident"];

			$arr_accidents[$ndate][$id]["Route Name"] = "";
			//route
			$routeid = $row1["routeid"];
			$sql2 = "select routename from tblroutes where id = ?";
			$stmt2 = $dbconn->prepare($sql2);
			$stmt2->execute(array($routeid));
			while ($row2 = $stmt2->fetch()) {
				$routename = str_replace(' ', '<span style="visibility:hidden;">.</span>', $row2["routename"]);
				$arr_accidents[$ndate][$id]["Route Name"] = $routename;
			};

			$arr_accidents[$ndate][$id]["Vehicle Name"] = "";
			$vehicleid = $row1["vehicleid"];
			$sql2 = "select name from tblvehicles where id = ?";
			$stmt2 = $dbconn->prepare($sql2);
			$stmt2->execute(array($vehicleid));
			while ($row2 = $stmt2->fetch()) {
				$arr_accidents[$ndate][$id]["Vehicle Name"] = $row2["name"];
			};

			$arr_accidents[$ndate][$id]["Driver"] = "";
			$employeeid = $row1["employeeid"];
			$sql2 = "select firstname, lastname from tblusers where id = ?";
			$stmt2 = $dbconn->prepare($sql2);
			$stmt2->execute(array($employeeid));
			while ($row2 = $stmt2->fetch()) {
				$arr_accidents[$ndate][$id]["Driver"] = $row2["firstname"] . '<span style="visibility:hidden;">.</span>' . $row2["lastname"];
			};

			$arr_accidents[$ndate][$id]["Vehicle Damaged"] = $row1["vehicle_damaged"];
			$arr_accidents[$ndate][$id]["Vehicle Drivable"] = $row1["vehicle_drivable"];
			$arr_accidents[$ndate][$id]["Driver Drug Test"] = $row1["employee_driver_drug_test"];


			$photos_taken = $row1["photos_taken"];
			if ($photos_taken == "true") {
				$arr_accidents[$ndate][$id]["Photos"] = "Yes";
			}
			else
			{
				$arr_accidents[$ndate][$id]["Photos"] = "No";
			};

			$other_documents = $row1["other_documents"];
			if ($other_documents == "true") {
				$arr_accidents[$ndate][$id]["Linked Documents"] = "Yes";
			}
			else
			{
				$arr_accidents[$ndate][$id]["Linked Documents"] = "No";
			};

			$contributing_factors = "";

			$contributing_factors_backing = $row1["contributing_factors_backing"];
			if ($contributing_factors_backing == "true") {
				$contributing_factors = $contributing_factors . "Backing, ";
			};

			$contributing_factors_turning = $row1["contributing_factors_turning"];
			if ($contributing_factors_turning == "true") {
				$contributing_factors = $contributing_factors . "Turning, ";
			};

			$contributing_factors_road_conditions = $row1["contributing_factors_road_conditions"];
			if ($contributing_factors_road_conditions == "true") {
				$contributing_factors = $contributing_factors . 'Road<span style="visibility:hidden;">.</span>Conditions, ';
			};

			$contributing_factors_speed = $row1["contributing_factors_speed"];
			if ($contributing_factors_speed == "true") {
				$contributing_factors = $contributing_factors . "Speed, ";
			};

			$contributing_factors_mechanical = $row1["contributing_factors_mechanical"];
			if ($contributing_factors_mechanical == "true") {
				$contributing_factors = $contributing_factors . "Mechanical, ";
			};

			$contributing_factors_spacing = $row1["contributing_factors_spacing"];
			if ($contributing_factors_spacing == "true") {
				$contributing_factors = $contributing_factors . "Spacing, ";
			};

			$contributing_factors_fixed_object = $row1["contributing_factors_fixed_object"];
			if ($contributing_factors_fixed_object == "true") {
				$contributing_factors = $contributing_factors . 'Fixed<span style="visibility:hidden;">.</span>Object, ';
			};

			$contributing_factors_moving_vehicle = $row1["contributing_factors_moving_vehicle"];
			if ($contributing_factors_moving_vehicle == "true") {
				$contributing_factors = $contributing_factors . 'Moving<span style="visibility:hidden;">.</span>Vehicle, ';
			};

			$contributing_factors_parked_vehicle = $row1["contributing_factors_parked_vehicle"];
			if ($contributing_factors_parked_vehicle == "true") {
				$contributing_factors = $contributing_factors . 'Parked<span style="visibility:hidden;">.</span>Vehicle, ';
			};

			$contributing_factors_head_on = $row1["contributing_factors_head_on"];
			if ($contributing_factors_head_on == "true") {
				$contributing_factors = $contributing_factors . 'Head<span style="visibility:hidden;">.</span>On, ';
			};

			$contributing_factors_pedestrian = $row1["contributing_factors_pedestrian"];
			if ($contributing_factors_pedestrian == "true") {
				$contributing_factors = $contributing_factors . "Pedestrian, ";
			};

			$contributing_factors_other = $row1["contributing_factors_other"];
			if ($contributing_factors_other == "true") {
				$contributing_factors = $contributing_factors . "Other, ";
			};

			$contributing_factors = rtrim($contributing_factors, ", ");

			$arr_accidents[$ndate][$id]["Contributing Factors"] = $contributing_factors;

			$sql2 = "SELECT COUNT(*) AS count
			FROM tblaccidentsotherdrivers where accidentid = ?";
			$stmt2 = $dbconn->prepare($sql2);
			$stmt2->execute(array($id));
			while ($row2 = $stmt2->fetch()) {
				$arr_accidents[$ndate][$id]["Other Drivers"] = $row2["count"];
			};

			$sql2 = "SELECT COUNT(*) AS count
			FROM tblaccidentsinjuries where accidentid = ?";
			$stmt2 = $dbconn->prepare($sql2);
			$stmt2->execute(array($id));
			while ($row2 = $stmt2->fetch()) {
				$arr_accidents[$ndate][$id]["Injuries"] = $row2["count"];
			};

			$sql2 = "SELECT COUNT(*) AS count
			FROM tblaccidentswitnesses where accidentid = ?";
			$stmt2 = $dbconn->prepare($sql2);
			$stmt2->execute(array($id));
			while ($row2 = $stmt2->fetch()) {
				$arr_accidents[$ndate][$id]["Witnesses"] = $row2["count"];
			};

		};


		$arr_accidents["report_group_by"] = $report_group_by;
		$arr["arr_results"] = $arr_accidents;


		echo json_encode($arr);
		die();

	};


	$breaktype = "";

	if($report_type == "total_fueling_hours") {
		$breaktype = "Fueling";
	};
	if($report_type == "total_onbreak_hours") {
		$breaktype = "On Break";
	};
	if($report_type == "total_washing_hours") {
		$breaktype = "Washing";
	};
	if($report_type == "total_parked_hours") {
		$breaktype = "Parked";
	};
	if($report_type == "total_maintenance_hours") {
		$breaktype = "Maintenance";
	};

	if (strlen($breaktype) > 0) {

		$reportcriteriawhere = "";

		if($report_subtype == "vehicle_number") {
			$reportcriteriawhere = "and tbldriversheets.vehicleid = '$report_criteria'";
		};

		$arr_times = [];

		$sql1 = "SELECT tblvehicles.id AS vehicleid,
		tbldriversheetdata.id as id,
		tbldriversheetdata.routeid as routeid,
		tbldriversheetdata.driversheetid,
		tbldriversheets.ndate as ndate,
		tbldriversheets.adate as adate,
		tbldriversheetdata.out_of_service_time as out_of_service_time,
		tbldriversheetdata.back_in_service_time as back_in_service_time,
		tbldriversheetdata.non_operational_reason as non_operational_reason
		FROM tblvehicles
		JOIN tbldriversheets on tbldriversheets.vehicleid = tblvehicles.id
		JOIN tbldriversheetdata on tbldriversheetdata.driversheetid = tbldriversheets.id
		$routegroupjoin
		$vehiclegroupjoin
		where 1=1 $sqldaterange
		$billablewhere
		$reportcriteriawhere
		$routegroupwhere
		$vehiclegroupwhere
		AND tbldriversheetdata.out_of_service_time != ''
		AND tbldriversheetdata.back_in_service_time != ''
		and tbldriversheets.active = ?
		and tbldriversheetdata.non_operational_reason = ?
		ORDER BY tbldriversheets.ndate, tbldriversheetdata.driversheetid";

		$stmt1 = $dbconn->prepare($sql1);
		$stmt1->execute(array('true', $breaktype));
		while ($row1 = $stmt1->fetch()) {

			$id = $row1["id"];

			$ndate = $row1["ndate"];

			//route
			$routeid = $row1["routeid"];
			$sql2 = "select routename from tblroutes where id = ?";
			$stmt2 = $dbconn->prepare($sql2);
			$stmt2->execute(array($routeid));
			while ($row2 = $stmt2->fetch()) {
				$arr_times[$ndate][$id]["Route Name"] = $row2["routename"];
			};


			$vehicleid = $row1["vehicleid"];
			$sql2 = "select name from tblvehicles where id = ?";
			$stmt2 = $dbconn->prepare($sql2);
			$stmt2->execute(array($vehicleid));
			while ($row2 = $stmt2->fetch()) {
				$arr_times[$ndate][$id]["Vehicle Name"] = $row2["name"];
			};

			$arr_times[$ndate][$id]["Date"] = $row1["adate"];

			$hour_with_colon = substr($row1["out_of_service_time"],0,2).':'.substr($row1["out_of_service_time"],2,2);
			$arr_times[$ndate][$id]["Out of Service"] = $hour_with_colon;

			$hour_with_colon = substr($row1["back_in_service_time"],0,2).':'.substr($row1["back_in_service_time"],2,2);
			$arr_times[$ndate][$id]["Back in Service"] = $hour_with_colon;

			$arr_times[$ndate][$id]["Outage Reason"] = $row1["non_operational_reason"];


			$out_of_service_time = $row1["out_of_service_time"];
			if(strlen($out_of_service_time) == 4) {
				$out_of_service_time  = strtotime($out_of_service_time);
			};

			$back_in_service_time = $row1["back_in_service_time"];
			if(strlen($back_in_service_time) == 4) {
				$back_in_service_time  = strtotime($back_in_service_time);
			};

			$difference = abs($back_in_service_time - $out_of_service_time) / 3600;

			$total_hours_out_of_service = floatval($difference);

			$arr_times[$ndate][$id]["Hours Out"] = $total_hours_out_of_service;

			if (!isset($arrdatetotal[$ndate])) {
				$arrdatetotal[$ndate] = [];
			};

			if (!isset($arrdatetotal[$ndate][$vehicleid]["Total Day Hours"])) {
				$arrdatetotal[$ndate][$vehicleid]["Total Day Hours"] = 0;
			};

			if (!isset($arrdatetotal[$ndate][$vehicleid]["Total Out of Service Hours"])) {
				$arrdatetotal[$ndate][$vehicleid]["Total Out of Service Hours"] = 0;
			};

			$arrdatetotal[$ndate][$vehicleid]["Total Out of Service Hours"] = floatval($arrdatetotal[$ndate][$vehicleid]["Total Out of Service Hours"]) + floatval($arr_times[$ndate][$id]["Hours Out"]);

		};

		$grandtotalinforday = 0;
		$grandtotaloutforday = 0;

		foreach ($arrdatetotal as $key=>$val) {

			$totaloutforday = 0;
			$daycount = 0;

			foreach($val as $key2=>$val1) {
				$totaloutforday = $totaloutforday + $val1["Total Out of Service Hours"];
				$daycount = $daycount + 1;
			};

			$totalinforday = ((floatval(24 * $daycount)) - floatval($totaloutforday));

			$grandtotalinforday = floatval($grandtotalinforday) + floatval($totalinforday);
			$grandtotaloutforday = floatval($grandtotaloutforday) + floatval($totaloutforday);
		};

		$arr_times["total"]["total"]["Grand Total Hours Out"] = $grandtotaloutforday;

		$arr_times["report_group_by"] = $report_group_by;
		$arr["arr_results"] = $arr_times;

		if ($report_group_by == "reports_group_by_day") {

			$arr2["report_group_by"] = $report_group_by;

			foreach ($arr_times as $key=>$val) {

				if ($key != "report_group_by") {

					$arr2[$key] = [];
					foreach ($val as $key1=>$val1) {

						if ($key == "total") {
							continue;
						};

						$total_hours_out_of_service = 0;
						$total_hours_in_service = 0;

						$vehiclename = $val1["Vehicle Name"];

						$arr2[$key][$vehiclename]["Date"] = $val1["Date"];

						$arr2[$key][$vehiclename]["Vehicle Name"] = $vehiclename;

						$out_of_service_time = str_replace(":", "", $val1["Out of Service"]);
						if(strlen($out_of_service_time) == 4) {
							$out_of_service_time  = strtotime($out_of_service_time);
						};

						$back_in_service_time = str_replace(":", "", $val1["Back in Service"]);
						if(strlen($back_in_service_time) == 4) {
							$back_in_service_time  = strtotime($back_in_service_time);
						};

						//$difference = round(abs($back_in_service_time - $out_of_service_time) / 3600, 2);
						$difference = abs($back_in_service_time - $out_of_service_time) / 3600;

						$total_hours_out_of_service = floatval($total_hours_out_of_service) + floatval($difference);

						if (!isset($arr2[$key][$vehiclename]["Total Out of Service"])) {
							$arr2[$key][$vehiclename]["Total Out of Service"] = 0;
						};

						$arr2[$key][$vehiclename]["Total Out of Service"] = floatval($arr2[$key][$vehiclename]["Total Out of Service"]) + floatval($total_hours_out_of_service);

						$total_hours_in_service = floatval(24) - floatval($arr2[$key][$vehiclename]["Total Out of Service"]);

						$arr2[$key][$vehiclename]["Total In Service"] = $total_hours_in_service;

					};

				};

			};

			$arr2["total"]["total"]["Grand Total In Service"] = $grandtotalinforday;
			$arr2["total"]["total"]["Grand Total Out of Service"] = $grandtotaloutforday;

			$arr["arr_results"] = $arr2;
		};

		echo json_encode($arr);
		die();

	};

	//############  total_fluids
	if($report_type == "total_fluids") {

		$gb = "";

		$sqldaterange = " and (tblfluids.ndatetime >= '$nfromdate' and tblfluids.ndatetime <= '$ntodate') ";

		$vehiclegroupjoin = "";
		$vehiclegroupwhere = "";
		if($vehicle_group != "all") {
			$vehiclegroupjoin = " JOIN tblvehiclegroups on tblfluids.vehicleid = tblvehiclegroups.vehicleid ";
			$vehiclegroupwhere = " AND tblvehiclegroups.groupname = '$vehicle_group' ";
		};

		//####### total_fluids-> all
		if($report_subtype == "all") {

			$arr_fluids = [];

			$grandtotalcng = 0;
			$grandtotaloil = 0;
			$grandtotalcoolant = 0;
			$grandtotalwasherfluid = 0;

			$sqlreporttype = "select tblfluids.id as entryid,
			tblfluids.adatetime as adatetime,
			tblfluids.ndatetime as ndatetime,
			tblfluids.shift as shift,
			tblfluids.mileage as mileage,
			tblfluids.hours as hours,
			tblfluids.cng as cng,
			tblfluids.oil as oil,
			tblfluids.coolant as coolant,
			tblfluids.washerfluid as washerfluid,
			tblvehicles.name as vehiclename,
			tblusers.firstname as firstname,
			tblusers.lastname as lastname
			from tblfluids
			join tblvehicles on tblfluids.vehicleid = tblvehicles.id
			join tblusers on tblfluids.fuelerid = tblusers.id
			$vehiclegroupjoin
			where 1=1 $sqldaterange
			$vehiclegroupwhere
			and tblfluids.active = 'true'
			$gb
			order by tblfluids.ndatetime asc";

			$stmt = $dbconn->prepare($sqlreporttype);
			$stmt->execute();
			while ($row = $stmt->fetch()) {

				$ndatetime = $row["ndatetime"];

				$entryid = $row["entryid"];

				$fullname = $row["firstname"] . " " . $row["lastname"];
				$arr_fluids[$ndatetime][$entryid]["Vehicle"] = $row["vehiclename"];
				$arr_fluids[$ndatetime][$entryid]["Date"] = $row["adatetime"];
				$arr_fluids[$ndatetime][$entryid]["Shift"] = $row["shift"];
				$arr_fluids[$ndatetime][$entryid]["Mileage"] = $row["mileage"];
				$arr_fluids[$ndatetime][$entryid]["Hours"] = $row["hours"];
				$arr_fluids[$ndatetime][$entryid]["CNG"] = $row["cng"];
				$grandtotalcng = floatval($grandtotalcng) + floatval( $row["cng"]);
				$arr_fluids[$ndatetime][$entryid]["Oil"] = $row["oil"];
				$grandtotaloil = floatval($grandtotaloil) + floatval( $row["oil"]);
				$arr_fluids[$ndatetime][$entryid]["Coolant"] = $row["coolant"];
				$grandtotalcoolant = floatval($grandtotalcoolant) + floatval( $row["coolant"]);
				$arr_fluids[$ndatetime][$entryid]["Washer Fluid"] = $row["washerfluid"];
				$grandtotalwasherfluid = floatval($grandtotalwasherfluid) + floatval( $row["washerfluid"]);

			};

			$arr_fluids["total"]["total"]["Grand Total CNG"] = $grandtotalcng;
			$arr_fluids["total"]["total"]["Grand Total Oil"] = $grandtotaloil;
			$arr_fluids["total"]["total"]["Grand Total Coolant"] = $grandtotalcoolant;
			$arr_fluids["total"]["total"]["Grand Total Washer Fluid"] = $grandtotalwasherfluid;

			$arr_fluids["report_group_by"] = $report_group_by;

			$arr["arr_results"] = $arr_fluids;

			if ($report_group_by == "reports_group_by_day") {

				$arr_fluids_grouped = [];
				$arr["arr_results"] = [];


				foreach($arr_fluids as $key=>$val) {

					if ($key == "total") {
						continue;
					};

					if ($key != "report_group_by") {

						$ndatetime = $key;
						$totalcng = 0;
						$totaloil = 0;
						$totalcoolant = 0;
						$totalwasherfluid = 0;

						foreach($val as $key1=>$val1) {

							$totalcng = floatval($totalcng) + floatval($val1["CNG"]);
							$totaloil = floatval($totaloil) + floatval($val1["Oil"]);
							$totalcoolant = floatval($totalcoolant) + floatval($val1["Coolant"]);
							$totalwasherfluid = floatval($totalwasherfluid) + floatval($val1["Washer Fluid"]);


						};

						$arr_fluids_grouped[$ndatetime][$ndatetime]["Date"] = $val1["Date"];
						$arr_fluids_grouped[$ndatetime][$ndatetime]["CNG"] = $totalcng;
						$arr_fluids_grouped[$ndatetime][$ndatetime]["Oil"] = $totaloil;
						$arr_fluids_grouped[$ndatetime][$ndatetime]["Coolant"] = $totalcoolant;
						$arr_fluids_grouped[$ndatetime][$ndatetime]["Washer Fluid"] = $totalwasherfluid;
					};

				};

				$arr_fluids_grouped["total"]["total"]["Grand Total CNG"] = $grandtotalcng;
				$arr_fluids_grouped["total"]["total"]["Grand Total Oil"] = $grandtotaloil;
				$arr_fluids_grouped["total"]["total"]["Grand Total Coolant"] = $grandtotalcoolant;
				$arr_fluids_grouped["total"]["total"]["Grand Total Washer Fluid"] = $grandtotalwasherfluid;

				$arr_fluids_grouped["report_group_by"] = $report_group_by;
				$arr["arr_results"] = $arr_fluids_grouped;
			};


		};

		//####### total_fluids-> vehicle_number
		if($report_subtype == "vehicle_number") {

			$arr_fluids = [];

			//if ($report_group_by == "reports_group_by_day") {
			//	$gb = " group by tblfluids.ndatetime ";
			//};

			$sqlreporttype = "select tblfluids.id as entryid,
			tblfluids.adatetime as adatetime,
			tblfluids.ndatetime as ndatetime,
			tblfluids.shift as shift,
			tblfluids.mileage as mileage,
			tblfluids.hours as hours,
			tblfluids.cng as cng,
			tblfluids.oil as oil,
			tblfluids.coolant as coolant,
			tblfluids.washerfluid as washerfluid,
			tblfluids.vehicleid as vehicleid,
			tblvehicles.name as vehiclename,
			tblusers.firstname as firstname,
			tblusers.lastname as lastname
			from tblfluids
			join tblvehicles on tblfluids.vehicleid = tblvehicles.id
			join tblusers on tblfluids.fuelerid = tblusers.id
			$routegroupjoin
			$vehiclegroupjoin
			where tblfluids.vehicleid = '$report_criteria' $sqldaterange
			$routegroupwhere
			$vehiclegroupwhere
			and tblfluids.active = 'true'
			$gb
			order by tblfluids.ndatetime asc";

			$stmt = $dbconn->prepare($sqlreporttype);
			$stmt->execute();
			while ($row = $stmt->fetch()) {

				$ndatetime = $row["ndatetime"];
				$vehicleid = $row["vehicleid"];

				$entryid = $row["entryid"];

				$fullname = $row["firstname"] . " " . $row["lastname"];

				$fullname = $row["firstname"] . " " . $row["lastname"];
				$arr_fluids[$ndatetime][$entryid]["Fueler"] = $fullname;
				$arr_fluids[$ndatetime][$entryid]["Vehicle"] = $row["vehiclename"];
				$arr_fluids[$ndatetime][$entryid]["vehicleid"] = $vehicleid;
				$arr_fluids[$ndatetime][$entryid]["Date"] = $row["adatetime"];
				$arr_fluids[$ndatetime][$entryid]["Shift"] = $row["shift"];
				$arr_fluids[$ndatetime][$entryid]["Mileage"] = $row["mileage"];
				$arr_fluids[$ndatetime][$entryid]["Hours"] = $row["hours"];
				$arr_fluids[$ndatetime][$entryid]["CNG"] = $row["cng"];
				$arr_fluids[$ndatetime][$entryid]["Oil"] = $row["oil"];
				$arr_fluids[$ndatetime][$entryid]["Coolant"] = $row["coolant"];
				$arr_fluids[$ndatetime][$entryid]["Washer Fluid"] = $row["washerfluid"];

			};

			$arr_fluids["report_group_by"] = $report_group_by;

			$arr["arr_results"] = $arr_fluids;

			if ($report_group_by == "reports_group_by_day") {

				$arr_fluids_grouped = [];
				$arr["arr_results"] = [];

				foreach($arr_fluids as $key=>$val) {

					$totalcng= 0;
					$totaloil= 0;
					$totalcoolant= 0;
					$totalwasherfluid= 0;

					if ($key != "report_group_by") {

						$ndatetime = $key;

						foreach($val as $key1=>$val1) {

							$totalcng = floatval($totalcng) + floatval($val1["CNG"]);
							$totaloil = floatval($totaloil) + floatval($val1["Oil"]);
							$totalcoolant = floatval($totalcoolant) + floatval($val1["Coolant"]);
							$totalwasherfluid = floatval($totalwasherfluid) + floatval($val1["Washer Fluid"]);


						};

						$arr_fluids_grouped[$ndatetime][$ndatetime]["Vehicle"] = $val1["Vehicle"];
						$arr_fluids_grouped[$ndatetime][$ndatetime]["Date"] = $val1["Date"];
						$arr_fluids_grouped[$ndatetime][$ndatetime]["CNG"] = $totalcng;
						$arr_fluids_grouped[$ndatetime][$ndatetime]["Oil"] = $totaloil;
						$arr_fluids_grouped[$ndatetime][$ndatetime]["Coolant"] = $totalcoolant;
						$arr_fluids_grouped[$ndatetime][$ndatetime]["Washer Fluid"] = $totalwasherfluid;
					};

				};

				$arr_fluids_grouped["report_group_by"] = $report_group_by;
				$arr["arr_results"] = $arr_fluids_grouped;
			};

		};

		//####### total_fluids-> specific_day
		if($report_subtype == "specific_day") {
			$arr_fluids = [];

			//if ($report_group_by == "reports_group_by_day") {
			//	$gb = " group by tblfluids.ndatetime ";
			//};

			$sqlreporttype = "select tblfluids.id as entryid,
			tblfluids.adatetime as adatetime,
			tblfluids.ndatetime as ndatetime,
			tblfluids.shift as shift,
			tblfluids.mileage as mileage,
			tblfluids.hours as hours,
			tblfluids.cng as cng,
			tblfluids.oil as oil,
			tblfluids.coolant as coolant,
			tblfluids.washerfluid as washerfluid,
			tblvehicles.name as vehiclename,
			tblusers.firstname as firstname,
			tblusers.lastname as lastname
			from tblfluids
			join tblvehicles on tblfluids.vehicleid = tblvehicles.id
			join tblusers on tblfluids.fuelerid = tblusers.id
			where dayname(FROM_UNIXTIME(tblfluids.ndatetime)) = '$report_criteria' $sqldaterange
			and tblfluids.active = 'true'
			$gb
			order by tblfluids.ndatetime asc";

			$stmt = $dbconn->prepare($sqlreporttype);
			$stmt->execute();
			while ($row = $stmt->fetch()) {

				$ndatetime = $row["ndatetime"];

				$entryid = $row["entryid"];

				$fullname = $row["firstname"] . " " . $row["lastname"];

				$fullname = $row["firstname"] . " " . $row["lastname"];
				$arr_fluids[$ndatetime][$entryid]["Fueler"] = $fullname;
				$arr_fluids[$ndatetime][$entryid]["Vehicle"] = $row["vehiclename"];
				$arr_fluids[$ndatetime][$entryid]["Date"] = $row["adatetime"];
				$arr_fluids[$ndatetime][$entryid]["Shift"] = $row["shift"];
				$arr_fluids[$ndatetime][$entryid]["Mileage"] = $row["mileage"];
				$arr_fluids[$ndatetime][$entryid]["Hours"] = $row["hours"];
				$arr_fluids[$ndatetime][$entryid]["CNG"] = $row["cng"];
				$arr_fluids[$ndatetime][$entryid]["Oil"] = $row["oil"];
				$arr_fluids[$ndatetime][$entryid]["Coolant"] = $row["coolant"];
				$arr_fluids[$ndatetime][$entryid]["Washer Fluid"] = $row["washerfluid"];

			};

			$arr_fluids["report_group_by"] = $report_group_by;

			$arr["arr_results"] = $arr_fluids;

			if ($report_group_by == "reports_group_by_day") {

				$arr_fluids_grouped = [];
				$arr["arr_results"] = [];

				foreach($arr_fluids as $key=>$val) {

					$totalcng= 0;
					$totaloil= 0;
					$totalcoolant= 0;
					$totalwasherfluid= 0;

					if ($key != "report_group_by") {

						$ndatetime = $key;

						foreach($val as $key1=>$val1) {

							$totalcng = floatval($totalcng) + floatval($val1["CNG"]);
							$totaloil = floatval($totaloil) + floatval($val1["Oil"]);
							$totalcoolant = floatval($totalcoolant) + floatval($val1["Coolant"]);
							$totalwasherfluid = floatval($totalwasherfluid) + floatval($val1["Washer Fluid"]);


						};

						$arr_fluids_grouped[$ndatetime][$ndatetime]["Vehicle"] = $val1["Vehicle"];
						$arr_fluids_grouped[$ndatetime][$ndatetime]["Date"] = $val1["Date"];
						$arr_fluids_grouped[$ndatetime][$ndatetime]["CNG"] = $totalcng;
						$arr_fluids_grouped[$ndatetime][$ndatetime]["Oil"] = $totaloil;
						$arr_fluids_grouped[$ndatetime][$ndatetime]["Coolant"] = $totalcoolant;
						$arr_fluids_grouped[$ndatetime][$ndatetime]["Washer Fluid"] = $totalwasherfluid;
					};

				};

				$arr_fluids_grouped["report_group_by"] = $report_group_by;
				$arr["arr_results"] = $arr_fluids_grouped;
			};

		};


	};

	//############  total_comments
	if($report_type == "total_comments") {

		$sqldaterange = " and (tblcomments.ndatetime >= '$nfromdate' and tblcomments.ndatetime <= '$ntodate') ";

		$routegroupjoin = "";
		$routegroupwhere = "";
		if($route_group != "all") {
			$routegroupjoin = " JOIN tblroutegroups on tblcomments.routeid = tblroutegroups.routeid ";
			$routegroupwhere = " AND tblroutegroups.groupname = '$route_group' ";
		};

		$vehiclegroupjoin = "";
		$vehiclegroupwhere = "";
		if($vehicle_group != "all") {
			$vehiclegroupjoin = " JOIN tblvehiclegroups on tblcomments.vehicleid = tblvehiclegroups.vehicleid ";
			$vehiclegroupwhere = " AND tblvehiclegroups.groupname = '$vehicle_group' ";
		};

		$commentswhere = "";

		if($report_subtype == "route") {
			if (strlen($report_criteria) > 0 && $report_criteria != "all") {
				$commentswhere = " and tblcomments.routeid = '$report_criteria' ";
			};
		};

		if($report_subtype == "comment_type") {
			if (strlen($report_criteria) > 0 && $report_criteria != "all") {
				$commentswhere = " and tblcomments.commenttype = '$report_criteria' ";
			};
		};

		$arr_results = [];

		$sql = "SELECT tblcomments.id as commentid,
		tblcomments.routeid as routeid,
		tblcomments.vehicleid as vehicleid,
		tblcomments.userid as userid,
		tblcomments.adatetime as adatetime,
		tblcomments.ndatetime as ndatetime,
		tblcomments.customername as customername,
		tblcomments.commenttype as commenttype,
		tblcomments.commenttext as commenttext,
		tblcomments.resolutiontext as resolutiontext
		FROM tblcomments
		$routegroupjoin
		$vehiclegroupjoin
		where 1=1 $sqldaterange
		$commentswhere
		$routegroupwhere
		$vehiclegroupwhere
		and tblcomments.active = 'true'
		order by tblcomments.ndatetime asc";

		$stmt = $dbconn->prepare($sql);
		$stmt->execute();
		while ($row = $stmt->fetch()) {

			$username = "";
			$sql1 = "SELECT CONCAT(tblusers.firstname, ' ', tblusers.lastname)
			AS username FROM tblusers WHERE id = ?";
			$stmt1 = $dbconn->prepare($sql1);
			$stmt1->execute(array($row["userid"]));
			while ($row1 = $stmt1->fetch()) {
				$username = $row1["username"];
			};

			$routename = "";
			$sql1 = "SELECT tblroutes.routename
			AS routename
			FROM tblroutes
			WHERE id = ?";
			$stmt1 = $dbconn->prepare($sql1);
			$stmt1->execute(array($row["routeid"]));
			while ($row1 = $stmt1->fetch()) {
				$routename = $row1["routename"];
			};

			$vehiclename = "";
			$sql1 = "SELECT tblvehicles.name
			AS vehiclename
			FROM tblvehicles
			WHERE id = ?";
			$stmt1 = $dbconn->prepare($sql1);
			$stmt1->execute(array($row["vehicleid"]));
			while ($row1 = $stmt1->fetch()) {
				$vehiclename = $row1["vehiclename"];
			};

			$arr_results[$row["ndatetime"]][$row["commentid"]]["Date"] = $row["adatetime"];
			$arr_results[$row["ndatetime"]][$row["commentid"]]["Customer Name"] = $row["customername"];
			$arr_results[$row["ndatetime"]][$row["commentid"]]["Employee Name"] = $username;
			$arr_results[$row["ndatetime"]][$row["commentid"]]["Route Name"] = $routename;
			$arr_results[$row["ndatetime"]][$row["commentid"]]["Vehicle Name"] = $vehiclename;
			$arr_results[$row["ndatetime"]][$row["commentid"]]["Type"] = ucfirst($row["commenttype"]);
			$arr_results[$row["ndatetime"]][$row["commentid"]]["Text"] = $row["commenttext"];
			$arr_results[$row["ndatetime"]][$row["commentid"]]["Resolution"] = $row["resolutiontext"];

		};

		$arr_results["report_group_by"] = $report_group_by;

		$arr["arr_results"] = $arr_results;

	};

	//############  total_users
	if($report_type == "total_users") {

		$arr_users = [];

		$x = 0;

		$sqldaterange = "";

		if($report_subtype == "senioritydate") {
				$sqldaterange = " and (tblusers.nsenioritydate >= '$nfromdate' and tblusers.nsenioritydate <= '$ntodate') ";
		};
		if($report_subtype == "hireddate") {
				$sqldaterange = " and (tblusers.nhireddate >= '$nfromdate' and tblusers.nhireddate <= '$ntodate') ";
		};
		if($report_subtype == "birthdate") {
				$sqldaterange = " and (tblusers.nbirthdate >= '$nfromdate' and tblusers.nbirthdate <= '$ntodate') ";
		};

		if($report_subtype == "driverlicenseexpiration") {
				$sqldaterange = " and (tblusers.ndriverlicenseexpiration >= '$nfromdate' and tblusers.ndriverlicenseexpiration <= '$ntodate') ";
		};

		if($report_subtype == "badgeexpirationdate") {
				$sqldaterange = " and (tblusers.nbadgeexpirationdate >= '$nfromdate' and tblusers.nbadgeexpirationdate <= '$ntodate') ";
		};

		if($report_subtype == "dotexpirationdate") {
				$sqldaterange = " and (tblusers.ndotexpirationdate >= '$nfromdate' and tblusers.ndotexpirationdate <= '$ntodate') ";
		};

		$sql = "SELECT id, firstname, lastname, address, city, state, zip, email, badgenumber, badgeexpirationdate, badgecolor, dotexpirationdate, position, hireddate, senioritydate, birthdate, driverlicensenumber, driverlicenseexpiration
		FROM tblusers
		WHERE status = ?
		$sqldaterange
		ORDER BY firstname, lastname";

		$stmt = $dbconn->prepare($sql);
		$stmt->execute(array('active'));
		while ($row = $stmt->fetch()) {

			$arr_users[$x][$row["id"]]["Name"] = $row["firstname"] . " " . $row["lastname"];
			$arr_users[$x][$row["id"]]["Email"] = $row["email"];
			$arr_users[$x][$row["id"]]["Address"] = $row["address"] . " " . $row["city"] . " " . $row["state"] . " " . $row["zip"];
			$arr_users[$x][$row["id"]]["Driver License Number"] = $row["driverlicensenumber"];
			$arr_users[$x][$row["id"]]["Driver License Exp"] = $row["driverlicenseexpiration"];
			$arr_users[$x][$row["id"]]["Badge Number"] = $row["badgenumber"];
			$arr_users[$x][$row["id"]]["Badge Color"] = $row["badgecolor"];
			$arr_users[$x][$row["id"]]["Badge Exp"] = $row["badgeexpirationdate"];
			$arr_users[$x][$row["id"]]["DOT Exp"] = $row["dotexpirationdate"];
			$arr_users[$x][$row["id"]]["Date Hired"] = $row["hireddate"];
			$arr_users[$x][$row["id"]]["Seniority Date"] = $row["senioritydate"];
			$arr_users[$x][$row["id"]]["Birth Day"] = $row["birthdate"];

			$x = $x + 1;


		};

		$arr_fluids_grouped["report_group_by"] = $report_group_by;
		$arr["arr_results"] = $arr_users;
	};

	echo json_encode($arr);
	die();

};

if ($action == "getroutegroups") {

	$sql = "SELECT DISTINCT groupname
	FROM tblroutegroups
	ORDER BY groupname ASC";
	$stmt = $dbconn->prepare($sql);
	$stmt->execute();
	while ($row = $stmt->fetch()) {
		$arr[$row["groupname"]] = $row["groupname"];
	};

	echo json_encode($arr);
	die();
};

if ($action == "getvehiclegroups") {

	$sql = "SELECT DISTINCT groupname
	FROM tblvehiclegroups
	ORDER BY groupname ASC";
	$stmt = $dbconn->prepare($sql);
	$stmt->execute();
	while ($row = $stmt->fetch()) {
		$arr[$row["groupname"]] = $row["groupname"];
	};

	echo json_encode($arr);
	die();
};

if ($action == "getvehicletypes") {


	$sql = $sql = "select id as id, type as vehicletype from tblvehicletypes where status = ? order by type";
	$stmt = $dbconn->prepare($sql);
	$stmt->execute(array('active'));
	while ($row = $stmt->fetch()) {
		$vehicletype = $row["vehicletype"];
		$id = $row["id"];
		$arr[$id] = $vehicletype;
	};

	echo json_encode($arr);
	die();
};

if ($action == "getusers") {

	$sql = $sql = "select id, firstname, lastname from tblusers where status = ?";
	$sqlarray = array('active');


	$stmt = $dbconn->prepare($sql);
	$stmt->execute($sqlarray);
	while ($row = $stmt->fetch()) {
		$nameid = $row["id"];
		$name = $row["firstname"] . " " . $row["lastname"];
		$arr[$nameid] = $name;
	};

	echo json_encode($arr);
	die();
};


$arr["message"] = "Invalid Action POSTed";
echo json_encode($arr);
die();

?>