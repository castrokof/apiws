<?php

namespace App\Http\Controllers\MedcolSos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use SimpleXMLElement;

class OrdenesSosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('menu.MedcolSos.indexFormulasSos');
    }

    public function formulasAfiliado(Request $request)
        {
            $validated = $request->validate([
                'tipoIdentificacion' => 'required|string',
                'numeroIdentificacion' => 'required|string'
                  ]);
            
            $tipoIdentificacion = $request->tipoIdentificacion;
            $numeroIdentificacion = $request->numeroIdentificacion;
            
            $username = "UD949121152";
            $password = "Wm2024*152";
            $ServicioWebRestFormulacionSos = "https://centralaplicaciones.sos.com.co/ServiciosExternosSaludRESTWeb/rest/FormulacionWS/consultar";
           
            
            $client = new Client();
            
            
            
        try {
            // Envía la solicitud POST usando Guzzle
            $response = $client->request('POST', $ServicioWebRestFormulacionSos, [
                'auth' => [$username, $password], // Autenticación básica
                'json' => [
                    'tipoIdentificacion' => $tipoIdentificacion,
                    'numeroIdentificacion' => $numeroIdentificacion,
                ],
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
            ]);

            // Decodifica la respuesta JSON
            $data = json_decode($response->getBody()->getContents(), true);

            // Pasa los datos a la vista
          return response()->json([
                    'status' => 'success',
                    'data' => $data,
                ]);

        } catch (\Exception $e) {
          \Log::error("Exception occurred: " . $e->getMessage());
                return response()->json(['status' => 'error', 'message' => 'An error occurred while processing your request: ' . $e->getMessage()], 500);
         }
        
        }
   
    
    }
