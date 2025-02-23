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
    ];

    // Relacionamento com estudante
    public function estudante()
    {
        return $this->belongsTo(Estudante::class);
    }
}