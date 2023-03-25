<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cupo extends Model
{
    use HasFactory;
    protected $fillable=[
        'ci',
        'nombres',
        'apellidos',
        'carrera',
        'foto',
        'celular',
        'direccion',
        'correo',
        'codigo',
        'credencial',
        'folder',
        'barbijo',
        'certificado',
        'tipo',
    ];

    public function materials()
    {
        return $this->hasMany(Material::class)->with('user');
    }
}
