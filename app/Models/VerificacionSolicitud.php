<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerificacionSolicitud extends Model
{
    use HasFactory;

    protected $table = 'verificacion_solicitudes';

    protected $fillable = [
        'user_id',
        'estado',
        'nota',
        'adjuntos',
    ];

    protected $casts = [
        'adjuntos' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
