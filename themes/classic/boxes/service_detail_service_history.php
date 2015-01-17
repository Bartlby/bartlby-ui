<?

$ch_time=time();
if($_GET[date_filter]) {
	$tt=explode("/",$_GET[date_filter]);
	//var_dump($tt);
	$ch_time=mktime(0,0,0,$tt[0],$tt[1],$tt[2]);	
}
$prev_day = "<span class='btn fa fa-backward' onClick='statehistory_next()'></span>";
$next_day = "<span class='btn fa fa-forward' onClick='statehistory_prev()'></span>";




echo $layout->FormBox(
							array(
								0=>"Date:",
								1=>$layout->Field("date_filter", "text", date("m/d/Y",$ch_time), "", "class='datepicker' onChange='statehistory_update_filter($(this).val())'") 
								)
			, true);

echo $layout->FormBox(
						array(
								0=> "",
								1=>$prev_day . $next_day
							)
			, true);
?>

<table class="table  table-bordered hover" id='service_history_table'>
							  <thead >
								  <tr>
								  	
								  	<th>Date</th>
									  <th>State</th>
									  <th>Output</th>
								  </tr>
							  </thead>
							    <tbody>


	
	</tbody>
	</table> 


	<script>
	function statehistory_prev() {
		console.log($("#date_filter").val());
		d=$("#date_filter").val();
		p=new Date( d );
		p.setDate(p.getDate() + 1);
		d=$("#date_filter").val((p.getMonth() + 1) + '/' + p.getDate() + '/' +  p.getFullYear());
		statehistory_update_filter($("#date_filter").val());
	}
	function statehistory_next() {
		console.log($("#date_filter").val());
		d=$("#date_filter").val();
		p=new Date( d );
		p.setDate(p.getDate() - 1 );
		d=$("#date_filter").val((p.getMonth() + 1) + '/' + p.getDate() + '/' +  p.getFullYear());
		statehistory_update_filter($("#date_filter").val());
	}
	function statehistory_update_filter(d) {
			console.log("FILTER CHANGED");
			if(window.log_filter_query.match(/date_filter/)) {
				window.log_filter_query=log_filter_query.replace(/(.*)&date_filter=.*&(.*)/, "$1&date_filter=" + d + "&$2");
				
			} else {
				window.log_filter_query += "&date_filter=" + d + "&a=1";
			}
			window.service_history_table.fnNewAjax("state_history.php?datatables_output=1&service_id=<?=$plcs[service][service_id]?>&" + window.log_filter_query + "");

		
	}


	$(document).ready(function() {
			window.log_filter_query="";
			 window.service_history_table = $('#service_history_table').dataTable({
            "iDisplayLength": 10,
            "aoColumns": [
              { "sWidth": "90" , "sClass": "" },
              { "sWidth": "10", "sClass": "" },
              { "sWidth": "10", "sClass": "" },
            ],
            "aaSortingFixed": [[ 0, 'asc' ]],
            "bSort": false,
            "aaSorting": [[ 1, 'asc' ]],
            "sDom": "<'row'<'col-sm-12'T<'pull-right form-group'f><'pull-left form-group'l>r<'clearfix'>>>t<'row'<'col-sm-12'<'pull-left'i><'pull-right'p><'clearfix'>>>",
            "sAjaxSource": "state_history.php?datatables_output=1&service_id=<?=$plcs[service][service_id]?>",
            "bServerSide": true,
            "bProcessing": true,
            "oLanguage": {
              "sEmptyTable": "No Entries found",
              "sProcessing": '<i class="fa fa-spinner fa-spin"></i> Loading'
            },
            "oTableTools": {
          		"sSwfPath": "/themes/classic/js/copy_csv_xls_pdf.swf",
            	"aButtons": ["csv", "pdf","xls" ]
        	}
            
         
          });		
	});

	</script>