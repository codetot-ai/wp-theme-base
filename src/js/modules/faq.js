export function initFaq() {
	const elements = document.querySelectorAll('[data-block="faq"]');
	if (!elements.length) return;

	elements.forEach((el) => {
		const contentEls = el.querySelectorAll('[role="tabpanel"]');
		const triggerEls = el.querySelectorAll('[role="tab"]');

		contentEls.forEach((panel) => panel.classList.remove('d-none'));

		triggerEls.forEach((trigger) => {
			trigger.addEventListener('click', () => {
				const targetId = trigger.getAttribute('aria-controls');
				const target = el.querySelector('#' + targetId);
				if (!target) return;

				const isExpanded =
					trigger.getAttribute('aria-expanded') === 'true';
				trigger.setAttribute('aria-expanded', !isExpanded);
				target.hidden = isExpanded;
			});
		});
	});
}
