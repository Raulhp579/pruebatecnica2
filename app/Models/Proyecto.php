<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proyecto extends Model
{
    use HasFactory;
    protected $table = "proyectos";
    protected $primaryKey = 'id';

    protected $fillable = [
        'nombre'
    ];

    // Un proyecto tiene muchas tareas
    public function tareas()
    {
        return $this->hasMany(Tarea::class, 'proyecto_id');
    }
}
