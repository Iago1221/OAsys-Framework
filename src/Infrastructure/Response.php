<?php

namespace Framework\Infrastructure;

class Response
{
    public static function success($data = null, $status = 200)
    {
        $response = [
            'success' => true,
            'status' => $status,
            'info' => $data
        ];
        self::sendResponse($response, $status);
    }

    public static function error($message, $status = 400)
    {
        $response = [
            'success' => false,
            'status' => $status,
            'info' => [
                'message' => $message
            ]
        ];
        http_response_code($status);
        header('Content-Type: application/json');
        header("HTTP/1.1 {$status}");

        echo json_encode($response);
        exit();
    }

    private static function sendResponse($data, $status = 200)
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }
}