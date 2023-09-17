@extends('secretaria.layouts.master')

@section('content')
<section class="section">
    <div class="section-header">
      <h1>Clientes</h1>
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
              <label for="filtroTipo">Filtrar por Tipo de Cliente:</label>
              <select class="form-control" id="filtroTipo" name="tipo">
                  <option value="">Todos</option>
                  <option value="individual" {{ $filtroTipo == 'individual' ? 'selected' : '' }}>Individual</option>
                  <option value="organizacion" {{ $filtroTipo == 'organizacion' ? 'selected' : '' }}>Organización</option>
                  <!-- Agrega más opciones según tus roles -->
              </select>
              <br>
              <button type="submit" class="btn btn-primary">Filtrar</button>
              <a href="{{ route('secretaria.clientes') }}" class="btn btn-secondary">Restablecer Filtros</a>
          </div>
        </form>
      </div>
      <div class="col-md-9 text-right">
        <a href="{{ route('secretaria.clientes.create') }}" class="btn btn-success">Nuevo registro</a>
      </div>
      <div class="col-lg-12 col-md-12 col-sm-12 col-12">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Tipo de Cliente</th>
                    <th>Organización</th>
                    <th>Numero de Documento</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
            @if ($clientes->isEmpty())
                <tr>
                    <td colspan="6">No hay resultados</td>
                </tr>
            @else
              @foreach ($clientes as $cliente)
                  <tr>
                      <td>{{ $cliente->nombre }}</td>
                      <td>{{ $cliente->apellido }}</td>
                      <td>{{ $cliente->tipo_cliente }}</td>
                      <td>{{ $cliente->organizacion ? $cliente->organizacion : 'Ninguna' }}</td>
                      <td>{{ $cliente->numero_documento }}</td>
                      <td>
                      <div class="btn-group" role="group" aria-label="Editar o eliminar">
                        <a href="{{ route('secretaria.clientes.edit', ['cliente' => $cliente->id]) }}" class="btn btn-primary btn-group-toggle mr-2">Editar</a>
                        
                        <form method="POST" action="{{ route('secretaria.clientes.destroy', ['cliente' => $cliente->id]) }}" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este cliente?')">
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
        <nav aria-label="Page navigation">
          <ul class="pagination justify-content-center">
              <li class="page-item {{ $clientes->onFirstPage() ? 'disabled' : '' }}">
                  <a class="page-link" href="{{ $clientes->appends(['tipo' => $filtroTipo])->previousPageUrl() }}" aria-label="Previous">
                      <span aria-hidden="true">&laquo;</span>
                  </a>
              </li>
              @for ($i = 1; $i <= $clientes->lastPage(); $i++)
                  <li class="page-item {{ $i == $clientes->currentPage() ? 'active' : '' }}">
                      <a class="page-link" href="{{ $clientes->appends(['rol' => $filtroTipo])->url($i) }}">{{ $i }}</a>
                  </li>
              @endfor
              <li class="page-item {{ $clientes->currentPage() == $clientes->lastPage() ? 'disabled' : '' }}">
                  <a class="page-link" href="{{ $clientes->appends(['rol' => $filtroTipo])->nextPageUrl() }}" aria-label="Next">
                      <span aria-hidden="true">&raquo;</span>
                  </a>
              </li>
          </ul>
        </nav>
      </div>
    </div>
</section>
@endsection
