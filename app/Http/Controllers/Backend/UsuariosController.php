<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsuariosController extends Controller
{
    public function index(Request $request) {
      // Obtén el filtro de rol del parámetro de la URL (si está presente)
      $filtroRol = $request->input('rol');

      // Si el filtro de rol está vacío, redirige a la misma función sin filtro
      if (isset($filtroRol) && empty($filtroRol)) {
        return redirect()->route('admin.usuarios');
      }

      // Inicializa la consulta de usuarios
      $query = User::query();

      // Aplica el filtro de rol si está presente
      if (!empty($filtroRol)) {
          $query->where('role', $filtroRol);
      }

      // Realiza la paginación de la consulta
      $usuarios = $query->paginate(1); // 10 es el número de usuarios por página

      // Agrega los parámetros de filtro a los enlaces de paginación
      $usuarios->appends(['rol' => $filtroRol]);

      return view('admin.usuarios.index', [
          'usuarios' => $usuarios,
          'filtroRol' => $filtroRol, // Pasa el filtroRol a la vista para mantenerlo seleccionado en el menú desplegable
      ]);
    }

    public function create() {
      return view('admin.usuarios.create'); // Vista del formulario de registro
    }

    public function edit(User $usuario) {
      return view('admin.usuarios.edit', ['usuario' => $usuario]);
    }

    public function store(Request $request) {
      // Validar los datos del formulario
      $request->validate([
        'name' => 'required|string|max:255',
        'lastname' => 'required|string|max:255',
        'username' => [
            'required',
            'string',
            'max:255',
            'unique:'.User::class, // Aplicar validación unique con la clase User
        ],
        'phone' => 'required|string|max:20',
        'email' => [
            'required',
            'string',
            'email',
            'max:255',
            'unique:'.User::class, // Aplicar validación unique con la clase User
        ],
        'role' => 'required|string|in:admin,tecnico,secretaria',
        'status' => 'required|string|in:active,inactive',
        'password' => ['required', 'string', 'min:8', Rules\Password::defaults()],
      ]);

      // Crear un nuevo usuario con los datos validados
      $user = new User([
        'name' => $request->input('name'),
        'lastname' => $request->input('lastname'),
        'username' => $request->input('username'),
        'phone' => $request->input('phone'),
        'email' => $request->input('email'),
        'role' => $request->input('role'),
        'status' => $request->input('status'),
        'password' => Hash::make($request->password),
      ]);

      // Guardar el usuario en la base de datos si no existe un usuario con el mismo username o email
      if (!User::where('username', $user->username)->exists() && !User::where('email', $user->email)->exists()) {
          $user->save();
          return redirect()->route('admin.usuarios')->with('success', 'Usuario creado exitosamente');
      }

      // Si el usuario ya existe, redirigir de vuelta al formulario de registro con un mensaje de error
      return redirect()->back()->withInput()->withErrors([
          'username' => 'El nombre de usuario ya está registrados.',
          'email' => 'El correo electrónico ya está registrados.',
      ]);
    }

    public function update(Request $request, User $usuario) {
    $request->validate([
        'name' => 'required|string|max:255',
        'lastname' => 'required|string|max:255',
        'username' => 'required|string|max:255',
        'phone' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email,' . $usuario->id,
        'role' => 'required|in:admin,tecnico,secretaria',
        'status' => 'required|in:active,inactive',
    ]);

    // Validar si el nuevo correo electrónico ya existe en otro usuario
    if ($request->email !== $usuario->email) {
      $request->validate([
          'email' => 'unique:users',
      ]);
    }

    $usuario->update([
        'name' => $request->input('name'),
        'lastname' => $request->input('lastname'),
        'username' => $request->input('username'),
        'phone' => $request->input('phone'),
        'email' => $request->input('email'),
        'role' => $request->input('role'),
        'status' => $request->input('status'),
    ]);

    return redirect()->route('admin.usuarios')->with('success', 'Usuario actualizado exitosamente');
  }

  public function destroy(User $usuario) {
    $usuario->delete();

    return redirect()->route('admin.usuarios')->with('success', 'Usuario eliminado exitosamente');
  }
}
