<?php

namespace Nikservik\AdminNotifications\Tests;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Lorisleiva\Actions\ActionServiceProvider;
use Nikservik\AdminDashboard\AdminDashboardServiceProvider;
use Nikservik\Users\UsersServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Nikservik\AdminNotifications\AdminNotificationsServiceProvider;

class TestCase extends Orchestra
{

    protected User $user;
    protected User $admin;

    public function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Database\\Factories\\'.class_basename($modelName).'Factory'
        );

        $this->user = User::factory()->create();
        $this->admin = User::factory()->create(['admin_role' => 4]);
    }

    protected function getPackageProviders($app): array
    {
        return [
            AdminNotificationsServiceProvider::class,
            UsersServiceProvider::class,
            AdminDashboardServiceProvider::class,
            ActionServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'mysql');
        $app['config']->set('app.fallback_locale', 'ru');
        $app['config']->set('simple-support.features', []);
    }

    protected function defineDatabaseMigrations()
    {
        $this->loadLaravelMigrations();
        $this->loadMigrationsFrom(__DIR__ . '/../vendor/nikservik/users/database/migrations');
        $this->loadMigrationsFrom(__DIR__ . '/../vendor/nikservik/simple-support/database/migrations');
    }

    protected function getBasePath(): string
    {
        return __DIR__.'/../skeleton';
    }
}
