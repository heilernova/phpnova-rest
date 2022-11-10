<?php

namespace Phpnova\Rest;

use Exception;
use Phpnova\Rest\ErrorRest;
use Phpnova\Rest\Http\HttpFuns;
use Phpnova\Rest\Http\Request;
use Phpnova\Rest\Http\Response;
use Phpnova\Rest\Router\Route;

class Server
{
    private mixed $handleResponse = null;
    private mixed $handleError = null;

    public function setHandleResponse(callable $fun): void
    {
        $this->handleResponse = $fun;
    }

    public function setHandleError(callable $fun): void
    {

    }

    public function setTimezone(string $timezone): void
    {
        date_default_timezone_set($timezone);
    }

    public function use(mixed ...$arg): void
    {
        try {
            Route::use(...$arg);
        } catch (\Throwable $th) {
            throw new ErrorRest($th);
        }
    }

    public function run(): never
    {
        try {
            $url = "/" . urldecode(explode( '?', trim(substr($_SERVER['REQUEST_URI'], strlen(dirname($_SERVER['SCRIPT_NAME']))), "/"))[0]) . "/";
            $method = $_SERVER['REQUEST_METHOD'];
            Request::set('url', $url);
            Request::set('method', $method);
            Request::set('queryParams', $_GET);
            Request::set('ip', HttpFuns::getIP());
            Request::set('device', HttpFuns::getDevice());
            Request::set('platform', HttpFuns::getPlatform());

            $response = nv_router_run($url, $method);

            if (is_callable($this->handleResponse)) {
                $fun = $this->handleResponse;
                $response = $fun($response);

                if (!($response instanceof Response)) {
                    throw new ErrorRest(new Exception("La funcion handleResponse debe retornar un Phpnova\Rest\Http\Response"));
                }
            }

        } catch (\Throwable $th) {
            $messsage = $th->getMessage() . "\n";
            $messsage .= "File: " . $th->getFile() . "\n";
            $messsage .= "Line: " . $th->getLine();

            $response = Response::textPlain($messsage, 500);
        }

        $response->send();
        exit;
    }
}