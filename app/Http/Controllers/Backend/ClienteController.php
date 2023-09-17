<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cliente;

class ClienteController extends Controller
{
  public function index(Request $request) {
    // Obtén el filtro de rol del parámetro de la URL (si está presente)
    $filtroTipo = $request->input('tipo');

    // Si el filtro de rol está vacío, redirige a la misma función sin filtro
    if (isset($filtroTipo) && empty($filtroTipo)) {
      return redirect()->route('admin.clientes');
    }

    // Inicializa la consulta de usuarios
    $query = Cliente::query();

    // Aplica el filtro de rol si está presente
    if (!empty($filtroTipo)) {
        $query->where('tipo_cliente', $filtroTipo);
    }

    // Realiza la paginación de la consulta
    $usuarios = $query->paginate(10); // 10 es el número de usuarios por página

    // Agrega los parámetros de filtro a los enlaces de paginación
    $usuarios->appends(['tipo' => $filtroTipo]);

    return view('admin.clientes.index', [
        'clientes' => $usuarios,
        'filtroTipo' => $filtroTipo, // Pasa el filtroRol a la vista para mantenerlo seleccionado en el menú desplegable
    ]);
  }

  public function create() {
    return view('admin.clientes.create'); // Vista del formulario de registro
  }

  public function edit(Cliente $cliente) {
    return view('admin.clientes.edit', ['cliente' => $cliente]);
  }

  public function store(Request $request) {
    // Validación de campos
    $request->validate([
      'nombre' => 'required|string|max:255',
      'apellido' => 'required|string|max:255',
      'numero_documento' => 'required|string|max:13',
      'tipo_cliente' => 'required|string|in:individual,organizacion',
      'organizacion' => [
          'required_if:tipo_cliente,organizacion',
          'nullable', // Permite que el campo sea nulo
          'string',
          'max:255',
        ],
    ]);

    // Crear y guardar el cliente en la base de datos
    $cliente = new Cliente();
    $cliente->nombre = $request->input('nombre');
    $cliente->apellido = $request->input('apellido');
    $cliente->tipo_cliente = $request->input('tipo_cliente');
    $cliente->organizacion = $request->input('organizacion');
    $cliente->numero_documento = $request->input('numero_documento');
    $cliente->tiene_visitas = $request->input('tiene_visitas');
    $cliente->save();

    // Redirigir con un mensaje de éxito
    return redirect()->route('admin.clientes')->with('success', 'Cliente creado exitosamente');
  }

  public function update(Request $request, Cliente $cliente) {
      $request->validate([
        'nombre' => 'required|string|max:255',
        'apellido' => 'required|string|max:255',
        'numero_documento' => 'required|string|max:13',
        'tipo_cliente' => 'required|string|in:individual,organizacion',
        'organizacion' => [
            'required_if:tipo_cliente,organizacion',
            'nullable', // Permite que el campo sea nulo
            'string',
            'max:255',
          ],
      ]);

      // Actualizar el cliente con los datos del formulario
      $cliente->update([
          'nombre' => $request->nombre,
          'apellido' => $request->apellido,
          'tipo_cliente' => $request->tipo_cliente,
          'organizacion' => $request->tipo_cliente === 'organizacion' ? $request->organizacion : null,
          'numero_documento' => $request->numero_documento,
          'tiene_visitas' => $request->tiene_visitas,
      ]);

      return redirect()->route('admin.clientes')->with('success', 'Cliente actualizado exitosamente');
    }

    public function destroy(Cliente $cliente) {
      $cliente->delete();
  
      return redirect()->route('admin.clientes')->with('success', 'Cliente eliminado exitosamente');
    }
}
