<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'descricao'
    ];

    /**
     * Obtém todos os estudantes matriculados neste curso.
     */
    public function estudantes()
    {
        return $this->hasMany(Estudante::class);
    }

    /**
     * Obtém todas as disciplinas deste curso.
     */
    public function disciplinas()
    {
        return $this->hasMany(Disciplina::class);
    }

    /**
     * Obtém todos os docentes que lecionam neste curso.
     */
    public function docentes()
    {
        return $this->belongsToMany(Docente::class, 'curso_docente')
                    ->withTimestamps();
    }

    /**
     * Escopo para buscar cursos por nome.
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('nome', 'LIKE', "%{$search}%")
                     ->orWhere('descricao', 'LIKE', "%{$search}%");
    }

    /**
     * Acessor para contar o número total de estudantes.
     */
    public function getTotalEstudantesAttribute()
    {
        return $this->estudantes()->count();
    }

    /**
     * Acessor para contar o número total de disciplinas.
     */
    public function getTotalDisciplinasAttribute()
    {
        return $this->disciplinas()->count();
    }

    /**
     * Acessor para contar o número total de docentes.
     */
    public function getTotalDocentesAttribute()
    {
        return $this->docentes()->count();
    }

    /**
     * Acessor para calcular a taxa de ocupação do curso.
     */
    public function getTaxaOcupacaoAttribute()
    {
        // Você pode definir uma capacidade máxima por curso
        $capacidadeMaxima = 100; // Exemplo
        return ($this->total_estudantes / $capacidadeMaxima) * 100;
    }
}