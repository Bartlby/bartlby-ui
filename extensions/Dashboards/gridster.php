<?
	
	include "config.php";
	include "layout.class.php";
	include "bartlby-ui.class.php";
	
	
	
	
	
	$btl=new BartlbyUi($Bartlby_CONF);
	
	
	$map = $btl->GetSVCMap();
$optind=0;


			$servers[$optind][c]="";
			$servers[$optind][v]="";	
			$servers[$optind][k]="Core Info";
			$servers[$optind][is_group]=1;
			$optind++;
			
			
		$servers[$optind][c]="";
		$servers[$optind][v]="overview_tactical";	
		$servers[$optind][k]="Tactical Overview";
		
		$optind++;
		$servers[$optind][c]="";
		$servers[$optind][v]="overview_health";	
		$servers[$optind][k]="System Health";
		
		$optind++;
		$servers[$optind][c]="";
		$servers[$optind][v]="overview_core";	
		$servers[$optind][k]="Core Info";
		
		$optind++;
		$servers[$optind][c]="";
		$servers[$optind][v]="overview_servergroups";	
		$servers[$optind][k]="ServerGroups";
		
		$optind++;
		$servers[$optind][c]="";
		$servers[$optind][v]="overview_servicegroups";	
		$servers[$optind][k]="ServiceGroups";
		
		$optind++;

			$servers[$optind][c]="";
			$servers[$optind][v]="";	
			$servers[$optind][k]="Services";
			$servers[$optind][is_group]=1;
			$optind++;

while(list($k, $servs) = @each($map)) {
	$displayed_servers++;
	
	for($x=0; $x<count($servs); $x++) {
		//$v1=bartlby_get_service_by_id($btl->CFG, $servs[$x][service_id]);
		
		if($x == 0) {
			//$isup=$btl->isServerUp($v1[server_id]);
			//if($isup == 1 ) { $isup="UP"; } else { $isup="DOWN"; }
		
		} else {
			
		}
		$state=$btl->getState($servs[$x][current_state]);
		$servers[$optind][c]="";
		$servers[$optind][v]="servicebox_" . $servs[$x][service_id];	
		$servers[$optind][k]=$servs[$x][server_name] . "/" . $servs[$x][service_name];
		
		$optind++;
	}
}
	
			$servers[$optind][c]="";
			$servers[$optind][v]="";	
			$servers[$optind][k]="Server Groups";
			$servers[$optind][is_group]=1;
			$optind++;
	
			$servs=$btl->GetServerGroups();
			
	
	
			for($x=0; $x<count($servs); $x++ ) {
				$servers[$optind][c]="";
				$servers[$optind][k]=$servs[$x][servergroup_name];	
				$servers[$optind][v]="servergroupbox_" . $servs[$x][servergroup_id];
				$optind++;
			}
			
	
	
			$servers[$optind][c]="";
			$servers[$optind][v]="";	
			$servers[$optind][k]="Service Groups";
			$servers[$optind][is_group]=1;
			$optind++;
			
			$servs=$btl->GetServiceGroups();
			
	
	
			for($x=0; $x<count($servs); $x++ ) {
				$servers[$optind][c]="";
				$servers[$optind][k]=$servs[$x][servicegroup_name];	
				$servers[$optind][v]="servicegroupbox_" . $servs[$x][servicegroup_id];
				$optind++;
			}
			
	//Widget Pipes
	$el = new Layout();
	
	$servers[$optind][c]="";
	$servers[$optind][v]="";	
	$servers[$optind][k]="Extensions";
	$servers[$optind][is_group]=1;
	$optind++;
	
	$widget_standalones = $btl->getExtensionsReturn("widget_standalone", $el);
	
	for($x=0; $x<count($widget_standalones); $x++ ) {
				$servers[$optind][c]="";
				$servers[$optind][v]="extension_" . $widget_standalones[$x][ex_name];
				$servers[$optind][k]=$widget_standalones[$x][ex_name];
				$optind++;
	}
	
	
	
	$widget_pipes = $btl->getExtensionsReturn("widget_pipe", $el);
	$optind=0;
	$pipes[$optind][c]="";
	$pipes[$optind][s]=1;
	$pipes[$optind][k]="-none-";
	$pipes[$optind][v]=-1;
	$optind++;
	for($x=0; $x<count($widget_pipes); $x++ ) {
				$pipes[$optind][c]="";
				$pipes[$optind][k]=$widget_pipes[$x][ex_name];
				$pipes[$optind][v]=$widget_pipes[$x][ex_name];
				$optind++;
		}
	
	$layout= new Layout();
	$layout->setTitle("Dashboards:");
	
	
	$layout->OUT .= "<script src='https://raw.github.com/ducksboard/gridster.js/master/dist/jquery.gridster.js'></script>

<style>
/*! gridster.js - v0.1.0 - 2012-10-20
* http://gridster.net/
* Copyright (c) 2012 ducksboard; Licensed MIT */

.gridster {
    position:relative;
}

.gridster > * {
    margin: 0 auto;
    -webkit-transition: height .4s;
    -moz-transition: height .4s;
    -o-transition: height .4s;
    -ms-transition: height .4s;
    transition: height .4s;
}

.gridster .gs_w{
    z-index: 2;
    position: absolute;
}

.ready .gs_w:not(.preview-holder) {
    -webkit-transition: opacity .3s, left .3s, top .3s;
    -moz-transition: opacity .3s, left .3s, top .3s;
    -o-transition: opacity .3s, left .3s, top .3s;
    transition: opacity .3s, left .3s, top .3s;
}

.ready .gs_w:not(.preview-holder) {
    -webkit-transition: opacity .3s, left .3s, top .3s, width .3s, height .3s;
    -moz-transition: opacity .3s, left .3s, top .3s, width .3s, height .3s;
    -o-transition: opacity .3s, left .3s, top .3s, width .3s, height .3s;
    transition: opacity .3s, left .3s, top .3s, width .3s, height .3s;
}

.gridster .preview-holder {
    z-index: 1;
    position: absolute;
    background-color: #fff;
    border-color: #fff;
    opacity: 0.3;
}

.gridster .player-revert {
    z-index: 10!important;
    -webkit-transition: left .3s, top .3s!important;
    -moz-transition: left .3s, top .3s!important;
    -o-transition: left .3s, top .3s!important;
    transition:  left .3s, top .3s!important;
}

.gridster .dragging {
    z-index: 10!important;
    -webkit-transition: all 0s !important;
    -moz-transition: all 0s !important;
    -o-transition: all 0s !important;
    transition: all 0s !important;
}

/* Uncomment this if you set helper : 'clone' in draggable options */
/*.gridster .player {
  opacity:0;
}*/	

ul.gridst
{
    list-style-type: none;
   
}
li.gridst {
	background-color: grey;
}
#myModal {
	width: 800px; /* SET THE WIDTH OF THE MODAL */

	margin: -250px 0 0 -350px; /* CHANGE MARGINS TO ACCOMODATE THE NEW WIDTH (original = margin: -250px 0 0 -280px;) */
}
#myModal .modal-body {
	height: 250px;
}
</style>
<script>
	var grid='';
	var auto_reload=0;
	function loadWidget(id) {
		switch(id) {
				case 'overview_core':
					
					$.getJSON('overview.php?json=1', function(data) {
 								$('#' + id).html('' + data.boxes.core_info + '');
 					});
					
					
				break;
				case 'overview_servergroups':
					
					$.getJSON('overview.php?json=1', function(data) {
 								$('#' + id).html('' + data.boxes.server_groups + '');
 					});
					
					
				break;
				case 'overview_servicegroups':
					
					$.getJSON('overview.php?json=1', function(data) {
 								$('#' + id).html('' + data.boxes.service_groups + '');
 					});
					
					
				break;
				case 'overview_health':
						$.getJSON('overview.php?json=1', function(data) {
 								$('#' + id).html('' + data.boxes.system_health + '');
 					});
				break;
				case 'overview_tactical':
						$.getJSON('overview.php?json=1', function(data) {
 								$('#' + id).html('' + data.boxes.tactical_overview + '');
 					});
				break;
				default:
								pipe = $('#' + id)[0].dataset.pipe;
								f=0;
								svc_type=id.split('_')[0];
								svc_id=id.split('_')[1];
								
								if(svc_type == 'servicebox') {
									if(pipe == '-1') {
											$.getJSON('service_detail.php?service_id=' + svc_id + '&json=1', function(data) {
 												$('#' + id).html('<div class=\'box\'><div class=\'box-header well\'><h2><i class=\'xicon-info-sign\'></i> ' + data.SVC_DETAIL.server_name + '/' + data.SVC_DETAIL.service_name.substring(0,7) +  ' </h2><div class=\'box-icon\'><a href=\'#\' class=\'btn btn-minimize btn-round\'><i class=\'icon-chevron-up\'></i></a></div></div><div class=\'box-content\' style=\'display:block\' ><font color=\'' +  data.SVC_DETAIL.svc_color  + '\'>' + data.SVC_DETAIL.svc_state + '</font>- <a href=\'service_detail.php?service_id=' + data.SVC_DETAIL.service_id + '\'>  ' + data.SVC_DETAIL.new_server_text.substring(0,30) + '</A><br>' + data.SVC_DETAIL.svc_options  + '<div class=\'clearfix\'></div></div></div>');
 								
 											});
 									} else {
 										//pipe through ext!
 											console.log('PIPE IT');
											$.getJSON('extensions_json.php?extension=' + pipe + '&service_id=' + svc_id + '&action=widget_do_pipe', function(data) {
 													console.log('GG:' + data);
 													$('#' + id).html(data);
 											});
 									}
 											f=1;
 								}
 								if(svc_type == 'extension') { 
 									f=1;
 									$.getJSON('extensions_json.php?extension=' + svc_id + '&action=widget_do_standalone', function(data) {
 													console.log('GG:' + data);
 													$('#' + id).html(data);
 									});
 								}
 								if(f==0) {
 									$('#' + id).html('TYPE not defined ->' + svc_type + '-> ' + pipe);
 								}
 								
 								
								console.log('GOT1: ' + id);	
				break;
				
			
			}
	}
	$.fn.chosenDestroy = function () {
		$(this).show().removeClass('chzn-done')
		$(this).next().remove()
		
		  return $(this);
	}
	function autoReloader() {
		console.log('RELOADER: ' + auto_reload);
		window.clearTimeout();
		$('#reload_dashboard').click();
		if(auto_reload==1) {
			window.setTimeout('autoReloader()', 30000);
		}
	}
	function loadDashboardLocal(in_data) {
		console.log('LOAD:' + in_data);			
		json = JSON.parse(in_data);
		for(i=0; i<json.length; i++) {
	   	 grid.add_widget(
	       	 '<div style=\'overflow:auto\' data-pipe=\'' + json[i]['pipe'] + '\' data-rel=\'widget\' id=\"' + json[i]['id'] + '\"></div>', 
	        json[i]['size_x'], 
	        json[i]['size_y'], 
	        json[i]['col'], 
	        json[i]['row'] 
	    );
	    loadWidget(json[i]['id']);
	    $('[data-rel=\"widget\"]').each(function() {
 				 			$(this).dblclick(function() {
 				 				grid.remove_widget($(this));
 				 			});
 			});
		}
	}
	$(function(){ //DOM Ready
   grid = $('.gridster ul').gridster({
        widget_margins: [5, 0],
          serialize_params: function(w, wgd) { 
          console.log(wgd.el[0].dataset.pipe);
        		return { 
	            id: wgd.el[0].id, 
	            col: wgd.col, 
	            row: wgd.row,
	            size_y: wgd.size_y,
	            size_x: wgd.size_x,
	            pipe: wgd.el[0].dataset.pipe
        	} 
    		},
        widget_base_dimensions: [250, 120]
      
    }).data('gridster');
    
    
    xajax_ExtensionAjax('Dashboards', 'loadDashboard');
    

 		$('#add_widget').click(function(e) {
 			e.preventDefault();
 			$('#widget_type').css(\"width\", \"400px\");
 			$('#widget_type').chosenDestroy();
 			$('#widget_type').chosen({ search_contains: true });
 			
 			
 			$('#widget_pipe').css(\"width\", \"400px\");
 			$('#widget_pipe').chosenDestroy();
 			$('#widget_pipe').chosen({ search_contains: true });
 			
			
			$('#myModal').modal('show');
			
			
 		});
 		$('#reload_dashboard').click(function(e) {
 			e.preventDefault();
 			$('[data-rel=\"widget\"]').each(function() {
 				 			loadWidget($(this)[0].id);
 			});
			
			
 		});
 		
 		
 		
 		$('#save_dashboard').click(function(e) {
 			e.preventDefault();
			ser = grid.serialize();
			console.log('save: ' + JSON.stringify(ser));
			xajax_ExtensionAjax('Dashboards', 'storeDashboard', '' + JSON.stringify(ser) + '');
 		});
 		$('#auto_reload').click(function() {
 			
 			if($('#auto_reload').prop('checked')) auto_reload=1;
 			if(!$('#auto_reload').prop('checked')) auto_reload=0;
 			
 			window.clearTimeout();
 			console.log('auto reload click' + $('#auto_reload').prop('checked'));
 			if(auto_reload == 1) {
 				window.setTimeout('autoReloader()', 30000);
 			}
 		});
 		
 		
 		$('#btn_add_widget_done').click(function(e) {
 			e.preventDefault();
			$('#myModal').modal('hide');
			id=$('#widget_type').val();
			pipe=$('#widget_pipe').val();
			if(pipe != '-1') {
				id = id + '_' + pipe;
			}
			do_not_add=0;
			
			switch(id) {
				case 'overview_core':
					
					w=3;
					h=2;
					
					
				break;
				case 'overview_health':
					w=3;
					h=1;
				break;
				case 'overview_servergroups':
					w=3;
					h=2;
				break;
				case 'overview_servicegroups':
					w=3;
					h=2;
				break;
				case 'overview_tactical':
						w=3;
						h=1;
				break;
				default:
					f=0;
					w=1;
					h=1;
					
					svc_type=id.split('_')[0];
					if(svc_type == 'servicebox') {
							f=1;
							svc_id=id.split('_')[1];
							w=1;
							h=1;
							if(pipe != '-1') {
								$.getJSON('extensions_json.php?extension=' + pipe + '&service_id=' + svc_id + '&action=widget_pipe_get_size', function(data) {
 									w=data.width;
 									h=data.height;
 									
 									grid.add_widget('<div style=\'overflow:auto\' data-pipe=\'' +  pipe + '\' data-rel=\'widget\' id=\'' +  id + '\'>asd</div>', w,h,1,1);
									loadWidget(id);
 								});
 								do_not_add=1;
							}
							console.log('W:' + w + ' H:' + h);
							
							
							
					}
					if(svc_type == 'extension') {
						f=1;
						ext=id.split('_')[1];
						
						$.getJSON('extensions_json.php?extension=' + ext + '&action=widget_standalone_size', function(data) {
 									w=data.width;
 									pipe='-1';
 									h=data.height;
 									
 									grid.add_widget('<div style=\'overflow:auto\' data-pipe=\'' +  pipe + '\' data-rel=\'widget\' id=\'' +  id + '\'>asd</div>', w,h,1,1);
									loadWidget(id);
 								});
 								do_not_add=1;
						
					}
					
					
					if(svc_type == 'servicegroupbox') {
							f=1;
							svc_id=id.split('_')[1];
							w=2;
							h=2;
					}
					if(svc_type == 'servergroupbox') {
							f=1;
							svc_id=id.split('_')[1];
							w=2;
							h=2;
					}
					
				
					
				break;
				
			
			}
			
			if(do_not_add == 0) {
				grid.add_widget('<div style=\'overflow:auto\' data-pipe=\'' +  pipe + '\' data-rel=\'widget\' id=\'' +  id + '\'>asd</div>', w,h,1,1);
				loadWidget(id);
			}
			
			$('[data-rel=\"widget\"]').each(function() {
 				 			$(this).dblclick(function() {
 				 				grid.remove_widget($(this));
 				 			});
 			});
 			
			
 		});
 		
 		

 
});
	</script>
";
	$layout->OUT .= '<button id="add_widget" class="btn btn-small btn-default">add widget</button>&nbsp;';
	$layout->OUT .= '<button id="save_dashboard" class="btn btn-small btn-default">Save Dashboard</button>&nbsp;';
	$layout->OUT .= '<button id="reload_dashboard" class="btn btn-small btn-default">Reload</button>&nbsp;';
	$layout->OUT .= '<input data-no-uniform="true" id="auto_reload" type="checkbox" > Auto Reload';
	$layout->OUT .= '<div class="gridster">
    <ul class=gridst>
    		
    </ul>
</div>
			<div class="modal hide fade" id="myModal" >
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">x</button>
				<h3>Add Widget</h3>
			</div>
			<div class="modal-body">
				<table width=100% border=0>
					<tr>
					<td>Type:</td>
					<td>
					' . $layout->DropDown("widget_type", $servers,"","",false) . '					
					</td>
					</tr>
					<tr>
					<td>Extension Pipe:</td>
					<td>
					' . $layout->DropDown("widget_pipe", $pipes,"","",false) . '					
					</td>
					</tr>
					
				</table>
			</div>
			<div class="modal-footer">
				<a href="#" class="btn" data-dismiss="modal">Close</a>
				<a href="#" id=btn_add_widget_done class="btn btn-primary">Save changes</a>
			</div>
		</div>

';

	
	$layout->display();
	
	
?>

