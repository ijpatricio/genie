<?php

namespace App\Helpers;

class Contents
{
    public static function replaceInFile(string|array $search, string|array $replace, string $file)
    {
        file_put_contents(
            filename: $file,
            data: str_replace($search, $replace, file_get_contents($file))
        );
    }

    public static function replaceFile(string $replace, string $file)
    {
        file_put_contents(
            filename: $file,
            data: file_get_contents(
                filename: resource_path('stubs/'.$replace),
            ),
        );
    }
}
