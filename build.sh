#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "$0")" && pwd)"
PLUGIN_MAIN_SOURCE="${ROOT_DIR}/index.php"
if [ -f "$PLUGIN_MAIN_SOURCE" ]; then
    TEXT_DOMAIN="$(awk -F': ' '/^\s*Text Domain:\s*/{print $2; exit}' "$PLUGIN_MAIN_SOURCE")"
else
    TEXT_DOMAIN=""
fi

if [ -n "$TEXT_DOMAIN" ]; then
    PLUGIN_NAME="$TEXT_DOMAIN"
else
    PLUGIN_NAME="$(basename "$ROOT_DIR")"
fi
BUILD_DIR="${HOME}/.build"
TIMESTAMP="$(date +%Y%m%d%H%M%S)"
ZIP_NAME="${PLUGIN_NAME}-${TIMESTAMP}.zip"

TMP_DIR="$(mktemp -d)"
STAGE_DIR="${TMP_DIR}/${PLUGIN_NAME}"

cleanup() {
    rm -rf "$TMP_DIR"
}
trap cleanup EXIT

mkdir -p "$BUILD_DIR" "$STAGE_DIR"

rsync -a --delete \
    --exclude ".git" \
    --exclude ".github" \
    --exclude ".vscode" \
    --exclude ".idea" \
    --exclude ".DS_Store" \
    --exclude ".trunk" \
    --exclude ".history" \
    --exclude ".cache" \
    --exclude "node_modules" \
    --exclude "vendor/bin" \
    --exclude "tests" \
    --exclude "docs" \
    --exclude "build" \
    --exclude "dist" \
    --exclude "*.zip" \
    --exclude "*.log" \
    --exclude "*.map" \
    --exclude "*.md" \
    --exclude ".env" \
    --exclude ".env.*" \
    --exclude ".editorconfig" \
    --exclude ".gitattributes" \
    --exclude ".gitignore" \
    --exclude ".phpunit.result.cache" \
    --exclude "composer.json" \
    --exclude "composer.lock" \
    --exclude "package.json" \
    --exclude "package-lock.json" \
    --exclude "pnpm-lock.yaml" \
    --exclude "yarn.lock" \
    --exclude "build.sh" \
    "${ROOT_DIR}/" "${STAGE_DIR}/"

SOURCE_PLUGIN_MAIN_FILE="${ROOT_DIR}/index.php"
if [ -z "${VERSION:-}" ]; then
    if [ -f "$SOURCE_PLUGIN_MAIN_FILE" ]; then
        CURRENT_VERSION="$(awk '/^\s*Version:/{line=$0; sub(/^[^:]*:/, "", line); gsub(/^[ \t]+|[ \t]+$/, "", line); print line; exit}' "$SOURCE_PLUGIN_MAIN_FILE")"
    else
        CURRENT_VERSION=""
    fi

    if [[ "$CURRENT_VERSION" =~ ^([0-9]+)\.([0-9]+)\.([0-9]+)$ ]]; then
        MAJOR="${BASH_REMATCH[1]}"
        MINOR="${BASH_REMATCH[2]}"
        PATCH="${BASH_REMATCH[3]}"
        if [ "$PATCH" -ge 99 ]; then
            MINOR=$((MINOR + 1))
            PATCH=0
        else
            PATCH=$((PATCH + 1))
        fi
        VERSION="${MAJOR}.${MINOR}.${PATCH}"
    else
        VERSION="1.0.0"
    fi
fi

if [ -f "$SOURCE_PLUGIN_MAIN_FILE" ]; then
    sed -i.bak -E "s/^(\s*Version:\s*).*/\1${VERSION}/" "$SOURCE_PLUGIN_MAIN_FILE"
    sed -i.bak -E "s/^(\s*define\('PLUGIN_VERSION',\s*')[^']*('\);)/\1${VERSION}\2/" "$SOURCE_PLUGIN_MAIN_FILE"
    rm -f "${SOURCE_PLUGIN_MAIN_FILE}.bak"
fi

PLUGIN_MAIN_FILE="${STAGE_DIR}/index.php"
if [ -f "$PLUGIN_MAIN_FILE" ]; then
    sed -i.bak -E "s/^(\s*Version:\s*).*/\1${VERSION}/" "$PLUGIN_MAIN_FILE"
    sed -i.bak -E "s/^(\s*define\('PLUGIN_VERSION',\s*')[^']*('\);)/\1${VERSION}\2/" "$PLUGIN_MAIN_FILE"
    rm -f "${PLUGIN_MAIN_FILE}.bak"
fi

REQUIRED_PATHS=(
    "index.php"
    "includes/helpers.php"
    "includes/private/post-types/init.php"
    "includes/private/classes/meta-box-renderer.php"
    "includes/private/classes/meta-boxes/init.php"
    "includes/public/classes/init.php"
    "includes/public/widgets/init.php"
)

for required in "${REQUIRED_PATHS[@]}"; do
    if [ ! -e "${STAGE_DIR}/${required}" ]; then
        echo "Falta archivo requerido en build: ${required}" >&2
        exit 1
    fi
done

minify_css_file() {
    local file="$1"
    if command -v npx >/dev/null 2>&1; then
        if npx --yes cleancss -o "$file" "$file" >/dev/null 2>&1; then
            return
        fi
    fi
    python3 - <<'PY' "$file"
import re
import sys
path = sys.argv[1]
with open(path, 'r', encoding='utf-8') as f:
    content = f.read()
content = re.sub(r'/\*.*?\*/', '', content, flags=re.S)
content = re.sub(r'\s+', ' ', content)
content = re.sub(r'\s*([{}:;,])\s*', r'\1', content)
content = content.strip()
with open(path, 'w', encoding='utf-8') as f:
    f.write(content)
PY
}

minify_js_file() {
    local file="$1"
    if command -v npx >/dev/null 2>&1; then
        if npx --yes terser "$file" --compress --mangle --output "$file" >/dev/null 2>&1; then
            return
        fi
    fi
    python3 - <<'PY' "$file"
import sys
path = sys.argv[1]
with open(path, 'r', encoding='utf-8') as f:
    lines = f.readlines()
stripped = []
for line in lines:
    stripped_line = line.strip()
    if not stripped_line:
        continue
    stripped.append(stripped_line)
with open(path, 'w', encoding='utf-8') as f:
    f.write(' '.join(stripped))
PY
}

if [ -d "${STAGE_DIR}/assets/css" ]; then
    while IFS= read -r -d '' file; do
        minify_css_file "$file"
    done < <(find "${STAGE_DIR}/assets/css" -type f -name "*.css" ! -name "*.min.css" -print0)
fi

if [ -d "${STAGE_DIR}/assets/js" ]; then
    while IFS= read -r -d '' file; do
        minify_js_file "$file"
    done < <(find "${STAGE_DIR}/assets/js" -type f -name "*.js" ! -name "*.min.js" -print0)
fi

if [ -d "${STAGE_DIR}/languages" ]; then
    if command -v msgfmt >/dev/null 2>&1; then
        while IFS= read -r -d '' po_file; do
            mo_file="${po_file%.po}.mo"
            msgfmt -o "$mo_file" "$po_file"
            rm -f "$po_file"
        done < <(find "${STAGE_DIR}/languages" -type f -name "*.po" -print0)
        if [ -f "${STAGE_DIR}/languages/jec-portfolio-es_ES.mo" ] && [ ! -f "${STAGE_DIR}/languages/jec-portfolio-es_419.mo" ]; then
            cp "${STAGE_DIR}/languages/jec-portfolio-es_ES.mo" "${STAGE_DIR}/languages/jec-portfolio-es_419.mo"
        fi
    else
        echo "msgfmt no está disponible. No se compilaron los .po." >&2
    fi
fi

(
    cd "$TMP_DIR"
    zip -r "$ZIP_NAME" "$PLUGIN_NAME" >/dev/null
)

mv "$TMP_DIR/$ZIP_NAME" "$BUILD_DIR/$ZIP_NAME"

echo "ZIP generado: $BUILD_DIR/$ZIP_NAME"
