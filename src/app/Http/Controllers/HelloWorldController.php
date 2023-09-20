<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HelloWorldController extends Controller
{
    public function run(Request $request)
    {
        return new JsonResponse(["Hello" => "World", "From" => "PHP " . PHP_VERSION]);
    }
}