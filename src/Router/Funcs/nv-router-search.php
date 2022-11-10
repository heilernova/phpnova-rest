<?php

use Phpnova\Rest\apirest;
use Phpnova\Rest\Http\Response;

function nv_router_search(string $url, string $http_method, string $url_parent = ""): array | Response | null
{
    # Cargamos las rutas a variable
    /** @var (array|callable)[] */
    $routes = apirest::getRoutes();

    foreach ($routes as $value) {
        if (is_array($value)) {

            # Definimos la expresión regular para buscar la ruta
            $patters = "/" . str_replace(':p', '(.+)', str_replace('/', '\/', $value['key']) ) . "/i";
            $match_result = preg_match($patters, $url);

            if ($match_result != false || $value['key'] == "/") {
                
                if ($value['type'] == 'router') {
                    # Si es un ruter cargamos las nueva rutas.
                    apirest::setRoutes( [] );
                    $value['fun'](); # Ejeuctamos la función

                    $num_delete = substr_count($value['key'], "/");
                    $url_explode = explode("/", $url);

                    $url_new = "";

                    if (is_int($match_result) && $value['key'] != "/") {
                        for ($i = $num_delete; $i < count($url_explode) - 1; $i++) {
                            $url_new .= "/" . trim($url_explode[$i] ?? "");
                        }
                        $url_new .= "/";
                    } else {
                        $url_new = $url;
                    }

                    $result = nv_router_search($url_new, $http_method, rtrim($url_parent, '/') . '/' . ltrim($value['path'], '/'));

                    if (!is_null($result)) return $result;
                } else {
                    # En caso de que sea una ruta

                    if (substr_count($url, '/') == substr_count($value['key'], '/') && $value['method'] == $http_method) {
                        $value['url'] = $url_parent  . ltrim($value['path'], '/');
                        return $value;
                    }

                    if ($value['key'] == '/' && $url == "//" && $value['method'] == $http_method) {
                        return $value;
                    }
                }
            }
            
        } else {
            # Middleware
            $result = $value(apirest::getRequest());

            if (!is_null($result)) {
                return $result instanceof Response ? $result : Response::json($result);
            }
        }
    }

    return Response::textPlain("Not found", 404);
}