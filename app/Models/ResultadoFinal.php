<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResultadoFinal extends Model
{
    use HasFactory;

    protected $table = 'resultados_finais';

    protected $fillable = [
        'estudante_id',
        'disciplina_id',
        'ano_lectivo_id',
        'mt1',
        'mt2',
        'mt3',
        'media_frequencia',
        'nota_exame',
        'media_final',
        'classificacao_final'
    ];

    public function estudante()
    {
        return $this->belongsTo(Estudante::class);
    }

    public function disciplina()
    {
        return $this->belongsTo(Disciplina::class);
    }

    public function turma()
    {
        return $this->belongsTo(Turma::class);
    }

    public function anoLectivo()
    {
        return $this->belongsTo(AnoLectivo::class);
    }
}
