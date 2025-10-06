<?php
/** @var TMW\SA100\Classes\Settings $settings */
/** @var array $connection_checks */
?>
<div class="wrap tmw-sa100-wrap">
    <h1><?php esc_html_e('Diagnostics', 'tmw-seo-autopilot-100'); ?></h1>

    <div class="tmw-sa100-card">
        <h2><?php esc_html_e('Connection Status', 'tmw-seo-autopilot-100'); ?></h2>
        <table class="widefat tmw-sa100-table">
            <thead>
                <tr>
                    <th><?php esc_html_e('Service', 'tmw-seo-autopilot-100'); ?></th>
                    <th><?php esc_html_e('Status', 'tmw-seo-autopilot-100'); ?></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?php esc_html_e('Serper API', 'tmw-seo-autopilot-100'); ?></td>
                    <td><?php echo ! empty($connection_checks['serper']) ? esc_html__('Online', 'tmw-seo-autopilot-100') : esc_html__('Offline', 'tmw-seo-autopilot-100'); ?></td>
                </tr>
                <tr>
                    <td><?php esc_html_e('OpenAI API', 'tmw-seo-autopilot-100'); ?></td>
                    <td><?php echo ! empty($connection_checks['openai']) ? esc_html__('Online', 'tmw-seo-autopilot-100') : esc_html__('Offline', 'tmw-seo-autopilot-100'); ?></td>
                </tr>
                <tr>
                    <td><?php esc_html_e('Site Frontend', 'tmw-seo-autopilot-100'); ?></td>
                    <td><?php echo ! empty($connection_checks['wordpress']) ? esc_html__('Reachable', 'tmw-seo-autopilot-100') : esc_html__('Unreachable', 'tmw-seo-autopilot-100'); ?></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="tmw-sa100-card">
        <h2><?php esc_html_e('Current Configuration', 'tmw-seo-autopilot-100'); ?></h2>
        <ul>
            <li><?php esc_html_e('Default Locale:', 'tmw-seo-autopilot-100'); ?> <strong><?php echo esc_html($settings->get('default_locale')); ?></strong></li>
            <li><?php esc_html_e('Serper API Key:', 'tmw-seo-autopilot-100'); ?> <strong><?php echo $settings->get('serper_api_key') ? esc_html__('Set', 'tmw-seo-autopilot-100') : esc_html__('Not Set', 'tmw-seo-autopilot-100'); ?></strong></li>
            <li><?php esc_html_e('OpenAI API Key:', 'tmw-seo-autopilot-100'); ?> <strong><?php echo $settings->get('openai_api_key') ? esc_html__('Set', 'tmw-seo-autopilot-100') : esc_html__('Not Set', 'tmw-seo-autopilot-100'); ?></strong></li>
        </ul>
    </div>
</div>
