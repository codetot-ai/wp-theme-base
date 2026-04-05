import { select, getData } from 'lib/dom'
import { parseOptions } from 'lib/utils'
import Swiper from 'swiper'
import { Navigation, Pagination } from 'swiper/modules'

export default el => {
	const swiperEl = select('.js-swiper', el)
	if (!swiperEl) return

	const options = parseOptions(getData('swiper-options', swiperEl), {})

	const navPrev = select('.swiper-button-prev', swiperEl)
	const navNext = select('.swiper-button-next', swiperEl)

	const swiperConfig = {
		modules: [Navigation, Pagination],
		...options,
	}

	if (navPrev && navNext) {
		swiperConfig.navigation = {
			prevEl: navPrev,
			nextEl: navNext,
		}
	}

	const paginationEl = select('.swiper-pagination', swiperEl)
	if (paginationEl) {
		swiperConfig.pagination = {
			el: paginationEl,
			clickable: true,
		}
	}

	const instance = new Swiper(swiperEl, swiperConfig)

	return {
		instance,
		unmount: () => instance.destroy(true, true),
	}
}
