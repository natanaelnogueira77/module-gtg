<?php

namespace Src\App\Controllers;

use Src\App\Controllers\Controller;

class CMediaLibrary extends Controller
{
    public function load(array $data): void 
    {
        $data = array_merge($data, filter_input_array(INPUT_GET, FILTER_UNSAFE_RAW));
        $callback = [];

        $filesCount = 0;
        $files = scandir($data['root']);

        if(isset($data['search'])) {
            if($data['search'] !== '') {
                if($files) {
                    $filteredFiles = [];
                    foreach($files as $file) {
                        $name = pathinfo($file, PATHINFO_FILENAME);
                        if(strpos(strtolower($name), strtolower($data['search']))) {
                            $filteredFiles[] = $file;
                        }
                    }
                    $files = $filteredFiles;
                }
            }
        }

        if($files) {
            foreach($files as $file) {
                if($file !== '.' && $file !== '..') {
                    if($filesCount >= $data['limit'] * ($data['page'] - 1) 
                        && $filesCount < ($data['limit'] * $data['page'])) {
                        $callback['files'][] = $file;
                    }
                    $filesCount++;
                }
            }
        }

        $callback['pages'] = ceil($filesCount / $data['limit']);
        
        $this->echoCallback($callback);
    }

    public function add(array $data): void
    {
        $callback = [];

        if(isset($_FILES) && isset($data['root'])) {
            $file = $_FILES['file'];
            $root = $data['root'];

            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $basename = pathinfo($file['name'], PATHINFO_FILENAME);
            $filename = generateSlug($basename) . '-' . time() . ".{$ext}";

            if(!is_dir($root)) {
                mkdir($root);
            }

            if(move_uploaded_file($file['tmp_name'], "{$root}/{$filename}")) {
                $callback['filename'] = $filename;
                $callback['success'] = true;
            }
        }
        
        $this->echoCallback($callback);
    }

    public function delete(array $data): void 
    {
        $callback = [];

        if(isset($data['name'])) {
            $files = glob($data['root'] . "/{$data["name"]}");

            if(count($files) > 0) {
                foreach($files as $file) {
                    if(is_file($file)) {
                        unlink($file);
                        $callback['success'] = true;
                    }
                }
            }
        } else {
            $this->throwException(_('Nenhum nome de arquivo foi declarado!'));
        }
        
        $this->echoCallback($callback);
    }
}