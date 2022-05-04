<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participa extends Model
{
    use HasFactory;

    protected $fillable = [
        'usuarioId',
        'documentoId',
        
    ];
    public function documentos(){
        return $this->belongsToMany('App\Models\Documento');
    }

}
