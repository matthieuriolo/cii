<?php
namespace cii\helpers;

use Yii;
use ZipArchive;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;

class FileHelper extends \yii\helpers\FileHelper {
    static public $faviconMimeTypes = ['image/x-icon'];
    static public $imageMimeTypes = [
        'image/jpeg',
        'image/png',
        'image/gif',
        'image/tiff',
        'image/svg+xml',
    ];

    static public $videoMimeTypes = [
        'video/mpeg',
        'video/mp4',
        'video/quicktime',
        'video/x-msvideo',
        'video/x-ms-asf',
    ];

    public static function compressDirectory($dir, $destination, $options = []) {
        $options = $options + [
            'excludeFiles' => [],
            'excludeDirectories' => [],
        ];

        $zip = new ZipArchive();
        if(!$zip->open($destination, ZipArchive::CREATE)) {
            return false;
        }
        
        $dirlist = new RecursiveDirectoryIterator($dir);
        $filelist = new RecursiveIteratorIterator($dirlist);

        foreach($filelist as $info) {
            if($info->getFilename() == '.' || $info->getFilename() == '..') {
                continue;
            }

            if($info->isLink()) {
                continue;
            }

            //exclude files
            if(in_array($info->getRealPath(), $options['excludeFiles'])) {
                continue;
            }

            //exclude directories and subfiles
            $dirname = dirname($info->getRealPath());
            $break = false;
            foreach($options['excludeDirectories'] as $excludeDir) {
                if(substr_compare($excludeDir, $dirname, 0, strlen($excludeDir)) === 0) {
                    $break = true;
                    break;
                }
            }

            if($break) {
                continue;
            }

            $name = ltrim(substr($info->getRealPath(), strlen($dir)), '/');
            if(!$zip->addFile($info->getRealPath(), $name)) {
                return false;
            }
        }
                
        return $zip->close();
    }
}
