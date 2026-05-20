<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{Turma, Classe, AnoLectivo};

class TurmaSeeder extends Seeder
{
    public function run()
    {
        $anoAtivo = AnoLectivo::where('status', 'Ativo')->first();

        $definicoes = [
            '10ª Classe' => [
                ['nome' => '10ª Classe - A', 'ano_serie' => 10, 'descricao' => 'Turma A do 10º Ano'],
                ['nome' => '10ª Classe - B', 'ano_serie' => 10, 'descricao' => 'Turma B do 10º Ano'],
            ],
            '11ª Classe' => [
                ['nome' => '11ª Classe - A', 'ano_serie' => 11, 'descricao' => 'Turma A do 11º Ano'],
                ['nome' => '11ª Classe - B', 'ano_serie' => 11, 'descricao' => 'Turma B do 11º Ano'],
            ],
            '12ª Classe' => [
                ['nome' => '12ª Classe - A', 'ano_serie' => 12, 'descricao' => 'Turma A do 12º Ano'],
                ['nome' => '12ª Classe - B', 'ano_serie' => 12, 'descricao' => 'Turma B do 12º Ano'],
            ],
        ];

        foreach ($definicoes as $classeNome => $turmas) {
            $classe = Classe::where('nome', $classeNome)->first();
            if (!$classe) {
                $nivel = (int)filter_var($classeNome, FILTER_SANITIZE_NUMBER_INT);
                $classe = Classe::firstOrCreate(['nome' => $classeNome], ['nivel' => $nivel]);
            }

            foreach ($turmas as $t) {
                Turma::updateOrCreate(
                    ['nome' => $t['nome'], 'ano_lectivo_id' => $anoAtivo?->id],
                    ['classe_id' => $classe->id, 'ano_serie' => $t['ano_serie'], 'descricao' => $t['descricao']]
                );
            }
        }
    }
}
