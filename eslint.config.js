import js from '@eslint/js';

export default [
	js.configs.recommended,
	{
		languageOptions: {
			ecmaVersion: 2022,
			sourceType: 'module',
			globals: {
				document: 'readonly',
				window: 'readonly',
				console: 'readonly',
				setTimeout: 'readonly',
				clearTimeout: 'readonly',
				setInterval: 'readonly',
				clearInterval: 'readonly',
				requestAnimationFrame: 'readonly',
				IntersectionObserver: 'readonly',
				MutationObserver: 'readonly',
				ResizeObserver: 'readonly',
				HTMLElement: 'readonly',
				NodeList: 'readonly',
				Event: 'readonly',
				CustomEvent: 'readonly',
				fetch: 'readonly',
				URLSearchParams: 'readonly',
				FormData: 'readonly',
				wp: 'readonly',
			},
		},
		rules: {
			'no-unused-vars': ['warn', { argsIgnorePattern: '^_' }],
			'no-console': ['warn', { allow: ['warn', 'error'] }],
			'prefer-const': 'error',
			'no-var': 'error',
			'eqeqeq': ['error', 'always'],
		},
	},
	{
		ignores: [
			'assets/**',
			'node_modules/**',
			'vendor/**',
			'*.min.js',
		],
	},
];
