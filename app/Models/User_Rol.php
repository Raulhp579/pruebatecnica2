<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User_Rol extends Model
{
    use HasFactory;

    protected $table = 'user_rol';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id_user',
        'id_rol',
    ];

    public function user(){
        return $this->belongsTo(User::class, "id_user","id");
    }

    public function rol(){
        require $this->belongsTo(Rol::class, "rol_id", "id");
    }
}
