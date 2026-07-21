<?php

namespace App\Observers;

use App\Models\Empresa;
use App\Support\RolePermissionDefinitions;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class EmpresaObserver
{
    public function created(Empresa $empresa): void
    {
        app(PermissionRegistrar::class)->setPermissionsTeamId($empresa->id);

        foreach (RolePermissionDefinitions::allPermissionNames() as $permissionName) {
            Permission::firstOrCreate([
                'name' => $permissionName,
                'guard_name' => 'sanctum',
            ]);
        }

        foreach (RolePermissionDefinitions::all() as $roleName => $permissoes) {
            $role = Role::create([
                'name' => $roleName,
                'guard_name' => 'sanctum',
                'empresa_id' => $empresa->id,
            ]);

            $role->givePermissionTo(
                RolePermissionDefinitions::resolvePermissions($permissoes)
            );
        }
    }
}