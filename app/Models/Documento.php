<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'fecha',
        'archivo',
        'usuarioId',

    ];

    //Relacion *...*
    public function users(){
        return $this->belongsTo(User::class,'id');
    }
}

