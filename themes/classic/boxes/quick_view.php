<?
/*
input: quick_view

*/


$qck = $plcs[quick_view];
$quick_view = "<style>.size18 {font-size: 18px; } </style><table class='table hover' width=100%><tbody class=' no-border no-border-y'>";
	while(list($k, $v)=@each($qck)) {
		
		if($k != $last_qck) {
			$cl="";
			$STATE="UP";
			if ($hosts_a_down[$qck[$k][10]] == 1) {
				$cl="";
				$STATE="DOWN";
			}
			$quick_view .= "<tr>";
			$quick_view .= "<td class=$cl><img src='server_icons/" . $qck[$k][server_icon] . "'><font size=1><a href='server_detail.php?server_id=" . $qck[$k][10] . "'>" . $qck[$k][server_name] . "</A></td>";
			$quick_view .= "<td class=$cl><font size=1>$STATE</td>";
			$quick_view .= "<td class=$cl><table class='table no-strip borderless' style='background-color: transparent'>";
			
			$sf=false;
			if($qck[$k][0]) {
				$sf=true;
				$qo="<tr><td ><font size=1><a href='services.php?server_id=" . $qck[$k][10] . "&expect_state=0'><span class='label label-success size18' >" . $qck[$k][0] . " OK's</A></span></td></tr>";
			}
			if($qck[$k][1]) {
				$sf=true;
				$qw="<tr><td><font size=1><a href='services.php?server_id=" . $qck[$k][10] . "&expect_state=1'><span class='label label-warning  size18'>" . $qck[$k][1] . " Warnings</A></span></td></tr>";
			}
			
			if($qck[$k][2]) {
				$sf=true;
				$qc="<tr><td><font size=1><a href='services.php?server_id=" . $qck[$k][10] . "&expect_state=2'><span class='label label-danger  size18'>" . $qck[$k][2] . " Criticals</A></span></td></tr>";
			}
			
			if($qck[$k][3]) {
				$sf=true;
				$qk="<tr><td ><font size=1><a href='services.php?server_id=" . $qck[$k][10] . "&expect_state=3'><span class='label label-default  size18'>" . $qck[$k][3] . " Unkown</A></span></td></tr>";
			}
			if($qck[$k][4]) {
				$sf=true;
				$qk="<tr><td ><font size=1><a href='services.php?server_id=" . $qck[$k][10] . "&expect_state=4'><span class='label label-default  size18'>" . $qck[$k][4] . " Info</A></span></td></tr>";
			}
			if($qck[$k][downtime]) {
				//$qk="<tr><td class=silver_box><font size=1>" . $qck[$k][downtime] . " Downtime</td></tr>";
				$qk="<tr><td ><font size=1><a href='services.php?server_id=" . $qck[$k][10] . "&downtime=true'><span class='label label-default  size18'>" . $qck[$k][downtime] . " Downtime</A></span></td></tr>";
			}
			if($qck[$k][handled]) {
				//$qk="<tr><td class=silver_box><font size=1>" . $qck[$k][downtime] . " Downtime</td></tr>";
				$qk="<tr><td ><font size=1><a href='services.php?server_id=" . $qck[$k][10] . "&handled=true'><span class='label label-default  size18'>" . $qck[$k][handled] . " Handled</A></span></td></tr>";
			}
			if($qck[$k][acks]) {
				$qk="<tr><td ><font size=1><a href='services.php?server_id=" . $qck[$k][10] . "&expect_state=2&acks=yes'><span class='label label-default  size18'>" . $qck[$k][acks] . " Ack Wait</A></span></td></tr>";
			}
					
				$quick_view .= "$qo";
				$quick_view .= "$qw";
				$quick_view .= "$qc";
				$quick_view .= "$qk";
			$quick_view .= "</table></td>";
			$quick_view .= "</tr>";
		
		}
		
		$last_qck=$k;	
		$qo="";
		$qw="";
		$qc="";
		$qk="";
	}
	$quick_view .= "</tbody></table>";

	echo $quick_view;
?>
