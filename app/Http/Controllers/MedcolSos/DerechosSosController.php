<?php

namespace App\Http\Controllers\MedcolSos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use SimpleXMLElement;


class DerechosSosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * 
     */
     
     public function index()
    {
        return view('menu.MedcolSos.indexValidacionDerechos');
    }

        public function consultarAfiliado(Request $request)
        {
            $validated = $request->validate([
                'tipoDocId' => 'required|string',
                'numeroDocId' => 'required|string',
                'plan' => 'required|string',
            ]);
            
            $username = "UD949121152";
            $password = "Wm2024*152";
            $ServicioWebSoapValidacioDerechos = "https://centralaplicaciones.sos.com.co/ValidadorService3/services/ConsultaValidadorWebService?wsdl";
            $login = "UD949121152";
            
            $client = new Client();
            
            try {
                $response = $client->post($ServicioWebSoapValidacioDerechos, [
                    'auth' => [$username, $password],
                    'headers' => [
                        'Content-Type' => 'text/xml; charset=utf-8',
                        'SOAPAction' => 'http://consulta.validador.ws.sos/getConsultaAfiliado',
                    ],
                    'body' => $this->createSoapEnvelope($validated, $login),
                    'http_errors' => false
                ]);
                
                $statusCode = $response->getStatusCode();
                $body = $response->getBody()->getContents();
                
                if ($statusCode != 200) {
                    \Log::error("SOAP request failed. Status code: " . $statusCode . ". Response body: " . $body);
                    return response()->json(['status' => 'error', 'message' => 'SOAP request failed'], $statusCode);
                }
        
                // Procesar la respuesta XML y convertirla a JSON
                $result = $this->parseXmlResponse($body);
                
                return response()->json([
                    'status' => 'success',
                    'data' => $result,
                ]);
            } catch (\Exception $e) {
                \Log::error("Exception occurred: " . $e->getMessage());
                return response()->json(['status' => 'error', 'message' => 'An error occurred while processing your request: ' . $e->getMessage()], 500);
            }
        }
    
    private function createSoapEnvelope($validated, $login)
    {
        return '<?xml version="1.0" encoding="UTF-8"?>
        <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:con="http://consulta.validador.ws.sos">
            <soapenv:Header/>
            <soapenv:Body>
                <con:getConsultaAfiliado>
                    <tipoDocId>'.$validated['tipoDocId'].'</tipoDocId>
                    <numeroDocId>'.$validated['numeroDocId'].'</numeroDocId>
                    <plan>'.$validated['plan'].'</plan>
                    <loginUsuario>'.$login.'</loginUsuario>
                </con:getConsultaAfiliado>
            </soapenv:Body>
        </soapenv:Envelope>';
    }
    
    private function parseXmlResponse($xmlString)
    {
        try {
            // Convertir la respuesta XML en un objeto SimpleXMLElement
            $xml = new \SimpleXMLElement($xmlString, LIBXML_NOCDATA);
            
           // Registrar el namespace si es necesario
            $namespaces = $xml->getNamespaces(true);
            foreach ($namespaces as $prefix => $ns) {
                $xml->registerXPathNamespace($prefix, $ns);
            }
    
           // Navegar hasta el nodo específico, ignorando el sobre SOAP
            $afiliadoNodes = $xml->xpath('//getConsultaAfiliadoReturn');
            if (empty($afiliadoNodes)) {
                throw new \Exception("No se encontró el nodo <afiliado> en la respuesta.");
            }
            $afiliado = $afiliadoNodes[0];
    
            // Convertir el objeto XML en un array asociativo
            $resultArray = json_decode(json_encode($afiliado), true);
    
            return $resultArray;
        } catch (\Exception $e) {
            \Log::error("Failed to parse XML: " . $e->getMessage());
            throw new \Exception("Failed to parse XML: " . $e->getMessage());
        }
    }  
            
       


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
