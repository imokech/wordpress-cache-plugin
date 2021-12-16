<?php
/**
 * Cinnamon Cache Everything!
 *
 * @author    Mohammad Keshavarz
 * @link      https://Mokech.ir
 * @copyright Copyright © 2021 Mokech
 *
 * Plugin Name:       Cinnamon Cache
 * Plugin URI:        http://drsaina.com
 * Description:       Cinnamon Cache 
 * Version:           1.0.0
 * Author:            Mokech
 * Author URI:        https://mokech.ir
 * Text Domain:       Ci_Cache
 * License URI:       LICENSE.txt
 * Domain Path:       /languages
 * Tested up to:      5.8
 */

function CinnamonCachePlugin ()
{
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


        public function cinnamonCacheToolbarItem ( $wp_admin_bar )
        {
            $args = array(
                'id'    => 'ci_cache_item',
                'title' => 'Flush All Cache',
                'href'  => admin_url() . 'admin.php?page=cinnamon_cache_management',
            );
            $wp_admin_bar->add_node( $args );
        }   
    }

    new Cache;
}

add_action('init', 'CinnamonCachePlugin');