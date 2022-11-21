<?php

use Phpnova\Rest\apirest;

$dir = apirest::getDir();

# Verificamos que el index.json exista
if (!file_exists("$dir/index.json")) {
    
    $resource = fopen("$dir/index.json", 'a+');
    fputs($resource, file_get_contents(__DIR__ . '/index.json'));
    fclose($resource);
    
    throw new Exception("No se encontro el index.json, por este motivo se a creado el archivo con la configuración de defecto.\nNota: es necesario que lo configure para evitar errore de funcionamiento.");
}

$index_config = json_decode(file_get_contents("$dir/index.json"), true);

if (is_null($index_config)) throw new Exception("El formato del index.json es errone");

# Verifacmos en el env.json exista
if (!file_exists("$dir/env.json")) {

    $env_json['databases'] = [];

    foreach($index_config['databases'] as $name => $config) {
        $env_json['databases'][$name][$name] = [
            "hostname" => "localhost",
            "username" => "root",
            "password" => "",
            "database" => "test",
            "port" => null
        ];
    }

    $resource = fopen("$dir/env.json", 'a+');
    fputs($resource, json_encode($env_json, 128));
    fclose($resource);
    
    throw new Exception("Falta configurar el env.json");
}

$env_json = json_decode(file_get_contents("$dir/env.json"), true);

if (is_null($env_json)) throw new Exception("El formato del inv.json es erroneo");

foreach ($index_config['databases'] as $key => $config) {

    if (!array_key_exists($key, $env_json['databases'])) {
        $env_json['databases'][$key] = ["hostname" => "localhost", "username" => "root", "", "password" => "", "databases" => "test", "port" => null];
        $resource = fopen("$dir/env.json", 'w');
        fputs($resource, json_encode($env_json, 128));
        fclose($resource);
    }

    foreach ($env_json['databases'][$key] as $name => $val) {
        $index_config['databases'][$name] = $val;
    }

}

# Establecemos la zona horaria
date_default_timezone_set($index_config['timezone'] ?? 'UTC');

apirest::setConfig($index_config);