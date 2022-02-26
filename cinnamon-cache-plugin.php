<?php

/**
 * Cinnamon Cache Everything!
 *
 * @author    Mohammad Keshavarz
 * @link      https://Mokech.ir
 * @copyright Copyright © 2021 Mokech
 *
 * Plugin Name:       Cinnamon Cache
 * Plugin URI:        https://mokech.ir/plugins/cinnamon-cache-plugin
 * Description:       Cinnamon Cache 
 * Version:           0.1.0
 * Author:            Mokech
 * Author URI:        https://mokech.ir
 * Text Domain:       cinnamon-cache
 * License URI:       LICENSE.txt
 * Domain Path:       /languages
 * Tested up to:      5.9
 */

use Cinnamon\Handlers\CacheHandler;
use Cinnamon\Includes\CinnamonCachePluginEnum;
use Cinnamon\Panel\Management;

if (!defined('ABSPATH')) exit();


class CinnamonCachePlugin
{
    public static function makeInstance()
    {
        $self = new self();

        add_action('init', [$self, 'init']);
        add_action('admin_bar_menu', [$self, 'cinnamonCacheToolbarItem'], 999);
        add_action('admin_enqueue_scripts', [$self, 'enqueueAssets']);
    }

    public function defineConstants()
    {
        define('CI_CACHE', plugin_dir_path(__FILE__));
        define('CI_CACHE_URI', plugin_dir_url(__FILE__));
    }

    public function init()
    {
        $this->defineConstants();
        $this->loader();
        $this->loadSettings();
        $this->startCaching();
    }

    public function loadSettings()
    {
        new Management;
    }

    private function startCaching()
    {
        new CacheHandler;
    }

    public function cinnamonCacheToolbarItem($wpAdminBar)
    {
        $args = array(
            'id'    => 'ci_cache_item',
            'title' => __('Flush All Cache', 'cinnamon-cache'),
            'href'  => wp_nonce_url(admin_url() . 'admin.php?page=' . CinnamonCachePluginEnum::PAGE_SLUG, -1, CinnamonCachePluginEnum::NONCE_NAME_PURGE),
        );
        $wpAdminBar->add_node($args);
    }

    public function enqueueAssets()
    {
        wp_enqueue_style('ci-cache-style', CI_CACHE_URI . 'assets/css/style.css', [], '1.0.0');
    }

    private function loader()
    {
        include_once CI_CACHE . 'includes/CinnamonCachePluginEnum.php';
        include_once CI_CACHE . 'panel/Management.php';
        include_once CI_CACHE . 'handlers/CacheHandler.php';
    }
}

add_action('plugins_loaded', array('CinnamonCachePlugin', 'makeInstance'));