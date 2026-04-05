/**
 * Critical CSS Generator
 *
 * Extracts above-the-fold CSS for each page type.
 * Requires the site to be running (LocalWP or production).
 *
 * Usage:
 *   npm run critical                                    # uses default
 *   SITE_URL=http://mytheme.test npm run critical       # local dev
 *   SITE_URL=https://example.com npm run critical       # production
 */

import { generate } from 'critical';
import path from 'path';
import fs from 'fs';
import { fileURLToPath } from 'url';

const __dirname = path.dirname(fileURLToPath(import.meta.url));

const BASE_URL = process.env.SITE_URL || 'http://localhost';
const DIST_DIR = path.resolve(__dirname, '..', 'assets', 'dist');
const CRITICAL_DIR = path.resolve(DIST_DIR, 'critical');

// Page types to generate critical CSS for.
// Customize URLs for your site.
const pages = [
	{ name: 'front-page', url: '/' },
	{ name: 'page', url: '/sample-page/' },
	{ name: 'single', url: '/hello-world/' },
	{ name: 'archive', url: '/blog/' },
];

async function generateCritical() {
	if (!fs.existsSync(CRITICAL_DIR)) {
		fs.mkdirSync(CRITICAL_DIR, { recursive: true });
	}

	console.log('Generating critical CSS...\n');

	for (const page of pages) {
		const url = BASE_URL + page.url;
		const outputFile = path.resolve(CRITICAL_DIR, page.name + '.css');

		try {
			console.log(`  ${page.name}: ${url}`);

			const { css } = await generate({
				src: url,
				width: 1440,
				height: 900,
				inline: false,
				extract: false,
				penthouse: {
					timeout: 60000,
				},
			});

			fs.writeFileSync(outputFile, css);
			const size = (Buffer.byteLength(css) / 1024).toFixed(1);
			console.log(`    -> ${outputFile} (${size} KB)\n`);
		} catch (err) {
			console.error(`    ERROR for ${page.name}: ${err.message}\n`);
		}
	}

	console.log('Done.');
}

generateCritical();
