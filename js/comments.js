$(document).ready(function() {
	
	$(".btn").click(function() {
		$("[data-dismiss='confirmation']").parent().parent().parent().hide();
	});


	$("#comments_view_data_delete_btn").confirmation({
		"popout": true,
		"btnOkClass": "btn-default",
		"btnCancelClass": "btn-danger",
		"placement": "bottom",
		"title": "Are You Sure?",
		"container": "body",
		"onConfirm": function() {
			var commentid = $("#comments_edit_data_commentid").val();
			deletecomment(commentid);
		}
	});


	$(".datetimepicker").datetimepicker("destroy");
	$(".datetimepicker").val("");

	$(".datetimepicker").datetimepicker({
		//format:'m/d/Y H:i'
		format:'m/d/Y',
		timepicker:false,
		scrollMonth : false,
		scrollInput : false
	});

	var current_datetime_string = $("#comments_criteria_daterange_todate").val();
	var past_datetime_string = $("#comments_criteria_daterange_fromdate").val();

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

	$("#comments_criteria_daterange_todate").val(current_datetime_string);
	$("#comments_criteria_daterange_fromdate").val(past_datetime_string);

	$("input").on("change", function() {
		$("*").removeClass("error");
	});

	$("select").on("change", function() {
		$("*").removeClass("error");
	});

	$("#comments_view_data_btn").click(function() {
		$(".comments_edit_data").hide();
		$.when( getcomments() ).done(function() {
			$(".comments_view_data").show();
		});
	});

	$("#comments_new_data_btn").click(function() {
		$(this).hide();
		$(".comments_view_data").hide();
		$(".comments_edit_data").show();
		$("#comments_edit_data_commentid").val("");
		$("#comments_edit_data_date_of_incident").val(getcurrentdate());
		$("#comments_edit_data_customer_name").val("");
		$("#comments_edit_data_comment_text").html("");
		$("#comments_edit_data_resolution_text").html("");
		getroutes($("#comments_edit_data_location_of_incident"));
		getusers($("#comments_edit_data_employee_involved"));
		getvehicles($("#comments_edit_data_vehicle_name"));
	});

	$("#comments_save_data_btn").click(function() {
		$.when( savecomment() ).done(function() {
			//getcomments();
		});
	});


	$("#comments_save_data_and_new_btn").click(function() {
		$.when( savecomment() ).done(function() {
			$("#comments_new_data_btn").trigger("click");
		});
	});

	$("#comments_criteria_btnfind").click(function() {
		getcomments();
	});

	$('.comments_edit_data_comment_type').click(function() {
		if ($(this).is(':checked')) {
			$("#comments_edit_data_comment_text").removeAttr("color");
			//$("#comments_edit_data_comment_text").css("color", "white");
			//$("#comments_edit_data_comment_text").css("font-size", "1.5em");
			$("#comments_edit_data_comment_text_label_type").html($(this).data("label_value"));
			$("#comments_edit_data_comment_text").css("background-color", $(this).data("bgcolor"));
			$("#comments_edit_data_comment_text").css("border-color", $(this).data("color"));
			$("#comments_edit_data_comment_text_label").css("color", $(this).data("color"));

		};
	});

	$("#comments_btn_createreport").click(function() {

		var tablehtml = $("#comments_results_table_parent").html();

		var reporttitle = "Comments Report";

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

		});

	});


	$("#comments_btnemployee_summary_report").click(function() {

		$("#comments_employee_summary_results_table").empty();

		$.when( getemployeesummarydata() ).done(function() {

			var tablehtml = $("#comments_employee_summary_results_table_div").html();

			var reporttitle = "Comments Report";

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

			});

		});

	});

	$('#comments_edit_data_comment_type_complaint').trigger("click");

	$.when (getcomments() ).done(function() {
		$(".container").fadeIn(150);
	});

});

function getcomments() {

	$("#comments_results_table_div").hide();

	var arr = {};

	var fromdate = $("#comments_criteria_daterange_fromdate").val();
	if (fromdate.length != 10) {
		$("#comments_criteria_daterange_fromdate").addClass("error").focus();
		return false;
	};


	var todate = $("#comments_criteria_daterange_todate").val();
	if (todate.length != 10) {
		$("#comments_criteria_daterange_todate").addClass("error").focus();
		return false;
	};

	arr["fromdate"] = fromdate;
	arr["todate"] = todate;

	pleasewait.show();

	return $.post("api/comments.php", {action: "getcomments", obj: arr},
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

		var tbl = $("#comments_results_table");

		tbl.trigger("destroy");

		tbl.find("thead")
		.empty()
		.append(
			'<tr>' +
				'<th>Date of Incident</th>' +
				'<th>Customer Name</th>' +
				'<th>Employee Involved</th>' +
				'<th>Location of Incident</th>' +
				'<th>Vehicle Name</th>' +
				'<th>Comment Type</th>' +
				'<th>Comment Text</th>' +
				'<th>Resolution</th>' +
			'<tr/>'
		);

		tbl.find("thead tr th").attr({
			'style': 'background-color:#f2f2f2;'
		});

		tbl.find("tbody").empty();

		$.each(d, function(key, val) {
			var id = key;
			var adatetime = val["adatetime"];
			var customername = val["customername"];
			var userid = val["userid"];
			var employeename = val["employeename"];
			var routeid = val["routeid"];
			var routename = val["routename"];
			var vehicleid = val["vehicleid"];
			var vehiclename = val["vehiclename"];
			var commenttype = val["commenttype"];
			var commenttext = val["commenttext"];
			var resolutiontext = val["resolutiontext"];
			var commenttype_style = "";

			if (commenttype == "complaint") {
				commenttype_style = ' style="color:blue;height:2em;" ';
			};
			if (commenttype == "compliment") {
				commenttype_style = ' style="color:green;height:2em;" ';
			};
			if (commenttype == "comment") {
				commenttype_style = ' style="color:black;height:2em;" ';
			};

			tbl.find("tbody")
			.append(
				'<tr data-id="' + id + '" style="height:2em;">' +
					'<td class="adatetime" style="height:2em;" valign="top"><span ' + commenttype_style + '>' + adatetime + '</span></td>' +
					'<td class="customername" style="height:2em;" valign="top"><span ' + commenttype_style + '>' + customername + '</span></td>' +
					'<td class="employeename" style="height:2em;" valign="top"><span ' + commenttype_style + '>' + employeename + '</span></td>' +
					'<td class="routename" style="height:2em;" valign="top"><span ' + commenttype_style + '>' + routename + '</span></td>' +
					'<td class="vehiclename" style="height:2em;" valign="top"><span ' + commenttype_style + '>' + vehiclename + '</span></td>' +
					'<td class="commenttype" style="height:2em;" valign="top"><span ' + commenttype_style + '>' + commenttype + '</span></td>' +
					'<td class="commenttext" style="height:2em;" valign="top"><span ' + commenttype_style + '>' + commenttext + '</span></td>' +
					'<td class="commenttext" style="height:2em;" valign="top"><span ' + commenttype_style + '>' + resolutiontext + '</span></td>' +
				'</tr>'
			);

		});

		tablesorter(tbl);

		tbl.find(".commenttype").css('textTransform', 'capitalize');

		tbl.find(".commenttype").addClass("block-with-text");

		tbl.find("tbody tr").on("click", function() {
			var rowid = $(this).data("id");
			$("#comments_edit_data_commentid").val(rowid);
			$(".comments_view_data").hide();
			getcommentdata(rowid)
		});

		setTimeout(function(){
			pleasewait.hide();
			$("#comments_results_table_div").show();
		}, 500);

	});

};

function getusers(obj) {

	var arr = {};

	arr["blah"] = "blah";

	return $.post("api/comments.php", {action: "getusers", obj: arr},
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

function getroutes(obj) {

	var arr = {};

	arr["status"] = "active";

	return $.post("api/comments.php", {action: "getroutes", obj: arr},
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

		obj.prepend($('<option>', {value:"", text:"- Select One -"}));
		obj.val("");

	});

};

function getvehicles(obj) {

	$("*").removeClass("error");

	obj.empty();
	obj.append($('<option>', {value:"", text:"- Select One -"}));

	var arr = {};

	arr["blah"] = "blah";

	return $.post("api/comments.php", {action: "getvehicles", obj: arr},
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

function savecomment() {

	$("*").removeClass("error");

	var commentid = $("#comments_edit_data_commentid").val();

	var date_of_incident = $("#comments_edit_data_date_of_incident").val();
	if (date_of_incident.length != 10) {
		$("#comments_edit_data_date_of_incident").addClass("error").focus();
		return false;
	};


	var customer_name = $("#comments_edit_data_customer_name").val();
	/*
	if (customer_name.length < 1) {
		$("#comments_edit_data_customer_name").addClass("error").focus();
		return false;
	};
	*/
	var employee_name = $("#comments_edit_data_employee_involved").val();
	/*
	if (employee_name.length < 1) {
		$("#comments_edit_data_employee_involved").addClass("error").focus();
		return false;
	};
	*/
	var location_of_incident = $("#comments_edit_data_location_of_incident").val();
	/*
	if (location_of_incident.length < 1) {
		$("#comments_edit_data_location_of_incident").addClass("error").focus();
		return false;
	};
	*/
	var vehicle = $("#comments_edit_data_vehicle_name").val();
	/*if (vehicle.length < 1) {
		$("#comments_edit_data_vehicle_name").addClass("error").focus();
		return false;
	};
	*/

	var comment_text = $("#comments_edit_data_comment_text").html();
	if (comment_text.length < 1) {
		$("#comments_edit_data_comment_text").addClass("error").focus();
		return false;
	};

	var resolution_text = $("#comments_edit_data_resolution_text").html();

	var comment_type = "";

	$(".comments_edit_data_comment_type").each(function() {
		if ($(this).is(':checked')) {
			comment_type = $(this).data("type");
		};
	});

	var arr = {};

	arr["commentid"] = commentid;
	arr["date_of_incident"] = date_of_incident;
	arr["customer_name"] = customer_name;
	arr["userid"] = employee_name;
	arr["routeid"] = location_of_incident;
	arr["vehicleid"] = vehicle;
	arr["commenttype"] = comment_type;
	arr["commenttext"] = comment_text;
	arr["resolutiontext"] = resolution_text;

	pleasewait.show();

	return $.post("api/comments.php", {action: "savecomment", obj: arr},
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

		setTimeout(function(){
			pleasewait.hide();
			//$("#comments_view_data_btn").trigger("click");
		}, 500);

	});

};

function getcommentdata(commentid) {

	var arr = {};

	arr["commentid"] = commentid;

	return $.post("api/comments.php", {action: "getcommentdata", obj: arr},
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

		$("#comments_edit_data_comment_text").html("");
		$("#comments_edit_data_resolution_text").html("");

		$.when( getroutes($("#comments_edit_data_location_of_incident")) ).done(function() {
			$.when( getusers($("#comments_edit_data_employee_involved")) ).done(function() {
				$.when( getvehicles($("#comments_edit_data_vehicle_name")) ).done(function() {

					$("#comments_edit_data_date_of_incident").val(d.adatetime);
					$("#comments_edit_data_customer_name").val(d.customername);
					$("#comments_edit_data_employee_involved").val(d.userid);
					$("#comments_edit_data_location_of_incident").val(d.routeid);
					$("#comments_edit_data_vehicle_name").val(d.vehicleid);

					$('input[name="comments_edit_data_comment_type"]').each(function() {
						var type = $(this).data("type");
						if (type == d.commenttype) {
							$(this).prop("checked", true);
							$(this).trigger("click");
						};

					});

					$("#comments_edit_data_comment_text").html(d.commenttext);
					$("#comments_edit_data_resolution_text").html(d.resolutiontext);


					$(".comments_edit_data").fadeIn(150);

					return true;

				});
			});
		});

	});

};

function deletecomment(commentid) {

	var arr = {};

	arr["commentid"] = commentid;

	return $.post("api/comments.php", {action: "deletecomment", obj: arr},
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

		$.when( getcomments() ).done(function() {
			$("#comments_view_data_btn").trigger("click");
		});

	});
};

function getcurrentdate() {

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

	return current_datetime_string;
};

function getemployeesummarydata() {

	var arr = {};

	var fromdate = $("#comments_criteria_daterange_fromdate").val();
	if (fromdate.length != 10) {
		$("#comments_criteria_daterange_fromdate").addClass("error").focus();
		return false;
	};


	var todate = $("#comments_criteria_daterange_todate").val();
	if (todate.length != 10) {
		$("#comments_criteria_daterange_todate").addClass("error").focus();
		return false;
	};

	arr["fromdate"] = fromdate;
	arr["todate"] = todate;

	return $.post("api/comments.php", {action: "employeesummaryreport", obj: arr},
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

		var html =  '<thead>' +
					'	<tr>' +
					'		<th>Employee Name</th><th>Comment Type</th><th style="text-align:right;">Total Comments</th>' +
					'	</tr>' +
					'</thead>' +
					'<tbody>';

		var tdhtml = "";

		$.each(d, function(key, val) {

			var userid = key;
			var fullname = val.fullname;

			delete val.fullname;

			$.each(val, function(key1, val1) {
				var commenttype = key1;
				var	commentcount = val1;

				tdhtml = tdhtml +
					'<tr>' +
					'	<td>' + fullname + '</td><td class="commenttype">' + commenttype + '</td><td style="text-align:right;">' + commentcount + '</td>' +
					'</tr>';

			});

		});

		html = html + tdhtml + '</tbody>';

		$("#comments_employee_summary_results_table").html(html);

		tablesorter($("#comments_employee_summary_results_table"));

		$("#comments_employee_summary_results_table").find(".commenttype").css('textTransform', 'capitalize');

	});

};


