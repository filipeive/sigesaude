<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classe extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'nivel',
        'descricao'
    ];

    /**
     * Turmas desta classe (ex: 10ª A, 10ª B em diferentes anos)
     */
    public function turmas()
    {
        return $this->hasMany(Turma::class);
    }

    /**
     * Disciplinas desta classe (ex: Matemática da 10ª)
     */
    public function disciplinas()
    {
        return $this->hasMany(Disciplina::class);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('nome', 'LIKE', "%{$search}%")
                     ->orWhere('descricao', 'LIKE', "%{$search}%");
    }
}
