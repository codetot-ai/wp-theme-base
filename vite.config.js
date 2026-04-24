import { defineConfig } from 'vite';
import { resolve } from 'path';

export default defineConfig({
	css: {
		preprocessorOptions: {
			scss: {
				api: 'modern',
				quietDeps: true,
				silenceDeprecations: [
					'import',
					'global-builtin',
					'color-functions',
					'if-function',
				],
			},
		},
	},
	build: {
		outDir: resolve(__dirname, 'assets/dist'),
		emptyOutDir: true,
		rollupOptions: {
			input: {
				main: resolve(__dirname, 'src/js/main.js'),
				critical: resolve(__dirname, 'src/postcss/critical.css'),
				font: resolve(__dirname, 'src/scss/font.scss'),
				bootstrap: resolve(__dirname, 'src/bootstrap/bootstrap.scss'),
			},
			output: {
				entryFileNames: '[name].js',
				chunkFileNames: '[name].js',
				assetFileNames: '[name][extname]',
			},
		},
		sourcemap: process.env.NODE_ENV !== 'production',
		minify: process.env.NODE_ENV === 'production' ? 'esbuild' : false,
	},
});
