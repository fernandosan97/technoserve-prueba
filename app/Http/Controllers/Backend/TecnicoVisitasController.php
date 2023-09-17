<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cita;
use App\Models\User;
use App\Models\Cliente;

class TecnicoVisitasController extends Controller
{
  public function index(Request $request) {
    // Usuario logueado
    $usuarioId = auth()->user()->id;

    // Obtén el filtro de rol del parámetro de la URL (si está presente)
    $filtroEstado = $request->input('estado');

    // Si el filtro de rol está vacío, redirige a la misma función sin filtro
    if (isset($filtroEstado) && empty($filtroEstado)) {
      return redirect()->route('secretaria.visitas');
    }

    // Inicializa la consulta de visitas
    $query = Cita::query();
    $query->where('usuario_id', $usuarioId);

    // Aplica el filtro de rol si está presente
    if (!empty($filtroEstado)) {
        $query->where('estado', $filtroEstado);
    }

    // Ordena las visitas por el campo 'created_at' en orden descendente (más reciente primero)
    $query->orderBy('created_at', 'desc');

    // Realiza la paginación de la consulta
    $visitas = $query->paginate(10); // 10 es el número de visitas por página

    // Agrega los parámetros de filtro a los enlaces de paginación
    $visitas->appends(['estado' => $filtroEstado]);

    return view('tecnico.visitas.index', [
        'visitas' => $visitas,
        'filtroEstado' => $filtroEstado, // Pasa el filtroRol a la vista para mantenerlo seleccionado en el menú desplegable
    ]);
  }

  public function edit(Cita $cita) {
    // Lógica para mostrar el formulario de creación de citas
    // Obtener usuarios de tipo técnico
    $tecnicos = User::where('role', 'tecnico')->get();

    // Obtener todos los clientes
    $clientes = Cliente::all();
    $departamentos = [
      'Atlántida',
      'Choluteca',
      'Colón',
      'Comayagua',
      'Copán',
      'Cortés',
      'El Paraíso',
      'Francisco Morazán',
      'Gracias a Dios',
      'Intibucá',
      'Islas de la Bahía',
      'La Paz',
      'Lempira',
      'Ocotepeque',
      'Olancho',
      'Santa Bárbara',
      'Valle',
      'Yoro',
    ];

    return view('tecnico.visitas.edit', compact('tecnicos', 'clientes', 'departamentos', 'cita'));
  }

  public function update(Request $request, Cita $cita) {
    $request->validate([
      'diagnostico' => 'required_if:estado,ejecutada|string|nullable',
      'practicas_a_desarrollar' => 'required_if:estado,ejecutada|string|nullable',
      'estado' => 'required|string|in:planificado,en proceso,ejecutada',
    ]);

    // Crear y guardar la cita en la base de datos
    $cita->update([
      'diagnostico' => $request->input('diagnostico'),
      'practicas_a_desarrollar' => $request->input('practicas'),
      'estado' => $request->estado,
    ]);

    // Redireccionar a la ruta de visitas con un mensaje de éxito
    return redirect()->route('tecnico.visitas')->with('success', 'La cita se ha editado exitosamente.');
  }
}
