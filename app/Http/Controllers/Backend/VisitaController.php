<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cita;
use App\Models\User;
use App\Models\Cliente;
use Illuminate\Support\Facades\Validator;
use PDF;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\VisitarExcel;

class VisitaController extends Controller
{
  public function index(Request $request) {
    // Obtén el filtro de rol del parámetro de la URL (si está presente)
    $filtroEstado = $request->input('estado');

    // Si el filtro de rol está vacío, redirige a la misma función sin filtro
    if (isset($filtroEstado) && empty($filtroEstado)) {
      return redirect()->route('secretaria.visitas');
    }

    // Inicializa la consulta de visitas
    $query = Cita::query();

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

    return view('secretaria.visitas.index', [
        'visitas' => $visitas,
        'filtroEstado' => $filtroEstado, // Pasa el filtroRol a la vista para mantenerlo seleccionado en el menú desplegable
    ]);
  }

  public function create() {
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
  

    return view('secretaria.visitas.create', compact('tecnicos', 'clientes', 'departamentos'));
  }

  public function visitasTecnico($tecnico) {
    // Consulta la base de datos para obtener las citas del técnico
    $citas = Cita::where('usuario_id', $tecnico)->with(['cliente', 'usuario'])->get();
    $coloresPorEstado = [
      'ejecutada' => 'badge-success', // Clase de color para "ejecutada"
      'en proceso' => 'badge-warning', // Clase de color para "en proceso"
      'planificada' => 'badge-primary', // Clase de color para "planificada"
    ];

    $citasTransformadas = $citas->map(function ($cita) use ($coloresPorEstado) {
      $estado = $cita->estado;
      $color = $coloresPorEstado[$estado] ?? 'badge-secondary'; // Color predeterminado si no hay coincidencia
  
      return [
          'id' => $cita->id,
          'cliente_nombre' => $cita->cliente->nombre,
          'cliente_apellido' => $cita->cliente->apellido,
          'usuario_nombre' => $cita->usuario->name,
          'usuario_apellido' => $cita->usuario->lastname,
          'fecha_inicio' => $cita->fecha_inicio,
          'fecha_fin' => $cita->fecha_fin,
          'estado' => $cita->estado,
          'ubicacion' => $cita->ubicacion,
          'color' => $color, // Asigna el color basado en el estado
          'diagnostico' => $cita->diagnostico,
          'practicas_a_desarrollar' => $cita->practicas_a_desarrollar,
      ];
    });
    // Devuelve las citas en formato JSON (o en la vista que prefieras)
    return response()->json($citasTransformadas);
  }

  public function store(Request $request) {
    $request->validate([
      'tecnico_id' => 'required|exists:users,id',
      'cliente_id' => 'required|exists:clientes,id',
      'fechaInicio' => 'required|date',
      'fechaFin' => 'required|date|after:fechaInicio',
      'ubicacion' => 'required|string',
    ]);

    // Crear y guardar la cita en la base de datos
    $cita = new Cita();
    $cita->usuario_id = $request->input('tecnico_id');
    $cita->cliente_id = $request->input('cliente_id');
    $cita->fecha_inicio = $request->input('fechaInicio');
    $cita->fecha_fin = $request->input('fechaFin');
    $cita->ubicacion = $request->input('ubicacion');
    $cita->estado = 'planificado';

    // Guardar la cita
    $cita->save();

    // Redireccionar a la ruta de visitas con un mensaje de éxito después de la descarga
    return redirect()->route('secretaria.visitas')->with('success', 'La cita se ha guardado exitosamente. Descargando el PDF...');
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

    return view('secretaria.visitas.edit', compact('tecnicos', 'clientes', 'departamentos', 'cita'));
  }

  public function update(Request $request, Cita $cita) {
    $request->validate([
      'tecnico_id' => 'required|exists:users,id',
      'cliente_id' => 'required|exists:clientes,id',
      'fechaInicio' => 'required|date',
      'fechaFin' => 'required|date|after:fechaInicio',
      'ubicacion' => 'required|string',
      'estado' => 'required|string|in:planificado,en proceso,ejecutada',
    ]);

    // Crear y guardar la cita en la base de datos
    $cita->update([
      'usuario_id' => $request->tecnico_id,
      'cliente_id'=> $request->cliente_id,
      'fecha_inicio' => $request->fechaInicio,
      'fecha_fin' => $request->fechaFin,
      'ubicacion' => $request->ubicacion,
      'diagnostico' => $request->input('diagnostico'),
      'practicas_a_desarrollar' => $request->input('practicas'),
      'estado' => $request->estado,
    ]);

    // Redireccionar a la ruta de visitas con un mensaje de éxito
    return redirect()->route('secretaria.visitas')->with('success', 'La cita se ha editado exitosamente.');
  }

  public function downloadPdf(Cita $cita) {
    // Obtén los datos de la cita que deseas mostrar en el ticket
    $data = [
        'cita' => $cita,
    ];

    // Genera el PDF del ticket a partir de la vista
    $pdf = PDF::loadView('ticket-pdf', $data);

    // Opcionalmente, puedes establecer opciones de configuración del PDF
    $pdf->setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif']);

    // Descarga el PDF o muestra en el navegador
    return $pdf->download('ticket-cita.pdf');
  }

  public function downloadExcel(Request $request) {
      // Aplica el filtro de estado a la consulta si se proporciona
      $filtroEstado = $request->input('estado');
      $query = Cita::query();
      
      if (!empty($filtroEstado)) {
        $query->where('estado', $filtroEstado);
      }
  
      $headings = [
        'ID',
        'Nombre Tecnico',
        'Nombre Cliente',
        'Fecha inicio',
        'Fecha fin',
        'Ubicacion',
        'Diagnostico',
        'Practicas a desarrollar',
        'Estado',
      ];
  
      $data = $query->get(); // Usa la consulta con el filtro aplicado
      return Excel::download(new VisitarExcel($headings, $data), 'visitas.xlsx');
    }

  public function destroy(Cita $cita) {
    $cita->delete();

    return redirect()->route('secretaria.visitas')->with('success', 'Cita eliminado exitosamente');
  }
}
