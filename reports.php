<?php
	include "api/includes.php";
	if (!logincheck()) {
		header("Location: /");
		die();
	}
	$arrroles = rolecheck();
	if (!in_array ("manager" , $arrroles)) {
		header("Location: /");
		die();
	}
	headermarkup("Reports");

?>

<body>

<?php globalnav(); ?>

<div class="container" style="margin-top:50px;">

	<div class="row">
		<div class="col-sm-12" style="text-align:right;">
			<button id="reports_btn_cancel" class="btn btn-danger">Clear Results</button>
			<button id="reports_btn_go" class="btn btn-success">Get Results</button>
		</div>
	</div>

	<div class="row">
		<div id="reports_criteria_daterange_div">
			<div class="col-sm-3">
				<label>From</label>
				<input id="reports_criteria_daterange_fromdate" autocomplete="off" class="form-control datepicker reports_select_results localstorage" data-localkey="reports_criteria_daterange_fromdate" />

				<label>To</label>
				<input id="reports_criteria_daterange_todate" autocomplete="off" class="form-control datepicker reports_select_results localstorage" data-localkey="reports_criteria_daterange_todate" />
			</div>
		</div>

		<div class="col-sm-3">
			<label for="reports_select_routegroup">Route Group</label>
			<select id="reports_select_routegroup" class="form-control reports_select_results localstorage" data-localkey="reports_select_routegroup">
			</select>
			<label for="reports_select_vehiclegroup">Vehicle Group</label>
			<select id="reports_select_vehiclegroup" class="form-control reports_select_results localstorage" data-localkey="reports_select_vehiclegroup">
			</select>
		</div>

		<div class="col-sm-2">
			<label for="reports_select_reporttype">Report Type</label>
			<select id="reports_select_reporttype" class="form-control reports_select_results localstorage" data-localkey="reports_select_reporttype">
				<option value="total_number_of_passengers">Passengers and Mileage</option>
				<option value="total_in_service_hours">In-Service Hours</option>
				<option value="total_onbreak_hours"> - On Break Hours</option>
				<option value="total_fueling_hours"> - Fueling Hours</option>
				<option value="total_washing_hours"> - Washing Hours</option>
				<option value="total_maintenance_hours"> - Maintenance Hours</option>
				<option value="total_parked_hours"> - Parked Hours</option>
				<option value="total_accidents">Accidents</option>
				<option value="total_fluids">Fluids</option>
				<option value="total_comments">Comments</option>
				<option value="total_users">Users</option>
			</select>
			<div id="reports_select_billable_div" style="display:none;">
				<label for="reports_select_billable">Billable</label>
				<select id="reports_select_billable" class="form-control reports_select_results localstorage" data-localkey="reports_select_billable">
					<option value="all">All</option>
					<option value="True">True</option>
					<option value="False">False</option>
				</select>
			</div>
		</div>
		<div class="col-sm-2">
			<label for="reports_select_reportsubtype">Sub<span style="visibility:hidden;">.</span>Type</label>
			<select id="reports_select_reportsubtype" class="form-control reports_select_results localstorage" data-localkey="reports_select_reportsubtype"></select>
		</div>
		<div class="col-sm-2">
			<div id="reports_criteria_main_div" style="display:none;">
				<label id="reportcriteria_label">Report Criteria</label>
				<select id="reports_select_reportcriteria" class="form-control reportcriteria reports_select_results"></select>
			</div>
			<div id="reports_criteria_second_div" style="display:none;">
				<label id="reportcriteria_label_second">Route</label>
				<select id="reports_select_reportcriteria_second" class="form-control reports_select_results"></select>
			</div>
			<div id="reports_criteria_third_div" style="display:none;">
				<label id="reportcriteria_label_third">Day of Week</label>
				<select id="reports_select_reportcriteria_third" class="form-control reports_select_results">
					<option value="Monday">Monday</option>
					<option value="Tuesday">Tuesday</option>
					<option value="Wednesday">Wednesday</option>
					<option value="Thursday">Thursday</option>
					<option value="Friday">Friday</option>
					<option value="Saturday">Saturday</option>
					<option value="Sunday">Sunday</option>
				</select>
			</div>
			<div id="reports_criteria_fourth_div" style="display:none;">
				<label id="reportcriteria_label_fourth">Vehicle Type</label>
				<select id="reports_select_reportcriteria_fourth" class="form-control reports_select_results"></select>
			</div>
		</div>
	</div>

	<div id="reports_results_div" style="display:none;">
		<div class="row">
			<div class="col-sm-12">
				<div class="row">
					<div class="col-sm-6">
						<div id="report_group_by_div" style="display:none;">
							<input id="reports_group_by_day" type="radio" class="report_group_by" name="report_group_by" checked />
							<label for="reports_group_by_day" class="rolloverhand underline">Summary Report</label>
							<br />

							<input id="reports_group_by_hour" type="radio" class="report_group_by" name="report_group_by" />
							<label for="reports_group_by_hour" class="rolloverhand underline">Detailed Report <span style="font-style:italic;font-size:.8em;">(Warning: might be SLOW!)</span></label>
						</div>
					</div>
					<div class="col-sm-6">
					<br />
					<br />
						<div style="text-align:right;margin-bottom:10px;">
							<span id="recordcount"></span>
							<button class="btn btn-primary tableexport" data-elid="reports_results_table" style="margin-left:10px;">Export .csv</button>
							<button id="reports_btn_createreport" class="btn btn-info" data-elid="reports_results_table">Printer-Friendly</button>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12">
						<div id="reports_results_table_div">
							<table id="reports_results_table" class="tablesorter">
								<thead>
								</thead>
								<tbody>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div id="reports_results_linechart_div" style="width:100%;margin-top:25px;"></div>

	<div style="margin-top:50px;"></div>

	<?php echo footer(); ?>

</div> <!-- /container -->

<?php jsscripts("reports"); ?>

</body>
</html>
