<?php

namespace PavelMironchik\LaravelBackupPanel\Tests;

use Illuminate\Foundation\Application;
use PavelMironchik\LaravelBackupPanel\LaravelBackupPanel;

class InterfaceTest extends TestCase
{
    public function test_panel_is_served_at_configured_path()
    {
        $this->get('/backup')
            ->assertOk();
    }

    public function test_home_view_is_served()
    {
        $this->get('/backup')
            ->assertViewIs('laravel_backup_panel::layout');
    }

    public function test_home_view_gets_global_variables()
    {
        $this->get('/backup')
            ->assertViewHas('globalVariables', [
                'path' => 'backup',
                'queue' => null,
                'assetsAreCurrent' => true,
            ]);
    }

    /**
     * Define environment setup.
     *
     * @param  Application  $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('app.key', 'base64:GhFMLyZ7x32kzu0How7wF8CIei+UC9Lc69Jcr+Z3sAk=');
    }

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        LaravelBackupPanel::auth(function ($request) {
            return true;
        });

        $this->app->instance('path.public', __DIR__.'/../../public');
    }

    protected function tearDown(): void
    {
        LaravelBackupPanel::$authUsing = null;

        parent::tearDown();
    }
}
