// estudante/notas_frequencia/notas.blade.php

<h1>Notas de Frequência - {{ $estudante->nome }}</h1>

<table class="table table-striped">
    <thead>
        <tr>
            <th>Disciplina</th>
            <th>Nota de Frequência</th>
        </tr>
    </thead>
    <tbody>
        @foreach($notasFrequencia as $notaFrequencia)
            <tr>
                <td>{{ $notaFrequencia->disciplina->nome }}</td>
                <td>{{ $notaFrequencia->nota }}</td>
            </tr>
        @endforeach
    </tbody>
</table>