<?php


namespace Nikservik\AdminNotifications\Actions;


use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsController;
use Lorisleiva\Actions\Concerns\AsObject;
use Nikservik\AdminDashboard\Middleware\AdminMiddleware;
use Nikservik\SimpleSupport\Models\SupportMessage;

class SearchNotifications
{
    use AsObject;
    use AsController;

    public static function route(): void
    {
        Route::get(
            '/' . Config::get('admin-notifications.route') . '/search',
            static::class
        )
            ->middleware(['web', 'auth', AdminMiddleware::class]);
    }

    public function handle(string $query)
    {
        return SupportMessage::where('type', 'notification')
            ->where('message', 'LIKE', "%$query%")
            ->withCount('readMarks')
            ->latest()
            ->paginate(Config::get('admin-notifications.messages-per-page'));
    }

    public function asController(ActionRequest $request)
    {
        $query = $request->get('q');

        $notifications = $this->handle($query);

    	return view('admin-notifications::index', [
            'notifications' => $notifications,
            'list' => 'search',
            'stats' => [
                'all' => SupportMessage::where('type', 'notification')->count(),
                'search' => $notifications->total()
            ],
        ]);
    }

}
