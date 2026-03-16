<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User_Permiso extends Model
{
    protected $table = 'user_permisos';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id_user',
        'id_permiso',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    public function permiso()
    {
        return $this->belongsTo(Permiso::class, 'id_permiso', 'id');
    }
}
