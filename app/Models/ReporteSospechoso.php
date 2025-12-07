<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReporteSospechoso extends Model
{
    use HasFactory;

    protected $table = 'reportes_sospechosos';

    protected $fillable = [
        'colaborador_id',
        'proyecto_id',
        'motivo',
        'evidencias',
        'estado',
        'respuesta',
    ];

    protected $casts = [
        'evidencias' => 'array',
    ];

    public function colaborador()
    {
        return $this->belongsTo(User::class, 'colaborador_id');
    }

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class);
    }
}
