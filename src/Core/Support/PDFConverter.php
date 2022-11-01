<?php

namespace Src\Core\Support;

use Dompdf\Dompdf;
use Dompdf\Options;
use Exception;
use stdClass;

class PDFConverter 
{
    /** @var Dompdf */
    private $dompdf;

    /** @var stdClass */
    private $data;

    /** @var Exception */
    private $error;

    public function __construct() 
    {
        $this->dompdf = new Dompdf(['enable_remote' => true]);
        $this->data = new stdClass();
    }

    public function loadHtml(string $html = ''): void 
    {
        $this->data->html = $html;
    }

    public function setPaper(?string $size = 'A4', ?string $orientation = 'landscape'): void 
    {
        $this->data->paperSize = $size;
        $this->data->paperOrientation = $orientation;
    }

    public function render()
    {
        try {
            if(!$this->data->html) {
                throw new Exception('O HTML é necessário!');
            }

            if(!$this->data->paperSize) {
                $this->data->paperSize = 'A4';
            }

            if(!$this->data->paperOrientation) {
                $this->data->paperOrientation = 'landscape';
            }

            $this->dompdf->loadHtml($this->data->html);
            $this->dompdf->setPaper($this->data->paperSize, $this->data->paperOrientation);
            $this->dompdf->render();
        } catch(Exception $exception) {
            $this->error = $exception;
            return false;
        }
    }

    public function stream(string $filename, array $options) 
    {
        $this->dompdf->stream($filename, $options);
    }

    public function error(): ?Exception 
    {
        return $this->error;
    }
}