<?php

namespace App\Console\Commands;

use App\Models\Empresa;
use App\Support\RolePermissionDefinitions;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class UpdateEmpresaRoles extends Command
{
    /**
     * php artisan empresa:update-roles 5
     *   → Atualiza (cria as que faltam + sincroniza permissões) todas as roles padrão da empresa 5
     *
     * php artisan empresa:update-roles 5 --role=vendedor
     *   → Atualiza só a role 'vendedor' da empresa 5
     */
    protected $signature = 'empresa:update-roles 
                            {empresa_id : ID da empresa a ser atualizada}
                            {--role= : Atualizar apenas uma role específica (padrão: todas)}';

    protected $description = 'Atualiza (cria/sincroniza) as roles e permissões padrão de uma empresa já existente';

    public function handle(): int
    {
        $empresa = Empresa::find($this->argument('empresa_id'));

        if (!$empresa) {
            $this->error("Empresa #{$this->argument('empresa_id')} não encontrada.");
            return self::FAILURE;
        }

        $roleFiltro = $this->option('role');
        $definicoes = RolePermissionDefinitions::all();

        if ($roleFiltro) {
            if (!isset($definicoes[$roleFiltro])) {
                $this->error("Role '{$roleFiltro}' não existe nas definições padrão.");
                return self::FAILURE;
            }
            $definicoes = [$roleFiltro => $definicoes[$roleFiltro]];
        }

        $this->info("Atualizando empresa #{$empresa->id} ({$empresa->nome})...");

        app(PermissionRegistrar::class)->setPermissionsTeamId($empresa->id);

        foreach (RolePermissionDefinitions::allPermissionNames() as $permissionName) {
            Permission::firstOrCreate([
                'name' => $permissionName,
                'guard_name' => 'sanctum',
            ]);
        }

        foreach ($definicoes as $roleName => $permissoes) {
            $role = Role::firstOrCreate(
                ['name' => $roleName, 'guard_name' => 'sanctum', 'empresa_id' => $empresa->id]
            );

            $foiCriada = $role->wasRecentlyCreated;

            $permissoesResolvidas = RolePermissionDefinitions::resolvePermissions($permissoes);

            $role->syncPermissions($permissoesResolvidas);

            $status = $foiCriada ? 'criada' : 'atualizada';
            $this->line("  ✓ Role '{$roleName}' {$status} -> " . count($permissoesResolvidas) . ' permissões');
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $this->info('Empresa atualizada com sucesso.');
        return self::SUCCESS;
    }
}