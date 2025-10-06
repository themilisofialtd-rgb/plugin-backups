<?php
namespace TMW\SA100\Core;

use TMW\SA100\Classes\Keyword_Engine;
use TMW\SA100\Classes\OpenAI_Client;
use TMW\SA100\Classes\Serper_Client;
use TMW\SA100\Classes\Settings;
use TMW\SA100\Classes\Task_Runner;

if (! defined('ABSPATH')) {
    exit;
}

/**
 * Main plugin controller.
 */
class Plugin
{
    /**
     * Plugin singleton instance.
     *
     * @var Plugin
     */
    protected static $instance;

    /**
     * Plugin settings controller.
     *
     * @var Settings
     */
    protected $settings;

    /**
     * Task runner for SEO automation routines.
     *
     * @var Task_Runner
     */
    protected $task_runner;

    /**
     * Retrieve singleton instance.
     *
     * @return Plugin
     */
    public static function get_instance()
    {
        if (! isset(static::$instance)) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * Constructor registers hooks.
     */
    protected function __construct()
    {
        $this->settings = new Settings();

        add_action('admin_menu', [$this, 'register_admin_menu']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_assets']);
        add_action('rest_api_init', [$this, 'register_rest_routes']);

        $this->task_runner = new Task_Runner(
            new Serper_Client($this->settings),
            new OpenAI_Client($this->settings),
            new Keyword_Engine()
        );

        add_filter('plugin_action_links_' . plugin_basename(TMW_SA100_PLUGIN_FILE), [$this, 'plugin_action_links']);
    }

    /**
     * Activate hook - ensure cron and default options exist.
     *
     * @return void
     */
    public static function activate()
    {
        Settings::activate();
    }

    /**
     * Deactivate hook - clean up scheduled tasks.
     *
     * @return void
     */
    public static function deactivate()
    {
        Settings::deactivate();
    }

    /**
     * Register the plugin admin menu.
     *
     * @return void
     */
    public function register_admin_menu()
    {
        add_menu_page(
            __('SEO Autopilot', 'tmw-seo-autopilot-100'),
            __('SEO Autopilot', 'tmw-seo-autopilot-100'),
            'manage_options',
            'tmw-seo-autopilot-100',
            [$this, 'render_dashboard'],
            'dashicons-admin-site-alt3'
        );

        add_submenu_page(
            'tmw-seo-autopilot-100',
            __('Keyword Engine', 'tmw-seo-autopilot-100'),
            __('Keyword Engine', 'tmw-seo-autopilot-100'),
            'manage_options',
            'tmw-sa100-keyword-engine',
            [$this, 'render_keyword_engine']
        );

        add_submenu_page(
            'tmw-seo-autopilot-100',
            __('Integrations', 'tmw-seo-autopilot-100'),
            __('Integrations', 'tmw-seo-autopilot-100'),
            'manage_options',
            'tmw-sa100-integrations',
            [$this, 'render_integrations']
        );

        add_submenu_page(
            'tmw-seo-autopilot-100',
            __('Diagnostics', 'tmw-seo-autopilot-100'),
            __('Diagnostics', 'tmw-seo-autopilot-100'),
            'manage_options',
            'tmw-sa100-diagnostics',
            [$this, 'render_diagnostics']
        );
    }

    /**
     * Enqueue admin assets.
     *
     * @return void
     */
    public function enqueue_assets($hook_suffix)
    {
        if (strpos($hook_suffix, 'tmw-seo-autopilot-100') === false) {
            return;
        }

        wp_enqueue_style(
            'tmw-sa100-admin',
            plugins_url('admin/assets/admin.css', TMW_SA100_PLUGIN_FILE),
            [],
            TMW_SA100_VERSION
        );

        wp_enqueue_script(
            'tmw-sa100-admin',
            plugins_url('admin/assets/admin.js', TMW_SA100_PLUGIN_FILE),
            ['jquery'],
            TMW_SA100_VERSION,
            true
        );

        wp_localize_script('tmw-sa100-admin', 'tmwSA100', [
            'nonce'      => wp_create_nonce('wp_rest'),
            'restUrl'    => rest_url('tmw-sa100/v1'),
            'hasSerper'  => (bool) $this->settings->get('serper_api_key'),
            'hasOpenAI'  => (bool) $this->settings->get('openai_api_key'),
        ]);
    }

    /**
     * Register custom REST routes for automation endpoints.
     *
     * @return void
     */
    public function register_rest_routes()
    {
        Rest_Routes::register($this->task_runner, $this->settings);
    }

    /**
     * Render dashboard page.
     *
     * @return void
     */
    public function render_dashboard()
    {
        tmw_sa100_view('dashboard', [
            'settings' => $this->settings,
        ]);
    }

    /**
     * Render keyword engine page.
     *
     * @return void
     */
    public function render_keyword_engine()
    {
        tmw_sa100_view('keyword-engine', [
            'task_runner' => $this->task_runner,
        ]);
    }

    /**
     * Render integrations page.
     *
     * @return void
     */
    public function render_integrations()
    {
        tmw_sa100_view('integrations', [
            'settings' => $this->settings,
        ]);
    }

    /**
     * Render diagnostics page.
     *
     * @return void
     */
    public function render_diagnostics()
    {
        tmw_sa100_view('diagnostics', [
            'settings' => $this->settings,
            'connection_checks' => [
                'serper' => tmw_sa100_is_connected('https://serper.dev/'),
                'openai' => tmw_sa100_is_connected('https://api.openai.com/v1/models'),
                'wordpress' => tmw_sa100_is_connected(home_url()),
            ],
        ]);
    }

    /**
     * Provide quick links from plugins list.
     *
     * @param array $links Current links.
     *
     * @return array
     */
    public function plugin_action_links(array $links)
    {
        $settings_link = '<a href="' . esc_url(admin_url('admin.php?page=tmw-sa100-integrations')) . '">' . __('Settings', 'tmw-seo-autopilot-100') . '</a>';
        array_unshift($links, $settings_link);

        return $links;
    }
}
