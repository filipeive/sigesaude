<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pagamento extends Model
{
    use HasFactory;

    protected $fillable = [
        'estudante_id',
        'valor',
        'data_pagamento',
        'referencia',
        'status',
        'data_vencimento',
    ];

    // Relacionamento com estudante
    public function estudante()
    {
        return $this->belongsTo(Estudante::class);
    }

    // Gerar referência única
    public static function gerarReferencia()
    {
        return 'PAG-' . now()->format('YmdHis') . '-' . rand(1000, 9999);
    }
    //casts
    protected $casts = [
        'data_pagamento' => 'datetime',
        'data_vencimento' => 'datetime',
    ];
}