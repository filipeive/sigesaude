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

    public function inscricao()
    {
        return $this->belongsTo(Inscricao::class);
    }

    public function disciplina()
    {
        return $this->belongsTo(Disciplina::class, 'disciplina_id');
    }
}
