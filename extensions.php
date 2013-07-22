<?php
function dnl($i) {
	return sprintf("%02d", $i);
}
include "layout.class.php";
include "config.php";
include "bartlby-ui.class.php";
$btl=new BartlbyUi($Bartlby_CONF);


$layout= new Layout();
$layout->set_menu("core");
$layout->setTitle("Core Extensions");
$layout->Table("100%");

$core_extensions="";
$cnt = (int)bartlby_config($btl->CFG, "extension_count");

if($cnt || $cnt == 0) {
	$ext_table = '<table class="table table-bordered table-striped table-condensed">
							  <thead>
								  <tr>
									  <th>Extension</th>
									  <th>Description</th>
									  <th>Version</th>
									  
								  </tr>
							  </thead>   ';

$ext_table .= ' <tbody>';					
	
	for($x=1; $x<=$cnt; $x++) {
		$path = bartlby_config($btl->CFG, "extension[" . $x . "]");
		if(function_exists("bartlby_get_core_extension_info")) {
			$core_info = bartlby_get_core_extension_info($path);
		} else {
			$core_info[name] = "Upgrade your bartlby-php extensions to retrieve core ext info";
		}
		$ext_table .= '<tr>';
		$ext_table .= '<td>' . basename($path) . '</td>';
		$ext_table .= '<td>' . $core_info[name] . '</td>';
		$ext_table .= '<td>' . $core_info[version] . '</td>';
		$ext_table .= '</tr>';
	
	}
} else {
	$ext_table = "no core extension is loaded";
}

$ext_table .= ' </tbody></table>';


$layout->Tr(
		$layout->Td(
				array(0=>$ext_table)
			)

		);		

$temp_layout = new Layout();

$r=$btl->getExtensionsReturn("_About", $temp_layout, true);

//FIXME create_box

$ext_table = '<table class="table table-bordered table-striped table-condensed">
							  <thead>
								  <tr>
									  <th>Extensions</th>
									  <th>Description</th>
									  <th>Provides</th>
									  <th>Status</th>
									  
								  </tr>
							  </thead>   ';

$ext_table .= ' <tbody>';						  

for($x=0; $x<count($r); $x++) {
		$ext_table .= ' <tr>';
		$ext_table .= ' <td>' . $r[$x][ex_name] . "</td>";
		$ext_table .= ' <td>' . $r[$x][out] . "</td>";
		$provides = "";
		$z = 0;
		$basic_provides="";
		$provides_widgets=false;
		for($y=0; $y<count($r[$x][methods]); $y++) {
			if(preg_match("/^(_|widget_).*/", $r[$x][methods][$y])) {
				
				$provides .= $r[$x][methods][$y] . "\r\n";
				if(preg_match("/^widget/", $r[$x][methods][$y])) {
					$provides_widgets = true;
				}
			}
			
		}
		$basic_provides="Functions";
		if($provides_widgets) $basic_provides .= ",Widgets";
		
		$ext_table .= ' <td><div data-rel="tooltip" title="' . $provides . '">' . $basic_provides . '</div></td>';
		
		$enabled = "Enabled";
		$btn_class="btn-success";
		if( file_exists("extensions/" .  $r[$x][ex_name] . ".disabled")) {
			$enabled="Disabled";
			$btn_class="btn-danger";
		}
		$ext_table .= ' <td><button id="extension_button_' . $r[$x][ex_name] . '" class="btn btn-mini ' . $btn_class . '" onClick="xajax_toggle_extension(\'' . $r[$x][ex_name] . '\');"><i class=""></i>' . $enabled . '</button></td>';
		$ext_table .= '</tr>';
}

$ext_table .= ' </tbody></table>';
$layout->create_box("UI-Extensions", $ext_table);


$layout->TableEnd();
$layout->display();

