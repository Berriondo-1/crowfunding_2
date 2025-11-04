<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'roles';
    protected $fillable = ['nombre_rol'];

    public function users()
    {
        return $this->belongsToMany(\App\Models\User::class, 'usuario_rol', 'rol_id', 'user_id');
    }
}
