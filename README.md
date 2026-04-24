# WP Theme Base

WordPress starter theme with selective Bootstrap 5, PostCSS pipeline, Vite build system, and full linting/testing toolchain.

## Quick Start

```bash
# 1. Clone and remove git tracking
git clone git@github.com:codetot-ai/wp-theme-base.git your-theme-name
cd your-theme-name
rm -rf .git/

# 2. Run setup (interactive)
bash setup.sh

# 3. Install dependencies and build
composer install
npm install
npm run build
```

### Non-Interactive Setup (for CI/scripts/AI agents)

```bash
bash setup.sh \
  --name "Client Theme" \
  --slug "client-theme" \
  --namespace "ClientTheme" \
  --url "http://client.test" \
  --author "Your Name" \
  --composer "your-org/client-theme"
```

## What `setup.sh` Does

Replaces all generic placeholders with your project-specific values:

| Placeholder | Example |
|------------|---------|
| Theme name (`WP Theme Base`) | `Client Theme` |
| Text domain / prefix (`codetot`) | `client-theme` |
| PHP namespace (`Codetot`) | `ClientTheme` |
| Constants (`CODETOT_`) | `CLIENT_THEME_` |
| Author (`CODE TOT`) | `Your Name` |
| Composer package (`codetot-ai/wp-theme-base`) | `your-org/client-theme` |

The script self-deletes after successful execution.

## Project Structure

```
/
├── functions.php              # Theme bootstrap (constants + requires)
├── style.css                  # Theme metadata
├── header.php / footer.php    # Global templates
├── index.php / 404.php        # Fallback templates
├── page-*.php                 # Page templates (one per design)
│
├── inc/
│   ├── setup.php              # Theme supports, menus, image sizes
│   ├── cleanup.php            # Remove WP bloat
│   ├── enqueue.php            # CSS/JS loading strategy
│   ├── critical-css.php       # Inline critical CSS
│   ├── helpers/
│   │   ├── env.php            # Environment detection
│   │   ├── cdn.php            # CDN URL helpers
│   │   ├── formatting.php     # Text formatting
│   │   └── template-tags.php  # SVG icons, reusable components
│   └── layouts/
│       ├── global.php         # Header/footer layout hooks
│       ├── archive.php        # Archive page layout
│       └── singular.php       # Single post/page layout
│
├── templates/
│   ├── blocks/                # Reusable block components (12 blocks)
│   ├── layouts/               # Page/single layout wrappers
│   └── core-blocks/           # WP core block overrides (image, button, swiper)
│
├── src/
│   ├── js/
│   │   ├── main.js            # Entry point (imports + DOMContentLoaded)
│   │   ├── modules/           # Block init functions
│   │   └── lib/               # Utilities (dom, tabs, utils)
│   ├── postcss/
│   │   ├── frontend.css       # Main entry (imports all below)
│   │   ├── variables.css      # CSS custom properties + custom media
│   │   ├── global/            # Reset, base styles
│   │   ├── blocks/            # One CSS file per block component
│   │   ├── core-blocks/       # WP core block style overrides
│   │   ├── mixins/            # PostCSS mixins
│   │   └── theme/             # Theme-specific overrides
│   ├── bootstrap/             # Selective Bootstrap 5 SCSS
│   └── scss/                  # Font handling (SCSS)
│
├── assets/dist/               # Vite build output (gitignored)
├── acf-json/                  # ACF field group storage
├── images/                    # Theme images
│
├── vite.config.js             # Build configuration
├── postcss.config.cjs         # PostCSS pipeline
├── eslint.config.js           # ESLint (flat config)
├── .stylelintrc.json          # Stylelint for PostCSS
├── .prettierrc                # Prettier formatting
├── jest.config.js             # Jest test configuration
├── phpcs.xml                  # PHP CodeSniffer rules
├── .editorconfig              # Editor settings
├── .husky/pre-commit          # Pre-commit hook (lint-staged)
├── setup.sh                   # One-time project setup
├── package.json               # Node dependencies + scripts
└── composer.json              # PHP dependencies + scripts
```

## Development

### Daily Commands

```bash
npm run dev          # Watch mode (Vite)
npm run build        # Production build
```

### Linting & Formatting

```bash
# Lint everything
npm run lint         # ESLint (JS) + Stylelint (CSS)
composer lint        # PHPCS (PHP)

# Auto-fix
npm run lint:fix     # Fix JS + CSS lint issues
composer lint:fix    # Fix PHP lint issues
npm run format       # Prettier format JS + CSS

# Check without fixing
npm run format:check # Prettier dry-run

# Full validation (CI-ready)
npm run validate     # lint + format:check + build
```

### Testing

```bash
npm run test         # Jest unit tests
npm run test:watch   # Jest in watch mode
```

### Pre-Commit Hook

Husky + lint-staged runs automatically on `git commit`:
- `*.php` files → `composer lint`
- `src/**/*.js` → ESLint fix + Prettier
- `src/**/*.css` → Stylelint fix + Prettier

### Build Output

Vite produces bundles in `assets/dist/`:

| File | Source | Size | Purpose |
|------|--------|------|---------|
| `main.js` | `src/js/main.js` | ~0.05 KB | Theme JS (block init, modules) |
| `font.css` | `src/scss/font.scss` | — | Font-face declarations |
| `bootstrap.css` | `src/bootstrap/bootstrap.scss` | — | Selective Bootstrap 5 |
| `frontend.css` | `src/postcss/frontend.css` | ~6.8 KB | Theme styles (PostCSS) |
| `critical.css` | Generated | ~1.8 KB | Inlined critical CSS |

## Block System

Blocks are reusable UI components with up to 3 files:

### 1. PHP Template — `templates/blocks/{name}.php`

```php
<?php
$data = wp_parse_args($args, [
    'class' => '',
    'title' => '',
    'items' => [],
]);

$_class = 'my-block';
$_class .= ! empty($data['class']) ? ' ' . esc_attr($data['class']) : '';
?>
<div class="<?php echo esc_attr($_class); ?>" data-block="my-block">
    <div class="container">
        <h2 class="my-block__title"><?php echo esc_html($data['title']); ?></h2>
    </div>
</div>
```

**Conventions:**
- `wp_parse_args($args, [...])` for data with defaults
- BEM naming: `{block-name}__{element}`
- `data-block="{name}"` attribute (only if JS is needed)
- Bootstrap 5 utilities for layout (`container`, `row`, `col-*`)
- Proper escaping: `esc_html()`, `esc_attr()`, `wp_kses_post()`

### 2. PostCSS — `src/postcss/blocks/{name}.css`

```css
.my-block__title {
    margin-bottom: 1.5rem;
}

.my-block__item {
    & .icon {
        transition: all 0.3s ease;
    }
}

@media (--md) {
    .my-block {
        padding: 4rem 0;
    }
}
```

Register in `src/postcss/blocks/index.css`:
```css
@import url('my-block.css');
```

### 3. JS Module (optional) — `src/js/modules/{name}.js`

```js
export function initMyBlock() {
    const elements = document.querySelectorAll('[data-block="my-block"]');
    if (!elements.length) return;

    elements.forEach((el) => {
        // Block initialization
    });
}
```

Register in `src/js/main.js`:
```js
import { initMyBlock } from './modules/my-block';

document.addEventListener('DOMContentLoaded', () => {
    initMyBlock();
});
```

### Using Blocks in Templates

```php
get_template_part('templates/blocks/my-block', null, [
    'title' => 'Section Title',
    'items' => $items_array,
]);
```

### Available Blocks (12)

| Block | File | Has JS | Description |
|-------|------|--------|-------------|
| header | `header.php` | No | Site header + navigation |
| footer | `footer.php` | No | Site footer |
| breadcrumb | `breadcrumb.php` | No | Breadcrumb navigation |
| content-area | `content-area.php` | No | Generic content section |
| post-card | `post-card.php` | No | Single post card |
| post-grid | `post-grid.php` | No | Grid of post cards |
| post-grid-loop | `post-grid-loop.php` | No | Post grid loop |
| post-header | `post-header.php` | No | Title + meta + featured image |
| post-content | `post-content.php` | No | Post body with typography |
| post-footer | `post-footer.php` | No | Tags + author bio |
| sidebar | `sidebar.php` | No | Widget area wrapper |
| related-posts | `related-posts.php` | No | Related posts by category |

### Cherry-picking blocks from feature/blocks

The `feature/blocks` branch contains 16 additional blocks for marketing pages, landing pages, and rich layouts. Cherry-pick only what you need.

#### Full file manifest

| Block | PHP | CSS | JS |
|-------|-----|-----|----|
| faq | `templates/blocks/faq.php` | `src/postcss/blocks/faq.css` | `src/js/modules/faq.js` + `src/js/blocks/faq.js` |
| feature-grid | `templates/blocks/feature-grid.php` | `src/postcss/blocks/feature-grid.css` | — |
| feature-item | `templates/blocks/feature-item.php` | — | — |
| feature-list-two-up | `templates/blocks/feature-list-two-up.php` | `src/postcss/blocks/feature-list-two-up.css` | — |
| hero-image | `templates/blocks/hero-image.php` | — | — |
| hero-post | `templates/blocks/hero-post.php` | — | — |
| hero-title | `templates/blocks/hero-title.php` | `src/postcss/blocks/hero-title.css` | — |
| highlight-section | `templates/blocks/highlight-section.php` | `src/postcss/blocks/highlight-section.css` | — |
| listing-section | `templates/blocks/listing-section.php` | `src/postcss/blocks/listing-section.css` | — |
| logo-grid | `templates/blocks/logo-grid.php` | `src/postcss/blocks/logo-grid.css` | — |
| pricing-table | `templates/blocks/pricing-table.php` | `src/postcss/blocks/pricing-table.css` | — |
| review-item | `templates/blocks/review-item.php` | `src/postcss/blocks/review-item.css` | — |
| reviews-slider | `templates/blocks/reviews-slider.php` | `src/postcss/blocks/reviews-slider.css` | `src/js/modules/reviews-slider.js` + `src/js/blocks/reviews-slider.js` |
| statistics | `templates/blocks/statistics.php` | `src/postcss/blocks/statistics.css` | — |
| step-section | `templates/blocks/step-section.php` | `src/postcss/blocks/step-section.css` | — |
| two-up-image | `templates/blocks/two-up-image.php` | `src/postcss/blocks/two-up-image.css` | — |

#### Example 1: FAQ block (PHP + CSS + JS)

```bash
# Cherry-pick files from feature/blocks
git checkout feature/blocks -- \
  templates/blocks/faq.php \
  src/postcss/blocks/faq.css \
  src/js/modules/faq.js \
  src/js/blocks/faq.js
```

Add to `src/postcss/blocks/index.css`:
```css
@import url('faq.css');
```

Add to `src/js/main.js`:
```js
import { initFaq } from './modules/faq';

document.addEventListener('DOMContentLoaded', () => {
    initFaq();
});
```

#### Example 2: Hero Image block (PHP only)

```bash
git checkout feature/blocks -- \
  templates/blocks/hero-image.php
```

No CSS or JS needed — this block uses only Bootstrap utilities.

#### Example 3: Reviews Slider block (PHP + CSS + JS + Swiper dependency)

```bash
# Cherry-pick files
git checkout feature/blocks -- \
  templates/blocks/reviews-slider.php \
  src/postcss/blocks/reviews-slider.css \
  src/js/modules/reviews-slider.js \
  src/js/blocks/reviews-slider.js

# Install Swiper dependency
npm install swiper
```

Add to `src/postcss/blocks/index.css`:
```css
@import url('reviews-slider.css');
```

Add to `src/js/main.js`:
```js
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
import { initReviewsSlider } from './modules/reviews-slider';

document.addEventListener('DOMContentLoaded', () => {
    initReviewsSlider();
});
```

## CSS Architecture

Two separate pipelines merged at build time:

### Bootstrap 5 (Selective)

Only essential modules (`src/bootstrap/bootstrap.scss`):
- `reboot` — CSS reset
- `type` — Typography
- `containers` + `grid` — Layout
- `buttons` — Button styles
- `helpers` + `utilities/api` — Utility classes

Customize via `src/bootstrap/bootstrap-variables.scss`.

### PostCSS Pipeline

```
src/postcss/frontend.css
├── variables.css          → CSS custom properties + @custom-media
├── global/index.css       → Reset, base styles
├── core-blocks/index.css  → WP core block overrides
├── blocks/index.css       → Block component styles
└── theme/index.css        → Theme-specific overrides
```

**PostCSS features:**
- `postcss-import` — `@import url()` resolution
- `postcss-mixins` — `@mixin` / `@define-mixin`
- `postcss-preset-env` — Nesting (`&`), `@custom-media`, modern CSS
- `autoprefixer` — Vendor prefixes

**Custom media queries** (`variables.css`):

| Token | Breakpoint |
|-------|-----------|
| `--s` | 480px |
| `--sm` | 600px |
| `--md` | 782px |
| `--lg` | 960px |
| `--xl` | 1080px |
| `--xxl` | 1280px |
| `--xxxl` | 1440px |

## Requirements

- Node.js 18+
- PHP 8.0+ (8.2+ recommended)
- WordPress 6.9+
- Composer 2+

## Tech Stack

- **Build:** Vite 6
- **CSS:** Bootstrap 5.2 (selective SCSS) + PostCSS (custom properties, nesting, mixins)
- **JS:** ES6 modules
- **PHP:** WordPress coding standards via PHPCS
- **Linting:** ESLint 10, Stylelint 17, Prettier 3
- **Testing:** Jest 30 (jsdom)
- **Git hooks:** Husky 9 + lint-staged
