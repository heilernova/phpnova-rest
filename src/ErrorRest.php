<?php
namespace Phpnova\Rest;

use Exception;
use Throwable;

class ErrorRest extends Exception
{
    public function __construct(Throwable $th)
    {
        $this->message = $th->getMessage();
        $this->code = $th->getCode();

        # Modificamos el archivo y la linea para que muestre el donde se ejecuta la función que crea el error
        $backtrace = debug_backtrace()[1] ?? null;
        if ($backtrace) {
            $this->file = $backtrace['file'];
            $this->line = $backtrace['line'];
        }
    }

    public static function create(string $message, $code = 0): ErrorRest
    {
        return new ErrorRest(new Exception($message, $code));
    }

    public static function next(Throwable $th): ErrorRest
    {
        return new ErrorRest($th);
    }
}