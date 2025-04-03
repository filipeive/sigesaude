<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotaFrequencia extends Model
{
    use HasFactory;

    protected $table = 'notas_frequencia';

    protected $fillable = [
        'estudante_id',
        'disciplina_id',
        'ano_lectivo_id',
        'nota',
        'status',
    ];

    public function estudante()
    {
        return $this->belongsTo(Estudante::class);
    }

    public function disciplina()
    {
        return $this->belongsTo(Disciplina::class);
    }
      // Relação com a tabela notas_detalhadas
      public function notasDetalhadas()
      {
          return $this->hasMany(NotaDetalhada::class, 'notas_frequencia_id');
      }

    public function inscricaoDisciplinas()
    {
        return $this->hasMany(InscricaoDisciplina::class);
    }
    public function inscricao()
    {
        return $this->belongsTo(Inscricao::class);
    }

    public function anoLectivo()
    {
        return $this->belongsTo(AnoLectivo::class);
    }

}
