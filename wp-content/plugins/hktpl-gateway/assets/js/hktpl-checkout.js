/**
 * HKTPL Checkout Enhancement
 *
 * Handles Tap & Go / FPS payment method selection.
 */

(function($) {
	'use strict';

	$(document).on('updated_checkout', function() {
		var $methods = $('#hktpl-payment-method-select input[type="radio"]');
		if ( $methods.length === 0 ) return;

		if ( !$methods.is(':checked') ) {
			$methods.first().prop('checked', true);
		}

		$methods.on('change', function() {
			$('.hktpl-method-label').removeClass('selected');
			$(this).closest('.hktpl-method-label').addClass('selected');
		});
		$methods.filter(':checked').trigger('change');
	});
})(jQuery);
