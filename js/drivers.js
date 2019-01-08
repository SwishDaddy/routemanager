$(document).ready(function() {

	$(".addgrid").click(function() {
		addgrid(true);
	});

	$("#driverinput_date").blur(function() {
		var current_date_string = $(this).val();
		localStorage.setItem("drivers_driverinput_date", current_date_string);
	});

	$(".btn").click(function() {
		$("[data-dismiss='confirmation']").parent().parent().parent().hide();
	});

	$(".removegrid").confirmation({
		"popout": true,
		"btnOkClass": "btn-default",
		"btnCancelClass": "btn-danger",
		"placement": "bottom",
		"title": "Are You Sure?",
		"container": "body",
		"onConfirm": function() {
			removegrid();
		}
	});


	$("#driverinputs_new_driver_sheet").click(function() {

		$.when( getvehicles( $("#driverinput_vehiclename") ) ).done(function() {
			$.when( getuserlist( $("#driverinput_drivername") ) ).done(function() {
				$(".addgrid").show();
				$(".removegrid").show();

				$("#driverinputs_new_or_edit").val("new");
				$(".driverinput_selects").prop( "disabled", false );


				var current_date_string = localStorage.getItem("drivers_driverinput_date");

				current_date_string = current_date_string + "";

				if ((current_date_string.length < 1) || (current_date_string == null) ||  (current_date_string == "null") || (current_date_string == "undefined")) {
					var current_date = new Date();
					var current_month = current_date.getMonth();
					current_month = current_month + 1;
					if (current_month < 10) {
						current_month = "0" + current_month;
					};
					var current_day = current_date.getDate();
					if (current_day < 10) {
						current_day = "0" + current_day;
					};
					var current_year = current_date.getFullYear();

					var current_date_string = current_month + "/" + current_day + "/" + current_year;

				};

				$("#driverinputs_controls_div1").hide();
				$("#driverinputs_controls_div3").hide();
				$("#driverinputs_title").html("Data Input Sheet");

				$("#driverinput_selects_parent").show();
				$("#driverinputs_controls_div2").show();

				$("#driverinput_date").val(current_date_string);

				localStorage.setItem('drivers_driverinput_date', current_date_string);


				$("#driverinputs_parent").fadeIn(250);

				$("#driverinput_vehiclename").focus();

			});
		});
	});

	$("#driverinputs_edit_driver_sheet").click(function() {
		$.when( getvehicles( $("#driverinput_vehiclename") ) ).done(function() {
			//$.when( getuserlist( $("#driverinput_drivername") ) ).done(function() {
				$("#driverinputs_new_or_edit").val("edit");
				$(".driverinput_selects").prop( "disabled", false );
				$(".addgrid").hide();
				$(".removegrid").hide();
				$("#driverinputs_title").html("Edit Driver Sheet");
				$("#driverinputs_controls_div1").hide();
				$("#driverinputs_controls_div3").hide();
				$("#driverinput_selects_parent").show();
				$("#driverinputs_controls_div2").show();
				$("#driverinputs_parent").fadeIn(250);
				$("#driverinput_date").focus();

			//});
		});
	});

	$("#driverinput_btnshowgrid").click(function() {

		$("*").removeClass("rt_error");

		var driverinput_date = $("#driverinput_date").val();
		if (driverinput_date.length != 10) {
			showerrormessage("Invalid Date");
			$("#driverinput_date").addClass("rt_error");
			return false;
		};

		var driverinput_vehiclename = $("#driverinput_vehiclename").val();
		if (driverinput_vehiclename.length < 1) {
			showerrormessage("Please Select a Vehicle");
			$("#driverinput_vehiclename").addClass("rt_error");
			return false;
		};
		/*
		var driverinput_drivername = $("#driverinput_drivername").val();
		if (driverinput_drivername.length < 1) {
			showerrormessage("Please Select a Driver");
			$("#driverinput_drivername").addClass("rt_error");
			return false;
		};
		*/

		$.when( getdriversheet() ).done(function() {
			$.when( tablesorter($(".driverinput_grid")) ).done(function() {
				$.when( font_size( $("#driverinput_table_parent")) ).done(function() {
					$(".btnhome").hide();
					$("#driverinputs_controls_div1").hide();
					$("#driverinputs_controls_div2").hide();
					$("#driverinputs_controls_div3").show();
					$("#driverinput_table_parent").fadeIn(250);
				});
			});
		});

	});

	$(".driverinputs_cancel").confirmation({
		"popout": true,
		"btnOkClass": "btn-default",
		"btnCancelClass": "btn-danger",
		"placement": "bottom",
		"title": "Are You Sure?",
		"container": "body",
		"onConfirm": function() {
			if ($(this).prop("id") == "driverinputs_save_driver_sheet_cancel") {
				$(".btnhome").show();
				$("*").removeClass("rt_error");
				$("#driverinputs_title").html("");
				$("#driverinputs_controls_div2").hide();
				$("#driverinputs_controls_div3").hide();
				$("#driverinput_table_parent").hide();
				$("#driverinput_container_table").html("");
				$(".driverinput_selects").val("");
				$("#driverinput_selects_parent").hide();
				$(".hide_on_cancel").hide();
				$("#driverinputs_controls_div1").show();
				$("#driverinputs_new_driver_sheet").trigger("click");
			}
			else
			{
				$(".btnhome").show();
				$("*").removeClass("rt_error");
				$("#driverinputs_title").html("");
				$("#driverinputs_controls_div2").hide();
				$("#driverinputs_controls_div3").hide();
				$("#driverinput_table_parent").hide();
				$("#driverinput_container_table").html("");
				$(".driverinput_selects").val("");
				$("#driverinput_selects_parent").hide();
				$(".hide_on_cancel").hide();
				$("#driverinputs_controls_div1").show();
				//$("#driver_inputs_sheet_id").val("");
				$("#driverinputs_new_driver_sheet").trigger("click");

			};
		}
	});

	$(".driverinput_selects").keydown(function(event) {
		if (event.keyCode == 13) {
			event.preventDefault();
			if($("#driverinput_btnshowgrid").is(':visible')) {
				$("#driverinput_btnshowgrid").trigger("click");
			};
		};
	});

	$("#driverinputs_save_driver_sheet").confirmation({
		"popout": true,
		"btnOkClass": "btn-default",
		"btnCancelClass": "btn-danger",
		"placement": "bottom",
		"title": "Are You Sure?",
		"container": "body",
		"onConfirm": function() {
			$(".container").hide();
			$("*").removeClass("griddata_tr_highlight");
			//$(".gridinput").each(function() {
			//	$(this).trigger("keyup");
			//});
			if ( savedriversheet() ) {

				//Find all "zero" values, blank them out, then check if all the (4) Passenger COunt and Trip Count input values are blank; if so, highlight the row
				$(".griddata_tr").each(function() {
					var total_cur_val_for_row = "";

					$(this).find("td .threenumbers").each(function() {
						var cur_val = $(this).val();

							if (cur_val == 0) {
								cur_val = "";
								$(this).val(cur_val);
							};

							total_cur_val_for_row = total_cur_val_for_row + cur_val;


					});

					if (total_cur_val_for_row.length < 1) {
						$(this).find("td .threenumbers").addClass("griddata_tr_highlight");
					};

				});

				$(".threenumbers").keyup(function() {

					var hr = $(this).data("hour");
					var position = $(this).data("position");

					//alert(position + "_" + hr);

					$("#grid_table_tr_" + position + "_" + hr).removeClass("griddata_tr_highlight");
				});

			};

			$(".container").fadeIn(250);

		}

	});

	/*
	$("#driverinputs_save_close_driver_sheet").confirmation({
		"popout": true,
		"btnOkClass": "btn-default",
		"btnCancelClass": "btn-danger",
		"placement": "bottom",
		"title": "Are You Sure?",
		"container": "body",
		"onConfirm": function() {

			$.when (savedriversheet() ).done(function() {
				$(".btnhome").show();
				$("*").removeClass("rt_error");
				$("#driverinputs_title").html("");
				$("#driverinputs_controls_div2").hide();
				$("#driverinputs_controls_div3").hide();
				$("#driverinput_table_parent").hide();
				$("#driverinput_container_table").html("");
				$(".driverinput_selects").val("");
				$("#driverinput_selects_parent").hide();
				$(".hide_on_cancel").hide();
				$("#driverinputs_controls_div1").show();
				$("#driverinputs_new_driver_sheet").trigger("click");
				$("#driverinput_vehiclename").focus();
			});

		}
	});
	*/

	$("#driverinputs_save_close_driver_sheet").click(function() {
		if ( savedriversheet() ) {
		//$.when( savedriversheet() ).done(function() {
			$(".btnhome").show();
			$("*").removeClass("rt_error");
			$("#driverinputs_title").html("");
			$("#driverinputs_controls_div2").hide();
			$("#driverinputs_controls_div3").hide();
			$("#driverinput_table_parent").hide();
			$("#driverinput_container_table").html("");
			$(".driverinput_selects").val("");
			$("#driverinput_selects_parent").hide();
			$(".hide_on_cancel").hide();
			$("#driverinputs_controls_div1").show();
			$("#driverinputs_new_driver_sheet").trigger("click");
			$("#driverinput_vehiclename").focus();
		//});
		};
	});


	$(".viewtotals").click(function() {

		$(".gridinput").each(function() {
			$(this).trigger("keyup");
		});
		$(".main_buttons").hide();
		$(".total_buttons").show();
		$("#driverinput_container_table_div").hide();
		$("#driverinput_totals_container_table_div").show();
		$("#driverinputs_controls_div3").hide();
		showtotals();

	});

	$(".viewdataentry").click(function() {

		$(".total_buttons").hide();
		$(".main_buttons").show();
		$("#driverinput_totals_container_table_div").hide();
		$("#driverinput_container_table_div").show();
		$("#driverinputs_controls_div3").show();
	});

	$("#driverinputs_new_driver_sheet").trigger("click");

});

function addgrid(doroutes) {

	$(".driverinput_selects").prop( "disabled", true );

	var ret = "";

	var tdhtml = "";

	var gridcount = $(".grid_parent").length;

	var position = gridcount + 1;

	if (position > 8) {
		return false;
	};

	$("#driverinputs_grid_count").html(gridcount + 1);

	var html = "";

	html =  '' +
	'<td style="padding:0 20px;" class="grid_parent" id="grid_cell_' + position + '">' +
	'	<table class="driverinput_grid" data-position="' + position + '" id="grid_table_' + position + '">' +
	'		<caption>' +
	'			<div class="row">' +
	'				<div class="col-sm-6">' +
	'					<label for="driverinput_route_name_' + position + '" style="color:black;">Route Name</label>' +
	'					<select id="driverinput_route_name_' + position + '" class="form-control driverinput_route_name json_data_val"  title="Select Route Name">' +
	'						<option value="">- Select One -</option>' +
	'					</select>' +
	'				</div>' +
	'			</div>' +
	'		</caption>' +
	'		<tbody>' +
	'		<tr>' +
	'		<td class="passengercount_tripcount_grid grid_data" data-position="' + position + '" rowspan="24"></td>' +
	'		<td class="grid_spacer" style="background-color:white;" rowspan="24"><span style="visibility:hidden;">_</span></td>' +
	'		<td class="out_of_service_grid grid_data" data-position="' + position + '" rowspan="24"></td>' +
	'		</tr>' +
	'		</tbody>' +
	'	</table>'
	'</td>';

	$("#driverinput_container_table").append(html);


	//#####################

	$('#grid_table_' + position + ' .out_of_service_grid').tabularInput({
		'rows': 25,
		'columns': 5,
		'animate': false,
		'columnHeads': ['<b>Out<span style="visibility:hidden;">.</span>of<span style="visibility:hidden;">.</span>Service</b><br />(Military Time)', '<b>Back<span style="visibility:hidden;">.</span>in<span style="visibility:hidden;">.</span>Service</b><br />(Military Time)', '<b>Outage<span style="visibility:hidden;">.</span>Reason</b><br/>(Select One)', '<b>Billable</b><br />(Blank=True)', '<b>Hours<span style="visibility:hidden;">.</span>Out</b><br />Total'],
		'name': 'out_of_service_input' + position
	});

	var arr_column_names = {};

	for (i = 0; i < 24; i++) {

		var hr = i;

		if (hr < 10) {
			hr = "0" + i;
		};

		var j = i + 1;
		var k = i + 2;

		$('#grid_table_' + position + ' .out_of_service_grid tr').eq(k)
		.attr({
			'id': 'grid_table_tr_' + position + '_' + hr + '_time',
			'data-rowid': 'grid_table_tr_' + position + '_' + hr,
			'data-position': position,
			'data-hour': hr + '00',
			'class': 'griddata_tr'
		});


		arr_column_names[0] = "out_of_service_time";
		arr_column_names[1] = "back_in_service_time";
		arr_column_names[2] = "non_operational_reason";
		arr_column_names[3] = "billable";
		arr_column_names[4] = "total_out_of_service_amount";


		var k = j + 1;

		for (x = 0; x < 4; x++) {

			var obj = $('input[name="out_of_service_input' + position + '[' + x + '][' + k + ']"]');

			obj.attr({
			'id': arr_column_names[x] + '_' + position + '_' + hr,
			'title': 'Military Time',
			'data-position': position,
			'data-hour': hr,
			'data-class': arr_column_names[x],
			'class': arr_column_names[x] + ' gridinput lightgreen militarytime grid_value ' + arr_column_names[x] + '_total_count_' + hr,
			'style': 'border:1px solid #ddd;text-align:center'
			});
		};

	};

	$('input[name="out_of_service_input' + position + '[0][1]"]')
	.attr({
		'disabled': true,
		'style': 'text-align:center;font-weight:bold;font-size:120%;'
	})
	.prop("disabled", true);

	$('input[name="out_of_service_input' + position + '[1][1]"]')
	.attr({
		'disabled': true,
		'style': 'text-align:center;font-weight:bold;font-size:120%;'
	})
	.prop("disabled", true);

	$('input[name="out_of_service_input' + position + '[2][1]"]')
	.attr({
		'disabled': true,
		'style': 'text-align:center;font-weight:bold;font-size:120%;'
	})
	.prop("disabled", true);


	$('input[name="out_of_service_input' + position + '[3][1]"]')
	.attr({
		'disabled': true,
		'style': 'text-align:center;font-weight:bold;font-size:120%;'
	})
	.prop("disabled", true);

	$('input[name="out_of_service_input' + position + '[4][1]"]')
	.attr({
		'id': 'grand_total_out_of_service_amount_' + position,
		'class': 'grand_total_out_of_service_amount highlight',
		'style': 'text-align:center;font-weight:bold;font-size:120%;',
		'title': 'Total Time (Hours and Minutes)'
	})
	.prop("disabled", true)
	.parent()
	.append('<span id="grand_total_out_of_service_amount_numeric_' + position + '" class="grand_total_out_of_service_amount_numeric" style="display:none;"></span>');


	for (i = 2; i < 26; i++) {

		var hr = i - 2;

		if (hr < 10) {
			hr = "0" + hr;
		};

		$('input[name="out_of_service_input' + position + '[4][' + i + ']"]')
		.removeClass('lightgreen')
		.attr({
			'id': 'total_out_of_service_amount_' + position + '_' + hr,
			'style': 'text-align:center;font-weight:bold;',
			'class': 'highlight total_out_of_service_amount_' + position,
			'data-total_out_of_service_amount': '',
			'title': 'Time (Hours and Minutes)'
		})
		.prop("disabled", true);

		$('input[name="out_of_service_input' + position + '[3][' + i + ']"]')
		.parent()
		.html(
			'<select style="background:transparent;border:none;" id="billable_' + position + '_' + hr + ' " name="billable_input' + position + '[0][' + i + ']" class="billable gridinput grid_value" title="Billable True or False" data-position="' + position + '" data-class="billable">' +
			'<option value=""></option>' +
			'<option value="False">False</option>' +
			'</select>'
		)
		.addClass("lightgreen");


		$('input[name="out_of_service_input' + position + '[2][' + i + ']"]')
		.parent()
		.html(
			'<select style="background:transparent;border:none;" id="non_operational_reason_' + position + '_' + hr + ' " name="out_of_service_input' + position + '[0][' + i + ']" class="non_operational_reason gridinput grid_value" title="On Break, Fueling, Washing, Maintenance, Other" data-position="' + position + '" data-class="non_operational_reason">' +
			'<option value=""></option>' +
			'<option value="On Break">On Break</option>' +
			'<option value="Fueling">Fueling</option>' +
			'<option value="Washing">Washing</option>' +
			'<option value="Maintenance">Maintenance</option>' +
			'<option value="Parked">Parked</option>' +
			'</select>'
		)
		.addClass("lightgreen");


	};

	$('#grid_table_' + position + ' .out_of_service_grid').find("table tbody tr td input").keyup(function (e) {

		var c = "";

		var currCell = $(this);
		var idx = 10;

		if (e.which == 13) {
			idx = currCell.parent().index();
			c = currCell.closest('tr').next().find('td:eq(' + (idx) + ')');
		} else if (e.which == 38) {
			// Up Arrow
			idx = currCell.parent().index();
			c = currCell.closest('tr').prev().find('td:eq(' + (idx) + ')');

		} else if (e.which == 40) {
			// Down Arrow
			idx = currCell.parent().index();
			c = currCell.closest('tr').next().find('td:eq(' + (idx) + ')');

		} else if (e.which == 39) {
			// Right Arrow
			idx = currCell.parent().index();
			c = currCell.closest('td').next('td');


		} else if (e.which == 37) {
			// Left Arrow
			idx = currCell.parent().index();
			c = currCell.closest('td').prev('td');
		}

		if (c.length > 0) {
			c.find("input").focus().select();
			c.find("select").focus().select();
		};

	});

	$('#grid_table_' + position + ' .out_of_service_grid').find("table tbody tr td input").on("focusin", function() {
		$(this).select();
	});

	$('#grid_table_' + position + ' .out_of_service_grid thead tr th').css("text-align", "center");


	///################

	arr_column_names = {};

	$('#grid_table_' + position + ' .passengercount_tripcount_grid').tabularInput({
		'rows': 25,
		'columns': 5,
		'animate': false,
		'columnHeads': ['<b>Hour</b>', '<b>Passenger<span style="visibility:hidden;">.</span>Count</b><br />Lot/Turnstile', '<b>Passenger<span style="visibility:hidden;">.</span>Count</b><br />Terminal/Concourse', '<b>Trip<span style="visibility:hidden;">.</span>Count</b><br />Lot/Turnstile', '<b>Trip<span style="visibility:hidden;">.</span>Count</b><br />Terminal/Concourse'],
		'name': 'passengercount_tripcount_input' + position
	});

	for (i = 0; i < 24; i++) {

		var hr = i;

		if (hr < 10) {
			hr = "0" + i;
		};

		var j = i + 1;
		var k = i + 2;

		$('#grid_table_' + position + ' .passengercount_tripcount_grid tr').eq(k)
			.attr({
			'id': 'grid_table_tr_' + position + '_' + hr,
			'data-rowid': 'grid_table_tr_' + position + '_' + hr,
			'data-position': position,
			'data-hour': hr + '00',
			'class': 'griddata_tr'
			}
		);

		//Tweak the Hour Column cells

		$('input[name="passengercount_tripcount_input' + position + '[0][' + k + ']"]').val(hr + "00");
		$('input[name="passengercount_tripcount_input' + position + '[0][' + k + ']"]').prop("disabled", true);
		$('input[name="passengercount_tripcount_input' + position + '[0][' + k + ']"]')
		.attr({
			'style': 'text-align:center;border-collapse:collapse;'
		});
		$('input[name="passengercount_tripcount_input' + position + '[0][' + k + ']"]').parent()
		.attr({
			"data-value": hr + "00",
			"id": 'td_hr_' + position + '_' + hr,
			'style': 'background-color:#fafafa !important;'
		})
		.addClass('td_hr');

		var arr_column_names = {};

		arr_column_names[1] = "passengercount_lot_turnstile";
		arr_column_names[2] = "passengercount_terminal_concourse";
		arr_column_names[3] = "tripcount_lot_turnstile";
		arr_column_names[4] = "tripcount_terminal_concourse";

		for (x = 1; x < 5; x++) {

			var obj = $('input[name="passengercount_tripcount_input' + position + '[' + x + '][' + k + ']"]');

			obj.attr({
			'id': arr_column_names[x] + '_' + position + '_' + hr,
			'title': 'Up To Three Digit Integer (1-999)',
			'data-position': position,
			'data-hour': hr,
			'data-class': arr_column_names[x],
			'class': arr_column_names[x] + ' gridinput threenumbers grid_value ' + arr_column_names[x] + '_total_count_' + hr,
			'style': 'border:1px solid #ddd;text-align:center'
			});
		};
	};

	$('input[name="passengercount_tripcount_input' + position + '[0][1]"]')
	.attr({
		'value': "",
		'style': 'font-weight:bold;font-size:120%;'
	})
	.prop("disabled", true);

	$('input[name="passengercount_tripcount_input' + position + '[1][1]"]')
	.attr({
		'id': 'passengercount_lot_turnstile_' + position + '_total',
		'class': 'highlight',
		'style': 'text-align:center;font-weight:bold;font-size:120%;'
	})
	.prop("disabled", true);

	$('input[name="passengercount_tripcount_input' + position + '[2][1]"]')
	.attr({
		'id': 'passengercount_terminal_concourse_' + position + '_total',
		'class': 'highlight',
		'style': 'text-align:center;font-weight:bold;font-size:120%;'
	})
	.prop("disabled", true);

	$('input[name="passengercount_tripcount_input' + position + '[3][1]"]')
	.attr({
		'id': 'tripcount_lot_turnstile_' + position + '_total',
		'class': 'highlight',
		'style': 'text-align:center;font-weight:bold;font-size:120%;'
	})
	.prop("disabled", true);

	$('input[name="passengercount_tripcount_input' + position + '[4][1]"]')
	.attr({
		'id': 'tripcount_terminal_concourse_' + position + '_total',
		'class': 'highlight',
		'style': 'text-align:center;font-weight:bold;font-size:120%;'
	})
	.prop("disabled", true);



	//$('#grid_table_' + position + ' .passengercount_tripcount_grid .threenumbers').off();
	//$('#grid_table_' + position + ' .passengercount_tripcount_grid .threenumbers').mask("000");

	$("#grid_table_" + position + " .gridinput").keyup(function(e) {

		var src_id = $(this).prop("id");
		var position = $(this).data("position");
		var data_class = $(this).data("class");

		var total = "";

		var val = 0;

		$("#grid_table_" + position + " ." + data_class).each(function() {
			if (!isNaN(parseFloat($(this).val()))) {
				val = parseFloat(val) + parseFloat($(this).val());
			};

		});

		$("#" + data_class + "_" + position + "_total").val(val);

	});


	$("#grid_table_" + position + " .gridinput").blur(function() {
		//$(this).parent().find("span").html($(this).val());
			$(this).trigger("keyup");
	});

	$('#grid_table_' + position + ' .passengercount_tripcount_grid').find("table tbody tr td input").keyup(function (e) {

		var c = "";

		var currCell = $(this);
		var idx = 10;

		if (e.which == 13) {
			idx = currCell.parent().index();
			c = currCell.closest('tr').next().find('td:eq(' + (idx) + ')');
		} else if (e.which == 38) {
			// Up Arrow
			idx = currCell.parent().index();
			c = currCell.closest('tr').prev().find('td:eq(' + (idx) + ')');

		} else if (e.which == 40) {
			// Down Arrow
			idx = currCell.parent().index();
			c = currCell.closest('tr').next().find('td:eq(' + (idx) + ')');

		} else if (e.which == 39) {
			// Right Arrow
			idx = currCell.parent().index();
			c = currCell.closest('td').next('td');


		} else if (e.which == 37) {
			// Left Arrow
			idx = currCell.parent().index();
			c = currCell.closest('td').prev('td');
		}

		if (c.length > 0) {
			c.find("input").focus().select();
			c.find("select").focus().select();
		};

	});


	$('#grid_table_' + position + ' .passengercount_tripcount_grid').find("table tbody tr td input").on("focusin", function() {
		$(this).select();
	});

	$('#grid_table_' + position + ' .passengercount_tripcount_grid thead tr th').css("text-align", "center");


	$(".undo").click(function() {
		doUndo();
	});

	if (doroutes) {
		getroutes('grid_table_' + position);
	};

	//tablesorter($("#grid_table_" + position));

	$("#grid_table_" + position + " .militarytime").off();

	$("#grid_table_" + position + " .militarytime").mask("00:00");

	$("#grid_table_" + position + " .militarytime").keyup(function() {

		$("#total_out_of_service_amount_" + position + "_" + hr).val("");

		var val = $(this).val();

		val = val.replace(":", "");

		if (val.length > 4) {
			$(this).val("");
			//return false;
		};

		var position = $(this).data("position");
		var hr = $(this).data("hour");


		$("#total_out_of_service_amount_" + position + "_" + hr).val("");

		///FIND ME clear data-total_out_of_service_amount

		$("#total_out_of_service_amount_" + position + "_" + hr).val("");
		$("#total_out_of_service_amount_" + position + "_" + hr)
		.attr({
			'data-total_out_of_service_amount':  ''
		});

		var validtime = true;
		if (val.length > 3) {
			var hrs = val.substring(0, 2);
			var mins = val.substring(2, 4);
			if (hrs > 23) {
				validtime = false;
			};
			if (mins > 59) {
				validtime = false;
			};

			if (val == 2400) {
				validtime = true;
			};

			if (! validtime) {
				$(this).val("");
				$("#total_out_of_service_amount_" + position + "_" + hr).val("");
				return false;
			};

			var out_of_service_time = $("#out_of_service_time_" + position + "_" + hr).val();
			var back_in_service_time = $("#back_in_service_time_" + position + "_" + hr).val();

			//alert(out_of_service_time + " - " + back_in_service_time);

			var dif = calculate_hours(out_of_service_time, back_in_service_time);

			if (dif < 0) {
				out_of_service_time = $("#out_of_service_time_" + position + "_" + hr).val("");
				back_in_service_time = $("#back_in_service_time_" + position + "_" + hr).val("");
			};

			$("#total_out_of_service_amount_" + position + "_" + hr)
			.attr({
				'data-total_out_of_service_amount':  dif
			});

			if (dif <= 0) {
				dif = "";
				//$("#total_out_of_service_amount_" + position + "_" + hr + "_span").html(dif);
				$("#total_out_of_service_amount_" + position + "_" + hr)
				.attr({
					'data-total_out_of_service_amount':  ''
				});
			}
			else
			{
				dif = minTommss(dif, "Ceil");
			};

			if (dif == "00:00") {
				dif = "";
				//$("#total_out_of_service_amount_" + position + "_" + hr + "_span").html(dif);
				$("#total_out_of_service_amount_" + position + "_" + hr)
				.attr({
					'data-total_out_of_service_amount':  ''
				});
			};

			$("#total_out_of_service_amount_" + position + "_" + hr).val(dif);

			};

		var grand_total_val = 0;
		$("#grand_total_out_of_service_amount_" + position).val("");

		$(".total_out_of_service_amount_" + position).each(function() {
			//var total_val = $(this).val();
			var total_val = $(this).attr("data-total_out_of_service_amount");
			if (!isNaN(parseFloat(total_val))) {
				grand_total_val = parseFloat(grand_total_val) + parseFloat(total_val);
			};
		});

		if (grand_total_val == 0) {
			$("#grand_total_out_of_service_amount_" + position).val("");
			$("#grand_total_out_of_service_amount_numeric_" + position).html("");
			return false;
		};
		grand_total_val = parseFloat(grand_total_val);
		//grand_total_val = Math.round( grand_total_val * 100 ) / 100;
		//grand_total_val = grand_total_val.toFixed(2);
		$("#grand_total_out_of_service_amount_numeric_" + position).html(grand_total_val);

		grand_total_val = minTommss(grand_total_val, "Ceil");

		//grand_total_out_of_service_amount_1

		//alert(grand_total_val);

		$("#grand_total_out_of_service_amount_" + position).val(grand_total_val);

	});

	$("#grid_table_" + position + " .militarytime").focusout(function() {
		var val = $(this).val();
		val = val.replace(":", "");
		var validtime = true;

		//alert('1');

		if (val.length != 4) {
			validtime = false;
		};
		if (! validtime) {
			$(this).val("");
			var position = $(this).data("position");
			var hr = $(this).data("hr");
			$("#total_out_of_service_amount_" + position + "_" + hr).val("");

			var grand_total_val = 0;
			$("#total_out_of_service_amount_" + position).val("");

			$(".total_out_of_service_amount_" + position).each(function() {
				//var total_val = $(this).val();
				var total_val = $(this).data('total_out_of_service_amount');
				//alert(total_val);
				if (!isNaN(parseFloat(total_val))) {
					grand_total_val = parseFloat(grand_total_val) + parseFloat(total_val);
				};
			});

			if (grand_total_val == 0) {
				$("#total_out_of_service_amount_" + position).val("");
				return false;
			};
			grand_total_val = parseFloat(grand_total_val);
			grand_total_val = Math.round( grand_total_val * 100 ) / 100;
			//grand_total_val = grand_total_val.toFixed(2);

			grand_total_val = minTommss(grand_total_val, "Ceil");

			$("#total_out_of_service_amount_" + position).val(grand_total_val);


		};
	});


	$("#grid_table_" + position + " .threenumbers").off();
	$("#grid_table_" + position + " .threenumbers").mask("000");

	$("#grid_table_" + position + " .gridinput").focusin(function() {
		//$(this).select();
		//var x = $(this).val();
		//$(this).val("");
		//$(this).trigger("keyup");
		//$(this).val(x);
		//setTimeout(function(){
		//	$(this).prop("disabled", true);
		//	$(this).select();
		//}, 10);
		//$(this).prop("disabled", false);
		$(this).select();
		//$(this).val("");
	});

	$("#grid_table_" + position + " .gridinput").keyup(function(e) {

		var src_id = $(this).prop("id");
		var position = $(this).data("position");
		var data_class = $(this).data("class");

		var total = "";

		var val = 0;

		$("#grid_table_" + position + " ." + data_class).each(function() {
			if (!isNaN(parseFloat($(this).val()))) {
				val = parseFloat(val) + parseFloat($(this).val());
			};

		});

		$("#" + data_class + "_" + position + "_total").val(val);

	});

	$("#grid_table_" + position + " .gridinput").blur(function() {
		$(this).trigger("keyup");
	});

	$("#grid_table_" + position + " tbody tr td input").keydown(function (e) {

		var c = "";

		var currCell = $(this);
		var idx = 10;

		if (e.which == 13) {
			idx = currCell.parent().index();
			c = currCell.closest('tr').next().find('td:eq(' + (idx) + ')');
		} else if (e.which == 38) {
			// Up Arrow
			idx = currCell.parent().index();
			c = currCell.closest('tr').prev().find('td:eq(' + (idx) + ')');

		} else if (e.which == 40) {
			// Down Arrow
			idx = currCell.parent().index();
			c = currCell.closest('tr').next().find('td:eq(' + (idx) + ')');

		} else if (e.which == 39) {
			// Right Arrow
			idx = currCell.parent().index();
			c = currCell.closest('td').next('td');


		} else if (e.which == 37) {
			// Left Arrow
			idx = currCell.parent().index();
			c = currCell.closest('td').prev('td');
		}

		if (c.length > 0) {
			c.find("input").focus().select();
			c.find("select").focus().select();

		};

	});

	$("#driverinput_container_table thead th").removeAttr("tabindex");

	$("driverinput_container_table .lightgreen").removeAttr("tabindex");

	$(".tripcount_terminal_concourse_total_count_23").on('keydown', function(e) {
		var keyCode = e.keyCode; // || e.which;
		if ((!event.shiftKey) && keyCode == 9) {
			e.preventDefault();
			return false;
		};
	});



};

function removegrid() {

	var gridcount = $(".grid_parent").length;
	var position = gridcount;

	$("#grid_cell_" + position).remove();

	var gridcount = $(".grid_parent").length;

	$("#driverinputs_grid_count").html(gridcount);

	return true;


};

function getvehicles(obj) {

	$("*").removeClass("error");

	obj.empty();
	obj.append($('<option>', {value:"", text:"- Select One -"}));

	//pleasewait.show();

	var arr = {};

	//TODO: Make a checkbox to allow for viewing active or inactive vehicles later... for now just set status to "active"
	arr["status"] = "active";

	return $.post("api/drivers.php", {action: "getvehicles", obj: arr},
	function(data) {

		var d = $.parseJSON(data);
		if (d.message != "success") {
			setTimeout(function(){
				pleasewait.hide();
			}, 500);
			showerrormessage(d.message);
			return false;
		}
		else
		{
			delete d["message"];
		};

		obj.empty();
		$.each(d, function(key, val) {
			obj.append($('<option>', {value:key, text:val}));
		});

		sortdropdown(obj);

		obj.prepend($('<option>', {value:"", text:"- Select One -"}));
		obj.val("");

		/*
		setTimeout(function(){
			pleasewait.hide();
		}, 500);
		*/

	});

};

function getuserlist(obj) {

	//Disables the driver dropdown. Mauy want to include drivers later.
	return false;

	var arr = {};

	//arr["userid"] = userid;

	//TODO: Make a checkbox to allow for viewing active or inactive users later... for now just set status to "active"
	arr["status"] = "active";

	return $.post("api/drivers.php", {action: "getuserlist", obj: arr},
	function(data) {

		var d = $.parseJSON(data);
		if (d.message != "success") {
			setTimeout(function(){
				pleasewait.hide();
			}, 500);
			showerrormessage(d.message);
			return false;
		}
		else
		{
			delete d["message"];
		};

		obj.empty();
		$.each(d, function(key, val) {
			obj.append($('<option>', {value:key, text:val}));
		});

		sortdropdown(obj);

		obj.prepend($('<option>', {value:"", text:"- Select One -"}));
		obj.val("");

		return true;
	});

};

function getroutes(gridid) {

	var obj = $("#" + gridid).find("caption").find(".driverinput_route_name");

	//obj.hide();

	//var routetype = $("#driverinput_route_type_" + position).val();

	var arr = {};

	//TODO: Make a checkbox to allow for viewing active or inactive vehicles later... for now just set status to "active"
	arr["status"] = "active";

	//arr["routetype"] = routetype;

	return $.post("api/drivers.php", {action: "getroutes", obj: arr},
	function(data) {

		var d = $.parseJSON(data);
		if (d.message != "success") {
			setTimeout(function(){
				pleasewait.hide();
			}, 100);
			showerrormessage(d.message);
			return false;
		}
		else
		{
			delete d["message"];
		};


		obj.empty();
		$.each(d, function(key, val) {
			obj.append($('<option>', {value:val.routeid, text:val.routename}));
		});

		//sortdropdown(obj);

		obj.prepend($('<option>', {value:"", text:"- Select One -"}));
		obj.val("");

		setTimeout(function(){
			//pleasewait.hide();
			//obj.fadeIn(150);
		}, 500);
	});

};

function savedriversheet() {

	$("*").removeClass("error");

	var arr = {};

	var date = $("#driverinput_date").val();
	var vehicleid = $("#driverinput_vehiclename").val();
	var routeid = "";
	var gridid = "";
	//var driversheetid = $("#driver_inputs_sheet_id").val();

	arr["date"] = date;
	arr["vehicleid"] = vehicleid;
	//arr["driversheetid"] = driversheetid;

	//pleasewait.show();

	var duplicate_routeid = [];

	var gridcount = $(".grid_parent").length;

	for (i = 1; i <= gridcount; i++) {


		var position = i;

		if ($('#driverinput_route_name_' + position).length) {
			routeid = $('#driverinput_route_name_' + position).val();
		};

		if (routeid.length < 1) {
			$(this).find("caption .driverinput_route_name").addClass("error");
			showerrormessage ("Sorry, each grid must have a Route Name.");
			return false;
		};
		duplicate_routeid.push(routeid);

	};

	var duplicates = duplicate_routeid.reduce(function(acc, el, i, arr1) {
	  if (arr1.indexOf(el) !== i && acc.indexOf(el) < 0) acc.push(el); return acc;
	}, []);

	if (duplicates.length > 0) {
		routename = $(this).find("caption .driverinput_route_name option:selected").text();
		showerrormessage ("Sorry, each grid must have a unique Route Name.");
		return false;
	};

	//arr["arr_data"] = {};

	//var arr_data = {};

	$("#driverinput_container_table .grid_data").each(function() {

		var arr_data = {};

		var position = $(this).data("position");

//alert(position);

		routeid = $('#driverinput_route_name_' + position).val();

		gridid = 'grid_table_' + position;

		if (!arr[gridid]) {
			arr[gridid] = {};
		};

		if (!arr[gridid][routeid]) {
			arr[gridid][routeid] = {};
		};


		//arr[gridid]["routeid"] = routeid;

		$(this).find(".griddata_tr").each(function() {

			var id = $(this).prop("id");

			arr_data[id] = {};

			var hour = $(this).data("hour");
			arr_data[id]["hour"] = hour + "";

			$(this).find('td .grid_value').each (function() {

				var val = $(this).val();
				var dataclass = $(this).data("class");

				if (val == 0) {
					val = "";
				};

				arr_data[id][dataclass] = val;

			});

		});

		$.each(arr_data, function(key, val) {

			if (key.indexOf("_time") > 0) {
				//var timekey = key;
				key = key.replace("_time", "");
			};

			$.each(val, function(key1, val1) {

				if (!arr[gridid][routeid][key]) {
					arr[gridid][routeid][key] = {};
				};

				arr[gridid][routeid][key][key1] = val1;


			});

			delete arr_data[key];

		});

	});

	//console.log(arr);

	//console.log(JSON.stringify(arr));

	return $.post("api/drivers.php", {action: "savedriversheet", obj: JSON.stringify(arr)},
	function(data) {

		var d = $.parseJSON(data);
		if (d.message != "success") {
			setTimeout(function(){
				pleasewait.hide();
			}, 500);
			showerrormessage(d.message);
			return false;
		}
		else
		{
			delete d["message"];
		};
		
		return true;
		
	});
};

function getdriversheet() {
	var arr = {};

	var date = $("#driverinput_date").val();
	var vehicleid = $("#driverinput_vehiclename").val();


	arr["date"]= date;
	arr["vehicleid"] = vehicleid;

	pleasewait.show();

	return $.post("api/drivers.php", {action: "getdriversheet", obj: arr},
	function(data) {

		var d = $.parseJSON(data);
		if (d.message != "success") {
			setTimeout(function(){
				pleasewait.hide();
			}, 500);
			showerrormessage(d.message);
			return false;
		}
		else
		{
			delete d["message"];
		};

		var routeid = "";
		var gridid = "";

		var arr_routeids = {};


		$.each(d, function(key, val) {

			if (Object.keys(val).length < 1) {
				addgrid(true);
				arr_routeids["grid_table_1"] = "";
			}
			else
			{

				$.each(val, function(key2, val2) {

					addgrid(false);

					gridid = key2;

					$.each(val2, function(key1, val1) {

						//alert(key1);

						routeid = val1.routeid;

						arr_routeids[gridid] = routeid;

						var gridrowid = val1.gridrowid;


						var out_of_service_time = val1.out_of_service_time;
						var out_of_service_time_hrs = out_of_service_time.substring(0, 2);
						var out_of_service_time_mins = out_of_service_time.substring(2, 4);
						out_of_service_time = out_of_service_time_hrs + ":" + out_of_service_time_mins;
						if (out_of_service_time == ":") {
							out_of_service_time = "";
						}

						var back_in_service_time = val1.back_in_service_time;
						var back_in_service_time_hrs = back_in_service_time.substring(0, 2);
						var back_in_service_time_mins = back_in_service_time.substring(2, 4);
						back_in_service_time = back_in_service_time_hrs + ":" + back_in_service_time_mins;
						if (back_in_service_time == ":") {
							back_in_service_time = "";
						};

						$("#" + gridrowid).find(".passengercount_lot_turnstile").val(val1.passengercount_lot_turnstile);
						$("#" + gridrowid).find(".passengercount_terminal_concourse").val(val1.passengercount_terminal_concourse);
						$("#" + gridrowid).find(".tripcount_lot_turnstile").val(val1.tripcount_lot_turnstile);
						$("#" + gridrowid).find(".tripcount_terminal_concourse").val(val1.tripcount_terminal_concourse);
						$("#" + gridrowid + "_time").find(".out_of_service_time").val(out_of_service_time);
						$("#" + gridrowid + "_time").find(".back_in_service_time").val(back_in_service_time);
						$("#" + gridrowid + "_time").find(".non_operational_reason").val(val1.non_operational_reason);
						$("#" + gridrowid + "_time").find(".billable").val(val1.billable);

					});

				});

			};

		});

		$(".gridinput").each(function() {
			$(this).trigger("keyup");
		});

		$(".militarytime").each(function() {
			$(this).trigger("keyup");
		});

		$.each(arr_routeids, function(key, val) {
			$.when( getroutes(key) ).done(function() {
				$("#" + key).find("caption").find(".driverinput_route_name").val(val);
			});
		});

		setTimeout(function(){
			pleasewait.hide();
			$("#driverinput_route_name_1").focus();
		}, 500);

	});
};

function showtotals() {

	var tdhtml = "";

	for (i = 0; i < 24; i++) {
		var hr = i;
		if (i < 10) {
			hr = "0" + i;
		};
		tdhtml += '<tr>' +
				'	<td class="td_hr">' + hr + '00</td>' +
				'	<td class="passengercount_lot_turnstile_' + hr + ' passengercount_lot_turnstile_subtotal" style="padding:5px;"></td>' +
				'	<td class="passengercount_terminal_concourse_' + hr +  ' passengercount_terminal_concourse_subtotal" style="padding:5px;"></td>' +
				'	<td class="tripcount_lot_turnstile_' + hr + ' tripcount_lot_turnstile_subtotal" style="padding:5px;"></td>' +
				'	<td class="tripcount_terminal_concourse_' + hr + ' tripcount_terminal_concourse_subtotal" style="padding:5px;"></td>' +
				'</tr>';

	};

	tdhtml = '<tr style="font-weight:bold;font-size:120%;">' +
			'	<td class="highlight"></td>' +
			'	<td class="highlight" style="padding:10px;"><span id="grand_total_lot_turnstile">0</span></td>' +
			'	<td class="highlight" style="padding:10px;"><span id="grand_total_terminal_concourse">0</span></td>' +
			'	<td class="highlight" style="padding:10px;"><span id="grand_total_from_lot_turnstile">0</span></td>' +
			'	<td class="highlight" style="padding:10px;"><span id="grand_total_from_terminal_concourse">0</span></td>' +
			'</tr>' + tdhtml;



	var html =  '' +
				'<td style="padding:0 20px;">' +
				'	<table class="driverinput_grid" id="driverinput_totals_table">' +
				'		<thead>' +
				'			<tr><th style="border-right:none;"></th><th colspan="2" style="border-left:none;">Passenger Count</th><th colspan="2">Trip<span style="visibility:hidden;">-</span>Count</th></tr>' +
				'			<tr><th>Hour</th><th>Lot/Turnstile</th><th>Terminal/Concourse</th><th>From<span style="visibility:hidden;">-</span>Lot/Turnstile</th><th>From<span style="visibility:hidden;">-</span>Terminal/Concourse</th></tr>' +
				'		</thead>' +
				'		<tbody>' +
							tdhtml +
				'		</tbody>' +
				'	</table>' +
				'</td>';


	$("#driverinput_totals_container_table").html(html);

	tablesorter($("#driverinput_totals_table"));

	var hr = "";

	var grand_total_lot_turnstile = 0;
	var grand_total_terminal_concourse = 0;
	var grand_total_from_lot_turnstile = 0;
	var grand_total_from_terminal_concourse = 0;

	for (i = 0; i < 24; i++) {
		var hr = i;
		if (i < 10) {
			hr = "0" + i;
		};

		var sub_total_lot_turnstile = 0;
		var sub_total_terminal_concourse = 0;
		var sub_total_from_lot_turnstile = 0;
		var sub_total_from_terminal_concourse = 0;

		$(".passengercount_lot_turnstile_total_count_" + hr).each(function() {
			var val = $(this).val();
			if(val.length < 1) {
				val = 0;
			};
			if (isNaN(val)) {
				val = 0;
			};
			sub_total_lot_turnstile = parseFloat(sub_total_lot_turnstile) + parseFloat(val);

			if (isNaN(sub_total_lot_turnstile)) {
				sub_total_lot_turnstile = 0;
			};

		});

		$(".passengercount_lot_turnstile_" + hr).html(parseFloat(sub_total_lot_turnstile));
		if (parseFloat(sub_total_lot_turnstile) > 0) {
			$(".passengercount_lot_turnstile_" + hr).addClass("bold")
		};



		$(".passengercount_terminal_concourse_total_count_" + hr).each(function() {
			var val = $(this).val();
			if(val.length < 1) {
				val = 0;
			};
			if (isNaN(val)) {
				val = 0;
			};
			sub_total_terminal_concourse = parseFloat(sub_total_terminal_concourse) + parseFloat(val);

			if (isNaN(sub_total_terminal_concourse)) {
				sub_total_terminal_concourse = 0;
			};

		});

		$(".passengercount_terminal_concourse_" + hr).html(parseFloat(sub_total_terminal_concourse));
		if (parseFloat(sub_total_terminal_concourse) > 0) {
			$(".passengercount_terminal_concourse_" + hr).addClass("bold")
		};

		$(".tripcount_lot_turnstile_total_count_" + hr).each(function() {
			var val = $(this).val();
			if(val.length < 1) {
				val = 0;
			};
			if (isNaN(val)) {
				val = 0;
			};
			sub_total_from_lot_turnstile = parseFloat(sub_total_from_lot_turnstile) + parseFloat(val);

			if (isNaN(sub_total_from_lot_turnstile)) {
				sub_total_from_lot_turnstile = 0;
			};

		});

		$(".tripcount_lot_turnstile_" + hr).html(parseFloat(sub_total_from_lot_turnstile));
		if (parseFloat(sub_total_from_lot_turnstile) > 0) {
			$(".tripcount_lot_turnstile_" + hr).addClass("bold")
		};
		$(".tripcount_terminal_concourse_total_count_" + hr).each(function() {
			var val = $(this).val();
			if(val.length < 1) {
				val = 0;
			};
			if (isNaN(val)) {
				val = 0;
			};
			sub_total_from_terminal_concourse = parseFloat(sub_total_from_terminal_concourse) + parseFloat(val);

			if (isNaN(sub_total_from_terminal_concourse)) {
				sub_total_from_terminal_concourse = 0;
			};

		});


		$(".tripcount_terminal_concourse_" + hr).html(parseFloat(sub_total_from_terminal_concourse));
		if (parseFloat(sub_total_from_terminal_concourse) > 0) {
			$(".tripcount_terminal_concourse_" + hr).addClass("bold")
		};
	};

	$(".passengercount_lot_turnstile_subtotal").each(function() {
		grand_total_lot_turnstile = parseFloat(grand_total_lot_turnstile) + parseFloat($(this).html());
	});

	$(".passengercount_terminal_concourse_subtotal").each(function() {
		grand_total_terminal_concourse = parseFloat(grand_total_terminal_concourse) + parseFloat($(this).html());
	});

	$(".tripcount_lot_turnstile_subtotal").each(function() {
		grand_total_from_lot_turnstile = parseFloat(grand_total_from_lot_turnstile) + parseFloat($(this).html());
	});

	$(".tripcount_terminal_concourse_subtotal").each(function() {
		grand_total_from_terminal_concourse = parseFloat(grand_total_from_terminal_concourse) + parseFloat($(this).html());
	});

	//grand_total_lot_turnstile
	$("#grand_total_lot_turnstile").html(grand_total_lot_turnstile);
	//grand_total_terminal_concourse
	$("#grand_total_terminal_concourse").html(grand_total_terminal_concourse);
	//grand_total_from_lot_turnstile
	$("#grand_total_from_lot_turnstile").html(grand_total_from_lot_turnstile);
	//grand_total_from_terminal_concourse
	$("#grand_total_from_terminal_concourse").html(grand_total_from_terminal_concourse);

	var grand_total_time_out = 0;

	$(".grand_total_out_of_service_amount_numeric").each(function() {

		var val = $(this).html();
		if(val.length < 1) {
				val = 0;
			};
			if (isNaN(val)) {
				val = 0;
			};

		grand_total_time_out = parseFloat(grand_total_time_out) + parseFloat(val);

	});

	var grand_total_time_in = parseFloat(24) - parseFloat(grand_total_time_out);

	$("#driverinput_totals_out_of_service").html(minTommss(grand_total_time_out, "Ceil"));

	$("#driverinput_totals_hours_in_service").html(minTommss(grand_total_time_in, "Floor"));

};

