$(document).ready(function() {

	$('.list_order_table tbody').sortable();

	$("#admin_select_user").change(function() {
		var userid = $(this).val();
		edituser(userid);
	});

	$("#admin_edit_user_info_btnpassword").click(function() {
		$("#admin_edit_user_info_main_btn_div").css("visibility", "hidden");
		$("#admin_edit_user_info_btnpassword_div").hide();
		$("#admin_edit_user_info_setpassword").val("");
		$("#admin_edit_user_info_setpassword_div").show();
	});

	$("#admin_edit_user_info_setpassword_btncancel").click(function() {
		$("#admin_edit_user_info_setpassword_div").hide();
		$("#admin_edit_user_info_setpassword").val("");
		$("#admin_edit_user_info_main_btn_div").css("visibility", "visible");
		$("#admin_edit_user_info_btnpassword_div").fadeIn(250);
	});

	$("#admin_edit_user_info_setpassword_btnapply").click(function() {

		$("*").removeClass("error");

		var pwd = $("#admin_edit_user_info_setpassword").val();

		if (pwd.length < 1) {
			showerrormessage("Please provide a password");
			$("#admin_edit_user_info_setpassword").addClass("error");
			return false;
		};

		var userid = $("#admin_select_user").val();

		setpassword(userid, pwd);

		$("#admin_edit_user_info_setpassword_btncancel").trigger("click");
	});

	$("#admin_edit_user_info_btnapply").click(function() {
		var userid = $("#admin_select_user").val();
		saveuserinfo(userid);
	});

	$("#admin_edit_user_info_btncancel").click(function() {

		pleasewait.show();

		$("#admin_edit_user_info_div").hide();
		$(".admin_edit_user_info").val("");
		$("#admin_select_user").val("");

		setTimeout(function(){
			pleasewait.hide();
		}, 500);
		return false;

	});

	$("#admin_btndeleteuser").click(function() {
		$("[data-dismiss='confirmation']").trigger("click");
	});

	$("#admin_btndeleteuser").confirmation({
		"popout": true,
		"singleton": true,
		"btnOkClass": "btn-default",
		"btnCancelClass": "btn-danger",
		"title": "Are You Sure?",
		"container": "body",
		"onConfirm": function() {
			deleteuser();
		}
	});

	$("#admin_edit_vehicle_info_btndeletevehicle_type").click(function() {
		$("[data-dismiss='confirmation']").trigger("click");
	});

	$("#admin_edit_vehicle_info_btndeletevehicle_type").confirmation({
		"popout": true,
		"singleton": true,
		"btnOkClass": "btn-default",
		"btnCancelClass": "btn-danger",
		"title": "Are You Sure?",
		"container": "body",
		"onConfirm": function() {
			deletevehicletype();
		}
	});

	$("#admin_newuser").click(function() {
		$("#admin_edit_user_info_btnpassword_div").hide();
		$("#admin_select_user").val("");
		edituser("");
	});

	$("#admin_btnusermanagement").click(function() {
		$(".admin_parent_div").hide();

		$("#admin_edit_user_info_btncancel").trigger("click");

		$.when( getuserlist($("#admin_select_user"), "") ).done(function() {
			$("#admin_usermanagement_div").fadeIn(250);
		});

	});

	$("#admin_btnvehiclemanagement").click(function() {
		$(".admin_parent_div").hide();
		var vehicletype = $("#admin_edit_vehicle_info_type");
		$.when( getvehicletypes(vehicletype) ).done(function() {
			$("#admin_vehiclemanagement_div").fadeIn(250);
		});
	});

	$("#admin_btnroutemanagement").click(function() {
		$(".admin_parent_div").hide();
		var route = $("#admin_edit_route_info_name");
		var routetype = $("#admin_edit_route_info_routetype").val();
		$.when( getroutes(route, routetype) ).done(function() {
			$("#admin_routemanagement_div").fadeIn(250);
		});
	});

	$("#admin_edit_vehicle_info_type").change(function() {
		//$(".admin_parent_div").hide();

		//$("#admin_edit_vehicle_info_name_select_div").hide();
		$("#admin_edit_vehicle_info_name_div").hide();
		$("#admin_edit_vehicle_info_vin_div").hide();
		$("#admin_edit_vehicle_info_licenseplate_div").hide();

		var vehicletype = $("#admin_edit_vehicle_info_type").val();

		if (vehicletype.length < 1)  {
			//$("#admin_edit_vehicle_info_name_select_div").hide();
			$("#admin_edit_vehicle_info_name_div").hide();
			$("#admin_edit_vehicle_info_name").empty();
			$("#admin_edit_vehicle_info_vin_div").hide();
			$("#admin_edit_vehicle_info_vin").empty();
			$("#admin_edit_vehicle_info_licenseplate_div").hide();
			$("#admin_edit_vehicle_info_licenseplate").empty();
			return false;
		};

		$.when( getvehicles(vehicletype, $("#admin_edit_vehicle_info_name")) ).done(function() {
			//$("#admin_edit_vehicle_info_name_select_div").fadeIn(250);
			$("#admin_edit_vehicle_info_name_div").fadeIn(250);
		});
	});

	$("#admin_edit_vehicle_info_name").change(function() {

		$("#admin_edit_vehicle_info_vin_div").hide();
		$("#admin_edit_vehicle_info_licenseplate_div").hide();
		$("#admin_edit_vehicle_info_vin").val("");
		$("#admin_edit_vehicle_info_licenseplate").val("");

		var vehicleid = $("#admin_edit_vehicle_info_name").val();

		if (vehicleid.length < 1)  {
			$("#admin_edit_vehicle_info_vin_div").hide();
			$("#admin_edit_vehicle_info_vin").val("");
			$("#admin_edit_vehicle_info_licenseplate_div").hide();
			$("#admin_edit_vehicle_info_licenseplate").val("");
			return false;
		};

		$.when( getvehicleinfo(vehicleid) ).done(function() {
			$("#admin_edit_vehicle_info_vin_div").fadeIn(250);
			$("#admin_edit_vehicle_info_licenseplate_div").fadeIn(250);
		});

	});

	$("#admin_btnroutemanagement").click(function() {
		$(".admin_parent_div").hide();
		$("#admin_routemanagement_div").fadeIn(250);
	});

	$("#admin_edit_vehicle_info_btnnewvehicle").click(function() {

		$("#admin_edit_vehicle_info_name_select_div").hide();
		//$("#admin_edit_vehicle_info_name_div").hide();
		$("#admin_edit_vehicle_info_name_input_div").fadeIn(250);
		$("#admin_edit_vehicle_info_vin_div").hide();
		$("#admin_edit_vehicle_info_vin").val("");
		$("#admin_edit_vehicle_info_licenseplate_div").hide();
		$("#admin_edit_vehicle_info_licenseplate").val("");

		$("#admin_edit_vehicle_info_new_name").focus();

	});

	$("#admin_edit_vehicle_info_btnnewvehicle_type").click(function() {

		$("#admin_edit_vehicle_info_name_div").hide();
		$("#admin_edit_vehicle_info_name").empty();
		$("#admin_edit_vehicle_info_vin_div").hide();
		$("#admin_edit_vehicle_info_vin").val("");
		$("#admin_edit_vehicle_info_licenseplate_div").hide();
		$("#admin_edit_vehicle_info_licenseplate").val("");
		$("#admin_edit_vehicle_info_type_select_div").hide();
		$("#admin_edit_vehicle_info_type_input_div").fadeIn(250);

		$("#admin_edit_vehicle_info_new_type").focus();

	});

	$("#admin_edit_vehicle_info_new_type_btncancel").click(function() {
		$("#admin_edit_vehicle_info_type_input_div").hide();
		$("#admin_edit_vehicle_info_new_type").val("");
		var vehicletype = $("#admin_edit_vehicle_info_type");
		$.when( getvehicletypes(vehicletype) ).done(function() {
			$("#admin_edit_vehicle_info_type_select_div").fadeIn(250);
		});
	});

	$("#admin_edit_vehicle_info_new_name_btncancel").click(function() {
		$("#admin_edit_vehicle_info_name_input_div").hide();
		$("#admin_edit_vehicle_info_new_name").val("");
		var vehicletype = $("#admin_edit_vehicle_info_type").val();
		$.when( getvehicles(vehicletype, $("#admin_edit_vehicle_info_name")) ).done(function() {
			$("#admin_edit_vehicle_info_name_select_div").fadeIn(250);
			$("#admin_edit_vehicle_info_name_div").fadeIn(250);
		});
	});

	$("#admin_edit_vehicle_info_new_type_btnapply").click(function() {

		$.when( savevehicletype() ).done(function() {
			$("#admin_edit_vehicle_info_new_type_btncancel").trigger("click");
		});
	});

	$("#admin_edit_vehicle_info_new_name_btnapply").click(function() {

		var vehicletype = $("#admin_edit_vehicle_info_type").val();
		var vehiclename = $("#admin_edit_vehicle_info_new_name").val();

		$.when( savenewvehicle(vehicletype, vehiclename) ).done(function() {
			$("#admin_edit_vehicle_info_new_name_btncancel").trigger("click");

		});

	});

	$(".admin_edit_vehicle_info_save_info").click(function() {

		var vehicleid = $("#admin_edit_vehicle_info_name").val();
		var vin = $("#admin_edit_vehicle_info_vin").val();
		var licenseplate = $("#admin_edit_vehicle_info_licenseplate").val();
		savevehicleinfo(vehicleid, vin, licenseplate);

	});

	$("#admin_edit_vehicle_info_btndeletevehicle").click(function() {
		$("[data-dismiss='confirmation']").trigger("click");
	});

	$("#admin_edit_vehicle_info_btndeletevehicle").confirmation({
		"popout": true,
		"singleton": true,
		"btnOkClass": "btn-default",
		"btnCancelClass": "btn-danger",
		"title": "Are You Sure?",
		"container": "body",
		"onConfirm": function() {
			deletevehiclename();
		}
	});

	$("#admin_edit_route_info_btnnewroute").click(function() {
		$(".admin_edit_route_info_mileage").hide();
		$("#admin_edit_route_info_mileage_value").val("");
		$("#admin_edit_route_info_name_select_div").hide();
		$("#admin_edit_route_info_name_input_div").fadeIn(250);
		$("#admin_edit_route_info_new_route").focus();

	});

	$("#admin_edit_route_group_btnnewgroup").click(function() {
		$("#admin_edit_route_info_group_select_div").hide();

		$("#admin_edit_route_info_route_group_members_div").hide();
		$("#admin_edit_route_info_tblgroupmembers tbody").empty();

		$("#admin_edit_route_info_group_input_div").fadeIn(250);
		$("#admin_edit_route_info_new_routegroup").focus();

	});

	$("#admin_edit_route_info_btndeleteroute").click(function() {
		$("[data-dismiss='confirmation']").trigger("click");
	});

	$("#admin_edit_route_info_btndeleteroute").confirmation({
		"popout": true,
		"singleton": true,
		"btnOkClass": "btn-default",
		"btnCancelClass": "btn-danger",
		"title": "Are You Sure?",
		"container": "body",
		"onConfirm": function() {
			$.when (deleteroute($("#admin_edit_route_info_name").val()) ).done(function() {
				$(".admin_edit_route_info_mileage").hide();
				$(".admin_edit_route_info_mileage").val("");
			});
		}
	});

	$("#admin_edit_route_info_new_route_btncancel").click(function() {
		$("#admin_edit_route_info_name_input_div").hide();
		$("#admin_edit_route_info_new_route").val("");
		var route = $("#admin_edit_route_info_name");
		var routetype = $("#admin_edit_route_info_routetype").val();
		$.when( getroutes(route, routetype) ).done(function() {
			$(".admin_edit_route_info_mileage").hide();
			$(".admin_edit_route_info_mileage").val("");
			$("#admin_edit_route_info_name_select_div").show();
		});
	});

	$("#admin_edit_route_info_new_route_btnapply").click(function() {
		var routename = $("#admin_edit_route_info_new_route").val();
		var routetype = $("#admin_edit_route_info_routetype").val();

		if( saveroute(routename, routetype) ) {
			$("#admin_edit_route_info_new_route_btncancel").trigger("click");
		};
	});

	$("#admin_edit_route_info_new_routegroup_btnapply").click(function() {
		var groupname = $("#admin_edit_route_info_new_routegroup").val();

		if( savenewroutegroup(groupname) ) {
			$("#admin_edit_route_info_new_routegroup_btncancel").trigger("click");
		};
	});

	$("#admin_edit_route_info_new_routegroup_btncancel").click(function() {
		var obj = $("#admin_edit_route_info_group");
		$.when( getroutegroups(obj) ).done(function() {
			$("#admin_edit_route_info_group_input_div").hide();
			$("#admin_edit_route_info_new_routegroup").val("");
			$("#admin_edit_route_info_group_select_div").fadeIn(250);
		});
	});


	$("#admin_edit_route_info_routetype").change(function() {
		var route = $("#admin_edit_route_info_name");
		var routetype = $("#admin_edit_route_info_routetype").val();
		$("#admin_edit_route_info_name").val("");
		$("#admin_edit_route_info_name").trigger("change");
		getroutes(route, routetype);
	});

	$("#admin_edit_route_info_name").change(function() {

		$("#admin_edit_route_info_mileage_value").val("");

		var routeid = $(this).val();

		if (routeid.length < 1) {
			$(".admin_edit_route_info_mileage").hide();
			return false;
		};

		var routetype = $("#admin_edit_route_info_routetype").val();

		//if (routetype == "special") {
			//$(".admin_edit_route_info_mileage").hide();
			//return false;
		//};

		var mileagetype = $("#admin_edit_route_info_mileage_type").val();

		var arr = {};
		arr["routeid"] = routeid;
		arr["mileagetype"] = mileagetype;

		return $.post("api/admin.php", {action: "getmileagevalue", obj: arr},
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

			$("#admin_edit_route_info_mileage_value").val(d["mileagevalue"]);

			$(".admin_edit_route_info_mileage").fadeIn(250);

		});

	});

	$("#admin_edit_route_info_group").change(function() {
		var groupname = $(this).val();
		getroutegroupmembers(groupname);
	});

	$("#admin_edit_route_group_btndeletegroup").confirmation({
		"popout": true,
		"singleton": true,
		"btnOkClass": "btn-default",
		"btnCancelClass": "btn-danger",
		"title": "Are You Sure?",
		"container": "body",
		"onConfirm": function() {
			var groupname = $("#admin_edit_route_info_group").val();
			if (groupname.length < 1) {
				return false;
			};
			deleteroutegroup(groupname);
		}
	});

	$("#admin_edit_vehicle_info_mileage_value_btnapply").click(function() {

		var routeid = $("#admin_edit_route_info_name").val();
		var mileagetype = $("#admin_edit_route_info_mileage_type").val();
		var mileagevalue = $("#admin_edit_route_info_mileage_value").val();

		savemileagevalue(routeid, mileagetype, mileagevalue);

	});

	$("#admin_edit_route_info_mileage_type").change(function() {

		$("#admin_edit_route_info_name").trigger("change");

	});

	$("#admin_edit_route_btn_list_order").click(function() {
		$("#admin_edit_route_info_div").hide();
		$("#admin_edit_route_route_group_div").hide();
		$.when( getroutelistorder() ).done(function() {
			$("#admin_edit_route_list_order_div").fadeIn(250);
		});
	});

	$("#admin_edit_route_btn_route_groups").click(function() {
		$("#admin_edit_route_info_div").hide();
		$("#admin_edit_route_list_order_div").hide();
		var obj = $("#admin_edit_route_info_group");
		$.when( getroutegroups(obj) ).done(function() {
			$("#admin_edit_route_route_group_div").fadeIn(250);
		});
	});

	$("#admin_edit_route_btn_list_order_apply").click(function() {

		var arr = {};

		$("#admin_edit_route_listorder tbody tr").each(function() {
			var id = $(this).data("dbid");
			var index = $(this).index();
			arr["lo_" + id] = index;
		});

		$.post("api/admin.php", {action: "saveroutelistorder", obj: arr},
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

			var route = $("#admin_edit_route_info_name");
			var routetype = $("#admin_edit_route_info_routetype").val();

			$.when( getroutes(route, routetype)).done(function() {
				$("#admin_edit_route_list_order_div").hide();
				$("#admin_edit_route_info_div").fadeIn(250);
			});

		});
	});

	$("#admin_edit_route_info_route_group_members_btnadd").click(function() {

		$("#admin_edit_route_info_route_group_div").hide();

		$("#admin_edit_route_info_route_group_members_list").hide();

		var groupname = $("#admin_edit_route_info_group").val();
		var obj = $("#admin_edit_route_info_route_group_members_select");

		$.when( getroutesforgroup(groupname, obj) ).done(function() {
			$("#admin_edit_route_info_route_group_members_add").fadeIn(250);
		});



	});

	$("#admin_edit_route_group_new_route_btnapply").click(function() {

		var groupname = $("#admin_edit_route_info_group").val();

		var routeid = $("#admin_edit_route_info_route_group_members_select").val() + "";

		if (groupname.length < 1) {
			return false;
		};

		if (routeid.length < 1) {
			showerrormessage("Please select a Route to add.");
			return false;
		};

		$.when( addroutegroupmember(groupname, routeid) ).done(function() {
			$("#admin_edit_route_group_new_route_btncancel").trigger("click");
		});

	});

	$("#admin_edit_route_group_new_route_btncancel").click(function() {
		var groupname = $("#admin_edit_route_info_group").val();
		$.when( getroutegroupmembers(groupname) ).done(function() {
			$("#admin_edit_route_info_route_group_members_add").hide();
			$("#admin_edit_route_info_route_group_members_list").fadeIn(250);
			$("#admin_edit_route_info_route_group_div").fadeIn(250);
			$("#admin_edit_route_info_route_group_members_div").fadeIn(250);
		});
	});

	$("#admin_edit_route_btn_route_groups_done").click(function() {
		$("#admin_edit_route_list_order_div").hide();
		$("#admin_edit_route_route_group_div").hide();
		var obj = $("#admin_edit_route_info_group");
		obj.empty();
		$("#admin_edit_route_info_route_group_members_div").hide();
		$("#admin_edit_route_info_tblgroupmembers tbody").empty();
		$("#admin_edit_route_info_div").fadeIn(250);
	});

	$("#admin_edit_vehicle_info_new_vehiclegroup_btnapply").click(function() {
		var groupname = $("#admin_edit_vehicle_info_new_vehiclegroup").val();

		if( savenewvehiclegroup(groupname) ) {
			$("#admin_edit_vehicle_info_new_vehiclegroup_btncancel").trigger("click");
		};
	});

	$("#admin_edit_vehicle_info_new_vehiclegroup_btncancel").click(function() {
		var obj = $("#admin_edit_vehicle_info_group");
		$.when( getvehiclegroups(obj) ).done(function() {
			$("#admin_edit_vehicle_info_group_input_div").hide();
			$("#admin_edit_vehicle_info_new_vehiclegroup").val("");
			$("#admin_edit_vehicle_info_group_select_div").fadeIn(250);
		});
	});

	$("#admin_edit_vehicle_group_btnnewgroup").click(function() {
		$("#admin_edit_vehicle_info_group_select_div").hide();

		$("#admin_edit_vehicle_info_vehicle_group_members_div").hide();
		$("#admin_edit_vehicle_info_tblgroupmembers tbody").empty();
		var obj = $("#admin_edit_vehicle_info_group ");

		$("#admin_edit_vehicle_info_group_input_div").fadeIn(250);
		$("#admin_edit_vehicle_info_new_vehiclegroup").focus();

	});

	$("#admin_edit_vehicle_btn_vehicle_groups").click(function() {
		$("#admin_edit_vehicle_info_div").hide();
		var obj = $("#admin_edit_vehicle_info_group");
		$.when( getvehiclegroups(obj) ).done(function() {
			$("#admin_edit_vehicle_vehicle_group_div").fadeIn(250);
		});
	});

	$("#admin_edit_vehicle_btn_vehicle_groups_done").click(function() {
		$("#admin_edit_vehicle_list_order_div").hide();
		$("#admin_edit_vehicle_vehicle_group_div").hide();
		var obj = $("#admin_edit_vehicle_info_group");
		obj.empty();
		$("#admin_edit_vehicle_info_vehicle_group_members_div").hide();
		$("#admin_edit_vehicle_info_tblgroupmembers tbody").empty();
		$("#admin_edit_vehicle_info_div").fadeIn(250);
	});

	$("#admin_edit_vehicle_info_vehicle_group_members_btnadd").click(function() {

		$("#admin_edit_vehicle_info_vehicle_group_div").hide();

		$("#admin_edit_vehicle_info_vehicle_group_members_list").hide();

		var groupname = $("#admin_edit_vehicle_info_group").val();
		var obj = $("#admin_edit_vehicle_info_vehicle_group_members_select");

		$.when( getvehiclesforgroup(groupname, obj) ).done(function() {
			$("#admin_edit_vehicle_info_vehicle_group_members_add").fadeIn(250);
		});

	});

	$("#admin_edit_vehicle_group_new_vehicle_btnapply").click(function() {

		var groupname = $("#admin_edit_vehicle_info_group").val();

		var vehicleid = $("#admin_edit_vehicle_info_vehicle_group_members_select").val() + "";

		if (groupname.length < 1) {
			return false;
		};

		if (vehicleid.length < 1) {
			showerrormessage("Please select a Vehicle to add.");
			return false;
		};

		$.when( addvehiclegroupmember(groupname, vehicleid) ).done(function() {
			$("#admin_edit_vehicle_group_new_vehicle_btncancel").trigger("click");
		});

	});

	$("#admin_edit_vehicle_group_new_vehicle_btncancel").click(function() {
		var groupname = $("#admin_edit_vehicle_info_group").val();
		$.when( getvehiclegroupmembers(groupname) ).done(function() {
			$("#admin_edit_vehicle_info_vehicle_group_members_add").hide();
			$("#admin_edit_vehicle_info_vehicle_group_members_list").fadeIn(250);
			$("#admin_edit_vehicle_info_vehicle_group_div").fadeIn(250);
			$("#admin_edit_vehicle_info_vehicle_group_members_div").fadeIn(250);
		});
	});

	$("#admin_edit_vehicle_group_btndeletegroup").confirmation({
		"popout": true,
		"singleton": true,
		"btnOkClass": "btn-default",
		"btnCancelClass": "btn-danger",
		"title": "Are You Sure?",
		"container": "body",
		"onConfirm": function() {
			var groupname = $("#admin_edit_vehicle_info_group").val();
			if (groupname.length < 1) {
				return false;
			};
			deletevehiclegroup(groupname);
		}
	});

	$("#admin_edit_vehicle_info_group").change(function() {
		var groupname = $(this).val();
		getvehiclegroupmembers(groupname);
	});


	$("#admin_btnusermanagement").trigger("click");

});

function edituser(userid) {

	$("#admin_edit_user_info_btnpassword_div").hide();

	$("#admin_edit_user_info_div").hide();
	$(".admin_edit_user_info").val("");

	var arr = {};

	if (userid.length > 0) {
		$("#admin_edit_user_info_btnpassword_div").fadeIn(250);
	}

	arr["userid"] = userid;

	pleasewait.show();

	$.post("api/admin.php", {action: "getuserinfo", obj: arr},
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

		var firstname = d["firstname"];
		var lastname = d["lastname"];
		var email = d["email"];
		var address = d["address"];
		var city = d["city"];
		var state = d["state"];
		var zip = d["zip"];
		var phone = d["phone"];
		var driverlicensenumber = d["driverlicensenumber"];
		var driverlicenseexpiration = d["driverlicenseexpiration"];

		var position = d["position"];
		var dotexpirationdate = d["dotexpirationdate"];
		var hireddate = d["hireddate"];
		var senioritydate = d["senioritydate"];
		var birthdate = d["birthdate"];
		var badgenumber = d["badgenumber"];
		var badgeexpirationdate = d["badgeexpirationdate"];
		var badgecolor = d["badgecolor"];

		var roles = d["roles"];

		$("#admin_edit_user_info_role_driver").prop("checked", false);
		$("#admin_edit_user_info_role_manager").prop("checked", false);
		$("#admin_edit_user_info_role_fluids").prop("checked", false);
		$("#admin_edit_user_info_role_admin").prop("checked", false);
		$("#admin_edit_user_info_role_comments").prop("checked", false);
		$("#admin_edit_user_info_role_accidents").prop("checked", false);


		if (roles.indexOf("driver") >= 0) {
			$("#admin_edit_user_info_role_driver").prop("checked", true);
		}

		if (roles.indexOf("manager") >= 0) {
			$("#admin_edit_user_info_role_manager").prop("checked", true);
		}

		if (roles.indexOf("admin") >= 0) {
			$("#admin_edit_user_info_role_admin").prop("checked", true);
		}

		if (roles.indexOf("comments") >= 0) {
			$("#admin_edit_user_info_role_comments").prop("checked", true);
		}

		if (roles.indexOf("fluids") >= 0) {
			$("#admin_edit_user_info_role_fluids").prop("checked", true);
		}

		if (roles.indexOf("accidents") >= 0) {
			$("#admin_edit_user_info_role_accidents").prop("checked", true);
		};

		$("#admin_edit_user_info_firstname").val(firstname);
		$("#admin_edit_user_info_lastname").val(lastname);
		$("#admin_edit_user_info_email").val(email);
		$("#admin_edit_user_info_address").val(address);
		$("#admin_edit_user_info_city").val(city);
		$("#admin_edit_user_info_state").val(state);
		$("#admin_edit_user_info_zip").val(zip);
		$("#admin_edit_user_info_phone").val(phone);
		$("#admin_edit_user_info_driverlicensenumber").val(driverlicensenumber);
		$("#admin_edit_user_info_driverlicenseexpiration").val(driverlicenseexpiration);

		$("#admin_edit_user_info_position").val(position);
		$("#admin_edit_user_info_dotexpiration").val(dotexpirationdate);
		$("#admin_edit_user_info_hiredate").val(hireddate);
		$("#admin_edit_user_info_senioritydate").val(senioritydate);
		$("#admin_edit_user_info_birthdate").val(birthdate);
		$("#admin_edit_user_info_badgenumber").val(badgenumber);
		$("#admin_edit_user_info_badgeexpiration").val(badgeexpirationdate);
		$("#admin_edit_user_info_badgecolor").val(badgecolor);




		setTimeout(function(){
			pleasewait.hide();
		}, 500);

		$("#admin_edit_user_info_div").fadeIn(250);

	});

};

function getuserlist(obj, userid) {

	pleasewait.show();

	var arr = {};

	arr["userid"] = userid;

	//TODO: Make a checkbox to allow for viewing active or inactive users later... for now just set status to "active"
	arr["status"] = "active";

	return $.post("api/admin.php", {action: "getuserlist", obj: arr},
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

		setTimeout(function(){
			pleasewait.hide();
		}, 500);
	});

};

function setpassword(userid, pwd) {

	var arr = {};

	arr["userid"] = userid;
	arr["password"] = pwd;

	$.post("api/admin.php", {action: "setpassword", obj: arr},
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



		showsuccessmessage("Password Updated!");
	});


}

function saveuserinfo(userid) {

	$("*").removeClass("error");

	var arr = {};

	arr["userid"] = userid;

	var firstname = $("#admin_edit_user_info_firstname").val();
	if (firstname.length < 1) {
		showerrormessage("Firstname is required");
		$("#admin_edit_user_info_firstname").addClass("error");
		return false;
	};

	var lastname = $("#admin_edit_user_info_lastname").val();
	if (lastname.length < 1) {
		showerrormessage("Lastname is required");
		$("#admin_edit_user_info_lastname").addClass("error");
		return false;
	};

	var email = $("#admin_edit_user_info_email").val();

	var isdriver = $("#admin_edit_user_info_role_driver").prop("checked");
	var ismanager = $("#admin_edit_user_info_role_manager").prop("checked");
	var isadmin = $("#admin_edit_user_info_role_admin").prop("checked");
	var iscomments = $("#admin_edit_user_info_role_comments").prop("checked");
	var isfluids = $("#admin_edit_user_info_role_fluids").prop("checked");
	var isaccidents = $("#admin_edit_user_info_role_accidents").prop("checked");

	var address = $("#admin_edit_user_info_address").val();
	var city = $("#admin_edit_user_info_city").val();
	var state = $("#admin_edit_user_info_state").val();
	var zip = $("#admin_edit_user_info_zip").val();
	var phone = $("#admin_edit_user_info_phone").val();
	var driverlicensenumber = $("#admin_edit_user_info_driverlicensenumber").val();
	var driverlicenseexpiration = $("#admin_edit_user_info_driverlicenseexpiration").val();

	var position = $("#admin_edit_user_info_position").val();
	var dotexpiration = $("#admin_edit_user_info_dotexpiration").val();
	var hireddate = $("#admin_edit_user_info_hiredate").val();
	var senioritydate = $("#admin_edit_user_info_senioritydate").val();
	var birthdate = $("#admin_edit_user_info_birthdate").val();
	var badgenumber = $("#admin_edit_user_info_badgenumber").val();
	var badgeexpirationdate = $("#admin_edit_user_info_badgeexpiration").val();
	var badgecolor = $("#admin_edit_user_info_badgecolor").val();

	arr["firstname"] = firstname;
	arr["lastname"] = lastname;
	arr["email"] = email;
	arr["isdriver"] = isdriver;
	arr["ismanager"] = ismanager;
	arr["isadmin"] = isadmin;
	arr["iscomments"] = iscomments;
	arr["isfluids"] = isfluids;
	arr["isaccidents"] = isaccidents;

	arr["address"] = address;
	arr["city"] = city;
	arr["state"] = state;
	arr["zip"] = zip;
	arr["phone"] = phone;
	arr["driverlicensenumber"] = driverlicensenumber;
	arr["driverlicenseexpiration"] = driverlicenseexpiration;

	arr["position"] = position;
	arr["dotexpiration"] = dotexpiration;
	arr["hireddate"] = hireddate;
	arr["senioritydate"] = senioritydate;
	arr["birthdate"] = birthdate;
	arr["badgenumber"] = badgenumber;
	arr["badgeexpirationdate"] = badgeexpirationdate;
	arr["badgecolor"] = badgecolor;

	if (userid.length > 0) {
		$("#admin_edit_user_info_btnpassword_div").fadeIn(250);
	};

	pleasewait.show();

	$.post("api/admin.php", {action: "saveuserinfo", obj: arr},
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
		}, 500);

		location.reload();

	});

};

function deleteuser() {

	$("*").removeClass("error");

	var arr = {};

	var userid = $("#admin_select_user").val();

	arr["userid"] = userid;

	if (userid.length < 1) {
		$("#admin_select_user").addClass("error");
		showerrormessage("Please select a User to delete.");
		return false;
	}

	pleasewait.show();

	$.post("api/admin.php", {action: "deleteuser", obj: arr},
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
			location.reload();
		}, 500);
	});





}

function getvehicletypes(obj) {

	pleasewait.show();

	var arr = {};

	//TODO: Make a checkbox to allow for viewing active or inactive vehicles later... for now just set status to "active"
	arr["status"] = "active";

	$("#admin_edit_vehicle_info_name_div").hide();
	$("#admin_edit_vehicle_info_name").empty();
	$("#admin_edit_vehicle_info_vin_div").hide();
	$("#admin_edit_vehicle_info_vin").val("");
	$("#admin_edit_vehicle_info_licenseplate_div").hide();
	$("#admin_edit_vehicle_info_licenseplate").val("");

	return $.post("api/admin.php", {action: "getvehicletypes", obj: arr},
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

		setTimeout(function(){
			pleasewait.hide();
		}, 500);
	});

};

function getvehicles(vehicletype, obj) {

	$("*").removeClass("error");

	obj.empty();
	obj.append($('<option>', {value:"", text:"- Select One -"}));

	pleasewait.show();

	var arr = {};

	arr["vehicletype"] = vehicletype;

	//TODO: Make a checkbox to allow for viewing active or inactive vehicles later... for now just set status to "active"
	arr["status"] = "active";

	$("#admin_edit_vehicle_info_name_div").hide();
	$("#admin_edit_vehicle_info_name").empty();
	$("#admin_edit_vehicle_info_vin_div").hide();
	$("#admin_edit_vehicle_info_vin").val("");
	$("#admin_edit_vehicle_info_licenseplate_div").hide();
	$("#admin_edit_vehicle_info_licenseplate").val("");

	return $.post("api/admin.php", {action: "getvehicles", obj: arr},
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

		setTimeout(function(){
			pleasewait.hide();
			$("#admin_edit_vehicle_info_name_div").fadeIn(250);
		}, 500);

	});

};

function savevehicletype() {

	var arr = {};

	$("*").removeClass("error");

	var vehicletype = $("#admin_edit_vehicle_info_new_type").val();

	if (vehicletype.length < 1) {
		showerrormessage("Please provide a Value for the Vehicle Type");
		return false;
	};

	arr["vehicletype"] = vehicletype;

	return $.post("api/admin.php", {action: "savevehicletype", obj: arr},
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
		}, 500);

	});

};

function deletevehicletype() {

	var arr = {};

	$("*").removeClass("error");

	var vehicletypeid = $("#admin_edit_vehicle_info_type").val();

	if (vehicletypeid.length < 1) {
		showerrormessage("Please select a Vehicle Type to Delete");
		return false;
	};

	arr["vehicletypeid"] = vehicletypeid;

	return $.post("api/admin.php", {action: "deletevehicletype", obj: arr},
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

		var obj = $("#admin_edit_vehicle_info_type");

		getvehicletypes(obj);

	});


};

function savenewvehicle(vehicletype, vehiclename) {
	var arr = {};

	$("*").removeClass("error");

	if (vehicletype.length < 1) {
		$("#admin_edit_vehicle_info_new_name").addClass("error");
		showerrormessage("Please select a Vehicle Type");
		return false;
	};

	if (vehiclename.length < 1) {
		$("#admin_edit_vehicle_info_new_name").addClass("error");
		showerrormessage("Please provide a Value for the Vehicle Name");
		return false;
	};

	arr["vehicletype"] = vehicletype;
	arr["vehiclename"] = vehiclename;

	return $.post("api/admin.php", {action: "savevehiclename", obj: arr},
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
		}, 500);

	});
};

function deletevehiclename(vehiclenameid) {

	var arr = {};

	$("*").removeClass("error");

	var vehiclenameid = $("#admin_edit_vehicle_info_name").val();

	if (vehiclenameid.length < 1) {
		showerrormessage("Please select a Vehicle Name to Delete");
		return false;
	};

	arr["vehiclenameid"] = vehiclenameid;

	return $.post("api/admin.php", {action: "deletevehiclename", obj: arr},
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

		var vehicletype = $("#admin_edit_vehicle_info_type").val();
		var obj = $("#admin_edit_vehicle_info_name");

		getvehicles(vehicletype, obj);

	});


};

function getroutes(obj, routetype) {

	pleasewait.show();

	var arr = {};

	//TODO: Make a checkbox to allow for viewing active or inactive vehicles later... for now just set status to "active"
	arr["status"] = "active";

	arr["routetype"] = routetype;

	$("#admin_edit_route_info_route_div").hide();
	$("#admin_edit_route_info_route").empty();

	return $.post("api/admin.php", {action: "getroutes", obj: arr},
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

		obj.prepend($('<option>', {value:"", text:"- Select One -"}));
		obj.val("");

		setTimeout(function(){
			pleasewait.hide();
		}, 500);
	});

};

function saveroute(routename, routetype) {

	var arr = {};

	$("*").removeClass("error");

	if (routename.length < 1) {
		showerrormessage("Please provide a Value for the New Route Name");
		return false;
	};

	if (routetype.length < 1) {
		showerrormessage("Please provide a Value for the Route Type");
		return false;
	};

	arr["routename"] = routename;

	arr["routetype"] = routetype;

	return $.post("api/admin.php", {action: "saveroutename", obj: arr},
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
		}, 500);

	});

};

function deleteroute(routeid) {

	var arr = {};

	$("*").removeClass("error");

	if (routeid.length < 1) {
		showerrormessage("Please select a Route to Delete");
		return false;
	};

	arr["routeid"] = routeid;

	return $.post("api/admin.php", {action: "deleteroute", obj: arr},
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

		var route = $("#admin_edit_route_info_name");
		var routetype = $("#admin_edit_route_info_routetype").val();

		getroutes(route, routetype);

	});


};

function savemileagevalue(routeid, mileagetype, mileagevalue) {

	var arr = {};

	if (routeid.length < 1) {
		return false;
	};

	if (mileagetype.length < 1) {
		return false;
	};

	arr["routeid"] = routeid;
	arr["mileagetype"] = mileagetype;
	arr["mileagevalue"] = mileagevalue;

	return $.post("api/admin.php", {action: "savemileage", obj: arr},
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
		}, 500);

	});


};

function getroutelistorder() {
	pleasewait.show();

	var arr = {};

	arr["status"] = "active";

	return $.post("api/admin.php", {action: "getroutelistorder", obj: arr},
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

		var tbodyhtml = "";

		$("#admin_edit_route_listorder tbody").empty();
		$.each(d, function(key, val) {
			tbodyhtml = tbodyhtml +
			'			<tr data-dbid="' + val.routeid + '">' +
			'				<td>' + val.routename + '</td>' +
			'			</tr>';
		});

		$("#admin_edit_route_listorder tbody").html(tbodyhtml);

		setTimeout(function(){
			pleasewait.hide();
		}, 500);
	});


};

function getvehicleinfo(vehicleid) {

	var arr = {};

	arr["vehicleid"] = vehicleid;

	return $.post("api/admin.php", {action: "getvehicleinfo", obj: arr},
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

		$("#admin_edit_vehicle_info_vin").val(d.vin);
		$("#admin_edit_vehicle_info_licenseplate").val(d.licenseplate);

	});

};

function savevehicleinfo(vehicleid, vin, licenseplate) {

	var arr = {};

	arr["vehicleid"] = vehicleid;
	arr["vin"] = vin;
	arr["licenseplate"] = licenseplate;

	return $.post("api/admin.php", {action: "savevehicleinfo", obj: arr},
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

	});

};

function savenewroutegroup(groupname) {

	var arr = {};

	$("*").removeClass("error");

	if (groupname.length < 1) {
		showerrormessage("Please provide a Value for the New Group Name");
		return false;
	};

	arr["groupname"] = groupname;

	return $.post("api/admin.php", {action: "savenewroutegroup", obj: arr},
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

		/*
		setTimeout(function(){
			pleasewait.hide();
		}, 500);
		*/

	});

};

function getroutegroups(obj) {

	var arr = {};

	arr["blah"] = "blah";

	return $.post("api/admin.php", {action: "getroutegroups", obj: arr},
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
		obj.append($('<option>', {value:"", text:"- Select One -"}));
		$.each(d, function(key, val) {
			obj.append($('<option>', {value:val.groupname, text:val.groupname}));
		});

	});

};

function getroutegroupmembers(groupname) {

	$("#admin_edit_route_info_route_group_members_div").hide();
	$("#admin_edit_route_info_tblgroupmembers tbody").empty();

	if (groupname.length < 1) {
		return false;
	};

	var arr = {};
	arr["groupname"] = groupname;

	return $.post("api/admin.php", {action: "getroutegroupmembers", obj: arr},
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

		var tbodyhtml = "";

		$.each(d, function(key, val) {
			tbodyhtml = tbodyhtml +
			'<tr><td><a data-dbid="' + key + '" class="routegroupmembers">' + val + '</a></td></tr>';
		});

		$("#admin_edit_route_info_tblgroupmembers tbody").append(tbodyhtml);

		$(".routegroupmembers").off();

		$(".routegroupmembers").confirmation({
		"popout": true,
		"singleton": true,
		"btnOkClass": "btn-default",
		"btnCancelClass": "btn-danger",
		"title": "Are You Sure?",
		"container": "body",
		"onConfirm": function() {
			var routeid = $(this).data("dbid");
			var groupname = $("#admin_edit_route_info_group").val();
			removeroutegroupmember(groupname, routeid);
		}
	});

		$("#admin_edit_route_info_route_group_members_div").fadeIn(250);

	});

};

function deleteroutegroup(groupname) {

	$("#admin_edit_route_info_route_group_members_div").hide();
	$("#admin_edit_route_info_tblgroupmembers tbody").empty();

	if (groupname.length < 1) {
		//return false;
	};

	var arr = {};
	arr["groupname"] = groupname;

	return $.post("api/admin.php", {action: "deleteroutegroup", obj: arr},
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

		var tbodyhtml = "";

		$("#admin_edit_route_info_new_routegroup_btncancel").trigger("click");

	});

};

function addroutegroupmember(groupname, routeid) {

	var arr = {};

	$("*").removeClass("error");

	if (routeid.length < 1) {
		showerrormessage("Please provide a Value for the Route Name");
		return false;
	};

	if (groupname.length < 1) {
		showerrormessage("Please provide a Value for the Group Name");
		return false;
	};

	arr["routeid"] = routeid;
	arr["groupname"] = groupname;

	return $.post("api/admin.php", {action: "addroutegroupmember", obj: arr},
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

function removeroutegroupmember(groupname, routeid) {

	var arr = {};

	arr["routeid"] = routeid;
	arr["groupname"] = groupname;

	return $.post("api/admin.php", {action: "removeroutegroupmember", obj: arr},
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

		var groupname = $("#admin_edit_route_info_group").val();
		var obj = $("#admin_edit_route_info_route_group_members_select");

		$.when(getroutegroupmembers(groupname) ).done(function() {
			$("#admin_edit_route_info_route_group_div").show();
			$("#admin_edit_route_info_route_group_members_list").show();
		});


	});

};

function getroutesforgroup(groupname, obj) {

	var arr = {};

	arr["status"] = "active";

	arr["groupname"] = groupname;

	return $.post("api/admin.php", {action: "getroutesforgroup", obj: arr},
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

		//sortdropdown(obj);

		obj.prepend($('<option>', {value:"", text:"- Select One -"}));
		obj.val("");

	});


};

function savenewvehiclegroup(groupname) {

	var arr = {};

	$("*").removeClass("error");

	if (groupname.length < 1) {
		showerrormessage("Please provide a Value for the New Group Name");
		return false;
	};

	arr["groupname"] = groupname;

	return $.post("api/admin.php", {action: "savenewvehiclegroup", obj: arr},
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

		/*
		setTimeout(function(){
			pleasewait.hide();
		}, 500);
		*/

	});

};

function getvehiclegroups(obj) {

	var arr = {};

	arr["blah"] = "blah";

	return $.post("api/admin.php", {action: "getvehiclegroups", obj: arr},
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
		obj.append($('<option>', {value:"", text:"- Select One -"}));
		$.each(d, function(key, val) {
			obj.append($('<option>', {value:val.groupname, text:val.groupname}));
		});

	});

};

function getvehiclegroupmembers(groupname) {

	$("#admin_edit_vehicle_info_vehicle_group_members_div").hide();
	$("#admin_edit_vehicle_info_tblgroupmembers tbody").empty();

	if (groupname.length < 1) {
		return false;
	};

	var arr = {};
	arr["groupname"] = groupname;

	return $.post("api/admin.php", {action: "getvehiclegroupmembers", obj: arr},
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

		var tbodyhtml = "";

		$.each(d, function(key, val) {
			tbodyhtml = tbodyhtml +
			'<tr><td><a data-dbid="' + key + '" class="vehiclegroupmembers">' + val + '</a></td></tr>';
		});

		$("#admin_edit_vehicle_info_tblgroupmembers tbody").append(tbodyhtml);

		$(".vehiclegroupmembers").off();

		$(".vehiclegroupmembers").confirmation({
		"popout": true,
		"singleton": true,
		"btnOkClass": "btn-default",
		"btnCancelClass": "btn-danger",
		"title": "Are You Sure?",
		"container": "body",
		"onConfirm": function() {
			var vehicleid = $(this).data("dbid");
			var groupname = $("#admin_edit_vehicle_info_group").val();
			removevehiclegroupmember(groupname, vehicleid);
		}
	});

		$("#admin_edit_vehicle_info_vehicle_group_members_div").fadeIn(250);

	});

};

function deletevehiclegroup(groupname) {

	$("#admin_edit_vehicle_info_vehicle_group_members_div").hide();
	$("#admin_edit_vehicle_info_tblgroupmembers tbody").empty();

	if (groupname.length < 1) {
		//return false;
	};

	var arr = {};
	arr["groupname"] = groupname;

	return $.post("api/admin.php", {action: "deletevehiclegroup", obj: arr},
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

		var tbodyhtml = "";

		$("#admin_edit_vehicle_info_new_vehiclegroup_btncancel").trigger("click");

	});

};

function addvehiclegroupmember(groupname, vehicleid) {

	var arr = {};

	$("*").removeClass("error");

	if (vehicleid.length < 1) {
		showerrormessage("Please provide a Value for the Vehicle Name");
		return false;
	};

	if (groupname.length < 1) {
		showerrormessage("Please provide a Value for the Group Name");
		return false;
	};

	arr["vehicleid"] = vehicleid;
	arr["groupname"] = groupname;

	return $.post("api/admin.php", {action: "addvehiclegroupmember", obj: arr},
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

function removevehiclegroupmember(groupname, vehicleid) {

	var arr = {};

	arr["vehicleid"] = vehicleid;
	arr["groupname"] = groupname;

	return $.post("api/admin.php", {action: "removevehiclegroupmember", obj: arr},
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

		var groupname = $("#admin_edit_vehicle_info_group").val();
		var obj = $("#admin_edit_vehicle_info_vehicle_group_members_select");

		$.when(getvehiclegroupmembers(groupname) ).done(function() {
			$("#admin_edit_vehicle_info_vehicle_group_div").show();
			$("#admin_edit_vehicle_info_vehicle_group_members_list").show();
		});


	});

};

function getvehiclesforgroup(groupname, obj) {

	var arr = {};

	arr["status"] = "active";

	arr["groupname"] = groupname;

	return $.post("api/admin.php", {action: "getvehiclesforgroup", obj: arr},
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

		//sortdropdown(obj);

		obj.prepend($('<option>', {value:"", text:"- Select One -"}));
		obj.val("");

	});


};

