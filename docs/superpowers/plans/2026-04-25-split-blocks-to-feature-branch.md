# Split Blocks: Clean Master + Feature/Blocks Branch

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Split wp-theme-base blocks into a clean `master` (structural blocks only) and `feature/blocks` (all extra blocks preserved for cherry-picking per project).

**Architecture:** Create `feature/blocks` from current master first (preserves everything). Then clean master by removing non-essential blocks, their CSS, and their JS. Create missing single-post blocks on master. Both branches build and lint cleanly.

**Tech Stack:** PHP (WordPress), PostCSS, ES6 modules, Vite, PHPCS, ESLint, Stylelint

---

## Block Classification

### KEEP on master (structural / every-project blocks)

| Block | PHP | CSS | JS | Notes |
|-------|-----|-----|----|-------|
| header | `templates/blocks/header.php` | `src/postcss/blocks/header.css` | — | Site header + nav |
| footer | `templates/blocks/footer.php` | `src/postcss/blocks/footer.css` | — | Site footer |
| breadcrumb | `templates/blocks/breadcrumb.php` | — | — | Breadcrumb nav |
| post-card | `templates/blocks/post-card.php` | — | — | Archive card |
| post-grid | `templates/blocks/post-grid.php` | — | — | Archive grid wrapper |
| post-grid-loop | `templates/blocks/post-grid-loop.php` | — | — | Archive loop |
| content-area | `templates/blocks/content-area.php` | — | — | Generic content block (used by single + page layouts) |

### CREATE on master (new single-post blocks)

| Block | PHP | CSS | Notes |
|-------|-----|-----|-------|
| post-header | `templates/blocks/post-header.php` | `src/postcss/blocks/post-header.css` | Title + meta + featured image |
| post-content | `templates/blocks/post-content.php` | `src/postcss/blocks/post-content.css` | Post body with typography |
| post-footer | `templates/blocks/post-footer.php` | `src/postcss/blocks/post-footer.css` | Tags + share + author bio |
| sidebar | `templates/blocks/sidebar.php` | `src/postcss/blocks/sidebar.css` | Widget area wrapper |
| related-posts | `templates/blocks/related-posts.php` | `src/postcss/blocks/related-posts.css` | Related posts grid |

### MOVE to feature/blocks (remove from master, preserved on branch)

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

---

## Task 1: Create feature/blocks branch (preserve all blocks)

**Files:** None modified — branch creation only.

- [ ] **Step 1: Create the branch from current master**

```bash
cd /Users/khoipro/Workspaces/wp-theme-base
git checkout master
git pull origin master
git checkout -b feature/blocks
```

- [ ] **Step 2: Push feature/blocks to remote**

```bash
git push -u origin feature/blocks
```

- [ ] **Step 3: Switch back to master**

```bash
git checkout master
```

---

## Task 2: Create new single-post blocks on master

**Files:**
- Create: `templates/blocks/post-header.php`
- Create: `templates/blocks/post-content.php`
- Create: `templates/blocks/post-footer.php`
- Create: `templates/blocks/sidebar.php`
- Create: `templates/blocks/related-posts.php`
- Create: `src/postcss/blocks/post-header.css`
- Create: `src/postcss/blocks/post-content.css`
- Create: `src/postcss/blocks/post-footer.css`
- Create: `src/postcss/blocks/sidebar.css`
- Create: `src/postcss/blocks/related-posts.css`
- Modify: `src/postcss/blocks/index.css`

- [ ] **Step 1: Create post-header block**

`templates/blocks/post-header.php`:
```php
<?php
/**
 * Block: Post Header
 *
 * Displays the post title, meta (date, author, categories), and featured image.
 *
 * @package codetot
 * @author codetot
 */

$data = wp_parse_args(
	$args, [
		'class'   => '',
		'post_id' => get_the_ID(),
	]
);

$_class = 'py-2 py-lg-4 post-header';
$_class .= ! empty( $data['class'] ) ? ' ' . esc_attr( $data['class'] ) : '';

$_post_id      = $data['post_id'];
$_title        = get_the_title( $_post_id );
$_date         = get_the_date( '', $_post_id );
$_author       = get_the_author_meta( 'display_name', get_post_field( 'post_author', $_post_id ) );
$_categories   = get_the_category( $_post_id );
$_thumbnail_id = get_post_thumbnail_id( $_post_id );

if ( ! empty( $_title ) ) :
	?>
<div class="<?php echo esc_attr( $_class ); ?>">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-lg-8">
				<?php if ( ! empty( $_categories ) ) : ?>
					<div class="mb-1 post-header__categories">
						<?php foreach ( $_categories as $_cat ) : ?>
							<a class="badge bg-primary text-decoration-none me-05 post-header__category" href="<?php echo esc_url( get_category_link( $_cat->term_id ) ); ?>"><?php echo esc_html( $_cat->name ); ?></a>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>

				<h1 class="h1 post-header__title"><?php echo esc_html( $_title ); ?></h1>

				<div class="d-flex align-items-center gap-2 text-muted small post-header__meta">
					<span class="post-header__date"><?php echo esc_html( $_date ); ?></span>
					<span class="post-header__separator">&middot;</span>
					<span class="post-header__author"><?php echo esc_html( $_author ); ?></span>
				</div>
			</div>
		</div>

		<?php if ( ! empty( $_thumbnail_id ) ) : ?>
			<div class="row justify-content-center mt-2">
				<div class="col-lg-10">
					<?php
					get_template_part(
						'templates/core-blocks/image', null, [
							'class'          => 'w-100 ratio ratio-16x9 image--cover rounded-3 post-header__image',
							'image_id'       => $_thumbnail_id,
							'image_size'     => 'large',
							'image_lazyload' => false,
						]
					);
					?>
				</div>
			</div>
		<?php endif; ?>
	</div>
</div>
	<?php
endif;
```

`src/postcss/blocks/post-header.css`:
```css
.post-header__title {
	margin-bottom: 0.75rem;
	letter-spacing: -0.03em;
}

.post-header__meta {
	font-size: 0.875rem;
}

.post-header__category {
	font-size: 0.75rem;
	padding: 0.3em 0.8em;
}
```

- [ ] **Step 2: Create post-content block**

`templates/blocks/post-content.php`:
```php
<?php
/**
 * Block: Post Content
 *
 * Displays the post body with proper typography wrapper.
 *
 * @package codetot
 * @author codetot
 */

$data = wp_parse_args(
	$args, [
		'class'         => '',
		'post_id'       => get_the_ID(),
		'content_class' => 'col-lg-8 mx-auto',
	]
);

$_class = 'py-2 post-content';
$_class .= ! empty( $data['class'] ) ? ' ' . esc_attr( $data['class'] ) : '';

$content = get_post_field( 'post_content', $data['post_id'] );

if ( ! empty( $content ) ) :
	?>
<div class="<?php echo esc_attr( $_class ); ?>">
	<div class="container">
		<div class="row justify-content-center">
			<div class="<?php echo esc_attr( $data['content_class'] ); ?> post-content__body">
				<?php
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped, WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
				echo apply_filters( 'the_content', $content );
				?>
			</div>
		</div>
	</div>
</div>
	<?php
endif;
```

`src/postcss/blocks/post-content.css`:
```css
.post-content__body {
	font-size: 1.05rem;
	line-height: 1.8;

	& h2 {
		margin-top: 2rem;
		margin-bottom: 1rem;
	}

	& h3 {
		margin-top: 1.5rem;
		margin-bottom: 0.75rem;
	}

	& p {
		margin-bottom: 1.25rem;
	}

	& img {
		max-width: 100%;
		height: auto;
		border-radius: 8px;
	}

	& blockquote {
		border-left: 4px solid var(--bs-primary);
		padding-left: 1.5rem;
		margin: 1.5rem 0;
		font-style: italic;
		color: var(--bs-gray-600);
	}

	& pre {
		background-color: var(--bs-gray-100);
		padding: 1rem;
		border-radius: 8px;
		overflow-x: auto;
	}

	& ul,
	& ol {
		padding-left: 1.5rem;
		margin-bottom: 1.25rem;
	}
}
```

- [ ] **Step 3: Create post-footer block**

`templates/blocks/post-footer.php`:
```php
<?php
/**
 * Block: Post Footer
 *
 * Displays tags and author bio after post content.
 *
 * @package codetot
 * @author codetot
 */

$data = wp_parse_args(
	$args, [
		'class'   => '',
		'post_id' => get_the_ID(),
	]
);

$_class = 'py-2 border-top post-footer';
$_class .= ! empty( $data['class'] ) ? ' ' . esc_attr( $data['class'] ) : '';

$_post_id = $data['post_id'];
$_tags    = get_the_tags( $_post_id );
$_author  = get_the_author_meta( 'display_name', get_post_field( 'post_author', $_post_id ) );
$_bio     = get_the_author_meta( 'description', get_post_field( 'post_author', $_post_id ) );

?>
<div class="<?php echo esc_attr( $_class ); ?>">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-lg-8">
				<?php if ( ! empty( $_tags ) ) : ?>
					<div class="mb-2 post-footer__tags">
						<?php foreach ( $_tags as $_tag ) : ?>
							<a class="badge bg-light text-dark text-decoration-none me-05 mb-05 post-footer__tag" href="<?php echo esc_url( get_tag_link( $_tag->term_id ) ); ?>">#<?php echo esc_html( $_tag->name ); ?></a>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>

				<?php if ( ! empty( $_bio ) ) : ?>
					<div class="d-flex gap-2 p-2 bg-light rounded-3 post-footer__author">
						<div class="flex-shrink-0 post-footer__avatar">
							<?php echo get_avatar( get_post_field( 'post_author', $_post_id ), 64, '', $_author, [ 'class' => 'rounded-circle' ] ); ?>
						</div>
						<div class="post-footer__author-info">
							<strong class="d-block post-footer__author-name"><?php echo esc_html( $_author ); ?></strong>
							<p class="small text-muted m-0 post-footer__author-bio"><?php echo esc_html( $_bio ); ?></p>
						</div>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>
```

`src/postcss/blocks/post-footer.css`:
```css
.post-footer__tag {
	font-size: 0.8rem;
	padding: 0.35em 0.8em;
}

.post-footer__avatar img {
	width: 64px;
	height: 64px;
	object-fit: cover;
}

.post-footer__author-bio {
	line-height: 1.5;
}
```

- [ ] **Step 4: Create sidebar block**

`templates/blocks/sidebar.php`:
```php
<?php
/**
 * Block: Sidebar
 *
 * Displays a widget area sidebar.
 *
 * @package codetot
 * @author codetot
 */

$data = wp_parse_args(
	$args, [
		'class'      => '',
		'sidebar_id' => 'sidebar-1',
	]
);

$_class = 'sidebar';
$_class .= ! empty( $data['class'] ) ? ' ' . esc_attr( $data['class'] ) : '';

if ( is_active_sidebar( $data['sidebar_id'] ) ) :
	?>
<aside class="<?php echo esc_attr( $_class ); ?>" role="complementary">
	<?php dynamic_sidebar( $data['sidebar_id'] ); ?>
</aside>
	<?php
endif;
```

`src/postcss/blocks/sidebar.css`:
```css
.sidebar {
	& .widget {
		margin-bottom: 2rem;
	}

	& .widget-title {
		font-size: 1rem;
		font-weight: 700;
		margin-bottom: 1rem;
		padding-bottom: 0.5rem;
		border-bottom: 2px solid var(--bs-primary);
	}

	& ul {
		list-style: none;
		padding: 0;

		& li {
			padding: 0.4rem 0;
			border-bottom: 1px solid var(--bs-gray-200);

			& a {
				color: var(--bs-body-color);
				text-decoration: none;

				&:hover {
					color: var(--bs-primary);
				}
			}
		}
	}
}
```

- [ ] **Step 5: Create related-posts block**

`templates/blocks/related-posts.php`:
```php
<?php
/**
 * Block: Related Posts
 *
 * Displays related posts based on shared categories.
 *
 * @package codetot
 * @author codetot
 */

$data = wp_parse_args(
	$args, [
		'class'   => '',
		'post_id' => get_the_ID(),
		'title'   => __( 'Related Posts', 'codetot' ),
		'count'   => 3,
	]
);

$_class = 'py-2 py-lg-4 bg-light related-posts';
$_class .= ! empty( $data['class'] ) ? ' ' . esc_attr( $data['class'] ) : '';

$_categories = wp_get_post_categories( $data['post_id'] );

if ( ! empty( $_categories ) ) :
	$_query = new \WP_Query(
		[
			'category__in'   => $_categories,
			'post__not_in'   => [ $data['post_id'] ],
			'posts_per_page' => $data['count'],
			'orderby'        => 'rand',
			'fields'         => 'ids',
		]
	);

	if ( $_query->have_posts() ) :
		?>
<div class="<?php echo esc_attr( $_class ); ?>">
	<div class="container">
		<h2 class="h4 mb-2 related-posts__title"><?php echo esc_html( $data['title'] ); ?></h2>
		<div class="row">
			<?php
			get_template_part(
				'templates/blocks/post-grid-loop', null, [
					'post_ids'     => $_query->posts,
					'column_class' => 'd-flex flex-column col-12 col-md-4 mb-2',
				]
			);
			?>
		</div>
	</div>
</div>
		<?php
	endif;
	wp_reset_postdata();
endif;
```

`src/postcss/blocks/related-posts.css`:
```css
.related-posts__title {
	font-weight: 700;
}
```

- [ ] **Step 6: Register new CSS imports in index.css**

Add to `src/postcss/blocks/index.css`:
```css
@import url('post-header.css');
@import url('post-content.css');
@import url('post-footer.css');
@import url('sidebar.css');
@import url('related-posts.css');
```

- [ ] **Step 7: Lint and build**

```bash
vendor/bin/phpcs --standard=phpcs.xml --runtime-set ignore_warnings_on_exit 1 \
  templates/blocks/post-header.php \
  templates/blocks/post-content.php \
  templates/blocks/post-footer.php \
  templates/blocks/sidebar.php \
  templates/blocks/related-posts.php

./node_modules/.bin/stylelint src/postcss/blocks/post-*.css src/postcss/blocks/sidebar.css src/postcss/blocks/related-posts.css

npm run build
```

Expected: 0 errors, build produces updated `main.css` and `critical.css`.

- [ ] **Step 8: Commit**

```bash
git add templates/blocks/post-header.php templates/blocks/post-content.php \
  templates/blocks/post-footer.php templates/blocks/sidebar.php \
  templates/blocks/related-posts.php \
  src/postcss/blocks/post-header.css src/postcss/blocks/post-content.css \
  src/postcss/blocks/post-footer.css src/postcss/blocks/sidebar.css \
  src/postcss/blocks/related-posts.css src/postcss/blocks/index.css

git commit -m "feat: add single-post blocks (post-header, post-content, post-footer, sidebar, related-posts)"
```

---

## Task 3: Update single.php layout to use new blocks

**Files:**
- Modify: `templates/layouts/single.php`

- [ ] **Step 1: Rewrite single.php to use new blocks**

Replace the entire contents of `templates/layouts/single.php`:
```php
<?php
/**
 * Single Post Template
 *
 * @package codetot
 * @author codetot
 * @since 0.0.1
 */

$_post_id = get_the_ID();

// Post header (title, meta, featured image)
get_template_part(
	'templates/blocks/post-header', null, [
		'class'   => 'mb-2',
		'post_id' => $_post_id,
	]
);

// Post content (body text)
get_template_part(
	'templates/blocks/post-content', null, [
		'class'         => 'mb-2',
		'post_id'       => $_post_id,
		'content_class' => 'col-lg-8 mx-auto',
	]
);

// Post footer (tags, author bio)
get_template_part(
	'templates/blocks/post-footer', null, [
		'class'   => 'mb-2',
		'post_id' => $_post_id,
	]
);

// Related posts
get_template_part(
	'templates/blocks/related-posts', null, [
		'post_id' => $_post_id,
	]
);
```

- [ ] **Step 2: Lint**

```bash
vendor/bin/phpcs --standard=phpcs.xml --runtime-set ignore_warnings_on_exit 1 templates/layouts/single.php
```

- [ ] **Step 3: Commit**

```bash
git add templates/layouts/single.php
git commit -m "refactor: single.php uses post-header, post-content, post-footer, related-posts blocks"
```

---

## Task 4: Remove non-essential blocks from master

**Files to delete:**
- `templates/blocks/faq.php`
- `templates/blocks/feature-grid.php`
- `templates/blocks/feature-item.php`
- `templates/blocks/feature-list-two-up.php`
- `templates/blocks/hero-image.php`
- `templates/blocks/hero-post.php`
- `templates/blocks/hero-title.php`
- `templates/blocks/highlight-section.php`
- `templates/blocks/listing-section.php`
- `templates/blocks/logo-grid.php`
- `templates/blocks/pricing-table.php`
- `templates/blocks/review-item.php`
- `templates/blocks/reviews-slider.php`
- `templates/blocks/statistics.php`
- `templates/blocks/step-section.php`
- `templates/blocks/two-up-image.php`
- `src/postcss/blocks/faq.css`
- `src/postcss/blocks/feature-grid.css`
- `src/postcss/blocks/feature-list-two-up.css`
- `src/postcss/blocks/hero-title.css`
- `src/postcss/blocks/highlight-section.css`
- `src/postcss/blocks/listing-section.css`
- `src/postcss/blocks/logo-grid.css`
- `src/postcss/blocks/pricing-table.css`
- `src/postcss/blocks/review-item.css`
- `src/postcss/blocks/reviews-slider.css`
- `src/postcss/blocks/statistics.css`
- `src/postcss/blocks/step-section.css`
- `src/postcss/blocks/two-up-image.css`
- `src/js/modules/faq.js`
- `src/js/modules/reviews-slider.js`
- `src/js/blocks/faq.js`
- `src/js/blocks/reviews-slider.js`

**Files to modify:**
- `src/postcss/blocks/index.css` — remove imports
- `src/js/main.js` — remove JS imports and init calls
- `src/postcss/critical.css` — remove hero-title import

- [ ] **Step 1: Delete block PHP templates**

```bash
git rm \
  templates/blocks/faq.php \
  templates/blocks/feature-grid.php \
  templates/blocks/feature-item.php \
  templates/blocks/feature-list-two-up.php \
  templates/blocks/hero-image.php \
  templates/blocks/hero-post.php \
  templates/blocks/hero-title.php \
  templates/blocks/highlight-section.php \
  templates/blocks/listing-section.php \
  templates/blocks/logo-grid.php \
  templates/blocks/pricing-table.php \
  templates/blocks/review-item.php \
  templates/blocks/reviews-slider.php \
  templates/blocks/statistics.php \
  templates/blocks/step-section.php \
  templates/blocks/two-up-image.php
```

- [ ] **Step 2: Delete block CSS files**

```bash
git rm \
  src/postcss/blocks/faq.css \
  src/postcss/blocks/feature-grid.css \
  src/postcss/blocks/feature-list-two-up.css \
  src/postcss/blocks/hero-title.css \
  src/postcss/blocks/highlight-section.css \
  src/postcss/blocks/listing-section.css \
  src/postcss/blocks/logo-grid.css \
  src/postcss/blocks/pricing-table.css \
  src/postcss/blocks/review-item.css \
  src/postcss/blocks/reviews-slider.css \
  src/postcss/blocks/statistics.css \
  src/postcss/blocks/step-section.css \
  src/postcss/blocks/two-up-image.css
```

- [ ] **Step 3: Delete block JS files**

```bash
git rm \
  src/js/modules/faq.js \
  src/js/modules/reviews-slider.js \
  src/js/blocks/faq.js \
  src/js/blocks/reviews-slider.js
```

- [ ] **Step 4: Update CSS index.css — keep only structural block imports**

Replace `src/postcss/blocks/index.css` with:
```css
@import url('footer.css');
@import url('header.css');
@import url('post-header.css');
@import url('post-content.css');
@import url('post-footer.css');
@import url('sidebar.css');
@import url('related-posts.css');
```

- [ ] **Step 5: Update main.js — remove faq and reviews-slider**

Replace `src/js/main.js` with:
```js
import '../postcss/frontend.css';

document.addEventListener('DOMContentLoaded', () => {
	// Block initializations added per-project
});
```

Note: Swiper CSS imports removed since reviews-slider is gone. Re-add when needed per project.

- [ ] **Step 6: Update critical.css — remove hero-title**

Replace `src/postcss/critical.css` with:
```css
/**
 * Critical CSS — above-the-fold styles inlined in <head>.
 *
 * Contains only what's needed for first paint:
 * - CSS variables (custom properties)
 * - Base reset (body, headings, figure, screen-reader-text)
 * - Header/navigation
 *
 * Built by Vite as a separate entry → assets/dist/critical.css
 * Inlined by inc/critical-css.php at priority 1.
 */

@import url('variables.css');
@import url('global/_reset.css');
@import url('blocks/header.css');
```

- [ ] **Step 7: Update page.php layout — use content-area (no hero dependency)**

Replace `templates/layouts/page.php` with:
```php
<?php
/**
 * Default Page Template Layout
 *
 * @package codetot
 * @author codetot
 * @since 0.0.1
 */

$_post_id = get_the_ID();

get_template_part(
	'templates/blocks/breadcrumb', null, [
		'class' => 'mb-1',
	]
);

get_template_part(
	'templates/blocks/content-area', null, [
		'class'         => 'py-2 py-lg-4',
		'post_id'       => $_post_id,
		'content_class' => 'col-lg-8 mx-auto',
	]
);
```

- [ ] **Step 8: Remove Swiper dependency (optional, only if not used elsewhere)**

Check if Swiper is used anywhere else. If not:
```bash
npm uninstall swiper
```

If kept for per-project use, leave it in `dependencies`.

- [ ] **Step 9: Build and lint**

```bash
npm run build
vendor/bin/phpcs --standard=phpcs.xml --runtime-set ignore_warnings_on_exit 1 templates/ inc/
./node_modules/.bin/stylelint 'src/postcss/**/*.css'
npx eslint src/js/
```

Expected: clean build, all linters pass.

- [ ] **Step 10: Commit**

```bash
git add -A
git commit -m "refactor: remove non-essential blocks from master — preserved on feature/blocks

Removed 16 blocks (faq, hero-*, feature-*, reviews-slider, statistics,
pricing-table, logo-grid, highlight-section, listing-section, step-section,
two-up-image). All preserved on feature/blocks branch for cherry-picking.

Master now contains only structural blocks:
- header, footer, breadcrumb
- post-card, post-grid, post-grid-loop
- post-header, post-content, post-footer, sidebar, related-posts
- content-area"
```

---

## Task 5: Update documentation

**Files:**
- Modify: `README.md` — update block table and cherry-pick instructions

- [ ] **Step 1: Update README.md block table**

Replace the "Available Blocks (23)" section with the new list (12 blocks) and add a section:

```markdown
### Cherry-picking blocks from feature/blocks

To add extra blocks to your project, copy all files for the block and register them.

#### Block file manifest

| Block | PHP | CSS | JS |
|-------|-----|-----|----|
| faq | `templates/blocks/faq.php` | `src/postcss/blocks/faq.css` | `src/js/modules/faq.js` `src/js/blocks/faq.js` |
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
| reviews-slider | `templates/blocks/reviews-slider.php` | `src/postcss/blocks/reviews-slider.css` | `src/js/modules/reviews-slider.js` `src/js/blocks/reviews-slider.js` |
| statistics | `templates/blocks/statistics.php` | `src/postcss/blocks/statistics.css` | — |
| step-section | `templates/blocks/step-section.php` | `src/postcss/blocks/step-section.css` | — |
| two-up-image | `templates/blocks/two-up-image.php` | `src/postcss/blocks/two-up-image.css` | — |

#### Example: add faq block

\`\`\`bash
# 1. Copy all block files from feature/blocks
git checkout feature/blocks -- \
  templates/blocks/faq.php \
  src/postcss/blocks/faq.css \
  src/js/modules/faq.js \
  src/js/blocks/faq.js

# 2. Register CSS — add to src/postcss/blocks/index.css:
#    @import url('faq.css');

# 3. Register JS — add to src/js/main.js:
#    import { initFaq } from './modules/faq';
#    // call initFaq() inside DOMContentLoaded

# 4. Build
npm run build
\`\`\`

#### Example: add hero-image block (no CSS/JS)

\`\`\`bash
# Only PHP — no CSS or JS registration needed
git checkout feature/blocks -- templates/blocks/hero-image.php
\`\`\`

#### Example: add reviews-slider (requires Swiper)

\`\`\`bash
# 1. Copy all files
git checkout feature/blocks -- \
  templates/blocks/reviews-slider.php \
  src/postcss/blocks/reviews-slider.css \
  src/js/modules/reviews-slider.js \
  src/js/blocks/reviews-slider.js

# 2. Add Swiper dependency (if not already installed)
npm install swiper

# 3. Register CSS — add to src/postcss/blocks/index.css:
#    @import url('reviews-slider.css');

# 4. Register JS — add to src/js/main.js:
#    import 'swiper/css';
#    import 'swiper/css/navigation';
#    import 'swiper/css/pagination';
#    import { initReviewsSlider } from './modules/reviews-slider';
#    // call initReviewsSlider() inside DOMContentLoaded

# 5. Build
npm run build
\`\`\`
```

- [ ] **Step 2: Commit**

```bash
git add README.md
git commit -m "docs: update README with clean block list + cherry-pick instructions"
```

---

## Task 6: Final validation and push

- [ ] **Step 1: Full validation**

```bash
npm run validate
composer lint
```

- [ ] **Step 2: Verify block count**

```bash
ls templates/blocks/*.php | wc -l
# Expected: 12 (header, footer, breadcrumb, post-card, post-grid, post-grid-loop,
#               content-area, post-header, post-content, post-footer, sidebar, related-posts)
```

- [ ] **Step 3: Verify feature/blocks has all blocks**

```bash
git stash  # if needed
git checkout feature/blocks
ls templates/blocks/*.php | wc -l
# Expected: 28 (original 23 + 5 new single blocks)
git checkout master
git stash pop  # if needed
```

- [ ] **Step 4: Push master**

```bash
git push origin master
```

- [ ] **Step 5: Update GitHub issue**

Comment on codetot-ai/wp-theme-base#3 with the block split summary.
