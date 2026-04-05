import {
  returnTrue,
  returnFalse,
  identity,
  not,
  curry,
  allPass,
  anyPass,
  cond,
  ifElse,
  when,
  map,
  filter,
  partial,
  pipe,
  always,
  getProp,
  setProp,
  inArray,
  nth,
  first,
  last,
  makeArray,
  parseOptions
} from '../utils'

describe('utils', () => {
  test('returnTrue always returns true', () => {
    expect(returnTrue()).toBe(true)
  })

  test('returnFalse always returns false', () => {
    expect(returnFalse()).toBe(false)
  })

  test('identity returns the value passed', () => {
    expect(identity(5)).toBe(5)
    expect(identity('test')).toBe('test')
  })

  test('not negates the value', () => {
    expect(not(true)).toBe(false)
    expect(not(false)).toBe(true)
    expect(not(0)).toBe(true)
    expect(not(1)).toBe(false)
  })

  describe('curry', () => {
    test('curries a function', () => {
      const add = (a, b) => a + b
      const curriedAdd = curry(add)
      expect(curriedAdd(1)(2)).toBe(3)
      expect(curriedAdd(1, 2)).toBe(3)
    })
  })

  describe('allPass', () => {
    test('returns true if all predicates pass', () => {
      const isEven = x => x % 2 === 0
      const isPositive = x => x > 0
      const isEvenAndPositive = allPass([isEven, isPositive])
      expect(isEvenAndPositive(4)).toBe(true)
      expect(isEvenAndPositive(3)).toBe(false)
      expect(isEvenAndPositive(-2)).toBe(false)
    })
  })

  describe('anyPass', () => {
    test('returns true if any predicate passes', () => {
      const isEven = x => x % 2 === 0
      const isPositive = x => x > 0
      const isEvenOrPositive = anyPass([isEven, isPositive])
      expect(isEvenOrPositive(4)).toBe(true)
      expect(isEvenOrPositive(3)).toBe(true)
      expect(isEvenOrPositive(-3)).toBe(false)
    })
  })

  describe('cond', () => {
    test('executes the transformer for the matched predicate', () => {
      const fn = cond([
        [x => x === 'foo', () => 'bar'],
        [x => x === 'baz', () => 'qux']
      ])
      expect(fn('foo')).toBe('bar')
      expect(fn('baz')).toBe('qux')
      expect(fn('other')).toBeUndefined()
    })
  })

  describe('ifElse', () => {
    test('executes true branch if predicate is true', () => {
      const fn = ifElse(x => x > 0, () => 'positive', () => 'non-positive')
      expect(fn(1)).toBe('positive')
      expect(fn(-1)).toBe('non-positive')
    })
  })

  describe('when', () => {
    test('executes function if predicate is true, else returns identity', () => {
      const fn = when(x => x > 0, x => x * 2)
      expect(fn(5)).toBe(10)
      expect(fn(-5)).toBe(-5)
    })
  })

  describe('map', () => {
    test('maps over array', () => {
      const double = x => x * 2
      expect(map(double, [1, 2, 3])).toEqual([2, 4, 6])
    })

    test('applies function to single value if not array', () => {
      const double = x => x * 2
      expect(map(double, 5)).toBe(10)
    })
  })

  describe('filter', () => {
    test('filters array', () => {
      const isEven = x => x % 2 === 0
      expect(filter(isEven, [1, 2, 3, 4])).toEqual([2, 4])
    })
  })

  describe('pipe', () => {
    test('pipes functions left to right', () => {
      const add1 = x => x + 1
      const double = x => x * 2
      const fn = pipe(add1, double)
      expect(fn(2)).toBe(6) // (2 + 1) * 2 = 6
    })
  })

  describe('getProp', () => {
    test('gets property from object', () => {
      const obj = { a: 1 }
      expect(getProp('a', obj)).toBe(1)
    })
    test('is curried', () => {
      const getA = getProp('a')
      expect(getA({ a: 1 })).toBe(1)
    })
  })

  describe('setProp', () => {
    test('sets property on object', () => {
      const obj = {}
      setProp('a', 1, obj)
      expect(obj.a).toBe(1)
    })
  })

  describe('inArray', () => {
    test('checks if item is in array', () => {
      expect(inArray([1, 2, 3], 2)).toBe(true)
      expect(inArray([1, 2, 3], 4)).toBe(false)
    })
  })

  describe('nth', () => {
    test('gets nth element', () => {
      const arr = ['a', 'b', 'c']
      expect(nth(0, arr)).toBe('a')
      expect(nth(1, arr)).toBe('b')
    })
    test('gets nth element from end with negative index', () => {
      const arr = ['a', 'b', 'c']
      expect(nth(-1, arr)).toBe('c')
    })
  })

  describe('makeArray', () => {
    test('converts array-like to array', () => {
      function testArgs() {
        return makeArray(arguments)
      }
      expect(testArgs(1, 2, 3)).toEqual([1, 2, 3])
    })
  })

  describe('parseOptions', () => {
    test('parses JSON string', () => {
      const json = '{"a": 1}'
      expect(parseOptions(json)).toEqual({ a: 1 })
    })
    test('returns default if parsing fails', () => {
      const def = { b: 2 }
      expect(parseOptions('invalid', def)).toEqual({ b: 2 })
    })
  })
})
