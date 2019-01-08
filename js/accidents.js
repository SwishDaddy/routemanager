$(document).ready(function() {

	$(".btn").click(function() {
		$("[data-dismiss='confirmation']").parent().parent().parent().hide();
	});

	$(".threenumbers").mask("000");
	$(".fournumbers").mask("0000");
	$(".sixnumbers").mask("000000");
	$(".militarytime").mask("00:00");

	var current_datetime_string = $("#accidents_criteria_daterange_todate").val();
	var past_datetime_string = $("#accidents_criteria_daterange_fromdate").val();

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
		var past_datetime = current_datetime.setDate(current_datetime.getDate() - 7);
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

	$("#accidents_criteria_daterange_todate").val(current_datetime_string);
	$("#accidents_criteria_daterange_fromdate").val(past_datetime_string);	

	//######################### SAVE

	$("#accidents_save_data_btn").click(function(scrolltoid) {		
		saveaccident("");
	});

	$(".btnapply").click(function() {		
		var scrolltoid = $(this).data("scrolltoid");		
		saveaccident(scrolltoid);
	});
	
	$("input").on("change", function() {
		$("*").removeClass("error");
	});

	$("select").on("change", function() {
		$("*").removeClass("error");
	});

	$("#accidents_new_data_btn").click(function() {

		pleasewait.show();

		$(".accidents_view_data").hide();

		$(".accidents_edit_data_postdata_val").val("");
		$(".accidents_edit_data_postdata_html").html("");
		$(".accidents_edit_data_postdata_chk").prop("checked", false);
		$(".clearval").val("");
		$(".clearvalno").val("No");
		$(".clearhtml").html("");
		$(".diagramcoordinates").val("");
		$(".showtable tbody").empty();
		$(".showtable").hide();
		$("#accidents_edit_data_other_driver_edit_div").hide();
		$("#accidents_edit_data_other_driver_table_div").show();
		$("#accidents_edit_data_injury_edit_div").hide();
		$("#accidents_edit_data_injury_table_div").show();
		$("#accidents_edit_data_witness_edit_div").hide();
		$("#accidents_edit_data_witness_table_div").show();
		
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
	

		$(".chkshowuploadbutton").trigger("change");

		$("#accidents_edit_data_locationname").val(location_name_long);

		$.when(getusers($(".userlist"))).done(function() {
			$.when(getvehicletypes($(".vehicletypelist"), "")).done(function() {
				$.when(getvehicles($(".vehiclelist"), "")).done(function() {
					$("#accidents_edit_data_employee_vehicletype").trigger("change");
					$.when(getroutes($(".routelist"))).done(function() {

						$(".diagramcoordinates").val("");
						$(".thumbnail").prop("src", "");
						$(".thumbnail").each(function() {
							var canvasid = $(this).data("canvasid");
							$(this).prop("src", "api/showfile.php?type=thumbnail&file=" + canvasid + ".png&d=" + canvasid + ".png&r=" + Math.random());
						});
						$.when( getuniqueid("accident", $("#accidents_edit_data_accidentid")) ).done(function() {
							
							$("#accidents_edit_data_accidentdate").val(current_datetime_string);

							setTimeout(function(){
								pleasewait.hide();
								$(".accidents_edit_data").fadeIn(250);
							}, 500);

						});

					});
				});
			});
		});
	});

	$("#accidents_cancel_data_btn").confirmation({
		"popout": true,
		"singleton": true,
		"btnOkClass": "btn-default",
		"btnCancelClass": "btn-danger",
		"title": "<i>Be sure you have saved your changes as necessary.</i><br /><br />Are You Sure?",
		"container": "body",
		"placement": "bottom",
		"onConfirm": function() {
			$(".accidents_edit_data").hide();
			$(".accidents_edit_data_postdata_val").val("");
			$(".accidents_edit_data_postdata_html").html("");
			$(".accidents_edit_data_postdata_chk").prop("checked", false);
			$(".clearval").val("");
			$(".clearvalno").val("No");
			$(".clearhtml").html("");
			$(".diagramcoordinates").val("");
			$(".showtable tbody").empty();
			$(".showtable").hide();
			$("#accidents_edit_data_locationname").val(location_name_long);
			$("#accidents_edit_data_other_driver_edit_div").hide();
			$("#accidents_edit_data_other_driver_table_div").show();
			$("#accidents_edit_data_injury_edit_div").hide();
			$("#accidents_edit_data_injury_table_div").show();
			$("#accidents_edit_data_witness_edit_div").hide();
			$("#accidents_edit_data_witness_table_div").show();

			$.when( getaccidents() ).done(function() {
				$(".accidents_view_data").fadeIn(250);
			});

			deletethumbnails();

		}
	});

	$(".chkshowuploadbutton").change(function() {

		var btnid = $(this).data("btnid");

		var obj = $("#" + btnid);

		if ($(this).prop("checked")) {
			obj.show();
		}
		else
		{
			obj.hide();
		};

	});

	$("#accidents_edit_data_employee_driver").change(function() {
		$("#accidents_edit_data_employee_info_div").hide();
		$("#accidents_edit_data_employee_info_div spandata").html("");
		if ($(this).val().length > 0) {
			$.when( getemployeeinfo($(this).val()) ).done(function() {
				$("#accidents_edit_data_employee_info_div").show();
			});
		};
	});

	$(".militarytime").keyup(function() {
		var val = $(this).val();
		val = val.replace(":", "");
		if (val.length > 4) {
			$(this).val("");
		};
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
				return false;
			};
		};
	});

	$(".militarytime").focusout(function() {
		var val = $(this).val();
		val = val.replace(":", "");
		var validtime = true;
		if (val.length != 4) {
			validtime = false;
		};
		if (! validtime) {
			$(this).val("");
		};
	});

	$("#accidents_edit_data_employee_vehicletype").change(function() {
		var obj = $("#accidents_edit_data_employee_vehiclename");
		var vehicletypeid = $(this).val();
		$.when( getvehicles(obj, vehicletypeid) ).done(function() {
			$("#accidents_edit_data_employee_vehiclename").trigger("change");
		});
	});

	$("#accidents_edit_data_employee_vehiclename").change(function() {
		//$("#accidents_edit_data_employee_vehicle_info_div").hide();
		$("#accidents_edit_data_employee_vehicle_info_div .spandata").html("");
		var vehicleid = $(this).val();
		if (vehicleid.length > 0) {
			$.when( getvehicleinfo(vehicleid) ).done(function() {
				$("#accidents_edit_data_employee_vehicle_info_div").show();
			});
		};
	});

	$(".damagediagramcanvas").click(function() {

		var canvasid = $(this).prop("id");
		var x = event.offsetX || event.layerX;
		var y = event.offsetY || event.layerY;

		var arrpoint = {};

		x = x-20;
		y = y-25;

		arrpoint["x"] = x;
		arrpoint["y"] = y;

		var arrcanvaspoints = [];

		var coords = $("#" + canvasid + "_coordinates").val();

		if (coords.length > 0) {
			arrcanvaspoints = $.parseJSON(coords);
		};

		arrcanvaspoints.push(arrpoint);
		//arrcanvaspoints[canvasid] = arrpoint;

		$("#" + canvasid + "_coordinates").val(JSON.stringify(arrcanvaspoints) );

		placecanvaspoints(canvasid);

	});

	$(".clearcanvas").click(function() {
		var canvasid = $(this).data("canvasid");

		$("#" + canvasid + "_coordinates").val("");

		var canvas = document.getElementById(canvasid), /// canvas element
		ctx = canvas.getContext('2d');

		ctx.clearRect(0, 0, canvas.width, canvas.height);

		loadcanvasbg(canvasid);

	});

	$(".savecanvas").click(function() {
		var canvasid = $(this).data("canvasid");
		var scrolltoid = $(this).data("scrolltoid");
		$.when( savethumbnail(canvasid) ).done(function() {
			var canvas = document.getElementById(canvasid), /// canvas element
			ctx = canvas.getContext('2d');
			ctx.clearRect(0, 0, canvas.width, canvas.height);
			$("#canvascontainer").hide();
			$("#maincontainer").show();
			var scrollto = $('#' + scrolltoid).position().top - 100;
			$("html, body").animate({ scrollTop: scrollto }, 600);
		});
	});

	$(".showcanvas").click(function() {
		var canvasid = $(this).data("canvasid");
		$("#maincontainer").hide();
		$.when(loadcanvasbg(canvasid) ).done(function() {
			$.when( placecanvaspoints(canvasid) ).done(function() {
				$("#canvascontainer").fadeIn(250);
			});
		});
	});

	$("#accidents_edit_data_other_driver_btnnew").click(function() {
		$("#accidents_edit_data_other_driver_table_div").hide();
		$("#accidents_edit_data_other_driver_edit_div .clearval").val("");
		$("#accidents_edit_data_other_driver_edit_div .clearvalno").val("No"); // For Yes/No only dropdowns without a blank val
		$("#accidents_edit_data_other_driver_edit_div .clearhtml").html("");
		$("#accidents_edit_data_other_driver_vehicle_damage_diagram_coordinates").val("");
		$("#accidents_edit_data_other_driver_vehicle_damage_diagram_thumbnail").prop("src", "img/accidents_edit_data_other_driver_vehicle_damage_diagram.png");
		$("#accidents_edit_data_other_driver_edit_div").fadeIn(250);
		getuniqueid("otherdriver", $("#accidents_edit_data_other_driver_id"));
		//var scrollto = $('#accidents_edit_data_other_driver_edit_div').position().top - 100;
		//$("html, body").animate({ scrollTop: scrollto }, 600);
	});

	$(".accidents_edit_data_other_driver_btnsave").click(function() {
		var otherdriverid = $("#accidents_edit_data_other_driver_id").val();
		addotherdrivertotable(otherdriverid, "", "tempthumbnail");
		$(".accidents_edit_data_other_driver_btnclose").trigger("click");
	});

	$(".accidents_edit_data_other_driver_btnclose").click(function() {
		$("#accidents_edit_data_other_driver_edit_div").hide();
		$("#accidents_edit_data_other_driver_edit_div .clearval").val("");
		$("#accidents_edit_data_other_driver_edit_div .clearvalno").val("No");
		$("#accidents_edit_data_other_driver_edit_div .clearhtml").html("");
		$("#accidents_edit_data_other_driver_vehicle_damage_diagram_thumbnail").prop("src", "img/accidents_edit_data_other_driver_vehicle_damage_diagram.png");
		$("#accidents_edit_data_other_driver_table_div").fadeIn(250);
	});

	$(".accidents_edit_data_injury_btnsave").click(function() {
		var injuryid = $("#accidents_edit_data_injury_id").val();
		addinjurytotable(injuryid, "");
		$(".accidents_edit_data_injury_btnclose").trigger("click");
	});

	$(".accidents_edit_data_witness_btnsave").click(function() {
		var witnessid = $("#accidents_edit_data_witness_id").val();
		addwitnesstotable(witnessid, "");
		$(".accidents_edit_data_witness_btnclose").trigger("click");
	});

	$(".accidents_edit_data_injury_btnclose").click(function() {
		$("#accidents_edit_data_injury_edit_div").hide();
		$("#accidents_edit_data_injury_edit_div .clearval").val("");
		$("#accidents_edit_data_injury_edit_div .clearhtml").html("");
		var rowcount = $("#accidents_edit_data_injury_table tbody tr").length;
		if (rowcount < 1) {
			$("#accidents_edit_data_injury_table").hide();
		}
		else
		{
			$("#accidents_edit_data_injury_table").fadeIn(250);
		};
		$("#accidents_edit_data_injury_table_div").fadeIn(250);
	});

	$(".accidents_edit_data_witness_btnclose").click(function() {
		$("#accidents_edit_data_witness_edit_div").hide();
		$("#accidents_edit_data_witness_edit_div .clearval").val("");
		$("#accidents_edit_data_witness_edit_div .clearhtml").html("");
		var rowcount = $("#accidents_edit_data_witness_table tbody tr").length;
		if (rowcount < 1) {
			$("#accidents_edit_data_witness_table").hide();
		}
		else
		{
			$("#accidents_edit_data_witness_table").fadeIn(250);
		};
		$("#accidents_edit_data_witness_table_div").fadeIn(250);
	});

	$("#accidents_edit_data_injury_btnnew").click(function() {
		$("#accidents_edit_data_injury_table_div").hide();
		$("#accidents_edit_data_injury_edit_div").fadeIn(250);
		$("#accidents_edit_data_injury_table_div").hide();
		$("#accidents_edit_data_injury_edit_div .clearval").val("");
		$("#accidents_edit_data_injury_edit_div .clearhtml").html("");
		$("#accidents_edit_data_injury_edit_div").fadeIn(250);
		getuniqueid("injury", $("#accidents_edit_data_injury_id"));
		//var scrollto = $('#accidents_edit_data_injury_edit_div').position().top - 100;
		//$("html, body").animate({ scrollTop: scrollto }, 600);
	});

	$("#accidents_edit_data_witness_btnnew").click(function() {
		$("#accidents_edit_data_witness_table_div").hide();
		$("#accidents_edit_data_witness_edit_div").fadeIn(250);
		$("#accidents_edit_data_witness_table_div").hide();
		$("#accidents_edit_data_witness_edit_div .clearval").val("");
		$("#accidents_edit_data_other_driver_edit_div .clearhtml").html("");
		$("#accidents_edit_data_witness_edit_div").fadeIn(250);
		getuniqueid("witness", $("#accidents_edit_data_witness_id"));
		//var scrollto = $('#accidents_edit_data_witness_edit_div').position().top - 100;
		//$("html, body").animate({ scrollTop: scrollto }, 600);
	});

	$("#accidents_edit_data_accident_information_btnedit").click(function() {
		$("#accidents_edit_data_accident_information_small").hide();
		$("#accidents_edit_data_accident_information").fadeIn(250);
	});

	$("#accidents_edit_data_accident_information_btnhide").click(function() {
		$("#accidents_edit_data_accident_information").hide();
		$("#accidents_edit_data_accident_information_small").fadeIn(250);
	});

	$("#accidents_edit_data_employee_info_btnedit").click(function() {
		$("#accidents_edit_data_employee_info_small").hide();
		$("#accidents_edit_data_employee_info_parent").fadeIn(250);
	});

	$(".accidents_edit_data_employee_info_btnhide").click(function() {
		$("#accidents_edit_data_employee_info_parent").hide();
		$("#accidents_edit_data_employee_info_small").fadeIn(250);
	});

	$("#accidents_edit_data_police_report_btnedit").click(function() {
		$("#accidents_edit_data_police_report_small").hide();
		$("#accidents_edit_data_police_report").fadeIn(250);
	});

	$("#accidents_edit_data_police_report_btnhide").click(function() {
		$("#accidents_edit_data_police_report").hide();
		$("#accidents_edit_data_police_report_small").fadeIn(250);
	});

	$("#accidents_edit_data_supervisor_statement_btnedit").click(function() {
		$("#accidents_edit_data_supervisor_statement_small").hide();
		$("#accidents_edit_data_supervisor_statement").fadeIn(250);
	});

	$("#accidents_edit_data_supervisor_statement_btnhide").click(function() {
		$("#accidents_edit_data_supervisor_statement").hide();
		$("#accidents_edit_data_supervisor_statement_small").fadeIn(250);
	});

	$(".btnupload").click(function() {

		$("*").removeClass("error");

		var filetype = $("#accidents_edit_data_linked_files_filetype").val();
		var accidentid = $("#accidents_edit_data_accidentid").val();

		if (filetype.length < 1) {
			$("#accidents_edit_data_linked_files_filetype").addClass("error");
			showerrormessage("Please select a File Type.");

			return false;
		};

		var uploadurl = "api/upload.php?filetype=" + filetype + "&recordid=" + accidentid;

		uploadfiles(uploadurl);

	});

	$(".glyphicon-upload").click(function() {
		var ufiletype = $(this).data("ufiletype");

		var arr = ufiletype.split("-");

		filetypelist(arr[0]);

		getlinkedfiles(arr[0]);
	});

	$("#accidents_edit_data_linked_files_btnclose").click(function() {
		$("#linkedfilescontainer").hide();
		$("#accidents_edit_data_linked_files_table tbody").empty();
		$("#maincontainer").fadeIn(250);
	});

	$("#accidents_criteria_btnfind").click(function() {
		$.when( getaccidents() ).done(function() {
			$(".container").fadeIn(150);
		});
	});

	$("#accidents_btn_createreport").click(function() {
		
		$("#accidents_view_data_table .btn").hide();
		
		var tablehtml = $("#accidents_view_data_table_parent").html();

		var reporttitle = "Accidents Report";

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

			resp = resp.replace("####TABLHTML###", tablehtml);

			$(w.document.body).html(resp);
			
			$("#accidents_view_data_table .btn").show();

		});

	});
	
	
	$(".accidents_edit_data_postdata_val").val("");
	$(".accidents_edit_data_postdata_html").html("");

	$.when( getaccidents() ).done(function() {
		$(".container").fadeIn(150);
	});

});

function getaccidents() {

	var arr = {};

	arr["fromdate"] = $("#accidents_criteria_daterange_fromdate").val();
	arr["todate"] = $("#accidents_criteria_daterange_todate").val();

	pleasewait.show();

	return $.post("api/accidents.php", {action: "getaccidents", obj: arr},
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

		var tbl = $("#accidents_view_data_table");

		tbl.trigger("destroy");

		tbl.find("thead")
		.empty()
		.append(
			'<tr>' +
				'<th>Date</th>' +
				'<th>Vehicle Name</th>' +
				'<th>Employee Driver</th>' +
				'<th></th>' +
			'<tr/>'
		);

		tbl.find("tbody").empty();

		$.each(d, function(key, val) {
			var id = key;
			var adatetime = val["adatetime"];
			var employeedriver = val["employeedrivername"];
			var vehiclename = val["vehiclename"];

			tbl.find("tbody")
			.append(
				'<tr data-id="' + id + '" style="height:2em;">' +
					'<td class="adatetime" style="height:2em;" valign="top">' + adatetime + '</td>' +
					'<td class="vehiclename" style="height:2em;" valign="top">' + vehiclename + '</td>' +
					'<td class="employeedriver" style="height:2em;" valign="top">' + employeedriver + '</td>' +
					'<td style="text-align:right;" class="prevent_td"><button class="btn btn-danger glyphicon glyphicon-remove deleteaccident" style="padding:1px 5px;" data-accidentid="' + id + '"></button></td>' +
				'</tr>'
			);

		});

		$("#accidents_view_data_table .prevent_td").click(function(event) {
			event.preventDefault();
			event.stopPropagation();
		});

		$(".deleteaccident").off();

		$(".deleteaccident").confirmation({
			"popout": true,
			"singleton": true,
			"btnOkClass": "btn-default",
			"btnCancelClass": "btn-danger",
			"title": "Are You Sure?",
			"container": "body",
			"placement": "bottom",
			"onConfirm": function() {
				var arr = {};
				arr["accidentid"] = $(this).data("accidentid");

				var filetype = arr["filetype"];

				return $.post("api/accidents.php", {action: "deleteaccident", obj: arr},
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

					 getaccidents();

				});
			}
		});

		tablesorter(tbl);

		tbl.find("tbody tr").on("click", function() {
			var rowid = $(this).data("id");
			$("#accidents_edit_data_accidentid").val(rowid);
			getaccidentdata(rowid);

		});

		setTimeout(function(){
			pleasewait.hide();
			$("#accidents_view_data_table_div").show();
		}, 500);

	});
};

function getaccidentdata(accidentid) {
	var arr = {};

	arr["accidentid"] = accidentid;

	pleasewait.show();

	return $.post("api/accidents.php", {action: "getaccidentdata", obj: arr},
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

		$(".accidents_view_data").hide();

		$(".accidents_edit_data_postdata_val").val("");
		$(".accidents_edit_data_postdata_html").html("");
		$(".clearval").val("");
		$(".clearvalno").val("No");
		$(".clearhtml").html("");
		$("#accidents_edit_data_other_driver_edit_div").hide();
		$("#accidents_edit_data_other_driver_table_div").show();
		$("#accidents_edit_data_injury_edit_div").hide();
		$("#accidents_edit_data_injury_table_div").show();
		$("#accidents_edit_data_witness_edit_div").hide();
		$("#accidents_edit_data_witness_table_div").show();

		$("#accidents_edit_data_accidentid").val(accidentid);

		$("#accidents_edit_data_locationname").val(location_name_long);

		$.when(getusers($(".userlist"))).done(function() {
			$.when(getvehicletypes($(".vehicletypelist"), "")).done(function() {
				$.when(getvehicles($(".vehiclelist"), "")).done(function() {
					$("#accidents_edit_data_employee_vehicletype").trigger("change");
					$.when(getroutes($(".routelist"))).done(function() {
						$(".diagramcoordinates").val("");
						$(".thumbnail").prop("src", "");
						$(".thumbnail").each(function() {
							var canvasid = $(this).data("canvasid");
							$(this).prop("src", "api/showfile.php?type=thumbnail&file=" + canvasid + ".png&d=" + canvasid + ".png&r=" + Math.random());
						});

						$("#accidents_edit_data_employee_vehicle_damage_diagram_thumbnail").prop("src", "api/showfile.php?type=thumbnail&file=" + accidentid + ".png&recordid=" + accidentid + "&d=accidents_edit_data_employee_vehicle_damage_diagram.png&r=" + Math.random());

						var el;
						$.each(d["maindata"], function(key, val) {
							$.each(val, function(key1, val1) {

								el = $('*[data-postkey~="' + key1 + '"]');

								if (el.hasClass("accidents_edit_data_postdata_val")) {
									el.val(val1);
								};
								if (el.hasClass("accidents_edit_data_postdata_html")) {
									el.html(val1);
								};
								if (el.hasClass("accidents_edit_data_postdata_chk")) {
									if (val1 == "true") {
										el.prop("checked", true);
									}
									else
									{
										el.prop("checked", false);
									};

								};
							});
						});

						//console.log(d);

						$.each(d["otherdriver"], function(key, val) {
							//$.each(val, function(key1, val1) {
								val["vehicle_damage_diagram_coordinates"] = $.parseJSON(val["vehicle_damage_diagram_coordinates"]);

								addotherdrivertotable(key, JSON.stringify(val), "thumbnail");
						});

						$.each(d["injury"], function(key, val) {
							addinjurytotable(key, JSON.stringify(val));
						});

						$.each(d["witness"], function(key, val) {
							addwitnesstotable(key, JSON.stringify(val));
						});

						$(".chkshowuploadbutton").trigger("change");

						$(".accidents_edit_data").fadeIn(250);

					});
				});
			});
		});

		setTimeout(function(){
			pleasewait.hide();
			$("#accidents_view_data_table_div").show();
			$(".accidents_edit_data").show();
		}, 500);

	});

};

function loadcanvasbg(canvasid) {
	var canvas = document.getElementById(canvasid),
    ctx = canvas.getContext('2d'),
    img = new Image;
	img.src = 'img/' + canvasid + '.png';
	ctx.drawImage(img, 0, 0);
};

function placecanvaspoints(canvasid) {
	$(".damage_diagram_div").hide();
	var coords = $("#" + canvasid + "_coordinates").val();
	if (coords.length > 0) {
		var arrcanvaspoints = $.parseJSON(coords);
		var canvas = document.getElementById(canvasid),
		ctx = canvas.getContext('2d'),
		img = new Image;
		img.src = 'img/x.png';
		ctx.clearRect(0, 0, canvas.width, canvas.height);
		$.each(arrcanvaspoints, function(key, val) {
			ctx.drawImage(img,val["x"], val["y"]);
		});
	};
	loadcanvasbg(canvasid);
	$("#" + canvasid + "_div").show();
};

function getusers(obj) {
	var arr = {};

	// Got to have something in the obj!
	arr["blah"] = "blah";

	return $.post("api/accidents.php", {action: "getusers", obj: arr},
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
			obj.append($('<option>', {value:key, text:val}));
		});

		sortdropdown(obj);

		obj.prepend($('<option>', {value:"", text:"- Select One -"}));
		obj.val("");

	});

};

function getvehicles(obj, vehicletypeid) {

	$("*").removeClass("error");

	obj.empty();
	obj.append($('<option>', {value:"", text:"- Select One -"}));

	var arr = {};

	arr["vehicletypeid"] = vehicletypeid;

	return $.post("api/accidents.php", {action: "getvehicles", obj: arr},
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
			obj.append($('<option>', {value:key, text:val}));
		});

		sortdropdown(obj);

		obj.prepend($('<option>', {value:"", text:"- Select One -"}));
		obj.val("");

	});

};

function getvehicletypes(obj) {

	$("*").removeClass("error");

	obj.empty();
	obj.append($('<option>', {value:"", text:"- Select One -"}));

	var arr = {};

	arr["status"] = "active";

	return $.post("api/accidents.php", {action: "getvehicletypes", obj: arr},
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
			obj.append($('<option>', {value:key, text:val}));
		});

		sortdropdown(obj);

		obj.prepend($('<option>', {value:"", text:"- Select One -"}));
		obj.val("");

	});

};

function getroutes(obj) {

	var arr = {};

	arr["status"] = "active";

	return $.post("api/accidents.php", {action: "getroutes", obj: arr},
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


		obj.prepend($('<option>', {value:"", text:"- Select One -"}));
		obj.val("");

		obj.parent().fadeIn(250);

	});

};

function getemployeeinfo(userid) {

	var arr = {};

	arr["userid"] = userid;

	return $.post("api/accidents.php", {action: "getemployeeinfo", obj: arr},
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

		$.each(d, function(key, val) {
			$("#accidents_edit_data_employee_" + key).html(val);
		});

	});


};

function getvehicleinfo(vehicleid) {

	var arr = {};

	arr["vehicleid"] = vehicleid;

	return $.post("api/accidents.php", {action: "getvehicleinfo", obj: arr},
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

		$.each(d, function(key, val) {
			$("#accidents_edit_data_employee_vehicle_" + key).html(val);
		});

	});


};

function savethumbnail(canvasid) {

	var filename = $("#accidents_edit_data_other_driver_id").val();

	if (canvasid == "accidents_edit_data_other_driver_vehicle_damage_diagram") {
		var otherdriverid = $("#accidents_edit_data_other_driver_id").val();
		$("#" + otherdriverid).data("thumbnailtype", "tempthumbnail");
	};

	if (canvasid == "accidents_edit_data_employee_vehicle_damage_diagram") {
		filename = $("#accidents_edit_data_accidentid").val();
	};

	var obj = $("#" + canvasid + "_thumbnail");

	obj.prop("src", "");

	var arr = {};

	var dataURL = document.getElementById(canvasid).toDataURL("image/png");

	// strip off invalid data for saving
	dataURL = dataURL.replace("data:image/png;base64,", "");

	arr["img"] = dataURL;
	arr["filename"] = filename + ".png";

	return $.post("api/accidents.php", {action: "savethumbnail", obj: arr},
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


		obj.prop("src", "api/showfile.php?type=tempthumbnail&file=" + filename + ".png&d=accidents_edit_data_other_driver_vehicle_damage_diagram.png&r=" + Math.random());

	});

};

function deletethumbnails() {

	var arr = {};

	arr["blah"] = "blah";

	return $.post("api/accidents.php", {action: "deletethumbnails", obj: arr},
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

	});

};

function addotherdrivertotable(otherdriverid, existingjson, thumbnailtype) {

	if (existingjson.length < 1) {
		var arr = {};


		$(".accidents_edit_data_other_driver_data_val").each(function() {
			var key = $(this).prop("id").replace("accidents_edit_data_other_driver_", "");
			arr[key] = $(this).val();
		});

		$(".accidents_edit_data_other_driver_data_html").each(function() {
			var key = $(this).prop("id").replace("accidents_edit_data_other_driver_", "");
			arr[key] = $(this).html();
		});

		var coords = $("#accidents_edit_data_other_driver_vehicle_damage_diagram_coordinates").val();

		arr["vehicle_damage_diagram_coordinates"] = [];

		if (coords.length > 0) {
			//arr["vehicle_damage_diagram_coordinates"] = $.parseJSON(coords);
			arr["vehicle_damage_diagram_coordinates"] = $.parseJSON(coords);
		};

		arr["accidentid"] = $("#accidents_edit_data_accidentid").val();

		var json = JSON.stringify(arr);
	}
	else
	{
		var json = existingjson;
		var arr = $.parseJSON(existingjson);

	};

	arr["accidentid"] = $("#accidents_edit_data_accidentid").val();

	//$("#" + otherdriverid).remove();

	var rowhtml = '<td>' + arr["name"] + '</td><td>' + arr["vehicletype"] + '</td><td>' + arr["driverinjured"] + '</td><td>' + arr["drivercited"] + '</td><td>' + arr["vehicledamaged"] + '</td><td style="text-align:right;" class="prevent_td"><button style="padding:1px 5px;margin-left:10px;" class="btn btn-danger glyphicon glyphicon-remove deleteotherdriver"  data-otherdriverid="' + otherdriverid + '"></button></td><td style="display:none;" class="json" data-postkey="' + otherdriverid + '_json">' + json + '</td>';


	if ($("#" + otherdriverid).length) {
		$("#" + otherdriverid).html(rowhtml);
	}
	else
	{
		rowhtml = '<tr id="' + otherdriverid + '" data-thumbnailtype="' + thumbnailtype + '">' + rowhtml + '</tr>';
		$("#accidents_edit_data_other_driver_table tbody").append(rowhtml);

	};

	$("#" . otherdriverid).data("thumbnailtype", thumbnailtype);

	tablesorter($("#accidents_edit_data_other_driver_table"));

	$("#accidents_edit_data_other_driver_table tbody").addClass("whitebg");

	$("#accidents_edit_data_other_driver_table").show();


	$("#accidents_edit_data_other_driver_table .prevent_td").off();
	$("#accidents_edit_data_other_driver_table .prevent_td").click(function(event) {
		event.preventDefault();
		event.stopPropagation();
	});

	$(".deleteotherdriver").click(function(event) {
		event.stopPropagation();
	});

	$(".deleteotherdriver").confirmation({
		"popout": true,
		"singleton": true,
		"btnOkClass": "btn-default",
		"btnCancelClass": "btn-danger",
		"title": "Are You Sure?",
		"container": "body",
		"onConfirm": function() {
			$(".confirmation").confirmation('hide');
			var otherdriverid = $(this).data("otherdriverid");
			$("#" + otherdriverid).remove();
			var rowcount = $("#accidents_edit_data_other_driver_table tbody tr").length;
			if (rowcount < 1) {
				$("#accidents_edit_data_other_driver_table").hide();
			};

		}
	});

	$("#accidents_edit_data_other_driver_table tbody tr").off();

	$("#accidents_edit_data_other_driver_table tbody tr").click(function() {

		var thumbnailtype = $(this).data("thumbnailtype");
		var otherdriverid = $(this).prop("id");
		var json = $(this).find(".json").html();
		var arr = $.parseJSON(json);
		var accidentid = arr["accidentid"];
		$("#accidents_edit_data_other_driver_table_div").hide();
		$("#accidents_edit_data_other_driver_edit_div .clearval").val("");
		$("#accidents_edit_data_other_driver_edit_div .clearvalno").val("No"); // For Yes/No only dropdowns without a blank val
		$("#accidents_edit_data_other_driver_edit_div .clearhtml").html("");
		$("#accidents_edit_data_other_driver_edit_div").fadeIn(250);
		$("#accidents_edit_data_other_driver_id").val(otherdriverid);

		$("#accidents_edit_data_other_driver_vehicle_damage_diagram_coordinates").val("");

		$.each(arr, function(key, val) {

			if (key == "vehicle_damage_diagram_coordinates") {
				$("#accidents_edit_data_other_driver_vehicle_damage_diagram_coordinates").val(JSON.stringify(arr["vehicle_damage_diagram_coordinates"]));
				return;
			};

			var el = $("#accidents_edit_data_other_driver_" + key);

			if (el.hasClass("accidents_edit_data_other_driver_data_val")) {
				el.val(val);
			};
			if (el.hasClass("accidents_edit_data_other_driver_data_html")) {
				el.html(val);
			};
		});

		$("#accidents_edit_data_other_driver_vehicle_damage_diagram_thumbnail").prop("src", "api/showfile.php?type="
		 + thumbnailtype + "&file=" + otherdriverid + ".png&recordid=" + accidentid + "&d=accidents_edit_data_other_driver_vehicle_damage_diagram.png&r=" + Math.random());

		//var scrollto = $('#accidents_edit_data_other_driver_edit_div').position().top - 100;
		//$("html, body").animate({ scrollTop: scrollto }, 600);

	});

};

function addinjurytotable(injuryid, existingjson) {

	if (existingjson.length < 1) {
		var arr = {};

		$(".accidents_edit_data_injury_data_val").each(function() {
			var key = $(this).prop("id").replace("accidents_edit_data_injury_", "");
			arr[key] = $(this).val();
		});

		$(".accidents_edit_data_injury_data_html").each(function() {
			var key = $(this).prop("id").replace("accidents_edit_data_injury_", "");
			arr[key] = $(this).html();
		});

		arr["accidentid"] = $("#accidents_edit_data_accidentid").val();

		var json = JSON.stringify(arr);
	}
	else
	{
		var json = existingjson;
		var arr = $.parseJSON(existingjson);
	};

	var rowhtml = '<td>' + arr["name"] + '</td><td>' + arr["age"] + '</td><td>' + arr["phone"] + '</td><td>' + arr["role"] + '</td><td style="text-align:right;" class="prevent_td"><button style="padding:1px 5px;margin-left:10px;" class="btn btn-danger glyphicon glyphicon-remove deleteinjury"  data-injuryid="' + injuryid + '"></button></td><td style="display:none;" class="json" data-postkey="' + injuryid + '_json">' + json + '</td>';

	if ($("#" + injuryid).length) {
		$("#" + injuryid).html(rowhtml);
	}
	else
	{
		rowhtml = '<tr id="' + injuryid + '">' + rowhtml + '</tr>';
		$("#accidents_edit_data_injury_table tbody").append(rowhtml);

	};

	tablesorter($("#accidents_edit_data_injury_table"));

	$("#accidents_edit_data_injury_table tbody").addClass("whitebg");

	$("#accidents_edit_data_injury_table").show();

	$("#accidents_edit_data_injury_table .prevent_td").off();
	$("#accidents_edit_data_injury_table .prevent_td").click(function(event) {
		event.preventDefault();
		event.stopPropagation();
	});

	$(".deleteinjury").click(function(event) {
		event.stopPropagation();
	});

	$(".deleteinjury").confirmation({
		"popout": true,
		"singleton": true,
		"btnOkClass": "btn-default",
		"btnCancelClass": "btn-danger",
		"title": "Are You Sure?",
		"container": "body",
		"onConfirm": function() {
			$(".confirmation").confirmation('hide');
			var injuryid = $(this).data("injuryid");
			$("#" + injuryid).remove();
			var rowcount = $("#accidents_edit_data_injury_table tbody tr").length;
			if (rowcount < 1) {
				$("#accidents_edit_data_injury_table").hide();
			};

		}
	});

	$("#accidents_edit_data_injury_table tbody tr").off();

	$("#accidents_edit_data_injury_table tbody tr").click(function() {
		var injuryid = $(this).prop("id");
		var json = $(this).find(".json").html();
		var arr = $.parseJSON(json);
		$("#accidents_edit_data_injury_table_div").hide();
		$("#accidents_edit_data_injury_edit_div .clearval").val("");
		$("#accidents_edit_data_injury_edit_div .clearhtml").html("");
		$("#accidents_edit_data_injury_edit_div").fadeIn(250);
		$("#accidents_edit_data_injury_id").val(injuryid);

		$.each(arr, function(key, val) {

			var el = $("#accidents_edit_data_injury_" + key);

			if (el.hasClass("accidents_edit_data_injury_data_val")) {
				el.val(val);
			};
			if (el.hasClass("accidents_edit_data_injury_data_html")) {
				el.html(val);
			};
		});

		//var scrollto = $('#accidents_edit_data_injury_edit_div').position().top - 100;
		//$("html, body").animate({ scrollTop: scrollto }, 600);

	});

};

function addwitnesstotable(witnessid, existingjson) {
	if (existingjson.length < 1) {

		var arr = {};

		$(".accidents_edit_data_witness_data_val").each(function() {
			var key = $(this).prop("id").replace("accidents_edit_data_witness_", "");
			arr[key] = $(this).val();
		});

		$(".accidents_edit_data_witness_data_html").each(function() {
			var key = $(this).prop("id").replace("accidents_edit_data_witness_", "");
			arr[key] = $(this).html();
		});

		arr["accidentid"] = $("#accidents_edit_data_accidentid").val();

		var json = JSON.stringify(arr);
	}
	else
	{
		var json = existingjson;
		var arr = $.parseJSON(existingjson);
	};

	$("#" + witnessid).remove();

	var tbody = '<tr id="' + witnessid + '"><td>' + arr["name"] + '</td><td>' + arr["dayphone"] + '</td><td>' + arr["eveningphone"] + '</td><td style="text-align:right;" class="prevent_td"><button style="padding:1px 5px;margin-left:10px;" class="btn btn-danger glyphicon glyphicon-remove deletewitness"  data-witnessid="' + witnessid + '"></button></td><td style="display:none;" class="json" data-postkey="' + witnessid + '_json">' + json + '</td></tr>';

	$("#accidents_edit_data_witness_table tbody").append(tbody);

	tablesorter($("#accidents_edit_data_witness_table"));

	$("#accidents_edit_data_witness_table tbody").addClass("whitebg");

	$("#accidents_edit_data_witness_table").show();

	$("#accidents_edit_data_witness_table .prevent_td").off();
	$("#accidents_edit_data_witness_table .prevent_td").click(function(event) {
		event.preventDefault();
		event.stopPropagation();
	});

	$(".deletewitness").click(function(event) {
		event.stopPropagation();
	});

	$(".deletewitness").confirmation({
		"popout": true,
		"singleton": true,
		"btnOkClass": "btn-default",
		"btnCancelClass": "btn-danger",
		"title": "Are You Sure?",
		"container": "body",
		"onConfirm": function() {
			$(".confirmation").confirmation('hide');
			var witnessid = $(this).data("witnessid");
			$("#" + witnessid).remove();
			var rowcount = $("#accidents_edit_data_witness_table tbody tr").length;
			if (rowcount < 1) {
				$("#accidents_edit_data_witness_table").hide();
			};

		}
	});

	$("#accidents_edit_data_witness_table tbody tr").off();

	$("#accidents_edit_data_witness_table tbody tr").click(function() {
		var witnessid = $(this).prop("id");
		var json = $(this).find(".json").html();
		var arr = $.parseJSON(json);
		$("#accidents_edit_data_witness_table_div").hide();
		$("#accidents_edit_data_witness_edit_div .clearval").val("");
		$("#accidents_edit_data_witness_edit_div .clearhtml").html("");
		$("#accidents_edit_data_witness_edit_div").fadeIn(250);
		$("#accidents_edit_data_witness_id").val(witnessid);

		$.each(arr, function(key, val) {

			var el = $("#accidents_edit_data_witness_" + key);

			if (el.hasClass("accidents_edit_data_witness_data_val")) {
				el.val(val);
			};
			if (el.hasClass("accidents_edit_data_witness_data_html")) {
				el.html(val);
			};
		});

		//var scrollto = $('#accidents_edit_data_witness_edit_div').position().top - 100;
		//$("html, body").animate({ scrollTop: scrollto }, 600);

	});

};

function filetypelist(setfiletype) {
	var obj = $("#accidents_edit_data_linked_files_filetype");

	obj.empty();

	obj.append($('<option>', {value:"", text:"- Select One -"}));

	var arr;

	$(".glyphicon-upload").each(function() {
		ufiletype = $(this).data("ufiletype");
		arr = ufiletype.split("-");

		obj.append($('<option>', {value:arr[0], text:arr[1]}));

	});

	obj.val(setfiletype);

	$("#maincontainer").hide();

	$("#linkedfilescontainer").fadeIn(250);
};

function uploadfiles(uploadurl) {

	$(function () {
    'use strict';

   $('.fileupload').fileupload({
        url: uploadurl,
        dataType: 'json',
        done: function (e, data) {
            $.each(data.result.files, function (index, file) {
				var accidentid = $("#accidents_edit_data_accidentid").val();
				var filetype = $("#accidents_edit_data_linked_files_filetype").val();
				getlinkedfiles(filetype);
            });
        },
        progressall: function (e, data) {
            var progress = parseInt(data.loaded / data.total * 100, 10);
			$('#progress').show();
            $('#progress .progress-bar').css(
                'width',
                progress + '%'
            );

        }
    }).prop('disabled', !$.support.fileInput)
        .parent().addClass($.support.fileInput ? undefined : 'disabled');
	});


};

function getlinkedfiles(filetype) {

	var arr = {};

	arr["filetype"] = filetype;

	var accidentid = $("#accidents_edit_data_accidentid").val();
	arr["accidentid"] = accidentid;

	return $.post("api/accidents.php", {action: "getlinkedfiles", obj: arr},
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

		var accidentid = $("#accidents_edit_data_accidentid").val();
		var filetype = $("#accidents_edit_data_linked_files_filetype").val();

		$("#accidents_edit_data_linked_files_table tbody").empty();

		$.each(d["existing"], function(key, val) {
			var rowhtml = '<tr><td><a target="_blank" href="api/showfile.php?type=linkedfile&filetype=' + filetype +  '&file=' + val.filename + '&recordid=' + accidentid + '">' + val.filename + '</a></td><td style="text-align:right;"><button class="btn btn-danger glyphicon glyphicon-remove deletefile"  data-type="existing" data-accidentid="' + accidentid + '" data-filetype="' + filetype + '" data-filename="' + val.filename + '"></button>';
			$("#accidents_edit_data_linked_files_table tbody").append(rowhtml);

		});

		$.each(d["new"], function(key, val) {
			var rowhtml = '<tr class="newfile"><td><a target="_blank" href="api/showfile.php?type=templinkedfile&filetype=' + filetype +  '&file=' + val.filename + '&recordid=' + accidentid + '">' + val.filename + '</a></td><td style="text-align:right;"><button class="btn btn-danger glyphicon glyphicon-remove deletefile" data-type="new" data-accidentid="' + accidentid + '" data-filetype="' + filetype + '" data-filename="' + val.filename + '"></button>';
			$("#accidents_edit_data_linked_files_table tbody").append(rowhtml);

		});

		$(".deletefile").off();

		$(".deletefile").confirmation({
			"popout": true,
			"singleton": true,
			"btnOkClass": "btn-default",
			"btnCancelClass": "btn-danger",
			"title": "Are You Sure?",
			"container": "body",
			"placement": "bottom",
			"onConfirm": function() {
				var arr = {};
				arr["type"] = $(this).data("type");
				arr["accidentid"] = $(this).data("accidentid");
				arr["filetype"] = $(this).data("filetype");
				arr["filename"] = $(this).data("filename");

				var filetype = arr["filetype"];

				return $.post("api/accidents.php", {action: "deletefile", obj: arr},
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

					getlinkedfiles(filetype);

				});
			}
		});



	});
};

function saveaccident(scrolltoid) {
	
	if (scrolltoid.length > 0) {
		var scrollto = $('#' + scrolltoid).position().top - 100;
	};

	$("#maincontainer").hide();
	pleasewait.show();

	var arr = {};
	var postkey = "";

	$(".accidents_edit_data_postdata_val").each(function() {
		arr[$(this).data("postkey")] = $(this).val();
	});

	$(".accidents_edit_data_postdata_html").each(function(){
		arr[$(this).data("postkey")] = $(this).html();
	});

	$(".accidents_edit_data_postdata_chk").each(function() {
		arr[$(this).data("postkey")] = $(this).prop("checked");
	});

	arr["damagediagramcoordinates"] = $("#accidents_edit_data_employee_vehicle_damage_diagram_coordinates").val();

	arr["otherdriver"] = {};

	$("#accidents_edit_data_other_driver_table .json").each(function() {
		postkey = $(this).data("postkey");
		arr["otherdriver"][postkey] = $(this).html();
	});

	arr["injury"] = {};
	$("#accidents_edit_data_injury_table .json").each(function() {
		postkey = $(this).data("postkey");
		arr["injury"][postkey] = $(this).html();
	});

	arr["witness"] = {};
	$("#accidents_edit_data_witness_table .json").each(function() {
		postkey = $(this).data("postkey");
		arr["witness"][postkey] = $(this).html();
	});

	$.post("api/accidents.php", {action: "saveaccident", obj: arr},
	function(data) {

		var d = $.parseJSON(data);
		if (d.message != "success") {
			setTimeout(function(){
				pleasewait.hide();
				$("#maincontainer").show();
			}, 100);
			showerrormessage(d.message);
			return false;
		}
		else
		{
			delete d["message"];
		};

		$(".accidents_edit_data_postdata_val").val("");
		$(".accidents_edit_data_postdata_html").html("");
		$(".accidents_edit_data_postdata_chk").prop("checked", false);
		$(".clearval").val("");
		$(".clearvalno").val("No");
		$(".clearhtml").html("");
		$(".diagramcoordinates").val("");
		$(".showtable tbody").empty();
		$(".showtable").hide();
		$("#accidents_edit_data_other_driver_edit_div").hide();
		$("#accidents_edit_data_other_driver_table_div").show();
		$("#accidents_edit_data_injury_edit_div").hide();
		$("#accidents_edit_data_injury_table_div").show();
		$("#accidents_edit_data_witness_edit_div").hide();
		$("#accidents_edit_data_witness_table_div").show();
		
		deletethumbnails();

		$.when( getaccidentdata(arr["accidentid"]) ).done(function() {			
			setTimeout(function(){
				pleasewait.hide();
				$("#maincontainer").show();				
				if (scrolltoid.length > 0) {
					$("html, body").animate({ scrollTop: scrollto }, 600);
				};				
			}, 500);

		});

	});

};

function getuniqueid(prefix, obj) {

	var arr = {};

	arr["prefix"] = prefix;

	$.post("api/accidents.php", {action: "getuniqueid", obj: arr},
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

		obj.val(d["uniqueid"]);

	});
};

