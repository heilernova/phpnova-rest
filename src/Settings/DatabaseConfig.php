<?php
namespace Phpnova\Rest\Settings;

use PDO;
use Phpnova\Rest\ErrorRest;

class DatabaseConfig
{
    public readonly string $type;
    public readonly string  $timezone;
    private readonly ?string $structure;
    public readonly string $hostname;
    public readonly string $username;
    public readonly string $password;
    public readonly string $database;
    public readonly ?string $port;

    public function __construct(array $data)
    {
        try {
            foreach($data as $key => $val){
                $this->$key = $val;
            }
        } catch (\Throwable $th) {
            throw new ErrorRest($th);
        }
    }

    public function getPDO(): PDO
    {
        try {
            $host = $this->hostname;
            $user = $this->username;
            $pass = $this->password;
            $db = $this->database;

            switch($this->type){
                case "mysql": return new PDO("mysql:host=$host; dbname=$db", $user, $pass);
                case "pgsql": return new PDO("pgsql:host=$host; dbname=$db", $user, $pass);
                default:
                    throw new ErrorRest("No hay soporte para el motor de la base de datos [" . $this->type .  "]");
            }
        } catch (\Throwable $th) {
            throw new ErrorRest($th);
        }
    }

    /**
     * Retorna la estructra de la base de datos
     */
    public function getStructure(): string
    {
        return "";
    }

    public function install(): array
    {
        return ['status' => true];
    }
}