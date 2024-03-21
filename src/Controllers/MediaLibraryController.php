<?php

namespace Src\Controllers;

use GTG\MVC\Request;
use Src\Config\{ FileSystem, Storage };

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
        return FileSystem::listFiles(Storage::getStorageFolder($this->session->getAuth()));
    }

    public function addFile(Request $request): void
    {
        if(!isset($_FILES)) {
            $this->writeUnprocessableEntityResponse([
                'message' => ['error', _('No file was found!')]
            ]);
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
            Storage::getStorageFolder($this->session->getAuth()) . '/' . $filename, 
            $file['tmp_name']
        );

        $this->writeSuccessResponse([
            'message' => ['success', _('The file was successfully loaded!')],
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
            $this->writeUnprocessableEntity([
                'message' => ['error', _('No filename was declared!')]
            ]);
            return;
        }
        
        FileSystem::deleteFile(
            Storage::getStorageFolder($this->session->getAuth()) . '/' . $request->get('name')
        );

        $this->writeSuccessResponse([
            'message' => ['success', _('The file was successfully removed.')]
        ]);
    }
}