<?php
namespace Phpnova\Rest\Settings;

use Phpnova\Rest\apirest;
use Phpnova\Rest\ErrorRest;

class DatabasesSettings
{
    public function default(): DatabaseConfig
    {
        $data = current(apirest::getConfigData()['databases']);
        if ($data == false) throw new ErrorRest("No hay configuración de bases de datos");
        return new DatabaseConfig($data);
    }

    public function get(string $name): ?DatabaseConfig
    {
        $data = apirest::getConfigData()['databases'][$name] ?? null;
        // if (is_null($data)) throw new ErrorRest("No se entro la configuración de la base de datos [$name]");
        
        return $data ? new DatabaseConfig($data) :  null;
    }

    /**
     * @return DatabaseConfig[]
     */
    public function getAll(): array {
        return array_map(fn($db) => new DatabaseConfig($db), apirest::getConfigData()['databases'] );
    }
    
}
