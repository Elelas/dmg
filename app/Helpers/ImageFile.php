<?php
/**
 * Created by PhpStorm.
 * User: alexander
 * Date: 6/26/16
 * Time: 2:35 PM
 */

namespace App\Helpers;


class ImageFile
{
    /**
     * Фильрует файлы и возвращает только файлы с изображениями
     * @param $files
     * @return array
     */
    public static function onlyImageFile($files)
    {
        return array_filter($files, function ($fileName) {
            return !preg_match('@.git.*@', $fileName);
        });
    }
}