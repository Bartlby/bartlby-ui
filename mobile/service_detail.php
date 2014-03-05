<?
  chdir("../");
  include "config.php";

  include "layout.class.php";
  include "bartlby-ui.class.php";
  
  $btl=new BartlbyUi($Bartlby_CONF);


  $info=$btl->info;
  
$_MOBILE[TITLE]="Service Detail";
$_MOBILE[CONTENT]='

<ul class="table-view">
  <li class="table-view-cell table-view-divider">Quick Status</li>
  <li class="table-view-cell">
  <a href=service_list.php>
     <div class="media-body">
      <span class="badge badge-primary">5</span>
      <span class="badge badge-primary">10</span>
      </div>
  </a>
  </li>
  <li class="table-view-cell table-view-divider">Last Notifications:</li>
  <li class="table-view-cell">
    <a class="push-right">
      <span class="badge">5</span>
      Whats On
    </a>
  </li>
  
  <li class="table-view-cell table-view-cell">Item 2</li>
  <li class="table-view-cell table-view-cell">Item 2</li>
  <li class="table-view-cell table-view-cell">Item 2</li>
  

  <li class="table-view-cell table-view-divider">Core Info</li>
  <li class="table-view-cell">
    
      
      <div class="media-body">
        Item 1
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore. Lorem ipsum dolor sit amet.</p>
      </div>
    
  </li>
</ul>
';

$_MOBILE[NAVBARBUTTONS]='  <a class="icon icon-left-nav pull-left" id=backbtn data-transition="slide-out"></a>';
$_MOBILE[NAVBARBUTTONS] .=  '<a class="icon icon-refresh pull-right" data-ignore="push" href="index.php"></a>';


$_MOBILE[NAVBARBOTTOM] .= '<a class="tab-item" href="#">
    <span class="icon icon-gear"></span>
    <span class="tab-label">Settings</span>
  </a>
  ';

include "index_template.php";
?>