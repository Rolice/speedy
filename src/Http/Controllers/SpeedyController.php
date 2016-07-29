<?php
namespace Rolice\Speedy\Http\Controllers;

use Illuminate\Http\Request;
use Rolice\Speedy\Speedy;

class SpeedyController extends Controller
{

    public function test(Request $request)
    {
        /**
         * @var Speedy $speedy
         */
        $speedy = app('speedy');
        return response()->json($speedy->login($request->get('username'), $request->get('password')));
    }

    public function session(Request $request)
    {
        /**
         * @var Speedy $speedy
         */
        $speedy = app('speedy');
        return response()->json($speedy->activeSession($request->get('session')));
    }

}