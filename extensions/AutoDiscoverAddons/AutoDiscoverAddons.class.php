<?

include "config.php";


class AutoDiscoverAddons {
        function AutoDiscoverAddons() {
        				global $btl, $defaults;
                $this->layout = new Layout();
                $this->disp="block";
                $rrd_dir=bartlby_config($btl->CFG, "performance_rrd_htdocs");
                $this->rrd_dir=bartlby_config($btl->CFG, "performance_rrd_htdocs");
								if(file_exists($rrd_dir . "/" . $defaults[service_id] . '_' .  $defaults[plugin] . '.rrd')) {
                      $this->disp="none";
                      
                }
                $pnp4_nagios=bartlby_config(getcwd() . "/ui-extra.conf", "pnp4nagios");
								if($pnp4_nagios) {
	                if(file_exists("pnp4data/" . $defaults[server_id] . '-' .  str_replace(" ", "_", $defaults[server_name]) . '/' . $defaults[service_id] . '-' . str_replace(" ", "_", $defaults[service_name]) . '.rrd')) {
	                	$this->disp="none";
	                }
	              }
        }
				function widget_do_pipe() {
						global $_GET;
						global $btl;
						//$_GET[pipe] -> TYPE (24h, all)
						$a = explode("_", $_GET[service_id]);
						
						
						if($a[0] != "servicebox") {
							return $a[0] . print_r($_GET, true);
						}
						
						$svcid=$a[1];
						$rrd_dir=$this->rrd_dir;
						$defaults[service_id]=$svcid;
						$btl->updatePerfHandler(0, $svcid);
						$all=true;
						$ww="height='190'";
						if($_GET[pipe] == "24h") {
								$all=false;
								
						}
						
						
						if($_GET[pipe] == "raw24h") {
							$all=false;
							$ww="";
						}
						
            $re .= $this->_globExt($svcid, $rrd_dir, $ww,$all);
            if($_GET[pipe] == "raw24h") {
            	return $re;            	
            }
            
            $defaults = bartlby_get_service_by_id($btl->RES, $svcid);
            
            
            $l = new Layout();
            $l->create_box("Graph of " . $defaults[server_name] . "/" . $defaults[service_name], "<div style='height: 100%; min-height:190px'><center>" . $re . "</center></div>", "extension_AutoDiscover");
						$re = $l->boxes[extension_AutoDiscover];
            
            return "" . $re . "";
						
						
						
				}
				function widget_pipe_get_size() {
					global $_GET;
					$a[width] = 4;
					$a[height] = 2;
					//$_GET[pipe] -> TYPE (24h, all)
					return $a;
				}
				function widget_pipe() {
      			$a[has_widget]=1;
      			$a[widgets][0][k]="24h Graph";
      			$a[widgets][0][v]="24h";
      			$a[widgets][1][k]="All Graphs";
      			$a[widgets][1][v]="all";
      			$a[widgets][2][k]="Raw 24h Image";
      			$a[widgets][2][v]="raw24h";
      			

      			return $a;
      	}
        function _About() {
                return "AutoDiscoverAddons Version 0.1 by h.januschka";
        }
        function _permissions() {
        	global $worker_rights;
        	$checked="";
        	if($worker_rights[ada_allowed][0] && $worker_rights[ada_allowed][0] != "false") {
        		$checked="checked";
        	}
        	
        	$r = "<input type=checkbox name='ada_allowed' $checked>allowed<br>";
        	return $r;	
        }
        /*
        function _overview() {
                return "_overview";
        }
        function _services() {
                return "_services";
        }
        function _processInfo() {
                return "_processInfo";
        }
        */
        /*
        function _serverDetail() {
                return "";
        }
        */
        function xajax_update() {
        	global $_GET, $btl;
        	$re = new XajaxResponse();
        	$rrd_dir=bartlby_config($btl->CFG, "performance_rrd_htdocs");
        	$svc_counter=bartlby_config(getcwd() . "/ui-extra.conf", "special_addon_ui_" . $svcid . "_cnt");
					if(!$svc_counter) {
						$this->images[] = '<div id="mygraph"></div><div id="mygraphPNP"></div>
															<a href="#" onClick="glb_fname_update()">Load RRD widget</A>
															';
						$this->images_labels[]="RRD Browser";
						
						
						$this->_globExt($_GET[xajaxargs][2], $rrd_dir);
						
						
						if(count($this->images) > 0)  {
							$r = $this->adaTabs();
						}
						
					
						
						$re->addAssign("ada_autorefresh", "innerHTML", $r);
							$re->addScript("$('#myTab a:first').tab('show');
														$('#myTab a').click(function (e) {
														  e.preventDefault();
														  $(this).tab('show');
														});
														$('#AutoDiscoverAddonsHide').css('display', 'none');");
														
														
					
					}
        	
        	
        	return $re;	
       }
       function getJavascripts() {
       	global $defaults;
       	$r = "<script language='JavaScript'>
        	  	
        	  	function updatePerfhandlerExt(cnt) {
        	  		as = document.getElementById('AutoDiscoverAddonsHide');
        	  		if(as.style.display == 'block') {
        	  			return;
        	  		}
				as.style.display = 'block';
				xajax_updatePerfHandler(\"xajax_ExtensionAjax('AutoDiscoverAddons', 'xajax_update', '" . $defaults[service_id] . "')\", '" . $defaults[server_id] . "','" . $defaults[service_id] . "');
        	  		
        	  	}
        	  	function glb_fname_update() {
        	  		if(typeof(fname_update) == 'function') {
        	  			fname_update();
        	  		}
        	  		if(typeof(fname_updatePNP) == 'function') {
        	  			fname_updatePNP();
        	  		}
        	  		
        	  	}
        	  	
        	  	
        	  </script>
        	  <div id=AutoDiscoverAddonsHide style='display:none'><font color='red'><img src='extensions/AutoDiscoverAddons/ajax-loader.gif'> reload in progress....</font></div><br>
        	  <div id='autodiscoveraddons_layer' style='display:" . $this->disp .  ";'>";
        	  return $r;
       }
       function endScripts() {
       	
       	$r = "</div>";
       	
       	
       	return $r;
       }
        function _globExt($svcid, $path, $width="", $all=true) {
        	  global $defaults, $xajax, $btl;
        	  $x = 0;        	  
        	  $defaults = bartlby_get_service_by_id($btl->RES, $svcid);
                foreach(glob($path . "/" . $svcid . "_*.png") as $fn) {
                				if($all == false) {
                					
                						if(!preg_match("/24h.png/", basename($fn)) ) {
                							continue;
                						} else {
                							$r .= "<img $width onClick='updatePerfhandlerExt();' id='perfh" . $x . "' src='rrd/" . basename($fn) . "?" . time() . "'><br>";
                							break;
                						}
                				}
                				$s_image ="<img $width onClick='updatePerfhandlerExt();' id='perfh" . $x . "' src='rrd/" . basename($fn) . "?" . time() . "'><br>";
                        $r .=  $s_image;
                        $this->images[] = $s_image;
                        $bn = basename($fn);
                        if(preg_match("/" . $defaults[plugin] . ".png$/i", basename($fn))) $bn = "1 hour";
                        if(preg_match("/24h.png$/i", basename($fn))) $bn = "24 hour";
                        if(preg_match("/31.png$/i", basename($fn))) $bn = "30 days";
                        if(preg_match("/365.png$/i", basename($fn))) $bn = "356 days";
                        if(preg_match("/7.png$/i", basename($fn))) $bn = "1 week";
                        	
                        $mt = date("d.m.Y H:i:s", filemtime($fn));
                        	
                        $this->images_labels[] = $bn;
                        $x++;
                } 
                
                 
                 $pnp4_nagios=bartlby_config(getcwd() . "/ui-extra.conf", "pnp4nagios");
									if($pnp4_nagios) {
														if(file_exists("pnp4data/" . $defaults[server_id] . '-' .  str_replace(" ", "_", $defaults[server_name]) . '/' . $defaults[service_id] . '-' . str_replace(" ", "_", $defaults[service_name]) . '.rrd')) {
															$t=time();
															$pnp4_hostname = $defaults[server_id] . "-" . $defaults[server_name];
															$pnp4_servicename = $defaults[service_id] . "-" .  $defaults[service_name];
															$i_start = time()-(60*60);
															$i_end = time();
															
															
															$s_image="";
															for($ploop=0; $ploop<8; $ploop++) {
																$i_url = $pnp4_nagios . "?host=" . $pnp4_hostname . "&srv=" . $pnp4_servicename . "&start=" . $i_start . "&end="  . $i_end . "&view=0&source=" . $ploop . "&cb=" . $t;
																$s_image .= "<img $width onClick='updatePerfhandlerExt();' src='" . $i_url . "' style='display:none;' onLoad='this.style.display=\"block\";'>";
																$re .= $s_image;
															}
															
															
															
															$this->images[] = $s_image;
															$this->images_labels[] = "1h";
															
															$pnp4_hostname = $defaults[server_id] . "-" . $defaults[server_name];
															$pnp4_servicename = $defaults[service_id] . "-" .  $defaults[service_name];
															$i_start = time()-86400;
															$i_end = time();

															$s_image="";
															for($ploop=0; $ploop<8; $ploop++) {
																$i_url = $pnp4_nagios . "?host=" . $pnp4_hostname . "&srv=" . $pnp4_servicename . "&start=" . $i_start . "&end="  . $i_end . "&view=0&source=" . $ploop . "&cb=" . $t;
																$s_image .= "<img  $width onClick='updatePerfhandlerExt();' src='" . $i_url . "' style='display:none;' onLoad='this.style.display=\"block\";'>";
																$tre .= $s_image;
															}
														
															$this->images[] = $s_image;
															$this->images_labels[] = "24h";
															
															if($all == false) {
																	return $tre;
																	
															}
															$re .= $tre . "<br>";
															
															
															$pnp4_hostname = $defaults[server_id] . "-" . $defaults[server_name];
															$pnp4_servicename = $defaults[service_id] . "-" .  $defaults[service_name];
															$i_start = time()-(86400*7);
															$i_end = time();
															
															
															$i_url = $pnp4_nagios . "?host=" . $pnp4_hostname . "&srv=" . $pnp4_servicename . "&start=" . $i_start . "&end="  . $i_end . "&view=0&source=0&cb=" . $t;
															$re .= "<img $width  onClick='updatePerfhandlerExt();' src='" . $i_url . "' style='display:none;' onLoad='this.style.display=\"block\";'><br>";
															
															
															$pnp4_hostname = $defaults[server_id] . "-" . $defaults[server_name];
															$pnp4_servicename = $defaults[service_id] . "-" .  $defaults[service_name];
															$i_start = time()-(86400*30);
															$i_end = time();
															
															$s_image="";
															for($ploop=0; $ploop<8; $ploop++) {
															
																$i_url = $pnp4_nagios . "?host=" . $pnp4_hostname . "&srv=" . $pnp4_servicename . "&start=" . $i_start . "&end="  . $i_end . "&view=0&source=" . $ploop . "&cb=" . $t;
																$s_image .= "<img $width  onClick='updatePerfhandlerExt();' src='" . $i_url . "' style='display:none;' onLoad='this.style.display=\"block\";'><br>";
																$re .= $s_image;
															}
															
															$this->images[] = $s_image;
															$this->images_labels[] = "30 days";
															
															$pnp4_hostname = $defaults[server_id] . "-" . $defaults[server_name];
															$pnp4_servicename = $defaults[service_id] . "-" .  $defaults[service_name];
															$i_start = time()-(86400*365);
															$i_end = time();
															
															$s_image="";
															for($ploop=0; $ploop<8; $ploop++) {
																$i_url = $pnp4_nagios . "?host=" . $pnp4_hostname . "&srv=" . $pnp4_servicename . "&start=" . $i_start . "&end="  . $i_end . "&view=0&source=" . $ploop . "&cb=" . $t;
																$s_image .= "<img $width onClick='updatePerfhandlerExt();' src='" . $i_url . "' style='display:none;' onLoad='this.style.display=\"block\";'><br>";
																$re .= $s_image;
																
															}
															$this->images[] = $s_image;
															$this->images_labels[] = "365 days";
													}
														
												}	
													$r .= $re;
                
               return $r;
        }
				function getRRDWidget() {
					global $defaults, $btl;
					
					$rrd_dir=bartlby_config($btl->CFG, "performance_rrd_htdocs");
						
						if(file_exists($rrd_dir . "/" . $defaults[service_id] . '_' .  $defaults[plugin] . '.rrd')) {
							
                        						$is_octets="false";
                        						if($defaults[plugin] == "bartlby_if") {
                        							$is_octets="true";
                        						}
																	  $re .= '<script>
																		
																		function fname_update() {
																	        fname="rrd/' .  $defaults[service_id] . '_' .  $defaults[plugin] . '.rrd";
																	        
																	        try {
																	          FetchBinaryURLAsync(fname,update_fname_handler);
																	        } catch (err) {
																	           alert("Failed loading "+fname+"\n"+err);
																	        }
																	      }
																	      
																	   function update_fname_handler(bf) {
																	          var i_rrd_data=undefined;
																	          try {
																	            var i_rrd_data=new RRDFile(bf);            
																	          } catch(err) {
																	            alert("File "+fname+" is not a valid RRD archive!\n"+err);
																	          }
																	          if (i_rrd_data!=undefined) {
																	            rrd_data=i_rrd_data;
																	            update_fname()
																	          }
																	      }
																	  
																	   function update_fname() {
																																		        
																	  			var dopts = {graph_width: "800px", graph_height: "300px", timezone: "+2", legend:"Bottom", "octets": ' . $is_octets . '};
																	        // the rrdFlot object creates and handles the graph
																	        var f=new rrdFlot("mygraph",rrd_data,null, null, dopts);
																	      }
																	      
																	      
																	      
																		
																	
																	</script>
																	';
																	$this->images[] = '<div id="mygraph"></div><div id="mygraphPNP"></div>
															<a href="#" onClick="glb_fname_update()">Load RRD widget</A>
															';
																	$this->images_labels[]="RRD Browser";
															}
                        			$pnp4_nagios=bartlby_config(getcwd() . "/ui-extra.conf", "pnp4nagios");
                        			if($pnp4_nagios) {
                        				
			                        			if(file_exists("pnp4data/" . $defaults[server_id] . '-' .  str_replace(" ", "_", $defaults[server_name]) . '/' . $defaults[service_id] . '-' . str_replace(" ", "_", $defaults[service_name]) . '.rrd')) {
			                        				
																				  $re .= '<script>
																					
																					function fname_updatePNP() {
																				        fname="pnp4data/' .  $defaults[server_id] . '-' .  str_replace(" ", "_",$defaults[server_name]) . '/' . $defaults[service_id] . '-' . str_replace(" ", "_",$defaults[service_name]) . '.rrd";
																				        
																				        try {
																				          FetchBinaryURLAsync(fname,update_fname_handlerPNP);
																				        } catch (err) {
																				           alert("Failed loading "+fname+"\n"+err);
																				        }
																				      }
																				      
																				   function update_fname_handlerPNP(bf) {
																				          var i_rrd_data=undefined;
																				          try {
																				            var i_rrd_data=new RRDFile(bf);            
																				          } catch(err) {
																				            alert("File "+fname+" is not a valid RRD archive!\n"+err);
																				          }
																				          if (i_rrd_data!=undefined) {
																				            rrd_data=i_rrd_data;
																				            update_fnamePNP()
																				          }
																				      }
																				  
																				   function update_fnamePNP() {
																																					        
																				  			var dopts = {graph_width: "800px", graph_height: "300px", timezone: "+2", legend:"Bottom"};
																				        // the rrdFlot object creates and handles the graph
																				        var f=new rrdFlot("mygraphPNP",rrd_data,null, null, dopts);
																				      }
																				      
																				      
																				      
																					
																				
																				</script>
																			';
																		
																		$this->images[] = '<div id="mygraph"></div><div id="mygraphPNP"></div>
															<a href="#" onClick="glb_fname_update()">Load RRD widget</A>
															';
																	$this->images_labels[]="RRD Browser";
																		}
																		
                        				
                        			}
                        			return $re;
				}
				function adaTabs() {
					$re = "";
																$re .= '<ul class="nav nav-tabs" id="myTab">';
                        		    for($x=0; $x<count($this->images); $x++) {
                        		    	$re .= '<li><a href="#tab' . $x . '">' . $this->images_labels[$x] . '</a></li>';
                        		    }
                         		    $re .= '</ul>';
                         		    $re .= '<div id="myTabContent" class="tab-content">';
                         		    for($x=0; $x<count($this->images); $x++) {
                         		    	$re .= '<div class="tab-pane" id="tab' . $x . '">';
                         		    	$re .= $this->images[$x];
                         		    	$re .= '</div>';
                         		    }
                         		    $re .= '</div>';
				return $re;                         		    
                         		    
				}
        function _serviceDetail() {
                global $defaults, $btl;
                if($btl->hasRight("ada_allowed", false)) {
                	$rrd_dir=bartlby_config($btl->CFG, "performance_rrd_htdocs");
                	if($rrd_dir) {
                     	   $svcid=$defaults[service_id];
                        	//see if someone has hardcoded some special_addon_stuff in ui config
                        	$svc_counter=bartlby_config(getcwd() . "/ui-extra.conf", "special_addon_ui_" . $svcid . "_cnt");
                        	if(!$svc_counter) {
                        		
                        				$re .= $this->getRRDWidget();
                        		    $re .= $this->getJavascripts();
                        		    $this->_globExt($svcid, $rrd_dir);
                        		    
                        		    
                        		    
                        		    
                        		    $re .= $this->endScripts();
                        		    
                        		    $re .= "<div id=ada_autorefresh>";
                        		    if(count($this->images) > 0) {
                        		    	$re .= $this->adaTabs();
                        		  	}
                        		  	$re .= "</div>";
                        		  	
                            	  if(count($this->images) <= 1) {
																			$re .= "<a href='#' onClick='updatePerfhandlerExt();'>Rescan for Perfhandler Data</A>";
																}
                        	}
                       
                       		
													return $re;
													
	                } else {
       	                 return "";
              	  }
		}


        }
}

?>
