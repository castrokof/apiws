<?php

namespace App\Models\compras;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Documentos extends Model
{
   protected $table = 'documentos';

    protected $fillable = [
    'documento',
    'consecutivo',
    'observacion',
    'user_id'
    ];


    public function useridd()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
