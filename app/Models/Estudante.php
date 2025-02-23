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
        'curso_id',
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

    public function curso()
    {
        return $this->belongsTo(Curso::class);
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
}