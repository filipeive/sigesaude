<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notificacao extends Model
{
    use HasFactory;
    // Definindo a tabela associada ao modelo
    protected $table = 'notificacoes';
    
    protected $fillable = [
        'user_id',
        'titulo',
        'mensagem',
        'tipo',
        'icone',
        'cor',
        'link',
        'lida'
    ];

    protected $casts = [
        'lida' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Helper para obter o ícone baseado no tipo
    public function getIconeClassAttribute()
    {
        return $this->icone ?? match($this->tipo) {
            'academico' => 'fa-book',
            'financeiro' => 'fa-money-bill',
            'administrativo' => 'fa-building',
            'geral' => 'fa-bell',
            default => 'fa-info-circle'
        };
    }

    // Helper para obter a cor baseada no tipo
    public function getCorClassAttribute()
    {
        return $this->cor ?? match($this->tipo) {
            'academico' => 'bg-primary',
            'financeiro' => 'bg-success',
            'administrativo' => 'bg-info',
            'geral' => 'bg-secondary',
            default => 'bg-info'
        };
    }
}
