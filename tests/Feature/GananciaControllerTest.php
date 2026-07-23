<?php

namespace Tests\Feature;

use App\Models\Permission;
use App\Models\Producto;
use App\Models\Role;
use App\Models\User;
use App\Models\Venta;
use App\Models\VentaDetalle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GananciaControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_sales_report_displays_profit_and_margin()
    {
        $salesPermission = Permission::create([
            'name' => 'Gestionar Ventas',
            'slug' => 'manage_sales',
        ]);

        $role = Role::create([
            'name' => 'Recepcionista',
            'slug' => 'recepcionista',
        ]);
        $role->permissions()->sync([$salesPermission->id]);

        $user = User::create([
            'nombre' => 'Laura',
            'apellido' => 'Soria',
            'name' => 'Laura Soria',
            'email' => 'laura@example.com',
            'password' => bcrypt('password123'),
            'role_id' => $role->id,
        ]);

        $producto = Producto::create([
            'codigo' => 'GAN-001',
            'nombre' => 'Aceite Capilar',
            'stock' => 20,
            'stock_minimo' => 5,
            'precio_compra' => 20.00,
            'precio_venta' => 40.00,
        ]);

        $venta = Venta::create([
            'cliente_nombre' => 'Cliente Casual',
            'vendedor_id' => $user->id,
            'subtotal' => 80.00,
            'descuento' => 0.00,
            'total' => 80.00,
            'metodo_pago' => 'efectivo',
            'estado_pago' => 'completado',
            'fecha_venta' => now(),
        ]);

        $venta->detalles()->create([
            'producto_id' => $producto->id,
            'cantidad' => 2,
            'precio_unitario' => 40.00,
            'descuento' => 0.00,
            'subtotal' => 80.00,
        ]);

        $response = $this->actingAs($user)->get(route('ganancias.index'));

        $response->assertOk();
        $response->assertSee('Ganancias');
        $response->assertSee('80.00');
        $response->assertSee('40.00');
        $response->assertSee('50.00%');
    }
}
