<?php

namespace App\Models\Listas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Listas extends Model
{
    protected $table = 'listas';

    protected $fillable = [
    'slug',
    'nombre',
    'descripcion',
    'activo',
    'user_id'
    ];


    public function userid()
    {
        return $this->belongsTo(Usuario::class, 'user_id');
    }

    public function ListasAdd(){
        return $this->hasMany(ListasDetalle::class, 'listas_id');
    }

}
