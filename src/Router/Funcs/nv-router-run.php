<?php

use Phpnova\Rest\Http\File;
use Phpnova\Rest\Http\Request;
use Phpnova\Rest\Http\Response;

function nv_router_run(string $url, string $method): Response
{
    $res = nv_router_search($url, $method);

    # Si es null repondenmos error 404;
    if (is_null($res)) return Response::json("Not found", 404);

    if ($res instanceof Response) return $res;
   
    # Cargamos los parametros de las rutas
    $url_explode = explode('/', $url);
    $key_explode = explode('/', $res['url'] ?? '');

    $params = [];
    foreach ($key_explode as $key => $val) {
        if (str_starts_with($val, ':')) {
            $params[ltrim($val, ':')] = $url_explode[$key];
        }
    }

    Request::set('params', $params);

    # Mapeamos el contenido


    $conent_type = apache_request_headers()['content-type'] ?? (apache_request_headers()['Content-Type'] ?? null);
    switch(explode(';', $conent_type ?? '')[0]){
        case "application/json":
            $body_conent = file_get_contents("php://input");
            if ($body_conent == '') break;
            if (json_last_error() != JSON_ERROR_NONE) {
                throw new Exception("El contendio JSON enviado en el boyd tiene un error : " . json_last_error_msg());
            }
            
            Request::set("body", json_decode($body_conent));
            break;
        case "multipart/form-data":
            if ($method == 'POST' && $method != 'GET'){
                $body = array_map(array: $_POST, callback: function($item){
                    if ( preg_match('/^\{?.+\}/', $$item) > 0 || preg_match('/^\[?.+\]/', $$item) > 0){
                        $json = json_decode($item);
                        return json_last_error() == JSON_ERROR_NONE ? $json : $item;
                    }
                    return $item;
                });

                Request::set("body", $body);
                break;
            }
            
            $body = __DIR__ . '/../../Http/Scripts/script-http-parce-body.php';
            Request::set('body', $body);
            break;
        default: break;
    }

    Request::set('files', array_map(fn($item) => new File($item), $_FILES));

    $res = $res['fun'](new Request());
    return $res instanceof Response ? $res : Response::json($res);
}