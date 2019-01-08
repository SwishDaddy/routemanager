<?php
	include "api/includes.php";
	if (!logincheck()) {
		header("Location: /");
		die();
	}
	$arrroles = rolecheck();
	if (!in_array ("comments" , $arrroles)) {
		header("Location: /");
		die();
	}
	headermarkup("Comments");


?>

	<body>

	<?php globalnav(); ?>

		<div class="container" style="margin-top:50px;display:none;">

		<div class="row">
			<div class="col-md-6">
				<button id="comments_save_data_btn" class="btn btn-success btnwidth comments_edit_data" style="display:none;">Save Comment</button>
				<button id="comments_view_data_btn" class="btn btn-primary btnwidth comments_edit_data" style="display:none;">Close</button>
				<button id="comments_save_data_and_new_btn" class="btn btn-warning btnwidth comments_edit_data" style="display:none;">Save and New</button>
				<button id="comments_new_data_btn" class="btn btn-primary btnwidth comments_view_data">New Comment</button>
			</div>

			<div class="col-md-6" style="text-align:right">
				<button id="comments_view_data_delete_btn" class="btn btn-danger btnwidth comments_edit_data" style="display:none;">Delete Comment</button>

			</div>

		</div>

		<div id="comments_edit_data_div" class="comments_edit_data" style="display:none;">

			<input id="comments_edit_data_commentid" style="display:none;"></input>

			<div class="row" style="margin-top:20px;">
				<div class="col-md-2">
					<label for="comments_edit_data_date_of_incident">Date<span style="visibility:hidden;">.</span>of<span style="visibility:hidden;">.</span>Incident</label>
					<input id="comments_edit_data_date_of_incident" class="form-control datepicker" />
				</div>
				<div class="col-md-3">
					<label for="comments_edit_data_customer_name">Customer<span style="visibility:hidden;">.</span>Name</label>
					<input id="comments_edit_data_customer_name" class="form-control" />
				</div>
				<div class="col-md-3">
					<label for="comments_edit_data_employee_involved">Employee<span style="visibility:hidden;">.</span>Involved</label>
					<select id="comments_edit_data_employee_involved" class="form-control"></select>
				</div>
				<div class="col-md-2">
					<label for="comments_edit_data_location_of_incident">Location</label>
					<select id="comments_edit_data_location_of_incident" class="form-control"></select>
				</div>
				<div class="col-md-2">
					<label for="comments_edit_data_vehicle_name">Vehicle<span style="visibility:hidden;">.</span>Name</label>
					<select id="comments_edit_data_vehicle_name" class="form-control"></select>
				</div>
			</div>

			<div class="row" style="margin-top:20px;">
				<div class="col-md-2" style="min-width:200px;">
					<br />
					<input id="comments_edit_data_comment_type_complaint" type="radio" class="comments_edit_data_comment_type" name="comments_edit_data_comment_type" data-type="complaint" data-color="blue" data-bgcolor="rgba(0, 0, 139, .1)" data-label_value="Complaint (Negative)" checked />
					<label for="comments_edit_data_comment_type_complaint" style="color:blue;" class="rolloverhand underline">Complaint (Negative)</label>
					<br />
					<input id="comments_edit_data_comment_type_compliment" type="radio" class="comments_edit_data_comment_type" name="comments_edit_data_comment_type" data-type="compliment" data-color="green" data-bgcolor="rgba(0, 128, 0, .1)"  data-label_value="Compliment (Positive)" />
					<label for="comments_edit_data_comment_type_compliment" style="color:green;" class="rolloverhand underline">Compliment (Positive)</label>
					<br />
					<input id="comments_edit_data_comment_type_comment" type="radio" class="comments_edit_data_comment_type" name="comments_edit_data_comment_type" data-type="comment" data-color="black" data-bgcolor="rgba(128, 128, 128, .1)" data-label_value="Comment (Generic)" />
					<label for="comments_edit_data_comment_type_comment" class="rolloverhand underline">Comment (Generic)</label>
				</div>

				<div class="col-md-5">
					<label id="comments_edit_data_comment_text_label" for="comments_edit_data_comment_text"><span id="comments_edit_data_comment_text_label_type">Comment</span></label>
					<br />
					<div id="comments_edit_data_comment_text" contenteditable="true" style="border: 1px solid #dedede;min-height:10em;padding:5px;"></div>


				</div>
				<div class="col-md-5">
					<label id="comments_edit_data_resolution_text_label" for="comments_edit_data_resolution_text"><span id="comments_edit_data_resolution_text_label_type">Resolution</span></label>
					<br />
					<div id="comments_edit_data_resolution_text" contenteditable="true" style="border: 1px solid #dedede;min-height:10em;padding:5px;"></div>


				</div>
			</div>


		</div>

		<div id="comments_view_data_div" class="comments_view_data" >

			<div class="row">
				<div class="col-md-2">
					<label for="comments_criteria_daterange_fromdate">From</label>
					<input id="comments_criteria_daterange_fromdate" class="form-control datepicker" />
				</div>

				<div class="col-md-2">
					<label for="comments_criteria_daterange_todate">To</label>
					<input id="comments_criteria_daterange_todate" class="form-control datepicker" />
				</div>

				<div class="col-md-2">
					<label style="visibility:hidden;">.</label>
					<br />
					<button id="comments_criteria_btnfind" class="btn btn-success form-control btnwidth">Find</button>
				</div>

				<div class="col-md-6" style="text-align:right;">
					<div class="col-md-12">
						<label style="visibility:hidden;">.</label>
						<br />
						<button id="comments_btnemployee_summary_report" data-elid="comments_employee_summary_results_table" class="btn btn-warning">Employee Summary Report</button>
					</div>
				</div>

			</div>

			<div class="row" style="margin-top:20px;">

				<div class="col-md-12">
					<div id="comments_results_table_div" style="width:100%;display:none;">
						<div class="col-md-12" style="text-align:right;padding-bottom:20px;">
							<button class="btn btn-primary tableexport" data-elid="comments_results_table">Export .csv</button>
							<button id="comments_btn_createreport" class="btn btn-info" data-elid="comments_results_table">Printer-Friendly</button>
						</div>
						<div class="col-md-12" id="comments_results_table_parent">
							<table id="comments_results_table" style="width:100%;" class="tablesorter">
								<thead>
								</thead>
								<tbody>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>

			<div class="row" style="display:none;">
				<div class="col-md-12" id="comments_employee_summary_results_table_div">

					<table id="comments_employee_summary_results_table" data-elid="comments_employee_summary_results_table" style="width:100%;" class="tablesorter">


					</table>
				</div>
			</div>

		</div>

		<?php echo footer(); ?>

	</div> <!-- /container -->

	<?php jsscripts("comments"); ?>

	</body>
</html>
