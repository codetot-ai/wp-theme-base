export default {
	testEnvironment: 'jsdom',
	transform: {},
	extensionsToTreatAsEsm: [],
	moduleNameMapper: {
		'^lib/(.*)$': '<rootDir>/src/js/lib/$1',
	},
	testMatch: [
		'<rootDir>/src/js/**/*.test.js',
		'<rootDir>/tests/**/*.test.js',
	],
};
