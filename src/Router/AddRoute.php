<?php
namespace Phpnova\Rest\Router;

use Phpnova\Rest\ErrorRest;

class AddRoute
{
    private string $path;
    public function __call($name, $arguments)
    {
        if ($name == "setPath"){
            $this->path = $arguments[0];
            return $this;
        }

        throw new ErrorRest("Método invalido [$name]");
    }

    public function use(callable $fun): void
    {
        try {
            Route::use($this->path, $fun);
        } catch (\Throwable $th) {
            throw new ErrorRest($th);
        }
    }

    public function get(callable $fun): void
    {
        try {
            Route::get($this->path, $fun);
        } catch (\Throwable $th) {
            throw new ErrorRest($th);
        }
    }

    public function post(callable $fun): void
    {
        try {
            Route::post($this->path, $fun);
        } catch (\Throwable $th) {
            throw new ErrorRest($th);
        }
    }

    public function put(callable $fun): void
    {
        try {
            Route::put($this->path, $fun);
        } catch (\Throwable $th) {
            throw new ErrorRest($th);
        }
    }

    public function delete(callable $fun): void
    {
        try {
            Route::delete($this->path, $fun);
        } catch (\Throwable $th) {
            throw new ErrorRest($th);
        }
    }

    public function patch(callable $fun): void
    {
        try {
            Route::patch($this->path, $fun);
        } catch (\Throwable $th) {
            throw new ErrorRest($th);
        }
    }
}