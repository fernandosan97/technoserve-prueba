@extends('secretaria.layouts.master')

@section('content')
<section class="section">
    <div class="section-header">
      <h1>Visitas</h1>
    </div>
    <div class="row">
        <div class="col-md-12">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif
        </div>
    </div>
    <div class="row">
      <div class="col-md-3">
        <form action="" method="GET">
          <div class="form-group">
              <label for="filtroEstado">Filtrar por Estado:</label>
              <select class="form-control" id="filtroEstado" name="estado">
                  <option value="">Todos</option>
                  <option value="planificado" {{ $filtroEstado == 'planificado' ? 'selected' : '' }}>Planificada</option>
                  <option value="en proceso" {{ $filtroEstado == 'en proceso' ? 'selected' : '' }}>En proceso</option>
                  <option value="ejecutada" {{ $filtroEstado == 'ejecutada' ? 'selected' : '' }}>Ejecutada</option>
              </select>
              <br>
              <button type="submit" class="btn btn-primary">Filtrar</button>
              <a href="{{ route('secretaria.visitas') }}" class="btn btn-secondary">Restablecer Filtros</a>
          </div>
        </form>
      </div>
      <div class="col-md-9 text-right">
        <a href="{{ route('secretaria.visitas.create') }}" class="btn btn-primary">Nuevo registro</a>
        <button id="descargarExcel" class="btn btn-success">Descargar Excel</button>
      </div>
      <div class="col-lg-12 col-md-12 col-sm-12 col-12">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Cliente</th>
                    <th>Tecnico</th>
                    <th>Fecha de Inicio</th>
                    <th>Fecha de Fin</th>
                    <th>Ubicación</th>
                    <th>Estado</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
            @if($visitas->isEmpty())
                <tr>
                    <td colspan="7">No hay resultados</td>
                </tr>
            @else
              @foreach ($visitas as $visita)
                  <tr>
                    <td>{{ $visita->cliente->nombre }}</td>
                    <td>{{ $visita->usuario->name }}</td>
                      <td>{{ $visita->fecha_inicio }}</td>
                      <td>{{ $visita->fecha_fin }}</td>
                      <td>{{ $visita->ubicacion }}</td>
                      <td>
                        @switch($visita->estado)
                          @case('planificada')
                              <span class="badge badge-primary">{{ strtoupper($visita->estado) }}</span>
                              @break

                          @case('en proceso')
                              <span class="badge badge-warning">{{ strtoupper($visita->estado) }}</span>
                              @break

                          @case('ejecutada')
                              <span class="badge badge-success">{{ strtoupper($visita->estado) }}</span>
                              @break

                          @default
                              <span class="badge badge-secondary">{{ strtoupper($visita->estado) }}</span>
                        @endswitch
                      </td>
                      <td>
                      <div class="btn-group" role="group" aria-label="Editar o eliminar">
                        <a href="{{ route('secretaria.visitas.tecnico.edit', ['cita' => $visita->id]) }}" class="btn btn-primary btn-group-toggle mr-2">Editar</a>
                        
                        <form method="POST" action="{{ route('secretaria.visitas.tecnico.destroy', ['cita' => $visita->id]) }}" onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta visita?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-group-toggle">Eliminar</button>
                        </form>
                      </div>
                      </td>
                  </tr>
              @endforeach
            @endif
            </tbody>
        </table>
        <div class="row">
          <div class="col-md-12">
            <div class="alert alert-success" role="alert" id="resultadoDescarga" style="display: none;">
                Descarga exitosa
            </div>
          </div>
        </div>
        <nav aria-label="Page navigation">
          <ul class="pagination justify-content-center">
              <li class="page-item {{ $visitas->onFirstPage() ? 'disabled' : '' }}">
                  <a class="page-link" href="{{ $visitas->appends(['tipo' => $filtroEstado])->previousPageUrl() }}" aria-label="Previous">
                      <span aria-hidden="true">&laquo;</span>
                  </a>
              </li>
              @for ($i = 1; $i <= $visitas->lastPage(); $i++)
                  <li class="page-item {{ $i == $visitas->currentPage() ? 'active' : '' }}">
                      <a class="page-link" href="{{ $visitas->appends(['rol' => $filtroEstado])->url($i) }}">{{ $i }}</a>
                  </li>
              @endfor
              <li class="page-item {{ $visitas->currentPage() == $visitas->lastPage() ? 'disabled' : '' }}">
                  <a class="page-link" href="{{ $visitas->appends(['rol' => $filtroEstado])->nextPageUrl() }}" aria-label="Next">
                      <span aria-hidden="true">&raquo;</span>
                  </a>
              </li>
          </ul>
        </nav>
      </div>
    </div>
</section>
@endsection
@section('js')
<script>
document.getElementById('descargarExcel').addEventListener('click', function() {
    // Obtener el valor del filtro de estado
    var filtroEstado = document.getElementById('filtroEstado').value;

    // Realizar una solicitud fetch al controlador
    fetch('/secretaria/visitas/tecnico/download/excel?estado=' + filtroEstado)
        .then(response => {
            if (!response.ok) {
                throw new Error('Error al descargar el archivo');
            }
            return response.blob();
        })
        .then(blob => {
            // Crear un enlace para descargar el archivo
            var url = window.URL.createObjectURL(blob);
            var a = document.createElement('a');
            a.href = url;
            a.download = 'visitas.xlsx';
            a.style.display = 'none';

            // Agregar el enlace al documento y hacer clic en él para iniciar la descarga
            document.body.appendChild(a);
            a.click();

            // Limpiar el enlace y mostrar el mensaje de éxito
            window.URL.revokeObjectURL(url);
            document.body.removeChild(a);
            document.getElementById('resultadoDescarga').style.display = 'block';
        })
        .catch(error => {
            // Mostrar un mensaje de error si la solicitud falla
            document.getElementById('resultadoDescarga').innerHTML = error.message;
            document.getElementById('resultadoDescarga').className = 'alert alert-danger';
            document.getElementById('resultadoDescarga').style.display = 'block';
        });
});
</script>
@endsection
