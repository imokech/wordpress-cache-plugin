<?php

include_once CI_CACHE . '/panel/Page.php';
include_once CI_CACHE . '/includes/Enum.php';

if (!defined('ABSPATH')) exit();

class Management extends Page
{
    public function __construct()
    {
        $this->pageTitle    = __('Cinnamon', Text_Domain);
        $this->menuTitle    = __('Cinnamon', Text_Domain);
        $this->capability   = 'manage_options';
        $this->menuSlug     = 'cinnamon_cache_management';

        parent::__construct();
    }

    public function index()
    {
        include_once CI_CACHE . DIRECTORY_SEPARATOR . "front/management.php";
    }

}
