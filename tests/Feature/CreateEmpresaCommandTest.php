<?php

namespace Tests\Feature;

use App\Models\Empresa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateEmpresaCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_a_company_and_admin_user_when_a_contract_reference_is_provided(): void
    {
        $this->artisan('empresa:create', [
            '--nome' => 'Empresa Contratada',
            '--cnpj' => '12345678000199',
            '--contrato' => 'CNT-2026-001',
            '--user-name' => 'Administrador',
            '--email' => 'admin@empresa-contratada.com',
            '--password' => 'senha1234',
        ])
            ->expectsOutputToContain('Empresa criada com sucesso')
            ->assertExitCode(0);

        $empresa = Empresa::where('cnpj', '12345678000199')->first();
        $this->assertNotNull($empresa);

        $user = User::where('email', 'admin@empresa-contratada.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals($empresa->id, $user->empresa_id);
        $this->assertTrue($user->hasRole('admin'));
    }

    public function test_it_uses_a_default_admin_email_based_on_company_name_when_email_is_not_provided(): void
    {
        $this->artisan('empresa:create', [
            '--nome' => 'Brock Solution',
            '--cnpj' => '98765432000188',
            '--contrato' => 'CNT-2026-002',
            '--password' => 'senha1234',
        ])
            ->expectsOutputToContain('Empresa criada com sucesso')
            ->assertExitCode(0);

        $user = User::where('email', 'admin@brocksolution.com')->first();
        $this->assertNotNull($user);
    }
}
