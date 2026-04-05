import tabs from '../tabs'
import { trigger } from '../dom'

describe('tabs', () => {
  let container

  beforeEach(() => {
    container = document.createElement('div')
    container.innerHTML = `
      <div class="tabs">
        <button role="tab" aria-selected="false">Tab 1</button>
        <button role="tab" aria-selected="false">Tab 2</button>
        <div role="tabpanel" aria-expanded="false">Panel 1</div>
        <div role="tabpanel" aria-expanded="false">Panel 2</div>
      </div>
    `
    document.body.appendChild(container)
  })

  afterEach(() => {
    document.body.removeChild(container)
    container = null
  })

  test('initializes tabs with first tab active', () => {
    tabs(container)
    
    const navItems = container.querySelectorAll('[role="tab"]')
    const panels = container.querySelectorAll('[role="tabpanel"]')

    expect(navItems[0].getAttribute('aria-selected')).toBe('true')
    expect(navItems[0].classList.contains('is-active')).toBe(true)
    expect(panels[0].getAttribute('aria-expanded')).toBe('true')
    expect(panels[0].classList.contains('is-active')).toBe(true)

    expect(navItems[1].getAttribute('aria-selected')).toBe('false')
    expect(panels[1].getAttribute('aria-expanded')).toBe('false')
  })

  test('switches tab on click', () => {
    tabs(container)
    
    const navItems = container.querySelectorAll('[role="tab"]')
    const panels = container.querySelectorAll('[role="tabpanel"]')

    // Click second tab
    navItems[1].click()

    expect(navItems[1].getAttribute('aria-selected')).toBe('true')
    expect(navItems[1].classList.contains('is-active')).toBe(true)
    expect(panels[1].getAttribute('aria-expanded')).toBe('true')
    expect(panels[1].classList.contains('is-active')).toBe(true)

    expect(navItems[0].getAttribute('aria-selected')).toBe('false')
    expect(panels[0].getAttribute('aria-expanded')).toBe('false')
  })

  test('loads lazy content from noscript', () => {
    container.innerHTML = `
      <div class="tabs">
        <button role="tab">Tab 1</button>
        <div role="tabpanel">
          <noscript>Lazy Content</noscript>
        </div>
      </div>
    `
    
    tabs(container, { lazyload: true })
    
    const panel = container.querySelector('[role="tabpanel"]')
    expect(panel.innerHTML).toContain('Lazy Content')
  })

  test('executes lazyload callback', () => {
    const callback = jest.fn()
    tabs(container, { 
      lazyload: true,
      lazyloadCallback: callback
    })
    
    expect(callback).toHaveBeenCalled()
  })
})
