<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Producto;
use App\Models\Venta;
use Mockery;
use PHPUnit\Framework\Attributes\RunInSeparateProcess;
use PHPUnit\Framework\Attributes\PreserveGlobalState;

class VentaStripeTest extends TestCase
{
    use RefreshDatabase;

    protected $recepcionistaUser;
    protected $producto;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Create the permission and role
        $manageSalesPermission = Permission::create([
            'name' => 'Gestionar Ventas',
            'slug' => 'manage_sales',
        ]);

        $role = Role::create([
            'name' => 'Recepcionista',
            'slug' => 'recepcionista',
        ]);
        $role->permissions()->sync([$manageSalesPermission->id]);

        // 2. Create the user and assign role
        $this->recepcionistaUser = User::create([
            'nombre' => 'María',
            'apellido' => 'Gómez',
            'name' => 'María Gómez',
            'email' => 'maria@anita.com',
            'password' => bcrypt('password123'),
            'role_id' => $role->id,
        ]);

        // 3. Create a product with stock
        $this->producto = Producto::create([
            'codigo' => 'PROD-001',
            'nombre' => 'Champú Anti-caída',
            'stock' => 10,
            'stock_minimo' => 2,
            'precio_compra' => 30.00,
            'precio_venta' => 50.00,
        ]);
    }

    #[RunInSeparateProcess]
    #[PreserveGlobalState(false)]
    public function test_stripe_payment_method_creates_pending_sale_and_redirects_to_stripe()
    {
        // Mock Stripe Checkout Session creation
        $sessionMock = Mockery::mock('alias:Stripe\Checkout\Session');
        $sessionMock->shouldReceive('create')
            ->once()
            ->andReturn((object)[
                'id' => 'cs_test_123',
                'url' => 'https://checkout.stripe.com/pay/cs_test_123'
            ]);

        $stripeMock = Mockery::mock('alias:Stripe\Stripe');
        $stripeMock->shouldReceive('setApiKey')->once();

        $response = $this->actingAs($this->recepcionistaUser)
            ->post(route('ventas.store'), [
                'cliente_nombre' => 'Cliente Casual',
                'metodo_pago' => 'stripe',
                'items' => [
                    [
                        'producto_id' => $this->producto->id,
                        'cantidad' => 2,
                    ]
                ]
            ]);

        // Verify redirection to Stripe
        $response->assertRedirect('https://checkout.stripe.com/pay/cs_test_123');

        // Verify sale was created in 'pendiente' state
        $venta = Venta::first();
        $this->assertNotNull($venta);
        $this->assertEquals('pendiente', $venta->estado_pago);
        $this->assertEquals('cs_test_123', $venta->stripe_session_id);
        
        // Verify stock was NOT decremented
        $this->producto->refresh();
        $this->assertEquals(10, $this->producto->stock);
    }

    #[RunInSeparateProcess]
    #[PreserveGlobalState(false)]
    public function test_stripe_success_verifies_payment_decrements_stock_and_completes_sale()
    {
        // Pre-create pending sale
        $venta = Venta::create([
            'cliente_nombre' => 'Cliente Casual',
            'vendedor_id' => $this->recepcionistaUser->id,
            'subtotal' => 100.00,
            'descuento' => 0.00,
            'total' => 100.00,
            'metodo_pago' => 'stripe',
            'estado_pago' => 'pendiente',
            'stripe_session_id' => 'cs_test_123',
            'fecha_venta' => now(),
        ]);

        $venta->detalles()->create([
            'producto_id' => $this->producto->id,
            'cantidad' => 2,
            'precio_unitario' => 50.00,
            'descuento' => 0.00,
            'subtotal' => 100.00,
        ]);

        // Mock Stripe Checkout Session retrieve
        $sessionMock = Mockery::mock('alias:Stripe\Checkout\Session');
        $sessionMock->shouldReceive('retrieve')
            ->once()
            ->with('cs_test_123')
            ->andReturn((object)[
                'payment_status' => 'paid'
            ]);

        $stripeMock = Mockery::mock('alias:Stripe\Stripe');
        $stripeMock->shouldReceive('setApiKey')->once();

        $response = $this->actingAs($this->recepcionistaUser)
            ->get(route('ventas.stripe.success', $venta->id));

        $response->assertRedirect(route('ventas.show', $venta->id));
        $response->assertSessionHas('success', 'Venta pagada y registrada exitosamente.');

        // Verify sale is now completed
        $venta->refresh();
        $this->assertEquals('completado', $venta->estado_pago);

        // Verify stock WAS decremented
        $this->producto->refresh();
        $this->assertEquals(8, $this->producto->stock);
    }

    #[RunInSeparateProcess]
    #[PreserveGlobalState(false)]
    public function test_stripe_cancel_marks_sale_as_cancelled()
    {
        // Pre-create pending sale
        $venta = Venta::create([
            'cliente_nombre' => 'Cliente Casual',
            'vendedor_id' => $this->recepcionistaUser->id,
            'subtotal' => 100.00,
            'descuento' => 0.00,
            'total' => 100.00,
            'metodo_pago' => 'stripe',
            'estado_pago' => 'pendiente',
            'stripe_session_id' => 'cs_test_123',
            'fecha_venta' => now(),
        ]);

        $response = $this->actingAs($this->recepcionistaUser)
            ->get(route('ventas.stripe.cancel', $venta->id));

        $response->assertRedirect(route('ventas.index'));
        $response->assertSessionHas('warning', 'El pago de la venta fue cancelado por el usuario.');

        // Verify sale is marked as cancelado
        $venta->refresh();
        $this->assertEquals('cancelado', $venta->estado_pago);
    }

    public function test_admin_can_manually_mark_pending_sale_as_completed()
    {
        // Pre-create pending sale
        $venta = Venta::create([
            'cliente_nombre' => 'Cliente Casual',
            'vendedor_id' => $this->recepcionistaUser->id,
            'subtotal' => 100.00,
            'descuento' => 0.00,
            'total' => 100.00,
            'metodo_pago' => 'stripe',
            'estado_pago' => 'pendiente',
            'stripe_session_id' => 'cs_test_123',
            'fecha_venta' => now(),
        ]);

        $venta->detalles()->create([
            'producto_id' => $this->producto->id,
            'cantidad' => 3,
            'precio_unitario' => 50.00,
            'descuento' => 0.00,
            'subtotal' => 100.00,
        ]);

        $response = $this->actingAs($this->recepcionistaUser)
            ->post(route('ventas.update-status', $venta->id), [
                'estado_pago' => 'completado'
            ]);

        $response->assertStatus(302);
        $venta->refresh();
        $this->assertEquals('completado', $venta->estado_pago);

        // Verify stock decremented
        $this->producto->refresh();
        $this->assertEquals(7, $this->producto->stock);
    }

    public function test_admin_can_manually_mark_pending_sale_as_cancelled()
    {
        // Pre-create pending sale
        $venta = Venta::create([
            'cliente_nombre' => 'Cliente Casual',
            'vendedor_id' => $this->recepcionistaUser->id,
            'subtotal' => 100.00,
            'descuento' => 0.00,
            'total' => 100.00,
            'metodo_pago' => 'stripe',
            'estado_pago' => 'pendiente',
            'stripe_session_id' => 'cs_test_123',
            'fecha_venta' => now(),
        ]);

        $venta->detalles()->create([
            'producto_id' => $this->producto->id,
            'cantidad' => 3,
            'precio_unitario' => 50.00,
            'descuento' => 0.00,
            'subtotal' => 100.00,
        ]);

        $response = $this->actingAs($this->recepcionistaUser)
            ->post(route('ventas.update-status', $venta->id), [
                'estado_pago' => 'cancelado'
            ]);

        $response->assertStatus(302);
        $venta->refresh();
        $this->assertEquals('cancelado', $venta->estado_pago);

        // Verify stock NOT decremented
        $this->producto->refresh();
        $this->assertEquals(10, $this->producto->stock);
    }

    public function test_non_authorized_user_cannot_manually_update_payment_status()
    {
        // Pre-create pending sale
        $venta = Venta::create([
            'cliente_nombre' => 'Cliente Casual',
            'vendedor_id' => $this->recepcionistaUser->id,
            'subtotal' => 100.00,
            'descuento' => 0.00,
            'total' => 100.00,
            'metodo_pago' => 'stripe',
            'estado_pago' => 'pendiente',
            'stripe_session_id' => 'cs_test_123',
            'fecha_venta' => now(),
        ]);

        $regularUser = User::create([
            'nombre' => 'Juan',
            'apellido' => 'Perez',
            'name' => 'Juan Perez',
            'email' => 'juan@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->actingAs($regularUser)
            ->post(route('ventas.update-status', $venta->id), [
                'estado_pago' => 'completado'
            ]);

        $response->assertStatus(403);
        $venta->refresh();
        $this->assertEquals('pendiente', $venta->estado_pago);
    }

    public function test_cannot_update_payment_status_if_already_completed()
    {
        // Pre-create completed sale
        $venta = Venta::create([
            'cliente_nombre' => 'Cliente Casual',
            'vendedor_id' => $this->recepcionistaUser->id,
            'subtotal' => 100.00,
            'descuento' => 0.00,
            'total' => 100.00,
            'metodo_pago' => 'stripe',
            'estado_pago' => 'completado',
            'stripe_session_id' => 'cs_test_123',
            'fecha_venta' => now(),
        ]);

        $response = $this->actingAs($this->recepcionistaUser)
            ->post(route('ventas.update-status', $venta->id), [
                'estado_pago' => 'cancelado'
            ]);

        $response->assertStatus(302);
        $venta->refresh();
        $this->assertEquals('completado', $venta->estado_pago);
    }
}

