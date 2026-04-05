import { selectAll, removeClass } from 'lib/dom'
import Tabs from 'lib/tabs'

export default el => {
	const contentEls = selectAll('[role="tabpanel"]', el)

	Tabs(el)

	removeClass('d-none', contentEls)
}
