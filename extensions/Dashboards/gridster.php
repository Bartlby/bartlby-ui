<?
	
	include "config.php";
	include "layout.class.php";
	include "bartlby-ui.class.php";
	
	
	
	
	
	$btl=new BartlbyUi($Bartlby_CONF);
	
	
	
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
		$servers[$optind][v]="logview";	
		$servers[$optind][k]="LogView";
		
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

$displayed_servers=0;
$btl->service_list_loop(function($svc, $shm) use(&$servers, &$optind, &$btl) {
		$state=$btl->getState($svc[current_state]);
		$servers[$optind][c]="";
		$servers[$optind][v]="servicebox_" . $svc[service_id];	
		$servers[$optind][k]=$svc[server_name] . "/" . $svc[service_name];
		$optind++;	
});				
	
			$servers[$optind][c]="";
			$servers[$optind][v]="";	
			$servers[$optind][k]="Servers";
			$servers[$optind][is_group]=1;
			$optind++;

$btl->server_list_loop(function($svc, $shm) use(&$servers, &$optind, &$btl) {
		$servers[$optind][c]="";
		$servers[$optind][v]="serverbox_" . $svc[server_id];	
		$servers[$optind][k]=$svc[server_name] ;
		
		$optind++;	
});				

			
			$servers[$optind][c]="";
			$servers[$optind][v]="";	
			$servers[$optind][k]="Server Groups";
			$servers[$optind][is_group]=1;
			$optind++;
	
$btl->servergroup_list_loop(function($svc, $shm) use(&$servers, &$optind, &$btl) {
		$servers[$optind][c]="";
		$servers[$optind][v]="servergroupbox_" . $svc[servergroup_id];	
		$servers[$optind][k]=$svc[servergroup_name] ;
		
		$optind++;	
});		
			
	
	
			$servers[$optind][c]="";
			$servers[$optind][v]="";	
			$servers[$optind][k]="Service Groups";
			$servers[$optind][is_group]=1;
			$optind++;
			
				
$btl->servicegroup_list_loop(function($svc, $shm) use(&$servers, &$optind, &$btl) {
		$servers[$optind][c]="";
		$servers[$optind][v]="servergroupbox_" . $svc[servicegroup_id];	
		$servers[$optind][k]=$svc[servicegroup_name] ;
		
		$optind++;	
});	
			
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
			$servers[$optind][v]="";	
			$servers[$optind][k]=$widget_standalones[$x][ex_name];
			$servers[$optind][is_group]=1;
			$optind++;
			for($y=0; $y<count($widget_standalones[$x][out][widgets]); $y++) {
					
					
					
					
					$servers[$optind][c]="";
					$servers[$optind][v]="extension_" . $widget_standalones[$x][ex_name] . "_" . $widget_standalones[$x][out][widgets][$y][v];
					$servers[$optind][k]=$widget_standalones[$x][ex_name] . "/" . $widget_standalones[$x][out][widgets][$y][k];
					$optind++;
				}
		
				
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
				$pipes[$optind][v]="";	
				$pipes[$optind][k]=$widget_pipes[$x][ex_name];
				$pipes[$optind][is_group]=1;
				$optind++;
				for($y=0; $y<count($widget_pipes[$x][out][widgets]); $y++) {
					
					$pipes[$optind][c]="";
					$pipes[$optind][k]=$widget_pipes[$x][ex_name] . "/" . $widget_pipes[$x][out][widgets][$y][k];
					$pipes[$optind][v]=$widget_pipes[$x][ex_name] . "_" . $widget_pipes[$x][out][widgets][$y][v];
					$optind++;
				}
		}
	
	$layout= new Layout();
	$layout->setTitle("Dashboards:");
	
	
	$layout->OUT .= "<script src='extensions/Dashboards/jquery.gridster.js'></script>
	<script src='extensions/Dashboards/jquery.gridster.extras.js'></script>
	<script src='extensions/Dashboards/jquery.coords.js'></script>
	<script src='extensions/Dashboards/jquery.draggable.js'></script>
	<script src='extensions/Dashboards/jquery.collision.js'></script>
	<script src='extensions/Dashboards/utils.js'></script>
	
	


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
.modal {
	overflow:none;
}
#myModal {
	width: 800px; /* SET THE WIDTH OF THE MODAL */

	xmargin: -250px 0 0 -350px; /* CHANGE MARGINS TO ACCOMODATE THE NEW WIDTH (original = margin: -250px 0 0 -280px;) */
	overflow:none !important;
}
#myModal .modal-body {
	height: 250px;
	overflow-y:none;
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
				
				case 'logview':
					
					$.getJSON('logview.php?bartlby_filter=@(LOG|NOT)@&json=1', function(data) {
 								if(data != null) {
 									rd = '<table width=100%>';
 									console.log(data);
 									
 									for(e in data) {
 										console.log(data[e].date);
 										rd += '<tr>';
 										rd += '<td style=\'font-size: 10px;\'>';
 										rd += data[e].date;
 										rd += '</td>';
 										
 										rd += '<td>';
 										rd += data[e].icon;
 										rd += '</td>';
 										rd += '<td style=\'font-size: 10px;\'>';
 										rd += data[e].txt;
 										rd += '</td>';
 										rd += '</tr>';
 										
 									}
 									rd += '</table>';
 									
 								} else {
 								  rd = 'NO LOG DATA FOUND';
 								}
 								$('#' + id).html('<div class=\'box\'><div class=\'box-header well\'><h2><i class=\'xicon-info-sign\'></i> LogView</h2><div class=\'box-icon\'></div></div><div class=\'box-content\' style=\'display:block; height:100%;\' >' +  rd + '<div class=\'clearfix\'></div></div></div>');
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
 								$('#system_health').css('height', '113px');
 					});
				break;
				case 'overview_tactical':
						$.getJSON('overview.php?json=1', function(data) {
 								$('#' + id).html('' + data.boxes.tactical_overview + '');
 								$('#tactical_overview').css('height', '113px');
 					});
				break;
				default:
								pipe = $('#' + id)[0].dataset.pipe;
								params = $('#' + id)[0].dataset.params;
								f=0;
								svc_type=id.split('_')[0];
								svc_id=id.split('_')[1];
								
								if(svc_type == 'servicebox') {
									if(pipe == '-1') {
											
											$.getJSON('service_detail.php?service_id=' + svc_id + '&json=1', function(data) {
											lbl='';
											if(data.SVC_DETAIL.svc_color == 'green') {
												lbl = 'label-success';
											}
												if(data.SVC_DETAIL.svc_color == 'orange') {
												lbl = 'label-warning';
											}
												if(data.SVC_DETAIL.svc_color == 'red') {
												lbl = 'label-danger';
											}
								
								
 											st='<span class=\'label ' + lbl + '\'>' + data.SVC_DETAIL.svc_state + '</span>';
 												
 												$('#' + id).html('<div class=\'box\'><div class=\'box-header well\'><h2><i class=\'xicon-info-sign\'></i> ' + data.SVC_DETAIL.server_name + '/' + data.SVC_DETAIL.service_name.substring(0,7) +  ' </h2><div class=\'box-icon\'></div></div><div class=\'box-content\' style=\'display:block; height:55px;\' >' + st + '  <a href=\'service_detail.php?service_id=' + data.SVC_DETAIL.service_id + '\'>  ' + data.SVC_DETAIL.new_server_text.substring(0,30) + '</A><div class=\'clearfix\'></div></div></div>');
 								
 											});
 									} else {
 										//pipe through ext!
 											console.log('PIPE IT');
 											ext=pipe.split('_')[0];
											pipe=pipe.split('_')[1];
											svc_id=id.split('_')[0] + '_' + id.split('_')[1];
											$.getJSON('extensions_json.php?params=' + params + '&extension=' + ext + '&service_id=' + svc_id + '&action=widget_do_pipe&pipe=' + pipe, function(data) {
 													console.log('GG:' + data);
 													$('#' + id).html(data);
 											});
 									}
 											f=1;
 								}
 								if(svc_type == 'extension') { 
 									f=1;
 									widget_sub = id.split('_')[2];
 									//svc_id=id.split('_')[0] + '_' + id.split('_')[1];
 									$.getJSON('extensions_json.php?params=' + params + '&extension=' + svc_id + '&action=widget_do_standalone&pipe=' + widget_sub, function(data) {
 													console.log('GG:' + data);
 													$('#' + id).html(data);
 									});
 								}
 								
 								if(svc_type == 'servicegroupbox' || svc_type == 'servergroupbox' || svc_type == 'serverbox') {
									if(pipe != '-1') {
											//pipe through ext!
 											console.log('PIPE IT');
 											ext=pipe.split('_')[0];
											pipe=pipe.split('_')[1];
											svc_id=id.split('_')[0] + '_' + id.split('_')[1];
											$.getJSON('extensions_json.php?params=' + params + '&extension=' + ext + '&service_id=' + svc_id + '&action=widget_do_pipe&pipe=' + pipe, function(data) {
 													console.log('GG:' + data);
 													$('#' + id).html(data);
 											});
 											f=1;
 									}
 											
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
	       	 '<div style=\'overflow:auto\' data-params=\'' + json[i]['params'] + '\' data-pipe=\'' + json[i]['pipe'] + '\' data-rel=\'widget\' id=\"' + json[i]['id'] + '\"><img src=\'extensions/AutoDiscoverAddons/ajax-loader.gif\'></div>', 
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
        widget_margins: [2, 0],
          serialize_params: function(w, wgd) { 
          console.log(wgd.el[0].dataset.pipe);
        		return { 
	            id: wgd.el[0].id, 
	            col: wgd.col, 
	            row: wgd.row,
	            size_y: wgd.size_y,
	            size_x: wgd.size_x,
	            pipe: wgd.el[0].dataset.pipe,
	            params: wgd.el[0].dataset.params
        	} 
    		},
        widget_base_dimensions: [100, 135],
        max_size_x: 50,
        max_size_y: 50
      
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
 							//$(this).html('<img src=\'extensions/AutoDiscoverAddons/ajax-loader.gif\'>');
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
 		
 		$('#clear_dashboard').click(function() {
 			$('[data-rel=\"widget\"]').each(function() {
 				 			grid.remove_widget($(this));
 				 			
 			});
 		});
 		$('#btn_add_widget_done').click(function(e) {
 			e.preventDefault();
			$('#myModal').modal('hide');
			id=$('#widget_type').val();
			pipe=$('#widget_pipe').val();
			params=$('#widget_params').val();
			if(pipe != '-1') {
				id = id + '_' + pipe;
				pipe_str=pipe;
				ext=pipe.split('_')[0];
				pipe=pipe.split('_')[1];
			}
			if(params != '') {
						id = id + '_' + hashCode(params);	
			}
			do_not_add=0;
			
			switch(id) {
				case 'overview_core':
					
					w=6;
					h=2;
					
					
				break;
				case 'logview':
					
					w=4;
					h=2;
					
					
				break;
				case 'overview_health':
					w=4;
					h=1;
				break;
				case 'overview_servergroups':
					w=6;
					h=2;
				break;
				case 'overview_servicegroups':
					w=6;
					h=2;
				break;
				case 'overview_tactical':
						w=6;
						h=1;
				break;
				default:
					f=0;
					w=2;
					h=1;
					
					svc_type=id.split('_')[0];
					if(svc_type == 'servicebox') {
							f=1;
							
							w=2;
							h=1;
							if(pipe != '-1') {
								svc_id=id.split('_')[0] + '_' +  id.split('_')[1];
								$.getJSON('extensions_json.php?params=' + params + '&extension=' + ext + '&service_id=' + svc_id + '&action=widget_pipe_get_size&pipe=' + pipe, function(data) {
 									w=data.width;
 									h=data.height;
 									
 									grid.add_widget('<div style=\'overflow:auto\' data-params=\'' + params + '\' data-pipe=\'' +  pipe_str + '\' data-rel=\'widget\' id=\'' +  id + '\'><img src=\'extensions/AutoDiscoverAddons/ajax-loader.gif\'></div>', w,h,1,1);
									loadWidget(id);
 								});
 								do_not_add=1;
							}
							console.log('W:' + w + ' H:' + h);
							
							
							
					}
					if(svc_type == 'extension') {
						f=1;
						ext=id.split('_')[1];
						widget_sub=id.split('_')[2];
						$.getJSON('extensions_json.php?params=' + params + '&extension=' + ext + '&action=widget_standalone_size&pipe=' + widget_sub, function(data) {
 									w=data.width;
 									pipe='-1';
 									h=data.height;
 									
 									grid.add_widget('<div style=\'overflow:auto\' data-params=\'' + params + '\' data-pipe=\'' +  pipe + '\' data-rel=\'widget\' id=\'' +  id + '\'><img src=\'extensions/AutoDiscoverAddons/ajax-loader.gif\'></div>', w,h,1,1);
									loadWidget(id);
 								});
 								do_not_add=1;
						
					}
					
					
					if(svc_type == 'servicegroupbox') {
							f=1;
							svc_id=id.split('_')[1];
							w=2;
							h=2;
							
							if(pipe != '-1') {
								svc_id=id.split('_')[0] + '_' +  id.split('_')[1];
								$.getJSON('extensions_json.php?params=' + params + '&extension=' + ext + '&service_id=' + svc_id + '&action=widget_pipe_get_size&pipe=' + pipe, function(data) {
 									w=data.width;
 									h=data.height;
 									
 									grid.add_widget('<div style=\'overflow:auto\' data-params=\'' + params + '\' data-pipe=\'' +  pipe_str + '\' data-rel=\'widget\' id=\'' +  id + '\'><img src=\'extensions/AutoDiscoverAddons/ajax-loader.gif\'></div>', w,h,1,1);
									loadWidget(id);
 								});
 								do_not_add=1;
							}
							
					}
					if(svc_type == 'servergroupbox') {
							f=1;
							svc_id=id.split('_')[1];
							w=2;
							h=2;
							if(pipe != '-1') {
								svc_id=id.split('_')[0] + '_' +  id.split('_')[1];
								$.getJSON('extensions_json.php?params=' + params + '&extension=' + ext + '&service_id=' + svc_id + '&action=widget_pipe_get_size&pipe=' + pipe, function(data) {
 									w=data.width;
 									h=data.height;
 									
 									grid.add_widget('<div style=\'overflow:auto\' data-params=\'' + params + '\' data-pipe=\'' +  pipe_str + '\' data-rel=\'widget\' id=\'' +  id + '\'><img src=\'extensions/AutoDiscoverAddons/ajax-loader.gif\'></div>', w,h,1,1);
									loadWidget(id);
 								});
 								do_not_add=1;
							}
					}
					if(svc_type == 'serverbox') {
							f=1;
							svc_id=id.split('_')[1];
							w=2;
							h=2;
							if(pipe != '-1') {
								svc_id=id.split('_')[0] + '_' +  id.split('_')[1];
								$.getJSON('extensions_json.php?params=' + params + '&extension=' + ext + '&service_id=' + svc_id + '&action=widget_pipe_get_size&pipe=' + pipe, function(data) {
 									w=data.width;
 									h=data.height;
 									
 									grid.add_widget('<div style=\'overflow:auto\' data-params=\'' + params + '\' data-pipe=\'' +  pipe_str + '\' data-rel=\'widget\' id=\'' +  id + '\'><img src=\'extensions/AutoDiscoverAddons/ajax-loader.gif\'></div>', w,h,1,1);
									loadWidget(id);
 								});
 								do_not_add=1;
							}
					}
					
				
					
				break;
				
			
			}
			
			if(do_not_add == 0) {
				console.log('W:' + w + ' H:' +h);
				grid.add_widget('<div style=\'overflow:auto\' data-params=\'' + params + '\' data-pipe=\'' +  pipe + '\' data-rel=\'widget\' id=\'' +  id + '\'><img src=\'extensions/AutoDiscoverAddons/ajax-loader.gif\'></div>', w,h,1,1);
				loadWidget(id);
			}
			
			$('[data-rel=\"widget\"]').each(function() {
 				 			$(this).dblclick(function() {
 				 				grid.remove_widget($(this));
 				 			});
 			});
 			
			
 		});
 		
 		

 
});
hashCode = function(str){
    var hash = 0;
    if (str.length == 0) return hash;
    for (i = 0; i < str.length; i++) {
        char = str.charCodeAt(i);
        hash = ((hash<<5)-hash)+char;
        hash = hash & hash; // Convert to 32bit integer
    }
    return hash;
}

	</script>
";
	$layout->OUT .= '<button id="add_widget" class="btn btn-small btn-default">add widget</button>&nbsp;';
	$layout->OUT .= '<button id="save_dashboard" class="btn btn-small btn-default">Save Dashboard</button>&nbsp;';
	$layout->OUT .= '<button id="clear_dashboard" class="btn btn-small btn-default">Clear Dashboard</button>&nbsp;';
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
					<tr>
					<td>Widget Params</td>
					<td>
					' . $layout->Field("widget_params", "") . '					
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

