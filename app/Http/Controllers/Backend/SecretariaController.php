<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cita;
use App\Models\Cliente;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\VisitarExcel;
use Carbon\Carbon;
use DB;

class SecretariaController extends Controller
{
  public function dashboard() {
    $citasPlanificadas = Cita::where('estado', 'planificado')->count();
    $citasEnProceso = Cita::where('estado', 'en proceso')->count();
    $citasEjecutadas = Cita::where('estado', 'ejecutada')->count();
    $totalClientes = Cliente::count();

    $data = Cita::groupBy('ubicacion')
    ->selectRaw('ubicacion, count(*) as count')
    ->pluck('count', 'ubicacion');

    $citasTecnico = Cita::join('users', 'citas.usuario_id', '=', 'users.id')
        ->select('users.name as tecnico', DB::raw('count(*) as count'))
        ->groupBy('users.name')
        ->pluck('count', 'tecnico');

    return view('secretaria.dashboard', [
        'citasPlanificadas' => $citasPlanificadas,
        'citasEnProceso' => $citasEnProceso,
        'citasEjecutadas' => $citasEjecutadas,
        'totalClientes' => $totalClientes,
        'data' => $data,
        'citasTecnico' => $citasTecnico,
    ]);
  }

  public function citasDelDiaExcel(Request $request) {
    // Obtener la fecha actual
    $fechaActual = Carbon::now()->toDateString();

    // Consulta para obtener las citas del día actual ordenadas por usuario_id
    $data = Cita::whereDate('fecha_inicio', $fechaActual)
        ->orderBy('usuario_id')
        ->get();

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

    // Exportar los datos de las citas del día actual a un archivo Excel y descargarlo
    return Excel::download(new VisitarExcel($headings, $data),'citas_del_dia.xlsx');
  }

  public function citasDelDiaEjecutadasExcel(Request $request) {
    // Obtén la fecha actual
    $fechaActual = Carbon::now()->toDateString();

    // Realiza la consulta para obtener las citas del día ejecutadas y ordénalas por usuario_id
    $data = Cita::whereDate('fecha_inicio', $fechaActual)
        ->where('estado', 'ejecutada')
        ->orderBy('usuario_id')
        ->get();

    // Define los encabezados para el archivo Excel
    $headings = [
        'ID',
        'Nombre Tecnico',
        'Nombre Cliente',
        'Fecha inicio',
        'Fecha fin',
        'Ubicación',
        'Diagnóstico',
        'Prácticas a desarrollar',
        'Estado',
    ];

    // Descarga el archivo Excel utilizando la clase de exportación correspondiente
    return Excel::download(new VisitarExcel($headings, $data), 'citas_ejecutadas_del_dia.xlsx');
  }

  public function citasDelMesExcel(Request $request) {
    // Obtén el primer y último día del mes actual
    $primerDiaDelMes = Carbon::now()->startOfMonth();
    $ultimoDiaDelMes = Carbon::now()->endOfMonth();

    // Realiza la consulta para obtener todas las citas del mes actual
    $data = Cita::whereBetween('fecha_inicio', [$primerDiaDelMes, $ultimoDiaDelMes])
        ->orderBy('usuario_id')
        ->orderBy('estado')
        ->get();

    // Define los encabezados para el archivo Excel
    $headings = [
        'ID',
        'Nombre Tecnico',
        'Nombre Cliente',
        'Fecha inicio',
        'Fecha fin',
        'Ubicación',
        'Diagnóstico',
        'Prácticas a desarrollar',
        'Estado',
    ];

    // Descarga el archivo Excel utilizando la clase de exportación correspondiente
    return Excel::download(new VisitarExcel($headings, $data), 'citas_del_mes.xlsx');
  }
}
