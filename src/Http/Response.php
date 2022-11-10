<?php
namespace Phpnova\Rest\Http;

class Response
{
    public static function json(mixed $body, int $status = 200): Response
    {
        return new Response($body, $status, "json");
    }

    public static function html(string $html_string, int $status = 200)
    {

    }

    public static function textPlain(string $text, int $status = 200)
    {
        return new Response($text, $status, "text-plain");
    }

    public function __construct(private mixed $body, private int $status = 200, private string $type = "json")
    {
        
    }

    public function __call($name, $arguments)
    {
        if ($name == "send") {

            echo match($this->type) {
                'json' => json_encode($this->body),
                'text-plain' => $this->body
            };

            header('content-type: ' . match($this->type){ 
                'json' => 'application/json',
                'html' => 'text/html',
                'text-plain' => 'text-plain'
            });

            http_response_code($this->status);
        }
    }

    public function getStatus(): int 
    {
        return $this->status;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getBody()
    {
        return $this->body;
    }
}