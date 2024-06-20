<?php 

namespace Views\Components\Modals;

use GTG\MVC\Application;
use Views\Widget;

class MediaLibraryModal extends Widget
{
    public string $addFileUrl;
    public string $removeFileUrl;
    public string $loadFilesUrl;

    public function __construct(
        public readonly bool $hasSession = false, 
    )
    {
        $router = Application::getInstance()->router;
        $this->addFileUrl = $router->route('mediaLibrary.addFile');
        $this->removeFileUrl = $router->route('mediaLibrary.deleteFile');
        $this->loadFilesUrl = $router->route('mediaLibrary.loadFiles');
    }

    public function __toString(): string 
    {
        return $this->getContext()->render('components/modals/media-library', ['view' => $this]);
    }

    public function getUploadLabelPanel(): string 
    {
        return $this->getContext()->render('components/media-library/upload-label-panel');
    }

    public function getTakePhotoPanel(): string 
    {
        return $this->getContext()->render('components/media-library/take-photo-panel');
    }

    public function getFileListPanel(): string 
    {
        return $this->getContext()->render('components/media-library/file-list-panel');
    }
}