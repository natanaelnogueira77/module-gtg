<?php

namespace Src\Core\App;

use Exception;
use League\Plates\Engine;
use Src\Core\App\CModel;

class MediaLibrary extends CModel
{
    public function load(array $data): void 
    {
        $data = array_merge($data, filter_input_array(INPUT_GET, FILTER_UNSAFE_RAW));
        $user = $this->getSessionUser();
        $callback = [];

        try {
            if(!$user) {
                $this->throwException('Você precisa estar logado para executar essa ação!');
            }

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
        } catch(\Exception $e) {
            $this->error = $e;
        }
        
        $this->echoCallback($callback);
    }

    public function add(array $data): void
    {
        $user = $this->getSessionUser();
        $callback = [];

        try {
            if(!$user) {
                $this->throwException('Você precisa estar logado para executar essa ação!');
            }

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
        } catch(\Exception $e) {
            $this->error = $e;
        }
        
        $this->echoCallback($callback);
    }

    public function delete(array $data): void 
    {
        $user = $this->getSessionUser();
        $callback = [];

        try {
            if(!$user) {
                $this->throwException('Você precisa estar logado para executar essa ação!');
            }

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
                $this->throwException('Nenhum nome de arquivo foi declarado!');
            }
        } catch(\Exception $e) {
            $this->error = $e;
        }
        
        $this->echoCallback($callback);
    }
}