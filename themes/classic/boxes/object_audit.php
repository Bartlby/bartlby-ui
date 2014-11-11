<table class="table table-bordered " id='object_audit_table'>
						  <thead>
							  <tr>
							  	
							  		<th>Date</th>

							  	  <th>Worker</th>
								  <th>Action</th>
                  <th>Object</th>
                  <th>Type</th>
								  <th>Prev</th>
								  
							  </tr>
						  </thead>
						    <tbody id=object_audit_log>




</tbody>
</table>
<!-- Modal -->
<div class="modal fade" id="auditModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Object Audit Diff Viewer</h4>
      </div>
      <div class="modal-body" id=audit_modal_body style='padding:0px;'>
           <div class="col-lg-12">
              <h5>Diff Against Current Object</h5>
              <div id="audit_date"></div>
               <div id=audit_intime>
              </div>
            </div>
        </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-default fa fa-close" data-dismiss="modal"> Close</button>
        <button type="button" class="btn btn-success fa fa-recycle" onClick="audit_recover_object();"> Recover</button>
      </div>
    </div>
  </div>
</div>

<script>
window.audit_recover_id=0;

function audit_recover_object() {

  swal({   title: "Are you sure?",   text: "You will recover the previous object - as the current new Object",   type: "warning",   showCancelButton: true,   confirmButtonColor: "#DD6B55",   confirmButtonText: "Yes, delete it!",   closeOnConfirm: false }, function(){   swal("Deleted!", "Your imaginary file has been deleted.", "success"); });

  swal({
  title: "Are you sure?",
  text: "You will recover the previous object - as the current new Object",
  type: "warning",
  showCancelButton: true,
  confirmButtonClass: "btn-danger",
  confirmButtonText: "Yes, Recover it!",
  closeOnConfirm: false
},
function(){
   $.getJSON("extensions_wrap.php?script=Audit/datatables.php?force_id=" + window.audit_recover_id + "&recover_id=" + window.audit_recover_id, function(r) {
       $("#auditModal").modal("hide");
       swal("Recovered!", "Object successfully recovered", "success");
   });
  
  
});

}
function audit_inspect(id, action) {
  window.audit_recover_id=id;
  $.getJSON("extensions_wrap.php?script=Audit/datatables.php?force_id=" + id, function(r) {
    console.log(r.current);
    var post_pend="";
    if(r.current != false && action == 3) {
      post_pend="<br><span class='label label-danger'>Object already been recovered - you can reset to this state</span>";
    }
    if(r.current == false) r.current = {}; 

    var diff = objectDiff.diffOwnProperties(r.prev, r.current);
   
    $("#audit_intime").html("<pre>" + objectDiff.convertToXMLString(diff) + "</pre>");
    $("#audit_date").html("Object in time is from <kbd>" +  r.date + "</kbd> changes marked RED")
    $("#auditModal").modal();

    if(diff.changed == "equal") {
      $("#audit_intime").html("<pre>Object in-time is the same as current state in DB</pre>");      
    }
    if(post_pend != "") {
      $("#audit_date").html($("#audit_date").html() + post_pend);
    }

  });



}
$(document).ready(function() {
  
	window.object_audit_log = $('#object_audit_table').dataTable({
          "iDisplayLength": 50,
          "sDom": "<'row'<'col-sm-12'T<'pull-right form-group'f><'pull-left form-group'l>r<'clearfix'>>>t<'row'<'col-sm-12'<'pull-left'i><'pull-right'p><'clearfix'>>>",
          "sAjaxSource": "extensions_wrap.php?script=Audit/datatables.php?type=<?=$plcs[type]?>&id=<?=$plcs[id]?>",
          "bServerSide": true,
          "bProcessing": true,
          "order": [[0, "desc" ]],
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