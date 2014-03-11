var cur_page=0;
var svc_list_max_page=0;
var qs="";
var base_qs="";
var search_timer;
var cur_svc_id=0;
var cur_srv_id=0;

//FIXME SUPPORT FOR MULTI_NODE MODE

 window.addEventListener('push', mypushHandler);
 function mypushHandler(ev) {
       btl_ready(ev);
 }

 function qsf(key) {
    key = key.replace(/[*+?^$.\[\]{}()|\\\/]/g, "\\$&"); // escape RegEx meta chars
    var match = location.search.match(new RegExp("[?&]"+key+"=([^&]+)(&|$)"));
    return match && decodeURIComponent(match[1].replace(/\+/g, " "));
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
	qs = base_qs + "&text_search=" + $("#svcsearchbox").val();
	cur_page=0;
	btl_svc_list(qs);
}
function btl_svc_detail(d) {
	$.getJSON("../api/v1/running/service/" + qsf("service_id") + "?service_expand_ui=1", function(d1) {
		cd=d1.services[0];
		console.log(cd);
		cur_svc_id=cd.service_id;
		cur_srv_id=cd.server_id;
		var d = new Date((cd.last_check+cd.check_interval)*1000);
		next_check=d;
		$("#svc_server").html(cd.server_name + " (ID:"  + cd.server_id + ") " + cd.beauty_server_enabled);
		$("#svc_name").html( cd.service_name);
		$("#svc_type").html(cd.beauty_type);
		$("#svc_plan").html(cd.beauty_check_plan);
		$("#svc_life").html(cd.beauty_dead_marker);
		$("#svc_running").html(cd.beauty_check_is_running);
		$("#svc_escalate").html(cd.escalate_divisor);
		$("#svc_fire").html(cd.beauty_fires_events);
		$("#svc_handled").html(cd.beauty_handled);
		$("#svc_trigger").html(cd.beauty_triggers);
		$("#svc_check").html(cd.beauty_service_enabled);
		$("#svc_renotify").html(cd.renotify_interval);
		$("#svc_last_notification").html(cd.service_ms);
		$("#svc_notify").html(cd.beauty_notify_enabled);
		$("#svc_delay").html("Delay: " + cd.service_delay + "ms Check-Time: " + cd.service_ms + "ms (AVG)");
		$("#svc_last_next").html("<span id=last_next1></span><br><span id=last_next2></span>");

		$("#last_next1").livestamp(cd.last_check);
		$("#last_next2").livestamp(((d.getTime()/1000)+cd.check_interval));


		$("#svc_last_notification").livestamp(cd.last_notify_send);
		$("#svc_intervall").html(cd.check_interval);
		$("#svc_flap").html(cd.flap_count + " / " + cd.flap_seconds + "s");
		$("#svc_status").html(cd.service_retain_current + " / " + cd.service_retain + "");
		$("#svc_last_state_change").livestamp(cd.last_state_change);
		$("#svc_curr_label").html(" <span class='badge' style='background-color:" + cd.beauty_color + "'>"  + cd.beauty_state + "</span> ");
		$("#svc_output").html(nl2br(cd.new_server_text).replace(/\\dbr/g, "<br>"));

		if(cd.notify_enabled == 1) {
			$(".trigger").attr("src", "themes/classic/images/trigger.gif");
			$("#trigger_text").html("Disable Notifications");
		} else {
			$(".trigger").attr("src", "themes/classic/images/notrigger.gif");
			$("#trigger_text").html("Enable Notifications");
		}
		$(".trigger").attr("id", "trigger_" + cd.service_id);
		$(".trigger_click").unbind("click").on("click",function() {
			//xajax_toggle_service_notify_check(cur_srv_id, cur_svc_id);
			$.post("../api/v1/running/service/" + cur_svc_id + "/trigger", function() {
				btl_svc_detail(base_qs);
			});
			

		});

		$(".refresh_click").unbind("click").on("click",function() {
			
				btl_svc_detail(base_qs);
		

		});
		$(".force_click").unbind("click").on("click",function() {
				$.post("../api/v1/running/service/" + cur_svc_id + "/force", function() {
					btl_svc_detail(base_qs);
					noty({"text":"Check has been forced","timeout": 600, "layout":"center","type":"success","animateOpen": {"opacity": "show"}});
				});		
		

		});



		if(cd.service_active == 1) {
			$(".check").attr("src", "themes/classic/images/enabled.gif");
			$("#check_text").html("Disable Checks");
		} else {
				$(".check").attr("src", "themes/classic/images/diabled.gif");
				$("#check_text").html("Enable Checks");
		}
		$(".check_click").unbind("click").on("click", function() {
			$.post("../api/v1/running/service/" + cur_svc_id + "/active", function() {
				btl_svc_detail(base_qs);
			});

		});
		$(".handle_click").css("display", "none");
		if(cd.current_state != 0 ) {
			$(".handle_click").css("display", "block");
		}
		


		if(cd.handled == 1) {
			$(".handle").attr("src", "themes/classic/images/handled.png");
			$("#handle_text").html("Unhandle");
		} else {
			$(".handle").attr("src", "themes/classic/images/unhandled.png");
			$("#handle_text").html("Handle");
		}
		$(".handle").attr("id", "handled_" + cd.service_id);


		$(".check").attr("id", "service_" + cd.service_id);


		$(".handle_click").unbind("click").on("click",function() {
			$.post("../api/v1/running/service/" + cur_svc_id + "/handle", function() {
				btl_svc_detail(base_qs);
			});
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



	$.getJSON("../api/v1/running/service?" +  qs + "&service_expand_ui=1&from=" + cur_page*20 + "&to=20", function(d) {
		var start_svc_id=-1;
		//Make PageLinks
		
		pages=Math.ceil(d.available_services/20);
		svc_list_max_page=pages;
		$("#pager").html(cur_page+1  + "/" + pages);
		r = d.services;
		var sortable = [];
		
		for(cb in r) {
			c=r[cb];
			
			//if(typeof(c) != "object") continue;
			if(c == null || typeof(c) != "object") continue;
			
			sortable.push([c, c.server_id]);
		}
		
		sortable.sort(function(a, b) {return a[1] - b[1]});
	
		for(cc in sortable) {
			c=sortable[cc][0];
			
			if(typeof(c) == "undefined") continue;
			
			if(c.server_id != start_svc_id) {
				$("#svclist").append('<li class="table-view-cell table-view-divider">' + c.server_name + '</li>');
				start_svc_id=c.server_id;
			}
			$("#svclist").append('<li class="table-view-cell"><div class="media-body"><a href="service_detail.php?service_id=' + c.service_id + '" class="push-right" data-transition="slide-in"><span class="icon icon-more-vertical" style="color:' + c.beauty_color + ';"></span>'  +  c.service_name + ' <p>' + c.new_server_text +  '</p></A></div></li>');	
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