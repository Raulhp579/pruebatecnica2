<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Process;

class iniciar extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:iniciar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Iniciando servidor multicanal...');

        $servicioPhp = Process::timeout(0)->start('php artisan serve');
        $reverb = Process::timeout(0)->start('php artisan reverb:start');
        /* $queue  = Process::timeout(0)->start('php artisan queue:work'); */
        $vite   = Process::timeout(0)->start('npm run dev');

        $this->info('Servicios corriendo...');

        while ($reverb->running() && $servicioPhp->running() /* && $queue->running() */) {
            sleep(1);
        }
    }
}
