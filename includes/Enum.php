<?php

class Enum {
    const NONCE_NAME_PURGE = '_wpnonce_purge';
    const NONCE_NAME_SETTING = '_wpnonce_setting';
    const PAGE_SLUG = 'cinnamon_cache_management';
    const CACHE_FOLDER = 'cache';
    const CACHE_TIME_META = '_ci_cache_time';
    const CACHE_MAX_TIME = 60 * 60 * 24 * 30;
    const CACHE_DIR = WP_CONTENT_DIR . DIRECTORY_SEPARATOR . Enum::CACHE_FOLDER;
}