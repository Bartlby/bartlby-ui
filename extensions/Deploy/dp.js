function dp_local_settings_update(agent_bin_path, plugin_b_path, c_b_path) {
	$("#agent_binary_base_path").val(agent_bin_path);
	$("#plugin_base_path").val(plugin_b_path);
	$("#config_base_path").val(c_b_path);
}

$(document).ready(function() {
	$("#dp_save_local").click(
		function() {
			r=xajax_ExtensionAjax('Deploy', 'dp_save_local_settings', $("#agent_binary_base_path").val(), $("#plugin_base_path").val(),  $("#config_base_path").val());
			console.log(r);
		}
	);

	$('.dp_table').dataTable({
					"iDisplayLength": 50,
					"fnDrawCallback": function ( oSettings ) {
						
						if ( oSettings.aiDisplay.length == 0 )
						{
							return;
						}
						
						var nTrs = $('tbody tr', oSettings.nTable);
						var iColspan = nTrs[0].getElementsByTagName('td').length;
						var sLastGroup = "";
						for ( var i=0 ; i<nTrs.length ; i++ )
						{
							var iDisplayIndex = oSettings._iDisplayStart + i;
							//var sGroup = oSettings.aoData[ oSettings.aiDisplay[iDisplayIndex] ]._aData[0];
							var sGroup = oSettings.aoData[ oSettings.aiDisplay[i] ]._aData[0];
							if ( sGroup != sLastGroup )
							{
								var nGroup = document.createElement( 'tr' );
								var nCell = document.createElement( 'td' );
								nCell.colSpan = iColspan;
								nCell.className = "group";
								nCell.innerHTML = sGroup;
								nGroup.appendChild( nCell );
								nTrs[i].parentNode.insertBefore( nGroup, nTrs[i] );
								sLastGroup = sGroup;
							}
						}
					},
					"aoColumnDefs": [
						{ "bVisible": false, "aTargets": [ 0 ] }
					],
					
					"aaSortingFixed": [[ 0, 'asc' ]],
					"bSort": true,
					"aaSorting": [[ 1, 'asc' ]],
					"sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span12'i><'span12 center'p>>",
					"sPaginationType": "bootstrap",
			    	"oLanguage": {
			    		"sEmptyTable": "No Files Found",
            			"sProcessing": "<img src='extensions/AutoDiscoverAddons/ajax-loader.gif'> Loading"
        			}
			    
       
				});




	xajax_ExtensionAjax('Deploy', 'dp_set_local_settings');

});