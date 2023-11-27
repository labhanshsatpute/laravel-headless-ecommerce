<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    function sendValidationError($message, $errors): Response {
        return response([
            'status' => false,
            'message' => $message,
            'errors' => $errors
        ], Response::HTTP_OK);
    }

    function sendResponseOk($message, $data): Response {
        return response([
            'status' => true,
            'message' => $message,
            'data' => $data
        ], Response::HTTP_OK);
    }

    function sendResponseCreated($message, $data): Response {
        return response([
            'status' => true,
            'message' => $message,
            'data' => $data
        ], Response::HTTP_CREATED);
    }

    function sendResponse($status, $message, $data, $status_code): Response {
        return response([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ], $status_code);
    }

    function sendExceptionError($errors): Response {
        return response([
            'status' => false,
            'message' => "Internal server error. Please try again",
            'errors' => $errors
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
