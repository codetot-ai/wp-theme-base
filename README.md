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
│   ├── blocks/                # Reusable block components (23 blocks)
│   ├── layouts/               # Page/single layout wrappers
│   └── core-blocks/           # WP core block overrides (image, button, swiper)
│
├── src/
│   ├── js/
│   │   ├── main.js            # Entry point (imports + DOMContentLoaded)
│   │   ├── modules/           # Block init functions (faq, reviews-slider)
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

Vite produces three bundles in `assets/dist/`:

| File | Source | Purpose |
|------|--------|---------|
| `main.js` | `src/js/main.js` | Theme JS (block init, modules) |
| `font.css` | `src/scss/font.scss` | Font-face declarations |
| `bootstrap.css` | `src/bootstrap/bootstrap.scss` | Selective Bootstrap 5 |
| `frontend.css` | `src/postcss/frontend.css` | Theme styles (PostCSS) |

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

### Available Blocks (23)

| Block | File | Has JS | Description |
|-------|------|--------|-------------|
| breadcrumb | `breadcrumb.php` | No | Breadcrumb navigation |
| content-area | `content-area.php` | No | Rich text content section |
| faq | `faq.php` | Yes | Accordion FAQ with schema.org |
| feature-grid | `feature-grid.php` | No | Grid of feature cards |
| feature-item | `feature-item.php` | No | Single feature card |
| feature-list-two-up | `feature-list-two-up.php` | No | Two-column feature list |
| footer | `footer.php` | No | Site footer |
| header | `header.php` | No | Site header + navigation |
| hero-image | `hero-image.php` | No | Hero with image + text |
| hero-post | `hero-post.php` | No | Hero from post data |
| hero-title | `hero-title.php` | No | Simple hero with title |
| highlight-section | `highlight-section.php` | No | Highlighted content section |
| listing-section | `listing-section.php` | No | Content listing with sidebar |
| logo-grid | `logo-grid.php` | No | Partner/client logo grid |
| post-card | `post-card.php` | No | Single post card |
| post-grid | `post-grid.php` | No | Grid of post cards |
| post-grid-loop | `post-grid-loop.php` | No | Post grid with WP loop |
| pricing-table | `pricing-table.php` | No | Pricing comparison table |
| review-item | `review-item.php` | No | Single review/testimonial |
| reviews-slider | `reviews-slider.php` | Yes | Swiper testimonial carousel |
| statistics | `statistics.php` | No | Number statistics section |
| step-section | `step-section.php` | No | Step-by-step process |
| two-up-image | `two-up-image.php` | No | Two-column image + text |

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
- **JS:** ES6 modules, Swiper 11
- **PHP:** WordPress coding standards via PHPCS
- **Linting:** ESLint 10, Stylelint 17, Prettier 3
- **Testing:** Jest 30 (jsdom)
- **Git hooks:** Husky 9 + lint-staged
