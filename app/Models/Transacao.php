<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transacao extends Model
{
    use HasFactory;
    protected $table = 'transacoes';
    protected $casts = [
        'data' => 'date',
    ];
    protected $fillable = [
        'descricao',
        'valor',
        'data',
        'tipo',
    ];
}
