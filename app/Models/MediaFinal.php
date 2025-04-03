<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaFinal extends Model
{
    use HasFactory;

    protected $table = 'media_finals';

    protected $fillable = [
        'estudante_id',
        'disciplina_id',
        'ano_lectivo_id',
        'media',
        'status',
    ];
    public function estudante()
    {
        return $this->belongsTo(Estudante::class);
    }

    public function disciplina()
    {
        return $this->belongsTo(Disciplina::class);
    }
}
