<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Estudante;
use App\Models\Turma;
use App\Models\AnoLectivo;
use App\Models\Matricula;
use App\Models\Pagamento;
use App\Models\Inscricao;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class EstudanteTesteSeeder extends Seeder
{
    /**
     * Cria um estudante de teste com:
     *  - User autenticável (email + senha)
     *  - Perfil Estudante ligado a uma Turma existente
     *  - Matrícula anual com referência ATM
     *  - Propinas mensais para o ano lectivo
     *  - Inscrição semestral confirmada
     *
     * Uso: php artisan db:seed --class=EstudanteTesteSeeder
     *
     * Login: estudante.teste@sigesaude.mz  /  password123
     */
    public function run(): void
    {
        // ── 1. Garantir ano lectivo activo ──────────────────────────────────
        $anoLectivo = AnoLectivo::where('status', 'Ativo')->first();
        if (!$anoLectivo) {
            $anoLectivo = AnoLectivo::create([
                'ano'   => date('Y') . '/' . (date('Y') + 1),
                'status' => 'Ativo',
            ]);
        }

        // ── 2. Garantir turma ───────────────────────────────────────────────
        $turma = Turma::first();
        if (!$turma) {
            $this->call(TurmaSeeder::class);
            $turma = Turma::first();
        }

        // ── 3. Criar / buscar user ──────────────────────────────────────────
        $user = User::firstOrCreate(
            ['email' => 'estudante.teste@sigesaude.mz'],
            [
                'name'     => 'João Alberto Massingue',
                'password' => Hash::make('password123'),
                'telefone' => '84 123 4567',
                'genero'   => 'Masculino',
                'tipo'     => 'estudante',
            ]
        );
        $user->assignRole('estudante');

        // ── 4. Criar perfil Estudante ───────────────────────────────────────
        $estudante = Estudante::firstOrCreate(
            ['user_id' => $user->id],
            [
                'matricula'       => 'EST-' . date('Y') . '-' . str_pad($user->id, 4, '0', STR_PAD_LEFT),
                'turma_id'        => $turma->id,
                'ano_lectivo_id'  => $anoLectivo->id,
                'data_nascimento' => '2008-03-15',
                'genero'          => 'Masculino',
                'ano_ingresso'    => (int) date('Y') - 3,
                'turno'           => 'Diurno',
                'status'          => 'Ativo',
                'contato_emergencia' => '87 987 6543 — Mãe: Ana Massingue',
            ]
        );

        // ── 5. Matrícula anual ──────────────────────────────────────────────
        $referenciaMatricula = '922' . str_pad(random_int(0, 999999999), 9, '0', STR_PAD_LEFT);

        $matricula = Matricula::firstOrCreate(
            ['estudante_id' => $estudante->id, 'ano_lectivo_id' => $anoLectivo->id],
            [
                'turma_id'         => $turma->id,
                'referencia'       => $referenciaMatricula,
                'valor'            => 1500.00,
                'tipo_matricula'   => 'normal',
                'data_matricula'   => Carbon::now(),
                'status'           => 'confirmada',
                'observacoes'      => 'Matrícula anualConfirmada após pagamento da taxa de inscrição.',
            ]
        );

        // ── 6. Pagamentos: matrícula + 6 propinas ───────────────────────────
        $pagamentos = [];

        // Pagamento da matrícula (pago)
        $pagamentos[] = Pagamento::firstOrCreate(
            ['referencia' => $referenciaMatricula, 'estudante_id' => $estudante->id],
            [
                'descricao'       => 'Taxa de Matrícula Anual',
                'valor'           => 1500.00,
                'status'          => 'pago',
                'data_vencimento' => Carbon::now()->addDays(30),
                'data_pagamento'  => Carbon::now()->subDays(20),
                'comprovante'     => null,
                'ano_lectivo_id'  => $anoLectivo->id,
            ]
        );

        // 6 propinas mensais (1 já paga, 5 pendentes)
        for ($mes = 1; $mes <= 6; $mes++) {
            $ref = '831';
            $ref .= str_pad($estudante->id, 3, '0', STR_PAD_LEFT);
            $ref .= str_pad($mes, 2, '0', STR_PAD_LEFT);

            $vencimento = Carbon::create((int) substr($anoLectivo->ano, 0, 4), $mes, 10);

            $pagamentos[] = Pagamento::firstOrCreate(
                ['referencia' => $ref, 'estudante_id' => $estudante->id],
                [
                    'descricao'       => "Propina Mensal — Mês {$mes}",
                    'valor'           => 850.00,
                    'status'          => $mes === 1 ? 'pago' : 'pendente',
                    'data_vencimento' => $vencimento,
                    'data_pagamento'  => $mes === 1 ? $vencimento->copy()->subDay() : null,
                    'comprovante'     => null,
                    'ano_lectivo_id'  => $anoLectivo->id,
                ]
            );
        }

        // ── 7. Inscrição semestral ──────────────────────────────────────────
        $inscricao = Inscricao::firstOrCreate(
            ['estudante_id' => $estudante->id, 'ano_lectivo_id' => $anoLectivo->id, 'semestre' => 1],
            [
                'status'         => 'Confirmada',
                'valor'          => 3750.00,
                'referencia'     => str_pad($estudante->id, 4, '0', STR_PAD_LEFT) . date('md') . str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT),
                'data_inscricao'=> Carbon::now()->subDays(60),
            ]
        );

        $this->command->info('');
        $this->command->info('═════════════════════════════════════════════════════');
        $this->command->info('  ✅ Estudante de teste criado com sucesso!');
        $this->command->info('═════════════════════════════════════════════════════');
        $this->command->info('   Email....: estudante.teste@sigesaude.mz');
        $this->command->info('   Senha....: password123');
        $this->command->info('   Nome.....: ' . $user->name);
        $this->command->info('   Matrícula: ' . $estudante->matricula);
        $this->command->info('   Turma....: ' . $turma->nome);
        $this->command->info('   Ref. ATM.: ' . $referenciaMatricula . ' (matrícula)');
        $this->command->info('   Propinas : 831[EST][01..06] (1 paga + 5 pendentes)');
        $this->command->info('═════════════════════════════════════════════════════');
    }
}
