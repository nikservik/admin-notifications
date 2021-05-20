<?php


namespace Nikservik\AdminNotifications\Actions;


use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Validator;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsController;
use Nikservik\AdminDashboard\Middleware\AdminMiddleware;
use Nikservik\SimpleSupport\Models\SupportMessage;

class EditNotification
{
    use AsController;

    public static function route(): void
    {
        Route::get(
            '/' . Config::get('admin-notifications.route') . '/{notification}',
            static::class
        )
            ->middleware(['web', 'auth', AdminMiddleware::class]);
    }

    public function asController(SupportMessage $notification)
    {
        return view('admin-notifications::edit', [
            'notification' => $notification,
        ]);
    }

    public function afterValidator(Validator $validator, ActionRequest $request): void
    {
        $notification = $request->route()->parameter('notification');

        if ($notification->type !== 'notification') {
            $validator->errors()->add('notification', 'only_notifications_allowed');
        }
    }

}
