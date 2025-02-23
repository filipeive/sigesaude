<?php
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Verificar e criar roles se não existirem
        $this->createRoleIfNotExists('admin');
        $this->createRoleIfNotExists('docente');
        $this->createRoleIfNotExists('estudante');
        $this->createRoleIfNotExists('secretaria');
        $this->createRoleIfNotExists('financeiro');

        // Criar usuário admin
        $this->createUserIfNotExists(
            'Administrador',
            'admin@example.com',
            'password',
            '999999999',
            'Masculino',
            'admin',
            'admin'
        );

        // Criar usuário secretaria
        $this->createUserIfNotExists(
            'Secretaria',
            'secretaria@example.com',
            'password',
            '999999998',
            'Feminino',
            'secretaria',
            'secretaria'
        );

        // Criar usuário docente
        $this->createUserIfNotExists(
            'Docente',
            'docente@example.com',
            'password',
            '999999997',
            'Masculino',
            'docente',
            'docente'
        );

        // Criar usuário financeiro
        $this->createUserIfNotExists(
            'Financeiro',
            'financeiro@example.com',
            'password',
            '999999996',
            'Feminino',
            'financeiro',
            'financeiro'
        );

        // Criar usuário estudante
        $this->createUserIfNotExists(
            'Estudante',
            'estudante@example.com',
            'password',
            '999999995',
            'Masculino',
            'estudante',
            'estudante'
        );
    }

    /**
     * Cria uma role se ela não existir.
     */
    protected function createRoleIfNotExists(string $roleName): void
    {
        if (!Role::where('name', $roleName)->exists()) {
            Role::create(['name' => $roleName]);
        }
    }

    /**
     * Cria um usuário se ele não existir.
     */
    protected function createUserIfNotExists(
        string $name,
        string $email,
        string $password,
        string $telefone,
        string $genero,
        string $tipo,
        string $role
    ): void {
        if (!User::where('email', $email)->exists()) {
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
                'telefone' => $telefone,
                'genero' => $genero,
                'tipo' => $tipo,
            ]);
            $user->assignRole($role);
        }
    }
}