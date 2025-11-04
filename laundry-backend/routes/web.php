<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'message' => 'Laundry Management System API',
        'version' => '1.0',
        'status' => 'running',
        'endpoints' => [
            'api' => '/api',
            'health' => '/up',
        ]
    ]);
});
