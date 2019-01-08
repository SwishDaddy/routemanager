$(document).ready(function() {

	$(".btn").click(function() {
		$("[data-dismiss='confirmation']").parent().parent().parent().hide();
	});

	$(".threenumbers").mask("000");
	$(".fournumbers").mask("0000");
	$(".fivenumbers").mask("00000");
	$(".sixnumbers").mask("000000");

	$("#fluids_view_data_delete_btn").confirmation({
		"popout": true,
		"btnOkClass": "btn-default",
		"btnCancelClass": "btn-danger",
		"placement": "bottom",
		"title": "Are You Sure?",
		"container": "body",
		"onConfirm": function() {
			var entryid = $("#fluids_edit_data_entryid").val();
			deleteentry(entryid);
		}
	});

	$(".datetimepicker").val("");
	$(".datepicker").val("");

	var current_datetime_string = $("#fluids_criteria_daterange_todate").val();
	var past_datetime_string = $("#fluids_criteria_daterange_fromdate").val();

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

	$("#fluids_criteria_daterange_todate").val(current_datetime_string);
	$("#fluids_criteria_daterange_fromdate").val(past_datetime_string);

	$("input").on("change", function() {
		$("*").removeClass("error");
	});

	$("select").on("change", function() {
		$("*").removeClass("error");
	});

	$("#fluids_criteria_btnfind").click(function() {
		getfluids();
	});

	$("#fluids_new_data_btn").click(function() {
		$(".fluids_view_data").hide();
		$.when( getvehicles( $("#fluids_edit_data_vehicle_name") ) ).done(function() {
			$.when( getusers( $("#fluids_edit_data_fueler_name") ) ).done(function() {

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

				$("#fluids_edit_data_adatetime").val(current_datetime_string);

				$(".fluids_edit_data").fadeIn(250);

				$("#fluids_edit_data_vehicle_name").focus();

			});
		});
	});

	$("#fluids_cancel_data_btn").click(function() {
		$(".fluids_edit_data_val").val("");
		$(".fluids_edit_data").hide();
		$.when( getfluids() ).done(function() {
			$(".fluids_view_data").fadeIn(250);
		});

	});

	$("#fluids_save_data_btn").click(function() {
		saveentry();
	});

	$("#fluids_save_data_and_new_btn").click(function() {

		var err = false;

		$(".fluids_edit_data_val").each(function() {
			if ( $(this).hasClass("isrequired") ) {
				if( $(this).val().length < 1) {
					$(this).addClass("error");
					err = true;
					$(this).focus();
					return false;
				};
			};
		});

		if (!err) {
			$.when( saveentry() ).done(function() {
				$(".fluids_edit_data_val").val("");
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

				$("#fluids_edit_data_adatetime").val(current_datetime_string);


			});
		};

		$("#fluids_edit_data_vehicle_name").focus();

	});

	$("#fluids_btn_createreport").click(function() {

		var tablehtml = $("#fluids_view_data_table_parent").html();

		var reporttitle = "Fluids Report";

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

	$("#fluids_edit_data_washerfluid").on({
		"keydown": function(e){
			if (e.which==9){
				e.preventDefault();
				$("#fluids_save_data_and_new_btn").focus();
			}
		}
	});

	$(".fluids_edit_data_val").keydown(function (e) {

		if(e.keyCode === 13){
			e.preventDefault();
			//$(this).nextAll('input:visible').eq(0).focus();
		};


	});


	$.when (getfluids() ).done(function() {
		$(".container").fadeIn(150);
	});


});

function getfluids() {

	$("#fluids_view_data_table_div").hide();

	var arr = {};

	var fromdate = $("#fluids_criteria_daterange_fromdate").val();
	if (fromdate.length != 10) {
		$("#fluids_criteria_daterange_fromdate").addClass("error").focus();
		return false;
	};

	var todate = $("#fluids_criteria_daterange_todate").val();
	if (todate.length != 10) {
		$("#fluids_criteria_daterange_todate").addClass("error").focus();
		return false;
	};

	arr["fromdate"] = fromdate;
	arr["todate"] = todate;

	pleasewait.show();

	return $.post("api/fluids.php", {action: "getfluids", obj: arr},
	function(data) {

		var d = $.parseJSON(data);
		if (d.message != "success") {
			setTimeout(function(){
				pleasewait.hide();
			}, 250);
			showerrormessage(d.message);
			return false;
		}
		else
		{
			delete d["message"];
		};

		var tbl = $("#fluids_view_data_table");

		tbl.trigger("destroy");

		tbl.find("thead")
		.empty()
		.append(
			'<tr>' +
				'<th>Vehicle Name</th>' +
				'<th>Date</th>' +
				'<th>Fueler</th>' +
				'<th>Shift</th>' +
				'<th>Mileage</th>' +
				'<th>Hours</th>' +
				'<th>CNG</th>' +
				'<th>Oil</th>' +
				'<th>Coolant</th>' +
				'<th>Washer Fluid</th>' +
			'<tr/>'
		);

		tbl.find("thead tr th").attr({
			'style': 'background-color:#f2f2f2;'
		});

		tbl.find("tbody").empty();

		$.each(d, function(key, val) {
			var id = key;
			var adatetime = val["adatetime"];
			var fuelername = val["fuelername"];
			var vehiclename = val["vehiclename"];
			var shift = val["shift"];
			var mileage = val["mileage"];
			var hours = val["hours"];
			var cng = val["cng"];
			var oil = val["oil"];
			var coolant = val["coolant"];
			var washerfluid = val["washerfluid"];

			tbl.find("tbody")
			.append(
				'<tr data-id="' + id + '" style="height:2em;">' +
					'<td class="vehiclename" style="height:2em;" valign="top">' + vehiclename + '</td>' +
					'<td class="adatetime" style="height:2em;" class="localstorage" valign="top">' + adatetime + '</td>' +
					'<td class="fuelername" style="height:2em;" valign="top">' + fuelername + '</td>' +
					'<td class="shift" style="height:2em;" valign="top">' + shift + '</td>' +
					'<td class="mileage" style="height:2em;" valign="top">' + mileage + '</td>' +
					'<td class="hours" style="height:2em;" valign="top">' + hours + '</td>' +
					'<td class="cng" style="height:2em;" valign="top">' + cng + '</td>' +
					'<td class="oil" style="height:2em;" valign="top">' + oil + '</td>' +
					'<td class="coolant" style="height:2em;" valign="top">' + coolant + '</td>' +
					'<td class="washerfluid" style="height:2em;" valign="top">' + washerfluid + '</td>' +
				'</tr>'
			);

		});

		tablesorter(tbl);

		tbl.find("tbody tr").on("click", function() {
			var rowid = $(this).data("id");
			$("#fluids_edit_data_entryid").val(rowid);
			$(".fluids_view_data").hide();
			getentrydata(rowid);
		});

		setTimeout(function(){
			pleasewait.hide();
			$("#fluids_view_data_table_div").show();
		}, 250);

	});


};

function getusers(obj) {

	var arr = {};

	// Got to have something in the obj!
	arr["blah"] = "blah";

	return $.post("api/fluids.php", {action: "getusers", obj: arr},
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

function getvehicles(obj) {

	$("*").removeClass("error");

	obj.empty();
	obj.append($('<option>', {value:"", text:"- Select One -"}));

	var arr = {};

	arr["blah"] = "blah";

	return $.post("api/fluids.php", {action: "getvehicles", obj: arr},
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

function saveentry() {

	$("*").removeClass("error");

	var arr = {};

	var err = false;

	$(".fluids_edit_data_val").each(function() {
		if ( $(this).hasClass("isrequired") ) {
			if( $(this).val().length < 1) {
				$(this).addClass("error");
				err = true;
				return false;
			};
		};
	});

	if (err) {
		return false;
	};

	//pleasewait.show();

	$(".fluids_edit_data_val").each(function() {
		arr[$(this).data('key')] = $(this).val();
	});

	return $.post("api/fluids.php", {action: "saveentry", obj: arr},
	function(data) {

		var d = $.parseJSON(data);
		if (d.message != "success") {
			setTimeout(function(){
				pleasewait.hide();
			}, 250);
			showerrormessage(d.message);
			return false;
		}
		else
		{
			delete d["message"];
		};

		var entryid = d["entryid"];

		$("#fluids_edit_data_entryid").val(entryid);


		setTimeout(function(){
			//pleasewait.hide();
		}, 250);

	});


};

function getentrydata(rowid) {

	var arr = {};

	arr["entryid"] = rowid;

	$(".fluids_view_data").hide();

	pleasewait.show();

	return $.post("api/fluids.php", {action: "getentrydata", obj: arr},
	function(data) {

		var d = $.parseJSON(data);
		if (d.message != "success") {
			setTimeout(function(){
				pleasewait.hide();
			}, 100);
			showerrormessage(d.message);
			$(".fluids_view_data").show();
			return false;
		}
		else
		{
			delete d["message"];
		};


		$.when( getvehicles( $("#fluids_edit_data_vehicle_name") ) ).done(function() {
			$.when( getusers( $("#fluids_edit_data_fueler_name") ) ).done(function() {

				$("#fluids_edit_data_entryid").val(d["entryid"]);
				$("#fluids_edit_data_fueler_name").val(d["fuelerid"]);
				$("#fluids_edit_data_vehicle_name").val(d["vehicleid"]);
				$("#fluids_edit_data_adatetime").val(d["adatetime"]);
				$("#fluids_edit_data_shift").val(d["shift"]);
				$("#fluids_edit_data_mileage").val(d["mileage"]);
				$("#fluids_edit_data_hours").val(d["hours"]);
				$("#fluids_edit_data_cng").val(d["cng"]);
				$("#fluids_edit_data_oil").val(d["oil"]);
				$("#fluids_edit_data_coolant").val(d["coolant"]);
				$("#fluids_edit_data_washerfluid").val(d["washerfluid"]);


				setTimeout(function(){
					pleasewait.hide();
					$(".fluids_edit_data").fadeIn(250);
				}, 250);


			});
		});


	});

};

function deleteentry(entryid) {

	var arr = {};

	arr["entryid"] = entryid;

	return $.post("api/fluids.php", {action: "deleteentry", obj: arr},
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

		$.when( getfluids() ).done(function() {
			$("#fluids_cancel_data_btn").trigger("click");
		});

	});
};


