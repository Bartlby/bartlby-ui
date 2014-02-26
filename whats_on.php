<?
//logview.php?bartlby_filter=.*;@LOG@.*\|[0,1,2]\|&json=1
	include "config.php";
	include "layout.class.php";
	include "bartlby-ui.class.php";
	$btl=new BartlbyUi($Bartlby_CONF);
	$btl->hasright("log.whatson");
	$info=$btl->getInfo();
	$layout= new Layout();

if(!$_GET[json]) {
		$whats_on=$btl->getWhatsOn();
		
		$layout->create_box("Notifications", "$notiy_cnt", "whats_on_notifications", array("whats_on" => $whats_on
			), "whats_on_notifications", false, false);

		$layout->create_box("State Changes", "$notiy_cnt", "whats_on_state_changes", array("whats_on" => $whats_on
			), "whats_on_state_changes", false, false);
		$layout->create_box("State Change Log", "$notiy_cnt", "whats_on_state_change_log", array("whats_on" => $whats_on
			), "whats_on_state_change_log", false, false);

		$layout->create_box("Notification Log", "$notiy_cnt", "whats_on_notify_log", array("whats_on" => $whats_on
			), "whats_on_notify_log", false, false);

		$whats_on_tab = "<div>Time Range: " . $whats_on[start_date] . " - " . $whats_on[end_date] . "</div>";

		$whats_on_tab .= "<div id=service_detail_service_info_ajax class='fifty_float_left'>";
		$whats_on_tab .= $layout->disp_box("whats_on_notifications");
		$whats_on_tab .= "</div>";

		$whats_on_tab .= "<div id=service_detail_service_info_ajax class='fifty_float_left'>";
		$whats_on_tab .= $layout->disp_box("whats_on_state_changes");
		$whats_on_tab .= "</div><div style='clear:both;'></div>";

		$whats_on_tab .= $layout->disp_box("whats_on_notify_log");
		$whats_on_tab .= $layout->disp_box("whats_on_state_change_log");


		$layout->Tab("Whats On <span class='notification red' style='display:inline-block; font-family: &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif; position:relative;top: 0px;'>" . $whats_on[state_changes] . "</span>", $whats_on_tab);
}
$layout->boxes_placed[MAIN]=true;
$layout->display("no");
?>