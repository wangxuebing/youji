<?php
/**
 * 存储音频文件
 * @date 2014-02-17
 */

class AudioStorage extends Storage
{
    public $baseType = 'audio';

    public function __construct($baseDir="/data1/storage") {
        parent::__construct($baseDir);
    }

    public function setMusicFile($file, $ext = "")
    {
        if (!file_exists($file)) {
            return false;
        }
        $md = $this->getFileMd($file);
        $targetDir = $this->getTargetDir($md); 
        if (!$targetDir) {
            return false;
        }
        if (!file_exists($targetDir)) {
            if (!mkdir($targetDir, 0777, true)) {
                return false;
            }
        }
        if (!is_dir($targetDir) || !is_writable($targetDir)) {
            return false;
        }

		$file_name = $md;
		if (strlen($ext) > 0) {
			$file_name .= ".{$ext}";
		}
        $targetFile = "{$targetDir}/{$file_name}";
        if (file_exists($targetFile)) {
            return $file_name;
        }

		if (true == $this->copyFile($file, $targetFile, false)) {
			return $file_name;
		} else {
			return false;
		}
    }

    public function getMusicFile($md)
    {
        $targetDir = $this->getTargetDir($md); 
        if (!$targetDir) {
            return false;
        }
        $targetFile = "{$targetDir}{$md}";
        if (!file_exists($targetFile)) {
            return false;
        }
        return $targetFile;
    }

    public function getMusicContent($md)
    {
        $targetFile = $this->getMusicFile($md);
        if (!$targetFile) {
            return false;
        }
        return file_get_contents($targetFile);
    }

    public function delMusic($md)
    {
        $targetFile = $this->getMusicFile($md);
        if (!$targetFile) {
            return false;
        }
        $res = @ unlink($targetFile);
        return $res;
    }

    public function setFile($file, $ext = "")
    {
        return $this->setMusicFile($file, $ext);
    }
    public function getFile($md)
    {
        return $this->getMusicFile($md);
    }
    public function delFile($md)
    {
        return $this->delMusic($md);
    }
}
