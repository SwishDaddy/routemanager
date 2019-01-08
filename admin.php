<?php
	include "api/includes.php";
	if (!logincheck()) {
		header("Location: /");
		die();
	}
	$arrroles = rolecheck();
	if (!in_array ("admin" , $arrroles)) {
		header("Location: /");
		die();
	}
	headermarkup("Admin");
?>

<body>

<?php globalnav(); ?>

<div class="container" style="margin-top:50px;">
	<div class="row">
		<div class="col-sm-12">
			<button class="btn btn-default" id="admin_btnusermanagement" style="width:80px;">Users</button>
			<button class="btn btn-default" id="admin_btnvehiclemanagement" style="width:80px;">Vehicles</button>
			<button class="btn btn-default" id="admin_btnroutemanagement" style="width:80px;">Routes</button>
		</div>
	</div>

	<div style="margin-top:20px;"><hr /></div>

	<div id="admin_usermanagement_div" class="admin_parent_div" style="display:none;">

		<div class="row">
			<div class="col-md-4">
				<label for="admin_select_user">Select User</label>

				<div class="input-group">
					<select class="form-control" id="admin_select_user"></select>
					<span class="input-group-btn">
						<button id="admin_btndeleteuser" class="btn btn-danger">Delete</button>
						<button class="btn btn-success" id="admin_newuser">New</button>
					</span>
				</div>
			</div>
			<div class="col-md-8" style="text-align:right;">

			</div>
		</div>

		<div id="admin_edit_user_info_div" style="margin-top:20px;display:none;">
			<div class="row">
			<hr />
				<div class="col-md-12" style="margin-top:10px;text-align:right;">
					<div id="admin_edit_user_info_main_btn_div">
						<button id="admin_edit_user_info_btnapply" class="btn btn-primary">Apply</button>
						<button id="admin_edit_user_info_btncancel"class="btn btn-danger" style="margin-left:10px;">Cancel</button>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-3" style="margin-top:10px;">
					<label for="admin_edit_user_info_firstname">Firstname</label>
					<input type="text" id="admin_edit_user_info_firstname" class="form-control admin_edit_user_info" />
				</div>
				<div class="col-md-3" style="margin-top:10px;">
					<label for="admin_edit_user_info_lastname">Lastname</label>
					<input type="text" id="admin_edit_user_info_lastname" class="form-control admin_edit_user_info" />
				</div>
				<div class="col-md-3" style="margin-top:10px;">
					<label for="admin_edit_user_info_email">Email</label>
					<input type="text" id="admin_edit_user_info_email" class="form-control admin_edit_user_info" />
				</div>
				<div class="col-md-3" style="margin-top:10px;">
					<div id="admin_edit_user_info_btnpassword_div" style="display:none;">
						<label style="visibility:hidden;">Set Password</label><br />
						<button id="admin_edit_user_info_btnpassword" class="btn btn-default">Set Password</button>
					</div>
					<div id="admin_edit_user_info_setpassword_div" style="display:none;">
						<label for="admin_edit_user_info_setpassword">Set Password</label>
						<div class="input-group">
							<input type="text" id="admin_edit_user_info_setpassword" class="form-control admin_edit_user_info" />
							<span class="input-group-btn">
								<button id="admin_edit_user_info_setpassword_btnapply" class="btn btn-default">Go</button>
								<button id="admin_edit_user_info_setpassword_btncancel" class="btn btn-danger">Cancel</button>
							</span>
						</div>
					</div>
				</div>

			</div>

			<br />
			<div class="row">
				<div class="col-md-3" style="min-width:256px;">
					<label>Roles</label>
					<p>
						<input type="checkbox" id="admin_edit_user_info_role_driver"><label for="admin_edit_user_info_role_driver" style="margin-left:10px;font-weight:10;" class="rolloverhand underline">Driver Sheet (Data Entry)</label></input>
						<br />
						<input type="checkbox" id="admin_edit_user_info_role_manager"><label for="admin_edit_user_info_role_manager" style="margin-left:10px;font-weight:10;" class="rolloverhand underline">Manager (View Reports)</label></input>
						<br />
						<input type="checkbox" id="admin_edit_user_info_role_comments"><label for="admin_edit_user_info_role_comments" style="margin-left:10px;font-weight:10;" class="rolloverhand underline">Comments (Manage Feedback)</label></input>
						<br />
						<input type="checkbox" id="admin_edit_user_info_role_fluids"><label for="admin_edit_user_info_role_fluids" style="margin-left:10px;font-weight:10;" class="rolloverhand underline">Fluids (CNG, Oil, etc.)</label></input>
						<br />
						<input type="checkbox" id="admin_edit_user_info_role_accidents"><label for="admin_edit_user_info_role_accidents" style="margin-left:10px;font-weight:10;" class="rolloverhand underline">Accidents (Document Accidents)</label></input>
						<br />
						<input type="checkbox" id="admin_edit_user_info_role_admin"><label for="admin_edit_user_info_role_admin" style="margin-left:10px;font-weight:10;" class="rolloverhand underline">Admin (Advanced Functions)</label></input>

					</p>
				</div>

				<div class="col-md-9">
					<div class="col-md-6">
						<div class="row">
							<div class="col-md-12">
								<label for="admin_edit_user_info_address">Address</label>
								<input type="text" id="admin_edit_user_info_address" class="form-control admin_edit_user_info" />
							</div>
						</div>
						<div class="row">
							<div class="col-md-5">
								<label for="admin_edit_user_info_city">City</label>
								<input type="text" id="admin_edit_user_info_city" class="form-control admin_edit_user_info" />
							</div>
							<div class="col-md-4">
								<label for="admin_edit_user_info_state">State</label>
								<select  id="admin_edit_user_info_state" class="form-control admin_edit_user_info statelist"></select>
							</div>
							<div class="col-md-3">
								<label for="admin_edit_user_info_zip">ZIP</label>
								<input type="text" id="admin_edit_user_info_zip" class="form-control admin_edit_user_info zipmask" />
							</div>
						</div>
						<div class="row">
							<div class="col-md-8">
								<label for="admin_edit_user_info_position">Position</label>
								<select id="admin_edit_user_info_position" class="form-control admin_edit_user_info">
									<option value="Manager">Manager</option>
									<option value="Admin">Admin</option>
									<option value="MOD">MOD</option>
									<option value="AMOD">AMOD</option>
									<option value="Attendent">Attendent</option>
									<option value="Driver">Driver</option>
									<option value="Supervisor">Supervisor</option>
									<option value="Other">Other</option>
								</select>
							</div>
							<div class="col-md-4">
								<label for="admin_edit_user_info_dotexpiration">DOT Exp</label>
								<input type="text" id="admin_edit_user_info_dotexpiration" class="form-control admin_edit_user_info datepicker" />
							</div>

						</div>
						<div class="row">
							<div class="col-md-4">
								<label for="admin_edit_user_info_hiredate">Hired</label>
								<input type="text" id="admin_edit_user_info_hiredate" class="form-control admin_edit_user_info datepicker" />
							</div>
							<div class="col-md-4">
								<label for="admin_edit_user_info_senioritydate">Seniority</label>
								<input type="text" id="admin_edit_user_info_senioritydate" class="form-control admin_edit_user_info datepicker" />
							</div>
							<div class="col-md-4">
								<label for="admin_edit_user_info_birthdate">DOB</label>
								<input type="text" id="admin_edit_user_info_birthdate" class="form-control admin_edit_user_info datepicker" />
							</div>
						</div>

					</div>

					<div class="col-md-6">
						<div class="row">
							<div class="col-md-12">
								<label for="admin_edit_user_info_phone">Phone</label>
								<input type="text" id="admin_edit_user_info_phone" class="form-control admin_edit_user_info phonemask" />
							</div>
						</div>
						<div class="row">
							<div class="col-md-8">
								<label for="admin_edit_user_info_driverlicensenumber">Driver's License No.</label>
								<input type="text" id="admin_edit_user_info_driverlicensenumber" class="form-control admin_edit_user_info" />
							</div>
							<div class="col-md-4">
								<label for="admin_edit_user_info_driverlicenseexpiration">Expiration</label>
								<input type="text" id="admin_edit_user_info_driverlicenseexpiration" class="form-control admin_edit_user_info datepicker" />
							</div>
						</div>

						<div class="row">
							<div class="col-md-5">
								<label for="admin_edit_user_info_badgenumber">Badge #</label>
								<input type="text" id="admin_edit_user_info_badgenumber" class="form-control admin_edit_user_info" />
							</div>
							<div class="col-md-4">
								<label for="admin_edit_user_info_badgeexpiration">Exp</label>
								<input type="text" id="admin_edit_user_info_badgeexpiration" class="form-control admin_edit_user_info datepicker" />
							</div>
							<div class="col-md-3">
								<label for="admin_edit_user_info_badgecolor">Color</label>
								<input type="text" id="admin_edit_user_info_badgecolor" class="form-control admin_edit_user_info" />
							</div>
						</div>
					</div>



				</div>
			</div>
		</div>
	</div>

	<div id="admin_vehiclemanagement_div" class="admin_parent_div" style="display:none;">

		<div id="admin_edit_vehicle_info_div" style="margin-top:20px;display-none;">

			<div class="row">
				<div class="col-md-6" class="admin_edit_vehicle_info_mileage">
					<button id="admin_edit_vehicle_btn_vehicle_groups" class="btn btn-warning">Vehicle Groups</button>
				</div>
			</div>

			<div class="row">
				<div class="col-md-3 admin_edit_vehicle_info">
					<label for="admin_edit_vehicle_info_type">Vehicle Type</label>
					<div class="input-group" id="admin_edit_vehicle_info_type_select_div">
						<select id="admin_edit_vehicle_info_type" class="form-control "></select>
						<span class="input-group-btn">
							<button id="admin_edit_vehicle_info_btndeletevehicle_type" class="btn btn-danger">Delete</button>
							<button id="admin_edit_vehicle_info_btnnewvehicle_type" class="btn btn-success">New</button>
						</span>
					</div>
					<div class="input-group"  id="admin_edit_vehicle_info_type_input_div" style="display:none;">
						<input id="admin_edit_vehicle_info_new_type" class="form-control" />
						<span class="input-group-btn">
							<button id="admin_edit_vehicle_info_new_type_btnapply" class="btn btn-success">Apply</button>
							<button id="admin_edit_vehicle_info_new_type_btncancel" class="btn btn-danger">Cancel</button>
						</span>
					</div>
				</div>
				<div class="col-md-3 admin_edit_vehicle_info" id="admin_edit_vehicle_info_name_div" style="display:none;">
					<label for="admin_edit_vehicle_info_name">Name/Number</label>
					<div class="input-group" id="admin_edit_vehicle_info_name_select_div">
						<select id="admin_edit_vehicle_info_name" class="form-control"></select>
						<span class="input-group-btn">
							<button id="admin_edit_vehicle_info_btndeletevehicle" class="btn btn-danger">Delete</button>
							<button id="admin_edit_vehicle_info_btnnewvehicle" class="btn btn-success">New</button>
						</span>
					</div>
					<div class="input-group"  id="admin_edit_vehicle_info_name_input_div" style="display:none;">
						<input id="admin_edit_vehicle_info_new_name" class="form-control" />
						<span class="input-group-btn">
							<button id="admin_edit_vehicle_info_new_name_btnapply" class="btn btn-success">Apply</button>
							<button id="admin_edit_vehicle_info_new_name_btncancel" class="btn btn-danger">Cancel</button>
						</span>
					</div>
				</div>
				<div class="col-md-3 admin_edit_vehicle_info" id="admin_edit_vehicle_info_vin_div" style="display:none;">
					<label for="admin_edit_vehicle_info_vin">VIN</label>
					<div class="input-group" id="admin_edit_vehicle_info_vin_select_div">
						<input id="admin_edit_vehicle_info_vin" class="form-control" data-label="vin"></input>
						<span class="input-group-btn">
							<button id="admin_edit_vehicle_info_vin_btnapply" class="btn btn-success admin_edit_vehicle_info_save_info">Apply</button>
						</span>
					</div>
				</div>

				<div class="col-md-3 admin_edit_vehicle_info" id="admin_edit_vehicle_info_licenseplate_div" style="display:none;">
					<label for="admin_edit_vehicle_info_licenseplate">License Plate No.</label>
					<div class="input-group" id="admin_edit_vehicle_info_licenseplate_select_div">
						<input id="admin_edit_vehicle_info_licenseplate" class="form-control" data-label="licenseplate"></input>
						<span class="input-group-btn">
							<button id="admin_edit_vehicle_info_licenseplate_btnapply" class="btn btn-success admin_edit_vehicle_info_save_info">Apply</button>
						</span>
					</div>
				</div>

			</div>

		</div>


		<div id="admin_edit_vehicle_vehicle_group_div" style="margin-top:20px;display:none;">
			<button id="admin_edit_vehicle_btn_vehicle_groups_done" class="btn btn-success">Done</button>
			<h2>Manage Vehicle Groups</h2>
			<div id="admin_edit_vehicle_info_vehicle_group_div">
				<div class="row">
					<div class="col-md-4">
						<label for="admin_edit_vehicle_info_vehicle_group">Vehicle Group</label>
						<div class="input-group" id="admin_edit_vehicle_info_group_select_div">
							<select id="admin_edit_vehicle_info_group" class="form-control "></select>
							<span class="input-group-btn">
								<button id="admin_edit_vehicle_group_btndeletegroup" class="btn btn-danger">Delete</button>
								<button id="admin_edit_vehicle_group_btnnewgroup" class="btn btn-success">New</button>
							</span>
						</div>
						<div class="input-group"  id="admin_edit_vehicle_info_group_input_div" style="display:none;">
							<input id="admin_edit_vehicle_info_new_vehiclegroup" class="form-control" />
							<span class="input-group-btn">
								<button id="admin_edit_vehicle_info_new_vehiclegroup_btnapply" class="btn btn-success">Apply</button>
								<button id="admin_edit_vehicle_info_new_vehiclegroup_btncancel" class="btn btn-danger">Cancel</button>
							</span>
						</div>
					</div>
				</div>
			</div>

			<div id="admin_edit_vehicle_info_vehicle_group_members_div" style="margin-top:20px;display:none;">
				<div class="row" id="admin_edit_vehicle_info_vehicle_group_members_list">
					<div class="col-md-4">
						<label style="font-size:1.2em;">Group Members</label>
						<button id="admin_edit_vehicle_info_vehicle_group_members_btnadd" style="margin-left:10px;"class="btn btn-primary">Add Vehicle</button>
						<br />
						<label>Click on a Vehicle to remove it</label>
						<table id="admin_edit_vehicle_info_tblgroupmembers" class="tablsorter">
							<tbody>
							</tbody>
						</table>

					</div>
				</div>
				<div class="row" id="admin_edit_vehicle_info_vehicle_group_members_add" style="display:none;">
					<div class="col-md-4">
						<label for="admin_edit_vehicle_info_vehicle_group_members_select">Select Vehicle</label>
						<div class="input-group">
						<select id="admin_edit_vehicle_info_vehicle_group_members_select" class="form-control "></select>
						<span class="input-group-btn">
							<button id="admin_edit_vehicle_group_new_vehicle_btnapply" class="btn btn-success">Apply</button>
							<button id="admin_edit_vehicle_group_new_vehicle_btncancel" class="btn btn-danger">Cancel</button>
						</span>
					</div>
					</div>
				</div>
			</div>

		</div>


	</div>

	<div id="admin_routemanagement_div" class="admin_parent_div" style="display:none;">

		<div id="admin_edit_route_info_div" style="margin-top:20px;">

			<div class="row">
				<div class="col-md-6" class="admin_edit_route_info_mileage">

					<button id="admin_edit_route_btn_list_order" class="btn btn-warning">List Order</button>
					<button id="admin_edit_route_btn_route_groups" class="btn btn-warning">Route Groups</button>

				</div>
			</div>

			<div class="row">
				<div class="admin_edit_route_info">
					<div class="col-md-2">
						<label for="admin_edit_route_info_routetype">RouteType</label>
						<select id="admin_edit_route_info_routetype" class="form-control">
							<option value="employee">Employee</option>
							<option value="public">Public</option>
							<!--<option value="special">Special</option>-->
						</select>
					</div>
					<div class="col-md-4">
						<label for="admin_edit_route_info_name">Route Name</label>
						<div class="input-group" id="admin_edit_route_info_name_select_div">
							<select id="admin_edit_route_info_name" class="form-control "></select>
							<span class="input-group-btn">
								<button id="admin_edit_route_info_btndeleteroute" class="btn btn-danger">Delete</button>
								<button id="admin_edit_route_info_btnnewroute" class="btn btn-success">New</button>
							</span>
						</div>
						<div class="input-group"  id="admin_edit_route_info_name_input_div" style="display:none;">
							<input id="admin_edit_route_info_new_route" class="form-control" />
							<span class="input-group-btn">
								<button id="admin_edit_route_info_new_route_btnapply" class="btn btn-success">Apply</button>
								<button id="admin_edit_route_info_new_route_btncancel" class="btn btn-danger">Cancel</button>
							</span>
						</div>
					</div>
					<div class="col-md-2 admin_edit_route_info_mileage" id="admin_edit_route_info_mileage_div" style="display:none;">
						<label for="admin_edit_route_info_mileage_type">Mileage Type</label>
						<select id="admin_edit_route_info_mileage_type" class="form-control">
							<option value="lot_to_terminal">&raquo;&raquo; Lot to Terminal &raquo;&raquo;</option>
							<option value="terminal_to_lot">&laquo;&laquo; Terminal to Lot &laquo;&laquo;</option>
						</select>
					</div>
					<div class="col-md-2 admin_edit_route_info_mileage" id="admin_edit_route_info_mileage_value_div" style="display:none;">
						<label for="admin_edit_route_info_mileage_value">Mileage Value</label>
						<div class="input-group" id="admin_edit_route_info_mileage_value_input_div">
							<input type="number" id="admin_edit_route_info_mileage_value" class="form-control" />
							<span class="input-group-btn">
								<button id="admin_edit_vehicle_info_mileage_value_btnapply" class="btn btn-success">Apply</button>
							</span>
						</div>
					</div>


				</div>

			</div>


		</div>

		<div id="admin_edit_route_list_order_div" style="margin-top:20px;display:none;">

			<div class="row">
				<div class="col-md-2" class="admin_edit_route_info_mileage">

					<button id="admin_edit_route_btn_list_order_apply" class="btn btn-success">Done</button>

				</div>
			</div>

			<h2>Drag the Routes Up or Down</h2>
			<p>Determines the order the Routes appear in Dropdown Lists</p>

			<div class="row">
				<div class="col-md-4" style="margin-top:20px;">
					<table class="list_order_table" id="admin_edit_route_listorder">
						<thead>
						</thead>
						<tbody>

						</tbody>
					</table>
				</div>
			</div>
		</div>

		<div id="admin_edit_route_route_group_div" style="margin-top:20px;display:none;">
			<button id="admin_edit_route_btn_route_groups_done" class="btn btn-success">Done</button>
			<h2>Manage Route Groups</h2>
			<div id="admin_edit_route_info_route_group_div">
				<div class="row">
					<div class="col-md-4">
						<label for="admin_edit_route_info_route_group">Route Group</label>
						<div class="input-group" id="admin_edit_route_info_group_select_div">
							<select id="admin_edit_route_info_group" class="form-control "></select>
							<span class="input-group-btn">
								<button id="admin_edit_route_group_btndeletegroup" class="btn btn-danger">Delete</button>
								<button id="admin_edit_route_group_btnnewgroup" class="btn btn-success">New</button>
							</span>
						</div>
						<div class="input-group"  id="admin_edit_route_info_group_input_div" style="display:none;">
							<input id="admin_edit_route_info_new_routegroup" class="form-control" />
							<span class="input-group-btn">
								<button id="admin_edit_route_info_new_routegroup_btnapply" class="btn btn-success">Apply</button>
								<button id="admin_edit_route_info_new_routegroup_btncancel" class="btn btn-danger">Cancel</button>
							</span>
						</div>
					</div>
				</div>
			</div>

			<div id="admin_edit_route_info_route_group_members_div" style="margin-top:20px;display:none;">
				<div class="row" id="admin_edit_route_info_route_group_members_list">
					<div class="col-md-4">
						<label style="font-size:1.2em;">Group Members</label>
						<button id="admin_edit_route_info_route_group_members_btnadd" style="margin-left:10px;"class="btn btn-primary">Add Route</button>
						<br />
						<label>Click on a Route to remove it</label>
						<table id="admin_edit_route_info_tblgroupmembers" class="tablsorter">
							<tbody>
							</tbody>
						</table>

					</div>
				</div>
				<div class="row" id="admin_edit_route_info_route_group_members_add" style="display:none;">
					<div class="col-md-4">
						<label for="admin_edit_route_info_route_group_members_select">Select Route</label>
						<div class="input-group">
						<select id="admin_edit_route_info_route_group_members_select" class="form-control "></select>
						<span class="input-group-btn">
							<button id="admin_edit_route_group_new_route_btnapply" class="btn btn-success">Apply</button>
							<button id="admin_edit_route_group_new_route_btncancel" class="btn btn-danger">Cancel</button>
						</span>
					</div>
					</div>
				</div>
			</div>

		</div>


	</div>

	<?php echo footer(); ?>
</div> <!-- /container -->

<?php jsscripts("admin"); ?>

</body>
</html>
