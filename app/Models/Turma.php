<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Turma = Secção dentro de uma Classe num dado Ano Lectivo
 * Ex: "10ª A" no ano 2026
 *
 * @property int $id
 * @property string $nome
 * @property string|null $descricao
 * @property int $ano_serie
 * @property int|null $classe_id
 * @property int|null $ano_lectivo_id
 */
class Turma extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'descricao',
        'ano_serie',
        'classe_id',
        'ano_lectivo_id'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($turma) {
            if (empty($turma->descricao) && $turma->classe) {
                $turma->descricao = "Turma {$turma->nome} da {$turma->classe->nome}";
            }
        });
    }

    /**
     * A classe (nível) a que esta turma pertence
     */
    public function classe()
    {
        return $this->belongsTo(Classe::class);
    }

    /**
     * Ano lectivo desta turma
     */
    public function anoLectivo()
    {
        return $this->belongsTo(AnoLectivo::class);
    }

    /**
     * Estudantes alocados nesta turma
     */
    public function estudantes()
    {
        return $this->hasMany(Estudante::class);
    }

    /**
     * Matrículas nesta turma
     */
    public function matriculas()
    {
        return $this->hasMany(Matricula::class);
    }

    public function getTotalEstudantesAttribute()
    {
        return $this->estudantes()->count();
    }

    /**
     * Nome completo da turma (ex: "10ª A - 2026")
     */
    public function getNomeCompletoAttribute()
    {
        $classe = $this->classe ? $this->classe->nome : '';
        $ano = $this->anoLectivo ? $this->anoLectivo->ano : '';
        return "{$classe} {$this->nome}" . ($ano ? " - {$ano}" : '');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('nome', 'LIKE', "%{$search}%")
                     ->orWhere('descricao', 'LIKE', "%{$search}%");
    }

    public function scopeAnoSerie($query, $ano)
    {
        return $query->where('ano_serie', $ano);
    }
}