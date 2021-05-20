<?php

namespace Nikservik\AdminNotifications;

use Illuminate\Support\ServiceProvider;

class AdminNotificationsServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/admin-notifications.php', 'admin-notifications');
    }

    public function boot()
    {
        $this->loadRoutes();
        $this->loadViews();
        $this->loadTranslations();

        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->publishes([
            __DIR__.'/../config/admin-notifications.php' => config_path('admin-notifications.php'),
        ], 'admin-notifications-config');
    }

    protected function loadRoutes(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../routes.php');
    }

    protected function loadViews(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'admin-notifications');
    }

    protected function loadTranslations(): void
    {
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'admin-notifications');
    }
}
