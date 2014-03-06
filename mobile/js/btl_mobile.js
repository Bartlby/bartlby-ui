var cur_page=0;
var svc_list_max_page=0;
var qs="";
var base_qs="";
var search_timer;

 window.addEventListener('push', mypushHandler);
 function mypushHandler(ev) {
       btl_ready(ev);
 }


function btl_svc_list_search() {
	console.log("SEARCH FOR:" + $("#svcsearchbox").val());
	qs = base_qs + "&sSearch=" + $("#svcsearchbox").val();
	cur_page=0;
	btl_svc_list(qs);
}
function btl_svc_detail(d) {
	$.getJSON("../services.php?" +  qs + "&datatables_output=1&rawService=1&iDisplayStart=" + cur_page*20 + "&iDisplayLength=20", function(d) {
		console.log(d);
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