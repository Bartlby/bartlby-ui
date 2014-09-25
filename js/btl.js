window.global_reload=1;
window.refreshable_objects=new Array();
window.auto_reloader=-1;

$(window).blur(function() 
{
	if(window.auto_reloader != -1) {
		console.log("DISABLE AUTO RELOAD INVISIBLE");
		window.clearInterval(window.auto_reloader);
	}
});
$(window).focus(function() {
	if(window.auto_reloader != -1) {
		console.log("ENABLE AUTORELOAD VISIBLE");
		btl_force_reload_ui();
		btl_start_auto_reload();
	}
});

function addAssignAllImg(id, src) {
	$('[id=' + id + ']').attr("src", src);
}

function quick_look_group() {

 $('#quick_look_table').dataTable({
					"fnInitComplete": function() {
						
					},
					"iDisplayLength": 50,
					"fnDrawCallback": function ( oSettings ) {
						
						if ( oSettings.aiDisplay.length == 0 )
						{
							return;
						}
						
						var nTrs = $('tbody tr', oSettings.nTable);
						var iColspan = nTrs[0].getElementsByTagName('td').length;
						var sLastGroup = "";
						for ( var i=0 ; i<nTrs.length ; i++ )
						{
							var iDisplayIndex = oSettings._iDisplayStart + i;
							//var sGroup = oSettings.aoData[ oSettings.aiDisplay[iDisplayIndex] ]._aData[0];
							var sGroup = oSettings.aoData[ oSettings.aiDisplay[i] ]._aData[0];
							if ( sGroup != sLastGroup )
							{
								var nGroup = document.createElement( 'tr' );
								var nCell = document.createElement( 'td' );
								nCell.colSpan = iColspan;
								nCell.className = "group";
								nCell.innerHTML = sGroup;
								nGroup.appendChild( nCell );
								nTrs[i].parentNode.insertBefore( nGroup, nTrs[i] );
								sLastGroup = sGroup;
							}
						}
						//$("#services_table").show();
					},
					"aoColumnDefs": [
						{ "bVisible": false, "aTargets": [ 0 ] }
					],
					
					"aaSortingFixed": [[ 0, 'asc' ]],
					"bSort": false,
					"bPaginate": false,
					"bFilter": false,
					"sDom": '<"top">rt<"bottom"flp><"clear">',
					"aaSorting": [[ 1, 'asc' ]],
				   "oLanguage": {
			    	"sEmptyTable": "No Services found",
            "sProcessing": "<img src='extensions/AutoDiscoverAddons/ajax-loader.gif'> Loading"
        	}
			    
       
				});
	
}

function btl_force_reload_ui() {
			console.log("FORCE LOAD");
			u = document.location.href;
			u += (u.match(/\?/) ? '&' : '?') + "json=1";
		
			$.getJSON(u, function(data) {
				btl_call_refreshable_objects(data);



			});
		
			



}
function btl_start_auto_reload() {
		
		window.auto_reloader= window.setInterval(function() {
	
		btl_force_reload_ui();
			
		},5000);
		
	}
	

function btl_get_refreshable_value(data, key) {
	rv=data.refreshable_objects[key];

	return rv;
}

function btl_add_refreshable_object(fcn_callback) {
		o = {
			callback: fcn_callback			
		}	
		window.refreshable_objects.push(o);
		
}
function toFixed(num, fixed) {
    fixed = fixed || 0;
    fixed = Math.pow(10, fixed);
    return Math.ceil(num * fixed) / fixed;
}
function btl_set_bars() {
	$(".bar").each(function() {
				px=$(this).css("width").replace(/px/, "");
				if(px > 25) {
					$(this).html($(this).data("perc") + '%');
				} else {
					$(this).html("");
				}
			});
		
}
function btl_call_refreshable_objects(data) {
	if(typeof(window.refreshable_objects.length) == "undefined") {
		return;
	}
	for(x=0; x<window.refreshable_objects.length; x++) {
		tw = 	window.refreshable_objects[x];
		tw.callback(data);
	}


	btl_set_bars();

}
	

function btl_change(t) {
		document.location.href='bartlby_action.php?set_instance_id=' + t.selectedIndex + '&action=set_instance_id';
}

function bulk_server_edit(mode) {
	servers_to_handle=new Array();
			$('.server_checkbox').each(function() {
				
				if($(this).is(':checked')) {
						servers_to_handle.push($(this).data("server_id"));
				}
			});
			console.log("Handle Servers");
			console.log(servers_to_handle);

			xajax_bulkEditValuesServer(servers_to_handle, xajax.getFormValues("servers_bulk_form"), mode);

}


function bulk_service_edit(mode) {
	services_to_handle=new Array();
			$('.service_checkbox').each(function() {
				
				if($(this).is(':checked')) {
						services_to_handle.push($(this).data("service_id"));
				}
			});
			console.log("Handle Services");
			console.log(services_to_handle);

			xajax_bulkEditValues(services_to_handle, xajax.getFormValues("services_bulk_form"), mode);

}
$(document).ready(function() {
		btl_set_bars();
		$("#services_bulk_edit_delete").click(function() {
			if(confirm("You really want to delete the selected services?")) {
				bulk_service_edit(3);	
			}
			
		})
		$("#services_bulk_edit_run").click(function() {
			bulk_service_edit(1);
		});
		//BULK EDIT
		$("#services_bulk_edit_dry_run").click(function() {
			//Get Service id list
			bulk_service_edit(0);

		});
		$("#services_bulk_edit").click(function() {
			window.clearTimeout(window.service_list_timer); //Disable auto reload
			if($('.service_checkbox').is(":checked") == false) {
				if(!confirm("You have not selected any service if you continue - all your bulk actions will apply to EVERY services (system wide)!!")) {
					return;
				}
			}
			$('#myModal').modal('show');
		});

		$("#servers_bulk_edit_run").click(function() {
			bulk_server_edit(1);
		});
		//BULK EDIT SERVER
		$("#servers_bulk_edit_dry_run").click(function() {
			//Get Service id list
			bulk_server_edit(0);

		});
		$("#servers_bulk_edit").click(function() {
			window.clearTimeout(window.server_list_timer); //Disable auto reload
			if($('.server_checkbox').is(":checked") == false) {
				if(!confirm("You have not selected any server if you continue - all your bulk actions will apply to EVERY server (system wide)!!")) {
					return;
				}
			}
			$('#myModal').modal('show');
		});



		$("#services_bulk_force").click(function() {
		var force_services = new Array();
			$('.service_checkbox').each(function() {
				if($(this).is(':checked')) {
						force_services.push($(this).data("service_id"));
				}
			});
			xajax_bulkForce(force_services);
		
	});
	
	
	$("#services_bulk_enable_checks").click(function() {
		var force_services = new Array();
			$('.service_checkbox').each(function() {
				if($(this).is(':checked')) {
						force_services.push($(this).data("service_id"));
				}
			});
			xajax_bulkEnableChecks(force_services);
		
	});
	
	$("#services_bulk_disable_checks").click(function() {
		var force_services = new Array();
			$('.service_checkbox').each(function() {
				if($(this).is(':checked')) {
						force_services.push($(this).data("service_id"));
				}
			});
			xajax_bulkDisableChecks(force_services);
		
	});
	
	
	$("#services_bulk_enable_notifys").click(function() {
		var force_services = new Array();
			$('.service_checkbox').each(function() {
				if($(this).is(':checked')) {
						force_services.push($(this).data("service_id"));
				}
			});
			xajax_bulkEnableNotifys(force_services);
		
	});
	
	$("#services_bulk_disable_notifys").click(function() {
		var force_services = new Array();
			$('.service_checkbox').each(function() {
				if($(this).is(':checked')) {
						force_services.push($(this).data("service_id"));
				}
			});
			xajax_bulkDisableNotifys(force_services);
		
	});
	
	
	
	
	
	$("#service_checkbox_select_all").click(function() {
		if($(this).is(':checked')) {
			console.log("check all");
			$('.service_checkbox').attr("checked", "checked");
		} else {
			$('.service_checkbox').removeAttr("checked", "checked");
		}
	});
	
	$("#server_checkbox_select_all").click(function() {
		if($(this).is(':checked')) {
			console.log("check all");
			$('.server_checkbox').attr("checked", "checked");
		} else {
			$('.server_checkbox').removeAttr("checked", "checked");
		}
	});
	
	//Service-DataTable
		s_url = document.location.href.replace(/\/s.*\.php/, "/services.php");
		s_char = "?";
		if(s_url.match(/\?/)) {
			s_char = "&";
		}
		
		server_ajax_url = document.location.href.replace(/\/s.*\.php/, "/servers.php");
		server_char = "?";
		if(server_ajax_url.match(/\?/)) {
			server_char = "&";
		}
				
	//$("#services_table").hide();
	window.oTable = $('#services_table').dataTable({
					"fnInitComplete": function() {
						
					},
					"iDisplayLength": 50,
					"fnDrawCallback": function ( oSettings ) {
						
						if ( oSettings.aiDisplay.length == 0 )
						{
							return;
						}
						
						var nTrs = $('tbody tr', oSettings.nTable);
						var iColspan = nTrs[0].getElementsByTagName('td').length;
						var sLastGroup = "";
						for ( var i=0 ; i<nTrs.length ; i++ )
						{
							var iDisplayIndex = oSettings._iDisplayStart + i;
							//var sGroup = oSettings.aoData[ oSettings.aiDisplay[iDisplayIndex] ]._aData[0];
							var sGroup = oSettings.aoData[ oSettings.aiDisplay[i] ]._aData[1];
							if ( sGroup != sLastGroup )
							{
								var nGroup = document.createElement( 'tr' );
								var nCell = document.createElement( 'td' );
								nCell.colSpan = iColspan;
								nCell.className = "group";
								nCell.innerHTML = sGroup;
								nGroup.appendChild( nCell );
								nTrs[i].parentNode.insertBefore( nGroup, nTrs[i] );
								sLastGroup = sGroup;
							}
						}
						//$("#services_table").show();
					},
					"aoColumnDefs": [
						{ "bVisible": false, "aTargets": [ 1 ] }
					],
					"aoColumns": [
						{ "sWidth": "1" },
						{ "sWidth": "1" },
						{ "sWidth": "50" },
						{ "sWidth": "100" },
						{ "sWidth": "100" },
						{ "sWidth": "150" },
						{ "sWidth": "450" },
						{ "sWidth": "320" },
					],
					"aaSortingFixed": [[ 0, 'asc' ]],
					"bSort": false,
					"aaSorting": [[ 1, 'asc' ]],
					"sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span12'i><'span12 center'p>>",
					//"sDom": '<"top"i>rt<"bottom"flp><"clear">',
					//"sDom": '<"wrapper"lfptip>',
					//"sDom": "<'row'<'span9'l><'span9'f>r>t<'row'<'span9'i><'span9'p>>",
			    "sPaginationType": "bootstrap",
			    "sAjaxSource": s_url + s_char + "datatables_output=1",
			    "bServerSide": true,
			    "bProcessing": true,
			    "oLanguage": {
			    	"sEmptyTable": "No Services found",
            "sProcessing": "<img src='extensions/AutoDiscoverAddons/ajax-loader.gif'> Loading"
        	}
			    
       
				});
				
window.servers_table = $('#servers_table').dataTable({
					"iDisplayLength": 50,
					
					"aoColumns": [
						{ "sWidth": "1" },
						{ "sWidth": "100" },
						{ "sWidth": "100" },
						{ "sWidth": "20" },
						{ "sWidth": "150" }
						],
					"aaSortingFixed": [[ 0, 'asc' ]],
					"bSort": false,
					"aaSorting": [[ 1, 'asc' ]],
					"sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span12'i><'span12 center'p>>",
					//"sDom": '<"top"i>rt<"bottom"flp><"clear">',
					//"sDom": '<"wrapper"lfptip>',
					//"sDom": "<'row'<'span9'l><'span9'f>r>t<'row'<'span9'i><'span9'p>>",
			    "sPaginationType": "bootstrap",
			    "sAjaxSource": server_ajax_url + server_char + "datatables_output=1",
			    "bServerSide": true,
			    "bProcessing": true,
			    "oLanguage": {
			    	"sEmptyTable": "No Servers found",
            "sProcessing": "<img src='extensions/AutoDiscoverAddons/ajax-loader.gif'> Loading"
        	}
			    
       
				});
		 
		$("#toggle_reload").click(function() {
			if(global_reload == 1) {
					global_reload=0;
					window.clearInterval(window.auto_reloader);
			} else {
				global_reload=1;
				btl_start_auto_reload();
			}
		});	
	});

	
	function downtime_type_selected() {
		drop = document.getElementsByName("downtime_type")[0];
		url ="";
		if(drop.options[drop.selectedIndex].value == 1) 	url = "service_list.php?script=add_downtime.php&pkey=downtime_type&pval=1";
		if(drop.options[drop.selectedIndex].value == 2) 	url = "server_list.php?script=add_downtime.php&pkey=downtime_type&pval=2";
		if(drop.options[drop.selectedIndex].value == 3)  	url = "servergroup_list.php?script=add_downtime.php&pkey=downtime_type&pval=3";
		if(drop.options[drop.selectedIndex].value == 4) 	url = "servicegroup_list.php?script=add_downtime.php&pkey=downtime_type&pval=4";
		
		document.location.href=url;
	}
	function GenericToggleFix(elID, st) {
	//alert(elID);
	//alert(st);
		obj=document.getElementById(elID);
		//alert(obj);
		obj.style.display=st;  
	}
	function GenericToggle(elID) {
		obj=document.getElementById(elID);
		obj.style.display=!(obj.style.display=="block")? "block" : "none";  
	}
	function jsLogout() {
		r=confirm("You really want to logout?");	
		if(r == true) {
			document.location.href='logout.php';	
		}
	}
	function doToggle(elID) {
		switch(elID) {
			case 'main':
				elID="Monitoring";
			break;
			case 'report':
				elID="Reporting";
			break;
			case 'client':
				elID="Server/s";
			break;
			case 'services':
				elID="Service/s";
			break;
			case 'downtimes':
				elID="Downtime/s";
			break;
			case 'worker':
				elID="Worker/s";
			break;
			case 'core':
				elID="Core";
			break;
			
		}
		//imgPlus='themes/'+js_theme_name+'/images/plus.gif';
		//imgMinus='themes/'+js_theme_name+'/images/minus.gif';
		//obj=document.getElementById(elID + "_sub");
		//obj.style.display=!(obj.style.display=="block")? "block" : "none";  
		
		
		//obji=document.getElementById(elID + "_plus");
		//cImg="images" + obji.src.substring(obji.src.lastIndexOf("/"), obji.src.length);
		
		
		//obji.src=!(cImg==imgMinus)? imgMinus : imgPlus;  
		
	}

	var buffer_suggest = 
	{
	        bufferText: false,
	        bufferTime: 500,
	        
	        modified : function(strId, fcn, scr)
	        {
	                setTimeout('buffer_suggest.compareBuffer("'+strId+'","'+document.getElementById(strId).value+'","'+ fcn +'", "'+scr+'");', this.bufferTime);
	        },
	        
	        compareBuffer : function(strId, strText, fcn, scr)
	        {
	            if (strText == document.getElementById(strId).value && strText != this.bufferText)
	            {
	                this.bufferText = strText;
	                buffer_suggest.makeRequest(strId, fcn, scr);
	            }
	        },
	        
	        makeRequest : function(strId, fcn, scr)
	        {
	            	            
	            eval(fcn + "(document.getElementById(strId).value, scr)");
	        }
	}



function serviceManageIconChange(f) {
	selval=f.server_icon.options[f.server_icon.selectedIndex].value;
	ph = document.getElementById("picholder");
	ph.innerHTML="<img src='server_icons/" + selval + "'>";
		
}
function openMap() {
	window.open('create_map.php','','width=1024,height=786, scrollbar=yes, scrollbars=yes')
}
function doReloadButton() {
	var obj = document.getElementById("reload");
        obj.style.visibility = "visible";
}

var menuwidth='250px' //default menu width
var menubgcolor='999999'  //menu bgcolor
var disappeardelay=250  //menu disappear speed onMouseout (in miliseconds)
var hidemenu_onclick="yes" //hide menu when user clicks within menu?

/////No further editting needed

var ie4=document.all
var ns6=document.getElementById&&!document.all

if (ie4||ns6)
document.write('<div id="dropmenudiv" style="visibility:hidden;width:'+menuwidth+';background-color:'+menubgcolor+'" onMouseover="clearhidemenu()" onMouseout="dynamichide(event)"></div>')

function getposOffset(what, offsettype){
var totaloffset=(offsettype=="left")? what.offsetLeft : what.offsetTop;
var parentEl=what.offsetParent;
while (parentEl!=null){
totaloffset=(offsettype=="left")? totaloffset+parentEl.offsetLeft : totaloffset+parentEl.offsetTop;
parentEl=parentEl.offsetParent;
}
return totaloffset;
}


function showhide(obj, e, visible, hidden, menuwidth){
if (ie4||ns6)
dropmenuobj.style.left=dropmenuobj.style.top=-500
if (menuwidth!=""){
dropmenuobj.widthobj=dropmenuobj.style
dropmenuobj.widthobj.width=menuwidth
}
if (e.type=="click" && obj.visibility==hidden || e.type=="mouseover")
obj.visibility=visible
else if (e.type=="click")
obj.visibility=hidden
}

function iecompattest(){
return (document.compatMode && document.compatMode!="BackCompat")? document.documentElement : document.body
}

function clearbrowseredge(obj, whichedge){
var edgeoffset=0
if (whichedge=="rightedge"){
var windowedge=ie4 && !window.opera? iecompattest().scrollLeft+iecompattest().clientWidth-15 : window.pageXOffset+window.innerWidth-15
dropmenuobj.contentmeasure=dropmenuobj.offsetWidth
if (windowedge-dropmenuobj.x < dropmenuobj.contentmeasure)
edgeoffset=dropmenuobj.contentmeasure-obj.offsetWidth
}
else{
var topedge=ie4 && !window.opera? iecompattest().scrollTop : window.pageYOffset
var windowedge=ie4 && !window.opera? iecompattest().scrollTop+iecompattest().clientHeight-15 : window.pageYOffset+window.innerHeight-18
dropmenuobj.contentmeasure=dropmenuobj.offsetHeight
if (windowedge-dropmenuobj.y < dropmenuobj.contentmeasure){ //move up?
edgeoffset=dropmenuobj.contentmeasure+obj.offsetHeight
if ((dropmenuobj.y-topedge)<dropmenuobj.contentmeasure) //up no good either?
edgeoffset=dropmenuobj.y+obj.offsetHeight-topedge
}
}
return edgeoffset
}

function populatemenu(what){
if (ie4||ns6)
dropmenuobj.innerHTML=what.join("")
}


function dropdownmenu(obj, e, menucontents, menuwidth){
if (window.event) event.cancelBubble=true
else if (e.stopPropagation) e.stopPropagation()
clearhidemenu()
dropmenuobj=document.getElementById? document.getElementById("dropmenudiv") : dropmenudiv
populatemenu(menucontents)

if (ie4||ns6){
showhide(dropmenuobj.style, e, "visible", "hidden", menuwidth)
dropmenuobj.x=getposOffset(obj, "left")
dropmenuobj.y=getposOffset(obj, "top")
dropmenuobj.style.left=dropmenuobj.x-clearbrowseredge(obj, "rightedge")+"px"
dropmenuobj.style.top=dropmenuobj.y-clearbrowseredge(obj, "bottomedge")+obj.offsetHeight+"px"
}

return clickreturnvalue()
}

function clickreturnvalue(){
if (ie4||ns6) return false
else return true
}

function contains_ns6(a, b) {
while (b.parentNode)
if ((b = b.parentNode) == a)
return true;
return false;
}

function dynamichide(e){
if (ie4&&!dropmenuobj.contains(e.toElement))
delayhidemenu()
else if (ns6&&e.currentTarget!= e.relatedTarget&& !contains_ns6(e.currentTarget, e.relatedTarget))
delayhidemenu()
}

function hidemenu(e){
if (typeof dropmenuobj!="undefined"){
if (ie4||ns6)
dropmenuobj.style.visibility="hidden"
}
}

function delayhidemenu(){
if (ie4||ns6)
delayhide=setTimeout("hidemenu()",disappeardelay)
}

function clearhidemenu(){
if (typeof delayhide!="undefined")
clearTimeout(delayhide)
}

if (hidemenu_onclick=="yes")
document.onclick=hidemenu







