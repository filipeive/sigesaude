<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConfiguracaoPagamento extends Model
{
    use HasFactory;

    protected $fillable = [
        'metodo_pagamento',
        'detalhes',
    ];
}