<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotor extends Model
{
    use HasFactory;

    protected $table = 'promotores';

    protected $fillable = [
        'nombre',
        'telefono',
        'email',
        'empresa',
        'notas',
    ];

    public function productos()
    {
        return $this->hasMany(Producto::class);
    }
}
