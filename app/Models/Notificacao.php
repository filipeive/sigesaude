<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notificacao extends Model
{
    use HasFactory;

    protected $table = 'notificacoes';
    
    protected $fillable = [
        'user_id',
        'titulo',
        'mensagem',
        'tipo',
        'icone',
        'cor',
        'link',
        'lida',
        'origem_id',
        'origem_type',
        'agendada_para',
        'dados_adicionais'
    ];

    protected $casts = [
        'lida' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'agendada_para' => 'datetime',
        'dados_adicionais' => 'array'
    ];

    /**
     * Obtém o usuário que recebeu a notificação
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relação polimórfica com a origem
     */
    public function origem()
    {
        return $this->morphTo();
    }
    /**
     * Relação para contar destinatários de notificações enviadas
     */
    public function destinatarios()
    {
        return $this->hasMany(Notificacao::class, 'origem_id')
            ->where('origem_type', 'App\Models\Docente');
    }
    /**
     * Relação para contar destinatários que leram
     */
    public function destinatariosLidos()
    {
        return $this->destinatarios()->where('lida', true);
    }

    /**
     * Escopo para notificações não lidas
     */
    public function scopeNaoLidas($query)
    {
        return $query->where('lida', false);
    }

    /**
     * Helper para obter ícone baseado no tipo
     */
    public function getIconeClassAttribute()
    {
        return $this->icone ?? match($this->tipo) {
            'academico' => 'fa-book',
            'avaliacao' => 'fa-clipboard-check',
            'exame' => 'fa-file-alt',
            'presenca' => 'fa-user-clock',
            'financeiro' => 'fa-money-bill',
            'administrativo' => 'fa-building',
            'geral' => 'fa-bell',
            default => 'fa-info-circle'
        };
    }

    /**
     * Helper para obter a cor baseada no tipo
     */
    public function getCorClassAttribute()
    {
        return $this->cor ?? match($this->tipo) {
            'academico' => 'primary',
            'avaliacao' => 'warning',
            'exame' => 'danger',
            'presenca' => 'info',
            'financeiro' => 'success',
            'administrativo' => 'secondary',
            'geral' => 'info',
            default => 'primary'
        };
    }

    /**
     * Marcar notificação como lida
     */
    public function marcarComoLida()
    {
        return $this->update(['lida' => true]);
    }

    /**
     * Verifica se a notificação é recente (menos de 24h)
     */
    public function isRecente()
    {
        return $this->created_at->diffInHours(now()) < 24;
    }
    /**
 * Cria uma notificação de pagamento pendente
 */
    public static function notificarPagamentoPendente($userId, \App\Models\Pagamento $pagamento)
    {
        return self::create([
            'user_id' => $userId,
            'titulo' => 'Novo Pagamento Pendente',
            'mensagem' => "Foi gerado um novo pagamento de {$pagamento->valor} MT com vencimento em " . $pagamento->data_vencimento->format('d/m/Y'),
            'tipo' => 'financeiro',
            'icone' => 'fa-money-bill', // usa FontAwesome, ou substitui
            'cor' => 'warning',
            'link' => route('estudante.pagamentos.show', $pagamento->id),
            'lida' => false,
            'origem_id' => $pagamento->id,
            'origem_type' => get_class($pagamento),
            'dados_adicionais' => [
                'referencia' => $pagamento->referencia,
            ]
        ]);
    }

}