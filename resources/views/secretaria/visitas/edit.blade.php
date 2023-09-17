@extends('secretaria.layouts.master')

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
          <form id="editarCitaForm" action="{{ route('secretaria.visitas.tecnico.update', ['cita' => $cita->id]) }}" method="POST">
            @csrf
            @method('patch')
            <div class="row">
                <!-- Dropdown para seleccionar Técnicos -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="tecnico">Seleccionar Técnico</label>
                        <select name="tecnico_id" id="tecnico" class="form-control">
                            @foreach($tecnicos as $tecnico)
                                <option value="{{ $tecnico->id }}" {{  $cita->usuario_id === $tecnico->id ? 'selected' : '' }}>{{ $tecnico->name }} {{ $tecnico->lastname }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Dropdown para seleccionar Clientes -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="cliente">Seleccionar Cliente</label>
                        <select name="cliente_id" id="cliente" class="form-control">
                            @foreach($clientes as $cliente)
                                <option value="{{ $cliente->id }}" {{ $cita->cliente_id === $cliente->id ? 'selected' : '' }}>{{ $cliente->nombre }} {{ $cliente->apellido }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Dropdown para seleccionar Ubicación -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="ubicacion">Ubicación</label>
                        <select name="ubicacion" id="ubicacion" class="form-control">
                            @foreach ($departamentos as $departamento)
                                <option value="{{ $departamento }}" {{ $cita->ubicacion === $departamento ? 'selected' : '' }}>{{ $departamento }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Fecha de Inicio, Fecha de Fin -->
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="fechaInicio">Fecha de Inicio</label>
                        <input type="datetime-local" name="fechaInicio" id="fechaInicio" class="form-control" value="{{ $cita->fecha_inicio }}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="fechaFin">Fecha de Fin</label>
                        <input type="datetime-local" name="fechaFin" id="fechaFin" class="form-control" value="{{ $cita->fecha_fin }}" required>
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
                    <a href="{{ route('secretaria.visitas.tecnico.download.pdf', ['cita' => $cita->id]) }}" class="btn btn-primary">Descargar PDF</a>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                    <a href="{{ route('secretaria.visitas') }}" class="btn btn-secondary">Cancelar</a>
                </div>
            </div>
          </form>
        </div>
    </div>
    <br>
    <div class="section-header">
        <h1>Horario de citas programadas del técnico </h1>
    </div>
    <div class="row">
        <!-- Div para mostrar el calendario -->
        <div class="col-md-12">
            <div id="calendar"></div>
        </div>
    </div>
</div>
</section>
@endsection()
@section('js')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Obtener el ID del técnico seleccionado
        const tecnicoDropdown = document.getElementById('tecnico');
        const selectedTecnicoId = tecnicoDropdown.value;

        // Configuración del calendario
        const calendarEl = document.getElementById('calendar');
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            editable: true,
            selectable: true,
            // eventClick: function(info) {
            //     // Mostrar el modal de edición al hacer clic en un evento
            //     $('#editarCitaModal').modal('show');
            // },
        });

        // Define un objeto de mapeo de colores
        const colorMapping = {
          "badge-success": "green",
          "badge-warning": "orange",
          "badge-danger": "red",
          "badge-info": "blue",
          "badge-primary": "purple",
        };

        // Cargar las citas desde el backend
        fetch(`/secretaria/visitas/tecnico/${selectedTecnicoId}`)
            .then(response => response.json())
            .then(data => {
                // Procesar y agregar las citas al calendario
                const eventosFullCalendar = data.map(evento => ({
                  id: evento.id,
                  title: `Cliente | ${evento.cliente_nombre} ${evento.cliente_apellido}` || 'Evento sin título', // Puedes ajustar esto según tus necesidades
                  start: new Date(evento.fecha_inicio),
                  end: new Date(evento.fecha_fin),
                  location: evento.ubicacion,
                  color: colorMapping[evento.color] || "gray",
                }));
                calendar.addEventSource(eventosFullCalendar);
                calendar.render();
            })
            .catch(error => {
                console.error('Error al cargar las citasss:', error);
            });

        // Manejar el cambio en el dropdown de técnicos
        tecnicoDropdown.addEventListener('change', function() {
            const selectedTecnicoId = this.value;

            // Limpiar el calendario
            calendar.removeAllEvents();

            // Cargar las citas para el técnico seleccionado
            fetch(`/secretaria/visitas/tecnico/${selectedTecnicoId}`)
                .then(response => response.json())
                .then(data => {
                    // Procesar y agregar las citas al calendario
                    const eventosFullCalendar = data.map(evento => ({
                      id: evento.id,
                      title: `${evento.usuario_nombre}${evento.usuario_apellido} | ${evento.cliente_nombre}${evento.cliente_apellido}` || 'Evento sin título', // Puedes ajustar esto según tus necesidades
                      start: new Date(evento.fecha_inicio),
                      end: new Date(evento.fecha_fin),
                      location: evento.ubicacion,
                      color: colorMapping[evento.color] || "gray",
                    }));
                    calendar.addEventSource(eventosFullCalendar);
                    calendar.render();
                })
                .catch(error => {
                    console.error('Error al cargar las citas:', error);
                });
        });
    });
</script>
@endsection
