<?php
class Api
{
    public static function respond($data)
    {
        header('Content-Type: application/json');
        return json_encode($data);
    }

    public static function error($app, $exception = false)
    {
        if($exception) {
            $error = new ApiError($exception->getCode(), $exception->getMessage(), self::statusCode($exception));
        } else {
            $error = new ApiError("UNKNOWN_ERROR", "Unexpected error", 500);
        }
        return new Response(self::respond($error), $error->statusCode);
    }

    private static function statusCode($exception)
    {
        if($exception) {

        }
        return 500;
    }
}

class ApiError {
    public $code, $message, $statusCode;

    public function __construct($code, $message, $statusCode)
    {
        $this->code = $code;
        $this->message = $message;
        $this->statusCode = $statusCode;
    }

}
