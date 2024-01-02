<?php

namespace Src\API\Controllers;

use GTG\MVC\Application;
use GTG\MVC\Controller;
use Src\Components\Constants;
use Src\Components\FileSystem;

class MediaLibraryController extends Controller
{
    private function getFiles(): array 
    {
        return FileSystem::listFiles(Constants::getStorageFolder($this->session));
    }

    public function load(array $data): void 
    {
        $data = array_merge($data, filter_input_array(INPUT_GET, FILTER_UNSAFE_RAW));
        $callback = [];
        
        $files = $this->getFiles();

        $count = 0;
        $limit = $data['limit'];
        $page = $data['page'];
        
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

        $callback['pages'] = $limit != 0 ? ceil(count($files) / $limit) : 0;
        $callback['success'] = true;

        $this->APIResponse($callback, 200);
    }

    public function add(array $data): void
    {
        if(!isset($_FILES)) {
            $this->setMessage('error', _('Nenhum arquivo foi escolhido!'))->APIResponse([], 422);
            return;
        }

        $file = $_FILES['file'];

        if($file['name'] == 'blob') {
            $filename = $this->getImageCaptureFilename();
        } else {
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $basename = slugify(pathinfo($file['name'], PATHINFO_FILENAME));
            $files = $this->getFiles();
            while($files && in_array($basename . '.' . $extension, $files)) {
                $basename .= '-1';
            }
    
            $filename = $basename . '.' . $extension;
        }

        $file = FileSystem::uploadFile(
            Constants::getStorageFolder($this->session) . '/' . $filename, 
            $file['tmp_name']
        );

        if(FileSystem::error()) {
            $this->setMessage('error', FileSystem::error()->getMessage())->APIResponse([], 422);
            return;
        }

        $this->setMessage(
            'success', 
            _('O arquivo foi carregado com sucesso!')
        )->APIResponse([
            'file' => $file
        ], 200);
    }

    public function delete(array $data): void 
    {
        if(!isset($data['name'])) {
            $this->setMessage('error', _('Nenhum nome de arquivo foi declarado!'))->APIResponse([], 422);
            return;
        }
        
        FileSystem::deleteFile(Constants::getStorageFolder($this->session) . '/' . $data["name"]);
        $this->setMessage('success', _('O arquivo foi excluído com sucesso.'))->APIResponse([], 200);
    }

    private function getImageCaptureFilename(): string 
    {
        return 'image-capture' . time() . '.png';
    }
}