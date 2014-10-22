$(document).ready(function() {
		console.log("PACKERY READY");

	
		// init
		 var $container = $('#container').packery({
		  itemSelector: '.item',
		  gutter: 2,
		  columnWidth: 50,
  		  rowHeight: 50

		});

		$container.find('.item').each( function( i, itemElem ) {
		  // make element draggable with Draggabilly
		  var draggie = new Draggabilly( itemElem );
		  // bind Draggabilly events to Packery
		  $container.packery( 'bindDraggabillyEvents', draggie );
		});
		
});;