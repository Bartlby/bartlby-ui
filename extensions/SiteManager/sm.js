/* SM.js */
function sm_local_settings_update(ui, core, core_replication_path, ui_replication_path) {
	$("#local_ui_path").val(ui);
	$("#local_core_path").val(core);

	$("#local_core_replication_path").val(core_replication_path);
	$("#local_ui_replication_path").val(ui_replication_path);
}
function sm_show_tab(t) {
	$('#coreTabs a[href=#' + t + ']').tab("show");
	
}
function sm_add_new() {
	$("#sm_edit_mode").html("ADD Node Mode");
	$("#sm_edit_node_id").val("");
	sm_show_tab("sm_add");
	sm_lock_form();
	xajax_ExtensionAjax("SiteManager", "sm_load_form", "");
	//Set type to ADD
}
function sm_copy_node(id) {
	$("#sm_edit_mode").html("COPY Node Mode");
	$("#sm_edit_node_id").val("");
	sm_lock_form();
	xajax_ExtensionAjax("SiteManager", "sm_load_form", id);
}
function sm_edit_node(id) {
	$("#sm_edit_mode").html("EDIT Node Mode");
	$("#sm_edit_node_id").val(id);
	sm_lock_form();
	xajax_ExtensionAjax("SiteManager", "sm_load_form", id);
	//Set type to MODIFY
	//Load Data
	//Show Tab
	
}
function sm_lock_form() {
	$("#sm_form :input").attr("disabled", true);
}
function sm_unlock_form() {
	$("#sm_form :input").attr("disabled", false);
}
function sm_delete_node(id) {
	//Confirm
	//DELETE ID
	//Reload mgmt list
	c=confirm("Really Delete Node id" + id);
	if(c) {
		xajax_ExtensionAjax("SiteManager", "sm_delete_node", id)
	}
	btl_force_reload_ui();	

}
function sm_hide_tab(t) {
	$('#coreTabs a[href=#' + t+ ']').css("display", "none");
}
$(document).ready(function() {

	//Hide The form Tab
	 sm_hide_tab("sm_add");
	 sm_show_tab("sm_manage");
	 window.refreshable_objects = new Array();
	 btl_add_refreshable_object(
		 	function(data) {
				
				$("#sm_manage").html(data.boxes.sm_manage);
				


	});
	window.clearInterval(window.auto_reloader);
	//btl_start_auto_reload();
	console.log("READY CALLED");
	$(document.body).on('click','.sm_modify_btn', function() {
		id=$(this).data("node-id");
		
		sm_edit_node(id);

	});
	$(document.body).on('click','.sm_copy_btn', function() {
		id=$(this).data("node-id");
		sm_copy_node(id);

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
			r=xajax_ExtensionAjax('SiteManager', 'sm_save_local_settings', $("#local_ui_path").val(), $("#local_core_path").val(), $("#local_core_replication_path").val(), $("#local_ui_replication_path").val());
			console.log(r);
		}
	);
	xajax_ExtensionAjax('SiteManager', 'sm_set_local_settings');


});