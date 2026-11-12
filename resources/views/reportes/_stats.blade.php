<table class="stats">
    <tr>
        @foreach ($cifras as [$valor, $etiqueta])
            <td class="{{ $loop->first ? 'pri' : '' }} {{ $loop->last ? 'ult' : '' }}"
                style="width: {{ round(100 / count($cifras), 2) }}%">
                <div class="n">{{ $valor }}</div>
                <div class="e">{{ $etiqueta }}</div>
            </td>
        @endforeach
    </tr>
</table>