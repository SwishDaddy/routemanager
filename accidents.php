<?php	
	include "api/includes.php";
	if (!logincheck()) {
		header("Location: /");
		die();
	}
	$arrroles = rolecheck();
	if (!in_array ("accidents" , $arrroles)) {
		header("Location: /");
		die();
	}
	headermarkup("Accidents");
?>

<style>
	.tablesorter th, .tablesorter td {
		padding: 15px !important;
	}
	
	input[type="checkbox"] { 
		position: absolute;
		
		margin: 19px 0px 15px 0px;
	}
	input[type="checkbox"] ~ label { 
		padding-left:.75em;
		display:inline-block;
	}
</style>

	<body>

		<?php globalnav(); ?>

		<div class="container" style="margin-top:50px;display:none;">

			<div id="maincontainer">
				<div class="row">
					<div class="col-sm-8">

						<button id="accidents_save_data_btn" class="btn btn-success accidents_edit_data btnwidth" style="display:none;">Save</button>
						<button id="accidents_new_data_btn" class="btn btn-primary accidents_view_data btnwidth">New Accident</button>
					</div>

					<div class="col-sm-4" style="text-align:right">
					<button id="accidents_cancel_data_btn" class="btn btn-danger accidents_edit_data btnwidth" style="display:none;">Close</button>
					
					</div>

				</div>

				<div id="accidents_view_data_div" class="accidents_view_data" >

					<div class="row">
						<div class="col-md-2">
							<label for="accidents_criteria_daterange_fromdate">From</label>
							<input type="text" id="accidents_criteria_daterange_fromdate" data-localkey="accidents_criteria_daterange_fromdate" class="form-control datepicker localstorage" autocomplete="off" />
						</div>

						<div class="col-md-2">
							<label for="accidents_criteria_daterange_todate">To</label>
							<input type="text" id="accidents_criteria_daterange_todate" data-localkey="accidents_criteria_daterange_todate" class="form-control datepicker localstorage" autocomplete="off" />
						</div>

						<div class="col-md-2">
							<label style="visibility:hidden;">.</label>
							<br />
							<button id="accidents_criteria_btnfind" class="btn btn-success form-control btnwidth">Find</button>
						</div>

						<div class="col-md-6" style="text-align:right;">

						</div>

					</div>

					<div id="accidents_view_data_table_div" style="display:none;">

					<div class="row">
						<div class="col-md-12">
							<div id="accidents_results_table_div" style="width:100%;">
								<div class="col-md-12" style="text-align:right;padding-bottom:20px;">
									<button class="btn btn-primary tableexport btnwidth" data-elid="accidents_view_data_table">Export .csv</button>
									<button id="accidents_btn_createreport" class="btn btn-info btnwidth" data-elid="accidents_view_data_table">Printer-Friendly</button>
								</div>

							</div>
						</div>
					</div>
						<div id="accidents_view_data_table_parent">
							<table id="accidents_view_data_table" class="tablesorter" style="width:100%;">
								<thead class="table_header_color"></thead>
								<tbody></tbody>
							</table>
						</div>
					</div>

				</div>

				<div id="accidents_edit_data_div" class="accidents_edit_data" style="display:none;">

					<input type="text" class="accidents_edit_data_postdata_val" id="accidents_edit_data_accidentid" style="display:none;" data-postkey="accidentid"></input>

					<div id="accidents_edit_data_accident_information_small" style="display:none;" class="div_parent_frame roundcorners">

						<div style="width:50%;float:left;">
							<h4><img src="img/about.png" style="height:2em;margin-right:10px;">ACCIDENT INFORMATION</h4>
						</div>

						<div style="width:50%;text-align:right;float:right;">
							<button id="accidents_edit_data_accident_information_btnedit"class="btn btn-success btnwidth">View/Edit</button>
						</div>
						<div style="clear:both;"></div>
					</div>

					<div id="accidents_edit_data_accident_information" class="div_parent_frame roundcorners">

						<div style="width:50%;float:left;">
							<h4><img src="img/about.png" style="height:2em;margin-right:10px;">ACCIDENT INFORMATION</h4>
						</div>

						<div style="width:50%;text-align:right;float:right;">
							<button id="accidents_edit_data_accident_information_btnhide"class="btn btn-info btnwidth">Hide</button>
						</div>
						<div style="clear:both;"></div>
						<div class="row">

							<div class="col-md-3">
								<label for="accidents_edit_data_accidentdate">Date of Accident</label>
								<input id="accidents_edit_data_accidentdate" type="text" class="form-control datepicker accidents_edit_data_postdata_val" data-postkey="date_of_accident"></input>
							</div>
							<div class="col-md-3">
								<label for="accidents_edit_data_accidenttime">Time of Accident</label>
								<input id="accidents_edit_data_accidenttime" type="text" class="form-control militarytime accidents_edit_data_postdata_val"  data-postkey="time_of_accident"></input>
							</div>
							<div class="col-md-3">
								<label for="accidents_edit_data_locationname">Service Location</label>
								<input id="accidents_edit_data_locationname" type="text" tabindex="-1" class="form-control accidents_edit_data_postdata_val" value="Denver International Airport (DIA)" data-postkey="location_name" readonly></input>
							</div>
							<div class="col-md-3">
								<label for="accidents_edit_data_route">Lot/Route</label>
								<select id="accidents_edit_data_route" class="form-control accidents_edit_data_postdata_val routelist" data-postkey="routeid"></select>
							</div>

						</div>

						<div class="row">

							<div class="col-md-3">
								<label for="accidents_edit_data_location_description">Location of Accident</label>
								<div id="accidents_edit_data_location_description" class="accidents_edit_data_postdata_html contenteditable whitebg" contenteditable="true" style="border: 1px solid #dedede;min-height:7em;padding:5px;" data-postkey="location_description"></div>
							</div>

							<div class="col-md-3">
								<label for="accidents_edit_data_propertydamage">Property Damage</label>
								<div id="accidents_edit_data_propertydamage" class="accidents_edit_data_postdata_html contenteditable whitebg" contenteditable="true" style="border: 1px solid #dedede;min-height:7em;padding:5px;" data-postkey="property_damage_description"></div>
							</div>

							<div class="col-md-3">
								<label for="accidents_edit_data_damagecaused_comments">General Comments</label>
								<div id="accidents_edit_data_damagecaused_comments" class="accidents_edit_data_postdata_html contenteditable whitebg" contenteditable="true" style="border: 1px solid #dedede;min-height:7em;padding:5px;" data-postkey="damagecaused_comments"></div>
							</div>

							<div class="col-md-3">
								<label>Linked Documents</label>
								<div class="row whitebg" style="border: 1px solid #dedede;min-height:7em;padding:0px 15px;margin:0px;">
									<!--<div class="col-md-12">-->
										<div style="float:left;">
											<input type="checkbox" class="chkshowuploadbutton accidents_edit_data_postdata_chk" id="accidents_edit_data_photostaken" data-btnid="accidents_edit_data_photostaken_btnlinkeddocs" data-postkey="photos_taken">
											<label for="accidents_edit_data_photostaken" style="margin-left:10px;" class="rolloverhand underline">Photos Taken</label></input>
										</div>
										<div style="float:right;">
											<button id="accidents_edit_data_photostaken_btnlinkeddocs" style="margin-top:10px;display:none;padding:1px 5px;" data-ufiletype="photos_taken-Photos Taken" class="btn btn-default glyphicon glyphicon-upload"></button>
										</div>
										<div style="clear:both;"></div>

										<div style="float:left;">
											<input type="checkbox" class="chkshowuploadbutton accidents_edit_data_postdata_chk" id="accidents_edit_data_btnlinkeddocs_otherdocs" data-btnid="accidents_edit_data_otherdocs_btnlinkeddocs" data-postkey="other_documents">
											<label for="accidents_edit_data_btnlinkeddocs_otherdocs" style="margin-left:10px;" class="rolloverhand underline">Other Documents</label></input>
										</div>
										<div style="float:right;">
											<button id="accidents_edit_data_otherdocs_btnlinkeddocs" style="margin-top:10px;padding:1px 5px;display:none;" class="btn btn-default glyphicon glyphicon-upload" data-ufiletype="other_documents-Other Documents"></button>
										</div>
										<div style="clear:both;"></div>
									<!--</div>-->
								</div>
							</div>

						</div>

						<div class="row">
							<div class="col-sm-5">
								<label>Conditions</label>
								<div class="row whitebg" style="border: 1px solid #dedede;min-height:7em;padding:5px;margin:0px;">
									<div class="col-xs-4">
										<label>ROAD</label>
										<br />
										<input type="checkbox" id="accidents_edit_data_road_conditions_dry" class="accidents_edit_data_postdata_chk" data-postkey="road_conditions_dry">
										<label for="accidents_edit_data_road_conditions_dry" style="margin-left:10px;" class="rolloverhand underline">Dry</label></input>
										<br />
										<input type="checkbox" id="accidents_edit_data_road_conditions_wet" class="accidents_edit_data_postdata_chk" data-postkey="road_conditions_wet">
										<label for="accidents_edit_data_road_conditions_wet" style="margin-left:10px;" class="rolloverhand underline">Wet</label></input>
										<br />
										<input type="checkbox" id="accidents_edit_data_road_conditions_snow" class="accidents_edit_data_postdata_chk" data-postkey="road_conditions_snow">
										<label for="accidents_edit_data_road_conditions_snow" style="margin-left:10px;" class="rolloverhand underline">Snow</label></input>
										<br />
										<input type="checkbox" id="accidents_edit_data_road_conditions_ice" class="accidents_edit_data_postdata_chk" data-postkey="road_conditions_ice">
										<label for="accidents_edit_data_road_conditions_ice" style="margin-left:10px;" class="rolloverhand underline">Ice</label></input>
									</div>
									<div class="col-xs-4">
										<label>WEATHER</label>
										<br />
										<input type="checkbox" id="accidents_edit_data_weather_conditions_clear" class="accidents_edit_data_postdata_chk" data-postkey="weather_conditions_clear">
										<label for="accidents_edit_data_weather_conditions_clear" style="margin-left:10px;" class="rolloverhand underline">Clear</label></input>
										<br />
										<input type="checkbox" id="accidents_edit_data_weather_conditions_rain" class="accidents_edit_data_postdata_chk" data-postkey="weather_conditions_rain">
										<label for="accidents_edit_data_weather_conditions_rain" style="margin-left:10px;" class="rolloverhand underline">Rain</label></input>
										<br />
										<input type="checkbox" id="accidents_edit_data_weather_conditions_snow" class="accidents_edit_data_postdata_chk" data-postkey="weather_conditions_snow">
										<label for="accidents_edit_data_weather_conditions_snow" style="margin-left:10px;" class="rolloverhand underline">Snow</label></input>
										<br />
										<input type="checkbox" id="accidents_edit_data_weather_conditions_fog" class="accidents_edit_data_postdata_chk" data-postkey="weather_conditions_fog">
										<label for="accidents_edit_data_weather_conditions_fog" style="margin-left:10px;" class="rolloverhand underline">Fog</label></input>
									</div>
									<div class="col-xs-4">
										<label>LIGHTING</label>
										<br />
										<input type="checkbox" id="accidents_edit_data_lighting_conditions_daylight" class="accidents_edit_data_postdata_chk" data-postkey="lighting_conditions_daylight">
										<label for="accidents_edit_data_lighting_conditions_daylight" style="margin-left:10px;" class="rolloverhand underline">Daylight</label></input>
										<br />
										<input type="checkbox" id="accidents_edit_data_lighting_conditions_dawn" class="accidents_edit_data_postdata_chk" data-postkey="lighting_conditions_dawn">
										<label for="accidents_edit_data_lighting_conditions_dawn" style="margin-left:10px;" class="rolloverhand underline">Dawn</label></input>
										<br />
										<input type="checkbox" id="accidents_edit_data_lighting_conditions_dusk" class="accidents_edit_data_postdata_chk" data-postkey="lighting_conditions_dusk">
										<label for="accidents_edit_data_lighting_conditions_dusk" style="margin-left:10px;" class="rolloverhand underline">Dusk</label></input>
										<br />
										<input type="checkbox" id="accidents_edit_data_lighting_conditions_dark" class="accidents_edit_data_postdata_chk" data-postkey="lighting_conditions_dark">
										<label for="accidents_edit_data_lighting_conditions_dark" style="margin-left:10px;" class="rolloverhand underline">Dark</label></input>
									</div>
								</div>
							</div>

							<div class="col-sm-7">
								<label>Contributing Factors</label>
								<div class="row whitebg" style="border: 1px solid #dedede;min-height:7em;padding:5px;margin:0px;">
									<div class="col-xs-4">
										<input type="checkbox" id="accidents_edit_data_contributing_factors_backing" class="accidents_edit_data_postdata_chk" data-postkey="contributing_factors_backing">
										<label for="accidents_edit_data_contributing_factors_backing" style="margin-left:10px;" class="rolloverhand underline">Backing</label></input>
										<br />
										<input type="checkbox" id="accidents_edit_data_contributing_factors_fixed_object" class="accidents_edit_data_postdata_chk" data-postkey="contributing_factors_fixed_object">
										<label for="accidents_edit_data_contributing_factors_fixed_object" style="margin-left:10px;" class="rolloverhand underline">Fixed Object</label></input>
										<br />
										<input type="checkbox" id="accidents_edit_data_contributing_factors_moving_vehicle" class="accidents_edit_data_postdata_chk" data-postkey="contributing_factors_moving_vehicle">
										<label for="accidents_edit_data_contributing_factors_moving_vehicle" style="margin-left:10px;" class="rolloverhand underline">Moving Vehicle</label></input>
										<br />
										<input type="checkbox" id="accidents_edit_data_contributing_factors_parked_vehicle" class="accidents_edit_data_postdata_chk" data-postkey="contributing_factors_parked_vehicle">
										<label for="accidents_edit_data_contributing_factors_parked_vehicle" style="margin-left:10px;" class="rolloverhand underline">Parked Vehicle</label></input>
										<br />
										<input type="checkbox" id="accidents_edit_data_contributing_factors_pedestrian" class="accidents_edit_data_postdata_chk" data-postkey="contributing_factors_pedestrian">
										<label for="accidents_edit_data_contributing_factors_pedestrian" style="margin-left:10px;" class="rolloverhand underline">Pedestrian</label></input>
									</div>

									
									<div class="col-xs-4">
										<input type="checkbox" id="accidents_edit_data_contributing_factors_head_on" class="accidents_edit_data_postdata_chk" data-postkey="contributing_factors_head_on">
										<label for="accidents_edit_data_contributing_factors_head_on" style="margin-left:10px;" class="rolloverhand underline">Head-On</label></input>
										<br />
										<input type="checkbox" id="accidents_edit_data_contributing_factors_turning" class="accidents_edit_data_postdata_chk" data-postkey="contributing_factors_turning">
										<label for="accidents_edit_data_contributing_factors_turning" style="margin-left:10px;" class="rolloverhand underline">Turning</label></input>
										<br />
										<input type="checkbox" id="accidents_edit_data_contributing_factors_road_conditions" class="accidents_edit_data_postdata_chk" data-postkey="contributing_factors_road_conditions">
										<label for="accidents_edit_data_contributing_factors_road_conditions" style="margin-left:10px;" class="rolloverhand underline">Road Conditions</label></input>
										<br />
										<input type="checkbox" id="accidents_edit_data_contributing_factors_speed" class="accidents_edit_data_postdata_chk" data-postkey="contributing_factors_speed">
										<label for="accidents_edit_data_contributing_factors_speed" style="margin-left:10px;" class="rolloverhand underline">Speed</label></input>
										<br />
										<input type="checkbox" id="accidents_edit_data_contributing_factors_mechanical" class="accidents_edit_data_postdata_chk" data-postkey="contributing_factors_mechanical">
										<label for="accidents_edit_data_contributing_factors_mechanical" style="margin-left:10px;" class="rolloverhand underline">Mechanical</label></input>
									</div>
								
								
									<div class="col-xs-4">
										<input type="checkbox" id="accidents_edit_data_contributing_factors_spacing" class="accidents_edit_data_postdata_chk" data-postkey="contributing_factors_spacing">
										<label for="accidents_edit_data_contributing_factors_spacing" style="margin-left:10px;" class="rolloverhand underline">Spacing</label></input>
										<br />
										<input type="checkbox" id="accidents_edit_data_contributing_factors_other" class="accidents_edit_data_postdata_chk" data-postkey="contributing_factors_other">
										<label for="accidents_edit_data_contributing_factors_other" style="margin-left:10px;" class="rolloverhand underline">Other</label></input>
										<br />

										<label>Comments</label>
										<div id="accidents_edit_data_contributing_factors_comments" class="clearhtml whitebg accidents_edit_data_postdata_html" contenteditable="true" style="border: 1px solid #dedede;min-height:5em;padding:5px;" data-postkey="contributing_factors_comments"></div>

									</div>
								
								
								</div>
							</div>

						</div>

					</div>

					<div id="accidents_edit_data_employee_info_small" class="div_parent_frame roundcorners">

						<div style="width:50%;float:left;">
							<h4><img src="img/dude4.png" style="height:2em;margin-right:10px;">EMPLOYEE INFORMATION</h4>
						</div>

						<div style="width:50%;text-align:right;float:right;">
							<button id="accidents_edit_data_employee_info_btnedit"class="btn btn-success btnwidth">View/Edit</button>
						</div>
						<div style="clear:both;"></div>
					</div>

					<div class="row div_parent_frame roundcorners" id="accidents_edit_data_employee_info_parent" style="display:none;">

						<div class="col-md-6">
							<!--<div class="row div_parent_frame roundcorners">-->
												
							<div style="float:left;width:60%;">
								<h4><img src="img/dude4.png" style="height:2em;margin-right:10px;"></img>EMPLOYEE DRIVER INFORMATION</h4>
							</div>
							<div style="float:right;text-align:right;width:40%;">
								<button class="btn btn-info btnwidth accidents_edit_data_employee_info_btnhide">Hide</button>
							</div>
							<div style="clear:both;"></div>
							
							
								<div class="col-xs-6">

									<label for="accidents_edit_data_employee_driver">Employee Name</label>
									<select id="accidents_edit_data_employee_driver" class="form-control userlist accidents_edit_data_postdata_val" data-postkey="employee_driver"></select>

									<div id="accidents_edit_data_employee_info_div"  class="row" style="display:none1;padding:10px 0px;">
										<br />
										<div class="col-md-12">
											<span class="bold">Badge No:</span> <span id="accidents_edit_data_employee_badgenumber" class="spandata accidents_edit_data_postdata_html" data-postkey="employee_badgenumber"></span>
											<br />
											<span class="bold">Exp:</span> <span id="accidents_edit_data_employee_badgeexpirationdate" class="spandata accidents_edit_data_postdata_html" data-postkey="employee_badgeexpirationdate"></span>
											<br /><br />
											<span class="bold">Driver License No:</span> <span id="accidents_edit_data_employee_driverlicensenumber" class="spandata accidents_edit_data_postdata_html"  data-postkey="employee_driverlicensenumber"></span>
											<br />
											<span class="bold">Exp:</span> <span id="accidents_edit_data_employee_driverlicenseexpiration" class="spandata accidents_edit_data_postdata_html" data-postkey="employee_driverlicenseexpiration"></span>
											<br /><br />
											<span class="bold">Address:</span> <span id="accidents_edit_data_employee_address" class="spandata accidents_edit_data_postdata_html" data-postkey="employee_address"></span>
											<br /><br />
											<span class="bold">Phone:</span> <span id="accidents_edit_data_employee_phone" class="spandata accidents_edit_data_postdata_html" data-postkey="employee_phone"></span>
										</div>

									</div>
								</div>

								<div class="col-xs-6">

									<label for="accidents_edit_data_employee_driver_injured">Driver Injured</label>
									<select id="accidents_edit_data_employee_driver_injured" class="form-control accidents_edit_data_postdata_val clearvalno" data-postkey="employee_driver_injured">
										<option value="No">No</option>
										<option value="Yes">Yes</option>
									</select>
									<label for="accidents_edit_data_employee_driver_cited">Driver Cited</label>
									<select id="accidents_edit_data_employee_driver_cited" class="form-control accidents_edit_data_postdata_val clearvalno" data-postkey="employee_driver_cited">
										<option value="No">No</option>
										<option value="Yes">Yes</option>
									</select>
									<label for="accidents_edit_data_employee_driver_drug_test">Drug Test</label>
									<select id="accidents_edit_data_employee_driver_drug_test" class="form-control accidents_edit_data_postdata_val clearvalno" data-postkey="employee_driver_drug_test">
										<option value="No">No</option>
										<option value="Yes">Yes</option>
									</select>

								</div>

								<div class="col-md-12" style="margin-bottom:20px;">
									<label>Comments</label>
									<div class="accidents_edit_data_postdata_html whitebg" contenteditable="true" style="border: 1px solid #dedede;min-height:9.75em;padding:5px;" data-postkey="employee_driver_comments"></div>
								</div>

							<!--</div>-->

						</div>

						<div class="col-md-6">
							<!--<div class="row div_parent_frame roundcorners">-->
							<div style="float:left;width:60%;">
								<h4><img src="img/minibus_grey.png" style="height:2em;margin-right:10px;"></img>EMPLOYEE VEHICLE INFORMATION</h4>
							</div>
							<div style="float:right;text-align:right;width:40%;">
								<button class="btn btn-info btnwidth accidents_edit_data_employee_info_btnhide">Hide</button>
							</div>
							<div style="clear:both;"></div>

							<div class="col-md-6">

								<label for="accidents_edit_data_employee_vehicletype">Vehicle Type</label>
								<select id="accidents_edit_data_employee_vehicletype" class="form-control accidents_edit_data_postdata_val vehicletypelist" data-postkey="employee_vehicletype"></select>
								<label for="accidents_edit_data_employee_vehiclename">Vehicle Name</label>
								<select id="accidents_edit_data_employee_vehiclename" class="form-control accidents_edit_data_postdata_val"  data-postkey="vehiclename"  data-postkey="employee_vehiclename"></select>

								<div class="row">
									<div class="col-md-12">
										<div id="accidents_edit_data_employee_vehicle_info_div"  class="row" style="padding:10px 0px;">
											<br />
											<div class="col-md-12">
												<span class="bold">VIN:</span> <span id="accidents_edit_data_employee_vehicle_vin" class="spandata accidents_edit_data_postdata_html" data-postkey="employee_vehicle_vin"></span>
												<br /><br />
												<span class="bold">License Plate No:</span> <span id="accidents_edit_data_employee_vehicle_licenseplate" class="spandata accidents_edit_data_postdata_html" data-postkey="employee_vehicle_licenseplate"></span>
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="col-md-6">
								<label for="accidents_edit_data_employee_vehicle_damaged">Vehicle Damaged</label>
								<select id="accidents_edit_data_employee_vehicle_damaged" class="form-control accidents_edit_data_postdata_val clearvalno"  data-postkey="vehicle_damaged">
									<option value="No">No</option>
									<option value="Yes">Yes</option>
								</select>
								<label for="accidents_edit_data_employee_vehicle_drivable">Vehicle Drivable</label>
								<select id="accidents_edit_data_employee_vehicle_drivable" class="form-control accidents_edit_data_postdata_val clearvalno"  data-postkey="vehicle_drivable">
									<option value="No">No</option>
									<option value="Yes">Yes</option>
								</select>

								<br />
								<div style="text-align:center;" class="damagediagramdiv">
									<img id="accidents_edit_data_employee_vehicle_damage_diagram_thumbnail" src="img/accidents_edit_data_employee_vehicle_damage_diagram.png" data-canvasid="accidents_edit_data_employee_vehicle_damage_diagram" style="max-height:100%;width:100%;" class="showcanvas thumbnail"></img>
									<input id="accidents_edit_data_employee_vehicle_damage_diagram_coordinates" class="diagramcoordinates accidents_edit_data_postdata_val" data-postkey="damagediagramcoordinates"style="display:none;"></input>
								</div>
							</div>
							<div class="col-md-12">
								<label>Comments</label>
								<div class="accidents_edit_data_postdata_html whitebg" contenteditable="true" style="border: 1px solid #dedede;min-height:4.75em;padding:5px;" data-postkey="employee_vehicle_comments"></div>
							</div>

						<!--</div>-->
						</div>
					</div>


					<div id="accidents_edit_data_police_report_small" class="div_parent_frame roundcorners">

						<div style="width:50%;float:left;">
							<h4><img src="img/policeman.png" style="height:2em;margin-right:10px;">POLICE REPORT</h4>
						</div>

						<div style="width:50%;text-align:right;float:right;">
							<button id="accidents_edit_data_police_report_btnedit"class="btn btn-success btnwidth">View/Edit</button>
						</div>
						<div style="clear:both;"></div>
					</div>

					<div id="accidents_edit_data_police_report" style="display:none;" class="row div_parent_frame roundcorners">
						<div style="float:left;width:60%;">
							<h4><img src="img/policeman.png" style="height:2em;margin-right:10px;"></img>POLICE REPORT</h4>
						</div>
						<div style="float:right;text-align:right;width:40%;">
							<button id="accidents_edit_data_police_report_btnhide" class="btn btn-info btnwidth">Hide</button>
						</div>
						<div style="clear:both;"></div>

						<div class="col-md-3">
							<label for="accidents_edit_data_police_report_reportnumber">Report Number</label>
							<input type="text" id="accidents_edit_data_police_report_reportnumber" class="form-control clearval accidents_edit_data_postdata_val" data-postkey="police_report_reportnumber"></input>
						</div>
						<div class="col-md-3">
							<label for="accidents_edit_data_police_report_policedepartment">Police Dept</label>
							<input type="text" id="accidents_edit_data_police_report_policedepartment" class="form-control clearval accidents_edit_data_postdata_val" data-postkey="police_report_policedepartment"></input>
						</div>
						<div class="col-md-3">
							<label for="accidents_edit_data_police_report_officername">Officer Name</label>
							<input type="text" id="accidents_edit_data_police_report_officername" class="form-control clearval accidents_edit_data_postdata_val" data-postkey="police_report_officername"></input>
						</div>
						<div class="col-md-3">
							<label for="accidents_edit_data_police_report_badgenumber">Badge Number</label>
							<input type="text" id="accidents_edit_data_police_report_badgenumber" class="form-control clearval accidents_edit_data_postdata_val" data-postkey="police_report_badgenumber"></input>
						</div>
						<div class="col-md-12">
							<label>Comments</label>
							<div id="accidents_edit_data_police_report_comments" class="clearhtml whitebg accidents_edit_data_postdata_html" contenteditable="true" style="border: 1px solid #dedede;min-height:4em;padding:5px;" data-postkey="police_report_comments"></div>
						</div>

					</div>

					<div id="accidents_edit_data_supervisor_statement_small" class="div_parent_frame roundcorners">

						<div style="width:50%;float:left;">
							<h4><img src="img/businessman.png" style="height:2em;margin-right:10px;">SUPERVISOR STATEMENT</h4>
						</div>

						<div style="width:50%;text-align:right;float:right;">
							<button id="accidents_edit_data_supervisor_statement_btnedit"class="btn btn-success btnwidth">View/Edit</button>
						</div>
						<div style="clear:both;"></div>
					</div>

					<div id="accidents_edit_data_supervisor_statement" style="display:none;" class="row div_parent_frame roundcorners">
						<div style="float:left;width:60%;">
							<h4><img src="img/businessman.png" style="height:2em;margin-right:10px;"></img>SUPERVISOR STATEMENT</h4>
						</div>
						<div style="float:right;text-align:right;width:40%;">
							<button id="accidents_edit_data_supervisor_statement_btnhide" class="btn btn-info btnwidth">Hide</button>
						</div>
						<div style="clear:both;"></div>

						<div class="col-md-3">
							<label for="accidents_edit_data_supervisor_statement_supervisorname">Type Supervisor Name</label>
							<input type="text" class="form-control accidents_edit_data_postdata_val" id="accidents_edit_data_supervisor_statement_supervisorname" data-postkey="supervisor_statement_supervisorname"></input>
							<br />
							<label for="accidents_edit_data_supervisor_statement_date">Date</label>
							<input type="text" class="form-control datepicker accidents_edit_data_postdata_val" id="accidents_edit_data_supervisor_statement_date" data-postkey="supervisor_statement_date"></input>
						</div>
						<div class="col-md-9">
							<label>Statement</label>
								<div id="accidents_edit_data_supervisor_statement_content" class="accidents_edit_data_postdata_html whitebg" contenteditable="true" style="border: 1px solid #dedede;min-height:9em;padding:5px;"  data-postkey="supervisor_statement_content"></div>

						</div>
					</div>

					<div id="accidents_edit_data_other_driver_table_div" class="row div_parent_frame roundcorners">
						<div style="width:50%;float:left;">
							<h4><img src="img/dude3.png" style="height:2em;margin-right:10px;">OTHER DRIVERS/VEHICLES LIST</h4>
						</div>
						<div style="width:50%;text-align:right;float:right;">
							<button id="accidents_edit_data_other_driver_btnnew"class="btn btn-success btnwidth">Add Driver/Vehicle</button>
						</div>
						<div style="clear:both;"></div>
						<table id="accidents_edit_data_other_driver_table" style="display:none;" class="tablesorter showtable">
							<thead class="table_header_color">
								<tr>
								<th>Driver Name</th>
								<th>Vehicle Make and Model</th>
								<th>Driver Injured</th>
								<th>Driver Cited</th>
								<th>Vehicle Damaged</th>
								<th></th>
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>

					</div>

					<div class="row div_parent_frame roundcorners" id="accidents_edit_data_other_driver_edit_div" style="display:none;">

						<input id="accidents_edit_data_other_driver_id" class="accidents_edit_data_other_driver_data_val" style="display:none;"></input>

						<div class="col-md-6">
							<!--<div class="row div_parent_frame roundcorners">-->
								<div style="float:left;width:60%;">
									<h4><img src="img/dude3.png" style="height:2em;margin-right:10px;"></img>OTHER DRIVER INFORMATION</h4>
								</div>							
								
								<div style="float:right;text-align:right;width:40%;">
									<button class="btn btn-danger accidents_edit_data_other_driver_btnclose">Close</button>
									<button class="btn btn-info accidents_edit_data_other_driver_btnsave btnapply" data-scrolltoid="accidents_edit_data_other_driver_edit_div">Apply</button>
								</div>
								<div style="clear:both;"></div>

								<div class="col-md-6">
									<label for="accidents_edit_data_other_driver_name">Other Driver Name</label>
									<input type="text" id="accidents_edit_data_other_driver_name" class="form-control clearval accidents_edit_data_other_driver_data_val"></input>
									<label for="accidents_edit_data_other_driver_address">Address</label>
									<input type="text" id="accidents_edit_data_other_driver_address" class="form-control clearval accidents_edit_data_other_driver_data_val"></input>
									<label for="accidents_edit_data_other_driver_city">City</label>
									<input type="text" id="accidents_edit_data_other_driver_city" class="form-control clearval accidents_edit_data_other_driver_data_val"></input>

									<div>

										<div style="width:45%;float:left;">
											<label for="accidents_edit_data_other_driver_state">State</label>
											<select type="text" id="accidents_edit_data_other_driver_state" class="form-control statelist clearval accidents_edit_data_other_driver_data_val"></select>
										</div>
										<div style="width:45%;float:right;">
											<label for="accidents_edit_data_other_driver_zip">ZIP</label>
											<input type="text" id="accidents_edit_data_other_driver_zip" class="form-control zipmask clearval accidents_edit_data_other_driver_data_val"></input>
										</div>
										<div style="clear:both;"></div>
									</div>
									<div>
										<div style="width:45%;float:left;">
											<label for="accidents_edit_data_other_driver_homephone">Home Phone</label>
											<input type="text" id="accidents_edit_data_other_driver_homephone" class="form-control phonemask clearval accidents_edit_data_other_driver_data_val"></input>
										</div>
										<div style="width:45%;float:right;">
											<label for="accidents_edit_data_other_driver_workphone">Work Phone</label>
											<input type="text" id="accidents_edit_data_other_driver_workphone" class="form-control phonemask clearval accidents_edit_data_other_driver_data_val"></input>
										</div>
										<div style="clear:both;"></div>
									</div>
								</div>

								<div class="col-md-6">
									<div>
										<div style="width:55%;float:left;">
											<label for="accidents_edit_data_other_driver_driverlicense">Driver License</label>
											<input type="text" id="accidents_edit_data_other_driver_driverlicense" class="form-control clearval accidents_edit_data_other_driver_data_val"></input>
										</div>
										<div style="width:40%;float:right;">
											<label for="accidents_edit_data_other_driver_driverlicenseexpiration">Exp</label>
											<input type="text" id="accidents_edit_data_other_driver_driverlicenseexpiration" class="form-control datepicker clearval accidents_edit_data_other_driver_data_val"></input>
										</div>
										<div style="clear:both;"></div>
									</div>
									<div>
										<div style="width:45%;float:left;">
											<label for="accidents_edit_data_other_driver_nameofinsurance">Insurance Co.</label>
									<input type="text" id="accidents_edit_data_other_driver_nameofinsurance" class="form-control clearval accidents_edit_data_other_driver_data_val"></input>

										</div>
										<div style="width:45%;float:right;">
											<label for="accidents_edit_data_other_driver_insurancephone">Phone No.</label>
											<input type="text" id="accidents_edit_data_other_driver_insurancephone" class="form-control phonemask clearval accidents_edit_data_other_driver_data_val"></input>

										</div>
										<div style="clear:both;"></div>
									</div>

									<label for="accidents_edit_data_other_driver_insurancepolicynumber">Policy No.</label>
									<input type="text" id="accidents_edit_data_other_driver_insurancepolicynumber" class="form-control clearval accidents_edit_data_other_driver_data_val"></input>


									<div>
										<div style="width:45%;float:left;">
											<label for="accidents_edit_data_other_driver_driverinjured">Driver Injured</label>
											<select id="accidents_edit_data_other_driver_driverinjured" class="form-control clearvalno accidents_edit_data_other_driver_data_val">
												<option value="No">No</option>
												<option value="Yes">Yes</option>
											</select>
										</div>
										<div style="width:45%;float:right;">
											<label for="accidents_edit_data_other_driver_drivercited">Driver Cited</label>
											<select id="accidents_edit_data_other_driver_drivercited" class="form-control clearvalno accidents_edit_data_other_driver_data_val">
												<option value="No">No</option>
												<option value="Yes">Yes</option>
											</select>
										</div>

										<div style="clear:both;"></div>
									</div>

									<label for="accidents_edit_data_other_driver_occupantcount">How Many in Vehicle</label>
									<select id="accidents_edit_data_other_driver_occupantcount" class="form-control clearval accidents_edit_data_other_driver_data_val">
										<option value="">- Select One -</option>
										<option value="1">1</option>
										<option value="2">2</option>
										<option value="3">3</option>
										<option value="4">4</option>
										<option value="5">5</option>
										<option value="6">6</option>
										<option value="7">7</option>
										<option value="8">8</option>
										<option value="9">9</option>
										<option value="10">10</option>
									</select>

								</div>

								<div class="col-md-12">
									<label>Comments</label>
									<div id="accidents_edit_data_other_driver_drivercomments" class="clearhtml whitebg accidents_edit_data_other_driver_data_html" contenteditable="true" style="border: 1px solid #dedede;min-height:4em;padding:5px;margin-bottom:20px;"></div>
								</div>

							<!--</div>-->

						</div>

						<div class="col-md-6">
							<!--<div class="row div_parent_frame roundcorners">-->
								<div style="float:left;width:60%;">
									<h4><img src="img/car_sedan_blue.png" style="height:2em;margin-right:10px;"></img>OTHER VEHICLE INFORMATION</h4>
								</div>
								<div style="float:right;text-align:right;width:40%;">
									<button class="btn btn-danger accidents_edit_data_other_driver_btnclose">Close</button>
									<button class="btn btn-info accidents_edit_data_other_driver_btnsave btnapply" data-scrolltoid="accidents_edit_data_other_driver_edit_div">Apply</button>
								</div>
								<div style="clear:both;"></div>
								<div class="col-md-6">

									<label for="accidents_edit_data_other_driver_vehicletype">Make and Model</label>
									<input type="text" id="accidents_edit_data_other_driver_vehicletype" class="form-control accidents_edit_data_other_driver_data_val clearval"></input>

									<label for="accidents_edit_data_other_driver_vehicleyear">Year</label>
									<input type="text" id="accidents_edit_data_other_driver_vehicleyear" class="form-control accidents_edit_data_other_driver_data_val yearmask clearval"></input>

									<label for="accidents_edit_data_other_driver_vehiclevin">VIN</label>
									<input type="text" id="accidents_edit_data_other_driver_vehiclevin" class="form-control accidents_edit_data_other_driver_data_val clearval"></input>

									<label for="accidents_edit_data_other_driver_vehiclelicenseplate">License Plate</label>
									<input type="text" id="accidents_edit_data_other_driver_vehiclelicenseplate" class="form-control accidents_edit_data_other_driver_data_val clearval"></input>

								</div>

								<div class="col-md-6">
									<label for="accidents_edit_data_other_driver_vehicledamaged">Vehicle Damaged</label>
									<select id="accidents_edit_data_other_driver_vehicledamaged" class="form-control clearvalno accidents_edit_data_other_driver_data_val">
										<option value="No">No</option>
										<option value="Yes">Yes</option>
									</select>
									<label for="accidents_edit_data_other_driver_vehicledrivable">Vehicle Drivable</label>
									<select id="accidents_edit_data_other_driver_vehicledrivable" class="form-control clearvalno accidents_edit_data_other_driver_data_val">
										<option value="No">No</option>
										<option value="Yes">Yes</option>
									</select>

									<br />
									<div style="text-align:center;" class="damagediagramdiv">
										<img id="accidents_edit_data_other_driver_vehicle_damage_diagram_thumbnail" src="img/accidents_edit_data_other_driver_vehicle_damage_diagram.png" data-canvasid="accidents_edit_data_other_driver_vehicle_damage_diagram" style="max-height:100%;width:100%;" class="showcanvas thumbnail"></img>
										<input id="accidents_edit_data_other_driver_vehicle_damage_diagram_coordinates" class="diagramcoordinates accidents_edit_data_other_driver_data_val" style="display:none;"></input>
									</div>
								</div>

								<div class="col-md-12">
									<label>Comments</label>
									<div id="accidents_edit_data_other_driver_vehiclecomments" class="clearhtml whitebg accidents_edit_data_other_driver_data_html" contenteditable="true" style="border: 1px solid #dedede;min-height:4.5em;padding:5px;"></div>
								</div>

							<!--</div>-->

						</div>

					</div>

					<div id="accidents_edit_data_injury_table_div" class="row div_parent_frame roundcorners">
						<div style="width:50%;float:left;">
							<h4><img src="img/band_aid.png" style="height:2em;margin-right:10px;">INJURIES LIST</h4>
						</div>
						<div style="width:50%;text-align:right;float:right;">
							<button id="accidents_edit_data_injury_btnnew"class="btn btn-success btnwidth">Add Injury</button>
						</div>
						<div style="clear:both;"></div>
						<table id="accidents_edit_data_injury_table" style="display:none;" class="tablesorter showtable">
							<thead class="table_header_color">
								<tr>
								<th>Name</th>
								<th>Age</th>
								<th>Phone</th>
								<th>Role</th>
								<th></th>
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
					</div>

					<div class="row div_parent_frame roundcorners" id="accidents_edit_data_injury_edit_div" style="display:none;" >

						<input id="accidents_edit_data_injury_id" class="accidents_edit_data_injury_data_val" style="display:none;"></input>

						<div class="col-md-12">
							<!--<div class="row div_parent_frame roundcorners">-->
								<div style="float:left;width:60%;">
									<h4><img src="img/band_aid.png" style="height:2em;margin-right:10px;"></img>INJURED PERSON INFORMATION</h4>
								</div>
								<div style="float:right;text-align:right;width:40%;">
									<button class="btn btn-danger accidents_edit_data_injury_btnclose">Close</button>
									<button class="btn btn-info accidents_edit_data_injury_btnsave btnapply" data-scrolltoid="accidents_edit_data_injury_edit_div">Apply</button>
								</div>
								<div style="clear:both;"></div>
								<div class="col-md-3">
									<label for="accidents_edit_data_injury_name">Name</label>
									<input type="text" id="accidents_edit_data_injury_name" class="form-control clearval accidents_edit_data_injury_data_val"></input>
								</div>
								<div class="col-md-3">
									<label for="accidents_edit_data_injury_age">Age</label>
									<input type="text" id="accidents_edit_data_injury_age" class="form-control clearval accidents_edit_data_injury_data_val agemask"></input>
								</div>
								<div class="col-md-3">
									<label for="accidents_edit_data_injury_phone">Phone</label>
									<input type="text" id="accidents_edit_data_injury_phone" class="form-control clearval accidents_edit_data_injury_data_val phonemask"></input>
								</div>
								<div class="col-md-3">
									<label for="accidents_edit_data_injury_role">Role</label>
									<select id="accidents_edit_data_injury_role" class="form-control clearval accidents_edit_data_injury_data_val">
										<option value="">- Select One -</option>
										<option value="Shuttle Driver">Shuttle Driver</option>
										<option value="Shuttle Passenger">Shuttle Passenger</option>
										<option value="Other Driver">Other Driver</option>
										<option value="Other Passenger">Other Passenger</option>
										<option value="Pedestrian">Pedestrian</option>
									</select>
								</div>


								<div class="col-md-5">
									<label for="accidents_edit_data_injury_address">Address</label>
									<input type="text" id="accidents_edit_data_injury_address" class="form-control clearval accidents_edit_data_injury_data_val"></input>
								</div>
								<div class="col-md-3">
									<label for="accidents_edit_data_injury_city">City</label>
									<input type="text" id="accidents_edit_data_injury_city" class="form-control clearval accidents_edit_data_injury_data_val"></input>
								</div>
								<div class="col-md-2">
									<label for="accidents_edit_data_injury_state">State</label>
									<select id="accidents_edit_data_injury_state" class="form-control clearval accidents_edit_data_injury_data_val statelist"></select>
								</div>
								<div class="col-md-2">
									<label for="accidents_edit_data_injury_zip">ZIP</label>
									<input type="text" id="accidents_edit_data_injury_zip" class="form-control clearval accidents_edit_data_injury_data_val zipmask"></input>
								</div>

								<div class="col-md-12">
									<label>Comments</label>
									<div id="accidents_edit_data_injury_comments" class="clearhtml whitebg accidents_edit_data_injury_data_html" contenteditable="true" style="border: 1px solid #dedede;min-height:4em;padding:5px;"></div>
								</div>
							<!--</div>-->
						</div>

					</div>

					<div id="accidents_edit_data_witness_table_div" class="row div_parent_frame roundcorners">
						<div style="width:50%;float:left;">
						<h4><img src="img/eyeglasses.png" style="height:2em;margin-right:10px;">WITNESS LIST</h4>
						</div>
						<div style="width:50%;text-align:right;float:right;">
							<button id="accidents_edit_data_witness_btnnew"class="btn btn-success btnwidth">Add Witness</button>
						</div>
						<div style="clear:both;"></div>
						<table id="accidents_edit_data_witness_table" class="tablesorter showtable" style="display:none;">
							<thead class="table_header_color">
								<tr>
									<th>Name</th><th>Day Phone</th><th>Evening Phone</th><th></th>
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
					</div>

					<div class="row div_parent_frame roundcorners" id="accidents_edit_data_witness_edit_div" style="display:none;" >

						<input id="accidents_edit_data_witness_id" class="accidents_edit_data_witness_data_val" style="display:none;"></input>

						<div class="col-md-12">
							<!--<div class="row div_parent_frame roundcorners">-->
								<div style="float:left;width:60%;">
									<h4><img src="img/eyeglasses.png" style="height:2em;margin-right:10px;"></img>WITNESS INFORMATION</h4>
								</div>
								<div style="float:right;text-align:right;width:40%;">
									<button class="btn btn-danger accidents_edit_data_witness_btnclose">Close</button>
									<button class="btn btn-info accidents_edit_data_witness_btnsave btnapply" data-scrolltoid="accidents_edit_data_witness_edit_div">Apply</button>
								</div>
								<div style="clear:both;"></div>
								<div class="col-md-4">
									<label for="accidents_edit_data_witness_name">Name</label>
									<input type="text" id="accidents_edit_data_witness_name" class="form-control clearval accidents_edit_data_witness_data_val"></input>
								</div>
								<div class="col-md-4">
									<label for="accidents_edit_data_witness_age">Day Phone</label>
									<input type="text" id="accidents_edit_data_witness_dayphone" class="form-control clearval accidents_edit_data_witness_data_val phonemask"></input>
								</div>
								<div class="col-md-4">
									<label for="accidents_edit_data_witness_phone">Evening Phone</label>
									<input type="text" id="accidents_edit_data_witness_eveningphone" class="form-control clearval accidents_edit_data_witness_data_val phonemask"></input>
								</div>


								<div class="col-md-5">
									<label for="accidents_edit_data_witness_address">Address</label>
									<input type="text" id="accidents_edit_data_witness_address" class="form-control clearval accidents_edit_data_witness_data_val"></input>
								</div>
								<div class="col-md-3">
									<label for="accidents_edit_data_witness_city">City</label>
									<input type="text" id="accidents_edit_data_witness_city" class="form-control clearval accidents_edit_data_witness_data_val"></input>
								</div>
								<div class="col-md-2">
									<label for="accidents_edit_data_witness_state">State</label>
									<select id="accidents_edit_data_witness_state" class="form-control clearval accidents_edit_data_witness_data_val statelist"></select>
								</div>
								<div class="col-md-2">
									<label for="accidents_edit_data_witness_zip">ZIP</label>
									<input type="text" id="accidents_edit_data_witness_zip" class="form-control clearval accidents_edit_data_witness_data_val zipmask"></input>
								</div>

								<div class="col-md-12">
									<label>Comments</label>
									<div id="accidents_edit_data_witness_comments" class="clearhtml whitebg accidents_edit_data_witness_data_html" contenteditable="true" style="border: 1px solid #dedede;min-height:4em;padding:5px;"></div>
								</div>
							<!--</div>-->
						</div>
					</div>

				</div>

			</div> <!-- /maincontainer -->

			<div id="canvascontainer" style="display:none;">

			<!-- Preload the images for the canvases. Required because the canvases draw the background so quickly that if the image hasn't been loaded yet and is only getting loaded when the canvas loads, the canvas background is blank. This makes sure that the DOM has downloaded the images into cache already, so the canvases have them instantly available. -->
				<img style="display:none;" src="img/x.png"></img>
				<img style="display:none;" src="img/accidents_edit_data_employee_vehicle_damage_diagram.png"></img>
				<img style="display:none;" src="img/accidents_edit_data_other_driver_vehicle_damage_diagram.png"></img>
			<!-- END Preload the images for the canvases -->

				<div id="accidents_edit_data_employee_vehicle_damage_diagram_div" class="damage_diagram_div">
					<div class="damagediagram">
						<canvas id="accidents_edit_data_employee_vehicle_damage_diagram" class="damagediagramcanvas" width="600px" height="300px"></canvas>
					</div>
					<div style="width:600px;margin-top:20px;">
						<div style="width:50%;float:left;">
							<button class="btn btn-danger clearcanvas btnwidth" data-canvasid="accidents_edit_data_employee_vehicle_damage_diagram">Clear</button>
						</div>
						<div style="width:50%;float:right;text-align:right;">
							<button class="btn btn-primary savecanvas btnwidth" data-scrolltoid="accidents_edit_data_employee_info_parent" data-canvasid="accidents_edit_data_employee_vehicle_damage_diagram">Apply</button>
						</div>
						<div style="clear:both;"></div>
					</div>
				</div>


				<div id="accidents_edit_data_other_driver_vehicle_damage_diagram_div" class="damage_diagram_div">
					<div class="damagediagram">
						<canvas id="accidents_edit_data_other_driver_vehicle_damage_diagram" class="damagediagramcanvas" width="600px" height="300px"></canvas>
					</div>
					<div style="width:600px;margin-top:20px;">
						<div style="width:50%;float:left;">
							<button class="btn btn-danger clearcanvas btnwidth" data-canvasid="accidents_edit_data_other_driver_vehicle_damage_diagram">Clear</button>
						</div>
						<div style="width:50%;float:right;text-align:right;">
							<button class="btn btn-primary savecanvas btnwidth" data-scrolltoid="accidents_edit_data_other_driver_edit_div" data-canvasid="accidents_edit_data_other_driver_vehicle_damage_diagram">Apply</button>
						</div>
						<div style="clear:both;"></div>
					</div>
				</div>

			</div>

			<div id="linkedfilescontainer" class="div_parent_frame roundcorners" style="display:none;">

				<div class="row">

					<div class="col-sm-3">
						<label for="accidents_edit_data_linked_files_filetype">File Type</label>
						<select id="accidents_edit_data_linked_files_filetype" class="form-control" disabled>
						</select>
					</div>

					<div class="col-sm-3 rightalign">
					<br /><br />
						<!-- The container for the uploaded files -->
						<span type="submit" id="accidents_edit_data_btnupload" class="btn btn-success fileinput-button btnupload">
							<i class="glyphicon glyphicon-plus"></i>
							<span>Select files...</span>
							<!-- The file input field used as target for the file upload widget -->
							<input id="accidents_edit_data_fileupload" style="display:none1;" class="fileupload " type="file" name="files[]" multiple>
						</span>
						<br />
						<br />
						<!-- The global progress bar -->
						<div id="progress" class="progress" style="width:100%;display:none;">
							<div class="progress-bar progress-bar-success"></div>
						</div>
						<div id="files" class="files"></div>
					</div>

					<div id="accidents_edit_data_linked_files_div" class="col-sm-6">
						<div style="text-align:right;">
							<button id="accidents_edit_data_linked_files_btnclose" class="btn btn-danger">Done</button>
						</div>
						<br />
						<table id="accidents_edit_data_linked_files_table" class="tablesorter">
							<thead class="table_header_color"><tr><th>Filename</th><th></th></tr></thead>
							<tbody></tbody>
						</table>

					</div>

				</div>

			</div>

			<?php echo footer(); ?>

		</div> <!-- /container -->

		<?php jsscripts("accidents"); ?>

	</body>

</html>
