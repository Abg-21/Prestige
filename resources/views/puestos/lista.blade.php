<option value="">Seleccionar puesto</option>
@if(isset($puestos))
    @foreach($puestos as $puesto)
        <option value="{{ $puesto->idPuestos }}">{{ $puesto->Puesto }}</option>
    @endforeach
@else
    @php
        $puestos = \App\Models\Puesto::all();
    @endphp
    @foreach($puestos as $puesto)
        <option value="{{ $puesto->idPuestos }}">{{ $puesto->Puesto }}</option>
    @endforeach
@endif