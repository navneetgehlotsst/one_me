<?php
namespace App\Http\Traits;

trait ApiResponser
{
    public function successResponse($data, $message = null, $code = 200)
    {
        return response()->json([
            'status' => true,
            'data' => $data,
            'message' => $message,
        ], $code);
    }

    public function errorResponse($message = null, $code = 400)
    {
        return response()->json([
            'status' => false,
            'message' => $message,
        ], $code);
    }
}
