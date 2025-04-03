<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotaExame extends Model
{
    use HasFactory;

    protected $table = 'notas_exame';

    protected $fillable = [
        'estudante_id',
        'disciplina_id',
        'ano_lectivo_id',
        'tipo_exame',
        'nota',
    ];

    public function estudante()
    {
        return $this->belongsTo(Estudante::class);
    }

    public function disciplina()
    {
        return $this->belongsTo(Disciplina::class);
    }
    public function anoLectivo()
    {
        return $this->belongsTo(AnoLectivo::class, 'ano_lectivo_id');
    }
}
