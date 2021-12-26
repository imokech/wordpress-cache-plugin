<?php

include_once CI_CACHE . '/handlers/Handler.php';
include_once CI_CACHE . '/includes/CinnamonCache.php';
include_once CI_CACHE . '/includes/Enum.php';

if (!defined('ABSPATH')) exit();

class CacheHandler extends Handler
{
    public function __construct()
    {
        parent::__construct();
        $this->index();

        add_action('wp_head', [$this, 'bufferStart']);
        add_action('wp_footer', [$this, 'bufferEnd']);
    }

    public function index()
    {
        if (isset($_POST['']) && !empty($_POST[''])) {
            
        }
    }

    private function callback($buffer) 
    {
        // modify buffer 
    }
      
    private function bufferStart() 
    {
        ob_start("callback");
    }
      
    private function bufferEnd()
    {
        ob_end_flush();
    }
}