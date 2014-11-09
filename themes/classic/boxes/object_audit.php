<table class="table table-bordered " id='object_audit_table'>
						  <thead>
							  <tr>
							  	
							  		<th>Date</th>
							  	  <th>Worker</th>
								  <th>Action</th>
								  <th>Prev</th>
								  
							  </tr>
						  </thead>
						    <tbody id=object_audit_log>




</tbody>
</table>


<script>
$(document).ready(function() {
	window.object_audit_log = $('#object_audit_table').dataTable({
          "iDisplayLength": 50,
          "sDom": "<'row'<'col-sm-12'T<'pull-right form-group'f><'pull-left form-group'l>r<'clearfix'>>>t<'row'<'col-sm-12'<'pull-left'i><'pull-right'p><'clearfix'>>>",
          "sAjaxSource": "extensions_wrap.php?script=Audit/datatables.php?type=<?=$plcs[type]?>&id=<?=$plcs[id]?>",
          "bServerSide": true,
          "bProcessing": true,
    
        "oTableTools": {
          "sSwfPath": "/themes/classic/js/copy_csv_xls_pdf.swf",
            "aButtons": [
                {
                    "sExtends":    "collection",
                    "sButtonText": "Export",
                    "aButtons":    [ "csv", "xls", "pdf" ]
                }
            ]
        },
          "oLanguage": {
            "sEmptyTable": "No Audit Log Entries found",
            "sProcessing": '<i class="fa fa-spinner fa-spin"></i> Loading'
          }
          
       
        });

});

</script>