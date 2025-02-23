<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matricula extends Model
{
    use HasFactory;

    protected $fillable = [
        'estudante_id',
        'disciplina_id',
    ];

    // Relacionamento com estudante
    public function estudante()
    {
        return $this->belongsTo(Estudante::class);
    }

    // Relacionamento com disciplina
    public function disciplina()
    {
        return $this->belongsTo(Disciplina::class);
    }
}