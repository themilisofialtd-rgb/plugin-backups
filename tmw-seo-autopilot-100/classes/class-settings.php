<?php
namespace TMW\SA100\Classes;

if (! defined('ABSPATH')) {
    exit;
}

/**
 * Manages plugin configuration values stored in the options table.
 */
class Settings
{
    const OPTION_KEY = 'tmw_sa100_settings';

    /**
     * Cached settings array.
     *
     * @var array
     */
    protected $settings = [];

    /**
     * Constructor loads settings.
     */
    public function __construct()
    {
        $this->settings = get_option(static::OPTION_KEY, [
            'serper_api_key' => '',
            'openai_api_key' => '',
            'default_locale' => 'en',
        ]);
    }

    /**
     * Activation routine ensures defaults exist.
     *
     * @return void
     */
    public static function activate()
    {
        if (! get_option(static::OPTION_KEY)) {
            add_option(static::OPTION_KEY, [
                'serper_api_key' => '',
                'openai_api_key' => '',
                'default_locale' => 'en',
            ]);
        }
    }

    /**
     * Deactivation clean-up routine.
     *
     * @return void
     */
    public static function deactivate()
    {
        // Intentionally left for future scheduled cleanup.
    }

    /**
     * Retrieve all settings.
     *
     * @return array
     */
    public function all()
    {
        return $this->settings;
    }

    /**
     * Retrieve a single setting value.
     *
     * @param string $key Setting key.
     *
     * @return mixed|null
     */
    public function get($key)
    {
        return $this->settings[$key] ?? null;
    }

    /**
     * Persist many values at once.
     *
     * @param array $values Key/value pairs to update.
     *
     * @return void
     */
    public function update_many(array $values)
    {
        $this->settings = wp_parse_args($values, $this->settings);
        update_option(static::OPTION_KEY, $this->settings);
    }
}
