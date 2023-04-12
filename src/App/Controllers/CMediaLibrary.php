<?php

namespace Src\App\Controllers;

use Src\App\Controllers\Controller;

class CMediaLibrary extends Controller
{
    public function load(array $data): void 
    {
        $data = array_merge($data, filter_input_array(INPUT_GET, FILTER_UNSAFE_RAW));
        $callback = [];

        $count = 0;
        $files = scandir($data['root']);
        $limit = $data['limit'];
        $page = $data['page'];

        $files = $files ? array_map(
            function ($o) { return $o; }, 
            array_filter($files, function ($e) {
                return !in_array($e, ['.', '..']);
            })
        ) : [];
        
        if(isset($data['search'])) {
            $search = $data['search'];
            if($search !== '') {
                $files = array_filter($files, function ($e) use ($search) {
                    return strpos(strtolower(pathinfo($e, PATHINFO_FILENAME)), strtolower($search)) !== false;
                });
            }
        }

        if(count($files) < $limit * ($page - 1)) {
            $page = 1;
        }

        foreach($files as $file) {
            if($count >= $limit * ($page - 1) && $count < $limit * $page) {
                $callback['files'][] = $file;
            }
            $count++;
        }

        $callback['pages'] = ceil((count($files) - 2) / $limit);

        $callback['success'] = true;
        $this->echoCallback($callback);
    }

    public function add(array $data): void
    {
        $callback = [];

        if(isset($_FILES) && isset($data['root'])) {
            $file = $_FILES['file'];
            $root = $data['root'];

            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $basename = slugify(pathinfo($file['name'], PATHINFO_FILENAME));

            $files = scandir($root);
            while($files && in_array($basename . '.' . $extension, $files)) {
                $basename .= '-1';
            }

            $filename = $basename . '.' . $extension;

            if(!is_dir($root)) {
                mkdir($root);
            }

            if(move_uploaded_file($file['tmp_name'], $root . '/' . $filename)) {
                $callback['filename'] = $filename;
                $callback['success'] = true;
            }

            $callback['success'] = true;
        }
        
        $this->echoCallback($callback);
    }

    public function delete(array $data): void 
    {
        $callback = [];

        if(isset($data['name'])) {
            $files = glob($data['root'] . '/' . $data["name"]);

            if(count($files) > 0) {
                foreach($files as $file) {
                    if(is_file($file)) {
                        unlink($file);
                        $callback['success'] = true;
                    }
                }
            }

            $callback['success'] = true;
        } else {
            $this->throwException(_('Nenhum nome de arquivo foi declarado!'));
        }
        
        $this->echoCallback($callback);
    }
}