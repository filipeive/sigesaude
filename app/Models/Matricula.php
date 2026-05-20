<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matricula extends Model
{
    use HasFactory;

    protected $fillable = [
        'estudante_id',
        'turma_id',
        'ano_lectivo_id',
        'valor',
        'referencia',
        'data_matricula',
        'tipo_matricula',
        'observacoes',
        'status',
        'comprovativo',
        'data_confirmacao'
    ];

    protected $casts = [
        'data_matricula' => 'date',
        'valor' => 'decimal:2',
    ];

    public function estudante()
    {
        return $this->belongsTo(Estudante::class);
    }

    public function turma()
    {
        return $this->belongsTo(Turma::class);
    }

    public function anoLectivo()
    {
        return $this->belongsTo(AnoLectivo::class);
    }

    // Gerar referência única (ATM Style - 9 dígitos)
    public static function gerarReferencia()
    {
        return '922' . str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
    }
}