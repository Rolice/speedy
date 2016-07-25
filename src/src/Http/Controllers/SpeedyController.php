<?php
namespace Rolice\Speedy\Http\Controllers;

use Illuminate\Http\Request;
use Rolice\Speedy\Speedy;

class SpeedyController extends Controller
{

    public function test(Request $request)
    {
        $speedy = new Speedy();
        return response()->json($speedy->login($request->get('username'), $request->get('password')));
    }

    public function session(Request $request)
    {
        $speedy = new Speedy();

        return response()->json($speedy->activeSession($request->get('session')));
    }

    public function calculate(Request $request)
    {
        $speedy = new Speedy();
        $calculation = $speedy->calculate($request->all());

        return response()->json($calculation);
    }

}