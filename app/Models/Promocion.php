<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Promocion extends Model
{
    use HasFactory;

    protected $table = 'promociones';

    protected $fillable = [
        'nombre',
        'descripcion',
        'descuento_porcentaje',
        'fecha_inicio',
        'fecha_fin',
        'activo',
        'servicio_id',
        'producto_id'
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'activo' => 'boolean',
        'descuento_porcentaje' => 'decimal:2',
    ];

    public function servicio()
    {
        return $this->belongsTo(Servicio::class);
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    /**
     * Scope to only include active promotions on a given date.
     */
    public function scopeVigente($query, $date = null)
    {
        $date = $date ? Carbon::parse($date) : Carbon::today();
        return $query->where('activo', true)
                     ->whereDate('fecha_inicio', '<=', $date)
                     ->whereDate('fecha_fin', '>=', $date);
    }

    /**
     * Check if the promotion is currently active and valid.
     */
    public function getEstaVigenteAttribute(): bool
    {
        $today = Carbon::today();
        return $this->activo && $this->fecha_inicio->lte($today) && $this->fecha_fin->gte($today);
    }
}
