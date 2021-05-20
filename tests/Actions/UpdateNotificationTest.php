<?php

namespace Nikservik\AdminNotifications\Tests\Actions;

use App\Models\User;
use Nikservik\AdminNotifications\Actions\UpdateNotification;
use Nikservik\AdminNotifications\Tests\TestCase;
use Nikservik\SimpleSupport\Models\SupportMessage;

class UpdateNotificationTest extends TestCase
{
    public function testHandle()
    {
        $notification = SupportMessage::factory()->notification()->create();

        UpdateNotification::run($notification, 'new text');

        $this->assertEquals('new text', $notification->message);
    }

    public function test_update_message()
    {
        $notification = SupportMessage::factory()->notification()->create();

        $this->actingAs($this->admin)
            ->patch("/notifications/{$notification->id}", [
                'message' => 'new text',
            ])
            ->assertStatus(302);

        $this->assertEquals('new text', $notification->refresh()->message);
    }

    public function test_dont_update_empty_message()
    {
        $notification = SupportMessage::factory()->notification()->create();

        $this->actingAs($this->admin)
            ->patch("/notifications/{$notification->id}", [
                'message' => '',
            ])
            ->assertStatus(302)
            ->assertSessionHasErrors('message');

        $this->assertNotEquals('', $notification->refresh()->message);
    }

    public function test_dont_update_user_message()
    {
        $user = User::factory()->create();
        $notification = SupportMessage::factory()->for($user)->fromUser()->create();

        $this->actingAs($this->admin)
            ->patch("/notifications/{$notification->id}", [
                'message' => 'new text',
            ])
            ->assertStatus(302)
            ->assertSessionHasErrors('notification');

        $this->assertNotEquals('new text', $notification->refresh()->message);
    }

    public function test_dont_update_support_message()
    {
        $user = User::factory()->create();
        $notification = SupportMessage::factory()->for($user)->fromSupport()->create();

        $this->actingAs($this->admin)
            ->patch("/notifications/{$notification->id}", [
                'message' => 'new text',
            ])
            ->assertStatus(302)
            ->assertSessionHasErrors('notification');

        $this->assertNotEquals('new text', $notification->refresh()->message);
    }

    public function test_redirect_when_not_authenticated()
    {
        $notification = SupportMessage::factory()->notification()->create();

        $this->patch("/notifications/{$notification->id}")
            ->assertRedirect('/login');
    }

    public function test_redirect_when_not_admin()
    {
        $notification = SupportMessage::factory()->notification()->create();

        $this->actingAs($this->user)
            ->patch("/notifications/{$notification->id}")
            ->assertRedirect('/login');
    }

}
