#!/usr/bin/env bash
set -euo pipefail

# WP Theme Base — Setup Script
# Replaces all generic placeholders with your project-specific values.
# Run once after cloning, then this script self-deletes.
#
# Usage:
#   Interactive:     bash setup.sh
#   Non-interactive: bash setup.sh --slug my-theme --name "My Theme" ...
#   Dry run:         bash setup.sh --slug my-theme --dry-run
#
# Options:
#   --name NAME          Theme display name (e.g. "Client Theme")
#   --slug SLUG          Text domain and function prefix (e.g. client-theme)
#   --namespace NS       PHP namespace prefix (e.g. ClientTheme)
#   --url URL            Local dev URL (e.g. http://client.test)
#   --author AUTHOR      Author name (e.g. "Your Name")
#   --composer PKG       Composer package name (e.g. your-org/client-theme)
#   --dry-run            Show what would change without modifying files
#   --yes, -y            Skip confirmation prompt

# --- Defaults ---
THEME_NAME=""
THEME_SLUG=""
THEME_NAMESPACE=""
DEV_URL=""
AUTHOR_NAME=""
COMPOSER_NAME=""
DRY_RUN=false
AUTO_YES=false

# --- Parse CLI arguments ---
while [[ $# -gt 0 ]]; do
	case "$1" in
		--name)       THEME_NAME="$2"; shift 2 ;;
		--slug)       THEME_SLUG="$2"; shift 2 ;;
		--namespace)  THEME_NAMESPACE="$2"; shift 2 ;;
		--url)        DEV_URL="$2"; shift 2 ;;
		--author)     AUTHOR_NAME="$2"; shift 2 ;;
		--composer)   COMPOSER_NAME="$2"; shift 2 ;;
		--dry-run)    DRY_RUN=true; shift ;;
		--yes|-y)     AUTO_YES=true; shift ;;
		-*)           echo "Unknown option: $1"; exit 1 ;;
		*)            echo "Unknown argument: $1"; exit 1 ;;
	esac
done

echo "=== WP Theme Base Setup ==="
echo ""

# --- Interactive prompts (only for missing values) ---
if [[ -z "$THEME_SLUG" ]]; then
	read -rp "Theme slug / text domain (e.g. client-theme): " THEME_SLUG
fi

if [[ -z "$THEME_NAME" ]]; then
	# Default: titlecase from slug
	DEFAULT_NAME=$(echo "$THEME_SLUG" | sed 's/-/ /g' | awk '{for(i=1;i<=NF;i++) $i=toupper(substr($i,1,1)) substr($i,2)}1')
	read -rp "Theme name [$DEFAULT_NAME]: " THEME_NAME
	THEME_NAME="${THEME_NAME:-$DEFAULT_NAME}"
fi

if [[ -z "$THEME_NAMESPACE" ]]; then
	# Default: PascalCase from slug
	DEFAULT_NS=$(echo "$THEME_SLUG" | sed 's/-/ /g' | awk '{for(i=1;i<=NF;i++) $i=toupper(substr($i,1,1)) substr($i,2)}1' | tr -d ' ')
	read -rp "PHP namespace prefix [$DEFAULT_NS]: " THEME_NAMESPACE
	THEME_NAMESPACE="${THEME_NAMESPACE:-$DEFAULT_NS}"
fi

if [[ -z "$DEV_URL" ]]; then
	DEFAULT_URL="http://${THEME_SLUG}.test"
	read -rp "Local dev URL [$DEFAULT_URL]: " DEV_URL
	DEV_URL="${DEV_URL:-$DEFAULT_URL}"
fi

if [[ -z "$AUTHOR_NAME" ]]; then
	read -rp "Author name [CODE TOT]: " AUTHOR_NAME
	AUTHOR_NAME="${AUTHOR_NAME:-CODE TOT}"
fi

if [[ -z "$COMPOSER_NAME" ]]; then
	DEFAULT_COMPOSER="codetot-ai/${THEME_SLUG}"
	read -rp "Composer package name [$DEFAULT_COMPOSER]: " COMPOSER_NAME
	COMPOSER_NAME="${COMPOSER_NAME:-$DEFAULT_COMPOSER}"
fi

# --- Validate ---
if [[ "$THEME_SLUG" =~ [[:space:]] ]]; then
	echo "Error: Theme slug must not contain spaces."
	exit 1
fi

if [[ "$THEME_NAMESPACE" =~ [[:space:]] ]]; then
	echo "Error: Namespace must not contain spaces."
	exit 1
fi

if [[ ! "$THEME_SLUG" =~ ^[a-z0-9-]+$ ]]; then
	echo "Error: Theme slug must be lowercase alphanumeric with hyphens only."
	exit 1
fi

# --- Old values ---
OLD_SLUG="codetot"
OLD_NAMESPACE="Codetot"
OLD_THEME_NAME="WP Theme Base"
OLD_DEV_URL="http://localhost:10086/"
OLD_COMPOSER="codetot-ai/wp-theme-base"

# Derive uppercase constant prefix from slug (hyphens → underscores)
THEME_FUNC_PREFIX=$(echo "$THEME_SLUG" | tr '-' '_')
THEME_UPPER=$(echo "$THEME_FUNC_PREFIX" | tr '[:lower:]' '[:upper:]')

# --- Show summary ---
echo ""
echo "Will replace:"
echo "  Theme name:         $OLD_THEME_NAME -> $THEME_NAME"
echo "  Text domain/prefix: $OLD_SLUG -> $THEME_SLUG"
echo "  Function prefix:    ${OLD_SLUG}_ -> ${THEME_FUNC_PREFIX}_"
echo "  Constants:          CODETOT_ -> ${THEME_UPPER}_"
echo "  Namespace:          $OLD_NAMESPACE -> $THEME_NAMESPACE"
echo "  Dev URL:            $OLD_DEV_URL -> $DEV_URL"
echo "  Author:             CODE TOT -> $AUTHOR_NAME"
echo "  Composer:           $OLD_COMPOSER -> $COMPOSER_NAME"
echo ""

if $DRY_RUN; then
	echo "[DRY RUN] No files were modified."
	exit 0
fi

if ! $AUTO_YES; then
	read -rp "Continue? (y/N): " CONFIRM
	if [[ "$CONFIRM" != "y" && "$CONFIRM" != "Y" ]]; then
		echo "Aborted."
		exit 1
	fi
fi

echo ""
echo "Replacing in PHP files..."

# PHP files: order matters — asset handles first, then text domain, to avoid double-replacement.
# Step 1: Replace asset handle prefixes (e.g. 'codetot-bootstrap' → 'mytheme-bootstrap')
# Must run BEFORE text domain replacement to avoid 'codetot-global-global-bootstrap'.
echo "Replacing asset handles..."
find . -name "*.php" \
	-not -path "*/vendor/*" \
	-not -path "*/node_modules/*" \
	-print0 | xargs -0 perl -pi -e "
	s/'codetot-/'${THEME_SLUG}-/g;
"

# Step 2: Replace namespace, function prefix, text domain, package tag, constants
find . -name "*.php" \
	-not -path "*/vendor/*" \
	-not -path "*/node_modules/*" \
	-print0 | xargs -0 perl -pi -e "
	s/Codetot\\\\Theme/${THEME_NAMESPACE}\\\\Theme/g;
	s/\\bCodetot\\b/${THEME_NAMESPACE}/g;
	s/\\bCODETOT_/${THEME_UPPER}_/g;
	s/\\bcodetot_/${THEME_FUNC_PREFIX}_/g;
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
	s/codetot_get_svg_icon/${THEME_FUNC_PREFIX}_get_svg_icon/g;
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

echo "Replacing dev URL..."
if [ -f webpack.config.js ]; then
	perl -pi -e "s|http://localhost:10086/|${DEV_URL}/|;" webpack.config.js
fi

echo ""
echo "=== Setup complete! ==="
echo ""
echo "Next steps:"
echo "  1. composer install"
echo "  2. npm install && npm run build"
echo "  3. Verify: grep -rn 'codetot' . --include='*.php' | grep -v vendor | grep -v node_modules"
echo "  4. git init && git add -A && git commit -m 'Initial ${THEME_NAME} theme'"
echo ""

# Self-delete
rm -- "$0"
