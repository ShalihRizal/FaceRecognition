<?php

namespace App\Helpers;

class ResponseFormatterHelper
{
    /**
     * Formatter data respone
     *
     * @param
     * - meta   : its contain status success respone, message for result respone, and time access
     * - data : data contain all result data
     * 
     * @return json
     *
     */

    protected static $response = [
        'meta' => [
            'status' => 'success',
            'message' => null,
            'time' => null
        ],
        'data' => null

    ];

    /**
     * Success response
     *
     * @param
     * - message    : message for response api
     * - data       : data for result for respone success
     * 
     * @return json
     *
     */
    public static function successResponse($data = null, $message = null)
    {
        self::$response['meta']['time'] = date('Y-m-d H:i:s');
        self::$response['meta']['message'] = $message;
        self::$response['data'] = $data;

        return response()->json(self::$response);
    }

    /**
     * Error response
     *
     * @param
     * - message    : message for response api
     * - data       : data default null, but it want to send data will error message its posible
     * 
     * @return json
     *
     */
    public static function errorResponse($data = null, $message = null)
    {
        self::$response['meta']['status'] = 'error';
        self::$response['meta']['message'] = $message;
        self::$response['meta']['time'] = date('Y-m-d H:i:s');
        self::$response['data'] = $data;

        return response()->json(self::$response);
    }
}
