<?php
namespace Phpnova\Rest;

use Exception;
use Phpnova\Rest\Http\Request;

class apirest
{
    private static array $routes = [];
    private static array $request;
    private static array $data = [];

    public static function create(): Server
    {
        return new Server();
    }

    public static function __callStatic($name, $arguments)
    {
        // echo $name . "\n";
        if ($name == 'addRoute') {
            self::$routes[] = $arguments[0];
            return;
        }

        if ($name == 'getRoutes') {
            return self::$routes;
        }
        if ($name == 'setRoutes') {
            self::$routes = $arguments[0];
            return;
        }

        if ($name == 'setRequest') {
            self::$request = $arguments[0];
            return;
        }

        if ($name == 'set') {
            self::$data[$arguments[0]] = $arguments[1];
            return;
        }



        throw new ErrorRest(new Exception("Método indefinido Phpnova\\Rest\\apirest::$name"));
    }

    public static function getRequest(): Request
    {
        return new Request();
    }

    public static function getDir(): string
    {
        return self::$data['dir'] ?? '';
    }
}

# Cargamos el ruta del directorio princial
foreach (get_required_files() as $file) {
    if (str_ends_with($file, 'autoload.php')) {
        apirest::set('dir', dirname($file, 2));
        try {
            require __DIR__ . '/Scripts/load-config.php';
        } catch (\Throwable $th) {
            echo $th->getMessage();
            header('content-type: text/plain');
            http_response_code(500);
            exit;
        }
        break;
    }
}