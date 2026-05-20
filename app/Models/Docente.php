<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Docente extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'departamento_id',
        'turma_id',          // turma titular / coordenador
        'formacao',
        'anos_experiencia',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function disciplinas()
    {
        return $this->hasMany(Disciplina::class);
    }

    // Departamento
    public function departamento()
    {
        return $this->belongsTo(Departamento::class, 'departamento_id');
    }

    // Turma titular do docente (se for coordenador de turma)
    public function turma()
    {
        return $this->belongsTo(Turma::class);
    }

    // Cursos associados
    public function cursos()
    {
        return $this->belongsToMany(Curso::class, 'curso_docente');
    }

    // Turmas em que o docente leciona (via disciplinas)
    public function turmasLecionadas()
    {
        return Turma::whereIn('id', $this->disciplinas()->pluck('turma_id'))->distinct();
    }

    /**
     * Alocações do docente: disciplinas que ele lecciona em turmas específicas
     * (tabela pivot docente_turma_disciplina)
     */
    public function alocacoes()
    {
        return $this->hasMany(DocenteTurmaDisciplina::class);
    }

    /**
     * Relacionamento com as notas de frequência
     */
    public function notasFrequencia(): HasMany
    {
        return $this->hasMany(NotaFrequencia::class);
    }

    /**
     * Relacionamento com as notas de exame
     */
    public function notasExame(): HasMany
    {
        return $this->hasMany(NotaExame::class);
    }
}
