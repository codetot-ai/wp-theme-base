#!/usr/bin/env bash
set -euo pipefail

# WP Theme Base — Setup Script
# Replaces all generic placeholders with your project-specific values.
# Run once after cloning, then this script self-deletes.

echo "=== WP Theme Base Setup ==="
echo ""

# --- Prompts ---
read -rp "Theme name (e.g. Chuyendev Theme): " THEME_NAME
read -rp "Theme slug / text domain (e.g. chuyendev): " THEME_SLUG
read -rp "PHP namespace prefix (e.g. Chuyendev): " THEME_NAMESPACE
read -rp "Local dev URL (e.g. http://chuyendev.test): " DEV_URL
read -rp "Author name (e.g. Your Name): " AUTHOR_NAME
read -rp "Composer package name (e.g. your-org/theme-slug): " COMPOSER_NAME

# --- Validate ---
if [[ "$THEME_SLUG" =~ [[:space:]] ]]; then
    echo "Error: Theme slug must not contain spaces."
    exit 1
fi

if [[ "$THEME_NAMESPACE" =~ [[:space:]] ]]; then
    echo "Error: Namespace must not contain spaces."
    exit 1
fi

# --- Old values ---
OLD_SLUG="codetot"
OLD_NAMESPACE="Codetot"
OLD_THEME_NAME="WP Theme Base"
OLD_DEV_URL="http://localhost:10086/"
OLD_COMPOSER="codetot-ai/wp-theme-base"

# --- Confirm ---
echo ""
echo "Will replace:"
echo "  Theme name:         $OLD_THEME_NAME -> $THEME_NAME"
echo "  Text domain/prefix: $OLD_SLUG -> $THEME_SLUG"
echo "  Namespace:          $OLD_NAMESPACE -> $THEME_NAMESPACE"
echo "  Dev URL:            $OLD_DEV_URL -> $DEV_URL"
echo "  Author:             CODE TOT -> $AUTHOR_NAME"
echo "  Composer:           $OLD_COMPOSER -> $COMPOSER_NAME"
echo ""
read -rp "Continue? (y/N): " CONFIRM
if [[ "$CONFIRM" != "y" && "$CONFIRM" != "Y" ]]; then
    echo "Aborted."
    exit 1
fi

echo ""
echo "Replacing in PHP files..."

# PHP files: namespace (full first, then standalone), function prefix, text domain, package tag
find . -name "*.php" \
    -not -path "*/vendor/*" \
    -not -path "*/node_modules/*" \
    -print0 | xargs -0 perl -pi -e "
    s/Codetot\\\\Theme/${THEME_NAMESPACE}\\\\Theme/g;
    s/\\bCodetot\\b/${THEME_NAMESPACE}/g;
    s/\\bcodetot_/${THEME_SLUG}_/g;
    s/'codetot'/'${THEME_SLUG}'/g;
    s/\"codetot\"/\"${THEME_SLUG}\"/g;
    s/\@package codetot/\@package ${THEME_SLUG}/g;
    s/\@author codetot/\@author ${THEME_SLUG}/g;
"

echo "Replacing in style.css..."
perl -pi -e "
    s/Theme Name: WP Theme Base/Theme Name: ${THEME_NAME}/;
    s/Text Domain: codetot/Text Domain: ${THEME_SLUG}/;
    s/Author: CODE TOT/Author: ${AUTHOR_NAME}/;
" style.css

echo "Replacing in phpcs.xml..."
perl -pi -e "
    s/<element value=\"codetot\"\\/>/<element value=\"${THEME_SLUG}\"\/>/g;
    s/WP Theme Base/${THEME_NAME}/g;
" phpcs.xml

# Also update the custom escape function name in phpcs.xml
perl -pi -e "
    s/codetot_get_svg_icon/${THEME_SLUG}_get_svg_icon/g;
" phpcs.xml

echo "Replacing in package.json..."
perl -pi -e "
    s/\"name\": \"wp-theme-base\"/\"name\": \"${THEME_SLUG}\"/;
    s|codetot-ai/wp-theme-base|${COMPOSER_NAME}|;
    s/\"name\": \"CODE TOT\"/\"name\": \"${AUTHOR_NAME}\"/;
" package.json

echo "Replacing in composer.json..."
perl -pi -e "
    s|\"codetot-ai/wp-theme-base\"|\"${COMPOSER_NAME}\"|;
    s/WordPress Theme Base[^\"]*/${THEME_NAME}/;
    s/\"name\": \"CODE TOT\"/\"name\": \"${AUTHOR_NAME}\"/;
" composer.json

echo "Replacing in webpack.config.js..."
perl -pi -e "
    s|http://localhost:10086/|${DEV_URL}/|;
" webpack.config.js

# Replace asset handle prefixes in class-theme-init.php (already done by PHP replacement above)
# But also handle the CSS/JS handle names like 'codetot-bootstrap', 'codetot-frontend'
find . -name "*.php" \
    -not -path "*/vendor/*" \
    -not -path "*/node_modules/*" \
    -print0 | xargs -0 perl -pi -e "
    s/'codetot-/'${THEME_SLUG}-/g;
"

echo ""
echo "=== Setup complete! ==="
echo ""
echo "Next steps:"
echo "  1. npm install && npm run build"
echo "  2. composer install"
echo "  3. Verify: grep -r 'codetot' . --include='*.php' (should return nothing)"
echo "  4. git init && git add -A && git commit -m 'Initial ${THEME_NAME} theme'"
echo ""

# Self-delete
rm -- "$0"
