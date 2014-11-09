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
    $aColumns = array( 'utime', 'worker_id', 'action', 'type', 'prev_object');
     
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
      for ( $i=0 ; $i<count($aColumns) ; $i++ )
      {
        /* if $aColumns[$i] is space is not data */
        if ( $aColumns[$i] != ' ' )
        {
        	switch($i) {
        		case 0:
        			$row[] = date("d.m.Y H:i:s", $aRow[ $aColumns[$i] ]);
        		break;
        		case 1:
        			$wrk_out="";
        			$id=$aRow[ $aColumns[$i] ];
        			$btl->worker_list_loop(function($wrk, $shm) use (&$wrk_out, $id) {
        				
        				if($wrk[worker_id] == $id) {
        					$wrk_out = $wrk[name];
        					
        					return LOOP_BREAK;
        				}
        			});
        			$row[] = $wrk_out;
        		break;
        		case 2:
        			$readable_action="UNKW";
        			switch( $aRow[ $aColumns[$i] ]) {
        				case BARTLBY_AUDIT_ACTION_ADD:
						$readable_action="<span class='label label-primary'>ADD</span>";
						break;
						case BARTLBY_AUDIT_ACTION_DELETE:
						$readable_action="<span class='label label-danger'>DELETED</span>";
						$prev_object=btl_audit_get_last($res, $id, $type);
						break;
						case BARTLBY_AUDIT_ACTION_MODIFY:
						$readable_action="<span class='label label-default'>MODIFIED</span>";
						$prev_object=btl_audit_get_last($res, $id, $type);
						break;
        			}
        			$row[] = $readable_action;
        		break;
        		case 4:
        			$row[] = $aRow[ $aColumns[$i] ];
        		break;
	       		default:
        			//$row[] = $aRow[ $aColumns[$i] ];
        		break;

        	}
          
        }
      }
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