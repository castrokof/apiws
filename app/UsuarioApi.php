<?php

namespace App;
use DateTimeInterface;

use Illuminate\Database\Eloquent\Model;

class UsuarioApi extends Model
{
    protected $table = 'usuarioapi';


    protected $fillable = [
        'idapi',
        'name',
        'email',
        'email_verified_at',
        'created_api',
        'updated_api'
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }


}
