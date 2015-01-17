<?

$srv_id=$_GET[server_id];
$uniq_svc="";
echo "<div class='row'>";
$btl->service_list_loop(function($s) use (  $srv_id, &$uniq_svc , &$layout) {
	if($s[server_id] == $srv_id) {
		if(!$uniq_svc[$s[plugin]] && file_exists("gauglets/" . $s[plugin] . ".php")) {
				$plcs[service]=$s;
				echo "<div class='col-lg-4'><h5>" . $s[service_name] . "</h5>";
				include("gauglets/" . $s[plugin] . ".php");
				echo "</div>";
				$uniq_svc[$s[plugin]]=1;
		}
	}
});
echo "</div>";

?>

	
