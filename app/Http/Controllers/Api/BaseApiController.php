<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class BaseApiController extends Controller
{
    protected function responseHasil($code, $status, $data)
    {
        return response()->json([
            'code'   => $code,
            'status' => $status,
            'data'   => $data,
        ], $code);
    }
}