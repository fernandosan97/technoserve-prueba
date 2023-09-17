@extends('secretaria.layouts.master')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Nuevo Cliente</h1>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-12">
            <form method="post" action="{{ route('secretaria.clientes.store') }}">
                @csrf

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre') }}" required autofocus>
                            @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="apellido">Apellido</label>
                            <input type="text" class="form-control @error('apellido') is-invalid @enderror" id="apellido" name="apellido" value="{{ old('apellido') }}" required>
                            @error('apellido')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="tipo_cliente">Tipo de Cliente</label>
                            <select id="tipo_cliente" class="form-control @error('tipo_cliente') is-invalid @enderror" name="tipo_cliente">
                                <option value="individual" {{ old('tipo_cliente') === 'individual' ? 'selected' : '' }}>Individual</option>
                                <option value="organizacion" {{ old('tipo_cliente') === 'organizacion' ? 'selected' : '' }}>Organización</option>
                            </select>
                            @error('tipo_cliente')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="organizacion">Organización</label>
                            <input type="text" class="form-control @error('organizacion') is-invalid @enderror" id="organizacion" name="organizacion" value="{{ old('organizacion') }}" required_if:tipo_cliente,organizacion>
                            @error('organizacion')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="numero_documento">Número de Documento</label>
                            <input type="text" class="form-control @error('numero_documento') is-invalid @enderror" id="numero_documento" name="numero_documento" value="{{ old('numero_documento') }}" required>
                            @error('numero_documento')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="tiene_visitas">¿Tiene Visitas?</label>
                            <select id="tiene_visitas" class="form-control @error('tiene_visitas') is-invalid @enderror" name="tiene_visitas">
                                <option value="1" {{ old('tiene_visitas') == 1 ? 'selected' : '' }}>Sí</option>
                                <option value="0" {{ old('tiene_visitas') == 0 ? 'selected' : '' }}>No</option>
                            </select>
                            @error('tiene_visitas')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 text-center">
                        <button type="submit" class="btn btn-primary">Guardar</button>
                        <a href="{{ route('secretaria.clientes') }}" class="btn btn-secondary">Cancelar</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
