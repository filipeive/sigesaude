<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

// app/Models/Estudante.php
class Estudante extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'matricula',
        'curso_id',
        'ano_lectivo_id',
        'data_nascimento',
        'genero',
        'ano_ingresso',
        'turno',
        'status',
        'contato_emergencia'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }

    public function anoLectivo()
    {
        return $this->belongsTo(AnoLectivo::class);
    }

    public function matriculas()
    {
        return $this->hasMany(Matricula::class);
    }
    
    public function pagamentos()
    {
        return $this->hasMany(Pagamento::class);
    }
    // app/Models/Estudante.php
    public function nivel()
    {
        return $this->belongsTo(Nivel::class, 'nivel_id');
    }

   // app/Models/Estudante.php
    public function getNivelAttribute()
    {
        // Determinar o nível com base no ano de ingresso e no ano atual
        $anoIngresso = $this->ano_ingresso;
        $anoAtual = date('Y');
        $diferencaAnos = $anoAtual - $anoIngresso;
        
        // Assumindo que o nível 1 corresponde ao primeiro ano, etc.
        $nivelId = min($diferencaAnos + 1, 5); // Limitar ao nível 5
        
        return Nivel::find($nivelId);
    }

}