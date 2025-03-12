<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelatorioFinanceiro extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'data',
        'conteudo',
    ];
}