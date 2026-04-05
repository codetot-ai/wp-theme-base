import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';

import '../postcss/frontend.css';

import { initFaq } from './modules/faq';
import { initReviewsSlider } from './modules/reviews-slider';

document.addEventListener('DOMContentLoaded', () => {
	initFaq();
	initReviewsSlider();
});
