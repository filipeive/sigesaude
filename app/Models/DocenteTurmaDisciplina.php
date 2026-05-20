<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocenteTurmaDisciplina extends Model
{
    use HasFactory;

    protected $table = 'docente_turma_disciplina';

    protected $fillable = [
        'docente_id',
        'turma_id',
        'disciplina_id',
        'ano_lectivo_id',
    ];

    public function docente()
    {
        return $this->belongsTo(Docente::class);
    }

    public function turma()
    {
        return $this->belongsTo(Turma::class);
    }

    public function disciplina()
    {
        return $this->belongsTo(Disciplina::class);
    }

    public function anoLectivo()
    {
        return $this->belongsTo(AnoLectivo::class);
    }
}
