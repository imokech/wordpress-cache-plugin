<?php
/**
 * Cinnamon Cache Everything!
 *
 * @author    Mohammad Keshavarz
 * @link      https://Mokech.ir
 * @copyright Copyright © 2021 Mokech
 *
 * Plugin Name:       Cinnamon Cache
 * Plugin URI:        https://mokech.ir
 * Description:       Cinnamon Cache 
 * Version:           1.0.0
 * Author:            Mokech
 * Author URI:        https://mokech.ir
 * Text Domain:       Ci_Cache
 * License URI:       LICENSE.txt
 * Domain Path:       /languages
 * Tested up to:      5.8
 */

if (!defined('ABSPATH')) exit();

function CinnamonCachePlugin ()
{
    include_once plugin_dir_path(__FILE__) . '/includes/Enum.php';
  
    class Cache
    {
        public function __construct()
        {
            $this->defineConstants();
            $this->init();
            add_action( 'admin_bar_menu', [$this, 'cinnamonCacheToolbarItem'], 999 );
            add_action('admin_enqueue_scripts', [$this, 'enqueueAssets']);
        }
        
        public function defineConstants()
        {
            define('CI_CACHE', plugin_dir_path(__FILE__));
            define('CI_CACHE_URI', plugin_dir_url(__FILE__));
            define('Text_Domain', 'Ci_Cache');
        }
        
        public function init()
        {
            $this->loadSettings();
            $this->receiveRequest();
            $this->startCaching();
        }
        
        public function loadSettings()
        {
            include_once CI_CACHE . '/panel/Management.php';
            new Management;
        }
        
        private function receiveRequest()
        {
            include_once CI_CACHE . 'handlers/ManagementHandler.php';
            new ManagementHandler;
        }

        private function startCaching()
        {
            include_once CI_CACHE . 'handlers/CacheHandler.php';
            new CacheHandler;
        }

        public function cinnamonCacheToolbarItem($wpAdminBar)
        {
            $args = array(
                'id'    => 'ci_cache_item',
                'title' => __('Flush All Cache', Text_Domain),
                'href'  => wp_nonce_url(admin_url() . 'admin.php?page='.Enum::PAGE_SLUG, -1, Enum::NONCE_NAME_PURGE),
            );
            $wpAdminBar->add_node( $args );
        }   

        public function enqueueAssets()
        {
            wp_enqueue_style( 'ci-cache-style', CI_CACHE_URI . 'assets/css/style.css', [], '1.0.0');
        }
    }

    $cacheInstance = new Cache;
}

add_action('init', 'CinnamonCachePlugin');