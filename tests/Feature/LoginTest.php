<?php

namespace Tests\Feature;

use App\Models\Empresa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    private function criarUsuario(): User
    {
        $empresa = Empresa::create([
            'nome' => 'Teste',
            'cnpj' => '00000000000',
        ]);

        return User::factory()->create([
            'empresa_id' => $empresa->id,
            'email' => 'admin@brocksolution.com',
            'password' => bcrypt('12345678'),
        ]);
    }

    public function test_usuario_nao_consegue_fazer_login_com_a_base_zerada(){
        $response = $this->postJson('/api/login',[
            'email' => 'admin@brocksolution.com',
            'password' => '12345678'
        ]);
         $response->assertStatus(401);
    }

    public function test_usuario_nao_consegue_fazer_login_sem_informar_email(){

        $this->criarUsuario();
        $response = $this->postJson('/api/login',[
            'email' => '',
            'password' => '12345678'
        ]);
        $response->assertStatus(422)->assertJsonValidationErrors(['email']);
    }

    public function test_usuario_consegue_fazer_login_com_credenciais_validas(){
        $this->criarUsuario();

        $response = $this->postJson('/api/login',[
            'email' => 'admin@brocksolution.com',
            'password' => '12345678'
        ]);
        
        $response->assertStatus(200);

        $response->assertJsonStructure([
            'token',
            'user'
        ]);
    }

}