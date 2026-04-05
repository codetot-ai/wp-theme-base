const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const BrowserSyncPlugin = require('browser-sync-webpack-plugin');
const StyleLintPlugin = require('stylelint-webpack-plugin');
const CssMinimizerPlugin = require('css-minimizer-webpack-plugin');
const postcssMixins = require('postcss-mixins');
const postcssPresetEnv = require('postcss-preset-env');
const sass = require('sass');
const devMode = process.env.NODE_ENV !== 'production';

module.exports = {
	entry: {
		frontend: path.resolve(process.cwd(), './src/frontend.js'),
		bootstrap: path.resolve(process.cwd(), './src/bootstrap.js'),
	},
	output: {
		path: path.resolve(__dirname, 'assets'),
		filename: !devMode ? './js/[name].min.js' : './js/[name].js',
		clean: true,
	},
	watch: devMode,
	devtool: 'eval-cheap-source-map',
	resolve: {
		alias: {
			lib: path.resolve(process.cwd(), './src/js/lib/'),
			blocks: path.resolve(process.cwd(), './src/js/blocks/'),
			modules: path.resolve(process.cwd(), './src/js/modules/'),
		},
		extensions: ['.js'],
	},
	module: {
		rules: [
			{
				test: /\.js$/,
				exclude: /(node_modules)/,
				resolve: {
					extensions: ['.js'],
				},
				use: {
					loader: 'babel-loader',
				},
			},
			{
				test: /\.(scss)$/,
				use: [
					devMode ? 'style-loader' : MiniCssExtractPlugin.loader,
					{
						loader: 'css-loader',
						options: {
							sourceMap: true,
						},
					},
					{
						// Run postcss actions
						loader: 'postcss-loader',
						options: {
							postcssOptions: {
								plugins: [require('autoprefixer'), require('postcss-import')],
							},
						},
					},
					{
						loader: 'sass-loader',
						options: {
							implementation: sass,
							api: 'modern',
							sassOptions: {
								quietDeps: true,
								silenceDeprecations: ['import', 'legacy-js-api', 'global-builtin', 'color-functions'],
							},
						},
					},
				],
			},
			{
				test: /\.css$/,
				use: [
					devMode ? 'style-loader' : MiniCssExtractPlugin.loader,
					{
						loader: 'css-loader',
						options: {
							sourceMap: true,
						},
					},
					{
						loader: 'postcss-loader',
						options: {
							postcssOptions: {
								plugins: [
									require('autoprefixer'),
									require('postcss-import'),
									postcssMixins({
										mixinsDir: path.join(__dirname, 'src/postcss/mixins'),
									}),
									postcssPresetEnv({
										importFrom: path.join(
											__dirname,
											'src/postcss/variables.css'
										),
										exportTo: 'variables.css',
										stage: 1,
										features: {
											'custom-media-queries': true,
											'nesting-rules': true,
										},
									}),
								],
							},
						},
					},
				],
			},
		],
	},
	optimization: {
		minimizer: [new CssMinimizerPlugin()],
	},
	plugins: [
		new MiniCssExtractPlugin({
			filename: devMode ? './css/[name].css' : './css/[name].min.css',
		}),
		// Lint CSS.
		new StyleLintPlugin({
			context: path.resolve(process.cwd(), './src/postcss/'),
			files: '**/*.css',
		}),
		new BrowserSyncPlugin(
			{
				host: 'localhost',
				port: 3000,
				// Update this URL to match your local dev environment
				proxy: {
					target: 'http://localhost:10086/',
					proxyReq: [
						(proxyReq) => {
							proxyReq.setHeader('X-WP-Theme-Env', process.env.NODE_ENV);
						},
					],
				},
				files: [
					{
						match: ['**/*.css'],
						fn: function (event, file) {
							if (event === 'change') {
								const bs = require('browser-sync').get('bs-webpack-plugin');
								bs.reload('*.css');
							}
						},
					},
				],
				injectChanges: true,
				notify: false,
				open: false,
			},
			{
				reload: false,
				injectCss: true,
			}
		),
	],
	externals: {
		jQuery: 'jQuery',
	},
};
