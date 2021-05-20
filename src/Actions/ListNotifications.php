<?php


namespace Nikservik\AdminNotifications\Actions;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsController;
use Lorisleiva\Actions\Concerns\AsObject;
use Nikservik\AdminDashboard\Middleware\AdminMiddleware;
use Nikservik\SimpleSupport\Models\SupportMessage;

class ListNotifications
{
    use AsObject;
    use AsController;

    public static function route(): void
    {
        Route::get(
            '/' . Config::get('admin-notifications.route'),
            static::class
        )
            ->middleware(['web', 'auth', AdminMiddleware::class]);
    }

    public function handle()
    {
        return SupportMessage::where('type', 'notification')
            ->withCount('readMarks as read')
            ->latest()
            ->paginate(Config::get('admin-notifications.messages-per-page'));
    }

    public function asController(ActionRequest $request)
    {
        $notifications = $this->handle();

    	return view('admin-notifications::index', [
            'notifications' => $notifications,
            'list' => 'all',
            'stats' => ['all' => $notifications->total()],
        ]);
    }
}
