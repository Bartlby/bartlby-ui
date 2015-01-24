<style>
#content_service_detail_status_text > div > div {
	background: #333333;
	padding-left: 10px;
}
#content_service_detail_status_text > div > div > kbd {
	box-shadow: none;
	
	background: #333333;
}
</style>
<div style='height: 250px;overflow:scroll'>
<div ><kbd><?=str_replace( "\\dbr", "<br>",nl2br(htmlspecialchars($plcs[service][current_output])))?></kbd></div>
</div>