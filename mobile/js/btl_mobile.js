 window.addEventListener('push', mypushHandler);
 function mypushHandler(ev) {
       btl_ready(ev);
 }


function btl_svc_list(d) {
	qs = d;
	$("#svclist li").remove();
	$(".svcoptions_el").css("display", "none");
	$("#svcoptions").click(function() {$(".svcoptions_el").toggle();});
	$("#svclist").append('<li class="table-view-cell table-view-divider">Services</li>');
	$.getJSON("../services.php?" +  qs, function(d) {
		r = d.rawService;

		for(x=0; x<r.length; x++) {
			c=r[x];
			$("#svclist").append('<li class="table-view-cell"><div class="media-body"><a href="service_detail.php" class="push-right" data-transition="slide-in"><span class="icon icon-more-vertical" style="color:' + c.color + ';"></span>'  + c.service_name + ' <p>' + c.new_server_text +  '</p></A></div></li>');
		}
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
	switch(a.data("name")) {
		case "service_list.php":
			btl_svc_list(qs);
		break;

	}

}
$(document).ready(function() {
	btl_ready();	
});