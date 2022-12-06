<?php
namespace Phpnova\Rest;

use Phpnova\Rest\Settings\DatabaseConfig;
use Phpnova\Rest\Settings\DatabasesSettings;

class Config
{
    public function getTimezone(): string
    {
        return apirest::getConfigData()['timezone'];
    }

    public function getDatabases(): DatabasesSettings
    {
        return new DatabasesSettings();
    }
}