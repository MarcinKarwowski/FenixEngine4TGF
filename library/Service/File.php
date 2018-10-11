<?php

namespace App\Service;

class File extends \Phalcon\Mvc\User\Component
{
    public static function copyDir($src, $dst)
    {
        $dir = opendir($src);
        $result = ($dir === false ? false : true);

        if ($result !== false) {
            if (!is_dir($dst))
            {
                $result = @mkdir($dst);
            }
        }

        if ($result === true) {
            while (false !== ($file = readdir($dir))) {
                if (($file != '.') && ($file != '..') && $result) {
                    if (is_dir($src . '/' . $file)) {
                        $result = self::copyDir($src . '/' . $file, $dst . '/' . $file);
                    } else {
                        $result = copy($src . '/' . $file, $dst . '/' . $file);
                    }
                }
            }
            closedir($dir);
        }
        return $result;
    }

    public static function delete($dirPath, $removeDir = false) {

        if (! is_dir($dirPath)) {
            throw new UnexpectedValueException("$dirPath must be a directory");
        }
        if (substr($dirPath, strlen($dirPath) - 1, 1) != DIRECTORY_SEPARATOR) {
            $dirPath .= DIRECTORY_SEPARATOR;
        }
        $files = new \DirectoryIterator($dirPath);
        foreach ($files as $file) {
            if ($file->isDot()) {
                continue;
            }
            if ($file->isDir()) {
                self::delete($file -> getPathname(), true);
            } else {
                unlink($file -> getPathname());
            }
        }
        if ($removeDir) rmdir($dirPath);
    }

    /**
     * Attempt to determine the real file type of a file.
     *
     * @param  ParseString $extension Extension (eg 'jpg')
     *
     * @return boolean
     */
    public static function imageCheck($extension)
    {
        $allowedTypes = [
            'image/gif',
            'image/jpg',
            'image/png',
            'image/bmp',
            'image/jpeg'
        ];

        return in_array($extension, $allowedTypes);
    }
}
