<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Inscricao extends Model
{
    use HasFactory;

    protected $table = 'inscricoes';

    protected $fillable = [
        'estudante_id',
        'ano_lectivo_id',
        'semestre',
        'status',
        'valor',
        'referencia',
        'data_inscricao'
    ];

    protected $casts = [
        'data_inscricao' => 'date',
        'valor' => 'decimal:2'
    ];

    /**
     * Obter o estudante associado à inscrição
     */
    public function estudante()
    {
        return $this->belongsTo(Estudante::class, 'estudante_id');
    }

    /**
     * Obter o ano letivo associado à inscrição
     */
    public function anoLectivo()
    {
        return $this->belongsTo(AnoLectivo::class, 'ano_lectivo_id');
    }

    /**
     * Obter as disciplinas associadas a esta inscrição
     */
    public function disciplinas()
    {
        return $this->belongsToMany(Disciplina::class, 'inscricao_disciplinas', 'inscricao_id', 'disciplina_id')
                    ->withPivot('tipo')
                    ->withTimestamps();
    }

    /**
     * Obter os registros de inscricao_disciplinas
     */
    public function inscricaoDisciplinas()
    {
        return $this->hasMany(InscricaoDisciplina::class);
    }
}