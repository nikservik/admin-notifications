<?php


namespace Nikservik\AdminNotifications\Actions;


use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsController;
use Lorisleiva\Actions\Concerns\AsObject;
use Nikservik\AdminDashboard\Middleware\AdminMiddleware;
use Nikservik\SimpleSupport\Models\SupportMessage;

class StoreNotification
{
    use AsObject;
    use AsController;

    public static function route(): void
    {
        Route::post(
            '/' . Config::get('admin-notifications.route'),
            static::class
        )
            ->middleware(['web', 'auth', AdminMiddleware::class]);
    }

    public function handle(string $message)
    {
        return SupportMessage::create([
                'message' => $message,
                'type' => 'notification',
            ]);
    }

    public function asController(ActionRequest $request)
    {
        $this->handle($request->get('message'));

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
}
