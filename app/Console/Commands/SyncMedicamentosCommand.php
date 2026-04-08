<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Http\Controllers\Compras\Medcol3\ControllerMedcol3;

class SyncMedicamentosCommand extends Command
{
    protected $signature = 'medicamentos:sync';
    protected $description = 'Sincroniza medicamentos con la API';

    public function handle()
    {
        try {
            $controller = new ControllerMedcol3();
            $request = new Request();
            
            $result = $controller->createmedicamentosapi($request, 'sistema_cron@medcol3.com');
            
            // Verificar si es una respuesta JSON con errores
            if ($result instanceof \Illuminate\Http\JsonResponse) {
                $data = $result->getData(true);
                
                foreach ($data as $mensaje) {
                    if (isset($mensaje['icon']) && $mensaje['icon'] === 'error') {
                        $this->error($mensaje['titulo'] . ': ' . $mensaje['respuesta']);
                        Log::error('Sync medicamentos: ' . $mensaje['respuesta']);
                        return 1;
                    } else {
                        $this->info($mensaje['titulo'] . ': ' . $mensaje['respuesta']);
                        Log::info('Sync medicamentos: ' . $mensaje['respuesta']);
                    }
                }
            }
            
            $this->info('Sincronización completada exitosamente');
            Log::info('Medicamentos sincronizados: ' . now());
            
            return 0;
            
        } catch (\Exception $e) {
            $this->error('Error en sincronización: ' . $e->getMessage());
            Log::error('Error sync medicamentos: ' . $e->getMessage());
            
            return 1;
        }
    }
}