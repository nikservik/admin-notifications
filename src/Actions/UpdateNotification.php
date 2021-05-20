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

class UpdateNotification
{
    use AsObject;
    use AsController;

    public static function route(): void
    {
        Route::patch(
            '/' . Config::get('admin-notifications.route') . '/{notification}',
            static::class
        )
            ->middleware(['web', 'auth', AdminMiddleware::class]);
    }

    public function handle(SupportMessage $notification, string $updated)
    {
        $notification->update(['message' => $updated]);
    }

    public function asController(SupportMessage $notification, ActionRequest $request)
    {
        $this->handle($notification, $request->get('message'));

        return redirect('/' . Config::get('admin-notifications.route'));
    }

    public function rules(): array
    {
        return [
            'message' => ['required', 'min:2'],
        ];
    }

    public function getValidationMessages(): array
    {
        return [
            'message.required' => 'message_required',
            'message.min' => 'message_min',
        ];
    }

    public function afterValidator(Validator $validator, ActionRequest $request): void
    {
        $notification = $request->route()->parameter('notification');

        if ($notification->type !== 'notification') {
            $validator->errors()->add('notification', 'only_notifications_allowed');
        }
    }

}
