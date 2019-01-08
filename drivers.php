<?php
	include "api/includes.php";
	if (!logincheck()) {
		header("Location: /");
		die();
	}
	$arrroles = rolecheck();
	if (!in_array ("driver" , $arrroles)) {
		header("Location: /");
		die();
	}
	headermarkup("Drivers");
?>

<style>
#driverinput_totals_container_table {
	text-align: center;
}

</style>

<body>

<?php globalnav(); ?>

<div class="container" style="margin-top:50px;margin-bottom:50px;">

<div id="test" contenteditable="true" style="display:none;">

</div>


	<div class="row"x>
		<div class="col-md-12">
			<div id="driverinputs_controls_div1">
				<button id="driverinputs_new_driver_sheet" class="btn btn-success btnwidth" style="display:none;">Driver Sheet</button>
				<!--<button id="driverinputs_edit_driver_sheet" class="btn btn-primary btnwidth">Edit Driver Sheet</button>-->
			</div>
			<div id="driverinputs_controls_div2" style="display:none;">
				<button id="driverinput_btnshowgrid" class="btn btn-success btnwidth">Data Management</button>
				<!--<button id="driverinput_btnshowgrid_cancel" class="btn btn-danger driverinputs_cancel btnwidth">Cancel</button>-->
			</div>
			<div id="driverinputs_controls_div3" style="display:none;">
				<div style="float:left;">
				<button id="driverinputs_save_close_driver_sheet" class="btn btn-success btnwidth confirmation_close" title="Save and Close">Save and Close</button>
				</div>
				<div style="text-align:right;float:right;">
				<button id="driverinputs_save_driver_sheet" class="btn btn-warning btnwidth confirmation_close" title="Save All Changes">Apply</button>
				<button id="driverinputs_save_driver_sheet_cancel" class="btn btn-danger driverinputs_cancel btnwidth confirmation_close" title="Close and Lose Unsaved Changes">Cancel</button>
				</div>
				<div class="clear:both;"></div>
			</div>
		</div>

		<div class="col-sm-4">
			<h2 style="display:none;" id="driverinputs_title"></h2>
		</div>

		<div class="col-sm-4">

		</div>

	</div>

	<div id="driverinputs_parent" style="display:none;">

		<div id="driverinput_selects_parent" class="row">
			<div class="col-sm-2">
				<label for="driverinput_date">Date</label>
				<input id="driverinput_date" class="form-control datepicker driverinput_selects" title="Click to Select Date" autocomplete="off" />
			</div>

			<div class="col-sm-2">
				<label for="driverinput_vehiclename">Vehicle</label>
				<select id="driverinput_vehiclename" class="form-control driverinput_selects"  title="Select Vehicle">
					<option value="">- Select One -</option>
				</select>
			</div>
			<!--<div class="col-sm-2">

				<label for="driverinput_drivername">Driver</label>
				<select id="driverinput_drivername" class="form-control driverinput_selects" title="Select Driver">
					<option value="">- Select One -</option>
				</select>

			</div>
			-->

			<div class="col-sm-8" style="text-align:right;">

			</div>
		</div>

	</div>

	<div id="driverinput_table_parent" style="margin-top:20px;display:none;">

		<div class="row" style="margin-bottom:0px;padding-left:15px;">
			<div class="col-md-12">
				<div class="main_buttons">
					<button class="btn btn-info viewtotals" tabindex="-1" title="View Totals">View Totals</button>
					<button class="btn btn-default glyphicon glyphicon-zoom-in increasefont" tabindex="-1" style="margin-left:20px;"  title="Zoom In"></button>
					<button class="btn btn-default glyphicon glyphicon-zoom-out decreasefont" tabindex="-1" title="Zoom Out" style="margin-left:10px;"></button>
					<button class="btn btn-default glyphicon glyphicon-refresh resetfont" tabindex="-1"  title="Default Zoom" style="margin-left:10px;"></button>
					<button class="btn btn-primary glyphicon glyphicon-plus addgrid" tabindex="-1" style="margin-left:20px;" title="Add Grid"></button>
					<button class="btn btn-primary glyphicon glyphicon-minus removegrid" tabindex="-1" style="margin-left:10px;"  title="Remove Right-Most Route Grid?"></button>

					<span style="margin-left:5px;vertical-align:bottom;" class="noshrink">Grid<span style="visibility:hidden;">.</span>Count:</span>
					<span id="driverinputs_grid_count" class="noshrink" style="vertical-align:bottom;">2</span>

					<!--<button class="btn btn-warning glyphicon glyphicon-transfer undo" tabindex="-1" style="margin-left:20px;display:none;"  title="Undo Last Entry"></button>-->

				</div>
				<div class="total_buttons hide_on_cancel" style="display:none;">
					<button class="btn btn-info viewdataentry" tabindex="-1" title="Back to Data Management">Back To Data Management</button>
				</div>
			</div>
		</div>

		<div>
			<div id="driverinput_container_table_div">
				<table id="driverinput_container_table" class="grid">

				</table>
			</div>
			<div id="driverinput_totals_container_table_div" style="margin-top:20px;display:none;">
				<table>
					<tbody>
						<tr>
							<td>
								<table id="driverinput_totals_container_table" class="grid">

								</table>
							</td>
							<td style="vertical-align:top;">
								<table id="driverinput_totals_general_table" class="grid">
									<thead>
										<tr style="font-size:150%;font-weight:bold;">
											<td style="text-align:left;padding:5px;">In<span style="visibility:hidden;">-</span>Service:</td>
											<td style="text-align:right;padding:5px;"><span id="driverinput_totals_hours_in_service"></span></td>
										</tr>
										<tr style="font-size:150%;font-weight:bold;background-color:white;">
											<td style="text-align:left;padding:5px;">Out<span style="visibility:hidden;">-</span>of<span style="visibility:hidden;">-</span>Service:</td>
											<td style="text-align:right;padding:5px;"><span id="driverinput_totals_out_of_service"></span></td>
										</tr>
									</thead>
								</table>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>


	<?php echo footer(); ?>

</div> <!-- /container -->

<?php jsscripts("drivers"); ?>

</body>
</html>
