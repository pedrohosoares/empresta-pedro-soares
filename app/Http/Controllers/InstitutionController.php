<?php

namespace App\Http\Controllers;

use App\Institution;
use Illuminate\Http\Request;

class InstitutionController extends Controller
{
    public function instution(Request $request){
        try {
            $data = response(Institution::view())->header('Content-Type','application/json')->setStatusCode(200);
        } catch (\Throwable $th) {
            $data = response('')->header('Content-Type','application/json')->setStatusCode(500,'Fail loading data');
        }
        return $data;
    }
}
