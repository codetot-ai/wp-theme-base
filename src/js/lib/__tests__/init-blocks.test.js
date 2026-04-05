import initBlocks from '../init-blocks'

describe('init-blocks', () => {
  let container

  beforeEach(() => {
    container = document.createElement('div')
    document.body.appendChild(container)
  })

  afterEach(() => {
    document.body.removeChild(container)
    container = null
  })

  test('initializes blocks found in DOM using provided loader', () => {
    container.innerHTML = '<div data-block="test-block"></div>'
    const el = container.querySelector('div')
    
    // Mock block constructor
    const MockBlock = jest.fn(element => ({
      el: element,
      displayName: 'TestBlock'
    }))

    // Mock loader function
    const mockLoader = jest.fn(name => {
      if (name === 'test-block') return MockBlock
      return null
    })
    
    const manager = initBlocks(container, mockLoader)

    expect(mockLoader).toHaveBeenCalledWith('test-block')
    expect(MockBlock).toHaveBeenCalledWith(el)
    
    // Verify instance is stored
    const instance = manager.getInstance(el)
    expect(instance).toBeDefined()
    expect(instance.displayName).toBe('TestBlock')
  })

  test('does not re-initialize already initialized blocks', () => {
    container.innerHTML = '<div data-block="test-block"></div>'
    const el = container.querySelector('div')
    
    const MockBlock = jest.fn(() => ({}))
    const mockLoader = jest.fn(() => MockBlock)
    
    // First init
    initBlocks(container, mockLoader)
    expect(MockBlock).toHaveBeenCalledTimes(1)
    
    // Second init
    initBlocks(container, mockLoader)
    expect(MockBlock).toHaveBeenCalledTimes(1) // Should still be 1
  })

  test('handles loader returning null (block not found)', () => {
    container.innerHTML = '<div data-block="unknown-block"></div>'
    const mockLoader = jest.fn(() => null)
    
    // Should not throw
    expect(() => initBlocks(container, mockLoader)).not.toThrow()
  })

  test('unmounts blocks', () => {
    container.innerHTML = '<div data-block="test-block"></div>'
    const el = container.querySelector('div')
    
    const unmountSpy = jest.fn()
    const MockBlock = jest.fn(() => ({
      unmount: unmountSpy
    }))
    const mockLoader = jest.fn(() => MockBlock)
    
    const manager = initBlocks(container, mockLoader)
    manager.unmount()
    
    expect(unmountSpy).toHaveBeenCalled()
    expect(manager.getInstance(el)).toBeUndefined()
  })
})
