<?php
namespace Phpnova\Rest;

class Config
{
    public function getTimezone(): string
    {
        return apirest::getConfigData()['timezone'];
    }

    /**
     * Retorna un array asociativo de la configuración de la base de datos
     */
    public function getDatabase(string $name = null): array
    {
        if (is_null($name)) {
            current(apirest::getConfigData()['databases']);
        }

        return apirest::getConfigData()['databases'][$name];
    }
}