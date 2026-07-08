<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\PermissionRegistrar;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
    public function index(Request $request)
    {
        $empresaId = $request->user()->empresa_id;
        $users = User::where('empresa_id', $empresaId)->with('roles')->get();
        return response()->json($users);
    }

    // Cadastro de uma nova empresa + primeiro usuário (admin)
    public function registrarEmpresa(Request $request)
    {
        if(!$request->user()->hasRole('admin')) {
            return response()->json(['message' => 'Somente uma conta administradora pode registrar uma nova empresa.'], 403);
        }

        $validado = $request->validate([
            'empresa_nome' => 'required|string|max:255',
            'cnpj' => 'required|string|unique:empresas,cnpj',
            'user_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $empresa = Empresa::create([
            'nome' => $validado['empresa_nome'],
            'cnpj' => $validado['cnpj'],
        ]);

        $user = User::create([
            'empresa_id' => $empresa->id,
            'name' => $validado['user_name'],
            'email' => $validado['email'],
            'password' => Hash::make($validado['password']),
        ]);

        app(PermissionRegistrar::class)->setPermissionsTeamId($empresa->id);

        $role = Role::where('name', 'admin')
            ->where('guard_name', 'sanctum')
            ->where('empresa_id', $empresa->id) // ou team_id, conforme seu config
            ->first();

        $user->assignRole($role);

        return response()->json([
            'message' => 'Empresa e usuário criados com sucesso.',
        ], 201);
    }

    // Login
    public function login(Request $request)
    {
        $validado = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $validado['email'])->first();

        if (! $user || ! Hash::check($validado['password'], $user->password)) {
            return response()->json(['message' => 'Credenciais inválidas.'], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        app(PermissionRegistrar::class)->setPermissionsTeamId($user->empresa_id);

        return response()->json([
            'token' => $token,
            'user' => $user,
            'roles' => $user->getRoleNames(),
            'permissions' => $user->getAllPermissions()->pluck('name'),
        ]);
    }

    // Dados do usuário logado (usado ao recarregar a SPA)
    public function me(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'user' => $user,
            'roles' => $user->getRoleNames(),
            'permissions' => $user->getAllPermissions()->pluck('name'),
        ]);
    }

    // Cadastro de novo usuário dentro de uma empresa já existente (feito por um admin)
    public function criarUsuario(Request $request)
    {
        $validado = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|string|exists:roles,name',
        ]);

        $empresaId = $request->user()->empresa_id;

        $user = User::create([
            'empresa_id' => $empresaId,
            'name' => $validado['name'],
            'email' => $validado['email'],
            'password' => Hash::make($validado['password']),
        ]);

        app(PermissionRegistrar::class)->setPermissionsTeamId($empresaId);

        if($validado['role'] == 'admin' && !$request->user()->hasRole('admin')) {
            return response()->json(['message' => 'Apenas administradores podem criar novos usuários admins.'], 403);
        }
        $user->assignRole($validado['role']);
        return response()->json(['message' => 'Usuário criado com sucesso.'], 201);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logout realizado.']);
    }

    public function atualizarUsuario(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $editandoSiMesmo = $user->id === $request->user()->id;

        if (!$editandoSiMesmo) {
            if ($user->empresa_id !== $request->user()->empresa_id || !$request->user()->can('cadastros.usuarios.edit')) {
                return response()->json(['message' => 'Você não tem permissão para atualizar este usuário.'], 403);
            }
        }

        $validado = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:users,email,' . $user->id,
            'password' => 'sometimes|required|string|min:8',
            'role' => 'sometimes|required|string|exists:roles,name',
        ]);

        if (isset($validado['name'])) {
            $user->name = $validado['name'];
        }
        if (isset($validado['email'])) {
            $user->email = $validado['email'];
        }
        if (isset($validado['password'])) {
            $user->password = Hash::make($validado['password']);
        }

        if (isset($validado['role']) && $request->user()->can('cadastros.usuarios.edit')) {
            $user->syncRoles([$validado['role']]);
        }

        $user->save();

        return response()->json(['message' => 'Usuário atualizado com sucesso.'],204);
    }

    public function editarUsuario(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if ($user->empresa_id !== $request->user()->empresa_id) {
            return response()->json(['message' => 'Você não tem permissão para visualizar este usuário.'], 403);
        }
        $user->load('roles');
        return response()->json($user);
    }
}