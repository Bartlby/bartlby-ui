<?

/*
<span class="badge badge-primary">5</span>
      <span class="badge badge-primary">10</span>
      */

  chdir("../");
  include "config.php";

  include "layout.class.php";
  include "bartlby-ui.class.php";
  
  $btl=new BartlbyUi($Bartlby_CONF);

  $won = $btl->GetWhatsOn();
  $info=$btl->info;

  $ok_count=0;
  $critical_count=0;
  $warning_count=0;
  $other_count=0;
  $gdelay_count=0;
  $gdelay_sum=0;
  $btl->service_list_loop(function($svc)  use(&$ok_count, &$warning_count, &$critical_count, &$other_count, &$gdelay_sum, &$gdelay_count) {
        //service_delay_sum
        $gdelay_sum += $svc[service_delay_sum];
        $gdelay_count += $svc[service_delay_count];

        switch($svc[current_state]) {
          case 0:
            $ok_count++;
          break;
          case 1:
            $warning_count++;
          break; 
          case 2:
            $critical_count++;
          break;
          default:
            $other_count++;

        }
        
  });

  $max_running = bartlby_config($btl->CFG, "max_concurent_checks");
  $max_load = bartlby_config($btl->CFG, "max_load");
  $curr_load = my_sys_getloadavg();
    
  if($curr_load[0] > $max_load) {
      
    if($info[current_running] >= $max_running) {
      $load_bar = "<font color=red>" . $info[current_running]  . " / " . $max_running  . " </font> <font color=red> " . $curr_load[0] . " / " . $max_load . " </font>";
  
    } else if ($info[current_running] >= $max_running-2) {
      $load_bar = "<font color=orange>" . $info[current_running]  . " / " . $max_running  . " </font> <font color=orange> " . $curr_load[0] . " / " . $max_load . " </font>";     

  
    } else {
      $load_bar = "<font color=green>" . $info[current_running]  . " / " . $max_running  . " </font>  <font color=green>" . $curr_load[0] . " / " . $max_load . " </font>";
  
    }
  } else {
    $load_bar = "<font color=green>" . $info[current_running]  . "</font>  <font color=green>" . $curr_load[0] . " / " . $max_load . " </font>"; 
  }


if($info[round_time_count] > 0 &&  $info[round_time_sum] > 0 ) {
    $rndMS=round($info[round_time_sum] / $info[round_time_count], 2);
  } else {
    $rndMS=0; 
  }


  if($gdelay_count>0 && $gdelay_sum > 0) {
    
    $avgDEL = round($gdelay_sum/$gdelay_count,2);
  } else {
    $avgDEL = 0;  
  }

  if($warning_count>0) {
    $warning = '<span class="badge badge-warning p1">' . $warning_count . '</span>';

  }

  if($ok_count>0) {
    $ok = '<span class="badge badge-positive p1">' . $ok_count . '</span>';

  }

  if($critical_count>0) {
    $criticals = '<span class="badge badge-negative p1">' . $critical_count . '</span>';

  }

  if($other_count>0) {
    $other = '<span class="badge p1">' . $other_count . ' Downtime/Infos</span>';

  }

if($warning_count == 0 && $critical_count == 0 && $other_count == 0) {
  $allok="Everthing is fine all services are good";
}

$_MOBILE[TITLE]="Bartlby";
$_MOBILE[CONTENT]='<div class="btlpage" data-name="index.php"></div>

<ul class="table-view">
  <li class="table-view-cell table-view-divider">Quick Status</li>
  <li class="table-view-cell">
  <a href="service_list.php?expect_state=0&invert=true&datatables_output=1&rawService=1&iDisplayStart=0&iDisplayLength=50" data-transition="slide-in" data-btl_init="service_list">
     <div class="media-body" style="text-align: center">
      ' . $ok .  $criticals . $warning  . $other . $allok . '
      </div>
  </a>
  </li>
  <li class="table-view-cell table-view-divider">Last Notifications:</li>
  
';
  for($x=count($won["notifications"][msgs])-1; $x>=0 && $x>count($won["notifications"][msgs])-10; $x--) {
    $cmsg = $won["notifications"][msgs][$x];
    $cl = '#4cd964';
    switch((int)$cmsg[state]) {
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

    $svc=bartlby_get_service_by_id($btl->RES, $cmsg[service_id]);
    
    $svc_str = $svc[server_name] . "/" . $svc[service_name];
    $_MOBILE[CONTENT] .= '<li class="table-view-cell">
    <a href="service_detail.php?service_id=' . $svc[service_id] . '" data-transition="slide-in">
    <span class="icon icon-sound" style="color: ' . $cl . ';"></span>
      <span style="font-size: 12px;">' . $cmsg[to] . ' (' . date("d.m.Y H:i:s", $cmsg[date]) . ')</span>
      <p>
      ' . $svc_str . '
      </p>
    </a></li>';
  }

$_MOBILE[CONTENT] .= '
  <li class="table-view-cell table-view-divider">Core Info</li>
   <li class="table-view-cell">
   <p>Uptime:</p> ' . ($btl->intervall(time()-$btl->info[startup_time])) . '
   
   <p>Load:</p>
   ' . $load_bar . '
   <p>Round Time(avg):</p>
   ' . $rndMS . ' ms
   <p>Service Delay (avg):</p>
    ' . $avgDEL . ' ms
   <p>Checks Performed:</p>
    ' . number_format($info[checks_performed], 0, ',', '.') .  '
   <p>Checks/s:</p>
   ' . round($info[checks_performed] / (time()-$btl->info[checks_performed_time]),2) . '
   </li>
</ul>
';


$_MOBILE[NAVBARBUTTONS]=' ';
$_MOBILE[NAVBARBUTTONS] .=  '<a class="icon icon-refresh pull-right" data-ignore="push" href="index.php"></a>';


$_MOBILE[NAVBARBOTTOM] .= '<a class="tab-item" href="#">
    <span class="icon icon-gear"></span>
    <span class="tab-label">Settings</span>
  </a>
  ';

include "index_template.php";


function my_sys_getloadavg() {
  $con = file_get_contents("/proc/loadavg");
  $r = explode(" ", $con);
  return $r;
  
  
}

?>