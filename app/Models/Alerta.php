<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alerta extends Model
{
    use HasFactory;

    protected $table = 'alertas';

    protected $fillable = [
        'tipo',
        'mensaje',
        'leido',
        'producto_id'
    ];

    protected $casts = [
        'leido' => 'boolean'
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
