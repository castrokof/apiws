<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Orden de Compra PDF</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        .header, .footer { text-align: center; }
        .info, .table-container { margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #333; padding: 5px; text-align: left; }
        th { background-color: #f2f2f2; }
        .estado { font-weight: bold; color: white; padding: 5px; }
        .Pendiente { background-color: red; }
        .Completa { background-color: green; }
        .Anulada { background-color: darkred; }
    </style>
</head>
<body>

    <div class="header" >
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <div>
                @if($logoBase64)
                    <img src="{{ $logoBase64 }}" alt="Logo" style="width: 300px; height: auto;">
                @endif
            </div>
            <div>
                <h2>Orden de Compra #{{ $ordenes['infoOrden']->orden_de_compra }}</h2>
            </div>    
        </div>
        
    </div>

    <div class="info">
        <table>
            <tr>
                <td><strong>Proveedor:</strong> {{ $ordenes['infoOrden']->proveedor }}</td>
                <td><strong>NIT:</strong> {{ $ordenes['infoOrden']->nit }}</td>
            </tr>
            <tr>
                <td><strong>Teléfono:</strong> {{ $ordenes['infoOrden']->telefono }}</td>
                <td><strong>Dirección:</strong> {{ $ordenes['infoOrden']->direccion }}</td>
            </tr>
            <tr>
                <td><strong>Email:</strong> {{ $ordenes['infoOrden']->email }}</td>
                <td><strong>Farmacia:</strong> {{ $ordenes['NombreCentroProduccion'] }}</td>
            </tr>
            <tr>
                <td><strong>Fecha:</strong> {{ $ordenes['infoOrden']->fecha }}</td>
                <td><strong>Realizado Por:</strong> {{ $ordenes['Usuario']->name }}</td>
            </tr>
            <tr>
                <td colspan="2"><strong>Observaciones:</strong> {{ $ordenes['infoOrden']->observaciones }}</td>
            </tr>
        </table>
    </div>

    <div class="table-container">
        <h4>Detalles de la Orden</h4>
        <table>
            <thead>
                <tr>
                    <th>Código Molécula</th>
                    <th>Descripción</th>
                    <th>Presentación</th>
                    <th>Cantidad</th>
                    <th>V. Unitario</th>
                    <th>Iva</th>
                    <th>V. Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($ordenes['Moleculas'] as $detalle)
                    <tr>
                        <td>{{ $detalle->codigo }}</td>
                        <td>{{ $detalle->nombre }}</td>
                        <td>{{ $detalle->presentacion }}</td>
                        <td>{{ $detalle->cantidad }}</td>
                        <td>${{ number_format($detalle->precio, 2) }}</td>
                        <td>${{ number_format($detalle->iva, 2) }}</td>
                        <td>${{ number_format($detalle->subtotal, 2) }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="5" style="text-align: right;"><strong>Sub Total:</strong></td>
                    <td colspan="2">${{ number_format($ordenes['infoOrden']->total, 2) }}</td>
                </tr>
                <tr>
                        <td colspan="5" style="text-align: right;"><strong>Iva:</strong></td>
                    <td colspan="2">${{ number_format($ordenes['infoOrden']->iva, 2) }}</td>
                </tr>
                <tr>
                    <td colspan="5" style="text-align: right;"><strong>Total:</strong></td>
                    <td colspan="2">${{ number_format($ordenes['infoOrden']->total, 2) }}</td>
                </tr>
                    

            </tbody>
        </table>
    </div>

</body>
</html>