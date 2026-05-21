<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Disciplina pertence a uma Classe (nível escolar).
 * Todos os alunos de turmas dessa classe estudam estas disciplinas.
 */
class Disciplina extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'carga_horaria',
        'docente_id',
        'classe_id',
        'nivel_id',
    ];

    public function docente()
    {
        return $this->belongsTo(Docente::class);
    }

    /**
     * Classe a que esta disciplina pertence (ex: 10ª Classe)
     */
    public function classe()
    {
        return $this->belongsTo(Classe::class);
    }

    public function nivel()
    {
        return $this->belongsTo(Nivel::class);
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

    public function anoLectivo()
    {
        return $this->belongsTo(AnoLectivo::class);
    }

    // relacao com turma
    public function turma()
    {
        return $this->belongsTo(Turma::class);
    }

    /**
     * Obter as inscricao_disciplinas desta disciplina
     */
    public function inscricaoDisciplinas()
    {
        return $this->hasMany(InscricaoDisciplina::class, 'disciplina_id');
    }

    /**
     * Obter os resultados finais (classificacao_final) dos estudantes nesta disciplina.
     * Usa a tabela resultados_finais, que substituiu o antigo campo status de notas_frequencia.
     */
    public function resultadosFinais()
    {
        return $this->hasMany(ResultadoFinal::class);
    }
}
