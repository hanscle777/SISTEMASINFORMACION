<?php

namespace Tests\Feature;

use App\Models\Permission;
use App\Models\Producto;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CompraControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_with_inventory_permission_can_create_a_purchase_and_increase_stock()
    {
        $inventoryPermission = Permission::create([
            'name' => 'Gestionar Inventario',
            'slug' => 'manage_inventory',
        ]);

        $role = Role::create([
            'name' => 'Administrador',
            'slug' => 'admin',
        ]);
        $role->permissions()->sync([$inventoryPermission->id]);

        $user = User::create([
            'nombre' => 'Carlos',
            'apellido' => 'Mendoza',
            'name' => 'Carlos Mendoza',
            'email' => 'carlos@example.com',
            'password' => bcrypt('password123'),
            'role_id' => $role->id,
        ]);

        $producto = Producto::create([
            'codigo' => 'CMP-001',
            'nombre' => 'Shampoo',
            'stock' => 10,
            'stock_minimo' => 2,
            'precio_compra' => 15.00,
            'precio_venta' => 20.00,
        ]);

        $response = $this->actingAs($user)
            ->post(route('compras.store'), [
                'proveedor' => 'Distribuidora Bella',
                'fecha_compra' => '2026-07-23',
                'items' => [[
                    'producto_id' => $producto->id,
                    'cantidad' => 5,
                    'precio_unitario' => 12.50,
                ]],
            ]);

        $response->assertRedirect(route('compras.index'));
        $response->assertSessionHas('success', 'Compra registrada exitosamente.');

        $this->assertDatabaseHas('compras', [
            'proveedor' => 'Distribuidora Bella',
            'usuario_id' => $user->id,
        ]);

        $this->assertDatabaseHas('compra_detalles', [
            'producto_id' => $producto->id,
            'cantidad' => 5,
            'precio_unitario' => 12.50,
        ]);

        $producto->refresh();
        $this->assertEquals(15, $producto->stock);
    }
}
