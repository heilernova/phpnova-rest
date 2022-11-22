<?php

require __DIR__ . '/../vendor/autoload.php';

use Phpnova\Rest\apirest;
use Phpnova\Rest\Http\Request;
use Phpnova\Rest\Http\Response;
use Phpnova\Rest\Router\Route;
use Phpnova\Rest\Server;

$app = new Server();


$app->use('/', function(){
    Route::get('', function(){
        return apirest::getConfig()->getDatabase();
    });

    Route::get('saludar', function(){

        $req = new Request();
        return $req;
        return "Hola";
    });
    Route::post('saludar/:name', function(){


        // return get_required_files();
        // return apirest::getDir();
        $req = new Request();
        return $req;
        return "Hola";
    });
});

$app->setHandleResponse(function(Response $res){
    return $res;
});

$app->setTimezone('UTC');

$app->run();