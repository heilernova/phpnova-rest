# PHPNova | REST
Libreria para el manejo de solicitudes HTTP

# Requerimiento
* Composer
* PHP 8.0+

# Instalación
```
composer nv phpnova/rest
```

# Preparación del entorno de trabajo
En la raiz del projecto se deden crear los ficherosn `index.php`, `.htaccess`, `index.json`, `env.json`
## Ficheros

### htaccess
En el fichero htaccess agraremos el siguiente comando para dirijir todas las peticiones del cliente al index.php
```
RewriteEngine On
RewriteRule ^(.*) index.php [L,QSA]
```
### index.json
En el index.json ingresaremos la cofiguración para ser utilizadar durante las peticiones HTTP
```json
{
    "timezone": "UTC",
    "databases": {
        "default": {
            "type": "mysql",
            "structure": "src/Databases"
        }
    }
}
```

## env.json
```json
{
    "databases": {
        "default": {
            "type": "mysql",
            "connectionData": {
                "hostname": "loscalhost",
                "username": "root",
                "password": "",
                "database": "test",
                "posrt": null,
                "timezone": null
            }
        }
    }
}
```

## index.php
```php
require __DIR__ . '/vendor/autoload.php';

use Phpnova\Rest\apirest;
use Phpnova\Rest\Router\Route;

$app = apirest::create();
$app->setTimezone('UTC');

$app->use('/', function(){
    Route::get('', fn() => "Hola mundo");
});

$app->run();
```

### Personalizar las respuestas
```php
# Personalizamos la respues creando una función
$app->setHandleResponse(function(Response $res): Response {

    if ($res->getStatus() == 200) {

        $body = [
            "name" => "Mi api",
            "version" => "1.0.0.BETA",
            "developers" => [
                {
                    "name" => "Mi nombre",
                    "homepage" => "https://www.miweb.com/",
                    "email" => "miemail@email.com"
                }
            ]
            "data" => $res->getBody()
        ];

        return new Response($body, $res->getStatus());
    }

    return $res;
});
```

### Manejo de errores
Manejo de errores arrojas en la ejecución de la aplicación
```php
use Throware;
$app->setHandleError(function(\Throware $th): Response {
    $json = [
        "name" => "Mi api",
        "version" => "1.0.0.BETA",
        "developers" => [
            {
                "name" => "Mi nombre",
                "homepage" => "https://www.miweb.com/",
                "email" => "miemail@email.com"
            }
        ]
        "data" => [
            "message" => $th->getMessage(),
            "file" => $th->getFile(),
            "line" => $th->getLine()
        ]
    ];

    return new Response($json, 500, 'json');
});

```