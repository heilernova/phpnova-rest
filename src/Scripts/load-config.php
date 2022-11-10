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