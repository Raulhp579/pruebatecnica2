<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SideNav extends Model
{
    use HasFactory;
    protected $table ="side_nav";
    protected $primaryKey = 'id';
    protected $fillable = [
        'text',
        'url',
        'icon',
        'header',
        'id_html'
    ];
}
