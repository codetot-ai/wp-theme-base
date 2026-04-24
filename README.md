# WP Theme Base

Generic WordPress starter theme with selective Bootstrap 5 SCSS, PostCSS pipeline, and Webpack 5 build system.

## Quick Start

```bash
# 1. Clone the repo and remove git tracking
git clone git@github.com:codetot-ai/wp-theme-base.git your-theme-name
cd your-theme-name
rm -rf .git/

# 2. Run the setup script (replaces text domain, namespace, prefixes)
bash setup.sh

# 3. Install dependencies and build
npm install && npm run build
composer install
```

## What `setup.sh` Does

Interactively replaces all generic placeholders:

- **Text domain** (`codetot` → your slug)
- **Function prefix** (`codetot_` → your prefix)
- **PHP namespace** (`Codetot\Theme` → your namespace)
- **Theme name** in `style.css`
- **BrowserSync proxy URL** in `webpack.config.js`
- **Package names** in `package.json` and `composer.json`

The script self-deletes after successful execution.

## Development

```bash
npm run dev    # Watch mode with BrowserSync
npm run build  # Production build
npm run fix    # Auto-fix formatting
npm run test   # Check formatting
```

### Build Output

- `assets/css/bootstrap.min.css` — Selective Bootstrap 5
- `assets/css/frontend.min.css` — Theme styles (PostCSS)
- `assets/js/bootstrap.min.js` — Bootstrap JS entry
- `assets/js/frontend.min.js` — Theme JS with block auto-init

## Bootstrap 5 (Minimal)

Only these Bootstrap modules are included (`src/bootstrap/bootstrap.scss`):

- `reboot` — CSS reset
- `type` — Typography
- `containers` — Container classes
- `grid` — 12-column grid
- `buttons` — Button styles
- `helpers` — Visibility, clearfix, etc.
- `utilities/api` — Utility classes (spacing, display, flex, text)

To add more modules (e.g., forms, modals), add imports in `bootstrap.scss`.

Customize variables in `src/bootstrap/bootstrap-variables.scss`.

## CSS Architecture

Two separate pipelines:

1. **Bootstrap SCSS** (`src/bootstrap/`) → `bootstrap.min.css`
2. **PostCSS** (`src/postcss/`) → `frontend.min.css`
   - `variables.css` — CSS custom properties + custom media queries
   - `global/` — Reset, utilities
   - `blocks/` — One file per component
   - `core-blocks/` — WordPress core block overrides
   - `mixins/` — PostCSS mixins

## Adding Blocks

1. **CSS**: Create `src/postcss/blocks/your-block.css`, import in `blocks/index.css`
2. **PHP**: Create `templates/blocks/your-block.php` with `data-block="your-block"` attribute
3. **JS** (optional): Create `src/js/blocks/your-block.js` — auto-loaded via `init-blocks.js` when `data-block` is found in DOM

## Requirements

- Node.js 18+
- PHP 8.2+
- WordPress 6.9+
- Composer 2+

## Stack

- Webpack 5 (dual entry: frontend + bootstrap)
- Bootstrap 5.2.x (selective SCSS imports)
- PostCSS (custom properties, custom media, nesting, mixins)
- PHP 8.2+ with namespaces
- PHPCS with WordPress coding standards
