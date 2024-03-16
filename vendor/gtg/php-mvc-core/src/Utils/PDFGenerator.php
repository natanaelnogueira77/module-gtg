<?php

namespace GTG\MVC\Utils;

use Dompdf\Dompdf;
use Dompdf\Options;
use GTG\MVC\Exceptions\PDFGeneratorException;
use stdClass;

class PDFGenerator 
{
    private Dompdf $dompdf;
    private Options $options;
    private stdClass $data;

    public function __construct() 
    {
        $this->options = new Options();
        $this->dompdf = new Dompdf();
        $this->data = new stdClass();
    }

    public function getDompdf(): Dompdf
    {
        return $this->dompdf;
    }

    public function setOptions(?array $options = ['isRemoteEnabled' => true, 'isPhpEnabled' => true]): self
    {
        foreach($options as $option => $value) {
            $this->options->set($option, $value);
        }
        return $this;
    }

    public function setHtml(?string $html): self 
    {
        $this->data->html = $html;
        return $this;
    }

    public function setPaper(string|array $size = 'A4', string $orientation = 'portrait'): self 
    {
        $this->data->paperSize = $size;
        $this->data->paperOrientation = $orientation;
        return $this;
    }

    public function apply(): self
    {
        $this->dompdf = new Dompdf($this->options);
        if(!$this->data->html) {
            throw new PDFGeneratorException('Error when applying. You must set the HTML!');
        }

        $this->dompdf->loadHtml($this->data->html);
        $this->dompdf->setPaper(
            $this->data->paperSize ?? 'A4', 
            $this->data->paperOrientation ?? 'portrait'
        );
        return $this;
    }

    public function render(): self
    {
        $this->dompdf->render();
        return $this;
    }

    public function stream(string $filename, array $options): void 
    {
        $this->dompdf->stream($filename, $options);
    }
}