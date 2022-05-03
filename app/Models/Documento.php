<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'estado',
        'link',
    ];

    //Relacion *...*
    public function users(){
        return $this->belongsToMany('App\Models\User');
    }
}

