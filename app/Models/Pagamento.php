<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pagamento extends Model
{
    use HasFactory;

    protected $fillable = [
        'estudante_id',
        'ano_lectivo_id',
        'valor',
        'data_pagamento',
        'referencia',
        'status',
        'data_vencimento',
        'descricao',
        'comprovante',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    // Relacionamento com estudante
    public function estudante()
    {
        return $this->belongsTo(Estudante::class);
    }

    public function anoLectivo()
    {
        return $this->belongsTo(AnoLectivo::class);
    }
    //relacao com turma
    public function turma()
    {
        return $this->belongsTo(Turma::class);
    }
    // Gerar referência única (ATM Style - 9 dígitos)
    public static function gerarReferencia()
    {
        // Exemplo: 987 + 6 dígitos aleatórios ou sequenciais
        return '921' . str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
    }
    //casts
    protected $casts = [
        'data_pagamento' => 'datetime',
        'data_vencimento' => 'datetime',
    ];
}