<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GrupoAsignatura extends Model
{
    protected $table = 'grupo_asignaturas';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'integer';
    protected $fillable = [ 
        'docente_id', 
        'grupo_id',
        'asignatura_id',
        'semestre_id',
        'observacion',
    ];

    public function grupo()
    {
        return $this->belongsTo(Grupo::class);
    }

    public function semestre()
    {
        return $this->belongsTo(Semestre::class);
    }

    public function asignatura()
    {
        return $this->belongsTo(Asignatura::class);
    }

    public function docente()
    {
        return $this->belongsTo(Docente::class);
    }

}


