<?php
/**
 * 视频存储
 * @date 2013-12-02
 */

class VideoStorage extends Storage
{
    public $baseType = 'video';
    
    public function __construct($baseDir="/data1/storage")
    {
        parent::__construct($baseDir);
    }

    public function setVideoFile($file, $ext = "")
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
            return $md;
        }

		if (true == $this->copyFile($file, $targetFile, false)) {
			return $file_name;
		} else {
			return false;
		}
    }

    public function getVideoFile($md)
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

    public function getVideoContent($md)
    {
        $targetFile = $this->getVideoFile($md);
        if (!$targetFile) {
            return false;
        }
        return file_get_contents($targetFile);
    }

    public function delVideo($md)
    {
        $targetFile = $this->getVideoFile($md);
        if (!$targetFile) {
            return false;
        }
        $res = @ unlink($targetFile);
        return $res;
    }

    public function setFile($file, $ext = "")
    {
        return $this->setVideoFile($file, $ext);
    }
    public function getFile($md)
    {
        return $this->getVideoFile($md);
    }
    public function delFile($md)
    {
        return $this->delVideo($md);
    }
}
