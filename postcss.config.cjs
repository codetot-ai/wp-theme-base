const path = require('path');

module.exports = {
	plugins: [
		require('postcss-import'),
		require('postcss-mixins')({
			mixinsDir: path.join(__dirname, 'src/postcss/mixins'),
		}),
		require('postcss-preset-env')({
			importFrom: path.join(__dirname, 'src/postcss/variables.css'),
			stage: 1,
			features: {
				'custom-media-queries': true,
				'nesting-rules': true,
			},
		}),
		require('autoprefixer'),
	],
};
