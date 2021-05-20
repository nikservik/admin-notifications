<?php

namespace Nikservik\AdminNotifications\Tests\Actions;

use App\Models\User;
use Nikservik\AdminNotifications\Actions\DeleteNotification;
use Nikservik\AdminNotifications\Tests\TestCase;
use Nikservik\SimpleSupport\Models\SupportMessage;

class DeleteNotificationTest extends TestCase
{
    public function testHandle()
    {
        $notification = SupportMessage::factory()->notification()->create();

        DeleteNotification::run($notification);

        $this->assertEquals(0, SupportMessage::where('type', 'notification')->count());
    }

    public function test_delete_notification()
    {
        $notification = SupportMessage::factory()->notification()->create();

        $this->actingAs($this->admin)
            ->get("/notifications/{$notification->id}/delete")
            ->assertStatus(302);

        $this->assertEquals(0, SupportMessage::where('type', 'notification')->count());
    }

    public function test_delete_notification_read_marks()
    {
        $user = User::factory()->create();
        $notification = SupportMessage::factory()->notification()->create();
        SupportMessage::factory()->for($user)->notificationRead($notification)->create();

        $this->actingAs($this->admin)
            ->get("/notifications/{$notification->id}/delete")
            ->assertStatus(302);

        $this->assertEquals(0, SupportMessage::where('type', 'notificationRead')->count());
    }

    public function test_redirect_when_notification_not_exists()
    {
        $this->actingAs($this->admin)
            ->get('/notifications/111/delete')
            ->assertStatus(404);
    }

    public function test_redirect_when_not_authenticated()
    {
        $user = User::factory()->create();
        $notification = SupportMessage::factory()->notification()->create();

        $this->get("/notifications/{$notification->id}/delete")
            ->assertRedirect('/login');
    }

    public function test_redirect_when_not_admin()
    {
        $user = User::factory()->create();
        $notification = SupportMessage::factory()->notification()->create();

        $this->actingAs($this->user)
            ->get("/notifications/{$notification->id}/delete")
            ->assertRedirect('/login');
    }

    public function test_dont_delete_user_notification()
    {
        $user = User::factory()->create();
        $notification = SupportMessage::factory()->for($user)->fromUser()->create();

        $this->actingAs($this->admin)
            ->get("/notifications/{$notification->id}/delete")
            ->assertStatus(302)
            ->assertSessionHasErrors('notification');

        $this->assertEquals(1, SupportMessage::where('type', 'userMessage')->count());
    }

    public function test_dont_delete_support_notification()
    {
        $user = User::factory()->create();
        $notification = SupportMessage::factory()->for($user)->fromSupport()->create();

        $this->actingAs($this->admin)
            ->get("/notifications/{$notification->id}/delete")
            ->assertStatus(302)
            ->assertSessionHasErrors('notification');

        $this->assertEquals(1, SupportMessage::where('type', 'supportMessage')->count());
    }

}
