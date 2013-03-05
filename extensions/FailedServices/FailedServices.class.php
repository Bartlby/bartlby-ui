<?

include "config.php";
include_once "bartlbystorage.class.php";

class FailedServices {
        function FailedServices() {
                $this->layout = new Layout();
                $this->storage=new bartlbyStorage("FailedServices");

        }


        function _About() {
                return "FailedServices Version 0.1 by h.januschka";
        }
        function widget_standalone_size() {
        	global $_GET;
        	//$_GET[pipe] = type (errors, all)
					$a[width] = 4;
					$a[height] = 2;
					return $a;
        }
        function widget_do_standalone() {
						global $_GET;
						global $btl;
						
						//$_GET[pipe] = type (errors, all)
						$l = new Layout();
						$r = '<div style="height: 100%; min-height:190px"><table  width="100%">
						  
						    <tbody>';
						 $found=0;
						$map = $btl->GetSVCMap();
						while(list($k, $servs) = @each($map)) {
							for($x=0; $x<count($servs); $x++) {
								if($_GET[pipe] != "all") {
									if($servs[$x][current_state] == 0 || $servs[$x][current_state] == 4) continue;
								}
								$found++;
								$svc_color=$btl->getColor($servs[$x][current_state]);
								$svc_state=$btl->getState($servs[$x][current_state]);
									$lbl = "label-default";
									if($svc_color == "green") {
											$lbl = "label-success";
									}
								
									if($svc_color == "orange") {
											$lbl = "label-warning";
									}
									if($svc_color == "red") {
											$lbl = "label-important";
									}
								$r .= "<tr >";
								$r .= "<td>";
								$r .= "<a href='service_detail.php?service_id=" . $servs[$x][service_id] . "' >" . substr($servs[$x][server_name] . "/" . $servs[$x][service_name],0, 45) . "</A>";
								$r .= "</td>";
								$r .= "<td>";
								$r .= date("H:i:s", $servs[$x][last_check]);
								$r .= "</td>";
								
								$r .= "<td>";
								$r .= "<span class='label " . $lbl .  "'>" . $svc_state . "</span>";
								$r .= "</td>";
								$r .= "</tr>";
							}
						}
						if($found == 0) {
								$r .= "<tr><td colspan=3>No Warn/Crit found</td></tr>";
						}
						$r .= '</tbody>
									</table></div>';
						
						
									
						$l->create_box("FailedServices", $r, "extension_FailedServices");
						$r = $l->boxes[extension_FailedServices];
						return $r;
				}
  			function widget_standalone() {
  				$a[widgets][0][k]="Only Errors";
      		$a[widgets][0][v]="errors";
      		$a[widgets][1][k]="All";
      		$a[widgets][1][v]="all";
  				return $a;
  			}
      
       
}

?>
