import { selectAll, getData } from './dom'

// Store instances associated with their DOM elements
const instances = new WeakMap()

/**
 * Default block loader using Webpack's dynamic require
 * @param {string} name
 * @returns {Function|null}
 */
const defaultLoader = (name) => {
  try {
    // eslint-disable-next-line
    return require(`blocks/${name}.js`).default
  } catch (e) {
    console.warn(`Block "${name}" not found.`, e)
    return null
  }
}

/**
 * Initialize blocks within a context
 * @param {HTMLElement} context - The DOM element to search within (default: document)
 * @param {Function} loader - Function that takes a block name and returns the module/constructor
 * @returns {Object} Manager object with getInstance and unmount methods
 */
export default function initBlocks(context = document, loader = defaultLoader) {
  // Find all elements with data-block attribute
  const elements = selectAll('[data-block]', context)

  elements.forEach(el => {
    // Skip if already initialized
    if (instances.has(el)) {
      return
    }

    const blockName = getData('block', el)
    if (!blockName) return

    const Block = loader(blockName)

    if (typeof Block === 'function') {
      try {
        const instance = Block(el)
        
        // Store instance if returned
        if (instance) {
          instances.set(el, instance)
          
          // Remove the data attribute to mark as processed (optional, but keeps DOM clean)
          // el.removeAttribute('data-block') 
          // Keeping it might be useful for styling or debugging, 
          // but the WeakMap prevents re-init anyway.
        }
      } catch (e) {
        console.error(`Error initializing block "${blockName}":`, e)
      }
    }
  })

  return {
    /**
     * Get the block instance associated with a DOM element
     * @param {HTMLElement} el
     * @returns {Object|undefined}
     */
    getInstance: (el) => instances.get(el),

    /**
     * Unmount all blocks initialized in this batch
     */
    unmount: () => {
      elements.forEach(el => {
        const instance = instances.get(el)
        if (instance && typeof instance.unmount === 'function') {
          try {
            instance.unmount()
          } catch (e) {
            console.error('Error unmounting block:', e)
          }
        }
        instances.delete(el)
      })
    }
  }
}
