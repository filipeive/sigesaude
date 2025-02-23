<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class AnoLectivo extends Model
{
    protected $table = 'anos_lectivos';
    protected $fillable = ['ano', 'status'];
    public function estudantes(){
        return $this->hasMany(Estudante::class);
    }
    public function matriculas(){
        return $this->hasMany(Matricula::class);
    }
    public function pagamentos(){
        return $this->hasMany(Pagamento::class);
    }

}
