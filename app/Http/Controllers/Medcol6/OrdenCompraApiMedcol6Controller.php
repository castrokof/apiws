<?php

namespace App\Http\Controllers\Medcol6;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Models\compras\medcol3\Medcolcompras3;
use App\Models\compras\MoleculaProveedorCodigo;
use App\Models\compras\Molecula;
use Illuminate\Http\Request;
use App\Models\Medcol6\OrdenCompraMedcol6;
use App\User;
use App\UsuarioApi;
use SebastianBergmann\Environment\Console;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\File;
use Facade\FlareClient\Api;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\DB;
use App\Exports\OrdenesExport;
use App\Exports\OrdenesExportDetalle;
use Maatwebsite\Excel\Facades\Excel;


class OrdenCompraApiMedcol6Controller extends Controller
{
    public function index(Request $request)
{
    $ordenes = OrdenCompraMedcol6::orderBy('fecha', 'desc')
                ->paginate(15); // Paginación de 15 registros por página
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
            $Diferencia = $infoOrden->totalParcial - $infoOrden->total;

            $Usuario =  User::where('id',$infoOrden->user_create)->first();
            $ordenes['infoOrden'] = $infoOrden;
            $ordenes['Moleculas'] = $Moleculas;
            $ordenes['Usuario'] = $Usuario;
            $ordenes['Diferencia'] = $Diferencia;

            return view('menu.Compras.Medcol3.detalleCompras', compact('ordenes'));
        }

public function resumenOrdenesCompra()
{
    $TotalValorOrdenes = OrdenCompraMedcol6::sum('total');
    $totalOrdenes = OrdenCompraMedcol6::count();
    $pendientes = OrdenCompraMedcol6::where('estado', 'Pendiente')->count();
    $completadas = OrdenCompraMedcol6::where('estado', 'Completada')->count();
    $incumplidas = OrdenCompraMedcol6::where('estado', 'Anulada')->count();

    return response()->json([
        'total_ordenes' => $totalOrdenes,
        'pendientes' => $pendientes,
        'completadas' => $completadas,
        'incumplidas' => $incumplidas,
        'TotalValorOrdenes' => $TotalValorOrdenes,
    ]);
}
public function actualizarDetalle(Request $request)
{
    $detalle = Medcolcompras3::find($request->detalle_id);
    

    if (!$detalle) {
        return response()->json(['error' => 'Detalle no encontrado'], 404);
    }

    if (($request->cantidadEntregada+$detalle->cantidadEntregada) > $detalle->cantidad) {
        return response()->json(['error' => 'La cantidad entregada no puede ser mayor a la cantidad solicitada'], 400);
    }

    if ($request->cantidadEntregada < 0) {
        return response()->json(['error' => 'La cantidad entregada no puede ser menor a 0'], 400);
    }

    if ($request->cantidadEntregada == 0) {
        return response()->json(['error' => 'La cantidad entregada no puede ser 0'], 400);
    }

    if (($request->cantidadEntregada+$detalle->cantidadEntregada) < $detalle->cantidadEntregada) {
        return response()->json(['error' => 'La cantidad entregada no puede ser menor a la cantidad ya entregada anteriormente'], 400);
    }

    if (($request->cantidadEntregada+$detalle->cantidadEntregada) == $detalle->cantidad) {
        $detalle->estado = 'Completa';
    }

    $orden = OrdenCompraMedcol6::where('num_orden_compra', $detalle->numeroOrden)->first();

    $SumaEntregas = ($request->cantidadEntregada+$detalle->cantidadEntregada);
    $detalle->UltimaEntrega = $detalle->cantidadEntregada;
    $detalle->cantidadEntregada = $SumaEntregas;
    $detalle->totalParcial = ($detalle->valorFacturado * $SumaEntregas);
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

        $Diferencia = $orden->totalParcial - $orden->total;

    return response()->json([
        'success' => 'Cantidad actualizada con éxito',
        'EsCompleta' => $EsCompleta,
        'UpdatetotalParcial' => $orden->totalParcial,
        'ValorParcial' => $detalle->totalParcial,
        'CantidadEntregada' => $detalle->cantidadEntregada,
        'Diferencia' => $Diferencia
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
    $Diferencia = $orden->totalParcial - $orden->total;

    return response()->json(['success' => 'Estado de la orden actualizado.', 'Diferencia' => $Diferencia]);
}

 public function ListarOrdenesCompra(Request $request)
{
    
    $codFarmacia = $request->input('cod_farmacia');
    $estado = $request->input('estado');
    $user_create = $request->input('user_create');
    $ordenCompra = $request->input('orden_de_compra');
    $proveedor = $request->input('proveedor');
    $fechaDesde = $request->input('fecha_desde');
    $fechaHasta = $request->input('fecha_hasta');
    $ClasiOrden = $request->input('ClasiOrden');

    $query = OrdenCompraMedcol6::query();

    if ($codFarmacia) {
        $query->where('cod_farmacia', 'like', '%' . $codFarmacia . '%');
    }
    if ($estado) {
        $query->where('estado', 'like', '%' . $estado . '%');
    }
    if ($user_create) {
        $query->where('user_create', 'like', '%' . $user_create . '%');
    }
    if ($ordenCompra) {
        $query->where('orden_de_compra', 'like', '%' . $ordenCompra . '%');
    }
    if ($proveedor) {
        $query->where('proveedor', 'like', '%' . $proveedor . '%');
    }
    if ($ClasiOrden) {
        $query->where('ClasiOrden', 'like', '%' . $ClasiOrden . '%');
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

    $TotalValorOrdenes = $todas->sum('total');
    $CantidadPendientes = $todas->where('estado', 'Pendiente')->count();
    $CantidadFinalizadas = $todas->where('estado', 'Completada')->count();
    $CantidadIncumplidas = $todas->where('estado', 'Anulada')->count(); // O "Incumplida" según tu lógica
    $CantidadOrdenes = $todas->count();

    $Estadistica = [
        'CantidadPendientes' => $CantidadPendientes,
        'CantidadFinalizadas' => $CantidadFinalizadas,
        'CantidadIncumplidas' => $CantidadIncumplidas,
        'CantidadOrdenes' => $CantidadOrdenes,
        'TotalValorOrdenes' => $TotalValorOrdenes,
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
    $TotalValorOrdenes = OrdenCompraMedcol6::sum('total');
    $CantidadOrdenes = OrdenCompraMedcol6::count();
    $OrdenesPendientes = OrdenCompraMedcol6::where('estado', 'Pendiente')->count();
    $OrdenesCompletadas = OrdenCompraMedcol6::where('estado', 'Completada')->count();
    $OrdenesIncumplidas = OrdenCompraMedcol6::where('estado', 'Anulada')->count();
    $Estadistica = [
        'CantidadOrdenes' => $CantidadOrdenes,
        'OrdenesPendientes' => $OrdenesPendientes,
        'OrdenesCompletadas' => $OrdenesCompletadas,
        'OrdenesIncumplidas' => $OrdenesIncumplidas,
        'TotalValorOrdenes' => $TotalValorOrdenes,
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
        'valorFacturado' => 'required|string',
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
    $Detalle->valorFacturado = $request->valorFacturado;
    $Detalle->observaciones = $request->observaciones;
    $Detalle->codigo = $request->codigo;
    $Detalle->cums = $request->cums;
    $Detalle->cantidadEntregada = $request->cantidadEntregada;
    $Detalle->totalParcial = ($Detalle->valorFacturado * $request->cantidadEntregada);
    $Detalle->subtotal = ($request->precio * $request->cantidad);

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
    $OrdenActualizada->total = $NuevoSubtotal; // Actualizar el total parcial de la orden
    $OrdenActualizada->update();

    $ordenes = [];

            // Paginación con 8 registros por página
            $Moleculas = Medcolcompras3::where('numeroOrden', $Detalle->numeroOrden)
            ->orderBy('nombre', 'asc') 
            ->paginate(100);
            $Moleculas->appends(['numeroOrden' => $Detalle->numeroOrden]);

            $infoOrden = OrdenCompraMedcol6::where('num_orden_compra', $Detalle->numeroOrden)->first();

            $Usuario =  User::where('id',$infoOrden->user_create)->first();

            $Diferencia = $infoOrden->totalParcial - $infoOrden->total;

            $ordenes['infoOrden'] = $infoOrden;
            $ordenes['Moleculas'] = $Moleculas;
            $ordenes['Usuario'] = $Usuario;
            $ordenes['Diferencia'] = $Diferencia;

            return view('menu.Compras.Medcol3.detalleCompras', compact('ordenes'));

    return redirect()->route('compras.medcol3')->with('success', 'Orden actualizada correctamente.');
}




public function exportarPDF_conlogo($numeroOrden)
   
    {
                // 1. Recuperar datos
                $ordenes['Moleculas']              = Medcolcompras3::where('numeroOrden', $numeroOrden)->get();
                $ordenes['infoOrden']              = OrdenCompraMedcol6::where('num_orden_compra', $numeroOrden)->firstOrFail();
                $ordenes['Usuario']                = User::find($ordenes['infoOrden']->user_create);
                $centros = [
                    'BIO1' => "FARMACIA BIOLÓGICOS", /* … */
                    'BDNT' => "BOLSA NORTE"
                ];
                $ordenes['NombreCentroProduccion'] = $centros[$ordenes['infoOrden']->cod_farmacia] 
                    ?? 'CENTRO DESCONOCIDO';
            
                // 2. Elegir logo según el código de orden
                $logoFile = Str::contains($ordenes['infoOrden']->orden_de_compra, 'M')
                    ? 'assets/images/medcol.png'
                    : 'assets/images/vitalia.png';
            
                // 3. Obtener la imagen vía URL pública y convertir a Base64
                $logoUrl     = asset($logoFile);
                $logoContent = @file_get_contents($logoUrl);
                $logoBase64  = $logoContent
                    ? 'data:image/png;base64,'.base64_encode($logoContent)
                    : null;
            
                // 4. Generar el PDF pasando el Base64
                $pdf = PDF::loadView(
                    'menu.Compras.Medcol3.pdf_orden_compra',
                    array_merge($ordenes, ['logoBase64' => $logoBase64])
                );
            
                // 5. Devolver streaming
                return $pdf->stream("orden_compra_{$ordenes['infoOrden']->orden_de_compra}.pdf");
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
    'SAPRO' => "SALA DE PROCEDIMIENTOS",
    'CENAC' => "CENTRO DE ACOPIO",
    'EVIO' => "EVENTO IDEO",
    'BDNT' => "BOLSA NORTE",
    'COOS' => "FARMACIA COOSALUD",
    'COMF' => "FARMACIA COMFENALCO",
    'INY' => "FARMACIA INYECTABLES",
    'FPEND' => "FARMACIA PENDIENTES"
    // Agrega más si los necesitas
 ];

   $NombreCentroProduccion = $centrosDeProduccion[$infoOrden->cod_farmacia] ?? "CENTRO DESCONOCIDO";
    


    $ordenes['infoOrden'] = $infoOrden;
    $ordenes['Moleculas'] = $Moleculas;
    $ordenes['Usuario'] = $Usuario;
    $ordenes['NombreCentroProduccion'] = $NombreCentroProduccion;

    // Convertir logo a base64
    $logoPath = null;
    // En tu controlador
    $logoPathVitalia = public_path("assets/img/vitalia.png");
    $logoPathMedcol = public_path("assets/img/medcol.png");
    $logoBase64 = null;
    $anioActual = now()->year;
    if (str_contains($infoOrden->orden_de_compra, $anioActual . 'M')) {
    $logoPath = $logoPathMedcol;
        } else {
            $logoPath = $logoPathVitalia;
        }

    if (File::exists($logoPath)) {
        $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
    }

    $pdf = PDF::loadView('menu.Compras.Medcol3.pdf_orden_compra', compact('ordenes', 'logoBase64'));

    return $pdf->stream("orden_compra_{$infoOrden->orden_de_compra}.pdf");
}





public function destroyDetallesAjax($id)
{
    $detalle = Medcolcompras3::findOrFail($id);
    
    $NumeroOrden = $detalle->numeroOrden;
    $totalMoleculas = Medcolcompras3::where('numeroOrden', $NumeroOrden)->get();
   if ($totalMoleculas->count() > 1){
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
    else{
        return response()->json(['error' => true, 'message' => 'Una orden de compra debe tener al menos una molécula.']);
    }
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

if ($request->input('cums') == null) {
    $request->merge(['cums' => "Sin Cums"]);
}
if ($request->input('presentacion') == null) {
    $request->merge(['presentacion' => "Sin Presentación"]);
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
        'valorFacturado'  => $request->precio_compra_subtotal_unitario,
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
        $NuevototalParcial = 0;

        foreach ($Detalles as $DetalleUnidad) {
            $NuevototalParcial += $DetalleUnidad->totalParcial;
            $NuevoSubtotal += $DetalleUnidad->subtotal;
        }

        $orden->total = $NuevoSubtotal;
        $orden->sub_total = $NuevoSubtotal;
        $orden->update();
    }

    return redirect()->back()->with('success', 'Artículo guardado correctamente.');
}


public function vistaIngreso($numeroOrden)
{
    $infoOrden = OrdenCompraMedcol6::where('num_orden_compra', $numeroOrden)->firstOrFail();

    // Solo con delta > 0
    $detalles = Medcolcompras3::where('numeroOrden', $numeroOrden)
        ->whereRaw('COALESCE(cantidadEntregada,0) > COALESCE(UltimaEntrega,0)')
        ->get();

    if ($detalles->isEmpty()) {
        // Si no hay nada que ingresar, muestra un mensaje y regresa a la vista igual
        return view('menu.Compras.Medcol3.ingreso', [
            'infoOrden' => $infoOrden,
            'mols'      => collect(),
            'avisos'    => ['No hay moléculas con entregas pendientes para esta orden.'],
        ]);
    }

    $avisos = [];

    // Prepara datos para la vista con cantidad = delta (y resuelve código alterno)
    $mols = $detalles->map(function ($m) use (&$avisos) {
        $solicitada = (float)($m->cantidad ?? 0);
        $subtotal   = (float)($m->subtotal ?? 0);
        $unitario   = $solicitada > 0 ? $subtotal / $solicitada : 0;
        $delta      = (float) (($m->cantidadEntregada ?? 0) - ($m->UltimaEntrega ?? 0));

        // 1) Intentar como código de proveedor
        $ProveedorRelacionado = MoleculaProveedorCodigo::where('codigo_proveedor', $m->codigo)->first();

        $MoleculaRfast = null;
        if ($ProveedorRelacionado) {
            $MoleculaRfast = Molecula::find($ProveedorRelacionado->molecule_id);
        } else {
            // 2) Intentar directo en nuestras moléculas (OJO: corrige el $$ por $)
            $MoleculaRfast = Molecula::where('codigo', $m->codigo)->first();
        }

        // Si no se pudo resolver, avisamos (pero NO rompemos la carga)
        if (!$MoleculaRfast) {
            $avisos[] = "No se encontró mapeo para el código «{$m->codigo}» ({$m->nombre}). Se dejará Alterno vacío.";
        }

        return (object)[
            'codigo'        => $m->codigo,                            // el que vino en la orden (visible)
            'nombre'        => $m->nombre,
            'presentacion'  => $m->presentacion ?? 'Unidad',
            'iva'           => (float)($m->iva ?? 0),
            'cantidad'      => $delta,                                // lo que se debe ingresar ahora
            'subtotal'      => $subtotal,
            'unitario'      => round($unitario, 2),
            'alterno'       => $MoleculaRfast->codigo ?? '',          // nuestro código interno (si se resolvió)
            'invima'        => $m->invima ?? '',
            'id'            => $m->id,
        ];
    })->filter(fn($r) => $r->cantidad > 0)->values();

    // Si después del filtro no quedó nada, avisa
    if ($mols->isEmpty()) {
        $avisos[] = 'No hay moléculas con entregas pendientes para esta orden.';
    }

    return view('menu.Compras.Medcol3.ingreso', compact('infoOrden','mols'))
           ->with('avisos', $avisos);
}

public function generarExcelIngreso(Request $request, $numeroOrden)
{
    $rows  = $request->input('rows', []);
    $filas = array_values(array_filter($rows, fn($r) => floatval($r['Cantidad'] ?? 0) > 0));
    if (empty($filas)) {
        return back()->with('error', 'No hay filas con cantidad > 0.')->withInput();
    }

    // 1) Cargar la plantilla ORIGINAL que el sistema sí acepta
    $templatePath = storage_path('app/plantillas/PLANTILLA_ALMACEN.XLS'); // ajusta la ruta
    $ss = IOFactory::load($templatePath);

    // 2) Usar la hoja EXACTA que trae la plantilla (no renombres)
    $sh = $ss->getSheet(0);
    $ss->setActiveSheetIndex(0);

    // Suponiendo que los encabezados están en la fila 1 y los datos empiezan en la 2:
    $r = 2;

    foreach ($filas as $f) {
        $alt  = $f['Alterno'] ?? '';
        $art  = $f['Articulo'] ?? '';
        $pres = 'Unidad';

        $iva  = (float)($f['IVA'] ?? 0);
        $cant = (float)($f['Cantidad'] ?? 0);
        $vu   = (float)($f['ValorUnitarioConIVA'] ?? 0);
        $vt   = (float)($f['ValorTotalConIVA'] ?? ($vu * $cant));
        $base = $iva > 0 ? round($vt / (1 + $iva/100), 2) : $vt;
        $tiva = round($vt - $base, 2);

        // Fecha como TEXTO DD/MM/YYYY
        $fvRaw = trim((string)($f['FechaVencimiento'] ?? ''));
        $fv = '';
        if ($fvRaw !== '') {
            $sep = (strpos($fvRaw, '-') !== false) ? '-' : ((strpos($fvRaw, '/') !== false) ? '/' : null);
            if ($sep) {
                $p = explode($sep, $fvRaw);
                if (count($p) === 3) {
                    $fv = (strlen($p[0]) === 4) ? "{$p[2]}/{$p[1]}/{$p[0]}" : "{$p[0]}/{$p[1]}/{$p[2]}";
                } else {
                    $fv = $fvRaw;
                }
            } else {
                $fv = $fvRaw;
            }
        }

        $lote = $f['Lote']   ?? '';
        $inv  = $f['Invima'] ?? '';

        // 3) Escribir respetando tipos (columnas A..L = 1..12)
        $sh->setCellValueExplicitByColumnAndRow(1,  $r, $alt,  DataType::TYPE_STRING);
        $sh->setCellValueExplicitByColumnAndRow(2,  $r, $art,  DataType::TYPE_STRING);
        $sh->setCellValueExplicitByColumnAndRow(3,  $r, $pres, DataType::TYPE_STRING);
        $sh->setCellValueExplicitByColumnAndRow(4,  $r, $iva,  DataType::TYPE_NUMERIC);
        $sh->setCellValueExplicitByColumnAndRow(5,  $r, $cant, DataType::TYPE_NUMERIC);
        $sh->setCellValueExplicitByColumnAndRow(6,  $r, $vt,   DataType::TYPE_NUMERIC);
        $sh->setCellValueExplicitByColumnAndRow(7,  $r, $vu,   DataType::TYPE_NUMERIC);
        $sh->setCellValueExplicitByColumnAndRow(8,  $r, $base, DataType::TYPE_NUMERIC);
        $sh->setCellValueExplicitByColumnAndRow(9,  $r, $tiva, DataType::TYPE_NUMERIC);
        $sh->setCellValueExplicitByColumnAndRow(10, $r, $fv,   DataType::TYPE_STRING); // fecha como texto
        $sh->setCellValueExplicitByColumnAndRow(11, $r, $lote, DataType::TYPE_STRING);
        $sh->setCellValueExplicitByColumnAndRow(12, $r, $inv,  DataType::TYPE_STRING);
        $r++;
    }

    // 4) Exportar con el NOMBRE EXACTO que espera VFP
    $fileName = 'PLANTILLA_ALMACEN.XLS';
    $writer = new Xls($ss);

    return new StreamedResponse(function () use ($writer) {
        $writer->save('php://output');
    }, 200, [
        'Content-Type'        => 'application/vnd.ms-excel',
        'Content-Disposition' => 'attachment; filename="'.$fileName.'"',
        'Cache-Control'       => 'max-age=0',
    ]);
}

public function exportExcel(Request $request)
{
    
    $file = 'ordenes_' . now()->format('Ymd_His') . '.xlsx';
    return Excel::download(new OrdenesExport($request), $file);
}

public function exportExcelDetalle(Request $request)
{
    
    $file = 'ordenes_detalles' . now()->format('Ymd_His') . '.xlsx';
    return Excel::download(new OrdenesExportDetalle($request), $file);
}

 

}