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
        if (wp_verify_nonce($_GET[Enum::NONCE_NAME])) {

        }
    }

    private function callback($buffer) 
    {
        // modify buffer here, and then return the updated code return $buffer;
    }
      
    private function bufferStart() 
    {
        ob_start("callback");
    }
      
    private function bufferEnd()
    {
        ob_end_flush();
    }


    // Cronjob
    // if (!wp_next_scheduled('my_task_hook')) {
    //    wp_schedule_event( time(), 'hourly', 'my_task_hook' );
    // }
      
    // add_action( 'my_task_hook', 'my_task_function' );
      
    // function my_task_function() {
    //    wp_mail('you@yoursite.com', 'Automatic email', 'Hello, this is an automatically scheduled email from WordPress.');
    // }

}