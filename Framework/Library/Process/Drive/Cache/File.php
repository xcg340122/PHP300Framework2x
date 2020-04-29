<?php

namespace Framework\Library\Process\Drive\Cache;
use Framework\Library\Process\Running;

/**
 * 文件缓存类
 * Class File
 * @package Framework\Library\Process\Drive\Cache
 */
class File
{
    /**
     * 缓存目录
     * @var string
     */
    private $cache_dir = '';

    /**
     * 构造方法
     * File constructor.
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        $this->cache_dir = Running::$framworkPath.'/Project/runtime/tmp';
        $available_options = array('cache_dir');
        foreach ($available_options as $name) {
            if (isset($options[$name])) {
                $this->$name = $options[$name];
            }
        }
    }

    /**
     * 获取缓存
     * @param $id
     * @return bool|mixed
     */
    public function get($id)
    {
        $file_name = $this->getFileName($id);
        if (!is_file($file_name) || !is_readable($file_name)) {
            return false;
        }
        $lines = file($file_name);
        $lifetime = array_shift($lines);
        $lifetime = (int)trim($lifetime);
        if ($lifetime !== 0 && $lifetime < time()) {
            @unlink($file_name);
            return false;
        }
        $serialized = join('', $lines);
        $data = unserialize($serialized);
        return $data;
    }

    /**
     * 获取文件名称
     * @param $id
     * @return string
     */
    protected function getFileName($id)
    {
        $directory = $this->getDirectory($id);
        $hash = sha1($id, false);
        $file = $directory . DIRECTORY_SEPARATOR . $hash . '.cache';
        return $file;
    }

    /**
     * 获取目录
     * @param $id
     * @return string
     */
    protected function getDirectory($id)
    {
        $hash = sha1($id, false);
        $dirs = array(
            $this->getCacheDirectory(),
            substr($hash, 0, 2),
            substr($hash, 2, 2)
        );
        return join(DIRECTORY_SEPARATOR, $dirs);
    }

    /**
     * 获取缓存目录
     * @return string
     */
    protected function getCacheDirectory()
    {
        return $this->cache_dir;
    }

    /**
     * 删除缓存
     * @param $id
     * @return bool
     */
    public function delete($id)
    {
        $file_name = $this->getFileName($id);
		if(is_file($file_name)){
			return unlink($file_name);
		}
        return false;
    }

    /**
     * 保存缓存
     * @param $id
     * @param $data
     * @param int $lifetime
     * @return bool
     */
    public function set($id, $data, $lifetime = 3600)
    {
        $dir = $this->getDirectory($id);
        if (!is_dir($dir)) {
            if (!mkdir($dir, 0755, true)) {
                return false;
            }
        }
        $file_name = $this->getFileName($id);
        $lifetime = time() + $lifetime;
        $serialized = serialize($data);
        $result = file_put_contents($file_name, $lifetime . PHP_EOL . $serialized);
        if ($result === false) {
            return false;
        }
        return true;
    }
}