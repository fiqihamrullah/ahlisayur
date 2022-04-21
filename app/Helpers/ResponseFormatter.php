<?php


namespace App\Helpers;

/**
 * Format response.
 */
class ResponseFormatter
{
    const HTTPCODE_SUCCESS = 200;
    const HTTPCODE_RESOURCE_CREATED = 201;
    const HTTPCODE_RESOURCE_DELETED = 204;
    const HTTPCODE_ERROR_INTERNALSERVERERROR = 500;
    const HTTPCODE_ERROR_SERVICEUNAVAILABLE = 503;
    const HTTPCODE_ERROR_RESOURCENOTFOUND = 404;
    const HTTPCODE_ERROR_BADREQUEST = 400;
    const HTTPCODE_ERROR_UNATHOURIZED = 401;
    const HTTPCODE_ERROR_FORBIDDEN = 403;
    const HTTPCODE_ERROR_METHODNOTALLOWED = 405;
    const HTTPCODE_ERROR_UNPROCESSABLE_ENTITY = 422;
    /**
     * API Response
     *
     * @var array
     */
    protected static $response = [        
        'status' => 'success',
        'message' => null,
        'data' => null,
    ];

    /**
     * Give success response.
     */
    public static function success($data = null, $message = null, $code = self::HTTPCODE_SUCCESS)
    {
       
        self::$response['message'] = $message;
        self::$response['data'] = $data;

        return response()->json(self::$response, $code);
    }




    /**
     * Give error response.
     */
    public static function error($data = null, $message = null, $errors = null, $code = self::HTTPCODE_ERROR_BADREQUEST)
    {
       // self::$response['code'] = $code;
        self::$response['status'] = 'error';
        self::$response['message'] = $message;
        self::$response['data'] = $data;

        if (!is_null($errors)) {
            self::$response['errors'] = $errors;
        }





        return response()->json(self::$response, $code);
    }
}
