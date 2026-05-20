<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

// app/Models/Estudante.php
class Estudante extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'matricula',
        'turma_id',
        'ano_lectivo_id',
        'data_nascimento',
        'genero',
        'ano_ingresso',
        'turno',
        'status',
        'contato_emergencia'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function turma()
    {
        return $this->belongsTo(Turma::class);
    }

    public function anoLectivo()
    {
        return $this->belongsTo(AnoLectivo::class);
    }

public function matriculas()
    {
        return $this->hasMany(Matricula::class);
    }
    
    public function pagamentos()
    {
        return $this->hasMany(Pagamento::class);
    }
public function notasFrequencia()
    {
        return $this->hasMany(NotaFrequencia::class);
    }
    public function notasExame()
    {
        return $this->hasMany(NotaExame::class);
    }
    public function notasDetalhadas()
    {
        return $this->hasMany(NotaDetalhada::class);
    }

    public function mediaFinais()
    {
        return $this->hasMany(MediaFinal::class);
    }
}