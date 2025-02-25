<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotaFrequencia extends Model
{
    use HasFactory;

    protected $table = 'notas_frequencia';

    protected $fillable = ['estudante_id', 'disciplina_id', 'nota_frequencia'];

    public function estudante()
    {
        return $this->belongsTo(Estudante::class);
    }

    public function disciplina()
    {
        return $this->belongsTo(Disciplina::class);
    }
}
