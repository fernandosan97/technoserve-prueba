@extends('tecnico.layouts.master')

@section('content')
<section class="section">
    <div class="section-header">
      <h1>Dashboard</h1>
    </div>
    <div class="row">
      <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
          <div class="card-icon bg-primary">
            <i class="far fa-user"></i>
          </div>
          <div class="card-wrap">
            <div class="card-header">
              <h4>Total Clientes Atendidos</h4>
            </div>
            <div class="card-body">
              {{ $totalClientes }}
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
          <div class="card-icon bg-danger">
            <i class="far fa-newspaper"></i>
          </div>
          <div class="card-wrap">
            <div class="card-header">
              <h4>Total Visitas Planificadas</h4>
            </div>
            <div class="card-body">
              {{ $citasPlanificadas }}
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
          <div class="card-icon bg-success">
            <i class="fas fa-circle"></i>
          </div>
          <div class="card-wrap">
            <div class="card-header">
              <h4>Total Visitas en Proceso</h4>
            </div>
            <div class="card-body">
              {{ $citasEnProceso }}
            </div>
          </div>
        </div>
      </div>   
      <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
          <div class="card-icon bg-info">
            <i class="fas fa-circle"></i>
          </div>
          <div class="card-wrap">
            <div class="card-header">
              <h4>Total Visitas Ejecutadas</h4>
            </div>
            <div class="card-body">
              {{ $citasEjecutadas }}
            </div>
          </div>
        </div>
      </div>                  
    </div>
    <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12 col-12">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Cliente</th>
                    <th>Fecha de Inicio</th>
                    <th>Fecha de Fin</th>
                    <th>Ubicación</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
            @if($citasDelDia->isEmpty())
                <tr>
                    <td colspan="5">No hay resultados</td>
                </tr>
            @else
              @foreach ($citasDelDia as $cita)
                  <tr>
                    <td>{{ $cita->cliente->nombre }}</td>
                    <td>{{ $cita->fecha_inicio }}</td>
                    <td>{{ $cita->fecha_fin }}</td>
                    <td>{{ $cita->ubicacion }}</td>
                    <td>
                      @switch($cita->estado)
                        @case('planificada')
                            <span class="badge badge-primary">{{ strtoupper($cita->estado) }}</span>
                            @break

                        @case('en proceso')
                            <span class="badge badge-warning">{{ strtoupper($cita->estado) }}</span>
                            @break

                        @case('ejecutada')
                            <span class="badge badge-success">{{ strtoupper($cita->estado) }}</span>
                            @break

                        @default
                            <span class="badge badge-secondary">{{ strtoupper($cita->estado) }}</span>
                      @endswitch
                    </td>
                  </tr>
              @endforeach
            @endif
            </tbody>
        </table>
      </div>
    </div>
</section>
@endsection