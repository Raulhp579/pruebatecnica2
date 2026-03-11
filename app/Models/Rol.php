<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    use HasFactory;
    protected $table = "rol";
    protected $primaryKey = 'id';

    protected $fillable = [
        "nombre",
        "descripcion"
    ];

    public function users()
    {
        return $this->hasMany(User_Rol::class, "id_rol", "id");
    }
}
