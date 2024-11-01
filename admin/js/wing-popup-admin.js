(function( $ ) {
	'use strict';
	// Add click event listener to all '.wdpop_core-cloneable-title' elements
	$(function() {
		$('.wdpop_core-cloneable-title').click(function() {
			// Get the clicked wrapper element
			var clickedWrapper = $(this).closest('.wdpop_core-cloneable-item');
		
			// Remove the 'wdpop_core-cloneable-item-active' class from all other wrapper elements
			clickedWrapper.siblings('.wdpop_core-cloneable-item').removeClass('wdpop_core-cloneable-item-active');
		
			// Toggle the class only on the clicked wrapper's item
			clickedWrapper.toggleClass('wdpop_core-cloneable-item-active');
		});
	});

})( jQuery );
