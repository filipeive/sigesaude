<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InscricaoDisciplina extends Model
{
    use HasFactory;

    protected $table = 'inscricao_disciplinas';

    protected $fillable = [
        'inscricao_id',
        'disciplina_id',
        'tipo',
    ];

    //relacao estudante
    public function estudante()
    {
        return $this->hasOne(Estudante::class, 'id', 'estudante_id');
    }

    public function inscricao()
    {
        return $this->belongsTo(Inscricao::class);
    }

    public function disciplina()
    {
        return $this->belongsTo(Disciplina::class, 'disciplina_id');
    }

    // Relacionamento com notas de frequência (singular)
    public function notasFrequencia()
    {
        return $this->hasOne(NotaFrequencia::class, 'disciplina_id', 'disciplina_id')
            ->where('estudante_id', function ($query) {
                $query->select('estudante_id')
                      ->from('inscricoes')
                      ->whereColumn('inscricoes.id', 'inscricao_disciplinas.inscricao_id')
                      ->limit(1);
            });
    }
    // Método auxiliar para obter o ID do estudante
    public function getEstudanteId()
    {
        return optional($this->inscricao)->estudante_id;
    }

}