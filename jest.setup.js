// Jest setup file
// Add custom matchers or global test utilities here

// Mock console methods to reduce noise in tests
global.console = {
  ...console,
  warn: jest.fn(),
  error: jest.fn(),
  log: jest.fn(),
  trace: jest.fn()
}
