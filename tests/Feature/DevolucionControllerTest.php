<?php

namespace Tests\Feature;

use App\Models\Permission;
use App\Models\Producto;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DevolucionControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_with_inventory_permission_can_register_a_product_return_and_increase_stock()
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
            'nombre' => 'Ana',
            'apellido' => 'Pérez',
            'name' => 'Ana Pérez',
            'email' => 'ana@example.com',
            'password' => bcrypt('password123'),
            'role_id' => $role->id,
        ]);

        $producto = Producto::create([
            'codigo' => 'DEV-001',
            'nombre' => 'Gel',
            'stock' => 8,
            'stock_minimo' => 2,
            'precio_compra' => 10.00,
            'precio_venta' => 15.00,
        ]);

        $response = $this->actingAs($user)
            ->post(route('devoluciones.store'), [
                'producto_id' => $producto->id,
                'cantidad' => 3,
                'motivo' => 'Producto defectuoso',
                'fecha_devolucion' => '2026-07-23',
            ]);

        $response->assertRedirect(route('devoluciones.index'));
        $response->assertSessionHas('success', 'Devolución registrada exitosamente.');

        $this->assertDatabaseHas('devoluciones', [
            'producto_id' => $producto->id,
            'cantidad' => 3,
            'motivo' => 'Producto defectuoso',
            'usuario_id' => $user->id,
        ]);

        $producto->refresh();
        $this->assertEquals(11, $producto->stock);
    }
}
