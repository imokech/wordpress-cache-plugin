<?php

namespace Cinnamon\Handlers;

use Cinnamon\Includes\CinnamonCache;
use Cinnamon\Includes\CinnamonCachePluginEnum;
use Exception;

include_once CI_CACHE . '/includes/CinnamonCache.php';
include_once CI_CACHE . '/includes/CinnamonCachePluginEnum.php';

if (!defined('ABSPATH')) exit();

class CacheHandler
{
    private $cacheObj;

    public function __construct()
    {
        $this->cacheObj = new CinnamonCache;

        $this->index();

        add_action('wp_head', [$this, 'bufferStart']);
        add_action('wp_footer', [$this, 'bufferEnd']);
    }

    public function index()
    {
        if (
            isset($_POST['cache_time']) 
            && !empty($_POST['cache_time']) 
            && isset($_POST['set_cache_time']) 
            && wp_verify_nonce($_REQUEST[CinnamonCachePluginEnum::NONCE_NAME_SETTING], CinnamonCachePluginEnum::NONCE_NAME_SETTING)
        ) {
            $cacheTime = (preg_match("/^[0-9]{1,8}$/", $_POST['cache_time'])) ? 
                         $_POST['cache_time'] : CinnamonCachePluginEnum::CACHE_MAX_TIME;
                         
            update_option(CinnamonCachePluginEnum::CACHE_TIME_META, $cacheTime);
        }

        if (
            (
                isset($_POST['flush_all_cache']) 
                && 
                wp_verify_nonce($_REQUEST[CinnamonCachePluginEnum::NONCE_NAME_PURGE], CinnamonCachePluginEnum::NONCE_NAME_PURGE)
            )
            ||
            (
                $_GET['page'] == CinnamonCachePluginEnum::PAGE_SLUG
                &&
                check_admin_referer(-1, CinnamonCachePluginEnum::NONCE_NAME_PURGE)
            )
        ) {
            $this->cacheObj->flush();
        }
    }

    public function callback($buffer) 
    {
        $scheme     = (!isset($_SERVER["HTTPS"]) || $_SERVER["HTTPS"] === "off") ? "http" : "https";
        $url        = "$scheme://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $key        = url_to_postid( $url );

        $cache = $this->cacheObj->get($key);
        $cacheTime = get_option(CinnamonCachePluginEnum::CACHE_TIME_META) ?? (24 * 60 * 60);
        
        try {
            if ($cache){
                $buffer = $cache;
            } else {
                if (!is_user_logged_in()) { 
                    $this->cacheObj->set($key, $buffer, '', $cacheTime);
                }
            }
        }
        catch (Exception $e) {
            return $e->getMessage();
        }

        return $buffer;
    }
      
    public function bufferStart() 
    {
        ob_start([$this, 'callback']);
    }
      
    public function bufferEnd()
    {
        ob_end_flush();
    }
}