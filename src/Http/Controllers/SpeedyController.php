<?php
namespace Rolice\Speedy\Http\Controllers;

use Illuminate\Http\Request;
use Rolice\Speedy\Speedy;

class SpeedyController extends Controller
{

    public function test(Request $request)
    {
        $speedy = new Speedy();
        $speedy->login($request->get('username'), $request->get('password'));
    }

}