
var location_name = "DIA";

var location_name_long = "Denver International Airport (DIA)";

$(document).ready(function() {

	$('.modal').on('show.bs.modal', function () {
        if ($(document).height() > $(window).height()) {
            // no-scroll
            $('body').addClass("modal-open-noscroll");
        }
        else {
            $('body').removeClass("modal-open-noscroll");
        }
    });
	// Might Need This...
    //$('.modal').on('hide.bs.modal', function () {
       // $('body').removeClass("modal-open-noscroll");
    //});

	tablesorter($(".tablesorter"));

	$(".zipmask").mask("00000");
	$(".phonemask").mask("(000) 000-0000");
	$(".yearmask").mask("0000");
	$(".agemask").mask("000");

	$.get("statelist.html", function(data) {
		$(".statelist").html("");
		$(".statelist").html(data);
	});

	$("#abm_rm_login_btnsignin").click(function() {
		login();
	});

	$("#abm_rm_login_btnlogout").click(function() {
		logout();
	});

	$(".logininput").keypress(function(event) {
		if (event.keyCode == 13) {
			$("#abm_rm_login_btnsignin").trigger("click");
		}
	});

	$(".tableexport").click(function() {
		var elid = $(this).data("elid");
		$("#" + elid).tableToCSV();
	});

	//$(".btnhome").fadeIn(150);
	$(".btnhome").show();

	$(".datepicker").datetimepicker({
		format:'m/d/Y',
		timepicker:false,
		scrollMonth : false,
		scrollInput : false
	});

	//$(".datepicker").prop("readonly", true);

	$(".localstorage").change(function() {
		var key = $(this).data("localkey");
		var val = $(this).val();
		localStorage.setItem(key, val);
	});

	$(".localstorage").each(function() {
		var key = $(this).data("localkey");
		if (localStorage[key]) {
			var val = localStorage.getItem(key);
			$(this).val(val);
		};
	});

});

function login() {

	$("*").removeClass("rt_error");

	var arr = {};

	var email = $("#abm_rm_login_email").val();
	if (email.length < 1) {
		showerrormessage("An Email Address is required");
		$("#abm_rm_login_email").addClass("rt_error");
		return false;
	}
	var pw = $("#abm_rm_login_password").val();
	if (pw.length < 1) {
		showerrormessage("A Password is required");
		$("#abm_rm_login_password").addClass("rt_error");
		return false;
	}

	arr["email"] = email;
	arr["password"] = pw;

	return $.post("api/login.php", {action: "login", obj: arr},
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

		location.reload();

	});


}

function logout() {
	var arr = {};
	arr["blank"] = "";
	$.post("api/login.php", {action: "logout", obj: arr},
	function(data) {
		location.reload();
	});
}

function showerrormessage(message) {
    $("#error_message").html(message);
    $("#error_div").modal('show');
}

function showsuccessmessage(message) {
    $("#success_message").html(message);
    $("#success_div").modal('show');
}

function sortdropdown(obj) {

	var select = obj;
	select.html(select.find('option').sort(function(x, y) {
	// to change to descending order switch "<" for ">"
	return $(x).text() > $(y).text() ? 1 : -1;
	}));
}

function tablesorter(el) {

	/*
	return el.tablesorter({
	  //widgets: ['zebra', 'stickyHeaders', 'reorder', 'resizable', 'scroller'],
	  widgets: ['scroller'],
	  widgetOptions: {
		//resizable: true,
		//resizable_addLastColumn : true,
		//stickyHeaders_attachTo: '#admin_batches_edit_batch_div_potency',
		//stickyHeaders_offset: 0,
		reorder_axis        : 'x', // 'x' or 'xy'
		reorder_delay       : 00,
		reorder_helperClass : 'tablesorter-reorder-helper',
		reorder_helperBar   : 'tablesorter-reorder-helper-bar',
		reorder_noReorder   : 'reorder-false',
		reorder_blocked     : 'reorder-block-left reorder-block-end',
		reorder_complete    : null,
		 // set number of columns to fix
	  scroller_fixedColumns : 1,
	  // add a fixed column overlay for styling
	  scroller_addFixedOverlay : false,
		scroller_height : 300,
		// scroll tbody to top after sorting
		scroller_upAfterSort: true,
		// pop table header into view while scrolling up the page
		scroller_jumpToHeader: true,
		// In tablesorter v2.19.0 the scroll bar width is auto-detected
		// add a value here to override the auto-detected setting
		scroller_barWidth : null
		// scroll_idPrefix was removed in v2.18.0
		// scroller_idPrefix : 's_'
	  }
	});

	//el.trigger('applyWidgets');

	*/



	var navbarheight = $(".navbar").css("height");

	el.tablesorter({
		widgets: ['stickyHeaders'],
		widgetOptions: {
		  stickyHeaders_offset: navbarheight
		}
	});

	return el.trigger('applyWidgets');


};

function font_size(obj) {

	//var originalSize = obj.css('font-size');

	var originalSize = "14px";

	// reset
	$(".resetfont").click(function() {
		obj.css('font-size', originalSize);
		return false;
	});

	// Increase Font Size
	$(".increasefont").click(function() {
		var currentSize = obj.css('font-size');
		var currentSize = parseFloat(currentSize)*1.05;
		obj.css('font-size', currentSize);
		return false;
	});

	// Decrease Font Size
	$(".decreasefont").click(function() {
		var currentFontSize = obj.css('font-size');
		var currentSize = obj.css('font-size');
		var currentSize = parseFloat(currentSize)*0.95;
		obj.css('font-size', currentSize);
		return false;
	});


	$(".noshrink").css('font-size', originalSize);
	//return;
};

function doUndo(){
  document.execCommand('undo', false, null);
}

function doRedo(){
  document.execCommand('redo', false, null);
}

function calculate_hours(starttime, endtime) {

	var dif = 0;

	if (starttime.length < 1 || endtime.length < 1) {
		return dif;
	};

	var startyear = "1970";
	var startmonth = "1";
	var startday = "2";
	var starthours = starttime.substring(0, 2);
	var startminutes = starttime.substring(3, 5);

	var startdatetime = new Date(startyear, startmonth, startday, starthours, startminutes);

	var endyear = "1970";
	var endmonth = "1";
	var endday = "2";
	var endhours = endtime.substring(0, 2);
	var endminutes = endtime.substring(3, 5);

	var enddatetime = new Date(endyear, endmonth, endday, endhours, endminutes);

	var dif = ( enddatetime - startdatetime ) / 1000 / 60 / 60;

	dif = parseFloat(dif);

	return dif;
};

function minTommss(minutes, ceil_or_floor){
	var sign = minutes < 0 ? "-" : "";

	var min = Math.floor(Math.abs(minutes));
	var sec = Math.floor((Math.abs(minutes) * 60) % 60);

	//alert(minutes);

	if (ceil_or_floor == "Ceil") {
		min = Math.floor(Math.abs(minutes));
		sec = Math.ceil((Math.abs(minutes) * 60) % 60);

		//alert(min + " - " + sec);
	};

	return sign + min + "h " + sec + "m";
}

jQuery.fn.tableToCSV = function() {

    var clean_text = function(text){
        text = text.replace(/"/g, '""');
		text = cleaninput(text);
		if ($.isNumeric( text )) {
			return text;
		}
		else
		{
			return '"'+text+'"';
		};
    };

	$(this).each(function(){
		var table = $(this);
		var caption = $(this).find('caption').text();
		var title = [];
		var rows = [];

		$(this).find('tr').each(function(){
			var data = [];
			$(this).find('th').each(function(){
				var text = clean_text($(this).text());
				title.push(text);
				});
			$(this).find('td').each(function(){
				var text = clean_text($(this).text());
				data.push(text);
				});
			data = data.join(",");
			rows.push(data);
			});
		title = title.join(",");
		rows = rows.join("\n");

		var csv = title + rows;
		var uri = 'data:text/csv;charset=utf-8,' + encodeURIComponent(csv);
		var download_link = document.createElement('a');
		download_link.href = uri;
		var ts = new Date().getTime();
		if(caption==""){
			dl_link =  ts+".csv";
		} else {
			//download_link.download = caption+"-"+ts+".csv";
			dl_link = caption+".csv";
		}

		download_link.download = dl_link;

		//download_link = download_link + "";
		if (navigator.msSaveBlob) { // IE 10+
			navigator.msSaveBlob(new Blob([csv], { type: 'text/csv;charset=utf-8;' }), dl_link);
		}
		else
		{
			document.body.appendChild(download_link);
			download_link.click();
			document.body.removeChild(download_link);
		};
	});

};

var pleasewait = (function ($) {

    // Creating modal dialog's DOM
    var $dialog = $(
        '<div class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true" style="padding-top:15%; overflow-y:visible;">' +
        '	<div class="modal-dialog modal-m">' +
        '		<div class="modal-content">' +
		'			<div class="modal-header"><h3 style="margin:0;"></h3></div>' +
		'				<div class="modal-body">' +
		'					<div class="progress progress-striped active" style="margin-bottom:0;"><div class="progress-bar" style="width: 100%"> ' +
		'					</div>' +
		'				</div>' +
        '			</div>' +
        '		</div>' +
		'	</div>' +
		'</div>');

    return {
        /**
         * Opens our dialog
         * @param message Custom message
         * @param options Custom options:
         *                   options.dialogSize - bootstrap postfix for dialog size, e.g. "sm", "m";
         *                   options.progressType - bootstrap postfix for progress bar type, e.g. "success", "warning".
         */
        show: function (message, options) {
            // Assigning defaults
            var settings = $.extend({
                dialogSize: 'm',
                progressType: ''
            }, options);
            if (typeof message === 'undefined') {
                message = 'Processing... Please Wait';
            }
            if (typeof options === 'undefined') {
                options = {};
            }
            // Configuring dialog
            $dialog.find('.modal-dialog').attr('class', 'modal-dialog').addClass('modal-' + settings.dialogSize);
            $dialog.find('.progress-bar').attr('class', 'progress-bar');
            if (settings.progressType) {
                $dialog.find('.progress-bar').addClass('progress-bar-' + settings.progressType);
            }
            $dialog.find('h3').text(message);
            // Opening dialog
            $dialog.modal();
        },
        /**
         * Closes dialog
         */
        hide: function () {
            $dialog.modal('hide');
        }
    }

})(jQuery);
