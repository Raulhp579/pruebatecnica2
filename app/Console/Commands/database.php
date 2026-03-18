<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class database extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:database';

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
        $this->info('borrando datos de la base de datos');
        $this->call('db:wipe');
        $this->info('cargando migraciones...');
        $this->call('migrate');
        $this->info('cargando seeders...');
        $this->call('db:seed');
        $this->info('migraciones y seeders cargados correctamente');
    }
}
