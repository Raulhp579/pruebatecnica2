<?php

namespace App\Providers;

use App\Models\SideNav;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;
use Illuminate\Support\Facades\Event;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);



        Event::listen(BuildingMenu::class,function(BuildingMenu $event){
            $opciones = SideNav::all();

            foreach ($opciones as $opcion) {
                $item = [
                    "text" => $opcion->text,
                    "url" => $opcion->url,
                    "header" => $opcion->header,
                    "icon" => $opcion->icon,
                    "id" => $opcion->id_html
                ];

                $event->menu->add($item);
            }

        });



    }

}
