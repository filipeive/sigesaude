<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreInscricao extends Model
{
    use HasFactory;

    protected $table = 'pre_inscricoes';

    protected $fillable = [
        'nome_completo',
        'email',
        'telefone',
        'documento_identificacao',
        'data_nascimento',
        'genero',
        'classe_id',
        'ano_lectivo_id',
        'codigo_pre_inscricao',
        'referencia',
        'valor',
        'data_limite',
        'status'
    ];

    protected $casts = [
        'data_nascimento' => 'date',
        'data_limite' => 'datetime',
    ];

    public function classe()
    {
        return $this->belongsTo(Classe::class);
    }

    public function anoLectivo()
    {
        return $this->belongsTo(AnoLectivo::class);
    }

    // Gerar referência única (ATM Style - 9 dígitos)
    public static function gerarReferencia()
    {
        return '923' . str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
    }
}
