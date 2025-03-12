<?php
// App\Models\Nivel.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class Nivel extends Model
{
    use HasFactory;
    // Se o nome da tabela não seguir a convenção (plural de "nivel"), defina explicitamente
    protected $table = 'niveis';

    // Defina os campos que podem ser preenchidos em massa
    protected $fillable = ['nome'];
}
