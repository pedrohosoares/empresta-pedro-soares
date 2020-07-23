<?php

namespace App\Http\Controllers;

use App\Convenience;
use Illuminate\Http\Request;

class ConvenienceController extends Controller
{
    public function convenience(Request $request){
        try {
            $data = response(Convenience::view())->header('Content-Type','application/json')->setStatusCode(200);
        } catch (\Throwable $th) {
            $data = response('')->header('Content-Type','application/json')->setStatusCode(500,'Fail loading data');
        }
        return $data;
    }
}
