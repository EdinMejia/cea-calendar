@php $max = max(1, collect($filas)->max('valor')); @endphp

<table class="barras">
    @foreach ($filas as $f)
        <tr>
            <td class="etq">{{ $f['etiqueta'] }}</td>
            <td class="pista">
                <div class="barra" @style(['width: '.round($f['valor'] / $max * 296).'px', 'background: '.($f['color'] ?? '#83C152')])></div>
            </td>
            <td class="num">{{ $f['texto'] ?? $f['valor'] }}</td>
        </tr>
    @endforeach
</table>