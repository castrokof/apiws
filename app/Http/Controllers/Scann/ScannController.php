<?php

namespace App\Http\Controllers\Scann;
use App\Models\Scann\Comprobante;

use thiagoalessio\TesseractOCR\TesseractOCR;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use PhpParser\Node\Stmt\Return_;
use stdClass;
use DataTables;


class ScannController extends Controller
{
    
    
      public function Index(Request $request) {
          
          $usuario = auth()->user()->name;
          $rol = auth()->user()->rol;
          
             if ($request->ajax()) {
              
                    
                    if ($usuario == "Jhonnathan Castro Galeano" || $usuario == "ERICK SANTIAGO CASTRO GALEANO" || $usuario == "NANCY MEJIA") {
                        $query = Comprobante::select(['id', 'codigo', 'comprobante', 'orden', 'pdf', 'usuario', 'created_at']);
                    
                        return DataTables::of($query)
                            ->addColumn('comprobante_urls', function ($row) {
                                $comprobantes = is_array($row->comprobante) ? $row->comprobante : json_decode($row->comprobante, true) ?? [];
                                return array_map(fn($img) => url(str_replace('public/', 'storage/', $img)), $comprobantes);
                            })
                            ->addColumn('orden_urls', function ($row) {
                                $ordenes = is_array($row->orden) ? $row->orden : json_decode($row->orden, true) ?? [];
                                return array_map(fn($img) => url(str_replace('public/', 'storage/', $img)), $ordenes);
                            })
                            ->addColumn('pdf_urls', function ($row) {
                               $pdfs = is_array($row->pdf) ? $row->pdf : json_decode($row->pdf, true) ?? [];
                                return array_map(fn($pdf) => url(str_replace('public/', 'storage/', $pdf)), $pdfs);
                            })
                            ->addColumn('acciones', function ($orden) {
                                return '
                                    <button class="btn btn-danger btn-sm eliminar-orden" data-id="' . $orden->id . '">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <button class="btn btn-success btn-sm detalle-orden" data-id="' . $orden->id . '">
                                        <i class="fas fa-eye"></i>
                                    </button>';
                            })
                            ->rawColumns(['acciones']) // solo necesitas rawColumns si hay HTML, no para arrays de URLs
                            ->make(true);
                    }
                                     
                 
                /* if($usuario == "Jhonnathan Castro Galeano" || $usuario == "ERICK SANTIAGO CASTRO GALEANO" ){
                $data = Comprobante::select(['id', 'codigo', 'comprobante', 'orden', 'pdf', 'usuario', 'created_at'])
                ->get();
     
               
            
                return DataTables()->of($data)
                    ->addColumn('comprobante_urls', function ($row) {
                        $comprobantes = is_array($row->comprobante) ? $row->comprobante : json_decode($row->comprobante, true) ?? [];
                        
                        $urls = [];
                        foreach ($comprobantes as $img) {
                            $urls[] = url(str_replace('public/', 'storage/', $img));
                        }
                        return $urls;
                    })
                    ->addColumn('orden_urls', function ($row) {
                        $ordenes = is_array($row->orden) ? $row->orden : json_decode($row->orden, true) ?? [];
                       
                        $urls = [];
                        foreach ($ordenes as $img) {
                            $urls[] = url(str_replace('public/', 'storage/', $img));
                        }
                        return $urls;
                    })
                    ->addColumn('pdf_urls', function ($row) {
                        $pdfs = is_array($row->pdf) ? $row->pdf : json_decode($row->pdf, true) ?? [];
                       
                        $urls = [];
                        foreach ($pdfs as $pdf) {
                            $urls[] = url(str_replace('public/', 'storage/', $pdf));
                        }
                        return $urls;
                    })
                    ->addColumn('acciones', function ($orden) {
                        return '
                            <button class="btn btn-danger btn-sm eliminar-orden" data-id="' . $orden->id . '">
                                <i class="fas fa-trash"></i>
                            </button>
                            <button class="btn btn-success btn-sm detalle-orden" data-id="' . $orden->id . '">
                                <i class="fas fa-eye"></i>
                            </button>

                        ';
                    })
                    ->rawColumns(['comprobante_urls', 'orden_urls', 'pdf_urls', 'acciones'])
                    ->make(true);*/
                    
                   
                 else{
                     
                
                
                 $data = Comprobante::select(['id', 'codigo', 'comprobante', 'orden', 'pdf', 'usuario', 'created_at'])->where('usuario',$usuario);

                return DataTables::of($data)
                    ->addColumn('comprobante_urls', function ($row) {
                       $comprobantes = is_array($row->comprobante) ? $row->comprobante : json_decode($row->comprobante, true) ?? [];
                        $urls = [];
                        foreach ($comprobantes as $img) {
                            $urls[] = url(str_replace('public/', 'storage/', $img));
                            
                        }
                        return $urls;
                    })
                    ->addColumn('orden_urls', function ($row) {
                       $ordenes = is_array($row->orden) ? $row->orden : json_decode($row->orden, true) ?? [];
                        $urls = [];
                        foreach ($ordenes as $img) {
                            $urls[] = url(str_replace('public/', 'storage/', $img));
                        }
                        return $urls;
                    })
                    ->addColumn('pdf_urls', function ($row) {
                        $pdfs = is_array($row->pdf) ? $row->pdf : json_decode($row->pdf, true) ?? [];
                        $urls = [];
                        foreach ($pdfs as $pdf) {
                            $urls[] = url(str_replace('public/', 'storage/', $pdf));
                        }
                        return $urls;
                    })
                    
                    ->addColumn('acciones', function ($orden) {
                        return '
                            <button class="btn btn-danger btn-sm eliminar-orden" data-id="' . $orden->id . '">
                                <i class="fas fa-trash"></i>
                            </button>
                            <button class="btn btn-success btn-sm detalle-orden" data-id="{{ $orden->id }}">
                                <i class="fas fa-eye"></i>
                            </button>
                        ';
                    })
                    ->rawColumns(['comprobante_urls', 'orden_urls', 'pdf_urls', 'acciones'])
                    ->make(true);
                     
                     
                     
                 }
                
            }
        
            return view('scann.index');
        }

            public function moverImagen(Request $request) {
                
                            $imagen = $request->input('imagen');
                            
                                $carpeta = $request->input('carpeta'); // Recibe la carpeta
                            
                                if (!$imagen || !$carpeta) {
                                    return response()->json(['error' => 'Faltan parámetros.'], 400);
                                }
                            
                                // Rutas de origen y destino según la carpeta
                                $rutaStorage = storage_path("app/public/{$carpeta}/{$imagen}");
                                $rutaPublic = public_path("uploads/{$carpeta}/{$imagen}");
                            
                                // Mover la imagen solo si no existe en public/uploads/
                                if (File::exists($rutaStorage) && !File::exists($rutaPublic)) {
                                    File::copy($rutaStorage, $rutaPublic);
                                }
                            
                                return response()->json([
                                    'url' => asset("uploads/{$carpeta}/{$imagen}") // Retornar la nueva ruta
                                ]);
            }
            
            public function detalleScann(Request $request)
            {
                 $usuario = auth()->user()->name;
          $rol = auth()->user()->rol;
          
             if ($request->ajax()) {
                 $id = $request->input('id'); 

                // Usuarios con acceso completo
                if ($usuario == "Jhonnathan Castro Galeano" || $usuario == "ERICK SANTIAGO CASTRO GALEANO" || $usuario == "NANCY MEJIA") {
                    $query = Comprobante::select(['id', 'codigo', 'comprobante', 'orden', 'pdf', 'usuario', 'created_at']);
        
                    if ($id) { 
                        $query->where('id', $id); 
                    }
        
                    $data = $query->get();
        
                } else {
                    $query = Comprobante::select(['id', 'codigo', 'comprobante', 'orden', 'pdf', 'usuario', 'created_at'])
                        ->where('usuario', $usuario);
        
                    if ($id) {
                        $query->where('id', $id);  
                    }
        
                    $data = $query->get();
                }

                 
                 if($usuario == "Jhonnathan Castro Galeano" || $usuario == "ERICK SANTIAGO CASTRO GALEANO" || $usuario == "NANCY MEJIA"){
                $data = Comprobante::select(['id', 'codigo', 'comprobante', 'orden', 'pdf', 'usuario', 'created_at'])
                ->get();
                $id = $request->input('id'); 

                    // Usuarios con acceso completo
                    if ($usuario == "Jhonnathan Castro Galeano" || $usuario == "ERICK SANTIAGO CASTRO GALEANO" || $usuario == "NANCY MEJIA") {
                        $query = Comprobante::select(['id', 'codigo', 'comprobante', 'orden', 'pdf', 'usuario', 'created_at']);
            
                        if ($id) {
                            $query->where('id', $id); 
                        }
            
                        $data = $query->get();
            
                    } else {
                        $query = Comprobante::select(['id', 'codigo', 'comprobante', 'orden', 'pdf', 'usuario', 'created_at'])
                            ->where('usuario', $usuario);
            
                        if ($id) {
                            $query->where('id', $id); 
                        }
            
                        $data = $query->get();
                    }

                        
        
                return DataTables()->of($data)
                    ->addColumn('comprobante_urls', function ($row) {
                       $comprobantes = is_array($row->comprobante) ? $row->comprobante : json_decode($row->comprobante, true) ?? [];
                        $urls = [];
                        foreach ($comprobantes as $img) {
                            $urls[] = url(str_replace('public/', 'storage/', $img));
                        }
                        return $urls;
                    })
                    ->addColumn('orden_urls', function ($row) {
                        $ordenes = is_array($row->orden) ? $row->orden : json_decode($row->orden, true) ?? [];
                        $urls = [];
                        foreach ($ordenes as $img) {
                            $urls[] = url(str_replace('public/', 'storage/', $img));
                        }
                        return $urls;
                    })
                    ->addColumn('pdf_urls', function ($row) {
                        $pdfs = is_array($row->pdf) ? $row->pdf : json_decode($row->pdf, true) ?? [];
                        $urls = [];
                        foreach ($pdfs as $pdf) {
                            $urls[] = url(str_replace('public/', 'storage/', $pdf));
                        }
                        return $urls;
                    })
                    ->addColumn('acciones', function ($orden) {
                        return '
                            <button class="btn btn-danger btn-sm eliminar-orden" data-id="' . $orden->id . '">
                                <i class="fas fa-trash"></i>
                            </button>
                            <button class="btn btn-success btn-sm detalle-orden" data-id="' . $orden->id . '">
                                <i class="fas fa-eye"></i>
                            </button>

                        ';
                    })
                    ->rawColumns(['comprobante_urls', 'orden_urls', 'pdf_urls', 'acciones'])
                    ->make(true);
                 }else{
                     
                     $data = Comprobante::select(['id', 'codigo', 'comprobante', 'orden', 'pdf', 'usuario', 'created_at'])
                     
                ->where('usuario',$usuario)
                ->get();

                return DataTables()->of($data)
                    ->addColumn('comprobante_urls', function ($row) {
                        $comprobantes = json_decode($row->comprobante, true) ?? []; // Si es null, usa []
                        $urls = [];
                        foreach ($comprobantes as $img) {
                            $urls[] = url(str_replace('public/', 'storage/', $img));
                            
                        }
                        return $urls;
                    })
                    ->addColumn('orden_urls', function ($row) {
                        $ordenes = json_decode($row->orden, true) ?? []; // Si es null, usa []
                        $urls = [];
                        foreach ($ordenes as $img) {
                            $urls[] = url(str_replace('public/', 'storage/', $img));
                        }
                        return $urls;
                    })
                    ->addColumn('pdf_urls', function ($row) {
                        $pdfs = is_array($row->pdf) ? $row->pdf : json_decode($row->pdf, true) ?? [];
                        $urls = [];
                        foreach ($ordenes as $img) {
                            $urls[] = url(str_replace('public/', 'storage/', $img));
                        }
                        return $urls;
                    })
                    
                    ->addColumn('acciones', function ($orden) {
                        return '
                            <button class="btn btn-danger btn-sm eliminar-orden" data-id="' . $orden->id . '">
                                <i class="fas fa-trash"></i>
                            </button>
                            <button class="btn btn-success btn-sm detalle-orden" data-id="{{ $orden->id }}">
                                <i class="fas fa-eye"></i>
                            </button>
                        ';
                    })
                    ->rawColumns(['comprobante_urls', 'orden_urls', 'pdf_urls','acciones'])
                    ->make(true);
                     
                     
                     
                 }
                
            }
        
             return view('scann.detalleScann');
            }

    
        public function subirImagenes(Request $request) {
            
                    $request->validate([
                        'codigo' => 'required|string|unique:comprobantes,codigo',
                        'comprobante' => 'array|max:10',
                        'orden' => 'array|max:10',
                    ]);
                
                    $comprobanteFotos = [];
                    $ordenFotos = [];
                
                    foreach ($request->comprobante as $index => $fotoBase64) {
                        $nombre = time() . "_comprobante_{$index}.jpg";
                        $ruta = "public/comprobantes/" . $nombre;
                        Storage::put($ruta, base64_decode(explode(",", $fotoBase64)[1]));
                        $comprobanteFotos[] = "storage/comprobantes/" . $nombre;
                    }
                
                    foreach ($request->orden as $index => $fotoBase64) {
                        $nombre = time() . "_orden_{$index}.jpg";
                        $ruta = "public/ordenes/" . $nombre;
                        Storage::put($ruta, base64_decode(explode(",", $fotoBase64)[1]));
                        $ordenFotos[] = "storage/ordenes/" . $nombre;
                    }
                
                    Comprobante::create([
                        'codigo' => $request->codigo,
                        'comprobante' => json_encode($comprobanteFotos),
                        'orden' => json_encode($ordenFotos),
                        'usuario' => $request->usuario
                    ]);
                
                    return response()->json(['success' => true]);
                }
                
               
                public function destroy($id)
                    {
                         $orden = Comprobante::find($id);
    
                                if (!$orden) {
                                    return response()->json(['message' => 'Orden no encontrada'], 404);
                                }
                            
                                // Obtener las fotos del comprobante y de la orden
                                $comprobanteFotos = json_decode($orden->comprobante, true) ?? [];
                                $ordenFotos = json_decode($orden->orden, true) ?? [];
                            
                                // Eliminar fotos del comprobante
                                foreach ($comprobanteFotos as $foto) {
                                    $ruta = str_replace('storage/', 'public/comprobantes/', $foto); // Ajustar ruta para `Storage::delete`
                                    if (Storage::exists($ruta)) {
                                        Storage::delete($ruta);
                                    }
                                }
                            
                                // Eliminar fotos de la orden
                                foreach ($ordenFotos as $foto) {
                                    $ruta = str_replace('storage/', 'public/ordenes/', $foto);
                                    if (Storage::exists($ruta)) {
                                        Storage::delete($ruta);
                                    }
                                }
                            
                                // Eliminar la orden de la base de datos
                                $orden->delete();
                            
                                return response()->json(['message' => 'Orden y fotos eliminadas correctamente']);
                    }
    
    
    
}
