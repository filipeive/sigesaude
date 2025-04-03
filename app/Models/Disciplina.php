<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

// app/Models/Disciplina.php
class Disciplina extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'docente_id',
        'curso_id',
        'nivel_id'
    ];

    public function docente()
    {
        return $this->belongsTo(Docente::class);
    }

    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }

    public function nivel()
    {
        return $this->belongsTo(Nivel::class);
    }

    public function matriculas()
    {
        return $this->hasMany(Matricula::class);
    }
    public function inscricoes()
    {
        return $this->hasMany(Inscricao::class);
    }
    public function notasFrequencia()
    {
        return $this->hasMany(NotaFrequencia::class);
    }
    public function notasExame()
    {
        return $this->hasMany(NotaExame::class);
    }
    public function anoLectivo()
    {
        return $this->belongsTo(AnoLectivo::class);
    }
    //notas detalhadas
    public function notasDetalhadas()
    {
        return $this->hasMany(NotaDetalhada::class);
    }
    //inscricoes disciplinas
    public function inscricaoDisciplinas()
    {
        return $this->hasMany(InscricaoDisciplina::class);
    }
   
}