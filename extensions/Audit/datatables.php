<?
	include "config.php";
	include "layout.class.php";
	include "bartlby-ui.class.php";
	
	include "extensions/Audit/Audit.class.php";
	
	$btl=new BartlbyUi($Bartlby_CONF);
	$btl->hasRight("audit");
	$ad = new Audit();
	

	 
	ini_set('display_errors', '1');
	error_reporting(E_ERROR);


	 try {
    // Set default timezone
    date_default_timezone_set('Europe/Rome');
     
    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
     * Easy set variables
     */
     
    /* Array of database columns which should be read and sent back to DataTables. Use a space where
     * you want to insert a non-database field (for example a counter or static image)
     */
    $aColumns = array( 'utime', 'worker_id', 'action', 'type', 'prev_object', 'id', 'object_id', 'label');
     
    /* Indexed column (used for fast and accurate table cardinality) */
    $sIndexColumn = "id";
     
    /* DB table to use */
    $sTable = "bartlby_object_audit";
     
    /* Database connection information */
    $gaSql['path']       = $ad->db->path;
    $file_db  = null;
  
    // Create (connect to) SQLite database in file
    $file_db = new PDO('sqlite:'.$gaSql['path']);
    // Set errormode to exceptions
    $file_db->setAttribute(PDO::ATTR_ERRMODE,
                            PDO::ERRMODE_EXCEPTION);
    /*
     * Paging
     */
    $sLimit = "";
    if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' )
    {
      $sLimit = "LIMIT ".intval( $_GET['iDisplayStart'] ).", ".
        intval( $_GET['iDisplayLength'] );
    }
     
     
    /*
     * Ordering
     */
    $sOrder = "";
    if ( isset( $_GET['iSortCol_0'] ) )
    {
      $sOrder = "ORDER BY  ";
      for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
      {
        if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
        {
          $sOrder .= "`".$aColumns[ intval( $_GET['iSortCol_'.$i] ) ]."` ".
            ($_GET['sSortDir_'.$i]==='asc' ? 'asc' : 'desc') .", ";
        }
      }
     
      $sOrder = substr_replace( $sOrder, "", -2 );
      if ( $sOrder == "ORDER BY" )
      {
        $sOrder = "";
      }
    }
     

     
    /*
     * Filtering
     * NOTE this does not match the built-in DataTables filtering which does it
     * word by word on any field. It's possible to do here, but concerned about efficiency
     * on very large tables
     */
    $sWhere = "";
    if ( isset($_GET['sSearch']) && $_GET['sSearch'] != "" )
    {
      $sWhere = "WHERE (";
      for ( $i=0 ; $i<count($aColumns) ; $i++ )
      {
        $sWhere .= "`".$aColumns[$i]."` LIKE ".$file_db->quote( '%'. $_GET['sSearch'] . '%' )." OR ";
      }
      $sWhere = substr_replace( $sWhere, "", -3 );
      $sWhere .= ')';
    }
    if((int)$_GET[id]) {
    	$sWhere = "WHERE object_id = " . (int)$_GET[id] . " and type =" . (int)$_GET[type] . " ";   
    }
    if((int)$_GET[force_id]) {
      $sWhere = "WHERE id = " . (int)$_GET[force_id]; 
    }
    /* Individual column filtering */
    for ( $i=0 ; $i<count($aColumns) ; $i++ )
    {
      if ( isset($_GET['bSearchable_'.$i]) && $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' )
      {
        if ( $sWhere == "" )
        {
          $sWhere = "WHERE ";
        }
        else
        {
          $sWhere .= " AND ";
        }
        $sWhere .= "`".$aColumns[$i]."` LIKE ".$file_db->quote( '%'. $_GET['sSearch_'.$i] . '%' )." ";
      }
    }
      
     //$sWhere = " object_id = " . (int)$_GET[id] . " and type =" . (int)$_GET[type] . " ";   

     //echo $sWhere;
    /*
     * SQL queries
     * Get data to display
     */
      
    /* Data set length after filtering */   
    $sQuery = "
      SELECT COUNT(`".$sIndexColumn."`) AS counter
      FROM   $sTable
      $sWhere
      $sOrder
      ";
    $rResult = $file_db->query($sQuery)->fetch(PDO::FETCH_ASSOC);
    $iFilteredTotal = $rResult['counter'];
     
     
    /* Total data set length */
    $sQuery = "
      SELECT COUNT(`".$sIndexColumn."`) AS counter
      FROM   $sTable";
    $rResult = $file_db->query($sQuery)->fetch(PDO::FETCH_ASSOC);
    $iTotal = $rResult['counter'];
     
    /*
     * Output
     */
    $output = array(
      "sEcho" => intval(@$_GET['sEcho']),
      "iTotalRecords" => $iTotal,
      "iTotalDisplayRecords" => $iFilteredTotal,
      "aaData" => array()
    );
     
    /* Data */
    $sQuery = "
      SELECT ".str_replace(" , ", " ", implode(", ", $aColumns))."
      FROM   $sTable
      $sWhere
      $sOrder
      $sLimit";
     


     
    $rResult = $file_db->query($sQuery);
    while ( $aRow = $rResult->fetch(PDO::FETCH_ASSOC) )
    {
      $row = array();
      $row[] = date("d.m.Y H:i:s", $aRow["utime"]);
      $wrk_out="";
      $id=$aRow["worker_id"];
      $btl->worker_list_loop(function($wrk, $shm) use (&$wrk_out, $id) {
        if($wrk[worker_id] == $id) {
          $wrk_out = $wrk[name];
                  
          return LOOP_BREAK;
       }
      });
      $row[] = $wrk_out;



      $readable_action="UNKW(" . $aRow["action"] . ")";
      switch( $aRow["action"]) {
        case BARTLBY_AUDIT_ACTION_ADD:
          $readable_action="<span class='label label-primary'>ADD</span>";
        break;
        case BARTLBY_AUDIT_ACTION_DELETE:
          $readable_action="<span class='label label-danger'>DELETED</span>";
           
        break;
        case BARTLBY_AUDIT_ACTION_MODIFY:
          $readable_action="<span class='label label-default'>MODIFIED</span>";
          
        break;
      }
      $row[] = $readable_action;



      

      

  
      if($_GET[force_id]) {
        $ad->db=null;
        $rResult->closeCursor();
        switch($aRow["type"]) {
          case BARTLBY_AUDIT_TYPE_SERVICE:
          $re = bartlby_get_service_by_id($btl->RES, $aRow["object_id"]);
          if((int)$_GET[recover_id]>0) {
              if($re) {
                bartlby_modify_service($btl->RES, $aRow["object_id"], json_decode($aRow[prev_object],true));  
              } else {
                $tmp_res=bartlby_add_service($btl->RES, json_decode($aRow[prev_object],true));
                bartlby_set_service_id($btl->RES, $tmp_res, $aRow["object_id"]);
              }
          }
          $obj_link="service";
          break;
          case BARTLBY_AUDIT_TYPE_SERVER:
          $re = bartlby_get_server_by_id($btl->RES, $aRow["object_id"]);
          if((int)$_GET[recover_id]>0) {
              if($re) {
                bartlby_modify_server($btl->RES, $aRow["object_id"], json_decode($aRow[prev_object],true));  
              } else {
                $tmp_res=bartlby_add_server($btl->RES, json_decode($aRow[prev_object],true));
                bartlby_set_server_id($btl->RES, $tmp_res, $aRow["object_id"]);
              }
          }
          
          break;
          case BARTLBY_AUDIT_TYPE_WORKER:
          $re = bartlby_get_worker_by_id($btl->RES, $aRow["object_id"]);

          if((int)$_GET[recover_id]>0) {
            
              if($re) {
                bartlby_modify_worker($btl->RES, $aRow["object_id"], json_decode($aRow[prev_object],true));  
              } else {

                $tmp_res=bartlby_add_worker($btl->RES, json_decode($aRow[prev_object],true));
                bartlby_set_worker_id($btl->RES, $tmp_res, $aRow["object_id"]);
              }
          }
          
          break;
          case BARTLBY_AUDIT_TYPE_DOWNTIME:
          $re = bartlby_get_downtime_by_id($btl->RES, $aRow["object_id"]);
          if((int)$_GET[recover_id]>0) {
              if($re) {
                bartlby_modify_downtime($btl->RES, $aRow["object_id"], json_decode($aRow[prev_object],true));  
              } else {
                $tmp_res=bartlby_add_downtime($btl->RES, json_decode($aRow[prev_object],true));
                bartlby_set_downtime_id($btl->RES, $tmp_res, $aRow["object_id"]);
              }
          }
          
          break;
          case BARTLBY_AUDIT_TYPE_SERVERGROUP:
          $re = bartlby_get_servergroup_by_id($btl->RES, $aRow["object_id"]);
          if((int)$_GET[recover_id]>0) {
              if($re) {
                bartlby_modify_servergroup($btl->RES, $aRow["object_id"], json_decode($aRow[prev_object],true));  
              } else {
                $tmp_res=bartlby_add_servergroup($btl->RES, json_decode($aRow[prev_object],true));
                bartlby_set_servergroup_id($btl->RES, $tmp_res, $aRow["object_id"]);
              }
          }
          
          break;
          case BARTLBY_AUDIT_TYPE_SERVICEGROUP:
          $re = bartlby_get_servicegroup_by_id($btl->RES, $aRow["object_id"]);
          if((int)$_GET[recover_id]>0) {
              if($re) {
                bartlby_modify_servicegroup($btl->RES, $aRow["object_id"], json_decode($aRow[prev_object],true));  
              } else {
                $tmp_res=bartlby_add_servicegroup($btl->RES, json_decode($aRow[prev_object],true));
                bartlby_set_servicegroup_id($btl->RES, $tmp_res, $aRow["object_id"]);
              }
          }
          break;
          case BARTLBY_AUDIT_TYPE_TRAP:
          $re = bartlby_get_trap_by_id($btl->RES, $aRow["object_id"]);
          if((int)$_GET[recover_id]>0) {
              if($re) {
                bartlby_modify_trap($btl->RES, $aRow["object_id"], json_decode($aRow[prev_object],true));  
              } else {
                $tmp_res=bartlby_add_trap($btl->RES, json_decode($aRow[prev_object],true));
                bartlby_set_trap_id($btl->RES, $tmp_res, $aRow["object_id"]);
              }
          }
          break;          
        
        }
        


  


        $rowa[current]=$re;
        $rowa[prev]=json_decode($aRow[prev_object]);
        $rowa[date] = date("d.m.Y H:i:s", $aRow["utime"]);
        echo json_encode($rowa);
        exit;
        
        break;
      }
            $obj_link="";
            $obj_type="";
        switch($aRow["type"]) {
          case BARTLBY_AUDIT_TYPE_SERVICE:
          
          $obj_link=" <a href='service_detail.php?service_id=" . $aRow["object_id"] . "'>" . $aRow["label"] . "</a>";
          $obj_type="SERVICE";
          break;
          case BARTLBY_AUDIT_TYPE_SERVER:
          
          $obj_link=" <a href='server_detail.php?server_id=" . $aRow["object_id"]  . "'>" . $aRow["label"] .  "</a>";
          $obj_type="SERVER";
          
          break;
          case BARTLBY_AUDIT_TYPE_WORKER:
          
          $obj_link=" <a href='worker_detail.php?worker_id=" . $aRow["object_id"]  . "'>" . $aRow["label"] .  "</a>";
          $obj_type="WORKER";
          
          
          break;
          case BARTLBY_AUDIT_TYPE_DOWNTIME:
          $obj_link=$aRow["label"];
          
          $obj_type="DOWNTIME";

          break;
          case BARTLBY_AUDIT_TYPE_SERVERGROUP:
          
          $obj_link=" <a href='servergroup_detail.php?servergroup_id=" . $aRow["object_id"]  . "'>" . $aRow["label"] .  "</a>";
          $obj_type="SERVERGROUP";
          
          break;
          case BARTLBY_AUDIT_TYPE_SERVICEGROUP:
          
          $obj_link=" <a href='servicegroup_detail.php?servicegroup_id=" . $aRow["object_id"]  . "'>" . $aRow["label"] .  "</a>";
          $obj_type="SERVICEGROUP";
          break;
          case BARTLBY_AUDIT_TYPE_TRAP:
          $obj_link=" <a href='trap_detail.php?trap_id=" . $aRow["object_id"]  . "'>" . $aRow["label"] .  "</a>";
          //$obj_link=" " . $aRow["label"] .  " ";
          $obj_type="TRAP";
          break;
        
        }

        //$obj_type=$aRow["type"];
        
      $row[] = $obj_link;
      $row[] = $obj_type;


      $prev="";
      if($aRow["prev_object"] != "") {
         $prev = '<span class="btn btn-primary btn-xs fa fa-eye" onClick="audit_inspect(' . $aRow["id"] . ', ' . $aRow[action] . ');"> Inspect</span>';
      }
      $row[]=$prev;

      $output['aaData'][] = $row;
    }
     
    echo json_encode( $output );
    }
  catch ( Exception $e )
  {
    header( $_SERVER['SERVER_PROTOCOL'] .' 500 Internal Server Error' );
        die( $e->getMessage() );
  }

?>