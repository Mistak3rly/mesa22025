<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asistencia extends Model
{
    protected $table = 'asistencias';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'integer';
    protected $fillable = [ 
        'horario_id', 
        'fecha_hora',
        'estado',
        'observacion'
    ];

    // Relación con Horario
    public function horario()
    {
        return $this->belongsTo(Horario::class, 'horario_id');
    }

    // 🔹 Opcional: helper para verificar estado
    public function isPresente(): bool
    {
        return $this->estado === 'presente';
    }

    public function isAusente(): bool
    {
        return $this->estado === 'ausente';
    }

    public function isJustificado(): bool
    {
        return $this->estado === 'justificado';
    }
}
