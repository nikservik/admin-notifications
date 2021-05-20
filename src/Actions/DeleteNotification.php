<?php


namespace Nikservik\AdminNotifications\Actions;


use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Validator;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsController;
use Lorisleiva\Actions\Concerns\AsObject;
use Nikservik\AdminDashboard\Middleware\AdminMiddleware;
use Nikservik\SimpleSupport\Models\SupportMessage;

class DeleteNotification
{
    use AsObject;
    use AsController;

    public static function route(): void
    {
        Route::delete(
            '/' . Config::get('admin-notifications.route') . '/{notification}',
            static::class
        )
            ->middleware(['web', 'auth', AdminMiddleware::class]);
    }

    public function handle(SupportMessage $notification)
    {
        SupportMessage::where('type', 'notificationRead')->where('message', $notification->id)->delete();

        $notification->delete();
    }

    public function asController(SupportMessage $notification)
    {
        $this->handle($notification);

        return redirect()->back();
    }

    public function afterValidator(Validator $validator, ActionRequest $request): void
    {
        $notification = $request->route()->parameter('notification');

        if ($notification->type !== 'notification') {
            $validator->errors()->add('notification', 'only_notifications_allowed');
        }
    }
}
