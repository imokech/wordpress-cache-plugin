<?php

namespace Cinnamon\Panel;

if (!defined('ABSPATH')) exit();

abstract class Page
{
    protected string $pageTitle;
    protected string $menuTitle;
    protected string $capability;
    protected string $menuSlug;
    protected string $callback;

    public function __construct()
    {
        $this->capability = 'manage_options';
        add_action('admin_menu', [$this, 'addPage']);
    }

    public function addPage()
    {
        add_menu_page(
            $this->pageTitle,
            $this->menuTitle,
            $this->capability,
            $this->menuSlug,
            [$this, 'index'],
        );
    }

    abstract public function index();
}
