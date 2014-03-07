var cur_page=0;
var svc_list_max_page=0;
var qs="";
var base_qs="";
var search_timer;
var cur_svc_id=0;
var cur_srv_id=0;

 window.addEventListener('push', mypushHandler);
 function mypushHandler(ev) {
       btl_ready(ev);
 }

function nl2br (str, is_xhtml) {
    var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
    return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
}
function addAssignAllImg(id, src) {
	$('[id=' + id + ']').attr("src", src);
}

function btl_svc_list_search() {
	console.log("SEARCH FOR:" + $("#svcsearchbox").val());
	qs = base_qs + "&sSearch=" + $("#svcsearchbox").val();
	cur_page=0;
	btl_svc_list(qs);
}
function btl_svc_detail(d) {
	$.getJSON("../service_detail.php?json=2&" + qs, function(d1) {
		cd=d1.boxes_values.service_detail_service_info;
		console.log(d1);
		cur_svc_id=cd.service.service_id;
		cur_srv_id=cd.service.server_id;
		$("#svc_server").html(cd.service.server_name + " (ID:"  + cd.service.server_id + ") " + cd.server_enabled);
		$("#svc_name").html( cd.service.service_name);
		$("#svc_type").html(cd.service_type);
		$("#svc_plan").html(cd.check_plan);
		$("#svc_life").html(cd.dead_marker);
		$("#svc_running").html(cd.currently_running);
		$("#svc_escalate").html(cd.escalate);
		$("#svc_fire").html(cd.fires_events);
		$("#svc_handled").html(cd.handled);
		$("#svc_trigger").html(cd.triggers);
		$("#svc_check").html(cd.service_enabled);
		$("#svc_renotify").html(cd.renotify);
		$("#svc_last_notification").html(cd.service_ms);
		$("#svc_notify").html(cd.notify_enabled);
		$("#svc_delay").html("Delay: " + cd.service_delay + "ms Check-Time: " + cd.service_ms + "ms (AVG)");
		$("#svc_last_next").html(Date(cd.service.last_check*1000) + "<br>" + Date((cd.service.last_check+cd.service.check_interval)*1000));
		$("#svc_last_notification").html(Date(cd.service.last_notify_send*1000) );
		$("#svc_intervall").html(cd.service.check_interval);
		$("#svc_flap").html(cd.service.flap_count + " / " + cd.service.flap_seconds + "s");
		$("#svc_status").html(cd.service.service_retain_current + " / " + cd.service.service_retain + "");
		$("#svc_last_state_change").html(Date(cd.service.last_state_change*1000) );
		$("#svc_curr_label").html(" <span class='badge' style='background-color:" + cd.color + "'>"  + cd.state + "</span> ");
		$("#svc_output").html(nl2br(cd.service.new_server_text).replace(/\\dbr/g, "<br>"));

		if(cd.service.notify_enabled == 1) {
			$(".trigger").attr("src", "themes/classic/images/trigger.gif");
			$("#trigger_text").html("Disable Notifications");
		} else {
			$(".trigger").attr("src", "themes/classic/images/notrigger.gif");
			$("#trigger_text").html("Enable Notifications");
		}
		$(".trigger").attr("id", "trigger_" + cd.service.service_id);
		$(".trigger_click").unbind("click").on("click",function() {
			xajax_toggle_service_notify_check(cur_srv_id, cur_svc_id);
			window.setTimeout(function() {
				btl_svc_detail(base_qs);
			}, 500);

		});

		$(".refresh_click").unbind("click").on("click",function() {
			
				btl_svc_detail(base_qs);
		

		});
		$(".force_click").unbind("click").on("click",function() {
				xajax_forceCheck(cur_srv_id, cur_svc_id);
			window.setTimeout(function() {
				btl_svc_detail(base_qs);
			}, 500);				
		

		});



		if(cd.service.service_active == 1) {
			$(".check").attr("src", "themes/classic/images/enabled.gif");
			$("#check_text").html("Disable Checks");
		} else {
				$(".check").attr("src", "themes/classic/images/diabled.gif");
				$("#check_text").html("Enable Checks");
		}
		$(".check_click").unbind("click").on("click", function() {
			xajax_toggle_service_check(cur_srv_id, cur_svc_id);
			window.setTimeout(function() {
				btl_svc_detail(base_qs);
			}, 500);

		});
		$(".handle_click").css("display", "none");
		if(cd.service.current_state != 0 ) {
			$(".handle_click").css("display", "block");
		}
		


		if(cd.service.handled == 1) {
			$(".handle").attr("src", "themes/classic/images/handled.png");
			$("#handle_text").html("Unhandle");
		} else {
			$(".handle").attr("src", "themes/classic/images/unhandled.png");
			$("#handle_text").html("Handle");
		}
		$(".handle").attr("id", "handled_" + cd.service.service_id);


		$(".check").attr("id", "service_" + cd.service.service_id);


		$(".handle_click").unbind("click").on("click",function() {
			xajax_toggle_service_handled(cur_srv_id, cur_svc_id);
			window.setTimeout(function() {
				btl_svc_detail(base_qs);
			}, 500);
		});

	});

}
function btl_svc_list(d) {
	qs = d;
	$("#svcsearchbox").unbind("keydown").on("keydown", function(d) {
			window.clearTimeout(search_timer);
			search_timer=window.setTimeout(btl_svc_list_search, 1000);
	});
	$("#prevpage").unbind("click").on("click", function() {
		cur_page--;
		
		if(cur_page < 0 ) {
			cur_page=0;
			
		}

		btl_svc_list(qs);
		console.log("PREV");
	});
	$("#nextpage").unbind("click").on("click", function() {
		cur_page++;
		if(cur_page > svc_list_max_page) {
			
			cur_page=svc_list_max_page;
		}
		btl_svc_list(qs);
		console.log("NEXT");
	});
	
	$("#svclist li").remove();
	$(".svcoptions_el").css("display", "none");
	$("#svcoptions").click(function() {$(".svcoptions_el").toggle();});
	$("#svclist").append('<li class="table-view-cell table-view-divider">Services</li>');



	$.getJSON("../services.php?" +  qs + "&datatables_output=1&rawService=1&iDisplayStart=" + cur_page*20 + "&iDisplayLength=20", function(d) {
		var start_svc_id=-1;
		//Make PageLinks
		pages=Math.ceil(d.iTotalDisplayRecords/20);
		svc_list_max_page=pages;
		$("#pager").html(cur_page+1  + "/" + pages);
		r = d.rawService;
		var sortable = [];
		
		for(cb in r) {
			c=r[cb];
			if(typeof(c) != "object") continue;
			sortable.push([c, c.server_id]);
		}
		console.log("SORT START");
		sortable.sort(function(a, b) {return a[1] - b[1]});
		console.log(sortable);
		console.log("SORT DONE");
		for(cc in sortable) {
			c=sortable[cc][0];
			
			if(typeof(c) == "undefined") continue;
			console.log(c);
			if(c.server_id != start_svc_id) {
				$("#svclist").append('<li class="table-view-cell table-view-divider">' + c.server_name + '</li>');
				start_svc_id=c.server_id;
			}
			$("#svclist").append('<li class="table-view-cell"><div class="media-body"><a href="service_detail.php?service_id=' + c.service_id + '" class="push-right" data-transition="slide-in"><span class="icon icon-more-vertical" style="color:' + c.color + ';"></span>'  +  c.service_name + ' <p>' + c.new_server_text +  '</p></A></div></li>');	
		}
		//
	});
	//$("#svclist").append('<li class="table-view-cell"><div class="media-body">adssdadsa </div></li>');
}
function btl_ready(ev) {
	console.log("MOBILE INIT");

	$("#backbtn").click(function() {
		history.go(-1);
	});

	a=$(".btlpage").last();
	qs=window.location.search.slice(1);
	base_qs=qs;
	switch(a.data("name")) {
		case 'service_detail.php':
			btl_svc_detail(qs);
		break;
		case "service_list.php":
			btl_svc_list(qs);
		break;

	}

}
$(document).ready(function() {
	btl_ready();	
});