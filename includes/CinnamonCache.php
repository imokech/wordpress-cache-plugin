<?php

include_once CI_CACHE . '/includes/Enum.php';

if (!defined('ABSPATH')) exit();

class CinnamonCache
{
    private $cacheDir = Enum::CACHE_DIR;

    public function set(string $key, $value, $group = '', $expire = 0): bool
    {
        $this->checkCache();

        if (!empty($group)) {
            $base = $this->cacheDir . DIRECTORY_SEPARATOR . md5($group);
        } else {
            $base = $this->cacheDir;
        }

        try {
            $fileName = md5($key);
            wp_mkdir_p($base);

            $cacheFile = fopen($base . DIRECTORY_SEPARATOR . $fileName, "w");

            $now = time();
            if ($expire) $expire += $now;

            $data = new stdClass();
            $data->value = $value;
            $data->valid_until = $expire;

            fwrite($cacheFile, json_encode($data));
            fclose($cacheFile);
            return true;
        }
        catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function add(string $key, $value, $group = '', $expire = 0): bool
    {
        if (!$this->get('key', 'default'))
            $this->set($key, $value, $group, $expire);

        return false;
    }

    public function get(string $key, $group = '')
    {
        $this->checkCache();

        if (!empty($group)) {
            $path = $this->cacheDir . DIRECTORY_SEPARATOR . md5($group) . DIRECTORY_SEPARATOR . md5($key);
        } else {
            $path = $this->cacheDir . DIRECTORY_SEPARATOR . md5($key);
        }
        if (file_exists($path)) {
            try {
                $cacheFile = fopen($path, 'r');
                $cacheData = fread($cacheFile, filesize($path));

                $cacheData = json_decode($cacheData);
                $expireDate = $cacheData->valid_until;
                $data = $cacheData->value;

                if ($expireDate < time()) {
                    $this->delete($key, $group);
                    return false;
                }
                return $data;
            }
            catch (Exception $e) {
                return $e->getMessage();
            }
        }
    }

    public function delete(string $key, $group = ''): bool
    {
        if (!empty($group)) {
            $path = $this->cacheDir . DIRECTORY_SEPARATOR . md5($group) . DIRECTORY_SEPARATOR . md5($key);
        } else {
            $path = $this->cacheDir . DIRECTORY_SEPARATOR . md5($key);
        }

        if (file_exists($path))
            return unlink($path);

        return false;
    }

    public function flush(): bool
    {
        if (is_dir($this->cacheDir)) {
            $it = new RecursiveDirectoryIterator($this->cacheDir, RecursiveDirectoryIterator::SKIP_DOTS);
            $files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);
            try {
                foreach ($files as $file) {
                    if ($file->isDir()) {
                        rmdir($file->getRealPath());
                    } else {
                        unlink($file->getRealPath());
                    }
                }
            }
            catch (Exception $e) {
                return $e->getMessage();
            }

            return true;
        }

        return false;
    }

    private function checkCache()
    {
        if (!WP_CACHE) 
            return false;
        
        if (!defined('WP_CACHE')) 
            defined('WP_CACHE', true);
    }
}
