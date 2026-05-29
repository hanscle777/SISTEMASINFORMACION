<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comision extends Model
{
    use HasFactory;

    protected $table = 'comisiones';

    protected $fillable = [
        'estilista_id',
        'cita_id',
        'monto_servicio',
        'porcentaje_comision',
        'monto_comision',
        'estado',
        'fecha_calculo'
    ];

    protected $casts = [
        'fecha_calculo' => 'datetime',
        'monto_servicio' => 'decimal:2',
        'porcentaje_comision' => 'decimal:2',
        'monto_comision' => 'decimal:2',
    ];

    public function estilista()
    {
        return $this->belongsTo(User::class, 'estilista_id');
    }

    public function cita()
    {
        return $this->belongsTo(Cita::class);
    }
}
