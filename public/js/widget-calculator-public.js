(function( $ ) {
	'use strict';

	$( document ).ready( function() {

		$( '#calculator_submit' ).on( 'click', function( ev ) {
			let user_quantity = document.getElementById('calc_input_quantity').value;
			let output_area = document.getElementById('packs_total_output');

			if (user_quantity > 0) {

				var data = {
					'action' : 'request_calculation',
					'user_quantity' : user_quantity
				};
			
				$.post( settings.ajaxurl, data, function( response ) {
		
					let packs = JSON.parse(JSON.stringify(response));

					$( output_area ).html(response.data.packs_fulfillment);
			
				} );
				
			}else{
				$( output_area ).html('<em>Please try a number greater than zero!</em>');
			}


			ev.preventDefault();
		})
	
	});

})( jQuery );
