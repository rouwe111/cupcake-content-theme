<?php
/**
 * CupCake Theme — Version info admin page.
 *
 * @package CupCake
 */

declare(strict_types=1);

defined('ABSPATH') || exit;

/**
 * Register the "CupCake Version" page under Appearance.
 */
function cupcake_register_version_page(): void {
    add_theme_page(
        __('CupCake Version', 'cupcake'),
        __('CupCake Version', 'cupcake'),
        'manage_options',
        'cupcake-version',
        'cupcake_render_version_page'
    );
}
add_action('admin_menu', 'cupcake_register_version_page');

/**
 * Fetch the latest published release from GitHub, cached for 6 hours.
 *
 * @return array{tag: string, url: string}|null
 */
function cupcake_get_latest_github_release(): ?array {
    $cached = get_transient('cupcake_latest_release');

    if (false !== $cached) {
        return is_array($cached) ? $cached : null;
    }

    $response = wp_remote_get(
        'https://api.github.com/repos/rouwe111/cupcake-content-theme/releases/latest',
        [
            'headers' => ['Accept' => 'application/vnd.github+json'],
            'timeout' => 10,
        ]
    );

    $result = null;

    if (! is_wp_error($response) && 200 === wp_remote_retrieve_response_code($response)) {
        $body = json_decode(wp_remote_retrieve_body($response), true);

        if (is_array($body) && ! empty($body['tag_name'])) {
            $result = [
                'tag' => (string) $body['tag_name'],
                'url' => (string) ($body['html_url'] ?? ''),
            ];
        }
    }

    // Cache the result (including a failed lookup) for 6 hours.
    set_transient('cupcake_latest_release', $result ?? '', 6 * HOUR_IN_SECONDS);

    return $result;
}

/**
 * Render the version info page.
 */
function cupcake_render_version_page(): void {
    if (! current_user_can('manage_options')) {
        return;
    }

    $installed_version = wp_get_theme()->get('Version') ?: __('Unknown', 'cupcake');
    $latest_release    = cupcake_get_latest_github_release();
    $latest_tag        = $latest_release['tag'] ?? null;
    $latest_version    = $latest_tag ? ltrim($latest_tag, 'v') : null;
    $update_available   = $latest_version && version_compare($latest_version, (string) $installed_version, '>');
    ?>
    <div class="wrap">
        <h1><?php esc_html_e('CupCake Content — Version', 'cupcake'); ?></h1>

        <table class="widefat striped" style="max-width: 600px;">
            <tbody>
                <tr>
                    <th scope="row"><?php esc_html_e('Installed version', 'cupcake'); ?></th>
                    <td><?php echo esc_html((string) $installed_version); ?></td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e('Latest GitHub release', 'cupcake'); ?></th>
                    <td>
                        <?php if ($latest_version) : ?>
                            <?php if (! empty($latest_release['url'])) : ?>
                                <a href="<?php echo esc_url($latest_release['url']); ?>" target="_blank" rel="noopener noreferrer">
                                    <?php echo esc_html($latest_version); ?>
                                </a>
                            <?php else : ?>
                                <?php echo esc_html($latest_version); ?>
                            <?php endif; ?>
                        <?php else : ?>
                            <em><?php esc_html_e('Unable to check (GitHub API unreachable)', 'cupcake'); ?></em>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e('Status', 'cupcake'); ?></th>
                    <td>
                        <?php if (! $latest_version) : ?>
                            <span>&mdash;</span>
                        <?php elseif ($update_available) : ?>
                            <strong style="color:#b32d2e;"><?php esc_html_e('Update available', 'cupcake'); ?></strong>
                        <?php else : ?>
                            <span style="color:#2e7d32;"><?php esc_html_e('Up to date', 'cupcake'); ?></span>
                        <?php endif; ?>
                    </td>
                </tr>
            </tbody>
        </table>

        <p style="margin-top: 16px;">
            <?php
            printf(
                /* translators: %d: number of hours */
                esc_html__('The latest release is cached for %d hours to avoid unnecessary GitHub API requests.', 'cupcake'),
                6
            );
            ?>
        </p>
    </div>
    <?php
}
