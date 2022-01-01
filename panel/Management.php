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
        $this->menuSlug     = Enum::PAGE_SLUG;

        parent::__construct();
    }

    public function index()
    {
        $cacheSize = $this->getUnitSize($this->getCacheSize());
        $cacheCount = $this->getCachedFilesCount();
        include_once CI_CACHE . DIRECTORY_SEPARATOR . "front/management.php";
    }

    private function getCacheSize()
    {
        $directory = Enum::CACHE_DIR;
        $size = 0;

        if (is_dir($directory)) {
            foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory)) as $file){
                $size += $file->getSize();
            }
        }

        return $size;
    }

    private function getCachedFilesCount()
    {
        $files = new FilesystemIterator(Enum::CACHE_DIR, FilesystemIterator::SKIP_DOTS);
        return iterator_count($files);
    }

    private function getUnitSize($fileSizeByte)
    {
        if ($fileSizeByte < 1024) {
            $fileSize = $fileSizeByte." bytes";
        }elseif (($fileSizeByte >= 1024) && ($fileSizeByte < 1048576)) {
            $fileSize = round(($fileSizeByte/1024), 2);
            $fileSize = $fileSize." KB";
        }elseif (($fileSizeByte >= 1048576) && ($fileSizeByte < 1073741824)) {
            $fileSize = round(($fileSizeByte/1048576), 2);
            $fileSize = $fileSize." MB";
        }elseif ($fileSizeByte >= 1073741824) {
            $fileSize = round(($fileSizeByte/1073741824), 2);
            $fileSize = $fileSize." GB";
        };

        return $fileSize;
    }
}
