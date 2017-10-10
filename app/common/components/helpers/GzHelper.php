<?php
/**
 * Created by PhpStorm.
 * User: skripov.in
 * Date: 02.08.2017
 * Time: 10:02
 */

namespace common\components\helpers;


class GzHelper
{
    /**
     * Извлекает GZ архивы
     * @param $input_file_path
     * @param bool $delete_source_file Удалить исходный файл
     * @return null|string
     */
    public static function unpack($input_file_path, $delete_source_file = false){
        if(empty($input_file_path)){
            return null;
        }

        $file_name = basename($input_file_path);
        $path = dirname($input_file_path);

        //This input should be from somewhere else, hard-coded in this example
        // Raising this value may increase performance
        $buffer_size = 4096; // read 4kb at a time
        $output_file_name = str_replace('.gz', '', $file_name);
        $output_file_path = $path . DIRECTORY_SEPARATOR . $output_file_name;
        // Open our files (in binary mode)
        $file = gzopen($input_file_path, 'rb');
        $out_file = fopen($output_file_path, 'wb');

        // Keep repeating until the end of the input file
        while(!gzeof($file)) {
        // Read buffer-size bytes
        // Both fwrite and gzread and binary-safe
            fwrite($out_file, gzread($file, $buffer_size));
        }

        // Files are done, close files
        fclose($out_file);
        gzclose($file);

        if($delete_source_file){
            unlink($input_file_path);
        }

        if(filesize($output_file_path)){
            return $output_file_path;
        }

        return null;
    }
}