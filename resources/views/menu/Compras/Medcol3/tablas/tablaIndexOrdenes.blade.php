@foreach($ordenes as $orden)
<tr>
    <td>#{{ $orden->orden_de_compra }}</td>
    <td>{{ $orden->cod_farmacia }}</td>
    <td>{{ $orden->fecha }}</td>
    <td>{{ $orden->proveedor }}</td>
    <td>{{ $orden->codigo_proveedor }}</td>
    <td>{{ $orden->telefono }}</td>
    <td>{{ number_format($orden->total, 2) }}</td>
    <td class="small-box shadow-lg 
        {{ $orden->estado == 'Pendiente' ? 'l-bg-orange-dark' : ($orden->estado == 'Completada' ? 'l-bg-green-dark' : 'l-bg-red-dark') }}">
        {{ $orden->estado }}
    </td>
    <td>
        <div class="btn-group" role="group">
            <a href="{{ route('ordenes.detalle', $orden->num_orden_compra) }}" class="btn btn-info btn-sm">
                <i class="fas fa-search"></i>
            </a>
            @if(Auth::user()->rol != '6')
            <a href="{{ route('ordenes.editar', $orden->id) }}" class="btn btn-warning btn-sm">
                <i class="fas fa-edit"></i>
            </a>
            @endif
            @if(Auth::user()->rol != '6')
            <button type="button" class="btn btn-danger btn-sm btn-eliminar-orden" data-id="{{ $orden->id }}">
                <i class="fas fa-trash-alt"></i>
            </button>
            @endif
        </div>
    </td>
</tr>
@endforeach
