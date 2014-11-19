

<div class=col-sm-12>


<div class="block-transparent">
          <div class="header">
            <h4>History</h4>
          </div>
          <ul class="timeline">
            <?
            		
            		$ad = new Audit();
            		$rResult = $ad->db->query("select * from bartlby_object_audit where worker_id=1");
            		while($aRow = $rResult->fetch(PDO::FETCH_ASSOC)) {
            ?>

            <li>
              <i class="fa fa-edit red"></i>
              
              <div class="content">
                <p>Modfied <a href=''><?=$aRow[label]?></a></p>
                <small><?=date("d.m.Y H:i:s", $aRow[utime])?></small>
              </div>
            </li>

           <?
           	}
           ?>
            
          </ul>
        </div>


</div>



			