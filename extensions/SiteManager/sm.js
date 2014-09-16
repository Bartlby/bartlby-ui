/* SM.js */
function sm_local_settings_update(obj) {
	$("#local_ui_path").val(obj.local_ui_path);
	$("#local_core_path").val(obj.local_core_path);

	$("#local_core_replication_path").val(obj.local_core_replication_path);
	$("#local_ui_replication_path").val(obj.local_ui_replication_path);

	$("#orch_ext_name").val(obj.orch_ext_name);
	$("#orch_db_name").val(obj.orch_db_name);
	$("#orch_db_pw").val(obj.orch_db_pw);
	$("#orch_db_user").val(obj.orch_db_user);
	console.log(obj);
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
function sm_toggle_sync_active(id) {
	xajax_ExtensionAjax("SiteManager", "sm_toggle_sync_active", id);
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
function sm_restart_node(id) {
	//Confirm
	//DELETE ID
	//Reload mgmt list
	c=confirm("Really Schedule a Restart of Node id" + id);
	if(c) {
		xajax_ExtensionAjax("SiteManager", "sm_restart_node", id)
	}
	btl_force_reload_ui();	

}
function sm_hide_tab(t) {
	$('#coreTabs a[href=#' + t+ ']').css("display", "none");
}
$(document).ready(function() {


	//Make Terminal
	$('#sm_terminal').terminal(function(command, term) {
        
    }, {
        greetings: '',
        name: 'js_demo',
        height: 300,
        width: '100%',
        prompt: ''});

	/*
	window.setInterval(function() {
		$('#sm_terminal').terminal().echo("OUTPUT");
	}, 400);
	*/

	$(document.body).on('click','.sm_orch_shuffle_btn', function() {
		c=confirm("Shuffle all servers uppon all orch-nodes?");
		if(c) {
			$("#sm_terminal").terminal().echo("#############  STARTING SHUFFLE JOB ###########");
			$.get("extensions_wrap.php?script=SiteManager/cron.php?sync=SHUFFLE&force_color=1", function(d) {
				$("#sm_terminal").terminal().echo(d);
			}).done(function() {
				$("#sm_terminal").terminal().echo("#############  DONE SHUFFLE JOB ###########");
			});
			
		}
	});
	$(document.body).on('click','.sm_orch_restart_btn', function() {
		c=confirm("Force RESTART job of scheduled-to-restart nodes?");
		if(c) {
			$("#sm_terminal").terminal().echo("#############  STARTING RESTART JOB ###########");
			$.get("extensions_wrap.php?script=SiteManager/cron.php?sync=RESTART&force_color=1", function(d) {
				$("#sm_terminal").terminal().echo(d);
			}).done(function() {
				$("#sm_terminal").terminal().echo("#############  DONE RESTART JOB ###########");	
			});
			
		}
	});

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
	$(document.body).on('click','.sm_toggle_sync_btn', function() {
		id=$(this).data("node-id");
		
		sm_toggle_sync_active(id);

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
	$(document.body).on('click','.sm_restart_btn', function() {
		id=$(this).data("node-id");
		
		sm_restart_node(id);
	});
	$("#sm_save_node").click(function() {
		xajax_ExtensionAjax("SiteManager", "sm_save_node", xajax.getFormValues("sm_form"));
	});
	$("#sm_save_local").click(
		function() {
			r=xajax_ExtensionAjax('SiteManager', 'sm_save_local_settings', $("#local_ui_path").val(), $("#local_core_path").val(), $("#local_core_replication_path").val(), $("#local_ui_replication_path").val(),
				$("#orch_ext_name").val(), 
				$("#orch_db_user").val(),
				$("#orch_db_pw").val(),
				$("#orch_db_name").val());
			console.log(r);
		}
	);
	xajax_ExtensionAjax('SiteManager', 'sm_set_local_settings');


});