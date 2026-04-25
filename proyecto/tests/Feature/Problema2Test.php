<?php

namespace Tests\Feature;

use App\Models\Empleado;
use App\Models\Incidencia;
use App\Models\Cliente;
use App\Models\Provincia;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Batería de pruebas automatizadas para el Problema 2.
 * @author Álvaro Torroba Velasco
 * @version 1.0
 * @date 2026-04-26
 */
class Problema2Test extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function las_rutas_existentes_muestran_contenido(): void
    {
        $this->get('/login')->assertStatus(200);
        $this->get('/cliente/registro')->assertStatus(200);
        $this->get('/incidencias')->assertStatus(302);
        $this->get('/clientes')->assertStatus(302);
        $this->get('/empleados')->assertStatus(302);
    }

    #[Test]
    public function envio_y_procesado_del_formulario_de_incidencias(): void
    {
        // Creamos admin con TODOS los campos requeridos
        $admin = Empleado::create([
            'dni' => '12345678A',
            'nombre' => 'Admin Test',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'telefono' => '600000000',
            'tipo' => 'administrador',
            'fecha_alta' => now(),
        ]);

        // Añadimos el campo 'pais' que es obligatorio
        $cliente = Cliente::create([
            'cif' => 'B12345678',
            'nombre' => 'Cliente Test',
            'email' => 'cliente@test.com',
            'telefono' => '611111111',
            'pais' => 'España', // 👈 CAMPO OBLIGATORIO QUE FALTABA
        ]);

        $provincia = Provincia::create([
            'codigo_ine' => '28',
            'nombre' => 'Madrid',
        ]);

        $this->actingAs($admin);

        $response = $this->post(route('incidencias.store'), [
            'cliente_id' => $cliente->id,
            'persona_contacto' => 'Test Automático',
            'telefono_contacto' => '612345678',
            'email_contacto' => 'test@auto.com',
            'descripcion' => 'Verificación de envío y procesado correcto.',
            'provincia_codigo' => $provincia->codigo_ine,
            'estado' => 'P',
            'operario_id' => $admin->id,
        ]);

        $response->assertRedirect(route('incidencias.index'));
        $this->assertDatabaseHas('incidencias', [
            'persona_contacto' => 'Test Automático',
            'descripcion' => 'Verificación de envío y procesado correcto.'
        ]);
    }

    #[Test]
    public function el_procesado_valida_los_datos_y_rechaza_errores(): void
    {
        $admin = Empleado::create([
            'dni' => '87654321B',
            'nombre' => 'Admin Val',
            'email' => 'admin2@test.com',
            'password' => bcrypt('password'),
            'telefono' => '600000001',
            'tipo' => 'administrador',
            'fecha_alta' => now(),
        ]);
        $this->actingAs($admin);

        $response = $this->post(route('incidencias.store'), [
            'telefono_contacto' => 'LETRAS_INVALIDAS',
            'descripcion' => ''
        ]);

        $response->assertSessionHasErrors(['telefono_contacto', 'descripcion']);
        $this->assertDatabaseCount('incidencias', 0);
    }
}