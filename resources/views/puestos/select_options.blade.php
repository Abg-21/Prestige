<option value="">Seleccione un puesto</option>
@foreach ($puestos as $puesto)
    <option value="{{ $puesto->idPuestos }}">{{ $puesto->Puesto }}</option>
@endforeach