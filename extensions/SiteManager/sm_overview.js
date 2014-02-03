function sm_overview_load() {
	var SCRIPT_REGEX = /<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi;
	console.log("RR");
		instance_ids=2;
		for(x=1; x<sm_conf_counter; x++) {
			console.log("Fetching Status from Node " + x);
			$.get("overview.php?instance_id="  + x  + "&json=1", function(js) {
				json = $.parseJSON(js);
				console.log(json.boxes);
				
				$("#sm_tacbox_" + json.instance_id).html(json.boxes.tactical_overview.replace(SCRIPT_REGEX, ""));
				$("#sm_system_health_" + json.instance_id).html(json.boxes.system_health.replace(SCRIPT_REGEX, ""));
				$("#sm_core_info_" + json.instance_id).html(json.boxes.core_info.replace(SCRIPT_REGEX, ""));
				window.setTimeout(function() { btl_set_bars(); }, 300);
			});			
		}
		


}
$(document).ready(function() {
		sm_overview_load();

		window.setInterval(function () {
			sm_overview_load();
			
		
		}, 5000);

});