import Swiper from 'swiper';
import { Navigation, Pagination } from 'swiper/modules';

export function initReviewsSlider() {
	const elements = document.querySelectorAll('[data-block="reviews-slider"]');
	if (!elements.length) return;

	elements.forEach((el) => {
		const swiperEl = el.querySelector('.js-swiper');
		if (!swiperEl) return;

		let options = {};
		try {
			options = JSON.parse(swiperEl.dataset.swiperOptions || '{}');
		} catch (e) {
			// ignore
		}

		const config = {
			modules: [Navigation, Pagination],
			...options,
		};

		const navPrev = swiperEl.querySelector('.swiper-button-prev');
		const navNext = swiperEl.querySelector('.swiper-button-next');
		if (navPrev && navNext) {
			config.navigation = { prevEl: navPrev, nextEl: navNext };
		}

		const paginationEl = swiperEl.querySelector('.swiper-pagination');
		if (paginationEl) {
			config.pagination = { el: paginationEl, clickable: true };
		}

		new Swiper(swiperEl, config);
	});
}
