function ocl_schedule_update(event, id, dfrom, dto) {
	console.log("UPDATE EVENT: " + id + " from: " + dfrom + " TO:" + dto + "ALLDAY: " + event.allDay);
	$.get("extensions_wrap.php?script=OcL/ocl_schedule.php?update=1&id=" + event.id + "&dfrom=" + dfrom/1000 + "&dto=" + dto/1000 + "&allday=" + event.allDay);
}
function ocl_make_draggable() {
	$('#ocl_external-events div.external-event').each(function() {
		
			// create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
			// it doesn't need to have a start or end
			var eventObject = {
				title: $.trim($(this).text()), // use the element's text as the event title
				worker_id: $(this).data("worker_id"),
				activity_level: $(this).data("activity_level")
			};
			
			// store the Event Object in the DOM element so we can get to it later
			$(this).data('eventObject', eventObject);
			
			// make the event draggable using jQuery UI
			$(this).draggable({
				zIndex: 999,
				revert: true,      // will cause the event to go back to its
				revertDuration: 0  //  original position after the drag
			});
			
		});
		$('#ocl_external-events div.external-event1').each(function() {
		
			// create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
			// it doesn't need to have a start or end
			var eventObject = {
				title: $.trim($(this).text()), // use the element's text as the event title
				worker_id: $(this).data("worker_id"),
				activity_level: $(this).data("activity_level"),
				color: "grey"
			};
			
			// store the Event Object in the DOM element so we can get to it later
			$(this).data('eventObject', eventObject);
			
			// make the event draggable using jQuery UI
			$(this).draggable({
				zIndex: 999,
				revert: true,      // will cause the event to go back to its
				revertDuration: 0  //  original position after the drag
			});
			
		});

}
function ocl_add_event(event) {
	console.log("ADD");
	console.log(event);
}
function ocl_add_new_entry(event) {

    var new_id;

    $.ajax({
        type: "GET",
        url: "extensions_wrap.php?script=OcL/ocl_schedule.php?update=2&dfrom=" + event.start.getTime()/1000 + "&dto=" + event.end + "&allday=" + event.allDay + "&activity_level=" + event.activity_level + "&worker_id=" + event.worker_id + "&color=" + event.color ,
        async: false,
        success : function(data) {
            new_id = data;
        }
    });
    return new_id;

}	
function ocl_delete_event(event) {
	if(confirm("Delete Event id: " + event.id)) {
		$.get("extensions_wrap.php?script=OcL/ocl_schedule.php?update=3&id=" + event.id, function() {
			$('#ocl_calendar').fullCalendar( 'refetchEvents' );
		});
	}
}
$(document).ready(function() {
	/*

	*/
	$(".ocl_save_managed").click(function() {
		xajax_ExtensionAjax("OcL", "ocl_save_managed", $("#worker_id").val());
	});
	$('[data-rel="ocl_chosen"],[rel="ocl_chosen"]').selectize({
    	create: false,
    	plugins: ['remove_button', 'drag_drop'],
    	sortField: 'text'
	});

		$('#coreTabs a[href=#ocl_schedule_tab]').click(function() {
			$("#ocl_calendar").fullCalendar('render');	
			
		});

		$("#ocl_schedule").append("Find Worker: <input type=text id=ocl_worker_filter style='margin-top:10px;'><div id='wrap'><div id='ocl_external-events'></div><div id='ocl_calendar'></div></div>");
		xajax_ExtensionAjax("OcL", "ocl_get_worker_list");


		$("#ocl_worker_filter").keyup(function() {
			xajax_ExtensionAjax("OcL", "ocl_get_worker_list", $("#ocl_worker_filter").val());
		});

		$("#ocl_schedule").append("");
		$("#ocl_calendar").fullCalendar({
			events: "extensions_wrap.php?script=OcL/ocl_schedule.php",
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,agendaWeek,agendaDay'
			},
			firstDay: 1,
			editable: true,
			axisFormat: 'HH:mm',
timeFormat: {
    agenda: 'H:mm{ - h:mm}'
},
ignoreTimezone: true,
			defaultEventMinutes: 120,
			droppable: true, // this allows things to be dropped onto the calendar !!!
			eventResize:function( event, jsEvent, ui, view ) { 
				
				ocl_schedule_update(event, event.id, event.start.getTime(), event.end.getTime());
			},
			eventDrop:function( event, jsEvent, ui, view ) { 
				ocl_schedule_update(event, event.id, event.start.getTime(), event.end.getTime());

			},
			eventClick: function( event, jsEvent, view ) {
					ocl_delete_event(event);
			},
			eventRender: function(event, element) { 
				//element.find('.fc-event-title').append("<div><button class='sm_add_new_btn btn  btn-danger'>delete</button><br><input type=radio name=activity" + event.id + " value=1>Active<br><input type=radio name=activity" + event.id + " value=2>Standby</div>"); 
			},

			drop: function(date, allDay) { // this function is called when something is dropped
			
				// retrieve the dropped element's stored Event Object
				var originalEventObject = $(this).data('eventObject');
				
				// we need to copy it, so that multiple events don't have a reference to the same object
				var copiedEventObject = $.extend({}, originalEventObject);
				
				// assign it the date that was reported
				copiedEventObject.start = date;
				copiedEventObject.allDay = allDay;
				copiedEventObject.end    = (date.getTime() + 1800000)/1000; // put your desired end time here
				copiedEventObject.id 	 = parseInt(ocl_add_new_entry(copiedEventObject));

				
				ocl_add_event(copiedEventObject);
				// render the event on the calendar
				// the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
				$('#ocl_calendar').fullCalendar( 'refetchEvents' );
								
				
			}
		});







});