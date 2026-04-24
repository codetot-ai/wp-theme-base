# Block System Guide

> For AI agents and developers working with wp-theme-base projects.

## Branch Strategy

| Branch | Blocks | Purpose |
|--------|--------|---------|
| `master` | 12 structural blocks | Clean base — use for every project |
| `feature/blocks` | 16 extra blocks | Cherry-pick into projects as needed |

## Master Blocks (12)

Always available. No cherry-picking needed.

### Structural

| Block | File | Description |
|-------|------|-------------|
| header | `templates/blocks/header.php` | Site header + navigation |
| footer | `templates/blocks/footer.php` | Site footer |
| breadcrumb | `templates/blocks/breadcrumb.php` | Breadcrumb navigation |
| content-area | `templates/blocks/content-area.php` | Generic content with column wrapper |

### Archive (listing pages)

| Block | File | Description |
|-------|------|-------------|
| post-card | `templates/blocks/post-card.php` | Single post card (thumbnail + title) |
| post-grid | `templates/blocks/post-grid.php` | Grid wrapper for post cards |
| post-grid-loop | `templates/blocks/post-grid-loop.php` | Loop that renders post cards |

### Single Post

| Block | File | CSS | Description |
|-------|------|-----|-------------|
| post-header | `templates/blocks/post-header.php` | `src/postcss/blocks/post-header.css` | Title, categories, date/author, featured image |
| post-content | `templates/blocks/post-content.php` | `src/postcss/blocks/post-content.css` | Post body with typography |
| post-footer | `templates/blocks/post-footer.php` | `src/postcss/blocks/post-footer.css` | Tags + author bio |
| sidebar | `templates/blocks/sidebar.php` | `src/postcss/blocks/sidebar.css` | Widget area wrapper |
| related-posts | `templates/blocks/related-posts.php` | `src/postcss/blocks/related-posts.css` | Related posts by shared category |

## Feature/Blocks — Full File Manifest

Cherry-pick these into your project. Each block may have PHP, CSS, and/or JS files — copy ALL files for the block.

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

## How to Cherry-Pick a Block

### Step 1: Copy files from feature/blocks

```bash
# From your project's theme directory (after setup.sh)
git remote add upstream git@github.com:codetot-ai/wp-theme-base.git
git fetch upstream feature/blocks

# Copy a block — include ALL its files (PHP + CSS + JS)
git checkout upstream/feature/blocks -- \
  templates/blocks/BLOCK_NAME.php \
  src/postcss/blocks/BLOCK_NAME.css      # if it exists
  src/js/modules/BLOCK_NAME.js           # if it exists
  src/js/blocks/BLOCK_NAME.js            # if it exists
```

### Step 2: Register CSS

Add to `src/postcss/blocks/index.css`:
```css
@import url('BLOCK_NAME.css');
```

### Step 3: Register JS (if block has JS)

Add to `src/js/main.js`:
```js
import { initBlockName } from './modules/block-name';

document.addEventListener('DOMContentLoaded', () => {
    initBlockName();
});
```

### Step 4: Build

```bash
npm run build
```

## Examples

### Add faq (PHP + CSS + JS)

```bash
git checkout upstream/feature/blocks -- \
  templates/blocks/faq.php \
  src/postcss/blocks/faq.css \
  src/js/modules/faq.js \
  src/js/blocks/faq.js
```

`src/postcss/blocks/index.css` — add:
```css
@import url('faq.css');
```

`src/js/main.js` — add:
```js
import { initFaq } from './modules/faq';
// call initFaq() inside DOMContentLoaded
```

### Add hero-image (PHP only — no CSS or JS)

```bash
git checkout upstream/feature/blocks -- templates/blocks/hero-image.php
```

No CSS/JS registration needed. Use directly:
```php
get_template_part('templates/blocks/hero-image', null, [
    'title' => 'Page Title',
    'image' => get_field('hero_image'),
]);
```

### Add reviews-slider (PHP + CSS + JS + Swiper)

```bash
# Install Swiper dependency
npm install swiper

# Copy block files
git checkout upstream/feature/blocks -- \
  templates/blocks/reviews-slider.php \
  src/postcss/blocks/reviews-slider.css \
  src/js/modules/reviews-slider.js \
  src/js/blocks/reviews-slider.js
```

`src/postcss/blocks/index.css` — add:
```css
@import url('reviews-slider.css');
```

`src/js/main.js` — add:
```js
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
import { initReviewsSlider } from './modules/reviews-slider';
// call initReviewsSlider() inside DOMContentLoaded
```

## Block Conventions

When creating new blocks, follow these patterns:

### PHP (`templates/blocks/{name}.php`)

```php
<?php
/**
 * Block: Block Name
 *
 * @package codetot
 * @author codetot
 */

$data = wp_parse_args(
    $args, [
        'class' => '',
        'title' => '',
    ]
);

$_class = 'block-name';
$_class .= ! empty($data['class']) ? ' ' . esc_attr($data['class']) : '';
?>
<div class="<?php echo esc_attr($_class); ?>">
    <div class="container">
        <h2 class="block-name__title"><?php echo esc_html($data['title']); ?></h2>
    </div>
</div>
```

Rules:
- `wp_parse_args($args, [...])` with sensible defaults
- `$_class` pattern for class building
- BEM: `{block-name}__{element}`
- `data-block="{name}"` only if JS is needed
- Escape everything: `esc_html()`, `esc_attr()`, `wp_kses_post()`, `esc_url()`
- Bootstrap 5 utilities for layout

### CSS (`src/postcss/blocks/{name}.css`)

```css
.block-name__title {
    margin-bottom: 1.5rem;
}

.block-name__item {
    & .icon {
        transition: all 0.3s ease;
    }
}

@media (--md) {
    .block-name {
        padding: 4rem 0;
    }
}
```

Rules:
- BEM naming matching PHP
- PostCSS nesting with `&`
- Custom media: `--md` (782px), `--lg` (960px), `--xl` (1080px)
- CSS custom properties: `var(--bs-primary)`, `var(--primary)`
- Must register in `src/postcss/blocks/index.css`

### JS (`src/js/modules/{name}.js`) — only if interactive

```js
export function initBlockName() {
    const elements = document.querySelectorAll('[data-block="block-name"]');
    if (!elements.length) return;

    elements.forEach((el) => {
        // initialization
    });
}
```

Rules:
- Named export: `initPascalCase()`
- Query by `[data-block="block-name"]`
- Guard with `if (!elements.length) return`
- Vanilla JS only (no jQuery)
- Must register in `src/js/main.js`

## Validation

After adding or creating blocks, always validate:

```bash
# PHP
vendor/bin/phpcs --standard=phpcs.xml templates/blocks/BLOCK_NAME.php

# CSS
./node_modules/.bin/stylelint src/postcss/blocks/BLOCK_NAME.css

# JS
npx eslint src/js/modules/BLOCK_NAME.js

# Build
npm run build
```
