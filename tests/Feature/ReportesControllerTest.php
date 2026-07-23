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

class ReportesControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_reportes_module_is_accessible_by_user_with_view_reports_permission()
    {
        $permission = Permission::create([
            'name' => 'Ver Reportes',
            'slug' => 'view_reports',
        ]);

        $role = Role::create([
            'name' => 'Supervisor',
            'slug' => 'supervisor',
        ]);
        $role->permissions()->sync([$permission->id]);

        $user = User::create([
            'nombre' => 'Miguel',
            'apellido' => 'Ruiz',
            'name' => 'Miguel Ruiz',
            'email' => 'miguel@example.com',
            'password' => bcrypt('password123'),
            'role_id' => $role->id,
        ]);

        $producto = Producto::create([
            'codigo' => 'REP-001',
            'nombre' => 'Sérum',
            'stock' => 10,
            'stock_minimo' => 2,
            'precio_compra' => 25.00,
            'precio_venta' => 50.00,
        ]);

        $venta = Venta::create([
            'cliente_nombre' => 'Cliente Casual',
            'vendedor_id' => $user->id,
            'subtotal' => 100.00,
            'descuento' => 0.00,
            'total' => 100.00,
            'metodo_pago' => 'efectivo',
            'estado_pago' => 'completado',
            'fecha_venta' => now(),
        ]);

        $venta->detalles()->create([
            'producto_id' => $producto->id,
            'cantidad' => 2,
            'precio_unitario' => 50.00,
            'descuento' => 0.00,
            'subtotal' => 100.00,
        ]);

        $response = $this->actingAs($user)->get(route('reportes.index'));

        $response->assertOk();
        $response->assertSee('Módulo de Reportes');
        $response->assertSee('Bs. 100.00');
        $response->assertSee('Bs. 50.00');
    }
}
