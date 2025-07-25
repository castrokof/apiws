<?php

namespace App\Console\Commands;

use App\Services\SaldosApiService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SincronizarSaldosCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'saldos:sincronizar 
                            {--force : Forzar sincronización sin confirmación}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sincronizar saldos de medicamentos desde la API externa';

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
        $this->info('🔄 Iniciando sincronización de saldos de medicamentos...');
        
        // Confirmar ejecución si no se usa --force
        if (!$this->option('force')) {
            if (!$this->confirm('¿Desea continuar con la sincronización de saldos?')) {
                $this->info('Sincronización cancelada por el usuario.');
                return 0;
            }
        }

        try {
            // Mostrar progreso
            $progressBar = $this->output->createProgressBar(3);
            $progressBar->setFormat(' %current%/%max% [%bar%] %percent:3s%% %message%');
            $progressBar->setMessage('Iniciando...');
            $progressBar->start();
            
            $progressBar->setMessage('Conectando con la API...');
            $progressBar->advance();

            // Ejecutar sincronización
            $resultado = $this->saldosApiService->sincronizar('artisan-command');
            
            $progressBar->setMessage('Procesando datos...');
            $progressBar->advance();

            if ($resultado['success']) {
                $progressBar->setMessage('Completado exitosamente');
                $progressBar->finish();
                $this->line('');
                $this->line('');
                
                $this->info('✅ ' . $resultado['message']);
                
                if (isset($resultado['data'])) {
                    $data = $resultado['data'];
                    $this->table(['Métrica', 'Valor'], [
                        ['Registros procesados', number_format($data['registros_procesados'])],
                        ['Errores', $data['errores']],
                        ['Fecha sincronización', $data['fecha_sincronizacion']]
                    ]);
                }

                return 0;

            } else {
                $progressBar->setMessage('Error en sincronización');
                $progressBar->finish();
                $this->line('');
                $this->line('');
                
                $this->error('❌ Error en la sincronización: ' . $resultado['message']);
                return 1;
            }

        } catch (\Exception $e) {
            $this->error('❌ Error crítico: ' . $e->getMessage());
            
            Log::error('Error en comando de sincronización de saldos', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return 1;
        }
    }
}