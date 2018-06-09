<?php

/**
 * 文件上传 - PHP300扩展类
 * Class Upload
 */

class Upload
{
    /** @var array 允许的扩展名 */
    public $allowExts = array();

    /** @var array 允许的文件类型 */
    public $allowTypes = array();

    /** @var string 文件保存路径 */
    public $savePath = './Upload/';

    /** @var int|string 最大上传大小 默认最大上传 2M = 2097152 B */
    public $maxSize = 2097152;

    //最近一次的错误
    /** @var bool 自动检测文件 默认未开启 */
    public $autoCheck = true;
    /** @var bool 是否覆盖同名文件 默认不覆盖 */
    public $uploadReplace = false;
    private $error = '';
    /** @var array 文件上传信息 */
    private $uploadFileInfo;

    /**
     * 架构函数
     * Upload constructor.
     * @param string $allowExts
     * @param string $maxSize
     * @param string $allowTypes
     */
    public function __construct($allowExts = '', $maxSize = '', $allowTypes = '')
    {
        //设置文件的后缀
        if (!empty($allowExts)) {
            if (is_array($allowExts)) {
                $this->allowExts = array_map('strtolower', $allowExts);
            } else {
                $this->allowExts = explode(',', strtolower($allowExts));
            }
        }
        if (!empty($maxSize) && is_numeric($maxSize)) {
            $this->maxSize = $maxSize;
        }
        if (!empty($allowTypes)) {
            if (is_array($allowTypes)) {
                $this->allowTypes = array_map('strtolower', $allowTypes);
            } else {
                $this->allowTypes = explode(',', strtolower($allowTypes));
            }
        }

    }

    /**
     * 上传所有文件
     * @param string $savePath
     * @return bool
     */
    public function upload($savePath = '')
    {
        if (empty($savePath))
            $savePath = $this->savePath;
        $savePath = rtrim($savePath, '/') . '/';
        if (!is_dir($savePath)) {
            $this->createDir($savePath);
            if (!is_dir($savePath)) {
                $this->error = "目录{$savePath}不存在";
                return false;
            }
        } else {
            if (!is_writeable($savePath)) {
                $this->error = "目录{$savePath}不可写";
                return false;
            }
        }

        $fileInfo = array();
        $isUpload = false;
        $files = $this->dealFiles($_FILES);
        foreach ($files as $key => $file) {
            if (!empty($file['name'])) {
                $file['key'] = $key;
                $file['extension'] = $this->getExt($file['name']);
                $file['savepath'] = $savePath;
                $file['savename'] = $this->getSaveName($file);
                if ($this->autoCheck) {
                    if (!$this->check($file))
                        return false;
                }

                if (!$this->save($file)) return false;
                unset($file['tmp_name'], $file['error']);
                $fileInfo[] = $file;
                $isUpload = true;

            }
        }
        if ($isUpload) {
            $this->uploadFileInfo = $fileInfo;
            return true;
        } else {
            $this->error = '没有选择上传文件';
            return false;
        }
    }

    /**
     * 遍历创建文件夹
     * @param $path
     */
    private function createDir($path)
    {
        if (!file_exists($path)) {
            $this->createDir(dirname($path));
            mkdir($path, 0777);
        }
    }

    /**
     * 处理$_FILES信息  将多个file分离
     * @param $files
     * @return array
     */
    private function dealFiles($files)
    {
        $fileArray = array();
        $n = 0;
        foreach ($files as $file) {
            if (is_array($file['name'])) {
                //关联数组
                $keys = array_keys($file);
                $count = count($file['name']);
                for ($i = 0; $i < $count; $i++) {
                    foreach ($keys as $key) {
                        $fileArray[$n][$key] = $file[$key][$i];
                    }
                    $n++;
                }
            } else {
                $fileArray[$n] = $file;
                $n++;
            }
        }
        return $fileArray;
    }

    /**
     * 获取扩展名
     * @param $filename
     * @return mixed
     */
    private function getExt($filename)
    {
        $pathinfo = pathinfo($filename);
        return $pathinfo['extension'];
    }

    /**
     * 文件命名 规则
     * @param $file
     * @return string
     */
    private function getSaveName($file)
    {
        $saveName = md5(uniqid()) . '.' . $file['extension'];
        return $saveName;
    }

    /**
     * 检测文件大小，文件扩展名，文件Mime类型，是否非法上传
     * @param $file
     * @return bool
     */
    private function check($file)
    {
        if ($file['error'] !== 0) {
            $this->error($file['error']);
            return false;
        }
        if (!$this->checkSize($file['size'])) {
            $this->error = '上传文件大小不符！';
            return false;
        }
        if (!$this->checkExt($file['extension'])) {
            $this->error = '上传文件类型不允许！';
            return false;
        }
        if (!$this->checkType($file['type'])) {
            $this->error = '上传文件MIME类型不允许！';
            return false;
        }
        if (!$this->checkUpload($file['tmp_name'])) {
            $this->error = '非法上传文件！';
            return false;
        }
        return true;
    }

    /**
     * 捕获错误上传信息
     * @param $errorCode
     */
    private function error($errorCode)
    {
        switch ($errorCode) {
            case 1:
                $this->error = '上传的文件超过了 php.ini 中 upload_max_filesize 选项限制的值';
                break;
            case 2:
                $this->error = '上传文件的大小超过了 HTML 表单中 MAX_FILE_SIZE 选项指定的值';
                break;
            case 3:
                $this->error = '文件只有部分被上传';
                break;
            case 4:
                $this->error = '没有文件被上传';
                break;
            case 6:
                $this->error = '找不到临时文件夹';
                break;
            case 7:
                $this->error = '文件写入失败';
                break;
            default:
                $this->error = '未知上传错误！';
        }
        return;
    }

    /**
     * 检测文件大小
     * @param $size
     * @return bool
     */
    private function checkSize($size)
    {
        return $size < $this->maxSize;
    }

    /**
     * 检测文件扩展名
     * @param $extension
     * @return bool
     */
    private function checkExt($extension)
    {
        if (!empty($this->allowExts))
            return in_array(strtolower($extension), $this->allowExts, true);
        return true;
    }

    /**
     * 检查文件Mime类型
     * @param $type
     * @return bool
     */
    private function checkType($type)
    {
        if (!empty($this->allowTypes))
            return in_array(strtolower($type), $this->allowTypes, true);
        return true;
    }

    /**
     * 检测是否非法上传
     * @param $filename
     * @return bool
     */
    private function checkUpload($filename)
    {
        return is_uploaded_file($filename);
    }

    /**
     * 保存一个文件
     * @param $file
     * @return bool
     */
    private function save($file)
    {
        $filename = $file['savepath'] . $file['savename'];
        if (!$this->uploadReplace && is_file($filename)) {
            $this->error = '文件已经存在！' . $filename;
            return false;
        }
        if (in_array(strtolower($file['extension']), array('gif', 'jpg', 'jpeg', 'bmp', 'png', 'swf')) && false === getimagesize($file['tmp_name'])) {
            $this->error = '非法图像文件';
            return false;
        }
        if (!move_uploaded_file($file['tmp_name'], $filename)) {
            $this->error = '文件上传保存错误！';
            return false;
        }
        return true;
    }

    /**
     * 通过指定文件的$_FILES['name']上传文件
     * @param $file
     * @param string $savePath
     * @return bool
     */
    public function uploadOne($file, $savePath = '')
    {
        if (empty($savePath))
            $savePath = $this->savePath;
        $savePath = rtrim($savePath, '/') . '/';
        if (!is_dir($savePath)) {
            $this->createDir($savePath);
            if (!is_dir($savePath)) {
                $this->error = "目录{$savePath}不存在";
                return false;
            }
        } else {
            if (!is_writeable($savePath)) {
                $this->error = '上传目录' . $savePath . '不可写';
                return false;
            }
        }
        if (!empty($file['name'])) {
            $fileArray = array();
            if (is_array($file['name'])) {
                $keys = array_keys($file);
                $count = count($file['name']);
                for ($i = 0; $i < $count; $i++) {
                    foreach ($keys as $key)
                        $fileArray[$i][$key] = $file[$key][$i];
                }
            } else {
                $fileArray[] = $file;
            }
            $fileInfo = array();
            foreach ($fileArray as $key => $file) {
                $file['extension'] = $this->getExt($file['name']);
                $file['savepath'] = $savePath;
                $file['savename'] = $this->getSaveName($file);
                if ($this->autoCheck) {
                    if (!$this->check($file))
                        return false;
                }
                if (!$this->save($file)) return false;
                unset($file['tmp_name'], $file['error']);
                $fileInfo[] = $file;
            }

            $this->uploadFileInfo = $fileInfo;
            return true;
        } else {
            $this->error = '没有选择上传文件';
            return false;
        }
    }

    /**
     * 获取文件上传成功之后的信息
     * @return array 文件上传信息
     */
    public function getUploadFileInfo()
    {
        return $this->uploadFileInfo;
    }

    /**
     * 获取最近一次的错误信息
     * @return string
     */
    public function getErrorMsg()
    {
        return $this->error;
    }
}