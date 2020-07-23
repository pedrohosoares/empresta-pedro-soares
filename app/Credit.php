<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Credit extends Model
{
    //taxas_instituicoes
    public static function view(){
        return json_decode(Storage::disk('local')->get('taxas_instituicoes.json'),true);
    }
}
