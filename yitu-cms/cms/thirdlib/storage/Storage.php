<?php
/**
 * 文件存储的基类
 * @date 2014-02-17
 */

define("DEFAULT_DIR", "/data1/storage");
class Storage
{
    // subLevel the subpath level
    protected $subLevel = 2;
    public $baseType = '';
    public $baseDir = NULL;

    public function __construct($baseDir)
    {
        $this->setBaseDir($baseDir);
        if (!$baseDir)
            $this->baseDir = DEFAULT_DIR;
    }

    public function setBaseDir($dir)
    {
        if ($dir && is_dir($dir) && is_writable($dir))
            $this->baseDir = $dir;
    }

    public function getBaseDir() {
        return $this->baseDir;
    }

    public function getTargetDir($md)
    {
        $subDir = $this->getSubPath($md);
        $targetDir = "{$this->baseDir}/{$this->baseType}/";
        $targetDir .= $subDir;
        return $targetDir;
    }

    public function setHashLevel($subLevel)
    {
        if ($subLevel > 0 && $subLevel < 8)
            $this->subLevel = $subLevel;
    }

    public function getMd($str)
    {
        return md5($str);
    }

    public function getFileMd($file)
    {
        return md5_file($file);
    }

    public function getSubPath($md)
    {
        $dir = '';
        for ($i=0; $i < $this->subLevel * 2; $i+=2) {
            $dir .= substr($md, $i, 2) . '/';
        }
        return $dir;
    }

    public function getSubPathByDate()
    {
        $dir = '';
        if ($this->subLevel == 1) {
            $dir = date("Ymd", time());
        } else if ($this->subLevel == 2) {
            $dir = date("Ymd/H", time());
        } else {
            $dir = date("Ymd/H/i", time());
        }
        return $dir;
    }

    public function copyFile($src, $target, $removable)
    {
        $res = copy($src, $target);
        if (filesize($src) == 0) {
            $res = true;
        }
        if ($res && $removable) {
            $res = @ unlink($src);
        }
        return $res;
    }

    /**
     * @funciton setFile
     */
    public function setFile($file)
    {
        return false;
    }
    public function getFile($md)
    {
        return false;
    }
    public function delFile($md)
    {
        return false;
    }
}
