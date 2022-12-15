<?php
namespace Phpnova\Rest\Router;

use Exception;
use Phpnova\Rest\apirest;
use Phpnova\Rest\ErrorRest;
use ReflectionClass;

class Route
{
    private static AddRoute $addRoute;

    private static function _register(string $type,  callable $acction, string $path = null, string $method = null): void
    {
        try {

            if (is_string($path)){
                $path = "/" . trim($path, "/") . "/";
                $path = str_replace('//', '/', $path);

                $patterns[] = "/(:\w+)/i";
                $replacements[] = ':p';
                $path_key = preg_replace($patterns, $replacements, $path);
            }

            switch($type){
                case 'middleware':
                    apirest::addRoute($acction);
                    break;

                case 'router':
                    apirest::addRoute([
                        'key'  => $path_key,
                        'type' => 'router',
                        'path' => $path,
                        'fun'  =>  $acction,
                    ]);
                    break;
                
                default: 
                    apirest::addRoute([
                        'key'  => $path_key,
                        'type' => 'route',
                        'method' => $method,
                        'path' => $path,
                        'fun'  => $acction,
                    ]);
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public static function add(string $path): AddRoute
    {
        return self::$addRoute->setPath($path);
    }

    public static function use(mixed ...$args): void
    {
        $num_args = count($args);
        switch ($num_args){
            case 1: # Middleware
                if (!is_callable($args[0])) {
                    throw new ErrorRest("El parametro debe ser una función [callable]");
                }
                self::_register('middleware', $args[0]);
                break;

            case 2: # Router
                if (!is_string($args[0])) throw new ErrorRest("El primer parametro debe ser un string de la ruta");
                if (!is_callable($args[1])) throw new ErrorRest("El segundo parametro de ser una función [callable]");
                
                self::_register(
                    type: 'router',
                    path: $args[0],
                    acction: $args[1]
                );
                break;

                case 0: # Error
                throw new ErrorRest("La función requiere como minimo un argumento");
                break;

            default: # Error
                throw new ErrorRest("Las función solo permite maximo 2 argumentos");
                break;
        }
    }

    public static function get(string $path, callable $action): void
    {
        try {
            self::_register(
                type: 'route',
                path: $path,
                method: 'GET',
                acction: $action
            );
        } catch (\Throwable $th) {
            throw new ErrorRest($th);
        }
    }

    public static function post(string $path, callable $action): void
    {
        try {
            self::_register(
                type: 'route',
                path: $path,
                method: 'POST',
                acction: $action
            );
        } catch (\Throwable $th) {
            throw new ErrorRest($th);
        }
    }

    public static function put(string $path, callable $action): void
    {
        try {
            self::_register(
                type: 'route',
                path: $path,
                method: 'PUT',
                acction: $action
            );
        } catch (\Throwable $th) {
            throw new ErrorRest($th);
        }
    }

    public static function patch(string $path, callable $action): void
    {
        try {
            self::_register(
                type: 'route',
                path: $path,
                method: 'PATCH',
                acction: $action
            );
        } catch (\Throwable $th) {
            throw new ErrorRest($th);
        }
    }

    public static function delete(string $path, callable $action): void
    {
        try {
            self::_register(
                type: 'route',
                path: $path,
                method: 'DELETE',
                acction: $action
            );
        } catch (\Throwable $th) {
            throw new ErrorRest($th);
        }
    }
}

$refle = new ReflectionClass(Route::class);
$refle->setStaticPropertyValue('addRoute', new AddRoute());
unset($refle);