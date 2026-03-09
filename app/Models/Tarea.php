<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Tarea extends Model
{
    protected $table = "tareas";
    protected $primaryKey = 'id';

    protected $fillable = [
        'descripcion',
        'tiempo_inicio',
        'tiempo_fin',
        'proyecto_id',
        'id_user'
    ];

    // Relación: Una tarea pertenece a un proyecto
    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class, 'proyecto_id');
    }

    // Relación: Una tarea pertenece a un usuario
    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
