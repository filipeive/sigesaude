<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

// app/Models/Docente.php
class Docente extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'departamento',
        'formacao',
        'anos_experiencia',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function disciplinas()
    {
        return $this->hasMany(Disciplina::class);
    }

    public function cursos()
    {
        return $this->belongsToMany(Curso::class, 'curso_docente');
    }
}