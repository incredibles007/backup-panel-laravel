<?php

namespace PavelMironchik\LaravelBackupPanel\Tests;

use Illuminate\Console\DetectsApplicationNamespace;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class InstallCommandTest extends TestCase
{
    use DetectsApplicationNamespace;

    public function test_install_command_publishes_assets()
    {
        $directory = public_path('vendor/laravel_backup_panel');

        $this->assertTrue(File::exists($directory.'/app.css'));
        $this->assertTrue(File::exists($directory.'/app.js'));
        $this->assertTrue(File::exists($directory.'/mix-manifest.json'));
    }

    public function test_install_command_publishes_config()
    {
        $this->assertTrue(File::exists(config_path('laravel_backup_panel.php')));
    }

    public function test_install_command_publishes_provider()
    {
        $this->assertTrue(File::exists(app_path('Providers/LaravelBackupPanelServiceProvider.php')));
    }

    public function test_install_command_sets_namespace_for_provider()
    {
        $namespace = Str::replaceLast('\\', '', $this->getAppNamespace());

        $provider = file_get_contents(app_path('Providers/LaravelBackupPanelServiceProvider.php'));

        $this->assertTrue(Str::contains(
            $provider,
            "namespace {$namespace}\Providers;"
        ));
    }

    public function test_install_command_registers_provider()
    {
        $namespace = Str::replaceLast('\\', '', $this->getAppNamespace());

        $appConfig = file_get_contents(config_path('app.php'));

        $this->assertTrue(Str::contains(
            $appConfig,
            "{$namespace}\\Providers\EventServiceProvider::class,".PHP_EOL."        {$namespace}\Providers\LaravelBackupPanelServiceProvider::class,".PHP_EOL
        ));
    }

    protected function setUp(): void
    {
        parent::setUp();

        Artisan::call('laravel-backup-panel:install');
    }

    protected function tearDown(): void
    {
        $this->clearFiles();

        parent::tearDown();
    }

    private function clearFiles()
    {
        // Clear assets.
        $path = public_path('vendor/laravel_backup_panel');
        if (File::exists($path)) {
            File::deleteDirectory($path);
        }

        // Clear config.
        $path = config_path('laravel_backup_panel.php');
        if (File::exists($path)) {
            File::delete($path);
        }

        // Clear provider.
        $path = app_path('Providers/LaravelBackupPanelServiceProvider.php');
        if (File::exists($path)) {
            File::delete($path);
        }

        // Reset providers.
        $namespace = Str::replaceLast('\\', '', $this->getAppNamespace());

        $appConfig = file_get_contents(config_path('app.php'));
        if (Str::contains($appConfig, $namespace.'\\Providers\\LaravelBackupPanelServiceProvider::class')) {
            file_put_contents(config_path('app.php'), str_replace(
                "{$namespace}\\Providers\EventServiceProvider::class,".PHP_EOL."        {$namespace}\Providers\LaravelBackupPanelServiceProvider::class,".PHP_EOL,
                "{$namespace}\\Providers\EventServiceProvider::class,".PHP_EOL,
                $appConfig
            ));
        }
    }
}
