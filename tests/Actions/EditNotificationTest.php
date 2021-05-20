<?php

namespace Nikservik\AdminNotifications\Tests\Actions;

use App\Models\User;
use Nikservik\AdminNotifications\Actions\EditNotification;
use Nikservik\AdminNotifications\Tests\TestCase;
use Nikservik\SimpleSupport\Models\SupportMessage;

class EditNotificationTest extends TestCase
{

    public function test_edit_message()
    {
        $notification = SupportMessage::factory()->notification()->create();

        $this->actingAs($this->admin)
            ->get("/notifications/{$notification->id}")
            ->assertOk()
            ->assertSee($notification->message);
    }

    public function test_dont_edit_user_message()
    {
        $user = User::factory()->create();
        $notification = SupportMessage::factory()->for($user)->fromUser()->create();

        $this->actingAs($this->admin)
            ->get("/notifications/{$notification->id}")
            ->assertStatus(302)
            ->assertSessionHasErrors('notification');
    }

    public function test_dont_edit_support_message()
    {
        $user = User::factory()->create();
        $notification = SupportMessage::factory()->for($user)->fromSupport()->create();

        $this->actingAs($this->admin)
            ->get("/notifications/{$notification->id}")
            ->assertStatus(302)
            ->assertSessionHasErrors('notification');
    }

    public function test_redirect_when_not_authenticated()
    {
        $notification = SupportMessage::factory()->notification()->create();

        $this->get("/notifications/{$notification->id}")
            ->assertRedirect('/login');
    }

    public function test_redirect_when_not_admin()
    {
        $notification = SupportMessage::factory()->notification()->create();

        $this->actingAs($this->user)
            ->get("/notifications/{$notification->id}")
            ->assertRedirect('/login');
    }

}
