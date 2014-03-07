<?
  chdir("../");
  include "config.php";

  include "layout.class.php";
  include "bartlby-ui.class.php";
  
  $btl=new BartlbyUi($Bartlby_CONF);


  $info=$btl->info;
  
$_MOBILE[TITLE]="Service Detail";
$_MOBILE[CONTENT]='<div class="btlpage" data-name="service_detail.php" ></div>
<div class="btlpage" data-name="service_detail.php" ></div>
<ul class="table-view">
  <li class="table-view-cell table-view-divider">Current State <span id=svc_curr_label></span></li></li>
  <li class="table-view-cell">
         <div class="media-body">
        
        <span id=svc_output>Item 1</span>
      </div>
    
  </li>
<li class="table-view-cell table-view-divider">Actions</li>

  <li class="table-view-cell media force_click">
    <a class="navigate-right">
      <img class="media-object pull-left force" src="themes/classic/images/force.gif">
      <div class="media-body">
        <span id=force_text>Force Immediate Check</span>
      </div>
    </a>
  </li>


  <li class="table-view-cell media">
    <a class="navigate-right trigger_click">
      <img class="media-object pull-left trigger" src="">
      <div class="media-body">
        <span id=trigger_text>Notifications</span>
      </div>
    </a>
  </li>
  <li class="table-view-cell media check_click">
    <a class="navigate-right">
      <img class="media-object pull-left check" src="">
      <div class="media-body">
        <span id=check_text>Check</span>
      </div>
    </a>
  </li>

  <li class="table-view-cell media handle_click">
    <a class="navigate-right">
      <img class="media-object pull-left handle" src="">
      <div class="media-body">
        <span id=handle_text>Handle</span>
      </div>
    </a>
  </li>

  <li class="table-view-cell table-view-divider">Service Info 
  <li class="table-view-cell">
         <div class="media-body">
        <p>Server</p>
        <span id=svc_server>Item 1</span>
      </div>
    
  </li>
  <li class="table-view-cell">
         <div class="media-body">
        <p>Name</p>
        <span id=svc_name>Item 1 (current state)</span>
      </div>
    
  </li>
  <li class="table-view-cell">
         <div class="media-body">
        <p>Type</p>
        <span id=svc_type>Item 1</span>
      </div>
    
  </li>
  <li class="table-view-cell">
         <div class="media-body">
        <p>Life Indicator</p>
        <span id=svc_life>Item 1</span>
      </div>
    
  </li>
  <li class="table-view-cell">
         <div class="media-body">
        <p>Last/next Check</p>
        <span id=svc_last_next>Item 1</span>
      </div>
    
  </li>
  <li class="table-view-cell">
         <div class="media-body">
        <p>Intervall</p>
        <span id=svc_intervall>Item 1</span>
      </div>
    
  </li>
  <li class="table-view-cell">
         <div class="media-body">
        <p>Last Notification Sent</p>
        <span id=svc_last_notification>Item 1</span>
      </div>
    
  </li>
  <li class="table-view-cell">
         <div class="media-body">
        <p>Re-Notification Interval</p>
        <span id=svc_renotify>Item 1</span>
      </div>
    
  </li>
  <li class="table-view-cell">
         <div class="media-body">
        <p>Escalate after</p>
        <span id=svc_escalate>Item 1</span>
      </div>
    
  </li>
  <li class="table-view-cell">
         <div class="media-body">
        <p>Notifications</p>
        <span id=svc_notify>Item 1</span>
      </div>
    
  </li>
  <li class="table-view-cell">
         <div class="media-body">
        <p>Check</p>
        <span id=svc_check>Item 1</span>
      </div>
    
  </li>
  <li class="table-view-cell">
         <div class="media-body">
        <p>Fires Events</p>
        <span id=svc_fire>Item 1</span>
      </div>
    
  </li>

<li class="table-view-cell">
         <div class="media-body">
        <p>Check Plan</p>
        <span id=svc_plan>Item 1</span>
      </div>
    
  </li>

  <li class="table-view-cell">
         <div class="media-body">
        <p>Flap Seconds</p>
        <span id=svc_flap>Item 1</span>
      </div>
    
  </li>
  <li class="table-view-cell">
         <div class="media-body">
        <p>Status</p>
        <span id=svc_status>Item 1</span>
      </div>
    
  </li>
  <li class="table-view-cell">
         <div class="media-body">
        <p>Last State Change</p>
        <span id=svc_last_state_change>Item 1</span>
      </div>
    
  </li>
  <li class="table-view-cell">
         <div class="media-body">
        <p>Currently Running</p>
        <span id=svc_running>Item 1</span>
      </div>
    
  </li>
  <li class="table-view-cell">
         <div class="media-body">
        <p>Delay Time</p>
        <span id=svc_delay>Item 1</span>
      </div>
    
  </li>
  <li class="table-view-cell">
         <div class="media-body">
        <p>Triggers</p>
        <span id=svc_trigger>Item 1</span>
      </div>
    
  </li>
  <li class="table-view-cell">
         <div class="media-body">
        <p>Handled</p>
        <span id=svc_handled>Item 1</span>
      </div>
    
  </li>
</ul>
';

$_MOBILE[NAVBARBUTTONS]='  <a class="icon icon-left-nav pull-left" id=backbtn data-transition="slide-out"></a>';
$_MOBILE[NAVBARBUTTONS] .=  '<a class="icon icon-refresh pull-right refresh_click" ></a>';


$_MOBILE[NAVBARBOTTOM] .= '<a class="tab-item" href="#">
    <span class="icon icon-gear"></span>
    <span class="tab-label">Settings</span>
  </a>
  ';

include "index_template.php";
?>