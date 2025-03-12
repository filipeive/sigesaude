<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    protected $dates = ['data_inscricao']; 
    public function estudante()
    {
        return $this->belongsTo(Estudante::class);
    }

    public function anoLectivo()
    {
        return $this->belongsTo(AnoLectivo::class, 'ano_lectivo_id');
    }

    public function disciplinas()
    {
        return $this->belongsToMany(Disciplina::class, 'inscricao_disciplinas', 'inscricao_id', 'disciplina_id')
                    ->withPivot('tipo'); 
    }
    
}
