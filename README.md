# Cupcake Content Theme

Custom WordPress theme with Elementor support for Cupcake Content.

## Theme Information

- Theme Name: Cupcake Content
- Version: 1.0.0
- Requires WordPress: 6.0+
- Requires PHP: 8.0+
- Text Domain: cupcake
- Repository: https://github.com/rouwe111/cupcake-content-theme

## Installation

### Option 1: Install from ZIP

1. In this repository, create a ZIP from the theme root folder.
2. In WordPress admin, go to Appearance > Themes > Add New > Upload Theme.
3. Upload the ZIP and activate the theme.

### Option 2: Install from Git

1. Clone this repository into `wp-content/themes/cupcake-content-theme`.
2. Go to Appearance > Themes and activate "Cupcake Content".

## Updating

### Manual update

1. Pull the latest changes from GitHub.
2. Replace the existing theme files in `wp-content/themes/cupcake-content-theme`.

### GitHub-based updates in WordPress

You can use a GitHub updater plugin (for example Git Updater) to receive updates directly in WordPress.

### Git Updater Plugin Setup

Use this when you want one-click theme updates in WordPress from this GitHub repository.

1. Install and activate the Git Updater plugin on your WordPress site.
2. Make sure the theme is installed and active.
3. In WordPress, go to Settings > Git Updater.
4. If the repository is private, add a GitHub Personal Access Token in the Git Updater settings.
5. Check for updates from Dashboard > Updates or from the theme screen.

Private repository note:

- The repository can stay private.
- The update check and download require a valid GitHub token on the WordPress site.

Suggested release flow:

1. Update the `Version` in `style.css`.
2. Commit and push.
3. Create a Git tag and GitHub release.
4. In WordPress, run update check and apply the update.

### Release and Tag Instructions

Use semantic versions for tags, for example `v1.0.1`.

1. Update `Version` in `style.css` to match the release (for example `1.0.1`).
2. Commit and push to `main`.
3. Create and push an annotated tag.
4. Create a GitHub release from that tag.

Example commands:

```bash
git add style.css README.md
git commit -m "Release 1.0.1"
git push origin main

git tag -a v1.0.1 -m "Release v1.0.1"
git push origin v1.0.1
```

After pushing the tag:

1. Open the repository Releases page.
2. Click "Draft a new release".
3. Select tag `v1.0.1`.
4. Publish the release notes.

If you need to correct a tag before users update:

```bash
git tag -d v1.0.1
git push origin :refs/tags/v1.0.1

git tag -a v1.0.1 -m "Release v1.0.1"
git push origin v1.0.1
```

If the update was already published, create a new version instead (for example `v1.0.2`) rather than reusing a tag.

## Preview Image

WordPress reads the theme preview from `screenshot.png` in the theme root.
Recommended size: 1200 x 900 pixels.

## Development Notes

- Main stylesheet: `style.css`
- Additional styles: `assets/css/main.css`
- JavaScript: `assets/js/main.js`
- Elementor widgets: `inc/elementor/widgets/`

## License

This repository currently uses the license declared in `style.css`.
If you want stricter usage restrictions, replace it with a proprietary license and update the theme header accordingly.
