<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calificacion extends Model
{
    use HasFactory;

    protected $table = 'calificaciones';

    protected $fillable = [
        'proyecto_id',
        'colaborador_id',
        'puntaje',
        'comentarios',
        'fecha_calificacion',
    ];

    protected $casts = [
        'fecha_calificacion' => 'datetime',
    ];

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class, 'proyecto_id');
    }

    public function colaborador()
    {
        return $this->belongsTo(User::class, 'colaborador_id');
    }
}
