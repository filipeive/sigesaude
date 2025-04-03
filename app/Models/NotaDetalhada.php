<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotaDetalhada extends Model
{
    use HasFactory;

    protected $table = 'notas_detalhadas';

    protected $fillable = [
        'notas_frequencia_id',
        'tipo',
        'nota',
    ];

    // Relação com a tabela notas_frequencia
    public function notaFrequencia()
    {
        return $this->belongsTo(NotaFrequencia::class, 'notas_frequencia_id');
    }
    
}