@extends('secretaria.layouts.master')

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
              <h4>Total Clientes</h4>
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
      <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
            <div class="card-icon bg-success">
                <i class="fas fa-circle"></i>
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4>Citas del dia</h4>
                </div>
                <div class="card-body">
                    <a href="{{ route('secretaria.dashboard.citas.del.dia.excel') }}" class="btn btn-success">Descargar Excel</a>
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
                    <h4>Citas ejecutadas del dia</h4>
                </div>
                <div class="card-body">
                    <a href="{{ route('secretaria.dashboard.citas.del.dia.ejecutadas.excel') }}" class="btn btn-info">Descargar Excel</a>
                </div>
            </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
            <div class="card-icon bg-warning">
                <i class="fas fa-circle"></i>
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4>Citas del mes</h4>
                </div>
                <div class="card-body">
                    <a href="{{ route('secretaria.dashboard.citas.del.mes.excel') }}" class="btn btn-warning">Descargar Excel</a>
                </div>
            </div>
        </div>
      </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Gráfico de Citas por Ubicación</div>
                <div class="card-body">
                    <canvas id="citasPorUbicacion"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Gráfico de Citas por Técnico</div>
                <div class="card-body">
                    <canvas id="citasPorTecnico"></canvas>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const data = @json($data);

    const ctx = document.getElementById('citasPorUbicacion').getContext('2d');
    const chartCitas = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: Object.keys(data),
            datasets: [{
                label: 'Citas por Ubicación',
                data: Object.values(data),
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    const tecnicos = @json($citasTecnico);

    const ctxTecnicos = document.getElementById('citasPorTecnico').getContext('2d');
    const chartTecnicos = new Chart(ctxTecnicos, {
        type: 'bar',
        data: {
            labels: Object.keys(tecnicos),
            datasets: [{
                label: 'Citas por Técnico',
                data: Object.values(tecnicos),
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endsection