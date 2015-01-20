/* SM.js */
function ar_GrpChk() {
			window.open('grpstr.php?str='+document.fm1.service_var.value, 'grp', 'width=600, height=600, scrollbars=yes');
		} 

function ar_local_settings_update(smtp, mailfrom) {
	$("#local_smtp_host").val(smtp);
	$("#local_mail_from").val(mailfrom);
}
function ar_show_tab(t) {
	$('#coreTabs a[href=#' + t + ']').tab("show");
	
}
function ar_copy_node(id) {
        $("#ar_edit_mode").html("COPY Report Mode");
        $("#ar_edit_node_id").val("");
        ar_lock_form();
        xajax_ExtensionAjax("AutoReports", "ar_load_form", id);
}
function ar_add_new() {
	$("#ar_edit_mode").html("ADD Report Mode");
	$("#ar_edit_node_id").val("");
	ar_show_tab("ar_add");
	ar_lock_form();
	xajax_ExtensionAjax("AutoReports", "ar_load_form", "");
	//Set type to ADD
}
function ar_edit_node(id) {
	$("#ar_edit_mode").html("EDIT Report Mode");
	$("#ar_edit_node_id").val(id);
	ar_lock_form();
	xajax_ExtensionAjax("AutoReports", "ar_load_form", id);
	//Set type to MODIFY
	//Load Data
	//Show Tab
	
}
function ar_lock_form() {
	$("#ar_form :input").attr("disabled", true);
}
function ar_unlock_form() {
	$("#ar_form :input").attr("disabled", false);
}
function ar_delete_node(id) {
	//Confirm
	//DELETE ID
	//Reload mgmt list
	c=confirm("Really Delete Node id" + id);
	if(c) {
		xajax_ExtensionAjax("AutoReports", "ar_delete_node", id)
	}
	btl_force_reload_ui();	

}
function ar_hide_tab(t) {
	$('#coreTabs a[href=#' + t+ ']').css("display", "none");
}
$(document).ready(function() {

	//Hide The form Tab
	 ar_hide_tab("ar_add");
	 ar_show_tab("ar_manage");
	 window.refreshable_objects = new Array();
	 btl_add_refreshable_object(
		 	function(data) {
				
				$("#ar_manage").html(data.boxes.ar_manage);
				


	});
	window.clearInterval(window.auto_reloader);
	//btl_start_auto_reload();
	console.log("READY CALLED");
	$(document.body).on('click','.ar_modify_btn', function() {
		id=$(this).data("node-id");
		
		ar_edit_node(id);

	});
	$(document.body).on('click','.ar_add_new_btn', function() {
		ar_add_new();
	});
	$(document.body).on('click','.ar_delete_btn', function() {
		id=$(this).data("node-id");
		
		ar_delete_node(id);
	});
	$(document.body).on('click','.ar_copy_btn', function() {
                id=$(this).data("node-id");
                ar_copy_node(id);

        });

	$("#ar_save_node").click(function() {
		xajax_ExtensionAjax("AutoReports", "ar_save_node", xajax.getFormValues("fm1"));
	});
	$("#ar_save_local").click(
		function() {
			r=xajax_ExtensionAjax('AutoReports', 'ar_save_local_settings', $("#local_smtp_host").val(), $("#local_mail_from").val());
			console.log(r);
		}
	);
	xajax_ExtensionAjax('AutoReports', 'ar_set_local_settings');


});
