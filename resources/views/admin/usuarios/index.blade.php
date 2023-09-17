@extends('admin.layouts.master')

@section('content')
<section class="section">
    <div class="section-header">
      <h1>Usuarios</h1>
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
              <label for="filtroRol">Filtrar por Rol:</label>
              <select class="form-control" id="filtroRol" name="rol">
                  <option value="">Todos</option>
                  <option value="admin" {{ $filtroRol == 'admin' ? 'selected' : '' }}>Administrador</option>
                  <option value="tecnico" {{ $filtroRol == 'tecnico' ? 'selected' : '' }}>Técnico</option>
                  <option value="secretaria" {{ $filtroRol == 'secretaria' ? 'selected' : '' }}>Secretaria</option>
                  <!-- Agrega más opciones según tus roles -->
              </select>
              <br>
              <button type="submit" class="btn btn-primary">Filtrar</button>
              <a href="{{ route('admin.usuarios') }}" class="btn btn-secondary">Restablecer Filtros</a>
          </div>
        </form>
      </div>
      <div class="col-md-9 text-right">
        <a href="{{ route('admin.usuarios.create') }}" class="btn btn-success">Nuevo registro</a>
      </div>
      <div class="col-lg-12 col-md-12 col-sm-12 col-12">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Teléfono</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Rol</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
            @if ($usuarios->isEmpty())
                <tr>
                    <td colspan="7">No hay resultados</td>
                </tr>
            @else
              @foreach ($usuarios as $usuario)
                  <tr>
                      <td>{{ $usuario->name }}</td>
                      <td>{{ $usuario->lastname }}</td>
                      <td>{{ $usuario->phone }}</td>
                      <td>{{ $usuario->email }}</td>
                      <td>{{ $usuario->status }}</td>
                      <td>{{ $usuario->role }}</td>
                      <td>
                      <div class="btn-group" role="group" aria-label="Editar o eliminar">
                        <a href="{{ route('admin.usuarios.edit', ['usuario' => $usuario->id]) }}" class="btn btn-primary btn-group-toggle mr-2">Editar</a>
                        
                        <form method="POST" action="{{ route('admin.usuarios.destroy', ['usuario' => $usuario->id]) }}" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este usuario?')">
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
              <li class="page-item {{ $usuarios->onFirstPage() ? 'disabled' : '' }}">
                  <a class="page-link" href="{{ $usuarios->appends(['rol' => $filtroRol])->previousPageUrl() }}" aria-label="Previous">
                      <span aria-hidden="true">&laquo;</span>
                  </a>
              </li>
              @for ($i = 1; $i <= $usuarios->lastPage(); $i++)
                  <li class="page-item {{ $i == $usuarios->currentPage() ? 'active' : '' }}">
                      <a class="page-link" href="{{ $usuarios->appends(['rol' => $filtroRol])->url($i) }}">{{ $i }}</a>
                  </li>
              @endfor
              <li class="page-item {{ $usuarios->currentPage() == $usuarios->lastPage() ? 'disabled' : '' }}">
                  <a class="page-link" href="{{ $usuarios->appends(['rol' => $filtroRol])->nextPageUrl() }}" aria-label="Next">
                      <span aria-hidden="true">&raquo;</span>
                  </a>
              </li>
          </ul>
        </nav>
      </div>
    </div>
</section>
@endsection
