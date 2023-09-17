<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket de Cita</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .ticket {
            width: 300px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #000;
            background-color: #fff;
            text-align: center;
        }

        .ticket h1 {
            font-size: 24px;
            margin-bottom: 10px;
        }

        .ticket p {
            font-size: 16px;
            margin-bottom: 5px;
        }

        .ticket .info-label {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="ticket">
        <h1>Ticket de Cita: #{{ $cita->id }}</h1>
        <p><span class="info-label">Cliente:</span> {{ $cita->cliente->nombre }} {{ $cita->cliente->apellido }}</p>
        <p><span class="info-label">Técnico:</span> {{ $cita->usuario->name }} {{ $cita->usuario->lastname }}</p>
        <p><span class="info-label">Fecha de Inicio:</span> {{ $cita->fecha_inicio }}</p>
        <p><span class="info-label">Fecha de Fin:</span> {{ $cita->fecha_fin }}</p>
        <p><span class="info-label">Ubicación:</span> {{ $cita->ubicacion }}</p>
        <p><span class="info-label">Estado:</span> {{ $cita->estado }}</p>
    </div>
</body>
</html>
