<?php

namespace Src\Controllers;

use GTG\MVC\Request;
use Src\Config\FileSystem;
use Src\Utils\StorageUtils;

class MediaLibraryController extends Controller
{
    public function loadFiles(Request $request): void 
    {
        $count = 0;
        $limit = $request->get('limit');
        $page = $request->get('page');
        
        $files = $this->getFiles();
        
        if($search = $request->get('search')) {
            $files = array_filter($files, function ($e) use ($search) {
                return strpos(strtolower(pathinfo($e, PATHINFO_FILENAME)), strtolower($search)) !== false;
            });
        }

        if(count($files) < $limit * ($page - 1)) {
            $page = 1;
        }

        foreach($files as $file) {
            if($count >= $limit * ($page - 1) && $count < $limit * $page) {
                $filesList[] = $file;
            }
            $count++;
        }

        $this->writeSuccessResponse([
            'pages' => $limit != 0 ? ceil(count($files) / $limit) : 0,
            'files' => $filesList
        ]);
    }

    private function getFiles(): array 
    {
        return FileSystem::listFiles(StorageUtils::getStorageFolder($this->session));
    }

    public function addFile(Request $request): void
    {
        if(!isset($_FILES)) {
            $this->writeUnprocessableEntityResponse(['message' => ['error', _('Nenhum arquivo foi encontrado!')]]);
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
            StorageUtils::getStorageFolder($this->session) . '/' . $filename, 
            $file['tmp_name']
        );

        $this->writeSuccessResponse([
            'message' => ['success', _('O arquivo foi carregado com sucesso!')],
            'file' => $file
        ]);
    }

    private function getImageCaptureFilename(): string 
    {
        return 'image-capture' . time() . '.png';
    }

    public function deleteFile(Request $request): void 
    {
        if(!$request->get('name')) {
            $this->writeUnprocessableEntity(['message' => ['error', _('Nenhum nome de arquivo foi declarado!')]]);
            return;
        }

        FileSystem::deleteFile(StorageUtils::getStorageFolder($this->session) . '/' . $request->get('name'));
        $this->writeSuccessResponse(['message' => ['success', _('O arquivo foi excluído com sucesso.')]]);
    }
}