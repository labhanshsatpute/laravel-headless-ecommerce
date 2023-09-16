<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    function sendValidationError($message, $errors) {
        return response([
            'status' => false,
            'message' => $message,
            'errors' => $errors
        ], 400);
    }

    function sendResponseOk($message, $data) {
        return response([
            'status' => true,
            'message' => $message,
            'data' => $data
        ], 200);
    }

    function sendResponseCreated($message, $data) {
        return response([
            'status' => true,
            'message' => $message,
            'data' => $data
        ], 201);
    }

    function sendResponse($status, $message, $data, $status_code) {
        return response([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ], $status_code);
    }

    function sendExceptionError($errors) {
        return response([
            'status' => false,
            'message' => "Internal server error. Please try again",
            'errors' => $errors
        ], 500);
    }
}
