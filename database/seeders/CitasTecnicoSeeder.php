<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Cita;
use App\Models\User;
use App\Models\Cliente;
use Carbon\Carbon;

class CitasTecnicoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run() {
      // Usuarios (Técnicos) disponibles para citas
      $tecnicos = User::whereIn('id', [2, 4])->get();

      // Clientes disponibles para citas
      $clientes = Cliente::whereIn('id', [1, 2])->get();

      // Estados posibles para las citas
      $estados = ['planificado', 'en proceso', 'ejecutada'];

      // Ciudades disponibles
      $departamentosHonduras = [
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

      // Generar 10 citas aleatorias
      for ($i = 0; $i < 10; $i++) {
        $tecnico = $tecnicos->random();
        $cliente = $clientes->random();

        $fechaInicio = Carbon::now()->addDays(rand(1, 30))->addHours(rand(0, 23))->addMinutes(rand(0, 59));
        $fechaFin = Carbon::parse($fechaInicio)->addHours(rand(1, 4));

        // Verificar que no choque con otras citas del mismo técnico
        $citasTecnico = $tecnico->citas()->whereBetween('fecha_inicio', [$fechaInicio, $fechaFin])
            ->orWhereBetween('fecha_fin', [$fechaInicio, $fechaFin])
            ->count();

        // Verificar que no choque con citas del mismo cliente
        $citasCliente = $cliente->citas()->whereBetween('fecha_inicio', [$fechaInicio, $fechaFin])
            ->orWhereBetween('fecha_fin', [$fechaInicio, $fechaFin])
            ->count();

        $estado = $estados[array_rand($estados)];

        $diagnostico = $estado === 'ejecutada' ? 'Empresa de frijol' : null;
        $practicasDesarrollar = $estado === 'ejecutada' ? 'Cultivo y cosecha de frijoles' : null;

        if ($citasTecnico === 0 && $citasCliente === 0) {
          Cita::create([
              'usuario_id' => $tecnico->id,
              'cliente_id' => $cliente->id,
              'fecha_inicio' => $fechaInicio,
              'fecha_fin' => $fechaFin,
              'estado' => $estados[array_rand($estados)],
              'diagnostico' => $diagnostico,
              'practicas_a_desarrollar' => $practicasDesarrollar,
              'ubicacion' => $departamentosHonduras[array_rand($departamentosHonduras)],
          ]);
        }
      }
    }
}
