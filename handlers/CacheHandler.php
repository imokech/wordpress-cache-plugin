<?php

include_once CI_CACHE . '/handlers/Handler.php';
include_once CI_CACHE . '/includes/CinnamonCache.php';
include_once CI_CACHE . '/includes/Enum.php';

if (!defined('ABSPATH')) exit();

class CacheHandler extends Handler
{
    private $cacheObj;

    public function __construct()
    {
        parent::__construct();
        $this->index();

        $this->cacheObj = new CinnamonCache;

        add_action('wp_head', [$this, 'bufferStart']);
        add_action('wp_footer', [$this, 'bufferEnd']);
    }

    public function index()
    {
        if (isset($_POST['cache_time']) && !empty($_POST['cache_time']) && isset($_POST['set_cache_time']) && wp_verify_nonce($_REQUEST[Enum::NONCE_NAME_SETTING], Enum::NONCE_NAME_SETTING)) {
            $cacheTime = (preg_match("/^[0-9]{1,8}$/", $_POST['cache_time'])) ? 
                         $_POST['cache_time'] : Enum::CACHE_MAX_TIME;
                         
            update_option(Enum::CACHE_TIME_META, $cacheTime);
        }

        // var_dump(wp_verify_nonce($_REQUEST[Enum::NONCE_NAME_PURGE], Enum::NONCE_NAME_PURGE));
        if (isset($_POST['flush_all_cache']) && !empty($_POST['flush_all_cache']) && wp_verify_nonce($_REQUEST[Enum::NONCE_NAME_PURGE], Enum::NONCE_NAME_PURGE)) {
            wp_die('test');
            var_dump($this->cacheObj->flush());
        }
    }

    public function callback($buffer) 
    {
        global $post;
        $key = $post->ID ?? get_queried_object_id();

        $cache = $this->cacheObj->get(md5($key));
        $cacheTime = get_option(Enum::CACHE_TIME_META) ?? (24 * 60 * 60);

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