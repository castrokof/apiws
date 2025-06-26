<table class="table table-bordered table-striped" id="tabla-detalles" data-orden-id="{{ $ordenes['infoOrden']->id }}">

                <thead>
                    <tr>
                        <th>Observación </th>
                        <th>Código Molécula</th>
                        <th>Descripción</th>
                        <th>Presentación</th>
                        <th>Cantidad</th>
                        <th>Entregadas</th>
                        <th>Faltantes</th>
                        <th>V. Unitario ($)</th>
                        <th>V. Total Parcial.($)</th>  
                        <th>V. Total Inicial.($)</th>                                          
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ordenes['Moleculas'] as $detalle)
                        <tr>
                        <td>
                        <button 
                            type="button" 
                            class="btn btn-sm {{ $detalle->observaciones ? 'btn-success' : 'btn-warning' }} btn-comentario" 
                            data-toggle="modal" 
                            data-target="#comentarioModal"
                            data-id="{{ $detalle->id }} | {{ $detalle->observaciones }}"
                            data-comentario="{{ e($detalle->observaciones ?? '') }}"
                            title="Agregar comentario"
                        >
                            <i class="fas fa-comment"></i>
                        </button>
                        </td>
                            <td>{{ $detalle->codigo}}</td>
                            <td>{{ $detalle->nombre }}</td>
                            <td>{{ $detalle->presentacion }}</td>
                            <td class="cantidad-cell">{{ $detalle->cantidad }}</td>
                            <td class="editable-cell" data-detalle-id="{{ $detalle->id }}" data-field="cantidadEntregada">
                            <span class="view-mode">{{ $detalle->cantidadEntregada }}</span>
                            <div class="edit-mode" style="display: none;">
                                <input type="number" class="form-control form-control-sm cantidad-entregada-input" value="{{ $detalle->cantidadEntregada }}">
                            </div>
                            </td>
                            <td class="faltantes-cell" >{{ $detalle->cantidad -  $detalle->cantidadEntregada}}</td>
                            <td>{{ number_format($detalle->precio, 2) }}</td>
                            <td class="subtotal_molecula">{{ number_format($detalle->totalParcial, 2) }}</td>  
                            <td>{{ number_format($detalle->subtotal, 2) }}</td>                                                    
                            
                            @if($detalle->estado == "Pendiente")
                                <td class="estado-cell" style="background-color: red; color: white;"> {{ $detalle->estado }}</td>       
                            @endif
                            @if($detalle->estado == "Completa")
                                <td class="estado-cell" style="background-color: green; color: white;"> {{ $detalle->estado }}</td>       
                            @endif
                            @if($detalle->estado == "Anulada")
                                <td class="estado-cell" style="background-color: red; color: white;"> {{ $detalle->estado }}</td>       
                            @endif
                            @if(Auth::user()->rol != '6')
                            <td>
                                    <button 
                                        type="button" 
                                        class="btn btn-danger btn-sm btn-eliminar" 
                                        data-id="{{ $detalle->id }}" 
                                        title="Eliminar molécula">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                            </td>
                            @endif
                             @if(Auth::user()->rol != '6')
                            <td>    
                                <a href="{{ route('ordenesDetalle.editar', $detalle->id) }}" class="btn btn-warning btn-sm" title="Editar Molecula">
                                        <i class="fas fa-edit"></i>
                                    </a>
                            </td>
                            @endif
                            <td>
                                
                                @if($detalle->estado != "Completa")
                                <button class="btn btn-sm btn-primary edit-btn" title="Editar Orden">
                                    <i class="fas fa-edit"></i>
                                </button>
                            <div class="edit-actions" style="display: none;">
                                <button class="btn btn-sm btn-success save-btn" style="margin: 5px;">Guardar</button>
                                
                                <button class="btn btn-sm btn-secondary cancel-btn" style="margin: 5px;">Cancelar</button>
                            </div>
                                @endif
                                
                            
                        </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            