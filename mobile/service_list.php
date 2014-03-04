<?
  chdir("../");
  include "config.php";

  include "layout.class.php";
  include "bartlby-ui.class.php";
  
  $btl=new BartlbyUi($Bartlby_CONF);


  $info=$btl->info;

$_MOBILE[TITLE]="Service List";
$_MOBILE[CONTENT]='

<ul class="table-view">
  <li class="table-view-cell table-view-divider">Services</li>
  ';

$btl->service_list_loop(function($svc, $shm_place)  {
    global $_GET, $ajax_search, $btl, $ajax_total_records, $xc, $ajax_displayed_records, $_MOBILE;
    $display_serv=$_GET[server_id];
    if($display_serv && $display_serv != $svc[server_id]) {
        return LOOP_CONTINUE; 
    }


  
    if($_GET[service_id] != "" && $svc[service_id] != $_GET[service_id]) {
          
      return LOOP_CONTINUE; 
    }
        
        
    if($_GET[downtime] == "" && $_GET[invert] == "" && $_GET[expect_state] != "" && $svc[current_state] != $_GET[expect_state]) {
      
      return LOOP_CONTINUE; 
    }
    if($_GET[downtime] == "" &&  $_GET[invert] && $_GET[expect_state] != "" && $svc[current_state] == $_GET[expect_state] ) {
    
      return LOOP_CONTINUE; 
    }
    if($_GET[invert] && $_GET[expect_state] != "" && $svc[handled] == 1) {
      return LOOP_CONTINUE; 
    }   
    if($_GET[invert] && $_GET[expect_state] != "" && $svc[current_state] == 4) {
      return LOOP_CONTINUE; 
    }
    
    if($_GET[downtime] && $svc[is_downtime] != 1) {
      return LOOP_CONTINUE;       
    }
    if($_GET[expect_state] != "" && $svc[is_downtime] == 1) {
      return LOOP_CONTINUE; 
    }
    if($_GET[expect_state] != "" && $svc[handled] == 1) {
      return LOOP_CONTINUE; 
    }
    if(($_GET[handled] == "yes"||$_GET[handled] == true) && $svc[handled] != 1) {
      return LOOP_CONTINUE;
    }
    if($_GET[acks] == "yes" && $svc[service_ack_current] != 2) {
      return LOOP_CONTINUE; 
    }
     switch((int)$svc[current_state]) {
      case 0:
        $cl = '#4cd964';
      break;
      case 1:
        $cl = 'orange';
      break;
      case 2:
        $cl='#d9534f';
      break;
      default:
      $cl = 'grey';
      break;
    }


    $_MOBILE[CONTENT] .= '<li class="table-view-cell">
    
      <a href="service_detail.php?service_id=' . $svc[service_id] . '">
      <div class="media-body">
       <span class="icon icon-more-vertical" style="color: ' . $cl . ';"></span>
        ' . $svc[server_name] . '/' . $svc[service_name] . '
        <p>' . $svc[new_server_text] . '</p>
      </div>
    </a>
  </li>
  ';

});


  

$_MOBILE[CONTENT] .= '
</ul>
';

$_MOBILE[NAVBARBUTTONS]=' <a class="icon icon-left-nav pull-left" href="index.php" data-transition="slide-out"></a>';
$_MOBILE[NAVBARBUTTONS] .=  '<a class="icon icon-refresh pull-right" data-ignore="push" href="index.php"></a>';


$_MOBILE[NAVBARBOTTOM] .= '<a class="tab-item" href="#">
    <span class="icon icon-gear"></span>
    <span class="tab-label">Settings</span>
  </a>
  ';

include "index_template.php";
?>