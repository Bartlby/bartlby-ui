/* SM.js */
function sm_local_settings_update(ui, core) {
	$("#local_ui_path").val(ui);
	$("#local_core_path").val(core);
}

$(document).ready(function() {
	 window.refreshable_objects = new Array();
	 btl_add_refreshable_object(
		 	function(data) {
				
				$("#sm_manage").html(data.boxes.sm_manage);
				


	});
	window.clearInterval(window.auto_reloader);
	btl_start_auto_reload();
	console.log("READY CALLED");
	$(document.body).on('click','.sm_delete_btn', function() {
		id=$(this).data("node-id");
		console.log("Delete id=>" + id);
	});
	$(document.body).on('click','.sm_edit_btn', function() {
		id=$(this).data("node-id");
		console.log("EDIT id=>" + id);
	});
	$("#sm_save_node").click(function() {
		console.log("SAVE CLICK");
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