<?php
/** @var TMW\SA100\Classes\Settings $settings */
?>
<div class="wrap tmw-sa100-wrap">
    <h1><?php esc_html_e('Integrations', 'tmw-seo-autopilot-100'); ?></h1>

    <div class="tmw-sa100-card">
        <form id="tmw-sa100-settings-form" data-loading="<?php esc_attr_e('Saving…', 'tmw-seo-autopilot-100'); ?>" data-success="<?php esc_attr_e('Settings saved.', 'tmw-seo-autopilot-100'); ?>">
            <?php wp_nonce_field('tmw-sa100-settings', 'tmw_sa100_settings_nonce'); ?>
            <table class="form-table">
                <tr>
                    <th scope="row"><label for="tmw-sa100-serper"><?php esc_html_e('Serper API Key', 'tmw-seo-autopilot-100'); ?></label></th>
                    <td>
                        <input type="password" id="tmw-sa100-serper" name="serper_api_key" class="regular-text" value="<?php echo esc_attr($settings->get('serper_api_key')); ?>">
                        <p class="description"><?php esc_html_e('Required for live SERP intelligence.', 'tmw-seo-autopilot-100'); ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="tmw-sa100-openai"><?php esc_html_e('OpenAI API Key', 'tmw-seo-autopilot-100'); ?></label></th>
                    <td>
                        <input type="password" id="tmw-sa100-openai" name="openai_api_key" class="regular-text" value="<?php echo esc_attr($settings->get('openai_api_key')); ?>">
                        <p class="description"><?php esc_html_e('Used for outline generation and AI prompts.', 'tmw-seo-autopilot-100'); ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="tmw-sa100-default-locale"><?php esc_html_e('Default Locale', 'tmw-seo-autopilot-100'); ?></label></th>
                    <td>
                        <select name="default_locale" id="tmw-sa100-default-locale">
                            <?php $selected_locale = $settings->get('default_locale'); ?>
                            <option value="en" <?php selected($selected_locale, 'en'); ?>>English</option>
                            <option value="es" <?php selected($selected_locale, 'es'); ?>>Español</option>
                            <option value="de" <?php selected($selected_locale, 'de'); ?>>Deutsch</option>
                            <option value="fr" <?php selected($selected_locale, 'fr'); ?>>Français</option>
                        </select>
                    </td>
                </tr>
            </table>
            <?php submit_button(__('Save Integrations', 'tmw-seo-autopilot-100')); ?>
            <p class="tmw-sa100-status" aria-live="polite"></p>
        </form>
    </div>
</div>
