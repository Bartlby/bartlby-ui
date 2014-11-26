

<div class=col-sm-12>


<div class="block-transparent">
          <div class="header">
            <h4>History</h4>
          </div>
          <ul class="timeline">
            <?
            		
            		$ad = new Audit();
            		$rResult = $ad->db->query("select * from bartlby_generic_audit where worker_id=" . (int)$_GET[worker_id] . " order by utime desc limit 0,20");
            		while($aRow = $rResult->fetch(PDO::FETCH_ASSOC)) {
                  $t = "";
                  $link = "";
                  switch($aRow[type]) {
                      case BARTLBY_AUDIT_TYPE_SERVICE:
                        $t = " Service";
                        $link = "<a href='service_detail.php?service_id=" . $aRow[object_id] . "'>" . $aRow[label] . "</A>";
                      break;
                      case BARTLBY_AUDIT_TYPE_SERVER:
                        $t = " Server";
                        $link = "<a href='server_detail.php?server_id=" . $aRow[object_id] . "'>" . $aRow[label] . "</A>";
                      break;
                      case BARTLBY_AUDIT_TYPE_WORKER:
                        $t = " Worker";
                        $link = "<a href='worker_detail.php?worker_id=" . $aRow[object_id] . "'>" . $aRow[label] . "</A>";
                      break;
                      case BARTLBY_AUDIT_TYPE_DOWNTIME:
                        $t = " Downtime";
                        $link = "" . $aRow[label] . "";
                      break;
                      case BARTLBY_AUDIT_TYPE_SERVICEGROUP:
                        $t = " Servicegroup";
                        $link = "<a href='servicegroup_detail.php?servicegroup_id=" . $aRow[object_id] . "'>" . $aRow[label] . "</A>";
                      break;
                      case BARTLBY_AUDIT_TYPE_SERVERGROUP:
                        $t = " Servergroup";
                        $link = "<a href='servergroup_detail.php?servergroup_id=" . $aRow[object_id] . "'>" . $aRow[label] . "</A>";
                      break;
                      default:
                        $t = "";
                        $link = "";
                      break;
                  }
                  $licolor="blue";
                  $fa_icon="fa-bars";
                  if(preg_match("/Edited/", $aRow[line])) {
                    $licolor="orange";
                    $fa_icon="fa-edit";
                  }
                  if(preg_match("/Deleted/", $aRow[line])) {
                    $licolor="red";
                    $fa_icon="fa-trash";
                  }
                  if(preg_match("/Added/", $aRow[line])) {
                    $licolor="green";
                    $fa_icon="fa-star";
                  }

            ?>

            <li>
              <i class="fa <?=$fa_icon?> <?=$licolor?>"></i>
              
              <div class="content">
                <p><?=$t?> <?=$link?> <?=$aRow[line]?></p>
                <small><?=date("d.m.Y H:i:s", $aRow[utime])?></small>
              </div>
            </li>

           <?
           	}
           ?>
            
          </ul>
        </div>


</div>



			
