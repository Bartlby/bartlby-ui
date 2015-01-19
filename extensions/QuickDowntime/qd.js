$(document).ready(function() {
		console.log("INIT QUICK DOWNTIME");
		

});

function qd_show_dialog(obj, id) {
		var qdModal = $('<div class="modal fade " id="qd-modal">  <div class="modal-dialog">    <div class="modal-content">      <div class="modal-header">        <button type="button" class="close" data-dismiss="modal" ><span aria-idden="true">&times;</span><span class="sr-only">Close</span></button>        <h4 class="modal-title">Bulk Service Modify</h4>      </div>      <div class="modal-body" id=qd_body name=qd_body>      </div>  <div class="modal-footer"> 	<button id=qd_go_btn class="btn btn-success" disabled><i class="fa fa-play"></i> Add</button> <button data-dismiss="modal"class="btn btn-primary" ><i class="fa fa-close"></i> Close</button>      </div>   </div>  </div></div>');
		
		$('body').append(qdModal);
		$('#qd-modal .modal-header').html("<h3> Quick Add Downtime</h3>");
    	$('#qd-modal .modal-body').html("Loading....");
    	$('#qd-modal .hide').show();
    	$('#qd-modal').modal();

    	xajax_ExtensionAjax("QuickDowntime", "qd_load_dialog", obj, id);
    	
}
function qd_add_done() {
	$('#qd_go_btn').removeAttr("disabled"); 
	$('#qd-modal').modal('hide');
}
function qd_load_done(dl) {
	
	$('#qd_go_btn').removeAttr('disabled');	

	$('.icheck').iCheck({
          checkboxClass: 'icheckbox_flat-blue',
          radioClass: 'iradio_flat-blue'
	});

	$('#qd_go_btn').click(function() {
		$('#qd_go_btn').attr('disabled', '1');
		swal({
		  title: "Create Downtime",
		  text: "A downtime for this Object will be added",
		  type: "warning",
		  showCancelButton: true,
		  confirmButtonClass: "btn-danger",
		  confirmButtonText: "Yes",
		  closeOnConfirm: true
		},
		function(){
			xajax_ExtensionAjax("QuickDowntime", "qd_add_downtime", xajax.getFormValues("qd_form"));
			$('#qd_go_btn').removeAttr("disabled");  	
		});
				
			



	});
}