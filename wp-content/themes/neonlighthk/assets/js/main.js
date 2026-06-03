/**
 * Main JS for Neon Light HK Theme
 *
 * Features:
 * - Mobile menu toggle
 * - Smooth scroll for anchor links
 * - Intersection Observer for fade-in animations
 */

(function () {
	'use strict';

	const doc = document;
	const win = window;

	// ───────────────────────────────────────────────────────────
	// 1. Mobile Menu Toggle
	// ───────────────────────────────────────────────────────────

	function initMobileMenu() {
		const toggleBtn = doc.querySelector('.nl-mobile-menu-toggle');
		const navMenu   = doc.querySelector('.nl-primary-nav');
		const body      = doc.body;

		if (!toggleBtn || !navMenu) return;

		toggleBtn.addEventListener('click', function () {
			const isOpen = navMenu.classList.toggle('is-open');
			toggleBtn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
			body.classList.toggle('menu-open', isOpen);
		});

		// Close menu when clicking a link (anchor links)
		navMenu.querySelectorAll('a').forEach(function (link) {
			link.addEventListener('click', function () {
				if (navMenu.classList.contains('is-open')) {
					navMenu.classList.remove('is-open');
					toggleBtn.setAttribute('aria-expanded', 'false');
					body.classList.remove('menu-open');
				}
			});
		});

		// Close on Escape key
		doc.addEventListener('keydown', function (e) {
			if (e.key === 'Escape' && navMenu.classList.contains('is-open')) {
				navMenu.classList.remove('is-open');
				toggleBtn.setAttribute('aria-expanded', 'false');
				body.classList.remove('menu-open');
			}
		});
	}

	// ───────────────────────────────────────────────────────────
	// 2. Smooth Scroll for Anchor Links
	// ───────────────────────────────────────────────────────────

	function initSmoothScroll() {
		doc.querySelectorAll('a[href^="#"]').forEach(function (anchor) {
			anchor.addEventListener('click', function (e) {
				const targetId = this.getAttribute('href');
				if (targetId === '#' || targetId.length < 2) return;

				const target = doc.querySelector(targetId);
				if (!target) return;

				e.preventDefault();

				const headerOffset = 80; // adjust for fixed header
				const elementPosition = target.getBoundingClientRect().top;
				const offsetPosition = elementPosition + win.pageYOffset - headerOffset;

				win.scrollTo({
					top: offsetPosition,
					behavior: 'smooth',
				});
			});
		});
	}

	// ───────────────────────────────────────────────────────────
	// 3. Intersection Observer — Fade-in Animations
	// ───────────────────────────────────────────────────────────

	function initScrollAnimations() {
		const observerOptions = {
			root: null,
			rootMargin: '0px 0px -50px 0px',
			threshold: 0.1,
		};

		const observer = new IntersectionObserver(function (entries) {
			entries.forEach(function (entry) {
				if (entry.isIntersecting) {
					entry.target.classList.add('is-visible');
					observer.unobserve(entry.target);
				}
			});
		}, observerOptions);

		doc.querySelectorAll('.nl-fade-in, .nl-slide-up, .nl-slide-left, .nl-slide-right').forEach(function (el) {
			observer.observe(el);
		});
	}

	// ───────────────────────────────────────────────────────────
	// 4. Sticky Header Shadow
	// ───────────────────────────────────────────────────────────

	function initStickyHeader() {
		const header = doc.querySelector('.nl-site-header');
		if (!header) return;

		let lastScroll = 0;

		win.addEventListener('scroll', function () {
			const currentScroll = win.pageYOffset;

			if (currentScroll > 10) {
				header.classList.add('is-scrolled');
			} else {
				header.classList.remove('is-scrolled');
			}

			lastScroll = currentScroll;
		});
	}

	// ───────────────────────────────────────────────────────────
	// Initialise
	// ───────────────────────────────────────────────────────────

	doc.addEventListener('DOMContentLoaded', function () {
		initMobileMenu();
		initSmoothScroll();
		initScrollAnimations();
		initStickyHeader();
	});
})();
