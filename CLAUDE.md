# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Overview

**Cupcake Content** is a WordPress theme built for Elementor. It requires WordPress 6.0+, PHP 8.0+, and the Elementor plugin. There is no build pipeline — all PHP, CSS, and JS files are edited directly and served as-is by WordPress.

- Text domain: `cupcake`
- Function prefix: `cupcake_`
- CSS class prefix: `cc-` (BEM-style: `cc-hero__heading`, `cc-hero__btn--primary`)
- All PHP files use `declare(strict_types=1)` and guard with `defined('ABSPATH') || exit`

## Development

This theme is installed inside a WordPress site's `wp-content/themes/` directory. There is no local dev server to start from this repo alone — you need a running WordPress environment (e.g. Local, DDEV, or Valet) with this theme folder symlinked or placed there.

No build step. Edit PHP/CSS/JS directly and reload the browser.

## Architecture

### Bootstrap flow (`functions.php`)

Three setup modules are loaded unconditionally:

| File | Purpose |
|---|---|
| `inc/setup/theme-setup.php` | Theme supports, nav menus (`primary`, `footer-col-1/2/3`, `social`), image sizes |
| `inc/setup/enqueue.php` | Enqueues CSS/JS; extracts `:root` block from `style.css` and inlines it via `wp_add_inline_style` |
| `inc/setup/customizer.php` | WP Customizer panel with color, header, footer, and typography controls |

Elementor integration is booted lazily via `cupcake_boot_elementor_integration()`, hooked to both `after_setup_theme` and `elementor/loaded` to handle either load order.

### Design token system

**`style.css`** is the single source of truth for all CSS custom properties (`--cc-*`). It contains only the theme header block and the `:root { }` block with tokens for colors, typography, spacing, layout, radii, shadows, and transitions.

`enqueue.php` regex-extracts that `:root {}` block and inlines it into `<head>` via `wp_add_inline_style`. `customizer.php` then overrides specific color and size tokens at runtime via a separate `<style id="cupcake-customizer-css">` tag on `wp_head` priority 99.

**Do not split tokens across files.** `style.css` `:root` block → inlined → overridden by Customizer. That is the full chain.

### Elementor integration (`inc/elementor/class-elementor-integration.php`)

`CupCake_Elementor_Integration` wires four Elementor hooks:

- **Widget registration** — `elementor/widgets/register`: requires and registers all 14 widget class files
- **Category** — `elementor/elements/categories_registered`: registers `cupcake-content` panel category
- **Kit seeding** — on first boot, writes brand colors and fonts into the active Elementor kit's post meta (guarded by the `cupcake_kit_seeded` option; runs once)
- **Theme locations** — `elementor/theme/register_locations`: exposes header/footer/archive/single for Elementor Theme Builder

### Custom widgets (`inc/elementor/widgets/`)

All 14 widgets follow the same pattern:
1. Extend `\Elementor\Widget_Base`
2. Belong to category `cupcake-content`
3. `register_controls()` defines the Elementor panel controls
4. `render()` outputs escaped HTML; theme-specific CSS tokens are injected as inline `style=""` on the wrapper element

Many widgets use `trait CupCake_Color_Sets` (`trait-color-sets.php`) for the shared four-color preset system (rose/sage/sand/berry). The Hero widget has its own two-preset system (rose/sage) resolved via `resolve_theme_tokens()`.

Widget-specific inline CSS tokens use the `--cc-[widget-name]-*` naming convention (e.g. `--cc-hero-pill-bg`).

### Template parts

- `template-parts/header/site-header.php` — sticky-capable header; logo pulled from `cupcake_logo_id` → `custom_logo` fallback; primary menu from `cupcake_primary_header_menu` → `primary` location fallback
- `template-parts/footer/site-footer.php` — two nav columns (`footer-col-1`, `footer-col-2`) + social links from Customizer URLs or `social` menu location

### Asset loading

| Handle | Source | Condition |
|---|---|---|
| `cupcake-google-fonts` | Google Fonts CDN | Always |
| `cupcake-main` (CSS) | `assets/css/main.css` | Always |
| `cupcake-elementor` (CSS) | `assets/css/elementor-overrides.css` | Elementor active |
| `cupcake-fontawesome-fallback` | cdnjs FA 5.15.4 | Elementor active |
| `cupcake-main` (JS) | `assets/js/main.js` | Always (deferred, footer) |
| `cupcake-editor-block-styles` | `assets/js/editor-block-styles.js` | Block editor only |
| `cupcake-customizer-preview` | `assets/js/customizer-preview.js` | Customizer preview only |

### Adding a new widget

1. Create `inc/elementor/widgets/class-widget-[name].php` — class name `CupCake_Widget_[Name]` extending `Widget_Base`
2. Set `get_categories()` to return `['cupcake-content']`
3. In `class-elementor-integration.php`, add a `require_once` and a `$widgets_manager->register(new CupCake_Widget_[Name]())` call in `register_widgets()`
4. Add widget-specific CSS under its BEM block in `assets/css/elementor-overrides.css`
