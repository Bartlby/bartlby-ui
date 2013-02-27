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
        	
					$a[width] = 2;
					$a[height] = 1;
					return $a;
        }
        function widget_do_standalone() {
						global $_GET;
						global $btl;
						$l = new Layout();
						$r = '<table  width="100%">
						  
						    <tbody>';
						 $found=0;
						$map = $btl->GetSVCMap();
						while(list($k, $servs) = @each($map)) {
							for($x=0; $x<count($servs); $x++) {
								if($servs[$x][current_state] != 1 && $servs[$x][current_state] != 2) continue;
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
								$r .= "<a href='service_detail.php?service_id=" . $servs[$x][service_id] . "'>" . $servs[$x][server_name] . "/" . $servs[$x][service_name] . "</A>";
								$r .= "</td>";
								$r .= "<td>";
								$r .= date("d.m.Y H:i:s", $servs[$x][last_check]);
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
									</table>';
									
						$l->create_box("FailedServices", $r, "extension_FailedServices");
						$r = $l->boxes[extension_FailedServices];
						return $r;
				}
  			function widget_standalone() {
  				return "1";  				
  			}
      
       
}

?>
