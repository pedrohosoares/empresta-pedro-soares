<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Convenience extends Model
{
    public static function view(){
        return Storage::disk('local')->get('convenios.json');
    }
}
