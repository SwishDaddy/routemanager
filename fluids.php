<?php
	include "api/includes.php";
	if (!logincheck()) {
		header("Location: /");
		die();
	}
	$arrroles = rolecheck();
	if (!in_array ("fluids" , $arrroles)) {
		header("Location: /");
		die();
	}
	headermarkup("Fluids");
?>

	<body>

	<?php globalnav(); ?>

		<div class="container" style="margin-top:50px;display:none;">

		<div class="row">
			<div class="col-md-5">
				<button id="fluids_save_data_and_new_btn" class="btn btn-success btnwidth fluids_edit_data" style="display:none;">Save and New</button>
				<div style="float:right;text-align:right;">
					<button id="fluids_save_data_btn" class="btn btn-warning btnwidth fluids_edit_data" style="display:none;">Apply</button>
					<button id="fluids_cancel_data_btn" class="btn btn-danger btnwidth fluids_edit_data" style="display:none;">Close</button>
				</div>
				<div style="clear:both;"></div>
				<button id="fluids_new_data_btn" class="btn btn-primary btnwidth fluids_view_data">New Entry</button>
			</div>

			<div class="col-md-7" style="text-align:right">
				<button id="fluids_view_data_delete_btn" class="btn btn-danger btnwidth fluids_edit_data" style="display:none;">Delete Entry</button>

			</div>

		</div>

		<div id="fluids_view_data_div" class="fluids_view_data" >

			<div class="row">
				<div class="col-md-2">
					<label for="fluids_criteria_daterange_fromdate">From</label>
					<input id="fluids_criteria_daterange_fromdate" class="form-control datepicker" />
				</div>

				<div class="col-md-2">
					<label for="fluids_criteria_daterange_todate">To</label>
					<input id="fluids_criteria_daterange_todate" class="form-control datepicker" />
				</div>

				<div class="col-md-2">
					<label style="visibility:hidden;">.</label>
					<br />
					<button id="fluids_criteria_btnfind" class="btn btn-success form-control btnwidth">Find</button>
				</div>

				<div class="col-md-6" style="text-align:right;">

				</div>

			</div>



			<div id="fluids_view_data_table_div" style="display:none;">

			<div class="row">
				<div class="col-md-12">
					<div id="fluids_results_table_div" style="width:100%;">
						<div class="col-md-12" style="text-align:right;padding-bottom:20px;">
							<button class="btn btn-primary tableexport" data-elid="fluids_view_data_table">Export .csv</button>
							<button id="fluids_btn_createreport" class="btn btn-info" data-elid="fluids_view_data_table">Printer-Friendly</button>
						</div>

					</div>
				</div>
			</div>
				<div id="fluids_view_data_table_parent">
					<table id="fluids_view_data_table" class="tablesorter" style="width:100%;">
						<thead></thead>
						<tbody></tbody>
					</table>
				</div>
			</div>

		</div>

		<div id="fluids_edit_data_div" class="fluids_edit_data" style="display:none;">

			<input id="fluids_edit_data_entryid" style="display:none;" class="fluids_edit_data_val" data-key="entryid"></input>

			<div class="row" style="margin-top:20px;">
				<div class="col-md-2">
					<label for="fluids_edit_data_vehicle_name">Vehicle<span style="visibility:hidden;">.</span>Name</label>
					<select id="fluids_edit_data_vehicle_name" class="form-control fluids_edit_data_val isrequired" data-key="vehicleid"></select>
				</div>
				<div class="col-md-2">
					<label for="fluids_edit_data_adatetime">Date</label>
					<input id="fluids_edit_data_adatetime" class="form-control datepicker fluids_edit_data_val isrequired" data-key="adatetime"></input>
				</div>
				<div class="col-md-3">
					<label for="fluids_edit_data_fueler_name">Fueler<span style="visibility:hidden;">.</span>Name</label>
					<select id="fluids_edit_data_fueler_name" class="form-control fluids_edit_data_val" data-key="fuelerid"></select>
				</div>
				<div class="col-md-3">
					<label for="fluids_edit_data_shift">Shift</label>
					<select id="fluids_edit_data_shift" class="form-control fluids_edit_data_val isrequired" data-key="shift">
						<option value="">- Select One -</option>
						<option value="Day">Day</option>
						<option value="Swing">Swing</option>
						<option value="Grave">Grave</option>
					</select>
				</div>
				<div class="col-md-2">
					<label for="fluids_edit_data_mileage">Mileage</label>
					<input id="fluids_edit_data_mileage" class="form-control fluids_edit_data_val sixnumbers" data-key="mileage"></input>
				</div>

			</div>

			<div class="row" style="margin-top:20px;">
				<div class="col-md-2">
					<label for="fluids_edit_data_hours">Hours</label>
					<input id="fluids_edit_data_hours" class="form-control fluids_edit_data_val fivenumbers" data-key="hours"></input>
				</div>
				<div class="col-md-2">
					<label for="fluids_edit_data_cng">CNG</label>
					<input id="fluids_edit_data_cng" class="form-control fluids_edit_data_val threenumbers" data-key="cng"></input>
				</div>
				<div class="col-md-3">
					<label for="fluids_edit_data_oil">Oil</label>
					<input id="fluids_edit_data_oil" class="form-control fluids_edit_data_val threenumbers" data-key="oil"></input>
				</div>
				<div class="col-md-3">
					<label for="fluids_edit_data_coolant">Coolant</label>
					<input id="fluids_edit_data_coolant" class="form-control fluids_edit_data_val threenumbers" data-key="coolant"></input>
				</div>
				<div class="col-md-2">
					<label for="fluids_edit_data_washerfluid">Washer Fluid</label>
					<input id="fluids_edit_data_washerfluid" class="form-control fluids_edit_data_val threenumbers" data-key="washerfluid"></input>
				</div>

			</div>



		</div>

		<?php echo footer(); ?>

	</div> <!-- /container -->

	<?php jsscripts("fluids"); ?>

	</body>
</html>
