$(document).ready(function() {

	$("#reports_select_reporttype").change(function() {

		var opt = $(this).val();

		var obj = $("#reports_select_reportsubtype");

		obj.empty();

		$("#reports_select_routegroup").prop("disabled", false);
		$("#reports_select_vehiclegroup").prop("disabled", false);

		$("#reports_select_billable_div").hide();

		if (opt == "total_number_of_passengers") {
			obj.append($('<option>', {value:"hourly_totals", text:"Hourly Totals (EKG)"}));
			obj.append($('<option>', {value:"vehicle_totals", text:"Vehicle Totals"}));
			obj.append($('<option>', {value:"datetime", text:"Date Range"}));
			obj.append($('<option>', {value:"specific_day", text:"Day of Week"}));
			obj.append($('<option>', {value:"vehicle_number", text:"Vehicle Number"}));
			obj.append($('<option>', {value:"route_total", text:"Route (Total)"}));
			obj.append($('<option>', {value:"from_lot", text:"Route (From Lot)"}));
			obj.append($('<option>', {value:"from_terminal", text:"Route (From Terminal)"}));
		};
		if (opt == "total_in_service_hours") {
			obj.append($('<option>', {value:"hourly_totals", text:"Hourly Totals (EKG)"}));
			obj.append($('<option>', {value:"vehicle_totals", text:"Vehicle Totals"}));
			obj.append($('<option>', {value:"datetime_by_date", text:"Date Range"}));
			obj.append($('<option>', {value:"vehicle_type", text:"Vehicle Type"}));
			obj.append($('<option>', {value:"vehicle_number", text:"Vehicle Number"}));
			obj.append($('<option>', {value:"route", text:"Route"}));
			$("#reports_select_billable_div").show();
		};
		if (opt == "total_fueling_hours") {
			obj.append($('<option>', {value:"all", text:"All Vehicles"}));
			obj.append($('<option>', {value:"vehicle_number", text:"Vehicle Number"}));
			$("#reports_select_billable_div").show();
		};
		if (opt == "total_onbreak_hours") {
			obj.append($('<option>', {value:"all", text:"All Vehicles"}));
			obj.append($('<option>', {value:"vehicle_number", text:"Vehicle Number"}));
			$("#reports_select_billable_div").show();
		};
		if (opt == "total_washing_hours") {
			obj.append($('<option>', {value:"all", text:"All Vehicles"}));
			obj.append($('<option>', {value:"vehicle_number", text:"Vehicle Number"}));
			$("#reports_select_billable_div").show();
		};
		if (opt == "total_maintenance_hours") {
			obj.append($('<option>', {value:"all", text:"All Vehicles"}));
			obj.append($('<option>', {value:"vehicle_number", text:"Vehicle Number"}));
			$("#reports_select_billable_div").show();
		};
		if (opt == "total_parked_hours") {
			obj.append($('<option>', {value:"all", text:"All Vehicles"}));
			obj.append($('<option>', {value:"vehicle_number", text:"Vehicle Number"}));
			$("#reports_select_billable_div").show();
		};
		if (opt == "total_fluids") {
			//$("#reports_select_routegroup").val("all");
			$("#reports_select_routegroup").prop("disabled", true);
			obj.append($('<option>', {value:"all", text:"Date Totals"}));
			obj.append($('<option>', {value:"vehicle_number", text:"Vehicle Number"}));
			obj.append($('<option>', {value:"specific_day", text:"Day of Week"}));
		};
		if (opt == "total_comments") {
			obj.append($('<option>', {value:"all", text:"Date Range"}));
			obj.append($('<option>', {value:"route", text:"Route"}));
			obj.append($('<option>', {value:"comment_type", text:"Comment Type"}));
		};
		if (opt == "total_users") {
			//$("#reports_select_routegroup").val("all");
			$("#reports_select_routegroup").prop("disabled", true);
			//$("#reports_select_vehiclegroup").val("all");
			$("#reports_select_vehiclegroup").prop("disabled", true);

			obj.append($('<option>', {value:"all", text:"All Users"}));
			obj.append($('<option>', {value:"senioritydate", text:"Seniority Date"}));
			obj.append($('<option>', {value:"hireddate", text:"Date Hired"}));
			obj.append($('<option>', {value:"birthdate", text:"Birthday"}));
			obj.append($('<option>', {value:"driverlicenseexpiration", text:"Driver License Expiration"}));
			obj.append($('<option>', {value:"badgeexpirationdate", text:"Badge Expiration"}));
			obj.append($('<option>', {value:"dotexpirationdate", text:"DOT Expiration"}));
		};
		if (opt == "total_accidents") {
			obj.append($('<option>', {value:"all", text:"All Accidents"}));
			obj.append($('<option>', {value:"contributing_factors", text:"Contributing Factors"}));
			obj.append($('<option>', {value:"driver", text:"Driver"}));
			obj.append($('<option>', {value:"vehicle_number", text:"Vehicle Number"}));
		};

		
		var key = obj.data("localkey");
		if (localStorage[key]) {
			var val = localStorage.getItem(key);
			if ( $("#reports_select_reportsubtype option[value='" + val + "']").length > 0 ){
				obj.val(val);
			};
		};

		$("#reports_select_reportsubtype").trigger("change");

	});

	$("#reports_select_reportsubtype").change(function() {

		$(".reportcriteria").hide();
		//$("#reportcriteria_label").hide();
		$("#reportcriteria_label").html("");

		$("#reports_criteria_main_div").hide();
		//$("#reports_criteria_daterange_div").hide();

		$("#reports_criteria_second_div").hide();
		$("#reports_select_reportcriteria_second").val("");

		$("#reports_criteria_third_div").hide();

		$("#reports_criteria_fourth_div").hide();
		$("#reports_select_reportcriteria_fourth").val("");

		var opt = $(this).val();

		var obj_select = $("#reports_select_reportcriteria");
		var obj_input = $("#reports_input_reportcriteria");

		var current_datetime_string = $("#reports_criteria_daterange_todate").val();
		var past_datetime_string = $("#reports_criteria_daterange_fromdate").val();


		if (current_datetime_string.length < 1) {
			var current_datetime = new Date();
			var current_month = current_datetime.getMonth();
			current_month = current_month + 1;
			if (current_month < 10) {
				current_month = "0" + current_month;
			};
			var current_day = current_datetime.getDate();
			if (current_day < 10) {
				current_day = "0" + current_day;
			};
			var current_year = current_datetime.getFullYear();
			var current_hour = current_datetime.getHours();
			if (current_hour < 10) {
				current_hour = "0" + current_hour;
			};
			var current_minutes = current_datetime.getMinutes();
			if (current_minutes < 10) {
				current_minutes = "0" + current_minutes;
			};

			//current_datetime_string = current_month + "/" + current_day + "/" + current_year + " " + current_hour + ":" + current_minutes;
			current_datetime_string = current_month + "/" + current_day + "/" + current_year;
		};

		if (past_datetime_string.length < 1) {
			var past_datetime = current_datetime.setDate(current_datetime.getDate() - 1);
			past_datetime = new Date(past_datetime);
			var past_month = past_datetime.getMonth();
			past_month = past_month + 1;
			if (past_month < 10) {
				past_month = "0" + past_month;
			};
			var past_day = past_datetime.getDate();
			if (past_day < 10) {
				past_day = "0" + past_day;
			};
			var past_year = past_datetime.getFullYear();
			var past_hour = past_datetime.getHours();
			if (past_hour < 10) {
				past_hour = "0" + past_hour;
			};
			var past_minutes = past_datetime.getMinutes();
			if (past_minutes < 10) {
				past_minutes = "0" + past_minutes;
			};

			//past_datetime_string = past_month + "/" + past_day + "/" + past_year + " " + past_hour + ":" + past_minutes;
			past_datetime_string = past_month + "/" + past_day + "/" + past_year;
		};

		obj_select.empty();
		obj_input.val("");

		if (opt == "vehicle_number") {

			var arr = {};

			$("#reportcriteria_label").html("Vehicle");

			//Might add Vehicle Type as a criteria later; for now default it to "all" to get all vehicles
			arr["vehicletype"] = "all";

			//Get Vehicles
			return $.post("api/reports.php", {action: "getvehicles", obj: arr},
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

				$.each(d, function(key, val) {
					obj_select.append($('<option>', {value:key, text:val}));
				});

				sortdropdown(obj_select);

				obj_select.prepend($('<option>', {value:"", text:"- Select One -"}));

				obj_select.val("");

				$("#reports_criteria_daterange_fromdate").val(past_datetime_string);
				$("#reports_criteria_daterange_todate").val(current_datetime_string);
				$("#reports_criteria_daterange_div").show();

				$("#reports_criteria_main_div").show();
				if (opt == "all") {
					$("#reports_criteria_main_div").hide();
				};

				obj_select.fadeIn(250);

			});

		};

		if (opt == "datetime" || opt == "vehicle_totals" || opt == "all") {
			$("#reports_criteria_daterange_fromdate").val(past_datetime_string);
			$("#reports_criteria_daterange_todate").val(current_datetime_string);
			$("#reports_criteria_daterange_div").show();
			obj_input.fadeIn(250);
		};

		if (opt == "datetime_by_date") {
			$("#reports_criteria_daterange_fromdate").val(past_datetime_string);
			$("#reports_criteria_daterange_todate").val(current_datetime_string);
			$("#reports_criteria_daterange_div").show();
			obj_input.fadeIn(250);
		};

		if (opt == "vehicle_type") {
			$("#reports_criteria_daterange_fromdate").val(past_datetime_string);
			$("#reports_criteria_daterange_todate").val(current_datetime_string);
			$("#reports_criteria_daterange_div").show();
			//$("#reports_criteria_main_div").show();
			var obj = $("#reports_select_reportcriteria_fourth");
			$.when( getvehicletypes(obj) ).done(function() {
				obj_select.fadeIn(250);
			});
		};

		if (opt == "route_total") {
			$("#reports_criteria_daterange_fromdate").val(past_datetime_string);
			$("#reports_criteria_daterange_todate").val(current_datetime_string);
			$("#reports_criteria_daterange_div").show();
			var obj = $("#reports_select_reportcriteria_second");
			$.when( getroutes(obj) ).done(function() {
				obj_input.fadeIn(250);
				$(".datepicker").datetimepicker({
					//format:'m/d/Y H:i'
					format:'m/d/Y',
					timepicker:false,
					scrollMonth : false,
					scrollInput : false
				});
			});

		};

		if (opt == "from_lot") {
			$("#reports_criteria_daterange_fromdate").val(past_datetime_string);
			$("#reports_criteria_daterange_todate").val(current_datetime_string);
			$("#reports_criteria_daterange_div").show();
			var obj = $("#reports_select_reportcriteria_second");
			$.when( getroutes(obj) ).done(function() {
				obj_input.fadeIn(250);
				$(".datepicker").datetimepicker({
					//format:'m/d/Y H:i'
					format:'m/d/Y',
					timepicker:false,
					scrollMonth : false,
					scrollInput : false
				});
			});

		};

		if (opt == "from_terminal") {
			$("#reports_criteria_daterange_fromdate").val(past_datetime_string);
			$("#reports_criteria_daterange_todate").val(current_datetime_string);
			$("#reports_criteria_daterange_div").show();
			var obj = $("#reports_select_reportcriteria_second");
			$.when( getroutes(obj) ).done(function() {

			});

		};

		if (opt == "specific_day") {
			$("#reports_criteria_daterange_fromdate").val(past_datetime_string);
			$("#reports_criteria_daterange_todate").val(current_datetime_string);
			$("#reports_criteria_daterange_div").show();
			$("#reports_criteria_third_div").fadeIn(250);
			obj_select.fadeIn(250);
			$(".datepicker").datetimepicker({
				//format:'m/d/Y H:i'
				format:'m/d/Y',
				timepicker:false,
				scrollMonth : false,
				scrollInput : false
			});
		};

		if (opt == "route") {
			$("#reports_criteria_daterange_fromdate").val(past_datetime_string);
			$("#reports_criteria_daterange_todate").val(current_datetime_string);
			$("#reports_criteria_daterange_div").show();
			$("#reports_criteria_second_div").fadeIn(250);
			var obj = $("#reports_select_reportcriteria_second");
			$.when( getroutes(obj) ).done(function() {

			});
		};

		if (opt == "comment_type") {
			$("#reports_criteria_daterange_fromdate").val(past_datetime_string);
			$("#reports_criteria_daterange_todate").val(current_datetime_string);
			$("#reports_criteria_daterange_div").show();
			$("#reports_criteria_second_div").fadeIn(250);
			var obj = $("#reports_select_reportcriteria_second");
			$.when( getcommenttypes(obj) ).done(function() {

			});
		};

		if (opt == "contributing_factors") {
			$("#reports_select_reportcriteria_third").empty();
			
			$("#reportcriteria_label_third").html("Contributing Factor");
			
			$("#reports_select_reportcriteria_third").append($('<option>', {value:"contributing_factors_backing", text:"Backing"}));
			$("#reports_select_reportcriteria_third").append($('<option>', {value:"contributing_factors_fixed_object", text:"Fixed Object"}));	
			$("#reports_select_reportcriteria_third").append($('<option>', {value:"contributing_factors_moving_vehicle", text:"Moving Vehicle"}));
			$("#reports_select_reportcriteria_third").append($('<option>', {value:"contributing_factors_parked_vehicle", text:"Parked Vehicle"}));	
			$("#reports_select_reportcriteria_third").append($('<option>', {value:"contributing_factors_pedestrian", text:"Pedestrian"}));
			$("#reports_select_reportcriteria_third").append($('<option>', {value:"contributing_factors_head_on", text:"Head On"}));	
			$("#reports_select_reportcriteria_third").append($('<option>', {value:"contributing_factors_turning", text:"Turning"}));
			$("#reports_select_reportcriteria_third").append($('<option>', {value:"contributing_factors_road_conditions", text:"Road Conditions"}));	
			$("#reports_select_reportcriteria_third").append($('<option>', {value:"contributing_factors_speed", text:"Speed"}));
			$("#reports_select_reportcriteria_third").append($('<option>', {value:"contributing_factors_mechanical", text:"Mechanical"}));	
			$("#reports_select_reportcriteria_third").append($('<option>', {value:"contributing_factors_spacing", text:"Spacing"}));
			$("#reports_select_reportcriteria_third").append($('<option>', {value:"contributing_factors_other", text:"Other"}));	
			
			$("#reports_select_reportcriteria_third").prepend($('<option>', {value:"", text:"- Select One -"}));
			
			$("#reports_select_reportcriteria_third").val("");
			
			$("#reports_criteria_third_div").fadeIn(100);
			
		};
		
		if (opt == "driver") {			
			$("#reports_select_reportcriteria_third").empty();
			
			$("#reportcriteria_label_third").html("Employee");
			getusers($("#reports_select_reportcriteria_third"));			
		};		
		
	});

	$(".reports_select_results").change(function() {
		$("*").removeClass("error");
		$("#reports_results_linechart_div").html("");
		$("#reports_results_linechart_div").hide();
		$("#reports_results_div").hide();
		$("#reports_results_table thead").empty();
		$("#reports_results_table tbody").empty();
	});

	$("#reports_btn_go").click(function() {
		$("#reports_group_by_day").prop("checked", true);
		 getresults();
	});
	
	// Printer-Freindly Button
	$("#reports_btn_createreport").click(function() {
		
		var fromdate = $("#reports_criteria_daterange_fromdate").val();
		var todate = $("#reports_criteria_daterange_todate").val();

		var reporttype = $("#reports_select_reporttype option:selected").text() + " " + $("#reports_select_reportsubtype option:selected").text();
		var tablehtml = $("#reports_results_table_div").html();
		
		var reporttypekey = $("#reports_select_reportsubtype option:selected").val();

		var reporttitle = reporttype + "<br />" + fromdate + " - " + todate;

		var reportdate = new Date();

		reportdate = reportdate.toLocaleDateString("en-US");
		
		var w = window.open();

		var d = new Date();
		var r = d.getTime();

		$.get("reporttemplate.php?r=" + r, function(data) {

			var resp = data;

			resp = resp.replace("###REPORTTITLE###", reporttitle);

			resp = resp.replace("###REPORTDATE###", reportdate);

			resp = resp.replace("###REPORTTITLE1###", reporttitle);

			resp = resp.replace("###REPORTDATE1###", reportdate);		

			
			$(".report_group_by").each(function() {
				var groupby_selected = $(this).prop("checked");
				if (groupby_selected) {
					groupby_id = $(this).prop("id");
				};
			});

			if ((reporttypekey == "hourly_totals") && (groupby_id == "reports_group_by_day")) {
				var dataURL = document.getElementById('report_barchart').toDataURL("image/png");
				resp = resp.replace("####TABLHTML###", '<img src="' + dataURL + '"></img>');		
			}
			else
			{
				resp = resp.replace("####TABLHTML###", tablehtml);
			};
		
			$(w.document.body).html(resp);

		});

	});

	$(".report_group_by").click(function() {
		 getresults();
	});

	$("#reports_btn_cancel").click(function() {
		$("*").removeClass("error");
		$("#reports_results_linechart_div").html("");
		$("#reports_results_linechart_div").hide();
		$("#reports_results_div").hide();
		$("#reports_results_table thead").empty();
		$("#reports_results_table tbody").empty();
	});

	var obj = $("#reports_select_routegroup");
	getroutegroups(obj);

	var obj = $("#reports_select_vehiclegroup");
	getvehiclegroups(obj);

	$("#reports_select_reporttype").trigger("change");

});

function getroutes(obj) {

	//pleasewait.show();

	var arr = {};

	//TODO: Make a checkbox to allow for viewing active or inactive routes later... for now just set status to "active"
	arr["status"] = "active";

	return $.post("api/reports.php", {action: "getroutes", obj: arr},
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
			obj.append($('<option>', {value:val.routeid, text:val.routename}));
		});

		//sortdropdown(obj);

		obj.prepend($('<option>', {value:"all", text:"All Routes"}));
		obj.prepend($('<option>', {value:"", text:"- Select One -"}));
		obj.val("");

		obj.parent().fadeIn(250);

		/*
		setTimeout(function(){
			pleasewait.hide();
			obj.parent().fadeIn(250);
		}, 500);
		*/
	});

};

function getresults() {

	$("*").removeClass("error");
	
	$(".tableexport").show();

	$("#reports_results_linechart_div").html("");
	$("#reports_results_linechart_div").hide();
	$("#reports_results_div").hide();
	$("#reports_results_table thead").empty();
	$("#reports_results_table tbody").empty();
	$("#report_group_by_div").hide();

	var report_type = $("#reports_select_reporttype").val();

	if (report_type.length < 1) {
		showerrormessage("Please select a Report Type");
		$("#reports_select_reporttype").addClass("error");
		return false;
	};

	var report_subtype = $("#reports_select_reportsubtype").val();

	if (report_subtype.length < 1) {
		showerrormessage("Please select a Report Sub-Type");
		$("#reports_select_reportsubtype").addClass("error");
		return false;
	};

	var report_fromdate = $("#reports_criteria_daterange_fromdate").val();

	if (report_fromdate.length < 1) {
		showerrormessage("Please select a Report From Date");
		$("#reports_criteria_daterange_fromdate").addClass("error");
		return false;
	};

	var report_todate = $("#reports_criteria_daterange_todate").val();
	if (report_todate.length < 1) {
		showerrormessage("Please select a Report To Date");
		$("#reports_criteria_daterange_todate").addClass("error");
		return false;
	};

	var route_group = $("#reports_select_routegroup").val();

	var vehicle_group = $("#reports_select_vehiclegroup").val();

	var report_criteria = "";

	if ($("#reports_select_reportcriteria").is(":visible")) {
		if ($("#reports_select_reportcriteria").val().length < 1) {
			showerrormessage("Please select the final Report Criteria");
			$("#reports_select_reportcriteria").addClass("error");
			return false;
		}
		else
		{
			report_criteria = $("#reports_select_reportcriteria").val();
		}
	};

	if ($("#reports_select_reportcriteria_second").is(":visible")) {
		if ($("#reports_select_reportcriteria_second").val().length < 1) {
			showerrormessage("Please select a Route");
			$("#reports_select_reportcriteria_second").addClass("error");
			return false;
		}
		else
		{
			report_criteria = $("#reports_select_reportcriteria_second").val();
		}
	};

	if ($("#reports_select_reportcriteria_third").is(":visible")) {
		if ($("#reports_select_reportcriteria_third").val().length < 1) {
			showerrormessage("Please select a Value");
			$("#reports_select_reportcriteria_third").addClass("error");
			return false;
		}
		else
		{
			report_criteria = $("#reports_select_reportcriteria_third").val();
		}
	};

	if ($("#reports_select_reportcriteria_fourth").is(":visible")) {
		if ($("#reports_select_reportcriteria_fourth").val().length < 1) {
			showerrormessage("Please select a Type");
			$("#reports_select_reportcriteria_fourth").addClass("error");
			return false;
		}
		else
		{
			report_criteria = $("#reports_select_reportcriteria_fourth").val();
		}
	};

	var groupby_id = "reports_group_by_day";

	$(".report_group_by").each(function() {
		var groupby_selected = $(this).prop("checked");
		if (groupby_selected) {
			groupby_id = $(this).prop("id");
		};
	});

	var billable = $("#reports_select_billable").val();

	var arr = {};

	arr["report_type"] = report_type;
	arr["report_subtype"] = report_subtype;
	arr["report_fromdate"] = report_fromdate;
	arr["report_todate"] = report_todate;
	arr["report_criteria"] = report_criteria;
	arr["report_group_by"] = groupby_id;
	arr["route_group"] = route_group;
	arr["vehicle_group"] = vehicle_group;
	arr["billable"] = billable;

	pleasewait.show();

	return $.post("api/reports.php", {action: "getresults", obj: arr},
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

		$("#reports_results_table").trigger("destroy");

		//var grandtotal = d["arr_results"]["Grand Total"];
		//delete d["arr_results"]["Grand Total"];

		var report_group_by = d["arr_results"]["report_group_by"];
		delete d["arr_results"]["report_group_by"];

		var thead = "";
		var tbody = "";

		var counter = 0;

		var total_lot_miles = "";
		var total_terminal_miles = "";
		var total_out_of_service = "";
		var total_in_service = "";
		var recordcount = 0;

		var arrtotals = {};

		//#### Line Chart for Hourly Totals (EKG) Summary Report only, Detailed uses the Tabular Data logic below

		if ((report_type == "total_number_of_passengers") && (report_subtype == "hourly_totals")) {
			if (report_group_by == "reports_group_by_day") {
				
				$(".tableexport").hide();

				var dataPoints1 = [];
				var dataPoints2 = [];
				var dataPoints3 = [];
				var dataPoints4 = [];

				var chart = new CanvasJS.Chart("reports_results_linechart_div", {
					title: {
						text: "", //"Hourly Totals (EKG)"
					},
					animationEnabled: true,
					axisX:{
						crosshair: {
							enabled: true,
							snapToDataPoint: true
						}
					},
					axisY: {
						title: "Count",
						crosshair: {
							enabled: true
						}
					},
					toolTip:{
						shared:true
					},
					legend:{
						cursor:"pointer",
						verticalAlign: "top",
						itemclick: function (e) {
							if (typeof (e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
								e.dataSeries.visible = false;
							} else {
								e.dataSeries.visible = true;
							}

							e.chart.render();
						}
					},
					data: [
					{
						type: "line",
						showInLegend: true,
						name: "Lot Passengers", //"Lot/Turnstile Passengers",
						dataPoints: dataPoints1
					},
					{
						type: "line",
						type: "line",
						showInLegend: true,
						name: "Terminal Passengers", //"Terminal/Concourse Passengers",
						dataPoints: dataPoints2
					},
					{
						type: "line",
						type: "line",
						showInLegend: true,
						name: "Lot Trip Count", //"Lot/Turnstile Trip Count",
						dataPoints: dataPoints3
					},
					{
						type: "line",
						type: "line",
						showInLegend: true,
						name: "Terminal Trip Count", //"Terminal/Concourse Trip Count",
						dataPoints: dataPoints4
					},
					]
				});
					
					
				var json = d["arr_results"];

				var arrfinal = {}

				$.each(json, function(key, val) {  //date
					arrfinal[key] = {};

					$.each(val, function(key1, val1) {	//line name

						$.each(val1, function(key2, val2) {	//hour

							if (key1 == "Lot/Turnstile Passengers") {
								dataPoints1.push({
									label: key2,
									y: val2
								});
							};

							if (key1 == "Terminal/Concourse Passengers") {
								dataPoints2.push({
									label: key2,
									y: val2
								});
							};

							if (key1 == "Lot/Turnstile Trip Count") {
								dataPoints3.push({
									label: key2,
									y: val2
								});
							};

							if (key1 == "Terminal/Concourse Trip Count") {
								dataPoints4.push({
									label: key2,
									y: val2
								});
							};

							arrfinal[key][key1] = dataPoints;

						});

						dataPoints = [];
					});

				});

				$("#reports_results_linechart_div").css("visibility", "hidden");

				$("#reports_results_linechart_div").show();	
				
				chart.render();		
				
				var canvasobj = $("#reports_results_linechart_div").find(".canvasjs-chart-canvas")[0];
				
				canvasobj.id = "report_barchart";

				$("#recordcount").html("");

				setTimeout(function(){
					pleasewait.hide();
					$("#reports_results_div").show();
					$("#reports_results_linechart_div").css("visibility", "visible");
				}, 500);

				return false;

			};

		};

		if ((report_type == "total_in_service_hours") && (report_subtype == "hourly_totals")) {
			if (report_group_by == "reports_group_by_day") {
				
				$(".tableexport").hide();

				var dataPoints = [];

				var dataPoints1 = [];

				var chart = new CanvasJS.Chart("reports_results_linechart_div", {
					title: {
						text: "", //"In Service Hourly Totals (EKG)"
					},
					animationEnabled: true,
					axisX:{
						crosshair: {
							enabled: true,
							snapToDataPoint: true
						}
					},
					axisY: {
						title: "Vehicles In Service",
						crosshair: {
							enabled: true
						}
					},
					toolTip:{
						shared:true
					},
					data: [
					{
						type: "line",
						name: "Vehicles In Service",
						dataPoints: dataPoints1
					}
					]
				});

				function addData(json) {
					$.each(json, function(key, val) {  //date
						$.each(val, function(key1, val1) {
							dataPoints1.push({
								label: val1["Time"],
								y: val1["Vehicles In Service"]
							});
						});
					});
					$("#reports_results_linechart_div").css("visibility", "hidden");
					$("#reports_results_linechart_div").show();
					chart.render();
					
					var canvasobj = $("#reports_results_linechart_div").find(".canvasjs-chart-canvas")[0];
					
					canvasobj.id = "report_barchart";
				};

				addData((d["arr_results"]));

				$("#recordcount").html("");

				setTimeout(function(){
					pleasewait.hide();
					$("#reports_results_linechart_div").css("visibility", "visible");
					$("#reports_results_div").show();
				}, 500);

				return false;

			};

		};

		// Tabular Data
		$.each(d["arr_results"], function(key1, val1) {

			$.each(val1, function(key2, val2) {

				if (thead.length < 1) {
					if (counter < 1) {
						thead = "<tr>";
						$.each(val2, function(key, val) {
							if (report_group_by == "reports_group_by_day") {
								if (key != "Hour" && key != "Route" && key != "Vehicle") {
									if (key.indexOf("Grand Total") < 0) {
										thead = thead + "<th>" + key + "</th>";
									};
								};
							}
							else
							{
								thead = thead + "<th>" + key + "</th>";
							}

						});
						thead = thead + "</tr>";
						$("#reports_results_table thead").append(thead);
						counter = counter + 1;
					};
				};

				tbody = "<tr>";

				var colname = "";


				$.each(val2, function(key, val) {

					if (key.indexOf("Grand Total") > -1) {
						if (key == "Grand Total Out of Service" || key == "Grand Total Hours Out") {
						val = minTommss(val, "Ceil")
						};

						if (key == "Grand Total In Service") {
							val = minTommss(val, "Floor")
						};
						colname = key.replace("Grand Total ", "");
						arrtotals[colname] = val;
						delete val2[key];
					};

				});

				$.each(val2, function(key, val) {

					var militarytime_css = "";
					if (key == "Time Out") {
						militarytime_css = "militarytime";
					};

					if (key == "Time In") {
						militarytime_css = "militarytime";
					};

					if (key == "Total Out of Service") {
						val = minTommss(val, "Ceil")
					};

					if (key == "Total In Service") {
						val = minTommss(val, "Floor")
					};

					if (key == "Hours Out") {
						val = minTommss(val, "Ceil")
					};

					if (key != "total") {

						if (report_group_by == "reports_group_by_day") {
							if (key != "Hour" && key != "Route" && key != "Vehicle") {
								if (key.indexOf("Grand Total") < 0) {
									tbody = tbody + '<td class="' + militarytime_css + '">' + val + '</td>';
								};
							};
						}
						else
						{
							tbody = tbody + '<td class="' + militarytime_css + '">' + val + '</td>';
						};

					};

				});

				tbody = tbody + "</tr>";

				$("#reports_results_table tbody").append(tbody);

				$("#reports_results_table tbody .militarytime").mask("00:00");

				tbody = "";

				recordcount = recordcount + 1;

			});

			if (recordcount > 99) {

				// PAGINATE

				//return false;
			};

		});

		var columnindex = "";
		var colcount = 0;
		var arrtotalindices = {};

		$.each(arrtotals, function(key, val) {
			var columnindex = $('#reports_results_table th:contains("' + key + '")').index();
			arrtotalindices[columnindex] = val;
			if(columnindex > colcount) {
			 colcount = columnindex;
			};
		});

		if (report_type != "total_comments" && report_subtype !== "hourly_totals" && report_type != "total_users" && report_type != "total_accidents") {

			tbody = '<tr id="reports_results_grandtotals" class="highlight" style="font-weight:bold;font-size:1.25em;">';

			for (i = 0; i <= colcount; i++) {
				if (i == 0) {
					tbody = tbody + '<td>Totals</td>';
				}
				else
				{
					tbody = tbody + '<td></td>';
				};
			};

			tbody = tbody + "</tr>";

			$("#reports_results_table tbody").prepend(tbody);
		};



		$.each(arrtotalindices, function(key, val) {

			$('#reports_results_grandtotals  td:eq(' + key + ')').html(val);
		});


		if ((report_subtype == "hourly_totals") || (report_type == "total_users") || (report_type == "total_accidents")) {
			$("#recordcount").html("Records Found: " + recordcount);
		}
		else
		{
			$("#recordcount").html("Records Found: " + (recordcount-1));
		};

		$("#reports_results_table thead tr th").css("padding", "10px 15px 10px 15px");
		$("#reports_results_table thead tr th").css("text-align", "center");
		$("#reports_results_table tbody tr td").css("padding", "10px 15px 10px 15px");

		$("#reports_results_table thead tr").addClass("table_header_color");

		$("#reports_results_table").addClass("grid");

		$("#reports_results_table").css("width", "100%");

		//$("#reports_results_table").tablesorter("destroy");

		$("#reports_results_table tbody tr:first").addClass("static");

		tablesorter($("#reports_results_table"));
		
		if ( report_type == "total_accidents" ){
			$('#reports_results_grandtotals').remove();			
		}
		else {
			$("#report_group_by_div").show();
		};

		setTimeout(function(){
			pleasewait.hide();
			$("#reports_results_div").show();
		}, 500);

	});

};

function getroutegroups(obj) {

	var arr = {};

	arr["blah"] = "blah";

	return $.post("api/reports.php", {action: "getroutegroups", obj: arr},
	function(data) {

		var d = $.parseJSON(data);
		if (d.message != "success") {
			showerrormessage(d.message);
			return false;
		}
		else
		{
			delete d["message"];
		};

		obj.empty();
		$.each(d, function(key, val) {
			obj.append($('<option>', {value:val, text:val}));
		});

		//sortdropdown(obj);

		obj.prepend($('<option>', {value:"all", text:"All Route Groups"}));
		//obj.prepend($('<option>', {value:"", text:"- Select One -"}));
		obj.val("all");

		var key = obj.data("localkey");
		if (localStorage[key]) {
			var val = localStorage.getItem(key);
			obj.val(val);
		};

		obj.parent().fadeIn(250);

	});


};

function getvehiclegroups(obj) {

	var arr = {};

	arr["blah"] = "blah";

	return $.post("api/reports.php", {action: "getvehiclegroups", obj: arr},
	function(data) {

		var d = $.parseJSON(data);
		if (d.message != "success") {
			showerrormessage(d.message);
			return false;
		}
		else
		{
			delete d["message"];
		};

		obj.empty();
		$.each(d, function(key, val) {
			obj.append($('<option>', {value:val, text:val}));
		});

		//sortdropdown(obj);

		obj.prepend($('<option>', {value:"all", text:"All Vehicle Groups"}));
		//obj.prepend($('<option>', {value:"", text:"- Select One -"}));
		obj.val("all");

		var key = obj.data("localkey");
		if (localStorage[key]) {
			var val = localStorage.getItem(key);
			obj.val(val);
		}

		obj.parent().fadeIn(250);

	});

};

function getcommenttypes(obj) {
	obj.empty();

	obj.append($('<option>', {value:"", text:"- Select One -"}));
	obj.append($('<option>', {value:"all", text:"All"}));
	obj.append($('<option>', {value:"compliment", text:"Compliment"}));
	obj.append($('<option>', {value:"complaint", text:"Complaint"}));
	obj.append($('<option>', {value:"comment", text:"Comment"}));
	obj.val("");

	obj.parent().fadeIn(250);

};

function getvehicletypes(obj) {

	//pleasewait.show();

	var arr = {};

	//TODO: Make a checkbox to allow for viewing active or inactive routes later... for now just set status to "active"
	arr["status"] = "active";

	return $.post("api/reports.php", {action: "getvehicletypes", obj: arr},
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

		//sortdropdown(obj);

		obj.prepend($('<option>', {value:"", text:"- Select One -"}));
		obj.val("");

		obj.parent().fadeIn(250);

	});

};

function getusers(obj) {

	var arr = {};

	arr["status"] = "active";

	return $.post("api/reports.php", {action: "getusers", obj: arr},
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

		obj.parent().fadeIn(250);

	});

};

