<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permiso extends Model
{
    protected $table = 'permisos';

    protected $primaryKey = 'id';

    protected $fillable = [
        'tipo_permiso',
    ];

    public function usuarios()
    {
        return $this->hasMany(User_Permiso::class, 'id_permiso', 'id');
    }

    public function roles()
    {
        return $this->hasMany(Rol_Permiso::class, 'id_permiso', 'id');
    }
}
