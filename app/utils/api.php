<?php
use Symfony\Component\HttpFoundation\JsonResponse;

class Api
{
    public static function respond($data)
    {
        header('Content-Type: application/json');
        return new JsonResponse($data);
    }

    public static function error($app, $exception = false)
    {
        $error = self::getError($exception);
        return new JsonResponse($error, $error->statusCode);
    }

    private static function getError($exception)
    {
        if($exception) {
            $message = $exception->getMessage();
            if(strpos($message, 'Duplicate entry')) {
                return new ApiError('DUPLICATE_ENTRY', 'An item like this already exists', 400);
            }
        }
        return new ApiError('UNKNOWN_ERROR', 'Unexpected error', 500);
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
