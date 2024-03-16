<?php 

namespace GTG\MVC;

class Response 
{
    public function __construct(
        private array $data,
        private int $statusCode
    ) 
    {
        $this->setResponseCode();
    }

    public function setMessage(string $type, string $message): self
    {
        $this->data['message'] = [$type, $message];
        return $this;
    }

    public function setErrors(array $errors): self
    {
        $this->data['errors'] = $errors;
        return $this;
    }

    private function setResponseCode(): void
    {
        http_response_code($this->statusCode);
    }

    public function writeToJSON(): void 
    {
        echo json_encode($this->data);
    }
}