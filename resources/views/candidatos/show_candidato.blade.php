@if(!$candidato)
    <div style="padding: 20px; color: #e74c3c; font-weight: bold;">
        No hay datos del candidato.
    </div>
@else
    <div style="max-width: 900px; margin: 30px auto; background: #fff; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); padding: 32px;">
        <h2 style="text-align: center; margin-bottom: 32px; color: #3498db;">Detalle de Candidato</h2>
        <table style="width:100%; border-collapse: separate; border-spacing: 0 18px;">
            <tr>
                <td style="font-weight:bold; width: 25%;">Nombre:</td>
                <td style="width: 25%;">{{ $candidato->Nombre }}</td>
                <td style="font-weight:bold;">Apellido Paterno:</td>
                <td>{{ $candidato->Apellido_Paterno }}</td>
                <td style="font-weight:bold;">Apellido Materno:</td>
                <td>{{ $candidato->Apellido_Materno }}</td>
            </tr>
            <tr>
                <td style="font-weight:bold;">Edad:</td>
                <td>{{ $candidato->Edad }}</td>
                <td style="font-weight:bold;">Teléfono:</td>
                <td>{{ $candidato->Telefono }}</td>
                <td style="font-weight:bold;">Estado:</td>
                <td>{{ $candidato->Estado }}</td>
            </tr>
            <tr>
                <td style="font-weight:bold;">Ruta:</td>
                <td>{{ $candidato->Ruta }}</td>
                <td style="font-weight:bold;">Escolaridad:</td>
                <td>{{ $candidato->Escolaridad }}</td>
                <td style="font-weight:bold;">Correo:</td>
                <td>{{ $candidato->Correo }}</td>
            </tr>
            <tr>
                <td style="font-weight:bold;">Experiencia:</td>
                <td>{{ $candidato->Experiencia }}</td>
                <td style="font-weight:bold;">Fecha de Postulación:</td>
                <td>{{ $candidato->Fecha_Postulacion }}</td>
                <td style="font-weight:bold;">Puesto:</td>
                <td>{{ $candidato->puesto?->Puesto ?? 'Sin puesto' }}</td>
            </tr>
        </table>
        <div style="margin-top: 36px; text-align: right;">
            <a href="{{ route('candidatos.index') }}" class="ajax-link" style="padding: 10px 24px; background: #3498db; color: #fff; border-radius: 4px; text-decoration: none;">Volver al listado</a>
        </div>
    </div>
@endif
