@extends('tecnico.layouts.master')

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.css" rel='stylesheet' />
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.js"></script>
@endsection
@section('content')
<section class="section">
    <div class="section-header">
        <h1>Editar Cita</h1>
    </div>
    <div class="row">
        <div class="col-lg-12">
          <form id="editarCitaForm" action="{{ route('tecnico.visitas.tecnico.update', ['cita' => $cita->id]) }}" method="POST">
            @csrf
            @method('patch')
            <div class="row">
                <!-- Dropdown para seleccionar Técnicos -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="tecnico">Seleccionar Técnico</label>
                        <input type="text" class="form-control" id="tecnico_id" name="tecnico_id" value="{{ $cita->usuario->name }} {{ $cita->usuario->lastname }}" disabled>
                    </div>
                </div>

                <!-- Dropdown para seleccionar Clientes -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="cliente">Seleccionar Cliente</label>
                        <input type="text" class="form-control" id="cliente_id" name="cliente_id" value="{{ $cita->cliente->nombre }} {{ $cita->cliente->apellido }}" disabled>
                    </div>
                </div>

                <!-- Dropdown para seleccionar Ubicación -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="ubicacion">Ubicación</label>
                        <input type="text" class="form-control" id="ubicacion" name="ubicacion" value="{{ $cita->ubicacion }}" disabled>
                    </div>
                </div>
            </div>

            <!-- Fecha de Inicio, Fecha de Fin -->
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="fechaInicio">Fecha de Inicio</label>
                        <input type="datetime-local" name="fechaInicio" id="fechaInicio" class="form-control" value="{{ $cita->fecha_inicio }}" disabled>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="fechaFin">Fecha de Fin</label>
                        <input type="datetime-local" name="fechaFin" id="fechaFin" class="form-control" value="{{ $cita->fecha_fin }}" disabled>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="estado">Estado</label>
                        <select name="estado" id="estado" class="form-control">
                          <option value="planificado" {{ $cita->estado === 'planificado' ? 'selected' : '' }}>Planificado</option>
                          <option value="en proceso" {{ $cita->estado === 'en proceso' ? 'selected' : '' }}>En proceso</option>
                          <option value="ejecutada" {{ $cita->estado === 'ejecutada' ? 'selected' : '' }}>Ejecutada</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="diagnostico">Diagnostico</label>
                        <textarea name="diagnostico" id="diagnostico" class="form-control">{{ $cita->diagnostico }}</textarea>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="practicas">Practicas a desarrollar</label>
                        <textarea name="practicas" id="practicas" class="form-control">{{ $cita->practicas_a_desarrollar }}</textarea>
                    </div>
                </div>
            </div>
            <!-- Aquí agrega el código para mostrar los errores de validación -->
            @if ($errors->any())
              <div class="row">
                <div class="col-lg-12">
                  <div class="alert alert-danger">
                      <ul>
                          @foreach ($errors->all() as $error)
                              <li>{{ $error }}</li>
                          @endforeach
                      </ul>
                  </div>
                </div>
              </div>
            @endif

            <!-- Botón para guardar la cita -->
            <div class="row">
                <div class="col-md-12 text-center">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                    <a href="{{ route('tecnico.visitas') }}" class="btn btn-secondary">Cancelar</a>
                </div>
            </div>
          </form>
        </div>
    </div>
</div>
</section>
@endsection()
