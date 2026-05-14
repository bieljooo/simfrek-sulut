<?php

namespace Tests\Feature;

use Tests\TestCase;

class SisfrekApplicationTest extends TestCase
{
    public function test_home_page_renders_the_public_shell(): void
    {
        $response = $this->get('/');

        $response
            ->assertOk()
            ->assertSee('SIMFREK')
            ->assertSee('SULUT')
            ->assertSee('Sistem Informasi Monitoring Spektrum Frekuensi Sulut')
            ->assertSee('Balmon')
            ->assertSee('Manado')
            ->assertDontSee('Radar Monitor')
            ->assertDontSee('Spektrum Frekuensi Radio Sulawesi Utara');
    }

    public function test_login_page_is_accessible(): void
    {
        $response = $this->get('/login');

        $response
            ->assertOk()
            ->assertSee('Login Admin');
    }

    public function test_admin_can_log_in_and_reach_dashboard(): void
    {
        $response = $this->post('/login', [
            'login' => 'admin',
            'password' => 'admin123',
        ]);

        $response->assertRedirect('/admin');

        $dashboard = $this->get('/admin');
        $dashboard
            ->assertOk()
            ->assertSee('Dashboard');

        $dashboardRedirect = $this->get('/admin/dashboard');
        $dashboardRedirect->assertRedirect('/admin');

        $dataManagement = $this->get('/admin/kelola-data');
        $dataManagement
            ->assertOk()
            ->assertSee('Kelola Data');
    }

    public function test_statistics_api_returns_success_payload(): void
    {
        $response = $this->getJson('/api/spectrum/statistics');

        $response
            ->assertOk()
            ->assertJson([
                'success' => true,
            ])
            ->assertJsonStructure([
                'success',
                'data' => ['total', 'granted', 'denda', 'pre_elim', 'canceled', 'pre_cancel', 'by_service'],
            ]);
    }
}
