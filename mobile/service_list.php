<?
  chdir("../");
  include "config.php";

  include "layout.class.php";
  include "bartlby-ui.class.php";
  
  $btl=new BartlbyUi($Bartlby_CONF);


  $info=$btl->info;

$_MOBILE[TITLE]="Service List";


$_MOBILE[TOP_CONTENT] = '<div class="bar bar-standard bar-header-secondary">
      <form>
        <input type="search" placeholder="Search">
      </form>
      
    </div>


';

$_MOBILE[CONTENT]='<div class="btlpage" data-name="service_list.php" ></div>

<ul class="table-view" id=svcoptions >

<li class="table-view-cell table-view-divider">Options</li>

<li class="table-view-cell svcoptions_el" >
    Item 2
    <div class="toggle active">
      <div class="toggle-handle"></div>
    </div>
  </li>
  </ul>


<ul class="table-view" id=svclist>
  
  ';

/*
<li class="table-view-cell">
    
      <a href="service_detail.php?service_id=' . $svc[service_id] . '">
      <div class="media-body">
       <span class="icon icon-more-vertical" style="color: ' . $cl . ';"></span>
        ' . $svc[server_name] . '/' . $svc[service_name] . '
        <p>' . $svc[new_server_text] . '</p>
      </div>
    </a>
  </li>
*/
$_MOBILE[CONTENT] .= '
</ul>
';

$_MOBILE[NAVBARBUTTONS]=' <a class="icon icon-left-nav pull-left" id=backbtn data-transition="slide-out"></a>';
$_MOBILE[NAVBARBUTTONS] .=  '<span class="pull-right" style="font-size:12px;text-valign:top">
<span class="icon icon-left-nav"></span>
100/999
<span class="icon icon-right-nav"></span>

</span>';


$_MOBILE[NAVBARBOTTOM] .= '<a class="tab-item" href="#">
    <span class="icon icon-gear"></span>
    <span class="tab-label">Settings</span>
  </a>
  ';

include "index_template.php";
?>