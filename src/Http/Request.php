<?php
namespace Phpnova\Rest\Http;

use Exception;
use Phpnova\Rest\ErrorRest;
use Throwable;

class Request
{
    /**
     * URL of the HTTP request
     */
    public readonly string $url;

    /**
     * Method of HTTP request
     */
    public readonly string $method;

    /**
     * HTTP request content
     */
    public readonly mixed $body;

    /**
     * Array of files sent in the HTTP query
     * @var File[] 
     * */
    public readonly array $files;
    
    /** URL parameters */
    public readonly array $params;

    /**
     * Query parameter
    */
    public readonly array $queryParams;

    /**
     * IP from which the HTTP request is being made
     * */
    public readonly string $ip;

    /** 
     * Device from which the HTTP request is being made
     * @return 'desktop'|'mobile'|'table'
     */
    public readonly string $device;

    public readonly string $platform;

    public function __construct()
    {
        $this->url = self::$data['url'] ?? '';
        $this->method = self::$data['method'] ?? '';
        $this->body = self::$data['body'] ;
        $this->files = self::$data['files'] ?? [];
        $this->params = self::$data['params'] ?? [];
        $this->queryParams = self::$data['queryParams'] ?? [];

        $this->ip = self::$data['ip'] ?? '';
        $this->device = self::$data['device'] ?? '';
        $this->platform = self::$data['platform'] ?? '';
    }

    private static array $data = [];
    public static function __callStatic($name, $arguments)
    {
        if ($name == 'set'){
            self::$data[$arguments[0]] = $arguments[1];
            return;
        }
        
        throw new ErrorRest(new Exception("Error en el llamado del método Phpnova\\Rest\\Http\\Request::$name"));
    }
}