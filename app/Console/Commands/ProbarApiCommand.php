<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SaldosApiService;

class ProbarApiCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'saldos:probar-api';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Probar conexión y diagnóstico de la API de saldos';

    private $saldosApiService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(SaldosApiService $saldosApiService)
    {
        parent::__construct();
        $this->saldosApiService = $saldosApiService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('🧪 Iniciando prueba de API de saldos...');
        $this->line('');

        try {
            $resultado = $this->saldosApiService->probarApi('console');

            if ($resultado['success']) {
                $this->info('✅ Prueba exitosa!');
                $this->line('');
                
                $diagnostico = $resultado['diagnostico'];
                
                $this->info('📊 Diagnóstico de la respuesta:');
                $this->line("Status Code: {$diagnostico['status_code']}");
                $this->line("Tamaño respuesta: {$diagnostico['body_length']} bytes");
                $this->line("Es JSON válido: " . ($diagnostico['is_json'] ? 'Sí' : 'No'));
                
                if (isset($diagnostico['json_keys'])) {
                    $this->line("Claves JSON: " . implode(', ', $diagnostico['json_keys']));
                }
                
                if (isset($diagnostico['data_count'])) {
                    $this->line("Registros encontrados: {$diagnostico['data_count']}");
                }
                
                if (isset($diagnostico['first_item_keys'])) {
                    $this->line("Campos por registro: " . implode(', ', $diagnostico['first_item_keys']));
                }
                
                if (isset($diagnostico['first_item_sample'])) {
                    $this->line('');
                    $this->info('📋 Muestra del primer registro:');
                    foreach ($diagnostico['first_item_sample'] as $campo => $valor) {
                        $this->line("  {$campo}: " . (is_string($valor) ? substr($valor, 0, 50) : $valor));
                    }
                }
                
                $this->line('');
                $this->info('💡 Revisa los logs de Laravel para más detalles técnicos.');
                
                return 0;
                
            } else {
                $this->error('❌ Error en la prueba: ' . $resultado['message']);
                return 1;
            }

        } catch (\Exception $e) {
            $this->error('❌ Error inesperado: ' . $e->getMessage());
            return 1;
        }
    }
}
