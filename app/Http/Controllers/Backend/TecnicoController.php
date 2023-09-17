<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cita;
use Carbon\Carbon;

class TecnicoController extends Controller
{
  public function dashboard() {
    $usuarioId = auth()->user()->id;

    $citasPlanificadas = Cita::where('estado', 'planificado')->where('usuario_id', $usuarioId)->count();
    $citasEnProceso = Cita::where('estado', 'en proceso')->where('usuario_id', $usuarioId)->count();
    $citasEjecutadas = Cita::where('estado', 'ejecutada')->where('usuario_id', $usuarioId)->count();
    $totalClientes = Cita::where('usuario_id', $usuarioId)->count();
    
    // Obtener la fecha actual
    $fechaActual = Carbon::now()->toDateString();

    // Consulta para obtener las citas del día actual ordenadas por usuario_id
    $citasDelDia = Cita::whereDate('fecha_inicio', $fechaActual)->where('usuario_id', $usuarioId)->where('estado', 'planificado')
        ->orderBy('fecha_inicio')
        ->get();

    return view('tecnico.dashboard', [
        'citasPlanificadas' => $citasPlanificadas,
        'citasEnProceso' => $citasEnProceso,
        'citasEjecutadas' => $citasEjecutadas,
        'totalClientes' => $totalClientes,
        'citasDelDia' => $citasDelDia,
    ]);
  }
}
