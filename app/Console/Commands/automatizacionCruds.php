<?php

namespace App\Console\Commands;

use App\Models\SideNav;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class automatizacionCruds extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crud:crear {nombre} {url} {autenticado} {admin} {id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Este comando automatiza la creacion de CRUDS crea el controlador, model, vistas y migraciones';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $nombreModelo = $this->argument("nombre");
        $nombreRuta = $this->argument("url");

        $this->info("creando el crud y todos sus componentes para {$nombreModelo}...");

        $this->call("make:controller", [
            'name' => $nombreModelo . 'Controller',
            '--api' => true
        ]);

        $this->call('make:model', [
            'name' => $nombreModelo
        ]);

        $this->call('make:migration', [
            'name' => "create_{$nombreModelo}_table"
        ]);

        $this->call('make:view', [
            'name' => $nombreModelo
        ]);

        $this->info("creando las rutas...");


        if($this->argument("autenticado")&&$this->argument("autenticado")=='si'&&$this->argument("admin")&&$this->argument("admin")=='si'){
            $rutaApi = "\nRoute::apiResource('{$nombreRuta}', App\Http\Controllers\\{$nombreModelo}Controller::class)->middleware('auth:sanctum',isAdminMiddleware::class);";
        }else if($this->argument("autenticado") &&$this->argument("autenticado")=='si'){
            $rutaApi = "\nRoute::apiResource('{$nombreRuta}', App\Http\Controllers\\{$nombreModelo}Controller::class)->middleware('auth:sanctum');";
        }else{
            $rutaApi = "\nRoute::apiResource('{$nombreRuta}', App\Http\Controllers\\{$nombreModelo}Controller::class);";
        }

        $rutaWeb = "\nRoute::resource('{$nombreRuta}', App\Http\Controllers\\{$nombreModelo}Controller::class);";

        File::append(base_path('routes/web.php'), $rutaWeb);
        File::append(base_path('routes/api.php'), $rutaApi);


        $this->info("creando elemento en el sideNav...");
        if ($this->argument("id")) {
            SideNav::create([
                'text' => $nombreModelo,
                'url' => $this->argument("url"),
                'id_html' => $this->argument("id"),
            ]);
        } else {
            SideNav::create([
                'text' => $nombreModelo,
                'url' => $this->argument("url"),
            ]);
        }



        $this->info("CRUD creado exitosamente");
        return Command::SUCCESS;
    }
}
