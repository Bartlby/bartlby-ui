/* SM.js */
function sm_local_settings_update(ui, core) {
	$("#local_ui_path").val(ui);
	$("#local_core_path").val(core);
}
function sm_show_tab(t) {
	$('#coreTabs a[href=#' + t + ']').tab("show");
}
function sm_add_new() {
	sm_show_tab("sm_add");
	//Set type to ADD
}
function sm_edit_node(id) {
	
	//Set type to MODIFY
	//Load Data
	//Show Tab
	sm_show_tab("sm_add");
}
function sm_delete_node(id) {
	//Confirm
	//DELETE ID
	//Reload mgmt list
	c=confirm("Really Delete Node id" + id);
	btl_force_reload_ui();	

}
$(document).ready(function() {

	
	 sm_show_tab("sm_manage");
	 window.refreshable_objects = new Array();
	 btl_add_refreshable_object(
		 	function(data) {
				
				$("#sm_manage").html(data.boxes.sm_manage);
				


	});
	window.clearInterval(window.auto_reloader);
	btl_start_auto_reload();
	console.log("READY CALLED");
	$(document.body).on('click','.sm_modify_btn', function() {
		id=$(this).data("node-id");
		
		sm_edit_node(id);

	});
	$(document.body).on('click','.sm_add_new_btn', function() {
		sm_add_new();
	});
	$(document.body).on('click','.sm_delete_btn', function() {
		id=$(this).data("node-id");
		
		sm_delete_node(id);
	});
	$("#sm_save_node").click(function() {
		xajax_ExtensionAjax("SiteManager", "sm_save_node", xajax.getFormValues("sm_form"));
	});
	$("#sm_save_local").click(
		function() {
			r=xajax_ExtensionAjax('SiteManager', 'sm_save_local_settings', $("#local_ui_path").val(), $("#local_core_path").val());
			console.log(r);
		}
	);
	xajax_ExtensionAjax('SiteManager', 'sm_set_local_settings');


});