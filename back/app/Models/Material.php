<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;
    protected $fillable=[
        'nombre',
        'fecha',
        'hora',
        'estado',
        'cupo_id',
        'user_id'
    ];
    public function user(){
        return $this->belongsTo(User::class);
    }
}
