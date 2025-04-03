<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Docente extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'departamento_id',
        'formacao',
        'anos_experiencia',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function disciplinas()
    {
        return $this->hasMany(Disciplina::class);

    }

    // Relação com o departamento
    public function departamento()
    {
        return $this->belongsTo(Departamento::class, 'departamento_id');
    }
    public function cursos()
    {
        return $this->belongsToMany(Curso::class, 'curso_docente');
    }
      /**
     * Relacionamento com as notas de frequência
     */
    public function notasFrequencia(): HasMany
    {
        return $this->hasMany(NotaFrequencia::class);
    }

    /**
     * Relacionamento com as notas de exame
     */
    public function notasExame(): HasMany
    {
        return $this->hasMany(NotaExame::class);
    }
}