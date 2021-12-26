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
        }
        
        public function defineConstants()
        {
            define('CI_CACHE', plugin_dir_path(__FILE__));
            define('Text_Domain', 'Ci_Cache');
        }
        
        public function init()
        {
            $this->loadSettings();
            $this->receiveRequest();
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
        
        public function cinnamonCacheToolbarItem ( $wpAdminBar )
        {
            $args = array(
                'id'    => 'ci_cache_item',
                'title' => __('Flush All Cache', Text_Domain),
                'href'  => wp_nonce_url(admin_url() . 'admin.php?page=cinnamon_cache_management', -1, Enum::NONCE_NAME),
            );
            $wpAdminBar->add_node( $args );
        }   
    }

    new Cache;
}

add_action('init', 'CinnamonCachePlugin');