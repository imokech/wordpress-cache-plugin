<?php

namespace Cinnamon\Panel;

use Cinnamon\Includes\CinnamonCachePluginEnum;
use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

include_once CI_CACHE . '/panel/Page.php';
include_once CI_CACHE . '/includes/CinnamonCachePluginEnum.php';

if (!defined('ABSPATH')) exit();

class Management extends Page
{
    public function __construct()
    {
        $this->pageTitle    = __('Cinnamon', 'cinnamon-cache');
        $this->menuTitle    = __('Cinnamon', 'cinnamon-cache');
        $this->capability   = 'manage_options';
        $this->menuSlug     = CinnamonCachePluginEnum::PAGE_SLUG;

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
        $directory = CinnamonCachePluginEnum::CACHE_DIR;
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
        if (!is_dir(CinnamonCachePluginEnum::CACHE_DIR))
            return false;

        $files = new FilesystemIterator(CinnamonCachePluginEnum::CACHE_DIR, FilesystemIterator::SKIP_DOTS);
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

        $fileSize = ($fileSizeByte <= 4096) ? 0 : $fileSize;
        
        return $fileSize;
    }
}
