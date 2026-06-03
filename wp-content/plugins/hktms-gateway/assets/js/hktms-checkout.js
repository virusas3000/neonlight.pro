/**
 * HKTMS Checkout enhancements — v1.10
 *
 * - Payment method radio toggle
 * - Apple Pay detection
 * - Form validation helper
 */
(function ($) {
	'use strict';

	$(document).ready(function () {
		var $fieldset = $('#hktms-payment-method-select');
		if (!$fieldset.length) return;

		// Default selection
		var $default = $fieldset.find('input[value="visamastercard"]');
		if ($default.length && !$fieldset.find('input:checked').length) {
			$default.prop('checked', true);
		}

		// Apple Pay availability check
		if (window.ApplePaySession && ApplePaySession.canMakePayments()) {
			$fieldset.find('input[value="applepay"]').closest('label').show();
		} else {
			$fieldset.find('input[value="applepay"]').closest('label').hide();
		}

		// Highlight selected method
		$fieldset.on('change', 'input[name="hktms_payment_method"]', function () {
			$fieldset.find('.hktms-method-label').removeClass('selected');
			$(this).closest('label').addClass('selected');
		});

		// Trigger initial highlight
		$fieldset.find('input:checked').trigger('change');
	});
})(jQuery);
