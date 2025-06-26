<?php

namespace App\Http\Controllers\Medcol6;

use App\Http\Controllers\Controller;
use App\Models\compras\medcol3\Medcolcompras3;
use Illuminate\Http\Request;
use App\Models\Medcol6\OrdenCompraMedcol6;
use App\User;
use App\UsuarioApi;
use SebastianBergmann\Environment\Console;
use Yajra\DataTables\Facades\DataTables;
use Pdf;
//use Barryvdh\DomPDF\Facade\Pdf;
use Facade\FlareClient\Api;
use Illuminate\Support\Facades\File;

class OrdenCompraApiMedcol6Controller extends Controller
{
    public function index(Request $request)
{
    $ordenes = Medcolcompras3::with('proveedor')
                ->orderBy('created_at', 'desc');
    if ($request->ajax()) {
        return view('menu.Compras.Medcol3.tablas._tabla_moleculas', compact('ordenes'))->render();
    }

    return view('menu.Compras.Medcol3.tablas.tablaIndexEntradas', compact('ordenes'));
}

    public function detalle($numeroOrden)
        {
            $ordenes = [];

            // Paginación con 8 registros por página
            $Moleculas = Medcolcompras3::where('numeroOrden', $numeroOrden)
            ->orderBy('nombre', 'asc')
            ->paginate(100);
            $Moleculas->appends(['numeroOrden' => $numeroOrden]);

            $infoOrden = OrdenCompraMedcol6::where('num_orden_compra', $numeroOrden)->first();

            $Usuario =  User::where('id',$infoOrden->user_create)->first();
            $ordenes['infoOrden'] = $infoOrden;
            $ordenes['Moleculas'] = $Moleculas;
            $ordenes['Usuario'] = $Usuario;

            return view('menu.Compras.Medcol3.detalleCompras', compact('ordenes'));
        }

public function resumenOrdenesCompra()
{
    $totalOrdenes = OrdenCompraMedcol6::count();
    $pendientes = OrdenCompraMedcol6::where('estado', 'Pendiente')->count();
    $completadas = OrdenCompraMedcol6::where('estado', 'Completada')->count();
    $incumplidas = OrdenCompraMedcol6::where('estado', 'Anulada')->count();

    return response()->json([
        'total_ordenes' => $totalOrdenes,
        'pendientes' => $pendientes,
        'completadas' => $completadas,
        'incumplidas' => $incumplidas,
    ]);
}
public function actualizarDetalle(Request $request)
{
    $detalle = Medcolcompras3::find($request->detalle_id);

    if (!$detalle) {
        return response()->json(['error' => 'Detalle no encontrado'], 404);
    }

    if ($request->cantidadEntregada > $detalle->cantidad) {
        return response()->json(['error' => 'La cantidad entregada no puede ser mayor a la cantidad solicitada'], 400);
    }

    if ($request->cantidadEntregada < 0) {
        return response()->json(['error' => 'La cantidad entregada no puede ser menor a 0'], 400);
    }

    if ($request->cantidadEntregada == 0) {
        return response()->json(['error' => 'La cantidad entregada no puede ser 0'], 400);
    }

    if ($request->cantidadEntregada < $detalle->cantidadEntregada) {
        return response()->json(['error' => 'La cantidad entregada no puede ser menor a la cantidad ya entregada anteriormente'], 400);
    }

    if ($request->cantidadEntregada == $detalle->cantidad) {
        $detalle->estado = 'Completa';
    }

    $orden = OrdenCompraMedcol6::where('num_orden_compra', $detalle->numeroOrden)->first();


    $detalle->cantidadEntregada = $request->cantidadEntregada;
    $detalle->totalParcial = ($detalle->precio * $request->cantidadEntregada);
    $detalle->save();
    $detalles = Medcolcompras3::where('numeroOrden', $detalle->numeroOrden)->get();
    $ContadorDeValorTotalParcial = 0;
    foreach ($detalles as $detalleUnidad) {
        $ContadorDeValorTotalParcial += $detalleUnidad->totalParcial;
    }
    $orden->totalParcial = $ContadorDeValorTotalParcial; // Actualizar el total parcial de la orden
    $orden->save();

    // Verificar si toda la orden está completa
    $EsCompleta = Medcolcompras3::where('numeroOrden', $detalle->numeroOrden)
        ->where('estado', '!=', 'Completa')
        ->count() === 0;

    return response()->json([
        'success' => 'Cantidad actualizada con éxito',
        'EsCompleta' => $EsCompleta,
        'UpdatetotalParcial' => $orden->totalParcial,
        'ValorParcial' => $detalle->totalParcial
    ]);
}
public function guardarComentario(Request $request)
{

    $detalle = Medcolcompras3::findOrFail($request->detalle_id);
    $detalle->observaciones = $request->observaciones;
    $detalle->save();

    return redirect()->back()->with('success', 'Comentario guardado con éxito.');
}

public function actualizarEstado(Request $request)
{
    $orden = OrdenCompraMedcol6::findOrFail($request->orden_id);
    $orden->estado = $request->nuevo_estado;
    $orden->save();

    return response()->json(['success' => 'Estado de la orden actualizado.']);
}
 public function ListarOrdenesCompra(Request $request)
{
    $codFarmacia = $request->input('cod_farmacia');
    $ordenCompra = $request->input('orden_de_compra');
    $proveedor = $request->input('proveedor');
    $fechaDesde = $request->input('fecha_desde');
    $fechaHasta = $request->input('fecha_hasta');

    $query = OrdenCompraMedcol6::query();

    if ($codFarmacia) {
        $query->where('cod_farmacia', 'like', '%' . $codFarmacia . '%');
    }
    if ($ordenCompra) {
        $query->where('orden_de_compra', 'like', '%' . $ordenCompra . '%');
    }
    if ($proveedor) {
        $query->where('proveedor', 'like', '%' . $proveedor . '%');
    }
    if ($fechaDesde && $fechaHasta) {
        $query->whereBetween('fecha', [$fechaDesde, $fechaHasta]);
    } elseif ($fechaDesde) {
        $query->whereDate('fecha', '>=', $fechaDesde);
    } elseif ($fechaHasta) {
        $query->whereDate('fecha', '<=', $fechaHasta);
    }

    // Paginación manual a 5 resultados
    $ordenes = $query->orderBy('fecha', 'desc')->get();

    // Contadores
    $estadisticaQuery = clone $query; // Clonamos para no perder la query original
    $todas = $estadisticaQuery->get();

    $CantidadPendientes = $todas->where('estado', 'Pendiente')->count();
    $CantidadFinalizadas = $todas->where('estado', 'Completada')->count();
    $CantidadIncumplidas = $todas->where('estado', 'Anulada')->count(); // O "Incumplida" según tu lógica
    $CantidadOrdenes = $todas->count();

    $Estadistica = [
        'CantidadPendientes' => $CantidadPendientes,
        'CantidadFinalizadas' => $CantidadFinalizadas,
        'CantidadIncumplidas' => $CantidadIncumplidas,
        'CantidadOrdenes' => $CantidadOrdenes,
    ];

    return response()->json([
        'data' => $ordenes,
        'Estadistica' => $Estadistica
    ]);
}

public function edit($id)
{
    $orden = OrdenCompraMedcol6::findOrFail($id);
    return view('menu.Compras.Medcol3.editarOrdenes', compact('orden'));
}

public function update(Request $request, $id)
{
    $orden = OrdenCompraMedcol6::findOrFail($id);

    $request->validate([
        'fecha' => 'required|date',
        'observaciones' => 'nullable|string',
        'estado' => 'required|string|in:Pendiente,Completada,Anulada',
        // agrega más validaciones según el modelo
    ]);

    $Detalles = Medcolcompras3::where('numeroOrden', $orden->num_orden_compra)->get();

    $PendienteActualizacion = false;
    foreach ($Detalles as $DetalleUnidad) { 
        if($DetalleUnidad->estado == "Pendiente"){$PendienteActualizacion = true;} 
    }
    if($request->estado == "Completada" && $PendienteActualizacion){
        return redirect()->back()->with('error', 'No se puede completar la orden, hay moleculas pendientes de entregar');
    }

    $orden->update($request->all());


    return redirect()->route('ordenes.detalle', $orden->num_orden_compra)->with('success', 'Orden actualizada correctamente.');
}
public function destroy($id)
{
    $orden = OrdenCompraMedcol6::findOrFail($id);

    $DetalleOrden = Medcolcompras3::where('numeroOrden', $orden->num_orden_compra)
    ->orderBy('nombre','asc')
    ->get();
    foreach ($DetalleOrden as $detalle) {
        $detalle->delete();
    }
    // Eliminar la orden
    $orden->delete();

    $TodasLasOrdenes = OrdenCompraMedcol6::paginate(100);
    $CantidadOrdenes = OrdenCompraMedcol6::count();
    $OrdenesPendientes = OrdenCompraMedcol6::where('estado', 'Pendiente')->count();
    $OrdenesCompletadas = OrdenCompraMedcol6::where('estado', 'Completada')->count();
    $OrdenesIncumplidas = OrdenCompraMedcol6::where('estado', 'Anulada')->count();
    $Estadistica = [
        'CantidadOrdenes' => $CantidadOrdenes,
        'OrdenesPendientes' => $OrdenesPendientes,
        'OrdenesCompletadas' => $OrdenesCompletadas,
        'OrdenesIncumplidas' => $OrdenesIncumplidas,
    ];

    return response()->json(['success' => true, 'message' => 'Orden eliminada correctamente.', 'Estadistica' => $Estadistica]);
}



 public function editDetalle($id)
{
    $detalle = Medcolcompras3::findOrFail($id);
    return view('menu.Compras.Medcol3.editarOrdenesDetalles', compact('detalle'));
}

public function updateDetalles(Request $request, $id)
{
    $Detalle = Medcolcompras3::findOrFail($id);

    $request->validate([
        'cantidad' => 'required|string',
        'precio' => 'required|string',
        'observaciones' => 'nullable|string',
        'codigo' => 'nullable|string',
        'cums' => 'nullable|string',
        'estado' => 'required|string|in:Pendiente,Completa,Anulada',
        // agrega más validaciones según el modelo
    ]);
    if ($request->cantidadEntregada > $request->cantidad) {
    return redirect()->back()->with('error', 'La cantidad entregada no puede ser mayor a la cantidad solicitada');      
    }
    if($request->cantidadEntregada < 0){
        return redirect()->back()->with('error', 'La cantidad entregada no puede ser menor a 0');
    }
    if($request->cantidad > $request->cantidadEntregada){
        $Detalle->estado = "Pendiente";
    }
    if($request->cantidad == $request->cantidadEntregada){
        $Detalle->estado = "Completa";
    }

     // Rellenar los demás campos manualmente
    $Detalle->cantidad = $request->cantidad;
    $Detalle->precio = $request->precio;
    $Detalle->observaciones = $request->observaciones;
    $Detalle->codigo = $request->codigo;
    $Detalle->cums = $request->cums;
    $Detalle->cantidadEntregada = $request->cantidadEntregada;
    $Detalle->totalParcial = ($Detalle->precio * $request->cantidadEntregada);

    $Detalle->update();

    $Detalles = Medcolcompras3::where('numeroOrden', $Detalle->numeroOrden)->get();

    $NuevoSubtotal = 0;
    $NuevototalParcial = 0;
    $PendienteActualizacion = false;
    foreach ($Detalles as $DetalleUnidad) {
        $NuevototalParcial += $DetalleUnidad->totalParcial;
        $NuevoSubtotal += $DetalleUnidad->subtotal; 
        if($DetalleUnidad->estado == "Pendiente"){$PendienteActualizacion = true;} 
    }

    $OrdenActualizada = OrdenCompraMedcol6::where('num_orden_compra', $Detalle->numeroOrden)->first();
    if($PendienteActualizacion){$OrdenActualizada->estado="Pendiente";}else{$OrdenActualizada->estado="Completada";}
    if($request->estado == "Completa" && $PendienteActualizacion){
        return redirect()->back()->with('error', 'No se puede completar la orden, hay moleculas pendientes de entregar');
    }
    $OrdenActualizada->totalParcial = $NuevototalParcial; // Actualizar el total parcial de la orden
    $OrdenActualizada->update();

    $ordenes = [];

            // Paginación con 8 registros por página
            $Moleculas = Medcolcompras3::where('numeroOrden', $Detalle->numeroOrden)
            ->orderBy('nombre', 'asc') 
            ->paginate(100);
            $Moleculas->appends(['numeroOrden' => $Detalle->numeroOrden]);

            $infoOrden = OrdenCompraMedcol6::where('num_orden_compra', $Detalle->numeroOrden)->first();

            $Usuario =  User::where('id',$infoOrden->user_create)->first();

            $ordenes['infoOrden'] = $infoOrden;
            $ordenes['Moleculas'] = $Moleculas;
            $ordenes['Usuario'] = $Usuario;

            return view('menu.Compras.Medcol3.detalleCompras', compact('ordenes'));

    return redirect()->route('compras.medcol3')->with('success', 'Orden actualizada correctamente.');
}

public function exportarPDF($numeroOrden)
{
    $ordenes = [];

    $Moleculas = Medcolcompras3::where('numeroOrden', $numeroOrden)->get();
    $infoOrden = OrdenCompraMedcol6::where('num_orden_compra', $numeroOrden)->firstOrFail();
    $Usuario = User::find($infoOrden->user_create);
    $NombreCentroProduccion = null;
    $centrosDeProduccion = [
    'BIO1' => "FARMACIA BIOLÓGICOS",
    'DLR1' => "FARMACIA DOLOR",
    'DPA1' => "FARMACIA PALIATIVOS",
    'EHU1' => "FARMACIA HUÉRFANAS",
    'EM01' => "FARMACIA EMCALI",
    'EVEN' => "FARMACIA EVENTO",
    'EVSM' => "EVENTO SALUD MENTAL",
    'FRJA' => "FARMACIA JAMUNDÍ",
    'INY' => "FARMACIA INYECTABLES",
    'PAC' => "FARMACIA PAC",
    'SM01' => "FARMACIA SALUD MENTAL",
    'BPDT' => "BOLSA",
    'FRIO' => "FARMACIA IDEO",
    'EVIO' => "EVENTO IDEO",
    'BDNT' => "BOLSA NORTE"
    // Agrega más si los necesitas
 ];

   $NombreCentroProduccion = $centrosDeProduccion[$infoOrden->cod_farmacia] ?? "CENTRO DESCONOCIDO";
    


    $ordenes['infoOrden'] = $infoOrden;
    $ordenes['Moleculas'] = $Moleculas;
    $ordenes['Usuario'] = $Usuario;
    $ordenes['NombreCentroProduccion'] = $NombreCentroProduccion;

    // Convertir logo a base64
    $logoPath = null;
    $logoPathVitalia = public_path('img/vitalia.png');
    $logoPathMedcol = public_path('img/medcol.png');
    $logoBase64 = null;
    if (str_contains($infoOrden->orden_de_compra, 'M')) {
    $logoPath = $logoPathMedcol;
        } else {
            $logoPath = $logoPathVitalia;
        }

    if (File::exists($logoPath)) {
        $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
    }

    $pdf = Pdf::loadView('menu.Compras.Medcol3.pdf_orden_compra', compact('ordenes', 'logoBase64'));

    return $pdf->stream("orden_compra_{$infoOrden->orden_de_compra}.pdf");
}

public function destroyDetallesAjax($id)
{
    $detalle = Medcolcompras3::findOrFail($id);
    $numeroOrden = $detalle->numeroOrden;
    $detalle->delete();
    $NuevoSubtotal = 0;
    $OrdenCompletada = false;
    $NuevototalParcial = 0;
    $Detalles = Medcolcompras3::where('numeroOrden', $numeroOrden )->get();
    foreach ($Detalles as $DetalleUnidad) {
        $NuevototalParcial += $DetalleUnidad->totalParcial;
        $NuevoSubtotal += $DetalleUnidad->subtotal;  
        if($DetalleUnidad->estado == "Pendiente"){$OrdenCompletada = true;} 
    }
    $OrdenActualizada = OrdenCompraMedcol6::where('num_orden_compra', $numeroOrden)->first();
    $OrdenActualizada->total = $NuevoSubtotal;
    $OrdenActualizada->totalParcial = $NuevototalParcial;
    $OrdenActualizada->update();
    // Redireccionar o retornar una respuesta JSON

    return response()->json(['success' => true, 'message' => 'Molecula eliminada correctamente.', 'Orden'=> $OrdenActualizada]);
}

public function guardarFactura(Request $request)
{
    $request->validate([
        'orden_id' => 'required',
        'numero_factura' => 'required|string|max:255',
    ]);

    $orden = OrdenCompraMedcol6::findOrFail($request->orden_id);

    // Si ya hay facturas, las concatenamos con coma
    if (!empty($orden->facturas)) {
        $orden->facturas .= ',' . $request->numero_factura;
    } else {
        $orden->facturas = $request->numero_factura;
    }

    $orden->save();

    return redirect()->back()->with('success', 'Factura guardada correctamente.');
}

 public function AgregarMolecula(Request $request)
{

if ($request->cums == null) {
    $request->cums = "Sin Cums";
}
if ($request->presentacion == null) {
    $request->presentacion = "Sin Presentación";
}
    // V
    // Validar datos requeridos
    $validated = $request->validate([
        'codigo' => 'required',
        'orden_id' => 'required',
        'cantidad' => 'required|integer|min:1',
        'precio_compra_subtotal_unitario' => 'required|numeric|min:0',
    ]);

    // Verificar si ya existe esa molécula para la orden
    $yaExiste = Medcolcompras3::where('numeroOrden', $request->orden_id)
                                ->where('codigo', $request->codigo)
                                ->exists();

    if ($yaExiste) {
        return redirect()->back()->with('error', 'Este artículo ya existe en la orden.');
    }

    // Preparar datos
    $EntradaMolecula = [
        'numeroOrden'     => $request->orden_id,
        'codigo'          => $request->codigo,
        'nombre'          => $request->nombrea,
        'cums'            => $request->cums,
        'marca'           => $request->marca,
        'cantidad'        => $request->cantidad,
        'precio'          => $request->precio_compra_subtotal_unitario,
        'subtotal'        => $request->precio_compra_total,
        'iva'             => $request->iva,
        'created_at'      => $request->created_at,
        'updated_at'      => $request->created_at,
        'estado'          => 'Pendiente',
        'cantidadEntregada' => 0,
        'observaciones'   => '',
        'presentacion'    => $request->presentacion,
        'totalParcial'    => 0
    ];
    $detalle = Medcolcompras3::where('numeroOrden', $EntradaMolecula['numeroOrden'])->first();

    if ($detalle) {
        $EntradaMolecula['documentoOrden'] = $detalle->documentoOrden;
        $EntradaMolecula['proveedor_id'] = $detalle->proveedor_id;
        $EntradaMolecula['usuario_id'] = $detalle->usuario_id;
        $EntradaMolecula['contrato'] = $detalle->contrato;
    }

    Medcolcompras3::create($EntradaMolecula);

    // Recalcular total de la orden
    $orden = OrdenCompraMedcol6::where('num_orden_compra', $EntradaMolecula['numeroOrden'])->first();

    if ($orden) {
        $Detalles = Medcolcompras3::where('numeroOrden', $EntradaMolecula['numeroOrden'])->get();
        $NuevoSubtotal = 0;

        foreach ($Detalles as $DetalleUnidad) {
            $NuevoSubtotal += $DetalleUnidad->subtotal;
        }

        $orden->total = $NuevoSubtotal;
        $orden->update();
    }

    return redirect()->back()->with('success', 'Artículo guardado correctamente.');
}

 

}