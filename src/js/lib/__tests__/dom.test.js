import {
  addClass,
  removeClass,
  hasClass,
  toggleClass,
  append,
  prepend,
  appendHtml,
  createNodes,
  setAttribute,
  getAttribute,
  getData,
  setData,
  setStyle,
  getStyle,
  on,
  select,
  selectAll,
  remove
} from '../dom'

describe('dom', () => {
  let container

  beforeEach(() => {
    container = document.createElement('div')
    document.body.appendChild(container)
  })

  afterEach(() => {
    document.body.removeChild(container)
    container = null
  })

  describe('class manipulation', () => {
    test('addClass adds class to element', () => {
      const el = document.createElement('div')
      addClass('test-class', el)
      expect(el.classList.contains('test-class')).toBe(true)
    })

    test('removeClass removes class from element', () => {
      const el = document.createElement('div')
      el.classList.add('test-class')
      removeClass('test-class', el)
      expect(el.classList.contains('test-class')).toBe(false)
    })

    test('hasClass checks if element has class', () => {
      const el = document.createElement('div')
      el.classList.add('test-class')
      expect(hasClass('test-class', el)).toBe(true)
      expect(hasClass('other-class', el)).toBe(false)
    })

    test('toggleClass toggles class', () => {
      const el = document.createElement('div')
      toggleClass('test-class', el)
      expect(el.classList.contains('test-class')).toBe(true)
      toggleClass('test-class', el)
      expect(el.classList.contains('test-class')).toBe(false)
    })
  })

  describe('DOM manipulation', () => {
    test('append appends element', () => {
      const el = document.createElement('div')
      const child = document.createElement('span')
      append(el, child)
      expect(el.lastChild).toBe(child)
    })

    test('prepend prepends element', () => {
      const el = document.createElement('div')
      const child1 = document.createElement('span')
      const child2 = document.createElement('p')
      append(el, child1)
      prepend(el, child2)
      expect(el.firstChild).toBe(child2)
    })

    test('appendHtml appends HTML string', () => {
      const el = document.createElement('div')
      appendHtml(el, '<span>test</span>')
      expect(el.innerHTML).toBe('<span>test</span>')
    })

    test('createNodes creates elements from HTML string', () => {
      const nodes = createNodes('<div>1</div><div>2</div>')
      expect(nodes.length).toBe(2)
      expect(nodes[0].tagName).toBe('DIV')
      expect(nodes[1].tagName).toBe('DIV')
    })

    test('remove removes element from parent', () => {
      const el = document.createElement('div')
      const child = document.createElement('span')
      el.appendChild(child)
      remove(child)
      expect(el.contains(child)).toBe(false)
    })
  })

  describe('attributes', () => {
    test('setAttribute sets attribute', () => {
      const el = document.createElement('div')
      setAttribute('id', 'test-id', el)
      expect(el.getAttribute('id')).toBe('test-id')
    })

    test('getAttribute gets attribute', () => {
      const el = document.createElement('div')
      el.setAttribute('id', 'test-id')
      expect(getAttribute('id', el)).toBe('test-id')
    })

    test('setData sets data attribute', () => {
      const el = document.createElement('div')
      setData('test', 'value', el)
      expect(el.getAttribute('data-test')).toBe('value')
    })

    test('getData gets data attribute', () => {
      const el = document.createElement('div')
      el.setAttribute('data-test', 'value')
      expect(getData('test', el)).toBe('value')
    })
  })

  describe('styles', () => {
    test('setStyle sets style', () => {
      const el = document.createElement('div')
      setStyle('color', 'red', el)
      expect(el.style.color).toBe('red')
    })
  })

  describe('events', () => {
    test('on attaches event listener', () => {
      const el = document.createElement('div')
      const handler = jest.fn()
      on('click', handler, el)
      el.click()
      expect(handler).toHaveBeenCalled()
    })
  })

  describe('selection', () => {
    test('select selects single element', () => {
      container.innerHTML = '<div class="test"></div>'
      const el = select('.test', container)
      expect(el).not.toBeNull()
      expect(el.className).toBe('test')
    })

    test('selectAll selects all elements', () => {
      container.innerHTML = '<div class="test"></div><div class="test"></div>'
      const els = selectAll('.test', container)
      expect(els.length).toBe(2)
    })
  })
})
