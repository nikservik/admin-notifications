<?php


namespace Nikservik\AdminNotifications\Actions;


use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use Lorisleiva\Actions\Concerns\AsController;
use Nikservik\AdminDashboard\Middleware\AdminMiddleware;

class CreateNotification
{
    use AsController;

    public static function route(): void
    {
        Route::get(
            '/' . Config::get('admin-notifications.route') . '/create',
            static::class
        )
            ->middleware(['web', 'auth', AdminMiddleware::class]);
    }

    public function asController()
    {
        return view('admin-notifications::create');
    }
}
