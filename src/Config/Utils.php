<?php 

use Src\Program;

function url(?string $uri = null): string 
{
    if($uri) {
        if(strpos($uri, 'http://') !== false || strpos($uri, 'https://') !== false) {
            return $uri;
        }

        return Program::getEnvironmentVariables()['app_url'] . "/{$uri}";
    }

    return Program::getEnvironmentVariables()['app_url'];
}

function slugify(string $str, string $delimiter = '-'): string
{
    return strtolower(
        trim(
            preg_replace(
                '/[\s-]+/', 
                $delimiter, 
                preg_replace(
                    '/[^A-Za-z0-9-]+/', 
                    $delimiter, 
                    preg_replace('/[&]/', 'and', preg_replace('/[\']/', '', iconv('UTF-8', 'ASCII//TRANSLIT', $str)))
                )
            ), 
            $delimiter
        )
    );
}