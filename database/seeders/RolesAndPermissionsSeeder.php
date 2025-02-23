<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder; // Adicione esta linha
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        Permission::create(['name' => 'view estudantes']);
        Permission::create(['name' => 'create estudantes']);
        Permission::create(['name' => 'edit estudantes']);
        Permission::create(['name' => 'delete estudantes']);
        
        Permission::create(['name' => 'view docentes']);
        Permission::create(['name' => 'create docentes']);
        Permission::create(['name' => 'edit docentes']);
        Permission::create(['name' => 'delete docentes']);
        
        Permission::create(['name' => 'view disciplinas']);
        Permission::create(['name' => 'create disciplinas']);
        Permission::create(['name' => 'edit disciplinas']);
        Permission::create(['name' => 'delete disciplinas']);
        
        Permission::create(['name' => 'view pagamentos']);
        Permission::create(['name' => 'create pagamentos']);
        Permission::create(['name' => 'edit pagamentos']);
        Permission::create(['name' => 'delete pagamentos']);

        // Create roles and assign permissions
        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo(Permission::all());

        $secretaria = Role::create(['name' => 'secretaria']);
        $secretaria->givePermissionTo([
            'view estudantes',
            'create estudantes',
            'edit estudantes',
            'view docentes',
            'view disciplinas'
        ]);

        $docente = Role::create(['name' => 'docente']);
        $docente->givePermissionTo([
            'view disciplinas',
            'edit disciplinas',
            'view estudantes'
        ]);

        $financeiro = Role::create(['name' => 'financeiro']);
        $financeiro->givePermissionTo([
            'view pagamentos',
            'create pagamentos',
            'edit pagamentos',
            'view estudantes'
        ]);

        $estudante = Role::create(['name' => 'estudante']);
        $estudante->givePermissionTo([
            'view disciplinas'
        ]);
    }
}