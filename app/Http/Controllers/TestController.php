<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;

class TestController extends Controller
{
    public function test(Request $request)
    {
        Artisan::call("migrate");
        echo Artisan::output();
        Artisan::call("db:seed");
        echo Artisan::output();
    }
}
