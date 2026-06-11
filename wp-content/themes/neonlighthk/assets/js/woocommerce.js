/**
 * WooCommerce JS Enhancements for Neon Light HK
 *
 * Features:
 * - Checkout field enhancements (datepicker, validation)
 * - Payment method toggle visibility
 * - Dynamic cart count update
 */

(function () {
	'use strict';

	const doc = document;
	const win = window;
	const $ = win.jQuery; // WooCommerce requires jQuery

	// ───────────────────────────────────────────────────────────
	// 1. Checkout Enhancements
	// ───────────────────────────────────────────────────────────

	function initCheckoutEnhancements() {
		if (!doc.body.classList.contains('woocommerce-checkout')) return;

		const deliveryDateInput = doc.getElementById('billing_delivery_date');
		if (deliveryDateInput && typeof flatpickr !== 'undefined') {
			flatpickr(deliveryDateInput, {
				dateFormat: 'Y-m-d',
				minDate: 'today',
				disableMobile: false,
			});
		}

		// Highlight empty required fields on blur
		const requiredFields = doc.querySelectorAll('.woocommerce-checkout .validate-required input, .woocommerce-checkout .validate-required textarea');
		requiredFields.forEach(function (field) {
			field.addEventListener('blur', function () {
				if (!this.value.trim()) {
					this.classList.add('nl-field-error');
				} else {
					this.classList.remove('nl-field-error');
				}
			});
		});

		// Real-time phone validation (HK format)
		const phoneField = doc.getElementById('billing_phone');
		if (phoneField) {
			phoneField.addEventListener('input', function () {
				const val = this.value.replace(/\D/g, '');
				if (val.length > 8) {
					this.value = val.slice(0, 8);
				}
			});
		}
	}

	// ───────────────────────────────────────────────────────────
	// 2. Payment Method Toggle
	// ───────────────────────────────────────────────────────────

	function initPaymentMethodToggle() {
		if (!doc.body.classList.contains('woocommerce-checkout')) return;

		const paymentMethods = doc.querySelectorAll('input[name="payment_method"]');
		const paymentDescriptions = doc.querySelectorAll('.nl-payment-description');

		function updatePaymentDescriptions() {
			const selected = doc.querySelector('input[name="payment_method"]:checked');
			if (!selected) return;

			const methodId = selected.value;

			paymentDescriptions.forEach(function (desc) {
				desc.style.display = 'none';
			});

			const activeDesc = doc.querySelector('.nl-payment-description[data-method="' + methodId + '"]');
			if (activeDesc) {
				activeDesc.style.display = 'block';
			}
		}

		paymentMethods.forEach(function (radio) {
			radio.addEventListener('change', updatePaymentDescriptions);
		});

		// Initial state
		updatePaymentDescriptions();
	}

	// ───────────────────────────────────────────────────────────
	// 3. Dynamic Cart Count (AJAX)
	// ───────────────────────────────────────────────────────────

	function initCartCountUpdate() {
		const cartCountBadge = doc.querySelector('.nl-cart-count');
		if (!cartCountBadge) return;

		// Listen for WooCommerce added_to_cart event
		$(doc.body).on('added_to_cart', function (event, fragments, cart_hash) {
			updateCartCount(fragments);
		});

		// Listen for removed_from_cart event
		$(doc.body).on('removed_from_cart', function (event, fragments, cart_hash) {
			updateCartCount(fragments);
		});

		function updateCartCount(fragments) {
			if (fragments && fragments['div.widget_shopping_cart_content']) {
				// WooCommerce updates fragments; we can re-read count from cookie or API
				fetchCartCount();
			}
		}

		function fetchCartCount() {
			if (typeof wc_cart_fragments_params === 'undefined') return;

			const data = new FormData();
			data.append('action', 'woocommerce_get_refreshed_fragments');

			fetch(wc_cart_fragments_params.ajax_url, {
				method: 'POST',
				body: data,
				credentials: 'same-origin',
			})
				.then(function (response) {
					return response.json();
				})
				.then(function (json) {
					if (json && json.fragments) {
						const count = extractCartCount(json.fragments);
						if (count !== null) {
							cartCountBadge.textContent = count;
							cartCountBadge.style.display = count > 0 ? 'inline-block' : 'none';
						}
					}
				})
				.catch(function () {
					// Silently fail — cart count will update on next page load
				});
		}

		function extractCartCount(fragments) {
			// Try to extract from the mini-cart fragment HTML
			const miniCart = fragments['div.widget_shopping_cart_content'];
			if (!miniCart) return null;

			const tmp = doc.createElement('div');
			tmp.innerHTML = miniCart;
			const countEl = tmp.querySelector('.cart-contents-count, .nl-cart-count');
			if (countEl) {
				const count = parseInt(countEl.textContent, 10);
				return isNaN(count) ? null : count;
			}
			return null;
		}
	}

	// ───────────────────────────────────────────────────────────
	// 4. Product Quick View (optional helper)
	// ───────────────────────────────────────────────────────────

	function initQuickView() {
		const quickViewBtns = doc.querySelectorAll('.nl-quick-view-btn');
		if (!quickViewBtns.length) return;

		quickViewBtns.forEach(function (btn) {
			btn.addEventListener('click', function (e) {
				e.preventDefault();
				const productId = this.dataset.productId;
				if (!productId) return;

				// Fire custom event for theme integration
				const event = new CustomEvent('nlQuickViewOpen', {
					detail: { productId: productId },
				});
				doc.dispatchEvent(event);
			});
		});
	}

	// ───────────────────────────────────────────────────────────
	// Initialise
	// ───────────────────────────────────────────────────────────

	doc.addEventListener('DOMContentLoaded', function () {
		initCheckoutEnhancements();
		initPaymentMethodToggle();
		initCartCountUpdate();
		initQuickView();
	});
})();
