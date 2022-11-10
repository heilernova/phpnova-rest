<?php
namespace Phpnova\Rest\Http;

use Phpnova\Rest\apirest;
use SplFileInfo;

class File
{
    public readonly string $name;
    public readonly string $type;
    public readonly string $tmpName;
    public readonly int $size;
    public readonly mixed $error;
    public function __construct(
        array $data
    ){
        $this->name = $data['name'];
        $this->type = $data['type'];
        $this->tmpName = $data['tmp_name'];
        $this->error = $data['error'];
        $this->size = $data['size'];
    }

    public function save(string $name = null): bool
    {
        $dir = apirest::getDir();
        $file_name = $this->name;
        $file_info = new SplFileInfo($file_name);
        $file_extencion = strtolower($file_info->getExtension());

        if ($name && strlen($name) > 0) {
            if (str_ends_with($name, '/')) {
                $dir .= "/" . trim($name, '/');
            } else {
                $dirname = dirname($name);
                $basename = basename($name);
                if ($dirname != ".") $dir .= "/$dirname";

                $file_name = $basename;
                $pre = "/\.$file_extencion$/i";

                if (preg_match($pre, $file_name)) {
                    $file_name = preg_replace($pre, ".$file_extencion", $file_name);
                } else {
                    $file_name .= "." . $file_extencion;
                }
            }
        } else {
            $dir .= "/uploads";
        }

        # Cramos el directorio si no exists
        if (!file_exists($dir)) {
            $explode = $explode = explode("/", $dir);
            $tempo = "";
            foreach ($explode as $value) {
                $tempo .= "$value/";
                if (file_exists($tempo)){
                    continue;
                }
                mkdir($tempo);
            }
        }
        
        $full_name = "$dir/$file_name";
        $method = apirest::getRequest()->method;
        if ($method == "POST" || $method == "GET") {
            return move_uploaded_file($this->tmpName, $full_name);
        } else {
            return rename($this->tmpName, $full_name);
        }
        return false;
    }
}