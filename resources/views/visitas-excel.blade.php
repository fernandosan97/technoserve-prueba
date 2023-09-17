<table>
    <thead>
        <tr>
            @foreach($headings as $heading)
                <th>{{ $heading }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($data as $row)
            <tr>
                <td>{{ $row->id }}</td>
                <td>{{ $row->usuario->name }} {{ $row->usuario->lastname }}</td>
                <td>{{ $row->cliente->nombre }} {{ $row->cliente->apellido }}</td>
                <td>{{ $row->fecha_inicio }}</td>
                <td>{{ $row->fecha_fin }}</td>
                <td>{{ $row->ubicacion }}</td>
                <td>{{ $row->diagnostico }}</td>
                <td>{{ $row->practicas_a_desarrollar }}</td>
                <td>{{ $row->estado }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
